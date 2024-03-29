@extends('layouts.master_admins')

@section('title_admin', 'Unit Bisnis')

@section('breadcrumb_admin')
    <li class="breadcrumb-item" aria-current="page">Master Data</li>
    <li class="breadcrumb-item active" aria-current="page">Unit Bisnis</li>
@endsection

@section('content_admin')

<div class="col-xl-12 col-lg-12 col-sm-12 layout-spacing">
    <div class="card">
        <div class="card-body">
            <div class="simple-tab">
                <ul class="nav nav-tabs" id="myTab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="home-tab-icon" data-bs-toggle="tab"
                            data-bs-target="#home-tab-icon-pane" type="button" role="tab"
                            aria-controls="home-tab-icon-pane" aria-selected="true">
                            <i class="far fa-list-alt text-success"></i> Data Unit Bisnis
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="profile-tab-icon" data-bs-toggle="tab"
                            data-bs-target="#profile-tab-icon-pane" type="button" role="tab"
                            aria-controls="profile-tab-icon-pane" aria-selected="false">
                            <i class="fas fa-trash-alt text-danger"></i> Trash Data Unit Bisnis
                        </button>
                    </li>
                </ul>

                <div class="tab-content" id="myTabContent">
                    <div class="tab-pane fade show active" id="home-tab-icon-pane" role="tabpanel"
                        aria-labelledby="home-tab-icon" tabindex="0">
                        <div class="widget-content widget-content-area br-8 mt-1">
                            <div class="table-responsive" id="divTables">
                                <table id="listTable" class="table table-striped table-bordered table-hover"
                                style="width:100%">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Opsi</th>
                                            <th>Nama Unit</th>
                                            <th>Kategori Unit</th>
                                            <th>Gambar Unit</th>
                                            <th>Jenis Harga</th>
                                            <th>Status Booking</th>
                                            <th>Admin</th>
                                        </tr>
                                    </thead>
                                    <tbody></tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="profile-tab-icon-pane" role="tabpanel"
                        aria-labelledby="profile-tab-icon" tabindex="0">
                        <div class="widget-content widget-content-area br-8 mt-1">
                            <div class="table-responsive">
                                <table id="listTableTrash" class="table table-striped table-bordered table-hover"
                                    style="width:100%">
                                    <thead>
                                        <tr>
                                            <th class="text-danger">No</th>
                                            <th class="text-secondary">Opsi</th>
                                            <th class="text-danger">Nama Unit</th>
                                            <th class="text-danger">Kategori Unit</th>
                                            <th class="text-danger">Gambar Unit</th>
                                            <th class="text-danger">Jenis Harga</th>
                                            <th class="text-danger">Status Booking</th>
                                            <th class="text-danger">Admin</th>
                                        </tr>
                                    </thead>
                                    <tbody></tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="modalForm" tabindex="-1" role="dialog" aria-labelledby="modalFormLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalFormLabel">Modal title</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                  <svg aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                </button>
            </div>
            <div class="modal-body">
                <form id="formData" action="#" method="POST" class="mt-0">
                    @method('PUT')
                    <div class="row">
                        <div class="col-md-4">
                            <div class="card mb-3">
                                <div class="card-header" style="padding: 0.2rem;">
                                    <b>Gambar Unit Bisnis</b>
                                </div>
                                <div class="card-body text-center" style="padding: 0.2rem;">
                                    <a href="javascript:void(0);" id="showImg_unit_bisnis" title="Klik untuk Zoom">
                                        <img src="{{ assetImg_thumbnail() }}" class="card-img" style="width: 200px; height: 120px;" alt="Gambar Unit Bisnis">
                                    </a>
                                    <input type="file" name="image" id="file_image_unit_bisnis" style="display: none;">
                                    <input type="hidden" name="is_remove" id="is_remove_unit_bisnis" value="0">
                                </div>
                                <div class="card-footer text-center" style="padding: 0.2rem;">
                                    <button type="button" class="btn btn-outline-primary btn-sm" id="choose_image_unit_bisnis"><i class="fas fa-upload"></i>
                                        Pilih</button>
                                    &emsp;
                                    <button type="button" id="remove_image_unit_bisnis" class="btn btn-outline-danger btn-sm"><i class="fas fa-times"></i> Hapus</button>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-8">
                            <div class="form-group mb-3">
                                <label>Kategori :</label>
                                <select id="kategori" name="kategori" class="form-control form-control-sm">
                                    <option value="">--Pilih--</option>
                                    <option value="Kolam Renang">Kolam Renang</option>
                                    <option value="Lapangan Basket">Lapangan Basket</option>
                                    <option value="Lapangan Tenis">Lapangan Tenis</option>
                                    <option value="Komunal Space">Komunal Space</option>
                                </select>
                            </div>
                            <div class="form-group mb-3">
                                <label>Nama Unit :</label>
                                <input id="name_unit" name="name_unit" class="form-control form-control-sm" type="text" placeholder="Nama Unit Bisnis">
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label>Jenis Harga :</label>
                                        <select id="jenis_harga" name="jenis_harga" class="form-control form-control-sm">
                                            <option value="">--Pilih--</option>
                                            <option value="Per Jam">Per Jam</option>
                                            <option value="Kedatangan">Kedatangan</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label>Status Booking :</label>
                                        <select id="status_booking" name="status_booking" class="form-control form-control-sm">
                                            <option value="">--Pilih--</option>
                                            <option value="Aktif">Aktif</option>
                                            <option value="Non Aktif">Non Aktif</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row" id="divHargaPerjam" style="display: none;">
                        <div class="col-md-4">
                            <div class="card mb-3">
                                <div class="card-header" style="padding: 0.5rem;">
                                    <b>Harga Perjam : WARGA </b>
                                </div>
                                <div class="card-body" style="padding: 0.5rem;">
                                    <div class="form-group">
                                        <label>06.00 - 17.00 :</label> &emsp; <b class="text-danger">FREE</b>
                                    </div>
                                    <div class="form-group">
                                        <label>17.00 - 21.00 :</label>
                                        <div class="form-group mb-3">
                                            <label><b>Weekday</b> :</label>
                                            <input id="harga_warga_1721_weekday" name="harga_warga_1721_weekday" class="form-control form-control-sm format-number-rp" type="text" placeholder="">
                                        </div>
                                        <div class="form-group mb-3">
                                            <label><b>Weekend</b> :</label>
                                            <input id="harga_warga_1721_weekend" name="harga_warga_1721_weekend" class="form-control form-control-sm format-number-rp" type="text" placeholder="">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-8">
                            <div class="card mb-3">
                                <div class="card-header" style="padding: 0.5rem;">
                                    <b>Harga Perjam : NON WARGA / UMUM </b>
                                </div>
                                <div class="card-body" style="padding: 0.5rem;">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>06.00 - 17.00 :</label>
                                                <div class="form-group mb-3">
                                                    <label><b>Weekday</b> :</label>
                                                    <input id="harga_umum_0617_weekday" name="harga_umum_0617_weekday" class="form-control form-control-sm format-number-rp" type="text" placeholder="">
                                                </div>
                                                <div class="form-group mb-3">
                                                    <label><b>Weekend</b> :</label>
                                                    <input id="harga_umum_0617_weekend" name="harga_umum_0617_weekend" class="form-control form-control-sm format-number-rp" type="text" placeholder="">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>17.00 - 21.00 :</label>
                                                <div class="form-group mb-3">
                                                    <label><b>Weekday</b> :</label>
                                                    <input id="harga_umum_1721_weekday" name="harga_umum_1721_weekday" class="form-control form-control-sm format-number-rp" type="text" placeholder="">
                                                </div>
                                                <div class="form-group mb-3">
                                                    <label><b>Weekend</b> :</label>
                                                    <input id="harga_umum_1721_weekend" name="harga_umum_1721_weekend" class="form-control form-control-sm format-number-rp" type="text" placeholder="">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row" id="divHargaKedatangan" style="display: none;">
                        <div class="col-md-4">
                            <div class="card mb-3">
                                <div class="card-header" style="padding: 0.5rem;">
                                    <b>Harga Kedatangan : WARGA </b>
                                </div>
                                <div class="card-body" style="padding: 0.5rem;">
                                    <div class="form-group">
                                        <label>06.00 - 17.00 :</label> &emsp; <b class="text-danger">FREE</b>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-8">
                            <div class="card mb-3">
                                <div class="card-header" style="padding: 0.5rem;">
                                    <b>Harga Kedatangan : NON WARGA / UMUM </b>
                                </div>
                                <div class="card-body" style="padding: 0.5rem;">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Membership :</label>
                                                <div class="form-group mb-3">
                                                    <label><b>4 x pertemuan</b> :</label>
                                                    <input id="harga_membership_4x" name="harga_membership_4x" class="form-control form-control-sm format-number-rp" type="text" placeholder="">
                                                </div>
                                                <div class="form-group mb-3">
                                                    <label><b>8 x pertemuan</b> :</label>
                                                    <input id="harga_membership_8x" name="harga_membership_8x" class="form-control form-control-sm format-number-rp" type="text" placeholder="">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Non Member :</label>
                                                <div class="form-group mb-3">
                                                    <label><b>Per Orang</b> :</label>
                                                    <input id="harga_non_member" name="harga_non_member" class="form-control form-control-sm format-number-rp" type="text" placeholder="">
                                                </div>
                                                <div class="form-group mb-3">
                                                    <label><b>Paket Khusus Tamu Warga</b> :</label>
                                                    <input id="harga_tamu_warga" name="harga_tamu_warga" class="form-control form-control-sm format-number-rp" type="text" placeholder="">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </form>
            </div>
            <div class="modal-footer">
                <button type="button" id="modalBtnSave" class="btn btn-primary"><i class="far fa-save"></i> Simpan</button>
                <button class="btn btn-light-dark" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

@endsection

@section('script_admin')
    {{ assets_js_back('showChangeImage') }}
    {{ assets_js_back('unit_bisnis') }}
@endsection
