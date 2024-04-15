@extends('frontend.master')
@section('content')



<!-- Hero section start -->

<section class="bg-no-repeat bg-left-bottom xl:bg-right-bottom bg-contain xl:bg-cover flex flex-wrap items-center relative">
    <div class="container">
        <div class="grid grid-cols-12">
            <div class="col-span-12">
                <div class="jarak20"></div>
                    
                   
                    <div class="jarak40"></div>
                    <h3 class="font-lora jarak30 custom-title">
                        Ticketing List
                    </h3>
                    <a href="{{ url('ticketing_add') }}"><div class="button-new-ticket"><i class="fa fa-ticket"></i> Open New Ticket</div></a> 
                    <div class="tablediv">
                    <table class="table" id="table-ticketing">
                        <thead>
                            <tr>
                                <th class="lengkung-atas-kiri">Number</th>
                                <th>Department</th>
                                <th>Subject</th>
                                <th>Status</th>
                                <th>Submitted</th>
                                <th class="lengkung-atas-kanan">Last Reply</th>
                               
                                
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($data as $d)
                            @php
                                $category = \App\Models\TicketingCategory::findorFail($d->department);
                            @endphp
                            <tr>
                            <td><a href="{{ url('/ticketing_detail') }}/{{ $d->ticket_number }}"><span style="color:blue;font-weight:bold;">{{ $d->ticket_number }}</span></a></td>
                            <td>{{ $category->category_name }}</td>
                            <td><a href="{{ url('/ticketing_detail') }}/{{ $d->ticket_number }}"><span style="color:blue;font-weight:bold;">{{ $d->subject }}</span></a><br><span style="font-style: italic"><?= substr($d->message, 0, 100) ;?>...</span></td>
                            <td>
                                @if($d->status == 0)
                                <span class="badge b-green">Waiting for Admin Response</span>
                                @elseif($d->status == 1)
                                <span class="badge b-green">Waiting For Your Response</span>
                                @elseif($d->status == 2)
                                <span class="badge b-orange">On Hold</span>
                                @elseif($d->status == 3)
                                <span class="badge b-grey">Ticket Resolved</span>
                                @endif
                                
                            </td>
                            <td>{{ date('d-m-Y', strtotime($d->created_at)) }}<br>{{ date('H:i:s', strtotime($d->created_at)) }} WIB</td>
                            <td>{{ date('d-m-Y', strtotime($d->updated_at)) }}<br>{{ date('H:i:s', strtotime($d->updated_at)) }} WIB</td>
                            </tr>
                            @endforeach
                        </tbody>
                     </table>
                    </div>
                    
                    <div class="jarak40"></div>
                
            </div>
        </div>
    </div>
</section>


<!-- Hero section end -->




@endsection