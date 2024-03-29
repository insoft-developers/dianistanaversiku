
<script>
function tutup_alert() {
	$(".alert").hide();
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