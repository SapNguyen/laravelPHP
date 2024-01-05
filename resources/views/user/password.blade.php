@extends('account')

@section('content')
<div class="container col-lg-9 shadow rounded" id='password'>
    <p class="title">Đổi Mật Khẩu</p>
    <div class="content" align="left">
        <form id="form_password">
            @csrf
            <table>
                <tr id="tr_pass" class="tr_input">
                    <td class="form-label">
                        Mật Khẩu Hiện Tại
                    </td>
                    <td style="padding-left:15px;width:365px">
                        <input type="password" name='old_pass' class="form-control" @required(true) style="max-width:350px">
                    </td>
                </tr>
                <tr class="tr_input" id='tr_newpass'>
                    <td class="form-label">
                        Mật Khẩu Mới
                    </td>
                    <td style="padding-left:15px;width:365px">
                        <input type="password" name="new_pass" class="form-control" @required(true) style="max-width:350px">
                    </td>
                </tr>
                <tr id="tr_confirm" class="tr_input">
                    <td class="form-label">
                        Xác Nhận Mật Khẩu
                    </td>
                    <td style="padding-left:15px;width:365px">
                        <input type="password" name="confirm_pass" class="form-control" @required(true) style="max-width:350px">
                    </td>
                </tr>
                <tr>
                    <td colspan="2"  align="center">
                        <button class="btn-disabled" id='btn_pass' disabled>Xác nhận</button>
                    </td>
                </tr>
            </table>
        </form>
    </div>
</div>                
@endsection