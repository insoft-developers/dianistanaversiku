@extends('frontend.master')
@section('content')


        <!-- Hero section end -->

        <!-- contact form start -->
        <div class="py-[80px] lg:py-[120px]">
            <div class="container">

                <form method="POST" action="{{ route('register_now') }}">
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
                                Create Account<span class="text-secondary">.</span></h2>

                                
                            <div class="grid grid-cols-12 gap-x-[20px] gap-y-[35px]">

                                <div class="col-span-12">
                                    <input class="font-light w-full sm:w-[400px] leading-[1.75] placeholder:opacity-100 placeholder:text-body border border-primary border-opacity-60 rounded-[8px] p-[15px] focus:border-secondary focus:border-opacity-60 focus:outline-none focus:drop-shadow-[0px_6px_15px_rgba(0,0,0,0.1)] " type="text" placeholder="Username" id="username" name="username">
                                </div>

                                <div class="col-span-12">
                                    <input class="font-light w-full sm:w-[400px] leading-[1.75] placeholder:opacity-100 placeholder:text-body border border-primary border-opacity-60 rounded-[8px] p-[15px] focus:border-secondary focus:border-opacity-60 focus:outline-none focus:drop-shadow-[0px_6px_15px_rgba(0,0,0,0.1)] " type="text" placeholder="Full Name" id="name" name="name">
                                </div>

                                <div class="col-span-12">
                                    <input class="font-light w-full sm:w-[400px] leading-[1.75] placeholder:opacity-100 placeholder:text-body border border-primary border-opacity-60 rounded-[8px] p-[15px] focus:border-secondary focus:border-opacity-60 focus:outline-none focus:drop-shadow-[0px_6px_15px_rgba(0,0,0,0.1)] " type="email" placeholder="Email" id="email" name="email">
                                </div>
                                 <div class="col-span-12">
                                    <input class="font-light w-full sm:w-[400px] leading-[1.75] placeholder:opacity-100 placeholder:text-body border border-primary border-opacity-60 rounded-[8px] p-[15px] focus:border-secondary focus:border-opacity-60 focus:outline-none focus:drop-shadow-[0px_6px_15px_rgba(0,0,0,0.1)] " type="text" placeholder="Whatsapp number ex: +6282165174545" id="no_hp" name="no_hp">
                                </div>


                                <div class="col-span-12">
                                    <input class="font-light w-full sm:w-[400px] leading-[1.75] placeholder:opacity-100 placeholder:text-body border border-primary border-opacity-60 rounded-[8px] p-[15px] focus:border-secondary focus:border-opacity-60 focus:outline-none focus:drop-shadow-[0px_6px_15px_rgba(0,0,0,0.1)] " type="password" placeholder="Password" id="password" name="password">

                                </div>

                                <div class="col-span-12">
                                    <input class="font-light w-full sm:w-[400px] leading-[1.75] placeholder:opacity-100 placeholder:text-body border border-primary border-opacity-60 rounded-[8px] p-[15px] focus:border-secondary focus:border-opacity-60 focus:outline-none focus:drop-shadow-[0px_6px_15px_rgba(0,0,0,0.1)] " type="password" placeholder="Confirm Password" id="password_confirmation" name="password_confirmation">

                                    <div class="flex flex-wrap items-center justify-between w-full sm:w-[400px]">
                                        <div class="flex flex-wrap mt-[15px] items-center">
                                            <input type="checkbox" id="checkbox1" name="Remember me">
                                            <label for="checkbox1" class="ml-[5px] cursor-pointer"> I agree with the
                                                <a href="{{ url('term') }}" target="_blank" class="underline text-secondary">Terms &
                                                    Conditions</a></label><br>
                                        </div>
                                    </div>
                                </div>



                                <div class="col-span-12">
                                    <div class="flex flex-wrap items-center">
                                        <button id="button_register" disabled="disabled" type="submit" class="before:rounded-md before:block before:absolute before:left-auto before:right-0 before:inset-y-0 before:-z-[1] before:bg-secondary before:w-0 hover:before:w-full hover:before:left-0 hover:before:right-auto before:transition-all leading-none px-[40px] py-[15px] capitalize font-medium text-white text-[14px] xl:text-[16px] relative after:block after:absolute after:inset-0 after:-z-[2] after:bg-primary after:rounded-md after:transition-all">Register</button>

                                        <p class="ml-[40px]">Already have an Account? <a href="{{ url('login') }}" class="text-secondary">Login</a></p>

                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-span-12 lg:col-span-6 mb-[30px]">
                            <img src="{{ asset('template/images') }}/register.webp" class="w-full lg:max-w-[538px] h-auto rounded-[10px]" width="546" height="668" alt="image">
                        </div>
                    </div>
                </form>

            </div>
        </div>
@endsection        