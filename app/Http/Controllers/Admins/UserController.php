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

class UserController extends Controller
{
    use DataTablesTraitStatic;
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $view = "user-list";
        return view("admins.user.index", compact('view'));
    }


    public function ajax_list()
    {
        $user = User::all();
        return Datatables::of($user)
            ->addColumn('is_active', function($user){
                if($user->is_active == 1) {
                    return '<center><i title="active" class="fa fa-check-circle text-success"></i></center>';
                } else {
                    return '<center><i title="not active" class="fa fa-exclamation-circle text-danger"></i></center>';
                }
            })
            ->addColumn('foto', function($user){
                if($user->foto == null || $user->foto == '') {
                    return '<img class="img-list-data" src="'.asset('template/images/profil_icon.png').'">';
                } else {
                    return '<a href="'.asset('storage/profile/'.$user->foto).'" target="_blank"><img class="img-list-data" src="'.asset('storage/profile/'.$user->foto).'"></a>';
                }
            })
            ->addColumn('action', function($user){
                $phone = str_replace("+", "", $user->no_hp);

                return '<a href="https://api.whatsapp.com/send/?phone='.$phone.'&text&type=phone_number&app_absent=0" target="_blank" class="bs-tooltip text-success mb-2" data-bs-toggle="tooltip" data-bs-placement="top" data-original-title="Whatsapp" aria-label="Whatsapp" data-bs-original-title="Whatsapp" title="Whatsapp"><i class="fab fa-whatsapp"></i></a>&nbsp;&nbsp;<a href="javascript:void(0);" class="bs-tooltip text-success mb-2" data-bs-toggle="tooltip" data-bs-placement="top" data-original-title="Detail" aria-label="Edit" data-bs-original-title="Detail" title="Detail" onclick="detailData('.$user->id.')"><i class="far fa-file"></i></a>&nbsp;&nbsp;<a href="javascript:void(0);" class="bs-tooltip text-warning mb-2" data-bs-toggle="tooltip" data-bs-placement="top" data-original-title="Edit" aria-label="Edit" data-bs-original-title="Edit" title="Edit" onclick="editData('.$user->id.')"><i class="far fa-edit"></i></a>&nbsp;<a href="javascript:void(0);" class="bs-tooltip text-danger mb-2" data-bs-toggle="tooltip" data-bs-placement="top" data-original-title="Hapus" aria-label="Hapus" data-bs-original-title="Hapus" title="Hapus" onclick="deleteData('.$user->id.')"><i class="far fa-times-circle"></i></i></a>';
        })->rawColumns(['action','is_active','foto'])
        ->addIndexColumn()
        ->make(true);
    }

    public function ajax_list_trash(Request $request)
    {
        return self::set_ajax_list($request, true);
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
        $input = $request->all();

        $rules = array(
            "name" => "required",
            "username" => "required|unique:users,username",
            "email" => "required|email|unique:users,email",
            "password" => "required|min:6",
            "jenis_kelamin" => "required",
            "no_hp" => "required|unique:users,no_hp",
            "level" => "required",
            "is_active" => "required",
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
        try {
            $input['image'] = null;
            $unik = uniqid();
            if($request->hasFile('image')){
                $input['image'] = Str::slug($unik, '-').'.'.$request->image->getClientOriginalExtension();
                $request->image->move(public_path('/storage/profile'), $input['image']);
            }

            $input['foto'] = $input['image'];
            User::create($input);
            return response()->json([
                "success" => true,
                "message" => "New Data Successfully Added.."
            ]);
        }catch(\Exception $e) {
            return response()->json([
                "success" => false,
                "message" => $e->getMessage()
            ]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $user = User::findorFail($id);
        
        $HTML = "";
        $HTML .= '<input type="hidden" value="'.$id.'" id="id-detail">';
        $HTML .= '<div class="row">';
        $HTML .= '<div class="col-md-6">';
        $HTML .= '<div class="card">';
        $HTML .= '<div class="card-body">';
        $HTML .= '<table class="table table-bordered table-striped">';
        $HTML .= '<tbody>';
        if($user->foto != null && $user->foto != '') {
            $HTML .= '<tr><th>Foto</th><th><img class="img-detail" src="'.asset('storage/profile/'.$user->foto).'"></th></tr>';
        } else {
            $HTML .= '<tr><th>Foto</th><th><img class="img-detail" src="'.asset('template/images/profil_icon.png').'"></th></tr>';
        }
        
        $HTML .= '<tr><th>Name</th><th>'.$user->name.'</th></tr>';
        $HTML .= '<tr><th>Username</th><th>'.$user->username.'</th></tr>';
        $HTML .= '<tr><th>Email</th><th>'.$user->email.'</th></tr>';
        $HTML .= '<tr><th>Jenis Kelamin</th><th>'.$user->jenis_kelamin.'</th></tr>';
        $HTML .= '<tr><th>Whatsapp</th><th>'.$user->no_hp.'</th></tr>';
        $HTML .= '<tr><th>Level</th><th>'.$user->level.'</th></tr>';
        if($user->is_active == 1) {
            $HTML .= '<tr><th>Status</th><th>Active</th></tr>';
        } else {
            $HTML .= '<tr><th>Status</th><th>Not Active</th></tr>';
        }
        
      
        $HTML .= '</tbody>';
        $HTML .= '</table>';


        $HTML .= '</div>'; //cardbody
        $HTML .= '</div>'; //card

        $HTML .= '</div>'; //col-md-6
        $HTML .= '<div class="col-md-6">';
        $HTML .= '<div class="card">';
        $HTML .= '<div class="card-body">'; 
        $HTML .= '<table class="table table-bordered table-striped">';
        $HTML .= '<tbody>';
        
        $HTML .= '<tr><th>Penyelia</th><th>'.$user->penyelia.'</th></tr>';
        $HTML .= '<tr><th>Blok/No Rumah</th><th>'.$user->blok.' / '.$user->nomor_rumah.'</th></tr>';
        $HTML .= '<tr><th>Daya Listrik</th><th>'.$user->daya_listrik.'</th></tr>';
        $HTML .= '<tr><th>Luas Tanah</th><th>'.$user->luas_tanah.'</th></tr>';
        $HTML .= '<tr><th>Iuran Bulanan</th><th>Rp. '.number_format($user->iuran_bulanan).'</th></tr>';
        $HTML .= '<tr><th>Whatsapp Emergency</th><th>'.$user->whatsapp_emergency.'</th></tr>';
        $HTML .= '<tr><th>Keterangan</th><th>'.$user->keterangan.'</th></tr>';
        $HTML .= '<tr><th>Alamat Surat Menyurat</th><th>'.$user->alamat_surat_menyurat.'</th></tr>';
        $HTML .= '<tr><th>No Telp Rumah</th><th>'.$user->nomor_telepon_rumah.'</th></tr>';
        $HTML .= '<tr><th>PDAM ID</th><th>'.$user->id_pelanggan_pdam.'</th></tr>';
        $HTML .= '<tr><th>PLN Meter</th><th>'.$user->nomor_meter_pln.'</th></tr>';
        $HTML .= '<tr><th>Mulai Menempati</th><th>'.date('d-m-Y', strtotime($user->mulai_menempati)).'</th></tr>';
        $HTML .= '<tr><th>Created At</th><th>'.date('d-m-Y', strtotime($user->created_at)).'</th></tr>';
       
      
        $HTML .= '</tbody>';
        $HTML .= '</table>';


        $HTML .= '</div>'; //cardbody
        $HTML .= '</div>'; //card

        $HTML .= '</div>';
        $HTML .= '</div>'; //row
    
     
    

        return $HTML;
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $query = User::findorFail($id);
        return $query;
    }

    
    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $input = $request->all();

        $data = User::findorFail($id);

        $rules = array(
            "name" => "required",
            "username" => "required|".Rule::unique('users')->ignore($id),
            "email" => "required|email|".Rule::unique('users')->ignore($id),
            "jenis_kelamin" => "required",
            "no_hp" => "required|".Rule::unique('users')->ignore($id),
            "level" => "required",
            "is_active" => "required",
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
        try {
            $input['image'] = $data->foto;
            $unik = uniqid();
            if($request->hasFile('image')){
                if($data->foto != null && $data->foto != '') {
                    $path = public_path('/storage/profile/'.$data->foto);
                    if(file_exists($path)) {
                        unlink($path);
                    }
                    $input['image'] = Str::slug($unik, '-').'.'.$request->image->getClientOriginalExtension();
                    $request->image->move(public_path('/storage/profile'), $input['image']);
                }
                
            }

            $input['foto'] = $input['image'];
            if(! empty($input['password'])) {
                $input['password'] = $input['password'];
            } else {
                $input['password'] = $data->password;
            }

            $data->update($input);
            return response()->json([
                "success" => true,
                "message" => "Data Successfully Updated.."
            ]);
        }catch(\Exception $e) {
            return response()->json([
                "success" => false,
                "message" => $e->getMessage()
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
       $query = User::destroy($id);
       return $query;
    }


    public function print_detail($id) {
        $user = User::findorFail($id);
        return view('admins.user.print', compact('user'));
    }

    public function upgrade_iuran_bulanan(Request $request) {
        $input = $request->all();
        $rules = array("up"=> "required");
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

        $user = User::all();
        foreach($user as $u) {
            $persen = $input['up'] * $u->iuran_bulanan /100;
            $baru = (int)$persen + $u->iuran_bulanan; 


            $usee = User::findorFail($u->id);
            $usee->iuran_bulanan = $baru;
            $usee->save();
        }

        return response()->json([
            "success" => true,
            "message" => "Upgrade Iuran Successfully executed.."
        ]);

        
    }

   
}
