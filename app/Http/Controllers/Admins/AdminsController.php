<?php

namespace App\Http\Controllers\Admins;

use App\Helpers\Resp;
use Illuminate\View\View;
use App\Models\AdminsData;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use App\Traits\DBcustom\DataTablesTraitStatic;

class AdminsController extends Controller
{
    use DataTablesTraitStatic;
    /**
     * Display a listing of the resource.
     */
    public function __construct()
    {
        return redirect('backdata/penyelia');
    }





    public function index(): View
    {
        $view = "admin";
        return view("admins.admin.index", compact('view'));
    }
    
    private static function set_ajax_list($request,bool $trash=false)
    {
        self::dataTablesQuery(AdminsData::query());
        self::dataTablesRequest($request);
        self::dataTablesOrderBy([null,'name','username','level','email','no_telp']);
        self::dataTablesSearch(['name','username','level','email','no_telp']);
        if ($trash) {
            self::dataTablesOnlyTrashed();
        }
        
        $dataResult = self::dataTablesGet();
        foreach ($dataResult as $item) {

            if($item->role == 1) {
                $item->role = "Super Admin";
            } else {
                $item->role = "Admin";
            }

            $btnAction = "";

            if ($trash) {
                $btnAction .= '<a href="javascript:void(0);" class="bs-tooltip text-info mb-2" data-bs-toggle="tooltip" data-bs-placement="top" data-original-title="Restore" aria-label="Restore" data-bs-original-title="Restore" title="Restore" onclick="restoreData('.$item->id.')"><i class="fas fa-arrow-circle-left"></i></a>';
            } else {
                $btnAction .= '<a href="javascript:void(0);" class="bs-tooltip text-warning mb-2" data-bs-toggle="tooltip" data-bs-placement="top" data-original-title="Edit" aria-label="Edit" data-bs-original-title="Edit" title="Edit" onclick="editData('.$item->id.')"><i class="far fa-edit"></i></a>';

                $btnAction .= '&emsp; <a href="javascript:void(0);" class="bs-tooltip text-danger mb-2" data-bs-toggle="tooltip" data-bs-placement="top" data-original-title="Hapus" aria-label="Hapus" data-bs-original-title="Hapus" title="Hapus" onclick="deleteData('.$item->id.')"><i class="far fa-times-circle"></i></i></a>';
            }
            $item->action = $btnAction;
        }
        return self::dataTablesJson($dataResult);
    }

    public function ajax_list(Request $request) 
    {
        return self::set_ajax_list($request);
    }

    public function ajax_list_trash(Request $request)
    {
        return self::set_ajax_list($request,true);
    }
    
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        abort(404);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $rules = [
            'name'  =>  'required',
            'email'  =>  'required|email|unique:admins',
            'no_telp'  =>  'required',
            'level'  =>  'required',
            'role'  =>  'required',
            'username'  =>  'required|unique:admins',
            'password'  =>  'required|min:6',
            'confirm_password'  =>  'required|same:password|min:6',
        ];

        $validator = $this->validateRed($request, $rules);
        if ($validator !== null) {
            Resp::error($validator);
        } else {
            $data = [
                'name'  =>  $request->name,
                'email'  =>  $request->email,
                'no_telp'  =>  $request->no_telp,
                'level'  =>  $request->level,
                'role'  =>  $request->role,
                'username'  =>  $request->username,
                'password'  =>  Hash::make($request->password),
            ];

            $insert = AdminsData::create($data);
            if ($insert) {
                Resp::success(alertSuccess("Data Berhasil di Simpan"));
            } else {
                Resp::error(alertDanger("Gagal Simpan Data"));
            }
        }
        return Resp::json();
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        abort(404);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $select = ["name","username","email","no_telp","level","role"];
        $getData = AdminsData::getFirstQuery(select: $select, where: ["id" => $id]);
        // $getData = AdminsData::getFirst(["id" => $id]);
        if ($getData) {
            Resp::success('data user per id', $getData);
        } else {
            Resp::error(alertDanger('Data Tidak Ada'));
        }
        return Resp::json();
    }

    public function editTrash(string $id)
    {
        $getData = AdminsData::getFirstOnlyTrashed(["id" => $id]);
        if ($getData) {
            Resp::success('data user per id', $getData);
        } else {
            Resp::error(alertDanger('Data Tidak Ada'));
        }
        return Resp::json();
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $getData = AdminsData::getFirst(["id" => $id]);
        if ($getData) {
            $rules = [
                'name'  =>  'required',
                'email'  =>  'required|email',
                'no_telp'  =>  'required',
                'level'  =>  'required',
                'role' => 'required'
            ];

            $password = $request->password;
            if (!empty($password)) {
                $rules['password'] = 'min:6';
                $rules['confirm_password'] = 'required|same:password|min:6';
            }

            $validator = $this->validateRed($request, $rules);
            if ($validator !== null) {
                Resp::error($validator);
            } else {
                $data = [
                    'name'  =>  $request->name,
                    'email'  =>  $request->email,
                    'no_telp'  =>  $request->no_telp,
                    'level'  =>  $request->level,
                    'role' => $request->role,
                ];

                $password = $request->password;
                if (!empty($password)) {
                    $data['password'] = Hash::make($password);
                }

                $update = AdminsData::updateWhere(["id" => $getData->id],$data);
                if ($update) {
                    Resp::success(alertSuccess("Data Berhasil di Update"));
                } else {
                    Resp::error(alertDanger("Gagal Update Data"));
                }
            }
        } else {
            Resp::error(alertDanger('Data Tidak Ada'));
        }
        return Resp::json();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $getData = AdminsData::getFirst(["id" => $id]);
        if ($getData) {
            $delete = AdminsData::deleteWhere(["id" => $id]);
            if ($delete) {
                Resp::success(alertSuccess("Data Berhasil di Hapus"));
            } else {
                Resp::error(alertDanger("Gagal Hapus Data"));
            }
        } else {
            Resp::error(alertDanger('Data Tidak Ada'));
        }
        return Resp::json();
    }

    public function restore(string $id)
    {
        $getData = AdminsData::getFirstOnlyTrashed(["id" => $id]);
        if ($getData) {
            $restore = AdminsData::restoreOnlyTrashed(["id" => $id]);
            if ($restore) {
                Resp::success(alertSuccess("Data Berhasil di Restore"));
            } else {
                Resp::error(alertDanger("Gagal Restore Data"));
            }
        } else {
            Resp::error(alertDanger('Data Tidak Ada'));
        }
        return Resp::json();
    }

    
}
