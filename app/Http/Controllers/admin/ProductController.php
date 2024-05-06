<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;

use App\Models\admin\product;
use App\Models\admin\product_size_color;
use App\Models\admin\brand;

use Illuminate\Support\Facades\File;
use Carbon\Carbon;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class ProductController extends Controller
{
    //[GET] /admin/products (use)
    public function list()
    {
        $pq = product_size_color::groupBy('product_id')
            ->get([
                'product_id',
                product_size_color::raw('SUM(quantity) as quan')
            ]);
        $search = request('searchName');
        if (isset($search)) {
            $count = product::where('product_active', '!=', -1)
                ->where('product_name', 'like', '%' . $search . '%')
                ->count('product_id');
            $get = product::join('brand', 'product.brand_id', '=', 'brand.brand_id')
                ->where('product_active', '!=', -1)
                ->where('product_name', 'like', '%' . $search . '%')
                ->paginate(8);
        } else {
            $count = product::where('product_active', '!=', -1)->count('product_id');
            $get = product::join('brand', 'product.brand_id', '=', 'brand.brand_id')
                ->where('product_active', '!=', -1)->paginate(8);
        }
        return view('admin.product.admin_product_page', ['product' => $get, 'count' => $count, 'quan' => $pq, 'title' => 'Products List']);
    }

    // public function list_inventory()
    // {
    //     $pq = product_size_color::groupBy('product_id')
    //         ->get([
    //             'product_id',
    //             product_size_color::raw('SUM(quantity) as quan')
    //         ]);
    //     $search = request('searchName');
    //     if (isset($search)) {
    //         $count = product::where('product_active', '!=', -1)
    //             ->where('product_name', 'like', '%' . $search . '%')
    //             ->count('product_id');
    //         $get = product::join('brand', 'product.brand_id', '=', 'brand.brand_id')
    //             ->where('product_active', '!=', -1)
    //             ->where('product_name', 'like', '%' . $search . '%')
    //             ->paginate(8);
    //     } else {
    //         $count = product::where('product_active', '!=', -1)->count('product_id');
    //         $get = product::join('brand', 'product.brand_id', '=', 'brand.brand_id')
    //             ->where('product_active', '!=', -1)->paginate(8);
    //     }

    //     return view('admin.product.admin_product_page_inventory', ['product' => $get, 'count' => $count, 'quan' => $pq, 'title' => 'Products List']);
    // }

    public function list_inventory()
    {
        $search = request('searchName');

        $pq = product_size_color::groupBy('product_id')
            ->get([
                'product_id',
                product_size_color::raw('SUM(quantity) as quan')
            ]);

        if (isset($search)) {

            $query = product::join('brand', 'product.brand_id', '=', 'brand.brand_id')
                ->leftJoin('product_size_color', 'product.product_id', '=', 'product_size_color.product_id')
                ->select('product.*', 'brand.brand_name', DB::raw('SUM(product_size_color.quantity) as quan'))
                ->where('product_active', '!=', -1)
                ->where('product_name', 'like', '%' . $search . '%')
                ->groupBy('product.product_id', 'product.product_name', 'product.product_material', 'product.product_des', 'product.product_price', 'product.product_genre', 'product.product_updated_date', 'product.product_active', 'product.brand_id', 'product.discount_id', 'brand.brand_name');

            $query->havingRaw('quan > 0 AND quan < 10');
    
            $get = $query->paginate(8);
        } else {
            $query = product::join('brand', 'product.brand_id', '=', 'brand.brand_id')
                ->leftJoin('product_size_color', 'product.product_id', '=', 'product_size_color.product_id')
                ->select('product.*', 'brand.brand_name', DB::raw('SUM(product_size_color.quantity) as quan'))
                ->where('product_active', '!=', -1)
                ->groupBy('product.product_id', 'product.product_name', 'product.product_material', 'product.product_des', 'product.product_price', 'product.product_genre', 'product.product_updated_date', 'product.product_active', 'product.brand_id', 'product.discount_id', 'brand.brand_name');

            $query->havingRaw('quan > 0 AND quan < 10');
    
            $get = $query->paginate(8);
        }

        return view('admin.product.admin_product_page_inventory', ['product' => $get, 'quan' => $pq, 'title' => 'Products List']);
    }

    public function list_bulk()
    {
        $search = request('searchName');

        $pq = product_size_color::groupBy('product_id')
            ->get([
                'product_id',
                product_size_color::raw('SUM(quantity) as quan')
            ]);

        $query = product::join('brand', 'product.brand_id', '=', 'brand.brand_id')
            ->leftJoin('product_size_color', 'product.product_id', '=', 'product_size_color.product_id')
            ->select('product.*', 'brand.brand_name', DB::raw('SUM(product_size_color.quantity) as quan'))
            ->where('product_active', '!=', -1)
            ->groupBy('product.product_id', 'product.product_name', 'product.product_material', 'product.product_des', 'product.product_price', 'product.product_genre', 'product.product_updated_date', 'product.product_active', 'product.brand_id', 'product.discount_id', 'brand.brand_name');

        if (isset($search)) {
            $query->where('product_name', 'like', '%' . $search . '%');
        }
        $query->havingRaw('quan > 20');

        // $count = $query->count('product.product_id');
        $get = $query->paginate(8);

        return view('admin.product.admin_product_page_bulk', ['product' => $get, 'quan' => $pq, 'title' => 'Products List']);
    }

    //[GET] /admin/product/view
    public function viewred()
    {
        $get = product::where('product_id', request('pid'))
            ->join('brand', 'product.brand_id', '=', 'brand.brand_id')
            ->where('product_active', '!=', -1)->paginate(7);
        $get2 = product_size_color::where('product_id', request('pid'))
            ->groupBy('product_id')
            ->groupBy('color')
            ->groupBy('product_image')
            ->get([
                'product_id', 'color',
                product_size_color::raw('COUNT(size) as row'),
                'product_image'
            ]);
        $get3 = product_size_color::where('product_id', request('pid'))->get();

        return view('admin.product.admin_product_view', [
            'product' => $get, 'psc1' => $get2, 'psc2' => $get3, 'title' => 'View Product'
        ]);
    }

    //[GET] /admin/product/add (use)
    public function addred()
    {
        $brand = brand::where('brand_active', '!=', -1)
            ->orderBy('brand_name', 'asc')->get();
        return view('admin.product.admin_product_add', ['brand' => $brand, 'title' => 'Add New Product']);
    }

    //[POST] /addProduct (use)
    public function add(Request $request)
    {
        $get = $request->all();

        if ($get['genre'] == "Unisex") {
            $genre = 2;
        } else if ($get['genre'] == "Nam") {
            $genre = 1;
        } else if ($get['genre'] == "Nữ") {
            $genre = 0;
        }

        $product = new product();
        $product->product_name = $get['name'];
        $product->product_material = $get['material'];
        $product->product_genre = $genre;
        $product->product_des = $get['des'];
        $product->product_price = $get['price'];
        $product->brand_id = $get['brand'];
        $product->save();

        $id = product::max('product_id');
        if (!isset($id)) {
            $id = 1;
        }

        $filepath = public_path('/img/product/' . $id);
        $temppath = public_path('/img/product/temp');
        if (File::exists($filepath)) {
            File::cleanDirectory($filepath);
        } else {
            File::makeDirectory($filepath);
        }
        if (!File::exists($temppath)) {
            File::makeDirectory($temppath);
        }
        $imgs = File::allFiles($temppath);
        for ($i = 0; $i < count($imgs); $i++) {
            File::move($temppath . '/' . $imgs[$i]->getFilename(), $filepath . '/' . $imgs[$i]->getFileName());
        }

        $pcsq = $get['pcsq'];
        $pcs = array();
        for ($i = 0; $i < count($pcsq); $i++) {
            $ex = explode('|', $pcsq[$i]);
            $pcs[$i] = new product_size_color();
            $pcs[$i]->product_id = $id;
            $pcs[$i]->color = $ex[0];
            $pcs[$i]->size = $ex[1];
            $pcs[$i]->quantity = $ex[2];
            $pcs[$i]->product_image = $ex[3];
            $pcs[$i]->save();
        }

        return response()->json([
            'message' => 'Product added successfully.'
        ]);
    }

    //[GET] /admin/product/edit (use)
    public function editred()
    {
        $pid = request('pid');
        $brand = brand::where('brand_active', '!=', -1)
            ->orderBy('brand_name', 'asc')->get();
        $get = product::where('product_id', request('pid'))
            ->join('brand', 'product.brand_id', '=', 'brand.brand_id')
            ->get();
        $get2 = product_size_color::where('product_id', request('pid'))
            ->groupBy('product_id')
            ->groupBy('color')
            ->groupBy('product_image')
            ->get([
                'product_id', 'color',
                product_size_color::raw('COUNT(size) as row'),
                'product_image'
            ]);
        $get3 = product_size_color::where('product_id', request('pid'))->get();
        return view('admin.product.admin_product_edit', [
            'product' => $get, 'psc1' => $get2, 'psc2' => $get3,
            'brand' => $brand, 'title' => 'Edit Product'
        ]);
    }

    //[POST] /updateProduct (use)
    public function update(Request $request)
    {
        $get = $request->all();
        if ($get['genre'] == "Unisex") {
            $genre = 2;
        } else if ($get['genre'] == "Nam") {
            $genre = 1;
        } else if ($get['genre'] == "Nữ") {
            $genre = 0;
        }

        product::where('product_id', $get['id'])->update([
            'product_name' => $get['name'],
            'product_material' => $get['material'],
            'product_genre' => $genre,
            'product_des' => $get['des'],
            'product_price' => $get['price'],
            'brand_id' => $get['brand'],
            'product_updated_date' => Carbon::now()->toDateString()
        ]);

        $id = $get['id'];


        $filepath = public_path('/img/product/' . $id);
        $temppath = public_path('/img/product/temp');
        if (File::exists($filepath)) {
            File::cleanDirectory($filepath);
        } else {
            File::makeDirectory($filepath);
        }
        if (!File::exists($temppath)) {
            File::makeDirectory($temppath);
        }
        $imgs = File::allFiles($temppath);
        for ($i = 0; $i < count($imgs); $i++) {
            File::move($temppath . '/' . $imgs[$i]->getFilename(), $filepath . '/' . $imgs[$i]->getFileName());
        }


        product_size_color::where('product_id', $get['id'])->delete();
        $pcsq = $get['pcsq'];
        $pcs = array();
        for ($i = 0; $i < count($pcsq); $i++) {
            $ex = explode('|', $pcsq[$i]);
            $pcs[$i] = new product_size_color();
            $pcs[$i]->product_id = $id;
            $pcs[$i]->color = $ex[0];
            $pcs[$i]->size = $ex[1];
            $pcs[$i]->quantity = $ex[2];
            $pcs[$i]->product_image = $ex[3];
            $pcs[$i]->save();
        }


        return response()->json([
            'message' => 'Product updated successfully.'
        ]);
    }

    //[GET] /admin/product/delete (use)
    public function delred()
    {
        $pq = product_size_color::groupBy('product_id')
            ->get([
                'product_id',
                product_size_color::raw('SUM(quantity) as quan')
            ]);
        $search = request('searchName');
        if (isset($search)) {
            $count = product::where('product_active', '!=', -1)
                ->where('product_name', 'like', '%' . $search . '%')
                ->count('product_id');
            $get = product::join('brand', 'product.brand_id', '=', 'brand.brand_id')
                ->where('product_active', '!=', -1)
                ->where('product_name', 'like', '%' . $search . '%')
                ->paginate(8);
        } else {
            $count = product::where('product_active', '!=', -1)->count('product_id');
            $get = product::join('brand', 'product.brand_id', '=', 'brand.brand_id')
                ->where('product_active', '!=', -1)->paginate(8);
        }
        return view('admin.product.admin_product_delete', [
            'product' => $get, 'count' => $count, 'quan' => $pq, 'title' => 'Products List'
        ]);
    }

    //[POST] /deleteProduct
    public function delete(Request $request)
    {
        $get = $request->all();
        product::where('product_id', $get['pid'])->update([
            'product_active' => -1
        ]);
    }

    //[POST] /activateProduct
    public function activate(Request $request)
    {
        $get = $request->all();
        product::where('product_id', $get['pid'])->update([
            'product_active' => 1
        ]);
    }

    //[POST] /deactivateProduct (use)
    public function deactivate(Request $request)
    {
        $get = $request->all();
        product::where('product_id', $get['pid'])->update([
            'product_active' => 0
        ]);
    }

    //[POST] addPimg (use)
    public function addimg(Request $request)
    {
        $get = $request->all();
        $path = public_path('img/product/temp');
        if (!File::exists($path)) {
            File::makeDirectory($path);
        }
        for ($i = 0; $i < count($get) - 1; $i++) {
            $img[$i] = $get[$i];
            $img_name = $img[$i]->hashName();
            $img[$i]->move($path . '/', $img_name);
            $name[$i] = $img_name;
        }

        $imgs = $name[0];
        for ($i = 1; $i < count($name); $i++) {
            $imgs = $imgs . ',' . $name[$i];
        }

        return response()->json([
            'message' => 'Add images to folder ' . $path . ' successfully.',
            'name' => $name, 'imgs' => $imgs
        ]);
    }

    //[POST] /removePcolor (use)
    public function removecolor(Request $request)
    {
        $get = $request->all();
        $path = public_path('img/product/temp');
        if (!is_null($get['imgs'])) {
            $name = explode(',', $get['imgs']);
            foreach ($name as $n) {
                File::delete($path . '/' . $n);
            }
        }
        return response()->json([
            'message' => 'Remove imgs of color successfully.'
        ]);
    }






    // API


    public function list_api()
    {
        try {
            $pq = product_size_color::groupBy('product_id')
                ->get([
                    'product_id',
                    product_size_color::raw('SUM(quantity) as quan')
                ]);
            $search = request('searchName');
            if (isset($search)) {
                $count = product::where('product_active', '!=', -1)
                    ->where('product_name', 'like', '%' . $search . '%')
                    ->count('product_id');
                $get = product::join('brand', 'product.brand_id', '=', 'brand.brand_id')
                    ->where('product_active', '!=', -1)
                    ->where('product_name', 'like', '%' . $search . '%')
                    ->simplePaginate(8);
            } else {
                $count = product::where('product_active', '!=', -1)->count('product_id');
                $get = product::join('brand', 'product.brand_id', '=', 'brand.brand_id')
                    ->where('product_active', '!=', -1)->simplePaginate(8);
            }
            return response()->json(['status' => 'success', 'product' => $get, 'count' => $count, 'quan' => $pq]);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'error' => $e->getMessage()]);
        }
    }
    public function viewred_api()
    {
        try {
            $get = product::where('product_id', request('pid'))
                ->join('brand', 'product.brand_id', '=', 'brand.brand_id')
                ->where('product_active', '!=', -1)->simplePaginate(8);
            $get2 = product_size_color::where('product_id', request('pid'))
                ->groupBy('product_id')
                ->groupBy('color')
                ->groupBy('product_image')
                ->get([
                    'product_id', 'color',
                    product_size_color::raw('COUNT(size) as row'),
                    'product_image'
                ]);
            $get3 = product_size_color::where('product_id', request('pid'))->get();

            return response()->json(['status' => 'success', 'product' => $get, 'psc1' => $get2, 'psc2' => $get3]);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'error' => $e->getMessage()]);
        }
    }
    public function addred_api()
    {
        try {
            $brand = brand::where('brand_active', '!=', -1)
                ->orderBy('brand_name', 'asc')->get();
            return response()->json(['status' => 'success', 'brand' => $brand]);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'error' => $e->getMessage()]);
        }
    }
    public function add_api(Request $request)
    {
        try {
            $get = $request->all();

            $product = new product();
            $product->product_id = $request->input('product_id');
            $product->product_name = $request->input('product_name');
            $product->product_material = $request->input('product_material');
            $product->product_des = $request->input('product_des');
            $product->product_price = $request->input('product_price');
            $product->product_genre = $request->input('product_genre');
            $product->brand_id = $request->input('brand_id');
            $product->save();

            //$id = product::max('product_id');
            //if (!isset($id)) {
            //    $id = 1;
            //}

            // $filepath = public_path('/img/product/' . $id);
            // $temppath = public_path('/img/product/temp');
            // if (File::exists($filepath)) {
            //     File::cleanDirectory($filepath);
            // } else {
            //     File::makeDirectory($filepath);
            // }
            // if (!File::exists($temppath)) {
            //     File::makeDirectory($temppath);
            // }

            // $imgs = File::allFiles($temppath);
            // for ($i = 0; $i < count($imgs); $i++) {
            //     File::move($temppath . '/' . $imgs[$i]->getFilename(), $filepath . '/' . $imgs[$i]->getFileName());
            // }

            $pcsq = $request->input('pcsq');
            $pcs = array();
            for ($i = 0; $i < count($pcsq); $i++) {
                $ex = explode('|', $pcsq[$i]);
                $pcs[$i] = new product_size_color();
                $pcs[$i]->product_id = $request->input('product_id');
                $pcs[$i]->color = $ex[0];
                $pcs[$i]->size = $ex[1];
                $pcs[$i]->quantity = $ex[2];
                $pcs[$i]->product_image = $ex[3];
                $pcs[$i]->save();
            }

            return response()->json(['status' => 'success', 'message' => 'Thêm sản phẩm thành công!'], 201);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Thêm sản phẩm thất bại', 'error' => $e->getMessage()], 500);
        }
    }
    public function editred_api()
    {
        try {
            $brand = brand::where('brand_active', '!=', -1)
                ->orderBy('brand_name', 'asc')->get();
            $get = product::where('product_id', request('pid'))
                ->join('brand', 'product.brand_id', '=', 'brand.brand_id')
                ->get();
            $get2 = product_size_color::where('product_id', request('pid'))
                ->groupBy('product_id')
                ->groupBy('color')
                ->groupBy('product_image')
                ->get([
                    'product_id', 'color',
                    product_size_color::raw('COUNT(size) as row'),
                    'product_image'
                ]);
            $get3 = product_size_color::where('product_id', request('pid'))->get();
            return response()->json([
                'status' => 'success',
                'product' => $get,
                'psc1' => $get2,
                'psc2' => $get3,
                'brand' => $brand
            ], 201);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'error' => $e->getMessage()], 500);
        }
    }
    public function update_api(Request $request)
    {
        try {
            // $get = $request->all();
            $id = $request->input('product_id');


            product::where('product_id', $id)->update([
                'product_name' => $request->input('product_name'),
                'product_material' => $request->input('product_material'),
                'product_des' => $request->input('product_des'),
                'product_price' => $request->input('product_price'),
                'product_genre' => $request->input('product_genre'),
                'brand_id' => $request->input('brand_id'),
                'product_updated_date' => Carbon::now()->toDateString()
            ]);

            // $id = $get['id'];

            // $filepath = public_path('/img/product/' . $id);
            // $temppath = public_path('/img/product/temp');
            // if (File::exists($filepath)) {
            //     File::cleanDirectory($filepath);
            // } else {
            //     File::makeDirectory($filepath);
            // }
            // if (!File::exists($temppath)) {
            //     File::makeDirectory($temppath);
            // }
            // $imgs = File::allFiles($temppath);
            // for ($i = 0; $i < count($imgs); $i++) {
            //     File::move($temppath . '/' . $imgs[$i]->getFilename(), $filepath . '/' . $imgs[$i]->getFileName());
            // }


            product_size_color::where('product_id', $id)->delete();
            //$pcsq = $get['pcsq'];
            $pcsq = $request->input('pcsq');
            $pcs = array();
            for ($i = 0; $i < count($pcsq); $i++) {
                $ex = explode('|', $pcsq[$i]);
                $pcs[$i] = new product_size_color();
                $pcs[$i]->product_id = $id;
                $pcs[$i]->color = $ex[0];
                $pcs[$i]->size = $ex[1];
                $pcs[$i]->quantity = $ex[2];
                $pcs[$i]->product_image = $ex[3];
                $pcs[$i]->save();
            }



            // product_size_color::where('product_id', $id)->delete();
            // product_size_color::where('product_size_color_id', 9)->update([
            //     'color' => 'Hi'
            // ]);
            //DB::update('update product_size_color set color = ? where product_size_color_id = ?', ['Hi', 9]);
            // $pcsq = $get['pcsq'];
            // $pcs = array();
            // for ($i = 0; $i < count($pcsq); $i++) {
            //     $ex = explode('|', $pcsq[$i]);
            //     $pcs[$i] = new product_size_color();
            //     $pcs[$i]->product_id = $id;
            //     $pcs[$i]->color = $ex[0];
            //     $pcs[$i]->size = $ex[1];
            //     $pcs[$i]->quantity = $ex[2];
            //     $pcs[$i]->product_image = $ex[3];
            //     $pcs[$i]->save();
            // }

            return response()->json(['status' => 'success', 'message' => 'Sửa sản phẩm thành công!'], 201);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Sửa sản phẩm thất bại', 'error' => $e->getMessage()], 500);
        }
    }
    public function delred_api()
    {
        try {
            $pq = product_size_color::groupBy('product_id')
                ->get([
                    'product_id',
                    product_size_color::raw('SUM(quantity) as quan')
                ]);
            $search = request('searchName');
            if (isset($search)) {
                $count = product::where('product_active', '!=', -1)
                    ->where('product_name', 'like', '%' . $search . '%')
                    ->count('product_id');
                $get = product::join('brand', 'product.brand_id', '=', 'brand.brand_id')
                    ->where('product_active', '!=', -1)
                    ->where('product_name', 'like', '%' . $search . '%')
                    ->simplePaginate(8);
            } else {
                $count = product::where('product_active', '!=', -1)->count('product_id');
                $get = product::join('brand', 'product.brand_id', '=', 'brand.brand_id')
                    ->where('product_active', '!=', -1)->simplePaginate(8);
            }
            // return view('admin.product.admin_product_delete', [
            //     'product' => $get, 'count' => $count, 'quan' => $pq, 'title' => 'Products List'
            // ]);
            return response()->json(['status' => 'success', 'product' => $get, 'count' => $count, 'quan' => $pq]);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'error' => $e->getMessage()]);
        }
    }
    public function delete_api(Request $request)
    {
        try {
            $get = $request->all();
            product::where('product_id', $get['pid'])->update([
                'product_active' => -1
            ]);
            return response()->json(['status' => 'success']);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'error' => $e->getMessage()]);
        }
    }
    public function activate_api(Request $request)
    {
        try {
            $get = $request->all();
            product::where('product_id', $get['pid'])->update([
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
            product::where('product_id', $get['pid'])->update([
                'product_active' => 0
            ]);
            return response()->json(['status' => 'success', 'message' => 'Deactivate thành công'], 201);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Deactivate thất bại', 'error' => $e->getMessage()], 500);
        }
    }
    public function addimg_api(Request $request)
    {
        try {
            $get = $request->all();
            $path = public_path('img/product/temp');
            if (!File::exists($path)) {
                File::makeDirectory($path);
            }
            for ($i = 0; $i < count($get) - 1; $i++) {
                $img[$i] = $get[$i];
                $img_name = $img[$i]->hashName();
                $img[$i]->move($path . '/', $img_name);
                $name[$i] = $img_name;
            }

            $imgs = $name[0];
            for ($i = 1; $i < count($name); $i++) {
                $imgs = $imgs . ',' . $name[$i];
            }

            // return response()->json([
            //     'message' => 'Add images to folder ' . $path . ' successfully.',
            //     'name' => $name, 'imgs' => $imgs
            // ]);
            return response()->json([
                'status' => 'success',
                'message' => 'Add images to folder ' . $path . ' successfully.',
                'name' => $name,
                'imgs' => $imgs
            ], 201);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Thêm ảnh thất bại', 'error' => $e->getMessage()], 500);
        }
    }
    public function removecolor_api(Request $request)
    {
        try {
            $get = $request->all();
            $path = public_path('img/product/temp');
            if (!is_null($get['imgs'])) {
                $name = explode(',', $get['imgs']);
                foreach ($name as $n) {
                    File::delete($path . '/' . $n);
                }
            }
            // return response()->json([
            //     'message' => 'Remove imgs of color successfully.'
            // ]);
            return response()->json(['status' => 'success', 'message' => 'Xóa ảnh của màu thành công'], 201);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Xóa ảnh của màu thất bại', 'error' => $e->getMessage()], 500);
        }
    }
}
