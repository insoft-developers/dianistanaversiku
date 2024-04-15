@extends('layouts.master_admins')

@section('title_admin', 'Payment List')

@section('breadcrumb_admin')
    <li class="breadcrumb-item" aria-current="page">Transaction</li>
    <li class="breadcrumb-item active" aria-current="page">Payment</li>
@endsection

@section('content_admin')

    <div class="col-xl-12 col-lg-12 col-sm-12 layout-spacing">
        <div class="card">
            <div class="card-body">
                <button onclick="addData()" class="btn btn-light-success mb-2 me-4 _effect--ripple waves-effect waves-light"><i class="fa fa-plus"></i> Add Payment Data</button>
                    <div class="widget-content widget-content-area br-8 mt-10">
                        <div class="table-responsive" id="divTables">
                            <table id="listTable" class="table table-striped table-bordered table-hover table-custom"
                            style="width:100%">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Action</th>
                                        <th>Date</th>
                                        <th>Payment Name&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
                                        <th>Type</th>
                                        <th>Due Date</th>
                                        <th>Periode</th>
                                        <th>Amount</th>
                                        <th>Bill To</th>
                                        
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>

                    </div>    
                
            </div>
        </div>
    </div>

    <div class="modal fade bd-example-modal-xl" id="modal-tambah" role="dialog" aria-labelledby="myExtraLargeModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
          <div class="modal-content">
            <form id="form-tambah" method="POST" action="{{ url('backdata/pembayaran') }}" enctype="multipart/form-data">
                {{ csrf_field() }} {{ method_field('POST') }}
              <div class="modal-header">
                  <h5 class="modal-title" id="myExtraLargeModalLabel"></h5>
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
                                    <label>Payment Name:</label>
                                    <input type="text" name="payment_name" id="payment_name" class="form-control" placeholder="enter payment title">
                                </div>
                                <div class="form-group mt15">
                                    <label>Description:</label>
                                    <textarea name="payment_desc" id="payment_desc" class="form-control" placeholder="enter description"></textarea>
                                </div>
                                <div class="form-group mt15">
                                    <label>Payment Type:</label>
                                    <select name="payment_type" id="payment_type" class="form-control">
                                        <option value="">- Select Payment Type - </option>
                                        <option value="1">Iuran Bulanan Komplek</option>
                                        <option value="2">Pembayaran Rutin</option>
                                        <option value="3">Sekali Bayar</option>
                                    </select>
                                </div>
                                <div class="form-group mt15">
                                    <label>Due Date:</label>
                                    <input type="date" name="due_date" id="due_date" class="form-control" placeholder="enter due date">
                                </div>
                                <div class="form-group mt15">
                                    <label>Period:</label>
                                    <input type="text" name="periode" id="periode" class="form-control" placeholder="ex: 04-2004">
                                </div>
                                <div class="form-group mt15">
                                    <label>Payment Amount:</label>
                                    <input type="number" name="payment_amount" id="payment_amount" class="form-control" placeholder="enter amount">
                                </div>
                                <div class="form-group mt15">
                                    <label>Bill To:</label>
                                    <select style="width: 100%;" name="payment_dedication" id="payment_dedication" class="form-control select-custom">
                                        <option value="">- Select Bill To - </option>
                                        <option value="-1">Bill To All</option>
                                        @foreach($user as $u)
                                        <option value="{{ $u->id }}">{{ $u->name }} - {{ $u->level }}</option>
                                        @endforeach
                                    </select>
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
                  <h5 class="modal-title" id="myExtraLargeModalLabel">Payment Summary</h5>
                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                    <svg aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                  </button>
              </div>
              <div class="modal-body" id="detail-content">
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-light-dark" data-bs-dismiss="modal">Oke</button>
              </div>
          </div>
        </div>
      </div>

      <div class="modal fade bd-example-modal-xl" id="modal-payment" tabindex="-1" role="dialog" aria-labelledby="myExtraLargeModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
          <div class="modal-content">
            <form id="form-payment-admin" method="POST">
                @csrf
              <div class="modal-header">
                  <h5 class="modal-title" id="myExtraLargeModalLabel">Add Payment</h5>
                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                    <svg aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                  </button>
              </div>
              
              <div class="modal-body">
                
                    <input type="hidden" id="payment_id_admin" name="payment_id_admin">
                    <div class="form-group mt20">
                        <label>Payment Name</label>
                        <input type="text" class="form-control agak-terang" id="payment_name_admin" name="payment_name_admin" readonly>
                    </div>
                    <div class="form-group mt20">
                        <label>Payment Type</label>
                        <input type="text" class="form-control agak-terang" id="payment_type_admin" name="payment_type_admin" readonly>
                        <input type="hidden" id="payment_type_hidden">
                    </div>
                    <div class="form-group mt15">
                        <label>Bill To:</label>
                        <select style="width: 100%;" name="payment_dedication_admin" id="payment_dedication_admin" class="form-control select-custom">
                            <option value="">- Select Bill To - </option>
                            <option value="-1">Bill To All</option>
                            @foreach($user as $u)
                            <option value="{{ $u->id }}">{{ $u->name }} - {{ $u->level }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group mt20">
                        <label>Payment Amount</label>
                        <input type="text" class="form-control agak-terang" id="payment_amount_admin" name="payment_amount_admin" readonly>
                    </div>
                
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-light-dark" data-bs-dismiss="modal">Cancel</button>
                  <button id="btn-pay-data" type="submit" class="btn btn-primary">Pay Now</button>
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
