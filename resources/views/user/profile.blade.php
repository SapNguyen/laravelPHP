@extends('account')

@section('css')
    <link rel="stylesheet" href="{{asset('css/user/profile.css')}}">
@endsection

@section('content')
<div class="container col-lg-9 shadow rounded"  id='profile'>
    <p class="title">Hồ Sơ Của Tôi</p>
    <div class="content" align="left">
        <form id="form_profile">
            @csrf
            <table>
                <tr class='tr_input' id='tr_name'>
                    <td class="form-label">
                        Tên
                    </td>
                    <td style="padding-left:15px">
                        <input type="text" class="form-control " id="name" name="name" @required(true) value="{{$user->name}}">
                    </td>
                </tr>
                <tr>
                    <td class="form-label">
                        Email
                    </td>
                    <td style="padding-left:15px">
                        {{$user->username}}
                    </td>
                </tr>
                <tr class='tr_input' id='tr_phone'>
                    <td class="form-label">
                        Số điện thoại
                    </td>
                    @if ($user->phone)
                        <td style="padding-left:15px">
                            <input type="text" class="form-control " id="phone" name="phone" value="(+84) {{$user->phone}}" >
                        </td>
                    @else
                        <td style="padding-left:15px">
                            <input type="text" class="form-control " id="phone" name="phone" value="" >
                        </td>
                    @endif
                    
                </tr>
                <tr class='tr_input' id='tr_address'>
                    <td class="form-label">
                        Địa chỉ
                    </td>
                    <td style="padding-left:15px">
                        @if ($user->address != 0)
                        <input type="text" class="form-control " id="address" name="address" value="{{$user->address}}" >
                        @else
                        <input type="text" class="form-control " id="address" name="address" value="" >
                        @endif
                        
                    </td>
                </tr>
                <tr>
                    <td colspan="2"  align="center">
                        <button id="btn_profile" class="button">Lưu</button>
                    </td>
                </tr>
            </table>
        </form>
    </div>
</div>  
@endsection