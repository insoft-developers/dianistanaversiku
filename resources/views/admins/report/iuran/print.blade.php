<!DOCTYPE html>
<html>
<head>
	<title>Print Laporan Detail Kas</title>
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
                font-family: 'Courier New', Courier, monospace;
            }
            table td{
                font-size: 13px;
            }

            .logo-atas{
                width: 40px;
                height: 40px;
                position: absolute;
                left: 311px;
                top: 15px;
            }

        }

            /* print styles */
            @media print {

                body {
                    margin: 0;
                    color: #000;
                    background-color: #fff;
                }
                table td{
                    font-size: 13px;
                }
                
                .logo-atas{
                    width: 40px;
                    height: 40px;
                    position: absolute;
                    left: 240px;
                    top: 15px;
                }
                table.print-friendly tr td, table.print-friendly tr th {
                    page-break-inside: avoid;
                }

            }
    </style>
</head>
<body onload="window.print();">
{{-- <body> --}}
    
    <div class="row">
    <div class="col-md-12">
    <div class="card">
    <div class="card-body">
    <table class="table table-striped">
        <thead>
            <tr>
            <th colspan="8"><center><img class="logo-atas" src="{{ asset('assets/template/main/img/dianlogo.png') }}"><h4>DIAN ISTANA<br>Laporan Detail Kas Masuk</h4><br>Tanggal : {{ date('d F Y', strtotime($awal)) }} s.d {{ date('d F Y', strtotime($akhir)) }}</center></th>
            </tr>
            <tr>
                <th>No</th>
                <th>Invoice/Time</th>
                <th>Penyelia</th>
                <th>User</th>
                <th>Description</th>
                <th>Amount</th>
                <th>Date</th>

            </tr>
        </thead>
        <tbody>
            @php
            $no= 0;
            $total = 0;
            @endphp
            @foreach($data as $key)
            @php
            $no++;
            $total = $total + $key->amount;
            $users = \App\Models\User::where('id', $key->user_id);
            if($users->count() > 0) {
                $user = $users->first();
                $user_name = $user->name;
                $penyelia = $user->penyelia;
                
            } else {
                $user_name = 'no-data';
                $penyelia = '';
            }
            @endphp
            <tr>
            <td>{{ $no }}</td>
            <td>{{ $key->invoice }}<br>{{ date('H:i:s', strtotime($key->paid_at)) }}</td>
            
            <td>{{ $penyelia }}</td>
            <td>{{ $user_name }}</td>
            <td>{{ $key->payment_name }}</td>
            <td style="text-align: right;white-space:nowrap;">IDR {{ number_format($key->amount) }}</td>
            <td style="white-space:nowrap;">{{ date('d-m-Y', strtotime($key->paid_at)) }}</td>
            </tr>
            @endforeach
    </tbody>
    <tfoot>
        <tr>
            <th colspan="2"></th>
            <th colspan="3">TOTAL NILAI TRANSAKSI</th>
            <th style="text-align: right;white-space:nowrap;">IDR {{ number_format($total) }}</th>
            <th></th>
        </tr>
    </tfoot>
    </table>


    </div> 
    </div> 
    </div>
    </div> 

</body>
</html>