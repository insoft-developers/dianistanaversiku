<?php

namespace App\Http\Controllers\Admins;

use App\Helpers\Resp;
use App\Models\Penyelia;
use Illuminate\View\View;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\PenyeliaKategori;
use App\Traits\DBcustom\DataTablesTraitStatic;

class PenyeliaController extends Controller
{
    use DataTablesTraitStatic;
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $kategori = PenyeliaKategori::get();

        return view("admins.penyelia.index",compact("kategori"));
    }
    
    private static function set_ajax_list($request,bool $trash=false)
    {
        self::dataTablesQuery(Penyelia::query());
        self::dataTablesSelect(["penyelia.*","penyelia_kategori.name_kategori"]);
        self::dataTablesRequest($request);
        self::dataTablesOrderBy([null,'name_penyelia','no_telp']);
        self::dataTablesSearch(['name_penyelia','no_telp']);
        $join = [
            "penyelia_kategori","penyelia.id_kategori","penyelia_kategori.id"
        ];
        self::dataTablesJoin($join,"left");
        if ($trash) {
            self::dataTablesOnlyTrashed();
        }
        $dataResult = self::dataTablesGet();
        foreach ($dataResult as $item) {

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
            'kategori_penyelia'  =>  'required',
            'name_penyelia'  =>  'required|unique:penyelia',
            'no_telp'  =>  'required',
        ];

        $validator = $this->validateRed($request, $rules);
        if ($validator !== null) {
            Resp::error($validator);
        } else {
            $data = [
                'id_kategori'  =>  $request->kategori_penyelia,
                'name_penyelia'  =>  $request->name_penyelia,
                'no_telp'  =>  $request->no_telp,
            ];

            $insert = Penyelia::create($data);
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
        $select = ["name_penyelia","no_telp"];
        $getData = Penyelia::getFirstQuery(where: ["id" => $id]);
        if ($getData) {
            Resp::success('data user per id', $getData);
        } else {
            Resp::error(alertDanger('Data Tidak Ada'));
        }
        return Resp::json();
    }

    public function editTrash(string $id)
    {
        $getData = Penyelia::getFirstOnlyTrashed(["id" => $id]);
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
        $getData = Penyelia::getFirst(["id" => $id]);
        if ($getData) {
            $rules = [
                'kategori_penyelia'  =>  'required',
                'name_penyelia'  =>  'required',
                'no_telp'  =>  'required',
            ];

            $validator = $this->validateRed($request, $rules);
            if ($validator !== null) {
                Resp::error($validator);
            } else {
                $data = [
                    'id_kategori'  =>  $request->kategori_penyelia,
                    'name_penyelia'  =>  $request->name_penyelia,
                    'no_telp'  =>  $request->no_telp,
                ];

                $update = Penyelia::updateWhere(["id" => $getData->id],$data);
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
        $getData = Penyelia::getFirst(["id" => $id]);
        if ($getData) {
            $delete = Penyelia::deleteWhere(["id" => $id]);
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
        $getData = Penyelia::getFirstOnlyTrashed(["id" => $id]);
        if ($getData) {
            $restore = Penyelia::restoreOnlyTrashed(["id" => $id]);
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
