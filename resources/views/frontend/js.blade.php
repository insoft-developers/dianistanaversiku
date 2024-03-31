
<script>

function formatAngka(angka, prefix){
    var number_string = angka.toString().replace(/[^,\d]/g, '').toString(),
    split   		= number_string.split(','),
    sisa     		= split[0].length % 3,
    rupiah     		= split[0].substr(0, sisa),
    ribuan     		= split[0].substr(sisa).match(/\d{3}/gi);

    // tambahkan titik jika yang di input sudah menjadi angka ribuan
    if(ribuan){
        separator = sisa ? '.' : '';
        rupiah += separator + ribuan.join('.');
    }

    rupiah = split[1] != undefined ? rupiah + ',' + split[1] : rupiah;
    return prefix == undefined ? rupiah : (rupiah ? 'Rp. ' + rupiah : '');
}

function tutup_alert() {
	$(".alert").hide();
}

function show_error(title, message) {
    $("#warning-box").slideDown();
    $(".alert-title").text(title);
    $(".alert-content").html(message);
    $("body").addClass("modal-open");
}



function close_warning_box() {
    $("#warning-box").slideUp();
    $("body").removeClass("modal-open");
}

</script>

@if($view == 'register')
<script>
	

	$('#checkbox1').change(function() {
        if(this.checked) {
            $("#button_register").removeAttr("disabled");
        } else{
            $("#button_register").attr("disabled", true);
        }
                
    });
	
</script>
@endif

@if($view == 'booking-detail')
<script>
    
    let selected_hour_start = "";
    let selected_hour_finish = "";
    let selected_booking_date = "";
    let selected_price_start = "";
    let selected_price_finish = "";

    $(document).ready(function(){
        var bulan = $("#current_month").val();
        var tahun = $("#current_year").val();
        display_calender(bulan, tahun);
        $("#bulan").val(bulan);
        $("#tahun").val(tahun);
        
    });


    $("#tahun").change(function(){
        var tahun = $(this).val();
        var bulan = $("#bulan").val();
        display_calender(bulan, tahun);
    });

    $("#bulan").change(function(){
        var bulan = $(this).val();
        var tahun = $("#tahun").val();
        display_calender(bulan, tahun);
    })

    function display_calender(bulan, tahun) {
        $.ajax({
            url: "{{ url('display_calendar') }}"+"/"+bulan+"/"+tahun,
            type: "GET",
            success: function(data) {
                console.log(data);
                $("#calendar-sitting").html(data);
            }
        })
    }


    var selected_date_index = 0;

    function select_date(n) {
        $("#list_"+n).addClass("active");
        $("#list_"+selected_date_index).removeClass("active");
        selected_date_index = n;
        display_hour_set(n)
        selected_booking_date = n;
        var tanggal = n.toString();
        var panjang = tanggal.length;

        var bulan = $("#bulan").val();
        var tahun = $("#tahun").val();
        var used_date = "";
        if(panjang == 1) {
            used_date = "0"+n.toString();
        } else {
            used_date = n.toString();
        }
        var booking_date = used_date+'-'+bulan+'-'+tahun;
        var input_date = tahun+'-'+bulan+'-'+used_date;
        $("#booking-date").text(booking_date);
        $("#booking-date-input").val(input_date);
    }

    function display_hour_set(n) {
        var product_id = $("#product_id").val();
        var tahun = $("#tahun").val();
        var bulan = $("#bulan").val();
        var csrf_token = $('meta[name="csrf-token"]').attr('content');
        $.ajax({
            url: "{{ url('booking_time') }}",
            type: "POST",
            data: {"n":n, "bulan":bulan, "tahun":tahun, "product_id":product_id, "_token":csrf_token },
            success:function(data) {
                $("#time-sitting").html(data);
            }
        })
    }

    var selected_hour_index = 0;
    function select_hour(id) {
        if($("#hour_"+id).hasClass("active")) {

        } else {
            $("#hour_"+id).addClass("active");
            $("#hour_"+selected_hour_index).removeClass("active");
            selected_hour_index = id;
            selected_hour_start = $("#hour_start_"+id).val();

            $("#start-time").text('PK '+selected_hour_start+':00 WIB');
            $("#start-time-input").val(selected_hour_start);
            selected_price_start = $("#price_start_"+id).val();
        }

        
        
    }

    var selected_finish_hour_index = 0;
    function select_finish_hour(id) {
        if($("#finish_"+id).hasClass("actived")) {

        } else {
            $("#finish_"+id).addClass("actived");
            $("#finish_"+selected_finish_hour_index).removeClass("actived");
        
            selected_hour_finish = $("#hour_finish_"+id).val();
            
            $("#finish-time").text('PK '+selected_hour_finish+':00 WIB');
            $("#finish-time-input").val(selected_hour_finish);
            selected_price_finish = $("#price_finish_"+id).val();


            if(selected_hour_start >= selected_hour_finish) {
                show_error("Error", "Hours selected not valid");
                selected_hour_finish = "";
                $("#finish_"+id).removeClass("actived");
                $("#finish-time").text('');
                $("#finish-time-input").val('');
                $("#quantity").text("");
                $("#quantity-input").val("");
                $("#price").text("");
                $("#price-input").val("");

            } else {
                var selisih = selected_hour_finish - selected_hour_start;
                if(selisih > 2) {
                    show_error("error", "we allow 2 hours booking duration only");
                    selected_hour_finish = "";
                    $("#finish_"+id).removeClass("actived");
                    $("#finish-time").text('');
                    $("#finish-time-input").val('');
                    $("#quantity").text("");
                    $("#quantity-input").val("");
                    $("#price").text("");
                    $("#price-input").val("");
                } else {
                    $("#quantity").text(selisih.toString()+" hours");
                    $("#quantity-input").val(selisih);
                    if(selisih == 2) {
                        var totalprice = +selected_price_start + +selected_price_finish;
                        $("#price").text("Rp. "+formatAngka(totalprice));
                        $("#price-input").val(totalprice);
                    } else {
                        $("#price").text("Rp. "+formatAngka(selected_price_start));
                        $("#price-input").val(selected_price_start);
                    }
                }
            }
            selected_finish_hour_index = id;
        }
        
        
    }

    function send_order() {
        show_success("Warning", "Do you want to process this transaction...?");
    }

    function close_success_box() {
        $("#success-box").slideUp();
        $("body").removeClass("modal-open");
    }

    function show_success(title, message) {
        $("#success-box").slideDown();
        $(".alert-title").text(title);
        $(".alert-content").text(message);
        $("body").addClass("modal-open");
    }

    function confirm_success_box() {
        var csrf_token = $('meta[name="csrf-token"]').attr('content');
        close_success_box();
        $("#btn_send_transaction").text("Processing....");
        var productId = $("#product_id").val();
        var invoice = $("#invoice").val();
        var startTime = $("#start-time-input").val();
        var finishTime = $("#finish-time-input").val();
        var quantity = $("#quantity-input").val();
        var totalPrice = $("#price-input").val();
        var bookingDate = $("#booking-date-input").val();
        $.ajax({
            url: "{{ url('transaction') }}",
            type: "POST",
            dataType: "JSON",
            data: {"business_unit_id":productId, "invoice":invoice, "start_time":startTime, "finish_time":finishTime, "quantity":quantity, "total_price":totalPrice, "booking_date":bookingDate, "_token":csrf_token},
            success: function(data) {
                console.log(data);
                $("#btn_send_transaction").text("Submit");
                if(data.success) {
                    alert(data.message);
                } else {
                    show_error("Error", data.message);
                }

            }

        });
    }


</script>
@endif