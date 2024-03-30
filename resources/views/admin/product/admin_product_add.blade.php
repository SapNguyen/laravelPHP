<?php
use Illuminate\Support\Facades\File;
$path = 'img/product/temp';
if(File::exists($path)){
    File::cleanDirectory($path);
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
  <link rel="stylesheet" href="/plugins/fontawesome-free/css/all.min.css">
  <!-- overlayScrollbars -->
  <link rel="stylesheet" href="/plugins/overlayScrollbars/css/OverlayScrollbars.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="/dist/css/adminlte.min.css">

  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

  <link rel="stylesheet" href="/css/product/admin_product_add.css">

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
            <h1>Add new product</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item">Products</li>
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
								<form method="post" id="addForm">
									@csrf
                                <table class="table table-bordered ">
                                    <colgroup>
                                        <col style="width: 20%;"/>
                                        <col/>
                                    </colgroup>
                                    <tbody>
                                        <tr>
                                            <th>Name</th>
                                            <td>
												<input type="text" id="pName">
											</td>
                                        </tr>
                                        <tr>
                                            <th>Brand</th>
                                            <td>
												<select id="pBrand">
												@foreach($brand as $b)
													<option value="{{ $b['brand_id'] }}">{{ $b['brand_name'] }}</option>
												@endforeach
												</select>
											</td>
                                        </tr>
										<tr>
                                            <th>Genre</th>
                                            <td>
												<select id="pGenre">
													<option value="Unisex">Unisex</option>
													<option value="Nam">Nam</option>
													<option value="Nữ">Nữ</option>
												</select>
											</td>
                                        </tr>
                                        <tr>
                                            <th>Material</th>
                                            <td>
												<input type="text" id="pMaterial">
											</td>
                                        </tr>
                                        <tr>
                                            <th>Price</th>
                                            <td>
											    <input type="number" min="1000" max="999999999" step="1000" id="pPrice" value="0">
											</td>
                                        </tr>
                                        <tr>
                                            <th>Description</th>
                                            <td style="padding: 0;">
												<textarea id="pDes"></textarea>
											</td>
                                        </tr>
                                    </tbody>
                                </table>
                                <div id="csqBlock">
									<button type="button" class="btn btn-block btn-primary" id="addColorBtn">Add color</button>
									<br>
									<div id="addColor">

										<table class="table table-bordered addItem">
											<tr>
												<th>
													<div>
														{{-- <input type="file" class="imgInput" multiple>
														<input type="hidden" class="imgText">
														<span>Images (max 6 images)</span>
														<button type="button" class="btn btn-block btn-warning addImg">Select</button> --}}
														<input type="file" class="imgInput" multiple>
														<input type="hidden" class="imgText">
														<span>Images</span>
														<button type="button" class="btn btn-block btn-warning addImg">Select</button>
													</div>
												</th>
												<th>
													<div>
														<span>Color</span>
														<button type="button" class="btn btn-block btn-danger removeColor">Remove</button>
													</div>
												</th>
												<th>
													<div>
														<span>Size</span>
														<button type="button" class="btn btn-block btn-secondary addSize">Add</button>
													</div>
												</th>
												<th>Quantity</th>
												<th></th>
											</tr>

											{{-- <tr>
												<td class="imgcol" rowspan="2">
													<div class="imgshow">
														<img src="/img/No_image.png" alt="">
													</div>
												</td>
												<td class="colorcol" rowspan="2">
													<textarea class="pColor"></textarea>
												</td>
											</tr> --}}

											<tr>
												<td class="imgcol" rowspan="2">
												  <div class="imgshow">
													  {{-- <img src="/img/product/temp/"> --}}
												  </div>
												</td>
												<td class="colorcol" rowspan="2">
												  <textarea class="pColor"></textarea>
												</td>
											  </tr>

											<tr>
												<td>
													<input type="number" min="0" step="0.25" class="pSize" value="0">
												</td>
												<td>
													<input type="number" min="0" class="pQuan" value="0">
												</td>
												<td class="removecol">
													<!-- <button type="button" class="btn btn-block btn-danger removeSize">X</button> -->
												</td>
											</tr>

										</table>

									</div>
								</div>
                                <div id="btnblock">
                                    <button type="button" class="btn btn-block btn-success" id="submitbtn">Save</button>
                                    <button type="button" class="btn btn-block btn-danger" id="cancelbtn">Cancel</button>
                                </div>
								</form>
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

<script src="/js/product/admin_product_add.js"></script>

<script>

submitbtn.addEventListener('click', (e) => {
	if(pName.value == ''){
		pName.focus();
		alert('Please fill in name!');
		return;
	}
	if(pMaterial.value == ''){
		pMaterial.focus();
		alert('Please fill in material!');
		return;
	}
	if(pDes.value == ''){
		pDes.focus();
		alert('Please fill in description!');
		return;
	}
	var imgtext = document.getElementsByClassName('imgText');
	for(var i = 0; i < imgtext.length; i++){
		if(imgtext[i].value == ''){
			imgtext[i].focus();
			alert('Please choose atleast 1 image for each color!');
			return;
		}
	}
	var color = document.getElementsByClassName('pColor');
	for(var i = 0; i < color.length; i++){
		if(color[i].value == ''){
			color[i].focus();
			alert('Please fill in color!');
			return;
		}
	}
	var Name = pName.value;
	var Brand = pBrand.value;
	var Genre = pGenre.value;
	var Material = pMaterial.value;
	var Price = pPrice.value;
	var Des = pDes.value;
	var formData = new FormData();
	formData.append('name', Name);
	formData.append('brand', Brand);
	formData.append('genre', Genre);
	formData.append('material', Material);
	formData.append('price', Price);
	formData.append('des', Des);
	var pscq = [];
	var sizes = document.getElementsByClassName('pSize');
	for(var i = 0; i < sizes.length; i++){
		var pcs_color = sizes[i].closest('table').querySelector('.pColor').value;
		var pcs_size = sizes[i].closest('tr').querySelector('.pSize').value;
		var pcs_quan = sizes[i].closest('tr').querySelector('.pQuan').value;
		var pcs_img = sizes[i].closest('table').querySelector('.imgText').value;
		pscq[i] = pcs_color + '|' + pcs_size + '|' + pcs_quan + '|' + pcs_img;
		console.log(pscq[i])
		formData.append('pcsq[]', pscq[i]);
	}
	$.ajax({
		type: 'POST',
		url: '/addProduct',
		data: formData,
		success: function(response){
			alert('Thêm sản phẩm thành công');
			window.location.href = '/admin/products';
		},
		error: function(response){
			alert('An error orcured when saving product!');
		},
		cache: false,
		contentType: false,
		processData: false,
	})
})

</script>

</body>
</html>
