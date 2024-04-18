@extends('layouts.master_admins')

@section('title_admin', 'Banner Iklan')

@section('breadcrumb_admin')
    <li class="breadcrumb-item" aria-current="page">Master Data</li>
    <li class="breadcrumb-item active" aria-current="page">Banner Iklan</li>
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
                                <i class="far fa-list-alt text-success"></i> Data Banner Iklan
                            </button>
                        </li>
                        <li style="display: none;" class="nav-item" role="presentation">
                            <button class="nav-link" id="profile-tab-icon" data-bs-toggle="tab"
                                data-bs-target="#profile-tab-icon-pane" type="button" role="tab"
                                aria-controls="profile-tab-icon-pane" aria-selected="false">
                                <i class="fas fa-trash-alt text-danger"></i> Trash Data Banner Iklan
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
                                                <th>Tgl Input</th>
                                                <th>Gambar Banner</th>
                                                <th>Judul Banner</th>
                                                <th>Link To</th>
                                            </tr>
                                        </thead>
                                        <tbody></tbody>
                                    </table>
                                </div>

                                <div class="card" id="divForm" style="display: none;">
                                    <div class="card-header">
                                        <b class="card-title">Form Tambah Data Banner Iklan</b>
                                        <a href="javascript:void(0);" class="bs-tooltip close-form text-danger mb-2 float-end" data-bs-toggle="tooltip" data-bs-placement="top" data-original-title="Tutup" aria-label="Tutup" data-bs-original-title="Tutup"><i class="far fa-times-circle"></i></a>
                                    </div>
                                    <div class="card-body">
                                        <form id="formData" action="#" method="POST" class="mt-0">
                                            @method('PUT')
                                            <div class="row">
                                                <div class="col-md-3">
                                                    <div class="card mb-3">
                                                        <div class="card-header" style="padding: 0.2rem;">
                                                            <b>Gambar Banner</b>
                                                        </div>
                                                        <div class="card-body text-center" style="padding: 0.2rem;">
                                                            <a href="javascript:void(0);" id="showImg_banner" title="Klik untuk Zoom">
                                                                <img src="{{ assetImg_thumbnail() }}" class="card-img" style="width: 200px; height: 120px;" alt="Gambar Banner">
                                                            </a>
                                                            <input type="file" name="image" id="file_image_banner" style="display: none;">
                                                            <input type="hidden" name="is_remove" id="is_remove_banner" value="0">
                                                        </div>
                                                        <div class="card-footer text-center" style="padding: 0.2rem;">
                                                            <button type="button" class="btn btn-outline-primary btn-sm" id="choose_image_banner"><i class="fas fa-upload"></i>
                                                                Pilih</button>
                                                            &emsp;
                                                            <button type="button" id="remove_image_banner" class="btn btn-outline-danger btn-sm"><i class="fas fa-times"></i> Hapus</button>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-9">
                                                    <div class="form-group mb-3">
                                                        <label>Judul Banner :</label>
                                                        <input id="title" name="title" class="form-control" type="text" placeholder="Judul Banner Iklan">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label>Link Terkait :</label>
                                                <input id="link_terkait" name="link_terkait" class="form-control" type="text" placeholder="Masukkan link">
                                            </div>
                                        </form>
                                    </div>
                                    <div class="card-footer">
                                        <button type="button" class="btn btn-outline-primary" id="btnSave"><i class="fas fa-save"></i> Simpan Data</button>
                                        &emsp;
                                        <button type="button" class="btn btn-outline-danger close-form"><i class="fas fa-times-circle"></i> Batal</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div style="display: none;" class="tab-pane fade" id="profile-tab-icon-pane" role="tabpanel"
                            aria-labelledby="profile-tab-icon" tabindex="0">
                            <div class="widget-content widget-content-area br-8 mt-1">
                                <div class="table-responsive">
                                    <table id="listTableTrash" class="table table-striped table-bordered table-hover"
                                        style="width:100%">
                                        <thead>
                                            <tr>
                                                <th class="text-danger">No</th>
                                                <th class="text-secondary">Opsi</th>
                                                <th class="text-danger">Tgl Input</th>
                                                <th class="text-danger">Gambar Banner</th>
                                                <th class="text-danger">Judul Banner</th>
                                                <th class="text-danger">Content</th>
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

@endsection

@section('script_admin')
    <script src="{{ asset('assets/plugins/tinymce/tinymce.min.js') }}"></script>

    {{ assets_js_back('showChangeImage') }}
    {{ assets_js_back('contentTinymce') }}
    {{ assets_js_back('banner_iklan') }}
@endsection
