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
            ajax: "{{ route('user.list') }}",
            order: [[ 1, "desc" ]],
            columns: [
                {data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                {data:'action', name: 'action', orderable: false, searchable: false},
                {data:'name', name: 'name'},
                {data:'username', name: 'username'},
                {data:'email', name: 'email'},
                {data:'no_hp', name: 'no_hp'},
                {data:'level', name: 'level'},
                {data:'is_active', name: 'is_active'}
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
                    unload();
                    if(data.success){
                        $('#modal-tambah').modal('hide');
                        table.ajax.reload(null, false);
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: data.message,
                            showConfirmButton: false,
                            timer: 2000,
                            scrollbarPadding: false,
                        });
                    }
                }

            });
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