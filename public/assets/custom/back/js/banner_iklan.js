
$(document).ready(function() {

    btnAdd = '&emsp;&emsp; <button type="button" class="btn btn-outline-primary btn-sm" title="Tambah Data Banner Iklan" id="btnAdd"> <i class="fas fa-plus"></i> Tambah Banner Iklan</button>';
    
    btnRefresh = '&emsp;&emsp; <button type="button" class="btn btn-outline-success btn-sm" title="Refresh Table" id="btnRefresh"> <i class="fas fa-sync-alt"></i> Refresh</button>';
    
    listDataTables("listTable","banner-iklan-list",btnRefresh,btnAdd);

    btnRefreshTrash = '&emsp;&emsp; <button type="button" class="btn btn-outline-success btn-sm" title="Refresh Table" id="btnRefreshTrash"> <i class="fas fa-sync-alt"></i> Refresh</button>';
    listDataTables("listTableTrash","banner-iklan-list-trash",btnRefreshTrash);
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
            oPaginate: { 
                sPrevious: '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-left"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>', 
                sNext: '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-right"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>' 
            },
        },
        ajax: {
            url: url_back+'/'+urlAjax,
            type: 'POST',
            headers: {'X-CSRF-TOKEN': tokenCsrf},
        },
        order:[[2, 'DESC']],
        columns:[
            {
                data:'no',
                searchable:false,
                orderable:false,
            },
            {
                data:'action',
                searchable:false,
                orderable:false,
            },
            { data:'created_at_carbon' },
            {
                data:'image_src',
                searchable:false,
                orderable:false,
            },
            { data:'title' },
            { data:'content_limit' },
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
    showForm();
    $("#formData")[0].reset();
    $("#is_remove_banner").val(0).trigger("change");
    $("#showImg_banner").children().attr("src",assetImg_thumbnail);
    save_method = "add";
    $("input[name='_method']").val("POST");
})

function editData(id) {
    idData = id;
    $("#formData")[0].reset();
    $("#is_remove_banner").val(0).trigger("change");
    showForm();
    save_method = "update";
    $("input[name='_method']").val("PUT");

    $.get(url_back+"/banner-iklan/"+idData+"/edit", function(resp) {
        if (resp.status == true) {
            $("#showImg_banner").children().attr("src",resp.data.image_src);
            $("#title").val(resp.data.title);
            // console.log(resp.data.content);

            if (resp.data.content != null) {
                tinymce.get("content_banner").setContent(resp.data.content);
            }
        } else {
            setTimeout(function() {
                showTables();
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

$("#btnSave").click(function() {
    $("#btnSave").attr("disabled",true);
    $("#btnSave").html('Sedang Proses Simpan...<i class="fa fa-spinner fa-spin"></i>');

    let content = tinymce.get("content_banner").getContent();
    // console.log(content);
    $("textarea[name=content]").val(content);
    
    if (save_method == "add") {
        var url = url_back+'/banner-iklan';
    } else {
        var url = url_back+'/banner-iklan/'+idData;
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
                    showTables();
                    reloadTable();
                },1500);

                $("#btnSave").attr("disabled",false);
                $("#btnSave").html('<i class="fas fa-save"></i> Simpan');

                Swal.fire({
                    icon: 'success',
                    title: resp.message,
                    showConfirmButton: false,
                    timer: 2000,
                    scrollbarPadding: false,
                });
                
            } else {
                $("#btnSave").attr("disabled",false);
                $("#btnSave").html('<i class="fas fa-save"></i> Simpan');

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
    $.get(url_back+"/banner-iklan/"+idData+"/edit", function(resp) {
        if (resp.status == true) {
            var msg = "";
            msg += '<div class="row">'
                        +'<div class="col-md-2">'
                            +'<div class="avatar avatar-lg"><img alt="'+resp.data.title+'" src="'+resp.data.image_src+'" class="rounded" /></div>'
                        +'</div>'
                        +'<div class="col-md-9">'
                            +'<ul class="text-red">'
                                +'<li class="float-start"><small>Tgl Input : <i>'+resp.data.created_at_carbon+'</i></small></li><br>'
                                +'<li class="float-start"><small>Judul Banner : <i>'+resp.data.title+'</i></small></li><br>'
                            +'</ul>'
                        +'</div>'
                    +'</div>';
                
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
                        url: url_back+"/banner-iklan/"+idData,
                        type: 'DELETE',
                        dataType: 'JSON',
                        headers: {'X-CSRF-TOKEN': tokenCsrf},
                        success: function(resp) {
                            if (resp.status == true) {
                                Swal.fire({
                                    icon: 'success',
                                    title: resp.message,
                                    showConfirmButton: false,
                                    timer: 2000,
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
    $.get(url_back+"/banner-iklan/"+idData+"/trash", function(resp) {
        if (resp.status == true) {
            var msg = "";
            msg += '<div class="row">'
                        +'<div class="col-md-2">'
                            +'<div class="avatar avatar-lg"><img alt="'+resp.data.title+'" src="'+resp.data.image_src+'" class="rounded" /></div>'
                        +'</div>'
                        +'<div class="col-md-9">'
                            +'<ul class="text-red">'
                                +'<li class="float-start"><small>Tgl Input : <i>'+resp.data.created_at_carbon+'</i></small></li><br>'
                                +'<li class="float-start"><small>Judul Banner : <i>'+resp.data.title+'</i></small></li><br>'
                            +'</ul>'
                        +'</div>'
                    +'</div>';
    
                
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
                        url: url_back+"/banner-iklan/"+idData+"/restore",
                        type: 'POST',
                        dataType: 'JSON',
                        headers: {'X-CSRF-TOKEN': tokenCsrf},
                        success: function(resp) {
                            if (resp.status == true) {
                                Swal.fire({
                                    icon: 'success',
                                    title: resp.message,
                                    showConfirmButton: false,
                                    timer: 2000,
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

$(".close-form").click(function() {
    showTables();
})

function showForm() 
{
    $("#divTables").hide(300);
    $("#divForm").show(300);
}

function showTables() 
{
    $("#divForm").hide(300);
    $("#divTables").show(300);
}

settingImageData("banner");

// $(".avatar img").click(function() {
$(document).on('click', '.avatar-lg img', function(e) {
    // alert("tewsadgt")
    Swal.fire({
        imageUrl: $(this).attr("src"),
    });
})