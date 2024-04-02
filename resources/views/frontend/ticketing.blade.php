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