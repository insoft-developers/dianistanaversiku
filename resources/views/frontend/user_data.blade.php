@extends('frontend.master')
@section('content')
<!-- Hero section start -->
<section class="bg-no-repeat bg-center bg-cover bg-[#FFF6F0] h-[350px] lg:h-[513px] flex flex-wrap items-center relative before:absolute before:inset-0 before:content-[''] before:bg-[#000000] before:opacity-[70%]" style="background-image: url('{{ asset('template/images/contact.webp') }}');">
    <div class="container">
        <div class="grid grid-cols-12">
            <div class="col-span-12">
                <div class="max-w-[650px]  mx-auto text-center text-white relative z-[1]">
                    <div class="mb-5"><span class="text-base block"><img class="dashboard-foto" src="{{ $data->foto == NULL || $data->foto == '' ? asset('template/images/profil_icon.png') : asset('storage/profile/'.$data->foto)  }}"><br> Welcome, {{ Auth::user()->name }} ({{ Auth::user()->level }})</span></div>
                    <h1 class="font-lora text-[36px] sm:text-[50px] md:text-[68px] lg:text-[50px] leading-tight xl:text-2xl font-medium">
                        User Data
                    </h1>

                    <p class="text-base mt-5 max-w-[500px] mx-auto text-center">
                        
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- Hero section end -->

<!-- Popular Properties start -->
<section class="popular-properties py-[80px] lg:py-[120px]">
    <div class="container">
        <div class="grid grid-cols-12 mb-[-30px] gap-[30px] xl:gap-[50px]">
            <div class="col-span-12 md:col-span-6 lg:col-span-8 mb-[30px]">
                <img src="{{ asset('template/images') }}/userdata_image.webp" class="w-auto h-auto" loading="lazy" alt="Elite Garden Resedence." width="770" height="465">
                
                <h4 class="font-lora text-primary text-[24px] leading-[1.277] sm:text-[28px] capitalize mt-[50px] mb-[40px] font-medium">Instalasi dan Kelengkapan<span class="text-secondary">.</span>
                </h4>

                <ul class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-1 lg:grid-cols-2 xl:grid-cols-3 px-[15px] mx-[-15px] mt-[40px]">
                    <li class="flex flex-wrap items-center mb-[25px]">
                        <img class="mr-[15px]" src="{{ asset('template/frontend') }}/assets/images/about/check.png" loading="lazy" alt="icon" width="20" height="20">
                        <span>Daya Listrik : {{ $data->daya_listrik }} WATT</span>
                    </li>
                    <li class="flex flex-wrap items-center mb-[25px]">
                        <img class="mr-[15px]" src="{{ asset('template/frontend') }}/assets/images/about/check.png" loading="lazy" alt="icon" width="20" height="20">
                        <span>Luas Tanah : {{ $data->luas_tanah }} m<sup>2</sup></span>
                    </li>
                    <li class="flex flex-wrap items-center mb-[25px]">
                        <img class="mr-[15px]" src="{{ asset('template/frontend') }}/assets/images/about/check.png" loading="lazy" alt="icon" width="20" height="20">
                        <span>Iuran Bulanan : Rp. {{ number_format($data->iuran_bulanan) }}</span>
                    </li>
                    <li class="flex flex-wrap items-center mb-[25px]">
                        <img class="mr-[15px]" src="{{ asset('template/frontend') }}/assets/images/about/check.png" loading="lazy" alt="icon" width="20" height="20">
                        <span>ID PDAM : {{ $data->id_pelanggan_pdam }}</span>
                    </li>
                    <li class="flex flex-wrap items-center mb-[25px]">
                        <img class="mr-[15px]" src="{{ asset('template/frontend') }}/assets/images/about/check.png" loading="lazy" alt="icon" width="20" height="20">
                        <span>PLN : {{ $data->nomor_meter_pln }}</span>
                    </li>
                    <li class="flex flex-wrap items-center mb-[25px]">
                        <img class="mr-[15px]" src="{{ asset('template/frontend') }}/assets/images/about/check.png" loading="lazy" alt="icon" width="20" height="20">
                        <span>Emergency : {{ $data->whatsapp_emergency }}</span>
                    </li>
                    
                </ul>
                
               

            </div>

            <div class="col-span-12 md:col-span-6 lg:col-span-4 mb-[30px]">
                <aside class="mb-[-60px] asidebar">
                    <div class="mb-[60px]">
                        <h3 class="text-primary leading-none text-[24px] font-lora underline mb-[40px] font-medium">Data Perumahan</h3>
                             <button type="submit" class="mb-[25px]  block z-[1] before:rounded-md before:block before:absolute before:left-auto before:right-0 before:inset-y-0 before:z-[-1] before:bg-secondary before:w-0 hover:before:w-full hover:before:left-0 hover:before:right-auto before:transition-all leading-none px-[30px] py-[12px] capitalize font-medium text-white text-[14px] xl:text-[16px] relative after:block after:absolute after:inset-0 after:z-[-2] after:bg-primary after:rounded-md after:transition-all">{{ $data->penyelia == "SDP" ? "Sarana Dian Property (SDP)" : "Dian Mega Sarana Indonesia (DMSI)" }}</button>    
                            <div class="relative mb-[25px] bg-white">
                                <div class="cust-label">BLOK</div>
                                <input readonly class="font-light w-full leading-[1.75] placeholder:opacity-100 placeholder:text-body border border-primary border-opacity-60 rounded-[8px] pl-[20px] pr-[20px] py-[8px] focus:border-secondary focus:border-opacity-60 focus:outline-none focus:drop-shadow-[0px_6px_15px_rgba(0,0,0,0.1)] bg-white" type="text" placeholder="Blok" value="BLOK   {{ $data->blok }}">
                               
                            </div>
                            <div class="relative mb-[25px] bg-white">
                                <div class="cust-label">Nomor Rumah</div>
                                <input readonly class="font-light w-full leading-[1.75] placeholder:opacity-100 placeholder:text-body border border-primary border-opacity-60 rounded-[8px] pl-[20px] pr-[20px] py-[8px] focus:border-secondary focus:border-opacity-60 focus:outline-none focus:drop-shadow-[0px_6px_15px_rgba(0,0,0,0.1)] bg-white" type="text" placeholder="Blok" value="No. {{ $data->nomor_rumah }}">
                               
                            </div>
                            <div class="relative mb-[25px] bg-white">
                                <div class="cust-label">Nama Pemilik Rumah</div>
                                <input readonly class="font-light w-full leading-[1.75] placeholder:opacity-100 placeholder:text-body border border-primary border-opacity-60 rounded-[8px] pl-[20px] pr-[20px] py-[8px] focus:border-secondary focus:border-opacity-60 focus:outline-none focus:drop-shadow-[0px_6px_15px_rgba(0,0,0,0.1)] bg-white" type="text" placeholder="Nama Pemilik Rumah" value="{{ $data->name }}">
                               
                            </div>
                            
                            <div class="relative mb-[25px] bg-white">
                                <div class="cust-label">Alamat Surat Menyurat</div>
                                <input readonly class="font-light w-full leading-[1.75] placeholder:opacity-100 placeholder:text-body border border-primary border-opacity-60 rounded-[8px] pl-[20px] pr-[20px] py-[8px] focus:border-secondary focus:border-opacity-60 focus:outline-none focus:drop-shadow-[0px_6px_15px_rgba(0,0,0,0.1)] bg-white" type="text" placeholder="Alamat Surat Menyurat" value="{{ $data->alamat_surat_menyurat }}">
                               
                            </div>
                            <div class="relative mb-[25px] bg-white">
                                <div class="cust-label">Nomor Telepon Rumah</div>
                                <input readonly class="font-light w-full leading-[1.75] placeholder:opacity-100 placeholder:text-body border border-primary border-opacity-60 rounded-[8px] pl-[20px] pr-[20px] py-[8px] focus:border-secondary focus:border-opacity-60 focus:outline-none focus:drop-shadow-[0px_6px_15px_rgba(0,0,0,0.1)] bg-white" type="text" placeholder="Nomor Telepon Rumah" value="{{ $data->nomor_telepon_rumah }}">
                               
                            </div>
                            <div class="relative mb-[25px] bg-white">
                                <div class="cust-label">Mulai Menempati</div>
                                <input readonly class="font-light w-full leading-[1.75] placeholder:opacity-100 placeholder:text-body border border-primary border-opacity-60 rounded-[8px] pl-[20px] pr-[20px] py-[8px] focus:border-secondary focus:border-opacity-60 focus:outline-none focus:drop-shadow-[0px_6px_15px_rgba(0,0,0,0.1)] bg-white" type="text" placeholder="Mulai Menempati" value="{{ date('d F Y', strtotime($data->mulai_menempati)) }}">
                               
                            </div>
                          
                      
                    </div>


                    <div class="mb-[60px]">
                        <h3 class="text-primary leading-none text-[24px] font-lora underline mb-[40px] font-medium">Informasi Lainnya</h3>
                        <ul class="flex flex-wrap my-[-7px] mx-[-5px] font-light text-[12px]">
                            
                            <li class="my-[7px] mx-[5px]"><a href="#" class="leading-none border border-[#E0E0E0] py-[8px] px-[10px] block rounded-[4px] hover:text-secondary">No. Whatsapp <i class="fa fa-arrow-right"></i> {{ $data->no_hp }}</a>
                            </li>
                            
                            
                            <li class="my-[7px] mx-[5px]"><a href="#" class="leading-none border border-[#E0E0E0] py-[8px] px-[10px] block rounded-[4px] hover:text-secondary">Jenis Kelamin <i class="fa fa-arrow-right"></i> {{ $data->jenis_kelamin }}</a>
                            </li>

                            <li class="my-[7px] mx-[5px]"><a href="#" class="leading-none border border-[#E0E0E0] py-[8px] px-[10px] block rounded-[4px] hover:text-secondary">Username <i class="fa fa-arrow-right"></i> {{ $data->username }}</a>
                            </li>

                            <li class="my-[7px] mx-[5px]"><a href="#" class="leading-none border border-[#E0E0E0] py-[8px] px-[10px] block rounded-[4px] hover:text-secondary">Email <i class="fa fa-arrow-right"></i> {{ $data->email }}</a>
                            </li>

                           
                           

                        </ul>
                    </div>
                </aside>
            </div>
        </div>

    </div>
</section>
<!-- Popular Properties end -->


<!-- News Letter section End -->
@endsection