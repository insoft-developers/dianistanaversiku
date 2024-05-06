<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Ticketing;
use App\Models\TicketingContent;
use App\Models\TicketingCategory;
use App\Models\User;
use App\Models\AdminsData;
use Validator;
use Illuminate\Support\Facades\Storage;

class TicketingController extends Controller
{
    public function ticketing_list($id) {
    	$data = [];
    	$query = Ticketing::where('user_id', $id)->orderBy('created_at','desc')->get();
    	foreach ($query as $key ) {
    		$row['id'] = $key->id;
    		$row['ticket_number'] = $key->ticket_number;
    		$row['user_id'] = $key->user_id;
    		$row['subject'] = $key->subject;
    		$row['department'] = $key->department;
    		$row['priority'] = $key->priority;
    		$row['message'] = $key->message;
    		$row['document'] = $key->document;
    		$row['status'] = $key->status;
    		$row['created_at'] = $key->created_at;
    		$row['updated_at'] = $key->updated_at;
    		$row['pesan'] = substr($key->message, 0, 120).'...';	
    		if($key->status == 0) {
    			$row['stat'] =  "Waiting for Admin Response";
    		} else if($key->status == 1) {
    			$row['stat'] =  "Waiting for Your Response";	
    		} else if($key->status == 2) {
    			$row['stat'] =  "On Hold";	
    		} else if($key->status == 3) {
    			$row['stat'] =  "Ticket Resolved";	
    		} 
            
            
    		$category = TicketingCategory::findorFail($key->department);
    		$row['category'] = $category->category_name;

    		array_push($data, $row);
    	}


    	return response()->json([
    		"success" => true,
    		"data" => $data
    	]);
    }

    public function ticketing_detail($number) {
        
        $query = TicketingContent::where('ticket_number', $number)->orderBy('id', 'desc')->get();
        $data = [];
        foreach ($query as $key ) {
        	$row['id'] = $key->id;
        	$row['ticket_number'] = $key->ticket_number;
        	$row['user_id'] = $key->user_id;
        	$row['message'] = $key->message;
        	$row['is_reply'] = $key->is_reply;
        	$row['document'] = $key->document;
        	$row['created_at'] = $key->created_at;

        	if($key->is_reply == 1) {
        		$user = AdminsData::findorFail($key->user_id);
        		
        		$row['profile_foto'] = "https://ui-avatars.com/api/?name=".$user->name;
        		// $row['profile_foto'] = asset('template/images/profil_icon.png');
        		
        	} else {
        		$user = User::findorFail($key->user_id);
        		if($user->foto != null) {
        			$row['profile_foto'] = asset('storage/profile/'.$user->foto);
        		} else {
        			$row['profile_foto'] = asset('template/images/profil_icon.png');
        		}
        		
        	}

        	$row['profile_name'] = $user->name;
        	$row['waktu'] = date('d F Y', strtotime($key->created_at))." Pukul ".date('H:i:s', strtotime($key->created_at));


        	array_push($data, $row);
        	# code...
        }

        return response()->json([
    		"success" => true,
    		"data" => $data
    	]);
        
    }

    public function department() {
    	$data = TicketingCategory::all();
    	return response()->json([
    		"success" => true,
    		"data" => $data
    	]);
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

            return response()->json([
            	"success" => false,
            	"message" => $html
            ]);
        }

        $input['user_id'] = $input['user_id'];
        $input['status'] = 0;
        $input['ticket_number'] = "DIT-".random_int(10000000, 99999999);
        $id = Ticketing::create($input)->id;
        $this->prepare_send($input['priority'].' - New Ticket Has Arrived : ', $input['subject']);

        return response()->json([
        	"success" => true,
        	"message" => "New ticket has created successfully",
        	"id" => $id

        ]);
        
        
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

    public function upload(Request $request) {


        $dir = 'ticketing/';
        $image = $request->file('image');
        $ids = $request->ids;
    
        if($request->has('image')) {
        	$extension = $request->file('image')->extension();
            $imageName = \Carbon\Carbon::now()->toDateString()."-".uniqid().".".$extension;
            Storage::disk('public')->put($dir.$imageName, file_get_contents($image));
    
        } else {
            return response()->json(['message'=> trans('/storage/test/'.'def.png.')],200);
        }

    
        $data = Ticketing::findorFail((int)$ids);
        $data->document = $imageName;
        $data->save();

        $content = TicketingContent::where('ticket_number', $data->ticket_number)->first();
        $content->document = $imageName;
        $content->save();


        return response()->json(['message'=> trans('/storage/test/'.$imageName)],200);
    }

    public function reply(Request $request) {
    	$input = $request->all();
        
        $rules = array(
            "message" => "required",
        );

        $validator = Validator::make($input,$rules);
        if($validator->fails()) {
            return response()->json([
            	"success" => false,
            	"message" => $validator->errors()

            ]);
        }

       
        $content = new TicketingContent;
        $content->ticket_number = $input['ticket_number'];
        $content->user_id = $input['user_id'];
        $content->message = $input['message'];
        $content->is_reply = 0;
        $content->created_at = date('Y-m-d H:i:s');
		$content->updated_at = date('Y-m-d H:i:s');
		$content->save();

        if($content) {
            $user = User::findorFail($input['user_id']);

            $data = \App\Models\Ticketing::where('ticket_number', $input['ticket_number'])->first();
            $data->status = 0;
            $data->updated_at = date('Y-m-d H:i:s');
            $data->save();

            $this->sent_to_single($input['ticket_number'], $input['message'], $user->name);
        }
        return response()->json([
            "success" => true,
            "message" => "success",
            "id" => $content->id

        ]);
    }

    public function reply_upload(Request $request) {
        $dir = 'ticketing/';
        $image = $request->file('image');
        $ids = $request->ids;
    
        if($request->has('image')) {
            $extension = $request->file('image')->extension();
            $imageName = \Carbon\Carbon::now()->toDateString()."-".uniqid().".".$extension;
            Storage::disk('public')->put($dir.$imageName, file_get_contents($image));
    
        } else {
            return response()->json(['message'=> trans('/storage/test/'.'def.png.')],200);
        }

        $content = TicketingContent::findorFail((int)$ids);
        $content->document = $imageName;
        $content->save();


        return response()->json(['message'=> trans('/storage/test/'.$imageName)],200);
    }



    public function sent_to_single($ticket_number, $message, $name) {
        $t = TicketingContent::where('ticket_number', $ticket_number)
            ->where('is_reply', 1);
        $data = Ticketing::where('ticket_number', $ticket_number)->first();

        if($t->count() > 0) {
            $send = $t->first();
            $admin = \App\Models\AdminsData::findorFail($send->user_id);
            $regid = $admin->remember_token;
            $this->notify("Reply From ".$name." For".$data->subject, $message, $regid);
        } else {
            $this->prepare_send($data->priority.' - New Ticket Has Arrived : ', $data->subject);
        }
        
    }
}
