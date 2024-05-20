<?php

namespace App\Http\Controllers\Admins;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Tunggakan;
use App\Models\User;
use App\Models\Payment;
use App\Models\PaymentDetail;
use DataTables;
use App\Models\Setting;
use Validator;
use DB;

class OutstandingController extends Controller
{
    /**
     * Display a listing of the resource.
     */

     public function ajax_list()
     {
         
         $data = User::where('level', 'user')->get();
         $setting = Setting::findorFail(1);
        
         return Datatables::of($data)
            ->addColumn('iuran', function($data){
                return '<div style="text-align:right;">'.number_format($data->iuran_bulanan).'</div>';
            })
            ->addColumn('last_paid', function($data){
                $d = DB::table('payment_details')
                    ->select('payment_details.*','payments.periode')
                    ->join('payments', 'payments.id', '=', 'payment_details.payment_id')
                    ->where('payment_details.user_id', $data->id)
                    ->where('payment_details.payment_status', 'PAID')
                    ->where('payments.payment_type', 1)
                    ->orderBy('payment_details.id', 'desc');
                
                if($d->count() > 0) {
                    $m = $d->first();
                    $pr = $m->periode;
                    $st = substr($pr, 0,2);
                    $sb = substr($pr, 3,4);
                    
                    if($st == '01') {
                        $bl = 'Jan';
                    }
                    else if($st == '02') {
                        $bl = 'Feb';
                    }
                    else if($st == '03') {
                        $bl = 'Mar';
                    }
                    else if($st == '04') {
                        $bl = 'Apr';
                    }
                    else if($st == '05') {
                        $bl = 'Mei';
                    }
                    else if($st == '06') {
                        $bl = 'Jun';
                    }
                    else if($st == '07') {
                        $bl = 'Jul';
                    }
                    else if($st == '08') {
                        $bl = 'Aug';
                    }
                    else if($st == '09') {
                        $bl = 'Sep';
                    }
                    else if($st == '10') {
                        $bl = 'Okt';
                    }else if($st == '11') {
                        $bl = 'Nov';
                    }else if($st == '12') {
                        $bl = 'Dec';
                    }
                    
                    $text = $bl.'-'.$sb;
                } else {
                    $text = 'Unavailable';
                }
                    

                return $text;
            })
             ->addColumn('denda', function($data) use ($setting){
                $tunggakan = Tunggakan::where('user_id', $data->id)->where('amount', '!=', 0)->where('payment_id', '>', 0)->sum('amount');
                $denda = $setting->percent_denda;
                $angka_denda = $tunggakan * $denda / 100;
                $nominal = $this->pembulatan((int)$angka_denda);

                return '<div style="text-align:right;">'.number_format($nominal).'</div>';
             })
             ->addColumn('total_outstanding', function($data) use($setting){
                $tunggakan = Tunggakan::where('user_id', $data->id)->where('amount', '!=', 0)->where('payment_id', '>', 0)->sum('amount');
                $adjust = Tunggakan::where('user_id', $data->id)->where('payment_id', -1)->sum('amount');
                $denda = $setting->percent_denda;
                $angka_denda = $tunggakan * $denda / 100;
                $nominal = $this->pembulatan((int)$angka_denda);

                $total = $nominal + $tunggakan + $adjust;
                return '<div style="text-align:right;">'.number_format($total).'</div>';
             })
             ->addColumn('next_bill', function($data) use ($setting){
                $tunggakan = Tunggakan::where('user_id', $data->id)->where('amount', '!=', 0)->where('payment_id', '>', 0)->sum('amount');
                $adjust = Tunggakan::where('user_id', $data->id)->where('payment_id', -1)->sum('amount');
                $denda = $setting->percent_denda;
                $angka_denda = $tunggakan * $denda / 100;
                $nominal = $this->pembulatan((int)$angka_denda);

                $total = $nominal + $tunggakan;
                $next = $total + $data->iuran_bulanan + $adjust;
                return '<div style="text-align:right;">'.number_format($next).'</div>';
             })
             ->addColumn('adjustment', function($data){
                $tunggakan = Tunggakan::where('user_id', $data->id)->where('payment_id', -1)->sum('amount');
                return '<div style="text-align:right;">'.number_format($tunggakan).'</div>';
             })
             ->addColumn('amount', function($data){
                $tunggakan = Tunggakan::where('user_id', $data->id)->where('amount', '!=', 0)->where('payment_id', '>', 0)->sum('amount');
                return '<div style="text-align:right;">'.number_format($tunggakan).'</div>';
             })
             ->addColumn('action', function($data) use ($setting){
               

             return '<center><a href="javascript:void(0);" class="bs-tooltip text-success mb-2" data-bs-toggle="tooltip" data-bs-placement="top" data-original-title="Detail" aria-label="Edit" data-bs-original-title="Detail" title="Detail" onclick="detailData('.$data->id.')"><i class="far fa-file"></i></a>';
         })->rawColumns(['action','amount','denda','total_outstanding','next_bill', 'adjustment','iuran','last_paid'])
         ->addIndexColumn()
         ->make(true);
     }



    public function index()
    {
        $view = "outstanding";
        return view('admins.outstanding.index', compact('view'));
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
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $data = Tunggakan::where('user_id', $id)
            ->where('amount','!=',0)
            ->get();
        
        $html = "";
        $html .= '<button onclick="add_adjustment('.$id.')" class="btn btn-success"><i class="fa fa-plus"></i> Add Adjustment</button>';
        $html .= '<div style="margin-top:20px;"></div>';
        $html .= '<table class="table table-bordered table-striped">';
        $html .= '<thead>';
        $html .= '<tr>';
        $html .= '<th>No</th>';
        $html .= '<th>User</th>';
        $html .= '<th>Payment</th>';
        $html .= '<th>Amount</th>';
        $html .= '<th>Adust</th>';
        $html .= '</tr>';
        $html .= '</thead>';
        $html .= '<tbody>';
        $no = 0;
        $total = 0;
        $adjust = 0;
        foreach($data as $key) {
            $user = User::findorFail($key->user_id);
            
            $no++;
            $html .= '<tr>';
            $html .= '<td>'.$no.'</td>';
            $html .= '<td>'.$user->name.'</td>';
            $html .= '<td>'.$key->description.'</td>';
            if($key->payment_id > 0) {
                $html .= '<td style="text-align:right;">'.number_format($key->amount).'</td>';
                $html .= '<td style="text-align:right;">0</td>';
                $total = $total + $key->amount;
            } else {
               
                $html .= '<td style="text-align:right;">0</td>';
                $html .= '<td style="text-align:right;">'.number_format($key->amount).'</td>';
                $adjust = $adjust + $key->amount;
            }
            
            $html .= '</tr>';
        }
        $html .= '<tr>';
        $html .= '<td></td>';
        $html .= '<td></td>';
        $html .= '<td>TOTAL</td>';
        $html .= '<td style="text-align:right;"><strong>'.number_format($total).'</strong></td>';
        $html .= '<td style="text-align:right;"><strong>'.number_format($adjust).'</strong></td>';
        $html .= '</tr>';
        $html .= '</tbody>';
        $html .= '</table>';

        return $html;
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function save_adjustment(Request $request) {
        $input = $request->all();
        
        $rules = array(
            "description" => "required",
            "type" => "required",
            "amount" => "required"
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

        $data = new Tunggakan;
        $data->user_id = $input['user_id'];
        $data->payment_id = -1;
        $data->amount = $input['type'] == 1 ? $input['amount'] : $input['amount'] * -1;
        $data->description = $input['description'];
        $data->save();
        return response()->json([
            "success" => true,
            "message" => 'successfully added'
        ]);
    }


    public function pembulatan($uang)
    {
        $ratusan = substr($uang, -3);
        if($ratusan<500) {
            $akhir = $uang - $ratusan;
        }   
        else {
            $akhir = $uang + (1000-$ratusan);
        }
       
        return $akhir;
    }
}
