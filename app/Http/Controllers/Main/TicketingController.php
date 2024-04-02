<?php

namespace App\Http\Controllers\Main;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\TicketingCategory;
use Validator;
use Session;
use App\Models\Ticketing;
use Illuminate\Support\Facades\Auth;

class TicketingController extends Controller
{
    public function index() {
        $view = "ticketing";
         return view("frontend.ticketing", compact('view'));
    }

    public function add() {
        $view = "ticketing";
        $category = TicketingCategory::all();
        return view("frontend.ticketing_add", compact('view','category'));
    }

    public function open(Request $request) {
        $input = $request->all();
        $rules = array(
            "subject" => "required",
            "department" => "required",
            "priority" => "required",
            "message" => "required",
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
            return redirect('/ticketing_add');
        }

        $input['user_id'] = Auth::user()->id;
        $input['status'] = 0;
        $input['ticket_number'] = "DIT-".random_int(10000000, 99999999);
        Ticketing::create($input);
        Session::flash('success', "");
        return redirect('/ticketing');
        
    }
}
