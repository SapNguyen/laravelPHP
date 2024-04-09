<?php

namespace App\Http\Controllers\user;

use App\Models\admin\cart;
use App\Models\admin\order;
use App\Models\admin\product;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Arr;

class CartController extends Controller
{
    public function payment(Request $request, $pid)
    {
        session(['prePage' => '/cart/' . $pid]);
        if (session('login') == 'false') {
            session(['cart' => $request->size . ',' . $request->color . ',' . $request->amount]);
            return redirect('/login');
        } else {
            if (session('cart') == null) {
                $request->validate([
                    'size' => 'required',
                    'color' => 'required',
                    'amount' => 'required'
                ]);
                $size = $request->size;
                $color = $request->color;
                $amount = $request->amount;
            } else {
                $arr = explode(',', session('cart'));
                $size = $arr[0];
                $color = $arr[1];
                $amount = $arr[2];
            }
            $product_size = DB::select('select * from product_size_color where product_id= :pid and size= :size and color= :color', [
                'pid' => $pid,
                'size' => $size,
                'color' => $color
            ]);
            if ($product_size[0]->quantity == 0) {
                return redirect('/products/' . $pid);
            } else {
                $products = DB::select('select cart.*,discount.*,product_size_color.*,product.* from cart 
                    inner join product on cart.product_id = product.product_id
                    inner join product_size_color on product_size_color.size = cart.size 
                        and cart.product_id = product_size_color.product_id and product_size_color.color = cart.color 
                    left join discount on discount.discount_id = product.discount_id
                where mem_id= :uid and product.product_active = 1
                 order by cart_id desc', [
                    'uid' => session('user')
                ]);
                $product_cart = DB::select('select * from cart 
                    where mem_id= :uid and product_id= :pid and size= :size and color= :color', [
                    'uid' => session('user'),
                    'pid' => $pid,
                    'size' => $size,
                    'color' => $color
                ]);
                if ($product_cart) {
                    if ($product_cart[0]->amount + $amount > $product_size[0]->quantity) {
                        DB::select('update cart set amount= :quantity 
                            where mem_id= :uid and product_id= :pid and size= :size and color= :color', [
                            'uid' => session('user'),
                            'pid' => $pid,
                            'quantity' =>  $product_size[0]->quantity,
                            'size' => $size,
                            'color' => $color
                        ]);
                    } else {
                        DB::select('update cart set amount= :quantity 
                            where mem_id= :uid and product_id= :pid and size= :size and color= :color', [
                            'uid' => session('user'),
                            'pid' => $pid,
                            'quantity' => ($product_cart[0]->amount + $amount),
                            'size' => $size,
                            'color' => $color
                        ]);
                    }
                } else {
                    if ($amount > $product_size[0]->quantity) {
                        DB::select('insert into cart (mem_id,product_id,amount,size,color) 
                                values (:uid, :pid, :quantity, :size, :color)', [
                            'uid' => session('user'),
                            'pid' => $pid,
                            'quantity' => $product_size[0]->quantity,
                            'size' => $size,
                            'color' => $color
                        ]);
                    } else {
                        DB::select('insert into cart (mem_id,product_id,amount,size,color) 
                                values (:uid, :pid, :quantity, :size, :color)', [
                            'uid' => session('user'),
                            'pid' => $pid,
                            'quantity' => $amount,
                            'size' => $size,
                            'color' => $color
                        ]);
                    }
                }
                $pri_product = DB::select('select cart.*,discount.*,product_size_color.*,product.* from cart 
                    inner join product on cart.product_id = product.product_id
                    inner join product_size_color on product_size_color.size = cart.size 
                        and cart.product_id = product_size_color.product_id and cart.color = product_size_color.color
                    left join discount on discount.discount_id = product.discount_id    
                where mem_id= :uid and cart.product_id= :pid and cart.size= :size and cart.color= :color and product.product_active = 1', [
                    'uid' => session('user'),
                    'pid' => $pid,
                    'size' => $size,
                    'color' => $color
                ]);
                if (!$pri_product) {
                    return redirect('/cart');
                }
                $sizes = DB::select('select cart.product_id, product_size_color.size from cart inner join product_size_color 
                    on cart.product_id= product_size_color.product_id
                    where mem_id= :uid group by cart.product_id, product_size_color.size', [
                    'uid' => session('user')
                ]);
                $colors = DB::select('select cart.product_id, product_size_color.color from cart inner join product_size_color 
                    on cart.product_id= product_size_color.product_id
                    where mem_id= :uid group by cart.product_id, product_size_color.color', [
                    'uid' => session('user')
                ]);
                $pscs = DB::select('select * from product_size_color');
                $brands = new HeaderController();
                $deleted_products = DB::select('select * from cart 
                    inner join product on product.product_id = cart.product_id
                    where product.product_active < 1 and mem_id= :uid', [
                    'uid' => session('user')
                ]);
                // dd( count($deleted_products));
                if ($deleted_products) {
                    for ($i = 0; $i < count($deleted_products); $i++) {
                        DB::select('delete from cart where mem_id = :uid and size = :size and color = :color and product_id = :pid', [
                            'uid' => session('user'),
                            'size' => $deleted_products[$i]->size,
                            'color' => $deleted_products[$i]->color,
                            'pid' => $deleted_products[$i]->product_id
                        ]);
                    }
                }
                return view('user/cart', [
                    'pscs' => $pscs,
                    'products' => $products,
                    'pid' => $pid,
                    'sizes' => $sizes,
                    'colors' => $colors,
                    'pri_product' => $pri_product[0],
                    'brands' => $brands->load(),
                    'count_deleted' => count($deleted_products)
                ]);
            }
        }
    }
    public function loadCart()
    {
        session(['prePage' => '/cart']);
        if (session('login') != 'true') {
            return redirect('/login');
        } else {
            $products = DB::select('select cart.*,discount.*,product_size_color.*,product.* from cart 
                inner join product on cart.product_id = product.product_id 
                inner join product_size_color on cart.size = product_size_color.size 
                    and cart.product_id= product_size_color.product_id and cart.color= product_size_color.color
                left join discount on discount.discount_id = product.discount_id
            where mem_id= :uid and product.product_active = 1 order by cart_id desc', [
                'uid' => session('user')
            ]);
            $sizes = DB::select('select cart.product_id, product_size_color.size from cart inner join product_size_color 
                on cart.product_id= product_size_color.product_id
                where mem_id= :uid group by cart.product_id, product_size_color.size', [
                'uid' => session('user')
            ]);
            $colors = DB::select('select cart.product_id, product_size_color.color from cart inner join product_size_color 
                on cart.product_id= product_size_color.product_id
                where mem_id= :uid group by cart.product_id, product_size_color.color', [
                'uid' => session('user')
            ]);
            $brands = new HeaderController();
            $pscs = DB::select('select * from product_size_color');
            $deleted_products = DB::select('select * from cart 
                inner join product on product.product_id = cart.product_id
                where product.product_active < 1 and mem_id= :uid', [
                'uid' => session('user')
            ]);
            // dd( count($deleted_products));
            if ($deleted_products) {
                for ($i = 0; $i < count($deleted_products); $i++) {
                    DB::select('delete from cart where mem_id = :uid and size = :size and color = :color and product_id = :pid', [
                        'uid' => session('user'),
                        'size' => $deleted_products[$i]->size,
                        'color' => $deleted_products[$i]->color,
                        'pid' => $deleted_products[$i]->product_id
                    ]);
                }
            }
            return view('user/cart', [
                'pscs' => $pscs,
                'products' => $products,
                'sizes' => $sizes,
                'colors' => $colors,
                'brands' => $brands->load(),
                'count_deleted' => count($deleted_products)
            ]);
        }
    }

    public function updateQuantity(Request $request)
    {
        session(['prePage' => '/cart']);
        if (session('login') != 'true') {
            return response()->json(['redirect', '/login']);
        } else {
            $request->validate([
                'id' => 'required',
                'size' => 'required',
                'color' => 'required',
                'quantity' => 'required'
            ]);
            $amount = $request->quantity;
            $product = DB::select('select * from cart inner join product on cart.product_id = product.product_id
                left join discount on discount.discount_id = product.discount_id 
            where mem_id= :uid and cart.product_id= :pid and size= :size and color= :color', [
                'uid' => session('user'),
                'pid' => $request->id,
                'size' => $request->size,
                'color' => $request->color
            ]);
            if (!$product[0]->discount_value) {
                $product[0]->discount_value = 0;
            }
            if (!$amount) {
                $amount = $product[0]->amount;
            }
            $product_size = DB::select('select * from product_size_color inner join cart 
                on cart.product_id = product_size_color.product_id  and cart.size = product_size_color.size 
                where cart.size= :size and cart.product_id= :pid and cart.color= :color and cart.mem_id= :uid', [
                'uid' => session('user'),
                'size' => $request->size,
                'pid' => $request->id,
                'color' => $request->color
            ]);
            if ($product_size[0]->quantity < $amount) {
                $amount = $product_size[0]->quantity;
            } else {
                if (isset($request->action)) {
                    if ($request->action == 'remove') {
                        if ($amount > 1 &&  $amount <= $product_size[0]->quantity) {
                            $amount -= 1;
                        }
                    }
                    if ($request->action == 'add') {
                        if ($amount < $product_size[0]->quantity) {
                            $amount += 1;
                        }
                    }
                }
            }
            if (strtotime($product[0]->discount_end) >= strtotime(date("Y-m-d")) && strtotime($product[0]->discount_start) <= strtotime(date("Y-m-d"))) {
                $p_price = $product[0]->product_price * (1 -  $product[0]->discount_value / 100);
            } else {
                $p_price = $product[0]->product_price;
            }

            $old_price =  $p_price * $product_size[0]->amount;
            $old_amount = $product_size[0]->amount;
            DB::select('update cart set amount= :amount where mem_id= :uid and product_id= :pid and size= :size and color= :color', [
                'uid' => session('user'),
                'pid' => $request->id,
                'amount' => $amount,
                'size' => $request->size,
                'color' => $request->color
            ]);

            return response()->json([
                'quantity' => $amount,
                's_price' => $amount * $p_price,
                'diff_price' => $amount * $p_price - $old_price,
                'diff_amount' => $amount - $old_amount
            ]);
        }
    }

    public function removeProduct(Request $request)
    {
        session(['prePage' => '/cart']);
        if (session('login') != 'true') {
            return response()->json(['redirect', '/login']);
        } else {
            $request->validate([
                'id' => 'required',
                'size' => 'required',
                'color' => 'required'
            ]);
            $product = DB::select('select * from cart where mem_id= :uid and product_id= :pid and size= :size and color= :color', [
                'uid' => session('user'),
                'pid' => $request->id,
                'size' => $request->size,
                'color' => $request->color
            ]);
            if ($product) {
                DB::select('delete from cart where mem_id= :uid and product_id= :pid and size= :size and color= :color', [
                    'uid' => session('user'),
                    'pid' => $request->id,
                    'size' => $request->size,
                    'color' => $request->color
                ]);
            }
            return response()->json([]);
        }
    }

    public function updateSize(Request $request)
    {
        session(['prePage' => '/cart']);
        if (session('login') != 'true') {
            return response()->json(['redirect', '/login']);
        } else {
            $request->validate([
                'pid' => 'required',
                'size' => 'required',
                'color' => 'required',
                'cid' => 'required'
            ]);
            $product_size = DB::select('select quantity from product_size_color where product_id= :pid and size= :size and color= :color', [
                'pid' => $request->pid,
                'size' => $request->size,
                'color' => $request->color
            ]);
            $check = DB::select('select * from cart where size= :size and mem_id= :uid and product_id= :pid and color= :color', [
                'uid' => session('user'),
                'pid' => $request->pid,
                'size' => $request->size,
                'color' => $request->color
            ]);
            if ($product_size && !$check) {
                if ($product_size[0]->quantity > 0) {
                    DB::select('update cart set size= :size, color= :color where cart_id= :cid', [
                        'cid' => $request->cid,
                        'size' => $request->size,
                        'color' => $request->color
                    ]);
                    $obj = DB::select('select * from cart where cart_id= :cid', [
                        'cid' => $request->cid
                    ]);
                    if ($obj[0]->amount > $product_size[0]->quantity) {
                        DB::select('update cart set amount= :amount where cart_id= :cid', [
                            'cid' => $request->cid,
                            'amount' => $product_size[0]->quantity
                        ]);
                    }
                    $product = DB::select('select * from cart inner join product_size_color on product_size_color.size = cart.size
                         and cart.product_id = product_size_color.product_id and cart.color = product_size_color.color
                        where cart_id= :cid', [
                        'cid' => $request->cid
                    ]);
                    return response()->json([
                        'quantity' => $product[0]->quantity,
                        'img' => explode(',', $product[0]->product_image)[0]
                    ]);
                } else {
                    return response()->json(['soldOut' => 'Sản phẩm đã hết hàng']);
                }
            } else {
                if ($check) {
                    return response()->json([]);
                }
                return response()->json(['soldOut' => 'Sản phẩm đã hết hàng']);
            }
        }
    }

    public function selectProduct(Request $request)
    {
        session(['prePage' => '/cart']);
        if (session('login') != 'true') {
            return response()->json(['redirect', '/login']);
        } else {
            $request->validate([
                'pid' => 'required'
            ]);
            if ($request->pid == 'all') {
                $s_amount = DB::select('select sum(amount) as Tongsoluong from cart where mem_id= :uid', [
                    'uid' => session('user')
                ])[0]->Tongsoluong;
                $cart = DB::select('select * from cart inner join product on cart.product_id = product.product_id 
                    left join discount on discount.discount_id = product.discount_id
                where mem_id= :uid', [
                    'uid' => session('user')
                ]);
                if (!$cart[0]->discount_value) {
                    $cart[0]->discount_value = 0;
                }
                $s_price = 0;
                for ($i = 0; $i < count($cart); $i++) {
                    if (strtotime($cart[$i]->discount_end) >= strtotime(date("Y-m-d")) && strtotime($cart[$i]->discount_start) <= strtotime(date("Y-m-d"))) {
                        $s_price += $cart[$i]->amount * $cart[$i]->product_price * (1 - $cart[$i]->discount_value / 100);
                    } else {
                        $s_price += $cart[$i]->amount * $cart[$i]->product_price;
                    }
                }
                return response()->json([
                    's_amount' => $s_amount,
                    's_price' => $s_price
                ]);
            } else {
                $s_amount = DB::select('select sum(amount) as Tongsoluong from cart 
                where mem_id= :uid and product_id= :pid and size= :size and color= :color', [
                    'uid' => session('user'),
                    'pid' => $request->pid,
                    'size' => $request->size,
                    'color' => $request->color
                ])[0]->Tongsoluong;

                $cart = DB::select('select * from cart inner join product on cart.product_id = product.product_id 
                    left join discount on discount.discount_id = product.discount_id
                where mem_id= :uid  and cart.product_id= :pid and cart.size= :size and color= :color', [
                    'uid' => session('user'),
                    'pid' => $request->pid,
                    'size' => $request->size,
                    'color' => $request->color
                ]);
                if (strtotime($cart[0]->discount_end) >= strtotime(date("Y-m-d")) && strtotime($cart[0]->discount_start) <= strtotime(date("Y-m-d"))) {
                    $s_price = $cart[0]->amount * $cart[0]->product_price * (1 - $cart[0]->discount_value / 100);
                } else {
                    $s_price = $cart[0]->amount * $cart[0]->product_price;
                }

                return response()->json([
                    's_amount' => $s_amount,
                    's_price' => $s_price
                ]);
            }
        }
    }







    // API
    public function payment_api(Request $request, $pid)
    {
        try {
            // session(['prePage' => '/cart/' . $pid]);
            // if (session('login') == 'false') {
            //     session(['cart' => $request->size . ',' . $request->color . ',' . $request->amount]);
            //     return redirect('/login');
            // } else {
            //     if (session('cart') == null) {
            $request->validate([
                'size' => 'required',
                'color' => 'required',
                'amount' => 'required'
            ]);
            $size = $request->size;
            $color = $request->color;
            $amount = $request->amount;
            // } else {
            //     $arr = explode(',', session('cart'));
            //     $size = $arr[0];
            //     $color = $arr[1];
            //     $amount = $arr[2];
            // }
            $product_size = DB::select('select * from product_size_color where product_id= :pid and size= :size and color= :color', [
                'pid' => $pid,
                'size' => $size,
                'color' => $color
            ]);
            // if ($product_size[0]->quantity == 0) {
            //     return redirect('/products/' . $pid);
            // } else {
            $products = DB::select('select cart.*,discount.*,product_size_color.*,product.* from cart 
                        inner join product on cart.product_id = product.product_id
                        inner join product_size_color on product_size_color.size = cart.size 
                            and cart.product_id = product_size_color.product_id and product_size_color.color = cart.color 
                        left join discount on discount.discount_id = product.discount_id
                    where mem_id= :uid and product.product_active = 1
                     order by cart_id desc', [
                'uid' => session('user')
            ]);
            $product_cart = DB::select('select * from cart 
                        where mem_id= :uid and product_id= :pid and size= :size and color= :color', [
                'uid' => session('user'),
                'pid' => $pid,
                'size' => $size,
                'color' => $color
            ]);
            if ($product_cart) {
                if ($product_cart[0]->amount + $amount > $product_size[0]->quantity) {
                    DB::select('update cart set amount= :quantity 
                                where mem_id= :uid and product_id= :pid and size= :size and color= :color', [
                        'uid' => session('user'),
                        'pid' => $pid,
                        'quantity' =>  $product_size[0]->quantity,
                        'size' => $size,
                        'color' => $color
                    ]);
                } else {
                    DB::select('update cart set amount= :quantity 
                                where mem_id= :uid and product_id= :pid and size= :size and color= :color', [
                        'uid' => session('user'),
                        'pid' => $pid,
                        'quantity' => ($product_cart[0]->amount + $amount),
                        'size' => $size,
                        'color' => $color
                    ]);
                }
            } else {
                if ($amount > $product_size[0]->quantity) {
                    DB::select('insert into cart (mem_id,product_id,amount,size,color) 
                                    values (:uid, :pid, :quantity, :size, :color)', [
                        'uid' => session('user'),
                        'pid' => $pid,
                        'quantity' => $product_size[0]->quantity,
                        'size' => $size,
                        'color' => $color
                    ]);
                } else {
                    DB::select('insert into cart (mem_id,product_id,amount,size,color) 
                                    values (:uid, :pid, :quantity, :size, :color)', [
                        'uid' => session('user'),
                        'pid' => $pid,
                        'quantity' => $amount,
                        'size' => $size,
                        'color' => $color
                    ]);
                }
            }
            $pri_product = DB::select('select cart.*,discount.*,product_size_color.*,product.* from cart 
                        inner join product on cart.product_id = product.product_id
                        inner join product_size_color on product_size_color.size = cart.size 
                            and cart.product_id = product_size_color.product_id and cart.color = product_size_color.color
                        left join discount on discount.discount_id = product.discount_id    
                    where mem_id= :uid and cart.product_id= :pid and cart.size= :size and cart.color= :color and product.product_active = 1', [
                'uid' => session('user'),
                'pid' => $pid,
                'size' => $size,
                'color' => $color
            ]);
            if (!$pri_product) {
                return redirect('/cart');
            }
            $sizes = DB::select('select cart.product_id, product_size_color.size from cart inner join product_size_color 
                        on cart.product_id= product_size_color.product_id
                        where mem_id= :uid group by cart.product_id, product_size_color.size', [
                'uid' => session('user')
            ]);
            $colors = DB::select('select cart.product_id, product_size_color.color from cart inner join product_size_color 
                        on cart.product_id= product_size_color.product_id
                        where mem_id= :uid group by cart.product_id, product_size_color.color', [
                'uid' => session('user')
            ]);
            $pscs = DB::select('select * from product_size_color');
            $brands = new HeaderController();
            $deleted_products = DB::select('select * from cart 
                        inner join product on product.product_id = cart.product_id
                        where product.product_active < 1 and mem_id= :uid', [
                'uid' => session('user')
            ]);
            // dd( count($deleted_products));
            if ($deleted_products) {
                for ($i = 0; $i < count($deleted_products); $i++) {
                    DB::select('delete from cart where mem_id = :uid and size = :size and color = :color and product_id = :pid', [
                        'uid' => session('user'),
                        'size' => $deleted_products[$i]->size,
                        'color' => $deleted_products[$i]->color,
                        'pid' => $deleted_products[$i]->product_id
                    ]);
                }
            }
            // return view('user/cart', [
            //     'pscs' => $pscs,
            //     'products' => $products,
            //     'pid' => $pid,
            //     'sizes' => $sizes,
            //     'colors' => $colors,
            //     'pri_product' => $pri_product[0],
            //     'brands' => $brands->load(),
            //     'count_deleted' => count($deleted_products)
            // ]);
            return response()->json([
                'status' => 'success',
                'pscs' => $pscs,
                'products' => $products,
                'pid' => $pid,
                'sizes' => $sizes,
                'colors' => $colors,
                'pri_product' => $pri_product[0],
                'brands' => $brands->load(),
                'count_deleted' => count($deleted_products)
            ], 201);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'error' => $e->getMessage()], 500);
        }
    }
    public function loadCart_api()
    {
        try {
            // session(['prePage' => '/cart']);
            // if (session('login') != 'true') {
            //     return redirect('/login');
            // } else {
            $products = DB::select('select cart.*,discount.*,product_size_color.*,product.* from cart 
                    inner join product on cart.product_id = product.product_id 
                    inner join product_size_color on cart.size = product_size_color.size 
                        and cart.product_id= product_size_color.product_id and cart.color= product_size_color.color
                    left join discount on discount.discount_id = product.discount_id
                where mem_id= :uid and product.product_active = 1 order by cart_id desc', [
                'uid' => session('user')
            ]);
            $sizes = DB::select('select cart.product_id, product_size_color.size from cart inner join product_size_color 
                    on cart.product_id= product_size_color.product_id
                    where mem_id= :uid group by cart.product_id, product_size_color.size', [
                'uid' => session('user')
            ]);
            $colors = DB::select('select cart.product_id, product_size_color.color from cart inner join product_size_color 
                    on cart.product_id= product_size_color.product_id
                    where mem_id= :uid group by cart.product_id, product_size_color.color', [
                'uid' => session('user')
            ]);
            $brands = new HeaderController();
            $pscs = DB::select('select * from product_size_color');
            $deleted_products = DB::select('select * from cart 
                    inner join product on product.product_id = cart.product_id
                    where product.product_active < 1 and mem_id= :uid', [
                'uid' => session('user')
            ]);
            // dd( count($deleted_products));
            if ($deleted_products) {
                for ($i = 0; $i < count($deleted_products); $i++) {
                    DB::select('delete from cart where mem_id = :uid and size = :size and color = :color and product_id = :pid', [
                        'uid' => session('user'),
                        'size' => $deleted_products[$i]->size,
                        'color' => $deleted_products[$i]->color,
                        'pid' => $deleted_products[$i]->product_id
                    ]);
                }
            }
            // return view('user/cart', [
            //     'pscs' => $pscs,
            //     'products' => $products,
            //     'sizes' => $sizes,
            //     'colors' => $colors,
            //     'brands' => $brands->load(),
            //     'count_deleted' => count($deleted_products)
            // ]);
            return response()->json([
                'status' => 'success',
                'pscs' => $pscs,
                'products' => $products,
                'sizes' => $sizes,
                'colors' => $colors,
                'brands' => $brands->load(),
                'count_deleted' => count($deleted_products)
            ], 201);
            // }
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'error' => $e->getMessage()], 500);
        }
    }

    public function updateQuantity_api(Request $request)
    {
        try {
            // session(['prePage' => '/cart']);
            // if (session('login') != 'true') {
            //     return response()->json(['redirect', '/login']);
            // } else {
            $request->validate([
                'id' => 'required',
                'size' => 'required',
                'color' => 'required',
                'quantity' => 'required'
            ]);
            $amount = $request->quantity;
            $product = DB::select('select * from cart inner join product on cart.product_id = product.product_id
                    left join discount on discount.discount_id = product.discount_id 
                where mem_id= :uid and cart.product_id= :pid and size= :size and color= :color', [
                'uid' => session('user'),
                'pid' => $request->id,
                'size' => $request->size,
                'color' => $request->color
            ]);
            if (!$product[0]->discount_value) {
                $product[0]->discount_value = 0;
            }
            if (!$amount) {
                $amount = $product[0]->amount;
            }
            $product_size = DB::select('select * from product_size_color inner join cart 
                    on cart.product_id = product_size_color.product_id  and cart.size = product_size_color.size 
                    where cart.size= :size and cart.product_id= :pid and cart.color= :color and cart.mem_id= :uid', [
                'uid' => session('user'),
                'size' => $request->size,
                'pid' => $request->id,
                'color' => $request->color
            ]);
            if ($product_size[0]->quantity < $amount) {
                $amount = $product_size[0]->quantity;
            } else {
                if (isset($request->action)) {
                    if ($request->action == 'remove') {
                        if ($amount > 1 &&  $amount <= $product_size[0]->quantity) {
                            $amount -= 1;
                        }
                    }
                    if ($request->action == 'add') {
                        if ($amount < $product_size[0]->quantity) {
                            $amount += 1;
                        }
                    }
                }
            }
            if (strtotime($product[0]->discount_end) >= strtotime(date("Y-m-d")) && strtotime($product[0]->discount_start) <= strtotime(date("Y-m-d"))) {
                $p_price = $product[0]->product_price * (1 -  $product[0]->discount_value / 100);
            } else {
                $p_price = $product[0]->product_price;
            }

            $old_price =  $p_price * $product_size[0]->amount;
            $old_amount = $product_size[0]->amount;
            DB::select('update cart set amount= :amount where mem_id= :uid and product_id= :pid and size= :size and color= :color', [
                'uid' => session('user'),
                'pid' => $request->id,
                'amount' => $amount,
                'size' => $request->size,
                'color' => $request->color
            ]);

            // return response()->json([
            //     'quantity' => $amount,
            //     's_price' => $amount * $p_price,
            //     'diff_price' => $amount * $p_price - $old_price,
            //     'diff_amount' => $amount - $old_amount
            // ]);
            return response()->json([
                'status' => 'success',
                'quantity' => $amount,
                's_price' => $amount * $p_price,
                'diff_price' => $amount * $p_price - $old_price,
                'diff_amount' => $amount - $old_amount
            ], 201);
            // }
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'error' => $e->getMessage()], 500);
        }
    }

    public function removeProduct_api(Request $request)
    {
        try {
            // session(['prePage' => '/cart']);
            // if (session('login') != 'true') {
            //     return response()->json(['redirect', '/login']);
            // } else {
            $request->validate([
                'id' => 'required',
                'size' => 'required',
                'color' => 'required'
            ]);
            $product = DB::select('select * from cart where mem_id= :uid and product_id= :pid and size= :size and color= :color', [
                'uid' => session('user'),
                'pid' => $request->id,
                'size' => $request->size,
                'color' => $request->color
            ]);
            if ($product) {
                DB::select('delete from cart where mem_id= :uid and product_id= :pid and size= :size and color= :color', [
                    'uid' => session('user'),
                    'pid' => $request->id,
                    'size' => $request->size,
                    'color' => $request->color
                ]);
            }
            return response()->json(['status' => 'success', 'message' => 'Xóa sản phẩm thành công!'], 201);
            // }
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Xóa sản phẩm thất bại', 'error' => $e->getMessage()], 500);
        }
    }

    public function updateSize_api(Request $request)
    {
        try {
            // session(['prePage' => '/cart']);
            // if (session('login') != 'true') {
            //     return response()->json(['redirect', '/login']);
            // } else {
            $request->validate([
                'pid' => 'required',
                'size' => 'required',
                'color' => 'required',
                'cid' => 'required'
            ]);
            $product_size = DB::select('select quantity from product_size_color where product_id= :pid and size= :size and color= :color', [
                'pid' => $request->pid,
                'size' => $request->size,
                'color' => $request->color
            ]);
            $check = DB::select('select * from cart where size= :size and mem_id= :uid and product_id= :pid and color= :color', [
                'uid' => session('user'),
                'pid' => $request->pid,
                'size' => $request->size,
                'color' => $request->color
            ]);
            if ($product_size && !$check) {
                if ($product_size[0]->quantity > 0) {
                    DB::select('update cart set size= :size, color= :color where cart_id= :cid', [
                        'cid' => $request->cid,
                        'size' => $request->size,
                        'color' => $request->color
                    ]);
                    $obj = DB::select('select * from cart where cart_id= :cid', [
                        'cid' => $request->cid
                    ]);
                    if ($obj[0]->amount > $product_size[0]->quantity) {
                        DB::select('update cart set amount= :amount where cart_id= :cid', [
                            'cid' => $request->cid,
                            'amount' => $product_size[0]->quantity
                        ]);
                    }
                    $product = DB::select('select * from cart inner join product_size_color on product_size_color.size = cart.size
                             and cart.product_id = product_size_color.product_id and cart.color = product_size_color.color
                            where cart_id= :cid', [
                        'cid' => $request->cid
                    ]);
                    // return response()->json([
                    //     'quantity' => $product[0]->quantity,
                    //     'img' => explode(',', $product[0]->product_image)[0]
                    // ]);
                    return response()->json([
                        'status' => 'success',
                        'quantity' => $product[0]->quantity,
                        'img' => explode(',', $product[0]->product_image)[0]
                    ], 201);
                } else {
                    return response()->json(['soldOut' => 'Sản phẩm đã hết hàng']);
                }
            } else {
                if ($check) {
                    return response()->json([]);
                }
                return response()->json(['soldOut' => 'Sản phẩm đã hết hàng']);
            }
            // }
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Sản phẩm lỗi', 'error' => $e->getMessage()], 500);
        }
    }

    public function selectProduct_api(Request $request)
    {
        try {
            // session(['prePage' => '/cart']);
            // if (session('login') != 'true') {
            //     return response()->json(['redirect', '/login']);
            // } else {
            $request->validate([
                'pid' => 'required'
            ]);
            if ($request->pid == 'all') {
                $s_amount = DB::select('select sum(amount) as Tongsoluong from cart where mem_id= :uid', [
                    'uid' => session('user')
                ])[0]->Tongsoluong;
                $cart = DB::select('select * from cart inner join product on cart.product_id = product.product_id 
                        left join discount on discount.discount_id = product.discount_id
                    where mem_id= :uid', [
                    'uid' => session('user')
                ]);
                if (!$cart[0]->discount_value) {
                    $cart[0]->discount_value = 0;
                }
                $s_price = 0;
                for ($i = 0; $i < count($cart); $i++) {
                    if (strtotime($cart[$i]->discount_end) >= strtotime(date("Y-m-d")) && strtotime($cart[$i]->discount_start) <= strtotime(date("Y-m-d"))) {
                        $s_price += $cart[$i]->amount * $cart[$i]->product_price * (1 - $cart[$i]->discount_value / 100);
                    } else {
                        $s_price += $cart[$i]->amount * $cart[$i]->product_price;
                    }
                }
                return response()->json([
                    's_amount' => $s_amount,
                    's_price' => $s_price
                ]);
            } else {
                $s_amount = DB::select('select sum(amount) as Tongsoluong from cart 
                    where mem_id= :uid and product_id= :pid and size= :size and color= :color', [
                    'uid' => session('user'),
                    'pid' => $request->pid,
                    'size' => $request->size,
                    'color' => $request->color
                ])[0]->Tongsoluong;

                $cart = DB::select('select * from cart inner join product on cart.product_id = product.product_id 
                        left join discount on discount.discount_id = product.discount_id
                    where mem_id= :uid  and cart.product_id= :pid and cart.size= :size and color= :color', [
                    'uid' => session('user'),
                    'pid' => $request->pid,
                    'size' => $request->size,
                    'color' => $request->color
                ]);
                if (strtotime($cart[0]->discount_end) >= strtotime(date("Y-m-d")) && strtotime($cart[0]->discount_start) <= strtotime(date("Y-m-d"))) {
                    $s_price = $cart[0]->amount * $cart[0]->product_price * (1 - $cart[0]->discount_value / 100);
                } else {
                    $s_price = $cart[0]->amount * $cart[0]->product_price;
                }

                // return response()->json([
                //     's_amount' => $s_amount,
                //     's_price' => $s_price
                // ]);
                return response()->json([
                    'status' => 'success',
                    's_amount' => $s_amount,
                    's_price' => $s_price
                ], 201);
            }
            // }
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Chọn sản phẩm thất bại', 'error' => $e->getMessage()], 500);
        }
    }

    public function cart_api(Request $request)
    {
        try {
            $cart = DB::select('select * from cart where cart_active=1 and mem_id= :mem_id and product_id= :product_id and color= :color', [
                'mem_id' => $request->input('mem_id'),
                'product_id' => $request->input('product_id'),
                'color' => $request->input('color'),
            ]);
            if ($cart) {
                return response()->json(['error' => 'Giỏ hàng của bạn đã tồn tại sản phẩm này']);
            }
            DB::table("cart")->insert([
                'mem_id' => $request->input('mem_id'),
                'product_id' => $request->input('product_id'),
                'color' => $request->input('color'),
                'size' => $request->input('size'),
                'quantity' => $request->input('quantity')
            ]);

            $cart = DB::select('select * from cart where cart_active=1 and mem_id= :mem_id and product_id= :product_id and color= :color and size= :size', [
                'mem_id' => $request->input('mem_id'),
                'product_id' => $request->input('product_id'),
                'color' => $request->input('color'),
                'size' => $request->input('size'),
                'quantity' => $request->input('quantity')
            ]);
            return response()->json(['status' => 'success', 'cart' => $cart]);
            // }
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'error' => $e->getMessage()]);
        }
    }

    public function user_cart_api(Request $request)
    {
        try {
            $user_id = $request->query('id');
            $products = Cart::where('mem_id', $user_id)->where('cart_active', '=', 1)
                ->with(['members', 'products' => function ($query) {
                    $query->with(['details', 'discounts' => function ($query) {
                        $query->where('discount_active', 1);
                    }]);
                }])
                ->get();
            return response()->json([
                'status' => 'success',
                'products' => $products,
            ]);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'error' => $e->getMessage()]);
        }
    }

    public function update_cart_api(Request $request)
    {
        try {
            $cartId = $request->input('cart_id');
            $color = $request->input('color');
            $size = $request->input('size');

            DB::table('cart')
                ->where('cart_id', $cartId)
                ->update([
                    'color' => $color,
                    'size' => $size
                ]);
            return response()->json([
                'status' => 'success',
                'update' => 'update thành công',
            ]);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'error' => $e->getMessage()]);
        }
    }

    public function remove_cart_api(Request $request)
    {
        try {
            $cartId = $request->input('cart_id');

            //DB::table('cart')->where('cart_id', $cartId)->delete();
            DB::table('cart')
                ->where('cart_id', $cartId)
                ->update([
                    'cart_active' => 0,
                ]);
            return response()->json([
                'status' => 'success',
                'Xóa' => 'Xóa thành công',
            ]);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'error' => $e->getMessage()]);
        }
    }

    public function order_user_api(Request $request)
    {
        try {
            $user_id = $request->query('id');
            $order_status = $request->query('status');
            if ($order_status == 3) {
                $orders = order::where('mem_id', $user_id)
                    ->with([
                        'members', 'feedbacks' => function ($query) {
                            $query->with([
                                'member'
                            ]);
                        }, 'details'
                        => function ($query) {
                            $query->with([
                                'product'
                            ]);
                        }

                    ])
                    ->get();
                return response()->json([
                    'status' => 'success',
                    'products' => $orders,
                ]);
            } else {
                $orders = order::where('mem_id', $user_id)
                    ->where('order_status', $order_status)
                    ->with([
                        'members', 'feedbacks' => function ($query) {
                            $query->with([
                                'member'
                            ]);
                        }, 'details' => function ($query) {
                            $query->with([
                                'product'
                            ]);
                        }

                    ])
                    ->get();
                return response()->json([
                    'status' => 'success',
                    'products' => $orders,
                ]);
            }
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'error' => $e->getMessage()]);
        }
    }

    public function feedback_api(Request $request)
    {
        try {
            $order_id = intval($request->input('order_id'));

            DB::table("feedback")->insert([
                'mem_id' => $request->input('mem_id'),
                'product_id' => $request->input('product_id'),
                'comment' => $request->input('comment'),
                'star' => $request->input('star'),
                'order_id' => $order_id,
            ]);

            return response()->json(['status' => 'success', 'message' => 'Feedback thành công']);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'error' => $e->getMessage()]);
        }
    }

    public function update_canceled_api(Request $request)
    {
        try {

            $details = $request->input('details');
            $array = json_decode($details, true);
            if (is_array($array) || is_object($array)) {
                $order_id = intval($request->input('order_id'));

                DB::table('order')
                    ->where('order_id', $order_id)
                    ->update([
                        'order_status' => -1,
                        'canceled_date' => DB::raw('CURRENT_TIMESTAMP()')
                    ]);

                foreach ($array as $product) {

                    DB::table('product_size_color')
                        ->where('product_id', $product['product_id'])
                        ->where('size', $product['size'])
                        ->where('color', $product['color'])
                        ->update(['quantity' => DB::raw('quantity + ' . $product['quantity'])]);
                }
                return response()->json([
                    'status' => 'success',
                    'update' => 'Hủy đơn hàng thành công',
                ]);
            } else {
                return response()->json(['status' => 'error']);
            }
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'error' => $e->getMessage()]);
        }
    }

    public function update_completion_api(Request $request)
    {
        try {
            $order_id = intval($request->input('order_id'));

            DB::table('order')
                ->where('order_id', $order_id)
                ->update([
                    'order_status' => 2,
                    'completion_date' => DB::raw('CURRENT_TIMESTAMP()')
                ]);
            return response()->json([
                'status' => 'success',
                'update' => 'Xác nhận nhận đơn hàng thành công',
            ]);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'error' => $e->getMessage()]);
        }
    }
}
