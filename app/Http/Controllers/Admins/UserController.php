<?php

namespace App\Http\Controllers\Admins;

use App\Helpers\Resp;
use Illuminate\View\View;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use App\Traits\DBcustom\DataTablesTraitStatic;
use DataTables;
use Validator;

class UserController extends Controller
{
    use DataTablesTraitStatic;
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $view = "user-list";
        return view("admins.user.index", compact('view'));
    }


    public function ajax_list()
    {
        $user = User::all();
        return Datatables::of($user)
            ->addColumn('action', function($user){
                return '<a href="javascript:void(0);" class="bs-tooltip text-success mb-2" data-bs-toggle="tooltip" data-bs-placement="top" data-original-title="Detail" aria-label="Edit" data-bs-original-title="Detail" title="Detail" onclick="detailData('.$user->id.')"><i class="far fa-file"></i></a>&nbsp;&nbsp;<a href="javascript:void(0);" class="bs-tooltip text-warning mb-2" data-bs-toggle="tooltip" data-bs-placement="top" data-original-title="Edit" aria-label="Edit" data-bs-original-title="Edit" title="Edit" onclick="editData('.$user->id.')"><i class="far fa-edit"></i></a>&nbsp;<a href="javascript:void(0);" class="bs-tooltip text-danger mb-2" data-bs-toggle="tooltip" data-bs-placement="top" data-original-title="Hapus" aria-label="Hapus" data-bs-original-title="Hapus" title="Hapus" onclick="deleteData('.$user->id.')"><i class="far fa-times-circle"></i></i></a>';
        })->rawColumns(['action'])
        ->addIndexColumn()
        ->make(true);
    }

    public function ajax_list_trash(Request $request)
    {
        return self::set_ajax_list($request, true);
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
        $input = $request->all();

        $rules = array(
            "name" => "required",
            "username" => "required",
            "email" => "required",
            "password" => "required",
            "jenis_kelamin" => "required",
            "no_hp" => "required",
            "level" => "required",
            "is_active" => "required",
        );

        
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
        $getData = UnitBisnis::getFirst(where: ["id" => $id]);
        if ($getData) {
            $getData->image_src = assetImg_thumbnail();
            if ($getData->image != "") {
                if (file_exists(public_path('storage/unit-bisnis/'.$getData->image))) {
                    $getData->image_src = asset('storage/unit-bisnis/'.$getData->image);
                }
            }


            $getData->harga_warga_1721_weekday = numberFormat($getData->harga_warga_1721_weekday);
            $getData->harga_warga_1721_weekend = numberFormat($getData->harga_warga_1721_weekend);
            $getData->harga_umum_0617_weekday = numberFormat($getData->harga_umum_0617_weekday);
            $getData->harga_umum_0617_weekend = numberFormat($getData->harga_umum_0617_weekend);
            $getData->harga_umum_1721_weekday = numberFormat($getData->harga_umum_1721_weekday);
            $getData->harga_umum_1721_weekend = numberFormat($getData->harga_umum_1721_weekend);

            $getData->harga_membership_4x = numberFormat($getData->harga_membership_4x);
            $getData->harga_membership_8x = numberFormat($getData->harga_membership_8x);
            $getData->harga_non_member = numberFormat($getData->harga_non_member);
            $getData->harga_tamu_warga = numberFormat($getData->harga_tamu_warga);
            
            Resp::success('data user per id', $getData);
        } else {
            Resp::error(alertDanger('Data Tidak Ada'));
        }
        return Resp::json();
    }

    public function editTrash(string $id)
    {
        $getData = UnitBisnis::getFirstOnlyTrashed(where: ["id" => $id]);
        if ($getData) {
            $getData->image_src = assetImg_thumbnail();
            

            if ($getData->image != "") {
                if (file_exists(public_path('storage/unit-bisnis/'.$getData->image))) {
                    $getData->image_src = asset('storage/unit-bisnis/'.$getData->image);
                }
            }

            $getData->harga_warga_1721_weekday = numberFormat($getData->harga_warga_1721_weekday);
            $getData->harga_warga_1721_weekend = numberFormat($getData->harga_warga_1721_weekend);
            $getData->harga_umum_0617_weekday = numberFormat($getData->harga_umum_0617_weekday);
            $getData->harga_umum_0617_weekend = numberFormat($getData->harga_umum_0617_weekend);
            $getData->harga_umum_1721_weekday = numberFormat($getData->harga_umum_1721_weekday);
            $getData->harga_umum_1721_weekend = numberFormat($getData->harga_umum_1721_weekend);

            $getData->harga_membership_4x = numberFormat($getData->harga_membership_4x);
            $getData->harga_membership_8x = numberFormat($getData->harga_membership_8x);
            $getData->harga_non_member = numberFormat($getData->harga_non_member);
            $getData->harga_tamu_warga = numberFormat($getData->harga_tamu_warga);

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
        $getData = UnitBisnis::getFirst(where: ["id" => $id]);
        if ($getData) {
            $rules = [
                'image' => 'mimes:jpg,jpeg,png,gif|max:2048',
                'kategori' => 'required',
                'name_unit' => 'required',
                'jenis_harga' => 'required',
                'status_booking' => 'required',
            ];
    
            if ($request->jenis_harga == "Per Jam") {
                $rules["harga_warga_1721_weekday"] = 'required';
                $rules["harga_warga_1721_weekend"] = 'required';
                $rules["harga_umum_0617_weekday"] = 'required';
                $rules["harga_umum_0617_weekend"] = 'required';
                $rules["harga_umum_1721_weekday"] = 'required';
                $rules["harga_umum_1721_weekend"] = 'required';
            } else if ($request->jenis_harga == "Kedatangan") {
                $rules["harga_membership_4x"] = 'required';
                $rules["harga_membership_8x"] = 'required';
                $rules["harga_non_member"] = 'required';
                $rules["harga_tamu_warga"] = 'required';
            }

            $validator = $this->validateRed($request, $rules);
            if ($validator !== null) {
                Resp::error($validator);
            } else {
                $data = [
                    'kategori' => $request->kategori,
                    'name_unit' => $request->name_unit,
                    'jenis_harga' => $request->jenis_harga,
                    'status_booking' => $request->status_booking,
                    'id_admin' => adminAuth()->id,
                ];
    
                if ($request->jenis_harga == "Per Jam") {
                    $data["harga_warga_1721_weekday"] = self::intReplace($request->harga_warga_1721_weekday);
                    $data["harga_warga_1721_weekend"] = self::intReplace($request->harga_warga_1721_weekend);
                    $data["harga_umum_0617_weekday"] = self::intReplace($request->harga_umum_0617_weekday);
                    $data["harga_umum_0617_weekend"] = self::intReplace($request->harga_umum_0617_weekend);
                    $data["harga_umum_1721_weekday"] = self::intReplace($request->harga_umum_1721_weekday);
                    $data["harga_umum_1721_weekend"] = self::intReplace($request->harga_umum_1721_weekend);
                } else if ($request->jenis_harga == "Kedatangan") {
                    $data["harga_membership_4x"] = self::intReplace($request->harga_membership_4x);
                    $data["harga_membership_8x"] = self::intReplace($request->harga_membership_8x);
                    $data["harga_non_member"] = self::intReplace($request->harga_non_member);
                    $data["harga_tamu_warga"] = self::intReplace($request->harga_tamu_warga);
                }
                
                $image = $request->file('image');
                if (!empty($image)) {
                    if (file_exists(public_path('storage/unit-bisnis/'.$getData->image))) {
                        unlink(public_path('storage/unit-bisnis/'.$getData->image));
                    }
                    $image->storeAs('public/unit-bisnis', $image->hashName());
                    $data["image"] = $image->hashName();
                } else {
                    if ($request->is_remove == 1) {
                        if (file_exists(public_path('storage/unit-bisnis/'.$getData->image))) {
                            unlink(public_path('storage/unit-bisnis/'.$getData->image));
                        }
                        $data["image"] = "";
                    }
                }

                $update = UnitBisnis::updateWhere(["id" => $getData->id], $data);
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
        $getData = UnitBisnis::getFirst(["id" => $id]);
        if ($getData) {
            $delete = UnitBisnis::deleteWhere(["id" => $id]);
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
        $getData = UnitBisnis::getFirstOnlyTrashed(["id" => $id]);
        if ($getData) {
            $restore = UnitBisnis::restoreOnlyTrashed(["id" => $id]);
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
