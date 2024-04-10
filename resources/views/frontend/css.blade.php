<style>
body {
    font-family: "Roboto", Sans-serif !important;
}

.footer-logo {
    width: 260px;
    margin-top: -18px;
}

ul.menu-atas{
    font-family: "Roboto", Sans-serif !important;
    font-size: 12px !important;
    font-weight: bold !important;
}
.jarak10{margin-top: 10px;}
.jarak15{margin-top: 15px;}
.jarak20{margin-top: 20px;}
.jarak25{margin-top: 25px;}
.jarak30{margin-top: 30px;}
.jarak40{margin-top: 40px;}
.jarak50{margin-top: 50px;}
.jarak60{margin-top: 60px;}
.jarak70{margin-top: 70px;}
.jarak80{margin-top: 80px;}
.jarak90{margin-top: 90px;}
.jarak100{margin-top: 100px;}

.alert-danger{
    color: white;
    background: #c3515b;
    padding: 21px;
    border-radius: 5px;
    margin-bottom: 19px;
    font-size: 15px;
}
.alert-success {
    color: white;
    background: green;
    padding: 21px;
    border-radius: 5px;
    margin-bottom: 19px;
    font-size: 15px;
}
.btn-colse{
    position: relative;
    float: right;
    top: -3px;
    margin-right: -2px;
    color: red;
    background: white;
    padding-left: 10px;
    padding-right: 10px;
    border-radius: 25px;
    cursor: pointer;        
}
.otp-text{
    border: 3px solid #87c9b7;
    width: 42%;
    padding-top: 20px;
    padding-bottom: 20px;
    padding-left: 20px;
    padding-right: 20px;
    font-size: 29px;
    border-radius: 20px;
    text-align: center;
}
.gambar-otp{
    width: 250px;
    height: 250px;
    position: relative;
    top: 0;
    /* left: 0%; */
    margin: auto;
}
.profile-image{
    position: absolute;
    width: 30px;
    height: 30px;
    left: -11px;
    top: 10px;
    background: white;
    border-radius: 15px;
    padding: 2px;
}
.dashboard-foto{
    width: 100px;
    height: 100px;
    position: relative;
    top: 0;
    left: 0;
    margin: auto;
    border-radius: 50px;
    border: 4px solid white;
}
.menu-image{
    height: 204px;
    object-fit: contain;
    width: 100%;
    border: 2px solid whitesmoke;
    padding: 15px;
    border-top-left-radius: 10px;
    border-top-right-radius: 10px;
    
}

.daftar-harga{
    background: #d7f3d7;
    padding: 12px;
    font-size: 15px;
    border-radius: 10px;
    opacity: 0.9;
}
.bg-red {
    color: red !important;

}
.side-booking-image{
    width: 500px;
    height: 194px;
    border-radius: 10px;
    object-fit: cover;
    border: 3px dotted orange;
    padding: 3px;
}
.w-50 {
    width: 50% !important;
}
.w-100 {
    width: 100% !important;
}
.w-25 {
    width: 25% !important;
}
.custom-input{
    border: 2px solid whitesmoke !important;
    padding-left: 20px !important;
    padding-right: 10px !important;
    padding-top: 15px !important;
    padding-bottom: 15px !important;
    border-radius: 10px !important;
    font-size: 20px !important;
}

.input-ticket{
    border: 2px solid whitesmoke !important;
    padding-left: 20px !important;
    padding-right: 10px !important;
    padding-top: 10px !important;
    padding-bottom: 10px !important;
    border-radius: 6px !important;
    font-size: 16px !important;
    background:white;

}
.badge{
    display: block;
    background: black;
    color: white;
    padding: 2px;
    border-radius: 20px;
    width: 89px !important;
    text-align: center;
    font-size: 12px !important;
}

.b-red{
    background: red !important;
    color: white;
}
.b-green{
    background: green !important;
    color: white;
}
.b-orange{
    background: orange !important;
    color: white;
}
.b-grey{
    background: grey !important;
    color: white;
}


.form-group {
    display: inline-flex;
    width: 100%;
    margin-bottom: 15px;
}
/* Month header */
.month {
  padding: 19px 25px;
  width: 100%;
  background: #1abc9c;
  text-align: center;
}

/* Month list */
.month ul {
  margin: 0;
  padding: 0;
}

.month ul li {
  color: white;
  font-size: 20px;
  text-transform: uppercase;
  letter-spacing: 3px;
}

/* Previous button inside month header */
.month .prev {
  float: left;
  padding-top: 10px;
}

/* Next button */
.month .next {
  float: right;
  padding-top: 10px;
}

/* Weekdays (Mon-Sun) */
.weekdays {
  margin: 0;
  padding: 10px 0;
  background-color:#ddd;
}

.weekdays li {
  display: inline-block;
  width: 13.6%;
  color: #666;
  text-align: center;
}

/* Days (1-31) */
.days {
  padding: 10px 0;
  background: #eee;
  margin: 0;
}

.days li {
  list-style-type: none;
  display: inline-block;
  width: 13.6%;
  text-align: center;
  margin-bottom: 5px;
  font-size:12px;
  color: #777;
  cursor: pointer;
}

/* Highlight the "current" day */
.days li .active {
  padding: 5px;
  background: #1abc9c;
  color: white !important
}

.active {
  padding: 5px !important;
  background: #1abc9c !important;
  color: white !important
}

.actived {
  padding: 5px !important;
  background: red !important;
  color: white !important
}

.hour-work{
    background: #7a7373;
    color: white;
    text-align: center;
    border-radius: 5px;
    width: 89px;
    color: white;
    text-align: center;
    border-radius: 5px;
    margin-bottom: 8px;
    width: 162px;
    cursor: pointer;
}

.hour-book{
    background: #100e7c;
    color: white;
    text-align: center;
    border-radius: 5px;
    
    width: 89px;
    
    color: white;
    text-align: center;
    border-radius: 5px;
    margin-bottom: 8px;
    width: 162px;

}

.hour-work:hover{
    background: orange;
    color: white;
}

.alert-red {
    background: #f70808;
    color: white;
    position: fixed;
    top: 180px;
    width: 500px;
    margin: auto;
    z-index: 999999;
    padding-left: 20px;
    padding-right: 20px;
    padding-top: 15px;
    padding-bottom: 15px;
    border-radius: 10px;
    left: 0;
    right: 0;
}

.alert-title{
    text-align: center;
    font-size: 24px;
    font-weight: bold;
}
.alert-content{
    text-align: center;
    font-size: 15px;
    font-weight: 300;
    margin-bottom: 21px;
}
.button-oke{
    float: right;
    border: 2px solid white;
    padding-left: 10px;
    padding-right: 10px;
    border-radius: 26px;
    font-size: 13px;
    padding-top: 1px;
    padding-bottom: 1px;
    cursor: pointer;
}
.button-oke:hover {
    background: orange;
    color: white;
}
body.modal-open {
    overflow: hidden;
}
.table{
    width: 100%;
    background: linear-gradient(146deg, #e9fff4, #c5c3cf);
    background-image: linear-gradient(45deg, rgb(233, 255, 244), rgb(197, 195, 207));
    background-position-x: initial;
    background-position-y: initial;
    background-size: initial;
    background-repeat: initial;
    background-attachment: initial;
    background-origin: initial;
    background-clip: initial;
    background-color: initial;
    border-radius: 10px;
    overflow-x: auto !important;
    margin: 0 auto;
  
}
.table td{
    padding: 15px 20px 15px 20px;
    border: 1px dotted #e9fff4;
    white-space: nowrap;
    font-size: 13px;
    
}
.table th{
    padding: 15px 20px 15px 20px;
    border: 1px dotted #e9fff4;
    background: lightblue;
    font-size: 14px;
}

.alert-green {
    background: #2e8833;
    color: white;
    position: fixed;
    top: 180px;
    width: 500px;
    margin: auto;
    z-index: 999999;
    padding-left: 20px;
    padding-right: 20px;
    padding-top: 15px;
    padding-bottom: 15px;
    border-radius: 10px;
    left: 0;
    right: 0;
}
.custom-title{
    font-size: 36px;
    font-weight: bold;
}
.tablediv{
    overflow-x: auto;
    width: 100% !important;
}

.lengkung-atas-kiri{
    border-top-left-radius: 10px !important;
}
.lengkung-atas-kanan{
    border-top-right-radius: 10px !important;
}
.bgred{
    width: 80px !important;
    background: red !important;
    border-radius: 5px !important;
}
.bggreen {
    width: 80px !important;
    background: green !important;
    border-radius: 5px !important;
}
.bgorange {
    width: 80px !important;
    background: orange !important;
    border-radius: 5px !important;
}
.button-new-ticket{
    background: green;
    float: right;
    color: white;
    padding-left: 10px;
    padding-right: 10px;
    padding-top: 5px;
    padding-bottom: 5px;
    border-radius: 5px;
    cursor: pointer;
    opacity: 1.0;
    margin-bottom: 20px;
}
.button-new-ticket:hover{
    opacity: 0.8;
}
.card-box{
    background: #ffffff;
    padding: 30px;
    border-radius: 5px;
    opacity: 0.8;
    border: 1px solid whitesmoke;
}
label{
    font-size: 18px;
    font-weight: 600;
}

.buttons{
    background: white;
    width: 150px;
    padding-top: 10px;
    padding-bottom: 10px;
    border-radius: 20px;
    font-weight: 600;
    cursor: pointer;
}

.buttons-small{
    background: white;
    width: 94px;
    padding-top: 3px;
    padding-bottom: 3px;
    border-radius: 5px;
    font-weight: bold;
    cursor: pointer;
    font-size: 14px;
}

.fr {
    float:right !important;
}

.buttons:hover{
    opacity: 0.8;
}

.btn-success{
    background: rgb(7, 146, 7);
    color: white;
}
.btn-primary{
    background: navy;
    color: white;
}
.btn-warning{
    background: orange;
    color: white;
}
.btn-danger{
    background: red;
    color: white;
}
.text-judul {
    font-size: 21px;
    font-weight: 500;
}
.custom-position {
    margin-top: -15px;
}
.paper{
    margin-bottom: 20px;
    background: whitesmoke;
    padding: 15px;
    border-radius: 2px;
}
.paper-profile{
    width: 50px;
    height: 50px;
    border-radius: 25px;
}
.paper-user-name{
    position: relative;
    left: 64px;
    top: -48px;
    font-size: 17px;
    font-weight: bold;
    color: #6767af;
}
.paper-date{
    position: relative;
    top: -54px;
    font-size: 13px;
    left: 64px;
    opacity: 0.7;
}
.paper-content{
    margin-left: 63px;
    font-size: 15px;
    margin-top: -34px;
}
.paper-attachment {
    position: relative;
    top: -48px;
    left: 64px;
    font-size: 14px;
  
    cursor: pointer;
}

.paper-attachment:hover {
    text-decoration: underline;
}

.cust-label{
    font-size: 12px;
    font-weight: bold;
    color: red;
}

.title-setting{
    position: relative !important;
    left: -24px !important;
    top: -157px !important;
    font-size: 33px;
    font-weight: bold;
    margin-top: 57px;
}

.input-setting {
    padding: 8px;
    border-radius: 5px;
    width: 100%;
    font-size: 15px;

}
.setting-container{
    position: relative;
    left: 16px;
    top: -157px;
    width: 537px;
    background: #bed6e3;
    padding: 20px;
    float: right !important;
    border-radius: 4px;
}
.form-place{
    margin-top: 10px;
}

.dashboard-title{
    font-size: 22px;
    font-family: 'Roboto', sans-serif;
    font-weight: bold;
    color: #73bf43;
}

.rata-tengah {
    margin-left: 50px;
    margin-right: 50px;
}

.readonly{
    background: whitesmoke;
    color: #918b8b;
}
.img-pro{
    margin: auto;
    width: 130px;
    height: 130px;
    border-radius: 65px;
    border: 6px solid yellow;
    object-fit: cover;
}
.image-pickup:hover{
    background: orange;
    border: 2px solid white;
}
.image-pickup{
    position: relative;
    right: -533px;
    top: -41px;
    background: green;
    color: white;
    padding-left: 8px;
    padding-right: 8px;
    padding-top: 8px;
    padding-bottom: 8px;
    border-radius: 30px;
    cursor: pointer;
}
.cancel-upload-container{
    position: absolute;
    right: 609px;
    top: 146px;
    background: red;
    color: white;
    padding-left: 9px;
    padding-right: 9px;
    padding-top: 2px;
    padding-bottom: 0px;
    border-radius: 30px;
    cursor: pointer;
    border: 3px solid white;
}

.detail-notif-image{

}
.notif-badge{
    background: white;
    padding-left: 8px;
    padding-right: 8px;
    padding-top: 1px;
    padding-bottom: 1px;
    border-radius: 20px;
    cursor: pointer;
}
.notif-number{
    background: red;
    color: white;
    font-size: 12px;
    font-weight: bold;
    padding-left: 7px;
    padding-right: 7px;
    border-radius: 13px;
    padding-top: 4px;
    position: relative;
    right: 11px;
    top: -14px;
    padding-bottom: 3px;
}

    

</style>