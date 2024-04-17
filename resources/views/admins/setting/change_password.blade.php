@extends('layouts.master_admins')

@section('title_admin', 'Change Password')

@section('breadcrumb_admin')
    <li class="breadcrumb-item" aria-current="page">Setting</li>
    <li class="breadcrumb-item active" aria-current="page">Change Password</li>
@endsection

@section('content_admin')

    <div class="col-xl-12 col-lg-12 col-sm-12 layout-spacing">
        <div class="card">
            <div class="card-body">
                @if($message = Session::get('error'))
                    <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert"> <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x close" data-bs-dismiss="alert"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg></button> <strong>Error!</strong> <?= $message ;?></div>
                @endif
                @if($message = Session::get('success'))
                    <div class="alert alert-success alert-dismissible fade show mb-4" role="alert"> <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x close" data-bs-dismiss="alert"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg></button> <strong>Success!</strong> <?= $message ;?></div>
                @endif

                <form method="POST" action="{{ route('backdata.password.update') }}">
                    @csrf
                <div class="row">
                    <div class="col-md-8">
                        <div class="card">
                            <div class="card-body">
                                <div class="form-group mt20">
                                    <label>Username:</label>
                                    <input type="text" class="form-control" id="username" name="username" value="{{ adminAuth()->username }}">
                                </div>
                                <div class="form-group mt20">
                                    <label>Old Password:</label>
                                    <input type="password" class="form-control" id="old_password" name="old_password" placeholder="enter your old password">
                                </div>

                                <div class="form-group mt20">
                                    <label>New Password:</label>
                                    <input type="password" class="form-control" id="password" name="password" placeholder="enter your New password">
                                </div>
                                <div class="form-group mt20">
                                    <label>Confirm Password:</label>
                                    <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" placeholder="confirm new password">
                                </div>
                                
                            </div>
                        </div>
                    </div>
                   
                    
                </div>
                <button type="submit" class="btn btn-success mt20">Submit</button>
                </form>
            </div>
        </div>
    </div>

   @endsection

@section('script_admin')
    <script src="{{ asset('assets/plugins/tinymce/tinymce.min.js') }}"></script>

    {{ assets_js_back('showChangeImage') }}
@endsection
