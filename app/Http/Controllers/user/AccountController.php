<?php

namespace App\Http\Controllers\user;

use Illuminate\Routing\Controller;
use App\Http\Resources\UserCollection;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\User;

class AccountController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function loadPage()
    {
        session(['prePage' => '/account/profile']);
        if (session('login') != 'true') {
            return redirect('/login');
        } else {
            $uid = session('user');
            $user = DB::select('select * from member where mem_id= :uid', [
                'uid' => $uid,
            ]);
            if ($user[0]->phone == 0) {
                $user[0]->phone = null;
            }
            $brands = new HeaderController();
            // return view('user/profile',[
            //     'user' => $user[0],
            //     'brands' => $brands->load()
            // ]);
            return new UserCollection($user[0], $brands);
        }
    }
    public function goLink($link, Request $request)
    {
        session(['prePage' => '/account/' . $link]);
        if (session('login') != 'true') {
            return redirect('/login');
        } else {
            $uid = session('user');
            $user = DB::select('select * from member where mem_id= :uid', [
                'uid' => $uid,
            ]);
            if ($user[0]->phone == 0) {
                $user[0]->phone = null;
            }
            $brands = new HeaderController();
            if ($link == 'order') {
                $orders = DB::select('select order.*,feedback.comment from order 
                left join feedback on feedback.order_id = order.order_id
                where order.mem_id= :uid order by order.order_id desc', [
                    'uid' => $uid
                ]);
                $product_orders = DB::select('select order.*,product_size_color.product_image,product.*,product_order.* from order 
                inner join product_order on product_order.order_id = order.order_id 
                inner join product on product.product_id = product_order.product_id
                inner join product_size_color on product_size_color.size = product_order.size and 
                    product_size_color.color = product_order.color and product_size_color.product_id = product_order.product_id
                    where order.mem_id= :uid', [
                    'uid' => $uid
                ]);
                return view('user/' . $link, [
                    'user' => $user[0],
                    'brands' => $brands->load(),
                    'orders' => $orders,
                    'product_orders' => $product_orders
                ]);
            }
            return view('user/' . $link, [
                'user' => $user[0],
                'brands' => $brands->load()
            ]);
        }
    }

    public function orderStatus($status)
    {
        $uid = session('user');
        $user = DB::select('select * from member where mem_id= :uid', [
            'uid' => $uid,
        ]);
        if ($user[0]->phone == 0) {
            $user[0]->phone = null;
        }
        $brands = new HeaderController();
        $orders = DB::select('select order.*,feedback.comment from order 
            left join feedback on feedback.order_id = order.order_id
            where order.mem_id= :uid and order_status= :status order by order.order_id desc', [
            'uid' => $uid,
            'status' => $status
        ]);
        $product_orders = DB::select('select order.*,product_size_color.product_image,product.*,product_order.* from order 
            inner join product_order on product_order.order_id = order.order_id 
            inner join product on product.product_id = product_order.product_id
            inner join product_size_color on product_size_color.size = product_order.size and 
                product_size_color.color = product_order.color and product_size_color.product_id = product_order.product_id
            where mem_id= :uid and  order_status= :status', [
            'status' => $status,
            'uid' => $uid
        ]);

        return view('user/order', [
            'user' => $user[0],
            'brands' => $brands->load(),
            'orders' => $orders,
            'product_orders' => $product_orders
        ]);
    }
    public function droporder(Request $request)
    {
        $request->validate([
            'rid' => 'required',
            'uid' => 'required'
        ]);
        $check = DB::select('select * from order where order_id=:rid and mem_id = :uid', [
            'rid' => $request->rid,
            'uid' => $request->uid
        ]);
        if ($check) {
            if ($check[0]->order_status == 0) {
                DB::select('update order set order_status= -1 where order_id= :rid', [
                    'rid' => $request->rid
                ]);
                $count = DB::select('select count(*) as TongDonHang from order where order_status= 0 and mem_id= :uid', [
                    'uid' => $request->uid
                ]);
                return response()->json([
                    'drop' => 'success',
                    'count' => $count[0]->TongDonHang
                ]);
            } else {
                return response()->json(['drop' => 'fail']);
            }
        } else {
            return response()->json(['drop' => 'fail']);
        }
    }
    public function confirmorder(Request $request)
    {
        $request->validate([
            'rid' => 'required',
            'uid' => 'required'
        ]);
        $check = DB::select('select * from order where order_id=:rid and mem_id = :uid', [
            'rid' => $request->rid,
            'uid' => $request->uid
        ]);
        if ($check) {
            if ($check[0]->order_status == 1) {
                DB::select('update order set order_status = 2 where order_id= :rid', [
                    'rid' => $request->rid
                ]);
                $count = DB::select('select count(*) as TongDonHang from order where order_status= 1 and mem_id= :uid', [
                    'uid' => $request->uid
                ]);
                return response()->json([
                    'confirm' => 'success',
                    'count' => $count[0]->TongDonHang
                ]);
            } else {
                return response()->json(['confirm' => 'fail']);
            }
        } else {
            return response()->json(['confirm' => 'fail']);
        }
    }

    public function writeRequest(Request $request)
    {
        $request->validate([
            'rid' => 'required'
        ]);
        $check = DB::select('select * from feedback where order_id= :rid', [
            'rid' => $request->rid
        ]);
        if ($check) {
            return response()->json(['write' => 'false']);
        } else {
            $products = DB::select('SELECT * FROM `product_order` 
                inner join product_size_color on product_size_color.product_id = product_order.product_id 
                    and product_size_color.size = product_order.size and product_size_color.color = product_order.color
                inner join product on product.product_id = product_order.product_id
                where product_order.order_id = :rid and product.product_active = 1', [
                'rid' => $request->rid
            ]);
            if (!$products) {
                return response()->json();
            }
            foreach ($products as $product) {
                $product->product_image = explode(',', $product->product_image)[0];
            }
            $pid = DB::select('select product_order.product_id from product_order 
            inner join product on product.product_id = product_order.product_id
            where product_order.order_id = :rid and product.product_active = 1
            group by product_order.product_id', [
                'rid' => $request->rid
            ]);
            return response()->json([
                'write' => 'true',
                'products' => $products,
                'pid' => $pid
            ]);
        }
    }
    public function readRequest(Request $request)
    {
        $request->validate([
            'rid' => 'required',
            'uid' => 'required'
        ]);
        $products = DB::select('select * from feedback 
            inner join product on product.product_id = feedback.product_id
            inner join product_order on product_order.order_id = feedback.order_id and product_order.product_id = feedback.product_id
            inner join product_size_color on product_size_color.product_id = product_order.product_id
                and product_size_color.size = product_order.size and product_size_color.color = product_order.color
            inner join member on member.mem_id = feedback.mem_id
            where feedback.order_id = :rid and feedback.mem_id = :uid and product.product_active = 1', [
            'rid' => $request->rid,
            'uid' => $request->uid
        ]);
        if (!$products) {
            return response()->json();
        }
        $feedbacks = DB::select('select feedback.*,member.name from feedback 
        inner join member on feedback.mem_id = member.mem_id
        where feedback.order_id = :rid and feedback.mem_id = :uid
        group by feedback.product_id,feedback.comment,feedback.star,feedback.order_id,feedback.mem_id,member.name', [
            'rid' => $request->rid,
            'uid' => $request->uid
        ]);
        if ($products && $feedbacks) {
            foreach ($products as $product) {
                $product->product_image = explode(',', $product->product_image)[0];
            }
            return response()->json([
                'read' => 'true',
                'products' => $products,
                'feedbacks' => $feedbacks
            ]);
        } else {
            return response()->json(['read' => 'fail']);
        }
    }
    public function addFeedback(Request $request)
    {
        $request->validate([
            'uid' => 'required',
            'rid' => 'required',
            'pid' => 'required',
            'star' => 'required',
            'comment' => 'required'
        ]);
        $arr_star = explode('|! ', $request->star);
        $arr_comment = explode('|! ', $request->comment);
        $arr_pid = explode('|! ', $request->pid);
        if (count($arr_comment) == count($arr_pid) and count($arr_pid) == count($arr_star)) {
            for ($i = 0; $i < count($arr_comment); $i++) {
                $star = $arr_star[$i];
                $comment = $arr_comment[$i];
                $pid = $arr_pid[$i];
                DB::select('insert into feedback values (:uid, :pid, :comment, :star, :rid)', [
                    'uid' => $request->uid,
                    'rid' => $request->rid,
                    'pid' => $pid,
                    'star' => $star,
                    'comment' => $comment
                ]);
            }
        }
        return response()->json();
    }
    public function changePass(Request $request)
    {
        session(['prePage' => '/account/profile']);
        if (session('login') != 'true') {
            return response()->json(['redirect', '/login']);
        } else {
            $request->validate([
                'old_pass' => 'required',
                'new_pass' => 'required',
                'confirm_pass' => 'required'
            ]);
            $uid = session('user');
            $user = DB::select('select * from member where mem_id= :uid', [
                'uid' => $uid,
            ]);
            if ($user[0]->password != $request->old_pass) {
                return response()->json([
                    'success' => 'fail',
                    'error' => 'pass'
                ]);
            }
            if ($request->new_pass != $request->confirm_pass) {
                return response()->json([
                    'success' => 'fail',
                    'error' => 'confirm'
                ]);
            }

            DB::select('update member set password= :pass where mem_id= :uid', [
                'uid' => $uid,
                'pass' => $request->new_pass
            ]);
            return response()->json(['success' => 'true']);
        }
    }


    public function changeProfile(Request $request)
    {
        session(['prePage' => '/account']);
        if (session('login') != 'true') {
            return response()->json(['redirect', '/login']);
        } else {
            $request->validate([
                'name' => 'required',
                'phone' => 'required|digits:10|max:10|min:10',
                'address' => 'required'
            ]);
            $query = DB::select('update member set name= :name, phone= :phone,address= :address where mem_id= :uid', [
                'name' => $request->name,
                'phone' => $request->phone,
                'address' => $request->address,
                'uid' => session('user')
            ]);

            return response()->json(['name' => $request->name]);
        }
    }






    // API

    public function loadPage_api()
    {
        try {
            // session(['prePage' => '/account/profile']);
            // if (session('login') != 'true') {
            //     return redirect('/login');
            // } 
            // else {
                $uid = session('user');
                $user = DB::select('select * from member where mem_id= :uid', [
                    'uid' => $uid,
                ]);
                if ($user[0]->phone == 0) {
                    $user[0]->phone = null;
                }
                $brands = new HeaderController();
                // return view('user/profile',[
                //     'user' => $user[0],
                //     'brands' => $brands->load()
                // ]);
                // return new UserCollection($user[0],$brands);

                return response()->json(['status' => 'success', 'brands' => $brands, 'user' => $user[0]]);
            // }
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'error' => $e->getMessage()]);
        }
    }
    public function goLink_api($link, Request $request)
    {
        try {
            // session(['prePage' => '/account/' . $link]);
            // if (session('login') != 'true') {
            //     return redirect('/login');
            // } else {
                $uid = session('user');
                $user = DB::select('select * from member where mem_id= :uid', [
                    'uid' => $uid,
                ]);
                if ($user[0]->phone == 0) {
                    $user[0]->phone = null;
                }
                $brands = new HeaderController();
                if ($link == 'order') {
                    $orders = DB::select('select order.*,feedback.comment from order 
                    left join feedback on feedback.order_id = order.order_id
                    where order.mem_id= :uid order by order.order_id desc', [
                        'uid' => $uid
                    ]);
                    $product_orders = DB::select('select order.*,product_size_color.product_image,product.*,product_order.* from order 
                    inner join product_order on product_order.order_id = order.order_id 
                    inner join product on product.product_id = product_order.product_id
                    inner join product_size_color on product_size_color.size = product_order.size and 
                        product_size_color.color = product_order.color and product_size_color.product_id = product_order.product_id
                        where order.mem_id= :uid', [
                        'uid' => $uid
                    ]);
                    return view('user/' . $link, [
                        'user' => $user[0],
                        'brands' => $brands->load(),
                        'orders' => $orders,
                        'product_orders' => $product_orders
                    ]);
                }
                // return view('user/' . $link, [
                //     'user' => $user[0],
                //     'brands' => $brands->load()
                // ]);
                return response()->json([
                    'status' => 'success',
                    'user' => $user[0],
                    'brands' => $brands->load()
                ]);
            // }
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'error' => $e->getMessage()]);
        }
    }

    public function orderStatus_api($status)
    {
        try {
            $uid = session('user');
            $user = DB::select('select * from member where mem_id= :uid', [
                'uid' => $uid,
            ]);
            if ($user[0]->phone == 0) {
                $user[0]->phone = null;
            }
            $brands = new HeaderController();
            $orders = DB::select('select order.*,feedback.comment from order 
                left join feedback on feedback.order_id = order.order_id
                where order.mem_id= :uid and order_status= :status order by order.order_id desc', [
                'uid' => $uid,
                'status' => $status
            ]);
            $product_orders = DB::select('select order.*,product_size_color.product_image,product.*,product_order.* from order 
                inner join product_order on product_order.order_id = order.order_id 
                inner join product on product.product_id = product_order.product_id
                inner join product_size_color on product_size_color.size = product_order.size and 
                    product_size_color.color = product_order.color and product_size_color.product_id = product_order.product_id
                where mem_id= :uid and  order_status= :status', [
                'status' => $status,
                'uid' => $uid
            ]);

            // return view('user/order', [
            //     'user' => $user[0],
            //     'brands' => $brands->load(),
            //     'orders' => $orders,
            //     'product_orders' => $product_orders
            // ]);
            return response()->json([
                'status' => 'success',
                'user' => $user[0],
                'brands' => $brands->load(),
                'orders' => $orders,
                'product_orders' => $product_orders
            ]);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'error' => $e->getMessage()]);
        }
    }
    public function droporder_api(Request $request)
    {
        try {
            $request->validate([
                'rid' => 'required',
                'uid' => 'required'
            ]);
            $check = DB::select('select * from order where order_id=:rid and mem_id = :uid', [
                'rid' => $request->rid,
                'uid' => $request->uid
            ]);
            if ($check) {
                if ($check[0]->order_status == 0) {
                    DB::select('update order set order_status= -1 where order_id= :rid', [
                        'rid' => $request->rid
                    ]);
                    $count = DB::select('select count(*) as TongDonHang from order where order_status= 0 and mem_id= :uid', [
                        'uid' => $request->uid
                    ]);
                    // return response()->json([
                    //     'drop' => 'success',
                    //     'count' => $count[0]->TongDonHang
                    // ]);
                    return response()->json(['status' => 'success', 'message' => 'Xóa đơn hàng thành công!', 'count' => $count[0]->TongDonHang], 201);
                } else {
                    return response()->json(['drop' => 'fail']);
                }
            } else {
                return response()->json(['drop' => 'fail']);
            }
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'drop' => 'fail', 'error' => $e->getMessage()], 500);
        }
    }
    public function confirmorder_api(Request $request)
    {
        try {
            $request->validate([
                'rid' => 'required',
                'uid' => 'required'
            ]);
            $check = DB::select('select * from order where order_id=:rid and mem_id = :uid', [
                'rid' => $request->rid,
                'uid' => $request->uid
            ]);
            if ($check) {
                if ($check[0]->order_status == 1) {
                    DB::select('update order set order_status = 2 where order_id= :rid', [
                        'rid' => $request->rid
                    ]);
                    $count = DB::select('select count(*) as TongDonHang from order where order_status= 1 and mem_id= :uid', [
                        'uid' => $request->uid
                    ]);
                    // return response()->json([
                    //     'confirm' => 'success',
                    //     'count' => $count[0]->TongDonHang
                    // ]);
                    return response()->json(['status' => 'success', 'message' => 'Xác nhận thành công!', 'count' => $count[0]->TongDonHang], 201);
                } else {
                    return response()->json(['status' => 'error', 'message' => 'Xác nhận thất bại'], 500);
                }
            } else {
                return response()->json(['status' => 'error', 'message' => 'Xác nhận thất bại'], 500);
            }
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Xác nhận thất bại', 'error' => $e->getMessage()], 500);
        }
    }

    public function writeRequest_api(Request $request)
    {
        try {
            $request->validate([
                'rid' => 'required'
            ]);
            $check = DB::select('select * from feedback where order_id= :rid', [
                'rid' => $request->rid
            ]);
            if ($check) {
                return response()->json(['write' => 'false']);
            } else {
                $products = DB::select('SELECT * FROM `product_order` 
                    inner join product_size_color on product_size_color.product_id = product_order.product_id 
                        and product_size_color.size = product_order.size and product_size_color.color = product_order.color
                    inner join product on product.product_id = product_order.product_id
                    where product_order.order_id = :rid and product.product_active = 1', [
                    'rid' => $request->rid
                ]);
                if (!$products) {
                    return response()->json();
                }
                foreach ($products as $product) {
                    $product->product_image = explode(',', $product->product_image)[0];
                }
                $pid = DB::select('select product_order.product_id from product_order 
                inner join product on product.product_id = product_order.product_id
                where product_order.order_id = :rid and product.product_active = 1
                group by product_order.product_id', [
                    'rid' => $request->rid
                ]);
                // return response()->json([
                //     'write' => 'true',
                //     'products' => $products,
                //     'pid' => $pid
                // ]);
                return response()->json([
                    'status' => 'success',
                    'message' => 'Viết đánh giá thành công!',
                    'products' => $products,
                    'pid' => $pid
                ], 201);
            }
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Viết đánh giá thất bại', 'error' => $e->getMessage()], 500);
        }
    }
    public function readRequest_api(Request $request)
    {
        try {
            $request->validate([
                'rid' => 'required',
                'uid' => 'required'
            ]);
            $products = DB::select('select * from feedback 
                inner join product on product.product_id = feedback.product_id
                inner join product_order on product_order.order_id = feedback.order_id and product_order.product_id = feedback.product_id
                inner join product_size_color on product_size_color.product_id = product_order.product_id
                    and product_size_color.size = product_order.size and product_size_color.color = product_order.color
                inner join member on member.mem_id = feedback.mem_id
                where feedback.order_id = :rid and feedback.mem_id = :uid and product.product_active = 1', [
                'rid' => $request->rid,
                'uid' => $request->uid
            ]);
            if (!$products) {
                return response()->json();
            }
            $feedbacks = DB::select('select feedback.*,member.name from feedback 
            inner join member on feedback.mem_id = member.mem_id
            where feedback.order_id = :rid and feedback.mem_id = :uid
            group by feedback.product_id,feedback.comment,feedback.star,feedback.order_id,feedback.mem_id,member.name', [
                'rid' => $request->rid,
                'uid' => $request->uid
            ]);
            if ($products && $feedbacks) {
                foreach ($products as $product) {
                    $product->product_image = explode(',', $product->product_image)[0];
                }
                // return response()->json([
                //     'read' => 'true',
                //     'products' => $products,
                //     'feedbacks' => $feedbacks
                // ]);
                return response()->json([
                    'status' => 'success',
                    'message' => 'Xem đánh giá thành công!',
                    'products' => $products,
                    'feedbacks' => $feedbacks
                ], 201);
            } else {
                // return response()->json(['read' => 'fail']);
                return response()->json(['status' => 'error', 'message' => 'Xem đánh giá thất bại'], 500);
            }
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Xem đánh giá thất bại', 'error' => $e->getMessage()], 500);
        }
    }
    public function addFeedback_api(Request $request)
    {
        try {
            $request->validate([
                'uid' => 'required',
                'rid' => 'required',
                'pid' => 'required',
                'star' => 'required',
                'comment' => 'required'
            ]);
            $arr_star = explode('|! ', $request->star);
            $arr_comment = explode('|! ', $request->comment);
            $arr_pid = explode('|! ', $request->pid);
            if (count($arr_comment) == count($arr_pid) and count($arr_pid) == count($arr_star)) {
                for ($i = 0; $i < count($arr_comment); $i++) {
                    $star = $arr_star[$i];
                    $comment = $arr_comment[$i];
                    $pid = $arr_pid[$i];
                    DB::select('insert into feedback values (:uid, :pid, :comment, :star, :rid)', [
                        'uid' => $request->uid,
                        'rid' => $request->rid,
                        'pid' => $pid,
                        'star' => $star,
                        'comment' => $comment
                    ]);
                }
            }
            // return response()->json();
            return response()->json(['status' => 'success', 'message' => 'Thêm đánh giá thành công!'], 201);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Thêm đánh giá thất bại', 'error' => $e->getMessage()], 500);
        }
    }
    public function changePass_api(Request $request)
    {
        try {
            // session(['prePage' => '/account/profile']);
            // if (session('login') != 'true') {
            //     return response()->json(['redirect', '/login']);
            // } else {
                $request->validate([
                    'old_pass' => 'required',
                    'new_pass' => 'required',
                    'confirm_pass' => 'required'
                ]);
                $uid = session('user');
                $user = DB::select('select * from member where mem_id= :uid', [
                    'uid' => $uid,
                ]);
                if ($user[0]->password != $request->old_pass) {
                    return response()->json([
                        'success' => 'fail',
                        'error' => 'pass'
                    ]);
                }
                if ($request->new_pass != $request->confirm_pass) {
                    return response()->json([
                        'success' => 'fail',
                        'error' => 'confirm'
                    ]);
                }

                DB::select('update member set password= :pass where mem_id= :uid', [
                    'uid' => $uid,
                    'pass' => $request->new_pass
                ]);
                // return response()->json(['success' => 'true']);
                return response()->json(['status' => 'success', 'message' => 'Thay đổi mật khẩu thành công!'], 201);
            // }
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Thay đổi mật khẩu thất bại', 'error' => $e->getMessage()], 500);
        }
    }


    public function changeProfile_api(Request $request)
    {
        try {
            // session(['prePage' => '/account']);
            // if (session('login') != 'true') {
            //     return response()->json(['redirect', '/login']);
            // } else {
                $request->validate([
                    'name' => 'required',
                    'phone' => 'required|digits:10|max:10|min:10',
                    'address' => 'required'
                ]);
                $query = DB::select('update member set name= :name, phone= :phone,address= :address where mem_id= :uid', [
                    'name' => $request->name,
                    'phone' => $request->phone,
                    'address' => $request->address,
                    'uid' => session('user')
                ]);

                // return response()->json(['name' => $request->name]);
                return response()->json(['status' => 'success', 'message' => 'Thay đổi thông tin thành công!', 'name' => $request->name], 201);
            // }
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Thay đổi thông tin thất bại', 'error' => $e->getMessage()], 500);
        }
    }
}
