<?php

namespace App\Http\Controllers\Main;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\TicketingCategory;
use Validator;
use Session;
use App\Models\Ticketing;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;
use App\Models\TicketingContent;
use Illuminate\Support\Facades\Storage;
use Redirect;



class TicketingController extends Controller
{
    public function index() {
        $view = "ticketing";
       
        $data = Ticketing::where('user_id', Auth::user()->id)->orderBy('created_at','desc')->get();
         return view("frontend.ticketing", compact('view','data'));
    }

    public function add() {
        $view = "ticketing";
        $category = TicketingCategory::all();
        $data = User::findorFail(Auth::user()->id);
        return view("frontend.ticketing_add", compact('view','category','data'));
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

        $input['document'] = null;
        $unique = uniqid();
        if($request->hasFile('document')){
            $input['document'] = Str::slug($unique, '-').'.'.$request->document->getClientOriginalExtension();
            $request->document->move(public_path('/storage/ticketing'), $input['document']);
        }

        $input['user_id'] = Auth::user()->id;
        $input['status'] = 0;
        $input['ticket_number'] = "DIT-".random_int(10000000, 99999999);
        Ticketing::create($input);
        Session::flash('success', "");
        return redirect('/ticketing');
        
    }

    public function ticketing_detail($number) {
        $view = "ticketing-detail";
        $detail = Ticketing::where('ticket_number', $number)->first();
        $category = TicketingCategory::findorFail($detail->department);
        $data = TicketingContent::where('ticket_number', $number)->orderBy('id', 'desc')->get();
        return view("frontend.ticketing_detail", compact('view','detail','category','data'));
    }

    public function download($file_name) {
        $file_path = public_path('storage/ticketing/'.$file_name);
        return response()->download($file_path);
    }

    public function reply(Request $request) {
        $input = $request->all();
        
        $rules = array(
            "message" => "required",
        );

        $validator = Validator::make($input,$rules);
        if($validator->fails()) {
            return Redirect::back()->with('error', $validator->errors());
        }

        $input['document'] = null;
        $unique = uniqid();
        if($request->hasFile('document')){
            $input['document'] = Str::slug($unique, '-').'.'.$request->document->getClientOriginalExtension();
            $request->document->move(public_path('/storage/ticketing'), $input['document']);
        }

        TicketingContent::create($input);
        return Redirect::back()->with('success', "Reply Successfully Added");
    }
}
