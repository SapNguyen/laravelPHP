<?php

namespace App\Http\Controllers\admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\admin\brand;
use App\Models\admin\product;

use Illuminate\Support\Facades\File;

use Illuminate\Filesystem\Filesystem;

class BrandController extends Controller
{
    // [GET] /admin/brands (use)
    public function list()
    {
        $searchName = request('searchName');
        if (isset($searchName)) {
            $count = brand::where('brand_active', '!=', -1)
                ->where('brand_name', 'like', '%' . $searchName . '%')->count('brand_id');
            $get = brand::where('brand_active', '!=', -1)
                ->where('brand_name', 'like', '%' . $searchName . '%')->paginate(8);
        } else {
            $count = brand::where('brand_active', '!=', -1)
                ->where('brand_name', 'like', '%' . $searchName . '%')
                ->count('brand_id');
            $get = brand::where('brand_active', '!=', -1)->paginate(8);
        }
        return view('admin.brand.admin_brand_page', ['brand' => $get, 'count' => $count, 'title' => 'Brands List']);
    }

    // [GET] /admin/brand/add (use)
    public function addred()
    {
        $get = brand::where('brand_active', '!=', -1)->get();
        return view('admin.brand.admin_brand_add', ['brand' => $get, 'title' => 'Add New Brand']);
    }

    // [POST] /admin/brand/add (use)
    public function add(Request $request)
    {
        $id = brand::max('brand_id');
        if (isset($id)) {
            $id = $id + 1;
        } else {
            $id = 1;
        }
        $temp1 = public_path('img/brand/temp1');
        $temp2 = public_path('img/brand/temp2');
        $temp3 = public_path('img/brand/temp3');
        $path = "img/brand/{$id}";
        if (!File::exists($path)) {
            File::makeDirectory($path, 0755, true, true);
        }
        $files1 = File::allFiles($temp1);
        $files2 = File::allFiles($temp2);
        $files3 = File::allFiles($temp3);
        foreach ($files1 as $f1) {
            File::move($temp1 . '/' . $f1->getFilename(), $path . '/' . $f1->getFilename());
        }
        foreach ($files2 as $f2) {
            File::move($temp2 . '/' . $f2->getFilename(), $path . '/' . $f2->getFilename());
        }
        foreach ($files3 as $f3) {
            File::move($temp3 . '/' . $f3->getFilename(), $path . '/' . $f3->getFilename());
        }

        $b = new brand();
        $b->brand_name = request('bName');
        if ($request->hasFile('bLogo')) {
            $request->file('bLogo')->move($path, 'logo.jpg');
            $logoImg = 'logo.jpg';
        } else {
            $logoImg = NULL;
        }
        $b->brand_logo = $logoImg;
        if ($request->hasFile('bHPimg')) {
            $request->file('bHPimg')->move($path, 'img.jpg');
            $homeImg = 'img.jpg';
        } else {
            $homeImg = NULL;
        }
        $b->brand_img = $homeImg;
        if ($request->hasFile('bBPimg')) {
            $request->file('bBPimg')->move($path, 'des.jpg');
            $brandImg = 'des.jpg';
        } else {
            $brandImg = NULL;
        }
        $b->brand_des_img = $brandImg;
        if ($request->hasFile('bBannerimg')) {
            $request->file('bBannerimg')->move($path, 'banner.jpg');
            $bannerImg = 'banner.jpg';
        } else {
            $bannerImg = NULL;
        }
        $b->brand_banner = $bannerImg;
        $b->brand_des = request('bDes');
        if (!isset($b->brand_des)) {
            $b->brand_des = "No data";
        }

        $b->save();
        return to_route('a.b.list');
    }

    // [POST] /upload1 (use)
    public function upload1(Request $request)
    {
        $data = $request->All();
        $path = public_path('img/brand/temp1');
        $file = new Filesystem;
        $file->cleanDirectory($path);
        $img_name = $data['img1']->getClientOriginalName();
        $data['img1']->move($path . '/', $img_name);
    }

    // [POST] /upload2 (use)
    public function upload2(Request $request)
    {
        $data = $request->All();
        $path = public_path('img/brand/temp2');
        $file = new Filesystem;
        $file->cleanDirectory($path);
        $img_name = $data['img2']->getClientOriginalName();
        $data['img2']->move($path . '/', $img_name);
    }

    // [POST] /upload3 (use)
    public function upload3(Request $request)
    {
        $data = $request->All();
        $path = public_path('img/brand/temp3');
        $file = new Filesystem;
        $file->cleanDirectory($path);
        $img_name = $data['img3']->getClientOriginalName();
        $data['img3']->move($path . '/', $img_name);
    }

    //[GET] /admin/brand/edit (use)
    public function editred()
    {
        $get = brand::where('brand_id', request('bid'))->get();
        return view('admin.brand.admin_brand_edit', ['brand' => $get, 'title' => 'Edit Brand']);
    }

    // [POST] /admin/brand/edit (use)
    public function edit(Request $request)
    {
        $id = request('bid');
        $brandImagePath = "img/brand/{$id}";
        if ($request->hasFile('bLogo')) {
            if (File::exists("{$brandImagePath}/logo.jpg")) {
                File::delete("{$brandImagePath}/logo.jpg");
            }

            $request->file('bLogo')->move($brandImagePath, 'logo.jpg');
            $logoImg = 'logo.jpg';
        } else {
            if (File::exists("{$brandImagePath}/logo.jpg")) {
                $logoImg = 'logo.jpg';
            } else {
                $logoImg = NULL;
            }
        }


        if ($request->hasFile('bHPimg')) {
            if (File::exists("{$brandImagePath}/img.jpg")) {
                File::delete("{$brandImagePath}/img.jpg");
            }
            $request->file('bHPimg')->move($brandImagePath, 'img.jpg');
            $homeImg = 'img.jpg';
        } else {
            if (File::exists("{$brandImagePath}/img.jpg")) {
                $homeImg = 'img.jpg';
            } else {
                $homeImg = NULL;
            }
        }

        if ($request->hasFile('bBPimg')) {
            if (File::exists("{$brandImagePath}/des.jpg")) {
                File::delete("{$brandImagePath}/des.jpg");
            }
            $request->file('bBPimg')->move($brandImagePath, 'des.jpg');
            $brandImg = 'des.jpg';
        } else {
            if (File::exists("{$brandImagePath}/des.jpg")) {
                $brandImg = 'des.jpg';
            } else {
                $brandImg = NULL;
            }
        }

        if ($request->hasFile('bBannerimg')) {
            if (File::exists("{$brandImagePath}/banner.jpg")) {
                File::delete("{$brandImagePath}/banner.jpg");
            }
            $request->file('bBannerimg')->move($brandImagePath, 'banner.jpg');
            $bannerImg = 'banner.jpg';
        } else {
            if (File::exists("{$brandImagePath}/banner.jpg")) {
                $bannerImg = 'banner.jpg';
            } else {
                $bannerImg = NULL;
            }
        }
        $brand_des = request('bDes');
        if (!isset($brand_des)) {
            $brand_des = "No data";
        }

        brand::where('brand_id', $id)->update([
            'brand_name' => request('bName'),
            'brand_logo' => $logoImg,
            'brand_img' => $homeImg,
            'brand_des_img' => $brandImg,
            'brand_banner' => $bannerImg,
            'brand_des' => $brand_des
        ]);

        return to_route('a.b.list');
    }

    // [GET] /admin/brand/delete (use)
    public function delred()
    {

        $searchName = request('searchName');
        if (isset($searchName)) {
            $count = brand::where('brand_active', '!=', -1)
                ->where('brand_name', 'like', '%' . $searchName . '%')->count('brand_id');
            $get = brand::where('brand_active', '!=', -1)
                ->where('brand_name', 'like', '%' . $searchName . '%')->paginate(8);
        } else {
            $count = brand::where('brand_active', '!=', -1)->count('brand_id');
            $get = brand::where('brand_active', '!=', -1)->paginate(8);
        }
        return view('admin.brand.admin_brand_delete', ['brand' => $get, 'count' => $count, 'title' => 'Delete Brands']);
    }

    // [POST] /deleteBrand (use)
    public function del(Request $request)
    {
        $get = $request->all();
        brand::where('brand_id', $get['bid'])->update([
            'brand_name' => $get['bid'] . 'deleted',
            'brand_logo' => NULL,
            'brand_img' => NULL,
            'brand_des_img' => NULL,
            'brand_des' => NULL,
            'brand_active' => -1
        ]);
        product::where('brand_id', $get['bid'])->update([
            'product_active' => 0
        ]);
    }

    //[POST] /activateBrand (use)
    public function activate(Request $request)
    {
        $get = $request->all();
        brand::where('brand_id', $get['bid'])->update([
            'brand_active' => 1
        ]);
        product::where('product_id', $get['bid'])->update([
            'product_active' => 1
        ]);
    }

    //[POST] /deactivateBrand (use)
    public function deactivate(Request $request)
    {
        $get = $request->all();
        brand::where('Brand_id', $get['bid'])->update([
            'brand_active' => 0
        ]);
        product::where('product_id', $get['bid'])->update([
            'product_active' => 0
        ]);
    }




    

    // API

    public function list_api()
    {
        try {
            $searchName = request('searchName');
            if (isset($searchName)) {
                $count = brand::where('brand_active', '!=', -1)
                    ->where('brand_name', 'like', '%' . $searchName . '%')->count('brand_id');
                $get = brand::where('brand_active', '!=', -1)
                    ->where('brand_name', 'like', '%' . $searchName . '%')->simplePaginate(8);
            } else {
                $count = brand::where('brand_active', '!=', -1)
                    ->where('brand_name', 'like', '%' . $searchName . '%')
                    ->count('brand_id');
                $get = brand::where('brand_active', '!=', -1)->simplePaginate(8);
            }
            return response()->json(['status' => 'success', 'count' => $count, 'data' => $get]);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'error' => $e->getMessage()]);
        }
    }
    public function addred_api()
    {
        try {
            $get = brand::where('brand_active', '!=', -1)->get();
            return response()->json(['status' => 'success', 'data' => $get]);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'error' => $e->getMessage()]);
        }
    }
    public function add_api(Request $request)
    {
        try {
            $id = brand::max('brand_id');
            if (isset($id)) {
                $id += 1;
            } else {
                $id = 1;
            }

            $request->validate([
                'brand_name' => 'required|string',
            ]);

            $brandImagePath = "img/brand/{$id}";
            if (!File::exists($brandImagePath)) {
                File::makeDirectory($brandImagePath, 0755, true, true);
            }
            if ($request->hasFile('bLogo')) {
                // $logoImg = $request->file('bLogo')->storeAs("img/brand/{$id}", "{$id}logo.jpg", 'public');
                $request->file('bLogo')->move($brandImagePath, 'logo.jpg');
                $logoImg = 'logo.jpg';
            } else {
                $logoImg = NULL;
            }


            if ($request->hasFile('bHPimg')) {
                $request->file('bHPimg')->move($brandImagePath, 'img.jpg');
                $homeImg = 'img.jpg';
            } else {
                $homeImg = NULL;
            }

            if ($request->hasFile('bBPimg')) {
                $request->file('bBPimg')->move($brandImagePath, 'des.jpg');
                $brandImg = 'des.jpg';
            } else {
                $brandImg = NULL;
            }

            if ($request->hasFile('bBannerimg')) {
                $request->file('bBannerimg')->move($brandImagePath, 'banner.jpg');
                $bannerImg = 'banner.jpg';
            } else {
                $bannerImg = NULL;
            }

            $b = new brand();
            $b->brand_id = $request->input('brand_id');
            $b->brand_name = $request->input('brand_name');
            $b->brand_logo = $request->input('brand_logo');
            $b->brand_img = $request->input('brand_img');
            $b->brand_des_img = $request->input('brand_des_img');
            $b->brand_banner = $request->input('brand_banner');
            $b->brand_des = $request->input('brand_des', 'No data');
            $b->save();

            if ($b->save()) {
                return response()->json(['message' => 'Brand created successfully'], 201);
            } else {
                $error = $b->errors()->all(); // Lấy tất cả lỗi khi lưu
                return response()->json(['error' => 'Failed to save data', 'details' => $error], 500);
            }

            return response()->json(['status' => 'success', 'message' => 'Thêm brand thành công!', 'data' => $b], 201);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Thêm brand thất bại', 'error' => $e->getMessage()], 500);
        }
    }

    public function editred_api()
    {
        try {
            $get = brand::where('brand_id', request('bid'))->get();
            return response()->json(['status' => 'success', 'data' => $get], 201);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'error' => $e->getMessage()], 500);
        }
    }
    public function edit_api(Request $request)
    {

        try {
            $idb = $request->input('brand_id');

            $request->validate([
                'brand_name' => 'required|string',
            ]);

            $brandImagePath = "img/brand/{$idb}";
            if ($request->hasFile('bLogo')) {
                if (File::exists("{$brandImagePath}/logo.jpg")) {
                    File::delete("{$brandImagePath}/logo.jpg");
                }
                // $logoImg = $request->file('bLogo')->storeAs("img/brand/{$id}", "{$id}logo.jpg", 'public');
                $request->file('bLogo')->move($brandImagePath, 'logo.jpg');
                $logoImg = 'logo.jpg';
            } else {
                $logoImg = NULL;
            }


            if ($request->hasFile('bHPimg')) {
                if (File::exists("{$brandImagePath}/img.jpg")) {
                    File::delete("{$brandImagePath}/img.jpg");
                }
                $request->file('bHPimg')->move($brandImagePath, 'img.jpg');
                $homeImg = 'img.jpg';
            } else {
                $homeImg = NULL;
            }

            if ($request->hasFile('bBPimg')) {
                if (File::exists("{$brandImagePath}/des.jpg")) {
                    File::delete("{$brandImagePath}/des.jpg");
                }
                $request->file('bBPimg')->move($brandImagePath, 'des.jpg');
                $brandImg = 'des.jpg';
            } else {
                $brandImg = NULL;
            }

            if ($request->hasFile('bBannerimg')) {
                if (File::exists("{$brandImagePath}/banner.jpg")) {
                    File::delete("{$brandImagePath}/banner.jpg");
                }
                $request->file('bBannerimg')->move($brandImagePath, 'banner.jpg');
                $bannerImg = 'banner.jpg';
            } else {
                $bannerImg = NULL;
            }
            $brand_des = request('bDes');


            brand::where('brand_id', $idb)->update([
                'brand_name' => $request->input('brand_name'),
                'brand_logo' => $request->input('brand_logo'),
                'brand_img' => $request->input('brand_img'),
                'brand_des_img' => $request->input('brand_des_img'),
                'brand_banner' => $request->input('brand_banner'),
                'brand_des' => $request->input('brand_des', 'No data')
            ]);

            return response()->json(['status' => 'success', 'message' => 'Sửa brand thành công!'], 201);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Sửa brand thất bại', 'error' => $e->getMessage()], 500);
        }
    }

    public function delred_api()
    {
        $searchName = request('searchName');
        try {
            if (isset($searchName)) {
                $count = brand::where('brand_name', 'like', '%' . $searchName . '%')->count('brand_id');
                $get = brand::where('brand_name', 'like', '%' . $searchName . '%')->paginate(8);
            } else {
                $count = brand::count('brand_id');
                $get = brand::paginate(8);
            }
            return response()->json(['status' => 'success', 'count' => $count, 'data' => $get], 201);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'error' => $e->getMessage()], 500);
        }
    }

    public function del_api(Request $request)
    {
        try {
            $get = $request->all();
            brand::where('brand_id', $get['bid'])->update([
                'brand_active' => -1,

            ]);
            product::where('brand_id', $get['bid'])->update([
                'product_active' => 0
            ]);
            return response()->json(['status' => 'success', 'message' => 'Xóa thành công'], 201);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Xóa thất bại', 'error' => $e->getMessage()], 500);
        }
    }
    public function activate_api(Request $request)
    {
        try {
            $get = $request->all();
            brand::where('brand_id', $get['bid'])->update([
                'brand_active' => 1
            ]);
            product::where('product_id', $get['bid'])->update([
                'product_active' => 1
            ]);
            return response()->json(['status' => 'success', 'message' => 'Activate thành công'], 201);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Activate thất bại', 'error' => $e->getMessage()], 500);
        }
    }
    public function deactivate_api(Request $request)
    {
        try {
            $get = $request->all();
            brand::where('brand_id', $get['bid'])->update([
                'brand_active' => 0
            ]);
            product::where('product_id', $get['bid'])->update([
                'product_active' => 0
            ]);
            return response()->json(['status' => 'success', 'message' => 'Deactivate thành công'], 201);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Deactivate thất bại', 'error' => $e->getMessage()], 500);
        }
    }
}
