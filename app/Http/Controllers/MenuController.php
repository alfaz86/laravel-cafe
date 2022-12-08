<?php

namespace App\Http\Controllers;

use App\Models\Draft;
use App\Models\Menu;
use App\Helper;
use Illuminate\Http\Request;
use DataTables;
use Illuminate\Support\Facades\DB;

class MenuController extends Controller
{
    public function index()
    {
        return view('menus.index');
    }

    public function datatableMenu(Request $request)
    {
        $menus = Menu::orderBy('id', 'DESC');

        if ($request->search) {
            $menus = $menus->where('name', 'like', "%$request->search%")
                ->orWhere('type', '=', $request->search);
        }

        return DataTables::of($menus)
            ->addIndexColumn()
            ->addColumn('action', function ($menu) {
                return '<button class="btn btn-warning" style="zoom: 80%;" onclick="getEditForm('.$menu->id.')">Edit</button>
                <button class="btn btn-danger" style="zoom: 80%;" onclick="confirmDelete('.$menu->id.')">Hapus</button>';
            })
            ->toJson();
    }

    public function formCreate(Request $request)
    {
        $fileImgName = $request->fileImgName;
        $fileImgData = $request->fileImgData;
        return view('menus.create-modal', compact('fileImgName', 'fileImgData'));
    }

    public function create(Request $request)
    {
        DB::beginTransaction();

        try {
            $menu = new Menu();
            $menu->img   = $request->img;
            $menu->name  = $request->name;
            $menu->price = Helper::strToNumber($request->price);
            $menu->type  = $request->type;
            $menu->save();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }

        DB::commit();
    }

    public function delete(Request $request)
    {
        DB::beginTransaction();

        try {
            Menu::find($request->id)->delete();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }

        DB::commit();
    }

    public function formEdit(Request $request)
    {
        $menu = Menu::find($request->id);
        $menu->price = Helper::formatRupiah($menu->price);
        $menu->imgName = $menu->name.".png";
        return view('menus.edit-modal', compact('menu'));
    }

    public function update(Request $request)
    {
        DB::beginTransaction();

        try {
            $menu = Menu::find($request->id);
            $menu->img   = $request->img;
            $menu->name  = $request->name;
            $menu->price = Helper::strToNumber($request->price);
            $menu->type  = $request->type;
            $menu->save();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }

        DB::commit();
    }

    public function addCart(Request $request)
    {
        $check = Draft::where('user_id', auth()->user()->id)->where('menu_id', $request->id)->first();

        if (!$check) {
            $darft = new Draft();
            $darft->user_id = auth()->user()->id;
            $darft->menu_id = $request->id;
            $darft->save();
        }
        
        $darfts = Draft::where('user_id', auth()->user()->id)->get();
        return $darfts->count();
    }

    public function showCart(Request $request)
    {
        $drafts = Draft::where('user_id', auth()->user()->id)->get('menu_id');
        $menus = Menu::whereIn('id', $drafts)->get();

        $element = "";
        $total = Helper::formatRupiah($menus->sum('price'));

        foreach ($menus as $i => $menu) {
            $price = Helper::formatRupiah($menu->price);
            $element .= "<div class='border-bottom pb-2 pt-2' id='row-$menu->id'>
                <table class='w-100'>
                    <tr>
                        <td rowspan='3' class='text-center align-middle' style='min-width: 90px;'>
                            <img draggable='false' src='$menu->img' class='card-img-top order-list'>
                        </td>
                        <td class='w-100'>
                            <span class='type div-two'>$menu->type</span>
                        </td>
                        <td rowspan='2' class='text-right' style='min-width: 90px;'>
                            <h6 class='text-warning mb-0' id='label-subtotal-$menu->id'>$price</h6>
                            <input type='hidden' value='$menu->id' name='id[]' />
                            <input type='hidden' id='price-$menu->id' value='$menu->price' />
                            <input type='hidden' id='subtotal-$menu->id' value='$menu->price' class='subtotal' name='subtotal[]' />
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <h6 class='div-two' style='font-weight: bold;'>$menu->name</h6>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <div class='input-group input-group-sm pill-num div-two'>
                                <div class='input-group-prepend' onclick='editMenu($menu->id, `min`)'>
                                    <span class='input-left'>
                                        <svg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='currentColor' class='bi bi-dash' viewBox='0 0 16 16'>
                                        <path d='M4 8a.5.5 0 0 1 .5-.5h7a.5.5 0 0 1 0 1h-7A.5.5 0 0 1 4 8z'/>
                                        </svg>
                                    </span>
                                </div>
                                <input type='number' class='form-num' value='1' id='qty-$menu->id' oninput='editMenu($menu->id)' name='qty[]' />
                                <div class='input-group-prepend' onclick='editMenu($menu->id, `plus`)'>
                                    <span class='input-right'>
                                    <svg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='currentColor' class='bi bi-plus' viewBox='0 0 16 16'>
                                    <path d='M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4z'/>
                                    </svg></span>
                                </div>
                            </div>
                        </td>
                        <td class='text-right' onclick='deleteMenu($menu->id)'>
                            <svg xmlns='http://www.w3.org/2000/svg' width='20' height='20' fill='currentColor' class='bi bi-trash' viewBox='0 0 16 16'>
                            <path fill='#ced4da' d='M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0V6z'/>
                            <path fill='#ced4da' fill-rule='evenodd' d='M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1v1zM4.118 4 4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4H4.118zM2.5 3V2h11v1h-11z'/>
                            </svg>
                        </td>
                    </tr>
                </table>
            </div>";
        }

        return [
            "element" => $element,
            "total" => $total
        ];
    }

    public function deleteCart(Request $request)
    {
        Draft::where('user_id', auth()->user()->id)->where('menu_id', $request->id)->delete();

        return Helper::totalDraft();
    }
}
