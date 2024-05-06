<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\admin\member;
use App\Models\admin\order;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class MemberController extends Controller
{
    //[GET] /admin/accounts/admin (use)
    public function adminshow()
    {
        $get = member::where('role', '!=', 'admin')
            ->orWhere('role', '=', null)
            ->get();
        $get2 = member::where('role', 'admin')->get();
        return view('admin.account.admin_admin_acc_page', ['accs' => $get, 'admin' => $get2, 'title' => 'Admin Account']);
    }

    //[POST] /admin/accounts/admin (use)
    public function update_admin(Request $request)
    {
        try {
            $mem_id = $request->input('aId');
            $username = $request->input('aName');
            $password = $request->input('aPass');

            DB::update('update `member` set `username` = ?, `password` = ? where `mem_id` = ?', [$username, $password, $mem_id]);
            return to_route('a.a.list');
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'error' => $e->getMessage()], 500);
        }
    }

    //[GET] /admin/accounts/staff (use)
    public function staffshow()
    {
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
        return view('admin.account.admin_staff_acc_page', ['accs' => $get, 'staff' => $get2, 'title' => 'Admin Account']);
    }

    //[POST] /updatestaff (use)
    public function update_staff(Request $request)
    {
        $get = $request->all();
        DB::update('update `member` set `username` = ?, `password` = ?, `name` = ?, `phone` = ?, `mem_active` = ? where `mem_id` = ?', [$get['sUser'], $get['sPass'], $get['sName'], $get['sPhone'], $get['sStas'], $get['sId']]);
    }
    //[POST] /deletestaff(use)
    public function delete_staff(Request $request)
    {
        $get = $request->all();
        DB::update('update `member` set  `mem_active` = -1 where `mem_id` = ?', [$get['sId']]);

        return response()->json([
            'message' => 'Account has been deleted successfully.',
        ]);
    }

    //[GET] /admin/accounts/staff/add (use)
    public function addstaffred()
    {
        $get = member::all();
        return view('admin.account.admin_staff_acc_add', ['accs' => $get, 'title' => 'Add New Staff Account']);
    }

    //[POST] /admin/accounts/staff/add (use)
    public function addstaff()
    {
        $username = request('nsUser');
        $password = request('nsPass');
        $name = request('nsName');
        $phone = request('nsPhone');

        DB::insert('insert into `member` (`username`, `password`, `name`, `phone`, `address`, `role`) values (?, ?, ?, ?, ?, ?)', [$username, $password, $name, $phone, '0', 'staff']);

        return to_route('a.s.list');
    }
    //[GET] /admin/accounts/member
    public function membershow()
    {
        $get = Member::where('role', null)
            ->where('mem_active', '!=', -1)
            ->withCount('orders')
            ->paginate(10);
        $count = member::where('role', null)
            ->where('mem_active', '!=', -1)
            ->count('mem_id');
        return view('admin.account.admin_member_acc_page', ['member' => $get, 'count' => $count, 'title' => 'Member Accounts']);
    }
    //[POST] /unbanmember (use)
    public function unban(Request $request)
    {
        $get = $request->all();
        DB::update('update `member` set  `mem_active` = 1 where `mem_id` = ?', [$get['mid']]);
    }
    //[POST] /banmember (use)
    public function ban(Request $request)
    {
        $get = $request->all();
        DB::update('update `member` set  `mem_active` = -1 where `mem_id` = ?', [$get['mid']]);
    }







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
