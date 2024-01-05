<?php

use App\Http\Controllers\admin\DiscountController;
use App\Http\Controllers\admin\ReceiptController;
use App\Http\Controllers\user\HeaderController;
use App\Http\Controllers\user\ProductController;
use App\Http\Controllers\user\HomeController;
use App\Http\Controllers\user\LoginController;
use App\Http\Controllers\user\CartController;
use App\Http\Controllers\user\AccountController;
use App\Http\Controllers\user\BrandController;
use App\Http\Controllers\user\PaymentController;
use App\Http\Middleware\AdminIsLogin;
use App\Http\Middleware\AdminStaffValidate;
use App\Http\Middleware\AdminValidate;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', [
    HomeController::class,'index',
]);

Route::get('/products', function () {
    $brands = new HeaderController();
    return view('user/product',[
        'brands' => $brands->load()
    ]);
});

Route::get('/products/{id}', [
    ProductController::class,
    'detail'
]);

Route::get('/cart/{pid}',[
    CartController::class,'payment'
]);

Route::get('/cart',[
    CartController::class,'loadCart'
]);

Route::post('/upload',[ProductController::class,'store']);

Route::get('/delTempImg',[
    ProductController::class,
    'delTempImg'
]);

Route::get('/updateImg/{id}',[
    ProductController::class,
    'updateImg'
]);

Route::get('/login', function () {
    if(session('login') == 'true'){
        return redirect(session('prePage'));
    }
    else{
        $brands = new HeaderController();
        return view('login',[
            'brands' => $brands->load()
        ]);
    }
})->name('login');

Route::post('/login',[
    LoginController::class,'login'
]);

Route::get('/register', function () {
    $brands = new HeaderController();
    return view('register',[
        'brands' => $brands->load()
    ]);
});

Route::post('/register',[
    LoginController::class,'register'
]);

Route::get('/logout',[
    LoginController::class,'logout'
]);

Route::get('/account', [
    AccountController::class,'loadPage'
]);

Route::post('/account/password',[
    AccountController::class,'changePass'
]);

Route::get('/account/{link}',[
    AccountController::class,'goLink'
]);
Route::get('/account/receipt/{status}',[
    AccountController::class,'receiptStatus'
]);

Route::post('/account/profile',[
    AccountController::class,'changeProfile'
]);

Route::get('/receipt/drop',[
    AccountController::class,'dropReceipt'
]);
Route::get('/receipt/confirm',[
    AccountController::class,'confirmReceipt'
]);
Route::post('/receipt/feedback',[
    AccountController::class,'addFeedback'
]);
Route::post('/feedback/writeRequest',[
    AccountController::class,'writeRequest'
]);
Route::post('/feedback/readRequest',[
    AccountController::class,'readRequest'
]);

Route::get('/loadHeader',function(){
    if(session('login') == 'true'){
        $user = DB::select('select * from member where mem_id= :uid',[
            'uid' => session('user')
        ]);
        $name_user = $user[0]->name;
        return response()->json(['name' => $name_user]);
    }
    return response()->json([]);
});

Route::post('/cart/quantity',[
    CartController::class,'updateQuantity'
]);

Route::post('/cart/remove',[
    CartController::class,'removeProduct'
]);

Route::post('/cart/updateSize',[
    CartController::class,'updateSize'
]);

Route::post('/cart/selectProduct',[
    CartController::class,'selectProduct',
]);

Route::post('/payment',[
    PaymentController::class,'loadPayment'
]);

Route::get('/payment/insert',[
    PaymentController::class,'insert'
]);

Route::get('/brand/{name}',[
    BrandController::class,'loadBrand'
]);

Route::get('/promotion',[
    BrandController::class,'promotion'
]);

Route::get('/search',[
    BrandController::class,'search'
]);

Route::middleware([AdminStaffValidate::class])->group(function () {
    Route::get('/admin', function () {
        return to_route('admin.page');
    });
    
    Route::get('/admin_page', 'App\Http\Controllers\admin\RevenueController@homepage')->name('admin.page');
    
    Route::get('/admin/brands', 'App\Http\Controllers\admin\BrandController@list')->name('a.b.list');
    Route::get('/admin/products', 'App\Http\Controllers\admin\ProductController@list')->name('a.p.list');
    Route::get('/admin/product/view', 'App\Http\Controllers\admin\ProductController@viewred')->name('a.p.view');
    Route::get('/admin/discounts', 'App\Http\Controllers\admin\DiscountController@list')->name('a.d.list');
    Route::get('/admin/discount/view', 'App\Http\Controllers\admin\DiscountController@viewred')->name('a.d.view');
    Route::get('/admin/receipt', [
        ReceiptController::class,'index'
    ])->name('a.r.list.1');
    Route::get('admin/receipt_unconfimred', [
        ReceiptController::class,'index_unconfimred'
    ])->name('a.r.list.0');
    
    Route::get('/admin/receipt_canceled', [
        ReceiptController::class,'index_canceled'
    ])->name('a.r.list.2');
    Route::get('/admin/receipt_finished', [
        ReceiptController::class,'index_finished'
    ])->name('a.r.list.3');
    Route::get('/admin/edit/{receipt}',[ReceiptController::class,'edit'])->name('a.r.edit.red');
    Route::get('/admin/detail/{receipt}',[ReceiptController::class,'detail'])->name('a.r.edit.red');
    Route::post('/admin/edit/{receipt}',[ReceiptController::class,'postedit'])->name('a.r.edit');
    Route::get('/admin/cancel_edit/{receipt}',[ReceiptController::class,'edit'])->name('a.r.edit.red');;
    Route::post('/admin/cancel_edit/{receipt}',[ReceiptController::class,'canceledit']);

    Route::get('/admin/revenue', 'App\Http\Controllers\admin\RevenueController@show')->name('a.revenue');
    Route::middleware([AdminValidate::class])->group(function () {
        Route::get('/admin/brand/add', 'App\Http\Controllers\admin\BrandController@addred')->name('a.b.add.red');
        Route::post('/admin/brand/add', 'App\Http\Controllers\admin\BrandController@add')->name('a.b.add');
        Route::post('/upload1', 'App\Http\Controllers\admin\BrandController@upload1');
        Route::post('/upload2', 'App\Http\Controllers\admin\BrandController@upload2');
        Route::post('/upload3', 'App\Http\Controllers\admin\BrandController@upload3');
        Route::post('/activateBrand', 'App\Http\Controllers\admin\BrandController@activate');
        Route::post('/deactivateBrand', 'App\Http\Controllers\admin\BrandController@deactivate');
        Route::get('/admin/brand/edit', 'App\Http\Controllers\admin\BrandController@editred')->name('a.b.edit.red');
        Route::post('/admin/brand/edit', 'App\Http\Controllers\admin\BrandController@edit')->name('a.b.edit');
        Route::get('/admin/brand/delete', 'App\Http\Controllers\admin\BrandController@delred')->name('a.b.del.red');
        Route::post('/deleteBrand', 'App\Http\Controllers\admin\BrandController@del');
        Route::post('/activateProduct', 'App\Http\Controllers\admin\ProductController@activate');
        Route::post('/deactivateProduct', 'App\Http\Controllers\admin\ProductController@deactivate');
        Route::get('/admin/product/add', 'App\Http\Controllers\admin\ProductController@addred')->name('a.p.add.red');
        Route::post('/addPimg', 'App\Http\Controllers\admin\ProductController@addimg');
        Route::post('/addPcolor', 'App\Http\Controllers\admin\ProductController@addcolor');
        Route::post('/removePcolor', 'App\Http\Controllers\admin\ProductController@removecolor');
        Route::post('/addProduct', 'App\Http\Controllers\admin\ProductController@add');
        Route::post('/updateProduct', 'App\Http\Controllers\admin\ProductController@update');
        Route::get('/admin/product/edit', 'App\Http\Controllers\admin\ProductController@editred')->name('a.p.edit.red');
        Route::get('/admin/product/delete', 'App\Http\Controllers\admin\ProductController@delred')->name('a.p.del.red');
        Route::post('/deleteProduct', 'App\Http\Controllers\admin\ProductController@delete');
        Route::post('/admin/discount/add', [DiscountController::class,'add'])->name('a.d.add');
        Route::get('/admin/discount/add', [DiscountController::class,'addred'])->name('a.d.add.red');
        Route::post('/activateDiscount', [DiscountController::class,'activate']);
        Route::post('/deactivateDiscount', [DiscountController::class,'deactivate']);
        Route::get('/admin/discount/edit', [DiscountController::class,'editred'])->name('a.d.edit.red');
        Route::post('/admin/discount/edit', [DiscountController::class,'edit'])->name('a.d.edit');
        Route::get('/admin/discount/delete', 'App\Http\Controllers\admin\DiscountController@delred')->name('a.d.del.red');
        Route::post('/deleteDiscount', 'App\Http\Controllers\admin\DiscountController@del');
        Route::post('/addProductDiscount', 'App\Http\Controllers\admin\DiscountController@addproduct');
        Route::post('/subProductDiscount', 'App\Http\Controllers\admin\DiscountController@subproduct');
        Route::post('/delReceipt', [ReceiptController::class,'delReceipt']);
        Route::get('/admin/accounts/admin', 'App\Http\Controllers\admin\MemberController@adminshow')->name('a.a.list');
        Route::post('/admin/accounts/admin', 'App\Http\Controllers\admin\MemberController@update_admin')->name('a.a.update');
        Route::get('/admin/accounts/staff', 'App\Http\Controllers\admin\MemberController@staffshow')->name('a.s.list');
        Route::post('/updatestaff', 'App\Http\Controllers\admin\MemberController@update_staff');
        Route::post('/deletestaff', 'App\Http\Controllers\admin\MemberController@delete_staff');
        Route::get('/admin/accounts/staff/add', 'App\Http\Controllers\admin\MemberController@addstaffred')->name('a.s.add.red');
        Route::post('/admin/accounts/staff/add', 'App\Http\Controllers\admin\MemberController@addstaff')->name('a.s.add');
        Route::get('/admin/accounts/member', 'App\Http\Controllers\admin\MemberController@membershow')->name('a.m.list');
        Route::post('/unbanmember', 'App\Http\Controllers\admin\MemberController@unban');
        Route::post('/banmember', 'App\Http\Controllers\admin\MemberController@ban');
    });
});
    
    
    

// Route::post('/admin/staff', 'App\Http\Controllers\admin\StaffController@list')->name('staff.search');

// Route::get('/home', [App\Http\Controllers\HomeController::class, 'App\Http\Controllers\admin\index'])->name('home');



// Route::get('/home', [App\Http\Controllers\HomeController::class, 'App\Http\Controllers\admin\index'])->name('home');
