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

class AuthUsersController extends Controller
{
    // public function index_register(): View
    // {
    //     return view("main.authusers.index_register");
    // }

    // public function prosesRegister(Request $request): JsonResponse
    // {
    //     $rules = [
    //         'email'  =>  'required|email',
    //         'password'  =>  'required|min:8',
    //         'confirm_password'  =>  'required|same:password|min:8',
    //     ];

    //     $validator = $this->validateRed($request, $rules);
    //     if ($validator !== null) {
    //         Resp::error($validator);
    //     } else {
    //         $checkEmail = UsersData::getFirst(["email" => $request->email]);
    //         if ($checkEmail) {
    //             Resp::error(spanRed("Email <i><u>".$request->email."</u></i> Sudah Terdaftar"));
    //         } else {
    //             $data = [
    //                 'email'     =>  $request->email,
    //                 'password'  =>  Hash::make($request->password),
    //                 'token'     =>  Str::random(80),
    //             ];
    //             $register = UsersData::insertId($data);
    //             if($register){
    //                 // send email


    //             } else {
    //                 Resp::error(alertDanger("Gagal Daftar Akun.!"));
    //             }
    //         }
    //     }
        
    //     return Resp::json();
    // }

    // public function index_login(): View 
    // {
    //     return view("main.authusers.index_login");
    // }

    // public function index_slider(): View 
    // {
    //     return view("main.slider");
    // }



    // public function prosesAuth(Request $request): JsonResponse
    // {
    //     $rules = [
    //         'email'  =>  'required|email',
    //         'password'  =>  'required',
    //     ];

    //     $validator = $this->validateRed($request, $rules);
    //     if ($validator !== null) {
    //         Resp::error($validator);
    //     } else {
    //         $checkEmail = UsersData::getFirst(["email"=>$request->email]);
    //         // nanti buat validate cek email yang belum verifikasi email ya....
            
    //         $credential = $request->only('email','password');
    //         $remember_me = $request->remember_me == null ? false : true;
    //         if (Auth::guard('web')->attempt($credential, $remember_me)) {
    //             Resp::success(alertSuccess("Berhasil Login"),["redirect" => route("home_main")]);
    //         } else {
    //             Resp::error(spanRed("Email atau Password yang anda masukkan salah.!"));
    //         }
    //     }
        
    //     return Resp::json();
    // }

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
            return redirect(route('home_public'));


        } else {
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

                Session::flash("success", "akun anda berhasil diaktifkan!");
                return redirect('otp');
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
        
        $key='c50b2c98d96b93b80307edbb3e85d4eab676044e3ecd3181'; //this is demo key please change with your own key
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


    public function logout() 
    {
        Auth::guard("web")->logout();
        return redirect(route('login_admin'));
    }
}

