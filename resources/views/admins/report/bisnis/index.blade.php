@extends('layouts.master_admins')

@section('title_admin', 'Laporan Unit Bisnis')

@section('breadcrumb_admin')
    <li class="breadcrumb-item" aria-current="page">Laporan</li>
    <li class="breadcrumb-item active" aria-current="page">Laporan Unit Bisnis</li>
@endsection

@section('content_admin')

    <div class="col-xl-12 col-lg-12 col-sm-12 layout-spacing">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Tanggal Awal:</label>
                            <input type="date" id="awal" class="form-control" value="{{ date('Y-m-01') }}">
                        </div>
                        
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Tanggal Akhir:</label>
                            <input type="date" id="akhir" class="form-control" value="{{ date('Y-m-t') }}">
                        </div>
                        
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Paid By:</label>
                            <select class="form-control" id="payment">
                                <option value="">- All Payment - </option>
                                @foreach($method as $m)
                                <option value="{{ $m->payment_method }}">{{ $m->payment_method }}</option>
                                @endforeach
                            </select>

                        </div>
                        
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <button id="btn-filter" class="btn btn-success btn-report"><i class="fa fa-filter"></i> Filter</button>
                        
                            <button id="btn-print-kas" class="btn btn-warning btn-report"><i class="fa fa-file"></i> Print Detail Kas Masuk</button>
                        
                                
                        </div>
                        
                    </div>
                    
                </div>
            </div>
        </div>
        
        <div class="card mt20">
            
            <div class="card-body" style="margin-top: -20px;">
                    <div class="widget-content widget-content-area br-8 mt-10">
                        <div class="table-responsive" id="divTables">
                            <table id="report-table" class="table table-striped table-bordered table-hover table-custom"
                            style="width:100%">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Action</th>
                                        <th>Date</th>
                                        <th>Invoice</th>
                                        <th>User</th>
                                        <th>Transaction Name</th>
                                        <th>Book. Date</th>
                                        <th>Book. Time</th>
                                        <th>Amount</th>
                                        <th>Status</th>
                                        <th>Payment</th>
                                        <th>Paid At</th>
                                        
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
