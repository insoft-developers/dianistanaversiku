@extends('frontend.master')
@section('content')



<!-- Hero section start -->
<section class="bg-no-repeat bg-left-bottom xl:bg-right-bottom bg-contain xl:bg-cover flex flex-wrap items-center relative">
    <div class="container">
        <div class="grid grid-cols-12">
            <div class="col-span-12">
                <div class="jarak20">
                    
                    <h3 class="font-lora jarak30 custom-title">
                        Payment List
                    </h3>
                    <div class="tablediv">
                    <table class="table" id="table-payment">
                        <thead>
                            <tr>
                                <th class="lengkung-atas-kiri">#</th>
                                <th>Action</th>
                                <th>Date</th>
                                <th>Status</th>
                                <th>Payment Name</th>
                                <th>Type</th>
                                <th>Due Date</th>
                                <th>Period</th>
                                <th>Amount</th>
                                <th class="lengkung-atas-kanan">Billed To</th>
                                
                            </tr>
                        </thead>
                        <tbody>
                        @php
                        $nomor=0;
                        @endphp
                        @foreach($data as $key)
                        @php

                        

                        if ( !function_exists( 'pembulatan' ) ) {
                            function pembulatan($uang)
                            {
                                $ratusan = substr($uang, -3);
                                if($ratusan<500) {
                                    $akhir = $uang - $ratusan;
                                }   
                                else {
                                    $akhir = $uang + (1000-$ratusan);
                                }
                            
                                return $akhir;
                            }
                        }

                        $nomor++;
                        if($key->payment_type == 1 ) {
                            $setting = \App\Models\Setting::findorFail(1);
                            $tunggakan = \App\Models\Tunggakan::where('user_id', Auth::user()->id)->where('payment_id', '>', 0);
                            $adjust = \App\Models\Tunggakan::where('user_id', Auth::user()->id)->where('payment_id', -1)->sum('amount');
                            if($tunggakan->count() > 0) {
                                $detail = \App\Models\PaymentDetail::where('payment_id', $key->id)
                                    ->where('user_id', Auth::user()->id)
                                    ->where('payment_status','PAID');
                                if($detail->count() > 0 ) {
                                    $detail_data = $detail->first();
                                    $amount = $detail_data->amount;
                                } else {
                                    $user = \App\Models\User::findorFail(Auth::user()->id);
                                    $iuran = $user->iuran_bulanan;
                                    $jumlah = $tunggakan->sum('amount');
                                    $percent  = $setting->percent_denda;
                                    $nomi = $percent * $jumlah / 100;
                                    $nom = pembulatan((int)$nomi);
                                    $total_tunggakan = $jumlah + (int)$nom;
                                    $amount = $total_tunggakan + $iuran + $adjust;
                                }
                                

                            } else {
                                $user = \App\Models\User::findorFail(Auth::user()->id);
                                $amount = $user->iuran_bulanan;
                            }

                            
                        } else {
                            $amount = $key->payment_amount;
                        }

                        if($key->payment_dedication > 0) {
                            $payee = \App\Models\User::findorFail($key->payment_dedication);
                            $bill = $payee->name;
                        } else {
                            $bill = "Bill to All";
                        }
                        
                        $det = \App\Models\PaymentDetail::where('payment_id', $key->id)
                                ->where('payment_status', 'PAID')
                                ->where('user_id', Auth::user()->id);
                        $count = $det->count();

                        @endphp
                         <tr>
                            <td>{{ $nomor }}</td>
                            <td>
                            @if($count > 0)    

                            <a href="{{ url('print_kwitansi') }}/{{ $key->id }}"><button id="btn_print_{{ $key->id }}" type="button" class="bggreen block z-[1] before:rounded-md before:block before:absolute before:left-auto before:right-0 before:inset-y-0 before:z-[-1] before:bg-secondary before:w-0 hover:before:w-full hover:before:left-0 hover:before:right-auto before:transition-all leading-none px-[10px] py-[8px] capitalize font-small text-white text-[13px] xl:text-[13px] relative after:block after:absolute after:inset-0 after:z-[-2]  after:rounded-md after:transition-all"><i class="fa fa-print"></i> print</button></a>
                            @else
                            <button id="btn_payment_{{ $key->id }}" onclick="payment_process({{$key->id}})" type="button" class="bgorange block z-[1] before:rounded-md before:block before:absolute before:left-auto before:right-0 before:inset-y-0 before:z-[-1] before:bg-secondary before:w-0 hover:before:w-full hover:before:left-0 hover:before:right-auto before:transition-all leading-none px-[10px] py-[8px] capitalize font-small text-white text-[13px] xl:text-[13px] relative after:block after:absolute after:inset-0 after:z-[-2] after:rounded-md after:transition-all"><i class="fa fa-dollar"></i> pay now</button>
                            @endif
                        
                            </td>
                            <td>{{ date('d F Y', strtotime($key->created_at)) }}</td>
                            <td>
                                @if($count > 0) 
                                <span style="color: green;"><i class="fa fa-check"></i> PAID</span>
                                @else
                                <span style="color: red;"><i class="fa fa-remove"></i> PENDING</span>
                                @endif
                            </td>
                            <td><strong>{{ $key->payment_name }}</strong><br>{{ $key->payment_desc }}</td>
                            <td>
                                @if($key->payment_type == 1)
                                Iuran Bulanan
                                @elseif($key->payment_type == 2)
                                Iuran Rutin
                                @else
                                Sekali Bayar
                                @endif
                            </td>
                            
                            
                            <td>{{ date('d F Y', strtotime($key->due_date)) }}</td>
                            <td>{{ $key->periode }}</td>
                            
                            <td style="text-align: right;">
                                <strong>{{ number_format($amount) }}</strong>
                            </td>
                            <td>{{ $bill }}</td>
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