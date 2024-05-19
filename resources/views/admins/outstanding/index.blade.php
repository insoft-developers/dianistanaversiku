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
                            <div class="table-responsive">
                            <table id="listTable" class="table table-striped table-bordered table-hover table-custom"
                            style="width:100%">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>#</th>
                                        <th>User</th>
                                        <th>Blok</th>
                                        <th>No.Rumah</th>
                                        <th>Penyelia</th>
                                        <th>Saldo Awal</th>
                                        <th>Denda</th>
                                        <th>Iuran</th>
                                        <th>Adjust</th>
                                        <th>Tg bln ini</th>
                                        <th>Next Bills</th>
                                        <th>Last Payment</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                          </div>

                    </div>    
                
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
                  <button type="button" class="btn btn-light-dark" data-bs-dismiss="modal">Cancel</button>
              </div>
          </div>
        </div>
      </div>

      <div class="modal fade bd-example-modal-xl" id="modal-adjustment" tabindex="-1" role="dialog" aria-labelledby="myExtraLargeModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
          <div class="modal-content">
              <div class="modal-header">
                  <h5 class="modal-title" id="myExtraLargeModalLabel">Adjustment</h5>
                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                    <svg aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                  </button>
              </div>
              <div class="modal-body">
                <input type="hidden" id="userid">
                <div class="form-group mt20">
                    <label>Description</label>
                    <textarea class="form-control" id="description" placeholder="enter description here..."></textarea>
                </div>
                <div class="form-group mt20">
                    <label>Type</label>
                    <select id="type" class="form-control">
                        <option value=""> - Select- </option>
                        <option value="1">Addition (Penambahan)</option>
                        <option value="2">Substraction (Pengurangan)</option>
                    </select>
                </div>
                <div class="form-group mt20">
                    <label>Amount</label>
                    <input type="number" class="form-control" id="amount" placeholder="enter amount...">
                </div>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-light-dark" data-bs-dismiss="modal">Cancel</button>
                <button id="btn-save-data" type="submit" class="btn btn-primary">Save</button>
              </div>
          </div>
        </div>
      </div>

@endsection

@section('script_admin')
    <script src="{{ asset('assets/plugins/tinymce/tinymce.min.js') }}"></script>

    {{ assets_js_back('showChangeImage') }}
@endsection
