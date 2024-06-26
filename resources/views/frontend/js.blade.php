
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
    let selected_package= "";
    let selected_package_price = "";

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
    var selected_j=0;
    var selected_k=0;
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
        disabled_option(id);
        
    }

    // onclick="select_finish_hour(2)"

    function disabled_option(id) {
        var kategori = $("#product_category").val();
        if(kategori == 'Komunal Space') {
            var j = +id + 1;
            var k = +id + 2;
            var l = +id + 3;
            
            selected_j = j;
            selected_k = k;
            selected_l = l;
            for(var n=1; n<17; n++) {
                if(n != j && n != k && n != l) {
                    $("#finish_"+n).addClass("hour-disabled");
                    $("#finish_"+n).removeAttr("onclick");
                    $("#finish_"+n).removeClass("actived");
                    
                }
            }
            
            $("#finish_"+selected_j).removeClass("hour-disabled");
            $("#finish_"+selected_j).attr("onclick", "select_finish_hour("+selected_j+")");
            $("#finish_"+selected_k).removeClass("hour-disabled");
            $("#finish_"+selected_k).attr("onclick", "select_finish_hour("+selected_k+")");
            $("#finish_"+selected_l).removeClass("hour-disabled");
            $("#finish_"+selected_l).attr("onclick", "select_finish_hour("+selected_l+")");
        } else {
            var j = +id + 1;
            var k = +id + 2;
            
            selected_j = j;
            selected_k = k;
            for(var n=1; n<17; n++) {
                if(n != j && n != k) {
                    $("#finish_"+n).addClass("hour-disabled");
                    $("#finish_"+n).removeAttr("onclick");
                    $("#finish_"+n).removeClass("actived");
                    
                }
            }
            
            $("#finish_"+selected_j).removeClass("hour-disabled");
            $("#finish_"+selected_j).attr("onclick", "select_finish_hour("+selected_j+")");
            $("#finish_"+selected_k).removeClass("hour-disabled");
            $("#finish_"+selected_k).attr("onclick", "select_finish_hour("+selected_k+")");
        }
        
    }

    var selected_finish_hour_index = 0;
    function select_finish_hour(id) {
        var pilihan = $("#hour_finish_"+id).val();
       
        

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
                var kategori = $("#product_category").val();
                if(kategori == 'Komunal Space') {
                    if(selisih > 3) {
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
                            
                        }
                        else if(selisih == 3) {

                            if(pilihan == 18) {
                                var totalprice = +selected_price_start + +selected_price_finish;
                                $("#price").text("Rp. "+formatAngka(totalprice));
                                $("#price-input").val(totalprice);
                            } else {
                                var totalprice = +selected_price_start + +selected_price_finish + +selected_price_finish;
                                $("#price").text("Rp. "+formatAngka(totalprice));
                                $("#price-input").val(totalprice);
                            }
                           
                        } 
                        
                        else {
                            $("#price").text("Rp. "+formatAngka(selected_price_start));
                            $("#price-input").val(selected_price_start);
                        }
                    }
                } else {
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
        var packageId = $("#package-input").val();
        var packageName = $("#package").text();
        $.ajax({
            url: "{{ url('transaction') }}",
            type: "POST",
            dataType: "JSON",
            data: {"business_unit_id":productId, "invoice":invoice, "start_time":startTime, "finish_time":finishTime, "quantity":quantity, "total_price":totalPrice, "booking_date":bookingDate, "package_id":packageId, "package_name":packageName, "_token":csrf_token},
            success: function(data) {
                console.log(data);
                $("#btn_send_transaction").text("Submit");
                if(data.success) {
                    // alert(data.message);
                    if(data.total_price <= 0) {
                        window.location = "{{ url('riwayat') }}";
                    } else {
                        payment_process(data.id);
                    }
                    
                   
                } else {
                    show_error("Error", data.message);
                }

            }

        });
    }
    
    $("#paket").change(function(){
        var paket = $(this).val();
        $("#package-input").val(paket);
        
        
        if(paket == 1) {
           $("#package").text("Membership 4x pertemuan");
           var harga = $("#harga_4x").val();
           selected_package_price = harga;
           
        }
        else if(paket == 2) {
           $("#package").text("Membership 8x pertemuan");
           var harga = $("#harga_8x").val();
           selected_package_price = harga;
        }
        else if(paket == 3) {
           $("#package").text("Non Member");
           var harga = $("#harga_non_member").val();
           selected_package_price = harga;
        }
        else if(paket == 4) {
           $("#package").text("Paket Khusus Tamu Warga");
           var harga = $("#harga_tamu_warga").val();
           selected_package_price = harga;
        }

        $("#quantity").text("");
        $("#quantity-input").val("");
        $("#price").text("");
        $("#price-input").val("");
        $("#input_q").val("");
    });

    $("#input_q").keyup(function(){
        var nilai = $(this).val();
        $("#quantity").text(nilai+" orang");
        $("#quantity-input").val(nilai);
        var totalharga = selected_package_price * nilai;
        $("#price").text("Rp. "+formatAngka(totalharga));
        $("#price-input").val(totalharga);
    })

</script>
@endif

@if($view == "riwayat" || $view == "booking-detail")
<script>
    function payment_process(id) {
        var csrf_token = $('meta[name="csrf-token"]').attr('content');
        $("#btn_payment_"+id).text("Processing...");
        $("#btn_payment_"+id).attr("disabled", true);
        $.ajax({
            url: "{{ url('payment_process') }}",
            type: "POST",
            dataType: "JSON",
            data: {"id":id, "_token":csrf_token},
            success: function(data) {
                $("#btn_payment_"+id).html('<i class="fa fa-dollar"></i> Payment');
                $("#btn_payment_"+id).removeAttr("disabled");
                if(data.success) {
                    window.location=data.data.paymentUrl;

                }
                
            }
        })
    }

    let table = $('#table-riwayat').DataTable({
        order: [[1, 'desc']]
    });
</script>
@endif

@if($view == "ticketing")
<script src="https://cdn.ckeditor.com/4.22.1/standard/ckeditor.js"></script>
<script>
    CKEDITOR.replace("message");
    let table = $("#table-ticketing").DataTable({
        order: [[4, 'desc']]
    });
</script>
@endif
@if($view == "ticketing-detail")
<script src="https://cdn.ckeditor.com/4.22.1/standard/ckeditor.js"></script>
<script>
    CKEDITOR.replace("message");
    $("#btn-reply").click(function(){
        $(this).hide();
        $("#btn-reply-cancel").show();

        $("#form-ticket-container").slideDown();
    });

    $("#btn-reply-cancel").click(function(){
        $(this).hide();
        $("#btn-reply").show();

        $("#form-ticket-container").slideUp();
    })

</script>
@endif

@if($view == 'frontend-setting')
<script>
    $('.image-pickup').click(function(){ $('#foto').trigger('click'); });
     
    $("#foto").change(function() {
        document.getElementById('img-pro').src = window.URL.createObjectURL(this.files[0]); 
        $(".cancel-upload-container").show();
    });
    function remove_foto() {
        $("#foto").val(null);
        $("#img-pro").attr('src', '{{ asset('template/images/profil_icon.png') }}');
        $(".cancel-upload-container").hide();
    }
</script>
@endif


@if($view == 'notif')
<script>
    let table = $("#table-notif").DataTable({
        bAutoWidth: false, 
        order: [[1, 'desc']],
        columnDefs: [{ width: '3%', targets: 0 },{ width: '10%', targets: 1 },{ width: '20%', targets: 2 }, { width: '30%', targets: 3 }]
    });
</script>
@endif

@if($view == 'payment-menu')

<script>
    
    let table = $("#table-payment").DataTable({
        bAutoWidth: false, 
        order: [[1, 'desc']],
        columnDefs: [{ width: '3%', targets: 0 },{ width: '10%', targets: 1 },{ width: '20%', targets: 2 }, { width: '30%', targets: 3 }]
    });


    function payment_process(id) {
        var csrf_token = $('meta[name="csrf-token"]').attr('content');
        $("#btn_payment_"+id).text("Processing...");
        $("#btn_payment_"+id).attr("disabled", true);
        $.ajax({
            url: "{{ route('payment.post') }}",
            type: "POST",
            dataType: "JSON",
            data: {"id":id, "_token": csrf_token},
            success: function(data) {
                $("#btn_payment_"+id).html('<i class="fa fa-dollar"></i> Payment');
                $("#btn_payment_"+id).removeAttr("disabled");
                if(data.success) {
                    console.log(data);
                    window.location=data.data.paymentUrl;
                    
                //     checkout.process(data.data.reference, {
                //     defaultLanguage: "id", //opsional pengaturan bahasa
                //     successEvent: function(result){
                //     // tambahkan fungsi sesuai kebutuhan anda
                //         console.log('success');
                //         console.log(result);
                //         alert('Payment Success');
                //     },
                //     pendingEvent: function(result){
                //     // tambahkan fungsi sesuai kebutuhan anda
                //         console.log('pending');
                //         console.log(result);
                //         alert('Payment Pending');
                //     },
                //     errorEvent: function(result){
                //     // tambahkan fungsi sesuai kebutuhan anda
                //         console.log('error');
                //         console.log(result);
                //         alert('Payment Error');
                //     },
                //     closeEvent: function(result){
                //     // tambahkan fungsi sesuai kebutuhan anda
                //         console.log('customer closed the popup without finishing the payment');
                //         console.log(result);
                //         alert('customer closed the popup without finishing the payment');
                //     }
                // }); 

                } else {
                    show_error("error", data.message);
                }

            }
        })
    }
</script>
@endif




