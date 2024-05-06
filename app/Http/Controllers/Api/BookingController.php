<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\AdminsData;
use App\Models\Transaction;
use App\Models\Setting;
use App\Models\UnitBisnis;
use Validator;
use App\Models\BookingSetting;
use App\Models\Tunggakan;



class BookingController extends Controller
{
    public function booking_list() {
    	$data = UnitBisnis::where('status_booking', 'Aktif')->get();

    	return response()->json([
    		"success" => true,
    		"data" => $data

    	]);
    }

    public function booking_resume(Request $request) {
        $input = $request->all();
       
        $un = UnitBisnis::findorFail($input['business_unit_id']);

        if($un->kategori != "Kolam Renang") {
            $rules = array(
                "business_unit_id" => "required",
                "invoice" => "required",
                "start_time" => "required",
                "finish_time" => "required",
                "quantity" => "required",
                "total_price" => "required",
                "booking_date" => "required",
                
            );
        } else {
            if($input['level'] == "guest") {
                $rules = array(
                    "business_unit_id" => "required",
                    "invoice" => "required",
                    "package_id" => "required",
                    "package_name" => "required",
                    "quantity" => "required",
                    "total_price" => "required",
                    "booking_date" => "required",
                    
                );
            } else {
                $rules = array(
                    "business_unit_id" => "required",
                    "invoice" => "required",
                    "start_time" => "required",
                    "finish_time" => "required",
                    "quantity" => "required",
                    "total_price" => "required",
                    "booking_date" => "required",
                    
                );
            }
                
        }
        

        $validator = Validator::make($input, $rules);
        if($validator->fails()) {
            $pesan = $validator->errors();
            $pesanarr = explode(",", $pesan);
            $find = array("[","]","{","}");
            $html = '';
            foreach($pesanarr as $p ) {
                $html .= str_replace($find,"",$p).'<br>';
            }
            return response()->json([
                "success" => false,
                "message" => $html
            ]);

        }
        return response()->json([
            "success" => true,
            "message" => "success",
           
        ]);
        
    }

    public function booking_invoice(Request $request) {
    	$input = $request->all();

        $random = random_int(1000, 9999);
        $invoice = $this->incrementalHash();
        
        $tanggal = $input['selected_date'];  
        $timestamp = strtotime($tanggal);
        $day = date('D', $timestamp);
        
        $js['6'] = 0;
        $js['7'] = 0;
        $js['8'] = 0;
        $js['9'] = 0;
        $js['10'] = 0;
        $js['11'] = 0;
        $js['12'] = 0;
        $js['13'] = 0;
        $js['14'] = 0;
        $js['15'] = 0;
        $js['16'] = 0;
        $js['17'] = 0;
        $js['18'] = 0;
        $js['19'] = 0;
        $js['20'] = 0;

        $jam_tutup = [];

        $cr = BookingSetting::where('date', $tanggal)
        			->where('unit_id', $input['selected_unit'])
                    ->where('is_active', 1)->get();
        if($cr->count() > 0) {
            foreach($cr as $a) {
                $row['awal'] = $a->start_time;
                $row['akhir'] = $a->finish_time;
                array_push($jam_tutup, $row);
            }
        }

        $dr = BookingSetting::where('booking_day', $day)
        			->where('unit_id', $input['selected_unit'])
                    ->where('is_active', 1)->get();
        if($dr->count() > 0) {
            foreach($dr as $a) {
                $row['awal'] = $a->start_time;
                $row['akhir'] = $a->finish_time;
                array_push($jam_tutup, $row);
            }
        }

        if($cr->count() >0 || $dr->count() > 0) {
            foreach($jam_tutup as $jt) {
                $pertama = (int)$jt['awal'];
                $ending = (int)$jt['akhir'];
                
                for($q = $pertama; $q < $ending; $q++) {
                    $js[$q]++;
                }
            }
        }



        $not = Transaction::where('booking_date', $tanggal)
            ->where('order_status', 1)
            ->where('business_unit_id', $input['selected_unit'])
            ->where('payment_status', 'PAID')
            ->orWhere('payment_status', 'PENDING')
            ->get();
        
        $jam6 = 0;
        $jam7 = 0;
        $jam8 = 0;
        $jam9 = 0;
        $jam10 = 0;
        $jam11 = 0;
        $jam12 = 0;
        $jam13 = 0;
        $jam14 = 0;
        $jam15 = 0;
        $jam16 = 0;
        $jam17 = 0;
        $jam18 = 0;
        $jam19 = 0;
        $jam20 = 0;
        
        foreach($not as $key) {
            if($key->quantity == 1) {
                if($key->start_time == "06") {
                    $jam6++;
                }
                else if($key->start_time == "07") {
                    $jam7++;
                }
                else if($key->start_time == "08") {
                    $jam8++;
                }
                else if($key->start_time == "09") {
                    $jam9++;
                }
                else if($key->start_time == "10") {
                    $jam10++;
                }
                else if($key->start_time == "11") {
                    $jam11++;
                }
                else if($key->start_time == "12") {
                    $jam12++;
                }
                else if($key->start_time == "13") {
                    $jam13++;
                }
                else if($key->start_time == "14") {
                    $jam14++;
                }
                else if($key->start_time == "15") {
                    $jam15++;
                }
                else if($key->start_time == "16") {
                    $jam16++;
                }
                else if($key->start_time == "17") {
                    $jam17++;
                }
                else if($key->start_time == "18") {
                    $jam18++;
                }
                else if($key->start_time == "19") {
                    $jam19++;
                }
                else if($key->start_time == "20") {
                    $jam20++;
                }
            } 
            else if($key->quantity == 2) {
                if($key->start_time == "06") {
                    $jam6++;
                    $jam7++;
                }
                else if($key->start_time == "07") {
                    $jam7++;
                    $jam8++;
                }
                else if($key->start_time == "08") {
                    $jam8++;
                    $jam9++;
                }
                else if($key->start_time == "09") {
                    $jam9++;
                    $jam10++;
                }
                else if($key->start_time == "10") {
                    $jam10++;
                    $jam11++;
                }
                else if($key->start_time == "11") {
                    $jam11++;
                    $jam12++;
                }
                else if($key->start_time == "12") {
                    $jam12++;
                    $jam13++;
                }
                else if($key->start_time == "13") {
                    $jam13++;
                    $jam14++;
                }
                else if($key->start_time == "14") {
                    $jam14++;
                    $jam15++;
                }
                else if($key->start_time == "15") {
                    $jam15++;
                    $jam16++;
                }
                else if($key->start_time == "16") {
                    $jam16++;
                    $jam17++;
                }
                else if($key->start_time == "17") {
                    $jam17++;
                    $jam18++;
                }
                else if($key->start_time == "18") {
                    $jam18++;
                    $jam19++;
                }
                else if($key->start_time == "19") {
                    $jam19++;
                    $jam20++;
                }
            }
            else {
                if($key->start_time == "06") {
                    $jam6++;
                    $jam7++;
                    $jam8++;
                }
                else if($key->start_time == "07") {
                    $jam7++;
                    $jam8++;
                    $jam9++;
                }
                else if($key->start_time == "08") {
                    $jam8++;
                    $jam9++;
                    $jam10++;
                }
                else if($key->start_time == "09") {
                    $jam9++;
                    $jam10++;
                    $jam11++;
                }
                else if($key->start_time == "10") {
                    $jam10++;
                    $jam11++;
                    $jam12++;
                }
                else if($key->start_time == "11") {
                    $jam11++;
                    $jam12++;
                    $jam13++;
                }
                else if($key->start_time == "12") {
                    $jam12++;
                    $jam13++;
                    $jam14++;
                }
                else if($key->start_time == "13") {
                    $jam13++;
                    $jam14++;
                    $jam15++;
                }
                else if($key->start_time == "14") {
                    $jam14++;
                    $jam15++;
                    $jam16++;
                }
                else if($key->start_time == "15") {
                    $jam15++;
                    $jam16++;
                    $jam17++;
                }
                else if($key->start_time == "16") {
                    $jam16++;
                    $jam17++;
                    $jam18++;
                }
                else if($key->start_time == "17") {
                    $jam17++;
                    $jam18++;
                    $jam19++;
                }
                else if($key->start_time == "18") {
                    $jam18++;
                    $jam19++;
                    $jam20++;
                }
                else if($key->start_time == "19") {
                    $jam19++;
                    $jam20++;
                }
            }
        }



        
        return response()->json([
        	"success" => true,
        	"data" => $invoice,
        	"input" => $input,
        	"js6" => $js['6'],
        	"js7" => $js['7'],
        	"js8" => $js['8'],
        	"js9" => $js['9'],
        	"js10" => $js['10'],
        	"js11" => $js['11'],
        	"js12" => $js['12'],
        	"js13" => $js['13'],
        	"js14" => $js['14'],
        	"js15" => $js['15'],
        	"js16" => $js['16'],
        	"js17" => $js['17'],
        	"js18" => $js['18'],
        	"js19" => $js['19'],
        	"js20" => $js['20'],
        	"jam6" => $jam6,
        	"jam7" => $jam7,
        	"jam8" => $jam8,
        	"jam9" => $jam9,
        	"jam10" => $jam10,
        	"jam11" => $jam11,
        	"jam12" => $jam12,
        	"jam13" => $jam13,
        	"jam14" => $jam14,
        	"jam15" => $jam15,
        	"jam16" => $jam16,
        	"jam17" => $jam17,
        	"jam18" => $jam18,
        	"jam19" => $jam19,
        	"jam20" => $jam20,
        ]);
    }

    public function incrementalHash($len = 5){
        $charset = "0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ";
        $base = strlen($charset);
        $result = '';

        $now = explode(' ', microtime())[1];
        while ($now >= $base){
            $i = $now % $base;
            $result = $charset[$i] . $result;
            $now /= $base;
        }
        return substr($result, -5);
    }

    public function count_booking_price(Request $request) {
    	$input = $request->all();

    	// awal: 20, akhir: 21, quantity: 1, booking_date: 2024-04-30, level: user, unit_id: 3

    	$unit = UnitBisnis::findorFail($input['unit_id']);
    	$tanggal = $input['booking_date'];  
        $timestamp = strtotime($tanggal);
        $day = date('D', $timestamp);


    	if($input['level'] == "user") {

    		if($input['quantity'] == 1) {
    			$intakhir = (int)$input['akhir'];
    			if($intakhir <= 17) {
    				$total_price = 0;
    			} else {
    				if($day == 'Sat' || $day == 'Sun') {
    					$total_price = $unit->harga_warga_1721_weekend;
    				} else {
    					$total_price = $unit->harga_warga_1721_weekday;
    				}	
    			}
    		} else if($input['quantity'] == 2) {
    			$intakhir = (int)$input['akhir'];
    			if($intakhir <= 17) {
    				$total_price = 0;
    			}
    			else if($intakhir == 18) {
    				if($day == 'Sat' || $day == 'Sun') {
    					$total_price = $unit->harga_warga_1721_weekend;
    				} else {
    					$total_price = $unit->harga_warga_1721_weekday;
    				}	
    			} 

    			else {
    				if($day == 'Sat' || $day == 'Sun') {
    					$total_price = 2 * $unit->harga_warga_1721_weekend;
    				} else {
    					$total_price = 2 * $unit->harga_warga_1721_weekday;
    				}	
    			}
    		}

    		else if($input['quantity'] == 3) {
    			$intakhir = (int)$input['akhir'];
    			if($intakhir <= 17) {
    				$total_price = 0;
    			}
    			else if($intakhir == 18) {
    				if($day == 'Sat' || $day == 'Sun') {
    					$total_price = $unit->harga_warga_1721_weekend;
    				} else {
    					$total_price = $unit->harga_warga_1721_weekday;
    				}	
    			}
    			else if($intakhir == 19) {
    				if($day == 'Sat' || $day == 'Sun') {
    					$total_price = 2 * $unit->harga_warga_1721_weekend;
    				} else {
    					$total_price = 2 *$unit->harga_warga_1721_weekday;
    				}	
    			} 

    			else {
    				if($day == 'Sat' || $day == 'Sun') {
    					$total_price = 3 * $unit->harga_warga_1721_weekend;
    				} else {
    					$total_price = 3 * $unit->harga_warga_1721_weekday;
    				}	
    			}
    		}

    	} else {
    		if($input['quantity'] == 1) {
    			$intakhir = (int)$input['akhir'];
    			if($intakhir <= 17) {
    				if($day == 'Sat' || $day == 'Sun') {
    					$total_price = $unit->harga_umum_0617_weekend;
    				} else {
    					$total_price = $unit->harga_umum_0617_weekday;
    				}
    			} else {
    				if($day == 'Sat' || $day == 'Sun') {
    					$total_price = $unit->harga_umum_1721_weekend;
    				} else {
    					$total_price = $unit->harga_umum_1721_weekday;
    				}	
    			}
    		} else if($input['quantity'] == 2) {
    			$intakhir = (int)$input['akhir'];
    			if($intakhir <= 17) {
    				if($day == 'Sat' || $day == 'Sun') {
    					$total_price = 2 * $unit->harga_umum_0617_weekend;
    				} else {
    					$total_price = 2 * $unit->harga_umum_0617_weekday;
    				}
    			}
    			else if($intakhir == 18) {
    				if($day == 'Sat' || $day == 'Sun') {
    					$total_price = $unit->harga_umum_0617_weekend + $unit->harga_umum_1721_weekend;
    				} else {
    					$total_price = $unit->harga_umum_0617_weekday + $unit->harga_umum_1721_weekday;
    				}	
    			} 

    			else {
    				if($day == 'Sat' || $day == 'Sun') {
    					$total_price = 2 * $unit->harga_umum_1721_weekend;
    				} else {
    					$total_price = 2 * $unit->harga_umum_1721_weekday;
    				}	
    			}
    		}

    		else if($input['quantity'] == 3) {
    			$intakhir = (int)$input['akhir'];
    			if($intakhir <= 17) {
    				if($day == 'Sat' || $day == 'Sun') {
    					$total_price = 3 * $unit->harga_umum_0617_weekend;
    				} else {
    					$total_price = 3 * $unit->harga_umum_0617_weekday;
    				}
    			}
    			else if($intakhir == 18) {
    				if($day == 'Sat' || $day == 'Sun') {
    					$total_price = (2 *$unit->harga_umum_0617_weekend) + $unit->harga_umum_1721_weekend;
    				} else {
    					$total_price = (2 * $unit->harga_umum_0617_weekday) + $unit->harga_umum_1721_weekday;
    				}	
    			}
    			else if($intakhir == 19) {
    				if($day == 'Sat' || $day == 'Sun') {
    					$total_price = $unit->harga_umum_0617_weekend + (2 * $unit->harga_umum_1721_weekend);
    				} else {
    					$total_price = $unit->harga_umum_0617_weekday + (2 * $unit->harga_umum_1721_weekday);
    				}	
    			} 

    			else {
    				if($day == 'Sat' || $day == 'Sun') {
    					$total_price = 3 * $unit->harga_umum_1721_weekend;
    				} else {
    					$total_price = 3 * $unit->harga_umum_1721_weekday;
    				}	
    			}
    		}
    	}

    	return response()->json([
    		"success" => true,
    		"data" => $total_price
    	]);

    }

    public function term() {
    	$setting = Setting::findorFail(1);
    	return response()->json([
    		"success" => true,
    		"data" => $setting
    	]);
    }

    public function transaction(Request $request) {
        $input = $request->all();
       
        $un = UnitBisnis::findorFail($input['business_unit_id']);

        if($un->kategori != "Kolam Renang") {
            $rules = array(
                "business_unit_id" => "required",
                "invoice" => "required",
                "start_time" => "required",
                "finish_time" => "required",
                "quantity" => "required",
                "total_price" => "required",
                "booking_date" => "required",
                
            );
        } else {
            if($input['level'] == "guest") {
                $rules = array(
                    "business_unit_id" => "required",
                    "invoice" => "required",
                    "package_id" => "required",
                    "package_name" => "required",
                    "quantity" => "required",
                    "total_price" => "required",
                    "booking_date" => "required",
                    
                );
            } else {
                $rules = array(
                    "business_unit_id" => "required",
                    "invoice" => "required",
                    "start_time" => "required",
                    "finish_time" => "required",
                    "quantity" => "required",
                    "total_price" => "required",
                    "booking_date" => "required",
                    
                );
            }
                
        }
        

        $validator = Validator::make($input, $rules);
        if($validator->fails()) {
            $pesan = $validator->errors();
            $pesanarr = explode(",", $pesan);
            $find = array("[","]","{","}");
            $html = '';
            foreach($pesanarr as $p ) {
                $html .= str_replace($find,"",$p).'<br>';
            }
            return response()->json([
                "success" => false,
                "message" => $html
            ]);

        }

        try{    
            
            if($input['total_price'] == 0 || $input['total_price'] == "0") {
                $input['payment_status'] = "PAID";
                $input['description'] = "free for user";
                $input['paid_at'] = date('Y-m-d H:i:s');
                $input['payment_method'] = "FREE HOUR";
                $input['payment_channel'] = "FREE HOUR";
            } else {
                $input['payment_status'] = "PENDING";
                $input['description'] = "order";
            }

            if($request->start_time == null) {
                $input['start_time'] = "00";
            }
            if($request->finish_time == null) {
                $input['finish_time'] = "00";
            }
            if($request->package_id == null) {
                $input['package_id'] = 0;
            }
            if($request->package_name == null) {
                $input['package_name'] = "no-package";
            }

            $input['order_status'] = 1;
            $data = Transaction::create($input);
            
            return response()->json([
                "success" => true,
                "message" => "success",
                "id" => $data->id,
                "total_price" => $input['total_price']
            ]);

        }catch(\Exception $e) {
            return response()->json([
                "success" => false,
                "message" => $e->getMessage()
            ]);
        }
        
    }


    public function payment_process(Request $request) {
        $input = $request->all();
        
        $trans = Transaction::findorFail($input['id']);
        $setting = Setting::findorFail(1);
        $amount = $trans->total_price;
      
        
        $user = User::findorFail($trans->user_id);
        $product = UnitBisnis::findorFail($trans->business_unit_id);

        $merchantCode = $setting->merchant_code; // dari duitku
        $merchantKey = $setting->api_payment; // dari duitku

        $timestamp = round(microtime(true) * 1000); //in milisecond
        $paymentAmount = $amount;
        $merchantOrderId = $trans->invoice; // dari merchant, unique
        $productDetails = "Order atas nama : ".$user->name. " \nuntuk fasilitas : ".$product->name_unit." \nuntuk tanggal : ".$trans->booking_date." \njam : ".$trans->start_time.":00 WIB - ".$trans->finish_time.":00 WIB";
        $email = $user->email; // email pelanggan merchant
        $phoneNumber = $user->no_hp; // nomor tlp pelanggan merchant (opsional)
        $additionalParam = ''; // opsional
        $merchantUserInfo = ''; // opsional
        $customerVaName = $user->name; // menampilkan nama pelanggan pada tampilan konfirmasi bank
        $callbackUrl = $setting->callback_payment; // url untuk callback
        $returnUrl = url('/api/print_ticket/'.$input['id']);
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
            'name' => "Order atas nama : ".$user->name. " <br>untuk fasilitas : ".$product->name_unit." <br>untuk tanggal : ".$trans->booking_date." <br>jam : ".$trans->start_time.":00 WIB - ".$trans->finish_time.":00 WIB",
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

    public function print_ticket($id) {
        $tran = Transaction::where('id', (int)$id)->where('payment_status', 'PAID');
        if($tran->count() <= 0) {
            return redirect('/riwayat');
        }
        $trans = $tran->first();
        $user = User::findorFail($trans->user_id);
        $product = UnitBisnis::findorFail($trans->business_unit_id);
        return view('mobile.ticket', compact('trans','user','product'));
    }

    public function booking_check(Request $request) {
    	$input = $request->all();

    	$sekarang = date('Y-m-d');
        $hitung = strtotime("-30 day", strtotime($sekarang));
        $awal = date('Y-m-d', $hitung);
        
        $cek = Transaction::where('user_id', $input['user_id'])->where('payment_status', 'PAID')
            ->where('booking_date', '>=', $awal)
            ->where('booking_date', '<=', $sekarang)
            ->get();

        if($cek->count() > 3) {
            // return Redirect::to('frontend_dashboard')->with('error', "You have booked more than 3 times within 30 days");
            return response()->json([
            	"success" => false,
            	"message" => "You have booked more than 3 times within 30 days",
            ]);
        }

        $cek2 = \App\Models\Tunggakan::where('user_id', $input['user_id'])
            ->where('amount', '>', 0)
            ->get();
       
        if($cek2->count() > 0) {    
            // return Redirect::to('frontend_dashboard')->with('error', "You can not use booking feature for your outstanding payments. Please Pay The Bills First");
            return response()->json([
            	"success" => false,
            	"message" => "You can not use booking feature for your outstanding payments. Please Pay The Bills First",
            ]);
        } 

        return response()->json([
        	"success" => true,
        	"message" => "success"
        ]);   
    }
}


