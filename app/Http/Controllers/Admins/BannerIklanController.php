<?php

namespace App\Http\Controllers\Admins;

use Carbon\Carbon;
use App\Helpers\Resp;
use Illuminate\View\View;
use App\Models\BannerIklan;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use App\Traits\DBcustom\DataTablesTraitStatic;

class BannerIklanController extends Controller
{
    use DataTablesTraitStatic;
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        return view("admins.banner_iklan.index");
    }

    private static function set_ajax_list($request, bool $trash = false)
    {
        self::dataTablesQuery(BannerIklan::query());
        self::dataTablesRequest($request);
        self::dataTablesOrderBy([null,null,'created_at',null,'title','content']);
        self::dataTablesSearch(['created_at','title','content']);
        if ($trash) {
            self::dataTablesOnlyTrashed();
        }
        $dataResult = self::dataTablesGet();
        foreach ($dataResult as $item) {

            $item->created_at_carbon = tagSmall(Carbon::parse($item->created_at)->format("Y-m-d H:i:s"));
            $item->image_src = assetImg_thumbnail();
            if ($item->image != "") {
                if (Storage::disk("local")->exists("public/banner/".$item->image)) {
                    $item->image_src = Storage::url("public/banner/".$item->image);
                }
            }

            $content_limit = Str::of($item->content)->stripTags('<br>');
            $content_limit = str_replace(['<br />','<br \/>'],' ',$content_limit);
            $item->content_limit = Str::limit($content_limit,40);

            $item->image_src = '<div class="avatar avatar-lg"><img alt="'.$item->title.'" src="'.$item->image_src.'" class="rounded" /></div>';
            
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

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $rules = [
            'image' => 'required|mimes:jpg,jpeg,png,gif|max:2048',
            'title' => 'required',
            'content' => 'required',
        ];

        $validator = $this->validateRed($request, $rules);
        if ($validator !== null) {
            Resp::error($validator);
        } else {
            $slug_title = $request->title;
            $checkData = BannerIklan::getFirst(where: ["title" => $slug_title]);
            if ($checkData) {
                $slug_title = $slug_title . time();
            }

            $image = $request->file('image');
            $image->storeAs('public/banner', $image->hashName());
            
            $data = [
                'image' => $image->hashName(),
                'title' => $request->title,
                'slug_title' => Str::slug($slug_title),
                'content' => $request->content,
            ];

            $insert = BannerIklan::create($data);
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
        $getData = BannerIklan::getFirst(where: ["id" => $id]);
        if ($getData) {
            $getData->created_at_carbon = tagSmall(Carbon::parse($getData->created_at)->format("Y-m-d H:i:s"));
            $getData->image_src = assetImg_thumbnail();
            if ($getData->image != "") {
                if (Storage::disk("local")->exists("public/banner/".$getData->image)) {
                    $getData->image_src = Storage::url("public/banner/".$getData->image);
                }
            }
            
            Resp::success('data user per id', $getData);
        } else {
            Resp::error(alertDanger('Data Tidak Ada'));
        }
        return Resp::json();
    }

    public function editTrash(string $id)
    {
        $getData = BannerIklan::getFirstOnlyTrashed(where: ["id" => $id]);
        if ($getData) {
            $getData->created_at_carbon = tagSmall(Carbon::parse($getData->created_at)->format("Y-m-d H:i:s"));
            $getData->image_src = assetImg_thumbnail();
            if ($getData->image != "") {
                if (Storage::disk("local")->exists("public/banner/".$getData->image)) {
                    $getData->image_src = Storage::url("public/banner/".$getData->image);
                }
            }
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
        $getData = BannerIklan::getFirst(where: ["id" => $id]);
        if ($getData) {
            $rules = [
                'image' => 'mimes:jpg,jpeg,png,gif|max:2048',
                'title' => 'required',
                'content' => 'required',
            ];

            $validator = $this->validateRed($request, $rules);
            if ($validator !== null) {
                Resp::error($validator);
            } else {
                $data = [
                    'title' => $request->title,
                    'content'=> $request->content,
                ];
                
                $image = $request->file('image');
                if (!empty($image)) {
                    if (Storage::disk("local")->exists("public/banner/".$getData->image)) {
                        Storage::disk("local")->delete("public/banner/".$getData->image);
                    }
                    $image->storeAs('public/banner', $image->hashName());
                    $data["image"] = $image->hashName();
                } else {
                    if ($request->is_remove == 1) {
                        if (Storage::disk("local")->exists("public/banner/".$getData->image)) {
                            Storage::disk("local")->delete("public/banner/".$getData->image);
                        }
                        $data["image"] = "";
                    }
                }

                $update = BannerIklan::updateWhere(["id" => $getData->id], $data);
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
        $getData = BannerIklan::getFirst(["id" => $id]);
        if ($getData) {
            $delete = BannerIklan::deleteWhere(["id" => $id]);
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
        $getData = BannerIklan::getFirstOnlyTrashed(["id" => $id]);
        if ($getData) {
            $restore = BannerIklan::restoreOnlyTrashed(["id" => $id]);
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
