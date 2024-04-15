<!DOCTYPE html>
<html>
<head>
	<title>Transaction Printing</title>
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
    @php
    $users = \App\Models\User::where('id', $transaction->user_id);
    if($users->count() > 0) {
        $user = $users->first();
        $userdata = $user->name;
    } else {
        $userdata = "no-data";
    }

    $units = \App\Models\UnitBisnis::where('id', $transaction->business_unit_id);
    if($units->count() > 0) {
        $unit = $units->first();
        $unitdata = $unit->name_unit;
    } else {
        $unitdata = "no-data";
    }   
    @endphp
    <tr><th>User Name</th><th>{{ $userdata }}</th></tr>
    <tr><th>Facility</th><th>{{ $unitdata }}</th></tr>
    <tr><th>Invoice</th><th>{{ $transaction->invoice }}</th></tr>
    <tr><th>Booking Date</th><th>{{ date('d F Y', strtotime($transaction->booking_date)) }}</th></tr>
    <tr><th>Booking Time</th><th>{{ $transaction->start_time }}.00 WIB - {{ $transaction->finish_time }}.00 WIB</th></tr>
    <tr><th>Number of User</th><th>{{ $transaction->quantity }}</th></tr>
    <tr><th>Total Price</th><th>Rp. {{ number_format($transaction->total_price) }}</th></tr>
    <tr><th>Description</th><th>{{ $transaction->description }}</th></tr>
    <tr><th>Package</th><th>{{ $transaction->package_name }}</th></tr>
    <tr><th>Payment Status</th><th>{{ $transaction->payment_status }}</th></tr>
    <tr><th>Payment Method</th><th>{{ $transaction->payment_method }}</th></tr>
    <tr><th>Payment Channel</th><th>{{ $transaction->payment_channel }}</th></tr>
    <tr><th>Paid At</th><th>{{ date('d F Y', strtotime($transaction->paid_at)) }}</th></tr>
    <tr><th>Created At</th><th>{{ date('d F Y', strtotime($transaction->created_at)) }}</th></tr>
    </tbody>
    </table>
    </div>
    </div>
    </div>
    </div>

</body>
</html>