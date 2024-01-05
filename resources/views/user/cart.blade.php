<!doctype html>
<html lang="en">

<head>
  <title>Title</title>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="_token" content="{{ csrf_token() }}"/>

  <!-- Bootstrap CSS v5.2.1 -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-iYQeCzEYFbKjA/T2uDLTpkwGzCiq6soy8tYaI1GyVh/UjpbCx/TYkiZhlZB6+fzT" crossorigin="anonymous">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@48,400,0,0" />
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Sharp:opsz,wght,FILL,GRAD@48,400,0,0" />
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
    <link rel="stylesheet" href="{{asset('css/module/header.css')}}">
    <link rel="stylesheet" href="{{asset('css/module/footer.css')}}">
    <link rel="stylesheet" href="{{asset('css/user/cart.css')}}">
    <script src="{{asset('js/jquery.min.js')}}"></script>
    <script src="{{asset('js/user/cart.js')}}"></script>
</head>

<body style='background: #f5f5f5'>
  @if (isset($count_deleted))
      <p style="display: none" id="count_deleted">{{$count_deleted}}</p>
  @endif
  @include('module.header')
  <main>
    @foreach ($pscs as $psc)
      <div class='psc' style="display: none">
        {{$psc->product_id}};{{$psc->size}};{{$psc->color}};{{$psc->quantity}}
      </div>
    @endforeach
    
    <div class='container-box'>
      <div class="space"></div>
      <div class='content-box'>
        <div class='item-box'>
          <div class="div_check">
            <input type="checkbox" class="checkbox check-all">
          </div>
          <div class="div_product">Sản phẩm</div>
          <div class="div_price_each gray">Đơn giá</div>
          <div class="div_amount gray">Số lượng</div>
          <div class="div_price_all gray">Số tiền</div>
          <div class="div_action gray">Thao tác</div>
        </div>
        @if (count($products) > 0 || isset($pri_product))  
          <div style='background:white'>
            @if (isset($pid) && isset($pri_product))
              <div class="item-box product">
                <p class="cid" style="display: none">{{$pri_product->cart_id}}</p>
                <div class="div_check">
                    @if ($pri_product->product_id == $pid)
                      <input type="checkbox" name="" id="{{$pri_product->product_id}}" class="checkbox check-one" checked>
                    @else
                      <input type="checkbox" name="" id="{{$pri_product->product_id}}" class="checkbox check-one">
                    @endif
                </div>
                <div class="div_product">
                  <div class="div_product-info">
                    <img src="/img/product/{{$pri_product->product_id}}/{{explode(',',$pri_product->product_image)[0]}}" class='product-img'>
                    <a href='/products/{{$pri_product->product_id}}' class="prod-link">
                      <p class="txt_product ml">{{$pri_product->product_name}}</p>
                    </a>
                    <div style="position: relative">
                      <div class="div_size">
                        <div class='flex'>
                          <p>Size: </p>
                          <p class="size">{{$pri_product->size}}</p>
                          <span class="material-symbols-outlined ml" style="font-size: 18px">
                            expand_more
                          </span>
                        </div>
                        <p class="color">{{$pri_product->color}}</p>
                      </div>
                      <div class="div_choose-size">
                        <div style="display: flex;justify-content: center">
                          <div class="size-arrow-outer">
                            <div class="size-arrow"></div>
                          </div>
                        </div>
                        <div>
                          <p style="font-size: 20px;color: #888">Size:</p>
                          <div>
                            @for ($j = 0; $j < count($sizes); $j++)
                                @if ($sizes[$j]->product_id == $pri_product->product_id)
                                    @if ($sizes[$j]->size ==  $pri_product->size)
                                    <div class="size-box selected">{{$pri_product->size}}</div>
                                    @else
                                      <div class="size-box">{{$sizes[$j]->size}}</div>
                                    @endif
                                @endif
                            @endfor
                          </div>
                        </div>
                        <div>
                          <p style="font-size: 20px;color: #888;margin-top:10px">Màu:</p>
                          <div>
                            @for ($j = 0; $j < count($colors); $j++)
                                @if ($colors[$j]->product_id == $pri_product->product_id)
                                    @if ($colors[$j]->color ==  $pri_product->color)
                                    <div class="size-box selected" style="width:auto">{{$pri_product->color}}</div>
                                    @else
                                      <div class="size-box" style="width:auto">{{$colors[$j]->color}}</div>
                                    @endif
                                @endif
                            @endfor
                          </div>
                        </div>
                        <div class="flex mt-3" style="justify-content: center">
                          <button class="btn_cancel-size">TRỞ LẠI</button>
                          <button class="btn_confirm-size">XÁC NHẬN</button>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="div_price_each">
                  @if ($pri_product->discount_value && strtotime($pri_product->discount_end) >= strtotime(date("Y-m-d")) 
                  && strtotime($pri_product->discount_start) <= strtotime(date("Y-m-d")))
                    <p class="price-disabled">{{number_format($pri_product->product_price)}}₫</p>
                    {{number_format($pri_product->product_price * (1 - $pri_product->discount_value/100))}}₫
                  @else
                    {{number_format($pri_product->product_price)}}₫
                  @endif
                </div>
                <div class="div_amount">
                  <div class='quantity-change' >
                    <button class="btn-change btn_dec">
                      <span class="material-symbols-outlined">
                        remove
                      </span>
                    </button>
                    <div style="width: 100%;">
                      <input type="number" name="quantity" class="quantity" min="1" value='{{$pri_product->amount}}' max='{{$pri_product->quantity}}'
                        onkeydown="javascript: return ['Backspace','Delete','ArrowLeft','ArrowRight'].includes(event.code) ? true : !isNaN(Number(event.key)) && event.code!=='Space'">
                    </div>
                    <button class="btn-change btn_inc">
                      <span class="material-symbols-outlined">
                        add
                      </span>
                    </button>
                  </div>
                </div>
                @if ($pri_product->discount_value && strtotime($pri_product->discount_end) >= strtotime(date("Y-m-d")) 
                && strtotime($pri_product->discount_start) <= strtotime(date("Y-m-d")))
                  <div class="div_price_all">
                    {{number_format($pri_product->product_price * (1 - $pri_product->discount_value/100) * $pri_product->amount)}}₫
                  </div>
                @else
                  <div class="div_price_all">
                    {{number_format($pri_product->product_price * $pri_product->amount)}}₫
                  </div>
                @endif
                <div class="div_action">
                  <a class="p_remove">Xóa</a>
                </div>
              </div>
              @for ($i = 0; $i < count($products); $i++)
                <div class="data" style="display: none">
                  {{$products[$i]->cart_id}};{{$products[$i]->product_id}};{{$products[$i]->color}};{{$products[$i]->size}};{{$products[$i]->amount}}
                </div>
                @if ($products[$i]->product_id == $pri_product->product_id && $pri_product->size == $products[$i]->size
                  && $pri_product->color == $products[$i]->color)
                @else
                <div class="item-box product">
                  <p class="cid" style="display: none">{{$products[$i]->cart_id}}</p>
                  <div class="div_check">
                    <input type="checkbox" name="" id="{{$products[$i]->product_id}}" class="checkbox check-one">
                  </div>
                  <div class="div_product">
                    <div class="div_product-info">
                      <img src="/img/product/{{$products[$i]->product_id}}/{{explode(',',$products[$i]->product_image)[0]}}" class='product-img'>
                      <a href='/products/{{$products[$i]->product_id}}' class="prod-link">
                        <p class="txt_product ml">{{$products[$i]->product_name}}</p>
                      </a>
                      <div style="position: relative">
                        <div class="div_size">
                          <div class='flex'>
                            <p>Size: </p>
                            <p class="size">{{$products[$i]->size}}</p>
                            <span class="material-symbols-outlined ml" style="font-size: 18px">
                              expand_more
                            </span>
                          </div>
                          <p class="color">{{$products[$i]->color}}</p>
                        </div>
                        <div class="div_choose-size">
                          <div style="display: flex;justify-content: center">
                            <div class="size-arrow-outer">
                              <div class="size-arrow"></div>
                            </div>
                          </div>
                          <div>
                            <p style="font-size: 20px;color: #888">Size:</p>
                            <div>
                              @for ($j = 0; $j < count($sizes); $j++)
                                  @if ($sizes[$j]->product_id == $products[$i]->product_id)
                                      @if ($sizes[$j]->size ==  $products[$i]->size)
                                      <div class="size-box selected">{{$products[$i]->size}}</div>
                                      @else
                                          <div class="size-box">{{$sizes[$j]->size}}</div>
                                      @endif
                                  @endif
                              @endfor
                            </div>
                          </div>
                          <div>
                            <p style="font-size: 20px;color: #888;margin-top:10px">Màu:</p>
                            <div>
                              @for ($j = 0; $j < count($colors); $j++)
                                  @if ($colors[$j]->product_id == $products[$i]->product_id)
                                      @if ($colors[$j]->color ==  $products[$i]->color)
                                      <div class="size-box selected" style="width:auto">{{$products[$i]->color}}</div>
                                      @else
                                        <div class="size-box" style="width:auto">{{$colors[$j]->color}}</div>
                                      @endif
                                  @endif
                              @endfor
                            </div>
                          </div>
                          <div class="flex mt-3" style="justify-content: center">
                            <button class="btn_cancel-size">TRỞ LẠI</button>
                            <button class="btn_confirm-size">XÁC NHẬN</button>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="div_price_each">
                    @if ($products[$i]->discount_value  && strtotime($products[$i]->discount_end) >= strtotime(date("Y-m-d")) 
                    && strtotime($products[$i]->discount_start) <= strtotime(date("Y-m-d")))
                      <p class="price-disabled">{{number_format($products[$i]->product_price)}}₫</p>
                      {{number_format($products[$i]->product_price * (1 - $products[$i]->discount_value/100))}}₫
                    @else
                      {{number_format($products[$i]->product_price)}}₫
                    @endif
                  </div>
                  <div class="div_amount">
                    <div class='quantity-change' >
                      <button class="btn-change btn_dec">
                        <span class="material-symbols-outlined">
                          remove
                        </span>
                      </button>
                      <div style="width: 100%;">
                        <input type="number" name="quantity" class="quantity" min="1" value='{{$products[$i]->amount}}' max='{{$products[$i]->quantity}}'
                          onkeydown="javascript: return ['Backspace','Delete','ArrowLeft','ArrowRight'].includes(event.code) ? true : !isNaN(Number(event.key)) && event.code!=='Space'">
                      </div>
                      <button class="btn-change btn_inc">
                        <span class="material-symbols-outlined">
                          add
                        </span>
                      </button>
                    </div>
                  </div>
                  @if ($products[$i]->discount_value && strtotime($products[$i]->discount_end) >= strtotime(date("Y-m-d")) 
                  && strtotime($products[$i]->discount_start) <= strtotime(date("Y-m-d")))
                    <div class="div_price_all">
                      {{number_format($products[$i]->product_price * (1 - $products[$i]->discount_value/100)* $products[$i]->amount)}}₫
                    </div>
                  @else
                    <div class="div_price_all">{{number_format($products[$i]->product_price * $products[$i]->amount)}}₫</div>
                  @endif
                  <div class="div_action">
                    <a class="p_remove">Xóa</a>
                  </div>
                </div>
                @endif
              @endfor
            @else
              @for ($i = 0; $i < count($products); $i++)
              <div class="data" style="display: none">
                {{$products[$i]->cart_id}};{{$products[$i]->product_id}};{{$products[$i]->color}};{{$products[$i]->size}};{{$products[$i]->amount}}
              </div>
                <div class="item-box product">
                  <p class="cid" style="display: none">{{$products[$i]->cart_id}}</p>
                  <div class="div_check">
                    <input type="checkbox" name="" id="{{$products[$i]->product_id}}" class="checkbox check-one">
                  </div>
                  <div class="div_product">
                    <div class="div_product-info">
                      <img src="/img/product/{{$products[$i]->product_id}}/{{explode(',',$products[$i]->product_image)[0]}}" class='product-img'>
                      <a href='/products/{{$products[$i]->product_id}}' class="prod-link">
                        <p class="txt_product ml">{{$products[$i]->product_name}}</p>
                      </a>
                      <div style="position: relative">
                        <div class="div_size">
                          <div class='flex'>
                            <p>Size: </p>
                            <p class="size">{{$products[$i]->size}}</p>
                            <span class="material-symbols-outlined ml" style="font-size: 18px">
                              expand_more
                            </span>
                          </div>
                          <p class="color">{{$products[$i]->color}}</p>
                        </div>
                        <div class="div_choose-size">
                          <div style="display: flex;justify-content: center">
                            <div class="size-arrow-outer">
                              <div class="size-arrow"></div>
                            </div>
                          </div>
                          <div>
                            <p style="font-size: 20px;color: #888">Size:</p>
                            <div>
                              @for ($j = 0; $j < count($sizes); $j++)
                                  @if ($sizes[$j]->product_id == $products[$i]->product_id)
                                      @if ($sizes[$j]->size ==  $products[$i]->size)
                                      <div class="size-box selected">{{$products[$i]->size}}</div>
                                      @else
                                          <div class="size-box">{{$sizes[$j]->size}}</div>
                                      @endif
                                  @endif
                              @endfor
                            </div>
                          </div>
                          <div>
                            <p style="font-size: 20px;color: #888;margin-top:10px">Màu:</p>
                            <div>
                              @for ($j = 0; $j < count($colors); $j++)
                                  @if ($colors[$j]->product_id == $products[$i]->product_id)
                                      @if ($colors[$j]->color ==  $products[$i]->color)
                                      <div class="size-box selected" style="width:auto">{{$products[$i]->color}}</div>
                                      @else
                                        <div class="size-box" style="width:auto">{{$colors[$j]->color}}</div>
                                      @endif
                                  @endif
                              @endfor
                            </div>
                          </div>
                          <div class="flex mt-3" style="justify-content: center">
                            <button class="btn_cancel-size">TRỞ LẠI</button>
                            <button class="btn_confirm-size">XÁC NHẬN</button>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="div_price_each">
                    @if ($products[$i]->discount_value  && strtotime($products[$i]->discount_end) >= strtotime(date("Y-m-d")) 
                    && strtotime($products[$i]->discount_start) <= strtotime(date("Y-m-d")))
                      <p class="price-disabled">{{number_format($products[$i]->product_price)}}₫</p>
                      {{number_format($products[$i]->product_price * (1 - $products[$i]->discount_value/100))}}₫
                    @else
                      {{number_format($products[$i]->product_price)}}₫
                    @endif
                  </div>
                  <div class="div_amount">
                    <div class='quantity-change' >
                      <button class="btn-change btn_dec">
                        <span class="material-symbols-outlined">
                          remove
                        </span>
                      </button>
                      <div style="width: 100%;">
                        <input type="number" name="quantity" class="quantity" min="1" value='{{$products[$i]->amount}}' max='{{$products[$i]->quantity}}'
                          onkeydown="javascript: return ['Backspace','Delete','ArrowLeft','ArrowRight'].includes(event.code) ? true : !isNaN(Number(event.key)) && event.code!=='Space'">
                      </div>
                      <button class="btn-change btn_inc">
                        <span class="material-symbols-outlined">
                          add
                        </span>
                      </button>
                    </div>
                  </div>
                  @if ($products[$i]->discount_value && strtotime($products[$i]->discount_end) >= strtotime(date("Y-m-d")) 
                  && strtotime($products[$i]->discount_start) <= strtotime(date("Y-m-d")))
                    <div class="div_price_all">
                      {{number_format($products[$i]->product_price * (1 - $products[$i]->discount_value/100)* $products[$i]->amount)}}₫
                    </div>
                  @else
                    <div class="div_price_all">{{number_format($products[$i]->product_price * $products[$i]->amount)}}₫</div>
                  @endif
                  <div class="div_action">
                    <a class="p_remove">Xóa</a>
                  </div>
                </div>
              @endfor
            @endif
          </div>
          <div class='item-box payment'>
            <div class="flex">
              <input type="checkbox" class='checkbox check-all'>
              <p class="ml" style="font-size: 17px">Chọn tất cả</p>
            </div>
            <div class="flex">
              @if (isset($pri_product))
                <p style="font-size: 18px" id="s_amount">Tổng thanh toán ({{$pri_product->amount}} Sản phẩm):</p>
                @if ($pri_product->discount_value && strtotime($pri_product->discount_end) >= strtotime(date("Y-m-d")) 
                && strtotime($pri_product->discount_start) <= strtotime(date("Y-m-d")))
                  <p id="s_price">
                    {{number_format($pri_product->product_price* (1 - $pri_product->discount_value/100) * $pri_product->amount)}}₫
                  </p>
                @else
                  <p id="s_price">{{number_format($pri_product->product_price * $pri_product->amount)}}₫</p>
                @endif
              @else
                <p style="font-size: 18px" id="s_amount">Tổng thanh toán (0 Sản phẩm):</p>
                <p id="s_price">0₫</p>
              @endif
              <button id='btn_payment'>Mua hàng</button>
              <form action="/payment" method="post" style="display: none">
                @csrf
                <input type="hidden" id="choice" name='choice'>
                <button id="submit"></button>
              </form>
            </div>
          </div>
        @else
          <div style="text-align: center;margin-top: 30px">
            <span class="material-symbols-outlined cart-ico" >
              shopping_bag
            </span>
            <p style="text-align: center">Giỏ hàng của bạn đang trống</p>
            <a href="/">
              <button class="btn-home">MUA NGAY</button>
            </a>
          </div>
        @endif
      </div>
      <div class="space"></div>
    </div>
    <div class='alert-screen'>
      <div class='alert-overlay'>
      </div>
      <div class='alert-box shadow'>
          <p style='font-size: 18px' id='msg'></p>
          <button id="btn_confirm"> OK</button>
      </div>
  </div>
  </main>
  @include('module.footer')
  <!-- Bootstrap JavaScript Libraries -->
  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"
    integrity="sha384-oBqDVmMz9ATKxIep9tiCxS/Z9fNfEXiDAYTujMAeBAsjFuCZSmKbSSUnQlmh/jp3" crossorigin="anonymous">
  </script>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/js/bootstrap.min.js"
    integrity="sha384-7VPbUDkoPSGFnVtYi0QogXtr74QeVeeIs99Qfg5YCF+TidwNdjvaKZX19NZ/e6oz" crossorigin="anonymous">
  </script>
</body>

</html>