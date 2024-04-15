<?php

namespace App\Http\Controllers\Admins;

use App\Helpers\Resp;
use Illuminate\View\View;
use App\Models\User;
use App\Models\Transaction;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use App\Traits\DBcustom\DataTablesTraitStatic;
use DataTables;
use Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class TransactionController extends Controller
{
    use DataTablesTraitStatic;
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $view = "transaction";
        return view("admins.transaction.index", compact('view'));
    }


    public function ajax_list()
    {
        $data = Transaction::all();
        return Datatables::of($data)
            ->addColumn('created_at', function($data){
                return '<div>'.date('d-m-Y', strtotime($data->created_at)).'<br>'.date('H:i:s', strtotime($data->created_at)).'</div>';
            })
            ->addColumn('paid_at', function($data){
                return '<div>'.date('d-m-Y', strtotime($data->paid_at)).'</div>';
            })
            ->addColumn('user_id', function($data){
                $users = \App\Models\User::where('id', $data->user_id);
                if($users->count() > 0) {
                    $user = $users->first();
                    return $user->name.'<br>( '.$user->level.' )';
                } else {
                    return '';
                }

            })
            ->addColumn('business_unit_id', function($data){
                $units = \App\Models\UnitBisnis::where('id', $data->business_unit_id);
                if($units->count() > 0) {
                    $unit = $units->first();
                    return $unit->name_unit;
                } else {
                    return '';
                }

            })
            ->addColumn('detail', function($data){
               return date('d-m-Y', strtotime($data->booking_date)).'<br>'.$data->start_time.' - '.$data->finish_time;
            })
            ->addColumn('total_price', function($data){
                if($data->total_price > 0 ) {
                    return '<div style="text-align:right;">'.number_format($data->total_price).'</div>';
                } else {
                    return 'Free';
                }
                
            })
            ->addColumn('payment_status', function($data){
                if($data->payment_status == 'PENDING') {
                    return '<span class="badge text-warning">PENDING</span>';
                } else  if($data->payment_status == 'PAID') {
                    return '<span class="badge text-success"><i class="fa fa-check"></i> PAID</span>';
                }
            })
            ->addColumn('action', function($data){
                if($data->payment_status == 'PAID') {
                    return '<a href="javascript:void(0);" class="bs-tooltip text-success mb-2" data-bs-toggle="tooltip" data-bs-placement="top" data-original-title="Detail" aria-label="Edit" data-bs-original-title="Detail" title="Detail" onclick="detailData('.$data->id.')"><i class="far fa-file"></i></a>&nbsp;&nbsp;<a href="javascript:void(0);" class="bs-tooltip text-success mb-2" data-bs-toggle="tooltip" data-bs-placement="top" data-original-title="Print Receipt" aria-label="Print Receipt" data-bs-original-title="Print Receipt" title="Print Receipt" onclick="printData('.$data->id.')"><i class="fa fa-print"></i></a>&nbsp;&nbsp;<a href="javascript:void(0);" class="bs-tooltip text-danger mb-2" data-bs-toggle="tooltip" data-bs-placement="top" data-original-title="Hapus" aria-label="Hapus" data-bs-original-title="Hapus" title="Hapus" onclick="deleteData('.$data->id.')"><i class="far fa-times-circle"></i></i></a>';
                } else {
                     return '<a href="javascript:void(0);" class="bs-tooltip text-success mb-2" data-bs-toggle="tooltip" data-bs-placement="top" data-original-title="Detail" aria-label="Detail" data-bs-original-title="Detail" title="Detail" onclick="detailData('.$data->id.')"><i class="far fa-file"></i></a>&nbsp;&nbsp;<a href="javascript:void(0);" class="bs-tooltip text-warning mb-2" data-bs-toggle="tooltip" data-bs-placement="top" data-original-title="Payment" aria-label="Payment" data-bs-original-title="Payment" title="Payment" onclick="paymentData('.$data->id.')"><i class="fa fa-file-invoice-dollar"></i></a>&nbsp;&nbsp;<a href="javascript:void(0);" class="bs-tooltip text-danger mb-2" data-bs-toggle="tooltip" data-bs-placement="top" data-original-title="Hapus" aria-label="Hapus" data-bs-original-title="Hapus" title="Hapus" onclick="deleteData('.$data->id.')"><i class="far fa-times-circle"></i></i></a>';
                }

               
        })->rawColumns(['action','detail', 'created_at', 'user_id','business_unit_id','total_price','paid_at','payment_status'])
        ->addIndexColumn()
        ->make(true);
    }

    public function ajax_list_trash(Request $request)
    {
        abort(404);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        abort(404);
    }

    private static function intReplace($val): int
    {
        return intval(str_replace(".","",$val));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        abort(404);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $transaction = Transaction::findorFail($id);
        $users = \App\Models\User::where('id', $transaction->user_id);
        if($users->count() > 0) {
            $user = $users->first();
            $userdata = $user->name;
        } else {
            $userdata = "no-data";
        }

        $units = \App\Models\UnitBisnis::where('id', $transaction->business_unit_id);
        if($units->count() > 0) {
            $unit = $units->first();
            $unitdata = $unit->name_unit;
        } else {
            $unitdata = "no-data";
        }
        
        $HTML = "";
        $HTML .= '<input type="hidden" value="'.$id.'" id="id-transaction">';
        $HTML .= '<div class="row">';
        $HTML .= '<div class="col-md-12">';
        $HTML .= '<div class="card">';
        $HTML .= '<div class="card-body">';
        $HTML .= '<table class="table table-bordered table-striped">';
        $HTML .= '<tbody>';    
        $HTML .= '<tr><th>User Name</th><th>'.$userdata.'</th></tr>';
        $HTML .= '<tr><th>Facility</th><th>'.$unitdata.'</th></tr>';
        $HTML .= '<tr><th>Invoice</th><th>'.$transaction->invoice.'</th></tr>';
        $HTML .= '<tr><th>Booking Date</th><th>'.date('d F Y', strtotime($transaction->booking_date)).'</th></tr>';
        $HTML .= '<tr><th>Booking Time</th><th>'.$transaction->start_time.'.00 WIB - '.$transaction->finish_time.'.00 WIB</th></tr>';
        $HTML .= '<tr><th>Number of User</th><th>'.$transaction->quantity.'</th></tr>';
        $HTML .= '<tr><th>Total Price</th><th>Rp. '.number_format($transaction->total_price).'</th></tr>';
        $HTML .= '<tr><th>Description</th><th>'.$transaction->description.'</th></tr>';
        $HTML .= '<tr><th>Package</th><th>'.$transaction->package_name.'</th></tr>';
        $HTML .= '<tr><th>Payment Status</th><th>'.$transaction->payment_status.'</th></tr>';
        $HTML .= '<tr><th>Payment Method</th><th>'.$transaction->payment_method.'</th></tr>';
        $HTML .= '<tr><th>Payment Channel</th><th>'.$transaction->payment_channel.'</th></tr>';
        $HTML .= '<tr><th>Paid At</th><th>'.date('d F Y', strtotime($transaction->paid_at)).'</th></tr>';
        $HTML .= '<tr><th>Created At</th><th>'.date('d F Y', strtotime($transaction->created_at)).'</th></tr>';
        $HTML .= '</tbody>';
        $HTML .= '</table>';
        $HTML .= '</div>'; //cardbody
        $HTML .= '</div>'; //card
        $HTML .= '</div>'; //col-md-6
        $HTML .= '</div>'; //row
    
     
    

        return $HTML;
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        abort(404);
    }

    
    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        abort(404);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
       $query = Transaction::destroy($id);
       return $query;
    }


    public function payment(Request $request) 
    {
        $input = $request->all();
        
        $data = Transaction::findorFail($input['id']);
        $data->payment_status = 'PAID';
        $data->paid_at = date('Y-m-d H:i:s');
        $data->payment_method = "ADMIN";
        $data->payment_channel = adminAuth()->name;
        $data->save();
        return response()->json([
            "success"=> true,
            "message" => "payment success"
        ]);
    }

    public function print_ticket($id) {
        $tran = \App\Models\Transaction::where('id', (int)$id)->where('payment_status', 'PAID');
        if($tran->count() <= 0) {
            return redirect('/backdata/transaction');
        }
        $trans = $tran->first();
        $user = \App\Models\User::findorFail($trans->user_id);
        $product = \App\Models\UnitBisnis::findorFail($trans->business_unit_id);
        return view('admins.transaction.ticket', compact('trans','user','product'));
    }


    public function print_transaction($id) {
        $transaction = Transaction::findorFail($id);
        return view('admins.transaction.print', compact('transaction'));
    }

   
}
