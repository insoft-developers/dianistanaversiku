<?php

namespace App\Http\Controllers\Main;

use App\Http\Controllers\Controller;
use App\Models\Notif;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Validator;
use Session;
use App\Models\User;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Redirect;

class NotifController extends Controller
{
    public function index(Request $request) {

        $request->session()->forget('session_notification_number');

        $view = "notif";
        $data = Notif::where('user_id', Auth::user()->id)->orWhere('user_id', -1)->get();
        return view('frontend.notif', compact('view','data'));
    }

    public function notif_detail($slug) {
        $view = "notif-detail";
        $data = Notif::where('slug', $slug)->first();
        $admin = \App\Models\AdminsData::findorFail($data->admin_id);
        return view('frontend.notif_detail', compact('view','data','admin'));
    }

    public function update_notif_number() {
        $jumlah = session('session_notification_number') == null ? 0 : (int)session('session_notification_number');
        $update = $jumlah + 1;
        session(['session_notification_number'=> $update]);

        $HTML = "";

        if($update > 0) {
            $HTML .= '<span class="notif-number">'.$update.'</span>';
        }
        
        
        return response()->json($HTML);
    }
}
