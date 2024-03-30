<?php

namespace App\Http\Controllers\user;

//use App\Models\product;
use Illuminate\Http\Request;
use App\Http\Resources\ProductImgs;
use App\Http\Resources\BrandHome;
use App\Http\Resources\Brands;
use App\Http\Resources\Products;
use App\Http\Resources\ProductsHot;
use App\Models\admin\product;
use App\Models\admin\member;
use App\Models\admin\order;
use App\Models\admin\product_order;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Arr;
use Illuminate\Database\Query\JoinClause;
use App\Models\admin\product_size_color;
use Illuminate\Support\Carbon;



/**
 * Store a newly created resource in storage.
 *
 * @return \Illuminate\Http\Response
 */
class HomeController extends Controller
{
    public function index()
    {
        // $brands = new HeaderController();
        // $brand = $brands->load();
        // $brandHome = [];
        // for ($i = 0; $i < 5; $i++) {
        //     $query = DB::select("select * from brand inner join product on brand.Brand_id = product.brand_id where Brand_name = :name", [
        //         'name' => $brand[$i]->brand_name
        //     ]);
        //     $brandHome = Arr::add($brandHome, $i, $query);
        // }
        // $products = DB::table('brand')
        //     ->join('product', 'brand.brand_id', '=', 'product.brand_id')
        //     ->leftJoin('discount', function (JoinClause $join) {
        //         $today = date("Y-m-d");
        //         $join->on('product.discount_id', '=', 'discount.discount_id')
        //             ->where('discount_end', '>=', $today)
        //             ->where('discount_start', '<=', $today);
        //     })
        //     ->where('product.product_active', '=', '1')
        //     ->select('brand.*', 'product.*', 'discount.*')
        //     ->get();

        // $product_imgs = DB::select(
        //     'select product.product_id,product_size_color.product_image from product 
        //     inner join product_size_color on product_size_color.product_id = product.product_id
        //     where product.product_active = 1
        //     GROUP by product_id,product_size_color.product_image'
        // );
        // $products_hot = DB::table('product_order')
        //     ->join('product', 'product.product_id', '=', 'product_order.product_id')
        //     ->leftJoin('discount', function (JoinClause $join) {
        //         $today = date("Y-m-d");
        //         $join->on('product.discount_id', '=', 'discount.discount_id')
        //             ->where('discount_end', '>=', $today)
        //             ->where('discount_start', '<=', $today);
        //     })
        //     ->join('order', 'order.order_id', '=', 'product_order.order_id')
        //     ->where('order_status', '=', '2')
        //     ->where('product.product_active', '=', '1')
        //     ->selectRaw('sum(product_order.quantity), product_name, product.product_id, product_price, discount_value')
        //     ->groupBy('product_name', 'product.product_id', 'product_price', 'discount_value')
        //     ->orderBy(DB::raw('sum(product_order.quantity)'), 'desc')
        //     ->limit(20)->get();
        // // $date = "2023-06-01";
        // // $label = [];
        // // $data = [];
        // // for ($i=0; $i < 7; $i++) { 
        // //     $orders = DB::select('select sum(order_value) as TongDoanhThu from order where order_status = 1 and created_date =:date',[
        // //         'date' => $date
        // //     ]);
        // //     if($orders[0]->TongDoanhThu){
        // //         $data = Arr::add($data,$i,$orders[0]->TongDoanhThu);
        // //     }
        // //     else{
        // //         $data = Arr::add($data,$i,0);
        // //     }
        // //     $d = strtotime($date);
        // //     $label = Arr::add($label,$i,date("l",$d));
        // //     $d = strtotime("+1 day",$d);
        // //     $date = date("Y-m-d",$d);
        // // }


        return view('user/homepage');

        // return response()->json([
        //     //"product_imgs" => new ProductImgs($product_imgs),
        //     //"products"=>new Products($products),
        //     "brandHome" => new BrandHome($brandHome),
        //     //"brands" => new Brands($brands->load()),
        //     //"products_hot" => new ProductsHot($products_hot)
        // ]);

    }




    //API 



    public function index_api()
    {
        try {
            $brands = new HeaderController();
            $brand = $brands->load();
            $brandHome = [];
            for ($i = 0; $i < 5; $i++) {
                $query = DB::select("select * from brand inner join product on brand.Brand_id = product.brand_id where Brand_name = :name", [
                    'name' => $brand[$i]->brand_name
                ]);
                $brandHome = Arr::add($brandHome, $i, $query);
            }
            $products = DB::table('brand')
                ->join('product', 'brand.brand_id', '=', 'product.brand_id')
                ->leftJoin('discount', function (JoinClause $join) {
                    $today = date("Y-m-d");
                    $join->on('product.discount_id', '=', 'discount.discount_id')
                        ->where('discount_end', '>=', $today)
                        ->where('discount_start', '<=', $today);
                })
                ->where('product.product_active', '=', '1')
                ->select('brand.*', 'product.*', 'discount.*')
                ->get();

            $product_imgs = DB::select(
                'select product.product_id,product_size_color.product_image from product 
                inner join product_size_color on product_size_color.product_id = product.product_id
                where product.product_active = 1
                GROUP by product_id,product_size_color.product_image'
            );
            $products_hot = DB::table('product_order')
                ->join('product', 'product.product_id', '=', 'product_order.product_id')
                ->leftJoin('discount', function (JoinClause $join) {
                    $today = date("Y-m-d");
                    $join->on('product.discount_id', '=', 'discount.discount_id')
                        ->where('discount_end', '>=', $today)
                        ->where('discount_start', '<=', $today);
                })
                ->join('order', 'order.order_id', '=', 'product_order.order_id')
                ->where('order_status', '=', '2')
                ->where('product.product_active', '=', '1')
                ->selectRaw('sum(product_order.quantity), product_name, product.product_id, product_price, discount_value')
                ->groupBy('product_name', 'product.product_id', 'product_price', 'discount_value')
                ->orderBy(DB::raw('sum(product_order.quantity)'), 'desc')
                ->limit(20)->get();
            // $date = "2023-06-01";
            // $label = [];
            // $data = [];
            // for ($i=0; $i < 7; $i++) { 
            //     $orders = DB::select('select sum(order_value) as TongDoanhThu from order where order_status = 1 and created_date =:date',[
            //         'date' => $date
            //     ]);
            //     if($orders[0]->TongDoanhThu){
            //         $data = Arr::add($data,$i,$orders[0]->TongDoanhThu);
            //     }
            //     else{
            //         $data = Arr::add($data,$i,0);
            //     }
            //     $d = strtotime($date);
            //     $label = Arr::add($label,$i,date("l",$d));
            //     $d = strtotime("+1 day",$d);
            //     $date = date("Y-m-d",$d);
            // }


            // return view('user/homepage')->with([
            //     'product_imgs' => $product_imgs,
            //     'products' => $products,
            //     'brandHome' => $brandHome,
            //     'brands' => $brands->load(),
            //     'products_hot' => $products_hot
            // ]);
            return response()->json([
                'status' => 'success',
                'product_imgs' => $product_imgs,
                'products' => $products,
                'brandHome' => $brandHome,
                'brands' => $brands->load(),
                'products_hot' => $products_hot
            ]);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'error' => $e->getMessage()]);
        }


        // return response()->json([
        //     //"product_imgs" => new ProductImgs($product_imgs),
        //     //"products"=>new Products($products),
        //     "brandHome" => new BrandHome($brandHome),
        //     //"brands" => new Brands($brands->load()),
        //     //"products_hot" => new ProductsHot($products_hot)
        // ]);

    }

    public function img_product_api(Request $request)
    {
        $id = $request->query('q');

        $data = DB::select("SELECT * FROM `product_size_color` WHERE product_id = $id");

        //$data = DB::select('select * from product_size_color');

        // Kiểm tra xem có dữ liệu hay không
        return response()->json(['status' => 'success', 'data' => $data]);
    }

    public function detail_product_api()
    {
        try {
            //$products = product::where('product_active','=','1')->with('discounts')->get();
            $products = product::where('product_active', '=', '1')->with(['details', 'discounts' => function ($query) {
                $query->where('discount_active', 1);
            }])->get();
            return response()->json([
                'status' => 'success',
                'products' => $products,
            ]);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'error' => $e->getMessage()]);
        }
    }

    public function search_product_api(Request $request)
    {
        try {
            $product_name = $request->query('name');
            // $products = product::with('details')->with('discounts')->get();
            $products = Product::where('product_active', '=', '1')->where('product_name', 'like', '%' . $product_name . '%')->with(['details', 'discounts' => function ($query) {
                $query->where('discount_active', 1);
            }])->get();

            return response()->json([
                'status' => 'success',
                'products' => $products,
            ]);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'error' => $e->getMessage()]);
        }
    }
    public function detail_product_id_api(Request $request)
    {
        try {
            $product_id = $request->query('id');
            // $products = product::with('details')->with('discounts')->get();
            $products = Product::where('product_active', '=', '1')->where('product_id', '=', $product_id)->with(['details', 'discounts' => function ($query) {
                $query->where('discount_active', 1);
            }])->with('brands', 'feedbacks')->get();

            return response()->json([
                'status' => 'success',
                'products' => $products,
            ]);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'error' => $e->getMessage()]);
        }
    }

    public function similar_product_api(Request $request)
    {
        try {
            $brand_id = $request->query('id');
            // $products = product::with('details')->with('discounts')->get();
            $products = Product::where('product_active', '=', '1')->where('brand_id', '=', $brand_id)->with(['details', 'discounts' => function ($query) {
                $query->where('discount_active', 1);
            }])->get();
            return response()->json([
                'status' => 'success',
                'products' => $products,
            ]);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'error' => $e->getMessage()]);
        }
    }

    public function feedback_product_api(Request $request)
    {
        try {
            $product_id = $request->query('id');
            $star = $request->query('star');

            if ($star == 0) {
                $products = Product::with(['feedbacks' => function ($query) use ($star) {
                    $query->with('member');
                }])->find($product_id);
            } else {
                $products = Product::with(['feedbacks' => function ($query) use ($star) {
                    $query->where('star', $star)->with('member');
                }])->find($product_id);
            }


            return response()->json([
                'status' => 'success',
                'products' => $products,
            ]);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'error' => $e->getMessage()]);
        }
    }

    public function count_feedback_api(Request $request)
    {
        try {
            $product_id = $request->query('id');

            $products = DB::table('feedback')
                ->selectRaw('COUNT(*) as total_feedbacks')
                ->selectRaw('SUM(CASE WHEN star = 5 THEN 1 ELSE 0 END) as star_5_feedbacks')
                ->selectRaw('SUM(CASE WHEN star = 4 THEN 1 ELSE 0 END) as star_4_feedbacks')
                ->selectRaw('SUM(CASE WHEN star = 3 THEN 1 ELSE 0 END) as star_3_feedbacks')
                ->selectRaw('SUM(CASE WHEN star = 2 THEN 1 ELSE 0 END) as star_2_feedbacks')
                ->selectRaw('SUM(CASE WHEN star = 1 THEN 1 ELSE 0 END) as star_1_feedbacks')
                ->where('product_id', $product_id)
                ->first();


            return response()->json([
                'status' => 'success',
                'products' => $products,
            ]);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'error' => $e->getMessage()]);
        }
    }

    public function user_api(Request $request)
    {
        try {
            $user_id = $request->query('id');

            $users = member::where('mem_id', $user_id)->get();

            return response()->json([
                'status' => 'success',
                'users' => $users,
            ]);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'error' => $e->getMessage()]);
        }
    }

    public function payment_store_api(Request $request)
    {
        try {
            // Tạo đơn hàng mới
            $order = new order();
            $order->created_date = DB::raw('CURRENT_TIMESTAMP()');
            $order->mem_id = $request->input('mem_id');
            $order->receiver_name = $request->input('receiver_name');
            $order->receiver_phone = $request->input('receiver_phone');
            $order->receiver_address = $request->input('receiver_address');
            $order->order_value = $request->input('total');
            $order->save();

            // // // Lấy id của đơn hàng vừa tạo
            $orderId = $order->order_id;

            $products = $request->input('products');
            $array = json_decode($products, true);
            if (is_array($array) || is_object($array)) {
                foreach ($array as $product) {
                    $orderProduct = new product_order();
                    $orderProduct->order_id = $orderId;
                    $orderProduct->product_id = $product['product_id'];
                    $orderProduct->size = $product['size'];
                    $orderProduct->color = $product['color'];
                    $orderProduct->quantity = $product['quantity'];
                    $orderProduct->sell_price = $product['price'];
                    $orderProduct->save();
                }
                return response()->json(['message' => 'Đã thêm đơn hàng thành công'], 201);
            } else {

                return response()->json(['status' => 'error', 'error' => 'Không phải mảng']);
            }
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'error' => $e->getMessage()]);
        }
    }
}
