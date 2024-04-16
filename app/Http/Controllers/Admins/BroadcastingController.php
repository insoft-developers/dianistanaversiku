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
use App\Models\Broadcasting;
use Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;

class BroadcastingController extends Controller
{
    use DataTablesTraitStatic;
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $view = "broadcasting";
        $user = User::all();
        return view("admins.broadcasting.index", compact('view','user'));
    }


    public function ajax_list()
    {
        $data = Broadcasting::all();
        return Datatables::of($data)
            ->addColumn('created_at', function($data){
                return date('d-m-Y', strtotime($data->created_at));
            })
            ->addColumn('message', function($data){
                return '<div style="white-space:normal;width:200px;"><a href="#">'.substr($data->message, 0, 80).'...</a></div>';
            })
            ->addColumn('title', function($data){
                return '<div style="white-space:normal;width:100px;">'.$data->title.'</div>';
            })
            ->addColumn('sending_status', function($data){
                if($data->sending_status == 1) {
                    return '<center><i class="fa fa-check-circle text-success"></i> Sent</center>';
                } else {
                    return '<center><i class="fa fa-exclamation-circle text-warning"></i> Waiting..</center>';
                }
            })
            ->addColumn('image', function($data){
                if($data->image == null || $data->image == '') {
                    return '';
                } else {
                    return '<a href="'.asset('template/images/notif/'.$data->image).'" target="_blank"><img class="img-list-data" src="'.asset('template/images/notif/'.$data->image).'"></a>';
                }
            })
            ->addColumn('admin_id', function($data){
                $users = \App\Models\AdminsData::where('id', $data->admin_id);
                if($users->count() > 0) {
                    $user = $users->first();
                    return $user->name;
                } else {
                    return '';
                }

            })
            ->addColumn('user_id', function($data){
                if($data->user_id < 0) {
                    return 'All User';
                } else {
                    $users = \App\Models\User::where('id', $data->user_id);
                    if($users->count() > 0) {
                        $user = $users->first();
                        return $user->name.'<br>( '.$user->level.' )';
                    } else {
                        return '';
                    }
                }
                

            })
            ->addColumn('action', function($data){
               
                return '<a href="javascript:void(0);" class="bs-tooltip text-success mb-2" data-bs-toggle="tooltip" data-bs-placement="top" data-original-title="Detail" aria-label="Edit" data-bs-original-title="Detail" title="Detail" onclick="detailData('.$data->id.')"><i class="far fa-file"></i></a>&nbsp;&nbsp;<a href="javascript:void(0);" class="bs-tooltip text-warning mb-2" data-bs-toggle="tooltip" data-bs-placement="top" data-original-title="Edit" aria-label="Edit" data-bs-original-title="Edit" title="Edit" onclick="editData('.$data->id.')"><i class="far fa-edit"></i></a>&nbsp;<a href="javascript:void(0);" class="bs-tooltip text-danger mb-2" data-bs-toggle="tooltip" data-bs-placement="top" data-original-title="Hapus" aria-label="Hapus" data-bs-original-title="Hapus" title="Hapus" onclick="deleteData('.$data->id.')"><i class="far fa-times-circle"></i></i></a>';
        })->rawColumns(['action','created_at','message','title','image','admin_id','user_id','sending_status'])
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

  
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $input = $request->all();

        $rules = array(
            "title" => "required",
            "pesan" => "required",
            "user_id" => "required",
            "send_date" => "required"
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
                $request->image->move(public_path('/template/images/notif'), $input['image']);
            }
            $input['message'] = $input['pesan'];
            $input['admin_id'] = adminAuth()->id;
            $input['sending_status'] = 0;
            Broadcasting::create($input);
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

        $data = [

            "to" => '/topics/dianistana_user',
            // "to" => $regid,
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

   
}
