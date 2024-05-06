<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
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
            return response()->json([
            	"success" => false,
            	"message" => $html
            ]);
        }

        if(Auth::attempt(['username'=> $input['username'] ,'password'=> $input['old_password']])) {
            $user = User::findorFail($input['id']);
            $user->password = bcrypt($input['password']);
            $user->save();
            return response()->json([
            	"success" => true,
            	"message" => 'Your password successfully updated!!'
            ]);
        
        } else {
             return response()->json([
            	"success" => false,
            	"message" => 'Wrong password!!!!'
            ]);
        }
    }

    public function user_data($id) {
    	$user = User::findorFail($id);

    	return response()->json([
    		"success" => true,
    		"data" => $user
    	]);

    }

    public function profile_update(Request $request) {
        $input = $request->all();
        $rules = array(
            "whatsapp_number" => "required",
            "jenis_kelamin" => "required",
            "email" => "required",
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

        $user = User::findorfail($input['id']);
        
        $user->email = $input['email'];
        $user->no_hp = $input['whatsapp_number'];
        $user->jenis_kelamin = $input['jenis_kelamin'];
        $user->whatsapp_emergency = $input['whatsapp_emergency'];
        $user->id_pelanggan_pdam = $input['id_pelanggan_pdam'];
        $user->nomor_meter_pln = $input['nomor_meter_pln'];
        $user->save();

        return response()->json([
        	"success" => true,
        	"message" => 'Your profile successfully updated..!'
        ]);
        
    }

    public function upload(Request $request) {

        $dir = 'profile/';
        $image = $request->file('image');
        $ids = $request->ids;
    
        if($request->has('image')) {
            $imageName = \Carbon\Carbon::now()->toDateString()."-".uniqid()."."."png";
            Storage::disk('public')->put($dir.$imageName, file_get_contents($image));
    
        } else {
            return response()->json(['message'=> trans('/storage/test/'.'def.png.')],200);
        }


    
        $data = User::findorFail($ids);
        $data->foto = $imageName;
        $data->save();
        return response()->json(['message'=> trans('/storage/test/'.$imageName)],200);
    }

    public function mobile_redirect($text) {
        return view('mobile.note', compact('text'));
    }   


}
