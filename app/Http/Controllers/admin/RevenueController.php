<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\admin\order;
use App\Models\admin\product_order;
use App\Models\admin\product;
use App\Models\admin\product_size_color;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Http;
use Illuminate\Validation\Rules\Exists;

use function PHPUnit\Framework\isNull;

class RevenueController extends Controller
{
    // [GET] /admin_page (use)
    public function show()
    {
        $year = order::where('order_status', 2)
            ->groupBy(order::raw('year(validated_date)'))
            ->get([
                order::raw('year(validated_date) as year'),
                order::raw('count(order_id) as nor'),
                order::raw('sum(order_value) as value')
            ]);

        $month = order::where('order_status', 2)
            ->groupBy(order::raw('year(validated_date)'))
            ->groupBy(order::raw('month(validated_date)'))
            ->get([
                order::raw('year(validated_date) as year'),
                order::raw('month(validated_date) as month'),
                order::raw('count(order_id) as nor'),
                order::raw('sum(order_value) as value')
            ]);

        $day = order::where('order_status', 2)
            ->groupBy(order::raw('year(validated_date)'))
            ->groupBy(order::raw('month(validated_date)'))
            ->groupBy(order::raw('day(validated_date)'))
            ->get([
                order::raw('year(validated_date) as year'),
                order::raw('month(validated_date) as month'),
                order::raw('day(validated_date) as day'),
                order::raw('count(order_id) as nor'),
                order::raw('sum(order_value) as value')
            ]);

        return view('admin.admin_revenue_page', ['year' => $year, 'month' => $month, 'day' => $day, 'title' => 'Revenue']);
    }

    //[GET] /admin_page (use)
    public function homepage()
    {
        // trend chart
        $lastmonth = new Carbon('first day of last month');
        echo $lastmonth;
        $trend_name = product_order::join('product', 'product_order.product_id', '=', 'product.product_id')
            ->join('brand', 'product.brand_id', '=', 'brand.brand_id')
            ->join('order', 'product_order.order_id', '=', 'order.order_id')
            ->whereBetween('validated_date', [$lastmonth, Carbon::now()->toDateString()])
            ->groupBy('brand.brand_name')
            ->get([
                'brand_name',
            ]);
        if (isset($trend_name[0])) {
            $trendname = '"' . $trend_name[0]->brand_name . '"';
            for ($i = 1; $i < count($trend_name); $i++) {
                $trendname = $trendname . ',"' . $trend_name[$i]->brand_name . '"';
            }
        } else {
            $trendname = '"None"';
        }
        $trend_sale = product_order::join('product', 'product_order.product_id', '=', 'product.product_id')
            ->join('brand', 'product.brand_id', '=', 'brand.brand_id')
            ->join('order', 'product_order.order_id', '=', 'order.order_id')
            ->whereBetween('validated_date', [$lastmonth, Carbon::now()->toDateString()])
            ->groupBy('brand.brand_name')
            ->get([
                product_order::raw('count(product_order.order_id) as sale')
            ]);
        if (isset($trend_sale[0])) {
            $trendsale = $trend_sale[0]->sale;
            for ($i = 1; $i < count($trend_sale); $i++) {
                $trendsale = $trendsale . ',' . $trend_sale[$i]->sale;
            }
        } else {
            $trendsale = '0';
        }

        // today sale chart
        $sale_brand = product_order::join('product', 'product_order.product_id', '=', 'product.product_id')
            ->join('brand', 'product.brand_id', '=', 'brand.brand_id')
            ->join('order', 'product_order.order_id', '=', 'order.order_id')
            ->where('validated_date', Carbon::today()->toDateString())
            ->where('order_status', 2)
            ->groupBy('brand.brand_name')
            ->get([
                'brand_name'
            ]);

        if (count($sale_brand) > 0) {
            $salebrand = '"' . $sale_brand[0]->brand_name . '"';
            for ($i = 1; $i < count($sale_brand); $i++) {
                $salebrand = $salebrand . ',"' . $sale_brand[$i]->brand_name . '"';
            }
        } else {
            $salebrand = '"None"';
        }

        $sale_quantity = product_order::join('product', 'product_order.product_id', '=', 'product.product_id')
            ->join('brand', 'product.brand_id', '=', 'brand.brand_id')
            ->join('order', 'product_order.order_id', '=', 'order.order_id')
            ->where('validated_date', Carbon::today()->toDateString())
            ->where('order_status', 2)
            ->groupBy('brand.brand_name')
            ->get([
                'brand_name',
                product_order::raw('sum(quantity) as tquan')
            ]);
        if (count($sale_quantity) > 0) {
            $salequan = $sale_quantity[0]->tquan;
            for ($i = 1; $i < count($sale_quantity); $i++) {
                $salequan = $salequan . ',' . $sale_quantity[$i]->tquan;
            }
        } else {
            $salequan = '0';
        }

        $startofmonth = Carbon::now()->startOfMonth();
        $endofmonth = Carbon::now()->endOfMonth();
        $mostsale = product_order::join('product', 'product_order.product_id', '=', 'product.product_id')
            ->join('order', 'product_order.order_id', '=', 'order.order_id')
            ->whereBetween('validated_date', [$startofmonth, $endofmonth])
            ->where('order_status', 2)
            ->groupBy('product.product_id')
            ->groupBy('product_name')
            ->orderBy(product_order::raw('sum(quantity)'), 'desc')
            ->get([
                'product.product_id',
                'product_name',
                product_order::raw('sum(quantity) as totalq')
            ])->take(10);
        for ($i = 0; $i < count($mostsale); $i++) {
            $img[$i] = product_size_color::where('product_id', $mostsale[$i]->product_id)->get()->take(1);
            $mostsale[$i]->product_image = explode(',', $img[$i][0]->product_image)[0];
        }

        return view('admin.admin_home_page', [
            'trendName' => $trendname,
            'trendSale' => $trendsale,
            'saleBrand' => $salebrand,
            'saleQuan' => $salequan,
            'topsale' => $mostsale,
            'title' => 'Admin Home Page'
        ]);
    }






    // API 

    public function show_api()
    {
        try {
            $year = order::where('order_status', 2)
                ->groupBy(order::raw('year(validated_date)'))
                ->get([
                    order::raw('year(validated_date) as year'),
                    order::raw('count(order_id) as nor'),
                    order::raw('sum(order_value) as value')
                ]);

            $month = order::where('order_status', 2)
                ->groupBy(order::raw('year(validated_date)'))
                ->groupBy(order::raw('month(validated_date)'))
                ->get([
                    order::raw('year(validated_date) as year'),
                    order::raw('month(validated_date) as month'),
                    order::raw('count(order_id) as nor'),
                    order::raw('sum(order_value) as value')
                ]);

            $day = order::where('order_status', 2)
                ->groupBy(order::raw('year(validated_date)'))
                ->groupBy(order::raw('month(validated_date)'))
                ->groupBy(order::raw('day(validated_date)'))
                ->get([
                    order::raw('year(validated_date) as year'),
                    order::raw('month(validated_date) as month'),
                    order::raw('day(validated_date) as day'),
                    order::raw('count(order_id) as nor'),
                    order::raw('sum(order_value) as value')
                ]);
            return response()->json(['status' => 'success', 'year' => $year, 'month' => $month, 'day' => $day], 201);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'error' => $e->getMessage()], 500);
        }
    }
    public function homepage_api()
    {
        // trend chart
        try {
            $lastmonth = new Carbon('first day of last month');
            $trend_name = product_order::join('product', 'product_order.product_id', '=', 'product.product_id')
                ->join('brand', 'product.brand_id', '=', 'brand.brand_id')
                ->join('order', 'product_order.order_id', '=', 'order.order_id')
                ->whereBetween('completion_date', [$lastmonth, Carbon::now()->toDateString()])
                ->groupBy('brand.brand_name')
                ->get([
                    'brand_name',
                ]);
            if (isset($trend_name[0])) {
                $trendname = '"' . $trend_name[0]->brand_name . '"';
                for ($i = 1; $i < count($trend_name); $i++) {
                    $trendname = $trendname . ',"' . $trend_name[$i]->brand_name . '"';
                }
            } else {
                $trendname = '"Hiện chưa có sản phẩm"';
            }
            $trend_sale = product_order::join('product', 'product_order.product_id', '=', 'product.product_id')
                ->join('brand', 'product.brand_id', '=', 'brand.brand_id')
                ->join('order', 'product_order.order_id', '=', 'order.order_id')
                ->whereBetween('completion_date', [$lastmonth, Carbon::now()->toDateString()])
                ->groupBy('brand.brand_name')
                ->get([
                    product_order::raw('count(product_order.order_id) as sale')
                ]);
            if (isset($trend_sale[0])) {
                $trendsale = $trend_sale[0]->sale;
                for ($i = 1; $i < count($trend_sale); $i++) {
                    $trendsale = $trendsale . ',' . $trend_sale[$i]->sale;
                }
            } else {
                $trendsale = '0';
            }

            // today sale chart
            $sale_brand = product_order::join('product', 'product_order.product_id', '=', 'product.product_id')
                ->join('brand', 'product.brand_id', '=', 'brand.brand_id')
                ->join('order', 'product_order.order_id', '=', 'order.order_id')
                ->where('completion_date', Carbon::today()->toDateString())
                ->where('order_status', 2)
                ->groupBy('brand.brand_name')
                ->get([
                    'brand_name'
                ]);

            if (count($sale_brand) > 0) {
                $salebrand = '"' . $sale_brand[0]->brand_name . '"';
                for ($i = 1; $i < count($sale_brand); $i++) {
                    $salebrand = $salebrand . ',"' . $sale_brand[$i]->brand_name . '"';
                }
            } else {
                $salebrand = '"Hiện chưa có sản phẩm"';
            }

            $sale_quantity = product_order::join('product', 'product_order.product_id', '=', 'product.product_id')
                ->join('brand', 'product.brand_id', '=', 'brand.brand_id')
                ->join('order', 'product_order.order_id', '=', 'order.order_id')
                ->where('completion_date', Carbon::today()->toDateString())
                ->where('order_status', 2)
                ->groupBy('brand.brand_name')
                ->get([
                    'brand_name',
                    product_order::raw('sum(quantity) as tquan')
                ]);
            if (count($sale_quantity) > 0) {
                $salequan = $sale_quantity[0]->tquan;
                for ($i = 1; $i < count($sale_quantity); $i++) {
                    $salequan = $salequan . ',' . $sale_quantity[$i]->tquan;
                }
            } else {
                $salequan = '0';
            }

            $startofmonth = Carbon::now()->startOfMonth();
            $endofmonth = Carbon::now()->endOfMonth();
            $mostsale = product_order::join('product', 'product_order.product_id', '=', 'product.product_id')
                ->join('order', 'product_order.order_id', '=', 'order.order_id')
                ->whereBetween('completion_date', [$startofmonth, $endofmonth])
                ->where('order_status', 2)
                ->groupBy('product.product_id')
                ->groupBy('product_name')
                ->orderBy(product_order::raw('sum(quantity)'), 'desc')
                ->get([
                    'product.product_id',
                    'product_name',
                    product_order::raw('sum(quantity) as totalq')
                ])->take(10);
            for ($i = 0; $i < count($mostsale); $i++) {
                $img[$i] = product_size_color::where('product_id', $mostsale[$i]->product_id)->get()->take(1);
                $mostsale[$i]->product_image = explode(',', $img[$i][0]->product_image)[0];
            }
            return response()->json([
                'status' => 'success',
                'trendName' => $trendname,
                'trendSale' => $trendsale,
                'saleBrand' => $salebrand,
                'saleQuan' => $salequan,
                'topsale' => $mostsale,
            ], 201);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'error' => $e->getMessage()], 500);
        }
    }
}
