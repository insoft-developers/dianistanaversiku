@extends("layouts.master_authusers")

@section("title_authuser","Login User")

@section("content_authuser")

<div class="row">
    <div class="col-md-12 mb-3">
        
        <h2>Login Pengguna DianIstana</h2>
        <p>Masukkan email dan password untuk login</p>
        <hr>
    </div>
    <div class="col-md-12">
        <div class="mb-3">
            <label class="form-label">Email</label>
            <input type="email" class="form-control">
        </div>
    </div>
    <div class="col-12">
        <div class="mb-4">
            <label class="form-label">Password</label>
            <input type="text" class="form-control">
        </div>
    </div>
    <div class="col-12">
        <div class="mb-2">
            <div class="form-check form-check-primary form-check-inline">
                <input class="form-check-input me-3" type="checkbox" id="form-check-default">
                <label class="form-check-label" for="form-check-default">
                    Remember me
                </label>
            </div>
        </div>
    </div>
    
    <div class="col-12">
        <div class="mb-4">
            <button type="button" id="btnSignIn" class="btn btn-secondary w-100">Login</button>
        </div>
    </div>

    <div class="col-12 mb-4">
        <div class="">
            <div class="seperator">
                <hr>
                <div class="seperator-text"> <span>Atau</span></div>
            </div>
        </div>
    </div>
    
    <div class="col-12">
        <div class="row">
            <div class="col-md-5">
                <p class="mb-0"><a href="{{ url("forget_password") }}" class="text-danger">Lupa Password.?</a></p>
            </div>
            <div class="col-md-7">
                <p class="mb-0">Tidak Punya Akun.? <a href="{{ route("register_user") }}" class="text-success">Daftar Akun</a></p>
            </div>
        </div>
    </div>
   
</div>      

@endsection