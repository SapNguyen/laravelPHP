<?php

namespace App\Http\Controllers\user;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;

use Illuminate\Http\Request;

class AccountController extends Controller
{
    public function loadPage()
    {
        session(['prePage'=>'/account/profile']);
        if(session('login') != 'true'){
            return redirect('/login');
        }else{
            $uid = session('user');
            $user = DB::select('select * from member where mem_id= :uid',[
                'uid' => $uid,
            ]);
            if($user[0]->phone == 0){
                $user[0]->phone = null;
            }
            $brands = new HeaderController();
            return view('user/profile',[
                'user' => $user[0],
                'brands' => $brands->load()
            ]);
        }
    }
    public function goLink($link, Request $request)
    {
        session(['prePage'=>'/account/'.$link]);
        if(session('login') != 'true'){
            return redirect('/login');
        }else{
            $uid = session('user');
            $user = DB::select('select * from member where mem_id= :uid',[
                'uid' => $uid,
            ]);
            if($user[0]->phone == 0){
                $user[0]->phone = null;
            }
            $brands = new HeaderController();
            if($link == 'receipt'){
                $receipts = DB::select('select receipt.*,feedback.comment from receipt 
                left join feedback on feedback.receipt_id = receipt.receipt_id
                where receipt.mem_id= :uid order by receipt.receipt_id desc',[
                    'uid' => $uid
                ]);
                $receipt_products = DB::select('select receipt.*,product_size_color.product_image,product.*,receipt_product.* from receipt 
                inner join receipt_product on receipt_product.receipt_id = receipt.receipt_id 
                inner join product on product.product_id = receipt_product.product_id
                inner join product_size_color on product_size_color.size = receipt_product.size and 
                    product_size_color.color = receipt_product.color and product_size_color.product_id = receipt_product.product_id
                    where receipt.mem_id= :uid',[
                        'uid' => $uid
                ]);
                return view('user/'.$link,[
                    'user' => $user[0],
                    'brands' => $brands->load(),
                    'receipts' => $receipts,
                    'receipt_products' => $receipt_products
                ]);
            }
            return view('user/'.$link,[
                'user' => $user[0],
                'brands' => $brands->load()
            ]);
        }
    }

    public function receiptStatus($status)
    {
        $uid = session('user');
        $user = DB::select('select * from member where mem_id= :uid',[
            'uid' => $uid,
        ]);
        if($user[0]->phone == 0){
            $user[0]->phone = null;
        }  
        $brands = new HeaderController();  
        $receipts = DB::select('select receipt.*,feedback.comment from receipt 
            left join feedback on feedback.receipt_id = receipt.receipt_id
            where receipt.mem_id= :uid and receipt_status= :status order by receipt.receipt_id desc',[
                    'uid' => $uid,
                    'status' =>$status
        ]);
        $receipt_products = DB::select('select receipt.*,product_size_color.product_image,product.*,receipt_product.* from receipt 
            inner join receipt_product on receipt_product.receipt_id = receipt.receipt_id 
            inner join product on product.product_id = receipt_product.product_id
            inner join product_size_color on product_size_color.size = receipt_product.size and 
                product_size_color.color = receipt_product.color and product_size_color.product_id = receipt_product.product_id
            where mem_id= :uid and  receipt_status= :status',[
                'status' => $status,
                'uid' => $uid
        ]);        
        
        return view('user/receipt',[
            'user' => $user[0],
            'brands' => $brands->load(),
            'receipts' => $receipts,
            'receipt_products' => $receipt_products
        ]);
    }
    public function dropReceipt(Request $request)
    {
        $request->validate([
            'rid' => 'required',
            'uid' => 'required'
        ]);
        $check = DB::select('select * from receipt where receipt_id=:rid and mem_id = :uid',[
            'rid' => $request->rid,
            'uid' => $request->uid
        ]);
        if($check){
            if($check[0]->receipt_status == 0){
                DB::select('update receipt set receipt_status= -1 where receipt_id= :rid',[
                    'rid' => $request->rid
                ]);
                $count = DB::select('select count(*) as TongDonHang from receipt where receipt_status= 0 and mem_id= :uid',[
                    'uid' => $request->uid
                ]);
                return response()->json([
                    'drop'=>'success',
                    'count' => $count[0]->TongDonHang
                ]);
            }
            else{
                return response()->json(['drop'=>'fail']);
            }
        }
        else{
            return response()->json(['drop'=>'fail']);
        }
    }
    public function confirmReceipt(Request $request)
    {
        $request->validate([
            'rid' => 'required',
            'uid' => 'required'
        ]);
        $check = DB::select('select * from receipt where receipt_id=:rid and mem_id = :uid',[
            'rid' => $request->rid,
            'uid' => $request->uid
        ]);
        if($check){
            if($check[0]->receipt_status == 1){
                DB::select('update receipt set receipt_status = 2 where receipt_id= :rid',[
                    'rid' => $request->rid
                ]);
                $count = DB::select('select count(*) as TongDonHang from receipt where receipt_status= 1 and mem_id= :uid',[
                    'uid' => $request->uid
                ]);
                return response()->json([
                    'confirm'=>'success',
                    'count' => $count[0]->TongDonHang
                ]);
            }
            else{
                return response()->json(['confirm'=>'fail']);
            }
        }
        else{
            return response()->json(['confirm'=>'fail']);
        }
    }

    public function writeRequest(Request $request)
    {
        $request->validate([
            'rid' => 'required'
        ]);
        $check = DB::select('select * from feedback where receipt_id= :rid',[
            'rid' => $request->rid
        ]);
        if($check){
            return response()->json(['write'=>'false']);
        }
        else{
            $products = DB::select('SELECT * FROM `receipt_product` 
                inner join product_size_color on product_size_color.product_id = receipt_product.product_id 
                    and product_size_color.size = receipt_product.size and product_size_color.color = receipt_product.color
                inner join product on product.product_id = receipt_product.product_id
                where receipt_product.receipt_id = :rid and product.product_active = 1',[
                'rid' => $request->rid
            ]);
            if(!$products){
                return response()->json();
            }
            foreach ($products as $product) {
                $product->product_image = explode(',',$product->product_image)[0];
            }
            $pid = DB::select('select receipt_product.product_id from receipt_product 
            inner join product on product.product_id = receipt_product.product_id
            where receipt_product.receipt_id = :rid and product.product_active = 1
            group by receipt_product.product_id',[
                'rid' => $request->rid
            ]);
            return response()->json([
                'write'=>'true',
                'products' =>$products,
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
            inner join receipt_product on receipt_product.receipt_id = feedback.receipt_id and receipt_product.product_id = feedback.product_id
            inner join product_size_color on product_size_color.product_id = receipt_product.product_id
                and product_size_color.size = receipt_product.size and product_size_color.color = receipt_product.color
            inner join member on member.mem_id = feedback.mem_id
            where feedback.receipt_id = :rid and feedback.mem_id = :uid and product.product_active = 1',[
                'rid' => $request->rid,
                'uid' => $request->uid
        ]);
        if(!$products){
            return response()->json();
        }
        $feedbacks = DB::select('select feedback.*,member.name from feedback 
        inner join member on feedback.mem_id = member.mem_id
        where feedback.receipt_id = :rid and feedback.mem_id = :uid
        group by feedback.product_id,feedback.comment,feedback.star,feedback.receipt_id,feedback.mem_id,member.name',[
            'rid' => $request->rid,
            'uid' => $request->uid
        ]);
        if ($products && $feedbacks) {
            foreach ($products as $product) {
                $product->product_image = explode(',',$product->product_image)[0];
            }
            return response()->json([
                'read'=>'true',
                'products' => $products,
                'feedbacks' => $feedbacks
            ]);
        }
        else{
            return response()->json(['read'=>'fail']);
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
        $arr_star = explode('|! ',$request->star);
        $arr_comment = explode('|! ',$request->comment);
        $arr_pid = explode('|! ',$request->pid);
        if(count($arr_comment) == count($arr_pid) and count($arr_pid) == count($arr_star)){
            for ($i=0; $i < count($arr_comment); $i++) { 
               $star = $arr_star[$i];
               $comment = $arr_comment[$i];
               $pid = $arr_pid[$i];
               DB::select('insert into feedback values (:uid, :pid, :comment, :star, :rid)',[
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
        if(session('login') != 'true'){
            return response()->json(['redirect','/login']);
        }
        else{
            $request->validate([
                'old_pass' => 'required',
                'new_pass' => 'required',
                'confirm_pass' => 'required'
            ]);
            $uid = session('user');
            $user = DB::select('select * from member where mem_id= :uid',[
                'uid' => $uid,
            ]);
            if($user[0]->password != $request->old_pass ){
                return response()->json([
                    'success' => 'fail',
                    'error' => 'pass'
                ]);
            }
            if($request->new_pass != $request->confirm_pass){
                return response()->json([
                    'success' => 'fail',
                    'error' => 'confirm'
                ]);
            }
    
            DB::select('update member set password= :pass where mem_id= :uid',[
                'uid' => $uid,
                'pass' => $request->new_pass
            ]);
            return response()->json(['success' => 'true']);
        }
        
    }


    public function changeProfile(Request $request)
    {
        session(['prePage' => '/account']);
        if(session('login') != 'true'){
            return response()->json(['redirect','/login']);
        }
        else{
            $request->validate([
                'name' => 'required',
                'phone' => 'required|digits:10|max:10|min:10',
                'address' => 'required'
            ]);
            $query = DB::select('update member set name= :name, phone= :phone,address= :address where mem_id= :uid',[
                'name' => $request->name,
                'phone' => $request->phone,
                'address' => $request->address,
                'uid' => session('user')
            ]);
    
            return response()->json(['name'=>$request->name]);
        }
        
    }
}
