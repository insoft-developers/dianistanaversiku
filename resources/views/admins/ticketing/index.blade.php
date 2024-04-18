@extends('layouts.master_admins')

@section('title_admin', 'Ticketing List')

@section('breadcrumb_admin')
    <li class="breadcrumb-item" aria-current="page">Transaction</li>
    <li class="breadcrumb-item active" aria-current="page">Ticketing List</li>
@endsection

@section('content_admin')

    <div class="col-xl-12 col-lg-12 col-sm-12 layout-spacing">
        
        <div class="card">
            
            <div class="card-body">
                <div class="card">
                    <div class="card-header">
                        Filtered Data By:
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Department :</label>
                                    <select class="form-control input-filter" id="department-filter">
                                        <option value=""> - All Department - </option>
                                        @foreach($category as $cat)
                                            <option value="{{ $cat->id }}">{{ $cat->category_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Priority :</label>
                                    <select class="form-control input-filter" id="priority-filter">
                                        <option value=""> - All Priority - </option>
                                        <option value="Low">Low</option>
                                        <option value="Medium">Medium</option>
                                        <option value="High">High</option>
                                        <option value="Critical">Critical</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <button onclick="filter_ticketing_data()" class="btn btn-info btn-filter-ticketing"><i class="fa fa-filter"></i> Filter</button>
                            </div>
                        </div>
                        
                    </div>
                </div>
                    <div class="widget-content widget-content-area br-8 mt-10">
                        <div class="table-responsive" id="divTables">
                            
                            
                            <table id="listTable" class="table table-striped table-bordered table-hover table-custom"
                            style="width:100%">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Action</th>
                                        <th>Status</th>
                                        <th>Date</th>
                                        <th>Ticket Number</th>
                                        <th>User</th>
                                        <th>Subject&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
                                        <th>Department</th>
                                        <th>Priority</th>
                                        <th>Attachment</th>
                                        
                                        <th>Last Update</th>
                                        
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
                  <h5 class="modal-title" id="myExtraLargeModalLabel">Detail Title</h5>
                  <button onclick="open_payment()" class="btn btn-sm btn-info btn-payment-ticketing"><i class="fa fa-list"></i> Payment List</button>
                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                    <svg aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                  </button>
              </div>
              <div class="modal-body">
                <form id="form-reply" method="POST" enctype="multipart/form-data">
                    @csrf
                <div id="detail-content"></div>
                </form>
              </div>
              <div class="modal-footer">
                  <button type="button" class="btn btn-light-dark" data-bs-dismiss="modal">Cancel</button>
                  <button id="btn-on-hold" class="btn btn-warning"><i class="fa fa-exclamation"></i> On Hold</button>
                  <button id="btn-resolved" class="btn btn-success"><i class="fa fa-check"></i> Resolved</button>
              </div>
          </div>
        </div>
      </div>

      <div class="modal fade bd-example-modal-xl" id="modal-payment-ticketing" tabindex="-1" role="dialog" aria-labelledby="myExtraLargeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
          <div class="modal-content">
              <div class="modal-header">
                  <h5  id="myExtraLargeModalLabel">Payment List</h5>
                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                    <svg aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                  </button>
              </div>
              <div class="modal-body">
                <div class="card">
                    <div class="card-header">
                        <a id="new_payment_text" href="javascript:void(0);"><i class="fa fa-plus"></i> Add New Payment</a> <a style="display: none;" id="cancel_payment_text" href="javascript:void(0);"><i class="fa fa-close"></i> Cancel</a>
                    </div>
                    
                    <div class="card-body" id="form-section" style="display: none;">
                        <form id="form-payment-ticketing" method="POST">
                        <div class="row">
                                @csrf
                            <input type="hidden" id="payment_dedication" name="payment_dedication">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Payment Name:</label>
                                    <input type="text" class="form-control" id="payment_name" name="payment_name" placeholder="enter payment name">
                                </div>
                                <div class="form-group mt20">
                                    <label>Payment Description:</label>
                                    <textarea class="form-control" id="payment_desc" name="payment_desc" placeholder="enter payment description"></textarea>
                                </div>
                                <div class="form-group mt20">
                                    <label>Payment Type:</label>
                                    <select class="form-control" id="payment_type" name="payment_type">
                                        <option value=""> - Select - </option>
                                        <option value="2">Pembayaran Rutin</option>
                                        <option value="3">Sekali Bayar</option>

                                    </select>
                                </div>
                                
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Due Date:</label>
                                    <input type="date" class="form-control" id="due_date" name="due_date" placeholder="enter due date">
                                </div>
                                <div class="form-group mt20">
                                    <label>Period:</label>
                                    <input type="text" class="form-control" id="periode" name="periode" placeholder="ex: 03-2024">
                                </div>
                                <div class="form-group mt20">
                                    <label>Payment Amount:</label>
                                    <input type="text" class="form-control" id="payment_amount" name="payment_amount" placeholder="enter amount">
                                </div>

                            </div>
                            
                            
                        </div>
                        <button type="submit" class="btn btn-success btn-sm mt30">Save</button>
                    </form>
                    </div>
                    
                
                </div>
                <div id="payment-content" class="mt40"></div>
              </div>
              <div class="modal-footer">
                  <button type="button" class="btn btn-light-dark" data-bs-dismiss="modal">Cancel</button>
              </div>
          </div>
        </div>
      </div>

@endsection

@section('script_admin')
    <script src="{{ asset('assets/plugins/tinymce/tinymce.min.js') }}"></script>

    {{ assets_js_back('showChangeImage') }}
@endsection
