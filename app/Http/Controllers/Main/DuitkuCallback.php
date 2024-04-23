<?php

namespace App\Http\Controllers\Main;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DuitkuCallback extends Controller
{
    public function callback(Request $request) {

        $apiKey = '2e083b1f97e15dfd4cb02e6aa1d057a7'; // API key anda
        $merchantCode = isset($_POST['merchantCode']) ? $_POST['merchantCode'] : null; 
        $amount = isset($_POST['amount']) ? $_POST['amount'] : null; 
        $merchantOrderId = isset($_POST['merchantOrderId']) ? $_POST['merchantOrderId'] : null; 
        $productDetail = isset($_POST['productDetail']) ? $_POST['productDetail'] : null; 
        $additionalParam = isset($_POST['additionalParam']) ? $_POST['additionalParam'] : null; 
        $paymentCode = isset($_POST['paymentCode']) ? $_POST['paymentCode'] : null; 
        $resultCode = isset($_POST['resultCode']) ? $_POST['resultCode'] : null; 
        $merchantUserId = isset($_POST['merchantUserId']) ? $_POST['merchantUserId'] : null; 
        $reference = isset($_POST['reference']) ? $_POST['reference'] : null; 
        $signature = isset($_POST['signature']) ? $_POST['signature'] : null; 
        $publisherOrderId = isset($_POST['publisherOrderId']) ? $_POST['publisherOrderId'] : null; 
        $spUserHash = isset($_POST['spUserHash']) ? $_POST['spUserHash'] : null; 
        $settlementDate = isset($_POST['settlementDate']) ? $_POST['settlementDate'] : null; 
        $issuerCode = isset($_POST['issuerCode']) ? $_POST['issuerCode'] : null; 

        //log callback untuk debug 
        // file_put_contents('callback.txt', "* Callback *\r\n", FILE_APPEND | LOCK_EX);

        if(!empty($merchantCode) && !empty($amount) && !empty($merchantOrderId) && !empty($signature))
        {
            $params = $merchantCode . $amount . $merchantOrderId . $apiKey;
            $calcSignature = md5($params);

            if($signature == $calcSignature)
            {
               
                $cek = substr($merchantOrderId, 0, 2);
                
                if($cek == "PM") {
                    $trans = \App\Models\PaymentDetail::where('invoice', $merchantOrderId)
                        ->where('payment_status', 'PENDING')
                        ->first();

                    if($resultCode == '00') {
                        $trans->payment_status = 'PAID';
                    } else {
                        $trans->payment_status = 'PENDING';
                    }
                    // $trans->payment_status = 'PAID';
                    $trans->payment_method = $paymentCode;
                    $trans->payment_channel = $paymentCode;
                    $trans->paid_at = date('Y-m-d H:i:s');
                    $trans->save();    

                    $payment_id = $trans->payment_id;
                    $payment = \App\Models\Payment::findorFail($payment_id);
                    if($payment->payment_type == 1 && $resultCode == '00') {

                        \App\Models\Tunggakan::where('user_id', $trans->user_id)->update(["amount" => 0]);
                        \App\Models\Tunggakan::where('user_id', $trans->user_id)
                            ->where('payment_id', -1)->delete();

                    }


                    return response()->json([
                        "success" => true,
                        "data" => $trans
                    ]);
                } else {
                    $trans = \App\Models\Transaction::where('invoice', $merchantOrderId)
                        ->where('payment_status', 'PENDING')
                        ->first();

                    if($resultCode === '00') {
                        $trans->payment_status = 'PAID';
                    } else {
                        $trans->payment_status = 'PENDING';
                    }
                    // $trans->payment_status = 'PAIDs';
                    $trans->payment_method = $paymentCode;
                    $trans->payment_channel = $paymentCode;
                    $trans->paid_at = date('Y-m-d H:i:s');
                    $trans->save();    


                    return response()->json([
                        "success" => true,
                        "data" => $trans
                    ]);
                }

            }
            else
            {
                // file_put_contents('callback.txt', "* Bad Signature *\r\n\r\n", FILE_APPEND | LOCK_EX);
                throw new Exception('Bad Signature');
            }
        }
        else
        {
            // file_put_contents('callback.txt', "* Bad Parameter *\r\n\r\n", FILE_APPEND | LOCK_EX);
            throw new Exception('Bad Parameter');
        }
    }
}
