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
use App\Models\Ticketing;
use App\Models\TicketingContent;

class TicketController extends Controller
{
    use DataTablesTraitStatic;
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $view = "ticketing";
        return view("admins.ticketing.index", compact('view'));
    }


    public function ajax_list()
    {
        $data = Ticketing::all();
        return Datatables::of($data)
            ->addColumn('document', function($data){
                if($data->document != '') {
                    return '<a href="'.asset('storage/ticketing/'.$data->document).'" target="_blank">'.$data->document.'</a>';
                }
                
            })
            ->addColumn('status', function($data){
                if($data->status == 0) {
                    return '<div style="white-space:normal;" class="text-danger">Waiting Admin Response</div>';
                }
                else if($data->status == 1) {
                    return '<div style="white-space:normal;" class="text-warning">Waiting User Response</div>';
                }
                else if($data->status == 3) {
                    return '<div style="white-space:normal;" class="text-info"><i class="fa fa-check"></i> Ticket Resolved</div>';
                }
                else if($data->status == 2) {
                    return '<div style="white-space:normal;" class="text-success"><i class="fa fa-exclamation"></i> On Hold</div>';
                }
            })
            ->addColumn('created_at', function($data){
                return date('d-m-Y', strtotime($data->created_at));
            })
            ->addColumn('updated_at', function($data){
                return date('d-m-Y', strtotime($data->created_at));
            })
            ->addColumn('user_id', function($data){
                $users = \App\Models\User::where('id', $data->user_id);
                if($users->count() > 0) {
                    $user = $users->first();
                    return $user->name;
                } else {
                    return '';
                }
            })
            ->addColumn('department', function($data){
                $kat = \App\Models\TicketingCategory::where('id', $data->department);
                if($kat->count() > 0) {
                    $kats = $kat->first();
                    return $kats->category_name;
                } else {
                    return '';
                }
            })
            ->addColumn('subject', function($data){
                return '<div style="white-space:normal;">'.$data->subject.'</div>';
            })
          
            ->addColumn('action', function($data){
               

                return '<a href="javascript:void(0);" class="bs-tooltip text-success mb-2" data-bs-toggle="tooltip" data-bs-placement="top" data-original-title="Detail" aria-label="Detail" data-bs-original-title="Detail" title="Detail" onclick="detailData('.$data->id.')"><i class="far fa-file"></i></a>&nbsp;&nbsp;<a href="javascript:void(0);" class="bs-tooltip text-danger mb-2" data-bs-toggle="tooltip" data-bs-placement="top" data-original-title="Hapus" aria-label="Hapus" data-bs-original-title="Hapus" title="Hapus" onclick="deleteData('.$data->id.')"><i class="far fa-times-circle"></i></i></a>';
        })->rawColumns(['action','created_at','user_id','subject','department','updated_at','status','document'])
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
            "pesan" => "required",
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
            $input['document'] = null;
            $unik = uniqid();
            if($request->hasFile('document')){
                $input['document'] = Str::slug($unik, '-').'.'.$request->document->getClientOriginalExtension();
                $request->document->move(public_path('/storage/ticketing'), $input['document']);
            }

            $ticket = Ticketing::findorFail($input['ticket_id']);

            $query = new \App\Models\TicketingContent;
            $query->ticket_number = $ticket->ticket_number;
            $query->user_id = adminAuth()->id;
            $query->message = $input['pesan'];
            $query->is_reply = 1;
            $query->document = $input['document'];
            $query->created_at = date('Y-m-d H:i:s');
            $query->updated_at = date('Y-m-d H:i:s');
            $action = $query->save();
            
            if($action) {
                $ticket->status = 1;
                $ticket->updated_at = date('Y-m-d H:i:s');
                $ticket->save();
            }



            return response()->json([
                "success" => true,
                "message" => "New Post Successfully Added.."
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
        $ticket = Ticketing::findorFail($id);
        $number = $ticket->ticket_number;


        
        $data = TicketingContent::where('ticket_number', $number)->orderBy('id', 'desc')->get();
        
        $html = "";
        $html .= '<div class="row">';
        $html .= '<div class="col-md-12">';
        $html .= '<div class="card">';
        $html .= '<div class="card-body">';
        $html .= '<h3><i class="fa fa-ticket"></i> '.$ticket->subject.'</h3>';
        $html .= '</div>';
        $html .= '</div>';
      
        $html .= '<input type="hidden" id="ticket_id" name="ticket_id" value="'.$id.'">';
        $html .= '<div class="form-group mt20">';
        $html .= '<label>Attachment:</label>';
        $html .= '<input type="file" class="form-control" id="document" name="document">';

        $html .='</div>';
       
        $html .= '<div class="form-group mt20">';
        $html .= '<label>Message:</label>';
        $html .= '<textarea class="form-control" id="message" name="message"></textarea>';


        $html .= '<div class="form-group mt20">';
        $html .= '<button id="btn-post-reply" class="btn btn-success">Post</button>';
        $html .='</div>';
        $html .= '<hr />';
        

        $html .= '</div>';
        $html .= '</div>';
        foreach($data as $key) {
            $html .='<div class="row">';
            
            $html .='<div class="col-md-12">';
            $html .='<div class="card mt20">';
            $html .='<div class="card-body">';
            if($key->is_reply == 0) {
                $user = User::findorFail($key->user_id);
                $html .='<img class="image-ticket" src="'.asset('storage/profile/'.$user->foto).'">';
            } else {
                $user = \App\Models\AdminsData::findorFail($key->user_id);
                $html .='<img class="image-ticket" src="https://ui-avatars.com/api/?name='.$user->name.' &background=random">';
                
            }
            $html .= '<div class="problem-name">'.$user->name.'</div>';
            $html .= '<div class="problem-time">'.date('d F Y', strtotime($key->created_at)).' Pukul '.date('H:i', strtotime($key->created_at)).' WIB</div>';
            if($key->document != null && $key->document != '') {
                $html .= '<div class="problem-doc"><i class="fa fa-file"></i> <a href="'.asset('storage/ticketing/'.$key->document).'" target="_blank">'.$key->document.'</a></div>';
            }
            
            $html .= '<div class="problem-content">'.$key->message.'</div>';
            $html .= '</div>';
            $html .='</div>';
            $html .= '</div>';
            
            
            $html .='</div>';
        }
        
    
     
    

        return $html;
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
        $ticket = Ticketing::findorFail($id);
        $query = Ticketing::destroy($id);
        if($query) {
            TicketingContent::where('ticket_number', $ticket->ticket_number)->delete();
        }
       return $query;
    }


    public function set_on_hold(Request $request) {
       $input = $request->all();
       $data = Ticketing::findorFail($input['id']);
       $data->status = 2;
       $data->save();

       return response()->json([
            "success" => true,
            "message" => "Ticket Successfully Set To On Hold"
       ]);
    }


    public function set_resolved(Request $request) {
        $input = $request->all();
        $data = Ticketing::findorFail($input['id']);
        $data->status = 3;
        $data->save();
 
        return response()->json([
             "success" => true,
             "message" => "Ticket Successfully Set Resolved"
        ]);
     }

   
}
