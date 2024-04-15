<script>
    function loading(id) {
        $("#"+id).text("Processing.....");
        $("#"+id).attr("disabled", true);
    }

    function unloading(id, text) {
        $("#"+id).text(text);
        $("#"+id).removeAttr("disabled");
    }

</script>

@if($view == "user-list")
        <script>
        $('.profile-image').click(function(){ $('#image').trigger('click'); });
     
        $("#image").change(function() {
            document.getElementById('profile-image').src = window.URL.createObjectURL(this.files[0]); 
            $("#remove-profile-image").show();
        });

        function remove_foto() {
            $("#image").val(null);
            $("#profile-image").attr('src', '{{ asset('template/images/profil_icon.png') }}');
            $("#remove-profile-image").hide();
        }    

        var table = $('#listTable').DataTable({
            processing:true,
            serverSide:true,
            dom: 'Blfrtip',
            buttons: [
                'copy', 'csv', 'excel', 'pdf', 'print'
            ],
            lengthMenu: [
                [10, 25, 50, -1],
                [10, 25, 50, 'All'],
            ],
            ajax: "{{ route('user.list') }}",
            order: [[ 0, "desc" ]],
            columns: [
                {data: 'id', name: 'id', searchable: false },
                {data:'action', name: 'action', orderable: false, searchable: false},
                {data:'level', name: 'level'},
                {data:'is_active', name: 'is_active'},
                {data:'name', name: 'name'},
                {data:'foto', name: 'foto'},
                {data:'username', name: 'username'},
                {data:'email', name: 'email'},
                {data:'no_hp', name: 'no_hp'},
               
            ]
        });

        function addData() {
            resetForm();
            
            save_method = "add";
            $('input[name=_method]').val('POST');
            $(".modal-title").text("Add Data");
            $("#modal-tambah").modal("show");
        }

        $("#form-tambah").submit(function(e){
            loading("btn-save-data");
            e.preventDefault();
            var id = $('#id').val();
            if(save_method == "add")  url = "{{ url('/backdata/user') }}";
            else url = "{{ url('/backdata/user') .'/'}}"+ id;
            $.ajax({
                url : url,
                type : "POST",
                data : new FormData($('#modal-tambah form')[0]),
                contentType:false,
                processData:false,
                success : function(data){
                    unloading("btn-save-data", "Save");
                    if(data.success){
                        $('#modal-tambah').modal('hide');
                        table.ajax.reload(null, false);
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: data.message,
                            showConfirmButton: false,
                            scrollbarPadding: false,
                        });
                    }
                }

            });
        });

        function editData(id) {
            save_method = "edit";
            $('input[name=_method]').val('PATCH');
            $.ajax({
                url: "{{ url('/backdata/user') }}" +"/"+id+"/edit",
                type: "GET",
                dataType: "JSON",
                success: function(data){
                    $('#modal-tambah').modal("show");
                    $('.modal-title').text("Edit Data");
                    $('#id').val(data.id);
                    $("#name").val(data.name);
                    $("#username").val(data.username);
                    $("#email").val(data.email);
                    $("#password").val("");
                    $("#jenis_kelamin").val(data.jenis_kelamin);
                    $("#no_hp").val(data.no_hp);
                    $("#level").val(data.level);
                    $("#is_active").val(data.is_active);
                    $("#penyelia").val(data.penyelia);
                    $("#blok").val(data.blok);
                    $("#nomor_rumah").val(data.nomor_rumah);
                    $("#daya_listrik").val(data.daya_listrik);
                    $("#luas_tanah").val(data.luas_tanah);
                    $("#iuran_bulanan").val(data.iuran_bulanan);
                    $("#whatsapp_emergency").val(data.whatsapp_emergency);
                    $("#keterangan").val(data.keterangan);
                    $("#alamat_surat_menyurat").val(data.alamat_surat_menyurat);
                    $("#nomor_telepon_rumah").val(data.nomor_telepon_rumah);
                    $("#id_pelanggan_pdam").val(data.id_pelanggan_pdam);
                    $("#nomor_meter_pln").val(data.nomor_meter_pln);
                    $("#mulai_menempati").val(data.mulai_menempati);
                    if(data.foto != null && data.foto != '') {
                        $("#profile-image").attr('src', '{{ asset('storage/profile') }}/'+data.foto);
                    } else {
                        $("#profile-image").attr('src', '{{ asset('template/images/profil_icon.png') }}');
                    }
                   

                }
            })
        }

        function deleteData(id) {
            Swal.fire({
                icon: 'question',
                title: 'Delete this data?',
                
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, Delete',
                cancelButtonText: 'Cancel',
            }).then((result) => {
                if (result.isConfirmed) {
                    var csrf_token = $('meta[name="csrf-token"]').attr('content');
                    $.ajax({
                        url  : "{{ url('/backdata/user') }}" + '/'+id,
                        type : "POST",
                        data : {'_method':'DELETE', '_token':csrf_token},
                        success : function($data){
                            table.ajax.reload(null, false);
                        }
                    });
                }
            });
        }
        
        function detailData(id) {
            $.ajax({
                url: "{{ url('backdata/user') }}"+"/"+id,
                type: "GET",
                success: function(data) {
                    $("#detail-content").html(data); 
                    $("#modal-detail").modal("show");
                }
            });    
        }

        $("#btn-print-detail").click(function(){
            var id = $("#id-detail").val();
            window.open('{{ url('backdata/print_detail') }}'+'/'+id, '_blank');

        });
        
        function resetForm() {
            $("#name").val("");
            $("#username").val("");
            $("#email").val("");
            $("#password").val("");
            $("#jenis_kelamin").val("");
            $("#no_hp").val("");
            $("#level").val("");
            $("#is_active").val("");
            $("#penyelia").val("");
            $("#blok").val("");
            $("#nomor_rumah").val("");
            $("#daya_listrik").val("");
            $("#luas_tanah").val("");
            $("#iuran_bulanan").val("");
            $("#whatsapp_emergency").val("");
            $("#keterangan").val("");
            $("#alamat_surat_menyurat").val("");
            $("#nomor_telepon_rumah").val("");
            $("#id_pelanggan_pdam").val("");
            $("#nomor_meter_pln").val("");
            $("#mulai_menempati").val("");
            $("#image").val(null);
            $("#profile-image").attr('src', '{{ asset('template/images/profil_icon.png') }}');
            $("#remove-profile-image").hide();
        }
    </script>
@endif
@if($view == "transaction")
        <script>
        var table = $('#listTable').DataTable({
            processing:true,
            serverSide:true,
            dom: 'Blfrtip',
            buttons: [
                'copy', 'csv', 'excel', 'pdf', 'print'
            ],
            lengthMenu: [
                [10, 25, 50, -1],
                [10, 25, 50, 'All'],
            ],
            ajax: "{{ route('transaction.list') }}",
            order: [[ 0, "desc" ]],
            columns: [
                {data: 'id', name: 'id', searchable: false },
                {data:'action', name: 'action', orderable: false, searchable: false},
                {data:'created_at', name: 'created_at'},
                {data:'payment_status', name: 'payment_status'},
                {data:'user_id', name: 'user_id'},
                {data:'business_unit_id', name: 'business_unit_id'},
                {data:'invoice', name: 'invoice'},
                {data:'detail', name: 'detail'},
                {data:'total_price', name: 'total_price'},
                {data:'package_name', name: 'package_name'},
               
                {data:'paid_at', name: 'paid_at'},
               
            ]
        });

        
        function deleteData(id) {
            Swal.fire({
                icon: 'question',
                title: 'Delete this transaction ?',
                
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, Delete',
                cancelButtonText: 'Cancel',
            }).then((result) => {
                if (result.isConfirmed) {
                    var csrf_token = $('meta[name="csrf-token"]').attr('content');
                    $.ajax({
                        url  : "{{ url('/backdata/transaction') }}" + '/'+id,
                        type : "POST",
                        data : {'_method':'DELETE', '_token':csrf_token},
                        success : function($data){
                            table.ajax.reload(null, false);
                        }
                    });
                }
            });
        }
        
        function detailData(id) {
            $.ajax({
                url: "{{ url('backdata/transaction') }}"+"/"+id,
                type: "GET",
                success: function(data) {
                    $("#detail-content").html(data); 
                    $("#modal-detail").modal("show");
                }
            });    
        }

        $("#btn-print-detail").click(function(){
            var id = $("#id-transaction").val();
            window.open('{{ url('backdata/print_transaction') }}'+'/'+id, '_blank');

        });

        function paymentData(id) {
            Swal.fire({
                icon: 'question',
                title: 'Process This Payment...?',
                
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, Process',
                cancelButtonText: 'Cancel',
            }).then((result) => {
                if (result.isConfirmed) {
                    var csrf_token = $('meta[name="csrf-token"]').attr('content');
                    $.ajax({
                        url  : "{{ url('/backdata/payment') }}",
                        type : "POST",
                        data : {'id':id, '_token':csrf_token},
                        success : function(data){
                            if(data.success) {
                                table.ajax.reload(null, false);
                            }
                            
                        }
                    });
                }
            });
        }
        

        function printData(id) {
            window.location = "{{ url('/backdata/print_ticket') }}"+"/"+id;
        }
        
    </script>
@endif
@if($view == "ticketing")
        <script>

        $("#btn-on-hold").click(function(){
            var id = $("#ticket_id").val();
            Swal.fire({
                icon: 'question',
                title: 'Set This Ticket to On Hold ?',
                
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, Set to On Hold',
                cancelButtonText: 'Cancel',
            }).then((result) => {
                if (result.isConfirmed) {
                    var csrf_token = $('meta[name="csrf-token"]').attr('content');
                    $.ajax({
                        url  : "{{ url('/backdata/set_on_hold') }}",
                        type : "POST",
                        data : {'id':id, '_token':csrf_token},
                        success : function(data){
                            if(data.success) {
                                table.ajax.reload(null, false);
                                Swal.fire({
                                    icon: 'success',
                                    title: data.message,
                                    showConfirmButton: false,
                                    scrollbarPadding: false,
                                });
                                $("#modal-detail").modal("hide");
                            }
                            
                        }
                    });
                }
            });
        });


        $("#btn-resolved").click(function(){
            var id = $("#ticket_id").val();
            Swal.fire({
                icon: 'question',
                title: 'Set This Ticket to Resolved ?',
                
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, Set to Resolved',
                cancelButtonText: 'Cancel',
            }).then((result) => {
                if (result.isConfirmed) {
                    var csrf_token = $('meta[name="csrf-token"]').attr('content');
                    $.ajax({
                        url  : "{{ url('/backdata/set_resolved') }}",
                        type : "POST",
                        data : {'id':id, '_token':csrf_token},
                        success : function(data){
                            if(data.success) {
                                table.ajax.reload(null, false);
                                Swal.fire({
                                    icon: 'success',
                                    title: data.message,
                                    showConfirmButton: false,
                                    scrollbarPadding: false,
                                });
                                $("#modal-detail").modal("hide");
                            }
                            
                        }
                    });
                }
            });
        });


        $(document).ready(function(){
            $("#form-reply").submit(function(e){
                loading("btn-post-reply");
                e.preventDefault();
                var message = CKEDITOR.instances.message.getData();
                var formdata = new FormData($('#modal-detail form')[0]);
                formdata.append('pesan', message);
                $.ajax({
                    url : "{{ url('backdata/ticketing') }}",
                    type : "POST",
                    data : formdata,
                    contentType:false,
                    processData:false,
                    success : function(data){
                        unloading("btn-post-reply", "Post");
                        if(data.success){
                            $('#modal-detail').modal('hide');
                            table.ajax.reload(null, false);
                            Swal.fire({
                                icon: 'success',
                                title: data.message,
                                showConfirmButton: false,
                                scrollbarPadding: false,
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: data.message,
                                showConfirmButton: false,
                                scrollbarPadding: false,
                            });
                        }
                    }

                });
            });
        
        });
    

        var table = $('#listTable').DataTable({
            processing:true,
            serverSide:true,
            dom: 'Blfrtip',
            buttons: [
                'copy', 'csv', 'excel', 'pdf', 'print'
            ],
            lengthMenu: [
                [10, 25, 50, -1],
                [10, 25, 50, 'All'],
            ],
            ajax: "{{ route('ticketing.list') }}",
            order: [[ 0, "desc" ]],
            columns: [
                {data: 'id', name: 'id', searchable: false },
                {data:'action', name: 'action', orderable: false, searchable: false},
                {data:'status', name: 'status'},
                {data:'created_at', name: 'created_at'},
                {data:'ticket_number', name: 'ticket_number'},
                {data:'user_id', name: 'user_id'},
                {data:'subject', name: 'subject'},
                {data:'department', name: 'department'},
                {data:'priority', name: 'priority'},
                {data:'document', name: 'document'},
                
                {data:'updated_at', name: 'updated_at'},
               
            ]
        });
        
        function deleteData(id) {
            Swal.fire({
                icon: 'question',
                title: 'Delete this Ticket ?',
                
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, Delete',
                cancelButtonText: 'Cancel',
            }).then((result) => {
                if (result.isConfirmed) {
                    var csrf_token = $('meta[name="csrf-token"]').attr('content');
                    $.ajax({
                        url  : "{{ url('/backdata/ticketing') }}" + '/'+id,
                        type : "POST",
                        data : {'_method':'DELETE', '_token':csrf_token},
                        success : function($data){
                            table.ajax.reload(null, false);
                        }
                    });
                }
            });
        }
        
        function detailData(id) {
            $.ajax({
                url: "{{ url('backdata/ticketing') }}"+"/"+id,
                type: "GET",
                success: function(data) {
                    $(".modal-title").text("Ticketing Summary");
                    $("#detail-content").html(data); 
                    $("#modal-detail").modal("show");
                    CKEDITOR.replace('message');    
                }
            });    
        }

        
        
    </script>
@endif