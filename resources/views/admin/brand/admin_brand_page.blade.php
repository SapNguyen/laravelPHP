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
            <h1>List of brands</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item">Brands</li>
              <li class="breadcrumb-item active">List</li>
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
                        <form method="get" action="{{ route('a.b.list') }}" style="width: 50%;">
                            <div class="input-group">
                                <input type="search" name="searchName" class="form-control form-control-lg" placeholder="Type brand's name here">
                                <div class="input-group-append">
                                    <button type="submit" class="btn btn-lg btn-default">
                                        <i class="fa fa-search"></i>
                                    </button>
                                </div>
                            </div>
                        </form>
                        <br>
                        <h3 class="card-title">Click on brand to see more details</h3>
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
                                            <th>Name</th>
                                            <th>logo</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($brand as $b)
                                        <tr data-widget="expandable-table" aria-expanded="false">
                                            <td>{{ $b->brand_id }}.</td>
                                            <td class="bName">{{ $b->brand_name }}</td>
                                            <td>
                                                @if($b->brand_logo == "No_image_2.png")
                                                <img src="/img/No_image_2.png" style="max-height: 75px; border: 1px solid black;">
                                                @else
                                                <img src="/img/brand/{{ $b->brand_id }}/{{ $b->brand_logo }}" style="max-height: 75px; border: 1px solid black;">
                                                @endif
                                            </td>
                                            @if($b->brand_active == 1)
                                            <td>Active</td>
                                            @else
                                            <td>Inactive</td>
                                            @endif
                                        </tr>
                                        <tr class="expandable-body">
                                        <td colspan="4">
                                            <table style="width: 100%;" class="hiddeninfo">
                                            <colgroup>
                                                <col style="width: 50%"/>
                                                <col style="width: 50%"/>
                                                <!-- <col style="width: 50%"/> -->
                                            </colgroup>
                                            <tbody>
                                                <tr>
                                                    <th>Home Page Image</th>
                                                    <th>Brand Page Image</th>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        @if($b->brand_img == "No_image_2.png")
                                                        <img src="/img/No_image_2.png" style="max-width: 100%; max-height: 100%; border: 1px solid black;">
                                                        @else
                                                        <img src="/img/brand/{{ $b->brand_id }}/{{ $b->brand_img }}" style="max-width: 100%; max-height: 200px; border: 1px solid black;">
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @if($b->brand_des_img == "No_image_2.png")
                                                        <img src="/img/No_image_2.png" style="max-width: 100%; max-height: 100%; border: 1px solid black;">
                                                        @else
                                                        <img src="/img/brand/{{ $b->brand_id }}/{{ $b->brand_des_img }}" style="max-width: 100%; max-height: 200px; border: 1px solid black;">
                                                        @endif
                                                    </td>
                                                    
                                                </tr>
                                                <tr>
                                                    <th colspan="2">Description</th>
                                                </tr>
                                                <tr>
                                                    @if($b->brand_des <> "")
                                                    <td colspan="2">{{ $b->brand_des }}</td>
                                                    @else
                                                    <td colspan="2">No data</td>
                                                    @endif
                                                </tr>
                                                <tr>
                                                    <td colspan="2">
                                                    @if($b->brand_active == 1)
                                                        <button type="button" class="btn btn-block btn-danger deactivebtn" onclick="deactivate(this)" data-bid="{{ $b->brand_id }}">Deactivate</button>
                                                    @else
                                                        <button type="button" class="btn btn-block btn-success activebtn" onclick="activate(this)" data-bid="{{ $b->brand_id }}">Activate</button>
                                                    @endif
                                                        <a href="{{ route('a.b.edit.red', ['bid'=>$b->brand_id]) }}" style="width: 40%; float: right;">
                                                            <button type="button" class="btn btn-block btn-info">Edit</button>
                                                        </a>
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
                                            <span>Showing {{ $brand->firstItem() }} to {{ $brand->lastItem() }} of {{ $count }} brands</span>
                                        </div>
                                        <div style="float: right">
                                            {{ $brand->links() }}
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
<!-- Page specific script -->
<script>

$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

function activate(button) {
    if(confirm('All product of this brand will be activated too.\nAre you sure want to activate this brand?')){
        var bid = button.getAttribute("data-bid");
        var formData = new FormData();
        formData.append('bid', bid);
        $.ajax({
            type: "POST",
            url: "/activateBrand",
            data: formData,
            success: function (response) {
                alert('Brand no.'+ bid +' has been activated.');
                window.location.reload();
            },
            error:function(response){
                alert("An error when activating brand!");
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
    if(confirm('All product of this brand will be deactivated too.\nAre you sure want to deactivate this brand?')){
        var bid = button.getAttribute("data-bid");
        var formData = new FormData();
        formData.append('bid', bid);
        $.ajax({
            type: "POST",
            url: "/deactivateBrand",
            data: formData,
            success: function (response) {
                alert('Brand no.'+ bid +' has been deactivated.');
                window.location.reload();
            },
            error:function(response){
                alert("An error when deactivating brand!");
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
