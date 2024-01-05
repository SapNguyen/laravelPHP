<?php

namespace App\Http\Controllers\user;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Arr;
use Illuminate\Database\Query\JoinClause;

class ProductController extends Controller
{
    public function detail($id) {
        $product = DB::table('product')
                ->join('brand','brand.brand_id','=','product.brand_id')
                ->leftJoin('discount',function (JoinClause $join) {
                    $today = date("Y-m-d");
                    $join->on('product.discount_id', '=', 'discount.discount_id')
                        ->where('discount_end','>=',$today)
                        ->where('discount_start','<=',$today);
                })
                ->where('product.product_active', '=', '1')
                ->where('product.product_id', '=', $id)
                ->select('*')
                ->get();
        if(!isset($product[0])){
            return view('user/product',[
                'product_deleted' => 'true'
            ]);
        }
        session([
            'prePage'=>'/products/'.$id,
            'cart' => null
        ]);
        $brands = new HeaderController();
        
        $sizes = DB::select('select size from product_size_color inner join product on product_size_color.product_id = product.product_id 
            where product.product_id= :id group by size',[
                'id' => $id
        ]);
        $colors = DB::select('select color,product_image,product.product_id from product_size_color inner join product on product_size_color.product_id = product.product_id 
            where product.product_id= :id group by color,product_image,product.product_id',[
                'id' => $id
        ]);
        $quantitys = DB::select('select * from product_size_color inner join product on product_size_color.product_id = product.product_id 
            where product.product_id= :id ',[
                'id' => $id
        ]);
        $product_imgs = DB::select('select product_size_color.product_id,product_size_color.product_image from product 
            inner join product_size_color on product_size_color.product_id = product.product_id
            where product.product_active = 1 and product.product_id= :id
            GROUP by product_id,product_size_color.product_image',[
                'id' => $id
        ]);
        $feedback = DB::select('select * from feedback 
                inner join member on member.mem_id = feedback.mem_id  
                where feedback.product_id= :id order by star desc',[
                    'id' => $id
        ]);
        if(!isset($feedback)){
            $feedback = null;
        }
        $average = 0;
        $count_star = [];
        for ($i=1; $i < 6; $i++) { 
            $arr = DB::select('select count(*) as total from feedback where star = :i and product_id= :id',[
                'i' => $i,
                'id' => $id
            ]);
            $count_star = Arr::add($count_star,$i,$arr[0]->total);
            $average += ($i * $arr[0]->total);
        }
        $total_feedback = DB::select('select count(*) as total from feedback where product_id= :id',[
            'id' => $id
        ]);
        if($average != 0){
            $average = round($average / $total_feedback[0]->total,2);
        }
        $star = explode('.',$average);
        $star_full = $star[0];
        $star_empty = explode('.',5-$average)[0];
        $star_fill = $average - $star[0];

        $products_similar = DB::table('brand')
                    ->join('product','product.brand_id','=','brand.brand_id') 
                    ->leftJoin('discount',function (JoinClause $join) {
                        $today = date("Y-m-d");
                        $join->on('product.discount_id', '=', 'discount.discount_id')
                            ->where('discount_end','>=',$today)
                            ->where('discount_start','<=',$today);
                    })
                    ->where('product.product_id','!=',$product[0]->product_id)
                    ->where('brand.brand_id','=',$product[0]->brand_id)
                    ->where('product.product_active', '=', '1')
                    ->select('product.product_id','product_name','product_price','discount_value')
                    ->limit(20)->get();
        for ($i=0; $i < count($products_similar); $i++) { 
            $img = DB::table('product_size_color')
                ->join('product','product.product_id','=','product_size_color.product_id')
                ->where('product.product_id','=',$products_similar[$i]->product_id)
                ->select('product_image')
                ->get();
            $products_similar[$i]->image = explode(',',$img[0]->product_image)[0];
        }
        return view('user/product', [
            'quantitys' => $quantitys,
            'product_imgs' => $product_imgs,
            'product' => $product[0],
            'sizes' => $sizes,
            'colors' => $colors,
            'brands' => $brands->load(),
            'feedback' => $feedback,
            'total_feedback' => $total_feedback[0]->total,
            'average' => $average,
            'star_full' => $star_full,
            'star_empty' => $star_empty,
            'star_fill' => $star_fill,
            'count_star' => $count_star,
            'products_similar' => $products_similar
        ]);
    }

}
