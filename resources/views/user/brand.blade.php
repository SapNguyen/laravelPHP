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
  <link rel="stylesheet" href="{{asset('css/user/brandstyle.css')}}">
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

<body>
  @include('module.header')
  <main>
    <input type="hidden" id="cur_page" value="{{$cur_page}}">
    @if (isset($name))
      <input type="hidden" id="link" value="/brand/{{$name}}">
    @elseif (isset($search))
      <input type="hidden" id="link" value="/search">
    @else
      <input type="hidden" id="link" value="/promotion">
    @endif
    
    @foreach ($brands as $brand)
        @if (isset($name))
          @if (strtoupper($name) == strtoupper($brand->brand_name))
            <img src="/img/brand/{{$brand->brand_id}}/{{$brand->brand_des_img}}" style="width:100%" alt="Converse">
          @endif
        @endif
    @endforeach
    <div class="container">
    @foreach ($brands as $brand)
        @if (isset($name))
          @if (strtoupper($name) == strtoupper($brand->brand_name))
            <div class="intro-brand">
              <p style="text-align:center;">
              <strong>{{$brand->brand_des}}</strong>
              </p>
            </div>
          @endif
        @endif
    @endforeach
      
      <div class="main-brand" style='margin-top: 50px'>
        <div class="list-product">
          <div class="name-list-product">DANH MỤC SẢN PHẨM</div>
          <div class="list-list-product">
            @foreach ($brands as $brand)
            <div class="name-list-list-product">
              <a href="/brand/{{$brand->brand_name}}" class="name-list-list-product-main">{{strtoupper($brand->brand_name)}}</a>
            </div>
            @endforeach
          </div>
          <div class="name-list-product" style="margin-top: 40px">MỨC GIÁ</div>
          <div class="list-list-product" id="list_cb">
            <div class="name-list-list-product" style="display: flex;align-items: center">
              <input type="checkbox" id="range_1" class="cb_price" {{ strpos($checked_box,'0') != '' ? 'checked' : '' }} >
              <label for="range_1" class="txt_price">500.000đ - 1.000.000đ</label>
            </div>
            <div class="name-list-list-product" style="display: flex;align-items: center">
              <input type="checkbox" id="range_2" class="cb_price" {{ strpos($checked_box,'1') != '' ? 'checked' : '' }}>
              <label for="range_2" class="txt_price">1.000.000đ - 1.500.000đ</label>
            </div>
            <div class="name-list-list-product" style="display: flex;align-items: center">
              <input type="checkbox" id="range_3" class="cb_price" {{ strpos($checked_box,'2') != '' ? 'checked' : '' }}>
              <label for="range_3" class="txt_price">1.500.000đ - 2.000.000đ</label>
            </div>
            <div class="name-list-list-product" style="display: flex;align-items: center">
              <input type="checkbox" id="range_4" class="cb_price" {{ strpos($checked_box,'3') != '' ? 'checked' : '' }}>
              <label for="range_4" class="txt_price">Trên 2.000.000đ</label>
            </div>
          </div>
        </div>
        <div class="main-product">
          <div style="display: flex;align-items: center">
            <div>
              <label for="sort" style="font-weight: 500;font-size: 17px">Ưu tiên xem:</label>
              <select name="sort" id="sort">
                <option value="" {{$sort_type == '' ? 'selected' : ''}}>Sắp xếp theo</option>
                <option value="name_asc" {{$sort_type == 'name_asc' ? 'selected' : ''}}>Từ A-Z</option>
                <option value="name_desc" {{$sort_type == 'name_desc' ? 'selected' : ''}}>Từ Z-A</option>
                <option value="price_asc" {{$sort_type == 'price_asc' ? 'selected' : ''}}>Giá tăng dần</option>
                <option value="price_desc" {{$sort_type == 'price_desc' ? 'selected' : ''}}>Giá giảm dần</option>
              </select>
            </div>
            <div style="margin-left: 50px">
              <label for="display" style="font-weight: 500;font-size: 17px">Hiển thị:</label>
              <select name="display" id="display">
                <option value="16" {{$amount == '16' ? 'selected' : ''}}>Số lượng sản phẩm</option>
                <option value="1" {{$amount == '1' ? 'selected' : ''}}>1 Sản Phẩm</option>
                <option value="10" {{$amount == '10' ? 'selected' : ''}}>10 Sản Phẩm</option>
                <option value="20" {{$amount == '20' ? 'selected' : ''}}>20 Sản Phẩm</option>
                <option value="25" {{$amount == '25' ? 'selected' : ''}}>25 Sản Phẩm</option>
              </select>
            </div>
          </div>
           <div class="owl-carousel owl-theme owl-loaded product">
            <div class="owl-stage-outer">
              <div class="owl-stage">
                @foreach ($products as $product)
                  @include('module.product-card')
                @endforeach   
              </div>
            </div>
           </div>
            {{-- <div class="list-pagination">
              <a class="list-pagination-a" id="prev">&laquo;</a>
              <a class="list-pagination-a" id="1">1</a>
              <a class="list-pagination-a" id="2">2</a>
              <a class="list-pagination-a" id="3">3</a>
              <a class="list-pagination-a" id="4">4</a>
              <a class="list-pagination-a" id="5">5</a>
              <a class="list-pagination-a" id="6">6</a>
              <a class="list-pagination-a" id="next">&raquo;</a>
            </div> --}}
            <div style="margin-top: 20px">
              {{ $products->onEachSide(5)->links() }}
            </div>
            
        </div>
      </div>
    </div>
  </main>
  @include('module.footer')
  <script src="{{asset('js/user/brand.js')}}"></script>
  <!-- Bootstrap JavaScript Libraries -->
  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"
    integrity="sha384-oBqDVmMz9ATKxIep9tiCxS/Z9fNfEXiDAYTujMAeBAsjFuCZSmKbSSUnQlmh/jp3" crossorigin="anonymous">
  </script>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/js/bootstrap.min.js"
    integrity="sha384-7VPbUDkoPSGFnVtYi0QogXtr74QeVeeIs99Qfg5YCF+TidwNdjvaKZX19NZ/e6oz" crossorigin="anonymous">
  </script>
</body>

</html>