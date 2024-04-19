@extends('frontend.master') @section('content') 

    <!-- Hero section end -->
    <!-- Popular Properties start -->
    <section class="popular-properties py-[10px] lg:py-[10px]">
    <div class="container">
        <h3 class="font-lora jarak30 custom-title">
            Booking Feature
        </h3>
        <div class="jarak20"></div>
        <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-[30px]">
            
            @foreach($unit as $u)
            <div class="swiper-slide">
                <div class="overflow-hidden rounded-md drop-shadow-[0px_0px_5px_rgba(0,0,0,0.1)] bg-[#FFFDFC] text-center transition-all duration-300 hover:-translate-y-[10px]">
                    <div class="relative">
                        <a href="{{ url('booking_detail') }}/{{ $u->slug }}" class="block"><img src="{{ asset('storage/unit-bisnis') }}/{{ $u->image }}" class="w-full h-full" loading="lazy" width="370" height="266" alt="Orchid Casel de Paradise."></a>
                        
                        <span class="absolute bottom-5 left-5 bg-[#FFFDFC] p-[5px] rounded-[2px] text-primary leading-none text-[14px] font-normal capitalize">available</span>
                    </div>
                    <div class="py-[20px] px-[20px] text-left">
                        <h3><a href="{{ url('booking_detail') }}/{{ $u->slug }}" class="font-lora leading-tight text-[22px] xl:text-[26px] text-primary hover:text-secondary transition-all font-medium">{{ $u->name_unit }}</a></h3>
                        <h4><a href="{{ url('booking_detail') }}/{{ $u->slug }}" class="font-light text-[14px] leading-[1.75] underline">{{ $u->kategori }}</a></h4>
                        
                        
                        @if(Auth::user()->level == "user")
                        <ul class="daftar-harga">
                            <li class="flex flex-wrap items-center justify-between">
                                <span class="font-lora text-base text-primary leading-none font-medium">06:00 - 17:00</span>
                                <span class="flex flex-wrap items-center">
                                    FREE
                                </span>
                            </li>
                            <hr/>
                            @if($u->kategori == "Kolam Renang")
                            <li class="flex flex-wrap items-center justify-between">
                                <span class="font-lora text-base text-primary leading-none font-medium">17:00 - 21:00</span>
                                <span class="flex flex-wrap items-center">
                                    FREE
                                </span>
                            </li>
                            @else
                            <li class="flex flex-wrap items-center justify-between">
                                <span class="font-lora text-base text-primary leading-none font-medium">17:00 - 21:00</span>
                                <span class="flex flex-wrap items-center">
                                    Price: {{ number_format($u->harga_warga_1721_weekday) }} (Weekday)<br>
                                    Price: {{ number_format($u->harga_warga_1721_weekend) }} (Weekend)
                                </span>
                            </li>
                            @endif
                        </ul>
                        @else

                            @if($u->kategori == "Kolam Renang")
                                <ul class="daftar-harga">
                                    <li class="flex flex-wrap items-center justify-between">
                                        <span class="font-lora text-base text-primary leading-none font-medium">Membership</span>
                                        <span class="flex flex-wrap items-center">
                                            Price: {{ number_format($u->harga_membership_4x) }} (4 x pertemuan)<br>
                                            
                                        </span>
                                    </li>
                                    <hr/>
                                    <li class="flex flex-wrap items-center justify-between">
                                        <span class="font-lora text-base text-primary leading-none font-medium">Membership</span>
                                        <span class="flex flex-wrap items-center">
                                            Price: {{ number_format($u->harga_membership_8x) }} (8 x pertemuan)<br>
                                            
                                        </span>
                                    </li>
                                    <hr/>
                                    <li class="flex flex-wrap items-center justify-between">
                                        <span class="font-lora text-base text-primary leading-none font-medium">Non Member</span>
                                        <span class="flex flex-wrap items-center">
                                            Price: {{ number_format($u->harga_non_member) }} (per orang per kedatangan)<br>
                                            
                                        </span>
                                    </li>
                                    <hr/>
                                    <li class="flex flex-wrap items-center justify-between">
                                        <span class="font-lora text-base text-primary leading-none font-medium">Paket Khusus Tamu Warga</span>
                                        <span class="flex flex-wrap items-center">
                                            Price: {{ number_format($u->harga_tamu_warga) }} (per orang per kedatangan)<br>
                                            
                                        </span>
                                    </li>
                                    
                                </ul>
                            @else
                            <ul class="daftar-harga">
                                <div style="margin-top:25px"></div>
                                <li class="flex flex-wrap items-center justify-between">
                                    <span class="font-lora text-base text-primary leading-none font-medium">06:00 - 17:00</span>
                                    <span class="flex flex-wrap items-center">
                                        Price: {{ number_format($u->harga_umum_0617_weekday) }} (Weekday)<br>
                                        Price: {{ number_format($u->harga_umum_0617_weekend) }} (Weekend)
                                    </span>
                                </li>
                                <hr/>
                                <li class="flex flex-wrap items-center justify-between">
                                    <span class="font-lora text-base text-primary leading-none font-medium">17:00 - 21:00</span>
                                    <span class="flex flex-wrap items-center">
                                        Price: {{ number_format($u->harga_umum_1721_weekday) }} (Weekday)<br>
                                        Price: {{ number_format($u->harga_umum_1721_weekend) }} (Weekend)
                                    </span>
                                </li>
                                <div style="margin-top:50px"></div>
                            </ul>
                            @endif
                        @endif
                    </div>
                </div>
            </div>
            @endforeach
        </div>
       
    </div>
    </section>
    <!-- Popular Properties end -->
    @endsection