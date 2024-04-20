
$(document).ready(function() {

    btnAdd = '&emsp;&emsp; <button type="button" class="btn btn-outline-primary btn-sm" title="Tambah Data Admin" id="btnAdd"> <i class="fas fa-plus"></i> Tambah Pengguna</button>';
    
    btnRefresh = '&emsp;&emsp; <button type="button" class="btn btn-outline-success btn-sm" title="Refresh Table" id="btnRefresh"> <i class="fas fa-sync-alt"></i> Refresh</button>';
    
    listDataTables("listTable","admins-list",btnRefresh,btnAdd);

    btnRefreshTrash = '&emsp;&emsp; <button type="button" class="btn btn-outline-success btn-sm" title="Refresh Table" id="btnRefreshTrash"> <i class="fas fa-sync-alt"></i> Refresh</button>';
    listDataTables("listTableTrash","admins-list-trash",btnRefreshTrash);
})

function listDataTables(idTable,urlAjax,btnRefresh,btnAdd="") 
{
    $("#"+idTable).DataTable({
        dom: "<'dt--top-section'<'row'<'col-12 col-sm-6 d-flex justify-content-sm-start justify-content-center'l><'col-12 col-sm-6 d-flex justify-content-sm-end justify-content-center mt-sm-0 mt-3'f>>>" + "<'table-responsive'tr>" + "<'dt--bottom-section d-sm-flex justify-content-sm-between text-center'<'dt--pages-count  mb-sm-0 mb-3'i><'dt--pagination'p>>",
        serverSide:true,
        processing:true,
        oLanguage:{
            sZeroRecords: "<center>Data tidak ditemukan</center>",
            sLengthMenu: "Tampilkan _MENU_ data "+btnAdd+btnRefresh,
            sSearch: '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-search"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>',
            sSearchPlaceholder: "Pencarian...",
            sInfo: "Menampilkan: _START_ - _END_ dari total: _TOTAL_ data",
            sInfoEmpty: "Menampilkan 0 ke 0 dari 0 data",
            // oPaginate: { 
            //     sPrevious: '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-left"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>', 
            //     sNext: '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-right"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>' 
            // },
            // oPaginate:{
            //     sFirst: "Awal", "sPrevious": "Sebelumnya",
            //     sNext: "Selanjutnya", "sLast": "Akhir"
            // }
        },
        ajax: {
            url: url_back+'/'+urlAjax,
            type: 'POST',
            headers: {'X-CSRF-TOKEN': tokenCsrf},
        },
        order:[[1, 'ASC']],
        columns:[
            {
                data:'no',
                searchable:false,
                orderable:false,
            },
            { data:'name' },
            { data:'username' },
            { data:'level' },
            { data:'role' },
            { data:'email' },
            { data:'no_telp' },
            {
                data:'action',
                searchable:false,
                orderable:false,
            },
        ]
    })
}

function reloadTable() {
    $("#listTable").DataTable().ajax.reload(null,false);
    $("#listTableTrash").DataTable().ajax.reload(null,false);
}

$(document).on('click', '#btnRefresh, #btnRefreshTrash', function(e) {
    e.preventDefault();
    reloadTable();
    $("#btnRefresh, #btnRefreshTrash").children().addClass("fa-spin");
    setTimeout(function() {
        $("#btnRefresh, #btnRefreshTrash").children().removeClass("fa-spin");
    }, 1000);
})

var idData, save_method;
$(document).on('click', '#btnAdd', function(e) {
    e.preventDefault();
    $("#modalForm").modal("show");
    $("#formData")[0].reset();
    $(".modal-title").text("Tambah Data Pengguna");
    $("#username").attr('readonly', false);
    save_method = "add";
    $("input[name='_method']").val("POST");
})

function editData(id) {
    idData = id;
    $("#formData")[0].reset();
    $("#modalForm").modal("show");
    $(".modal-title").text("Edit Data Pengguna");
    $("#username").attr('readonly', true);
    save_method = "update";
    $("input[name='_method']").val("PUT");

    $.get(url_back+"/admins/"+idData+"/edit", function(resp) {
        if (resp.status == true) {
            $("#name").val(resp.data.name);
            $("#email").val(resp.data.email);
            $("#no_telp").val(resp.data.no_telp);
            $("#level").val(resp.data.level);
            $("#username").val(resp.data.username);
            $("#username").attr('readonly',true);
            $("#role").val(resp.data.role);
        } else {
            setTimeout(function() {
                $("#modalForm").modal("hide");
            },1000);

            Swal.fire({
                icon: 'error',
                title: resp.message,
                showConfirmButton: false,
                timer: 2000,
                scrollbarPadding: false,
            });
            reloadTable();
        }
    })
}

$("#modalBtnSave").click(function() {
    $("#modalBtnSave").attr("disabled",true);
    $("#modalBtnSave").html('Sedang Proses Simpan...<i class="fa fa-spinner fa-spin"></i>');

    if (save_method == "add") {
        var url = url_back+'/admins';
    } else {
        var url = url_back+'/admins/'+idData;
    }

    var formData = new FormData($("#formData")[0]);
    $.ajax({
        url: url,
        type: 'POST',
        data: formData,
        contentType: false,
        processData: false,
        dataType: 'JSON',
        headers: {'X-CSRF-TOKEN': tokenCsrf},
        success: function(resp) {
            if (resp.status == true) {
                setTimeout(function() {
                    $("#formData")[0].reset();
                    $("#modalForm").modal("hide");
                    reloadTable();
                },1500);

                $("#modalBtnSave").attr("disabled",false);
                $("#modalBtnSave").html('<i class="far fa-save"></i> Simpan');

                Swal.fire({
                    icon: 'success',
                    title: resp.message,
                    showConfirmButton: false,
                    timer: 2500,
                    scrollbarPadding: false,
                });
                
            } else {
                $("#modalBtnSave").attr("disabled",false);
                $("#modalBtnSave").html('<i class="far fa-save"></i> Simpan');

                Swal.fire({
                    icon: 'error',
                    html: resp.message,
                });
            }
        }
    });
})

function deleteData(id) {
    idData = id;
    $.get(url_back+"/admins/"+idData+"/edit", function(resp) {
        if (resp.status == true) {
            var msg = "";
            msg += '<ul class="text-red">'
                    +'<li class="float-start"><small>Nama : <i>'+resp.data.name+'</i></small></li><br>'
                    +'<li class="float-start"><small>Username : <i>'+resp.data.username+'</i></small></li><br>'
                    +'<li class="float-start"><small>Level : <i>'+resp.data.level+'</i></small></li><br>'
                    +'<li class="float-start"><small>Email : <i>'+resp.data.email+'</i></small></li><br>'
                    +'<li class="float-start"><small>No Telp : <i>'+resp.data.no_telp+'</i></small></li><br>'
                +'</ul>';
                
            Swal.fire({
                icon: 'question',
                title: 'Apakah anda yakin Hapus.?',
                html: msg,
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, Hapus',
                cancelButtonText: 'Batal',
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: url_back+"/admins/"+idData,
                        type: 'DELETE',
                        dataType: 'JSON',
                        headers: {'X-CSRF-TOKEN': tokenCsrf},
                        success: function(resp) {
                            if (resp.status == true) {
                                Swal.fire({
                                    icon: 'success',
                                    title: resp.message,
                                    showConfirmButton: false,
                                    timer: 2500,
                                    scrollbarPadding: false,
                                });
                            } else {
                                Swal.fire({
                                    icon: 'error',
                                    title: resp.message,
                                    showConfirmButton: false,
                                    timer: 2000,
                                    scrollbarPadding: false,
                                });
                            }
                            reloadTable();
                        }
                    });
                }
            })
        } else {
            Swal.fire({
                icon: 'error',
                title: resp.message,
                showConfirmButton: false,
                timer: 2000,
                scrollbarPadding: false,
            });
            reloadTable();
        }
    })

}


function restoreData(id) {
    idData = id;
    $.get(url_back+"/admins/"+idData+"/trash", function(resp) {
        if (resp.status == true) {
            var msg = "";
            msg += '<ul class="text-red">'
                    +'<li class="float-start"><small>Nama : <i>'+resp.data.name+'</i></small></li><br>'
                    +'<li class="float-start"><small>Username : <i>'+resp.data.username+'</i></small></li><br>'
                    +'<li class="float-start"><small>Level : <i>'+resp.data.level+'</i></small></li><br>'
                    +'<li class="float-start"><small>Email : <i>'+resp.data.email+'</i></small></li><br>'
                    +'<li class="float-start"><small>No Telp : <i>'+resp.data.no_telp+'</i></small></li><br>'
                +'</ul>';
                
            Swal.fire({
                icon: 'question',
                title: 'Apakah anda yakin Restore.?',
                html: msg,
                showCancelButton: true,
                confirmButtonColor: '#00ab55',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, Restore',
                cancelButtonText: 'Batal',
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: url_back+"/admins/"+idData+"/restore",
                        type: 'POST',
                        dataType: 'JSON',
                        headers: {'X-CSRF-TOKEN': tokenCsrf},
                        success: function(resp) {
                            if (resp.status == true) {
                                Swal.fire({
                                    icon: 'success',
                                    title: resp.message,
                                    showConfirmButton: false,
                                    timer: 2500,
                                    scrollbarPadding: false,
                                });
                            } else {
                                Swal.fire({
                                    icon: 'error',
                                    title: resp.message,
                                    showConfirmButton: false,
                                    timer: 2000,
                                    scrollbarPadding: false,
                                });
                            }
                            reloadTable();
                        }
                    });
                }
            })
        } else {
            Swal.fire({
                icon: 'error',
                title: resp.message,
                showConfirmButton: false,
                timer: 2000,
                scrollbarPadding: false,
            });
            reloadTable();
        }
    })

}