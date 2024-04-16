@extends('frontend.master')
@section('content')



<!-- Hero section start -->
<section class="bg-no-repeat bg-left-bottom xl:bg-right-bottom bg-contain xl:bg-cover flex flex-wrap items-center relative">
    <div class="container">
        <div class="grid grid-cols-12">
            <div class="col-span-12">
                <div class="jarak20">
                    
                    <h3 class="font-lora jarak30 custom-title">
                        Notification List
                    </h3>
                    <div class="tablediv">
                    <table class="table" id="table-notif">
                        <thead>
                            <tr>
                                <th class="lengkung-atas-kiri">#</th>
                                <th>Date</th>
                                <th>Title</th>
                                <th>Message</th>
                                <th>Image</th>
                                <th class="lengkung-atas-kanan">Sender</th>
                                
                            </tr>
                        </thead>
                        <tbody>
                           @php
                                $no=0;
                           @endphp
                           @foreach($data as $key) 

                           @php
                                $no++;
                                $adm = \App\Models\AdminsData::where('id', $key->admin_id);
                                if($adm->count() > 0) {
                                    $admin = $adm->first('name');
                                } else {
                                    $admin = 'Auto-Sending';
                                }
                           @endphp
                                <tr>
                                    <td>{{ $no }}</td>
                                    <td style="text-align: center;">{{ date('d F Y', strtotime($key->created_at)) }}<br>{{ date('H:i:s', strtotime($key->created_at)) }}</td>
                                    <td style="white-space: normal;"><a href="{{ url('notif_detail/'.$key->slug) }}" style="color: blue; font-weight:bold;">{{ $key->title }}</a></td>
                                    <td style="white-space: normal;"><?= substr($key->message, 0, 200) ;?></td>
                                    @if(! empty($key->image)) 
                                        <td><center><a href="{{ asset('template/images/notif/'.$key->image) }}" target="_blank"><img style="width: 100px;border-radius:10px;" class="notif-image" src="{{ asset('template/images/notif/'.$key->image) }}"></a></center></td>
                                    
                                    @else 
                                        <td></td>
                                    @endif

                                    <td>{{ $admin }}</td>
                                    
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