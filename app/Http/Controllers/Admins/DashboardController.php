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
use App\Models\Tunggakan;
use DataTables;
use App\Models\Setting;
use Validator;

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


        $transaction = Transaction::where('payment_status', 'PAID')->limit(6)->orderBy('created_at', 'desc')->get();


        $details = PaymentDetail::where('payment_status', 'PAID')->limit(6)->orderBy('created_at', 'desc')->get();


        return view("admins.dashboard.index", compact('view', 'iuran_today','iuran_bulan','booking_today','lain_today','transaction','details'));
    }

    public function ajax_list()
     {
        $current = date('m-Y');
        $data = [];

         $query = DB::table('users')
            ->where('level', 'user')
            ->get();
         
         foreach($query as $q) {
            $row['id'] = $q->id;
            $row['name'] = $q->name;
            $row['email'] = $q->email;
            $row['no_hp'] = $q->no_hp;
            $row['level'] = $q->level;
            $row['iuran_bulanan'] = $q->iuran_bulanan;
            $row['blok'] = $q->blok;
            $row['penyelia'] = $q->penyelia;
            $row['nomor_rumah'] = $q->nomor_rumah;

            $d = DB::table('payment_details')
                    ->select('payment_details.*','payments.periode')
                    ->join('payments', 'payments.id', '=', 'payment_details.payment_id')
                    ->where('payment_details.user_id', $q->id)
                    ->where('payment_details.payment_status', 'PAID')
                    ->where('payments.payment_type', 1)
                    ->orderBy('payment_details.id', 'desc');
                
            if($d->count() > 0) {
                $m = $d->first();
                $text = $m->periode;
                if($text == $current) {

                } else {
                    array_push($data, $row);    
                }
            } else {
                array_push($data, $row);
            }  
         }
         
         
         $setting = Setting::findorFail(1);
        
         return Datatables::of($data)
            ->addColumn('iuran', function($data){
                return '<div style="text-align:right;">'.number_format($data['iuran_bulanan']).'</div>';
            })
            ->addColumn('last_paid', function($data){
                $d = DB::table('payment_details')
                    ->select('payment_details.*','payments.periode')
                    ->join('payments', 'payments.id', '=', 'payment_details.payment_id')
                    ->where('payment_details.user_id', $data['id'])
                    ->where('payment_details.payment_status', 'PAID')
                    ->where('payments.payment_type', 1)
                    ->orderBy('payment_details.id', 'desc');
                
                if($d->count() > 0) {
                    $m = $d->first();
                    $text = $m->periode;
                } else {
                    $text = 'Unavailable';
                }
                    

                return '<div style="text-align:right">'.$text.'</div>';
            })
             ->addColumn('denda', function($data) use ($setting){
                $tunggakan = Tunggakan::where('user_id', $data['id'])->where('amount', '!=', 0)->where('payment_id', '>', 0)->sum('amount');
                $denda = $setting->percent_denda;
                $angka_denda = $tunggakan * $denda / 100;
                $nominal = $this->pembulatan((int)$angka_denda);

                return '<div style="text-align:right;">'.number_format($nominal).'</div>';
             })
             ->addColumn('total_outstanding', function($data) use($setting){
                $tunggakan = Tunggakan::where('user_id', $data['id'])->where('amount', '!=', 0)->where('payment_id', '>', 0)->sum('amount');
                $adjust = Tunggakan::where('user_id', $data['id'])->where('payment_id', -1)->sum('amount');
                $denda = $setting->percent_denda;
                $angka_denda = $tunggakan * $denda / 100;
                $nominal = $this->pembulatan((int)$angka_denda);

                $total = $nominal + $tunggakan;
                $next = $total + $data['iuran_bulanan'] + $adjust;
                return '<div style="text-align:right;">'.number_format($next).'</div>';
             })
             
             ->addColumn('adjustment', function($data){
                $tunggakan = Tunggakan::where('user_id', $data['id'])->where('payment_id', -1)->sum('amount');
                return '<div style="text-align:right;">'.number_format($tunggakan).'</div>';
             })
             ->addColumn('amount', function($data){
                $tunggakan = Tunggakan::where('user_id', $data['id'])->where('amount', '!=', 0)->where('payment_id', '>', 0)->sum('amount');
                return '<div style="text-align:right;">'.number_format($tunggakan).'</div>';
             })
             ->addColumn('action', function($data) use ($setting){
                $phone = str_replace("+", "", $data['no_hp']);

                $tunggakan = Tunggakan::where('user_id', $data['id'])->where('amount', '!=', 0)->where('payment_id', '>', 0)->sum('amount');
                $adjust = Tunggakan::where('user_id', $data['id'])->where('payment_id', -1)->sum('amount');
                $denda = $setting->percent_denda;
                $angka_denda = $tunggakan * $denda / 100;
                $nominal = $this->pembulatan((int)$angka_denda);

                $total = $nominal + $tunggakan;
                $next = $total + $data['iuran_bulanan'] + $adjust;

                $periode = date('F Y');

             return '<center>
             <a style="margin-left:10px;" href="https://api.whatsapp.com/send?phone='.$phone.'&text=Yth%20Bapak%2FIbu%20'.$data['name'].'%2C%20Kami%20ingin%20menginformasikan%20pembayaran%20iuran%20bulanan%20perumahan%20 periode '.$periode.'%20sebesar%20 Rp. '.number_format($next).'%20masih%20belum%20dibayarkan.%20Mohon%20untuk%20segera%20melakukan%20pembayaran%20melalui%20aplikasi%20MyDianIstana.%0A%0ATerima%20kasih%20atas%20perhatiannya%20dan%20sehat%20selalu%0A%0ASalam%2C%0AAdmin%20Dian%20Istana" target="_blank" class="bs-tooltip text-success mb-2" data-bs-toggle="tooltip" data-bs-placement="top" data-original-title="Whatsapp" aria-label="whatsapp" data-bs-original-title="whatsapp" title="whatsapp"><i class="fab fa-whatsapp"></i></a>';
         })->rawColumns(['action','amount','denda','total_outstanding','next_bill', 'adjustment','iuran','last_paid'])
         ->addIndexColumn()
         ->make(true);
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
