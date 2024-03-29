<?php

namespace App\Http\Controllers\Admins;

use App\Helpers\Resp;
use Illuminate\View\View;
use App\Models\UnitBisnis;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use App\Traits\DBcustom\DataTablesTraitStatic;

class UnitBisnisController extends Controller
{
    use DataTablesTraitStatic;
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        return view("admins.unit_bisnis.index");
    }

    private static function set_ajax_list($request, bool $trash = false)
    {
        self::dataTablesQuery(UnitBisnis::query());
        self::dataTablesRequest($request);
        self::dataTablesSelect(["unit_bisnis.*","admins.name"]);
        self::dataTablesOrderBy([null,null,'name_unit','kategori',null,'jenis_harga','status_booking','admins.name']);
        self::dataTablesSearch(['name_unit','kategori','jenis_harga','status_booking','admins.name']);
        $join = [
            "admins","unit_bisnis.id_admin","admins.id"
        ];
        self::dataTablesJoin($join,"left");
        if ($trash) {
            self::dataTablesOnlyTrashed();
        }
        $dataResult = self::dataTablesGet();
        foreach ($dataResult as $item) {

            $item->image_src = assetImg_thumbnail();
            if ($item->image != "") {
                if (Storage::disk("local")->exists("public/unit-bisnis/".$item->image)) {
                    $item->image_src = Storage::url("public/unit-bisnis/".$item->image);
                }
            }
            $item->image_src = '<div class="avatar avatar-lg"><img alt="'.$item->title.'" src="'.$item->image_src.'" class="rounded" /></div>';

            if($item->status_booking == "Aktif"){
                $item->status_booking = spanGreen($item->status_booking);
            } else if($item->status_booking == "Non Aktif"){
                $item->status_booking = spanRed($item->status_booking);
            }
            
            $btnAction = "";
            if ($trash) {
                $btnAction .= '<a href="javascript:void(0);" class="bs-tooltip text-info mb-2" data-bs-toggle="tooltip" data-bs-placement="top" data-original-title="Restore" aria-label="Restore" data-bs-original-title="Restore" title="Restore" onclick="restoreData(' . $item->id . ')"><i class="fas fa-arrow-circle-left"></i></a>';
            } else {
                $btnAction .= '<a href="javascript:void(0);" class="bs-tooltip text-warning mb-2" data-bs-toggle="tooltip" data-bs-placement="top" data-original-title="Edit" aria-label="Edit" data-bs-original-title="Edit" title="Edit" onclick="editData(' . $item->id . ')"><i class="far fa-edit"></i></a>';

                $btnAction .= '&emsp; <a href="javascript:void(0);" class="bs-tooltip text-danger mb-2" data-bs-toggle="tooltip" data-bs-placement="top" data-original-title="Hapus" aria-label="Hapus" data-bs-original-title="Hapus" title="Hapus" onclick="deleteData(' . $item->id . ')"><i class="far fa-times-circle"></i></i></a>';
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
            $slug = $request->name_unit;
            $checkData = UnitBisnis::getFirst(where: ["name_unit" => $slug]);
            if ($checkData) {
                $slug = $slug . time();
            }
            
            $data = [
                'kategori' => $request->kategori,
                'name_unit' => $request->name_unit,
                'slug' => Str::slug($slug),
                'jenis_harga' => $request->jenis_harga,
                'status_booking' => $request->status_booking,
                'id_admin' => adminAuth()->id,
            ];

            $image = $request->file('image');
            if (!empty($image)) {
                $image->storeAs('public/unit-bisnis', $image->hashName());
                $data["image"] = $image->hashName();
            }

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

            $insert = UnitBisnis::create($data);
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
        $getData = UnitBisnis::getFirst(where: ["id" => $id]);
        if ($getData) {
            $getData->image_src = assetImg_thumbnail();
            if ($getData->image != "") {
                if (Storage::disk("local")->exists("public/unit-bisnis/".$getData->image)) {
                    $getData->image_src = Storage::url("public/unit-bisnis/".$getData->image);
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
                if (Storage::disk("local")->exists("public/unit-bisnis/".$getData->image)) {
                    $getData->image_src = Storage::url("public/unit-bisnis/".$getData->image);
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
                    if (Storage::disk("local")->exists("public/unit-bisnis/".$getData->image)) {
                        Storage::disk("local")->delete("public/unit-bisnis/".$getData->image);
                    }
                    $image->storeAs('public/unit-bisnis', $image->hashName());
                    $data["image"] = $image->hashName();
                } else {
                    if ($request->is_remove == 1) {
                        if (Storage::disk("local")->exists("public/unit-bisnis/".$getData->image)) {
                            Storage::disk("local")->delete("public/unit-bisnis/".$getData->image);
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
