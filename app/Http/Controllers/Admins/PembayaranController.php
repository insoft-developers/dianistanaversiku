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
use App\Models\Payment;
use App\Models\PaymentDetail;
use DateTime;


class PembayaranController extends Controller
{
    use DataTablesTraitStatic;
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $view = "pembayaran-list";
        $user = User::all();
        return view("admins.pembayaran.index", compact('view', 'user'));
    }


    public function ajax_list()
    {
        $data = Payment::all();
        return Datatables::of($data)
            ->addColumn('payment_dedication', function($data){
                if($data->payment_dedication < 0) {
                    return 'All User';
                } else {
                    $users = \App\Models\User::where('id', $data->payment_dedication);
                    if($users->count() > 0) {
                        $user = $users->first();
                        return $user->name;
                    } else {
                        return '';
                    }
                }
                
            })
            ->addColumn('payment_amount', function($data){
                if($data->payment_type == 1) {
                    return 'set by admin';
                } else {
                    return '<div style="text-align:right;">Rp. '.number_format($data->payment_amount).'</div>';
                }
                
            })
            ->addColumn('created_at', function($data){
                return date('d-m-Y', strtotime($data->created_at));
            })
            ->addColumn('due_date', function($data){
                return date('d-m-Y', strtotime($data->due_date));
            })
            ->addColumn('payment_type', function($data){
                if($data->payment_type == 1) {
                    return 'Iuran Bulanan Komplek';
                } else if($data->payment_type == 2) {
                    return 'Pembayaran Rutin';
                } else if($data->payment_type == 3) {
                    return 'Sekali Bayar';
                }
            })
            ->addColumn('payment_name', function($data){
                return '<div style="white-space:normal;";><strong>'.$data->payment_name.'</strong><br>'.$data->payment_desc.'</div>';
            })
            ->addColumn('action', function($data){
               
                return '<a href="javascript:void(0);" class="bs-tooltip text-success mb-2" data-bs-toggle="tooltip" data-bs-placement="top" data-original-title="Detail" aria-label="Edit" data-bs-original-title="Detail" title="Detail" onclick="detailData('.$data->id.')"><i class="far fa-file"></i></a>&nbsp;&nbsp;<a href="javascript:void(0);" class="bs-tooltip text-success mb-2" data-bs-toggle="tooltip" data-bs-placement="top" data-original-title="Payment" aria-label="Payment" data-bs-original-title="Payment" title="Payment" onclick="paymentData('.$data->id.')"><i class="fa fa-dollar-sign"></i></a>&nbsp;&nbsp;<a href="javascript:void(0);" class="bs-tooltip text-warning mb-2" data-bs-toggle="tooltip" data-bs-placement="top" data-original-title="Edit" aria-label="Edit" data-bs-original-title="Edit" title="Edit" onclick="editData('.$data->id.')"><i class="far fa-edit"></i></a>&nbsp;&nbsp;<a href="javascript:void(0);" class="bs-tooltip text-primary mb-2" data-bs-toggle="tooltip" data-bs-placement="top" data-original-title="Copy Link" aria-label="Copy Link" data-bs-original-title="Copy Link" title="Copy Link" onclick="copyData('.$data->id.')"><i class="fa fa-copy"></i></i></a>&nbsp;&nbsp;<a href="javascript:void(0);" class="bs-tooltip text-danger mb-2" data-bs-toggle="tooltip" data-bs-placement="top" data-original-title="Hapus" aria-label="Hapus" data-bs-original-title="Hapus" title="Hapus" onclick="deleteData('.$data->id.')"><i class="far fa-times-circle"></i></i></a>';
        })->rawColumns(['action','created_at','payment_name','due_date','payment_type','payment_amount','payment_dedication'])
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
            "payment_name" => "required",
            "payment_desc" => "required",
            "payment_type" => "required",
            "due_date" => "required",
            "payment_dedication" => "required",
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
            
            if(empty($input['payment_amount'])) {
                $input['payment_amount'] = 0;
            }
            Payment::create($input);
            return response()->json([
                "success" => true,
                "message" => "New Payment Successfully Added.."
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
        $payment = Payment::findorFail($id);
        $data = PaymentDetail::where('payment_id', $id)->orderby('id','desc')->get();
        $html = "";
        $html.= '<button onclick="paymentData('.$id.')" class="btn btn-success"><i class="fa fa-plus"></i> Add Payment</button>';
        $html .= '<div style="margin-top:20px;"></div>';
        $html .= '<div class="table-responsive">';
        $html .= '<table class="table table-bordered table-striped">';
        $html .= '<thead>';
        $html .= '<tr>';
        $html .= '<th>#</th>';
        $html .= '<th>Action</th>';
        $html .= '<th>Invoice</th>';
        $html .= '<th>Payment Status</th>';
        $html .= '<th>Payment Name</th>';
        $html .= '<th>User</th>';
        $html .= '<th>Amount</th>';
        
        $html .= '<th>Payment Method</th>';
        $html .= '<th>Payment Channel</th>';
        $html .= '<th>Created At</th>';
        $html .= '<th>Paid At</th>';
        $html .= '</tr>';
        $html .= '</thead>';
        $html .= '<tbody>';
        $no = 0;
        foreach($data as $key) {

            $users = \App\Models\User::where('id', $key->user_id);
            if($users->count() > 0) {
                $user = $users->first();
                $payee =  $user->name;
            } else {
                $payee = '';
            }
            $no++;
            $html .= '<tr>';
            
            $html .= '<td>'.$no.'</td>';
            if($key->payment_status == 'PAID' ) {
                $html .= '<td><button onclick="print_kwitansi('.$key->id.')" class="btn btn-success btn-sm"><i class="fa fa-print"></i> Print</button></td>';
            } else {    
                $html .= '<td><button onclick="delete_payment('.$key->id.')" class="btn btn-danger btn-sm"><i class="fa fa-trash"></i> Delete</button></td>';
            }   
            
            $html .= '<td>'.$key->invoice.'</td>';
            $html .= '<td>'.$key->payment_status.'</td>';
            $html .= '<td>'.$payment->payment_name.'</td>';
            $html .= '<td>'.$payee.'</td>';
            $html .= '<td>'.number_format($key->amount).'</td>';
            
            $html .= '<td>'.$key->payment_method.'</td>';
            $html .= '<td>'.$key->payment_channel.'</td>';
            $html .= '<td>'.date('d-m-Y', strtotime($key->created_at)).'</td>';
            if($key->paid_at == null) {
                $html .= '<td></td>';
            } else {
                $html .= '<td>'.date('d-m-Y', strtotime($key->paid_at)).'</td>';
            }
            
            $html .= '</tr>';
        }
        


        $html .= '</tbody>';
        $html .= '</table>';
        $html .= '</div>';

        return $html;
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $query = Payment::findorFail($id);
        return $query;
    }

    
    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $input = $request->all();

        $rules = array(
            "payment_name" => "required",
            "payment_desc" => "required",
            "payment_type" => "required",
            "due_date" => "required",
            "payment_dedication" => "required",
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
            
            if(empty($input['payment_amount'])) {
                $input['payment_amount'] = 0;
            }
            
            $data = Payment::findorFail($id);
            $data->update($input);

            return response()->json([
                "success" => true,
                "message" => "Payment Successfully Updated.."
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
        $data = Payment::findorFail($id);
        $query = Payment::destroy($id);
        if($query) {
            PaymentDetail::where('payment_id', $id)->delete();
        }
        return $query;
    }


    public function print_detail($id) {
        $user = User::findorFail($id);
        return view('admins.user.print', compact('user'));
    }


    public function kwitansi($id) {
        $view = "backdata-kwitansi";
        $data = PaymentDetail::findorFail($id);
        $setting = \App\Models\Setting::findorFail(1);
        $payment = Payment::findorFail($data->payment_id);    
        return view('admins.pembayaran.kwitansi', compact('view','data','setting','payment'));
    }

    public function delete_payment(Request $request) {
        $input = $request->all();
        $detail = PaymentDetail::where('id', $input['id'])->first();
        $query = PaymentDetail::destroy($input['id']);
        return $detail->payment_id;
    }

    public function process_payment(Request $request) {
        $input = $request->all();
        $payment = Payment::findorFail($input['payment_id_admin']);

        if($request->payment_dedication_admin == null) {
            $input['payment_dedication_admin'] = $payment->payment_dedication;
        }
        
        $cek = PaymentDetail::where('payment_id', $input['payment_id_admin'])
            ->where('user_id', $input['payment_dedication_admin'])
            ->where('payment_status', 'PAID');
        if($cek->count() > 0) {
            return response()->json([
                "success" => false,
                "message" => "this bill has already paid"
            ]);
        }

        if($request->payment_dedication_admin < 0) {
            return response()->json([
                "success" => false,
                "message" => "User Not Valid"
            ]);
        }
        
        $random = random_int(1000, 9999);
        $invoice = "PM-".date('dmyHis').$random;

        $detail = new PaymentDetail;
        $detail->invoice = $invoice;
        $detail->payment_id = $input['payment_id_admin'];
        $detail->user_id = $input['payment_dedication_admin'];
        $detail->amount = $input['payment_amount_admin'];
        $detail->payment_status = "PAID";
        $detail->created_at = date('Y-m-d H:i:s');
        $detail->updated_at = date('Y-m-d H:i:s');
        $detail->paid_at = date('Y-m-d H:i:s');
        $detail->payment_method = "ADMIN PAYMENT";
        $detail->payment_channel =  adminAuth()->name;
        $detail->save();
        return response()->json([
            "success" => true,
            "message" => "payment successfull"
        ]);
       
    }

    public function payment_admin($id) {
        $payment = Payment::findorFail($id);
        return $payment;
    }

    public function get_iuran_bulanan($id) {
        $user = User::findorFail($id);
        $tagihan =  $user->iuran_bulanan;
        $setting = \App\Models\Setting::findorFail(1);
        $tgl_tempo = $setting->tanggal_jatuh_tempo_iuran_bulanan;
        
        $bulan_sekarang = date('m');
        $tahun_sekarang = date('Y');
        $due = $tahun_sekarang.'-'.$bulan_sekarang.'-'.$tgl_tempo;
        $sekarang = date('Y-m-d');

        if($sekarang > $due) {
            $percent_denda = $setting->percent_denda;
            $denda = $percent_denda * $tagihan /100;
            $total = (int)$denda + $tagihan;
            $iuran_bulanan = $total;
        } else {
            $iuran_bulanan = $tagihan;
        }

        return $iuran_bulanan;

    }


    public function check_payment_routine() {
        $setting = \App\Models\Setting::findorFail(1);
        $tgl_iuran = $setting->tgl_create_iuran_bulanan;
        $tgl_tempo = $setting->tanggal_jatuh_tempo_iuran_bulanan;
        $tgl_sekarang = date('d');
        $bln_sekarang = date('m');
        $thn_sekarang = date('Y');
        $periode = $bln_sekarang.'-'.$thn_sekarang;
        $due = $thn_sekarang.'-'.$bln_sekarang.'-'.$tgl_tempo;


        if($tgl_sekarang >= $tgl_iuran) {
            $cek = Payment::where('payment_type', 1)
                ->where('periode', $periode)
                ->where('payment_dedication', -1);
            if($cek->count() > 0) {

            } else {
                $i = new Payment;
                $i->payment_name = "Iuran Bulanan Periode ".$periode;
                $i->payment_desc = "Iuran Bulanan Periode ".$periode;
                $i->payment_type = 1;
                $i->due_date = $due;
                $i->periode = $periode;
                $i->payment_amount = 0;
                $i->payment_dedication = -1;
                $i->created_at = date('Y-m-d H:i:s');
                $i->updated_at = date('Y-m-d H:i:s');
                $i->save();

            }
        } else {
            return 2;
        }
        
    }

    public function notifikasi_bulanan() {
       $payment = Payment::where('payment_type', 1)->get();
       $user = User::where('no_hp', '!=', '')->where('level','user')
                ->where('id', 3)
                ->get();
       
       $sekarang = date('Y-m-d');

       $belum_bayar = [];
       foreach($payment as $p) {
           foreach($user as $u) {   
               $cek = PaymentDetail::where('user_id', $u->id)->where('payment_id', $p->id)->where('payment_status','PAID');
               if($cek->count() > 0) {} 
               else {
                    $row['id'] = $u->id;
                    $row['name'] = $u->name;
                    $row['wa'] = $u->no_hp;
                    $row['payment'] = $p->id;
                    $row['periode'] = $p->periode;
                    $row['reg_id'] = $u->token;
                    $row['due'] = $p->due_date;
                    array_push($belum_bayar, $row);
               }
           } 
       }
       
       

       foreach($belum_bayar as $b) {
            $cek_sudah_kirim = \App\Models\NotifTagihan::where('date', $sekarang)->where('payment_id', $b['payment']);
            if($cek_sudah_kirim->count() > 0 ) {}
            else {
                // $tgl1 = new DateTime($b['due']);
                // $tgl2 = new DateTime($sekarang);
                // $jarak = $tgl2->diff($tgl1);
                // $selisih = $jarak->d;
                $sekarang = date('d');
                if($sekarang == '01' || $sekarang == '10' || $sekarang == '15' || $sekarang == '18' || $sekarang == '19' || $sekarang == '22') {
                    $this->send_wa($b['wa'], $b['name'], $b['periode']);
                    $isi = new \App\Models\NotifTagihan;
                    $isi->date = date('Y-m-d');
                    $isi->payment_id = $b['payment'];
                    $isi->save();

                    $this->send_notif_to($b);

                } 
                // else {
                //     if($selisih == 8 || $selisih == 16) {
                //         $this->send_wa($b['wa'], $b['name'], $b['periode']);
                //         $isi = new \App\Models\NotifTagihan;
                //         $isi->date = $sekarang;
                //         $isi->payment_id = $b['payment'];
                //         $isi->save();
                //         $this->send_notif_to($b);
                //     }
                // }
            }
       }
    }


    public function send_notif_to($b) {
        if(!empty($b['reg_id'])) {
            $title = "Tagihan Iuran Bulanan Periode ".$b['periode'];
            $message = '[MyDianIstana] - Bpk/Ibu '.$b['name'].' yang terhormat, Tagihan iuran bulanan anda untuk periode '.$b['periode'].' telah jatuh tempo. Mohon segera dilakukan pembayaran. Terima Kasih';
            $regid = $b['reg_id'];
            $n = new \App\Models\Notif;
            $n->title = $title;
            $n->slug = str_replace(" ", "", $title);
            $n->message = $message;
            $n->admin_id = -1;
            $n->user_id = $b['id'];
            $n->status = 0;
            $n->created_at = date('Y-m-d H:i:s');
            $n->updated_at = date('Y-m-d H:i:s');
            $n->save();
            $this->notify($title, $message, $regid);
        }
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

    public function send_wa($phone, $name, $periode ) {
        $setting = \App\Models\Setting::findorFail(1);
        $key = $setting->api_wa;
        
        $url='http://116.203.191.58/api/send_message';
        $data = array(
          "phone_no"  => $phone,
          "key"       => $key,
          "message"   => '[MyDianIstana] - Bpk/Ibu '.$name.' yang terhormat, Tagihan iuran bulanan anda untuk periode '.$periode.' telah jatuh tempo. Mohon segera dilakukan pembayaran. Terima Kasih',
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


    public function check_due_bills() {
        $sekarang = date('Y-m-d');
        $payments = Payment::where('payment_type', 1)
                ->where('due_date', '<', $sekarang)
                ->get();
        $users = User::where('level', 'user')->where('id',3)->get();
        $belum_bayar = [];
        foreach($payments as $p) {
            foreach($users as $u) {   
                $cek = PaymentDetail::where('user_id', $u->id)->where('payment_id', $p->id)->where('payment_status','PAID');
                if($cek->count() > 0) {} 
                else {
                     $cek_tunggakan = \App\Models\Tunggakan::where('payment_id', $p->id)
                            ->where('user_id', $u->id);
                     if($cek_tunggakan->count() > 0) {

                     } else {
                        $tunggakan = new \App\Models\Tunggakan;
                        $tunggakan->user_id = $u->id;
                        $tunggakan->payment_id = $p->id;
                        $tunggakan->amount = $u->iuran_bulanan;
                        $tunggakan->description = $p->payment_name;
                        $tunggakan->save();
                     }
                     
                }
            } 
        }

        return response()->json($belum_bayar);
    }
   
}
