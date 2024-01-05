@extends('account')

@section('css')
    <link rel="stylesheet" href="{{asset('css/user/receipt.css')}}">
@endsection

@section('content')
<div class="container col-lg-9"  id='receipt'>
    <div style="display: none" id="uid">{{session('user')}}</div>
    <div class='item-box' style="position: sticky;top:0">
        <a class="r-tab {{ request()->is('account/receipt') ? 'active' : '' }}" href="/account/receipt">Tất cả</a>
        <a class="r-tab {{ request()->is('account/receipt/0') ? 'active' : '' }}" href="/account/receipt/0">Chờ duyệt</a>
        <a class="r-tab {{ request()->is('account/receipt/1') ? 'active' : '' }}" href="/account/receipt/1">Vận chuyển</a>
        <a class="r-tab {{ request()->is('account/receipt/2') ? 'active' : '' }}" href="/account/receipt/2">Hoàn thành</a>
        <a class="r-tab {{ request()->is('account/receipt/-1') ? 'active' : '' }}" href="/account/receipt/-1">Đã hủy</a>
    </div>
    @if (count($receipts) > 0)  
        @for ($i = 0; $i < count($receipts); $i++)
            <div class="receipt" id="{{$receipts[$i]->receipt_id}}">
                @if ($receipts[$i]->receipt_status == 1)
                    <div class="receipt-header">
                        <span class="status">VẬN CHUYỂN</span>
                    </div>
                @elseif ($receipts[$i]->receipt_status == 0)
                    <div class="receipt-header">
                        <span class="status">CHỜ DUYỆT</span>
                    </div>
                @elseif ($receipts[$i]->receipt_status == 2)
                    <div class="receipt-header">
                        <span class="status">HOÀN THÀNH</span>
                    </div>
                @else
                    <div class="receipt-header">
                        <span class="status">ĐÃ HỦY</span>
                    </div>
                @endif
                @for ($j = 0; $j < count($receipt_products); $j++)
                    @if ($receipt_products[$j]->receipt_id == $receipts[$i]->receipt_id)
                    <div class='product-box'>
                        <div class="product">
                            <img src="/img/product/{{$receipt_products[$j]->product_id}}/{{explode(",",$receipt_products[$j]->product_image)[0]}}" class="img">
                            <div class="prod-info">
                                <a href="/products/{{$receipt_products[$j]->product_id}}">
                                    <div class="prod-name">
                                        {{$receipt_products[$j]->product_name}}
                                    </div>
                                </a>
                                <div class="prod-cate">
                                    Size: {{$receipt_products[$j]->size}}, Màu: {{$receipt_products[$j]->color}} 
                                </div>
                            </div>
                            <div class="prod-amount">
                                x{{$receipt_products[$j]->quantity}}
                            </div>
                            <div class="prod-price">
                                {{number_format($receipt_products[$j]->sell_price)}}₫
                            </div>
                        </div>
                    </div>
                    @endif  
                @endfor
                <div class="receipt-footer">
                    @if ($receipts[$i]->receipt_status == 0)
                        <div class="btn-drop btn-gray" id="{{$receipts[$i]->receipt_id}}">
                            Hủy đơn hàng
                        </div>
                    @elseif ($receipts[$i]->receipt_status == 1)
                        <div class="btn-confirm btn-red" id="{{$receipts[$i]->receipt_id}}">
                            Đã nhận hàng
                        </div>
                    @elseif ($receipts[$i]->receipt_status == 2)
                        @if (!$receipts[$i]->comment)
                            <div class="feedback btn-red">
                                Đánh giá
                            </div>
                        @endif
                        @if ($receipts[$i]->comment)
                            <div class="btn-gray view-feedback">
                                Xem đánh giá
                            </div>
                        @endif
                    @endif
                    <div style="display: flex;margin-left: 40px;align-items: center">
                        Thành tiền:
                        <p style="color:red;font-size:18px;margin-left:15px" >
                            {{number_format($receipts[$i]->receipt_value)}}₫
                        </p>
                    </div>
                </div>
            </div>
        @endfor
    @else
      <div class="empty-alert">
        <span class="material-symbols-outlined cart-ico" >
            receipt
        </span>
        <p style="text-align: center;font-size:20px">Chưa có đơn hàng</p>
      </div>
    @endif
    <div class='feedback-screen' style="display:none">
        <div class='success-overlay'>
        </div>
        <div class='feedback-box'>
            <p class="feedback-title">Đánh Giá Sản Phẩm</p>
            {{-- <div>
                <div class="flex starbox">
                    @for ($i = 1; $i < 6; $i++)
                    <div class="div_star" id="{{$i}}">
                        <span class="material-symbols-outlined star" style="font-size: 30px">
                        star
                        </span>
                        <span class="material-symbols-outlined star-fill star-deactive">
                        star
                        </span>
                    </div>
                    @endfor
                </div>
                <textarea class="comment" placeholder="Nhập đánh giá của bạn"></textarea>
            </div> --}}
            <div class="flex mt-3" style="justify-content: center">
                <p class="error-txt"></p>
                <button class="btn_cancel-feedback">TRỞ LẠI</button>
                <button class="btn_confirm-feedback">ĐÁNH GIÁ</button>
            </div>
        </div>
    </div>
    <div class='feedback-screen-view' style="display:none">
        <div class='feedback-overlay'>
        </div>
        <div class='feedback-box-view'>
            <p class="feedback-title">Đánh Giá Của Bạn</p>
            {{-- <div class="feedback-product-view">
                <div class="product-feedback">
                    <img src="" class="img-product-feedback">
                    <div style="margin-left: 10px;text-align: left">
                        <p class="name-product-feedback">Giày</p>
                        <p class="product-classify">Size: 31, Màu: Trắng</p>
                    </div>
                </div>
                <div class="flex mt-3" style="justify-content: left;">
                    <div class="comment-box">
                        <div class="avata material-symbols-outlined">
                            account_circle
                        </div>
                        <div class="comment-info">
                            <div class="name">
                                Tam Minh
                            </div>
                            <div class="rate">
                                <div class="flex" >
                                    @for ($k = 0; $k < 4; $k++)
                                        <div style="position: relative;height:20px">
                                            <span class="material-symbols-outlined star" style="font-size: 20px;cursor: default">
                                                star
                                            </span>
                                            <span class="material-symbols-outlined star-fill" style="width: 100%;font-size:20px;cursor: default">
                                                star
                                            </span>
                                        </div>
                                    @endfor
                                    @for ($k = 0; $k < 5-4; $k++)
                                        <div style="position: relative;height:20px">
                                            <span class="material-symbols-outlined star" style="font-size: 20px;cursor: default">
                                                star
                                            </span>
                                            <span class="material-symbols-outlined star-fill" style="width: 0px;font-size: 20px;cursor: default">
                                                star
                                            </span>
                                        </div>
                                    @endfor
                                </div>
                            </div>
                            <div class="content">
                                OK
                            </div>
                        </div>
                    </div>    
                </div>
            </div> --}}
            <div style="display: flex; justify-content: right;margin-top: 20px">
                <div class="btn-gray btn_close" style="width: 100px">OK</div>
            </div>
        </div>
    </div>
    @section('script')
        <script src="{{asset('js/user/receipt.js')}}"></script>
    @endsection
</div>  
@endsection