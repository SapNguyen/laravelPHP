<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
  <title>{{ $title }}</title>
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="/plugins/fontawesome-free/css/all.min.css">
  <!-- overlayScrollbars -->
  <link rel="stylesheet" href="/plugins/overlayScrollbars/css/OverlayScrollbars.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="/dist/css/adminlte.min.css">

  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <style>
    li{
    list-style-type: none;
    }
    p{
      margin: 0px;
    }
    a{
      text-decoration: none;
    }
    main{
      width: 100%;
      display: flex !important;
      justify-content: space-evenly;
      min-height: 700px;
    }
    img{
      height: 80px;
      width: 80px;
    }
    
    .receipt_detail{
      width: 60%;
    }
    .receipt_total{
      border-top: 1px solid;
      padding-top: 8px;
      padding-right: 8%;
    }
    .receipt_member{
      width: 30%;
      height: 60px;
    }
    .member_head{
      border-bottom: 1px solid ;
    }
    .member-name-name,.member-email-email,.member-sdt-sdt,.member-address-address{
      font-size: 20px;
      padding-top: 20px;
    }
    .btn-confirm-cancel{
      display: flex;
      padding-top: 80px;
      justify-content: space-evenly;
    }
    .btn-cancel-primary{
      background-color: red !important;
      border: 1px solid red !important;
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
        <aside class="main-sidebar sidebar-dark-danger elevation-4">
          <!-- Brand Logo -->
          <a href="{{ route('admin.page') }}" class="brand-link">
            <span class="brand-text font-weight-light">Group 7 - 65PM2<br>Onine Shoes Store</span>
          </a>
      
          <!-- Sidebar -->
          <div class="sidebar">
            <!-- Sidebar user (optional) -->
            <div class="user-panel mt-3 pb-3 mb-3 d-flex">
              <div class="image">
                <img src="/dist/img/Admin.png" class="img-circle elevation-2" alt="Admin Icon">
              </div>
              <div class="info">
                <h5 class="d-block" style="color: white;">Admin</h5>
              </div>
            </div>
      
            <!-- Sidebar Menu -->
            <nav class="mt-2">
              <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                <!-- Add icons to the links using the .nav-icon class
                     with font-awesome or any other icon font library -->
      
                  <!-- Home -->
                  <li class="nav-item">
                      <a href="{{ route('admin.page') }}" class="nav-link">
                          <i class="fa-solid fa-house nav-icon" style="color: #ffffff;"></i>
                          <p>
                              Home
                          </p>
                      </a>
                  </li>
      
                  <!-- Brand -->
                  <li class="nav-item">
                      <a href="#" class="nav-link">
                          <i class="fa-solid fa-tags nav-icon" style="color: #ffffff;"></i>
                          <p>
                              Brands
                              <i class="right fas fa-angle-left"></i>
                          </p>
                      </a>
                      <ul class="nav nav-treeview">
                          <li class="nav-item">
                              <a href="{{ route('a.b.list') }}" class="nav-link">
                                  <i class="far fa-circle nav-icon"></i>
                                  <p>List</p>
                              </a>
                          </li>
                          <li class="nav-item">
                              <a href="{{ route('a.b.add.red') }}" class="nav-link">
                                  <i class="far fa-circle nav-icon"></i>
                                  <p>Add</p>
                              </a>
                          </li>
                          <li class="nav-item">
                              <a href="{{ route('a.b.del.red') }}" class="nav-link">
                                  <i class="far fa-circle nav-icon"></i>
                                  <p>Delete</p>
                              </a>
                          </li>
                      </ul>
                  </li>
      
                  <!-- Product -->
                  <li class="nav-item ">
                      <a href="#" class="nav-link active">
                      <i class="fas fa-boxes-stacked nav-icon" style="color: #ffffff;"></i>
                          <p>
                              Products
                              <i class="right fas fa-angle-left"></i>
                          </p>
                      </a>
                      <ul class="nav nav-treeview">
                      <li class="nav-item">
                          <a href="{{ route('a.p.list') }}" class="nav-link">
                              <i class="far fa-circle nav-icon"></i>
                              <p>List</p>
                          </a>
                      </li>
                      <li class="nav-item">
                          <a href="{{ route('a.p.add.red') }}" class="nav-link">
                              <i class="far fa-circle nav-icon"></i>
                              <p>Add</p>
                          </a>
                      </li>
                      <li class="nav-item">
                              <a href="{{ route('a.p.del.red') }}" class="nav-link">
                              <i class="far fa-circle nav-icon"></i>
                              <p>Delete</p>
                          </a>
                      </li>
                      </ul>
                  </li>
      
                  <!-- Discount -->
                  <li class="nav-item">
                      <a href="#" class="nav-link">
                          <i class="fa-solid fa-percent nav-icon" style="color: #ffffff;"></i>
                          <p>
                              Discounts
                              <i class="right fas fa-angle-left"></i>
                          </p>
                      </a>
                      <ul class="nav nav-treeview">
                      <li class="nav-item">
                          <a href="{{ route('a.d.list') }}" class="nav-link">
                          <i class="far fa-circle nav-icon"></i>
                          <p>List</p>
                          </a>
                      </li>
                      <li class="nav-item">
                          <a href="{{ route('a.d.add.red') }}" class="nav-link">
                          <i class="far fa-circle nav-icon"></i>
                          <p>Add</p>
                          </a>
                      </li>
                      <li class="nav-item">
                          <a href="{{ route('a.d.del.red') }}" class="nav-link">
                          <i class="far fa-circle nav-icon"></i>
                          <p>Delete</p>
                          </a>
                      </li>
                      </ul>
                  </li>
      
                  <!-- Receipt -->
                  <li class="nav-item menu-open">
                      <a href="#" class="nav-link">
                      <i class="fa-solid fa-file-invoice-dollar nav-icon" style="color: #ffffff;"></i>
                      <p>
                          Receipts
                          <i class="right fas fa-angle-left"></i>
                      </p>
                      </a>
                      <ul class="nav nav-treeview">
                      <li class="nav-item">
                          <a href="{{ route('a.r.list.0') }}" class="nav-link active">
                          <i class="far fa-circle nav-icon"></i>
                          <p>Unconfirmed</p>
                          </a>
                      </li>
                      <li class="nav-item">
                          <a href="{{ route('a.r.list.1') }}" class="nav-link">
                          <i class="far fa-circle nav-icon"></i>
                          <p>Confirmed</p>
                          </a>
                      </li>
                      <li class="nav-item">
                        <a href="{{ route('a.r.list.2') }}" class="nav-link">
                        <i class="far fa-circle nav-icon"></i>
                        <p>Canceled</p>
                        </a>
                    </li>
                      </ul>
                  </li>
      
                  <!-- Revenue -->
                  <li class="nav-item">
                      <a href="{{ route('a.revenue') }}" class="nav-link">
                          <i class="fa-sharp fa-solid fa-chart-simple nav-icon" style="color: #ffffff;"></i>
                          <p>
                              Revenue
                          </p>
                      </a>
                  </li>
      
                  <!-- Account -->
                  <li class="nav-item">
                      <a href="#" class="nav-link">
                          <i class="fa-solid fa-users nav-icon" style="color: #ffffff;"></i>
                          <p>
                              Accounts
                              <i class="right fas fa-angle-left"></i>
                          </p>
                      </a>
                      <ul class="nav nav-treeview">
                      <li class="nav-item">
                          <a href="{{ route('a.a.list') }}" class="nav-link">
                          <i class="far fa-circle nav-icon"></i>
                          <p>Admin</p>
                          </a>
                      </li>
                      <li class="nav-item">
                          <a href="{{ route('a.s.list') }}" class="nav-link">
                          <i class="far fa-circle nav-icon"></i>
                          <p>Staff</p>
                          </a>
                      </li>
                      <li class="nav-item">
                          <a href="{{ route('a.m.list') }}" class="nav-link">
                          <i class="far fa-circle nav-icon"></i>
                          <p>Member</p>
                          </a>
                      </li>
                      </ul>
                  </li>
      
              </ul>
            </nav>
            <!-- /.sidebar-menu -->
          </div>
          <!-- /.sidebar -->
        </aside>
      
        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
          <!-- Content Header (Page header) -->
          
          <section class="content-header">
            <div class="container-fluid">
              <div class="row mb-2">
                <div class="col-sm-6">
                  <h1>Detail Receipt</h1>
                </div>
                <div class="col-sm-6">
                  <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item">Receipts</li>
                    <li class="breadcrumb-item"><a href="{{ route('a.r.list.0') }}">List</a></li>
                    <li class="breadcrumb-item">Detail</li>
                  </ol>
                </div>
              </div>
            </div><!-- /.container-fluid -->
          </section>
      
    <main>
      <div class="receipt_detail">
      <h1 align=center>Detail biên lai</h1>
      @include('module.error')
      <table class="table table-striped">
        <thead>
          <tr>
            <th>Ảnh</th>
            <th>Tên</th>
            <th>Giá bán</th>
            <th>Số lượng</th>
            <th>Màu</th>
            <th>Size</th>
            <th>Thành tiền</th>
        </tr>
        </thead>
        <tbody>
            @foreach($receipt_products as $rp)
                <tr>
                    <td valign="middle" ><img src="/img/product/{{$rp->product_id}}/{{explode(',',$rp->product_image)[0]}}" class="img"></td>
                    {{-- <td valign="middle"><img src="/img/18/img-01.jpg"></td> --}}
                    <td valign="middle" style="vertical-align: middle;">{{$rp->product_name}}</td>
                    <td valign="middle" style="vertical-align: middle;">{{number_format($rp->sell_price)}}</td>
                    <td valign="middle" style="vertical-align: middle;text-align:center">{{$rp->quantity}}</td>
                    <td valign="middle" style="vertical-align: middle;text-align:center">{{$rp->color}}</td>
                    <td valign="middle" style="vertical-align: middle;text-align:center">{{$rp->size}}</td>
                    <td valign="middle" style="vertical-align: middle;">{{number_format($rp->sell_price * $rp->quantity)}}</td>
                    {{-- <td valign="middle">{{$receipt_product->receipt_value}}</td> --}}
                    
                    {{-- <td>
                      <input type="radio" name=rddmtrangthai value=1 @if ($receipt_product->receipt_status == 1)  checked  @endif>Xác nhận 
                      <input type="radio" name=rddmtrangthai value=0 @if ($receipt_product->receipt_status == 0) checked @endif>Chưa xác nhận 
                    </td> --}}
                    
                </tr>
            @endforeach
            <tr></tr>
        </tbody>
        </table>
        <h3 align=right class="receipt_total">Tổng cộng: {{number_format($receipt_product->receipt_value)}}</h3>
            <div class="btn-confirm-cancel">
            <form action="/admin/edit/{{$receipt_product->receipt_id}}" method="post">             
              @csrf
              <div class="btn-confirm">
                <button type="submit" class="btn btn-primary"  onclick="return confirm('Bạn có chắc đã xác nhận rồi không?');">Xác nhận biên lai</button>
              </div>
            </form>
            <form action="/admin/cancel_edit/{{$receipt_product->receipt_id}}" method="post">
              @csrf
              <div class="btn-cancel">
                <button type="submit" class="btn btn-primary btn-cancel-primary"  onclick="return confirm('Bạn có chắc hủy biên lai này không?');">Hủy biên lai</button>
              </div>
            </form>
          </div>
          
        </div>
        <div class="receipt_member">
          <div class="member_head">
            <h2 align=center>Khách hàng</h2>
          </div>
          <div class="member_detail">
            <div class="member-name">
              <div class="member-name-name">Tên: </div>
              <p>{{$receipt_product->receiver_name}}</p>
            </div>
            <div class="member-email">
              <div class="member-email-email">Email: </div>
              <p>{{$receipt_product->username}}</p>
            </div>
            <div class="member-sdt">
              <div class="member-sdt-sdt">Số điện thoại: </div>
              <p>{{$receipt_product->receiver_phone}}</p>
            </div>
            <div class="member-address">
              <div class="member-address-address">Địa chỉ: </div>
              <p>{{$receipt_product->receiver_address}}</p>
            </div>
          </div>
        </div>
    </main>
    <script src="/plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- overlayScrollbars -->
<script src="/plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js"></script>
<!-- AdminLTE App -->
<script src="/dist/js/adminlte.min.js"></script>

  
  <script src="/theme/dist/js/adminlte.min.js"></script>
</body>
</html>