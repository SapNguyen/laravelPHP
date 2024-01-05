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
    td {
        vertical-align: middle !important;
    }
    .activebtn, .deactivebtn {
        width: 40%;
        float: left;
    }
    .hiddeninfo th, .hiddeninfo td {
        background-color: #dee2e6;
    }
    #paginate {
        width: 100%;
        padding: 7.5px;
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
            <h1>List of discounts</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item">Discounts</li>
              <li class="breadcrumb-item">List</li>
            </ol>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card card-info">
                    <div class="card-header">
                        <form method="get" action="{{ route('a.d.list') }}" style="width: 50%;">
                            <div class="input-group">
                                <input type="search" name="searchName" class="form-control form-control-lg" placeholder="Type discount's name here">
                                <div class="input-group-append">
                                    <button type="submit" class="btn btn-lg btn-default">
                                        <i class="fa fa-search"></i>
                                    </button>
                                </div>
                            </div>
                        </form>
                        <br>
                        <h3 class="card-title">Click on discount to see more details</h3>
                    </div>
                    <div class="card-body">
                        <div id="example2_wrapper" class="dataTables_wrapper dt-bootstrap4">
                            <div class="row"><div class="col-sm-12 col-md-6"></div>
                            <div class="col-sm-12 col-md-6"></div></div><div class="row">
                                <div class="col-sm-12"> 
  <table class="table table-bordered table-hover">
    <thead>
      <tr>
        <th>Mã khuyến mại</th>
        <th>Tên khuyến mại</th>
        <th>Ngày bắt đầu</th>
        <th>Ngày kết thúc</th>
        <th>Giá trị khuyến mại</th>
        <th>Trạng thái khuyến mại</th>
    </tr>
    </thead>
    <tbody>
        @foreach($discounts as $discount)
        <tr data-widget="expandable-table" aria-expanded="false">
            
                <td valign="middle">{{$discount->discount_id}}</td>
                <td valign="middle">{{$discount->discount_name}}</td>
                <td valign="middle">{{$discount->discount_start}}</td>
                <td valign="middle">{{$discount->discount_end}}</td>
                <td valign="middle">{{$discount->discount_value}}</td>
                @if($discount->discount_active == 1)
                                            <td valign="middle">Active</td>
                                            @else
                                            <td valign="middle">Inactive</td>
                                            @endif
                <td>
                  <a class="btn btn-primary mr-2" href="{{ route('a.d.edit.red', ['did'=>$discount->discount_id]) }}"><i class="fa fa-edit"></i></a>
                    <a class="btn btn-primary mr-2" href="{{ route('a.d.view', ['discount_id'=>$discount->discount_id]) }}"><i class="fa fa-plus"></i></a>
                    
                </td>
            
          </tr>
            <tr class="expandable-body">
              <td colspan="7">
                  <table style="width: 100%;" class="hiddeninfo">
                  <colgroup>
                      <col style="width: 50%"/>
                      <col style="width: 50%"/>
                  </colgroup>
                  <tbody>
                      
                      <tr>
                          <td colspan="1">
                          @if($discount->discount_active == 1)
                              <button type="button" class="btn btn-block btn-danger deactivebtn" onclick="deactivate(this)" data-did="{{$discount->discount_id}}">Deactivate</button>
                          @else
                              <button type="button" class="btn btn-block btn-success activebtn" onclick="activate(this)" data-did="{{$discount->discount_id}}">Activate</button>
                          @endif
                          </td>
                      </tr>
                  </tbody>
                  </table>
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
                <span>Showing {{ $discounts->firstItem() }} to {{ $discounts->lastItem() }} of {{ $count }} discounts</span>
            </div>
            <div style="float: right">
                {{ $discounts->appends(['searchName'=>$searchName ?? ''])->links() }}
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
function activate(button) {
    if(confirm('Bạn có muốn active khuyến mại này không ?')){
        var did = button.getAttribute("data-did");
        var formData = new FormData();
        formData.append('did', did);
        $.ajax({
            type: "POST",
            url: "/activateDiscount",
            data: formData,
            success:function (response) {
                alert('discount no.'+ did +' has been activated.');
                window.location.reload();
            },
            error:function(response){
                alert("An error when activating discount!");
            },
            cache: false,
            contentType: false,
            processData: false,
        });
    }
    else{
        alert('Why did you press this button in the first place!?')
    }
}

function deactivate(button) {
    if(confirm('Bạn có muốn deactive khuyến mại này không ?')){
        var did = button.getAttribute("data-did");
        var formData = new FormData();
        formData.append('did', did);
        $.ajax({
            type: "POST",
            url: "/deactivateDiscount",
            data: formData,
            success:function (response) {
                alert('discount no.'+ did +' has been deactivated.');
                window.location.reload();
            },
            error:function(response){
                alert(" An error when deactivating discount!");
            },
            cache: false,
            contentType: false,
            processData: false,
        });
    }
    else{
        alert('Why did you press this button in the first place!?')
    }
}
</script>
</body>
</html>
