<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>{{ $title }}</title>
  <style>
    td{
      padding-top: 8px
    }
  </style>
  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="/plugins/fontawesome-free/css/all.min.css">
  <!-- overlayScrollbars -->
  <link rel="stylesheet" href="/plugins/overlayScrollbars/css/OverlayScrollbars.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="/dist/css/adminlte.min.css">
  <link rel="stylesheet" href="../../plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
  <link rel="stylesheet" href="../../plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
  <link rel="stylesheet" href="../../plugins/datatables-buttons/css/buttons.bootstrap4.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
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

<!-- Receipt -->

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Add new discount</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item">Discounts</li>
              <li class="breadcrumb-item">Add</li>
            </ol>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        <div class="row">
            <div class="card card-info" style="width: 100%;">
                <div class="card-header">
                    <h3 class="card-title">Please fill in this form to add new discount</h3>
                </div>
      
      <form method="post" action="{{route('a.d.add')}}"  onsubmit="return checkForm()">
        @csrf
        <div class="card-body">
        <div class="form-group">
          {{-- <label for="exampleInputEmail1">Mã giảm giá:<b style="color: red;"> *</b></label> --}}
          <input type="hidden" name="txtid" class="form-control" id="exampleInputMa" value="{{$id}}"
          required oninvalid="this.setCustomValidity('Please fill in discount\'s id!')" oninput="this.setCustomValidity('')">
        </div>
        <div class="form-group">
          <label for="exampleInputEmail1">Tên giảm giá:<b style="color: red;"> *</b></label>
          <input type="text" name="txtname" class="form-control" id="exampleInputTen" placeholder="Enter discount's name"
          required oninvalid="this.setCustomValidity('Please fill in discount\'s name!')" oninput="this.setCustomValidity('')">
        </div>
        <?php 
              $month = date('m');
              $day = date('d');
              $year = date('Y');

              $today = $year . '-' . $month . '-' . $day;
            ?>
        <div class="form-group">
          <label for="exampleInputEmail1">Ngày bắt đầu:</label>
          <input type="date" value="<?php echo $today; ?>" name="date-start" class="form-control" 
          required oninvalid="this.setCustomValidity('Please fill in discount\'s date-start!')" oninput="this.setCustomValidity('')">
        </div>
        <div class="form-group">
          <label for="exampleInputEmail1">Ngày kết thúc:<b style="color: red;"> *</b></label>
          <input type="date" name="date-end" class="form-control" 
          required oninvalid="this.setCustomValidity('Please fill in discount\'s date-end!')" oninput="this.setCustomValidity('')">
        </div>
        <div class="form-group">
          <label for="exampleInputEmail1">Mức giảm:<b style="color: red;"> *</b></label>
          <input type="text" name="txtvalue" class="form-control"  placeholder="Enter discount's value"
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
<script src="../../plugins/datatables/jquery.dataTables.min.js"></script>
<script src="../../plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
<script src="../../plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
<script src="../../plugins/datatables-responsive/js/responsive.bootstrap4.min.js"></script>
<script src="../../plugins/datatables-buttons/js/dataTables.buttons.min.js"></script>
<script src="../../plugins/datatables-buttons/js/buttons.bootstrap4.min.js"></script>
<script src="../../plugins/jszip/jszip.min.js"></script>
<script src="../../plugins/pdfmake/pdfmake.min.js"></script>
<script src="../../plugins/pdfmake/vfs_fonts.js"></script>
<script src="../../plugins/datatables-buttons/js/buttons.html5.min.js"></script>
<script src="../../plugins/datatables-buttons/js/buttons.print.min.js"></script>
<script src="../../plugins/datatables-buttons/js/buttons.colVis.min.js"></script>
<!-- bs-custom-file-input -->
<script src="../../plugins/bs-custom-file-input/bs-custom-file-input.min.js"></script>
<!-- AdminLTE App -->
<script src="/dist/js/adminlte.min.js"></script>

<script>
  $.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});
$(function () {
    $('#example2').DataTable({
      "paging": false,
      "lengthChange": false,
      "searching": false,
      "ordering": true,
      "info": true,
      "autoWidth": false,
      "responsive": true,
    });
    bsCustomFileInput.init();
});
function checkForm(){
    // var input = document.getElementById('exampleInputMa');
    var input1 = document.getElementById('exampleInputTen');
    var check = <?php echo json_encode($discount); ?>;
    console.log(check.length)
    // for(var i = 0; i < check.length; i++){
    //     if(check[i]['discount_id'].toLowerCase() == input.value.toLowerCase()){
    //         alert('Discount id "' + input.value +'" is already existing!');
    //         return false;
    //         break;
    //     }
    // }
    for(var i = 0; i < check.length; i++){
        if(check[i]['discount_name'].toLowerCase() == input1.value.toLowerCase()){
            alert('Discount name "' + input1.value +'" is already existing!');
            return false;
            break;
        }
    }
    
    if(confirm('Are you sure want to add this discount?')){
        alert('discount "' + input1.value + '" added successfully.');
        return true;
    }
    else{
        return false;
    }
}
</script>
</body>
</html>
