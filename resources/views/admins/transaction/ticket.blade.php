
<!DOCTYPE html>
<html>
<head>

	
  <meta charset="utf-8" />
  <meta http-equiv="x-ua-compatible" content="ie=edge" />
  <title>Dian Istana</title>
  <meta name="AdsBot-Google" content="noindex follow" />
  <meta name="description" content="Bery-Real Estate Listing Template">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />  
  <link rel="stylesheet" href=" https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />

    <style>
        * {
  box-sizing: border-box;
}
html, body {
  height: 100%;
  margin: 0;
}
body {
  @import url('https://fonts.googleapis.com/css?family=Ubuntu:300,400,500,700');
  font-family: 'Ubuntu', sans-serif;
  background-color: #3a394a;
  height: 100%;
  -webkit-font-smoothing: antialiased;
  -moz-osx-font-smoothing: grayscale;
  text-align: center;
  color: #1c1c1c;
  display: flex;
  justify-content: center;
}

.myqrcode{
    position: absolute;
    right: 18px;
    top: 25px;
}

.product-title {
    font-size: 15px;
    width: 160px;
}
.receipt-title {
    font-size: 17px;

}

.ticket-system {
  max-width: 385px;
  .top {
    display: flex;
    align-items: center;
    flex-direction: column;
    .title {
      font-weight: normal;
      font-size: 1.6em;
      text-align: left;
      margin-left: 20px;
      margin-bottom: 50px;
      color: #fff;
    }
    .printer {
      width: 90%;
      height: 20px;
      border: 5px solid #fff;
      border-radius: 10px;
      box-shadow: 1px 3px 3px 0px rgba(0, 0, 0, 0.2);
    }
  }

  .receipts-wrapper {
    overflow: hidden;
    margin-top: -10px;
    padding-bottom: 10px;
  }

  .receipts {
    width: 100%;
    display: flex;
    align-items: center;
    flex-direction: column;
    transform: translateY(-510px);

    animation-duration: 2.5s;
    animation-delay: 500ms;
    animation-name: print;
    animation-fill-mode: forwards;


    .receipt {
      padding: 25px 30px;
      text-align: left;
      min-height: 200px;
      width: 88%;
      background-color: #fff;
      border-radius: 10px 10px 20px 20px;
      box-shadow: 1px 3px 8px 3px rgba(0, 0, 0, 0.2);

      .airliner-logo {
        max-width: 80px;
      }

      .route {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin: 30px 0;
        font-size: 7px;

        .plane-icon {
          width: 30px;
          height: 30px;
          transform: rotate(90deg);
        }
        h2 {
          font-weight: 300;
          font-size: 2.2em;
          margin: 0;
        }
      }

      .details {
        display: flex;
        justify-content: space-between;
        flex-wrap: wrap;

        .item {
          display: flex;
          flex-direction: column;
          min-width: 70px;

          span {
            font-size: .8em;
            color: rgba(28, 28, 28, .7);
            font-weight: 500;
          }
          h3 {
            margin-top: 10px;
            margin-bottom: 25px;
          }
        }
      }

      &.qr-code {
        height: 110px;
        min-height: unset;
        position: relative;
        border-radius: 20px 20px 10px 10px;
        display: flex;
        align-items: center;

        //TODO: replace with svg
        &::before {
          content: '';
          background: linear-gradient(to right, #fff 50%, #3f32e5 50%);
          background-size: 22px 4px, 100% 4px;
          height: 4px;
          width: 90%;
          display: block;
          left: 0;
          right: 0;
          top: -1px;
          position: absolute;
          margin: auto;
        }

        .qrcode {
          width: 30px !important;
          height: 30px !important;
        }
        
        .description {
          margin-left: 20px;

          h2 {
            margin: 0 0 5px 0;
            font-weight: 500;
          }
          p {
            margin: 0;
            font-weight: 400;
          }
        }
      }
    }
  }
}

@keyframes print {
  0% {
    transform: translateY(-510px)
  }
  35% {
    transform: translateY(-395px);
  }
  70% {
    transform: translateY(-140px);
  }
  100% {
    transform: translateY(0);
  }
}

@media print {
  .judul {
    display: none; 
  }
}

.judul {
  color: white;
}

.paid {
  position: absolute;
    width: 113px;
    top: 292px;
    left: 95px;
}

.footnote{
  display: none;
}

@media only screen and (max-width: 768px) {
  .footnote{
    display: block !important;
    color: white;
    margin-left: 41px;
    margin-right: 41px;
    font-size: 18px;
  }
}

    </style>
</head>
<body>
<!-- INSPO:  https://www.behance.net/gallery/69583099/Mobile-Flights-App-Concept -->
<main class="ticket-system">
    <div class="top" style="margin-top: 50px;">
    <a class="judul" href="{{ url('/backdata/transaction') }}">Back To Transaction List</a>
    <div class="top" style="margin-top: 30px;">
    <div class="printer"></div>
    </div>
    <div class="receipts-wrapper">
       <div class="receipts">
          <div class="receipt">
             <img src="{{ asset('template/images/dian.png') }}">
             
                <h2 class="receipt-title">UNIT BUSINESS BOOKING TICKET</h2>
                
             <p style="font-size: 14px;">BOOKING CODE : {{ $trans->invoice }}</p>
             <div class="details">
                <div class="item">
                   <span>Name</span>
                   <h3>{{ $user->name }}</h3>
                </div>
                <div class="item">
                   <span>Status.</span>
                   <h3>{{ $user->level }}</h3>
                </div>
                <div class="item">
                   <span>Booking Date</span>
                   <h3>{{ date('d-m-Y', strtotime($trans->booking_date)) }}</h3>
                </div>
                <div class="item">
                   <span>Start</span>
                   <h3><i class="fa fa-clock"></i> {{ $trans->start_time.":00" }}</h3>
                </div>
                
                <div class="item">
                   <span>Finish</span>
                   <h3><i class="fa fa-clock"></i> {{ $trans->finish_time.":00" }}</h3>
                </div>
                <div class="item">
                    <span>Number of User</span>
                    @if($product->kategori == "Kolam Renang")
                    <h3><i class="fa fa-user"></i> {{ $trans->quantity }}</h3>
                    @else
                    <h3><i class="fa fa-clock"></i> {{ $trans->quantity }}</h3>
                    @endif
                </div>
                <div class="item">
                    <span>Price</span>
                    <h3>{{ $trans->total_price == 0 ? "FREE           " : "Rp. ".number_format($trans->total_price, 2) }}</h3>
                 </div>
             </div>
          </div>
          <div class="receipt qr-code">
             
             <div class="description">
                <h2 class="product-title">{{ $product->name_unit }} - [ {{ $trans->package_name }} ]</h2>
                <div class="myqrcode">{!! QrCode::size(70)->generate($trans->invoice) !!}</div>
             </div>
          </div>
          <img class="paid" src="{{ asset('template/images/paid.webp') }}">
       </div>
    </div>
    <p class="footnote">Please Screenshot this page and show it to the staff on-site</p>
 </main>
</body>
</html>