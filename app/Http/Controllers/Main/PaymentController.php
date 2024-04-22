<?php

namespace App\Http\Controllers\Main;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Payment;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Validator;
use Session;
use App\Models\User;
use Illuminate\Support\Facades\Storage;
use Redirect;
use Illuminate\Support\Facades\Http;
use App\Models\PaymentDetail;
use App\Models\Setting;
class PaymentController extends Controller
{
    public function index() {
        if(Auth::user()->level != "user" ) {
            return redirect("frontend_dashboard");
        }

        $view = 'payment-menu';
        $data = Payment::where('payment_dedication', Auth::user()->id)->orWhere('payment_dedication', -1)->get();
        return view('frontend.payment', compact('view','data'));
    }


    public function payment_post(Request $request) {
        if(Auth::user()->level != "user" ) {
            return redirect("frontend_dashboard");
        }

       

        $input = $request->all();



        
        $payment = Payment::findorFail($input['id']);
        $random = random_int(1000, 9999);
        $invoice = "PM-".date('dmyHis').$random;


        if($payment->payment_type == 1) {
            $setting= \App\Models\Setting::findorFail(1);
            // $tgl_tempo = $setting->tanggal_jatuh_tempo_iuran_bulanan;
            $denda = $setting->percent_denda;
            // $tahun_ini = date('Y');
            // $bulan_ini = date('m');
            // $due = $tahun_ini.'-'.$bulan_ini.'-'.$tgl_tempo;
            $sekarang = date('Y-m-d');
            $user = User::findorFail(Auth::user()->id);
            $due = $payment->due_date;
            $iuran = $user->iuran_bulanan;
            if($sekarang > $due) {
                return response()->json([
                    "success" => false,
                    "message" => "Tagihan anda telah lewat jatuh tempo dan akan dikenakan denda serta diakumulasikan pada tagihan berikutnya."
                ]);
                // $tagihan = $user->iuran_bulanan;
                // $nom_denda = $denda * $tagihan /100;
                // $amount = (int)$nom_denda + $tagihan;  
                // $text_denda = "Denda Rp. ".number_format($nom_denda);
                

            } else {
                $tunggakan = \App\Models\Tunggakan::where('user_id', Auth::user()->id)
                            ->where('amount', '!=', 0);
                if($tunggakan->count() > 0) {
                    $jumlah_tunggakan = $tunggakan->sum('amount');
                    $nom_denda = $denda * $jumlah_tunggakan /100;
                    $total_tunggakan = (int)$nom_denda + $jumlah_tunggakan;
                    $amount = $iuran + $total_tunggakan;
                    $text_denda = "Iuran Bulan ini :".number_format($iuran).'<br>Tunggakan : '.number_format($jumlah_tunggakan).'<br>Denda Tunggakan : '.number_format($nom_denda).'<br> Total Tunggakan : '.number_format($total_tunggakan);
                } else {
                    $amount = $iuran;
                    $text_denda = "";
                }
                
            }         

        } else {
            $amount = $payment->payment_amount;
            $text_denda = "";
        }

        


        $detail = new PaymentDetail;
        $detail->invoice = $invoice;
        $detail->payment_id = $input['id'];
        $detail->user_id = Auth::user()->id;
        $detail->amount = $amount;
        $detail->payment_status = "PENDING";
        $detail->created_at = date('Y-m-d H:i:s');
        $detail->updated_at = date('Y-m-d H:i:s');
        $detail->save();

        if($detail) {
            $setting = \App\Models\Setting::findorFail(1);
            // $pajak = $setting->pajak;
            // $adminfee = $setting->admin_fee;

            // if(! empty($pajak)) {
            //     $percent = $amount * $pajak /100;
            //     $nominal = (int)$percent;
            //     $vat = $amount + $nominal;
            // } else {
            //     $nominal = 0;
            //     $vat = $amount;
            // }

            // if(! empty($adminfee)) {
            //     $final = $vat + $adminfee;
            // } else {
            //     $final = $vat;
            // }

            $api_pay = base64_encode($setting->api_payment. ':');
            $secret_key = 'Basic '.$api_pay;
            $external_id = $invoice;
            $data_request = Http::withHeaders([
                'Authorization' => $secret_key
            ])->post('https://api.xendit.co/v2/invoices', [
                'external_id' => $external_id,
                'amount' => $amount,
                'success_redirect_url' => url('/payment'),
                'failure_redirect_url' => url('/payment'),
                'description' => "Pembayaran : ".$payment->payment_name. " <br>Note : ".$payment->payment_desc." <br>Periode : ".$payment->periode. " ".$text_denda,
            ]);
            
            $response = $data_request->object();
            
            return response()->json([
                "success" => true,
                "data" => $response->invoice_url
            ]);
        }

       
    }


    public function payment_link_share($id) {
        if(Auth::user()->level != "user" ) {
            return redirect("frontend_dashboard");
        }

        $auth = \App\Models\Payment::findorFail($id);
        if($auth->payment_dedication != Auth::user()->id) {
            return  '<script>
                        alert("Payment Bill To Not Match");
                        window.location = "'.url('frontend_dashboard').'";
                    </script>';
        }



        $cek = \App\Models\PaymentDetail::where('payment_id', $id)
            ->where('user_id', Auth::user()->id)
            ->where('payment_status', 'PAID');

        
        if($cek->count() > 0) {
            return redirect('/payment');
        }

        $payment = Payment::findorFail($id);
        $random = random_int(1000, 9999);
        $invoice = "PM-".date('dmyHis').$random;


        if($payment->payment_type == 1) {
            $user = User::findorFail(Auth::user()->id);
            $amount = $user->iuran_bulanan;
        } else {
            $amount = $payment->payment_amount;
        }


        $setting = \App\Models\Setting::findorFail(1);
        // $pajak = $setting->pajak;
        // $adminfee = $setting->admin_fee;

        // if(! empty($pajak)) {
        //     $percent = $amount * $pajak /100;
        //     $nominal = (int)$percent;
        //     $vat = $amount + $nominal;
        // } else {
        //     $nominal = 0;
        //     $vat = $amount;
        // }

        // if(! empty($adminfee)) {
        //     $final = $vat + $adminfee;
        // } else {
        //     $final = $vat;
        // }
        
        
        $api_pay = base64_encode($setting->api_payment. ':');
        $secret_key = 'Basic '.$api_pay;
        $external_id = $invoice;
        $data_request = Http::withHeaders([
            'Authorization' => $secret_key
        ])->post('https://api.xendit.co/v2/invoices', [
            'external_id' => $external_id,
            'amount' => $amount,
            'success_redirect_url' => url('/payment'),
            'failure_redirect_url' => url('/payment'),
            'description' => "Pembayaran : ".$payment->payment_name. " <br>Note : ".$payment->payment_desc." <br>Periode : ".$payment->periode,
        ]);

        
        
        $response = $data_request->object();
        return redirect($response->invoice_url);
        
        

       
    }



    public function kwitansi($id) {
        $view = "kwitansi";
        $data = PaymentDetail::where('payment_id', $id)
            ->where('user_id', Auth::user()->id)
            ->where('payment_status', 'PAID')
            ->first();
        $setting = Setting::findorFail(1);
        $payment = Payment::findorFail($id);    
        return view('frontend.kwitansi', compact('view','data','setting','payment'));
    }
}
