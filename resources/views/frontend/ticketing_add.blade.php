@extends('frontend.master')
@section('content')



<!-- Hero section start -->

<section class="bg-no-repeat bg-left-bottom xl:bg-right-bottom bg-contain xl:bg-cover flex flex-wrap items-center relative">
    <div class="container">
        <div class="grid grid-cols-12">
            <div class="col-span-12">
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
                    <h3 class="font-lora jarak30 custom-title">
                        Open New Ticket
                    </h3>
                      
                    <div class="card-box jarak20">
                        <form method="POST" action="{{ route('open.ticket') }}" enctype="multipart/form-data">
                            @csrf

                        <img class="img-pro" src="{{ $data->foto == NULL || $data->foto == '' ? asset('template/images/profil_icon.png') : asset('storage/profile/'.$data->foto)  }}">    
                        <label>Department : </label>
                        <select class="input-ticket w-100" id="department" name="department">
                            <option value="">Select Department</option>
                            @foreach($category as $item)
                                <option <?php if($item->category_name == "Komplain") echo 'selected'; ?> value="{{ $item->id }}">{{ $item->category_name }}</option>
                            @endforeach
                        </select>
                        <div class="jarak20"></div>
                        <label>Priority : </label>
                        <select class="input-ticket w-100" id="priority" name="priority">
                            <option value="">Select Priority</option>
                            <option value="Low">Low</option>
                            <option <?php echo 'selected' ;?>  value="Medium">Medium</option>
                            <option value="High">High</option>
                            <option value="Critical">Critical</option>
                        </select>
                        <div class="jarak20"></div>
                        <label>Subject : </label>
                        <input type="text" class="input-ticket w-100" placeholder="Enter Subject" id="subject" name="subject">
                        <div class="jarak20"></div>
                        <label>Attachment : </label>
                        <input type="file" class="input-ticket w-100" id="document" name="document">
                        <div class="jarak20"></div>
                        <label>Message : </label>
                        <textarea class="input-ticket w-100" placeholder="Describe Your Message" id="message" name="message"></textarea>
                        <div class="jarak40"></div>
                        <center><button type="submit" class="buttons btn-success">Submit</button></center>
                        </form>  
                    </div>
                    <div class="jarak40"></div>
                    
                    
                    
                    <div class="jarak40"></div>
                </div>
            </div>
        </div>
    </div>
</section>


<!-- Hero section end -->




@endsection