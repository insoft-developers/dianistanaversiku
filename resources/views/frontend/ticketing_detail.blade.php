@extends('frontend.master')
@section('content')



<!-- Hero section start -->

<section class="bg-no-repeat bg-left-bottom xl:bg-right-bottom bg-contain xl:bg-cover flex flex-wrap items-center relative">
    <div class="container">
        <div class="grid grid-cols-12">
            <div class="col-span-12">
                <div class="jarak20"></div>
                <div class="grid grid-cols-12">
                    <div class="col-span-12 md:col-span-12 lg:col-span-12">
                        <div class="card-box">
                            <div class="text-judul"><i class="fa fa-pen"></i> {{ $detail->subject }}</div>
                            <button class="buttons-small fr b-green custom-position"><i class="fa fa-reply"></i> Reply</button>
                        </div>
                    </div>
                </div>
                <div class="grid grid-cols-12 jarak20 gap-[30px]">
                    <div class="col-span-8 md:col-span-8 lg:col-span-8 mb-[30px]">
                        
                    </div>
                    <div class="col-span-4 md:col-span-4 lg:col-span-4 mb-[30px]">
                        <div class="card-box">
                            <div class="text-judul">Detail Ticket</div>
                            <label>Number</label>
                            <p>{{ $detail->ticket_number }}</p>
                            <div class="jarak20"></div>
                            <label>Department</label>
                            <p>{{ $category->category_name }}</p>
                            <div class="jarak20"></div>
                            <label>Status</label>
                            @if($detail->status == 0)
                            <span class="badge b-green">Open</span>
                            @else 
                            <span class="badge b-grey">Closed</span>
                            @endif
                            <div class="jarak20"></div>
                            <label>Submitted</label>
                            <p>{{ date('d-m-Y H:i:s', strtotime($detail->created_at)) }}</p>
                            <div class="jarak20"></div>
                            <label>Last Reply</label>
                            <p>{{ date('d-m-Y H:i:s', strtotime($detail->updated_at)) }}</p>
                        </div>
                    </div>
                </div>
                    
                <div class="jarak40"></div>
                
            </div>
        </div>
    </div>
</section>


<!-- Hero section end -->




@endsection