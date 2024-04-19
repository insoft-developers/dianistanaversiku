@extends('layouts.master_admins')

@section('title_admin', 'Booking Setting')

@section('breadcrumb_admin')
    <li class="breadcrumb-item" aria-current="page">Setting</li>
    <li class="breadcrumb-item active" aria-current="page">Booking Setting</li>
@endsection

@section('content_admin')

    <div class="col-xl-12 col-lg-12 col-sm-12 layout-spacing">
        <div class="card">
            <div class="card-body">
                <button onclick="addData()" class="btn btn-light-success mb-2 me-4 _effect--ripple waves-effect waves-light"><i class="fa fa-plus"></i> Add Booking Setting</button>
                    <div class="widget-content widget-content-area br-8 mt-10">
                        <div class="table-responsive" id="divTables">
                            <table id="listTable" class="table table-striped table-bordered table-hover table-custom"
                            style="width:100%">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Action</th>
                                        <th>Type</th>
                                        <th>Unit</th>
                                        <th>Day</th>
                                        <th>Date</th>
                                        <th>Time</th>
                                        <th>Status</th>
                        
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
        <div class="modal-dialog modal-lg" role="document">
          <div class="modal-content">
            <form id="form-tambah" method="POST" action="{{ url('backdata/booking_setting') }}" enctype="multipart/form-data">
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
                                    <label>Booking Type:</label>
                                    <select name="type" id="type" class="form-control">
                                        <option value="">- Select Type - </option>
                                        <option value="1" <?php echo 'selected';?>>Single Date</option>
                                        <option value="2">Every Day</option>
                                    </select>
                                </div>
                                <div class="form-group mt15">
                                    <label>Business Unit:</label>
                                    <select name="unit_id" id="unit_id" class="form-control">
                                        <option value="">- Select Business Unit - </option>
                                        @foreach($units as $unit)
                                        <option value="{{ $unit->id }}">{{ $unit->name_unit }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group mt15" id="day-container" style="display: none;">
                                    <label>Booking Day:</label>
                                    <select name="booking_day" id="booking_day" class="form-control">
                                        <option value="">- Select Day - </option>
                                        <option value="Sun">Sunday / Minggu</option>
                                        <option value="Mon">Monday / Senin</option>
                                        <option value="Tue">Tuesday / Selasa</option>
                                        <option value="Wed">Wednesday / Rabu</option>
                                        <option value="Thu">Thursday / Kamis</option>
                                        <option value="Fri">Friday / Jumat</option>
                                        <option value="Sat">Saturday / Sabtu</option>
                                        
                                    </select>
                                </div>
                                <div class="form-group mt15" id="date-container">
                                    <label>Booking Date:</label>
                                    <input type="date" name="date" id="date" class="form-control">
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group mt15">
                                            <label>Start Time:</label>
                                            <select name="start_time" id="start_time" class="form-control">
                                                <option value="">- Select Time - </option>
                                                <option value="06">06:00 WIB</option>
                                                <option value="07">07:00 WIB</option>
                                                <option value="08">08:00 WIB</option>
                                                <option value="09">09:00 WIB</option>
                                                <option value="10">10:00 WIB</option>
                                                <option value="11">11:00 WIB</option>
                                                <option value="12">12:00 WIB</option>
                                                <option value="13">13:00 WIB</option>
                                                <option value="14">14:00 WIB</option>
                                                <option value="15">15:00 WIB</option>
                                                <option value="16">16:00 WIB</option>
                                                <option value="17">17:00 WIB</option>
                                                <option value="18">18:00 WIB</option>
                                                <option value="19">19:00 WIB</option>
                                                <option value="20">20:00 WIB</option>
                                                
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group mt15">
                                            <label>Finish Time:</label>
                                            <select name="finish_time" id="finish_time" class="form-control">
                                                <option value="">- Select Time - </option>
                                                <option value="07">07:00 WIB</option>
                                                <option value="08">08:00 WIB</option>
                                                <option value="09">09:00 WIB</option>
                                                <option value="10">10:00 WIB</option>
                                                <option value="11">11:00 WIB</option>
                                                <option value="12">12:00 WIB</option>
                                                <option value="13">13:00 WIB</option>
                                                <option value="14">14:00 WIB</option>
                                                <option value="15">15:00 WIB</option>
                                                <option value="16">16:00 WIB</option>
                                                <option value="17">17:00 WIB</option>
                                                <option value="18">18:00 WIB</option>
                                                <option value="19">19:00 WIB</option>
                                                <option value="20">20:00 WIB</option>
                                                <option value="06">21:00 WIB</option>
                                            </select>
                                        </div>
                                    </div>
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

@endsection

@section('script_admin')
    <script src="{{ asset('assets/plugins/tinymce/tinymce.min.js') }}"></script>

    {{ assets_js_back('showChangeImage') }}
@endsection
