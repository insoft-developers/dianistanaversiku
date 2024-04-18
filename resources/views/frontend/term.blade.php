@extends('frontend.master')
@section('content')

<style>
    ul li { 
        display: list-item;
    }
</style>

<!-- Hero section start -->
<section class="bg-no-repeat bg-left-bottom xl:bg-right-bottom bg-contain xl:bg-cover flex flex-wrap items-center relative">
    <div class="container">
        <div class="grid grid-cols-12">
            <div class="col-span-12">
                <div class="jarak20">
                      
                    <h3 class="font-lora text-primary text-[36px] sm:text-[50px] md:text-[68px] lg:text-[50px] leading-tight xl:text-2xl title font-medium jarak30">
                        Terms & Conditions<span class="text-secondary">.</span>
                    </h3>

                    
                    <div class="jarak40 isi-data-term"><?= $setting->term ;?></div>
                    <div class="jarak40"></div>
                </div>
            </div>
        </div>
    </div>
</section>


<!-- Hero section end -->




@endsection