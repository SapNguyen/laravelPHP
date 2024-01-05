<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>{{ $title }}</title>
  <!-- Bootstrap CSS v5.2.1 -->
  {{-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-iYQeCzEYFbKjA/T2uDLTpkwGzCiq6soy8tYaI1GyVh/UjpbCx/TYkiZhlZB6+fzT" crossorigin="anonymous">
    
    <link rel="stylesheet" href="{{asset('css/module/header.css')}}">
    <link rel="stylesheet" href="{{asset('css/module/footer.css')}}">
    <link rel="stylesheet" href="{{asset('css/user/homepage.css')}}">
    <link rel="stylesheet" href="{{asset('css/owl.carousel.min.css')}}">
    <link rel="stylesheet" href="{{asset('css/owl.theme.default.min.css')}}">
    <link rel="stylesheet" href="/theme/plugins/fontawesome-free/css/all.min.css"> --}}
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="/plugins/fontawesome-free/css/all.min.css">
  <!-- overlayScrollbars -->
  <link rel="stylesheet" href="/plugins/overlayScrollbars/css/OverlayScrollbars.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="/dist/css/adminlte.min.css">

  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- icheck bootstrap -->
    {{-- <link rel="stylesheet" href="/theme/plugins/icheck-bootstrap/icheck-bootstrap.min.css"> --}}
    <!-- Theme style ĐAQLGIAY/QLGiay/public-->
    {{-- <link rel="stylesheet" href="/theme/dist/css/adminlte.min.css"> --}}

    
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
          <!-- Content Wrapper. Contains page content -->
      <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
          <div class="container-fluid">
            <div class="row mb-2">
              <div class="col-sm-6">
                <h1>List Receipts Unconfimred</h1>
              </div>
              <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                  <li class="breadcrumb-item">Receipts</li>
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
                          <form method="get" action="{{ route('a.r.list.0') }}" style="width: 50%;">
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
                        <h3 class="card-title">Nhập ngày đã tạo để tìm kiếm</h3>
                          
                      </div>
                      <div class="card-body">
                          <div id="example2_wrapper" class="dataTables_wrapper dt-bootstrap4">
                              <div class="row"><div class="col-sm-12 col-md-6"></div>
                              <div class="col-sm-12 col-md-6"></div></div><div class="row">
                                  <div class="col-sm-12"> 
    <table class="table table-bordered table-hover">
          <thead>
            <tr>
              <th>Mã biên lai</th>
              <th>Ngày tạo</th>
              <th>Ngày xác thực</th>
              <th>Giá trị biên lai</th>
              <th>Trạng thái xác thực</th>
              <th>Mã khách hàng</th>
              <th>Mã nhân viên</th>
              {{-- <th>Địa chỉ khách hàng</th>
              <th>SĐT khách hàng</th>
              <th>Email khách hàng</th> --}}
          </tr>
          </thead>
          <tbody>
              @foreach($receipts as $receipt)
                  <tr>
                      <td valign="middle">{{$receipt->receipt_id}}</td>
                      <td valign="middle">{{$receipt->created_date}}</td>
                      <td valign="middle">{{$receipt->validated_date}}</td>
                      <td valign="middle">{{$receipt->receipt_value}}</td>
                      @if($receipt->receipt_status == 0)
                      <td valign="middle"><td valign="middle"><b style="color: blue;">Chưa xác nhận</b></td></td>
                                            
                                            @endif
                      
                      <td valign="middle">{{$receipt->mem_id}}</td>
                      <td valign="middle">{{$receipt->staff_id}}</td>
                      <td>
                        <a class="btn btn-primary mr-2" href="/admin/edit/{{$receipt->receipt_id}}"><i class="fa fa-edit"></i></a>
                        {{-- <a class="btn btn-danger " href="/admin/delete_receipt/{{$receipt->receipt_id}}" onclick="return confirm('Bạn có chắc hủy biên lai này không?');"><i class="fa fa-trash"></i></a> --}}
                        <button type="button" class="btn btn-danger" onclick="del(this)" data-did="{{$receipt->receipt_id}}"><i class="fa fa-trash"></i></button>

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
                  <span>Showing {{ $receipts->firstItem() }} to {{ $receipts->lastItem() }} of {{ $count }} receipts</span>
              </div>
              <div style="float: right">
                {{ $receipts->appends(['searchName'=>$searchName ?? ''])->links()}}
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
  <script>
    $.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});
function del(button) {
    if(confirm('Bạn có muốn xóa receipt này không ?')){
        var did = button.getAttribute("data-did");
        var formData = new FormData();
        formData.append('did', did);
        $.ajax({
            type: "POST",
            url: "/delReceipt",
            data: formData,
            success:function (response) {
                alert('Receipt '+ did +' đã được xóa');
                window.location.reload();
            },
            error:function(response){
                alert("Lỗi khi xóa receipt!");
            },
            cache: false,
            contentType: false,
            processData: false,
        });
    }
    else{
        alert('Tại sao bạn không muốn xóa Receipt này !?')
    }
}
  </script>
</body>
</html>