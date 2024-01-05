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
    .imgshow {
        width: 100%;
        max-width: 700px;
        display: flex;
        flex-wrap: wrap;
        justify-content: space-around;
        align-content: center;
        align-items: center;
    }
    .imgshow img {
        max-width: 100%;
        max-height: 100px;
        border: 1px solid black;
        margin: 5px;
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
            <h1>Product No.{{ $product[0]->product_id }}</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item">Products</li>
              <li class="breadcrumb-item"><a href="{{ route('a.p.list') }}">List</a></li>
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
            <div class="col-12">
                <div class="card card-info">
                    <div class="card-header">
                        <h3 class="card-title">Product's information</h3>
                    </div>
                    <div class="card-body">
                        <div id="example2_wrapper" class="dataTables_wrapper dt-bootstrap4">
                            <div class="row"><div class="col-sm-12 col-md-6"></div>
                            <div class="col-sm-12 col-md-6"></div></div><div class="row">
                                <div class="col-sm-12">
                                <table class="table table-bordered ">
                                    <colgroup>
                                        <col style="width: 20%;"/>
                                        <col/>
                                    </colgroup>
                                    <tbody>
                                        <tr>
                                            <th>Product No.</th>
                                            <td>{{ $product[0]->product_id }}</td>
                                        </tr>
                                        <tr>
                                            <th>Name</th>
                                            <td>{{ $product[0]->product_name }}</td>
                                        </tr>
                                        <tr>
                                            <th>Brand</th>
                                            <td>{{ $product[0]->brand_name }}</td>
                                        </tr>
                                        <tr>
                                            <th>Material</th>
                                            <td>{{ $product[0]->product_material }}</td>
                                        </tr>
                                        <tr>
                                            <th>Price&nbsp;&nbsp;&nbsp;(VND)</th>
                                            <td>{{ $product[0]->product_price }}</td>
                                        </tr>
                                        <tr>
                                            <th>Left</th>
                                            <td id="totalLeft">0</td>
                                        </tr>
                                        <tr>
                                            <th>Description</th>
                                            <td>{{ $product[0]->product_des }}</td>
                                        </tr>
                                        <tr>
                                            <th>Status</th>
                                            @if( $product[0]->product_active == 1)
                                            <td>Active</td>
                                            @else
                                            <td>Inactive</td>
                                            @endif
                                        </tr>
                                        <tr>
                                            <th>Last updated on</th>
                                            <td>{{ $product[0]->product_updated_date }}</td>
                                        </tr>
                                    </tbody>
                                    </table>
                                    <table class="table table-bordered ">
                                        <colgroup>
                                        </colgroup>
                                        <thead>
                                            <th>Images</th>
                                            <th>Color</th>
                                            <th>Size</th>
                                            <th>Quantity</th>
                                        </thead>
                                        <tbody>
                                        @foreach($psc1 as $sc1)
                                        <tr>
                                            <td rowspan="{{ $sc1->row + 1 }}">
                                                <div class="imgshow">
                                                @foreach(explode(',', $sc1->product_image) as $img)
                                                    @if($img == "No_image_2.png")
                                                    <img src = "/img/No_image_2.png">
                                                    @else
                                                    <img src="/img/product/{{ $product[0]->product_id }}/{{ $img }}">
                                                    @endif
                                                @endforeach
                                                </div>
                                            </td>
                                            <td rowspan="{{ $sc1->row + 1 }}">{{ $sc1->color }}</td>
                                        </tr>
                                        @foreach($psc2 as $sc2)   
                                            @if($sc2->product_id == $sc1->product_id && $sc2->color == $sc1->color)
                                        <tr>    
                                            <td>{{ $sc2->size }}</td>
                                            <td class="pq">{{ $sc2->quantity }}</td>
                                        </tr>
                                            @endif
                                        @endforeach
                                        </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                    <div style="width: 100%; padding: 12px;">
                                        @if($product[0]->product_active == 1)
                                        <button type="button" class="btn btn-block btn-danger" style="width: 40%; float: left;" data-pid="{{ $product[0]->product_id }}" onclick="deactivate(this)">Deactivate</button>
                                        @else
                                        <button type="button" class="btn btn-block btn-success" style="width: 40%; float: left;" data-pid="{{ $product[0]->product_id }}" onclick="activate(this)">Activate</button>
                                        @endif
                                        <a href="{{ route('a.p.edit.red', ['pid'=>$product[0]->product_id]) }}" style="width: 40%; float: right;">
                                            <button type="button" class="btn btn-block btn-info">Edit</button>
                                        </a>
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
    if(confirm('Are you sure want to activate this product ?')){
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
    if(confirm('Are you sure want to deactivate this product?')){
        var pid = button.getAttribute("data-pid");
        var formData = new FormData();
        formData.append('pid', pid);
        $.ajax({
            type: "POST",
            url: "/deactivateProduct",
            data: formData,
            success: function (response) {
                alert('Brand no.'+ pid +' has been deactivated.');
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

const totalLeft = document.getElementById('totalLeft');
var pq = document.getElementsByClassName('pq');
for(var i = 0; i < pq.length; i++){
    totalLeft.innerHTML = Number(totalLeft.innerHTML) + Number(pq[i].innerHTML);
}

</script>
</body>
</html>
