<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\admin\member;
use App\Models\admin\order;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Http;

class MemberController extends Controller
{

    public function adminshow()
    {
        // $get = member::where('role', '!=', 'admin')
        //     ->orWhere('role', '=', null)
        //     ->get();
        // $get2 = member::where('role', 'admin')->get();
        // return view('admin.account.admin_admin_acc_page', ['accs' => $get, 'admin' => $get2, 'title' => 'Admin Account']);

        $response = Http::get('https://s25sneaker.000webhostapp.com/api/admin/accounts/admin');

        if ($response->successful()) {
            // Lấy dữ liệu JSON từ phản hồi
            $responseData = $response->json();

            $accs = $responseData['accs'];

            $admin = $responseData['admin'];

            return view('admin.account.admin_admin_acc_page', ['accs' => $accs, 'admin' => $admin, 'title' => 'Admin Account']);
        } else {
            $statusCode = $response->status();
            $errorMessage = $response->body();
        }
    }
    public function update_admin()
    {
        $aId = request('aId');

        $postData = [
            'mem_id' => $aId,
            'username' => request('aName'),
            'password' => request('aPass')
        ];

        $response = Http::post('https://s25sneaker.000webhostapp.com/api/admin/accounts/admin', $postData);

        if ($response->successful()) {
            return to_route('a.a.list');
        } else {
            // Xử lý lỗi nếu có
            $statusCode = $response->status();
            $errorMessage = $response->body();
            return response()->json(['error' => 'Failed to post data'], $statusCode);
        }

        // member::where('mem_id', request('aId'))->update([
        //     'username' => request('aName'),
        //     'password' => request('aPass')
        // ]);
        // return to_route('a.a.list');
    }
    public function staffshow()
    {
        $search = request('table_search');
        // if (isset($search)) {
        //     $get = member::all();
        //     $get2 = member::where('role', 'staff')
        //         ->where('mem_active', '!=', -1)
        //         ->where('username', 'like', '%' . $search . '%')
        //         ->get();
        // } else {
        //     $get = member::all();
        //     $get2 = member::where('role', 'staff')
        //         ->where('mem_active', '!=', -1)
        //         ->get();
        // }
        // return view('admin.account.admin_staff_acc_page', ['accs' => $get, 'staff' => $get2, 'title' => 'Admin Account']);

        $response = Http::get('https://s25sneaker.000webhostapp.com/api/admin/accounts/staff', ['table_search' => $search]);

        if ($response->successful()) {
            $responseData = $response->json();

            $accs = $responseData['accs'];

            $staff = $responseData['staff'];

            return view('admin.account.admin_staff_acc_page', ['accs' => $accs, 'staff' => $staff, 'title' => 'Admin Account']);
        } else {
            $statusCode = $response->status();
            $errorMessage = $response->body();
        }
    }
    public function update_staff(Request $request)
    {
        // $get = $request->all();
        // member::where('mem_id', $get['sId'])->update([
        //     'username' => $get['sUser'],
        //     'password' => $get['sPass'],
        //     'name' => $get['sName'],
        //     'phone' => $get['sPhone'],
        //     'mem_active' => $get['sStas']
        // ]);

        $get = $request->all();

        $sId = $get['sId'];

        $postData = [
            'mem_id' => $sId,
            'username' => $get['sUser'],
            'password' => $get['sPass'],
            'name' => $get['sName'],
            'address' => 'Hà Nội',
            'phone' => $get['sPhone'],
            'mem_active' => $get['sStas']
        ];


        $response = Http::post('https://s25sneaker.000webhostapp.com/api/updatestaff', $postData);

        if ($response->successful()) {
            // return to_route('a.a.list');
        } else {
            $statusCode = $response->status();
            $errorMessage = $response->body();
            return response()->json(['error' => 'Failed to post data'], $statusCode);
        }
    }
    public function delete_staff(Request $request)
    {
        // $get = $request->all();
        // member::where('mem_id', $get['sId'])->update([
        //     'username' => $get['sId'] . 'deleted',
        //     'password' => $get['sId'] . 'deleted',
        //     'name' => null,
        //     'mem_active' => -1
        // ]);
        // return response()->json([
        //     'message' => 'Account has been deleted successfully.',
        // ]);

        $get = $request->all();
        $response = Http::post('https://s25sneaker.000webhostapp.com/api/deletestaff', $get);
        if ($response->successful()) {
            // return to_route('a.b.list');
        } else {
            // Xử lý lỗi nếu có
            $statusCode = $response->status();
            $errorMessage = $response->body();
            return response()->json(['error' => 'Failed to post data'], $statusCode);
        }
    }
    public function addstaffred()
    {
        // $get = member::all();
        // return view('admin.account.admin_staff_acc_add', ['accs' => $get, 'title' => 'Add New Staff Account']);
        $response = Http::get('https://s25sneaker.000webhostapp.com/api/admin/accounts/staff/add');

        if ($response->successful()) {
            $responseData = $response->json();

            $accs = $responseData['accs'];

            return view('admin.account.admin_staff_acc_add', ['accs' => $accs, 'title' => 'Add New Staff Account']);
        } else {
            $statusCode = $response->status();
            $errorMessage = $response->body();
        }
    }
    public function addstaff()
    {
        // $staff = new member();
        // $staff->username = request('nsUser');
        // $staff->password = request('nsPass');
        // $staff->name = request('nsName');
        // $staff->phone = request('nsPhone');
        // $staff->address = '0';
        // $staff->role = 'staff';
        // $staff->save();

        // return to_route('a.s.list');

        $postData = [
            'username' => request('nsUser'),
            'password' => request('nsPass'),
            'name' => request('nsName'),
            'phone' => request('nsPhone'),
            'address' => '0',
            'role' => 'staff',
        ];

        $response = Http::post('https://s25sneaker.000webhostapp.com/api/admin/accounts/staff/add', $postData);

        if ($response->successful()) {
            return to_route('a.s.list');
        } else {
            // Xử lý lỗi nếu có
            $statusCode = $response->status();
            $errorMessage = $response->body();
            return response()->json(['error' => 'Failed to post data'], $statusCode);
        }
    }
    public function membershow()
    {
        // $get = member::where('role', null)
        //     ->where('mem_active', '!=', -1)
        //     ->paginate(10);
        // $count = member::where('role', null)
        //     ->where('mem_active', '!=', -1)
        //     ->count('mem_id');
        // $nor = order::where('order_status', 1)
        //     ->groupBy('mem_id')
        //     ->get(['mem_id', order::raw('COUNT(order_id) as nor')]);
        // return view('admin.account.admin_member_acc_page', ['member' => $get, 'nor' => $nor, 'count' => $count, 'title' => 'Member Accounts']);
        $page = request('page', 1);

        $response = Http::get('https://s25sneaker.000webhostapp.com/api/admin/accounts/member', ['page' => $page]);

        if ($response->successful()) {
            $responseData = $response->json();

            $count = $responseData['count'];

            $nor = $responseData['nor'];

            $members = collect($responseData['member']['data']);

            $perPage = 10;

            // $currentPage = $responseData['member']['current_page'];

            $paginator = new LengthAwarePaginator(
                $members,
                $responseData['count'],
                $perPage,
                $page,
                ['path' => url()->current(), 'query' => request()->query()]
            );

            return view('admin.account.admin_member_acc_page', ['member' => $paginator, 'nor' => $nor, 'count' => $count, 'title' => 'Member Accounts']);
        } else {
            $statusCode = $response->status();
            $errorMessage = $response->body();
        }
    }
    public function unban(Request $request)
    {
        // $get = $request->all();
        // member::where('mem_id', $get['mid'])->update([
        //     'mem_active' => 1
        // ]);
        $get = $request->all();
        $response = Http::post('https://s25sneaker.000webhostapp.com/api/unbanmember', $get);
        if ($response->successful()) {
        } else {
            // Xử lý lỗi nếu có
            $statusCode = $response->status();
            $errorMessage = $response->body();
            return response()->json(['error' => 'Failed to post data'], $statusCode);
        }
    }
    public function ban(Request $request)
    {
        // $get = $request->all();
        // member::where('mem_id', $get['mid'])->update([
        //     'mem_active' => 0
        // ]);
        $get = $request->all();
        $response = Http::post('https://s25sneaker.000webhostapp.com/api/banmember', $get);
        if ($response->successful()) {
        } else {
            // Xử lý lỗi nếu có
            $statusCode = $response->status();
            $errorMessage = $response->body();
            return response()->json(['error' => 'Failed to post data'], $statusCode);
        }
    }





    //  API
    //  API

    public function adminshow_api()
    {
        try {
            $get = member::where('role', '!=', 'admin')
                ->orWhere('role', '=', null)
                ->get();
            $get2 = member::where('role', 'admin')->get();
            return response()->json(['status' => 'success', 'accs' => $get, 'admin' => $get2], 201);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'error' => $e->getMessage()], 500);
        }
    }
    public function update_admin_api(Request $request)
    {
        try {
            $request->validate([
                'username' => 'required|string',
                'password' => 'required|string',
            ]);
            $id = $request->input('mem_id');
            member::where('mem_id', $id)->update([
                'username' => $request->input('username'),
                'password' => $request->input('password')
            ]);
            return response()->json(['status' => 'success', 'message' => 'Cập nhật admin thành công'], 201);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Cập nhật admin thất bại', 'error' => $e->getMessage()], 500);
        }
    }
    public function staffshow_api()
    {
        try {
            $search = request('table_search');
            if (isset($search)) {
                $get = member::all();
                $get2 = member::where('role', 'staff')
                    ->where('mem_active', '!=', -1)
                    ->where('username', 'like', '%' . $search . '%')
                    ->get();
            } else {
                $get = member::all();
                $get2 = member::where('role', 'staff')
                    ->where('mem_active', '!=', -1)
                    ->get();
            }

            return response()->json(['status' => 'success', 'accs' => $get, 'staff' => $get2,], 201);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'error' => $e->getMessage()], 500);
        }
    }
    public function update_staff_api(Request $request)
    {
        try {
            // $get = $request->all();
            // $request->validate([
            //     'username' => 'required|string',
            //     'password' => 'required|string',
            //     'name' => 'required|string',
            //     'phone' => 'required|numeric',
            // ]);

            // $get = $request->all();
            $id = $request->input('mem_id');
            member::where('mem_id', $id)->update([
                'username' => $request->input('username'),
                'password' => $request->input('password'),
                'name' => $request->input('name'),
                'address' => $request->input('address'),
                'phone' => $request->input('phone'),
                'mem_active' => $request->input('mem_active'),
            ]);
            // member::where('mem_id',3)->update([
            //     'username' => 'Sap1@gmail.com',
            //     'password' => $get['password'],
            //     'name' => $get['name'],
            //     'address' => $get['address'],
            //     'phone' => $get['phone'],
            //     'mem_active' => $get['mem_active']
            // ]);
            return response()->json(['status' => 'success', 'message' => 'Cập nhật nhân viên thành công'], 201);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Cập nhật nhân viên thất bại', 'error' => $e->getMessage()], 500);
        }
    }
    public function delete_staff_api(Request $request)
    {
        try {
            $get = $request->all();
            member::where('mem_id', $get['sId'])->update([
                'username' => $get['sId'] . 'deleted',
                'password' => $get['sId'] . 'deleted',
                'name' => null,
                'mem_active' => -1
            ]);
            return response()->json([
                'status' => 'success',
                'message' => 'Xóa nhân viên thành công.',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Xóa nhân viên thất bại.',
            ]);
        }
    }
    public function addstaffred_api()
    {
        try {
            $get = member::all();
            return response()->json(['status' => 'success', 'accs' => $get], 201);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'error' => $e->getMessage()], 500);
        }
    }
    public function addstaff_api(Request $request)
    {
        try {
            $request->validate([
                'username' => 'required|string',
                'password' => 'required|string',
                'name' => 'required|string',
                'phone' => 'required|numeric',
                'address' => 'required|string',
            ]);

            $staff = new member();
            $staff->username = $request->input('username');
            $staff->password = $request->input('password');
            $staff->name = $request->input('name');
            $staff->phone = $request->input('phone');
            $staff->address = $request->input('address');
            $staff->role = 'staff';
            $staff->save();

            return response()->json(['status' => 'success', 'message' => 'Thêm nhân viên thành công!', 'data' => $staff], 201);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Thêm nhân viên thất bại', 'error' => $e->getMessage()], 500);
        }
    }
    public function membershow_api()
    {
        try {
            $get = member::where('role', null)
                ->where('mem_active', '!=', -1)
                ->paginate(10);
            $count = member::where('role', null)
                ->where('mem_active', '!=', -1)
                ->count('mem_id');
            $nor = order::where('order_status', 1)
                ->groupBy('mem_id')
                ->get(['mem_id', order::raw('COUNT(order_id) as nor')]);
            return response()->json(['status' => 'success', 'member' => $get, 'nor' => $nor, 'count' => $count], 201);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'error' => $e->getMessage()], 500);
        }
    }
    public function unban_api(Request $request)
    {
        try {
            $get = $request->all();
            member::where('mem_id', $get['mid'])->update([
                'mem_active' => 1
            ]);
            return response()->json(['status' => 'success', 'message' => 'Bỏ ban thành công'], 201);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Bỏ ban thất bại thất bại', 'error' => $e->getMessage()], 500);
        }
    }
    public function ban_api(Request $request)
    {
        try {
            $get = $request->all();
            member::where('mem_id', $get['mid'])->update([
                'mem_active' => 0
            ]);
            return response()->json(['status' => 'success', 'message' => 'Ban thành công'], 201);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Ban thất bại thất bại', 'error' => $e->getMessage()], 500);
        }
    }
}
