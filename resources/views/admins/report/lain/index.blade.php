@extends('layouts.master_admins')

@section('title_admin', 'Laporan Pendapatan Lain Lain')

@section('breadcrumb_admin')
    <li class="breadcrumb-item" aria-current="page">Laporan</li>
    <li class="breadcrumb-item active" aria-current="page">Laporan Pendapatan Lain Lain</li>
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
                    <div class="col-md-3">
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
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Penyelia:</label>
                            <select class="form-control" id="penyelia">
                                <option value="">- All Penyelia - </option>
                                <option value="SDP">SDP</option>
                                <option value="DMSI">DMSI</option>
                            </select>

                        </div>
                        
                    </div>
                    
                    <div class="col-md-8">
                        <div class="form-group">
                            <button id="btn-filter" class="btn btn-success btn-report"><i class="fa fa-filter"></i> Filter</button>
                            <button id="btn-export-accounting" class="btn btn-danger btn-report"><i class="fa fa-file-excel"></i> Export to Accounting System</button>
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
                                        <th>Paid At</th>
                                        
                                        <th>Invoice</th>
                                        <th>User</th>
                                        <th>Transaction Name</th>
                                        <th>Period</th>
                                        <th>Due Date</th>
                                        <th>Amount</th>
                                        <th>Status</th>
                                        <th>Payment</th>
                                        <th>Date</th>
                                        
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>

                    </div>    
                
            </div>
        </div>
    </div>

@endsection

@section('script_admin')
    <script src="{{ asset('assets/plugins/tinymce/tinymce.min.js') }}"></script>

    {{ assets_js_back('showChangeImage') }}
@endsection
