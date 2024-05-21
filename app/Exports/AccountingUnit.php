<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use DB;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class AccountingUnit implements FromView
{
    /**
    * @return \Illuminate\Support\Collection
    */
    protected $awal;
    protected $akhir;
    protected $payment;
    protected $unit;

    function __construct($awal, $akhir, $payment, $unit) {
            $this->awal = $awal;
            $this->akhir = $akhir;
            $this->payment = $payment;
            $this->unit = $unit;
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
            $query = DB::table('transactions')
                                ->select('transactions.*', 'unit_bisnis.name_unit')
                                ->join('unit_bisnis', 'unit_bisnis.id', '=', 'transactions.business_unit_id', 'left')
                                ->where('transactions.payment_status', 'PAID')
                                ->where('transactions.paid_at', '>=', $start)
                                ->where('transactions.paid_at', '<=', $end)
                                ->orderBy('transactions.paid_at', 'asc');
        } else {
            $query = DB::table('transactions')
                                ->select('transactions.*', 'unit_bisnis.name_unit')
                                ->join('unit_bisnis', 'unit_bisnis.id', '=', 'transactions.business_unit_id', 'left')
                                ->where('transactions.payment_status', 'PAID')
                                ->where('transactions.paid_at', '>=', $this->awal)
                                ->where('transactions.paid_at', '<=', $sampai)
                                ->orderBy('transactions.paid_at', 'asc');
        }

        if($this->payment != 0) {
            $query->where('transactions.payment_method', $this->payment);
        }

        if($this->unit != 0) {
            $query->where('transactions.business_unit_id', $this->unit);
        }

        $data = $query->get();
        return view('admins.report.bisnis.accounting_unit', [
            'data' => $data, 'awal'=> $this->awal, 'akhir' => $this->akhir, 'payment' => $this->payment, 'unit'=> $this->unit
        ]);
    }
}
