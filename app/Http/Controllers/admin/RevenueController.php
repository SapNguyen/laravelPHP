<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\admin\receipt;
use App\Models\admin\receipt_product;
use App\Models\admin\product;
use App\Models\admin\product_size_color;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules\Exists;

use function PHPUnit\Framework\isNull;

class RevenueController extends Controller
{
    public function show(){

        $year = receipt::where('receipt_status', 2)
        ->groupBy(receipt::raw('year(validated_date)'))
        ->get([
            receipt::raw('year(validated_date) as year'),
            receipt::raw('count(receipt_id) as nor'),
            receipt::raw('sum(receipt_value) as value')
        ]);

        $month = receipt::where('receipt_status', 2)
        ->groupBy(receipt::raw('year(validated_date)'))
        ->groupBy(receipt::raw('month(validated_date)'))
        ->get([
            receipt::raw('year(validated_date) as year'),
            receipt::raw('month(validated_date) as month'),
            receipt::raw('count(receipt_id) as nor'),
            receipt::raw('sum(receipt_value) as value')
        ]);

        $day = receipt::where('receipt_status', 2)
        ->groupBy(receipt::raw('year(validated_date)'))
        ->groupBy(receipt::raw('month(validated_date)'))
        ->groupBy(receipt::raw('day(validated_date)'))
        ->get([
            receipt::raw('year(validated_date) as year'),
            receipt::raw('month(validated_date) as month'),
            receipt::raw('day(validated_date) as day'),
            receipt::raw('count(receipt_id) as nor'),
            receipt::raw('sum(receipt_value) as value')
        ]);

        return view('admin.admin_revenue_page', ['year'=>$year, 'month'=>$month, 'day'=>$day, 'title'=>'Revenue']);
    }
    public function homepage(){
    // trend chart
        $lastmonth = new Carbon('first day of last month');
        echo $lastmonth;
        $trend_name = receipt_product::join('product', 'receipt_product.product_id', '=', 'product.product_id')
        ->join('brand', 'product.brand_id', '=', 'brand.brand_id')
        ->join('receipt', 'receipt_product.receipt_id', '=', 'receipt.receipt_id')
        ->whereBetween('validated_date', [$lastmonth, Carbon::now()->toDateString()])
        ->groupBy('brand.brand_name')
        ->get([
            'brand_name',
        ]);
        if(isset($trend_name[0])){
            $trendname = '"'.$trend_name[0]->brand_name.'"';
            for($i = 1; $i < count($trend_name); $i++){
                $trendname = $trendname.',"'.$trend_name[$i]->brand_name.'"';
            }
        }
        else{
            $trendname = '"None"';
        }
        $trend_sale = receipt_product::join('product', 'receipt_product.product_id', '=', 'product.product_id')
        ->join('brand', 'product.brand_id', '=', 'brand.brand_id')
        ->join('receipt', 'receipt_product.receipt_id', '=', 'receipt.receipt_id')
        ->whereBetween('validated_date', [$lastmonth, Carbon::now()->toDateString()])
        ->groupBy('brand.brand_name')
        ->get([
            receipt_product::raw('count(receipt_product.receipt_id) as sale')
        ]);
        if(isset($trend_sale[0])){
            $trendsale = $trend_sale[0]->sale;
            for($i = 1; $i < count($trend_sale); $i++){
                $trendsale = $trendsale.','.$trend_sale[$i]->sale;
            }
        }
        else{
            $trendsale = '0';
        }
        
    // today sale chart
        $sale_brand = receipt_product::join('product', 'receipt_product.product_id', '=', 'product.product_id')
        ->join('brand', 'product.brand_id', '=', 'brand.brand_id')
        ->join('receipt', 'receipt_product.receipt_id', '=', 'receipt.receipt_id')
        ->where('validated_date', Carbon::today()->toDateString())
        ->where('receipt_status', 2)
        ->groupBy('brand.brand_name')
        ->get([
            'brand_name'
        ]);
        
        if(count($sale_brand) > 0){
            $salebrand = '"'.$sale_brand[0]->brand_name.'"';
            for($i = 1; $i < count($sale_brand); $i++){
                $salebrand = $salebrand.',"'.$sale_brand[$i]->brand_name.'"';
            }
        }
        else{
            $salebrand = '"None"';
        }
        
        $sale_quantity = receipt_product::join('product', 'receipt_product.product_id', '=', 'product.product_id')
        ->join('brand', 'product.brand_id', '=', 'brand.brand_id')
        ->join('receipt', 'receipt_product.receipt_id', '=', 'receipt.receipt_id')
        ->where('validated_date', Carbon::today()->toDateString())
        ->where('receipt_status', 2)
        ->groupBy('brand.brand_name')
        ->get([
            'brand_name',
            receipt_product::raw('sum(quantity) as tquan')
        ]);
        if(count($sale_quantity) > 0){
            $salequan = $sale_quantity[0]->tquan;
            for($i = 1; $i < count($sale_quantity); $i++){
                $salequan = $salequan.','.$sale_quantity[$i]->tquan;
            }
        }
        else{
            $salequan = '0';
        }

        $startofmonth = Carbon::now()->startOfMonth();
        $endofmonth = Carbon::now()->endOfMonth();
        $mostsale = receipt_product::join('product', 'receipt_product.product_id', '=', 'product.product_id')
        ->join('receipt', 'receipt_product.receipt_id', '=', 'receipt.receipt_id')
        ->whereBetween('validated_date', [$startofmonth, $endofmonth])
        ->where('receipt_status', 2)
        ->groupBy('product.product_id')
        ->groupBy('product_name')
        ->orderBy(receipt_product::raw('sum(quantity)'), 'desc')
        ->get([
            'product.product_id',
            'product_name',
            receipt_product::raw('sum(quantity) as totalq')
        ])->take(10);
        for($i = 0; $i < count($mostsale); $i++){
            $img[$i] = product_size_color::where('product_id', $mostsale[$i]->product_id)->get()->take(1);
            $mostsale[$i]->product_image = explode(',', $img[$i][0]->product_image)[0];
        }

        return view('admin.admin_home_page', [
            'trendName'=>$trendname,
            'trendSale'=>$trendsale,
            'saleBrand'=>$salebrand,
            'saleQuan'=>$salequan,
            'topsale'=>$mostsale,
            'title'=>'Admin Home Page'
        ]);
    }
}
