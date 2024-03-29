@extends("layouts.master_admins")

@section("title_admin","Penyelia")

@section("breadcrumb_admin")
<li class="breadcrumb-item" aria-current="page">Master Data</li>
<li class="breadcrumb-item" aria-current="page">Data Penyelia</li>
<li class="breadcrumb-item active" aria-current="page">Penyelia</li>
@endsection

@section("content_admin")

<div class="col-xl-12 col-lg-12 col-sm-12 layout-spacing">
    <div class="card">
        <div class="card-body">
            <div class="simple-tab">
                <ul class="nav nav-tabs" id="myTab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="home-tab-icon" data-bs-toggle="tab" data-bs-target="#home-tab-icon-pane" type="button" role="tab" aria-controls="home-tab-icon-pane" aria-selected="true">
                            <i class="far fa-list-alt text-success"></i> Data Penyelia
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="profile-tab-icon" data-bs-toggle="tab" data-bs-target="#profile-tab-icon-pane" type="button" role="tab" aria-controls="profile-tab-icon-pane" aria-selected="false">
                            <i class="fas fa-trash-alt text-danger"></i> Trash Data Penyelia
                        </button>
                    </li>
                </ul>
                
                <div class="tab-content" id="myTabContent">
                    <div class="tab-pane fade show active" id="home-tab-icon-pane" role="tabpanel" aria-labelledby="home-tab-icon" tabindex="0">
                        <div class="widget-content widget-content-area br-8 mt-1">
                            <table id="listTable" class="table table-striped table-bordered table-hover" style="width:100%">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Nama Penyelia</th>
                                        <th>No Telp</th>
                                        <th>Kategori Penyelia</th>
                                        <th>Opsi</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="profile-tab-icon-pane" role="tabpanel" aria-labelledby="profile-tab-icon" tabindex="0">
                        <div class="widget-content widget-content-area br-8 mt-1">
                            <table id="listTableTrash" class="table table-striped table-bordered table-hover" style="width:100%">
                                <thead>
                                    <tr>
                                        <th class="text-danger">No</th>
                                        <th class="text-danger">Nama Penyelia</th>
                                        <th class="text-danger">No Telp</th>
                                        <th class="text-danger">Kategori Penyelia</th>
                                        <th class="text-secondary">Opsi</th>
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

<!-- Modal -->
<div class="modal fade" id="modalForm" tabindex="-1" role="dialog" aria-labelledby="modalFormLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
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
                    <div class="form-group mb-3">
                        <label>Kategori :</label>
                        <select id="kategori_penyelia" name="kategori_penyelia" class="form-control form-control-sm">
                            <option value="">--Pilih--</option>
                            @forelse ($kategori as $val)
                                <option value="{{ $val->id }}">{{ $val->name_kategori }}</option>
                            @empty
                            @endforelse
                        </select>
                    </div>
                    <div class="form-group mb-3">
                        <label>Nama Penyelia :</label>
                        <input id="name_penyelia" name="name_penyelia" class="form-control form-control-sm" type="text" placeholder="Nama Penyelia">
                    </div>
                    <div class="form-group mb-3">
                        <label>No Telp :</label>
                        <input id="no_telp" name="no_telp" class="form-control form-control-sm" type="text" placeholder="No Telp Penyelia">
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

@section("script_admin")
{{ assets_js_back("penyelia") }}
@endsection