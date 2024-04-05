@extends('frontend.master') @section('content') 



<!-- Hero section start -->
<section class="bg-no-repeat bg-left-bottom xl:bg-right-bottom bg-contain xl:bg-cover flex flex-wrap items-center relative">
    <div class="container">
        <div class="grid grid-cols-12">
            <div class="col-span-12 rata-tengah">
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
                    Profile Setting
                </h3>
                  
                <div class="card-box jarak20">
                    <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data">
                        @csrf
                    <img id="img-pro" class="img-pro" src="{{ $data->foto == NULL || $data->foto == '' ? asset('template/images/profil_icon.png') : asset('storage/profile/'.$data->foto)  }}">    
                    <a class="image-pickup"><i class="fa fa-edit"></i></a>
                    <a onclick="remove_foto()" style="display: none;" class="cancel-upload-container"><i class="fa fa-remove"></i></a>
                    <input style="display: none;" type="file" name="foto" id="foto" accept=".jpg, .jpeg, .png">
                    <div class="jarak20"></div>
                    <label>Name : </label>
                    <input readonly type="text" class="input-ticket w-100 readonly" value="{{$data->name}}">
                    <div class="jarak20"></div>
                    <label>Username : </label>
                    <input readonly type="text" class="input-ticket w-100 readonly" value="{{$data->username}}">
                    <div class="jarak20"></div>
                    <label>Email : </label>
                    <input readonly type="text" class="input-ticket w-100 readonly" value="{{$data->email}}">
                    <div class="jarak20"></div>
                    <label>Whatsapp Number : </label>
                    <input name="whatsapp_number" type="text" class="input-ticket w-100" placeholder="ex: +6282165174567" value="{{ $data->no_hp }}">
                    <div class="jarak20"></div>
                    <label>Jenis Kelamin : </label>
                    <select name="jenis_kelamin" class="input-ticket w-100">
                        <option value=""> -- Pilih Jenis Kelamin-- </option>
                        <option <?php if($data->jenis_kelamin == 'Laki-laki') echo 'selected'; ?> value="Laki-laki">Laki-Laki</option>
                        <option <?php if($data->jenis_kelamin == 'Perempuan') echo 'selected'; ?> value="Perempuan">Perempuan</option>
                    </select>
                    <div class="jarak20"></div>
                    <label>Whatsapp Emergency : </label>
                    <input name="whatsapp_emergency" type="text" class="input-ticket w-100" placeholder="ex: +6282165174567" value="{{ $data->whatsapp_emergency }}">
                    <div class="jarak20"></div>
                    <label>User Level : </label>
                    <input readonly  type="text" class="input-ticket w-100 readonly" value="{{ Auth::user()->level }}">

                    <div class="jarak40"></div>
                    <center><button type="submit" class="buttons btn-success">Submit</button></center>
                    </form>
                </div>
                <div class="jarak40"></div>
                
            </div>
        </div>
    </div>
</section>
@endsection