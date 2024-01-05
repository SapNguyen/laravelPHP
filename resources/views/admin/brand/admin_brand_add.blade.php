<?php
    use Illuminate\Support\Facades\File;

    $tempPath1 = public_path('img/brand/temp1');
    if(!File::exists($tempPath1)){
        File::makeDirectory($tempPath1);
    }
    else{
        File::cleanDirectory($tempPath1);
    }
    $tempPath2 = 'img/brand/temp2';
    if(!File::exists($tempPath2)){
        File::makeDirectory($tempPath2);
    }
    else{
        File::cleanDirectory($tempPath2);
    }
    $tempPath3 = 'img/brand/temp3';
    if(!File::exists($tempPath3)){
        File::makeDirectory($tempPath3);
    }
    else{
        File::cleanDirectory($tempPath3);
    } 
?>

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
            <h1>Add new brand</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item">Brands</li>
              <li class="breadcrumb-item active">Add</li>
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
                        <h3 class="card-title">Please fill in this form to add new brand</h3>
                    </div>
                    <form method="post" action="{{ route('a.b.add') }}" onsubmit="return checkForm()">
                        @csrf
                        <div class="card-body">
                            <div class="form-group">
                                <label for="exampleInputEmail1">Brand's Name<b style="color: red;"> *</b></label>
                                <input type="text" name="bName" class="form-control" id="exampleInputEmail1" placeholder="Enter brand's name"
                                required oninvalid="this.setCustomValidity('Please fill in brand\'s name!')" oninput="this.setCustomValidity('')">
                            </div>
                            <div class="form-group">
                                <label for="exampleInputFile">Brand's Logo</label>
                                <div class="input-group">
                                <div class="custom-file">
                                    <input type="file" name="bLogo" class="custom-file-input" id="bLogo" accept="image/*">
                                    <label class="custom-file-label" for="exampleInputFile">Choose file</label>
                                </div>
                                <div class="input-group-append">
                                    <span class="input-group-text">Upload</span>
                                </div>
                                </div>
                                <img src="/img/No_image_2.png" style="max-height: 200px; border: 1px solid black; margin-top: 5px;" id="logoImg">
                            </div>
                            <div class="form-group">
                                <label for="exampleInputFile">Home Page Image</label>
                                <div class="input-group">
                                    <div class="custom-file">
                                        <input type="file" name="bHPimg" class="custom-file-input" id="bHPimg" accept="image/*">
                                        <label class="custom-file-label" for="exampleInputFile">Choose file</label>
                                    </div>
                                    <div class="input-group-append">
                                        <span class="input-group-text">Upload</span>
                                    </div>
                                </div>
                                <img src="/img/No_image_2.png" style="max-height: 200px; border: 1px solid black; margin-top: 5px;" id="homeImg">
                            </div>
                            <div class="form-group">
                                <label for="exampleInputFile">Brand's Image</label>
                                <div class="input-group">
                                <div class="custom-file">
                                    <input type="file" name="bBPimg" class="custom-file-input" id="bBPimg" accept="image/*">
                                    <label class="custom-file-label" for="exampleInputFile">Choose file</label>
                                </div>
                                <div class="input-group-append">
                                    <span class="input-group-text">Upload</span>
                                </div>
                                </div>
                                <img src="/img/No_image_2.png" style="max-height: 200px; border: 1px solid black; margin-top: 5px;" id="brandImg">
                            </div>
                            <div class="form-group">
                                <label>Description</label>
                                <textarea name="bDes" class="form-control" rows="3" placeholder="Enter ..." style="min-height: 85.6px;"></textarea>
                            </div>
                        </div>
                        <!-- /.card-body -->

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
<!-- DataTables  & Plugins -->
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
<!-- Page specific script -->
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

document.getElementById('bLogo').addEventListener('change', (e) => {
    console.log(e.target.files[0])
    var formData = new FormData();
    formData.append('img1', e.target.files[0]);
    $.ajax({
        type: "POST",
        url: "/upload1",
        data: formData,
        success: function (response) {
            document.getElementById('logoImg').src = document.getElementById('logoImg').src.replace('No_image_2.png', 'brand/temp1/' + e.target.files[0].name);
        },
        error:function(response){
            alert("An error occurd when uploading file!");
        },
        cache: false,
        contentType: false,
        processData: false,
    });
});

document.getElementById('bHPimg').addEventListener('change', (e) => {
    var formData = new FormData();
    formData.append('img2', e.target.files[0]);
    $.ajax({
        type: "POST",
        url: "/upload2",
        data: formData,
        success: function (response) {
            document.getElementById('homeImg').src = document.getElementById('homeImg').src.replace('No_image_2.png', 'brand/temp2/' + e.target.files[0].name);
        },
        error:function(response){
            alert("An error occurd when uploading file!");
        },
        cache: false,
        contentType: false,
        processData: false,
    });
});

document.getElementById('bBPimg').addEventListener('change', (e) => {
    var formData = new FormData();
    formData.append('img3', e.target.files[0]);
    $.ajax({
        type: "POST",
        url: "/upload3",
        data: formData,
        success: function (response) {
            document.getElementById('brandImg').src = document.getElementById('brandImg').src.replace('No_image_2.png', 'brand/temp3/' + e.target.files[0].name);
        },
        error:function(response){
            alert("An error occurd when uploading file!");
        },
        cache: false,
        contentType: false,
        processData: false,
    });
});

document.getElementById('resetbtn').addEventListener('click', () => {
    document.getElementById('logoImg').src = '/img/No_image_2.png';
    document.getElementById('homeImg').src = '/img/No_image_2.png';
    document.getElementById('brandImg').src = '/img/No_image_2.png';
})

function checkForm(){
    var input = document.getElementById('exampleInputEmail1');
    var check = <?php echo json_encode($brand); ?>;
    console.log(check.length)
    for(var i = 0; i < check.length; i++){
        if(check[i]['brand_name'].toLowerCase() == input.value.toLowerCase()){
            alert('Brand "' + input.value +'" is already existing!');
            return false;
            break;
        }
    }
    if(confirm('Are you sure want to add this brand?')){
        alert('Brand "' + input.value + '" added successfully.');
        return true;
    }
    else{
        return false;
    }
}
</script>
</body>
</html>