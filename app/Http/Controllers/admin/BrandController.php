<?php

namespace App\Http\Controllers\admin;

use Illuminate\Http\Request;

use App\Http\Controllers\Controller;

use App\Models\admin\brand;
use App\Models\admin\product;

use Illuminate\Support\Facades\File;

use Illuminate\Filesystem\Filesystem;

class BrandController extends Controller
{
    public function list(){
        $searchName = request('searchName');
        if(isset($searchName)){
            $count = brand::where('brand_active', '!=', -1)
            ->where('brand_name', 'like', '%'.$searchName.'%')->count('brand_id');
            $get = brand::where('brand_active', '!=', -1)
            ->where('brand_name', 'like', '%'.$searchName.'%')->paginate(7);
        }
        else{
            $count = brand::where('brand_active', '!=', -1)
            ->where('brand_name', 'like', '%'.$searchName.'%')
            ->count('brand_id');
            $get = brand::where('brand_active', '!=', -1)->paginate(7);
        }
        return view('admin.brand.admin_brand_page', ['brand'=>$get, 'count'=>$count, 'title'=>'Brands List']);
    }
    public function addred(){
        $get = brand::where('brand_active', '!=', -1)->get();
        return view('admin.brand.admin_brand_add', ['brand'=>$get, 'title'=>'Add New Brand']);
    }
    public function add(){
        $id = brand::max('brand_id');
        if(isset($id)){
            $id += 1;
        }
        else{
            $id = 1;
        }
        $temp1 = public_path('img/brand/temp1');
        $temp2 = public_path('img/brand/temp2');
        $temp3 = public_path('img/brand/temp3');
        $path = 'img/brand/'.$id;
        File::makeDirectory($path);
        $files1 = File::allFiles($temp1);
        $files2 = File::allFiles($temp2);
        $files3 = File::allFiles($temp3);
        foreach($files1 as $f1){
            File::move($temp1.'/'.$f1->getFilename(), $path.'/'.$f1->getFilename());
        }
        foreach($files2 as $f2){
            File::move($temp2.'/'.$f2->getFilename(), $path.'/'.$f2->getFilename());
        }
        foreach($files3 as $f3){
            File::move($temp3.'/'.$f3->getFilename(), $path.'/'.$f3->getFilename());
        }

        $b = new brand();
        $b->brand_name = request('bName');
        $b->brand_logo = request('bLogo');
        if(!isset($b->brand_logo)){
            $b->brand_logo = "No_image_2.png";
        }
        $b->brand_img = request('bHPimg');
        if(!isset($b->brand_img)){
            $b->brand_img = "No_image_2.png";
        }
        $b->brand_des_img = request('bBPimg');
        if(!isset($b->brand_des_img)){
            $b->brand_des_img = "No_image_2.png";
        }
        $b->brand_des = request('bDes');
        if(!isset($b->brand_des)){
            $b->brand_des = "No data";
        }
        $b->save();
        return to_route('a.b.list');
    }

    public function upload1(Request $request){
        $data = $request->All();
        $path = public_path('img/brand/temp1');
        $file = new Filesystem;
        $file->cleanDirectory($path);
        $img_name = $data['img1']->getClientOriginalName();
        $data['img1']->move($path.'/', $img_name);
    }
    public function upload2(Request $request){
        $data = $request->All();
        $path = public_path('img/brand/temp2');
        $file = new Filesystem;
        $file->cleanDirectory($path);
        $img_name = $data['img2']->getClientOriginalName();
        $data['img2']->move($path.'/', $img_name);
    }
    public function upload3(Request $request){
        $data = $request->All();
        $path = public_path('img/brand/temp3');
        $file = new Filesystem;
        $file->cleanDirectory($path);
        $img_name = $data['img3']->getClientOriginalName();
        $data['img3']->move($path.'/', $img_name);
    }
    public function editred(){
        $get = brand::where('brand_id', request('bid'))->get();
        return view('admin.brand.admin_brand_edit', ['brand'=>$get, 'title'=>'Edit Brand']);
    }
    public function edit(){
        $id = request('bid');
        $temp1 = public_path('img/brand/temp1');
        $temp2 = public_path('img/brand/temp2');
        $temp3 = public_path('img/brand/temp3');
        $path = 'img/brand/'.$id;
        $file = new Filesystem;
        $file->cleanDirectory($path);
        $files1 = File::allFiles($temp1);
        $files2 = File::allFiles($temp2);
        $files3 = File::allFiles($temp3);
        foreach($files1 as $f1){
            File::move($temp1.'/'.$f1->getFilename(), $path.'/'.$f1->getFilename());
        }
        foreach($files2 as $f2){
            File::move($temp2.'/'.$f2->getFilename(), $path.'/'.$f2->getFilename());
        }
        foreach($files3 as $f3){
            File::move($temp3.'/'.$f3->getFilename(), $path.'/'.$f3->getFilename());
        }

        $brand_logo = request('bLogo');
        if(!isset($brand_logo)){
            $brand_logo = "No_image_2.png";
        }
        $brand_img = request('bHPimg');
        if(!isset($brand_img)){
            $brand_img = "No_image_2.png";
        }
        $brand_des_img = request('bBPimg');
        if(!isset($brand_des_img)){
            $brand_des_img = "No_image_2.png";
        }
        $brand_des = request('bDes');
        if(!isset($brand_des)){
            $brand_des = "No data";
        }

        brand::where('brand_id', $id)->update([
            'brand_name'=>request('bName'),
            'brand_logo'=>$brand_logo,
            'brand_img'=>$brand_img,
            'brand_des_img'=>$brand_des_img,
            'brand_des'=>$brand_des
        ]);

        return to_route('a.b.list');
    }
    public function delred(){
        $searchName = request('searchName');
        if(isset($searchName)){
            $count = brand::where('brand_active', '!=', -1)
            ->where('brand_name', 'like', '%'.$searchName.'%')->count('brand_id');
            $get = brand::where('brand_active', '!=', -1)
            ->where('brand_name', 'like', '%'.$searchName.'%')->paginate(7);
        }
        else{
            $count = brand::count('brand_id');
            $get = brand::where('brand_active', '!=', -1)->paginate(7);
        }
        return view('admin.brand.admin_brand_delete', ['brand'=>$get, 'count'=>$count, 'title'=>'Delete Brands']);
    }
    public function del(Request $request){
        $get = $request->all();
        brand::where('brand_id', $get['bid'])->update([
            'brand_name'=>$get['bid'].'deleted',
            'brand_logo'=>NULL,
            'brand_img'=>NULL,
            'brand_des_img'=>NULL,
            'brand_des'=>NULL,
            'brand_active'=>-1
        ]);
        product::where('brand_id', $get['bid'])->update([
            'product_active'=>0
        ]);
    }
    public function activate(Request $request){
        $get = $request->all();
        brand::where('brand_id', $get['bid'])->update([
            'brand_active'=>1
        ]);
        product::where('product_id', $get['bid'])->update([
            'product_active'=>1
        ]);
    }
    public function deactivate(Request $request){
        $get = $request->all();
        brand::where('Brand_id', $get['bid'])->update([
            'brand_active'=>0
        ]);
        product::where('product_id', $get['bid'])->update([
            'product_active'=>0
        ]);
    }
}
