<?php

namespace App\Http\Controllers\admin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\admin\member;
use App\Models\admin\receipt;

class MemberController extends Controller
{
    
    public function adminshow(){
        $get = member::where('role', '!=', 'admin')
        ->orWhere('role', '=', null)
        ->get();
        $get2 = member::where('role', 'admin')->get();
        return view('admin.account.admin_admin_acc_page', ['accs'=>$get, 'admin'=>$get2, 'title'=>'Admin Account']);
    }
    public function update_admin(){
        member::where('mem_id', request('aId'))->update([
            'username'=>request('aName'),
            'password'=>request('aPass')
        ]);
        return to_route('a.a.list');
    }
    public function staffshow(){
        $search = request('table_search');
        if(isset($search)){
            $get = member::all();
            $get2 = member::where('role', 'staff')
            ->where('mem_active', '!=', -1)
            ->where('username', 'like', '%'.$search.'%')
            ->get();
        }
        else{
            $get = member::all();
            $get2 = member::where('role', 'staff')
            ->where('mem_active', '!=', -1)
            ->get();
        }
        
        return view('admin.account.admin_staff_acc_page', ['accs'=>$get, 'staff'=>$get2, 'title'=>'Admin Account']);
    }
    public function update_staff(Request $request){
        $get = $request->all();
        member::where('mem_id', $get['sId'])->update([
            'username'=>$get['sUser'],
            'password'=>$get['sPass'],
            'name'=>$get['sName'],
            'phone'=>$get['sPhone'],
            'mem_active'=>$get['sStas']
        ]);
    }
    public function delete_staff(Request $request){
        $get = $request->all();
        member::where('mem_id', $get['sId'])->update([
            'username'=>$get['sId'].'deleted',
            'password'=>$get['sId'].'deleted',
            'name'=>null,
            'mem_active'=>-1
        ]);
        return response()->json([
            'message'=>'Account has been deleted successfully.',
        ]);
    }
    public function addstaffred(){
        $get = member::all();
        return view('admin.account.admin_staff_acc_add', ['accs'=>$get, 'title'=>'Add New Staff Account']);
    }
    public function addstaff(){
        $staff = new member();
        $staff->username = request('nsUser');
        $staff->password = request('nsPass');
        $staff->name = request('nsName');
        $staff->phone = request('nsPhone');
        $staff->address = '0';
        $staff->role = 'staff';
        $staff->save();

        return to_route('a.s.list');
    }
    public function membershow(){
        $get = member::where('role', null)
        ->where('mem_active', '!=', -1)
        ->paginate(10);
        $count = member::where('role', null)
        ->where('mem_active', '!=', -1)
        ->count('mem_id');
        $nor = receipt::where('receipt_status', 1)
        ->groupBy('mem_id')
        ->get(['mem_id', receipt::raw('COUNT(receipt_id) as nor')]);
        return view('admin.account.admin_member_acc_page', ['member'=>$get, 'nor'=>$nor, 'count'=>$count, 'title'=>'Member Accounts']);
    }
    public function unban(Request $request){
        $get = $request->all();
        member::where('mem_id', $get['mid'])->update([
            'mem_active'=>1
        ]);
    }
    public function ban(Request $request){
        $get = $request->all();
        member::where('mem_id', $get['mid'])->update([
            'mem_active'=>0
        ]);
    }
}
