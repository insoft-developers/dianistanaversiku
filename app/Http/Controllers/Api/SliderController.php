<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\BannerIklan;

class SliderController extends Controller
{
    public function get_data_banner() {
    	$data = BannerIklan::where('is_active', 1)->get();
    	return response()->json([
    		"success" => true,
    		"data" => $data
    	]);
    }
}
