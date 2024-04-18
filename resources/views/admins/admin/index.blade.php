@extends("layouts.master_admins")

@section("title_admin","Data Admin")

@section("breadcrumb_admin")    
<li class="breadcrumb-item active" aria-current="page">Data Admins</li>
@endsection

@section("content_admin")

<div class="col-xl-12 col-lg-12 col-sm-12 layout-spacing">
    <div class="card">
        <div class="card-body">
            <div class="simple-tab">
                <ul class="nav nav-tabs" id="myTab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="home-tab-icon" data-bs-toggle="tab" data-bs-target="#home-tab-icon-pane" type="button" role="tab" aria-controls="home-tab-icon-pane" aria-selected="true">
                            <i class="far fa-list-alt text-success"></i> Data Pengguna
                        </button>
                    </li>
                    <li style="display: none;" class="nav-item" role="presentation">
                        <button class="nav-link" id="profile-tab-icon" data-bs-toggle="tab" data-bs-target="#profile-tab-icon-pane" type="button" role="tab" aria-controls="profile-tab-icon-pane" aria-selected="false">
                            <i class="fas fa-trash-alt text-danger"></i> Trash Data Pengguna
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
                                        <th>Nama</th>
                                        <th>Username</th>
                                        <th>Level</th>
                                        <th>Email</th>
                                        <th>No Telp</th>
                                        <th>Opsi</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
                    </div>
                    <div style="display: none;" class="tab-pane fade" id="profile-tab-icon-pane" role="tabpanel" aria-labelledby="profile-tab-icon" tabindex="0">
                        <div class="widget-content widget-content-area br-8 mt-1">
                            <table id="listTableTrash" class="table table-striped table-bordered table-hover" style="width:100%">
                                <thead>
                                    <tr>
                                        <th class="text-danger">No</th>
                                        <th class="text-danger">Nama</th>
                                        <th class="text-danger">Username</th>
                                        <th class="text-danger">Level</th>
                                        <th class="text-danger">Email</th>
                                        <th class="text-danger">No Telp</th>
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
                    <div class="row">
                        <div class="col-xl-6">
                            <div class="form-group mb-3">
                                <label>Nama :</label>
                                <input id="name" name="name" class="form-control form-control-sm" type="text" placeholder="Nama">
                            </div>
                            <div class="form-group mb-3">
                                <label>Email :</label>
                                <input id="email" name="email" class="form-control form-control-sm" type="text" placeholder="Email">
                            </div>
                            <div class="form-group mb-3">
                                <label>No Telp :</label>
                                <input id="no_telp" name="no_telp" class="form-control form-control-sm" type="text" placeholder="No Telp">
                            </div>
                            <div class="form-group mb-3">
                                <label>Level :</label>
                                <select id="level" name="level" class="form-control form-control-sm">
                                    <option value="">--Pilih--</option>
                                    <option value="admin">Admin</option>
                                    <option value="finance">Finance</option>
                                    <option value="manager">manager</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-xl-6">
                            <div class="form-group mb-3">
                                <label>Username :</label>
                                <input id="username" name="username" class="form-control form-control-sm" type="text" placeholder="Username">
                            </div>
                            <div class="form-group mb-3">
                                <label>Password :</label>
                                <input id="password" name="password" class="form-control form-control-sm" type="password" placeholder="Password">
                            </div>
                            <div class="form-group mb-3">
                                <label>Confirm Password :</label>
                                <input id="confirm_password" name="confirm_password" class="form-control form-control-sm" type="password" placeholder="Confirm Password">
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

@section("script_admin")
{{ assets_js_back("admins_data") }}
@endsection