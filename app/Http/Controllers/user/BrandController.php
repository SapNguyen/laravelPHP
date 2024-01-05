<?php

namespace App\Http\Controllers\user;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Pagination\Paginator;
use Illuminate\Database\Query\JoinClause;

class BrandController extends Controller
{
    public function loadBrand(Request $request,$name)
    {
        Paginator::useBootstrapFour();
        $amount = 16;
        $min_price = 0;
        $checked_box = '';
        $max_price = 1000000000;
        $target = 'product.product_id';
        $type = 'asc';
        if($request->checked_box){
            $checked_box = $request->checked_box;
        }
        
        if($request->min_price && $request->max_price){
            $min_price = $request->min_price;
            $max_price = $request->max_price;
        }
        if($request->display_amount){
            if($request->display_amount != ''){
                $amount = $request->display_amount;
            }
        }
        if($request->sort_type){
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
        }
        else{
            $sort_type = '';
        }
        $products = DB::table('brand')
                ->join('product','brand.brand_id','=','product.brand_id')
                ->leftJoin('discount',function (JoinClause $join) {
                    $today = date("Y-m-d");
                    $join->on('product.discount_id', '=', 'discount.discount_id')
                        ->where('discount_end','>=',$today)
                           ->where('discount_start','<=',$today);
                })
                ->where('brand_name','=',$name)
                ->where('product.product_active', '=', '1')
                ->where(DB::raw('product.product_price * (1 - ifnull(discount_value,0)/100)'), '>=' , $min_price)
                ->where(DB::raw('product.product_price * (1 - ifnull(discount_value,0)/100)'), '<=' , $max_price)
                ->select('brand.*','product.*','discount.*')
                ->orderBy($target,$type)
                ->paginate($amount);

        $product_imgs = DB::select('select product.product_id,product_size_color.product_image from product 
            inner join product_size_color on product_size_color.product_id = product.product_id
            where product.product_active = 1
            GROUP by product_id,product_size_color.product_image'
        );
        $brands = new HeaderController();
        return view('user/brand',[
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
        if($request->checked_box){
            $checked_box = $request->checked_box;
        }
        if($request->min_price && $request->max_price){
            $min_price = $request->min_price;
            $max_price = $request->max_price;
        }
        if($request->display_amount){
            if($request->display_amount != ''){
                $amount = $request->display_amount;
            }
        }
        $today = date("Y-m-d");
        if($request->sort_type){
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
            
        }
        else{
            $sort_type = "";
        }
        $products = DB::table('brand')
                ->join('product','brand.brand_id','=','product.brand_id')
                ->leftJoin('discount','product.discount_id','=','discount.discount_id')
                ->where('discount_end','>=',$today)
                ->where('discount_start','<=',$today)
                ->where('product.product_active', '=', '1')
                ->where(DB::raw('product.product_price * (1 - ifnull(discount_value,0)/100)'), '>=' , $min_price)
                ->where(DB::raw('product.product_price * (1 - ifnull(discount_value,0)/100)'), '<=' , $max_price)
                ->select('brand.*','product.*','discount.*')
                ->orderBy($target,$type)
                ->paginate($amount);

        $product_imgs = DB::select('select product.product_id,product_size_color.product_image from product 
            inner join product_size_color on product_size_color.product_id = product.product_id
            where product.product_active = 1
            GROUP by product_id,product_size_color.product_image'
        );
        $brands = new HeaderController();
        return view('user/brand',[
            'product_imgs' => $product_imgs,
            'brands' => $brands->load(),
            'products' => $products,
            'sort_type' => $sort_type ,
            'amount' => $amount,
            'checked_box' => $checked_box,
            'cur_page' => $request->page
        ]);
        
    }

    function search(Request $request){
        Paginator::useBootstrapFour();
        $amount = 16;
        $checked_box = '';
        $sort_type = "";
        $request->validate([
            'search_value' => 'required'
        ]);
        $products = DB::table('brand')
                ->join('product','brand.brand_id','=','product.brand_id')
                ->leftJoin('discount',function (JoinClause $join) {
                    $today = date("Y-m-d");
                    $join->on('product.discount_id', '=', 'discount.discount_id')
                        ->where('discount_end','>=',$today)
                           ->where('discount_start','<=',$today);
                })
                ->where('product.product_active', '=', '1')
                ->where('product.product_name', 'like', '%'.$request->search_value.'%')
                ->select('brand.*','product.*','discount.*')
                ->paginate($amount);
        $product_imgs = DB::select('select product.product_id,product_size_color.product_image from product 
            inner join product_size_color on product_size_color.product_id = product.product_id
            where product.product_active = 1
            GROUP by product_id,product_size_color.product_image'
        );
        $brands = new HeaderController();
        return view('user/brand',[
            'product_imgs' => $product_imgs,
            'brands' => $brands->load(),
            'products' => $products,
            'sort_type' => $sort_type ,
            'amount' => $amount,
            'checked_box' => $checked_box,
            'cur_page' => $request->page
        ]);
    }
}
