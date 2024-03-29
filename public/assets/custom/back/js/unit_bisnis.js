
$(document).ready(function() {

    btnAdd = '&emsp;&emsp; <button type="button" class="btn btn-outline-primary btn-sm" title="Tambah Data Unit Bisnis" id="btnAdd"> <i class="fas fa-plus"></i> Tambah Unit Bisnis</button>';
    
    btnRefresh = '&emsp;&emsp; <button type="button" class="btn btn-outline-success btn-sm" title="Refresh Table" id="btnRefresh"> <i class="fas fa-sync-alt"></i> Refresh</button>';
    
    listDataTables("listTable","unit-bisnis-list",btnRefresh,btnAdd);

    btnRefreshTrash = '&emsp;&emsp; <button type="button" class="btn btn-outline-success btn-sm" title="Refresh Table" id="btnRefreshTrash"> <i class="fas fa-sync-alt"></i> Refresh</button>';
    listDataTables("listTableTrash","unit-bisnis-list-trash",btnRefreshTrash);
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
        order:[[2, 'ASC']],
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
            { data:'name_unit' },
            { data:'kategori' },
            {
                data:'image_src',
                searchable:false,
                orderable:false,
            },
            { data:'jenis_harga' },
            { data:'status_booking' },
            { data:'name' },
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
    $(".modal-title").text("Tambah Data Unit Bisnis");
    $("#showImg_unit_bisnis").children().attr("src",assetImg_thumbnail);
    $("#divHargaPerjam").hide();
    $("#divHargaKedatangan").hide();
    save_method = "add";
    $("input[name='_method']").val("POST");
})

$("#jenis_harga").change(function() {
    var val = $(this).val();
    if (val != "") {
        if (val == "Per Jam") {
            $("#divHargaPerjam").show();
            $("#divHargaKedatangan").hide();
        } else if (val == "Kedatangan") {
            $("#divHargaPerjam").hide();
            $("#divHargaKedatangan").show();
        } else {
            $("#divHargaPerjam").hide();
            $("#divHargaKedatangan").hide();
        }
    } else {
        $("#divHargaPerjam").hide();
        $("#divHargaKedatangan").hide();
    }
})

function formatNumberOnInput(input) {
    var value = $(input).val();
    value = value.replace(/[\D\s\._\-]+/g, "");
    value = value ? parseInt(value, 10) : 0;
    $(input).val(function () {
        return (value === 0) ? "" : value.toLocaleString("id-ID");
    });
}

$(".format-number-rp").keyup(function() {
    formatNumberOnInput(this);
})

$(".format-number-rp").change(function() {
    formatNumberOnInput(this);
})


function editData(id) {
    idData = id;
    $("#formData")[0].reset();
    $("#modalForm").modal("show");
    $(".modal-title").text("Edit Data Unit Bisnis");
    save_method = "update";
    $("input[name='_method']").val("PUT");

    $.get(url_back+"/unit-bisnis/"+idData+"/edit", function(resp) {
        if (resp.status == true) {
            $("#showImg_unit_bisnis").children().attr("src",resp.data.image_src);
            $("#kategori").val(resp.data.kategori);
            $("#name_unit").val(resp.data.name_unit);
            $("#jenis_harga").val(resp.data.jenis_harga);
            $("#status_booking").val(resp.data.status_booking);
            
            if (resp.data.jenis_harga == "Per Jam") {
                $("#harga_warga_1721_weekday").val(resp.data.harga_warga_1721_weekday);
                $("#harga_warga_1721_weekend").val(resp.data.harga_warga_1721_weekend);
                $("#harga_umum_0617_weekday").val(resp.data.harga_umum_0617_weekday);
                $("#harga_umum_0617_weekend").val(resp.data.harga_umum_0617_weekend);
                $("#harga_umum_1721_weekday").val(resp.data.harga_umum_1721_weekday);
                $("#harga_umum_1721_weekend").val(resp.data.harga_umum_1721_weekend);
                $("#divHargaPerjam").show();
                $("#divHargaKedatangan").hide();
            } else if (resp.data.jenis_harga == "Kedatangan") {
                $("#harga_membership_4x").val(resp.data.harga_membership_4x);
                $("#harga_membership_8x").val(resp.data.harga_membership_8x);
                $("#harga_non_member").val(resp.data.harga_non_member);
                $("#harga_tamu_warga").val(resp.data.harga_tamu_warga);
                $("#divHargaPerjam").hide();
                $("#divHargaKedatangan").show();
            }
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
        var url = url_back+'/unit-bisnis';
    } else {
        var url = url_back+'/unit-bisnis/'+idData;
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
    $.get(url_back+"/unit-bisnis/"+idData+"/edit", function(resp) {
        if (resp.status == true) {
            var msg = "";
            msg += '<ul class="text-red">'
                    +'<li class="float-start"><small>Nama Unit : <i>'+resp.data.name_unit+'</i></small></li><br>'
                    +'<li class="float-start"><small>Kategori : <i>'+resp.data.kategori+'</i></small></li><br>'
                    +'<li class="float-start"><small>Jenis Harga : <i>'+resp.data.jenis_harga+'</i></small></li><br>'
                    +'<li class="float-start"><small>Status Booking : <i>'+resp.data.status_booking+'</i></small></li><br>'
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
                        url: url_back+"/unit-bisnis/"+idData,
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
    $.get(url_back+"/unit-bisnis/"+idData+"/trash", function(resp) {
        if (resp.status == true) {
            var msg = "";
            msg += '<ul class="text-red">'
                    +'<li class="float-start"><small>Nama Unit : <i>'+resp.data.name_unit+'</i></small></li><br>'
                    +'<li class="float-start"><small>Kategori : <i>'+resp.data.kategori+'</i></small></li><br>'
                    +'<li class="float-start"><small>Jenis Harga : <i>'+resp.data.jenis_harga+'</i></small></li><br>'
                    +'<li class="float-start"><small>Status Booking : <i>'+resp.data.status_booking+'</i></small></li><br>'
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
                        url: url_back+"/unit-bisnis/"+idData+"/restore",
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

settingImageData("unit_bisnis");

$(document).on('click', '.avatar-lg img', function(e) {
    // alert("tewsadgt")
    Swal.fire({
        imageUrl: $(this).attr("src"),
    });
})