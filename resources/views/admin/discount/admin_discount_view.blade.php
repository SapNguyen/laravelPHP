<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>{{ $title }}</title>

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="/plugins/fontawesome-free/css/all.min.css">
  <!-- overlayScrollbars -->
  <link rel="stylesheet" href="/plugins/overlayScrollbars/css/OverlayScrollbars.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="/dist/css/adminlte.min.css">

  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

  <style>
    img{
        height: 80px;
        width: 80px;
    }
  </style>
</head>

<body class="hold-transition sidebar-mini layout-fixed">
<!-- Site wrapper -->
<div class="wrapper">
  <!-- Navbar -->
  <nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
      </li>
      <li class="nav-item d-none d-sm-inline-block">
        <a href="{{ route('admin.page') }}" class="nav-link">Home</a>
      </li>
    </ul>

    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">   
      <li class="nav-item">
        <a class="nav-link" data-widget="fullscreen" href="#" role="button">
          <i class="fas fa-expand-arrows-alt"></i>
        </a>
      </li>
    </ul>
  </nav>
  <!-- /.navbar -->

  <!-- Main Sidebar Container -->
  @include('admin.navbar')

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Discount's  detail</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item">Discounts</li>
              <li class="breadcrumb-item"><a href="{{ route('a.d.list') }}">List</a></li>
              <li class="breadcrumb-item">Detail</li>
            </ol>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-6">
            <div class="card card-info">
                <div class="card-header">
                    <form method="get" action="{{ route('a.d.view') }}" style="width: 50%;">
                        <input type="hidden" value="{{$discount_id}}" name="discount_id">
                        <div class="input-group">
                            <input type="search" name="searchName" class="form-control form-control-lg" placeholder="Type product's name here">
                            <div class="input-group-append">
                                <button type="submit" class="btn btn-lg btn-default">
                                    <i class="fa fa-search"></i>
                                </button>
                            </div>
                        </div>
                    </form>
                    <br>
                    
                </div>
                <div class="card-body">
                    <div id="example2_wrapper" class="dataTables_wrapper dt-bootstrap4">
                        <div class="row"><div class="col-sm-12 col-md-6"></div>
                        <div class="col-sm-12 col-md-6"></div></div><div class="row">
                            <div class="col-sm-12"> 
                              <h3>Danh sách sản phẩm</h3>
<table class="table table-bordered table-hover">
<thead>
  <tr>
    <th>Mã sản phẩm </th>
    <th>Ảnh sản phẩm </th>
    <th>Tên sản phẩm </th>
    <th>Chất liệu sản phẩm </th>
    <th>Giá tiền sản phẩm </th>
    <th>Trạng thái sản phẩm </th>
</tr>
</thead>
<tbody>
    @foreach($products as $product)
    <tr data-widget="expandable-table" aria-expanded="false">
        <td valign="middle">{{$product->product_id}}</td>
        <td valign="middle"><img src="/img/product/{{$product->product_id}}/{{explode(',',$product->product_image)[0]}}" class="img"></td>
            <td valign="middle">{{$product->product_name}}</td>
            <td valign="middle">{{$product->product_material}}</td>
            <td valign="middle">{{$product->product_price}}</td>
            @if($product->product_active == 1)
                                        <td valign="middle">Active</td>
                                        @else
                                        <td valign="middle">Inactive</td>
                                        @endif
            <td>
              
              <button type="button" class="btn btn-primary mr-2" onclick="add(this)" data-did="{{$product->product_id}}"  data-diid="{{$discountes}}" ><i class="fa fa-plus"></i></button>
                
            </td>
        
      </tr>
        
        
    @endforeach
    
</tbody>
</table>
</div>
</div>
<div class="row">
<div style="width: 100%;">
    <div class="dataTables_paginate paging_simple_numbers" id="paginate">
        <div style="float: left">
            <span>Showing {{ $products->firstItem() }} to {{ $products->lastItem() }} of {{ $count }} product</span>
        </div>
        <div style="float: right">
            @if (isset($searchName) && isset($searchNamed))
                {{ $products->appends([
                  'get' => $products->currentPage(),
                  'searchName'=>$searchName ?? '',
                  'searchNamed'=>$searchNamed ?? ''
              ])->links() }}
            @else
                {{ $products->appends([
                    'get' => $products->currentPage(),
                    'discount_id'=>$discount_id ?? ''
                ])->links() }}
            @endif
        </div>
    </div>
</div>
</div>
</div>
</div>
<!-- /.card-body -->
</div>
<!-- /.card -->
</div>
<div class="col-6">
  <div class="card card-info">
      <div class="card-header">
          <form method="get" action="{{ route('a.d.view') }}" style="width: 50%;">
            <input type="hidden" value="{{$discount_id}}" name="discount_id">
              <div class="input-group">
                  <input type="search" name="searchNamed" class="form-control form-control-lg" placeholder="Type product's name here">
                  <div class="input-group-append">
                      <button type="submit" class="btn btn-lg btn-default">
                          <i class="fa fa-search"></i>
                      </button>
                  </div>
              </div>
          </form>
          <br>
          
      </div>
      <div class="card-body">
          <div id="example2_wrapper" class="dataTables_wrapper dt-bootstrap4">
              <div class="row"><div class="col-sm-12 col-md-6"></div>
              <div class="col-sm-12 col-md-6"></div></div><div class="row">
                  <div class="col-sm-12"> 
                    <h3>Danh sách sản phẩm đã được thêm mã khuyến mại </h3>
<table class="table table-bordered table-hover">
<thead>
<tr>
<th>Mã sản phẩm </th>
<th>Ảnh sản phẩm </th>
<th>Tên sản phẩm </th>
<th>Chất liệu sản phẩm </th>
<th>Giá tiền sản phẩm </th>
<th>Trạng thái sản phẩm </th>
</tr>
</thead>
<tbody>
@foreach($product_eds as $producted)
<tr data-widget="expandable-table" aria-expanded="false">
    <td valign="middle">{{$producted->product_id}}</td>
    <td valign="middle"><img src="/img/product/{{$producted->product_id}}/{{explode(',',$producted->product_image)[0]}}" class="img"></td>
  <td valign="middle">{{$producted->product_name}}</td>
  <td valign="middle">{{$producted->product_material}}</td>
  <td valign="middle">{{$producted->product_price}}</td>
  @if($producted->product_active == 1)
                              <td valign="middle">Active</td>
                              @else
                              <td valign="middle">Inactive</td>
                              @endif
  <td>
      
      <button type="button" class="btn btn-primary mr-2" onclick="sub(this)" data-did="{{$producted->product_id}}"><i class="fa fa-minus"></i></button>
    
  </td>

</tr>

@endforeach

</tbody>
</table>
</div>
</div>
<div class="row">
<div style="width: 100%;">
<div class="dataTables_paginate paging_simple_numbers" id="paginate">
<div style="float: left">
  <span>Showing {{ $product_eds->firstItem() }} to {{ $product_eds->lastItem() }} of {{ $countd }} product</span>
</div>
<div style="float: right">
    @if (isset($searchName) && isset($searchNamed))
    {{ $product_eds->appends([
        'geted' => $product_eds->currentPage(),
        'searchName'=>$searchName ?? '',
        'searchNamed'=>$searchNamed ?? ''
    ])->links() }}
    @else
        {{ $product_eds->appends([
            'geted' => $product_eds->currentPage(),
            'discount_id'=>$discount_id ?? ''
        ])->links() }}
    @endif
  
</div>
</div>
</div>
</div>
</div>
</div>
<!-- /.card-body -->
</div>
<!-- /.card -->
</div>
<!-- /.col -->
</div>
<!-- /.row -->
</div>



    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->

  <footer class="main-footer">
    <!-- <strong>Copyright &copy; 2023 Group7-65PM2.</strong> All rights reserved. -->
  </footer>

  <!-- Control Sidebar -->
  <aside class="control-sidebar control-sidebar-dark">
    <!-- Control sidebar content goes here -->
  </aside>
  <!-- /.control-sidebar -->
</div>
<!-- ./wrapper -->

<!-- jQuery -->
<script src="/plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- overlayScrollbars -->
<script src="/plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js"></script>
<!-- AdminLTE App -->
<script src="/dist/js/adminlte.min.js"></script>
<script>
  $.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});
function add(button) {
    if(confirm('Bạn có muốn thêm product  này vào khuyến mại này không ?')){
        var did = button.getAttribute("data-did");
        var diid = button.getAttribute("data-diid");
        var formData = new FormData();
        formData.append('did', did);
        formData.append('diid', diid);
        $.ajax({
            type: "POST",
            url: "/addProductDiscount",
            data: formData,
            success:function (response) {
                alert('Product '+ did +' đã được thêm vào');
                window.location.reload();
            },
            error:function(response){
                alert("Lỗi khi thêm product!");
            },
            cache: false,
            contentType: false,
            processData: false,
        });
    }
    else{
        alert('Tại sao bạn không muốn thêm Product này !?')
    }
}
function sub(button) {
    if(confirm('Bạn có muốn xóa product  này ra khỏi khuyến mại này không ?')){
        var did = button.getAttribute("data-did");
        var formData = new FormData();
        formData.append('did', did);
        $.ajax({
            type: "POST",
            url: "/subProductDiscount",
            data: formData,
            success:function (response) {
                alert('Product '+ did +' đã được xóa khỏi');
                window.location.reload();
            },
            error:function(response){
                alert("Lỗi khi xóa product!");
            },
            cache: false,
            contentType: false,
            processData: false,
        });
    }
    else{
        alert('Tại sao bạn không muốn xóa Product này !?')
    }
}
</script>
</body>
</html>
