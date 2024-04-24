<?php

namespace App\Http\Controllers\Admins;

use App\Helpers\Resp;
use Illuminate\View\View;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use App\Traits\DBcustom\DataTablesTraitStatic;
use DataTables;
use Validator;
use Illuminate\Validation\Rule;
use App\Models\Setting;
use Illuminate\Support\Facades\Auth;

use Session;
use Redirect;
use App\Models\Blok;

class SettingController extends Controller
{
    use DataTablesTraitStatic;
    /**
     * Display a listing of the resource.
     */

    public function blok_setting_list() {
        $data = Blok::all();
        return Datatables::of($data)
            
            ->addColumn('action', function($data){
                
                $button = "";
                $button .= '<a href="javascript:void(0);" class="bs-tooltip text-warning mb-2" data-bs-toggle="tooltip" data-bs-placement="top" data-original-title="Edit" aria-label="Edit" data-bs-original-title="Edit" title="Edit" onclick="editData('.$data->id.')"><i class="far fa-edit"></i></a>&nbsp;';

                if(adminAuth()->level == 'admin') {

                } else {
                    $button .= '<a href="javascript:void(0);" class="bs-tooltip text-danger mb-2" data-bs-toggle="tooltip" data-bs-placement="top" data-original-title="Hapus" aria-label="Hapus" data-bs-original-title="Hapus" title="Hapus" onclick="deleteData('.$data->id.')"><i class="far fa-times-circle"></i></i></a>';
                }

                return $button;
        })->rawColumns(['action'])
        ->addIndexColumn()
        ->make(true);
    }
    public function index(): View
    {
        $view = "setting-list";
        $setting = Setting::findorFail(1);
        $terms = $setting->term;
        $privacies = $setting->privacy;
        $bloks = \App\Models\Blok::all();
        return view("admins.setting.index", compact('view','setting','terms', 'privacies','bloks'));
    }


    public function setting_update(Request $request) {
        $input = $request->all();
        
        $rules = array(
            "app_name" => "required",
            "app_title" =>"required",
            "address_title" => "required",
            "phone" => "required",
            "address" => "required",
            "hp" => "required",
            "email" => "required|email",
            "api_wa" =>"required",
            "api_payment" => "required",
            "duitku_url" => "required",
            "callback_payment"=> "required",
            "merchant_code" => "required",
            "term" => "required",
            "privacy" => "required",
            "tanggal_jatuh_tempo_iuran_bulanan" => "required",
            "tgl_create_iuran_bulanan" => "required",
            "percent_denda" => "required"

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

            return Redirect::back()->with('error', $html);
        } 

        $setting = Setting::findorFail(1);
        $setting->update($input);
        return Redirect::back()->with('success', 'Setting successfully updated...');

    }

    public function password_admin_update(Request $request) {
        $input = $request->all();
        $rules = array(
            "old_password" => "required",
            "password" => "required|min:6|confirmed"
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
            return Redirect::back()->with('error', $html);
        }
        
        if(Auth::guard('webadmin')->attempt(['username'=> $input['username'] ,'password'=> $input['old_password']])) {
            $user = \App\Models\AdminsData::findorFail(adminAuth()->id);
            $user->password = bcrypt($input['password']);
            $user->save();
            return Redirect::back()->with('success', 'Your password successfully updated!!');
        
        } else {
            return Redirect::back()->with('error', 'Password anda masih salah!!');
        }

    }
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        abort(404);
    }

    private static function intReplace($val): int
    {
        return intval(str_replace(".","",$val));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
      
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
       
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
       
    }

    
    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
       
    }

    public function change_password() {
        $view = "change_password";
        return view('admins.setting.change_password', compact('view'));
    }

    public function add_blok(Request $request) {
        $input = $request->all();
        if($input['method'] == 'add') {
            $blok = new Blok;
            $blok->blok_name = $input['blok'];
            $blok->save();
        } else {
            $blok = Blok::findorFail($input['id']);
            $blok->blok_name = $input['blok'];
            $blok->save();
        }

        return response()->json([
            "success" => true,
            "message" => "success"
        ]);
    }

    public function blok_edit($id) {
        $data = Blok::findorFail($id);
        return $data;
    }

    public function blok_delete(Request $request) {
        $input = $request->all();
        $query = Blok::destroy($input['id']);
        return $query;
    }

   
}
