<?php

namespace App\Http\Controllers\admin;


use App\Http\Services\ReceiptService;
// use App\Models\receipt;
use App\Models\admin\receipt;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;

class ReceiptController extends Controller
{
    
    public function index(){
        $searchName = request('searchName');
        if(isset($searchName)){
            $count = receipt::where('receipt_status', '=', 1)->where('validated_date','=',$searchName)->count('receipt_id');
            $get = DB::table('receipt')->where('receipt_status', '=', 1)->where('validated_date','=',$searchName)->orderByRaw('validated_date DESC')->paginate(10);
        }
        else{
            $count = receipt::where('receipt_status', '=', 1)->count('receipt_id');
            $get = DB::table('receipt')->where('receipt_status', '=', 1)->orderByRaw('validated_date DESC')->paginate(10);
        }
        // $count = receipt::where('receipt_status', '=', 1)->count('receipt_id');
        return view('admin.receipt.confirm',[
            
            'receipts' => $get,
            'title'=>'List Receipt Confirm',
            'count'=>$count,
            'searchName'=>$searchName
        ]); 
    }
    public function index_unconfimred(){
        $searchName = request('searchName');
        if(isset($searchName)){
            $count = receipt::where('receipt_status', '=', 0)->where('created_date','=',$searchName)->count('receipt_id');
            $get = DB::table('receipt')->where('receipt_status', '=', 0)->where('created_date','=',$searchName)->paginate(10);
        }
        else{
            $count = receipt::where('receipt_status', '=', 0)->count('receipt_id');
            $get = DB::table('receipt')->where('receipt_status', '=', 0)->paginate(10);
        }
        // $count = receipt::where('receipt_status', '=', 0)->count('receipt_id');
        return view('admin.receipt.unconfimred',[
            
            'receipts' => $get,
            'title'=>'List Receipt Unconfimred',
            'count'=>$count,
            'searchName'=>$searchName
        ]);     
    }
    public function index_canceled(){
        $searchName = request('searchName');
        if(isset($searchName)){
            $count = receipt::where('receipt_status', '=', -1)->where('created_date','=',$searchName)->count('receipt_id');
            $get = DB::table('receipt')->where('receipt_status', '=', -1)->where('created_date','=',$searchName)->paginate(10);
        }
        else{
            $count = receipt::where('receipt_status', '=', -1)->count('receipt_id');
            $get = DB::table('receipt')->where('receipt_status', '=', -1)->paginate(10);
        }
        // $count = receipt::where('receipt_status', '=', 2)->count('receipt_id');
        return view('admin.receipt.canceled_confirm',[
            
            'receipts' => $get,
            'title'=>'List Receipt Canceled',
            'count'=>$count,
            'searchName'=>$searchName
        ]);     
    }
    public function index_finished(){
        $searchName = request('searchName');
        if(isset($searchName)){
            $count = receipt::where('receipt_status', '=', 2)->where('validated_date','=',$searchName)->count('receipt_id');
            $get = DB::table('receipt')->where('receipt_status', '=', 2)->where('validated_date','=',$searchName)->paginate(10);
        }
        else{
            $count = receipt::where('receipt_status', '=', 2)->count('receipt_id');
            $get = DB::table('receipt')->where('receipt_status', '=', 2)->paginate(10);
        }
        // $count = receipt::where('receipt_status', '=', 2)->count('receipt_id');
        return view('admin.receipt.finished',[
            
            'receipts' => $get,
            'title'=>'List Receipt Canceled',
            'count'=>$count,
            'searchName'=>$searchName
        ]);     
    }
    public function detail($receipt_id){
        // $receipt = DB::select("select * from receipt where receipt_id= :receipt_id",[
        //     'receipt_id' => $receipt_id
        // ]);
        $receipt_products=DB::select("select * from product inner join receipt_product on receipt_product.product_id=product.product_id
		inner join receipt on receipt.receipt_id = receipt_product.receipt_id 
        inner join member on receipt.mem_id = member.mem_id 
        where  receipt.receipt_id= :receipt_id",[
            'receipt_id' => $receipt_id
        ]);
        for ($i=0; $i < count($receipt_products); $i++) { 
            $img = DB::select('select product.product_id,product_size_color.product_image from product 
                inner join product_size_color on product_size_color.product_id = product.product_id
                where product.product_active = 1 and product.product_id = :pid
                GROUP by product_id,product_size_color.product_image',[
                    'pid' => $receipt_products[$i]->product_id
            ]);
            $receipt_products[$i]->product_image = $img[0]->product_image;
        }
        // $receipt_products=product::join('receipt_product','receipt_product.product_id','=','product.product_id')->join('receipt','receipt.receipt_id','=','receipt_product.receipt_id')->join('member','receipt.mem_id','=','member.mem_id')->join('product_size_color','product.product_id','=','product_size_color.product_id')->where('receipt.receipt_id','=',$receipt_id);
        $receipt_product=DB::select("select * from ((product inner join receipt_product on receipt_product.product_id=product.product_id)
         inner join receipt on receipt.receipt_id = receipt_product.receipt_id)
         inner join member on receipt.mem_id = member.mem_id  where  receipt.receipt_id= :receipt_id",[
            'receipt_id' => $receipt_id
        ]);
        

        return view('admin/receipt/detail', [
            'receipt_products'=>$receipt_products,
            'receipt_product'=>$receipt_product[0],
            'title'=>'Detail Receipt'
        ]);
    }
    public function edit($receipt_id){
        // $receipt = DB::select("select * from receipt where receipt_id= :receipt_id",[
        //     'receipt_id' => $receipt_id
        // ]);
        $receipt_products=DB::select("select * from product inner join receipt_product on receipt_product.product_id=product.product_id
		inner join receipt on receipt.receipt_id = receipt_product.receipt_id 
        inner join member on receipt.mem_id = member.mem_id 
        where  receipt.receipt_id= :receipt_id",[
            'receipt_id' => $receipt_id
        ]);
        for ($i=0; $i < count($receipt_products); $i++) { 
            $img = DB::select('select product.product_id,product_size_color.product_image from product 
                inner join product_size_color on product_size_color.product_id = product.product_id
                where product.product_active = 1 and product.product_id = :pid
                GROUP by product_id,product_size_color.product_image',[
                    'pid' => $receipt_products[$i]->product_id
            ]);
            $receipt_products[$i]->product_image = $img[0]->product_image;
        }
        // $receipt_products=product::join('receipt_product','receipt_product.product_id','=','product.product_id')->join('receipt','receipt.receipt_id','=','receipt_product.receipt_id')->join('member','receipt.mem_id','=','member.mem_id')->join('product_size_color','product.product_id','=','product_size_color.product_id')->where('receipt.receipt_id','=',$receipt_id);
        $receipt_product=DB::select("select * from ((product inner join receipt_product on receipt_product.product_id=product.product_id)
         inner join receipt on receipt.receipt_id = receipt_product.receipt_id)
         inner join member on receipt.mem_id = member.mem_id  where  receipt.receipt_id= :receipt_id",[
            'receipt_id' => $receipt_id
        ]);

        // $receipt_product=DB::table('receipt')
        // ->join('receipt_product','receipt_product.receipt_id','=','receipt.receipt_id')
        // ->join('product','product.receipt_product.product_id','=','product.product_id')
        // ->join('member','receipt.mem_id','=','member.mem_id')
        // // ->join('product_size_color','product.product_id','=','product_size_color.product_id')
        // ->where('receipt.receipt_id','=',$receipt_id);

            

        // dd($receipt_product);
        // $receipt[0]->receipt_value = number_format($receipt[0]->receipt_value);
        // $receipt_products[0]->product_price = number_format($receipt_products[0]->product_price);
        return view('admin/receipt/edit_confirm', [
            'receipt_products'=>$receipt_products,
            'receipt_product'=>$receipt_product[0],
            'title'=>'Edit Receipt'
        ]);
    }
    public function delReceipt(Request $request){
        $get = $request->all();
        // dd($get['did']);
        receipt::where('receipt_id', $get['did'])->update([
            'receipt_status'=>-1
        ]);
        
        
    }
    // public function edit(receipt $receipt){
    //     return view('admin.edit_confirm',[
    //         'receipt'=>$receipt
    //     ]);
    // }
    public function postedit($receipt_id){
        try{
            DB::select("update receipt set receipt_status=1,validated_date=current_timestamp() where receipt_id= :receipt_id ",[
                'receipt_id' => $receipt_id
            ]);
            
            $products = DB::table('receipt_product')
                ->where('receipt_id','=',$receipt_id)
                ->get();
            foreach ($products as $product) {
                DB::table('product_size_color')
                ->where('size','=',$product->size)
                ->where('color','=',$product->color)
                ->where('product_id','=',$product->product_id)
                ->decrement('quantity', $product->quantity);
            }
            
            session()->regenerate();
            Session()->flash('success','Xác nhận thành công');
        }
        catch(Exception $ex){
            // Session()->flash('success','');
            session()->regenerate();
            Session()->flash('error','Xác nhận thất bại');
            return redirect('/admin/edit/'.$receipt_id);
        }
       return redirect('admin/receipt');
    }
    public function canceledit($receipt_id){
        try{
       
       DB::select("update receipt set receipt_status=-1,validated_date=current_timestamp() where receipt_id= :receipt_id ",[
        'receipt_id' => $receipt_id
       ]);
       session()->regenerate();
       Session()->flash('success','Hủy thành công');
        }
        catch(Exception $ex){
            session()->regenerate();
            Session()->flash('error','Hủy thất bại');
            return redirect('/admin/edit/'.$receipt_id);
        }
       return redirect('admin/receipt_canceled');
    }
}
