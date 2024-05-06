<?php

namespace App\Http\Controllers\admin;


use App\Http\Services\OrderService;
// use App\Models\order;
use App\Models\admin\order;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class OrderController extends Controller
{
    //[GET] /admin/order (use)
    public function index()
    {
        $searchName = request('searchName');
        if (isset($searchName)) {
            $count = order::where('order_status', '=', 1)->where('validated_date', '=', $searchName)->count('order_id');
            $get = DB::table('order')->where('order_status', '=', 1)->where('validated_date', '=', $searchName)->orderBy('order_id', 'desc')->paginate(10);
        } else {
            $count = order::where('order_status', '=', 1)->count('order_id');
            $get = DB::table('order')->where('order_status', '=', 1)->orderBy('order_id', 'desc')->paginate(10);
        }
        return view('admin.order.confirm', [

            'orders' => $get,
            'title' => 'List order Confirm',
            'count' => $count,
            'searchName' => $searchName
        ]);
    }

    //[GET] admin/order_unconfimred (use)
    public function index_unconfimred()
    {
        $searchName = request('searchName');
        if (isset($searchName)) {
            $count = order::where('order_status', '=', 0)->where('created_date', '=', $searchName)->count('order_id');
            $get = DB::table('order')->where('order_status', '=', 0)->where('created_date', '=', $searchName)->orderBy('order_id', 'desc')->paginate(10);
        } else {
            $count = order::where('order_status', '=', 0)->count('order_id');
            $get = DB::table('order')->where('order_status', '=', 0)->orderBy('order_id', 'desc')->paginate(10);
        }

        return view('admin.order.unconfimred', [

            'orders' => $get,
            'title' => 'List order Unconfimred',
            'count' => $count,
            'searchName' => $searchName
        ]);
    }

    // [GET] /admin/order_canceled (use)
    public function index_canceled()
    {
        $searchName = request('searchName');
        if (isset($searchName)) {
            $count = order::where('order_status', '=', -1)->where('canceled_date', '=', $searchName)->count('order_id');
            $get = DB::table('order')->where('order_status', '=', -1)->where('canceled_date', '=', $searchName)->orderBy('order_id', 'desc')->paginate(10);
        } else {
            $count = order::where('order_status', '=', -1)->count('order_id');
            $get = DB::table('order')->where('order_status', '=', -1)->orderBy('order_id', 'desc')->paginate(10);
        }

        return view('admin.order.canceled_confirm', [

            'orders' => $get,
            'title' => 'List order Canceled',
            'count' => $count,
            'searchName' => $searchName
        ]);
    }

    //[GET] /admin/order_finished (use)
    public function index_finished()
    {
        $searchName = request('searchName');
        if (isset($searchName)) {
            $count = order::where('order_status', '=', 2)->where('completion_date', '=', $searchName)->count('order_id');
            $get = DB::table('order')->where('order_status', '=', 2)->where('completion_date', '=', $searchName)->orderBy('order_id', 'desc')->paginate(10);
        } else {
            $count = order::where('order_status', '=', 2)->count('order_id');
            $get = DB::table('order')->where('order_status', '=', 2)->orderBy('order_id', 'desc')->paginate(10);
        }

        return view('admin.order.finished', [

            'orders' => $get,
            'title' => 'List order Completed',
            'count' => $count,
            'searchName' => $searchName
        ]);
    }

    public function revenue_finished()
    {
        $searchName = request('searchName');
        if (isset($searchName)) {
            $count = order::where('order_status', '=', 2)->where('completion_date', '=', $searchName)->count('order_id');
            $get = DB::table('order')->where('order_status', '=', 2)->where('completion_date', '=', $searchName)->orderBy('order_id', 'desc')->paginate(10);
        } else {
            $count = order::where('order_status', '=', 2)->count('order_id');
            $get = DB::table('order')->where('order_status', '=', 2)->orderBy('order_id', 'desc')->paginate(10);
        }

        return view('admin.order.Revenuefinished', [

            'orders' => $get,
            'title' => 'Order Statistics',
            'count' => $count,
            'searchName' => $searchName
        ]);
    }

    // [GET] /admin/detail/{order} (use)
    public function detail($order_id)
    {

        $product_orders = DB::select("select * from product inner join product_order on product_order.product_id=product.product_id
        inner join `order` on `order`.order_id = product_order.order_id 
        inner join member on order.mem_id = member.mem_id 
        where  `order`.order_id= :order_id", [
            'order_id' => $order_id
        ]);
        for ($i = 0; $i < count($product_orders); $i++) {
            $img = DB::select('select product.product_id,product_size_color.product_image from product 
                inner join product_size_color on product_size_color.product_id = product.product_id
                where product.product_active = 1 and product.product_id = :pid
                GROUP by product_id,product_size_color.product_image', [
                'pid' => $product_orders[$i]->product_id
            ]);
            $product_orders[$i]->product_image = $img[0]->product_image;
        }

        $product_order = DB::select("select * from ((product inner join product_order on product_order.product_id=product.product_id)
         inner join `order` on `order`.order_id = product_order.order_id)
         inner join member on `order`.mem_id = member.mem_id  where  `order`.order_id= :order_id", [
            'order_id' => $order_id
        ]);


        return view('admin/order/detail', [
            'product_orders' => $product_orders,
            'product_order' => $product_order[0],
            'title' => 'Detail order'
        ]);
    }

    //[GET] /admin/edit/{order} (use)
    public function edit($order_id)
    {

        $product_orders = DB::select("select * from product inner join product_order on product_order.product_id=product.product_id
        inner join `order` on `order`.order_id = product_order.order_id 
        inner join member on `order`.mem_id = member.mem_id 
        where  `order`.order_id= :order_id", [
            'order_id' => $order_id
        ]);
        for ($i = 0; $i < count($product_orders); $i++) {
            $img = DB::select('select product.product_id,product_size_color.product_image from product 
                inner join product_size_color on product_size_color.product_id = product.product_id
                where product.product_active = 1 and product.product_id = :pid
                GROUP by product_id,product_size_color.product_image', [
                'pid' => $product_orders[$i]->product_id
            ]);
            $product_orders[$i]->product_image = $img[0]->product_image;
        }

        $product_order = DB::select("select * from ((product inner join product_order on product_order.product_id=product.product_id)
         inner join `order` on `order`.order_id = product_order.order_id)
         inner join member on `order`.mem_id = member.mem_id  where  `order`.order_id= :order_id", [
            'order_id' => $order_id
        ]);

        return view('admin/order/edit_confirm', [
            'product_orders' => $product_orders,
            'product_order' => $product_order[0],
            'title' => 'Edit order'
        ]);
    }

    //[POST] /delorder (use)
    public function delorder(Request $request)
    {
        $get = $request->all();
        // dd($get['did']);
        order::where('order_id', $get['did'])->update([
            'order_status' => -1
        ]);
    }

    //[POST] /admin/edit/{order} (use)
    public function postedit($order_id)
    {
        try {
            DB::select("update `order` set order_status=1,validated_date=current_timestamp() where order_id= :order_id ", [
                'order_id' => $order_id
            ]);

            session()->regenerate();
            Session()->flash('success', 'Xác nhận thành công');
        } catch (Exception $ex) {
            // Session()->flash('success','');
            session()->regenerate();
            Session()->flash('error', 'Xác nhận thất bại');
            return redirect('/admin/edit/' . $order_id);
        }
        return redirect('admin/order');
    }

    //[POST] /admin/cancel_edit/{order}
    // public function canceledit($order_id)
    // {
    //     try {
    //         DB::select("update `order` set order_status=-1,canceled_date=current_timestamp() where order_id= :order_id ", [
    //             'order_id' => $order_id
    //         ]);

    //         $products = DB::table('product_order')
    //             ->where('order_id', '=', $order_id)
    //             ->get();
    //         foreach ($products as $product) {
    //             DB::table('product_size_color')
    //                 ->where('size', '=', $product->size)
    //                 ->where('color', '=', $product->color)
    //                 ->where('product_id', '=', $product->product_id)
    //                 ->increment('quantity', $product->quantity);
    //         }

    //         session()->regenerate();
    //         Session()->flash('success', 'Xác nhận thành công');
    //     } catch (Exception $ex) {
    //         // Session()->flash('success','');
    //         session()->regenerate();
    //         Session()->flash('error', 'Xác nhận thất bại');
    //         return redirect('/admin/edit/' . $order_id);
    //     }
    //     return redirect('admin/order');
    // }

    public function canceledit($order_id)
    {
        DB::beginTransaction();

        try {
            DB::update("update `order` set order_status = -1, canceled_date = current_timestamp() where order_id = :order_id", [
                'order_id' => $order_id
            ]);

            $products = DB::table('product_order')
                ->where('order_id', '=', $order_id)
                ->get();

            foreach ($products as $product) {
                DB::table('product_size_color')
                    ->where('size', '=', $product->size)
                    ->where('color', '=', $product->color)
                    ->where('product_id', '=', $product->product_id)
                    ->increment('quantity', $product->quantity);
            }

            DB::commit();

            session()->regenerate();
            Session()->flash('success', 'Xác nhận thành công');
        } catch (\Exception $ex) {
            DB::rollBack();

            session()->regenerate();
            Session()->flash('error', 'Xác nhận thất bại');
            return redirect('/admin/edit/' . $order_id);
        }

        return redirect('admin/order');
    }








    // API

    public function index_api()
    {
        try {
            $searchName = request('searchName');
            if (isset($searchName)) {
                $count = order::where('order_status', '=', 1)->where('validated_date', '=', $searchName)->count('order_id');
                $get = DB::table('order')->where('order_status', '=', 1)->where('validated_date', '=', $searchName)->orderBy('order_id', 'desc')->paginate(10);
            } else {
                $count = order::where('order_status', '=', 1)->count('order_id');
                $get = DB::table('order')->where('order_status', '=', 1)->orderBy('order_id', 'desc')->paginate(10);
            }

            return response()->json([
                'status' => 'success',
                'orders' => $get,
                'count' => $count,
                'searchName' => $searchName
            ]);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'error' => $e->getMessage()]);
        }
    }
    public function index_unconfimred_api()
    {
        try {
            $searchName = request('searchName');
            if (isset($searchName)) {
                $count = order::where('order_status', '=', 0)->where('created_date', '=', $searchName)->count('order_id');
                $get = DB::table('order')->where('order_status', '=', 0)->where('created_date', '=', $searchName)->orderBy('order_id', 'desc')->paginate(10);
            } else {
                $count = order::where('order_status', '=', 0)->count('order_id');
                $get = DB::table('order')->where('order_status', '=', 0)->orderBy('order_id', 'desc')->paginate(10);
            }
            return response()->json([
                'status' => 'success',
                'orders' => $get,
                'count' => $count,
                'searchName' => $searchName
            ]);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'error' => $e->getMessage()]);
        }
    }
    public function index_canceled_api()
    {
        try {
            $searchName = request('searchName');
            if (isset($searchName)) {
                $count = order::where('order_status', '=', -1)->where('canceled_date', '=', $searchName)->count('order_id');
                $get = DB::table('order')->where('order_status', '=', -1)->where('canceled_date', '=', $searchName)->orderBy('order_id', 'desc')->paginate(10);
            } else {
                $count = order::where('order_status', '=', -1)->count('order_id');
                $get = DB::table('order')->where('order_status', '=', -1)->orderBy('order_id', 'desc')->paginate(10);
            }

            return response()->json([
                'status' => 'success',
                'orders' => $get,
                'count' => $count,
                'searchName' => $searchName
            ]);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'error' => $e->getMessage()]);
        }
    }
    public function index_finished_api()
    {
        try {
            $searchName = request('searchName');
            if (isset($searchName)) {
                $count = order::where('order_status', '=', 2)->where('completion_date', '=', $searchName)->count('order_id');
                $get = DB::table('order')->where('order_status', '=', 2)->where('completion_date', '=', $searchName)->orderBy('order_id', 'desc')->paginate(10);
            } else {
                $count = order::where('order_status', '=', 2)->count('order_id');
                $get = DB::table('order')->where('order_status', '=', 2)->orderBy('order_id', 'desc')->paginate(10);
            }
            return response()->json([
                'status' => 'success',
                'orders' => $get,
                'count' => $count,
                'searchName' => $searchName
            ]);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'error' => $e->getMessage()]);
        }
    }
    public function detail_api($order_id)
    {
        try {
            $product_orders = DB::select("select * from product inner join product_order on product_order.product_id=product.product_id
            inner join `order` on `order`.order_id = product_order.order_id 
            inner join member on `order`.mem_id = member.mem_id 
            where  `order`.order_id= :order_id", [
                'order_id' => $order_id
            ]);
            for ($i = 0; $i < count($product_orders); $i++) {
                $img = DB::select('select product.product_id,product_size_color.product_image from product 
                    inner join product_size_color on product_size_color.product_id = product.product_id
                    where product.product_active = 1 and product.product_id = :pid
                    GROUP by product_id,product_size_color.product_image', [
                    'pid' => $product_orders[$i]->product_id
                ]);
                $product_orders[$i]->product_image = $img[0]->product_image;
            }

            $product_order = DB::select("select * from ((product inner join product_order on product_order.product_id=product.product_id)
             inner join `order` on `order`.order_id = product_order.order_id)
             inner join member on `order`.mem_id = member.mem_id  where  `order`.order_id= :order_id", [
                'order_id' => $order_id
            ]);

            return response()->json([
                'status' => 'success',
                'product_orders' => $product_orders,
                'product_order' => $product_order[0],
            ]);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'error' => $e->getMessage()]);
        }
    }
    public function edit_api($order_id)
    {
        try {
            $product_orders = DB::select("select * from product inner join product_order on product_order.product_id=product.product_id
            inner join `order` on `order`.order_id = product_order.order_id 
            inner join member on `order`.mem_id = member.mem_id 
            where  `order`.order_id= :order_id", [
                'order_id' => $order_id
            ]);
            for ($i = 0; $i < count($product_orders); $i++) {
                $img = DB::select('select product.product_id,product_size_color.product_image from product 
                    inner join product_size_color on product_size_color.product_id = product.product_id
                    where product.product_active = 1 and product.product_id = :pid
                    GROUP by product_id,product_size_color.product_image', [
                    'pid' => $product_orders[$i]->product_id
                ]);
                $product_orders[$i]->product_image = $img[0]->product_image;
            }

            $product_order = DB::select("select * from ((product inner join product_order on product_order.product_id=product.product_id)
             inner join `order` on `order`.order_id = product_order.order_id)
             inner join member on `order`.mem_id = member.mem_id  where  `order`.order_id= :order_id", [
                'order_id' => $order_id
            ]);

            return response()->json([
                'status' => 'success',
                'product_orders' => $product_orders,
                'product_order' => $product_order[0],
            ]);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'error' => $e->getMessage()]);
        }
    }
    public function delorder_api(Request $request)
    {
        try {
            $get = $request->all();
            order::where('order_id', $get['did'])->update([
                'order_status' => -1
            ]);
            return response()->json(['status' => 'success', 'message' => 'Hủy đơn hàng thành công!'], 201);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Hủy đơn hàng thất bại', 'error' => $e->getMessage()], 500);
        }
    }

    public function postedit_api($order_id)
    {
        try {
            DB::select("update `order` set order_status=1,validated_date=current_timestamp() where order_id= :order_id ", [
                'order_id' => $order_id
            ]);

            $products = DB::table('product_order')
                ->where('order_id', '=', $order_id)
                ->get();
            foreach ($products as $product) {
                DB::table('product_size_color')
                    ->where('size', '=', $product->size)
                    ->where('color', '=', $product->color)
                    ->where('product_id', '=', $product->product_id)
                    ->decrement('quantity', $product->quantity);
            }

            session()->regenerate();
            Session()->flash('success', 'Xác nhận thành công');
            return response()->json(['status' => 'success', 'message' => 'Xác nhận thành công!'], 201);
        } catch (\Exception $e) {
            // Session()->flash('success','');
            session()->regenerate();
            Session()->flash('error', 'Xác nhận thất bại');
            // return redirect('/admin/edit/' . $order_id);
            return response()->json(['status' => 'error', 'message' => 'Xác nhận thất bại', 'error' => $e->getMessage()], 500);
        }
        // return redirect('admin/order');
    }
    public function canceledit_api($order_id)
    {
        try {

            DB::select("update `order` set order_status=-1,canceled_date=current_timestamp() where order_id= :order_id ", [
                'order_id' => $order_id
            ]);
            session()->regenerate();
            Session()->flash('success', 'Hủy thành công');
            return response()->json(['status' => 'success', 'message' => 'Hủy đơn hàng thành công!'], 201);
        } catch (\Exception $e) {
            session()->regenerate();
            Session()->flash('error', 'Hủy thất bại');
            // return redirect('/admin/edit/' . $order_id);
            return response()->json(['status' => 'error', 'message' => 'Hủy đơn hàng thất bại', 'error' => $e->getMessage()], 500);
        }
        // return redirect('admin/order_canceled');
    }
}
