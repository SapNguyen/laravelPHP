<?php

namespace App\Http\Controllers\admin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\admin\discount;
use App\Models\admin\product;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\DB;


class DiscountController extends Controller
{
    
    public function list(){
        $searchName = request('searchName');
        if(isset($searchName)){
            $count = discount::where('discount_active', '!=', -1)->where('discount_name', 'like', '%'.$searchName.'%')->count('discount_id');
            $get = discount::where('discount_active', '!=', -1)
            ->where('discount_name', 'like', '%'.$searchName.'%')->paginate(7);
        }
        else{
            $count = discount::where('discount_active', '!=', -1)->count('discount_id');
            $get = discount::where('discount_active', '!=', -1)->paginate(7);
        }
        return view('admin.discount.admin_discount_page',
         ['discounts'=>$get,
         'count'=>$count,
         'title'=>'Discounts List',
         'searchName'=>$searchName
        ]);
    }
    public function activate(Request $request){
        $get = $request->all();
        discount::where('discount_id', $get['did'])->update([
            'discount_active'=>1
        ]);
        
    }
    public function deactivate(Request $request){
        $get = $request->all();
        discount::where('discount_id', $get['did'])->update([
            'discount_active'=>0
        ]);
        
    }
    public function addred(){
        $id=DB::table('discount')->count('discount_id')+1;
        $get = discount::where('discount_active', '!=', -1)->get();
        return view('admin.discount.admin_discount_add', [
            'title'=>'Add New Discount',
            'id'=>$id,
            'discount'=>$get
        ]);
    }
    public function add(){
        $d = new discount();
        // $discountproducts=DB::table('discount')->join('product','discount.discount_id','=','product.discount_id')->where('discount.discount_id','=',$d->discount_id)->paginate(1);
        // $products=DB::select("select * from product ");
        // $txtid = Request::createFromGlobals()->get('txtid');
        // $txtname = Request::createFromGlobals()->get('txtname');
        // $datestart=Request::createFromGlobals()->get('date-start');
        // $dateend=Request::createFromGlobals()->get('date-end');
        // $txtvalue=Request::createFromGlobals()->get('txtvalue');
        // $insert=DB::select("insert into discount(discount_id,discount_name,discount_start,discount_end,discount_value) values (:txtid,:txtname,:datestart,:dateend,:txtvalue) ",[
        //     'txtid' => $txtid,
        //     'txtname' => $txtname,
        //     'date-start' => $datestart,
        //     'date-end' => $dateend,
        //     'txtvalue' => $txtvalue
        // ]);
        // $insert=DB::insert('insert into discount(discount_id,discount_name,discount_start,discount_end,discount_value) values (?,?,?,?,?)',[$txtid,$txtname,$datestart,$dateend,$txtvalue]);
        // $discountproducts=DB::select("select * from (discount INNER JOIN discount_product ON discount.discount_id = discount_product.discount_id) INNER JOIN product ON discount_product.product_id = product.product_id ");
        
        $d->discount_id = request('txtid');
        $d->discount_name = request('txtname');
        $d->discount_start = request('date-start');
        $d->discount_end = request('date-end');
        $d->discount_value = request('txtvalue');
        $d->save();
        $id=DB::table('discount')->count('discount_id')+1;
        
        return to_route('a.d.list');
        
        
        
    }
    public function viewred(){
        Paginator::useBootstrapFive();
        $searchName = request('searchName');
        $discount =  request('discount_id');
        
        if(isset($searchName)){
            $count = product::where('discount_id', NULL)->where('product_name', 'like', '%'.$searchName.'%')->count('product_id');
            
            $get = product::where('discount_id', NULL)
                ->where('product_name', 'like', '%'.$searchName.'%')
                ->paginate(7,['*'],'get');
            for ($i=0; $i < count($get); $i++) { 
                $img = DB::select('select product.product_id,product_size_color.product_image from product 
                inner join product_size_color on product_size_color.product_id = product.product_id
                where product.product_active = 1 and product.product_id = :pid
                GROUP by product_id,product_size_color.product_image',[
                    'pid' => $get[$i]->product_id
                ]);
                $get[$i]->product_image = $img[0]->product_image;
            }
        }else{
            $count = product::join('product_size_color', 'product.product_id', '=', 'product_size_color.product_id')
                ->where('discount_id', NULL)
                ->count('product.product_id');
            $get = product::where('discount_id', NULL)
                ->paginate(7,['*'],'get');
            for ($i=0; $i < count($get); $i++) { 
                $img = DB::select('select product.product_id,product_size_color.product_image from product 
                inner join product_size_color on product_size_color.product_id = product.product_id
                where product.product_active = 1 and product.product_id = :pid
                GROUP by product_id,product_size_color.product_image',[
                    'pid' => $get[$i]->product_id
                ]);
                $get[$i]->product_image = $img[0]->product_image;
            }
        }
        $searchNamed = request('searchNamed');
        if(isset($searchNamed)){
            $counted = product::where('discount_id',  '=',$discount)->where('product_name', 'like', '%'.$searchNamed.'%')->count('product_id');
            $geted = product::where('product_name', 'like', '%'.$searchNamed.'%')
                ->where('discount_id', '=',$discount)
                ->paginate(7,['*'],'geted');
            for ($i=0; $i < count($geted); $i++) { 
                $img = DB::select('select product.product_id,product_size_color.product_image from product 
                inner join product_size_color on product_size_color.product_id = product.product_id
                where product.product_active = 1 and product.product_id = :pid
                GROUP by product_id,product_size_color.product_image',[
                    'pid' => $geted[$i]->product_id
                ]);
                $geted[$i]->product_image = $img[0]->product_image;
            }
            
        }else{
            $counted = product::where('discount_id',  '=',$discount)->count('product_id');
            $geted = product::where('discount_id',  '=',$discount)
                ->paginate(7,['*'],'geted');
            for ($i=0; $i < count($geted); $i++) { 
                $img = DB::select('select product.product_id,product_size_color.product_image from product 
                    inner join product_size_color on product_size_color.product_id = product.product_id
                    where product.product_active = 1 and product.product_id = :pid
                    GROUP by product_id,product_size_color.product_image',[
                    'pid' => $geted[$i]->product_id
                ]);
                 $geted[$i]->product_image = $img[0]->product_image;
            }      
        }
        // dd($discount);
        // dd($geted);
        return view('admin.discount.admin_discount_view', [
            'count'=>$count,
            'products'=>$get,
            'countd'=>$counted,
            'product_eds'=>$geted,
            'discountes'=>$discount,
            'discount_id'=>$discount,
            'title'=>'Discount\'s detail',
            'searchName'=>$searchName,
            'searchNamed'=>$searchNamed
        ]);
    }
    public function addproduct(Request $request){
        $get = $request->all();
        product::where('product_id', $get['did'])->update([
            'discount_id'=>$get['diid']
        ]);
        
        
    }
    public function subproduct(Request $request){
        $get = $request->all();
        product::where('product_id', $get['did'])->update([
            'discount_id'=>NULL
        ]);
        
        
    }
    public function editred(){
        $get = discount::where('discount_id', request('did'))->get();
        return view('admin.discount.admin_discount_edit', ['discount'=>$get,'title'=>'Edit Discount']);
    }
    public function edit(){
        $id = request('did');
        

        $discount_name = request('txtname');
        if(!isset($discount_name)){
            $discount_name = "Discount no name";
        }
        $discount_start = request('date-start');
        if(!isset($discount_start)){
            $discount_start = "Discount no date start";
        }$discount_end = request('date-end');
        if(!isset($discount_end)){
            $discount_end = "Discount no date end";
        }$discount_value = request('txtvalue');
        if(!isset($discount_value)){
            $discount_value = "Discount no value";
        }

        discount::where('discount_id', $id)->update([
            'discount_name'=>$discount_name,
            'discount_start'=>$discount_start,
            'discount_end'=>$discount_end,
            'discount_value'=>$discount_value,
        ]);

        return to_route('a.d.list');
    }
    public function delred(){
        $searchName = request('searchName');
        if(isset($searchName)){
            $count = discount::where('discount_active', '!=', -1)->where('discount_name', 'like', '%'.$searchName.'%')->count('discount_id');
            $get = discount::where('discount_active', '!=', -1)
            ->where('discount_name', 'like', '%'.$searchName.'%')->paginate(7);
        }
        else{
            $count = discount::where('discount_active', '!=', -1)->count('discount_id');
            $get = discount::where('discount_active', '!=', -1)->paginate(7);
        }
        return view('admin.discount.admin_discount_delete', ['discounts'=>$get, 'count'=>$count,'title'=>'Delete Discounts','searchName'=>$searchName]);
    }
    public function del(Request $request){
        $get = $request->all();
        DB::select("update discount inner join product on discount.discount_id = product.discount_id 
            set discount_name = 'deleted',
            discount_end = NULL,
            discount_active = -1,
            product.discount_id = NULL
            where discount.discount_id = :discount_id
        ",
        [
            'discount_id'=>$get['did']
        ]);
    }
}
