@extends('frontend.master')
@section('content')



<!-- Hero section start -->
<section class="bg-no-repeat bg-left-bottom xl:bg-right-bottom bg-contain xl:bg-cover flex flex-wrap items-center relative">
    <div class="container">
        <div class="grid grid-cols-12">
            <div class="col-span-12">
                <div class="jarak20">
                    <img class="mx-auto w-full" src="{{ asset('template/images') }}/about.webp"  alt="about image">    
                    <h3 class="font-lora text-primary text-[36px] sm:text-[50px] md:text-[68px] lg:text-[50px] leading-tight xl:text-2xl title font-medium jarak30">
                        Privacy Policy<span class="text-secondary">.</span>
                    </h3>

                    
                    <div class="jarak40"><?= $setting->privacy ;?></div>
                    <div class="jarak40"></div>
                </div>
            </div>
        </div>
    </div>
</section>


<!-- Hero section end -->




@endsection