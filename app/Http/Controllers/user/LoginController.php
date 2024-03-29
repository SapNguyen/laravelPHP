<?php

namespace App\Http\Controllers\user;

use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class LoginController extends Controller
{
    public function login(Request $request)
    {
        // $request->validate([
        //     'email' => 'required',
        //     'pswd' => 'required'
        // ]);
        // $user = DB::select('select * from member where username = :uname and password= :pswd', [
        //     'uname' => $request->email,
        //     'pswd' => $request->pswd
        // ]);
        // if (!$user) {
        //     return response()->json([
        //         'error' => 'Sai mật khẩu hoặc email'
        //     ]);
        // } else {
        //     session(['login' => 'true']);
        //     session(['user' => $user[0]->mem_id]);
        //     if ($user[0]->role == 'admin') {
        //         session(['admin' => 'true']);
        //         $dir = '/admin_page';
        //     } else if ($user[0]->role == 'staff') {
        //         session(['staff' => 'true']);
        //         $dir = '/admin_page';
        //     } else {
        //         if (session('prePage')) {
        //             $dir = session('prePage');
        //         } else {
        //             $dir = '/';
        //         }
        //     }
        //     return response()->json(['dir' => $dir]);
        // }
        $postData = [
            'username' => $request->email,
            'password' => $request->pswd
        ];
        $response = Http::get('https://s25sneaker.000webhostapp.com/api/login',$postData);

        if ($response->successful()) {
            $responseData = $response->json();

            $user = $responseData['user'];

            if (!$user) {
                return response()->json([
                    'error' => 'Sai mật khẩu hoặc email'
                    // 'error' => $request->email

                ]);
            } else {
                session(['login' => 'true']);
                session(['user' => $user[0]['mem_id']]);
                if ($user[0]['role'] == 'admin') {
                    session(['admin' => 'true']);
                    $dir = '/admin_page';
                } else if ($user[0]['role'] == 'staff') {
                    session(['staff' => 'true']);
                    $dir = '/admin_page';
                } else {
                    if (session('prePage')) {
                        $dir = session('prePage');
                    } else {
                        $dir = '/';
                    }
                }
                return response()->json(['dir' => $dir]);
            }
        } else {
            $statusCode = $response->status();
            $errorMessage = $response->body();
            return response()->json(['error' => 'Failed to post data'], $statusCode);
        }
    }
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email:filter',
            'pswd' => 'required'
        ]);
        $user = DB::select('select * from member where username= :uname', [
            'uname' => $request->email
        ]);
        if ($user) {
            return response()->json(['error' => 'Email đã tồn tại']);
        } else {
            session(['login' => 'true']);

            DB::table("member")->insert([
                'username' => $request->email,
                'password' => $request->pswd,
                'name' => $request->name,
                'address' => 0,
                'phone' => 0
            ]);

            $user = DB::select('select * from member where username = :uname', [
                'uname' => $request->email
            ]);
            session(['user' => $user[0]->mem_id]);

            $dir = session('prePage');
            return response()->json([
                'dir' => $dir
            ]);
        }
    }

    public function logout()
    {
        session(['staff' => 'false']);
        session(['admin' => 'false']);
        session(['login' => 'false']);
        session(['user' => null]);
        $dir = session('prePage');
        return redirect($dir);
    }






    // API
    public function login_api(Request $request)
    {
        try {
            $user = DB::select('select * from member where username = :uname and password= :pswd', [
                'uname' => $request->email,
                'pswd' => $request->pswd
            ]);
            return response()->json(['status' => 'success', 'user' => $user]);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'error' => $e->getMessage()]);
        }
    }
    public function register_api(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required',
                'email' => 'required|email:filter',
                'pswd' => 'required'
            ]);
            $user = DB::select('select * from member where username= :uname', [
                'uname' => $request->email
            ]);
            if ($user) {
                return response()->json(['error' => 'Email đã tồn tại']);
            } else {
                session(['login' => 'true']);

                DB::table("member")->insert([
                    'username' => $request->email,
                    'password' => $request->pswd,
                    'name' => $request->name,
                    'address' => 0,
                    'phone' => 0
                ]);

                $user = DB::select('select * from member where username = :uname', [
                    'uname' => $request->email
                ]);
                session(['user' => $user[0]->mem_id]);

                $dir = session('prePage');
                return response()->json([
                    'status' => 'success',
                    'dir' => $dir
                ]);
            }
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'error' => $e->getMessage()]);
        }
    }


    public function logout_api()
    {
        try {
            session(['staff' => 'false']);
            session(['admin' => 'false']);
            session(['login' => 'false']);
            session(['user' => null]);
            $dir = session('prePage');
            // return redirect($dir);
            return response()->json(['status' => 'success', 'dir' => $dir]);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'error' => $e->getMessage()]);
        }
    }
}
