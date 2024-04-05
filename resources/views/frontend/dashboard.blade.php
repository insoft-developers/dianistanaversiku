@extends('frontend.master')
@section('content')

<section class="bg-no-repeat bg-center bg-cover bg-[#FFF6F0] h-[350px] lg:h-[513px] flex flex-wrap items-center relative before:absolute before:inset-0 before:content-[''] before:bg-[#000000] before:opacity-[70%]" style="background-image: url('{{ asset('template/images/contact.webp') }}');">
    <div class="container">
        <div class="grid grid-cols-12">
            <div class="col-span-12">
                <div class="max-w-[650px]  mx-auto text-center text-white relative z-[1]">
                    <div class="mb-5"><span class="text-base block"><img class="dashboard-foto" src="{{ $data->foto == NULL || $data->foto == '' ? asset('template/images/profil_icon.png') : asset('storage/profile/'.$data->foto)  }}"><br> Welcome, {{ Auth::user()->name }} ({{ Auth::user()->level }})</span></div>
                    <h1 class="font-lora text-[36px] sm:text-[50px] md:text-[68px] lg:text-[50px] leading-tight xl:text-2xl font-medium">
                        Dashboard
                    </h1>

                    <p class="text-base mt-5 max-w-[500px] mx-auto text-center">
                        
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Hero section end -->




<!-- service Section Start-->

<section class="pt-[80px] pb-[120px] lg:py-[120px]">
    <div class="container">
        <div class="grid grid-cols-12">
            <div class="col-span-12">
                <div class="mb-[30px] lg:mb-[60px] text-center">
                    <span class="text-secondary text-tiny inline-block mb-2">Main Menu</span>
                    <h2 class="font-lora text-primary text-[24px] sm:text-[30px] xl:text-xl font-medium">
                        Explore Our Menu<span class="text-secondary">.</span></h2>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-[30px]">
            <div class="relative group">
                <a href="{{ url('frontend_booking') }}" class="block">
                    <img src="{{ asset('template/images/unit') }}/booking.png" class="menu-image" loading="lazy" width="270" height="290" alt="Sale Property">
                    <div class="drop-shadow-[0px_2px_15px_rgba(0,0,0,0.1)] hover:drop-shadow-[0px_8px_20px_rgba(0,0,0,0.15)] bg-[#FFFDFC] rounded-[0px_0px_6px_6px] px-[25px] py-[25px]">
                        <h3 class="dashboard-title font-lora font-normal text-[24px] xl:text-lg text-primary group-hover:text-secondary transition-all mb-[5px]">Booking<span class="text-secondary group-hover:text-primary"></span> </h3>
                        <p class="font-light text-tiny">Provide unit business place or comunal facility to book for user to enjoy.</p>
                    </div>
                </a>
            </div>
            <div class="relative group">
                <a href="{{ url('ticketing') }}" class="block">
                    <img src="{{ asset('template/images/unit') }}/ticketing.png" class="menu-image" loading="lazy" width="270" height="290" alt="Buy Property">
                    <div class="drop-shadow-[0px_2px_15px_rgba(0,0,0,0.1)] hover:drop-shadow-[0px_8px_20px_rgba(0,0,0,0.15)] bg-[#FFFDFC] rounded-[0px_0px_6px_6px] px-[25px] py-[25px]">
                        <h3 class="dashboard-title font-lora font-normal text-[24px] xl:text-lg text-primary group-hover:text-secondary transition-all mb-[5px]">Ticketing<span class="text-secondary group-hover:text-primary"></span> </h3>
                        <p class="font-light text-tiny">Provide System for User to send questions, requests or complains.</p>
                    </div>
                </a>
            </div>
            <div class="relative group">
                <a href="properties-details.html" class="block">
                    <img src="{{ asset('template/images/unit') }}/payment.png" class="menu-image" loading="lazy" width="270" height="290" alt="Rent Property">
                    <div class="drop-shadow-[0px_2px_15px_rgba(0,0,0,0.1)] hover:drop-shadow-[0px_8px_20px_rgba(0,0,0,0.15)] bg-[#FFFDFC] rounded-[0px_0px_6px_6px] px-[25px] py-[25px]">
                        <h3 class="dashboard-title font-lora font-normal text-[24px] xl:text-lg text-primary group-hover:text-secondary transition-all mb-[5px]">Payment<span class="text-secondary group-hover:text-primary"></span> </h3>
                        <p class="font-light text-tiny">Provide billing system for user to pay according to bill of amount </p>
                    </div>
                </a>
            </div>
            <div class="relative group">
                <a href="{{ url('user_data') }}" class="block">
                    <img src="{{ asset('template/images/unit') }}/datamenu.png" class="menu-image" loading="lazy" width="270" height="290" alt="Buy Property">
                    <div class="drop-shadow-[0px_2px_15px_rgba(0,0,0,0.1)] hover:drop-shadow-[0px_8px_20px_rgba(0,0,0,0.15)] bg-[#FFFDFC] rounded-[0px_0px_6px_6px] px-[25px] py-[25px]">
                        <h3 class="dashboard-title font-lora font-normal text-[24px] xl:text-lg text-primary group-hover:text-secondary transition-all mb-[5px]">User Data<span class="text-secondary group-hover:text-primary"></span> </h3>
                        <p class="font-light text-tiny">Provide System for User to send questions, requests or complains.</p>
                    </div>
                </a>
            </div>
            <div class="relative group">
                <a href="{{ url('ticketing') }}" class="block">
                    <img src="{{ asset('template/images/unit') }}/notif_menu.png" class="menu-image" loading="lazy" width="270" height="290" alt="Buy Property">
                    <div class="drop-shadow-[0px_2px_15px_rgba(0,0,0,0.1)] hover:drop-shadow-[0px_8px_20px_rgba(0,0,0,0.15)] bg-[#FFFDFC] rounded-[0px_0px_6px_6px] px-[25px] py-[25px]">
                        <h3 class="dashboard-title font-lora font-normal text-[24px] xl:text-lg text-primary group-hover:text-secondary transition-all mb-[5px]">Nofitications<span class="text-secondary group-hover:text-primary"></span> </h3>
                        <p class="font-light text-tiny">Provide System for User to send questions, requests or complains.</p>
                    </div>
                </a>
            </div>
            <div class="relative group">
                <a href="{{ url('frontend_setting') }}" class="block">
                    <img src="{{ asset('template/images/unit') }}/menu_profiles.png" class="menu-image" loading="lazy" width="270" height="290" alt="Mortgage">
                    <div class="drop-shadow-[0px_2px_15px_rgba(0,0,0,0.1)] hover:drop-shadow-[0px_8px_20px_rgba(0,0,0,0.15)] bg-[#FFFDFC] rounded-[0px_0px_6px_6px] px-[25px] py-[25px]">
                        <h3 class="dashboard-title font-lora font-normal text-[24px] xl:text-lg text-primary group-hover:text-secondary transition-all mb-[5px]">Profile<span class="text-secondary group-hover:text-primary"></span> </h3>
                        <p class="font-light text-tiny">Provide Setting for user to customize profile image, adress and phone number. </p>
                    </div>
                </a>
            </div>
            <div class="relative group">
                <a href="{{ url('frontend_change_password') }}" class="block">
                    <img src="{{ asset('template/images/unit') }}/menu_password.png" class="menu-image" loading="lazy" width="270" height="290" alt="Mortgage">
                    <div class="drop-shadow-[0px_2px_15px_rgba(0,0,0,0.1)] hover:drop-shadow-[0px_8px_20px_rgba(0,0,0,0.15)] bg-[#FFFDFC] rounded-[0px_0px_6px_6px] px-[25px] py-[25px]">
                        <h3 class="dashboard-title font-lora font-normal text-[24px] xl:text-lg text-primary group-hover:text-secondary transition-all mb-[5px]">Change Password<span class="text-secondary group-hover:text-primary"></span> </h3>
                        <p class="font-light text-tiny">Provide Setting for user to customize profile image, adress and phone number.. </p>
                    </div>
                </a>
            </div>
        </div>
    </div>
</section>

<!-- service Section End-->

@endsection