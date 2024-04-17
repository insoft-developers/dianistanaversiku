<?php

namespace App\Http\Controllers\Admins;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;
use DB;
use App\Models\Transaction;
use App\Models\Payment;
use App\Models\PaymentDetail;
use App\Models\User;

class DashboardController extends Controller
{
    public function index(): View 
    {
        $view = "dashboard";

        // HARI INI
        $awal = date('Y-m-d');
        $ending = strtotime("+1 day", strtotime($awal));
        $akhir = date('Y-m-d', $ending);




        $booking_today_query = DB::table('transactions')
            ->where('payment_status', 'PAID')
            ->where('paid_at', '>=', $awal)
            ->where('paid_at', '<=', $akhir)
            ->get();

        $booking_today = 0;
        foreach($booking_today_query as $key) {
            $booking_today = $booking_today + $key->total_price;
        }



        $iuran_today_query = DB::table('payment_details')
                ->select('payment_details.*', 'payments.payment_name')
                ->join('payments', 'payments.id', '=', 'payment_details.payment_id')
                ->where('payments.payment_type', 1)
                ->where('payment_details.payment_status', 'PAID')
                ->where('payment_details.paid_at', '>=', $awal)
                ->where('payment_details.paid_at', '<=', $akhir)
                ->get();

        $iuran_today = 0;

        foreach($iuran_today_query as $key) {
            $iuran_today = $iuran_today + $key->amount;
        }


        $lain_today_query = DB::table('payment_details')
                ->select('payment_details.*', 'payments.payment_name')
                ->join('payments', 'payments.id', '=', 'payment_details.payment_id')
                ->where('payments.payment_type', '!=', 1)
                ->where('payment_details.payment_status', 'PAID')
                ->where('payment_details.paid_at', '>=', $awal)
                ->where('payment_details.paid_at', '<=', $akhir)
                ->get();

        $lain_today = 0;

        foreach($lain_today_query as $key) {
            $lain_today = $lain_today + $key->amount;
        }


        // BULAN INI
        $awal_bulan = date('Y-m-01');
       
        $akhir_bulan = date('Y-m-31');

        $iuran_bulan_query = DB::table('payment_details')
                ->select('payment_details.*', 'payments.payment_name')
                ->join('payments', 'payments.id', '=', 'payment_details.payment_id')
                ->where('payments.payment_type', 1)
                ->where('payment_details.payment_status', 'PAID')
                ->where('payment_details.paid_at', '>=', $awal_bulan)
                ->where('payment_details.paid_at', '<=', $akhir_bulan)
                ->get();

        $iuran_bulan = 0;
        foreach($iuran_bulan_query as $key) {
            $iuran_bulan = $iuran_bulan + $key->amount;
        }


        $transaction = Transaction::limit(6)->orderBy('created_at', 'desc')->get();


        $details = PaymentDetail::limit(6)->orderBy('created_at', 'desc')->get();


        return view("admins.dashboard.index", compact('view', 'iuran_today','iuran_bulan','booking_today','lain_today','transaction','details'));
    }
}
