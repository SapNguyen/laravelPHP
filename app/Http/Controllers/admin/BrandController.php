<?php

namespace App\Http\Controllers\admin;

use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Http\Controllers\Controller;

use App\Models\admin\brand;
use App\Models\admin\product;

use Illuminate\Support\Facades\File;

use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

class BrandController extends Controller
{
    public function list()
    {
        // $searchName = request('searchName');
        // $apiUrl = 'http://127.0.0.1:8000/api/admin/brands';
        // // Thực hiện yêu cầu GET đến API
        // $response = Http::get($apiUrl);

        // // Kiểm tra xem yêu cầu có thành công hay không (status code 2xx)
        // if ($response->successful()) {
        //     // Lấy dữ liệu từ phản hồi
        //     $get = $response->json();

        //     $count = brand::where('brand_active', '!=', -1)
        //         ->where('brand_name', 'like', '%' . $searchName . '%')->count('brand_id');

        //     // Xử lý dữ liệu theo nhu cầu của bạn
        //     return view('admin.brand.admin_brand_page', ['brand' => $get, 'count' => $count, 'title' => 'Brands List']);

        // } else {
        //     // Xử lý trường hợp yêu cầu không thành công
        //     return view('error-view');
        // }

        $searchName = request('searchName');
        $page = request('page', 1);

        $response = Http::get('https://s25sneaker.000webhostapp.com/api/admin/brands', ['searchName' => $searchName, 'page' => $page]);

        // Kiểm tra nếu yêu cầu thành công (status code 200)
        if ($response->successful()) {
            // Lấy dữ liệu JSON từ phản hồi
            $responseData = $response->json();

            // Truy cập dữ liệu từ 'data' trong 'data'
            // $data = $responseData['data']['data'];

            $count = $responseData['count'];

            // Lấy dữ liệu thương hiệu từ $data['data']
            $brands = collect($responseData['data']['data']);

            // Số lượng mục trên mỗi trang
            $perPage = 7; // Hoặc bất kỳ giá trị nào bạn muốn

            // Trang hiện tại
            // $currentPage = $responseData['data']['current_page'];

            // Tạo LengthAwarePaginator
            $paginator = new LengthAwarePaginator(
                $brands,
                $responseData['count'],
                $perPage,
                $page,
                ['path' => url()->current(), 'query' => request()->query()]
            );

            // Trả về view và truyền dữ liệu vào view
            // return view('admin.brand.admin_brand_page', compact('data'));
            // dd($data, $count);
            return view('admin.brand.admin_brand_page', ['brand' => $paginator, 'count' => $count, 'title' => 'Brands List']);
        } else {
            $statusCode = $response->status();
            $errorMessage = $response->body();
        }
    }


    public function addred()
    {

        // $get = brand::where('brand_active', '!=', -1)->get();

        // return view('admin.brand.admin_brand_add', ['brand' => $get, 'title' => 'Add New Brand']);
        $response = Http::get('https://s25sneaker.000webhostapp.com/api/admin/brand/add');

        if ($response->successful()) {
            $responseData = $response->json();

            $get = $responseData['data'];

            return view('admin.brand.admin_brand_add', ['brand' => $get, 'title' => 'Add New Brand']);
        } else {
            $statusCode = $response->status();
            $errorMessage = $response->body();
        }
    }
    public function add(Request $request)
    {
        // $id = brand::max('brand_id');
        // if (isset($id)) {
        //     $id += 1;
        // } else {
        //     $id = 1;
        // }
        $totalPages = 1;
        $currentPage = 1;
        $maxBrandId = 0;
        do {
            $response = Http::get('https://s25sneaker.000webhostapp.com/api/admin/brands?page=' . $currentPage);

            if ($response->successful()) {
                $responseData = $response->json();


                $id = collect($responseData['data']['data'])->max('brand_id') + 1;
                if ($id > $maxBrandId) {
                    $maxBrandId = $id;
                }

                $currentPage++;

                $totalPages = $responseData['data']['last_page'];
            } else {
                $statusCode = $response->status();
                $errorMessage = $response->body();
                break;
            }
        } while ($currentPage <= $totalPages);
        
        $brandImagePath = "img/brand/{$id}";
        if (!File::exists($brandImagePath)) {
            File::makeDirectory($brandImagePath, 0755, true, true);
        }
        if ($request->hasFile('bLogo')) {
            // $logoImg = $request->file('bLogo')->storeAs("img/brand/{$id}", "{$id}logo.jpg", 'public');
            $request->file('bLogo')->move($brandImagePath, 'logo.jpg');
            $logoImg = 'logo.jpg';
            // $imageUrl = Storage::url($request->file('bLogo')->move($brandImagePath, 'logo.jpg'));
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

        // $b = new brand();
        // $b->brand_id = $id;
        // $b->brand_name = request('bName');
        // $b->brand_logo = $logoImg;
        // $b->brand_img = $homeImg;
        // $b->brand_des_img = $brandImg;
        // $b->brand_banner = $bannerImg;
        // $b->brand_des = request('bDes');
        // if (!isset($b->brand_des)) {
        //     $b->brand_des = "No data";
        // }
        // $b->save();

        $postData = [
            'brand_id' => $id,
            'brand_name' => request('bName'),
            'brand_logo' => $logoImg,
            'brand_img' => $homeImg,
            'brand_des_img' => $brandImg,
            'brand_banner' => $bannerImg,
            'brand_des' => request('bDes'),
        ];

        $response = Http::post('https://s25sneaker.000webhostapp.com/api/admin/brand/add', $postData);

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

    public function upload1(Request $request)
    {
        $data = $request->All();
        $path = public_path('img/brand/temp1');
        $file = new Filesystem;
        $file->cleanDirectory($path);
        $img_name = $data['img1']->getClientOriginalName();
        $data['img1']->move($path . '/', $img_name);
    }
    public function upload2(Request $request)
    {
        $data = $request->All();
        $path = public_path('img/brand/temp2');
        $file = new Filesystem;
        $file->cleanDirectory($path);
        $img_name = $data['img2']->getClientOriginalName();
        $data['img2']->move($path . '/', $img_name);
    }
    public function upload3(Request $request)
    {
        $data = $request->All();
        $path = public_path('img/brand/temp3');
        $file = new Filesystem;
        $file->cleanDirectory($path);
        $img_name = $data['img3']->getClientOriginalName();
        $data['img3']->move($path . '/', $img_name);
    }
    public function editred()
    {
        $bid = request('bid');
        $response = Http::get('https://s25sneaker.000webhostapp.com/api/admin/brand/edit', ['bid' => $bid]);

        if ($response->successful()) {
            $responseData = $response->json();

            $get = $responseData['data'];

            return view('admin.brand.admin_brand_edit', ['brand' => $get, 'title' => 'Edit Brand']);
        } else {
            $statusCode = $response->status();
            $errorMessage = $response->body();
        }
        // $get = brand::where('brand_id', request('bid'))->get();
        // return view('admin.brand.admin_brand_edit', ['brand' => $get, 'title' => 'Edit Brand']);
    }
    public function edit(Request $request)
    {
        $id = request('bid');
        $brandImagePath = "img/brand/{$id}";
        if ($request->hasFile('bLogo')) {
            if (File::exists("{$brandImagePath}/logo.jpg")) {
                File::delete("{$brandImagePath}/logo.jpg");
            }
            // $logoImg = $request->file('bLogo')->storeAs("img/brand/{$id}", "{$id}logo.jpg", 'public');
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

        // brand::where('brand_id', $id)->update([
        //     'brand_name' => request('bName'),
        //     'brand_logo' => $logoImg,
        //     'brand_img' => $homeImg,
        //     'brand_des_img' => $brandImg,
        //     'brand_banner' => $bannerImg,
        //     'brand_des' => $brand_des
        // ]);

        $postData = [
            'brand_id' => $id,
            'brand_name' => request('bName'),
            'brand_logo' => $logoImg,
            'brand_img' => $homeImg,
            'brand_des_img' => $brandImg,
            'brand_banner' => $bannerImg,
            'brand_des' => $brand_des
        ];

        $response = Http::post('https://s25sneaker.000webhostapp.com/api/admin/brand/edit', $postData);

        if ($response->successful()) {
            return to_route('a.b.list');
        } else {
            // Xử lý lỗi nếu có
            $statusCode = $response->status();
            $errorMessage = $response->body();
            return response()->json(['error' => 'Failed to post data'], $statusCode);
        }
    }
    public function delred()
    {
        $searchName = request('searchName');
        // if (isset($searchName)) {
        //     $count = brand::where('brand_active', '!=', -1)
        //         ->where('brand_name', 'like', '%' . $searchName . '%')->count('brand_id');
        //     $get = brand::where('brand_active', '!=', -1)
        //         ->where('brand_name', 'like', '%' . $searchName . '%')->paginate(7);
        // } else {
        //     $count = brand::count('brand_id');
        //     $get = brand::where('brand_active', '!=', -1)->paginate(7);
        // }
        // return view('admin.brand.admin_brand_delete', ['brand' => $get, 'count' => $count, 'title' => 'Delete Brands']);
        $page = request('page', 1);

        $response = Http::get('https://s25sneaker.000webhostapp.com/api/admin/brand/delete', ['searchName' => $searchName, 'page' => $page]);

        // Kiểm tra nếu yêu cầu thành công (status code 200)
        if ($response->successful()) {
            // Lấy dữ liệu JSON từ phản hồi
            $responseData = $response->json();

            $count = $responseData['count'];

            $brands = collect($responseData['data']['data']);

            $perPage = 7; // Hoặc bất kỳ giá trị nào bạn muốn

            // $currentPage = $responseData['data']['current_page'];

            $paginator = new LengthAwarePaginator(
                $brands,
                $responseData['count'],
                $perPage,
                $page,
                ['path' => url()->current(), 'query' => request()->query()]
            );

            // return view('admin.brand.admin_brand_page', ['brand' => $paginator, 'count' => $count, 'title' => 'Brands List']);
            return view('admin.brand.admin_brand_delete', ['brand' => $paginator, 'count' => $count, 'title' => 'Delete Brands']);
        } else {
            dd($response);
            $statusCode = $response->status();
            $errorMessage = $response->body();
        }
    }
    public function del(Request $request)
    {
        // $id = request('bid');
        // $postData = [
        //     'brand_id' => $id
        // ];
        $get = $request->all();
        $response = Http::post('https://s25sneaker.000webhostapp.com/api/admin/deleteBrand', $get);
        if ($response->successful()) {
            // return to_route('a.b.list');
        } else {
            // Xử lý lỗi nếu có
            $statusCode = $response->status();
            $errorMessage = $response->body();
            return response()->json(['error' => 'Failed to post data'], $statusCode);
        }
        // $get = $request->all();
        // brand::where('brand_id', $get['bid'])->update([
        //     'brand_name' => $get['bid'] . 'deleted',
        //     'brand_active' => -1,
        // ]);
        // product::where('brand_id', $get['bid'])->update([
        //     'product_active' => 0
        // ]);
    }
    public function activate(Request $request)
    {
        // $get = $request->all();
        // brand::where('brand_id', $get['bid'])->update([
        //     'brand_active' => 1
        // ]);
        // product::where('product_id', $get['bid'])->update([
        //     'product_active' => 1
        // ]);
        $get = $request->all();
        $response = Http::post('https://s25sneaker.000webhostapp.com/api/admin/activateBrand', $get);
        if ($response->successful()) {
            // return to_route('a.b.list');
        } else {
            // Xử lý lỗi nếu có
            $statusCode = $response->status();
            $errorMessage = $response->body();
            return response()->json(['error' => 'Failed to post data'], $statusCode);
        }
    }
    public function deactivate(Request $request)
    {
        // $get = $request->all();
        // brand::where('Brand_id', $get['bid'])->update([
        //     'brand_active' => 0
        // ]);
        // product::where('product_id', $get['bid'])->update([
        //     'product_active' => 0
        // ]);
        $get = $request->all();
        $response = Http::post('https://s25sneaker.000webhostapp.com/api/admin/deactivateBrand', $get);
        if ($response->successful()) {
            // return to_route('a.b.list');
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
            $searchName = request('searchName');
            if (isset($searchName)) {
                $count = brand::where('brand_active', '!=', -1)
                    ->where('brand_name', 'like', '%' . $searchName . '%')->count('brand_id');
                $get = brand::where('brand_active', '!=', -1)
                    ->where('brand_name', 'like', '%' . $searchName . '%')->paginate(7);
            } else {
                $count = brand::where('brand_active', '!=', -1)
                    ->where('brand_name', 'like', '%' . $searchName . '%')
                    ->count('brand_id');
                $get = brand::where('brand_active', '!=', -1)->paginate(7);
            }
            return response()->json(['status' => 'success', 'count' => $count, 'data' => $get]);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'error' => $e->getMessage()]);
        }
        // return view('admin.brand.admin_brand_page', ['brand'=>$get, 'count'=>$count, 'title'=>'Brands List']);
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



        // $b = new brand();
        // $b->brand_name = request('bName');
        // $b->brand_logo = request('bLogo');
        // if (!isset($b->brand_logo)) {
        //     $b->brand_logo = "No_image_2.png";
        // }
        // $b->brand_img = request('bHPimg');
        // if (!isset($b->brand_img)) {
        //     $b->brand_img = "No_image_2.png";
        // }
        // $b->brand_des_img = request('bBPimg');
        // if (!isset($b->brand_des_img)) {
        //     $b->brand_des_img = "No_image_2.png";
        // }
        // $b->brand_des = request('bDes');
        // if (!isset($b->brand_des)) {
        //     $b->brand_des = "No data";
        // }
        // $b->save();
        // return to_route('a.b.list');
    }

    public function editred_api()
    {
        try {
            $get = brand::where('brand_id', request('bid'))->get();
            return response()->json(['status' => 'success', 'data' => $get], 201);
            // return view('admin.brand.admin_brand_edit', ['brand' => $get, 'title' => 'Edit Brand']);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'error' => $e->getMessage()], 500);
        }
    }
    public function edit_api(Request $request)
    {

        try {
            $idb= $request->input('brand_id');
            
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
                $count = brand::where('brand_active', '!=', -1)
                    ->where('brand_name', 'like', '%' . $searchName . '%')->count('brand_id');
                $get = brand::where('brand_active', '!=', -1)
                    ->where('brand_name', 'like', '%' . $searchName . '%')->paginate(7);
            } else {
                $count = brand::count('brand_id');
                $get = brand::where('brand_active', '!=', -1)->paginate(7);
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
