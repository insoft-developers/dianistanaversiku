<!DOCTYPE html>
<html>
<head>
	<title>Printing</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('assets/template/main/img/dianlogo.png') }}">
    <link href="{{ asset('') }}assets/template/src/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
    <style>
         .img-detail{
            width: 141px;
            height: 176px;
            object-fit: cover;
            border-radius: 5px;
        }

        @media screen {

            body {
            margin-left: 15em;
            margin-right: 15em;
            margin-top: 5em;
            margin-bottom: 5em;
            color: #fff;
            background-color: #000;
            }

            }

            /* print styles */
            @media print {

            body {
            margin: 0;
            color: #000;
            background-color: #fff;
            }

            }
    </style>
</head>
<body onload="window.print();">
    
    <div class="row">
    <div class="col-md-12">
    <div class="card">
    <div class="card-body">
    <table class="table table-bordered table-striped">
    <tbody>
    @if($user->foto != null && $user->foto != '') 
        <tr><th>Foto</th><th><img class="img-detail" src="{{ asset('storage/profile/'.$user->foto) }}"></th></tr>
    @else 
        <tr><th>Foto</th><th><img class="img-detail" src="{{ asset('template/images/profil_icon.png') }}"></th></tr>
    @endif
    
    <tr><th>Name</th><th>{{ $user->name }}</th></tr>
    <tr><th>Username</th><th>{{ $user->username }}</th></tr>
    <tr><th>Email</th><th>{{ $user->email }}</th></tr>
    <tr><th>Jenis Kelamin</th><th>{{ $user->jenis_kelamin }}</th></tr>
    <tr><th>Whatsapp</th><th>{{ $user->no_hp }}</th></tr>
    <tr><th>Level</th><th>{{ $user->level }}</th></tr>
    @if($user->is_active == 1) 
        <tr><th>Status</th><th>Active</th></tr>
    @else
        <tr><th>Status</th><th>Not Active</th></tr>
    @endif
    
  
    
    
    <tr><th>Penyelia</th><th>{{ $user->penyelia }}</th></tr>
    <tr><th>Blok/No Rumah</th><th>{{ $user->blok }} / {{ $user->nomor_rumah }}</th></tr>
    <tr><th>Daya Listrik</th><th>{{ $user->daya_listrik }}</th></tr>
    <tr><th>Luas Tanah</th><th>{{ $user->luas_tanah }}</th></tr>
    <tr><th>Iuran Bulanan</th><th>Rp. {{ number_format($user->iuran_bulanan) }}</th></tr>
    <tr><th>Whatsapp Emergency</th><th>{{ $user->whatsapp_emergency }}</th></tr>
    <tr><th>Keterangan</th><th>{{ $user->keterangan }}</th></tr>
    <tr><th>Alamat Surat Menyurat</th><th>{{ $user->alamat_surat_menyurat }}</th></tr>
    <tr><th>No Telp Rumah</th><th>{{ $user->nomor_telepon_rumah }}</th></tr>
    <tr><th>PDAM ID</th><th>{{ $user->id_pelanggan_pdam }}</th></tr>
    <tr><th>PLN Meter</th><th>{{ $user->nomor_meter_pln }}</th></tr>
    <tr><th>Mulai Menempati</th><th>{{ date('d-m-Y', strtotime($user->mulai_menempati)) }}</th></tr>
    <tr><th>Created At</th><th>{{ date('d-m-Y', strtotime($user->created_at)) }}</th></tr>
   
  
    </tbody>
    </table>


    </div> 
    </div> 
    </div>
    </div> 

</body>
</html>