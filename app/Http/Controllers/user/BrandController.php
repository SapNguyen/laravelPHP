<?php

namespace App\Http\Controllers\user;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Pagination\Paginator;
use App\Models\admin\brand;
use Illuminate\Database\Query\JoinClause;

class BrandController extends Controller
{
    public function loadBrand(Request $request, $name)
    {
        Paginator::useBootstrapFour();
        $amount = 16;
        $min_price = 0;
        $checked_box = '';
        $max_price = 1000000000;
        $target = 'product.product_id';
        $type = 'asc';
        if ($request->checked_box) {
            $checked_box = $request->checked_box;
        }

        if ($request->min_price && $request->max_price) {
            $min_price = $request->min_price;
            $max_price = $request->max_price;
        }
        if ($request->display_amount) {
            if ($request->display_amount != '') {
                $amount = $request->display_amount;
            }
        }
        if ($request->sort_type) {
            $sort_type = $request->sort_type;
            switch ($sort_type) {
                case 'name_asc':
                    $target = 'product.product_name';
                    $type = 'asc';
                    break;
                case 'name_desc':
                    $target = 'product.product_name';
                    $type = 'desc';
                    break;

                case 'price_asc':
                    $target = 'product.product_price';
                    $type = 'asc';
                    break;
                case 'price_desc':
                    $target = 'product.product_price';
                    $type = 'desc';
                    break;
            }
        } else {
            $sort_type = '';
        }
        $products = DB::table('brand')
            ->join('product', 'brand.brand_id', '=', 'product.brand_id')
            ->leftJoin('discount', function (JoinClause $join) {
                $today = date("Y-m-d");
                $join->on('product.discount_id', '=', 'discount.discount_id')
                    ->where('discount_end', '>=', $today)
                    ->where('discount_start', '<=', $today);
            })
            ->where('brand_name', '=', $name)
            ->where('product.product_active', '=', '1')
            ->where(DB::raw('product.product_price * (1 - ifnull(discount_value,0)/100)'), '>=', $min_price)
            ->where(DB::raw('product.product_price * (1 - ifnull(discount_value,0)/100)'), '<=', $max_price)
            ->select('brand.*', 'product.*', 'discount.*')
            ->orderBy($target, $type)
            ->paginate($amount);

        $product_imgs = DB::select(
            'select product.product_id,product_size_color.product_image from product 
            inner join product_size_color on product_size_color.product_id = product.product_id
            where product.product_active = 1
            GROUP by product_id,product_size_color.product_image'
        );
        $brands = new HeaderController();
        return view('user/brand', [
            'product_imgs' => $product_imgs,
            'brands' => $brands->load(),
            'products' => $products,
            'name' => $name,
            'sort_type' => $sort_type,
            'amount' => $amount,
            'checked_box' => $checked_box,
            'cur_page' => $request->page
        ]);
    }
    public function promotion(Request $request)
    {
        Paginator::useBootstrapFour();
        $amount = 16;
        $min_price = 0;
        $checked_box = '';
        $max_price = 1000000000;
        $target = 'product.product_id';
        $type = 'asc';
        if ($request->checked_box) {
            $checked_box = $request->checked_box;
        }
        if ($request->min_price && $request->max_price) {
            $min_price = $request->min_price;
            $max_price = $request->max_price;
        }
        if ($request->display_amount) {
            if ($request->display_amount != '') {
                $amount = $request->display_amount;
            }
        }
        $today = date("Y-m-d");
        if ($request->sort_type) {
            $sort_type = $request->sort_type;
            switch ($sort_type) {
                case 'name_asc':
                    $target = 'product.product_name';
                    $type = 'asc';
                    break;
                case 'name_desc':
                    $target = 'product.product_name';
                    $type = 'desc';
                    break;

                case 'price_asc':
                    $target = 'product.product_price';
                    $type = 'asc';
                    break;
                case 'price_desc':
                    $target = 'product.product_price';
                    $type = 'desc';
                    break;
            }
        } else {
            $sort_type = "";
        }
        $products = DB::table('brand')
            ->join('product', 'brand.brand_id', '=', 'product.brand_id')
            ->leftJoin('discount', 'product.discount_id', '=', 'discount.discount_id')
            ->where('discount_end', '>=', $today)
            ->where('discount_start', '<=', $today)
            ->where('product.product_active', '=', '1')
            ->where(DB::raw('product.product_price * (1 - ifnull(discount_value,0)/100)'), '>=', $min_price)
            ->where(DB::raw('product.product_price * (1 - ifnull(discount_value,0)/100)'), '<=', $max_price)
            ->select('brand.*', 'product.*', 'discount.*')
            ->orderBy($target, $type)
            ->paginate($amount);

        $product_imgs = DB::select(
            'select product.product_id,product_size_color.product_image from product 
            inner join product_size_color on product_size_color.product_id = product.product_id
            where product.product_active = 1
            GROUP by product_id,product_size_color.product_image'
        );
        $brands = new HeaderController();
        return view('user/brand', [
            'product_imgs' => $product_imgs,
            'brands' => $brands->load(),
            'products' => $products,
            'sort_type' => $sort_type,
            'amount' => $amount,
            'checked_box' => $checked_box,
            'cur_page' => $request->page
        ]);
    }

    function search(Request $request)
    {
        Paginator::useBootstrapFour();
        $amount = 16;
        $checked_box = '';
        $sort_type = "";
        $request->validate([
            'search_value' => 'required'
        ]);
        $products = DB::table('brand')
            ->join('product', 'brand.brand_id', '=', 'product.brand_id')
            ->leftJoin('discount', function (JoinClause $join) {
                $today = date("Y-m-d");
                $join->on('product.discount_id', '=', 'discount.discount_id')
                    ->where('discount_end', '>=', $today)
                    ->where('discount_start', '<=', $today);
            })
            ->where('product.product_active', '=', '1')
            ->where('product.product_name', 'like', '%' . $request->search_value . '%')
            ->select('brand.*', 'product.*', 'discount.*')
            ->paginate($amount);
        $product_imgs = DB::select(
            'select product.product_id,product_size_color.product_image from product 
            inner join product_size_color on product_size_color.product_id = product.product_id
            where product.product_active = 1
            GROUP by product_id,product_size_color.product_image'
        );
        $brands = new HeaderController();
        return view('user/brand', [
            'product_imgs' => $product_imgs,
            'brands' => $brands->load(),
            'products' => $products,
            'sort_type' => $sort_type,
            'amount' => $amount,
            'checked_box' => $checked_box,
            'cur_page' => $request->page
        ]);
    }







    // API 

    public function loadBrand_api(Request $request, $name)
    {
        try {
            Paginator::useBootstrapFour();
            $amount = 16;
            $min_price = 0;
            $checked_box = '';
            $max_price = 1000000000;
            $target = 'product.product_id';
            $type = 'asc';
            if ($request->checked_box) {
                $checked_box = $request->checked_box;
            }

            if ($request->min_price && $request->max_price) {
                $min_price = $request->min_price;
                $max_price = $request->max_price;
            }
            if ($request->display_amount) {
                if ($request->display_amount != '') {
                    $amount = $request->display_amount;
                }
            }
            if ($request->sort_type) {
                $sort_type = $request->sort_type;
                switch ($sort_type) {
                    case 'name_asc':
                        $target = 'product.product_name';
                        $type = 'asc';
                        break;
                    case 'name_desc':
                        $target = 'product.product_name';
                        $type = 'desc';
                        break;

                    case 'price_asc':
                        $target = 'product.product_price';
                        $type = 'asc';
                        break;
                    case 'price_desc':
                        $target = 'product.product_price';
                        $type = 'desc';
                        break;
                }
            } else {
                $sort_type = '';
            }
            $products = DB::table('brand')
                ->join('product', 'brand.brand_id', '=', 'product.brand_id')
                ->leftJoin('discount', function (JoinClause $join) {
                    $today = date("Y-m-d");
                    $join->on('product.discount_id', '=', 'discount.discount_id')
                        ->where('discount_end', '>=', $today)
                        ->where('discount_start', '<=', $today);
                })
                ->where('brand_name', '=', $name)
                ->where('product.product_active', '=', '1')
                ->where(DB::raw('product.product_price * (1 - ifnull(discount_value,0)/100)'), '>=', $min_price)
                ->where(DB::raw('product.product_price * (1 - ifnull(discount_value,0)/100)'), '<=', $max_price)
                ->select('brand.*', 'product.*', 'discount.*')
                ->orderBy($target, $type)
                ->paginate($amount);

            $product_imgs = DB::select(
                'select product.product_id,product_size_color.product_image from product 
                inner join product_size_color on product_size_color.product_id = product.product_id
                where product.product_active = 1
                GROUP by product_id,product_size_color.product_image'
            );
            $brands = new HeaderController();
            return response()->json([
                'status' => 'success',
                'product_imgs' => $product_imgs,
                'brands' => $brands->load(),
                'products' => $products,
                'name' => $name,
                'sort_type' => $sort_type,
                'amount' => $amount,
                'checked_box' => $checked_box,
                'cur_page' => $request->page
            ], 201);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'error' => $e->getMessage()], 500);
        }
    }
    public function promotion_api(Request $request)
    {
        try {
            Paginator::useBootstrapFour();
            $amount = 16;
            $min_price = 0;
            $checked_box = '';
            $max_price = 1000000000;
            $target = 'product.product_id';
            $type = 'asc';
            if ($request->checked_box) {
                $checked_box = $request->checked_box;
            }
            if ($request->min_price && $request->max_price) {
                $min_price = $request->min_price;
                $max_price = $request->max_price;
            }
            if ($request->display_amount) {
                if ($request->display_amount != '') {
                    $amount = $request->display_amount;
                }
            }
            $today = date("Y-m-d");
            if ($request->sort_type) {
                $sort_type = $request->sort_type;
                switch ($sort_type) {
                    case 'name_asc':
                        $target = 'product.product_name';
                        $type = 'asc';
                        break;
                    case 'name_desc':
                        $target = 'product.product_name';
                        $type = 'desc';
                        break;

                    case 'price_asc':
                        $target = 'product.product_price';
                        $type = 'asc';
                        break;
                    case 'price_desc':
                        $target = 'product.product_price';
                        $type = 'desc';
                        break;
                }
            } else {
                $sort_type = "";
            }
            $products = DB::table('brand')
                ->join('product', 'brand.brand_id', '=', 'product.brand_id')
                ->leftJoin('discount', 'product.discount_id', '=', 'discount.discount_id')
                ->where('discount_end', '>=', $today)
                ->where('discount_start', '<=', $today)
                ->where('product.product_active', '=', '1')
                ->where(DB::raw('product.product_price * (1 - ifnull(discount_value,0)/100)'), '>=', $min_price)
                ->where(DB::raw('product.product_price * (1 - ifnull(discount_value,0)/100)'), '<=', $max_price)
                ->select('brand.*', 'product.*', 'discount.*')
                ->orderBy($target, $type)
                ->paginate($amount);

            $product_imgs = DB::select(
                'select product.product_id,product_size_color.product_image from product 
                inner join product_size_color on product_size_color.product_id = product.product_id
                where product.product_active = 1
                GROUP by product_id,product_size_color.product_image'
            );
            $brands = new HeaderController();
            return response()->json([
                'status' => 'success',
                'product_imgs' => $product_imgs,
                'brands' => $brands->load(),
                'products' => $products,
                'sort_type' => $sort_type,
                'amount' => $amount,
                'checked_box' => $checked_box,
                'cur_page' => $request->page
            ], 201);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'error' => $e->getMessage()], 500);
        }
    }

    function search_api(Request $request)
    {
        try {
            Paginator::useBootstrapFour();
            $amount = 16;
            $checked_box = '';
            $sort_type = "";
            $request->validate([
                'search_value' => 'required'
            ]);
            $products = DB::table('brand')
                ->join('product', 'brand.brand_id', '=', 'product.brand_id')
                ->leftJoin('discount', function (JoinClause $join) {
                    $today = date("Y-m-d");
                    $join->on('product.discount_id', '=', 'discount.discount_id')
                        ->where('discount_end', '>=', $today)
                        ->where('discount_start', '<=', $today);
                })
                ->where('product.product_active', '=', '1')
                ->where('product.product_name', 'like', '%' . $request->search_value . '%')
                ->select('brand.*', 'product.*', 'discount.*')
                ->paginate($amount);
            $product_imgs = DB::select(
                'select product.product_id,product_size_color.product_image from product 
                inner join product_size_color on product_size_color.product_id = product.product_id
                where product.product_active = 1
                GROUP by product_id,product_size_color.product_image'
            );
            $brands = new HeaderController();
            return response()->json([
                'status' => 'success',
                'product_imgs' => $product_imgs,
                'brands' => $brands->load(),
                'products' => $products,
                'sort_type' => $sort_type,
                'amount' => $amount,
                'checked_box' => $checked_box,
                'cur_page' => $request->page
            ], 201);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'error' => $e->getMessage()], 500);
        }
    }


    //USE

    //[GET] /user/banner
    public function banner_api()
    {
        try {
            $data = DB::select("SELECT brand.*, COUNT(product.product_id) as product_count FROM brand INNER JOIN product ON brand.brand_id = product.brand_id WHERE brand.brand_banner != '' AND brand.brand_active = 1 GROUP BY brand.brand_id,brand.brand_name,brand.brand_des,brand.brand_img,brand.brand_banner,brand.brand_des_img,brand.brand_logo,brand.brand_active LIMIT 5");

            return response()->json(['status' => 'success', 'data' => $data], 201);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'error' => $e->getMessage()], 500);
        }
    }

    //[GET] /user/home 
    public function home_api()
    {
        try {

            $data = DB::select("SELECT brand.*, COUNT(product.product_id) as product_count FROM brand INNER JOIN product ON brand.brand_id = product.brand_id WHERE brand.brand_img != '' AND brand.brand_active = 1 GROUP BY brand.brand_id,brand.brand_name,brand.brand_des,brand.brand_img,brand.brand_banner,brand.brand_des_img,brand.brand_logo,brand.brand_active LIMIT 5");

            return response()->json(['status' => 'success', 'data' => $data], 201);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'error' => $e->getMessage()], 500);
        }
    }

    //[GET] /user/logo
    public function logo_api()
    {
        try {

            $data = DB::select("select * from brand where brand_logo != '' and brand_active=1 ");

            return response()->json(['status' => 'success', 'data' => $data], 201);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'error' => $e->getMessage()], 500);
        }
    }

    //[GET] /brands
    public function brand_api()
    {
        try {
            $data = DB::select("select * from brand where brand_active = 1 LIMIT 8");

            return response()->json(['status' => 'success', 'data' => $data], 201);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'error' => $e->getMessage()], 500);
        }
    }

    //[GET] /detail/brand

    public function detail_brand_api(Request $request)
    {
        try {
            $brand_name = $request->input('brand_name');
            if ($brand_name != "All") {
                $data = brand::where('brand_active', '=', 1)
                    ->where('brand_name', '=', $brand_name)->get();

                // return response()->json($data);
                return response()->json(['status' => 'success', 'data' => $data], 201);
            } else {
                $data = brand::where('brand_active', '=', 1)
                    ->where('brand_id', '=', 8)->get();

                // return response()->json($data);
                return response()->json(['status' => 'success', 'data' => $data], 201);
            }
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'error' => $e->getMessage()], 500);
        }
    }
}
