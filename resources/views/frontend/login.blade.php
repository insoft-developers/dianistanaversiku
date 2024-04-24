
@extends('frontend.master')
@section('content')
<!-- Hero section start -->
       

        <!-- contact form start -->
        <div class="py-[80px] lg:py-[120px]">
            <div class="container">
                <form  method="POST" action="{{ route('frontend.login') }}">
                	@csrf
                    <div class="grid grid-cols-12 gap-x-[30px] mb-[-30px]">
                        <div class="col-span-12 lg:col-span-6 mb-[30px]">
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
                            <h2 class="font-lora text-primary text-[24px] sm:text-[30px] leading-[1.277] xl:text-xl mb-[15px] font-medium">
                                Login<span class="text-secondary">.</span></h2>

                            
                            <div class="grid grid-cols-12 gap-x-[20px] gap-y-[35px]">
                            	
                            		
                                <div class="col-span-12">
                                    <input title="untuk login dengan no whatsapp masukkan no whatsapp anda lengkap dengan kode negera (+6282166775544)" class="font-light w-full sm:w-[400px] leading-[1.75] placeholder:opacity-100 placeholder:text-body border border-primary border-opacity-60 rounded-[8px] p-[15px] focus:border-secondary focus:border-opacity-60 focus:outline-none focus:drop-shadow-[0px_6px_15px_rgba(0,0,0,0.1)] " type="text" name="username" placeholder="masukkan username/email/no whatsapp">
                                </div>


                                <div class="col-span-12">
                                    <input class="font-light w-full sm:w-[400px] leading-[1.75] placeholder:opacity-100 placeholder:text-body border border-primary border-opacity-60 rounded-[8px] p-[15px] focus:border-secondary focus:border-opacity-60 focus:outline-none focus:drop-shadow-[0px_6px_15px_rgba(0,0,0,0.1)] " type="password" name="password" placeholder="Password">

                                    <div class="flex flex-wrap items-center justify-between w-full sm:w-[400px] mt-[15px]">
                                        <div class="flex flex-wrap items-center">
                                            <input type="checkbox" id="checkbox1" name="Remember me">
                                            <label for="checkbox1" class="ml-[5px] cursor-pointer text-[14px]"> Remember
                                                me</label><br>
                                        </div>
                                        <a href="https://api.whatsapp.com/send?phone=6282231353000&text=Saya%20Lupa%20Password%20aplikasi%20MyDianIstana%2C%20mohon%20dibantu%20untuk%20mereset%20password%0A%0ANama%3A%0ABlok%20%26%20Nomor%20Rumah%3A" class="hover:text-secondary text-[14px] block">Forgot Password</a>
                                    </div>
                                </div>



                                <div class="col-span-12">
                                    <div class="flex flex-wrap items-center">
                                        <button type="submit" class="before:rounded-md before:block before:absolute before:left-auto before:right-0 before:inset-y-0 before:-z-[1] before:bg-secondary before:w-0 hover:before:w-full hover:before:left-0 hover:before:right-auto before:transition-all leading-none px-[40px] py-[15px] capitalize font-medium text-white text-[14px] xl:text-[16px] relative after:block after:absolute after:inset-0 after:-z-[2] after:bg-primary after:rounded-md after:transition-all">Login</button>

                                        {{-- <a href="{{ url('frontend_register') }}" class="font-medium text-primary hover:text-secondary ml-[40px]">Register</a> --}}
                                    </div>
                                </div>
                            
                            </div>
                        </div>

                        <div class="col-span-12 lg:col-span-6 mb-[30px]">
                            <img src="{{ asset('template/images/login.webp') }}" class="w-full h-auto rounded-[10px]" width="546" height="478" alt="image">
                        </div>
                    </div>
                </form>

            </div>
        </div>
       
 @endsection