<?php

namespace App\Http\Controllers\Admins;

use App\Helpers\Resp;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Contracts\View\View;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Session;
use Validator;

class AuthController extends Controller
{
    // public function index(): View 
    // {
    //     return view("admins.auth.index");
    // }

    // public function prosesAuth(Request $request): JsonResponse
    // {
    //     $rules = [
    //         'username'  =>  'required',
    //         'password'  =>  'required',
    //     ];

    //     $validator = $this->validateRed($request, $rules);
    //     if ($validator !== null) {
    //         Resp::error($validator);
    //     } else {
    //         $credential = $request->only('username','password');
    //         $remember_me = $request->remember_me == null ? false : true;
    //         if (Auth::guard('webadmin')->attempt($credential, $remember_me)) {
    //             Resp::success(alertSuccess("Berhasil Login"),["redirect" => route("home_admin")]);
    //         } else {
    //             Resp::error(spanRed("Username atau Password yang anda masukkan salah.!"));
    //         }
    //     }
        
    //     return Resp::json();
    // }

    


    public function logout() 
    {
        Auth::guard("webadmin")->logout();
        return redirect(route('login_admin'));
    }
}
