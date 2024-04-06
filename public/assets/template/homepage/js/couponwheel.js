/*
	Plugin Name: Coupon Wheel For WooCommerce and WordPress
	Description: One nice gamified exit-intent popup plugin :-)
	Author: Jure Hajdinjak / Copyright (c) 2017-2020 Jure Hajdinjak
*/

if ( typeof window.couponwheel_AnimFrame == 'undefined' ) {
	window.couponwheel_AnimFrame = (function(){
		return window.requestAnimationFrame		||
		window.webkitRequestAnimationFrame		||
		window.mozRequestAnimationFrame			||
		function(){};
	})();
}

function couponwheel(init_params)
{
	//init vars
	this.wheel_hash = init_params.wheel_hash;
	this.wheel_dom = init_params.wheel_dom;
	this.timed_trigger = init_params.timed_trigger;
	this.exit_trigger = init_params.exit_trigger;
	this.show_popup_after = init_params.show_popup_after;
	this.preview_key = init_params.preview_key;
	this.recaptcha_sitekey = init_params.recaptcha_sitekey;
	this.require_recaptcha = init_params.require_recaptcha;
	this.prevent_triggers_on_mobile = init_params.prevent_triggers_on_mobile;
	this.kiosk_mode = init_params.kiosk_mode;
	this.confirm_close_text = init_params.confirm_close_text;
	this.custom_on_show_popup = init_params.custom_on_show_popup;

	this.on_win_url = '';
	this.on_win_url_target_blank = false;
	this.recaptcha_rendered = false;
	this.exit_triggered = false;
	this.can_show = true;
	this.can_close = true;
	this.reload_page = false;
	this.window_scroll_value = 0;
	this.stage = 1;
	this.wheel_slice_number = 0;
	
	//functions
	this.render_recaptcha = function()
	{
		grecaptcha.render('couponwheel'+this.wheel_hash+'_recaptcha', {'sitekey' : this.recaptcha_sitekey});
		this.recaptcha_rendered = true;
	};
	
	this.ios_input_workaround_enable = function()
	{
		// iOS fixed input render bug workaround
		if (this.is_ios() === false) return;
		this.window_scroll_value = jQuery(window).scrollTop();
		jQuery(window).scrollTop(0);
		jQuery('.couponwheel_popup').css({'position':'absolute', top:0});
		jQuery('body').addClass('couponwheel_ios_stop_scrolling');
		// 
	};
	
	this.ios_input_workaround_disable = function()
	{
		if (this.is_ios() === false) return;
		jQuery('body').removeClass('couponwheel_ios_stop_scrolling');
		jQuery(window).scrollTop(this.window_scroll_value);
	};
	
	this.is_ios = function()
	{
		if (/iPad|iPhone|iPod/.test(navigator.userAgent)) return true;
		return false;
	};
	
	this.is_embed = function()
	{
		var i = jQuery(this.wheel_dom).parent()[0].className.indexOf('couponwheel_embed');
		if (i !== -1) return true;
		return false;
	};

	this.show_popup = function(exit_trigger_check)
	{

		if ( typeof exit_trigger_check == 'undefined')
		{
			exit_trigger_check = true;
		}
		
		if (this.can_show === false) return;
		if (exit_trigger_check && this.exit_triggered) return;
		this.exit_triggered = true;
		this.can_show = false;
		if (this.require_recaptcha && this.recaptcha_rendered === false) this.render_recaptcha();
		if (!this.is_embed()) this.ios_input_workaround_enable();
		jQuery(this.wheel_dom).css('pointer-events','auto');
		jQuery(this.wheel_dom+' .couponwheel_popup').show();
		if (!this.is_embed()) {
			jQuery(this.wheel_dom+' .couponwheel_popup_shadow').show();
			jQuery(this.wheel_dom+' .couponwheel_popup').css({'left':'-100%'});
			jQuery(this.wheel_dom+' .couponwheel_popup').animate({left: '0px'},500,'easeOutExpo');
		}
		jQuery.ajax({
			url: couponwheel_ajaxurl,
			type: 'POST',
			data: {
				action: 'couponwheel_event',
				code: 'show_popup',
				wheel_hash: this.wheel_hash,
				preview_key: this.preview_key
				}
		});

		this.custom_on_show_popup();

	};
	this.close_popup = function()
	{
		if(this.can_close === false) return;
		this.can_close = false;
		jQuery(this.wheel_dom).css('pointer-events','none');
		jQuery(this.wheel_dom+' .couponwheel_popup').css({'left':'0%'});
		jQuery(this.wheel_dom+' .couponwheel_popup').animate({left: '-100%'},600,'easeInExpo',function(){
			jQuery(this.wheel_dom+' .couponwheel_popup_shadow').hide();
			jQuery(this.wheel_dom+' .couponwheel_popup').hide();
			this.can_close = true;
			this.can_show = true;
			
			if (this.kiosk_mode)
			{
				this.reset_popup();
				return;
			}
			
			if (this.reload_page) location.reload();
			this.ios_input_workaround_disable();
			window.couponwheel_notice.reload();
		}.bind(this));
	};
	
	this.reset_popup = function()
	{
		var is_embed = this.is_embed();
		jQuery(this.wheel_dom).remove();
		window['couponwheel'+this.wheel_hash] = undefined;
		window.couponwheel_manual_trigger(this.wheel_hash,is_embed);
	};
	
	this.hide_popup = function()
	{
		jQuery(this.wheel_dom+' .couponwheel_popup_shadow').hide();
		jQuery(this.wheel_dom+' .couponwheel_popup').hide();
	};
	
	this.go_to_stage2 = function()
	{
		jQuery(this.wheel_dom+' .couponwheel_form_stage1').hide();
		jQuery(this.wheel_dom+' .couponwheel_form_stage2').removeClass('couponwheel_hidden');
		jQuery(this.wheel_dom+' .couponwheel_form_stage2').addClass('couponwheel_effects_animated couponwheel_effects_bounceIn');
		
		setTimeout(function(){
			jQuery(this.wheel_dom+' .couponwheel_popup').animate({'scrollTop':   jQuery(this.wheel_dom+' .couponwheel_form_stage2').offset().top}, 650,'swing');
		}.bind(this),1000);
		
		this.can_close = true;
		this.stage = 2;
	};
	
	this.submit_form_done = function(response)
	{
		jQuery(this.wheel_dom+' .couponwheel_ajax_loader').hide();
		
		if (response.hide_popup === true)
		{
			this.hide_popup();
			return;
		}
		
		if (response.error_code == 'form_error')
		{
			this.can_close = true;
			jQuery(this.wheel_dom+' .couponwheel_popup_form_error_text').html(response.error_msg);
			jQuery(this.wheel_dom+' .couponwheel_form_stage1 *').attr('disabled',false);
			return;
		}
		
		if (response.success)
		{
			jQuery(this.wheel_dom+' .couponwheel_popup').animate({'scrollTop':   jQuery(this.wheel_dom+' .couponwheel_wheel_crop').offset().top}, 650,'swing');
			jQuery(this.wheel_dom+' .couponwheel_form_stage2 .couponwheel_popup_heading_text').html(response.stage2_heading_text);
			jQuery(this.wheel_dom+' .couponwheel_form_stage2 .couponwheel_popup_main_text').html(response.stage2_main_text);
			this.start_wheel_animation(response.wheel_deg_end,response.wheel_time_end);
			this.wheel_slice_number = response.wheel_slice_number;
			this.reload_page = response.reload_page;
			if (response.notice !== false) localStorage.setItem('couponwheel_notice',response.notice);
			if (typeof response.on_win_url != 'undefined') this.on_win_url = response.on_win_url;
			if (typeof response.on_win_url_target_blank != 'undefined') this.on_win_url_target_blank = response.on_win_url_target_blank;
			return;
		}
	
		this.can_close = true;
	};
	
	this.submit_form = function(form_data)
	{
		jQuery(this.wheel_dom+' .couponwheel_ajax_loader').show();
		jQuery(this.wheel_dom+' .couponwheel_form_stage1 *').attr('disabled',true);
		jQuery(this.wheel_dom+' .couponwheel_popup_form_error_text').html('');
		jQuery.ajax({
			url: couponwheel_ajaxurl,
			type: 'POST',
			data: {
				action: 'couponwheel_wheel_run',
				form_data: form_data,
				preview_key: this.preview_key
				},
			context: this,
		}).done(function(json){
			this.submit_form_done(jQuery.parseJSON(json));
		});
	};
	
	this.start_wheel_animation = function(wheel_deg_end,wheel_time_end)
	{
		this.wheel_deg_end = wheel_deg_end;
		this.wheel_time_end = wheel_time_end;

		this.wheel_time = 0;
		this.wheel_deg = 0;
		
		var parent = this;
		this.animation_start_time = null;
		couponwheel_AnimFrame(parent.animate.bind(parent));
	};
	
	//animations
	this.wheel_time = 0;
	this.wheel_deg = 0;
	this.wheel_deg_end = 0;
	this.wheel_time_end = 0;
	this.wheel_deg_ease = 0;
	this.animation_start_time = null;
	
	this.wheel_ease = function(x)
	{
		return 1 - Math.pow( 1 - x, 5 );
	};
	
	this.marker_ease = function(x)
	{
		var n = (- Math.pow((1-(x*2)),2)+1);
		if (n < 0) n = 0;
		return n;
	};
	this.animate = function(timestamp)
	{
		if (!this.animation_start_time) this.animation_start_time = timestamp;
		this.wheel_time = timestamp - this.animation_start_time;
		if(this.wheel_time > this.wheel_time_end) this.wheel_time = this.wheel_time_end;
		this.wheel_deg_ease = this.wheel_ease( (( this.wheel_deg_end / this.wheel_time_end ) * this.wheel_time) / this.wheel_deg_end );
		this.wheel_deg = this.wheel_deg_ease * this.wheel_deg_end;
		
		if(this.wheel_deg_ease > 0.99){
			jQuery(this.wheel_dom+' .couponwheel_marker').css({'transform' : 'translateY(-50%) rotate3d(0,0,1,0deg)','-webkit-transform' : 'translateY(-50%) rotate3d(0,0,1,0deg)'});
		}
		
		var ticker_calc = this.wheel_deg - Math.floor(this.wheel_deg/360)*360;
		var i;
		
		for (i = 1; i <= 12; i++) {
			if ((ticker_calc >= (i*30)-18) && (ticker_calc <= (i*30)))
			{
				var aa = 0.2;
				if(this.wheel_deg_ease > aa) aa=this.wheel_deg_ease;
	
					var bb = this.marker_ease(-(((i*30)-18)-ticker_calc)/10) * (30*aa);
	
				jQuery(this.wheel_dom+' .couponwheel_marker').css({'transform' : 'translateY(-50%)  rotate3d(0,0,1,'+ (0-bb) + 'deg)','-webkit-transform' : 'translateY(-50%)  rotate3d(0,0,1,'+ (0-bb) + 'deg)'});
			}
		}

		jQuery(this.wheel_dom+' .couponwheel_wheel').css({'transform' : 'rotate3d(0,0,1,'+ this.wheel_deg +'deg)','-webkit-transform' : 'rotate3d(0,0,1,'+ this.wheel_deg +'deg)'});

		var deg_remaining = this.wheel_deg_end - this.wheel_deg;
		
		// release from animation early to prevent delay
		if ( 1 > deg_remaining && this.stage == 1 ) this.go_to_stage2();
		if ( timestamp - this.animation_start_time > this.wheel_time_end ) return;
		
		this.last_wheel_deg = this.wheel_deg;

		couponwheel_AnimFrame(this.animate.bind(this));
	};

	//main init
	jQuery(this.wheel_dom+' .couponwheel_stage1_submit_btn').attr('disabled',false);

	jQuery(this.wheel_dom+' .couponwheel_stage2_continue_btn').click(function()
	{
		
		if (this.on_win_url.length > 0)
			{
				if (this.on_win_url_target_blank === true) {
					window.open(this.on_win_url);
				} else {
					window.location = this.on_win_url;
					return;
				}
			}
			
		this.close_popup();
		
	}.bind(this));
	
	jQuery(this.wheel_dom+' .couponwheel_spin_again_btn').click(function(){this.kiosk_mode = true; this.close_popup(); }.bind(this));
	jQuery(this.wheel_dom+' .couponwheel_popup_close_btn').click(function(){this.close_popup(); }.bind(this));
	jQuery(this.wheel_dom+' .couponwheel_popup_shadow').click(function(){
		if (this.confirm_close_text.length === 0) {
			this.close_popup();
			return;
		}
		if ( confirm(this.confirm_close_text+'?') == true ) { this.close_popup(); }
	}.bind(this));

	jQuery(this.wheel_dom+' .couponwheel_form_stage1').on('submit',function(event){
		event.preventDefault();
		this.can_close = false;
		this.submit_form(jQuery(this.wheel_dom+' .couponwheel_form_stage1').serialize());
	}.bind(this));
	
	var isMobile = /iPhone|iPad|iPod|Android/i.test(navigator.userAgent);

	if (typeof this.prevent_triggers_on_mobile == 'undefined') this.prevent_triggers_on_mobile = false;
	
	if (((isMobile) && (this.prevent_triggers_on_mobile)) === false)
	{
		if (this.timed_trigger)
		{
			new couponwheel_DialogTrigger(function(){this.show_popup();}.bind(this), { trigger: 'timeout', timeout: this.show_popup_after*1000 });
		}
		
		if (this.exit_trigger)
		{
			new couponwheel_DialogTrigger(function(){this.show_popup();}.bind(this), { trigger: 'exitIntent' });
			new couponwheel_DialogTrigger(function(){this.show_popup();}.bind(this), { trigger: 'scrollUp', percent: 10, scrollInterval: 150 });
		}
	}

}

function couponwheel_manual_trigger(wheel_hash,embed)
{
	
	if (typeof embed == 'undefined') embed = false;
	
	if ( jQuery('#couponwheel'+wheel_hash).length == 0 )
	{
		jQuery.ajax({
			url: couponwheel_ajaxurl,
			type: 'POST',
			data: {
				action: 'couponwheel_load_popups',
				wheel_hash: wheel_hash
				},
			context: this,
		}).done(function(html){
			
			if (embed)
			{
				couponwheel_append_wheel_to_document(html,'.couponwheel_embed_'+wheel_hash);
			} else {
				couponwheel_append_wheel_to_document(html);
			}
			
			if (typeof window['couponwheel'+wheel_hash] != 'undefined')
			{
				window['couponwheel'+wheel_hash].show_popup(0);
			} else {
				console.log('Coupon Wheel with hash '+wheel_hash+' does not exist or is not LIVE');
			}
		});
	} else {
		window['couponwheel'+wheel_hash].show_popup(0);
	}
}

function couponwheel_append_wheel_to_document(html,target)
{
	if (typeof target == 'undefined') target = document.body;
	jQuery.each(jQuery(html),function(i,v) {
		var wheel_hash = jQuery(v).data('item');
		if ( jQuery('#couponwheel'+wheel_hash).length == 0 ) {
			jQuery(target).append(v.innerHTML);
		}
	});
}

window.addEventListener('load',function(){

	// CSS trigger
	jQuery('[class*="couponwheel_css_trigger_"]').click(function(e){
		e.preventDefault();
		e.currentTarget.classList.forEach(v => {
			if ( v.indexOf('couponwheel_css_trigger_') === 0 ) couponwheel_manual_trigger( v.split('_')[3] );
		});
	});
	//	

	if (typeof window.couponwheel_embed != 'undefined') return;

	jQuery.ajax({
		url: couponwheel_ajaxurl,
		type: 'POST',
		data: {
			action: 'couponwheel_load_popups',
			page_id: couponwheel_page_id,
			post_is_single: couponwheel_post_is_single,
			locale: couponwheel_locale,
			order_received: couponwheel_order_received
			},
		context: this,
	}).done(function(html){
		couponwheel_append_wheel_to_document(html);
	});
	
});


// Coupon Wheel notice class

couponwheel_notice = function()
{
	this.interval = 0;
	
	this.reload = function()
	{
		var ls = localStorage.getItem('couponwheel_notice');

		if ((ls !== null) && (ls.length !== 0))
		{
			jQuery('#couponwheel_notice').empty();
			
			try {
				jQuery('#couponwheel_notice').append(ls);
			} catch(e) {
				window.couponwheel_notice.clear();
			}
			
			return;
		}
	};
	
	this.clear = function()
	{
		jQuery('#couponwheel_notice_content').hide();
		clearInterval(window.couponwheel_notice.interval);
		localStorage.removeItem('couponwheel_notice');
	};
	
	this.start = function(notice, template_vars, expire_timestamp, lang_days)
	{
		
		if (typeof expire_timestamp == 'undefined') expire_timestamp = 0;
		if (typeof lang_days == 'undefined') lang_days = 'days';
		
		function seconds_to_minsec(ds) {
			var d = Math.floor(ds / 86400);
			var h = Math.floor(ds % 86400 / 3600);
			var m = Math.floor(ds % 3600 / 60);
			var s = Math.floor(ds % 3600 % 60);
			
			if (d > 0) return Math.floor(d) + ' ' + lang_days;
			if (h > 0) return Math.floor(h) + couponwheel_notice_translations.h + ' ' + Math.floor(m) + couponwheel_notice_translations.m + ' ' + Math.floor(s) + couponwheel_notice_translations.s;
			return Math.floor(m) + couponwheel_notice_translations.m + ' ' + Math.floor(s) + couponwheel_notice_translations.s;
		}
		
		function refresh()
		{
			var secleft = expire_timestamp - Math.floor(Date.now() / 1000);

			jQuery('#couponwheel_notice_timer').html(seconds_to_minsec(secleft));
			
			if (secleft < 0)
			{
				window.couponwheel_notice.clear();
			}
		}
		
		var parsed_notice = notice;
		parsed_notice = parsed_notice.replace('{couponcode}','<strong>'+template_vars.couponcode_raw+'</strong>');

		jQuery.each( template_vars, function(key,value) {
			parsed_notice = parsed_notice.replace('{'+key+'}',value);
		});
		
		parsed_notice = parsed_notice.replace('{timer}','<strong id="couponwheel_notice_timer">{timer}</strong>');
		jQuery('#couponwheel_notice_content > span').html(parsed_notice);
		
		refresh();
		
		clearInterval(window.couponwheel_notice.interval);
		window.couponwheel_notice.interval = setInterval(function(){
			refresh();
		},1000);
	};
};

window.couponwheel_notice = new couponwheel_notice();