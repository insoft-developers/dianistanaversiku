<style>
.footer-logo {
    width: 260px;
    margin-top: -18px;
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
    padding: 10px;
    border-radius: 10px;
    margin-bottom: 19px;
}
.alert-success {
    color: white;
    background: green;
    padding: 10px;
    border-radius: 10px;
    margin-bottom: 19px;
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
     border: 1px solid whitesmoke;
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
    left: 0;
    top: 12px;
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
    object-fit: cover;
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
    border: 2px solid whitesmoke;
    padding-left: 20px;
    padding-right: 10px;
    padding-top: 15px;
    padding-bottom: 15px;
    border-radius: 10px;
    font-size: 20px;
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
    cursor: pointer;background: #7a7373;
    color: white;
    text-align: center;
    border-radius: 5px;
    margin-bottom: 8px;
    width: 89px;
    cursor: pointer;
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
}
.table td{
    padding: 15px 20px 15px 20px;
    border: 1px dotted #e9fff4;
}

</style>