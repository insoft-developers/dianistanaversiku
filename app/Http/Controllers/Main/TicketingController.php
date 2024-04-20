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
        $this->prepare_send($input['priority'].' - New Ticket Has Arrived : ', $input['subject']);


        Session::flash('success', "");
        return redirect('/ticketing');
        
    }

    public function prepare_send($title, $message) {
        $admins = \App\Models\AdminsData::where('remember_token', '!=', '')->get();
        foreach($admins as $admin) {
            $this->notify($title, $message, $admin->remember_token);
        }
    }


    public function notify($title, $message, $regid) {
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

        $query = TicketingContent::create($input);
        if($query) {
            $data = \App\Models\Ticketing::where('ticket_number', $input['ticket_number'])->first();
            $data->status = 0;
            $data->updated_at = date('Y-m-d H:i:s');
            $data->save();

            $this->sent_to_single($input['ticket_number'], $input['message']);
        }
        return Redirect::back()->with('success', "Reply Successfully Added");
    }

    public function sent_to_single($ticket_number, $message) {
        $t = TicketingContent::where('ticket_number', $ticket_number)
            ->where('is_reply', 1);
        $data = Ticketing::where('ticket_number', $ticket_number)->first();

        if($t->count() > 0) {
            $send = $t->first();
            $admin = \App\Models\AdminsData::findorFail($send->user_id);
            $regid = $admin->remember_token;
            $this->notify("Reply From ".Auth::user()->name." For".$data->subject, $message, $regid);
        } else {
            $this->prepare_send($data->priority.' - New Ticket Has Arrived : ', $data->subject);
        }
        
    }
}
