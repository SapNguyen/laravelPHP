<?php

namespace App\Http\Controllers\admin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\admin\product;
use App\Models\admin\product_size_color;
use App\Models\admin\brand;

use Illuminate\Support\Facades\File;
use Carbon\Carbon;

class ProductController extends Controller
{
    public function list(){
        $pq = product_size_color::groupBy('product_id')
            ->get([
                'product_id',
                product_size_color::raw('SUM(quantity) as quan')
            ]);
        $search = request('searchName');
        if(isset($search)){
            $count = product::where('product_active', '!=', -1)
            ->where('product_name', 'like', '%'.$search.'%')
            ->count('product_id');
            $get = product::join('brand', 'product.brand_id', '=', 'brand.brand_id')
            ->where('product_active', '!=', -1)
            ->where('product_name', 'like', '%'.$search.'%')
            ->paginate(7);
        }
        else{
            $count = product::where('product_active', '!=', -1)->count('product_id');
            $get = product::join('brand', 'product.brand_id', '=', 'brand.brand_id')
            ->where('product_active', '!=', -1)->paginate(7);
        }
        return view('admin.product.admin_product_page', ['product'=>$get, 'count'=>$count, 'quan'=>$pq, 'title'=>'Products List']);
    }
    public function viewred(){
        $get = product::where('product_id', request('pid'))
        ->join('brand', 'product.brand_id', '=', 'brand.brand_id')
        ->where('product_active', '!=', -1)->paginate(7);
        $get2 = product_size_color::where('product_id', request('pid'))
        ->groupBy('product_id')
        ->groupBy('color')
        ->groupBy('product_image')
        ->get([
            'product_id', 'color',
            product_size_color::raw('COUNT(size) as row'),
            'product_image'
        ]);
        $get3 = product_size_color::where('product_id', request('pid'))->get();
        return view('admin.product.admin_product_view', [
            'product'=>$get, 'psc1'=>$get2, 'psc2'=>$get3,'title'=>'View Product'
        ]);
    }
    public function addred(){
        $brand = brand::where('brand_active', '!=', -1)
        ->orderBy('brand_name', 'asc')->get();
        return view('admin.product.admin_product_add', ['brand'=>$brand, 'title'=>'Add New Product']);
    }
    public function add(Request $request){
        $get = $request->all();

        $product = new product();
        $product->product_name = $get['name'];
        $product->product_material = $get['material'];
        $product->product_des = $get['des'];
        $product->product_price = $get['price'];
        $product->brand_id = $get['brand'];
        $product->save();

        $id = product::max('product_id');
        if(!isset($id)){
            $id = 1;
        }

        $filepath = public_path('/img/product/'.$id);
        $temppath = public_path('/img/product/temp');
        if(File::exists($filepath)){
            File::cleanDirectory($filepath);
        }
        else{
            File::makeDirectory($filepath);
        }
        if(!File::exists($temppath)){
            File::makeDirectory($temppath);
        }
        $imgs = File::allFiles($temppath);
        for($i = 0; $i < count($imgs); $i++){
            File::move($temppath.'/'.$imgs[$i]->getFilename(), $filepath.'/'.$imgs[$i]->getFileName());
        }

        $pcsq = $get['pcsq'];
        $pcs = array();
        for($i = 0; $i < count($pcsq); $i++){
            $ex = explode('|', $pcsq[$i]);
            $pcs[$i] = new product_size_color();
            $pcs[$i]->product_id = $id;
            $pcs[$i]->color = $ex[0];
            $pcs[$i]->size = $ex[1];
            $pcs[$i]->quantity = $ex[2];
            $pcs[$i]->product_image = $ex[3];
            $pcs[$i]->save();
        }

        return response()->json([
            'message'=>'Product added successfully.'
        ]);
    }
    public function editred(){
        $brand = brand::where('brand_active', '!=', -1)
        ->orderBy('brand_name', 'asc')->get();
        $get = product::where('product_id', request('pid'))
        ->join('brand', 'product.brand_id', '=', 'brand.brand_id')
        ->get();
        $get2 = product_size_color::where('product_id', request('pid'))
        ->groupBy('product_id')
        ->groupBy('color')
        ->groupBy('product_image')
        ->get([
            'product_id', 'color',
            product_size_color::raw('COUNT(size) as row'),
            'product_image'
        ]);
        $get3 = product_size_color::where('product_id', request('pid'))->get();
        return view('admin.product.admin_product_edit', [
            'product'=>$get, 'psc1'=>$get2, 'psc2'=>$get3,
            'brand'=>$brand, 'title'=>'Edit Product'
        ]);
    }
    public function update(Request $request){
        $get = $request->all();

        product::where('product_id', $get['id'])->update([
            'product_name'=>$get['name'],
            'product_material'=>$get['material'],
            'product_des'=>$get['des'],
            'product_price'=>$get['price'],
            'brand_id'=>$get['brand'],
            'product_updated_date'=>Carbon::now()->toDateString()
        ]);

        $id = $get['id'];

        $filepath = public_path('/img/product/'.$id);
        $temppath = public_path('/img/product/temp');
        if(File::exists($filepath)){
            File::cleanDirectory($filepath);
        }
        else{
            File::makeDirectory($filepath);
        }
        if(!File::exists($temppath)){
            File::makeDirectory($temppath);
        }
        $imgs = File::allFiles($temppath);
        for($i = 0; $i < count($imgs); $i++){
            File::move($temppath.'/'.$imgs[$i]->getFilename(), $filepath.'/'.$imgs[$i]->getFileName());
        }


        product_size_color::where('product_id', $get['id'])->delete();
        $pcsq = $get['pcsq'];
        $pcs = array();
        for($i = 0; $i < count($pcsq); $i++){
            $ex = explode('|', $pcsq[$i]);
            $pcs[$i] = new product_size_color();
            $pcs[$i]->product_id = $id;
            $pcs[$i]->color = $ex[0];
            $pcs[$i]->size = $ex[1];
            $pcs[$i]->quantity = $ex[2];
            $pcs[$i]->product_image = $ex[3];
            $pcs[$i]->save();
        }

        return response()->json([
            'message'=>'Product updated successfully.'
        ]);
    }
    public function delred(){
        $pq = product_size_color::groupBy('product_id')
            ->get([
                'product_id',
                product_size_color::raw('SUM(quantity) as quan')
            ]);
        $search = request('searchName');
        if(isset($search)){
            $count = product::where('product_active', '!=', -1)
            ->where('product_name', 'like', '%'.$search.'%')
            ->count('product_id');
            $get = product::join('brand', 'product.brand_id', '=', 'brand.brand_id')
            ->where('product_active', '!=', -1)
            ->where('product_name', 'like', '%'.$search.'%')
            ->paginate(7);
        }
        else{
            $count = product::where('product_active', '!=', -1)->count('product_id');
            $get = product::join('brand', 'product.brand_id', '=', 'brand.brand_id')
            ->where('product_active', '!=', -1)->paginate(7);
        }
        return view('admin.product.admin_product_delete', [
            'product'=>$get, 'count'=>$count, 'quan'=>$pq, 'title'=>'Products List'
        ]);
    }
    public function delete(Request $request){
        $get = $request->all();
        product::where('product_id', $get['pid'])->update([
            'product_active'=>-1
        ]);
    }
    public function activate(Request $request){
        $get = $request->all();
        product::where('product_id', $get['pid'])->update([
            'product_active'=>1
        ]);
    }
    public function deactivate(Request $request){
        $get = $request->all();
        product::where('product_id', $get['pid'])->update([
            'product_active'=>0
        ]);
    }
    public function addimg(Request $request){
        $get = $request->all();
        $path = public_path('img/product/temp');
        if(!File::exists($path)){
            File::makeDirectory($path);
        }
        if($get['old'] != 'No_image_2.png'){
            $checklist = File::files($path);
            for($i = 0; $i < count($checklist); $i++){
                $checkname[$i] = $checklist[$i]->getFileName();
            }
            $check = explode(',', $get['old']);
            $result = array_intersect($check, $checkname);
            if(count($result) > 0){
                foreach($result as $r){
                    File::delete($path.'/'.$r);
                }
            }
        }
        for($i = 0; $i < count($get) - 1; $i++){
            $img[$i] = $get[$i];
            $img_name = $img[$i]->hashName();
            $img[$i]->move($path.'/', $img_name);
            $name[$i] = $img_name;
        }

        $imgs = $name[0];
        for($i = 1; $i < count($name); $i++){
            $imgs = $imgs . ',' . $name[$i];
        }

        return response()->json([
            'message'=>'Add images to folder '.$path.' successfully.',
            'name'=>$name, 'imgs'=>$imgs
        ]);
    }
    public function removecolor(Request $request){
        $get = $request->all();
        $path = public_path('img/product/temp');
        if(!is_null($get['imgs'])){
            $name = explode(',', $get['imgs']);
            foreach($name as $n){
                File::delete($path.'/'.$n);
            }
        }
        return response()->json([
            'message'=>'Remove imgs of color successfully.'
        ]);
    }
}
