<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
  <!-- Bootstrap CSS v5.2.1 -->
  <title>{{ $title }}</title>

  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="../../plugins/fontawesome-free/css/all.min.css">
  <!-- overlayScrollbars -->
  <link rel="stylesheet" href="/plugins/overlayScrollbars/css/OverlayScrollbars.min.css">
  <!-- DataTables -->
  <link rel="stylesheet" href="../../plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
  <link rel="stylesheet" href="../../plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
  <link rel="stylesheet" href="../../plugins/datatables-buttons/css/buttons.bootstrap4.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="../../dist/css/adminlte.min.css">

  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
   
    
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
                <h1>List Orders Canceled</h1>
              </div>
              <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                  <li class="breadcrumb-item">orders</li>
                  <li class="breadcrumb-item">List</li>
                </ol>
              </div>
            </div>
          </div><!-- /.container-fluid -->
        </section>

        <section class="content">
        <div class="container-fluid">
          <div class="row">
              <div class="col-12">
                  <div class="card card-info">
                      <div class="card-header">
                          <form method="get" action="{{ route('a.r.list.2') }}" style="width: 50%;">
                              <div class="input-group">
                                  <input type="date" name="searchName" class="form-control form-control-lg" placeholder="Type discount's name here">
                                  <div class="input-group-append">
                                      <button type="submit" class="btn btn-lg btn-default">
                                          <i class="fa fa-search"></i>
                                      </button>
                                  </div>
                              </div>
                          </form>
                          <br>
                        <h3 class="card-title">Nhập ngày đã hủy để tìm kiếm</h3>
                          
                      </div>
                      <div class="card-body">
                          <div id="example2_wrapper" class="dataTables_wrapper dt-bootstrap4">
                              <div class="row"><div class="col-sm-12 col-md-6"></div>
                              <div class="col-sm-12 col-md-6"></div></div><div class="row">
                                  <div class="col-sm-12"> 
    <table class="table table-bordered table-hover">
          <thead>
            <tr>
              <th>#</th>
              <th>Mã đơn hàng</th>
              <th>Ngày tạo</th>
              <th>Ngày xác thực</th>
              <th>Ngày hủy</th>
              <th>Ngày hoàn thành</th>
              <th>Giá trị</th>
              <th>Trạng thái</th>
              <th>Mã khách hàng</th>
              {{-- <th>Địa chỉ khách hàng</th>
              <th>SĐT khách hàng</th>
              <th>Email khách hàng</th> --}}
          </tr>
          </thead>
          <tbody>
              @foreach($orders as $order)
                  <tr>
                      <td>{{ ($orders->currentPage() - 1) * $orders->perPage() + $loop->iteration }}.</td>
                      <td valign="middle">{{$order->order_id}}</td>
                      <td valign="middle">{{$order->created_date}}</td>
                      <td valign="middle">{{$order->validated_date}}</td>
                      <td valign="middle">{{$order->canceled_date}}</td>
                      <td valign="middle">{{$order->completion_date}}</td>
                      <td valign="middle">{{number_format($order->order_value)}}</td>
                      @if($order->order_status == -1)
                      <td valign="middle"><b style="color: red;">Đã bị hủy</b></td>
                                            @endif
                      
                      <td valign="middle">{{$order->mem_id}}</td>
                      <td valign="middle"><a class="btn btn-primary mr-2" href="/admin/detail/{{$order->order_id}}"><i class="fa fa-edit"></i></a></td>
                      
                  </tr>
                  
                  
              @endforeach
              <tr></tr>
              
          </tbody>
      
  </table>
  </div>
  </div>
  <div class="row">
      <div style="width: 100%;">
          <div class="dataTables_paginate paging_simple_numbers" id="paginate">
              <div style="float: left">
                  <span>Showing {{ $orders->firstItem() }} to {{ $orders->lastItem() }} of {{ $count }} orders</span>
              </div>
              <div style="float: right">
                {{ $orders->appends(['searchName'=>$searchName ?? ''])->links()}}
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
<script src="/plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- overlayScrollbars -->
<script src="/plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js"></script>
<!-- AdminLTE App -->
<script src="/dist/js/adminlte.min.js"></script>

</body>
</html>