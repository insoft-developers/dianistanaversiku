<!DOCTYPE html>
<html>
<head>
	<title>Dian Istana - Form Permohonan</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('assets/template/main/img/dianlogo.png') }}">
    <link href="{{ asset('') }}assets/template/src/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
    <meta
			name="viewport"
			content="width=device-width, initial-scale=1"
		/>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>

    @include('admins.user.print_css')
   
</head>
{{-- <body onload="window.print();"> --}}
<body>
    
    <div class="row">
    <div class="col-md-12">
        <div class="top-title">
            <p>PROPERTY MANAGEMENT<br>DIAN ISTANA
                <br>
                Telp:{{ $setting->phone }} / WA TEXT {{ $setting->hp }}
            </p>

        </div>
        <p class="middle-title">FORM PERMOHONAN</p>
        <p class="kepada">
            Kepada :
            <br>
            Property Management Dian Istana
            <br>
            Di Tempat
        </p>
        <p>Yang bertanda tangan dibawah ini :</p>
        <table class="table">
            <tr>
                <td>Nama</td>
                <td>:</td>
                <td>{{ $data->name }}</td>
            </tr>
            <tr>
                <td>Alamat</td>
                <td>:</td>
                <td>{{ $data->alamat_surat_menyurat }}</td>
            </tr>
            <tr>
                <td>No HP</td>
                <td>:</td>
                <td>{{ $data->no_hp }}</td>
            </tr>
            <tr>
                <td>Status </td>
                <td>:</td>
                <td>PEMILIK ATAU KONTRAKTOR</td>
            </tr>
        </table>
        <p>Dengan ini mengajukan permohonan : </p>
        <ol>
            <li>Pasang baru listrik dengan daya .................................</li>
            <li>Tambah daya lisrik dari ....................menjadi..............</li>
            <li>Pemasangan baru PDAM</li>
            <li>Pindah pohon atau penggeseran pohon.</li>
            <li>Renovasi / Perbaikan Rumah.......................................
                <br>
                Tanggal Mulai.....................Tanggal Selesai ...............
                <br>
                Nominal Jaminan Rp. ............. No.Rek BCA ....................
            </li>
            <li>Lain lain........................................................</li>

        </ol>
        <p>Demikian atas perhatian dan kerjasamanya kami ucapkan. Terima Kasih.</p>
        <p>Surabaya, ...................................</p>
        <table class="table">
            <tr>
                <td style="text-align: center;">Pemohon</td>
                <td style="text-align: center;">Penerima</td>
                <td style="text-align: center;">Menyetujui</td>
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td></td>
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td></td>
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td></td>
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td></td>
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td></td>
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td></td>
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td></td>
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td></td>
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td></td>
            </tr>
            <tr>
                <td style="text-align: center;">({{ $data->name }})</td>
                <td style="text-align: center;">(.................)</td>
                <td style="text-align: center;">(.................)</td>
            </tr>
        </table>
        
    </div>
    </div> 
    
</body>
</html>