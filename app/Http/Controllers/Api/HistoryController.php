<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\AdminsData;
use App\Models\Transaction;
use App\Models\Setting;
use App\Models\UnitBisnis;
use Validator;
use DB;

class HistoryController extends Controller
{
    public function history($id) {
    	$data = DB::table('transactions')
    		->select('transactions.*', 'users.name','unit_bisnis.name_unit')
    		->join('users', 'users.id', '=', 'transactions.user_id', 'left')
    		->join('unit_bisnis', 'unit_bisnis.id', '=', 'transactions.business_unit_id', 'left')
    		->where('transactions.user_id', $id)
    		->orderBy('transactions.id', 'desc')
    		->get();

    	return response()->json([
    		"success" => true,
    		"data" => $data
    	]);

    }
}
