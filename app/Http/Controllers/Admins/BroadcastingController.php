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
        $bloks = \App\Models\Blok::all();
        return view("admins.broadcasting.index", compact('view','user','bloks'));
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
                if($data->is_blok == 1) {
                    $bloks = \App\Models\Blok::where('id', $data->user_id);
                    if($bloks->count() > 0) {
                        $blok = $bloks->first();
                        return 'BLOK - '.$blok->blok_name;
                    } else {
                        return '-';
                    }
                } else {
                    if($data->user_id == -1) {
                        return 'All User';
                    } 
                    else {
                        $users = \App\Models\User::where('id', $data->user_id);
                        if($users->count() > 0) {
                            $user = $users->first();
                            return $user->name.'<br>( '.$user->level.' )';
                        } else {
                            return '';
                        }
                    }
                    

                }
                
            })
            ->addColumn('action', function($data){
                if(adminAuth()->level == 'admin') {
                    return '<a href="javascript:void(0);" class="bs-tooltip text-warning mb-2" data-bs-toggle="tooltip" data-bs-placement="top" data-original-title="Edit" aria-label="Edit" data-bs-original-title="Edit" title="Edit" onclick="editData('.$data->id.')"><i class="far fa-edit"></i></a>';
                } else {
                    return '<a href="javascript:void(0);" class="bs-tooltip text-warning mb-2" data-bs-toggle="tooltip" data-bs-placement="top" data-original-title="Edit" aria-label="Edit" data-bs-original-title="Edit" title="Edit" onclick="editData('.$data->id.')"><i class="far fa-edit"></i></a>&nbsp;<a href="javascript:void(0);" class="bs-tooltip text-danger mb-2" data-bs-toggle="tooltip" data-bs-placement="top" data-original-title="Hapus" aria-label="Hapus" data-bs-original-title="Hapus" title="Hapus" onclick="deleteData('.$data->id.')"><i class="far fa-times-circle"></i></i></a>';
                }
                
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
            "message" => "required",
            "user_id" => "required",
            "send_date" => "required"
        );

        if($input['user_id'] == -2) {
            $rules['blok'] = "required";
        }



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
            $input['admin_id'] = adminAuth()->id;
            $input['sending_status'] = 0;
            $input['is_blok'] = 0;
            if($input['user_id'] == -2) {
                $input['user_id'] = $input['blok'];
                $input['is_blok'] = 1;
            }

            $br = Broadcasting::create($input);
            $id = $br->id;
            $sekarang = date('Y-m-d');
            if($input['send_date'] == $sekarang) {

                if($input['is_blok'] == 1) {
                    $blok = \App\Models\Blok::findorFail($input['blok']);
                    $users = \App\Models\User::where('blok', $blok->blok_name)->get();
                    if($users->count() > 0) {
                        foreach($users as $user) {
                            if($user->token != null) {
                                $this->notify($input['title'], $input['message'], $user->id, $id);
                            }
                            $this->make_notif($input['title'], $input['message'], $input['image'], $user->id);
                        }
                    }
                } else {
                    $this->notify($input['title'], $input['message'], $input['user_id'], $id);
                    $this->make_notif($input['title'], $input['message'], $input['image'], $input['user_id']);
                }

                
            }

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
        $query = Broadcasting::findorFail($id);
        return $query;
    }

    
    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $input = $request->all();

        $data = Broadcasting::findorFail($id);

        $rules = array(
            "title" => "required",
            "message" => "required",
            "user_id" => "required",
            "send_date" => "required"
        );
        if($input['user_id'] == -2) {
            $rules['blok'] = "required";
        }

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
            $input['image'] = $data->image;
            $unik = uniqid();
            if($request->hasFile('image')){
                if(! empty($data->image)) {
                    $path = public_path('/template/images/notif/'.$data->image);
                    if(file_exists($path)) {
                        unlink($path);
                    }
                }
                $input['image'] = Str::slug($unik, '-').'.'.$request->image->getClientOriginalExtension();
                $request->image->move(public_path('/template/images/notif'), $input['image']);
            }
            $input['admin_id'] = adminAuth()->id;
            $input['sending_status'] = 0;
            $input['is_blok'] = 0;
            if($input['user_id'] == -2) {
                $input['user_id'] = $input['blok'];
                $input['is_blok'] = 1;
            }
            $br = $data->update($input);
            $sekarang = date('Y-m-d');
            if($input['send_date'] == $sekarang) {
                if($input['is_blok'] == 1) {
                    $blok = \App\Models\Blok::findorFail($input['blok']);
                    $users = \App\Models\User::where('blok', $blok->blok_name)->get();
                    if($users->count() > 0) {
                        foreach($users as $user) {
                            if($user->token != null) {
                                $this->notify($input['title'], $input['message'], $user->id, $id);
                            }
                            $this->make_notif($input['title'], $input['message'], $input['image'], $user->id);
                        }
                    }
                } else {
                    $this->notify($input['title'], $input['message'], $input['user_id'], $id);
                    $this->make_notif($input['title'], $input['message'], $input['image'], $input['user_id']);
                }
            }

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
        $query = Broadcasting::destroy($id);
        return $query;
    }

    public function check_broadcasting() {
        $sent = 0;
        $sekarang = date('Y-m-d');
        $cek = Broadcasting::where('sending_status', 0)->where('send_date', $sekarang)->get();
        if($cek->count() > 0) {
            foreach($cek as $key) {
                if($key->is_blok == 1) {
                    $blok = \App\Models\Blok::findorFail($key->user_id);
                    $users = \App\Models\User::where('blok', $blok->blok_name)->get();
                    if($users->count() > 0) {
                        foreach($users as $user) {
                            if($user->token != null) {
                                $this->notify($key->title, $key->message, $user->id, $key->id);
                            }
                        }
                    }
                } else {
                    $this->notify($key->title, $key->message, $key->user_id, $key->id);
                }
                $sent++;
            }
        }
        
        return $sent;
    }



    public function make_notif($title, $message, $image, $user_id) {
        $data = new \App\Models\Notif;
        $data->title = $title;
        $data->slug = str_replace(" ","-", $title);
        $data->message = $message;
        $data->image = $image;
        $data->admin_id = adminAuth()->id;
        $data->user_id = $user_id;
        $data->status = 0;
        $data->created_at = date('Y-m-d H:i:s');
        $data->updated_at = date('Y-m-d H:i:s');
        $data->save();
    }


    public function notify($title, $message, $user_id, $id) {
        
        $SERVER_API_KEY = 'AAAAwbylMgg:APA91bF2ALenum4cb5ossrjcPIXOGJbUyjrSDu7YUS6LS8RQI2WDKsliccvbH8JHP3zYJIaZSpS-emPRjDy3EzAZjEZu4NHTfPu1L4rtknAZgeYqpc5Ck-uzbc_nA0cgPYDmTH-5EQV7';

        if($user_id == -1) {
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
        } else {
            $user = User::findorFail($user_id);
            $regid = $user->token;
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
        }
        
       
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

        $br = Broadcasting::findorFail($id);
        $br->sending_status = 1;
        $br->save();
        
        return $response;
        
    }

   
}
