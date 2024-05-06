<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Notif;
use App\Models\User;
use App\Models\AdminsData;

class NotifController extends Controller
{
    public function notif_list($id) {
    	$data = Notif::where('user_id', $id)->orWhere('user_id', -1)->orderBy('created_at', 'desc')->get();
    	$rows = [];
    	foreach($data as $key) {
    		if($key->admin_id == -1) {
    			$row['admin_name'] = "Auto Sending";
    		} else {
    			$admin = AdminsData::findorFail($key->admin_id);
    			$row['admin_name'] = $admin->name;
    		}


    		$row['id'] = $key->id;
    		$row['title'] = $key->title;
    		$row['slug'] = $key->slug;
    		$row['message'] = $key->message;
    		$row['image'] = $key->image;
    		$row['admin_id'] = $key->admin_id;
    		$row['user_id'] = $key->user_id;
    		$row['status'] = $key->status;
    		$row['created_at'] = $key->created_at;
    		$row['pesan_singkat'] = substr($key->message, 0, 120)." ....";
    		$row['waktu'] = date('d F Y H:i:s', strtotime($key->created_at));


    		array_push($rows, $row);
     	}

    	return response()->json([
    		"success" => true,
    		"data" => $rows
    	]);
    }
}
