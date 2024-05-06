<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\admin\discount;
use App\Models\admin\product;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class DiscountController extends Controller
{

    // [GET] /admin/discounts (use)
    public function list()
    {
        $searchName = request('searchName');
        if (isset($searchName)) {
            $count = discount::where('discount_active', '!=', -1)->where('discount_name', 'like', '%' . $searchName . '%')->count('discount_id');
            $get = discount::where('discount_active', '!=', -1)
                ->where('discount_name', 'like', '%' . $searchName . '%')->paginate(7);
        } else {
            $count = discount::where('discount_active', '!=', -1)->count('discount_id');
            $get = discount::where('discount_active', '!=', -1)->paginate(7);
        }
        return view(
            'admin.discount.admin_discount_page',
            [
                'discounts' => $get,
                'count' => $count,
                'title' => 'Discounts List',
                'searchName' => $searchName
            ]
        );
    }

    // [POST] /activateDiscount (use)
    public function activate(Request $request)
    {
        $get = $request->all();
        discount::where('discount_id', $get['did'])->update([
            'discount_active' => 1
        ]);
    }

    // [POST] /deactivateDiscount (use)
    public function deactivate(Request $request)
    {
        $get = $request->all();
        discount::where('discount_id', $get['did'])->update([
            'discount_active' => 0
        ]);
    }

    // [GET] /admin/discount/add (use)
    public function addred()
    {
        $id = DB::table('discount')->count('discount_id') + 1;
        $get = discount::where('discount_active', '!=', -1)->get();
        return view('admin.discount.admin_discount_add', [
            'title' => 'Add New Discount',
            'id' => $id,
            'discount' => $get
        ]);
    }

    //[POST] /admin/discount/add (use)
    public function add()
    {
        $d = new discount();
        $d->discount_id = request('txtid');
        $d->discount_name = request('txtname');
        $d->discount_start = request('date-start');
        $d->discount_end = request('date-end');
        $d->discount_value = request('txtvalue');
        $d->save();
        $id = DB::table('discount')->count('discount_id') + 1;

        return to_route('a.d.list');
    }

    // [GET] /admin/discount/view (use)
    public function viewred()
    {
        Paginator::useBootstrapFive();
        $searchName = request('searchName');
        $discount =  request('discount_id');

        if (isset($searchName)) {
            $count = product::where('discount_id', NULL)->where('product_active', 1)->where('product_name', 'like', '%' . $searchName . '%')->count('product_id');

            // $get = product::where('discount_id', NULL)
            //     ->where('product_name', 'like', '%' . $searchName . '%')
            //     ->paginate(8, ['*'], 'get');

            $get = product::where('discount_id', NULL)
                ->where('product_active', 1)
                ->where('product_name', 'like', '%' . $searchName . '%')
                ->paginate(8, ['*'], 'get');

            foreach ($get as $product) {
                $img = DB::table('product')
                    ->join('product_size_color', 'product_size_color.product_id', '=', 'product.product_id')
                    ->select('product.product_id', 'product_size_color.product_image')
                    ->where('product.product_active', 1)
                    ->where('product.product_id', $product->product_id)
                    ->groupBy('product.product_id', 'product_size_color.product_image')
                    ->first();

                if ($img) {
                    $product->product_image = $img->product_image;
                } else {
                    $product->product_image = ''; // Hoặc giá trị mặc định khác nếu không có hình ảnh
                }
            }
        } else {
            $count = product::where('discount_id', NULL)->where('product_active', 1)->count('product_id');

            $get = product::where('discount_id', NULL)
                ->where('product_active', 1)
                ->paginate(8, ['*'], 'get');

            foreach ($get as $product) {
                $img = DB::table('product')
                    ->join('product_size_color', 'product_size_color.product_id', '=', 'product.product_id')
                    ->select('product.product_id', 'product_size_color.product_image')
                    ->where('product.product_active', 1)
                    ->where('product.product_id', $product->product_id)
                    ->groupBy('product.product_id', 'product_size_color.product_image')
                    ->first();

                if ($img) {
                    $product->product_image = $img->product_image;
                } else {
                    $product->product_image = ''; // Hoặc giá trị mặc định khác nếu không có hình ảnh
                }
            }
        }
        $searchNamed = request('searchNamed');
        if (isset($searchNamed)) {
            $counted = product::where('discount_id',  '=', $discount)->where('product_active', 1)->where('product_name', 'like', '%' . $searchNamed . '%')->count('product_id');

            $geted = product::where('discount_id', $discount)
                ->where('product_active', 1)
                ->where('product_name', 'like', '%' . $searchNamed . '%')
                ->paginate(8, ['*'], 'get');

            foreach ($geted as $product) {
                $img = DB::table('product')
                    ->join('product_size_color', 'product_size_color.product_id', '=', 'product.product_id')
                    ->select('product.product_id', 'product_size_color.product_image')
                    ->where('product.product_active', 1)
                    ->where('product.product_id', $product->product_id)
                    ->groupBy('product.product_id', 'product_size_color.product_image')
                    ->first();

                if ($img) {
                    $product->product_image = $img->product_image;
                } else {
                    $product->product_image = '';
                }
            }
        } else {
            $counted = product::where('discount_id',  '=', $discount)
                ->where('product_active', 1)
                ->count('product_id');
            $geted = product::where('discount_id', $discount)
                ->where('product_active', 1)
                ->paginate(8, ['*'], 'get');

            foreach ($geted as $product) {
                $img = DB::table('product')
                    ->join('product_size_color', 'product_size_color.product_id', '=', 'product.product_id')
                    ->select('product.product_id', 'product_size_color.product_image')
                    ->where('product.product_active', 1)
                    ->where('product.product_id', $product->product_id)
                    ->groupBy('product.product_id', 'product_size_color.product_image')
                    ->first();

                if ($img) {
                    $product->product_image = $img->product_image;
                } else {
                    $product->product_image = '';
                }
            }
        }
        return view('admin.discount.admin_discount_view', [
            'count' => $count,
            'products' => $get,
            'countd' => $counted,
            'product_eds' => $geted,
            'discountes' => $discount,
            'discount_id' => $discount,
            'title' => 'Discount\'s detail',
            'searchName' => $searchName,
            'searchNamed' => $searchNamed
        ]);
    }

    //[POST] /addProductDiscount (use)
    public function addproduct(Request $request)
    {
        $get = $request->all();
        product::where('product_id', $get['did'])->update([
            'discount_id' => $get['diid']
        ]);
    }

    //[POST] /subProductDiscount (use)
    public function subproduct(Request $request)
    {
        $get = $request->all();
        product::where('product_id', $get['did'])->update([
            'discount_id' => NULL
        ]);
    }

    //[GET] /admin/discount/edit (use)
    public function editred()
    {
        $get = discount::where('discount_id', request('did'))->get();
        return view('admin.discount.admin_discount_edit', ['discount' => $get, 'title' => 'Edit Discount']);
    }

    //[POST] /admin/discount/edit (use)
    public function edit()
    {
        $id = request('did');


        $discount_name = request('txtname');
        if (!isset($discount_name)) {
            $discount_name = "Discount no name";
        }
        $discount_start = request('date-start');
        if (!isset($discount_start)) {
            $discount_start = "Discount no date start";
        }
        $discount_end = request('date-end');
        if (!isset($discount_end)) {
            $discount_end = "Discount no date end";
        }
        $discount_value = request('txtvalue');
        if (!isset($discount_value)) {
            $discount_value = "Discount no value";
        }

        discount::where('discount_id', $id)->update([
            'discount_name' => $discount_name,
            'discount_start' => $discount_start,
            'discount_end' => $discount_end,
            'discount_value' => $discount_value,
        ]);

        return to_route('a.d.list');
    }

    //[GET] /admin/discount/delete (use)

    public function delred()
    {
        $searchName = request('searchName');
        if (isset($searchName)) {
            $count = discount::where('discount_active', '!=', -1)->where('discount_name', 'like', '%' . $searchName . '%')->count('discount_id');
            $get = discount::where('discount_active', '!=', -1)
                ->where('discount_name', 'like', '%' . $searchName . '%')->paginate(7);
        } else {
            $count = discount::where('discount_active', '!=', -1)->count('discount_id');
            $get = discount::where('discount_active', '!=', -1)->paginate(7);
        }
        return view('admin.discount.admin_discount_delete', ['discounts' => $get, 'count' => $count, 'title' => 'Delete Discounts', 'searchName' => $searchName]);
    }

    //[POST] /deleteDiscount (use)
    public function del(Request $request)
    {
        $get = $request->all();
        Discount::where('discount_id', $get['did'])
            ->update([
                'discount_active' => -1,
            ]);

        Product::where('discount_id', $get['did'])
            ->update([
                'discount_id' => NULL,
            ]);
    }






    // API

    public function list_api()
    {
        try {
            $searchName = request('searchName');
            if (isset($searchName)) {
                $count = discount::where('discount_active', '!=', -1)->where('discount_name', 'like', '%' . $searchName . '%')->count('discount_id');
                $get = discount::where('discount_active', '!=', -1)
                    ->where('discount_name', 'like', '%' . $searchName . '%')->paginate(8);
            } else {
                $count = discount::where('discount_active', '!=', -1)->count('discount_id');
                $get = discount::where('discount_active', '!=', -1)->paginate(8);
            }
            return response()->json(['status' => 'success', 'searchName' => $searchName, 'count' => $count, 'data' => $get]);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'error' => $e->getMessage()]);
        }
    }

    public function activate_api(Request $request)
    {
        try {
            $get = $request->all();
            discount::where('discount_id', $get['did'])->update([
                'discount_active' => 1
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
            discount::where('discount_id', $get['did'])->update([
                'discount_active' => 0
            ]);
            return response()->json(['status' => 'success', 'message' => 'Deactivate thành công'], 201);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Deactivate thất bại', 'error' => $e->getMessage()], 500);
        }
    }
    public function addred_api()
    {
        try {
            $id = DB::table('discount')->count('discount_id') + 1;
            $get = discount::where('discount_active', '!=', -1)->get();
            return response()->json(['status' => 'success', 'id' => $id, 'data' => $get]);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'error' => $e->getMessage()]);
        }
    }
    public function add_api(Request $request)
    {
        try {
            $request->validate([
                'discount_name' => 'required|string',
                'discount_start' => 'required|date',
                'discount_end' => 'required|date',
                'discount_value' => 'required|numeric',
            ]);

            $d = new discount();
            $d->discount_id = $request->input('discount_id');
            $d->discount_name = $request->input('discount_name');
            $d->discount_start = $request->input('discount_start');
            $d->discount_end = $request->input('discount_end');
            $d->discount_value = $request->input('discount_value');
            $d->save();
            $id = DB::table('discount')->count('discount_id') + 1;
            return response()->json(['status' => 'success', 'message' => 'Thêm giảm giá thành công!', 'data' => $d], 201);

            // return to_route('a.d.list');
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Thêm giảm giá thất bại', 'error' => $e->getMessage()], 500);
        }
    }
    public function viewred_api()
    {
        try {
            Paginator::useBootstrapFive();
            $searchName = request('searchName');
            $discount =  request('discount_id');

            if (isset($searchName)) {
                $count = product::where('discount_id', NULL)->where('product_active', 1)->where('product_name', 'like', '%' . $searchName . '%')->count('product_id');

                // $get = product::where('discount_id', NULL)
                //     ->where('product_name', 'like', '%' . $searchName . '%')
                //     ->paginate(7, ['*'], 'get');
                // for ($i = 0; $i < count($get); $i++) {
                //     $img = DB::select('select product.product_id,product_size_color.product_image from product 
                //     inner join product_size_color on product_size_color.product_id = product.product_id
                //     where product.product_active = 1 and product.product_id = :pid
                //     GROUP by product_id,product_size_color.product_image', [
                //         'pid' => $get[$i]->product_id
                //     ]);
                //     //$get[$i]->product_image = $img[0]->product_image;
                // }
                $get = product::where('discount_id', NULL)
                    ->where('product_active', 1)
                    ->where('product_name', 'like', '%' . $searchName . '%')
                    ->paginate(8, ['*'], 'get');

                foreach ($get as $product) {
                    $img = DB::table('product')
                        ->join('product_size_color', 'product_size_color.product_id', '=', 'product.product_id')
                        ->select('product.product_id', 'product_size_color.product_image')
                        ->where('product.product_active', 1)
                        ->where('product.product_id', $product->product_id)
                        ->groupBy('product.product_id', 'product_size_color.product_image')
                        ->first();

                    if ($img) {
                        $product->product_image = $img->product_image;
                    } else {
                        $product->product_image = ''; // Hoặc giá trị mặc định khác nếu không có hình ảnh
                    }
                }
            } else {
                $count = product::join('product_size_color', 'product.product_id', '=', 'product_size_color.product_id')
                    ->where('product_active', 1)
                    ->where('discount_id', NULL)
                    ->count('product.product_id');
                // $get = product::where('discount_id', NULL)
                //     ->paginate(7, ['*'], 'get');
                // for ($i = 0; $i < count($get); $i++) {
                //     $img = DB::select('select product.product_id,product_size_color.product_image from product 
                //     inner join product_size_color on product_size_color.product_id = product.product_id
                //     where product.product_active = 1 and product.product_id = :pid
                //     GROUP by product_id,product_size_color.product_image', [
                //         'pid' => $get[$i]->product_id
                //     ]);
                //     //$get[$i]->product_image = $img[0]->product_image;
                // }
                $get = product::where('discount_id', NULL)
                    ->where('product_active', 1)
                    ->paginate(8, ['*'], 'get');

                foreach ($get as $product) {
                    $img = DB::table('product')
                        ->join('product_size_color', 'product_size_color.product_id', '=', 'product.product_id')
                        ->select('product.product_id', 'product_size_color.product_image')
                        ->where('product.product_active', 1)
                        ->where('product.product_id', $product->product_id)
                        ->groupBy('product.product_id', 'product_size_color.product_image')
                        ->first();

                    if ($img) {
                        $product->product_image = $img->product_image;
                    } else {
                        $product->product_image = ''; // Hoặc giá trị mặc định khác nếu không có hình ảnh
                    }
                }
            }
            $searchNamed = request('searchNamed');
            if (isset($searchNamed)) {
                $counted = product::where('discount_id',  '=', $discount)->where('product_active', 1)->where('product_name', 'like', '%' . $searchNamed . '%')->count('product_id');
                // $geted = product::where('product_name', 'like', '%' . $searchNamed . '%')
                //     ->where('discount_id', '=', $discount)
                //     ->paginate(7, ['*'], 'geted');
                // for ($i = 0; $i < count($geted); $i++) {
                //     $img = DB::select('select product.product_id,product_size_color.product_image from product 
                //     inner join product_size_color on product_size_color.product_id = product.product_id
                //     where product.product_active = 1 and product.product_id = :pid
                //     GROUP by product_id,product_size_color.product_image', [
                //         'pid' => $geted[$i]->product_id
                //     ]);
                //     //$geted[$i]->product_image = $img[0]->product_image;
                // }
                $geted = product::where('discount_id', $discount)
                    ->where('product_active', 1)
                    ->where('product_name', 'like', '%' . $searchNamed . '%')
                    ->paginate(8, ['*'], 'get');

                foreach ($geted as $product) {
                    $img = DB::table('product')
                        ->join('product_size_color', 'product_size_color.product_id', '=', 'product.product_id')
                        ->select('product.product_id', 'product_size_color.product_image')
                        ->where('product.product_active', 1)
                        ->where('product.product_id', $product->product_id)
                        ->groupBy('product.product_id', 'product_size_color.product_image')
                        ->first();

                    if ($img) {
                        $product->product_image = $img->product_image;
                    } else {
                        $product->product_image = '';
                    }
                }
            } else {
                $counted = product::where('discount_id',  '=', $discount)
                    ->where('product_active', 1)
                    ->count('product_id');
                // $geted = product::where('discount_id',  '=', $discount)
                //     ->paginate(7, ['*'], 'geted');
                // for ($i = 0; $i < count($geted); $i++) {
                //     $img = DB::select('select product.product_id,product_size_color.product_image from product 
                //         inner join product_size_color on product_size_color.product_id = product.product_id
                //         where product.product_active = 1 and product.product_id = :pid
                //         GROUP by product_id,product_size_color.product_image', [
                //         'pid' => $geted[$i]->product_id
                //     ]);
                //     //$geted[$i]->product_image = $img[0]->product_image;
                // }
                $geted = product::where('discount_id', $discount)
                    ->where('product_active', 1)
                    ->paginate(8, ['*'], 'get');

                foreach ($geted as $product) {
                    $img = DB::table('product')
                        ->join('product_size_color', 'product_size_color.product_id', '=', 'product.product_id')
                        ->select('product.product_id', 'product_size_color.product_image')
                        ->where('product.product_active', 1)
                        ->where('product.product_id', $product->product_id)
                        ->groupBy('product.product_id', 'product_size_color.product_image')
                        ->first();

                    if ($img) {
                        $product->product_image = $img->product_image;
                    } else {
                        $product->product_image = '';
                    }
                }
            }
            return response()->json([
                'status' => 'success',
                'count' => $count,
                'products' => $get,
                'countd' => $counted,
                'product_eds' => $geted,
                'discountes' => $discount,
                'discount_id' => $discount, 'searchName' => $searchName,
                'searchNamed' => $searchNamed
            ]);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'error' => $e->getMessage()]);
        }
    }
    public function addproduct_api(Request $request)
    {
        try {
            $get = $request->all();
            product::where('product_id', $get['did'])->update([
                'discount_id' => $get['diid']
            ]);
            return response()->json(['status' => 'success', 'message' => 'Thêm sản phẩm thành công']);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'error' => $e->getMessage()]);
        }
    }
    public function subproduct_api(Request $request)
    {
        try {
            $get = $request->all();
            product::where('product_id', $get['did'])->update([
                'discount_id' => NULL
            ]);
            return response()->json(['status' => 'success', 'message' => 'Xóa sản phẩm thành công']);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'error' => $e->getMessage()]);
        }
    }
    public function editred_api()
    {
        try {
            $get = discount::where('discount_id', request('did'))->get();
            return response()->json(['status' => 'success', 'data' => $get], 201);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'error' => $e->getMessage()], 500);
        }
    }
    public function edit_api(Request $request)
    {
        // $id = request('did');

        try {
            $id = $request->input('discount_id');
            $request->validate([
                'discount_name' => 'required|string',
                'discount_start' => 'required|date',
                'discount_end' => 'required|date',
                'discount_value' => 'required|numeric',
            ]);

            // $discount_name = request('txtname');
            // $discount_start = request('date-start');
            // $discount_end = request('date-end');
            // $discount_value = request('txtvalue');

            discount::where('discount_id', $id)->update([
                'discount_name' => $request->input('discount_name'),
                'discount_start' => $request->input('discount_start'),
                'discount_end' => $request->input('discount_end'),
                'discount_value' => $request->input('discount_value'),
            ]);

            return response()->json(['status' => 'success', 'message' => 'Sửa giảm giá thành công!'], 201);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Sửa giảm giá thất bại', 'error' => $e->getMessage()], 500);
        }
    }
    public function delred_api()
    {
        $searchName = request('searchName');
        try {
            if (isset($searchName)) {
                $count = discount::where('discount_active', '!=', -1)->where('discount_name', 'like', '%' . $searchName . '%')->count('discount_id');
                $get = discount::where('discount_active', '!=', -1)
                    ->where('discount_name', 'like', '%' . $searchName . '%')->paginate(8);
            } else {
                $count = discount::where('discount_active', '!=', -1)->count('discount_id');
                $get = discount::where('discount_active', '!=', -1)->paginate(7);
            }
            return response()->json(['status' => 'success', 'count' => $count, 'searchName' => $searchName, 'data' => $get], 201);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'error' => $e->getMessage()], 500);
        }
    }
    public function del_api(Request $request)
    {
        try {
            $get = $request->all();
            //     DB::select(
            //         "update discount inner join product on discount.discount_id = product.discount_id 
            //     set discount_active = -1,
            //     product.discount_id = NULL
            //     where discount.discount_id = :discount_id
            // ",
            //         [
            //             'discount_id' => $get['did']
            //         ]
            //     );
            Discount::where('discount_id', $get['did'])
                ->update([
                    'discount_active' => -1,
                ]);

            Product::where('discount_id', $get['did'])
                ->update([
                    'discount_id' => NULL,
                ]);
            return response()->json(['status' => 'success', 'message' => 'Xóa thành công'], 201);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Xóa thất bại', 'error' => $e->getMessage()], 500);
        }
    }
}
