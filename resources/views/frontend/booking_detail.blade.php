@extends('frontend.master') @section('content') 

<!-- Hero section end -->
<!-- Popular Properties start -->
<section class="popular-properties pb-[60px] lg:pb-[60px]">
<div class="container">
    <h3 class="font-lora jarak30 custom-title">
        Booking Detail
    </h3>
    <div class="jarak20"></div>
	<div class="grid grid-cols-12 mb-[-30px] gap-[30px] xl:gap-[50px]">
        <div class="col-span-12 md:col-span-6 lg:col-span-6 sm:col-span-12 xs:col-span-6  mb-[30px]">
			<aside class="mb-[-60px] asidebar">
			<div class="mb-[60px]">
				<h3 class="text-primary leading-none text-[24px] font-lora underline mb-[40px] font-medium">Please complete this form.</h3>
                <input type="hidden" id="current_month" value="{{ date('m') }}">
                <input type="hidden" id="current_year" value="{{ date('Y') }}">
                <input type="hidden" id="product_id" value="{{ $data->id }}">
                <input type="hidden" id="harga_4x" value="{{ $data->harga_membership_4x }}">
				<input type="hidden" id="harga_8x" value="{{ $data->harga_membership_8x }}">
                <input type="hidden" id="harga_non_member" value="{{ $data->harga_non_member }}">
                <input type="hidden" id="harga_tamu_warga" value="{{ $data->harga_tamu_warga }}">
                
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
                

                @if($data->kategori == "Kolam Renang" && Auth::user()->level == "guest")
                <select id="paket" name="paket" class="custom-input jarak20 w-100 mr-[10px]">
                    <option value="">Select Package</option>
                    <option value="1">Membership 4x pertemuan</option>
                    <option value="2">Membership 8x pertemuan</option>
                    <option value="3">Non Member</option>
                    <option value="4">Paket Khusus Tamu Warga</option>
                </select>
                
                    
                <input class="custom-input jarak20 w-100 mr-[10px]" type="number" id="input_q"  placeholder="quantity">
                @endif 
			</div>
			
			
			</aside>
		</div>
		<div class="col-span-12 md:col-span-6 lg:col-span-6 mb-[30px]">
			{{-- <img src="{{ asset('storage/unit-bisnis') }}/{{ $data->image }}" class="side-booking-image" loading="lazy" alt="Elite Garden Resedence." width="770" height="465"> --}}
            @php
                function incrementalHash($len = 5){
                    $charset = "0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ";
                    $base = strlen($charset);
                    $result = '';

                    $now = explode(' ', microtime())[1];
                    while ($now >= $base){
                        $i = $now % $base;
                        $result = $charset[$i] . $result;
                        $now /= $base;
                    }
                    return substr($result, -5);
                }

                $random = random_int(1000, 9999);
                $invoice = incrementalHash();
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
                    <td><strong>Package</strong></td>
                    <td>:</td>
                    <td><span id="package"></span><input type="hidden" id="package-input"></td>
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