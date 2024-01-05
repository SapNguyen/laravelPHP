<!DOCTYPE html>
<html lang="en">
<head>
    <title>Title</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-iYQeCzEYFbKjA/T2uDLTpkwGzCiq6soy8tYaI1GyVh/UjpbCx/TYkiZhlZB6+fzT" crossorigin="anonymous">
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@48,400,0,0" />
  <link rel="stylesheet" href="{{asset('css/user/payment.css')}}">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-iYQeCzEYFbKjA/T2uDLTpkwGzCiq6soy8tYaI1GyVh/UjpbCx/TYkiZhlZB6+fzT" crossorigin="anonymous">
    <link rel="stylesheet" href="{{asset('css/owl.carousel.min.css')}}">
    <link rel="stylesheet" href="{{asset('css/owl.theme.default.min.css')}}">
    
    <script src="{{asset('js/jquery.min.js')}}"></script>
    <script src="{{asset('js/owl.carousel.min.js')}}"></script>
</head>
<body>
    <div class="main">
        <div class="payment-product">
            <div class="payment-product-info">
                <div class="payment-product-info-header">
                    <a href="" class="payment-product-info-header-logo">
                        <h1 class="payment-product-info-header-logo-text">GROUP 7</h1>
                    </a>
                    <div class="payment-product-info-header-breadcrumb">
                        <div class="payment-product-info-header-breadcrumb-item">
                          <a href="">Giỏ hàng</a>
                        </div>
                        <div class="payment-product-info-header-breadcrumb-item-current">
                          Thông tin giao hàng
                        </div>
                        <div class="payment-product-info-header-breadcrumb-item-pt">Phương thức thanh toán</div>
                    </div>
                </div>
                <form id="form_payment" action="/payment/insert">
                <div class="payment-product-section">
                  <div class="payment-product-section-header">
                    <h2 class="payment-product-section-header-title">Thông tin giao hàng</h2>
                  </div>
                  @if (isset($account))
                  <div class="payment-product-section-content">
                    {{-- <div class="payment-product-section-content-name">
                      <div class="payment-product-section-content-wrapper">
                        
                      </div>
                    </div> --}}
                    <div class="payment-product-section-content-email">
                      <div class="payment-product-section-content-wrapper-email">
                        <label for="name" style="margin-bottom: 10px">Tên:</label>
                        <input type="text" placeholder="Họ và tên" spellcheck="false" class="input payment-product-section-content-wrapper-sdt-sdt" 
                        size="30" id="billing_address_full_name" name="txtHoten" value="{{$account->name}}" id="name" required>
                        <p class="error-msg"></p>
                      </div>
                    </div>
                    <div class="payment-product-section-content-sdt">
                      <div class="payment-product-section-content-wrapper-sdt">
                        <label for="i_phone" style="margin-bottom: 10px">Số điện thoại:</label>
                        @if ($account->phone)
                          <input type="text" placeholder="Số điện thoại" class="input payment-product-section-content-wrapper-sdt-sdt"
                           id="i_phone" name="txtPhone" value="(+84) {{$account->phone}}" required>
                        @else
                          <input type="text" placeholder="Số điện thoại" spellcheck="false" class="input payment-product-section-content-wrapper-sdt-sdt"
                           id="i_phone" name="txtPhone" value="" required>
                        @endif
                        <p class="error-msg"></p>
                      </div>
                    </div>
                    <div class="payment-product-section-content-address">
                      <div class="payment-product-section-content-wrapper-address">
                        <label for="i_address" style="margin-bottom: 10px">Địa chỉ:</label>
                        @if ($account->address != 0)
                          <input type="text" placeholder="Địa chỉ" spellcheck="false" class="input payment-product-section-content-wrapper-address-address" 
                          size="30" id="billing_address_address" name="txtDiachi" value="{{$account->address}}" id="i_address" required>
                          <p class="error-msg"></p>
                        @else
                          <input type="text" placeholder="Địa chỉ" spellcheck="false" class="input payment-product-section-content-wrapper-address-address" 
                          size="30" id="billing_address_address" name="txtDiachi" value="" id="i_address" required>
                          <p class="error-msg"></p>
                        @endif
                      </div>
                    </div>
                  </div>
                  @endif
                </form>
                <div class="payment-product-footer">
                  <div class="payment-product-footer-form">
                    <button class="payment-product-footer-content btn_disabled" type="submit" id="btn_submit" disabled>
                        Hoàn tất
                    </button>
                  </div>
                  <a href="/cart" class="payment-product-footer-link">Giỏ hàng</a>
                </div>
                </div>
            </div>
        </div>
        <div class="price-product">
          <div class="price-product-content">
            <div class="price-product-content-content">
              <div class="price-product-sections">
                @if (isset($products))
                  @for ($i = 0; $i < count($products); $i++)
                  <div class="price-product-sections-order-list">
                    <div class="price-product-sections-order-list-img">
                      <img src="/img/product/{{$products[$i][0]->product_id}}/{{explode(',',$products[$i][0]->product_image)[0]}}" alt="" class="price-product-sections-order-list-img-img">
                    </div>
                    <div class="price-product-sections-order-list-info">
                      <div class="price-product-sections-order-list-info-name">
                        {{$products[$i][0]->product_name}}
                      </div>
                      <div style="color: #888;margin-top:5px;font-size:14px">
                        Size: {{$products[$i][0]->size}},
                        {{$products[$i][0]->color}}
                      </div>
                    </div>
                    <div class="price-product-sections-order-list-info-quantity">
                      Số lượng : {{$products[$i][0]->amount}}
                    </div>
                    <div class="price-product-sections-order-list-price">
                      @if ($products[$i][0]->discount_value && strtotime($products[$i][0]->discount_end) >= strtotime(date("Y-m-d")) 
                             && strtotime($products[$i][0]->discount_start) <= strtotime(date("Y-m-d")))
                        <span>
                          {{number_format($products[$i][0]->amount * $products[$i][0]->product_price*(1-$products[$i][0]->discount_value/100))}}₫
                        </span>
                      @else
                        <span>
                          {{number_format($products[$i][0]->amount * $products[$i][0]->product_price)}}₫
                        </span>
                      @endif
                    </div>
                  </div>
                  @endfor
                @endif
                <div class="price-product-sections-payment">
                  <div class="price-product-sections-payment-ship">
                    <div class="price-product-sections-payment-ship-price">
                      <div class="price-product-sections-payment-ship-price-name">
                        Tạm tính
                      </div>
                      <div class="price-product-sections-payment-ship-price-price">
                        {{number_format($total)}}₫
                      </div>
                    </div>
                    <div class="price-product-sections-payment-ship-ship">
                      <div class="price-product-sections-payment-ship-ship-name">
                        Phí vận chuyển
                      </div>
                      <div class="price-product-sections-payment-ship-ship-price">
                        0₫
                      </div>
                    </div>
                  </div>
                  <div class="price-product-sections-payment-total">
                    <div class="price-product-sections-payment-total-total">
                      Tổng cộng
                    </div>
                    <div class="price-product-sections-payment-total-price">
                      {{number_format($total)}}₫
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
    </div>
</body>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"
    integrity="sha384-oBqDVmMz9ATKxIep9tiCxS/Z9fNfEXiDAYTujMAeBAsjFuCZSmKbSSUnQlmh/jp3" crossorigin="anonymous">
  </script>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/js/bootstrap.min.js"
    integrity="sha384-7VPbUDkoPSGFnVtYi0QogXtr74QeVeeIs99Qfg5YCF+TidwNdjvaKZX19NZ/e6oz" crossorigin="anonymous">
  </script>
  <script src="{{asset('js/user/payment.js')}}"></script>
</html>