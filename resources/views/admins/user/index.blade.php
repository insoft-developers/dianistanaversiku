@extends('layouts.master_admins')

@section('title_admin', 'Data User')

@section('breadcrumb_admin')
    <li class="breadcrumb-item" aria-current="page">Master Data</li>
    <li class="breadcrumb-item active" aria-current="page">Data User</li>
@endsection

@section('content_admin')

    <div class="col-xl-12 col-lg-12 col-sm-12 layout-spacing">
        <div class="card">
            <div class="card-body">
                <button onclick="addData()" class="btn btn-light-success mb-2 me-4 _effect--ripple waves-effect waves-light"><i class="fa fa-plus"></i> Add User Data</button>
                    <div class="widget-content widget-content-area br-8 mt-10">
                        <div class="table-responsive" id="divTables">
                            <table id="listTable" class="table table-striped table-bordered table-hover table-custom"
                            style="width:100%">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Action</th>
                                        <th>Name</th>
                                        <th>Username</th>
                                        <th>Email</th>
                                        <th>WA</th>
                                        <th>Level</th>
                                        <th>Status</th>
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
                                            <div class="form-group mt15 mb-3">
                                                <label>Judul Banner :</label>
                                                <input id="title" name="title" class="form-control" type="text" placeholder="Judul Banner Iklan">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group mt15">
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
        </div>
    </div>

    <div class="modal fade bd-example-modal-xl" id="modal-tambah" tabindex="-1" role="dialog" aria-labelledby="myExtraLargeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
          <div class="modal-content">
            <form id="form-tambah" method="POST" action="{{ url('backdata/user') }}" enctype="multipart/form-data">
                {{ csrf_field() }} {{ method_field('POST') }}
              <div class="modal-header">
                  <h5 class="modal-title" id="myExtraLargeModalLabel">Extra Large</h5>
                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                    <svg aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                  </button>
              </div>
              <div class="modal-body">
                <div class="row">
                    <div class="col-md-4">
                        <div class="card">
                            <div class="card-body">
                                <div class="form-group mt15">
                                    <label>Name:</label>
                                    <input type="text" name="name" id="name" class="form-control" placeholder="enter your name">
                                </div>
                                <div class="form-group mt15">
                                    <label>Username:</label>
                                    <input type="text" name="username" id="username" class="form-control" placeholder="enter your username">
                                </div>
                                <div class="form-group mt15">
                                    <label>Email:</label>
                                    <input type="email" name="email" id="email" class="form-control" placeholder="enter your email">
                                </div>
                                <div class="form-group mt15">
                                    <label>Password:</label>
                                    <input type="password" name="password" id="password" class="form-control" placeholder="enter your password">
                                </div>
                                <div class="form-group mt15">
                                    <label>Gender:</label>
                                    <select name="jenis_kelamin" id="jenis_kelamin" class="form-control">
                                        <option value="">- Select Gender - </option>
                                        <option value="Laki-laki">Laki-Laki</option>
                                        <option value="Perempuan">Perempuan</option>
                                    </select>
                                </div>
                                <div class="form-group mt15">
                                    <label>Whatsapp Number:</label>
                                    <input type="text" name="no_hp" id="no_hp" class="form-control" placeholder="ex: +6282165174433">
                                </div>
                                <div class="form-group mt15">
                                    <label>Level:</label>
                                    <select name="level" id="level" class="form-control">
                                        <option value="">- Select Level - </option>
                                        <option value="user">User</option>
                                        <option value="guest">Guest</option>
                                    </select>
                                </div>
                                <div class="form-group mt15">
                                    <label>Status:</label>
                                    <select name="is_active" id="is_active" class="form-control">
                                        <option value="">- Select Status - </option>
                                        <option value="1">Active</option>
                                        <option value="0">Not Active</option>
                                    </select>
                                </div>
                            </div>
                        </div>
        
                    </div>
                    <div class="col-md-4">
                        <div class="card">
                            <div class="card-body">
                                <div class="form-group mt15">
                                    <label>Penyelia:</label>
                                    <select name="penyelia" id="penyelia" class="form-control">
                                        <option value="">- Select Penyelia - </option>
                                        <option value="SDP">SDP</option>
                                        <option value="DMSI">DMSI</option>
                                    </select>
                                </div>
                                <div class="form-group mt15">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <label>BLOK:</label>
                                            <input type="text" name="blok" id="blok" class="form-control" placeholder="enter blok">
                                        </div>
                                        <div class="col-md-6">
                                            <label>House No. :</label>
                                            <input type="text" name="nomor_rumah" id="nomor_rumah" class="form-control" placeholder="enter house number">
                                        </div>
                                    </div>
                                    
                                </div>
                                <div class="form-group mt15">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <label>Daya Listrik:</label>
                                            <input type="text" name="daya_listrik" id="daya_listrik" class="form-control" placeholder="daya listrik">
                                        </div>
                                        <div class="col-md-6">
                                            <label>Luas Tanah :</label>
                                            <input type="text" name="luas_tanah" id="luas_tanah" class="form-control" placeholder="luas tanah">
                                        </div>
                                    </div>
                                    
                                </div>
                                <div class="form-group mt15">
                                    <label>Iuran Bulanan:</label>
                                    <input type="text" name="iuran_bulanan" id="iuran_bulanan" class="form-control" placeholder="enter iuran bulanan">
                                </div>
                                <div class="form-group mt15">
                                    <label>Whatsapp Emergency:</label>
                                    <input type="text" name="whatsapp_emergency" id="whatsapp_emergency" class="form-control" placeholder="ex: +6282165174635">
                                </div>
                                <div class="form-group mt15">
                                    <label>Description:</label>
                                    <textarea name="keterangan" id="keterangan" class="form-control" placeholder="Enter Description"></textarea>
                                </div>
                                <div class="form-group mt15">
                                    <label>Alamat Surat Menyurat:</label>
                                    <textarea name="alamat_surat_menyurat" id="alamat_surat_menyurat" class="form-control" placeholder="Enter Address"></textarea>
                                </div>
                                <div class="form-group mt15">
                                    <label>Office Phone Number:</label>
                                    <input type="text" name="nomor_telepon_rumah" id="nomor_telepon_rumah" class="form-control" placeholder="ex: +617969900">
                                </div>
                                <div class="form-group mt15">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <label>PDAM ID:</label>
                                            <input type="text" name="id_pelanggan_pdam" id="id_pelanggan_pdam" class="form-control" placeholder="PDAM ID">
                                        </div>
                                        <div class="col-md-6">
                                            <label>PLN Meter :</label>
                                            <input type="text" name="nomor_meter_pln" id="nomor_meter_pln" class="form-control" placeholder="PLN Meter">
                                        </div>
                                    </div>
                                    
                                </div>
                                <div class="form-group mt15">
                                    <label>Mulai menempati:</label>
                                    <input type="date" name="mulai_menempati" id="mulai_menempati" class="form-control">
                                </div>
                            </div>
                        </div>
                        
                    </div>
                    <div class="col-md-4">
                        <div class="card">
                            <div class="card-body">
                                <label>Profile Image</label>
                                <center><img id="profile-image" class="profile-image" src="{{ asset('template/images/profil_icon.png') }}"></center>
                                <div onclick="remove_foto()" style="display: none;" id="remove-profile-image"><i class="fa fa-trash"></i></div>
                                <input style="display: none;" type="file" id="image" name="image" accept=".jpeg, .jpg, .png">
                            </div>
                        </div>
                        
                    </div>
                </div>
                
                
              </div>
              <div class="modal-footer">
                  <button type="button" class="btn btn-light-dark" data-bs-dismiss="modal">Cancel</button>
                  <button type="submit" class="btn btn-primary">Save</button>
              </div>
            </form>
          </div>
        </div>
      </div>

@endsection

@section('script_admin')
    <script src="{{ asset('assets/plugins/tinymce/tinymce.min.js') }}"></script>

    {{ assets_js_back('showChangeImage') }}
@endsection
