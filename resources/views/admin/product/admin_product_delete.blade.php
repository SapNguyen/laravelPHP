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
    .expandable-body td {
        padding: 12px !important;
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
            <h1>List of products</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item">Products</li>
              <li class="breadcrumb-item">Delete</li>
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
                        <form method="get" action="{{ route('a.p.list') }}" style="width: 50%;">
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
                        <h3 class="card-title">Click on product to see delete button</h3>
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
                                            <th>Brand</th>
                                            <th>Price</th>
                                            <th>Left</th>
                                            <th>Last updated on</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($product as $p)
                                        <tr data-widget="expandable-table" aria-expanded="false">
                                            <td>{{ $p->product_id }}.</td>
                                            <td>{{ $p->product_name }}</td>
                                            <td>{{ $p->brand_name }}</td>
                                            <td>{{ $p->product_price }}</td>
                                            <td>
                                                @foreach($quan as $q)
                                                @if($q->product_id == $p->product_id)
                                                {{ $q->quan }}
                                                @endif
                                                @endforeach
                                            </td>
                                            <td>{{ $p->product_updated_date }}</td>
                                            @if( $p->product_active == 1)
                                            <td>Active</td>
                                            @else
                                            <td>Inactive</td>
                                            @endif
                                        </tr>
                                        <tr class="expandable-body">
                                            <td colspan="7">
                                                @if($p->product_active == 1)
                                                <button type="button" class="btn btn-block btn-danger" style="width: 40%; float: left;" data-pid="{{ $p->product_id }}" onclick="deactivate(this)">Deactivate</button>
                                                @else
                                                <button type="button" class="btn btn-block btn-success" style="width: 40%; float: left;" data-pid="{{ $p->product_id }}" onclick="activate(this)">Activate</button>
                                                @endif
                                                <button type="button" class="btn btn-block btn-warning" style="width: 40%; float: right;" data-pid="{{ $p->product_id }}" onclick="deletePro(this)">Delete</button>
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
                                            <span>Showing {{ $product->firstItem() }} to {{ $product->lastItem() }} of {{ $count }} brands</span>
                                        </div>
                                        <div style="float: right">
                                            {{ $product->links() }}
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
    if(confirm('Are you sure want to activate product no.' + button.getAttribute("data-pid") + '?')){
        var pid = button.getAttribute("data-pid");
        var formData = new FormData();
        formData.append('pid', pid);
        $.ajax({
            type: "POST",
            url: "/activateProduct",
            data: formData,
            success: function (response) {
                alert('Product no.'+ pid +' has been activated.');
                window.location.reload();
            },
            error:function(response){
                alert("An error when activating product!");
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
    if(confirm('Are you sure want to deactivate product no.' + button.getAttribute("data-pid") + '?')){
        var pid = button.getAttribute("data-pid");
        var formData = new FormData();
        formData.append('pid', pid);
        $.ajax({
            type: "POST",
            url: "/deactivateProduct",
            data: formData,
            success: function (response) {
                alert('Product no.'+ pid +' has been deactivated.');
                window.location.reload();
            },
            error:function(response){
                alert("An error when deactivating product!");
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

function deletePro(button){
	if(confirm('Are you sure want to delete product no.' + button.getAttribute("data-pid") + '?')){
        var pid = button.getAttribute("data-pid");
        var formData = new FormData();
        formData.append('pid', pid);
        $.ajax({
            type: "POST",
            url: "/deleteProduct",
            data: formData,
            success: function (response) {
                alert('Product no.'+ pid +' has been deleted.');
                window.location.reload();
            },
            error:function(response){
                alert("An error when deactivating product!");
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
