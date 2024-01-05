<?php

namespace App\Http\Controllers\user;

use Faker\Core\Number;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Arr;

class PaymentController extends Controller
{
    public function loadPayment(Request $request)
    {
        if(session('login') != 'true'){
            return redirect('/login');
        }else{
            session(['payment'=>$request->choice]);
            $arr = explode("; ",$request->choice);
            $products = [];
            $total = 0;
            for ($i=0; $i < count($arr); $i++) { 
                $id = explode(",",$arr[$i])[0];
                $size = explode(",",$arr[$i])[1];
                $color = explode(",",$arr[$i])[2];
                $product = DB::select('select cart.*,discount.*,product.*,product_size_color.* from cart 
                    inner join product_size_color on product_size_color.size = cart.size and cart.product_id = product_size_color.product_id
                    inner join product on cart.product_id = product.product_id 
                    left join discount on discount.discount_id = product.discount_id
                where cart.product_id= :pid and cart.size= :size and cart.color= :color and mem_id= :uid and product.product_active = 1',[
                    'pid' => $id,
                    'size' => $size,
                    'color' => $color,
                    'uid' => session('user')
                ]);
                if(!$product){
                    return redirect('/cart');
                }
                if(!$product[0]->discount_value){
                    $product[0]->discount_value = 0;
                }
                if(strtotime($product[0]->discount_end) >= strtotime(date("Y-m-d")) && strtotime($product[0]->discount_start) <= strtotime(date("Y-m-d"))){
                    $price = $product[0]->product_price*( 1 - $product[0]->discount_value/100);
                }
                else{
                    $price = $product[0]->product_price;
                }
                $total += $price * $product[0]->amount;
                $products = Arr::add($products,$i,$product);
            }
            
            $account = DB::select('select * from member where mem_id= :uid',[
                'uid' => session('user')
            ]);
            return view('user/payment',[
                'total' => $total,
                'products' => $products,
                'account' => $account[0]
            ]); 
        }   
    }

    public function insert(Request $request)
    {
        if(session('login') != 'true'){
            return redirect('/login');
        }
        else{
            $request->validate([
                'txtHoten' => 'required',
                'txtPhone' => 'required',
                'txtDiachi' => 'required'
            ]);
            if(!session('payment')){
                return redirect('/cart');
            }
            else{
                $arr = explode("; ",session('payment'));
                $last_receipt = DB::table('receipt')->latest('receipt_id')->first();
                if($last_receipt){
                    $r_id = $last_receipt->receipt_id + 1;
                }
                else{
                    $r_id = 1;
                }
                $total = 0;
                for ($i=0; $i < count($arr); $i++) { 
                    $id = explode(",",$arr[$i])[0];
                    $size = explode(",",$arr[$i])[1];
                    $color = explode(",",$arr[$i])[2];
                    $product = DB::select('select cart.*,discount.*,product.*,product_size_color.* from cart 
                        inner join product_size_color on product_size_color.size = cart.size
                             and cart.product_id = product_size_color.product_id and cart.color = product_size_color.color
                        inner join product on cart.product_id = product.product_id 
                        left join discount on discount.discount_id = product.discount_id
                    where cart.product_id= :pid and cart.size= :size and mem_id= :uid and cart.color= :color
                        and product.product_active = 1',[
                        'pid' => $id,
                        'size' => $size,
                        'color' => $color,
                        'uid' => session('user')
                    ]);
                    if(!$product){
                        return redirect('/cart');
                    }
                    if(!$product[0]->discount_value){
                        $product[0]->discount_value = 0;
                    }
                    if(strtotime($product[0]->discount_end) >= strtotime(date("Y-m-d")) && strtotime($product[0]->discount_start) <= strtotime(date("Y-m-d"))){
                        $total += $product[0]->product_price * ( 1 - $product[0]->discount_value/100) * $product[0]->amount;
                    }
                    else{
                        $total += $product[0]->product_price * $product[0]->amount;
                    }
                    
                }
                DB::table('receipt')->updateOrInsert([
                    'receipt_id' => $r_id,
                    'receipt_value' => $total,
                    'mem_id' => session('user'),
                    'receiver_name' => $request -> txtHoten,
                    'receiver_phone' => str_replace('(+84) ',"",$request -> txtPhone),
                    'receiver_address' => $request -> txtDiachi
                ]);
                for ($i=0; $i < count($arr); $i++) { 
                    $pid = explode(",",$arr[$i])[0];
                    $size = explode(",",$arr[$i])[1];
                    $color = explode(",",$arr[$i])[2];
                    $product = DB::select('select cart.*,discount.*,product.*,product_size_color.* from cart
                        inner join product_size_color on product_size_color.size = cart.size and 
                            cart.product_id = product_size_color.product_id and cart.color = product_size_color.color
                        inner join product on cart.product_id = product.product_id 
                        left join discount on discount.discount_id = product.discount_id
                    where cart.product_id= :pid and cart.size= :size and mem_id= :uid and cart.color= :color and product.product_active = 1',[
                        'pid' => $pid,
                        'size' => $size,
                        'color' => $color,
                        'uid' => session('user')
                    ]);
                    if(!$product){
                        return redirect('/cart');
                    }
                    if (!$product[0]->discount_value) {
                        $product[0]->discount_value = 0;
                    }
                    if(strtotime($product[0]->discount_end) >= strtotime(date("Y-m-d")) && strtotime($product[0]->discount_start) <= strtotime(date("Y-m-d"))){
                        $sell_price = $product[0]->product_price * ( 1 - $product[0]->discount_value/100);
                    }
                    else{
                        $sell_price = $product[0]->product_price ;
                    }
                    DB::table('receipt_product')->updateOrInsert([
                        'receipt_id' => $r_id,
                        'size' => $size,
                        'color' => $color,
                        'product_id' => $pid,
                        'quantity' => $product[0]->amount,
                        'sell_price' => $sell_price
                    ]);
                    DB::table('cart')->where([
                        'mem_id' => session('user'),
                        'product_id' => $pid,
                        'size' => $size
                    ])->delete();
                }
                session(['payment'=>null]);
                return redirect('/account/receipt/0');
            }
        }
    }
}
