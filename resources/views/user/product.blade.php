<!doctype html>
<html lang="en">

<head>
  
  <title>Title</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-iYQeCzEYFbKjA/T2uDLTpkwGzCiq6soy8tYaI1GyVh/UjpbCx/TYkiZhlZB6+fzT" crossorigin="anonymous">
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@48,400,0,0" />
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@48,400,1,0" />
  <link rel="stylesheet" href="{{asset('css/owl.carousel.min.css')}}">
  <link rel="stylesheet" href="{{asset('css/owl.theme.default.min.css')}}">

  <script src="{{asset('js/jquery.min.js')}}"></script>
  <script src="{{asset('js/owl.carousel.min.js')}}"></script>

  <link rel="stylesheet" href="{{asset('css/user/product.css')}}">
  <link rel="stylesheet" href="{{asset('css/module/header.css')}}">
  <link rel="stylesheet" href="{{asset('css/module/footer.css')}}">
  <script src="{{asset('js/user/product.js')}}"></script>
</head>
<body>
  @if (isset($product_deleted))
      <div id="alert-deleted" style="display: none">{{session('prePage')}}</div>
  @else
    @include('module.header')
    <main style="margin-top: 25px;padding-left: 10%">

      {{-- Image --}}
      {{-- <form id="image_upload" action="/updateImg/{{$product->product_id}}">
        @csrf
        <input type="file" name="img[]" class="input" id="img" @required(true)  accept="image/gif, image/jpeg, image/png" multiple>
        <label for="img">
          <div class="btn btn-primary">Choose Image</div>
        </label>
        <button class="btn btn-dark" type="submit">Submit</button>
      </form>
      <div id="preview" class="mt-3" style="background: #ccc; display:flex">
        <ul class="preview">
          <img src="{{asset('img/01/img-01.jpg')}}" class="img-fluid">
          <div class="delBtn">
            <span class="material-symbols-outlined">
              close
            </span>
          </div>
        </ul>
      </div>
      <script>
        function deleteImg(e) { 
          var data = $(e).siblings('img').attr('src').split("temp/");
          
          // alert(data[1]);
          $.ajaxSetup({
            headers: {
              'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
            }
          });
          $.ajax({
            url: "/delTempImg",
            type: "get",
            data: {nameImg: data[1]},
            success: function (response) {
              // alert(response.path);
              $(e).parent().attr("class","deleted");
              $("ul").remove(".deleted");
            },
            error:function (response) {
              alert("error");
            }
          });
        }
      </script> --}}
      {{-- <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="/">Home</a></li>
          <li class="breadcrumb-item"><a href="/brand/{{$product->Brand_name}}">{{ucwords(strtolower($product->Brand_name))}}</a></li>
          <li class="breadcrumb-item active" aria-current="page" style="color: #d0021b !important">Giày Converse Chuck Taylor All Star Classic Hi Top</li>
        </ol>
      </nav> --}}
    <div class="container-fluid mt-3" style="margin-top:40px !important; padding-left: 0px !important ">
      <p style="display: none" id="pid">{{$product->product_id}}</p>
      <div class="row">
        <div class="col-sm-8" style="background:white;">
          <div class="row">
            <div class="col-sm-6">
              <img src="/img/product/{{$product->product_id}}/{{explode(',',$product_imgs[0]->product_image)[0]}}" class="large-img">
            </div>
            <div class="col-sm-6">
              <div class="owl-carousel owl-theme owl-loaded" style="width:90%; position: relative;">
                <div class="owl-stage-outer" style="height: 85px;" >
                  {{-- <div class="owl-stage">
                    <div class="owl-item">
                      <div class="item">
                        <img src="{{asset('img/01/img-01.jpg')}}" class="small-img">
                      </div>
                    </div>
                    <div class="owl-item">
                      <div class="item">
                        <img src="{{asset('img/01/img-02.jpg')}}" class="small-img">
                      </div>
                    </div>
                    <div class="owl-item">
                      <div class="item">
                        <img src="{{asset('img/01/img-03.jpg')}}" class="small-img">
                      </div>
                    </div>
                    <div class="owl-item">
                      <div class="item">
                        <img src="{{asset('img/01/img-04.jpg')}}" class="small-img">
                      </div>
                    </div>
                    <div class="owl-item">
                      <div class="item">
                        <img src="{{asset('img/01/img-05.jpg')}}" class="small-img">
                      </div>
                    </div>
                  </div> --}}
                  <div class="owl-stage">
                    @foreach ($product_imgs as $product_img)
                      @foreach (explode(',',$product_img->product_image) as $image)
                        <div class="owl-item">
                          <div class="item">
                              <img src="/img/product/{{$product->product_id}}/{{$image}}" class="small-img">
                          </div>
                        </div>
                      @endforeach
                    @endforeach
                  </div>
                </div>
                <div class="owl-nav">
                  <button type="button" class="btn btn-link owl-prev">
                    <span class="material-symbols-outlined" style="font-size: 40px">
                      chevron_left
                    </span>
                  </button>
                  
                  <button type="button" class="btn btn-link owl-next">
                    <span class="material-symbols-outlined " style="font-size: 40px">
                      chevron_right
                    </span>
                  </button>
                </div>
              </div>
              <div style="margin-top: 30px">
                <h4>
                  {{ $product->product_name}}
                </h4>
                <div class="status-line">
                  <span class="mgr-5"> Thương hiệu: </span>
                  <span class="mgr-10" style="color: #d0021b">{{ucwords(strtolower($product->brand_name))}}</span>
                  
                </div>
                @if (isset($feedback))
                  <div class="status-line" style="margin: 10px 0">
                    @if (isset($total_feedback))
                      <div class="flex" style="border-right:1px solid rgb(0, 0, 0)">
                        <span style="color: #747474">Đánh Giá:</span>
                        <span style="font-size: 18px;margin-left:10px;margin-right:20px">{{ $total_feedback}} </span>
                      </div>
                    @endif
                    <div class="flex" style="margin-left: 20px">
                      @if (isset($average))
                        <p class="average-star">{{$average}}</p>
                        <div class="flex" style="margin-left: 5px">
                          @for ($i = 0; $i < $star_full; $i++)
                            <div style="position: relative;height:24px">
                              <span class="material-symbols-outlined star">
                                star
                              </span>
                              <span class="material-symbols-outlined star-fill" style="width: 100%">
                                star
                              </span>
                            </div>
                          @endfor
                          @if ($star_fill > 0)
                          <div style="position: relative;height:24px">
                            <span class="material-symbols-outlined star">
                              star
                            </span>
                            <span class="material-symbols-outlined star-fill" style="width: {{$star_fill*100}}%">
                              star
                            </span>
                          </div>
                          @endif
                          @for ($i = 0; $i < $star_empty; $i++)
                            <div style="position: relative;height:24px">
                              <span class="material-symbols-outlined star">
                                star
                              </span>
                              <span class="material-symbols-outlined star-fill" style="width: 0px">
                                star
                              </span>
                            </div>
                          @endfor
                        </div>
                        @endif
                    </div>
                  </div>
                @else
                  <div class="status-line" style="margin: 10px 0">
                    <div class="flex" style="border-right:1px solid rgb(0, 0, 0)">
                      <span style="color: #747474">Đánh Giá:</span>
                      <span style="font-size: 18px;margin-left:10px;margin-right:20px"> 0</span>
                    </div>
                    <div class="flex" style="margin-left: 20px">
                        <p class="average-star"> 0 </p>
                        @for ($i = 0; $i < 5; $i++)
                        <div class="flex" style="margin-left: 5px">
                            <div style="position: relative;height:24px">
                              <span class="material-symbols-outlined star">
                                star
                              </span>
                              <span class="material-symbols-outlined star-fill" style="width: 0px">
                                star
                              </span>
                            </div>
                        </div>
                        @endfor
                    </div>
                  </div>
                @endif
                @if ($product->discount_id && strtotime($product->discount_end) >= strtotime(date("Y-m-d")) 
                  && strtotime($product->discount_start) <= strtotime(date("Y-m-d")))
                  <div style="display: flex">
                    <h2 class="price">
                      {{ number_format($product->product_price * (1-$product->discount_value/100)) }}₫
                    </h2>
                    <h5 class="price-disabled">
                      {{ number_format($product->product_price)}}₫
                    </h5>
                    <div class="discount-value">
                      -{{$product->discount_value}}%
                    </div>
                  </div>
                @else
                  <h2 class="price">
                    {{ number_format($product->product_price)}}₫
                  </h2>
                @endif
                @if ($product->discount_id && strtotime($product->discount_end) >= strtotime(date("Y-m-d")) 
                && strtotime($product->discount_start) <= strtotime(date("Y-m-d")))
                  <div>
                    <span style="font-size: 18px">KHUYẾN MÃI</span>
                    <div id="promotion">
                      <div class="promotion-item">
                        <div class="promotion-tag">
                          <span class="rectangle">
                            {{strtoupper($product->discount_name)}}
                          </span>  
                        </div>
                        <div class="promotion-info shadow" >
                          GIẢM  {{$product->discount_value}} %
                        </div>
                      </div>
                    </div>
                  </div>
                @endif
              </div>
              <div class="row" id="div_size" style="margin-top:20px">
                <h6 class="col-sm-2" style="margin-top: 8px">Size:</h6>
                @for ($i = 0; $i < count($sizes); $i++)
                  <div class="size-box col-sm-1" >
                    <p class='size-id'>{{$sizes[$i]->size}}</p>
                    @foreach ($quantitys as $quantity)
                      @if ($quantity->size == $sizes[$i]->size)
                        <div class="div_quantity">
                          <p style="display: none" class="amount-hidden">{{$quantity->quantity}}</p>
                          <p style="display: none" class="color-hidden">{{$quantity->color}}</p>
                        </div>
                      @endif
                    @endforeach
                  </div>
                @endfor
              </div>
              <div class="row" id="div_color" style="margin-top:20px;align-items: center">
                <h6 class="col-sm-2" style="margin-top: 10px;padding-top: 5px 0;">Màu:</h6>
                @for ($i = 0; $i < count($colors); $i++)
                  <div class="size-box color-box col-sm-1" style="width: auto !important;margin-top:10px">
                    <p class='color'  style="padding: 0 15px">{{$colors[$i]->color}}</p>
                    <img src="/img/product/{{$colors[$i]->product_id}}/{{explode(',',$colors[$i]->product_image)[0]}}" style="display:none">
                  </div>
                @endfor
              </div>
              <div class="material">
                <h6 style="margin-top: 8px">Chất liệu:</h6>
                <div class="chat-lieu col-sm-">{{ strtoupper($product->product_material)}}</div>
              </div>
              <div class="material" id="setAmount">
                <input type="hidden" name="size" id="size_color" >
                <h6 style="margin-top: 8px">Số lượng:</h6>
                <div class="btn-group">
                  <div class="button">
                    <span class="material-symbols-outlined" style="font-size: 20px">
                      remove
                    </span>
                  </div>
                  <input type="number" id="buy-amount" name="amount" value="1" min="1" required>
                  <div class="button">
                    <span class="material-symbols-outlined" style="font-size: 20px">
                      add
                    </span>
                  </div>
                </div>
                <p id="has-amount"></p>
              </div>
              <div id="div_alert" style="color: red;font-size:14px"></div>
              <div class="material" style="justify-content: center">
                  <button type ='submit' class="buy-btn btn-allow">Mua ngay</button>
              </div>
            </div>
          </div>
          <div>
            <div class="tab row">
              <button class="tablinks col-sm-3 rounded-top active" onclick="openTab(event, 'description')">Mô tả sản phẩm</button>
              <button class="tablinks col-sm-3 rounded-top" onclick="openTab(event, 'size-table')">Bảng Size</button>
              <button class="tablinks col-sm-3 rounded-top" onclick="openTab(event, 'policy')">Chính sách bán hàng</button>
              <button class="tablinks col-sm-3 rounded-top" onclick="openTab(event, 'preserve')">Thông tin bảo quản</button>
            </div>
            <div id="description" class="tabcontent" style="display: block">
              <p>
                {{ $product->product_des}}
              </p>
              </div>
            
            <div id="size-table" class="tabcontent">
              <img src="{{asset('img/size.jpg')}}" alt="" class="img-fluid">
            </div>
            
            <div id="policy" class="tabcontent">
              <h5>Hàng đã mua không đổi trả</h5>
            </div>
    
            <div id="preserve" class="tabcontent">
              <p>Đối Với Giày Vải bạt (canvas):</p>
              <p>1.Sản phẩm chỉ nên giặt bằng tay và tránh việc chà sát mạnh trên bề mặt vải.</p>
              <p>
                2.Đối với hóa chất tẩy rửa hoặc xà phòng có tính kiềm cao đều dễ gây nên tình trạng bung keo, biến dạng hoặc loang màu. 
                Do đó chỉ nên dùng dầu gội, sữa tắm hoặc dung dịch chuyên dụng dành cho sản phẩm.
              </p>
              <p>3.Khuyến cáo không phơi sản phẩm dưới ánh nắng trực tiếp hoặc sấy khô bằng nhiệt độ cao.</p>
              <p>4.Đối với giày trắng, giặt xong sẽ quấn nhiều lớp giấy ăn xung quanh để thấm hút nước và nhân lúc giày còn ẩm 
                rắc bột phấn rôm lên trực tiếp bề mặt vải, sau đó để giày khô tự nhiên.
              </p>
              <p>5.Đối với khách hàng thường xuyên vận động hoặc ra nhiều mồ hôi, nên phun một lớp giấm ăn lên giày trước khi sử dụng.</p>
              <p style="margin-top: 10px">Đối Với Giày Da (leather):</p>
              <p>1.Sản phẩm bằng da, giả da hoặc da lộn khi bị bám bụi bẩn chỉ nên sử dụng khăn lông ẩm để vệ sinh và làm sạch.</p>
              <p>2.Trong quá trình sử dụng, nên hạn chế va chạm vật sắt/ nhọn lên trên bề mặt da; tránh đi dưới 
                trời mưa hoặc khi dính vết trà, cà phê thì phải xử lý ngay để không lưu lại vết ố.</p>
              <p>3.Không tự ý bôi hoặc phun các chất tẩy rửa lên bề mặt da, trừ những dung dịch chuyên dụng dành cho sản phẩm.</p>
              <p>4.Khuyến cáo không phơi sản phẩm dưới ánh nắng trực tiếp hoặc sấy khô bằng nhiệt độ cao.</p>
              <p style="margin-top: 10px"> Lưu ý chung : Đối với sản phẩm không sử dụng thường xuyên thì nên nhét 
                giấy bên trong để giữ được form dáng như ban đầu.
              </p>
            </div>
          </div>
          <div class="feedback-container">
              <p class="title">Đánh giá sản phẩm</p>
              @if (count($feedback) == 0)
                <div class="empty-feedback">
                  <span class="material-symbols-outlined" style="font-size:50px;color:#747474">
                    chat
                  </span>
                  <p style="font-size:20px">Chưa có đánh giá</p>
                </div>
              @else
                <div class="feedback-title">
                  <div class="star-container">
                    <p class="feedback-average">{{$average}} trên 5</p>
                    <div class="flex" style="margin-left: 5px">
                      @for ($i = 0; $i < $star_full; $i++)
                        <div style="position: relative;height:24px">
                          <span class="material-symbols-outlined star">
                            star
                          </span>
                          <span class="material-symbols-outlined star-fill" style="width: 100%">
                            star
                          </span>
                        </div>
                      @endfor
                      @if ($star_fill > 0)
                      <div style="position: relative;height:24px">
                        <span class="material-symbols-outlined star">
                          star
                        </span>
                        <span class="material-symbols-outlined star-fill" style="width: {{$star_fill*100}}%">
                          star
                        </span>
                      </div>
                      @endif
                      @for ($i = 0; $i < $star_empty; $i++)
                        <div style="position: relative;height:24px">
                          <span class="material-symbols-outlined star">
                            star
                          </span>
                          <span class="material-symbols-outlined star-fill" style="width: 0px">
                            star
                          </span>
                        </div>
                      @endfor
                    </div>
                  </div>
                  <div class="type_star-container">
                    <div class="star-box star-active" id="all">
                      Tất cả
                    </div>
                    @for ($i = 5; $i > 0; $i--)
                      <div class="star-box" id="{{$i}}">
                        {{$i}} Sao ({{$count_star[$i]}})
                      </div>
                      <div style="display: none" class="count-feedback">
                        {{$count_star[$i]}}
                      </div>
                    @endfor
                  </div>
                </div>
                <div class="star-feedback-empty">
                  <span class="material-symbols-outlined" style="font-size:50px;color:#747474">
                    chat
                  </span>
                  <p style="font-size:20px">Chưa có đánh giá</p>
                </div>
                @foreach ($feedback as $fb)
                  <div class="feedback-content">
                    <div class='comment-box {{$fb->star}}'>
                      <div class="avata material-symbols-outlined">
                        account_circle
                      </div>
                      <div class="comment-info">
                        <div class="name">
                          {{$fb->name}}
                        </div>
                        <div class="rate">
                          <div class="flex">
                            @for ($i = 0; $i < $fb->star; $i++)
                              <div style="position: relative;height:20px">
                                <span class="material-symbols-outlined star" style="font-size: 20px">
                                  star
                                </span>
                                <span class="material-symbols-outlined star-fill" style="width: 100%;font-size:20px">
                                  star
                                </span>
                              </div>
                            @endfor
                            @for ($i = 0; $i < 5-$fb->star; $i++)
                              <div style="position: relative;height:20px">
                                <span class="material-symbols-outlined star" style="font-size: 20px">
                                  star
                                </span>
                                <span class="material-symbols-outlined star-fill" style="width: 0px;font-size: 20px">
                                  star
                                </span>
                              </div>
                            @endfor
                          </div>
                        </div>
                        {{-- <div class="product-info">
                          Size: 41, Màu: Trắng
                        </div> --}}
                        <div class="content">
                          {{$fb->comment}}
                        </div>
                      </div>
                    </div>
                  </div>
                @endforeach
              @endif
          </div>
        </div>
        <div class="col-sm-4">
          <table class="table table-bordered proc-table">
            <thead>
              <tr>
                <th colspan="2">THÔNG TIN SẢN PHẨM</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td>Barcode</td>
                <td>{{ $product->product_id}}</td>
              </tr>
              <tr>
                <td>Dòng sản phẩm</td>
                <td>Chuck Taylor All Star Classic</td>
              </tr>
              <tr>
                <td>Giới tính</td>
                <td>Unisex</td>
              </tr>
              <tr>
                <td>Chế độ bảo hành</td>
                <td>1 Tháng
                  <p>(Không áp dụng sản phẩm giảm giá)</p>
                </td>
              </tr>
              <tr>
                <td>Phụ kiện kèm theo</td>
                <td>Shopping Bag + HĐ Mua Hàng +
                  Vớ Tặng (Áp dụng 1 số sản phẩm)</td>
              </tr>
            </tbody>
          </table>
          @if (isset($products_similar))
            <div style="margin-top: 80px">
              <p style="font-size: 22px">SẢN PHẨM TƯƠNG TỰ</p>
              <div class="container-fluid mt-3">
                @foreach ($products_similar as $p_similar)
                  <a class="row box-product" href="/products/{{$p_similar->product_id}}">
                    <div class="col-sm-4" style="padding:0">
                      <img src="/img/product/{{$p_similar->product_id}}/{{$p_similar->image}}" class="img-fluid" style="height: 100%">
                    </div>
                    <div class="col-sm-8" >
                      <p class="ps_name">{{$p_similar->product_name}}</p>
                      @if (isset($p_similar->discount_value))
                        <div class="div_ps-price">
                          <p style="font-weight: bolder;color: red">
                            {{number_format($p_similar->product_price * (1-$p_similar->discount_value/100))}}đ
                          </p>
                          <p class="ps-price-disabled">{{number_format($p_similar->product_price)}}đ</p>
                        </div>
                      @else
                        <p style="font-weight: bolder;color: red">{{number_format($p_similar->product_price)}}đ</p>
                      @endif
                      
                    </div>
                  </a>
                @endforeach
              </div>
            </div>
          @endif
        </div>
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
  @endif
</body>

</html>