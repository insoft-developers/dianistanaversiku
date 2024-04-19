<?php

namespace App\Http\Controllers\Main;

use App\Helpers\Resp;
use App\Models\UsersData;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Contracts\View\View;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Validator;
use Session;
use App\Models\User;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Redirect;
use Mail;
use App\Mail\RegisterMail;
use App\Models\BannerIklan;
use App\Models\BookingSetting;

class AuthUsersController extends Controller
{
    
    public function fcm_token(Request $request){
        try{
            $request->user()->update(['token'=>$request->token]);
            return response()->json([
                'success'=>true
            ]);
        }catch(\Exception $e){
            report($e);
            return response()->json([
                'success'=>false
            ],500);
        }
    }
    
    public function user_data() {

        if(Auth::user()->level != "user" ) {
            return redirect("frontend_dashboard");
        }

        $view = "user-data";
        $data = User::findorFail(Auth::user()->id);
        return view('frontend.user_data', compact('view','data'));
    }

    public function change_password() {
        $view = "change-password";
        $data = User::findorFail(Auth::user()->id);
        return view('frontend.change_password', compact('view','data'));
    }

    public function password_update(Request $request) {
        $input = $request->all();
        $rules = array(
            "old_password" => "required",
            "password" => "required|min:6|confirmed"
        );

        $validator = Validator::make($input, $rules);
        if($validator->fails()) {
            $pesan = $validator->errors();
            $pesanarr = explode(",", $pesan);
            $find = array("[","]","{","}");
            $html = '';
            foreach($pesanarr as $p ) {
                $html .= str_replace($find,"",$p).'<br>';
            }
            return Redirect::back()->with('error', $html);
        }

        if(Auth::attempt(['username'=> $input['username'] ,'password'=> $input['old_password']])) {
            $user = User::findorFail(Auth::user()->id);
            $user->password = bcrypt($input['password']);
            $user->save();
            return Redirect::back()->with('success', 'Your password successfully updated!!');
        
        } else {
            return Redirect::back()->with('error', 'Password anda masih salah!!');
        }

    }


    public function setting() {
        $view = "frontend-setting";
        $data = User::findorFail(Auth::user()->id);
        return view('frontend.setting', compact('view','data'));
    }
    

    public function profile_update(Request $request) {
        $input = $request->all();
        $rules = array(
            "whatsapp_number" => "required",
            "jenis_kelamin" => "required",
        );

        $validator = Validator::make($input, $rules);
        if($validator->fails()) {
            $pesan = $validator->errors();
            $pesanarr = explode(",", $pesan);
            $find = array("[","]","{","}");
            $html = '';
            foreach($pesanarr as $p ) {
                $html .= str_replace($find,"",$p).'<br>';
            }
            return Redirect::back()->with('error', $html);
        }

        $input['foto'] = null;
        $unique = uniqid();
        if($request->hasFile('foto')){
            $input['foto'] = Str::slug($unique, '-').'.'.$request->foto->getClientOriginalExtension();
            $request->foto->move(public_path('/storage/profile'), $input['foto']);
        }

        $user = User::findorfail(Auth::user()->id);
        $user->no_hp = $input['whatsapp_number'];
        $user->jenis_kelamin = $input['jenis_kelamin'];
        $user->whatsapp_emergency = $input['whatsapp_emergency'];
        $user->foto = $input['foto'];
        $user->save();

        return Redirect::back()->with('success', "Your Profile Successfully Updated!");
        
    }

    public function dashboard() {
        $view = 'frontend-dashboard';
        $data = User::findorFail(Auth::user()->id);
        $banner = BannerIklan::where('is_active', 1)->get();
        return view('frontend.dashboard', compact('view','data','banner'));
    }


    public function login(){
        $view = 'login';
        return view('frontend.login', compact('view'));
    }


    public function frontend_login(Request $request) {
        $input = $request->all();

        $rules = array(
            "username" =>"required",
            "password" => "required|min:6"

        );

        $validator = Validator::make($input, $rules);
        if($validator->fails()) {
            $pesan = $validator->errors();
            $pesanarr = explode(",", $pesan);
            $find = array("[","]","{","}");
            $html = '';
            foreach($pesanarr as $p ) {
                $html .= str_replace($find,"",$p).'<br>';
            }

            Session::flash('error', $html);
            return redirect('/login');
        }

        if(Auth::attempt(['username'=>$input['username'],'password'=>$input['password']])) {
            
            $data = User::where('username', $input['username'])->first();
            session(['session_id' => $data->id]);
            session(['session_name' => $data->name]);
            session(['session_email' => $data->email]);
            session(['session_hp' => $data->no_hp]);
            session(['session_level' => $data->level]);
            session(['session_foto' => $data->foto]);

            $data->token = session('session_frm_key');
            $data->save();

            return redirect('/frontend_dashboard');
        }
        
        else if(Auth::attempt(['email'=>$input['username'],'password'=>$input['password']])) {
            
            $data = User::where('email', $input['username'])->first();
            session(['session_id' => $data->id]);
            session(['session_name' => $data->name]);
            session(['session_email' => $data->email]);
            session(['session_hp' => $data->no_hp]);
            session(['session_level' => $data->level]);
            session(['session_foto' => $data->foto]);

            $data->token = session('session_frm_key');
            $data->save();

            return redirect('/frontend_dashboard');
        }

        else if(Auth::attempt(['no_hp'=>$input['username'],'password'=>$input['password']])) {
            
            $data = User::where('no_hp', $input['username'])->first();
            session(['session_id' => $data->id]);
            session(['session_name' => $data->name]);
            session(['session_email' => $data->email]);
            session(['session_hp' => $data->no_hp]);
            session(['session_level' => $data->level]);
            session(['session_foto' => $data->foto]);

            $data->token = session('session_frm_key');
            $data->save();

            return redirect('/frontend_dashboard');


        }
        
        
        else {
            Session::flash('error', 'username atau password masih salah!');
            return redirect('/login');
        }
    }



    public function register() {
        $view = 'register';
        return view('frontend.register', compact('view'));
    }

    public function register_now(Request $request) {
        $input = $request->all();

        $rules = array(
            "username" =>"required|min:6|unique:users,username",
            "name" => "required",
            "email" => 'required|email|unique:users,email',
            "no_hp" => 'required|unique:users,no_hp',
            "password" => "required|min:6|confirmed"
        );

        $validator = Validator::make($input, $rules);
        if($validator->fails()) {
            $pesan = $validator->errors();
            $pesanarr = explode(",", $pesan);
            $find = array("[","]","{","}");
            $html = '';
            foreach($pesanarr as $p ) {
                $html .= str_replace($find,"",$p).'<br>';
            }

            Session::flash('error', $html);
            return redirect('/frontend_register');
        }

        try{

            $input['level'] = "guest";
            $input['is_active'] = 0;
            $input['passcode'] = random_int(100000, 999999);
            
            User::create($input);
            $this->send_wa($input['no_hp'], $input['passcode']);
            $this->sendMail($input['email'], $input['passcode']);
            Session::flash("success", "Registrasi Berhasil, Silahkan aktifkan akun anda terlebih dahulu");
            session(['session_register_otp' => $input['email']]);
            return redirect('/otp');

        }catch(\Exception $e) {
            Session::flash("error", $e->getMessage());
            return redirect('/frontend_register');
        }

    }


    public function send_otp(Request $request) {
        $input = $request->all();
        $rules = array(
            "email" => "required",
            "passcode" => "required|min:6|max:6"
        );

        $validator = Validator::make($input, $rules);
        if($validator->fails()) {
            $pesan = $validator->errors();
            $pesanarr = explode(",", $pesan);
            $find = array("[","]","{","}");
            $html = '';
            foreach($pesanarr as $p ) {
                $html .= str_replace($find,"",$p).'<br>';
            }

            Session::flash('error', $html);
            return redirect('/otp');
        }

        $cek = User::where('email', $input['email'])->where('passcode', $input['passcode']);
        if($cek->count() == 1) {
            $data = $cek->first();
            if($data->is_active == 0) {
                $data->is_active = 1;
                $data->save();

                Auth::login($data);
                return redirect('frontend_dashboard');
            } else {
                Session::flash("error", "akun anda sudah active");
                return redirect('otp');
            }

        } else {
            Session::flash("error", "passcode yang anda masukkan salah");
            return redirect('otp');
        }
    }


    public function send_wa($phone, $passcode) {
        $setting = \App\Models\Setting::findorFail(1);
        $key = $setting->api_wa;
        $url='http://116.203.191.58/api/send_message';
        $data = array(
          "phone_no"  => $phone,
          "key"       => $key,
          "message"   => '[MyDianIstana] - Kode Passcode anda adalah '.$passcode.' masukkan 6 angka ini untuk mengaktifkan akun anda',
          "skip_link" => True, // This optional for skip snapshot of link in message
          "flag_retry"  => "on", // This optional for retry on failed send message
          "pendingTime" => 3 // This optional for delay before send message
        );
        $data_string = json_encode($data);

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_VERBOSE, 0);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 0);
        curl_setopt($ch, CURLOPT_TIMEOUT, 360);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
          'Content-Type: application/json',
          'Content-Length: ' . strlen($data_string))
        );
        echo $res=curl_exec($ch);
        curl_close($ch);
    }

    public function otp() {
        $view = 'otp';
        return view('frontend.otp', compact('view'));
    }


    public function sendMail($email, $passcode) {

        $user = User::where('email', $email)->first();

        $details = [
            'nama' => $user->name,
            'email' => $email,
            'passcode' => $passcode
        ];
         
        Mail::to($email)->send(new RegisterMail($details));
    }



    public function logout(Request $request) {
        $request->session()->regenerate();
        $request->session()->invalidate();
        $request->session()->flush();
        return redirect('/login');
    }


    public function booking() {
        $view = "booking";
        $unit = \App\Models\UnitBisnis::where('status_booking', 'Aktif')->get();
        return view('frontend.booking', compact('view','unit'));
    }


    public function booking_detail($slug) {
        $view = "booking-detail";
        $data = \App\Models\UnitBisnis::where('slug', $slug)->first();
        return view('frontend.booking_detail', compact('view', 'data'));
    }

    public function display_calendar($bulan, $tahun) {
        if($bulan == '01') {
            $month = "January";
        }
        else if($bulan == '02') {
            $month = "February";
        }
        else if($bulan == '03') {
            $month = "March";
        }
        else if($bulan == '04') {
            $month = "April";
        }
        else if($bulan == '05') {
            $month = "Mei";
        }
        else if($bulan == '06') {
            $month = "June";
        }
        else if($bulan == '07') {
            $month = "July";
        }
        else if($bulan == '08') {
            $month = "August";
        }
        else if($bulan == '09') {
            $month = "September";
        }
        else if($bulan == '10') {
            $month = "October";
        }
        else if($bulan == '11') {
            $month = "November";
        }
        else if($bulan == '12') {
            $month = "December";
        }
        
        $d=cal_days_in_month(CAL_GREGORIAN,$bulan,$tahun);

        $tanggal = $tahun.'-'.$bulan.'-01';
        $timestamp = strtotime($tanggal);

        $day = date('D', $timestamp);
       
        
        $HTML = "";
        $HTML .= '<div class="month">';
        $HTML .= '<ul>';
        $HTML .= '<li class="prev">&#10094;</li>';
        $HTML .= '<li class="next">&#10095;</li>';
        $HTML .= '<li>'.$month.'<br><span style="font-size:18px">'.$tahun.'</span></li>';
        $HTML .= '</ul>';
        $HTML .= '</div>';
        $HTML .= '<ul class="weekdays">';
        $HTML .= '<li>Mo</li><li>Tu</li><li>We</li><li>Th</li><li>Fr</li><li>Sa</li><li>Su</li></ul>';
        $HTML .= '<ul class="days">';
        if($day =="Mon") {

        }
        else if($day =="Tue") {
            $HTML .= '<li></li>';
        }
        else if($day =="Wed") {
            $HTML .= '<li></li>';
            $HTML .= '<li></li>';
        }
        else if($day =="Thu") {
            $HTML .= '<li></li>';
            $HTML .= '<li></li>';
            $HTML .= '<li></li>';
        }
        else if($day =="Fri") {
            $HTML .= '<li></li>';
            $HTML .= '<li></li>';
            $HTML .= '<li></li>';
            $HTML .= '<li></li>';
        }
        else if($day =="Sat") {
            $HTML .= '<li></li>';
            $HTML .= '<li></li>';
            $HTML .= '<li></li>';
            $HTML .= '<li></li>';
            $HTML .= '<li></li>';
        }
        else if($day =="Sun") {
            $HTML .= '<li></li>';
            $HTML .= '<li></li>';
            $HTML .= '<li></li>';
            $HTML .= '<li></li>';
            $HTML .= '<li></li>';
            $HTML .= '<li></li>';
        }
        for($n=1; $n<= $d; $n++) {
            
            $HTML .= '<li onclick="select_date('.$n.')" id="list_'.$n.'">'.$n.'</li>';
        }
                      
        $HTML .= '</ul>';

        return $HTML;
    }

    public function booking_time(Request $request) {
        $input = $request->all();
        $str_date = (string)$input['n'];
        $panjang = strlen($str_date);
        if($panjang == 1) {
            $du = "0".$str_date;
        } else {
            $du = $str_date;
        }
        
        $tanggal = $input['tahun'].'-'.$input['bulan'].'-'.$du;  
        $timestamp = strtotime($tanggal);
        $day = date('D', $timestamp);
        $level = Auth::user()->level;

        $js['6'] = 0;
        $js['7'] = 0;
        $js['8'] = 0;
        $js['9'] = 0;
        $js['10'] = 0;
        $js['11'] = 0;
        $js['12'] = 0;
        $js['13'] = 0;
        $js['14'] = 0;
        $js['15'] = 0;
        $js['16'] = 0;
        $js['17'] = 0;
        $js['18'] = 0;
        $js['19'] = 0;
        $js['20'] = 0;

        $jam_tutup = [];

        $cr = BookingSetting::where('date', $tanggal)
                    ->where('is_active', 1)->get();
        if($cr->count() > 0) {
            foreach($cr as $a) {
                $row['awal'] = $a->start_time;
                $row['akhir'] = $a->finish_time;
                array_push($jam_tutup, $row);
            }
        }

        $dr = BookingSetting::where('booking_day', $day)
                    ->where('is_active', 1)->get();
        if($dr->count() > 0) {
            foreach($dr as $a) {
                $row['awal'] = $a->start_time;
                $row['akhir'] = $a->finish_time;
                array_push($jam_tutup, $row);
            }
        }


        if($cr->count() >0 || $dr->count() > 0) {
            foreach($jam_tutup as $jt) {
                $pertama = (int)$jt['awal'];
                $ending = (int)$jt['akhir'];
                
                for($q = $pertama; $q < $ending; $q++) {
                    $js[$q]++;
                }
            }
        }
        
        
        

        $data = \App\Models\UnitBisnis::findorFail($input['product_id']);
        if($level == "guest") {
            if($day == "Sat" || $day=="Sun") {
                $price1 = $data->harga_umum_0617_weekend;
                $price2 = $data->harga_umum_1721_weekend; 
            } else {
                $price1 = $data->harga_umum_0617_weekday;
                $price2 = $data->harga_umum_1721_weekday; 
            }
        } else {
            if($data->kategori == 'Kolam Renang') {
                $price1 = 0;
                $price2 = 0;
            } else {
                if($day == "Sat" || $day=="Sun") {
                    $price1 = 0;
                    $price2 = $data->harga_warga_1721_weekend; 
                } else {
                    $price1 = 0;
                    $price2 = $data->harga_warga_1721_weekday; 
                }
            }
            
        }

        $not = \App\Models\Transaction::where('booking_date', $tanggal)
            ->where('order_status', 1)
            ->where('business_unit_id', $input['product_id'])
            ->where('payment_status', 'PAID')
            ->orWhere('payment_status', 'PENDING')
            ->get();
        
        $jam6 = 0;
        $jam7 = 0;
        $jam8 = 0;
        $jam9 = 0;
        $jam10 = 0;
        $jam11 = 0;
        $jam12 = 0;
        $jam13 = 0;
        $jam14 = 0;
        $jam15 = 0;
        $jam16 = 0;
        $jam17 = 0;
        $jam18 = 0;
        $jam19 = 0;
        $jam20 = 0;
        
        foreach($not as $key) {
            if($key->quantity == 1) {
                if($key->start_time == "06") {
                    $jam6++;
                }
                else if($key->start_time == "07") {
                    $jam7++;
                }
                else if($key->start_time == "08") {
                    $jam8++;
                }
                else if($key->start_time == "09") {
                    $jam9++;
                }
                else if($key->start_time == "10") {
                    $jam10++;
                }
                else if($key->start_time == "11") {
                    $jam11++;
                }
                else if($key->start_time == "12") {
                    $jam12++;
                }
                else if($key->start_time == "13") {
                    $jam13++;
                }
                else if($key->start_time == "14") {
                    $jam14++;
                }
                else if($key->start_time == "15") {
                    $jam15++;
                }
                else if($key->start_time == "16") {
                    $jam16++;
                }
                else if($key->start_time == "17") {
                    $jam17++;
                }
                else if($key->start_time == "18") {
                    $jam18++;
                }
                else if($key->start_time == "19") {
                    $jam19++;
                }
                else if($key->start_time == "20") {
                    $jam20++;
                }
            } 
            else if($key->quantity == 2) {
                if($key->start_time == "06") {
                    $jam6++;
                    $jam7++;
                }
                else if($key->start_time == "07") {
                    $jam7++;
                    $jam8++;
                }
                else if($key->start_time == "08") {
                    $jam8++;
                    $jam9++;
                }
                else if($key->start_time == "09") {
                    $jam9++;
                    $jam10++;
                }
                else if($key->start_time == "10") {
                    $jam10++;
                    $jam11++;
                }
                else if($key->start_time == "11") {
                    $jam11++;
                    $jam12++;
                }
                else if($key->start_time == "12") {
                    $jam12++;
                    $jam13++;
                }
                else if($key->start_time == "13") {
                    $jam13++;
                    $jam14++;
                }
                else if($key->start_time == "14") {
                    $jam14++;
                    $jam15++;
                }
                else if($key->start_time == "15") {
                    $jam15++;
                    $jam16++;
                }
                else if($key->start_time == "16") {
                    $jam16++;
                    $jam17++;
                }
                else if($key->start_time == "17") {
                    $jam17++;
                    $jam18++;
                }
                else if($key->start_time == "18") {
                    $jam18++;
                    $jam19++;
                }
                else if($key->start_time == "19") {
                    $jam19++;
                    $jam20++;
                }
            }
            else {
                if($key->start_time == "06") {
                    $jam6++;
                    $jam7++;
                    $jam8++;
                }
                else if($key->start_time == "07") {
                    $jam7++;
                    $jam8++;
                    $jam9++;
                }
                else if($key->start_time == "08") {
                    $jam8++;
                    $jam9++;
                    $jam10++;
                }
                else if($key->start_time == "09") {
                    $jam9++;
                    $jam10++;
                    $jam11++;
                }
                else if($key->start_time == "10") {
                    $jam10++;
                    $jam11++;
                    $jam12++;
                }
                else if($key->start_time == "11") {
                    $jam11++;
                    $jam12++;
                    $jam13++;
                }
                else if($key->start_time == "12") {
                    $jam12++;
                    $jam13++;
                    $jam14++;
                }
                else if($key->start_time == "13") {
                    $jam13++;
                    $jam14++;
                    $jam15++;
                }
                else if($key->start_time == "14") {
                    $jam14++;
                    $jam15++;
                    $jam16++;
                }
                else if($key->start_time == "15") {
                    $jam15++;
                    $jam16++;
                    $jam17++;
                }
                else if($key->start_time == "16") {
                    $jam16++;
                    $jam17++;
                    $jam18++;
                }
                else if($key->start_time == "17") {
                    $jam17++;
                    $jam18++;
                    $jam19++;
                }
                else if($key->start_time == "18") {
                    $jam18++;
                    $jam19++;
                    $jam20++;
                }
                else if($key->start_time == "19") {
                    $jam19++;
                    $jam20++;
                }
            }
        }

       
        $HTML = "";

        if($data->kategori == 'Kolam Renang') {

            // if(Auth::user()->level == 'user') {
                $HTML .= '<div class="jarak20"></div>';
                $HTML .= '<p>Select Booking Start Time:</p>';
                $HTML .= '<div class="jarak10 grid grid-cols-12 items-center">';
                
            
                $HTML .= '<div class="col-span-4 md:col-span-4 lg:col-span-4">';
                $HTML .= '<div class="hour-work" id="hour_1" onclick="select_hour(1)" ><i class="fa fa-clock"></i> 06:00</div>';
                $HTML .= '<input type="hidden" id="hour_start_1" value="06">';
                $HTML .= '<input type="hidden" id="price_start_1" value="'.$price1.'">';
                $HTML .= '</div>';
            
            
            
                $HTML .= '<div class="col-span-4 md:col-span-4 lg:col-span-4">';
                $HTML .= '<div class="hour-work" id="hour_2" onclick="select_hour(2)"><i class="fa fa-clock"></i> 07:00</div>';
                $HTML .= '<input type="hidden" id="hour_start_2" value="07">';
                $HTML .= '<input type="hidden" id="price_start_2" value="'.$price1.'">';
                $HTML .= '</div>';
            
                $HTML .= '<div class="col-span-4 md:col-span-4 lg:col-span-4">';
                $HTML .= '<div class="hour-work" id="hour_3" onclick="select_hour(3)"><i class="fa fa-clock"></i> 08:00</div>';
                $HTML .= '<input type="hidden" id="hour_start_3" value="08">';
                $HTML .= '<input type="hidden" id="price_start_3" value="'.$price1.'">';
                $HTML .= '</div>';
            
                $HTML .= '<div class="col-span-4 md:col-span-4 lg:col-span-4">';
                $HTML .= '<div class="hour-work" id="hour_4" onclick="select_hour(4)"><i class="fa fa-clock"></i> 09:00</div>';
                $HTML .= '<input type="hidden" id="hour_start_4" value="09">';
                $HTML .= '<input type="hidden" id="price_start_4" value="'.$price1.'">';
                $HTML .= '</div>';
            
                $HTML .= '<div class="col-span-4 md:col-span-4 lg:col-span-4">';
                $HTML .= '<div class="hour-work" id="hour_5" onclick="select_hour(5)"><i class="fa fa-clock"></i> 10:00</div>';
                $HTML .= '<input type="hidden" id="hour_start_5" value="10">';
                $HTML .= '<input type="hidden" id="price_start_5" value="'.$price1.'">';
                $HTML .= '</div>';
            
                $HTML .= '<div class="col-span-4 md:col-span-4 lg:col-span-4">';
                $HTML .= '<div class="hour-work" id="hour_6" onclick="select_hour(6)"><i class="fa fa-clock"></i> 11:00</div>';
                $HTML .= '<input type="hidden" id="hour_start_6" value="11">';
                $HTML .= '<input type="hidden" id="price_start_6" value="'.$price1.'">';
                $HTML .= '</div>';
            
                $HTML .= '<div class="col-span-4 md:col-span-4 lg:col-span-4">';
                $HTML .= '<div class="hour-work" id="hour_7" onclick="select_hour(7)"><i class="fa fa-clock"></i> 12:00</div>';
                $HTML .= '<input type="hidden" id="hour_start_7" value="12">';
                $HTML .= '<input type="hidden" id="price_start_7" value="'.$price1.'">';
                $HTML .= '</div>';
            
                $HTML .= '<div class="col-span-4 md:col-span-4 lg:col-span-4">';
                $HTML .= '<div class="hour-work" id="hour_8" onclick="select_hour(8)"><i class="fa fa-clock"></i> 13:00</div>';
                $HTML .= '<input type="hidden" id="hour_start_8" value="13">';
                $HTML .= '<input type="hidden" id="price_start_8" value="'.$price1.'">';
                $HTML .= '</div>';
            
                $HTML .= '<div class="col-span-4 md:col-span-4 lg:col-span-4">';
                $HTML .= '<div class="hour-work" id="hour_9" onclick="select_hour(9)"><i class="fa fa-clock"></i> 14:00</div>';
                $HTML .= '<input type="hidden" id="hour_start_9" value="14">';
                $HTML .= '<input type="hidden" id="price_start_9" value="'.$price1.'">';
                $HTML .= '</div>';
            
                $HTML .= '<div class="col-span-4 md:col-span-4 lg:col-span-4">';
                $HTML .= '<div class="hour-work" id="hour_10" onclick="select_hour(10)"><i class="fa fa-clock"></i> 15:00</div>';
                $HTML .= '<input type="hidden" id="hour_start_10" value="15">';
                $HTML .= '<input type="hidden" id="price_start_10" value="'.$price1.'">';
                $HTML .= '</div>';
            
                $HTML .= '<div class="col-span-4 md:col-span-4 lg:col-span-4">';
                $HTML .= '<div class="hour-work" id="hour_11" onclick="select_hour(11)"><i class="fa fa-clock"></i> 16:00</div>';
                $HTML .= '<input type="hidden" id="hour_start_11" value="16">';
                $HTML .= '<input type="hidden" id="price_start_11" value="'.$price1.'">';
                $HTML .= '</div>';           
            
                $HTML .= '</div>';
                $HTML .= '<div class="jarak20"></div>';
                $HTML .= '<p>Select Booking Finish Time:</p>';


                $HTML .= '<div class="jarak10 grid grid-cols-12 items-center">';
                
                $HTML .= '<div class="col-span-4 md:col-span-4 lg:col-span-4">';
                $HTML .= '<div class="hour-work" id="finish_2" onclick="select_finish_hour(2)"><i class="fa fa-clock"></i> 07:00</div>';
                $HTML .= '<input type="hidden" id="hour_finish_2" value="07">';
                $HTML .= '<input type="hidden" id="price_finish_2" value="'.$price1.'">';
                $HTML .= '</div>';
            
                $HTML .= '<div class="col-span-4 md:col-span-4 lg:col-span-4">';
                $HTML .= '<div class="hour-work" id="finish_3" onclick="select_finish_hour(3)"><i class="fa fa-clock"></i> 08:00</div>';
                $HTML .= '<input type="hidden" id="hour_finish_3" value="08">';
                $HTML .= '<input type="hidden" id="price_finish_3" value="'.$price1.'">';
                $HTML .= '</div>';
            
                $HTML .= '<div class="col-span-4 md:col-span-4 lg:col-span-4">';
                $HTML .= '<div class="hour-work" id="finish_4" onclick="select_finish_hour(4)"><i class="fa fa-clock"></i> 09:00</div>';
                $HTML .= '<input type="hidden" id="hour_finish_4" value="09">';
                $HTML .= '<input type="hidden" id="price_finish_4" value="'.$price1.'">';
                $HTML .= '</div>';
            
                $HTML .= '<div class="col-span-4 md:col-span-4 lg:col-span-4">';
                $HTML .= '<div class="hour-work" id="finish_5" onclick="select_finish_hour(5)"><i class="fa fa-clock"></i> 10:00</div>';
                $HTML .= '<input type="hidden" id="hour_finish_5" value="10">';
                $HTML .= '<input type="hidden" id="price_finish_5" value="'.$price1.'">';
                $HTML .= '</div>';
            
                $HTML .= '<div class="col-span-4 md:col-span-4 lg:col-span-4">';
                $HTML .= '<div class="hour-work" id="finish_6" onclick="select_finish_hour(6)"><i class="fa fa-clock"></i> 11:00</div>';
                $HTML .= '<input type="hidden" id="hour_finish_6" value="11">';
                $HTML .= '<input type="hidden" id="price_finish_6" value="'.$price1.'">';
                $HTML .= '</div>';
            
                $HTML .= '<div class="col-span-4 md:col-span-4 lg:col-span-4">';
                $HTML .= '<div class="hour-work" id="finish_7" onclick="select_finish_hour(7)"><i class="fa fa-clock"></i> 12:00</div>';
                $HTML .= '<input type="hidden" id="hour_finish_7" value="12">';
                $HTML .= '<input type="hidden" id="price_finish_7" value="'.$price1.'">';
                $HTML .= '</div>';
        
                $HTML .= '<div class="col-span-4 md:col-span-4 lg:col-span-4">';
                $HTML .= '<div class="hour-work" id="finish_8" onclick="select_finish_hour(8)"><i class="fa fa-clock"></i> 13:00</div>';
                $HTML .= '<input type="hidden" id="hour_finish_8" value="13">';
                $HTML .= '<input type="hidden" id="price_finish_8" value="'.$price1.'">';
                $HTML .= '</div>';
        
                $HTML .= '<div class="col-span-4 md:col-span-4 lg:col-span-4">';
                $HTML .= '<div class="hour-work" id="finish_9" onclick="select_finish_hour(9)"><i class="fa fa-clock"></i> 14:00</div>';
                $HTML .= '<input type="hidden" id="hour_finish_9" value="14">';
                $HTML .= '<input type="hidden" id="price_finish_9" value="'.$price1.'">';
                $HTML .= '</div>';
            
                $HTML .= '<div class="col-span-4 md:col-span-4 lg:col-span-4">';
                $HTML .= '<div class="hour-work" id="finish_10" onclick="select_finish_hour(10)"><i class="fa fa-clock"></i> 15:00</div>';
                $HTML .= '<input type="hidden" id="hour_finish_10" value="15">';
                $HTML .= '<input type="hidden" id="price_finish_10" value="'.$price1.'">';
                $HTML .= '</div>';
            
                $HTML .= '<div class="col-span-4 md:col-span-4 lg:col-span-4">';
                $HTML .= '<div class="hour-work" id="finish_11" onclick="select_finish_hour(11)"><i class="fa fa-clock"></i> 16:00</div>';
                $HTML .= '<input type="hidden" id="hour_finish_11" value="16">';
                $HTML .= '<input type="hidden" id="price_finish_11" value="'.$price1.'">';
                $HTML .= '</div>';
            
                $HTML .= '<div class="col-span-4 md:col-span-4 lg:col-span-4">';
                $HTML .= '<div class="hour-work" id="finish_12" onclick="select_finish_hour(12)"><i class="fa fa-clock"></i> 17:00</div>';
                $HTML .= '<input type="hidden" id="hour_finish_12" value="17">';
                $HTML .= '<input type="hidden" id="price_finish_12" value="'.$price1.'">';
                $HTML .= '</div>';
            
                
            
                $HTML .= '</div>';
            // } else {
                
            // }

            
        } else {
            $HTML .= '<div class="jarak20"></div>';
            $HTML .= '<p>Select Booking Start Time:</p>';
            $HTML .= '<div class="jarak10 grid grid-cols-12 items-center">';
            
            if($jam6 > 0) {
                $HTML .= '<div class="col-span-4 md:col-span-4 lg:col-span-4">';
                $HTML .= '<div class="hour-book"><i class="fa fa-book"></i> Booked</div>';
                $HTML .= '</div>';
            } else{

                if($js['6'] > 0) {
                    $HTML .= '<div class="col-span-4 md:col-span-4 lg:col-span-4">';
                    $HTML .= '<div style="opacity:0.1;" class="hour-book"><i class="fa fa-lock"></i> Closed</div>';
                    $HTML .= '</div>';
                } else {
                    $HTML .= '<div class="col-span-4 md:col-span-4 lg:col-span-4">';
                    $HTML .= '<div class="hour-work" id="hour_1" onclick="select_hour(1)" ><i class="fa fa-clock"></i> 06:00</div>';
                    $HTML .= '<input type="hidden" id="hour_start_1" value="06">';
                    $HTML .= '<input type="hidden" id="price_start_1" value="'.$price1.'">';
                    $HTML .= '</div>';
                }

                
            }
            
            if($jam7 > 0) {
                $HTML .= '<div class="col-span-4 md:col-span-4 lg:col-span-4">';
                $HTML .= '<div class="hour-book"><i class="fa fa-book"></i> Booked</div>';
                $HTML .= '</div>';
            } else{
                if($js['7'] > 0) {
                    $HTML .= '<div class="col-span-4 md:col-span-4 lg:col-span-4">';
                    $HTML .= '<div style="opacity:0.1;" class="hour-book"><i class="fa fa-lock"></i> Closed</div>';
                    $HTML .= '</div>';
                }
                else {
                    $HTML .= '<div class="col-span-4 md:col-span-4 lg:col-span-4">';
                    $HTML .= '<div class="hour-work" id="hour_2" onclick="select_hour(2)"><i class="fa fa-clock"></i> 07:00</div>';
                    $HTML .= '<input type="hidden" id="hour_start_2" value="07">';
                    $HTML .= '<input type="hidden" id="price_start_2" value="'.$price1.'">';
                    $HTML .= '</div>';
                }
                
            }
            if($jam8 > 0) {
                $HTML .= '<div class="col-span-4 md:col-span-4 lg:col-span-4">';
                $HTML .= '<div class="hour-book"><i class="fa fa-book"></i> Booked</div>';
                $HTML .= '</div>';
            } else{
                if($js['8'] > 0) {
                    $HTML .= '<div class="col-span-4 md:col-span-4 lg:col-span-4">';
                    $HTML .= '<div style="opacity:0.1;" class="hour-book"><i class="fa fa-lock"></i> Closed</div>';
                    $HTML .= '</div>';
                } else {
                    $HTML .= '<div class="col-span-4 md:col-span-4 lg:col-span-4">';
                    $HTML .= '<div class="hour-work" id="hour_3" onclick="select_hour(3)"><i class="fa fa-clock"></i> 08:00</div>';
                    $HTML .= '<input type="hidden" id="hour_start_3" value="08">';
                    $HTML .= '<input type="hidden" id="price_start_3" value="'.$price1.'">';
                    $HTML .= '</div>';
                }
               
            }    
            
            if($jam9 > 0) {
                $HTML .= '<div class="col-span-4 md:col-span-4 lg:col-span-4">';
                $HTML .= '<div class="hour-book"><i class="fa fa-book"></i> Booked</div>';
                $HTML .= '</div>';
            } else{
                if($js['9'] > 0) {
                    $HTML .= '<div class="col-span-4 md:col-span-4 lg:col-span-4">';
                    $HTML .= '<div style="opacity:0.1;" class="hour-book"><i class="fa fa-lock"></i> Closed</div>';
                    $HTML .= '</div>';
                } else {
                    $HTML .= '<div class="col-span-4 md:col-span-4 lg:col-span-4">';
                    $HTML .= '<div class="hour-work" id="hour_4" onclick="select_hour(4)"><i class="fa fa-clock"></i> 09:00</div>';
                    $HTML .= '<input type="hidden" id="hour_start_4" value="09">';
                    $HTML .= '<input type="hidden" id="price_start_4" value="'.$price1.'">';
                    $HTML .= '</div>';
                }
                
            }
            if($jam10 > 0) {
                $HTML .= '<div class="col-span-4 md:col-span-4 lg:col-span-4">';
                $HTML .= '<div class="hour-book"><i class="fa fa-book"></i> Booked</div>';
                $HTML .= '</div>';
            } else{
                if($js['10'] > 0) {
                    $HTML .= '<div class="col-span-4 md:col-span-4 lg:col-span-4">';
                    $HTML .= '<div style="opacity:0.1;" class="hour-book"><i class="fa fa-lock"></i> Closed</div>';
                    $HTML .= '</div>';
                } else {
                    $HTML .= '<div class="col-span-4 md:col-span-4 lg:col-span-4">';
                    $HTML .= '<div class="hour-work" id="hour_5" onclick="select_hour(5)"><i class="fa fa-clock"></i> 10:00</div>';
                    $HTML .= '<input type="hidden" id="hour_start_5" value="10">';
                    $HTML .= '<input type="hidden" id="price_start_5" value="'.$price1.'">';
                    $HTML .= '</div>';
                }
                
            }
            if($jam11 > 0) {
                $HTML .= '<div class="col-span-4 md:col-span-4 lg:col-span-4">';
                $HTML .= '<div class="hour-book"><i class="fa fa-book"></i> Booked</div>';
                $HTML .= '</div>';
            } else{
                if($js['11'] > 0) {
                    $HTML .= '<div class="col-span-4 md:col-span-4 lg:col-span-4">';
                    $HTML .= '<div style="opacity:0.1;" class="hour-book"><i class="fa fa-lock"></i> Closed</div>';
                    $HTML .= '</div>';
                } else {
                    $HTML .= '<div class="col-span-4 md:col-span-4 lg:col-span-4">';
                    $HTML .= '<div class="hour-work" id="hour_6" onclick="select_hour(6)"><i class="fa fa-clock"></i> 11:00</div>';
                    $HTML .= '<input type="hidden" id="hour_start_6" value="11">';
                    $HTML .= '<input type="hidden" id="price_start_6" value="'.$price1.'">';
                    $HTML .= '</div>';
                }
                
            }
            if($jam12 > 0) {
                $HTML .= '<div class="col-span-4 md:col-span-4 lg:col-span-4">';
                $HTML .= '<div class="hour-book"><i class="fa fa-book"></i> Booked</div>';
                $HTML .= '</div>';
            } else{
                if($js['12'] > 0) {
                    $HTML .= '<div class="col-span-4 md:col-span-4 lg:col-span-4">';
                    $HTML .= '<div style="opacity:0.1;" class="hour-book"><i class="fa fa-lock"></i> Closed</div>';
                    $HTML .= '</div>';
                } else {
                    $HTML .= '<div class="col-span-4 md:col-span-4 lg:col-span-4">';
                    $HTML .= '<div class="hour-work" id="hour_7" onclick="select_hour(7)"><i class="fa fa-clock"></i> 12:00</div>';
                    $HTML .= '<input type="hidden" id="hour_start_7" value="12">';
                    $HTML .= '<input type="hidden" id="price_start_7" value="'.$price1.'">';
                    $HTML .= '</div>';
                }
                
            }
            if($jam13 > 0) {
                $HTML .= '<div class="col-span-4 md:col-span-4 lg:col-span-4">';
                $HTML .= '<div class="hour-book"><i class="fa fa-book"></i> Booked</div>';
                $HTML .= '</div>';
            } else{
                if($js['13'] > 0) {
                    $HTML .= '<div class="col-span-4 md:col-span-4 lg:col-span-4">';
                    $HTML .= '<div style="opacity:0.1;" class="hour-book"><i class="fa fa-lock"></i> Closed</div>';
                    $HTML .= '</div>';
                } else {
                    $HTML .= '<div class="col-span-4 md:col-span-4 lg:col-span-4">';
                    $HTML .= '<div class="hour-work" id="hour_8" onclick="select_hour(8)"><i class="fa fa-clock"></i> 13:00</div>';
                    $HTML .= '<input type="hidden" id="hour_start_8" value="13">';
                    $HTML .= '<input type="hidden" id="price_start_8" value="'.$price1.'">';
                    $HTML .= '</div>';
                }
                
            }
            if($jam14 > 0) {
                $HTML .= '<div class="col-span-4 md:col-span-4 lg:col-span-4">';
                $HTML .= '<div class="hour-book"><i class="fa fa-book"></i> Booked</div>';
                $HTML .= '</div>';
            } else{
                if($js['14'] > 0) {
                    $HTML .= '<div class="col-span-4 md:col-span-4 lg:col-span-4">';
                    $HTML .= '<div style="opacity:0.1;" class="hour-book"><i class="fa fa-lock"></i> Closed</div>';
                    $HTML .= '</div>';
                } else {
                    $HTML .= '<div class="col-span-4 md:col-span-4 lg:col-span-4">';
                    $HTML .= '<div class="hour-work" id="hour_9" onclick="select_hour(9)"><i class="fa fa-clock"></i> 14:00</div>';
                    $HTML .= '<input type="hidden" id="hour_start_9" value="14">';
                    $HTML .= '<input type="hidden" id="price_start_9" value="'.$price1.'">';
                    $HTML .= '</div>';
                }
                
            }
            if($jam15 > 0) {
                $HTML .= '<div class="col-span-4 md:col-span-4 lg:col-span-4">';
                $HTML .= '<div class="hour-book"><i class="fa fa-book"></i> Booked</div>';
                $HTML .= '</div>';
            } else{
                if($js['15'] > 0) {
                    $HTML .= '<div class="col-span-4 md:col-span-4 lg:col-span-4">';
                    $HTML .= '<div style="opacity:0.1;" class="hour-book"><i class="fa fa-lock"></i> Closed</div>';
                    $HTML .= '</div>';
                } else {
                    $HTML .= '<div class="col-span-4 md:col-span-4 lg:col-span-4">';
                    $HTML .= '<div class="hour-work" id="hour_10" onclick="select_hour(10)"><i class="fa fa-clock"></i> 15:00</div>';
                    $HTML .= '<input type="hidden" id="hour_start_10" value="15">';
                    $HTML .= '<input type="hidden" id="price_start_10" value="'.$price1.'">';
                    $HTML .= '</div>';
                }
               
            }
            if($jam16 > 0) {
                $HTML .= '<div class="col-span-4 md:col-span-4 lg:col-span-4">';
                $HTML .= '<div class="hour-book"><i class="fa fa-book"></i> Booked</div>';
                $HTML .= '</div>';
            } else{
                if($js['16'] > 0) {
                    $HTML .= '<div class="col-span-4 md:col-span-4 lg:col-span-4">';
                    $HTML .= '<div style="opacity:0.1;" class="hour-book"><i class="fa fa-lock"></i> Closed</div>';
                    $HTML .= '</div>';
                } else {
                    $HTML .= '<div class="col-span-4 md:col-span-4 lg:col-span-4">';
                    $HTML .= '<div class="hour-work" id="hour_11" onclick="select_hour(11)"><i class="fa fa-clock"></i> 16:00</div>';
                    $HTML .= '<input type="hidden" id="hour_start_11" value="16">';
                    $HTML .= '<input type="hidden" id="price_start_11" value="'.$price1.'">';
                    $HTML .= '</div>';
                }
                
            }
            if($jam17 > 0) {
                $HTML .= '<div class="col-span-4 md:col-span-4 lg:col-span-4">';
                $HTML .= '<div class="hour-book"><i class="fa fa-book"></i> Booked</div>';
                $HTML .= '</div>';
            } else{
                if($js['17'] > 0) {
                    $HTML .= '<div class="col-span-4 md:col-span-4 lg:col-span-4">';
                    $HTML .= '<div style="opacity:0.1;" class="hour-book"><i class="fa fa-lock"></i> Closed</div>';
                    $HTML .= '</div>';
                } else {
                    $HTML .= '<div class="col-span-4 md:col-span-4 lg:col-span-4">';
                    $HTML .= '<div class="hour-work" id="hour_12" onclick="select_hour(12)"><i class="fa fa-clock"></i> 17:00</div>';
                    $HTML .= '<input type="hidden" id="hour_start_12" value="17">';
                    $HTML .= '<input type="hidden" id="price_start_12" value="'.$price2.'">';
                    $HTML .= '</div>';
                }
                
            }
            if($jam18 > 0) {
                $HTML .= '<div class="col-span-4 md:col-span-4 lg:col-span-4">';
                $HTML .= '<div class="hour-book"><i class="fa fa-book"></i> Booked</div>';
                $HTML .= '</div>';
            } else{
                if($js['18'] > 0) {
                    $HTML .= '<div class="col-span-4 md:col-span-4 lg:col-span-4">';
                    $HTML .= '<div style="opacity:0.1;" class="hour-book"><i class="fa fa-lock"></i> Closed</div>';
                    $HTML .= '</div>';
                } else {
                    $HTML .= '<div class="col-span-4 md:col-span-4 lg:col-span-4">';
                    $HTML .= '<div class="hour-work" id="hour_13" onclick="select_hour(13)"><i class="fa fa-clock"></i> 18:00</div>';
                    $HTML .= '<input type="hidden" id="hour_start_13" value="18">';
                    $HTML .= '<input type="hidden" id="price_start_13" value="'.$price2.'">';
                    $HTML .= '</div>';
                }
                
            }
            if($jam19 > 0) {
                $HTML .= '<div class="col-span-4 md:col-span-4 lg:col-span-4">';
                $HTML .= '<div class="hour-book"><i class="fa fa-book"></i> Booked</div>';
                $HTML .= '</div>';
            } else{
                if($js['19'] > 0) {
                    $HTML .= '<div class="col-span-4 md:col-span-4 lg:col-span-4">';
                    $HTML .= '<div style="opacity:0.1;" class="hour-book"><i class="fa fa-lock"></i> Closed</div>';
                    $HTML .= '</div>';
                } else {
                    $HTML .= '<div class="col-span-4 md:col-span-4 lg:col-span-4">';
                    $HTML .= '<div class="hour-work" id="hour_14" onclick="select_hour(14)"><i class="fa fa-clock"></i> 19:00</div>';
                    $HTML .= '<input type="hidden" id="hour_start_14" value="19">';
                    $HTML .= '<input type="hidden" id="price_start_14" value="'.$price2.'">';
                    $HTML .= '</div>';
                }
                
            }
            if($jam20 > 0) {
                $HTML .= '<div class="col-span-4 md:col-span-4 lg:col-span-4">';
                $HTML .= '<div class="hour-book"><i class="fa fa-book"></i> Booked</div>';
                $HTML .= '</div>';
            } else{
                if($js['20'] > 0) {
                    $HTML .= '<div class="col-span-4 md:col-span-4 lg:col-span-4">';
                    $HTML .= '<div style="opacity:0.1;" class="hour-book"><i class="fa fa-lock"></i> Closed</div>';
                    $HTML .= '</div>';
                } else {
                    $HTML .= '<div class="col-span-4 md:col-span-4 lg:col-span-4">';
                    $HTML .= '<div class="hour-work" id="hour_15" onclick="select_hour(15)"><i class="fa fa-clock"></i> 20:00</div>';
                    $HTML .= '<input type="hidden" id="hour_start_15" value="20">';
                    $HTML .= '<input type="hidden" id="price_start_15" value="'.$price2.'">';
                    $HTML .= '</div>';
                }
                
            }    

            $HTML .= '</div>';
            $HTML .= '<div class="jarak20"></div>';
            $HTML .= '<p>Select Booking Finish Time:</p>';


            $HTML .= '<div class="jarak10 grid grid-cols-12 items-center">';
            if($jam6 > 0) {
                $HTML .= '<div class="col-span-4 md:col-span-4 lg:col-span-4">';
                $HTML .= '<div class="hour-book"><i class="fa fa-book"></i> Booked</div>';
                $HTML .= '</div>';
            } else{
                if($js['6'] > 0) {
                    $HTML .= '<div class="col-span-4 md:col-span-4 lg:col-span-4">';
                    $HTML .= '<div style="opacity:0.1;" class="hour-book"><i class="fa fa-lock"></i> Closed</div>';
                    $HTML .= '</div>';
                } else {
                    $HTML .= '<div class="col-span-4 md:col-span-4 lg:col-span-4">';
                    $HTML .= '<div class="hour-work" id="finish_2" onclick="select_finish_hour(2)"><i class="fa fa-clock"></i> 07:00</div>';
                    $HTML .= '<input type="hidden" id="hour_finish_2" value="07">';
                    $HTML .= '<input type="hidden" id="price_finish_2" value="'.$price1.'">';
                    $HTML .= '</div>';
                }
                
            }
            if($jam7 > 0) {
                $HTML .= '<div class="col-span-4 md:col-span-4 lg:col-span-4">';
                $HTML .= '<div class="hour-book"><i class="fa fa-book"></i> Booked</div>';
                $HTML .= '</div>';
            } else{
                if($js['7'] > 0) {
                    $HTML .= '<div class="col-span-4 md:col-span-4 lg:col-span-4">';
                    $HTML .= '<div style="opacity:0.1;" class="hour-book"><i class="fa fa-lock"></i> Closed</div>';
                    $HTML .= '</div>';
                } else {
                $HTML .= '<div class="col-span-4 md:col-span-4 lg:col-span-4">';
                $HTML .= '<div class="hour-work" id="finish_3" onclick="select_finish_hour(3)"><i class="fa fa-clock"></i> 08:00</div>';
                $HTML .= '<input type="hidden" id="hour_finish_3" value="08">';
                $HTML .= '<input type="hidden" id="price_finish_3" value="'.$price1.'">';
                $HTML .= '</div>';
                }
            }
            if($jam8 > 0) {
                $HTML .= '<div class="col-span-4 md:col-span-4 lg:col-span-4">';
                $HTML .= '<div class="hour-book"><i class="fa fa-book"></i> Booked</div>';
                $HTML .= '</div>';
            } else{
                if($js['8'] > 0) {
                    $HTML .= '<div class="col-span-4 md:col-span-4 lg:col-span-4">';
                    $HTML .= '<div style="opacity:0.1;" class="hour-book"><i class="fa fa-lock"></i> Closed</div>';
                    $HTML .= '</div>';
                } else {
                $HTML .= '<div class="col-span-4 md:col-span-4 lg:col-span-4">';
                $HTML .= '<div class="hour-work" id="finish_4" onclick="select_finish_hour(4)"><i class="fa fa-clock"></i> 09:00</div>';
                $HTML .= '<input type="hidden" id="hour_finish_4" value="09">';
                $HTML .= '<input type="hidden" id="price_finish_4" value="'.$price1.'">';
                $HTML .= '</div>';
                }
            }
            if($jam9 > 0) {
                $HTML .= '<div class="col-span-4 md:col-span-4 lg:col-span-4">';
                $HTML .= '<div class="hour-book"><i class="fa fa-book"></i> Booked</div>';
                $HTML .= '</div>';
            } else{
                if($js['6'] > 9) {
                    $HTML .= '<div class="col-span-4 md:col-span-4 lg:col-span-4">';
                    $HTML .= '<div style="opacity:0.1;" class="hour-book"><i class="fa fa-lock"></i> Closed</div>';
                    $HTML .= '</div>';
                } else {
                $HTML .= '<div class="col-span-4 md:col-span-4 lg:col-span-4">';
                $HTML .= '<div class="hour-work" id="finish_5" onclick="select_finish_hour(5)"><i class="fa fa-clock"></i> 10:00</div>';
                $HTML .= '<input type="hidden" id="hour_finish_5" value="10">';
                $HTML .= '<input type="hidden" id="price_finish_5" value="'.$price1.'">';
                $HTML .= '</div>';}
            }
            if($jam10 > 0) {
                $HTML .= '<div class="col-span-4 md:col-span-4 lg:col-span-4">';
                $HTML .= '<div class="hour-book"><i class="fa fa-book"></i> Booked</div>';
                $HTML .= '</div>';
            } else{
                if($js['10'] > 0) {
                    $HTML .= '<div class="col-span-4 md:col-span-4 lg:col-span-4">';
                    $HTML .= '<div style="opacity:0.1;" class="hour-book"><i class="fa fa-lock"></i> Closed</div>';
                    $HTML .= '</div>';
                } else {
                $HTML .= '<div class="col-span-4 md:col-span-4 lg:col-span-4">';
                $HTML .= '<div class="hour-work" id="finish_6" onclick="select_finish_hour(6)"><i class="fa fa-clock"></i> 11:00</div>';
                $HTML .= '<input type="hidden" id="hour_finish_6" value="11">';
                $HTML .= '<input type="hidden" id="price_finish_6" value="'.$price1.'">';
                $HTML .= '</div>';}
            }
            if($jam11 > 0) {
                $HTML .= '<div class="col-span-4 md:col-span-4 lg:col-span-4">';
                $HTML .= '<div class="hour-book"><i class="fa fa-book"></i> Booked</div>';
                $HTML .= '</div>';
            } else{
                if($js['11'] > 0) {
                    $HTML .= '<div class="col-span-4 md:col-span-4 lg:col-span-4">';
                    $HTML .= '<div style="opacity:0.1;" class="hour-book"><i class="fa fa-lock"></i> Closed</div>';
                    $HTML .= '</div>';
                } else {
                $HTML .= '<div class="col-span-4 md:col-span-4 lg:col-span-4">';
                $HTML .= '<div class="hour-work" id="finish_7" onclick="select_finish_hour(7)"><i class="fa fa-clock"></i> 12:00</div>';
                $HTML .= '<input type="hidden" id="hour_finish_7" value="12">';
                $HTML .= '<input type="hidden" id="price_finish_7" value="'.$price1.'">';
                $HTML .= '</div>';}
        }
        if($jam12 > 0) {
                $HTML .= '<div class="col-span-4 md:col-span-4 lg:col-span-4">';
                $HTML .= '<div class="hour-book"><i class="fa fa-book"></i> Booked</div>';
                $HTML .= '</div>';
            } else{
                if($js['12'] > 0) {
                    $HTML .= '<div class="col-span-4 md:col-span-4 lg:col-span-4">';
                    $HTML .= '<div style="opacity:0.1;" class="hour-book"><i class="fa fa-lock"></i> Closed</div>';
                    $HTML .= '</div>';
                } else {
                $HTML .= '<div class="col-span-4 md:col-span-4 lg:col-span-4">';
                $HTML .= '<div class="hour-work" id="finish_8" onclick="select_finish_hour(8)"><i class="fa fa-clock"></i> 13:00</div>';
                $HTML .= '<input type="hidden" id="hour_finish_8" value="13">';
                $HTML .= '<input type="hidden" id="price_finish_8" value="'.$price1.'">';
                $HTML .= '</div>';}
        }
        if($jam13 > 0) {
                $HTML .= '<div class="col-span-4 md:col-span-4 lg:col-span-4">';
                $HTML .= '<div class="hour-book"><i class="fa fa-book"></i> Booked</div>';
                $HTML .= '</div>';
            } else{
                if($js['13'] > 0) {
                    $HTML .= '<div class="col-span-4 md:col-span-4 lg:col-span-4">';
                    $HTML .= '<div style="opacity:0.1;" class="hour-book"><i class="fa fa-lock"></i> Closed</div>';
                    $HTML .= '</div>';
                } else {
                $HTML .= '<div class="col-span-4 md:col-span-4 lg:col-span-4">';
                $HTML .= '<div class="hour-work" id="finish_9" onclick="select_finish_hour(9)"><i class="fa fa-clock"></i> 14:00</div>';
                $HTML .= '<input type="hidden" id="hour_finish_9" value="14">';
                $HTML .= '<input type="hidden" id="price_finish_9" value="'.$price1.'">';
                $HTML .= '</div>';}
            }
            if($jam14 > 0) {
                $HTML .= '<div class="col-span-4 md:col-span-4 lg:col-span-4">';
                $HTML .= '<div class="hour-book"><i class="fa fa-book"></i> Booked</div>';
                $HTML .= '</div>';
            } else{
                if($js['14'] > 0) {
                    $HTML .= '<div class="col-span-4 md:col-span-4 lg:col-span-4">';
                    $HTML .= '<div style="opacity:0.1;" class="hour-book"><i class="fa fa-lock"></i> Closed</div>';
                    $HTML .= '</div>';
                } else {
                $HTML .= '<div class="col-span-4 md:col-span-4 lg:col-span-4">';
                $HTML .= '<div class="hour-work" id="finish_10" onclick="select_finish_hour(10)"><i class="fa fa-clock"></i> 15:00</div>';
                $HTML .= '<input type="hidden" id="hour_finish_10" value="15">';
                $HTML .= '<input type="hidden" id="price_finish_10" value="'.$price1.'">';
                $HTML .= '</div>';}
            }
            if($jam15 > 0) {
                $HTML .= '<div class="col-span-4 md:col-span-4 lg:col-span-4">';
                $HTML .= '<div class="hour-book"><i class="fa fa-book"></i> Booked</div>';
                $HTML .= '</div>';
            } else{
                if($js['15'] > 0) {
                    $HTML .= '<div class="col-span-4 md:col-span-4 lg:col-span-4">';
                    $HTML .= '<div style="opacity:0.1;" class="hour-book"><i class="fa fa-lock"></i> Closed</div>';
                    $HTML .= '</div>';
                } else {
                $HTML .= '<div class="col-span-4 md:col-span-4 lg:col-span-4">';
                $HTML .= '<div class="hour-work" id="finish_11" onclick="select_finish_hour(11)"><i class="fa fa-clock"></i> 16:00</div>';
                $HTML .= '<input type="hidden" id="hour_finish_11" value="16">';
                $HTML .= '<input type="hidden" id="price_finish_11" value="'.$price1.'">';
                $HTML .= '</div>';}
            }
            if($jam16 > 0) {
                $HTML .= '<div class="col-span-4 md:col-span-4 lg:col-span-4">';
                $HTML .= '<div class="hour-book"><i class="fa fa-book"></i> Booked</div>';
                $HTML .= '</div>';
            } else{
                if($js['16'] > 0) {
                    $HTML .= '<div class="col-span-4 md:col-span-4 lg:col-span-4">';
                    $HTML .= '<div style="opacity:0.1;" class="hour-book"><i class="fa fa-lock"></i> Closed</div>';
                    $HTML .= '</div>';
                } else {
                $HTML .= '<div class="col-span-4 md:col-span-4 lg:col-span-4">';
                $HTML .= '<div class="hour-work" id="finish_12" onclick="select_finish_hour(12)"><i class="fa fa-clock"></i> 17:00</div>';
                $HTML .= '<input type="hidden" id="hour_finish_12" value="17">';
                $HTML .= '<input type="hidden" id="price_finish_12" value="'.$price1.'">';
                $HTML .= '</div>';}
            }
            if($jam17 > 0) {
                $HTML .= '<div class="col-span-4 md:col-span-4 lg:col-span-4">';
                $HTML .= '<div class="hour-book"><i class="fa fa-book"></i> Booked</div>';
                $HTML .= '</div>';
            } else{
                if($js['17'] > 0) {
                    $HTML .= '<div class="col-span-4 md:col-span-4 lg:col-span-4">';
                    $HTML .= '<div style="opacity:0.1;" class="hour-book"><i class="fa fa-lock"></i> Closed</div>';
                    $HTML .= '</div>';
                } else {
                $HTML .= '<div class="col-span-4 md:col-span-4 lg:col-span-4">';
                $HTML .= '<div class="hour-work" id="finish_13" onclick="select_finish_hour(13)"><i class="fa fa-clock"></i> 18:00</div>';
                $HTML .= '<input type="hidden" id="hour_finish_13" value="18">';
                $HTML .= '<input type="hidden" id="price_finish_13" value="'.$price2.'">';
                $HTML .= '</div>';}
        }
        if($jam18 > 0) {
                $HTML .= '<div class="col-span-4 md:col-span-4 lg:col-span-4">';
                $HTML .= '<div class="hour-book"><i class="fa fa-book"></i> Booked</div>';
                $HTML .= '</div>';
            } else{
                if($js['18'] > 0) {
                    $HTML .= '<div class="col-span-4 md:col-span-4 lg:col-span-4">';
                    $HTML .= '<div style="opacity:0.1;" class="hour-book"><i class="fa fa-lock"></i> Closed</div>';
                    $HTML .= '</div>';
                } else {
                $HTML .= '<div class="col-span-4 md:col-span-4 lg:col-span-4">';
                $HTML .= '<div class="hour-work" id="finish_14" onclick="select_finish_hour(14)"><i class="fa fa-clock"></i> 19:00</div>';
                $HTML .= '<input type="hidden" id="hour_finish_14" value="19">';
                $HTML .= '<input type="hidden" id="price_finish_14" value="'.$price2.'">';
                $HTML .= '</div>';}
            }
            if($jam19 > 0) {
                $HTML .= '<div class="col-span-4 md:col-span-4 lg:col-span-4">';
                $HTML .= '<div class="hour-book"><i class="fa fa-book"></i> Booked</div>';
                $HTML .= '</div>';
            } else{

                if($js['19'] > 0) {
                    $HTML .= '<div class="col-span-4 md:col-span-4 lg:col-span-4">';
                    $HTML .= '<div style="opacity:0.1;" class="hour-book"><i class="fa fa-lock"></i> Closed</div>';
                    $HTML .= '</div>';
                } else {
                $HTML .= '<div class="col-span-4 md:col-span-4 lg:col-span-4">';
                $HTML .= '<div class="hour-work" id="finish_15" onclick="select_finish_hour(15)"><i class="fa fa-clock"></i> 20:00</div>';
                $HTML .= '<input type="hidden" id="hour_finish_15" value="20">';
                $HTML .= '<input type="hidden" id="price_finish_15" value="'.$price2.'">';
                $HTML .= '</div>';
                }
            }    
            if($jam20 > 0) {
                $HTML .= '<div class="col-span-4 md:col-span-4 lg:col-span-4">';
                $HTML .= '<div class="hour-book"><i class="fa fa-book"></i> Booked</div>';
                $HTML .= '</div>';
            } else{
                if($js['20'] > 0) {
                    $HTML .= '<div class="col-span-4 md:col-span-4 lg:col-span-4">';
                    $HTML .= '<div style="opacity:0.1;" class="hour-book"><i class="fa fa-lock"></i> Closed</div>';
                    $HTML .= '</div>';
                } else {
                $HTML .= '<div class="col-span-4 md:col-span-4 lg:col-span-4">';
                $HTML .= '<div class="hour-work" id="finish_16" onclick="select_finish_hour(16)"><i class="fa fa-clock"></i> 21:00</div>';
                $HTML .= '<input type="hidden" id="hour_finish_16" value="21">';
                $HTML .= '<input type="hidden" id="price_finish_16" value="'.$price2.'">';
                $HTML .= '</div>';}
            }
            $HTML .= '</div>';
        }
        

        return $HTML;
    }

    public function transaction(Request $request) {
        $input = $request->all();
       
        $un = \App\Models\UnitBisnis::findorFail($input['business_unit_id']);

        if($un->kategori != "Kolam Renang") {
            $rules = array(
                "business_unit_id" => "required",
                "invoice" => "required",
                "start_time" => "required",
                "finish_time" => "required",
                "quantity" => "required",
                "total_price" => "required",
                "booking_date" => "required",
                
            );
        } else {
            if(Auth::user()->level == "guest") {
                $rules = array(
                    "business_unit_id" => "required",
                    "invoice" => "required",
                    "package_id" => "required",
                    "package_name" => "required",
                    "quantity" => "required",
                    "total_price" => "required",
                    "booking_date" => "required",
                    
                );
            } else {
                $rules = array(
                    "business_unit_id" => "required",
                    "invoice" => "required",
                    "start_time" => "required",
                    "finish_time" => "required",
                    "quantity" => "required",
                    "total_price" => "required",
                    "booking_date" => "required",
                    
                );
            }
                
        }
        

        $validator = Validator::make($input, $rules);
        if($validator->fails()) {
            $pesan = $validator->errors();
            $pesanarr = explode(",", $pesan);
            $find = array("[","]","{","}");
            $html = '';
            foreach($pesanarr as $p ) {
                $html .= str_replace($find,"",$p).'<br>';
            }
            return response()->json([
                "success" => false,
                "message" => $html
            ]);

        }

        try{    
            $input['user_id'] = Auth::user()->id;
            
            if($input['total_price'] == 0 || $input['total_price'] == "0") {
                $input['payment_status'] = "PAID";
                $input['description'] = "free for user";
            } else {
                $input['payment_status'] = "PENDING";
                $input['description'] = "order";
            }

            if($request->start_time == null) {
                $input['start_time'] = "00";
            }
            if($request->finish_time == null) {
                $input['finish_time'] = "00";
            }
            if($request->package_id == null) {
                $input['package_id'] = 0;
            }
            if($request->package_name == null) {
                $input['package_name'] = "no-package";
            }

            $input['order_status'] = 1;
            $data = \App\Models\Transaction::create($input);
            
            return response()->json([
                "success" => true,
                "message" => "success",
                "id" => $data->id,
                "total_price" => $input['total_price']
            ]);

        }catch(\Exception $e) {
            return response()->json([
                "success" => false,
                "message" => $e->getMessage()
            ]);
        }
        
    }

    public function riwayat() {
        $view = "riwayat";
        $transaction = \App\Models\Transaction::where('user_id', Auth::user()->id)->orderBy('id', 'desc')->get();
        return view('frontend.riwayat',compact('view', 'transaction'));
    }

    public function payment_process(Request $request) {
        $input = $request->all();
        
        $trans = \App\Models\Transaction::findorFail($input['id']);
        $setting = \App\Models\Setting::findorFail(1);
        $amount = $trans->total_price;
        $pajak = $setting->pajak;
        $adminfee = $setting->admin_fee;

        if(! empty($pajak)) {
            $percent = $amount * $pajak /100;
            $nominal = (int)$percent;
            $vat = $amount + $nominal;
        } else {
            $vat = $amount;
            $nominal = 0;
        }

        if(! empty($adminfee)) {
            $final = $vat + $adminfee;
        } else {
            $final = $vat;
        }
        
        $user = \App\Models\User::findorFail($trans->user_id);
        $product = \App\Models\UnitBisnis::findorFail($trans->business_unit_id);


        
        $api_pay = base64_encode($setting->api_payment. ':');
        $secret_key = 'Basic '.$api_pay;
        $external_id = $trans->invoice;
        $data_request = Http::withHeaders([
            'Authorization' => $secret_key
        ])->post('https://api.xendit.co/v2/invoices', [
            'external_id' => $external_id,
            'amount' => $final,
            'success_redirect_url' => url('/print_ticket/'.$input['id']),
            'failure_redirect_url' => url('/riwayat'),
            'description' => "Order atas nama : ".$user->name. " <br>untuk fasilitas : ".$product->name_unit." <br>untuk tanggal : ".$trans->booking_date." <br>jam : ".$trans->start_time.":00 WIB - ".$trans->finish_time.":00 WIB - Tax :".number_format($nominal)." admin fee: ".number_format($adminfee),
        ]);
        
        $response = $data_request->object();
    
        return response()->json([
            "success" => true,
            "data" => $response->invoice_url
        ]);
    }

    public function callback(Request $request) {
        $input = $request->all();
        $cek = substr($input['external_id'], 0, 2);
        
        if($cek == "PM") {
            $trans = \App\Models\PaymentDetail::where('invoice', $input['external_id'])
                ->where('payment_status', 'PENDING')
                ->first();

            $trans->payment_status = $input['status'];
            $trans->payment_method = $input['payment_method'];
            $trans->payment_channel = $input['payment_channel'];
            $trans->paid_at = date('Y-m-d H:i:s');
            $trans->save();    


            return response()->json([
                "success" => true,
                "data" => $trans
            ]);
        } else {
            $trans = \App\Models\Transaction::where('invoice', $input['external_id'])
                ->where('payment_status', 'PENDING')
                ->first();

            $trans->payment_status = $input['status'];
            $trans->payment_method = $input['payment_method'];
            $trans->payment_channel = $input['payment_channel'];
            $trans->paid_at = date('Y-m-d H:i:s');
            $trans->save();    


            return response()->json([
                "success" => true,
                "data" => $trans
            ]);
        }
        

        
    }


    public function print($id) {
        $view = 'print-ticket';
        $tran = \App\Models\Transaction::where('id', (int)$id)->where('payment_status', 'PAID');
        if($tran->count() <= 0) {
            return redirect('/riwayat');
        }
        $trans = $tran->first();
        $user = \App\Models\User::findorFail($trans->user_id);
        $product = \App\Models\UnitBisnis::findorFail($trans->business_unit_id);
        return view('frontend.ticket', compact('view','trans','user','product'));
    }


    
    public function notify() {
        $title ="It is a long established fact that a reader";
        $message = "It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout. The point of using Lorem Ipsum is that it has a more-or-less normal distribution of letters, as opposed to using 'Content here, content here', making it look like readable English. Many desktop publishing packages and web page editors now use Lorem Ipsum as their default model text, and a search for 'lorem ipsum' will uncover many web sites still in their infancy. Various versions have evolved over the years, sometimes by accident, sometimes on purpose (injected humour and the like).";
        $regid ="cih6Wo03OOpg9vSS3QBaWW:APA91bEw0Rx8qj_CFTp46WMrfK7AbUMaA6hP4fYybwz5TaJMkMoLoDodN4T6SxZmGZajM7YaAR4dxoiBMWKW-w2VJYoRXMfXA4fcSxi4Dh-R2Xi2MpwioQrM-UuRQ1P7Dh8oDTKYa-ch";
        

        $SERVER_API_KEY = 'AAAAwbylMgg:APA91bF2ALenum4cb5ossrjcPIXOGJbUyjrSDu7YUS6LS8RQI2WDKsliccvbH8JHP3zYJIaZSpS-emPRjDy3EzAZjEZu4NHTfPu1L4rtknAZgeYqpc5Ck-uzbc_nA0cgPYDmTH-5EQV7';

        $data = [

            // "to" => '/topics/comment',
            "to" => $regid,
            "notification" => [
                "title" => $title,
                "body" => $message,
                "sound"=> "default",
                    // required for sound on ios
            ],
        ];
    
        
        
    
        $dataString = json_encode($data);
    
        $headers = [
    
            'Authorization: key=' . $SERVER_API_KEY,
    
            'Content-Type: application/json',
    
        ];
    
        $ch = curl_init();
    
        curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');
    
        curl_setopt($ch, CURLOPT_POST, true);
    
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    
        curl_setopt($ch, CURLOPT_POSTFIELDS, $dataString);
    
        $response = curl_exec($ch);
        
        return $response;
        
    }

    
}

