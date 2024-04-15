<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>Kwitansi {{ $data->invoice }}</title>
    <style>
        .clearfix:after {
  content: "";
  display: table;
  clear: both;
}

a {
  color: #5D6975;
  text-decoration: underline;
}

body {
  position: relative;
  width: 21cm;  
  height: 29.7cm; 
  margin: 0 auto; 
  color: #001028;
  background: #FFFFFF; 
  font-family: Arial, sans-serif; 
  font-size: 12px; 
  font-family: Arial;
}

header {
  padding: 10px 0;
  margin-bottom: 30px;
}

#logo {
  text-align: center;
  margin-bottom: 10px;
}

#logo img {
  width: 90px;
}

h1 {
  border-top: 1px solid  #5D6975;
  border-bottom: 1px solid  #5D6975;
  color: #5D6975;
  font-size: 2.4em;
  line-height: 1.4em;
  font-weight: normal;
  text-align: center;
  margin: 0 0 20px 0;
  background: url({{ asset('template/images/dimension.png') }});
}

#project {
  float: left;
}

#project span {
  color: #5D6975;
  text-align: right;
  width: 52px;
  margin-right: 10px;
  display: inline-block;
  font-size: 0.8em;
}

#company {
  float: right;
  text-align: right;
}

#project div,
#company div {
  white-space: nowrap;        
}

table {
  width: 100%;
  border-collapse: collapse;
  border-spacing: 0;
  margin-bottom: 20px;
}

table tr:nth-child(2n-1) td {
  background: #F5F5F5;
}

table th,
table td {
  text-align: center;
}

table th {
  padding: 5px 20px;
  color: #5D6975;
  border-bottom: 1px solid #C1CED9;
  white-space: nowrap;        
  font-weight: normal;
}

table .service,
table .desc {
  text-align: left;
}

table td {
  padding: 20px;
  text-align: right;
}

table td.service,
table td.desc {
  vertical-align: top;
}

table td.unit,
table td.qty,
table td.total {
  font-size: 1.2em;
}

table td.grand {
  border-top: 1px solid #5D6975;;
}

#notices .notice {
  color: #5D6975;
  font-size: 1.2em;
}

footer {
  color: #5D6975;
  width: 100%;
  height: 30px;
  position: absolute;
  bottom: 0;
  border-top: 1px solid #C1CED9;
  padding: 8px 0;
  text-align: center;
}
    </style>
  </head>
  <body onload="window.print();">
    <header class="clearfix">
      @php
        $user = \App\Models\User::findorFail($data->user_id);
        if($user->penyelia == "SDP") {
            $com = "PT. SARANA DIAN PROPERTI";
        } else {
            $com = "PT. DIAN MEGA SARANA INDONESIA";
        }

      @endphp


      <div id="logo">
        <img src="{{ asset('template/images/dian.png') }}">
      </div>
      <h1>PAYMENT RECEIPT <br><small>No. {{ $data->invoice }}</small></h1>
      <div id="company" class="clearfix">
        <div>{{ $com }}</div>
        <div>{{ $setting->address }}</div>
        <div>{{ $setting->phone }}</div>
        <div><a href="mailto:company@example.com">{{ $setting->email }}</a></div>
      </div>
      <div id="project">
        
        <div><span>RECEIVED FROM  :</span></div>
        <div><span>NAME</span> {{ $user->name }}</div>
        <div><span>BLOK/NO</span> {{ $user->blok }} / {{ $user->nomor_rumah }}</div>
        <div><span>EMAIL</span> <a href="mailto:john@example.com">{{ $user->email }}</a></div>
        <div><span>DATE</span> {{ date('d F Y', strtotime($data->paid_at)) }}</div>
        
      </div>
    </header>
    <main>
      <table>
        <thead>
          <tr>
           
            <th class="desc">DESCRIPTION</th>
            <th>PRICE</th>
            <th>QTY</th>
            <th>TOTAL</th>
          </tr>
        </thead>
        <tbody>
          <tr>
           
            <td class="desc"><strong>{{ $payment->payment_name }}</strong> <br> {{ $payment->payment_desc }}</td>
            <td class="unit">{{ number_format($data->amount) }}</td>
            <td class="qty">1</td>
            <td class="total">{{ number_format($data->amount) }}</td>
          </tr>
          
          <tr>
            <td colspan="3">SUBTOTAL</td>
            <td class="total">Rp. {{ number_format($data->amount) }}</td>
          </tr>
          
          <tr>
            <td colspan="3" class="grand total">GRAND TOTAL</td>
            <td class="grand total" style="font-size: 16px;"><strong>Rp. {{ number_format($data->amount) }}</strong></td>
          </tr>
        </tbody>
      </table>
      <div id="notices">
        <div>NOTICE:</div>
        <div class="notice">Paid By {{ $data->payment_method }} via {{ $data->payment_channel }}</div>
      </div>
    </main>
    <footer>
      Invoice was created on a computer and is valid without the signature and seal.
    </footer>
  </body>
</html>