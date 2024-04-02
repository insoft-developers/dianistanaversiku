@extends('frontend.master')
@section('content')



<!-- Hero section start -->
<section class="bg-no-repeat bg-left-bottom xl:bg-right-bottom bg-contain xl:bg-cover flex flex-wrap items-center relative">
    <div class="container">
        <div class="grid grid-cols-12">
            <div class="col-span-12">
                <div class="jarak20">
                    
                    <h3 class="font-lora jarak30 custom-title">
                        Booking List
                    </h3>
                    <div class="tablediv">
                    <table class="table" id="table-riwayat">
                        <thead>
                            <tr>
                                <th class="lengkung-atas-kiri">#</th>
                                <th>No</th>
                                <th>Payment</th>
                                <th>Date</th>
                                <th>Name</th>
                                <th>Business Unit</th>
                                <th>Invoice</th>
                                <th>Booking Date</th>
                                <th>Booking Time</th>
                                <th>Qty</th>
                                <th>Total Price</th>
                                <th class="lengkung-atas-kanan">Description</th>
                               
                                
                            </tr>
                        </thead>
                        <tbody>
                            @php
                            $no=0;
                            @endphp
                            @foreach($transaction as $t)
                            @php
                            $no++;
                            $user = \App\Models\User::findorFail($t->user_id);
                            $bisnis = \App\Models\UnitBisnis::findorFail($t->business_unit_id);
                            @endphp
                            <tr>
                                @if($t->payment_status == 'PAID')
                                <td><a href="{{ url('print_ticket') }}/{{ $t->id }}"><button id="btn_print_{{ $t->id }}" type="button" class="bggreen block z-[1] before:rounded-md before:block before:absolute before:left-auto before:right-0 before:inset-y-0 before:z-[-1] before:bg-secondary before:w-0 hover:before:w-full hover:before:left-0 hover:before:right-auto before:transition-all leading-none px-[10px] py-[8px] capitalize font-small text-white text-[13px] xl:text-[13px] relative after:block after:absolute after:inset-0 after:z-[-2]  after:rounded-md after:transition-all"><i class="fa fa-print"></i> print</button></a></td>
                                @else
                                <td><button id="btn_payment_{{ $t->id }}" onclick="payment_process({{$t->id}})" type="button" class="bgorange block z-[1] before:rounded-md before:block before:absolute before:left-auto before:right-0 before:inset-y-0 before:z-[-1] before:bg-secondary before:w-0 hover:before:w-full hover:before:left-0 hover:before:right-auto before:transition-all leading-none px-[10px] py-[8px] capitalize font-small text-white text-[13px] xl:text-[13px] relative after:block after:absolute after:inset-0 after:z-[-2] after:rounded-md after:transition-all"><i class="fa fa-dollar"></i> payment</button></td>
                                @endif
                                <td>{{ $no }}</td>
                                @if($t->payment_status == 'PAID')
                                <td><i class="fa fa-check"></i> <span style="color:green;">{{ $t->payment_status }}</span></td>
                                @else
                                <td><i class="fa fa-remove"></i> <span style="color: red;">{{ $t->payment_status }}</span></td>
                                @endif
                               
                                <td style="text-align: center;">{{ date('d-m-Y', strtotime($t->created_at)) }}</td>
                                <td>{{ $user->name }}</td>
                                <td>{{ $bisnis->name_unit }}</td>
                                <td style="text-align: center;">{{ $t->invoice }}</td>
                                <td style="text-align: center;">{{ date('d-m-Y', strtotime($t->booking_date)) }}</td>
                                <td style="text-align: center;">{{ $t->start_time.':00 - '.$t->finish_time.':00' }}</td>
                                <td>{{ $t->quantity }}</td>
                                <td style="text-align: right;">Rp. {{ number_format($t->total_price) }}</td>
                                <td>{{ $t->description }}</td>
                                
                                
                                
                            </tr>
                            @endforeach
                        </tbody>
                     </table>
                    </div>
                    
                    <div class="jarak40"></div>
                </div>
            </div>
        </div>
    </div>
</section>


<!-- Hero section end -->




@endsection