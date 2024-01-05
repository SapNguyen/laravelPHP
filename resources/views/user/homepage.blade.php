{{
  session(['prePage' => '/'])
}}
<!doctype html>
<html lang="en">

<head>
  <title>Title</title>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
  <!-- Bootstrap CSS v5.2.1 -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-iYQeCzEYFbKjA/T2uDLTpkwGzCiq6soy8tYaI1GyVh/UjpbCx/TYkiZhlZB6+fzT" crossorigin="anonymous">
    
    <link rel="stylesheet" href="{{asset('css/module/header.css')}}">
    <link rel="stylesheet" href="{{asset('css/module/footer.css')}}">
    <link rel="stylesheet" href="{{asset('css/module/product-card.css')}}">
    <link rel="stylesheet" href="{{asset('css/user/homepage.css')}}">
    <link rel="stylesheet" href="{{asset('css/owl.carousel.min.css')}}">
    <link rel="stylesheet" href="{{asset('css/owl.theme.default.min.css')}}">
    
    <script src="{{asset('js/jquery.min.js')}}"></script>
    <script src="{{asset('js/owl.carousel.min.js')}}"></script>
    
</head>

<body style="background: #efefef">
  @include('module.header')
  <main>
    <div id="slide" class="carousel slide" data-bs-ride="carousel">
      <!-- Indicators/dots -->
      <div class="carousel-indicators">
        <button type="button" data-bs-target="#slide" data-bs-slide-to="0" class="active"></button>
        @if (count($brands) > 1)
          @for ($i = 1; $i < count($brands); $i++)
            <button type="button" data-bs-target="#slide" data-bs-slide-to="{{$i}}"></button>
          @endfor
        @endif
      </div>
    
      <!-- The slideshow/carousel -->
      <div class="carousel-inner" href="/brand/{{$brands[0]->brand_name}}">
        <a class="carousel-item active">
          <img src="/img/brand/{{$brands[0]->brand_id}}/{{$brands[0]->brand_des_img}}" class="d-block w-100">
        </a>
        @if (count($brands) > 1)
          @for ($i = 1; $i < count($brands); $i++)
            <a class="carousel-item" href="/brand/{{$brands[$i]->brand_name}}">
              <img src="/img/brand/{{$brands[$i]->brand_id}}/{{$brands[$i]->brand_des_img}}" class="d-block w-100">
            </a>
          @endfor
        @endif
      </div>
    
      <!-- Left and right controls/icons -->
      <button class="carousel-control-prev" type="button" data-bs-target="#slide" data-bs-slide="prev">
        <span class="carousel-control-prev-icon"></span>
      </button>
      <button class="carousel-control-next" type="button" data-bs-target="#slide" data-bs-slide="next">
        <span class="carousel-control-next-icon"></span>
      </button>
    </div>
    <div class="container-fluid " align="center" style="padding: 15px 10%;background: white">
      <div class="owl-carousel owl-theme owl-loaded" id="logo" >
        <div class="owl-stage-outer">
          <div class="owl-stage">
            @foreach ($brands as $brand)
              <div class="owl-item">
                <div class="item">
                  <a href="/brand/{{$brand->brand_name}}">
                    <img src="/img/brand/{{$brand->brand_id}}/{{$brand->brand_logo}}" class="logo">
                  </a>
                </div>
              </div>
            @endforeach
          </div>
        </div>
        <div class="owl-nav" id="category" style="margin:0">
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
    </div>
    <div class="container-fluid " align="center" style="padding-left:0;">
      <div class="row ">
        @for ($i = 0; $i < 2; $i++)
            @if (isset($brands[$i]))
              <div class="col-sm-6" style="padding-right:0">
                <a href="/brand/{{$brands[$i]->brand_name}}">
                  <div class="category">
                    <img src="/img/brand/{{$brands[$i]->brand_id}}/{{$brands[$i]->brand_img}}" class="img-fluid" >
                    <div class="caption" align="center">
                      <h2 style="font-weight: bold">{{strtoupper($brands[$i]->brand_name)}}</h2>
                      <p class="total-amount">{{count($brandHome[$i])}} sản phẩm</p>
                    </div>
                  </div>
                </a>
              </div>
            @endif
        @endfor
      </div>
      {{-- <div class="row ">
        
        <div class="col-sm-6" style="padding-right:0">
          <a href="/brand/VANS">
            <div class="category">
              <img src="https://theme.hstatic.net/200000265619/1000946504/14/banner_project_2.jpg?v=417" class="img-fluid">
              <div class="caption" align="center">
                <h2 style="font-weight: bold">VANS</h2>
                <p class="total-amount">{{count($brandHome[1])}} sản phẩm</p>
              </div>
            </div>
          </a>
        </div>
      </div> --}}
      <div class="row mt-3">
        @for ($i = 2; $i < 5; $i++)
          @if (isset($brands[$i]))
            <div class="col-sm-4" style="padding-right:0;">
              <a href="/brand/{{$brands[$i]->brand_name}}">
                <div class="category">
                  <img src="/img/brand/{{$brands[$i]->brand_id}}/{{$brands[$i]->brand_img}}" class="img-fluid" style="height:350px;width: 100%" >
                  <div class="caption" align="center">
                    <h2 style="font-weight: bold">{{strtoupper($brands[$i]->brand_name)}}</h2>
                    <p class="total-amount">{{count($brandHome[$i])}} sản phẩm</p>
                  </div>
                </div>
              </a>
            </div>
          @endif
        @endfor
        {{-- <div class="col-sm-4" style="padding-right:0">
          <a href="/brand/NIKE">
            <div class="category">
              <img src="https://fastsole.co.uk/wp-content/uploads/2021/05/Nike-Air-Max-Pre-Day-Summit-White-DA4263-100-on-foot-01.jpg" 
              class="img-fluid" style="height: 100%">
              <div class="caption" align="center">
                <h2 style="font-weight: bold">NIKE</h2>
                <p class="total-amount">{{count($brandHome[3])}} sản phẩm</p>
              </div>
            </div>
          </a>
        </div>
        <div class="col-sm-4" style="padding-right:0">
          <a href="/brand/BALENCIAGA">
            <div class="category">
              <img src="https://cdn3.dhht.vn/wp-content/uploads/2021/10/5-15-cac-hang-giay-noi-tieng-ban-chay-nhat-tren-the-gioi.jpg" 
              class="img-fluid" style="height: 100%">
              <div class="caption" align="center">
                <h2 style="font-weight: bold">BALENCIAGA</h2>
                <p class="total-amount">{{count($brandHome[4])}} sản phẩm</p>
              </div>
            </div>
          </a>
        </div> --}}
      </div>
    </div>
    @if(count($products_hot) > 0)
      <div class="product-list shadow" align="center" >
        <p class="title">SẢN PHẨM HOT</p>
        <div class="container-fluid mt-3" align="center" style="position: relative">
          <div class="owl-carousel owl-theme owl-loaded" id="hot-sale">
            <div class="owl-stage-outer">
              <div class="owl-stage">
                  @foreach ($products_hot as $product)
                    @include('module.product-card')
                  @endforeach
              </div>
            </div>
          </div>
        </div>
      </div>
    @endif
    @for ($i = 0; $i < 2; $i++)
      <div class="product-list shadow" style="padding-top:0;padding-right: 12px">
        <div style="display: flex;align-items: flex-start">
          <img src="/img/brand/{{$brands[$i]->brand_id}}/{{$brands[$i]->brand_img}}" class="img-fluid" style="margin-bottom: 20px; max-width: 800px; max-height: 400px">
          <div style="padding: 40px 70px 50px 50px">
            <div class='title' style="padding-bottom: 25px">{{$brands[$i]->brand_name}}</div>
            <p>{{$brands[$i]->brand_des}}</p>
          </div>
        </div>
        <div class="container-fluid mt-3" align="center" style="position: relative">
          <a href="/brand/{{$brands[$i]->brand_name}}">
            <div class='title view-all' style="right: 45px;">
              XEM TẤT CẢ
              <span class="material-symbols-outlined ico-view" >
                chevron_right
              </span>
            </div>
          </a>
          <div class="owl-carousel owl-theme owl-loaded product">
            <div class="owl-stage-outer">
              <div class="owl-stage">
                @foreach ($products as $product)
                  @if ($product->brand_name == $brands[$i]->brand_name)
                    @include('module.product-card')
                  @endif
                @endforeach   
              </div>
            </div>
          </div>
        </div>
      </div>
    @endfor
    {{-- <div class="product-list shadow" style="padding-top:0;padding-right: 12px">
      <div class="row">
        <div class="col-sm-7" style="padding: 40px 70px 50px 50px">
          <div class='title' style="padding-bottom: 25px">ADIDAS</div>
          <p>Adidas là một trong những thương hiệu phân phối giày thể thao và các phụ kiện thể thao lớn nhất trên thế giới. 
            Kiểu dáng thiết kế của giày thể thao Adidas luôn đem lại cảm giác trẻ trung và được hầu hết các bạn trẻ chọn là dòng 
            giày đi chơi, tập thể thao. Hơn thế nữa so về giá thì Adidas có nhiều phân khúc khác nhau mà vẫn yêu cầu khắt khe về 
            thẩm mỹ cũng như hỗ trợ bàn chân tốt nhất.
          </p>
          <h6 style="margin-top:8px">Giày Adidas Originals</h6>
          <p>
            Bao gồm các mẫu giày Adidas, được các Sneakerhead ưa chuộng bao gồm những cái tên quen thuộc: NMD, EQT, Stan Smith, Super 
            Star. Gắn liền với nét cổ điển 3 sọc huyền thoại bên thân, chất liệu da bền bỉ, cùng sự tối giản trong thiết kế, chỉ như 
            vậy thôi cũng đã đủ đem lại sức hút tuyệt vời.
          </p>
          <h6 style="margin-top:8px">Giày Adidas Performance</h6>
          <p>
            Là dòng giày hỗ trợ cho người chuyên tập thể thao, vận động viên chuyên nghiệp, vì vậy thiết kế của Giày Adidas Performance
             được áp dụng nhiều công nghệ tối ưu và tiên tiến. Với hai công nghệ Pure Boost và Ultra Boost được Adidas giới thiệu 
             chính thức từ năm 2013 đã đánh dấu bước ngoặt công nghệ trong chất liệu đế giày thể thao (midsole) của Adidas, so với chất
              liệu truyền thống quen thuộc là EVA và TPU. Ngoài công nghệ Boost, Bounce thì Adidas còn liên tục cải tiến các mẫu giày 
              dựa theo công nghệ miCoach, công nghệ theo dõi và đánh giá quá trình luyện tập của vận động viên trong từng trường hợp.
          </p>
        </div>
        <div class="col-sm-5">
          <img src="https://bizweb.dktcdn.net/100/413/756/files/giay-adidas-nu-trang-11.jpg?v=1614313800746" class="img-fluid" style="height: auto;margin-bottom: 20px">
        </div>
      </div>
      <div class="container-fluid mt-3" align="center" style="position: relative">
        <a href="/brand/ADIDAS">
          <div class='title view-all' style="right: 45px;">
            XEM TẤT CẢ
            <span class="material-symbols-outlined ico-view" >
              chevron_right
            </span>
          </div>
        </a>
        <div class="owl-carousel owl-theme owl-loaded product">
          <div class="owl-stage-outer">
            <div class="owl-stage">
              @foreach ($products as $product)
                @if ($product->brand_name == 'ADIDAS')
                  @include('module.product-card')
                @endif
              @endforeach 
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="product-list shadow" style="padding-top:0;padding-right: 12px">
      <div class="row">
        <div class="col-sm-5">
          <img src="https://topbrands.vn/wp-content/uploads/2021/01/Shop-giay-nike-chinh-hang-tphcm.jpg" class="img-fluid" style="height: auto;margin-bottom: 20px">
        </div>
        <div class="col-sm-7" style="padding: 40px 70px 50px 50px">
          <div class='title' style="padding-bottom: 25px">NIKE</div>
          <p>Thương hiệu của Nike là dấu Swoosh được thiết kế bởi Carolyn Davidson cùng với câu slogan kinh điển “Just Do It” hãy 
            làm điều bạn muốn đã trở thành thương hiệu giày dễ nhận biết nhất mọi thời đại.Nike Luôn luôn áp dụng công nghệ mới tiên 
            tiến lên sản phẩm đem đến sự trải nghiệm tuyệt vời cho người dùng. Một số sản phẩm được ưa chuộng như: Nike Air Force 1, 
            Nike Air Jordan, Air Max … Nike sở hữu hệ thống cửa hàng rộng rãi ở mọi quốc gia, không chỉ ở thị trường Châu u. Nike dần
             chinh phục giới trẻ Châu Á đặc biệt là ở Việt Nam bằng sức hút “Just Do It” của mình
          </p>
        </div>
      </div>
      <div class="container-fluid mt-3" align="center" style="position: relative">
        <a href="/brand/NIKE">
          <div class='title view-all' style="right: 45px;">
            XEM TẤT CẢ
            <span class="material-symbols-outlined ico-view" >
              chevron_right
            </span>
          </div>
        </a>
        
        <div class="owl-carousel owl-theme owl-loaded product">
          <div class="owl-stage-outer">
            <div class="owl-stage">
              @foreach ($products as $product)
                @if ($product->brand_name == 'NIKE')
                  @include('module.product-card')
                @endif
              @endforeach 
            </div>
          </div>
        </div>
      </div>
    </div> --}}
  </main>
  @include('module.footer')
  
  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"
    integrity="sha384-oBqDVmMz9ATKxIep9tiCxS/Z9fNfEXiDAYTujMAeBAsjFuCZSmKbSSUnQlmh/jp3" crossorigin="anonymous">
  </script>
  <script src="{{asset('js/user/homepage.js')}}"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/js/bootstrap.min.js"
    integrity="sha384-7VPbUDkoPSGFnVtYi0QogXtr74QeVeeIs99Qfg5YCF+TidwNdjvaKZX19NZ/e6oz" crossorigin="anonymous">
  </script>
  <script src="{{asset('js/user/homepage.js')}}"></script>


</body>

</html>