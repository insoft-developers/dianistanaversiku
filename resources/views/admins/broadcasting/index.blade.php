@extends('layouts.master_admins')

@section('title_admin', 'Broad Casting')

@section('breadcrumb_admin')
    <li class="breadcrumb-item" aria-current="page">Utility</li>
    <li class="breadcrumb-item active" aria-current="page">Broad Casting</li>
@endsection

@section('content_admin')

    <div class="col-xl-12 col-lg-12 col-sm-12 layout-spacing">
        <div class="card">
            <div class="card-body">
                <button onclick="addData()" class="btn btn-light-success mb-2 me-4 _effect--ripple waves-effect waves-light"><i class="fa fa-plus"></i> Add New BroadCast</button>
                    <div class="widget-content widget-content-area br-8 mt-10">
                        <div class="table-responsive" id="divTables">
                            <table id="listTable" class="table table-striped table-bordered table-hover table-custom"
                            style="width:100%">
                                <thead>
                                
                                    <tr>
                                        <th>No</th>
                                        <th>Action</th>
                                        <th>Date</th>
                                        <th>Status</th>
                                        <th>Title</th>
                                        <th>Message</th>
                                        <th>Image</th>
                                        <th>Sender</th>
                                        <th>Receiver</th>
                                        <th>Send On</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>

                    </div>    
                
            </div>
        </div>
    </div>

    <div class="modal fade bd-example-modal-xl" id="modal-tambah" tabindex="-1" role="dialog" aria-labelledby="myExtraLargeModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
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
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-body">
                                <input type="hidden" id="id" name="id">
                               
                                <div class="form-group mt15">
                                    <label>Title:</label>
                                    <input type="text" name="title" id="title" class="form-control" placeholder="enter broadcast title">
                                </div>
                                
                                <div class="form-group mt15">
                                    <label>Image:</label>
                                    <input type="file" name="image" id="image" class="form-control" accept=".jpg, .jpeg, .png">
                                </div>
                                
                                <div class="form-group mt15">
                                    <label>Broadcast To:</label>
                                    <select style="width: 100%" name="user_id" id="user_id" class="form-control">
                                        <option value="">- Select - </option>
                                        <option value="-1">Send To ALL USERS</option>
                                        <option value="-2">Send To BLOK</option>
                                        @foreach($user as $u)
                                        <option value="{{ $u->id }}">{{ $u->name }} - {{ $u->level }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group mt15" id="blok-container" style="display: none;">
                                    <label>Blok:</label>
                                    <select name="blok" id="blok" class="form-control">
                                        <option value="">- Select - </option>
                                        @foreach($bloks as $blok)
                                        <option value="{{ $blok->id }}">{{ $blok->blok_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group mt15">
                                    <label>Message:</label>
                                    <textarea name="message" id="message" class="form-control" placeholder="enter your message"></textarea>
                                </div>
                                <div class="form-group mt15">
                                    <label>Send Date:</label>
                                    <input type="date" name="send_date" id="send_date" class="form-control" placeholder="date to send broadcast">
                                </div>
                                
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
                  <h5 class="modal-title" id="myExtraLargeModalLabel">Detail Title</h5>
                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                    <svg aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                  </button>
              </div>
              <div class="modal-body" id="detail-content">
              </div>
              <div class="modal-footer">
                  <button id="btn-print-detail" class="btn btn-primary"><i class="fa fa-print"></i> Print</button>
              </div>
          </div>
        </div>
      </div>

@endsection

@section('script_admin')
    <script src="{{ asset('assets/plugins/tinymce/tinymce.min.js') }}"></script>

    {{ assets_js_back('showChangeImage') }}
@endsection
