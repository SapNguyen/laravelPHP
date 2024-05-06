<?php

namespace App\Http\Controllers\user;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class HeaderController extends Controller
{
    public function load()
    {
        $brands = DB::select('select * from brand where brand_active = 1');
        return $brands;
    }

    public function login()
    {
        // if (session('login') == 'true') {
        //     return redirect(session('prePage'));
        // } else {
        // $brands = new HeaderController();
        // return view('login', [
        //     'brands' => $brands->load()
        // ]);


        return view('login');
        // }
    }

    public function register()
    {
        // $brands = new HeaderController();
        // return view('register', [
        //     'brands' => $brands->load()
        // ]);

        $response = Http::get('http://127.0.0.1:8000/api/load');

        if ($response->successful()) {
            $responseData = $response->json();

            $brands = $responseData['brands'];

            return view('register', [
                'brands' => $brands
            ]);
        } else {
            $statusCode = $response->status();
            $errorMessage = $response->body();
        }
    }



    //API

    public function load_api()
    {
        try {
            $brands = DB::select('select * from brand where brand_active = 1');
            return response()->json(['status' => 'success', 'brands' => $brands], 201);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error'], 500);
        }
    }

    public function product_api()
    {
        try {
            $brands = new HeaderController();
            return response()->json(['status' => 'success', 'brands' => $brands->load()], 201);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error'], 500);
        }
    }

    public function get_register_api()
    {
        try {
            $brands = new HeaderController();
            return response()->json(['status' => 'success', 'brands' => $brands->load()]);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'error' => $e->getMessage()]);
        }
    }

    public function get_login_api()
    {
        try {
            $brands = new HeaderController();
            return response()->json(['status' => 'success', 'brands' => $brands->load()]);
            // }
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'error' => $e->getMessage()]);
        }
    }
}
