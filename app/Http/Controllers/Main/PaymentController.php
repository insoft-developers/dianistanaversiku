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

        $user = User::findorFail(Auth::user()->id);
        if($payment->payment_type == 1) {
            $setting= \App\Models\Setting::findorFail(1);
            // $tgl_tempo = $setting->tanggal_jatuh_tempo_iuran_bulanan;
            $denda = $setting->percent_denda;
            // $tahun_ini = date('Y');
            // $bulan_ini = date('m');
            // $due = $tahun_ini.'-'.$bulan_ini.'-'.$tgl_tempo;
            $sekarang = date('Y-m-d');
            
            $due = $payment->due_date;
            $iuran = $user->iuran_bulanan;
            if($sekarang > $due) {
                return response()->json([
                    "success" => false,
                    "message" => "Payment of bills is due by the 20th of each month. Your current bill is already past due and therefore cannot be paid at this time. A penalty will be incurred and added to your next bill"
                ]);
                // $tagihan = $user->iuran_bulanan;
                // $nom_denda = $denda * $tagihan /100;
                // $amount = (int)$nom_denda + $tagihan;  
                // $text_denda = "Denda Rp. ".number_format($nom_denda);
                

            } else {
                $tunggakan = \App\Models\Tunggakan::where('user_id', Auth::user()->id)
                            ->where('amount', '!=', 0)->where('payment_id', '>', 0);
                $adjust = \App\Models\Tunggakan::where('user_id', Auth::user()->id)->where('payment_id', -1)->sum('amount');
                if($tunggakan->count() > 0) {
                    $jumlah_tunggakan = $tunggakan->sum('amount');
                    $nomi = $denda * $jumlah_tunggakan /100;
                    $nom_denda = $this->pembulatan((int)$nomi);
                    
                    $total_tunggakan = $nom_denda + $jumlah_tunggakan;
                    $amount = $iuran + $total_tunggakan + $adjust;
                    $text_denda = "Iuran Bulan ini :".number_format($iuran).'\nTunggakan : '.number_format($jumlah_tunggakan).'\nDenda Tunggakan : '.number_format($nom_denda).'\n Total Tunggakan : '.number_format($total_tunggakan).'\nAdjustment : '.number_format($adjust);
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
            
            
           
            $merchantCode = $setting->merchant_code; // dari duitku
            $merchantKey = $setting->api_payment; // dari duitku

            $timestamp = round(microtime(true) * 1000); //in milisecond
            $paymentAmount = $amount;
            $merchantOrderId = $invoice; // dari merchant, unique
            $productDetails = $payment->payment_name;
            $email = $user->email; // email pelanggan merchant
            $phoneNumber = $user->no_hp; // nomor tlp pelanggan merchant (opsional)
            $additionalParam = ''; // opsional
            $merchantUserInfo = ''; // opsional
            $customerVaName = $user->name; // menampilkan nama pelanggan pada tampilan konfirmasi bank
            $callbackUrl = $setting->callback_payment; // url untuk callback
            $returnUrl = url('/payment');//'http://example.com/return'; // url untuk redirect
            $expiryPeriod = 10; // untuk menentukan waktu kedaluarsa dalam menit
            $signature = hash('sha256', $merchantCode.$timestamp.$merchantKey);
            //$paymentMethod = 'VC'; //digunakan untuk direksional pembayaran

           
            $customerDetail = array(
                'firstName' => $user->name,
                'lastName' => "",
                'email' => $user->email,
                'phoneNumber' => str_replace("+62","",$user->no_hp),
            );


            $item1 = array(
                'name' => $payment->payment_name,
                'price' => $amount,
                'quantity' => 1);

          
            $itemDetails = array(
                $item1
            );

           
            $params = array(
                'paymentAmount' => $paymentAmount,
                'merchantOrderId' => $merchantOrderId,
                'productDetails' => $productDetails,
                'additionalParam' => $additionalParam,
                'merchantUserInfo' => $merchantUserInfo,
                'customerVaName' => $customerVaName,
                'email' => $email,
                'phoneNumber' => $phoneNumber,
                // 'itemDetails' => $itemDetails,
                'customerDetail' => $customerDetail,
                //'creditCardDetail' => $creditCardDetail,
                'callbackUrl' => $callbackUrl,
                'returnUrl' => $returnUrl,
                'expiryPeriod' => $expiryPeriod
                //'paymentMethod' => $paymentMethod
            );

            $params_string = json_encode($params);
            
            $url = $setting->duitku_link.'/api/merchant/createinvoice'; // Sandbox
            // $url = 'https://api-prod.duitku.com/api/merchant/createinvoice'; // Production

            $ch = curl_init();


            curl_setopt($ch, CURLOPT_URL, $url); 
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");                                                                     
            curl_setopt($ch, CURLOPT_POSTFIELDS, $params_string);                                                                  
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);                                                                      
            curl_setopt($ch, CURLOPT_HTTPHEADER, array(                                                                          
                'Content-Type: application/json',                                                                                
                'Content-Length: ' . strlen($params_string),
                'x-duitku-signature:' . $signature ,
                'x-duitku-timestamp:' . $timestamp ,
                'x-duitku-merchantcode:' . $merchantCode    
                )                                                                       
            );   
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);

          
            $request = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

           
            $response = json_decode($request);
            
            
            return response()->json([
                "success" => true,
                "data" => $response
            ]);
        }

       
    }


    public function payment_link_share($id) {
        if(Auth::user()->level != "user" ) {
            return redirect("frontend_dashboard");
        }
        $setting = \App\Models\Setting::findorFail(1);
        $user = User::findorFail(Auth::user()->id);
        $auth = \App\Models\Payment::findorFail($id);
        if($auth->payment_dedication != Auth::user()->id && $auth->payment_dedication != -1 ) {
            return  '<script>
                        alert("Payment Bill To Not Match");
                        window.location = "'.url('frontend_dashboard').'";
                    </script>';
        }



        $cek = \App\Models\PaymentDetail::where('payment_id', $id)
            ->where('user_id', Auth::user()->id)
            ->where('payment_status', 'PAID');

        
        if($cek->count() > 0) {
            return  '<script>
                        alert("This payment has already paid");
                        window.location = "'.url('payment').'";
                    </script>';
           
        }

        $payment = Payment::findorFail($id);
        $random = random_int(1000, 9999);
        $invoice = "PM-".date('dmyHis').$random;


        if($payment->payment_type == 1) {
            $tunggakan = \App\Models\Tunggakan::where('user_id', Auth::user()->id)
                ->where('payment_id', '>', 0)->sum('amount');
            $adjust = \App\Models\Tunggakan::where('user_id', Auth::user()->id)
            ->where('payment_id', -1)->sum('amount');

            if($tunggakan > 0 ) {
                $tagihan = $user->iuran_bulanan;
                $percent = $setting->percent_denda;
                $nomi = $percent * $tunggakan / 100;
                $denda = $this->pembulatan((int)$nomi);
                

                $amount = $tagihan + $tunggakan + $denda + $adjust;
            } else {
                $amount = $user->iuran_bulanan + $adjust;
            }
            
            
        } else {
            $amount = $payment->payment_amount;
        }


        $setting = \App\Models\Setting::findorFail(1);
        
        
        // $api_pay = base64_encode($setting->api_payment. ':');
        // $secret_key = 'Basic '.$api_pay;
        // $external_id = $invoice;
        // $data_request = Http::withHeaders([
        //     'Authorization' => $secret_key
        // ])->post('https://api.xendit.co/v2/invoices', [
        //     'external_id' => $external_id,
        //     'amount' => $amount,
        //     'success_redirect_url' => url('/payment'),
        //     'failure_redirect_url' => url('/payment'),
        //     'description' => "Pembayaran : ".$payment->payment_name. " <br>Note : ".$payment->payment_desc." <br>Periode : ".$payment->periode,
        // ]);
        
        // $response = $data_request->object();
        // return redirect($response->invoice_url);

        $detail = new PaymentDetail;
        $detail->invoice = $invoice;
        $detail->payment_id = $id;
        $detail->user_id = Auth::user()->id;
        $detail->amount = $amount;
        $detail->payment_status = "PENDING";
        $detail->created_at = date('Y-m-d H:i:s');
        $detail->updated_at = date('Y-m-d H:i:s');
        $detail->save();

        $merchantCode = $setting->merchant_code; // dari duitku
        $merchantKey = $setting->api_payment; // dari duitku

        $timestamp = round(microtime(true) * 1000); //in milisecond
        $paymentAmount = $amount;
        $merchantOrderId = $invoice; // dari merchant, unique
        $productDetails = "Pembayaran : ".$payment->payment_name. " \nNote : ".$payment->payment_desc." \nPeriode : ".$payment->periode;
        $email = $user->email; // email pelanggan merchant
        $phoneNumber = $user->no_hp; // nomor tlp pelanggan merchant (opsional)
        $additionalParam = ''; // opsional
        $merchantUserInfo = ''; // opsional
        $customerVaName = $user->name; // menampilkan nama pelanggan pada tampilan konfirmasi bank
        $callbackUrl = $setting->callback_payment; // url untuk callback
        $returnUrl = url('/payment');//'http://example.com/return'; // url untuk redirect
        $expiryPeriod = 10; // untuk menentukan waktu kedaluarsa dalam menit
        $signature = hash('sha256', $merchantCode.$timestamp.$merchantKey);
        //$paymentMethod = 'VC'; //digunakan untuk direksional pembayaran

        
        $customerDetail = array(
            'firstName' => $user->name,
            'lastName' => "",
            'email' => $user->email,
            'phoneNumber' => str_replace("+62","",$user->no_hp),
        );


        // $item1 = array(
        //     'name' => $payment->payment_name,
        //     'price' => $amount,
        //     'quantity' => 1);

        
        // $itemDetails = array(
        //     $item1
        // );

        
        $params = array(
            'paymentAmount' => $paymentAmount,
            'merchantOrderId' => $merchantOrderId,
            'productDetails' => $productDetails,
            'additionalParam' => $additionalParam,
            'merchantUserInfo' => $merchantUserInfo,
            'customerVaName' => $customerVaName,
            'email' => $email,
            'phoneNumber' => $phoneNumber,
            // 'itemDetails' => $itemDetails,
            'customerDetail' => $customerDetail,
            //'creditCardDetail' => $creditCardDetail,
            'callbackUrl' => $callbackUrl,
            'returnUrl' => $returnUrl,
            'expiryPeriod' => $expiryPeriod
            //'paymentMethod' => $paymentMethod
        );

        $params_string = json_encode($params);
        
        $url = $setting->duitku_link.'/api/merchant/createinvoice'; // Sandbox
        // $url = 'https://api-prod.duitku.com/api/merchant/createinvoice'; // Production

        $ch = curl_init();


        curl_setopt($ch, CURLOPT_URL, $url); 
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");                                                                     
        curl_setopt($ch, CURLOPT_POSTFIELDS, $params_string);                                                                  
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);                                                                      
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(                                                                          
            'Content-Type: application/json',                                                                                
            'Content-Length: ' . strlen($params_string),
            'x-duitku-signature:' . $signature ,
            'x-duitku-timestamp:' . $timestamp ,
            'x-duitku-merchantcode:' . $merchantCode    
            )                                                                       
        );   
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);

        
        $request = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        
        $response = json_decode($request);
        
        
        return redirect($response->paymentUrl);

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
