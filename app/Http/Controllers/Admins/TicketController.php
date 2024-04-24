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
use App\Models\TicketingCategory;
use App\Models\Payment;

class TicketController extends Controller
{
    use DataTablesTraitStatic;
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $request->session()->forget('session_number_admin');

        
        $view = "ticketing";
        $category = TicketingCategory::all();
        return view("admins.ticketing.index", compact('view','category'));
    }


    public function ajax_list(Request $request)
    {
        $input = $request->all();
        $query = Ticketing::orderBy('id', 'desc');
        if(! empty($input['department'])) {
            $query->where('department', $input['department']);
        }
        if(! empty($input['priority'])) {
            $query->where('priority', $input['priority']);
        }
        if($input['status'] != '') {
            $query->where('status', $input['status']);
        }
        
        $data = $query->get();
        
        return Datatables::of($data)
            ->addColumn('document', function($data){
                if($data->document != '') {
                    return '<a href="'.asset('storage/ticketing/'.$data->document).'" target="_blank">'.$data->document.'</a>';
                }
                
            })
            ->addColumn('status', function($data){
                if($data->status == 0) {
                    return '<div onclick="detailData('.$data->id.')" style="white-space:normal;" class="text-danger">Waiting Admin Response</div>';
                }
                else if($data->status == 1) {
                    return '<div onclick="detailData('.$data->id.')" style="white-space:normal;" class="text-warning">Waiting User Response</div>';
                }
                else if($data->status == 3) {
                    return '<div onclick="detailData('.$data->id.')" style="white-space:normal;" class="text-info"><i class="fa fa-check"></i> Ticket Resolved</div>';
                }
                else if($data->status == 2) {
                    return '<div onclick="detailData('.$data->id.')" style="white-space:normal;" class="text-success"><i class="fa fa-exclamation"></i> On Hold</div>';
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
            ->addColumn('ticket_number', function($data){
                return '<div onclick="detailData('.$data->id.')">'.$data->ticket_number.'</div>';
            })
          
            ->addColumn('action', function($data){
                if(adminAuth()->level == 'admin') {
                    return '<a href="javascript:void(0);" class="bs-tooltip text-success mb-2" data-bs-toggle="tooltip" data-bs-placement="top" data-original-title="Detail" aria-label="Detail" data-bs-original-title="Detail" title="Detail" onclick="detailData('.$data->id.')"><i class="far fa-file"></i></a>';
                } else {
                    return '<a href="javascript:void(0);" class="bs-tooltip text-success mb-2" data-bs-toggle="tooltip" data-bs-placement="top" data-original-title="Detail" aria-label="Detail" data-bs-original-title="Detail" title="Detail" onclick="detailData('.$data->id.')"><i class="far fa-file"></i></a>&nbsp;&nbsp;<a href="javascript:void(0);" class="bs-tooltip text-danger mb-2" data-bs-toggle="tooltip" data-bs-placement="top" data-original-title="Hapus" aria-label="Hapus" data-bs-original-title="Hapus" title="Hapus" onclick="deleteData('.$data->id.')"><i class="far fa-times-circle"></i></i></a>';
                }

                
        })->rawColumns(['action','created_at','user_id','subject','department','updated_at','status','document','ticket_number'])
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
        $html .= '<input type="hidden" id="ticket_user_id" value="'.$ticket->user_id.'">';
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


     public function payment_ticketing_list($user_id) {
        $payment = Payment::where('payment_dedication', $user_id)
                    ->orWhere('payment_dedication', -1)
                    ->orderBy('id', 'desc')
                    ->get();

        $h = '';
        $h .= '<div class="table-responsive">';
        $h .= '<table id="table-payment-ticketing" class="table table-striped table-bordered">';
        $h .= '<thead>';
        $h .= '<tr>';
        $h .= '<th>No</th>';
        $h .= '<th>Action</th>';
        $h .= '<th>Date</th>';
        $h .= '<th>Title</th>';
        $h .= '<th>Type</th>';
        $h .= '<th>Due Date</th>';
        $h .= '<th>Period</th>';
        $h .= '<th>Amount</th>';
        $h .= '<th>Bill To</th>';
        $h .= '</tr>';
        $h .= '</thead>';
        $h .= '<tbody>';

        $no=0;
        foreach($payment as $pay) {

            if($pay->payment_dedication < 0 ) {
                $bill = 'All User';
            }else {
                $user_query = User::where('id', $pay->payment_dedication);
                if($user_query->count() > 0) {
                    $user = $user_query->first();
                    $bill = $user->name;
                } else {
                    $bill = 'User Not Found';
                }

            }

            $no++;
            $h .= '<tr>';
            $h .= '<td>'.$no.'</td>';
            $h .= '<td><center><a onclick="copy_payment_link('.$pay->id.')" href="javascript:void(0);">Copy Link</a></center></td>';
            $h .= '<td>'.date('d-m-Y', strtotime($pay->created_at)).'</td>';
            $h .= '<td>'.$pay->payment_name.'</td>';
            if($pay->payment_type == 1) {
                $h .= '<td>Iuran Bulanan</td>';
            }
            else if($pay->payment_type == 2) {
                $h .= '<td>Payment Rutin</td>';
            }   
            else {
                $h .= '<td>Sekali Bayar</td>';
            }
            
            $h .= '<td>'.date('d-m-Y', strtotime($pay->due_date)).'</td>';
            $h .= '<td>'.$pay->periode.'</td>';
            $h .= '<td style="text-align:right;">'.number_format($pay->payment_amount).'</td>';
            $h .= '<td>'.$bill.'</td>';
            $h .= '</tr>';
        }
        

        $h .= '</tbody>';
        $h .= '</table>'; 
        $h .= '</div>';
        return $h;
     }

     public function add_ticketing_payment(Request $request) {
        $input = $request->all();
        $rules = array(
            "payment_name" => "required",
            "payment_desc" => "required",
            "payment_type" => "required",
            "due_date" => "required",
            "payment_amount" => "required",
            "payment_dedication" =>"required"
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

        Payment::create($input);
        return response()->json([
            "success" => true,
            "message" => "New Payment Successfully Added.."
        ]);

     }

     public function update_notif_number() {
        $jumlah = session('session_number_admin') == null ? 0 : (int)session('session_number_admin');
        $update = $jumlah + 1;
        session(['session_number_admin'=> $update]);

        
        
        return response()->json($update);
    }
}
