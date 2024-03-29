<?php

namespace App\Http\Controllers\Main;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use App\Models\BannerIklan;
use App\Models\Setting;

class DashboardMainController extends Controller
{
    public function index(): View 
    {
        return view("main.dashboard.index");
    }

    public function dashboard() {
    	$view = "dashboard";
    	$banner = BannerIklan::where('is_active', 1)->get();
    	return view('frontend.dashboard', compact('view','banner'));
    }

    public function term() {
    	$setting = Setting::findorFail(1);
    	$view = 'term';
    	return view('frontend.term', compact('view', 'setting'));
    }

    public function privacy() {
    	$setting = Setting::findorFail(1);
    	$view = 'privacy';
    	return view('frontend.privacy', compact('view', 'setting'));
    }

    public function contact() {
    	$setting = Setting::findorFail(1);
    	$view = 'contact';
    	return view('frontend.contact', compact('view', 'setting'));
    }

    public function send_wa() {
    	
		$key='c50b2c98d96b93b80307edbb3e85d4eab676044e3ecd3181'; //this is demo key please change with your own key
		$url='http://116.203.191.58/api/send_message';
		$data = array(
		  "phone_no"  => '+6282165174835',
		  "key"       => $key,
		  "message"   => 'DEMO AKUN WOOWA. tes woowa api v3.0 mohon di abaikan',
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
}
