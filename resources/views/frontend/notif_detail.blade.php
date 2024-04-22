@extends('frontend.master')
@section('content')

@php
    if($data->admin_id == -1) {
        $admin_name = 'Auto Sending';
    } else {
        $admin = \App\Models\AdminsData::findorFail($data->admin_id);
        $admin_name = $admin->name;
    }
    


@endphp

<!-- Popular Properties start -->
<section class="popular-properties py-[80px] lg:py-[120px]">
    <div class="container">

        <div class="grid grid-cols-12 mb-[-30px] gap-[30px] xl:gap-[50px]">
            <div class="col-span-12 md:col-span-12 lg:col-span-12 mb-[30px]">
                <center><img style="width:600px;Object-fit:cover;border-radius:10px;" src="{{ asset('template/images/notif/'.$data->image) }}" class="detail-notif-image" loading="lazy" alt="Notif Image"></center>
                <div class="mt-[55px] mb-[35px]">
                    <span
                        class="block leading-none font-normal text-[18px] text-secondary mb-[15px]">{{ $admin_name }} on {{ date('d F Y', strtotime($data->created_at)) }}</span>
                    <h2 class="font-lora leading-tight text-[22px] md:text-[28px] lg:text-[32px] text-primary mb-[10px] font-medium"> {{ $data->title }}.</h2>

                    <?= $data->message ;?>
                </div>            

            </div>

           
        </div>

    </div>
</section>
<!-- Popular Properties end -->
@endsection