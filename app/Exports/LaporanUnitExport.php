<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use DB;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class LaporanUnitExport implements FromView
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
            $data = DB::table('transactions')
                                ->select('transactions.*', 'unit_bisnis.name_unit')
                                ->join('unit_bisnis', 'unit_bisnis.id', '=', 'transactions.business_unit_id', 'left')
                                ->where('transactions.payment_status', 'PAID')
                                ->where('transactions.paid_at', '>=', $start)
                                ->where('transactions.paid_at', '<=', $end)
                                ->orderBy('transactions.paid_at', 'asc')
                                ->get();
        } else {
            $data = DB::table('transactions')
                                ->select('transactions.*', 'unit_bisnis.name_unit')
                                ->join('unit_bisnis', 'unit_bisnis.id', '=', 'transactions.business_unit_id', 'left')
                                ->where('transactions.payment_status', 'PAID')
                                ->where('transactions.paid_at', '>=', $this->awal)
                                ->where('transactions.paid_at', '<=', $sampai)
                                ->orderBy('transactions.paid_at', 'asc')
                                ->get();
        }
        
        return view('admins.report.bisnis.print_excel', [
            'data' => $data, 'awal'=> $this->awal, 'akhir' => $this->akhir
        ]);
    }
}
