<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
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
            <h1>Edit discount</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item">Discounts</li>
              <li class="breadcrumb-item"><a href="{{ route('a.d.list') }}">List</a></li>
              <li class="breadcrumb-item">Edit</li>
            </ol>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
      @include('module.error')
      <div class="container-fluid">
        <div class="row">
            <div class="card card-info" style="width: 100%;">
                <div class="card-header">
                    <h3 class="card-title">Change discount no.{{ $discount[0]->discount_id }} 's infomation</h3>
                </div>
      <div class="main-discount" >
      <div class="input-discord">
      <form name=f action="{{route('a.d.edit', ['did'=>$discount[0]->discount_id])}}" method=Post onsubmit="return checkForm()">
        @csrf
        <div class="card-body">
        <div class="form-group">
          <label for="exampleInputEmail1">Mã giảm giá:<b style="color: red;"> *</b></label>
          <div>{{ $discount[0]->discount_id }}</div>
          
        </div>
        <div class="form-group">
          <label for="exampleInputEmail1">Tên giảm giá:<b style="color: red;"> *</b></label>
          <input type="text" name="txtname" class="form-control" id="exampleInputEmail1" value="{{ $discount[0]->discount_name }}"
          required oninvalid="this.setCustomValidity('Please fill in discount\'s name!')" oninput="this.setCustomValidity('')">
        </div>
        
        <div class="form-group">
          <label for="exampleInputEmail1">Ngày bắt đầu:</label>
          <input type="date"  name="date-start" class="form-control" id="exampleInputEmail1" value="{{ $discount[0]->discount_start }}"
          required oninvalid="this.setCustomValidity('Please fill in discount\'s date-start!')" oninput="this.setCustomValidity('')">
        </div>
        <div class="form-group">
          <label for="exampleInputEmail1">Ngày kết thúc:<b style="color: red;"> *</b></label>
          <input type="date" name="date-end" class="form-control" id="exampleInputEmail1" value="{{ $discount[0]->discount_end }}"
          required oninvalid="this.setCustomValidity('Please fill in discount\'s date-end!')" oninput="this.setCustomValidity('')">
        </div>
        <div class="form-group">
          <label for="exampleInputEmail1">Mức giảm:<b style="color: red;"> *</b></label>
          <input type="text" name="txtvalue" class="form-control" id="exampleInputEmail1" value="{{ $discount[0]->discount_value }}"
          required oninvalid="this.setCustomValidity('Please fill in discount\'s value!')" oninput="this.setCustomValidity('')">
        </div> 
          
      </div>
        <div class="card-footer">
          <button type="submit" class="btn btn-success" style="float: right;">Save</button>
          <button type="reset" class="btn btn-warning" style="float: left;" id="resetbtn">Reset</button>
        </div>
          
      </form>	
     
    </div>
    
    </div>
  </div>
</div>
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

</body>
</html>
