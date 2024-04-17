<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use DB;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class LaporanLainExport implements FromView
{
    /**
    * @return \Illuminate\Support\Collection
    */
    protected $awal;
    protected $akhir;

    function __construct($awal, $akhir) {
            $this->awal = $awal;
            $this->akhir = $akhir;
    }

    public function view(): View
    {
       
        $ending = strtotime("+1 day", strtotime($this->akhir));
        $sampai = date('Y-m-d', $ending);
        if(empty($this->awal) && empty($this->akhir)) {
            $bln = date('m');
            $thn = date('Y');
            $start = $thn.'-'.$bln.'-01';
            $end = $thn.'-'.$bln.'-31';
            $data = DB::table('payment_details')
                                ->select('payment_details.*', 'payments.payment_name', 'payments.due_date','payments.periode')
                                ->join('payments', 'payments.id', '=', 'payment_details.payment_id')
                                ->where('payments.payment_type','!=', 1)
                                ->where('payment_details.payment_status', 'PAID')
                                ->where('payment_details.paid_at', '>=', $start)
                                ->where('payment_details.paid_at', '<=', $end)
                                ->orderBy('payment_details.paid_at', 'asc')
                                ->get();
        } else {
            $data = DB::table('payment_details')
                                ->select('payment_details.*', 'payments.payment_name', 'payments.due_date','payments.periode')
                                ->join('payments', 'payments.id', '=', 'payment_details.payment_id')
                                ->where('payments.payment_type','!=', 1)
                                ->where('payment_details.payment_status', 'PAID')
                                ->where('payment_details.paid_at', '>=', $this->awal)
                                ->where('payment_details.paid_at', '<=', $sampai)
                                ->orderBy('payment_details.paid_at', 'asc')
                                ->get();
        }
        
        return view('admins.report.lain.print_excel', [
            'data' => $data, 'awal'=> $this->awal, 'akhir' => $this->akhir
        ]);
    }
}
