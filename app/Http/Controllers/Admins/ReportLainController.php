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
use App\Models\Payment;
use App\Models\PaymentDetail;
use Illuminate\Validation\Rule;
use DB;
use PDF;

use App\Exports\LaporanLainExport;
use Maatwebsite\Excel\Facades\Excel;


class ReportLainController extends Controller
{
    use DataTablesTraitStatic;
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $view = "report-lain";
        $method = PaymentDetail::where('payment_method', '!=', null)->groupBy('payment_method')->get();
        return view("admins.report.lain.index", compact('view','method'));
    }


    public function ajax_list(Request $request)
    {
        $input = $request->all();
        $awal = $input['awal'];
        $akhir = $input['akhir'];
        $ending = strtotime("+1 day", strtotime($akhir));
        $sampai = date('Y-m-d', $ending);
        if(empty($awal) && empty($akhir)) {
            $bln = date('m');
            $thn = date('Y');
            $start = $thn.'-'.$bln.'-01';
            $end = $thn.'-'.$bln.'-31';
            $query = DB::table('payment_details')
                                ->select('payment_details.*', 'payments.payment_name', 'payments.due_date','payments.periode')
                                ->join('payments', 'payments.id', '=', 'payment_details.payment_id')
                                ->join('users', 'users.id', '=', 'payment_details.user_id')
                                ->where('payments.payment_type','!=', 1)
                                ->where('payment_details.payment_status', 'PAID')
                                ->where('payment_details.paid_at', '>=', $start)
                                ->where('payment_details.paid_at', '<=', $end);
                                
        } else {
            $query = DB::table('payment_details')
                                ->select('payment_details.*', 'payments.payment_name', 'payments.due_date','payments.periode')
                                ->join('payments', 'payments.id', '=', 'payment_details.payment_id')
                                ->join('users', 'users.id', '=', 'payment_details.user_id')
                                ->where('payments.payment_type','!=', 1)
                                ->where('payment_details.payment_status', 'PAID')
                                ->where('payment_details.paid_at', '>=', $awal)
                                ->where('payment_details.paid_at', '<=', $sampai);
                                
        }
        
        if(! empty($input['payment'])) {
            $query->where('payment_details.payment_method', $input['payment']);
        }
        if(! empty($input['penyelia'])) {
            $query->where('users.penyelia', $input['penyelia']);
        }
        $data = $query->get();
        


        return Datatables::of($data)
            ->addColumn('created_at', function($data){
                return '<center>'.date('d-m-Y', strtotime($data->created_at)).'</center>';
            })
            ->addColumn('due_date', function($data){
                return '<center>'.date('d-m-Y', strtotime($data->due_date)).'</center>';
            })
            ->addColumn('paid_at', function($data){
                return '<center>'.date('d-m-Y', strtotime($data->paid_at)).'</center>';
            })
            ->addColumn('user_id', function($data){
                $users = \App\Models\User::where('id', $data->user_id);
                if($users->count() > 0) {
                    $user = $users->first();
                    return $user->name.'<br>[ '.$user->blok.' - '.$user->nomor_rumah.' ]';
                } else {
                    return 'no-data';
                }
            })
            ->addColumn('amount', function($data){
                return '<div style="text-align:right;">'.number_format($data->amount).'</div>';
            })
            ->addColumn('action', function($data){
                return '<a href="javascript:void(0);" class="bs-tooltip text-success mb-2" data-bs-toggle="tooltip" data-bs-placement="top" data-original-title="Detail" aria-label="Edit" data-bs-original-title="Detail" title="Detail" onclick="printData('.$data->id.')"><i class="far fa-file"></i></a>';
        })->rawColumns(['action','created_at','user_id','due_date','paid_at','amount'])
        ->addIndexColumn()
        ->make(true);
    }

    
    public function print_lain_report($awal, $akhir, $payment, $penyelia) {
        $ending = strtotime("+1 day", strtotime($akhir));
        $sampai = date('Y-m-d', $ending);
        if(empty($awal) && empty($akhir)) {
            $bln = date('m');
            $thn = date('Y');
            $start = $thn.'-'.$bln.'-01';
            $end = $thn.'-'.$bln.'-31';
            $query = DB::table('payment_details')
                                ->select('payment_details.*', 'payments.payment_name', 'payments.due_date','payments.periode')
                                ->join('payments', 'payments.id', '=', 'payment_details.payment_id', 'left')
                                ->join('users', 'users.id', '=', 'payment_details.user_id', 'left')
                                ->where('payments.payment_type', '!=', 1)
                                ->where('payment_details.payment_status', 'PAID')
                                ->where('payment_details.paid_at', '>=', $start)
                                ->where('payment_details.paid_at', '<=', $end)
                                ->orderBy('payment_details.paid_at', 'asc');
        } else {
            $query = DB::table('payment_details')
                                ->select('payment_details.*', 'payments.payment_name', 'payments.due_date','payments.periode')
                                ->join('payments', 'payments.id', '=', 'payment_details.payment_id', 'left')
                                ->join('users', 'users.id', '=', 'payment_details.user_id', 'left')
                                ->where('payments.payment_type', '!=', 1)
                                ->where('payment_details.payment_status', 'PAID')
                                ->where('payment_details.paid_at', '>=', $awal)
                                ->where('payment_details.paid_at', '<=', $sampai)
                                ->orderBy('payment_details.paid_at', 'asc');
        }

        if($payment != 0) {
            $query->where('payment_details.payment_method', $payment);
        }

        if($penyelia != 0) {
            $query->where('users.penyelia', $penyelia);
        }


        $data = $query->get();

        $setting = \App\Models\Setting::findorFail(1);
        return view('admins.report.lain.print', compact('data','awal','akhir','setting','awal','akhir','payment','penyelia'));
    }


    public function print_lain_report_pdf($awal, $akhir, $payment, $penyelia) {
        $ending = strtotime("+1 day", strtotime($akhir));
        $sampai = date('Y-m-d', $ending);
        if(empty($awal) && empty($akhir)) {
            $bln = date('m');
            $thn = date('Y');
            $start = $thn.'-'.$bln.'-01';
            $end = $thn.'-'.$bln.'-31';
            $query = DB::table('payment_details')
                                ->select('payment_details.*', 'payments.payment_name', 'payments.due_date','payments.periode')
                                ->join('payments', 'payments.id', '=', 'payment_details.payment_id', 'left')
                                ->join('users', 'users.id', '=', 'payment_details.user_id', 'left')
                                ->where('payments.payment_type','!=', 1)
                                ->where('payment_details.payment_status', 'PAID')
                                ->where('payment_details.paid_at', '>=', $start)
                                ->where('payment_details.paid_at', '<=', $end)
                                ->orderBy('payment_details.paid_at', 'asc');
        } else {
            $query = DB::table('payment_details')
                                ->select('payment_details.*', 'payments.payment_name', 'payments.due_date','payments.periode')
                                ->join('payments', 'payments.id', '=', 'payment_details.payment_id', 'left')
                                ->join('users', 'users.id', '=', 'payment_details.user_id', 'left')
                                ->where('payments.payment_type','!=', 1)
                                ->where('payment_details.payment_status', 'PAID')
                                ->where('payment_details.paid_at', '>=', $awal)
                                ->where('payment_details.paid_at', '<=', $sampai)
                                ->orderBy('payment_details.paid_at', 'asc');
                                
        }

        if(! empty($payment)) {
            $query->where('payment_details.payment_method', $payment);
        }

        if(! empty($penyelia)) {
            $query->where('users.penyelia', $penyelia);
        }
        
        $data = $query->get();

        $setting = \App\Models\Setting::findorFail(1);
        $pdf= PDF::loadView('admins.report.lain.print_pdf', compact('data','awal','akhir','setting'));
        $pdf->setPaper('a4', 'potrait');
        return $pdf->stream();
        // return view('admins.report.iuran.print_pdf', compact('data','awal','akhir','setting'));
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


    public function print_lain_report_excel($awal, $akhir) 
    {
         return Excel::download(new LaporanLainExport($awal, $akhir), 'laporan_kas_lain.xlsx');
    }

   
    
}
