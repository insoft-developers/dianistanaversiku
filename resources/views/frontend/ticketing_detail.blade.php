@extends('frontend.master')
@section('content')



<!-- Hero section start -->

<section class="bg-no-repeat bg-left-bottom xl:bg-right-bottom bg-contain xl:bg-cover flex flex-wrap items-center relative">
    <div class="container">
        <div class="grid grid-cols-12">
            <div class="col-span-12">
                <div class="jarak20"></div>
                <div class="grid grid-cols-12">
                    
                </div>
                <div class="grid grid-cols-12 jarak20 gap-[30px]">
                    <div class="col-span-8 md:col-span-8 lg:col-span-8">
                        <div class="card-box">
                           
                            <div class="text-judul"><i class="fa fa-ticket"></i> {{ $detail->subject }}</div>
                            <button id="btn-reply" class="buttons-small fr btn-primary custom-position"><i class="fa fa-reply"></i> Reply</button>
                            <button id="btn-reply-cancel" style="display:none;" class="buttons-small fr b-orange custom-position"><i class="fa fa-remove"></i> Cancel</button>
                            <div class="jarak20"></div>
                            @if($message = Session::get('error'))
                                <div class="alert alert-danger" role="alert">
                                    <span onclick="tutup_alert()" class="btn-colse">x</span>
                                    <?= $message ;?>
                                </div>
                                @endif
                                @if($message = Session::get('success'))
                                    <div class="alert alert-success" role="alert">
                                        <span onclick="tutup_alert()" class="btn-colse">x</span>
                                        <?= $message ;?>
                                    </div>
                                @endif
                            <div id="form-ticket-container" class="jarak20" style="display: none;">
                                
                                <form method="POST" action="{{ route('reply.ticket') }}" enctype="multipart/form-data">
                                    @csrf
                                <input type="hidden" name="ticket_number" value="{{ $detail->ticket_number }}">
                                <input type="hidden" name="user_id" value="{{ Auth::user()->id }}">
                                <input type="hidden" name="is_reply" value="0">
                                <label>Attachment : </label>
                                <input type="file" class="input-ticket w-100" id="document" name="document">
                                <div class="jarak20"></div>
                                <label>Message : </label>
                                <textarea class="input-ticket w-100" placeholder="Describe Your Message" id="message" name="message"></textarea>
                                <div class="jarak15"></div>
                                <center><button type="submit" class="buttons-small btn-success">POST</button></center>
                                </form>
                            </div>
                        </div>
                       
                        
                        <div class="jarak15"></div>
                        @foreach($data as $d)
                            @php
                            if($d->is_reply == 1) {
                                $query = \App\Models\AdminsData::findorFail($d->user_id);
                                $user = $query->name;
                            } else {
                                $query = \App\Models\User::findorFail($d->user_id);
                                $user = $query->name;
                            }


                            @endphp

                            <div class="paper">
                                @if($d->is_reply== 1)
                                <img class="paper-profile" src="{{ asset('template/images/profil.png') }}">
                                @else
                                <img class="paper-profile" src="{{ asset('template/images/person.webp') }}">
                                @endif
                                <span class="paper-user-name">{{ $user }}</span>
                                <div class="paper-date">{{ date('d F Y', strtotime($d->created_at)) }} Pukul {{ date('H:i:s', strtotime($d->created_at)) }} WIB</div>
                                @if($d->document != null)
                                <div class="paper-attachment"><i class="fa fa-file"></i> <a href="{{ url('donwload_ticketing') }}/{{ $d->document }}"> {{ $d->document }}</a></div>
                                @endif
                                <div class="paper-content"><?= $d->message ;?></div>
                            </div>
                        @endforeach
                    </div>
                    
                    <div class="col-span-4 md:col-span-4 lg:col-span-4 mb-[30px]">
                        <div class="card-box">
                            <div class="text-judul">Detail Ticket</div>
                            <div class="jarak20"></div>
                            <label>Number</label>
                            <p>{{ $detail->ticket_number }}</p>
                            <div class="jarak15"></div>
                            <label>Department</label>
                            <p>{{ $category->category_name }}</p>
                            <div class="jarak15"></div>
                            <label>Status</label>
                            @if($detail->status == 0)
                            <span class="badge b-green">Open</span>
                            @else 
                            <span class="badge b-grey">Closed</span>
                            @endif
                            <div class="jarak15"></div>
                            <label>Submitted</label>
                            <p>{{ date('d F Y', strtotime($detail->created_at)) }} Pukul {{ date('H:i:s', strtotime($detail->created_at)) }} WIB</p>
                            <div class="jarak15"></div>
                            <label>Last Reply</label>
                            <p>{{ date('d F Y', strtotime($detail->updated_at)) }} Pukul {{ date('H:i:s', strtotime($detail->updated_at)) }} WIB</p>
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