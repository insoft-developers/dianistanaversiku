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
             ->addColumn('denda', function($data) use ($setting){
                $tunggakan = Tunggakan::where('user_id', $data->id)->where('amount', '>', 0)->sum('amount');
                $denda = $setting->percent_denda;
                $nominal = $tunggakan * $denda / 100;

                return '<div style="text-align:right;">'.number_format($nominal).'</div>';
             })
             ->addColumn('total_outstanding', function($data) use($setting){
                $tunggakan = Tunggakan::where('user_id', $data->id)->where('amount', '>', 0)->sum('amount');
                $denda = $setting->percent_denda;
                $nominal = $tunggakan * $denda / 100;
                $total = $nominal + $tunggakan;
                return '<div style="text-align:right;">'.number_format($total).'</div>';
             })
             ->addColumn('next_bill', function($data) use ($setting){
                $tunggakan = Tunggakan::where('user_id', $data->id)->where('amount', '>', 0)->sum('amount');
                $denda = $setting->percent_denda;
                $nominal = $tunggakan * $denda / 100;
                $total = $nominal + $tunggakan;
                $next = $total + $data->iuran_bulanan;
                return '<div style="text-align:right;">'.number_format($next).'</div>';
             })
             ->addColumn('amount', function($data){
                $tunggakan = Tunggakan::where('user_id', $data->id)->where('amount', '>', 0)->sum('amount');
                return '<div style="text-align:right;">'.number_format($tunggakan).'</div>';
             })
             ->addColumn('action', function($data){
             return '<a href="javascript:void(0);" class="bs-tooltip text-success mb-2" data-bs-toggle="tooltip" data-bs-placement="top" data-original-title="Detail" aria-label="Edit" data-bs-original-title="Detail" title="Detail" onclick="detailData('.$data->id.')"><i class="far fa-file"></i></a>';
         })->rawColumns(['action','amount','denda','total_outstanding','next_bill'])
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
            ->where('amount','>',0)
            ->get();
        
        $html = "";
        $html .= '<table class="table table-bordered table-striped">';
        $html .= '<thead>';
        $html .= '<tr>';
        $html .= '<th>No</th>';
        $html .= '<th>User</th>';
        $html .= '<th>Payment</th>';
        $html .= '<th>Amount</th>';
        $html .= '</tr>';
        $html .= '</thead>';
        $html .= '<tbody>';
        $no = 0;
        $total = 0;
        foreach($data as $key) {
            $user = User::findorFail($key->user_id);
            $total = $total + $key->amount;
            $no++;
            $html .= '<tr>';
            $html .= '<td>'.$no.'</td>';
            $html .= '<td>'.$user->name.'</td>';
            $html .= '<td>'.$key->description.'</td>';
            $html .= '<td style="text-align:right;">'.number_format($key->amount).'</td>';
            $html .= '</tr>';
        }
        $html .= '<tr>';
        $html .= '<td></td>';
        $html .= '<td></td>';
        $html .= '<td>TOTAL</td>';
        $html .= '<td style="text-align:right;"><strong>'.number_format($total).'</strong></td>';
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
}
