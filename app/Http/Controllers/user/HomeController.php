<?php

namespace App\Http\Controllers\user;

use App\Models\product;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Arr;
use Illuminate\Database\Query\JoinClause;

class HomeController extends Controller
{
    public function index(){
        $brands = new HeaderController();
        $brand = $brands->load();
        $brandHome = [];
        for ($i=0; $i < 5; $i++) { 
            $query = DB::select("select * from brand inner join product on brand.Brand_id = product.brand_id where Brand_name = :name",[
                'name' => $brand[$i]->brand_name
            ]);
            $brandHome = Arr::add($brandHome, $i, $query);
        }
        $products = DB::table('brand')
                ->join('product','brand.brand_id','=','product.brand_id')
                ->leftJoin('discount',function (JoinClause $join) {
                    $today = date("Y-m-d");
                    $join->on('product.discount_id', '=', 'discount.discount_id')
                        ->where('discount_end','>=',$today)
                        ->where('discount_start','<=',$today);
                })
                ->where('product.product_active', '=', '1')
                ->select('brand.*','product.*','discount.*')
                ->get();

        $product_imgs = DB::select('select product.product_id,product_size_color.product_image from product 
            inner join product_size_color on product_size_color.product_id = product.product_id
            where product.product_active = 1
            GROUP by product_id,product_size_color.product_image'
        );
        $products_hot = DB::table('receipt_product')
                    ->join('product','product.product_id','=','receipt_product.product_id') 
                    ->leftJoin('discount',function (JoinClause $join) {
                        $today = date("Y-m-d");
                        $join->on('product.discount_id', '=', 'discount.discount_id')
                            ->where('discount_end','>=',$today)
                            ->where('discount_start','<=',$today);
                    })
                    ->join('receipt', 'receipt.receipt_id','=','receipt_product.receipt_id')
                    ->where('receipt_status','=','2')
                    ->where('product.product_active', '=', '1')
                    ->selectRaw('sum(receipt_product.quantity), product_name, product.product_id, product_price, discount_value')
                    ->groupBy('product_name', 'product.product_id', 'product_price', 'discount_value')
                    ->orderBy(DB::raw('sum(receipt_product.quantity)'),'desc')
                    ->limit(20)->get();
        // $date = "2023-06-01";
        // $label = [];
        // $data = [];
        // for ($i=0; $i < 7; $i++) { 
        //     $receipts = DB::select('select sum(receipt_value) as TongDoanhThu from receipt where receipt_status = 1 and created_date =:date',[
        //         'date' => $date
        //     ]);
        //     if($receipts[0]->TongDoanhThu){
        //         $data = Arr::add($data,$i,$receipts[0]->TongDoanhThu);
        //     }
        //     else{
        //         $data = Arr::add($data,$i,0);
        //     }
        //     $d = strtotime($date);
        //     $label = Arr::add($label,$i,date("l",$d));
        //     $d = strtotime("+1 day",$d);
        //     $date = date("Y-m-d",$d);
        // }
        return view('user/homepage')->with([
            'product_imgs' => $product_imgs,
            'products'=>$products,
            'brandHome' => $brandHome,
            'brands' => $brands->load(),
            'products_hot' => $products_hot
        ]);
    }
}
