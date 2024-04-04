@extends('frontend.master') @section('content') 
<!-- Hero section start -->
<section class="bg-no-repeat bg-center bg-cover bg-[#FFF6F0] h-[350px] lg:h-[513px] flex flex-wrap items-center relative before:absolute before:inset-0 before:content-[''] before:bg-[#000000] before:opacity-[70%]" style="background-image: url('{{ asset('template/images/contact.webp') }}');">
<div class="container">
	<div class="grid grid-cols-12">
		<div class="col-span-12">
			<div class="max-w-[650px] mx-auto text-center text-white relative z-[1]">
				<div class="mb-5">
					<span class="text-base block"><img class="dashboard-foto" src="{{ asset('template/images/person.webp') }}"><br>
					 Welcome, {{ Auth::user()->name }} ({{ Auth::user()->level }})</span>
				</div>
				<h1 class="font-lora text-[36px] sm:text-[50px] md:text-[68px] lg:text-[50px] leading-tight xl:text-2xl font-medium">Setting</h1>
				<p class="text-base mt-5 max-w-[500px] mx-auto text-center"></p>
			</div>
		</div>
	</div>
</div>
</section>
<section class="testimonial-section pb-[80px] lg:pb-[120px] mt-[40px] bg-center bg-no-repeat bg-white relative z-10">
<div class="container testimonial-carousel-two relative">
	<div class="scene dots-shape absolute left-0">
		<img data-depth="0.4" class="z-[1]" src="{{ asset('template/frontend') }}/assets/images/testimonial/dots.png" width="106" height="129" loading="lazy" alt="shape">
	</div>
	<div class="grid items-center grid-cols-12 gap-x-[30px]">
		<div class="col-span-12 relative">
			<div class="swiper rounded-[30px] pb-[40px] md:pb-0">
				<div class="swiper-wrapper">
					<div class="swiper-slide">
						<!-- shape and images -->
						<div class="pl-[50px] xl:pl-[150px]">
							<div class="inline-block relative bg-primary text-primary rounded-[30px] z-10">
								<img src="{{ asset('template/images') }}/menu_profiles.png" class="w-auto h-auto block mx-auto relative z-[2] thumb" loading="lazy" width="402" height="505">
								<img class="absolute left-[0px] top-0 z-[1]" src="{{ asset('template/frontend') }}/assets/images/testimonial/persone-pattern.png" width="400" height="500" loading="lazy" alt="shape">
							</div>
						</div>
						<div class="before:absolute before:top-1/2 before:-translate-y-1/2 before:left-0 before:w-full before:h-[395px] before:content-[''] before:bg-[#F5F9F8] before:rounded-[30px]">
							<div class="text-left rounded-[14px] max-w-[100%] sm:max-w-[90%] md:max-w-[560px] mx-auto md:ml-auto absolute top-[65%] sm:top-1/2 left-0 md:left-auto right-0 md:right-[50px] xl:right-0 -translate-y-1/2 px-[20px] md:px-[30px] xl:pl-[0px] xl:pr-[60px] py-[20px] md:py-[30px] z-20 bg-white xl:bg-transparent shadow lg:shadow-none scale-[0.8] sm:scale-100">
								<div class="title-setting">
                                    Profile Setting
								</div>
								
							</div>
						</div>
					</div>
					<div class="swiper-slide">
						<!-- shape and images -->
						<div class="pl-[50px] xl:pl-[150px]">
							<div class="inline-block relative bg-primary text-primary rounded-[30px] z-10">
								<img src="{{ asset('template/images') }}/menu_password.png" class="w-auto h-auto block mx-auto relative z-[2] thumb" loading="lazy" width="402" height="505" alt="Sun Francisco">
								<img class="absolute left-[0px] top-0 z-[1]" src="{{ asset('template/frontend') }}/assets/images/testimonial/persone-pattern.png" width="400" height="500" loading="lazy" alt="shape">
							</div>
						</div>
						<div class="before:absolute before:top-1/2 before:-translate-y-1/2 before:left-0 before:w-full before:h-[395px] before:content-[''] before:bg-[#F5F9F8] before:rounded-[30px]">
							<div class="text-left rounded-[14px] max-w-[100%] sm:max-w-[90%] md:max-w-[560px] mx-auto md:ml-auto absolute top-[65%] sm:top-1/2 left-0 md:left-auto right-0 md:right-[50px] xl:right-0 -translate-y-1/2 px-[20px] md:px-[30px] xl:pl-[0px] xl:pr-[60px] py-[20px] md:py-[30px] z-20 bg-white xl:bg-transparent shadow lg:shadow-none scale-[0.8] sm:scale-100">
								<div class="title-setting">
                                    Change Password
								</div>
								
							</div>
						</div>
					</div>
				</div>
				<!-- If we need navigation buttons -->
				<div class="testimonial-pagination hidden sm:block">
					<div class="swiper-button-prev w-[36px] h-[36px] rounded-full bg-secondary xl:bg-primary text-white hover:bg-secondary top-auto bottom-[85px] left-[30px]"></div>
					<div class="swiper-button-next w-[36px] h-[36px] rounded-full bg-secondary xl:bg-primary text-white hover:bg-secondary top-auto bottom-[85px] left-[85px]"></div>
				</div>
			</div>
		</div>
	</div>
</div>
</section>
@endsection