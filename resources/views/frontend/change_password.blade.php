@extends('frontend.master') @section('content') 



<!-- Hero section start -->
<section class="bg-no-repeat bg-left-bottom xl:bg-right-bottom bg-contain xl:bg-cover flex flex-wrap items-center relative">
    <div class="container">
        <div class="grid grid-cols-12">
            <div class="col-span-12 rata-tengah">
                <div class="jarak20"></div>
                @if($message = Session::get('error'))
                    <div class="alert alert-danger" role="alert">
                        <span onclick="tutup_alert()" class="btn-colse">x</span>
                        <?= $message ;?>
                    </div>
                @endif
                @if($message = Session::get('success'))
                    <div class="alert alert-success" role="alert">
                        <span onclick="tutup_alert()" class="btn-colse">x</span>
                        <i class="fa fa-check"></i> <?= $message ;?>
                    </div>
                @endif

                <h3 class="font-lora jarak30 custom-title">
                    Change Password
                </h3>
                  
                <div class="card-box jarak20">
                    <form method="POST" action="{{ route('password.update') }}">
                        @csrf
                    <input type="hidden" value="{{ Auth::user()->username }}" name="username">
                    <label>Your Old Password : </label>
                    <input name="old_password" type="password" class="input-ticket w-100" placeholder="Enter your old password">
                    <div class="jarak20"></div>
                    <label>Your New Password : </label>
                    <input name="password" type="password" class="input-ticket w-100" placeholder="Enter your new password">
                    <div class="jarak20"></div>
                    <label>Confirm New Password : </label>
                    <input name="password_confirmation" type="password" class="input-ticket w-100" placeholder="Confirm your new password">  
                    <div class="jarak40"></div>
                    <center><button type="submit" class="buttons btn-success">Submit</button></center>
                    </form>
                </div>
                <div class="jarak40"></div>
                
            </div>
        </div>
    </div>
</section>
@endsection