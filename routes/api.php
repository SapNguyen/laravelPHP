<?php

use App\Http\Controllers\admin\DiscountController;
use App\Http\Controllers\admin\OrderController;
use App\Http\Controllers\user\AccountController;
use App\Http\Controllers\user\BrandController;
use App\Http\Controllers\user\CartController;
use App\Http\Controllers\user\HeaderController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\user\HomeController;
use App\Http\Controllers\user\LoginController;
use App\Http\Controllers\user\PaymentController;
use App\Http\Controllers\user\ProductController;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\File;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });
Route::get('/home', [
    HomeController::class, 'index_api',
]);
Route::get('/product/feedback', [
    HomeController::class, 'feedback_product_api',
]);
Route::post('/payment/store', [
    HomeController::class, 'payment_store_api',
]);
Route::get('/cart/user', [
    CartController::class, 'user_cart_api',
]);

// Brand
Route::get('/admin/brands', 'App\Http\Controllers\admin\BrandController@list_api');
Route::get('/admin/brand/add', 'App\Http\Controllers\admin\BrandController@addred_api');
Route::post('/admin/brand/add', 'App\Http\Controllers\admin\BrandController@add_api');
Route::get('/admin/brand/edit', 'App\Http\Controllers\admin\BrandController@editred_api');
Route::post('/admin/brand/edit', 'App\Http\Controllers\admin\BrandController@edit_api');
Route::get('/admin/brand/delete', 'App\Http\Controllers\admin\BrandController@delred_api');
Route::post('/admin/deleteBrand', 'App\Http\Controllers\admin\BrandController@del_api');
Route::post('/admin/activateBrand', 'App\Http\Controllers\admin\BrandController@activate_api');
Route::post('/admin/deactivateBrand', 'App\Http\Controllers\admin\BrandController@deactivate_api');

//Discount
Route::get('/admin/discounts', 'App\Http\Controllers\admin\DiscountController@list_api');
Route::get('/admin/discount/view', 'App\Http\Controllers\admin\DiscountController@viewred_api');
Route::post('/admin/discount/add', [DiscountController::class, 'add_api']);
Route::get('/admin/discount/add', [DiscountController::class, 'addred_api']);
Route::post('/activateDiscount', [DiscountController::class, 'activate_api']);
Route::post('/deactivateDiscount', [DiscountController::class, 'deactivate_api']);
Route::get('/admin/discount/edit', [DiscountController::class, 'editred_api']);
Route::post('/admin/discount/edit', [DiscountController::class, 'edit_api']);
Route::get('/admin/discount/delete', 'App\Http\Controllers\admin\DiscountController@delred_api');
Route::post('/deleteDiscount', 'App\Http\Controllers\admin\DiscountController@del_api');
Route::post('/addProductDiscount', 'App\Http\Controllers\admin\DiscountController@addproduct_api');
Route::post('/subProductDiscount', 'App\Http\Controllers\admin\DiscountController@subproduct_api');

//Revenue
Route::get('/admin_page', 'App\Http\Controllers\admin\RevenueController@homepage_api');
Route::get('/admin/revenue', 'App\Http\Controllers\admin\RevenueController@show_api');


//Member
Route::get('/admin/accounts/admin', 'App\Http\Controllers\admin\MemberController@adminshow_api');
Route::post('/admin/accounts/admin', 'App\Http\Controllers\admin\MemberController@update_admin_api');
Route::get('/admin/accounts/staff', 'App\Http\Controllers\admin\MemberController@staffshow_api');
Route::post('/updatestaff', 'App\Http\Controllers\admin\MemberController@update_staff_api');
Route::post('/deletestaff', 'App\Http\Controllers\admin\MemberController@delete_staff_api');
Route::get('/admin/accounts/staff/add', 'App\Http\Controllers\admin\MemberController@addstaffred_api');
Route::post('/admin/accounts/staff/add', 'App\Http\Controllers\admin\MemberController@addstaff_api');
Route::get('/admin/accounts/member', 'App\Http\Controllers\admin\MemberController@membershow_api');
Route::post('/unbanmember', 'App\Http\Controllers\admin\MemberController@unban_api');
Route::post('/banmember', 'App\Http\Controllers\admin\MemberController@ban_api');


//Order
Route::get('/admin/order', [
    OrderController::class, 'index_api'
]);
Route::get('admin/order_unconfimred', [
    OrderController::class, 'index_unconfimred_api'
]);
Route::post('/delorder', [OrderController::class, 'delorder_api']);

Route::get('/admin/order_canceled', [
    OrderController::class, 'index_canceled_api'
]);
Route::get('/admin/order_finished', [
    OrderController::class, 'index_finished_api'
]);
Route::get('/admin/edit/{order}', [OrderController::class, 'edit_api']);
Route::get('/admin/detail/{order}', [OrderController::class, 'detail_api']);
Route::post('/admin/edit/{order}', [OrderController::class, 'postedit_api']);
Route::get('/admin/cancel_edit/{order}', [OrderController::class, 'edit_api']);;
Route::post('/admin/cancel_edit/{order}', [OrderController::class, 'canceledit_api']);



//Product
Route::get('/admin/products', 'App\Http\Controllers\admin\ProductController@list_api');
Route::get('/admin/product/view', 'App\Http\Controllers\admin\ProductController@viewred_api');
Route::post('/activateProduct', 'App\Http\Controllers\admin\ProductController@activate_api');
Route::post('/deactivateProduct', 'App\Http\Controllers\admin\ProductController@deactivate_api');
Route::get('/admin/product/add', 'App\Http\Controllers\admin\ProductController@addred_api');
Route::post('/addPimg', 'App\Http\Controllers\admin\ProductController@addimg_api');
Route::post('/addPcolor', 'App\Http\Controllers\admin\ProductController@addcolor_api');
Route::post('/removePcolor', 'App\Http\Controllers\admin\ProductController@removecolor_api');
Route::post('/addProduct', 'App\Http\Controllers\admin\ProductController@add_api');
Route::post('/updateProduct', 'App\Http\Controllers\admin\ProductController@update_api');
Route::get('/admin/product/edit', 'App\Http\Controllers\admin\ProductController@editred_api');
Route::get('/admin/product/delete', 'App\Http\Controllers\admin\ProductController@delred_api');
Route::post('/deleteProduct', 'App\Http\Controllers\admin\ProductController@delete_api');



//User API
Route::get('/imgproduct', [
    HomeController::class, 'img_product_api',
]);
Route::get('/detailproduct', [
    HomeController::class, 'detail_product_api',
]);
Route::get('/searchproduct', [
    HomeController::class, 'search_product_api',
]);
//Brand
Route::get('/user/brand/{name}', [
    BrandController::class, 'loadBrand_api'
]);

Route::get('/user/promotion', [
    BrandController::class, 'promotion_api'
]);

Route::get('/user/search', [
    BrandController::class, 'search_api'
]);
Route::get('/user/banner', [
    BrandController::class, 'banner_api'
]);

Route::get('/user/home', [
    BrandController::class, 'home_api'
]);




//Account
Route::get('/account', [
    AccountController::class, 'loadPage_api'
]);

Route::post('/account/password', [
    AccountController::class, 'changePass_api'
]);

Route::get('/account/{link}', [
    AccountController::class, 'goLink_api'
]);
Route::get('/account/order/{status}', [
    AccountController::class, 'orderStatus_api'
]);

Route::post('/account/profile', [
    AccountController::class, 'changeProfile_api'
]);

Route::get('/order/drop', [
    AccountController::class, 'droporder_api'
]);
Route::get('/order/confirm', [
    AccountController::class, 'confirmorder_api'
]);
Route::post('/order/feedback', [
    AccountController::class, 'addFeedback_api'
]);
Route::post('/feedback/writeRequest', [
    AccountController::class, 'writeRequest_api'
]);
Route::post('/feedback/readRequest', [
    AccountController::class, 'readRequest_api'
]);




//Cart

Route::get('/cart/{pid}', [
    CartController::class, 'payment_api'
]);

Route::get('/cart', [
    CartController::class, 'loadCart_api'
]);
Route::post('/cart/quantity', [
    CartController::class, 'updateQuantity_api'
]);

Route::post('/cart/remove', [
    CartController::class, 'removeProduct_api'
]);

Route::post('/cart/updateSize', [
    CartController::class, 'updateSize_api'
]);

Route::post('/cart/selectProduct', [
    CartController::class, 'selectProduct_api',
]);

Route::get('/order/user', [
    CartController::class, 'order_user_api'
]);



//Header
Route::get('/products', [
    HeaderController::class, 'products_api'
]);
Route::get('/login', [
    HeaderController::class, 'get_login_api'
]);
Route::get('/register', [
    HeaderController::class, 'get_register_api'
]);


//Login
Route::post('/login', [
    LoginController::class, 'login_api'
]);
Route::post('/register', [
    LoginController::class, 'register_api'
]);

Route::get('/logout', [
    LoginController::class, 'logout_api'
]);

//Payment
Route::post('/payment', [
    PaymentController::class, 'loadPayment_api'
]);

Route::get('/payment/insert', [
    PaymentController::class, 'insert_api'
]);

//Product
Route::get('/products/{id}', [
    ProductController::class,
    'detail'
]);
Route::post('/upload', [ProductController::class, 'store']);

Route::get('/delTempImg', [
    ProductController::class,
    'delTempImg'
]);

Route::get('/updateImg/{id}', [
    ProductController::class,
    'updateImg'
]);
