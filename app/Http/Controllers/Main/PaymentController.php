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
            $user = User::findorFail(Auth::user()->id);
            $amount = $user->iuran_bulanan;
        } else {
            $amount = $payment->payment_amount;
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
            $secret_key = 'Basic '.config('xendit.key_auth');
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
            return response()->json([
                "success" => true,
                "data" => $response->invoice_url
            ]);
        }

       
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
