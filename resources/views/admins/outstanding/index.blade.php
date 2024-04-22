@extends('layouts.master_admins')

@section('title_admin', 'Outstanding Bills')

@section('breadcrumb_admin')
    <li class="breadcrumb-item" aria-current="page">Transaction</li>
    <li class="breadcrumb-item active" aria-current="page">Outstanding Bills</li>
@endsection

@section('content_admin')

    <div class="col-xl-12 col-lg-12 col-sm-12 layout-spacing">
        <div class="card">
            <div class="card-body">
               
                    <div class="widget-content widget-content-area br-8 mt-10">
                        
                            <table id="listTable" class="table table-striped table-bordered table-hover table-custom"
                            style="width:100%">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Action</th>
                                        <th>User</th>
                                        <th>Amount</th>
                                        <th>Denda</th>
                                        <th>Total Outstanding</th>
                                        <th>Next Bills</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        

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
                                <input type="hidden" id="id" name="id">
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
                  <button id="btn-save-data" type="submit" class="btn btn-primary">Save</button>
              </div>
            </form>
          </div>
        </div>
      </div>


     
      <div class="modal fade bd-example-modal-xl" id="modal-detail" tabindex="-1" role="dialog" aria-labelledby="myExtraLargeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
          <div class="modal-content">
              <div class="modal-header">
                  <h5 class="modal-title" id="myExtraLargeModalLabel">Detail Outstanding Payment</h5>
                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                    <svg aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                  </button>
              </div>
              <div class="modal-body" id="detail-content">
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-light-dark" data-bs-dismiss="modal">OK</button>
              </div>
          </div>
        </div>
      </div>

@endsection

@section('script_admin')
    <script src="{{ asset('assets/plugins/tinymce/tinymce.min.js') }}"></script>

    {{ assets_js_back('showChangeImage') }}
@endsection
