@extends('frontend.master')
@section('content')



<!-- Hero section start -->
<section class="bg-no-repeat bg-left-bottom xl:bg-right-bottom bg-contain xl:bg-cover flex flex-wrap items-center relative">
    <div class="container">
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
                    <?= $message ;?>
                </div>
            @endif
        <div class="grid grid-cols-12">

            <div class="col-span-12">
                <div class="jarak20">
                    <form method="POST" action="{{ route('send.otp') }}">
                    @csrf
                    <center><p>Masukkan 6 digit angka yang kami kirimkan melalui no whatsapp anda<p></center>
                    <img class="gambar-otp" src="{{ asset('template/images') }}/otp.png"  alt="about image">   
                    <input type="hidden" name="email" value="{{ session('session_register_otp') }}"> 
                    <center><input type="text" name="passcode" class="form-control otp-text" placeholder="masukkan 6 digit passcode anda"> </center>

                   <center><button id="button_register"  type="submit" class="before:rounded-md jarak20 before:block before:absolute before:left-auto before:right-0 before:inset-y-0 before:-z-[1] before:bg-secondary before:w-0 hover:before:w-full hover:before:left-0 hover:before:right-auto before:transition-all leading-none px-[40px] py-[15px] capitalize font-medium text-white text-[14px] xl:text-[16px] relative after:block after:absolute after:inset-0 after:-z-[2] after:bg-primary after:rounded-md after:transition-all">Send Code</button></center>
                    </form>
                    <div class="jarak40"></div>
                </div>

                    
            </div>
        </div>
    </div>
</section>


<!-- Hero section end -->




@endsection