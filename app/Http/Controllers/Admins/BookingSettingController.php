<?php

namespace App\Http\Controllers\Admins;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\BookingSetting;
use DataTables;
use App\Models\UnitBisnis;
use Validator;

class BookingSettingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function ajax_list()
    {
        $data = BookingSetting::all();
        return Datatables::of($data)
            ->addColumn('booking_time', function($data){
                return '<div>'.$data->start_time.' - '.$data->finish_time.'</div>';
            })
            ->addColumn('is_active', function($data){
                if($data->is_active == 1) {
                    return '<center><i title="active" class="fa fa-check-circle text-success"></i></center>';
                } else {
                    return '<center><i title="not active" class="fa fa-exclamation-circle text-danger"></i></center>';
                }
            })
           
            ->addColumn('action', function($data){
                return '<a href="javascript:void(0);" class="bs-tooltip text-warning mb-2" data-bs-toggle="tooltip" data-bs-placement="top" data-original-title="Edit" aria-label="Edit" data-bs-original-title="Edit" title="Edit" onclick="editData('.$data->id.')"><i class="far fa-edit"></i></a>&nbsp;<a href="javascript:void(0);" class="bs-tooltip text-danger mb-2" data-bs-toggle="tooltip" data-bs-placement="top" data-original-title="Hapus" aria-label="Hapus" data-bs-original-title="Hapus" title="Hapus" onclick="deleteData('.$data->id.')"><i class="far fa-times-circle"></i></i></a>';
        })->rawColumns(['action','is_active','booking_time'])
        ->addIndexColumn()
        ->make(true);
    }


    public function index()
    {
        $view = 'booking-setting';
        $units = UnitBisnis::where('status_booking', 'Aktif')->get();
        return view('admins.booking_setting.index', compact('view','units'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $input = $request->all();
        
        if($input['type'] == 1) {
            $input['booking_day'] = "";
        } else {
            $input['date'] = null;
        }

        $rules = array(
            "type" => "required",
            "unit_id" => "required",
            "finish_time" => "required",
            "is_active" => "required"
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

        BookingSetting::create($input);
        return response()->json([
            "success" => true,
            "message" => "Success"
        ]);

    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $data = BookingSetting::findorFail($id);
        return $data;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $input = $request->all();
        
        if($input['type'] == 1) {
            $input['booking_day'] = "";
        } else {
            $input['date'] = null;
        }

        $rules = array(
            "type" => "required",
            "unit_id" => "required",
            "finish_time" => "required",
            "is_active" => "required"
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

        $data = BookingSetting::findorFail($id);
        $data->update($input);
        return response()->json([
            "success" => true,
            "message" => "Success"
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $query = BookingSetting::destroy($id);
        return $query;
    }
}
