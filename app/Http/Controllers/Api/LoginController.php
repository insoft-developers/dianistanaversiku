<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Validator;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    public function login(Request $request) {
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

            return response()->json([
            	"success" => false,
            	"message" => $html
            ]);
        }

        
        if(Auth::attempt(['username'=>$input['username'],'password'=>$input['password']])) {
        	$data = User::where('username', $input['username'])->first();
            
            $token = SHA1(date('Y-m-d H:i:s'));
           	return response()->json([
            	"success" => true,
            	"message" => "success",
            	"data" => $data,
            	"token" => $token
            ]);
        }
        
        else if(Auth::attempt(['email'=>$input['username'],'password'=>$input['password']])) {
            
            $data = User::where('email', $input['username'])->first();
            return response()->json([
            	"success" => true,
            	"message" => "success",
            	"data" => $data
            ]);
        }

        else if(Auth::attempt(['no_hp'=>$input['username'],'password'=>$input['password']])) {
            
            $data = User::where('no_hp', $input['username'])->first();
            return response()->json([
            	"success" => true,
            	"message" => "success",
            	"data" => $data
            ]);


        }
        
        
        else {
            return response()->json([
            	"success" => false,
            	"message" => "wrong username or password"

            ]);
        }
    }

    public function update_fcm_token(Request $request) {
        $input = $request->all();
        $data = User::findorFail($input['id']);
        $data->token = $input['token'];
        $data->save();
        return response()->json([
            "success" => true,
            "message" => "token saved ".$input['token']
        ]);
    }

}
