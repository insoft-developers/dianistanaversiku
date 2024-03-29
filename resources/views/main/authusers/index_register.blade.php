@extends("layouts.master_authusers")

@section("title_authuser","Daftar Akun")

@section("content_authuser")

<div class="row">
    <div class="col-md-12 mb-3">
        <h4>Daftar Akun Pengguna Aplikasi DianIstana</h4>
        <div class="seperator">
            <hr>
            <div class="seperator-text"></div>
        </div>
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
        <div class="mb-4">
            <label class="form-label">Confrim Password</label>
            <input type="text" class="form-control">
        </div>
    </div>
    
    <div class="col-12">
        <div class="mb-4">
            <button type="button" class="btn btn-secondary w-100">Daftar Akun</button>
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
        <div class="text-center">
            <p class="mb-0">Sudah Punya Akun.? <a href="{{ route("login_user") }}" class="text-success">Login Disini</a></p>
        </div>
    </div>
</div>      

@endsection