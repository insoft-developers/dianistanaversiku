@extends('frontend.master') @section('content') 
<!-- Hero section start -->
<section class="bg-no-repeat bg-center bg-cover bg-[#FFF6F0] h-[350px] lg:h-[350px] flex flex-wrap items-center relative before:absolute before:inset-0 before:content-[''] before:bg-[#000000] before:opacity-[70%]" style="background-image: url('{{ asset('template/images/contact.webp') }}');">
<div class="container">
	<div class="grid grid-cols-12">
		<div class="col-span-12">
			<div class="max-w-[600px] mx-auto text-center text-white relative z-[1]">
				<div class="mb-5">
					<span class="text-base block">{{ $data->name_unit }}</span>
				</div>
				<h1 class="font-lora text-[36px] sm:text-[50px] md:text-[68px] lg:text-[50px] leading-tight xl:text-2xl font-medium">Booking Details</h1>
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
        <div class="col-span-12 md:col-span-6 lg:col-span-6 mb-[30px]">
			<aside class="mb-[-60px] asidebar">
			<div class="mb-[60px]">
				<h3 class="text-primary leading-none text-[24px] font-lora underline mb-[40px] font-medium">Please complete this form.</h3>
                <input type="hidden" id="current_month" value="{{ date('m') }}">
                <input type="hidden" id="current_year" value="{{ date('Y') }}">
                <input type="hidden" id="product_id" value="{{ $data->id }}">
				
                
                <div class="form-group">
                <select id="bulan" name="bulan" class="custom-input w-100 mr-[10px]">
                    <option value="">Select Month</option>
                    <option value="01">January</option>
                    <option value="02">February</option>
                    <option value="03">March</option>
                    <option value="04">April</option>
                    <option value="05">Mei</option>
                    <option value="06">June</option>
                    <option value="07">July</option>
                    <option value="08">August</option>
                    <option value="09">September</option>
                    <option value="10">October</option>
                    <option value="11">November</option>
                    <option value="12">December</option>
                </select>
            
                <select id="tahun" name="tahun" class="custom-input w-25">
                    <option value="">Year</option>
                    <?php
                    $now = date('Y');
                    for($i=0; $i<10; $i++) {?>
                        
                        <option value="{{ $now }}">{{ $now }}</option>
                    <?php $now++;  } ?>
                    
                    
                </select>
                </div>
                <p>Select booking date:    
                <div id="calendar-sitting"></div>
                <div id="time-sitting"></div>

                {{-- <div class="relative mb-[25px] bg-white">
                    
                    <input class="font-light w-full leading-[1.75] placeholder:opacity-100 placeholder:text-body border border-primary border-opacity-60 rounded-[8px] pl-[40px] pr-[20px] py-[8px] focus:border-secondary focus:border-opacity-60 focus:outline-none focus:drop-shadow-[0px_6px_15px_rgba(0,0,0,0.1)] bg-white" type="text" placeholder="Location">
                    
                </div> --}}
					
					
				
				
			</div>
			
			
			</aside>
		</div>
		<div class="col-span-12 md:col-span-6 lg:col-span-6 mb-[30px]">
			{{-- <img src="{{ asset('storage/unit-bisnis') }}/{{ $data->image }}" class="side-booking-image" loading="lazy" alt="Elite Garden Resedence." width="770" height="465"> --}}
            @php
                $random = random_int(1000, 9999);
                $invoice = date('ymdHis').$random;
            @endphp
			<table class="table nowrap">
                <tr>
                    <td width="35%"><strong>Image</strong></td>
                    <td width="5%">:</td>
                    <td width="*"><img src="{{ asset('storage/unit-bisnis') }}/{{ $data->image }}" class="side-booking-image"></td>
                <tr>
                    <td><strong>Facility Name</strong></td>
                    <td>:</td>
                    <td>{{ $data->name_unit }}</td>
                </tr>
                <tr>
                    <td><strong>Category</strong></td>
                    <td>:</td>
                    <td>{{ $data->kategori }}</td>
                </tr>
                <tr>
                    <td><strong>Transaction Date</strong></td>
                    <td>:</td>
                    <td>{{ date('d-m-Y') }}</td>
                </tr>
                <tr>
                    <td><strong>Invoice</strong></td>
                    <td>:</td>
                    <td>{{ $invoice }}<input type="hidden" id="invoice" value="{{ $invoice }}"></td>
                </tr>
                <tr>
                    <td><strong>Booking Date</strong></td>
                    <td>:</td>
                    <td><span id="booking-date"></span><input type="hidden" id="booking-date-input"></td>
                </tr>
                <tr>
                    <td><strong>Start Time</strong></td>
                    <td>:</td>
                    <td><span id="start-time"></span><input type="hidden" id="start-time-input"></td>
                </tr>
                <tr>
                    <td><strong>Finish Time</strong></td>
                    <td>:</td>
                    <td><span id="finish-time"></span><input type="hidden" id="finish-time-input"></td>
                </tr>
                <tr>
                    <td><strong>Quantity</strong></td>
                    <td>:</td>
                    <td><span id="quantity"></span><input type="hidden" id="quantity-input"></td>
                </tr>
                <tr>
                    <td><strong>Price</strong></td>
                    <td>:</td>
                    <td><span id="price"></span><input type="hidden" id="price-input"></td>
                </tr>
            </table>

            <div class="jarak20"></div>	
                <button id="btn_send_transaction" onclick="send_order()" type="button" class="block z-[1] before:rounded-md before:block before:absolute before:left-auto before:right-0 before:inset-y-0 before:z-[-1] before:bg-secondary before:w-0 hover:before:w-full hover:before:left-0 hover:before:right-auto before:transition-all leading-none px-[30px] py-[12px] capitalize font-medium text-white text-[14px] xl:text-[16px] relative after:block after:absolute after:inset-0 after:z-[-2] after:bg-primary after:rounded-md after:transition-all">Submit</button>
		</div>
		
	</div>
</div>
</section>
<!-- Popular Properties end -->

<div id="success-box" class="alert-green" style="display:none;">
    <div class="alert-title">Peringatan</div>
    <div class="alert-content">Maaf Data yang anda masukkan salah</div>
    <div style="margin-left:5px;" onclick="confirm_success_box()" class="button-oke">Confirm</div>
    <div onclick="close_success_box()" class="button-oke">Cancel</div>
</div>

@endsection