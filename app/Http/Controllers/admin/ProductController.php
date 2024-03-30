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
    public function list()
    {
        // $pq = product_size_color::groupBy('product_id')
        //     ->get([
        //         'product_id',
        //         product_size_color::raw('SUM(quantity) as quan')
        //     ]);
        $search = request('searchName');
        // if (isset($search)) {
        //     $count = product::where('product_active', '!=', -1)
        //         ->where('product_name', 'like', '%' . $search . '%')
        //         ->count('product_id');
        //     $get = product::join('brand', 'product.brand_id', '=', 'brand.brand_id')
        //         ->where('product_active', '!=', -1)
        //         ->where('product_name', 'like', '%' . $search . '%')
        //         ->paginate(1);
        // } else {
        //     $count = product::where('product_active', '!=', -1)->count('product_id');
        //     $get = product::join('brand', 'product.brand_id', '=', 'brand.brand_id')
        //         ->where('product_active', '!=', -1)->paginate(1);
        // }
        // return view('admin.product.admin_product_page', ['product' => $get, 'count' => $count, 'quan' => $pq, 'title' => 'Products List']);

        $page = request('page', 1);

        $response = Http::get('https://s25sneaker.000webhostapp.com/api/admin/products', ['searchName' => $search, 'page' => $page,]);

        // Kiểm tra nếu yêu cầu thành công (status code 200)
        if ($response->successful()) {
            // Lấy dữ liệu JSON từ phản hồi
            $responseData = $response->json();

            $count = $responseData['count'];

            $quan = $responseData['quan'];

            // Lấy dữ liệu thương hiệu từ $data['data']
            $product = collect($responseData['product']['data']);

            // $product = $responseData['product']['data'];

            // Số lượng mục trên mỗi trang
            $perPage = 7; // Hoặc bất kỳ giá trị nào bạn muốn

            // Trang hiện tại
            // $currentPage = $responseData['product']['current_page'];

            // $currentPage = Paginator::resolveCurrentPage() ?: 1; // Trang hiện tại, mặc định là 1 nếu không có trang nào được chỉ định
            // $paginator = new Paginator($product, $perPage, $currentPage);

            // Tạo LengthAwarePaginator
            $paginator = new LengthAwarePaginator(
                $product,
                $responseData['count'],
                $perPage,
                $page,
                ['path' => url()->current(), 'query' => request()->query()]
            );

            return view('admin.product.admin_product_page', ['product' => $paginator, 'count' => $count, 'quan' => $quan, 'title' => 'Products List']);
        } else {
            $statusCode = $response->status();
            $errorMessage = $response->body();
        }
    }
    public function viewred()
    {
        // $get = product::where('product_id', request('pid'))
        //     ->join('brand', 'product.brand_id', '=', 'brand.brand_id')
        //     ->where('product_active', '!=', -1)->paginate(7);
        // $get2 = product_size_color::where('product_id', request('pid'))
        //     ->groupBy('product_id')
        //     ->groupBy('color')
        //     ->groupBy('product_image')
        //     ->get([
        //         'product_id', 'color',
        //         product_size_color::raw('COUNT(size) as row'),
        //         'product_image'
        //     ]);
        // $get3 = product_size_color::where('product_id', request('pid'))->get();

        // return view('admin.product.admin_product_view', [
        //     'product' => $get, 'psc1' => $get2, 'psc2' => $get3, 'title' => 'View Product'
        // ]);
        $pid = request('pid');
        $page = request('page', 1);

        $response = Http::get('https://s25sneaker.000webhostapp.com/api/admin/product/view', ['pid' => $pid, 'page' => $page]);

        // Kiểm tra nếu yêu cầu thành công (status code 200)
        if ($response->successful()) {
            // Lấy dữ liệu JSON từ phản hồi
            $responseData = $response->json();

            $psc1 = $responseData['psc1'];

            $psc2 = $responseData['psc2'];

            $product = collect($responseData['product']['data']);

            $perPage = 7;

            // Trang hiện tại
            // $currentPage = $responseData['product']['current_page'];

            // Tạo LengthAwarePaginator
            $paginator = new LengthAwarePaginator(
                $product,
                $perPage,
                $page,
                ['path' => url()->current(), 'query' => request()->query()]
            );

            return view('admin.product.admin_product_view', [
                'product' => $paginator, 'psc1' => $psc1, 'psc2' => $psc2, 'title' => 'View Product'
            ]);
        } else {
            $statusCode = $response->status();
            $errorMessage = $response->body();
        }
    }
    public function addred()
    {
        // $brand = brand::where('brand_active', '!=', -1)
        //     ->orderBy('brand_name', 'asc')->get();
        // return view('admin.product.admin_product_add', ['brand' => $brand, 'title' => 'Add New Product']);
        $response = Http::get('https://s25sneaker.000webhostapp.com/api/admin/product/add');


        if ($response->successful()) {
            $responseData = $response->json();
            $get = $responseData['brand'];


            // return view('admin.brand.admin_brand_add', ['brand' => $get, 'title' => 'Add New Brand']);
            return view('admin.product.admin_product_add', ['brand' => $get, 'title' => 'Add New Product']);
        } else {
            $statusCode = $response->status();
            $errorMessage = $response->body();
        }
    }
    public function add(Request $request)
    {
        $get = $request->all();

        // $product = new product();
        // $product->product_name = $get['name'];
        // $product->product_material = $get['material'];
        // $product->product_des = $get['des'];
        // $product->product_price = $get['price'];
        // $product->brand_id = $get['brand'];
        // $product->save();

        // $id = product::max('product_id') + 2;
        // if (!isset($id)) {
        //     $id = 1;
        // }
        $totalPages = 1;
        $currentPage = 1;
        $maxProductId = 0;
        do {
            $response = Http::get('https://s25sneaker.000webhostapp.com/api/admin/products?page=' . $currentPage);

            if ($response->successful()) {
                $responseData = $response->json();


                $id = collect($responseData['product']['data'])->max('product_id') + 1;
                if ($id > $maxProductId) {
                    $maxProductId = $id;
                }

                $currentPage++;

                $totalPages = $responseData['product']['last_page'];

                
            } else {
                $statusCode = $response->status();
                $errorMessage = $response->body();
                break;
            }
        } while ($currentPage <= $totalPages);

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

        // return response()->json([
        //     'message' => 'Product added successfully.'
        // ]);

        if ($get['genre'] == "Unisex") {
            $genre = 2;
        } else if ($get['genre'] == "Nam") {
            $genre = 1;
        } else if ($get['genre'] == "Nữ") {
            $genre = 0;
        }

        $postData = [
            'product_id' => $id,
            'product_name' => $get['name'],
            'product_material' => $get['material'],
            'product_genre' => $genre,
            'product_des' => $get['des'],
            'product_price' => $get['price'],
            'brand_id' => $get['brand'],
            'product_updated_date' => Carbon::now()->toDateString(),
            'pcsq' => $get['pcsq']
        ];

        $response = Http::post('https://s25sneaker.000webhostapp.com/api/addProduct', $postData);

        // Kiểm tra nếu yêu cầu thành công (status code 2xx)
        if ($response->successful()) {
            // $responseData = $response->json();
            // Xử lý dữ liệu phản hồi nếu cần
            // return response()->json(['message' => 'Data posted successfully']);
            return to_route('a.b.list');
        } else {
            // Xử lý lỗi nếu có
            $statusCode = $response->status();
            $errorMessage = $response->body();
            return response()->json(['error' => 'Failed to post data'], $statusCode);
        }
    }
    public function editred()
    {
        $pid = request('pid');
        // $brand = brand::where('brand_active', '!=', -1)
        //     ->orderBy('brand_name', 'asc')->get();
        // $get = product::where('product_id', request('pid'))
        //     ->join('brand', 'product.brand_id', '=', 'brand.brand_id')
        //     ->get();
        // $get2 = product_size_color::where('product_id', request('pid'))
        //     ->groupBy('product_id')
        //     ->groupBy('color')
        //     ->groupBy('product_image')
        //     ->get([
        //         'product_id', 'color',
        //         product_size_color::raw('COUNT(size) as row'),
        //         'product_image'
        //     ]);
        // $get3 = product_size_color::where('product_id', request('pid'))->get();
        // return view('admin.product.admin_product_edit', [
        //     'product' => $get, 'psc1' => $get2, 'psc2' => $get3,
        //     'brand' => $brand, 'title' => 'Edit Product'
        // ]);

        $response = Http::get('https://s25sneaker.000webhostapp.com/api/admin/product/edit', ['pid' => $pid]);

        if ($response->successful()) {
            $responseData = $response->json();

            $get = $responseData['product'];

            $psc1 = $responseData['psc1'];

            $psc2 = $responseData['psc2'];

            $brand = $responseData['brand'];

            return view('admin.product.admin_product_edit', [
                'product' => $get, 'psc1' => $psc1, 'psc2' => $psc2,
                'brand' => $brand, 'title' => 'Edit Product'
            ]);
        } else {
            $statusCode = $response->status();
            $errorMessage = $response->body();
        }
    }
    public function update(Request $request)
    {
        $get = $request->all();

        // product::where('product_id', $get['id'])->update([
        //     'product_name' => $get['name'],
        //     'product_material' => $get['material'],
        //     'product_des' => $get['des'],
        //     'product_price' => $get['price'],
        //     'brand_id' => $get['brand'],
        //     'product_updated_date' => Carbon::now()->toDateString()
        // ]);

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


        // product_size_color::where('product_id', $get['id'])->delete();
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

        if ($get['genre'] == "Unisex") {
            $genre = 2;
        } else if ($get['genre'] == "Nam") {
            $genre = 1;
        } else if ($get['genre'] == "Nữ") {
            $genre = 0;
        }

        $postData = [
            'product_id' => $id,
            'product_name' => $get['name'],
            'product_material' => $get['material'],
            'product_genre' => $genre,
            'product_des' => $get['des'],
            'product_price' => $get['price'],
            'brand_id' => $get['brand'],
            'product_updated_date' => Carbon::now()->toDateString(),
            'pcsq' => $get['pcsq']
        ];
        $response = Http::post('https://s25sneaker.000webhostapp.com/api/updateProduct', $postData);

        if ($response->successful()) {
            return response()->json([
                'message' => 'Product updated successfully.'
            ]);
        } else {
            // Xử lý lỗi nếu có
            $statusCode = $response->status();
            $errorMessage = $response->body();
            return response()->json(['error' => 'Failed to post data'], $statusCode);
        }

        // return response()->json([
        //     'message' => 'Product updated successfully.'
        // ]);
    }
    public function delred()
    {
        // $pq = product_size_color::groupBy('product_id')
        //     ->get([
        //         'product_id',
        //         product_size_color::raw('SUM(quantity) as quan')
        //     ]);
        $search = request('searchName');
        // if (isset($search)) {
        //     $count = product::where('product_active', '!=', -1)
        //         ->where('product_name', 'like', '%' . $search . '%')
        //         ->count('product_id');
        //     $get = product::join('brand', 'product.brand_id', '=', 'brand.brand_id')
        //         ->where('product_active', '!=', -1)
        //         ->where('product_name', 'like', '%' . $search . '%')
        //         ->paginate(7);
        // } else {
        //     $count = product::where('product_active', '!=', -1)->count('product_id');
        //     $get = product::join('brand', 'product.brand_id', '=', 'brand.brand_id')
        //         ->where('product_active', '!=', -1)->paginate(7);
        // }
        // return view('admin.product.admin_product_delete', [
        //     'product' => $get, 'count' => $count, 'quan' => $pq, 'title' => 'Products List'
        // ]);
        $page = request('page', 1);

        $response = Http::get('https://s25sneaker.000webhostapp.com/api/admin/product/delete', ['searchName' => $search, 'page' => $page]);

        if ($response->successful()) {
            $responseData = $response->json();

            $count = $responseData['count'];

            $quan = $responseData['quan'];

            $product = collect($responseData['product']['data']);

            $perPage = 7;

            // $currentPage = $responseData['product']['current_page'];

            $paginator = new LengthAwarePaginator(
                $product,
                $responseData['count'],
                $perPage,
                $page,
                ['path' => url()->current(), 'query' => request()->query()]
            );


            return view('admin.product.admin_product_delete', [
                'product' => $paginator,
                'count' => $count,
                'quan' => $quan, 'title' => 'Products List'
            ]);
        } else {
            $statusCode = $response->status();
            $errorMessage = $response->body();
        }
    }
    public function delete(Request $request)
    {
        $get = $request->all();
        // product::where('product_id', $get['pid'])->update([
        //     'product_active' => -1
        // ]);

        $response = Http::post('https://s25sneaker.000webhostapp.com/api/deleteProduct', $get);
        if ($response->successful()) {
        } else {
            // Xử lý lỗi nếu có
            $statusCode = $response->status();
            $errorMessage = $response->body();
            return response()->json(['error' => 'Failed to post data'], $statusCode);
        }
    }
    public function activate(Request $request)
    {
        $get = $request->all();
        // product::where('product_id', $get['pid'])->update([
        //     'product_active' => 1
        // ]);
        $response = Http::post('https://s25sneaker.000webhostapp.com/api/activateProduct', $get);
        if ($response->successful()) {
        } else {
            // Xử lý lỗi nếu có
            $statusCode = $response->status();
            $errorMessage = $response->body();
            return response()->json(['error' => 'Failed to post data'], $statusCode);
        }
    }
    public function deactivate(Request $request)
    {
        $get = $request->all();
        // product::where('product_id', $get['pid'])->update([
        //     'product_active' => 0
        // ]);
        $response = Http::post('https://s25sneaker.000webhostapp.com/api/deactivateProduct', $get);
        if ($response->successful()) {
            // return to_route('a.b.list');
        } else {
            // Xử lý lỗi nếu có
            $statusCode = $response->status();
            $errorMessage = $response->body();
            return response()->json(['error' => 'Failed to post data'], $statusCode);
        }
    }
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
        // $response = Http::post('https://s25sneaker.000webhostapp.com/api/addPimg', $get);
        // if ($response->successful()) {
        // } else {
        //     // Xử lý lỗi nếu có
        //     $statusCode = $response->status();
        //     $errorMessage = $response->body();
        //     return response()->json(['error' => 'Failed to post data'], $statusCode);
        // }
    }
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
        // return response()->json([
        //     'message' => 'Remove imgs of color successfully.'
        // ]);
        $response = Http::post('https://s25sneaker.000webhostapp.com/api/removePcolor', $get);
        if ($response->successful()) {
            return response()->json([
                'message' => 'Remove imgs of color successfully.'
            ]);
        } else {
            // Xử lý lỗi nếu có
            $statusCode = $response->status();
            $errorMessage = $response->body();
            return response()->json(['error' => 'Failed to post data'], $statusCode);
        }
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
                    ->paginate(7);
            } else {
                $count = product::where('product_active', '!=', -1)->count('product_id');
                $get = product::join('brand', 'product.brand_id', '=', 'brand.brand_id')
                    ->where('product_active', '!=', -1)->paginate(7);
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
            $product->product_name = $get['name'];
            $product->product_material = $get['material'];
            $product->product_des = $get['des'];
            $product->product_price = $get['price'];
            $product->product_genre = $get['genre'];
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
            $get = $request->all();

            product::where('product_id', $get['id'])->update([
                'product_name' => $get['name'],
                'product_material' => $get['material'],
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
                    ->paginate(7);
            } else {
                $count = product::where('product_active', '!=', -1)->count('product_id');
                $get = product::join('brand', 'product.brand_id', '=', 'brand.brand_id')
                    ->where('product_active', '!=', -1)->paginate(7);
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
