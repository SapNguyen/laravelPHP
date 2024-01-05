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

	<link rel="stylesheet" href="/admin/css/account/admin_staff_acc.css">

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
            <h1>Staff accounts</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item">Accounts</li>
              <li class="breadcrumb-item">Staff</li>
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
					<div class="card">
						<div class="card-header">
							<h3 class="card-title">
								<a href="{{ route('a.s.add.red') }}">
									<button class="btn btn-block btn-info">Add new staff account</button>
								</a>
							</h3>

							<div class="card-tools">
								<form method="get" action="{{ route('a.s.list') }}">
								<div class="input-group input-group-sm">
									<input type="text" name="table_search" class="form-control float-right" placeholder="Search username">
									<div class="input-group-append">
										<button type="submit" class="btn btn-default">
											<i class="fas fa-search"></i>
										</button>
									</div>
								</div>
								</form>
							</div>
							
						</div>
						<!-- /.card-header -->
						<div class="card-body table-responsive p-0" id="showBlock">
							<form method="post" id="staffList">
								@csrf
								<table class="table table-head-fixed table-bordered text-nowrap" id="staffTable">
                  <colgroup>
                    <col/>
                    <col/>
                    <col/>
                    <col/>
                    <col style="min-width: 127px;"/>
                    <col/>
                  </colgroup>
									<thead>
									<tr>
										<th>Username</th>
										<th>Password</th>
										<th>Name</th>
                    <th>Phone</th>
										<th>Status</th>
										<th></th>
									</tr>
									</thead>
									<tbody>
									@foreach($staff as $s)
									<tr>
										<input type="hidden" value="{{ $s->mem_id }}">
										<td>
											<input type="text" value="{{ $s->username }}" class="sUserInput" title="{{ $s->username }}" disabled>
											<input type="hidden" value="{{ $s->username }}">
										</td>
										<td>
											<input type="text" value="{{ $s->password }}" class="sPassInput" title="{{ $s->password }}" disabled>
										</td>
										<td>
											<input type="text" value="{{ $s->name }}" class="sNameInput" title="{{ $s->name }}" disabled>
										</td>
                    <td>
                      <input type="text" value="{{ $s->phone }}" class="sPhoneInput" title="{{ $s->phone }}" disabled>
                      <input type="hidden" value="{{ $s->phone }}">
                    </td>
										<td>
											<select disabled>
												@if($s->mem_active == 1)
												<option value="1" selected>Active</option>
												<option value="0">Inactive</option>
												@else
												<option value="1">Active</option>
												<option value="0" selected>Inactive</option>
												@endif
											</select>
										</td>
										<td>
											<button type="button" class="btn btn-block btn-primary changebtn">Change</button>
											<button type="button" class="btn btn-block btn-danger deletebtn" data-sid="{{ $s->mem_id }}">Delete</button>
											<button type="button" class="btn btn-block btn-success savebtn">Save</button>
											<button type="button" class="btn btn-block btn-warning resetbtn">Reset</button>
											<button type="button" class="btn btn-block btn-danger cancelbtn">Cancel</button>
										</td>
									</tr>
									@endforeach
									</tbody>
								</table>
							</form>
						</div>
					<!-- /.card-body -->
					</div>
				<!-- /.card -->
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

<script src="/admin/js/account/admin_staff_acc.js"></script>

<script>

const accs = <?php echo json_encode($accs); ?>;
const inputUser = document.getElementsByClassName('sUserInput');
const inputPass = document.getElementsByClassName('sPassInput');
const inputName = document.getElementsByClassName('sNameInput');
const inputPhone = document.getElementsByClassName('sPhoneInput');

$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

$('.savebtn').click(function(){
    var tr = $(this).closest('tr')[0].childNodes;

    for(var i = 0; i < accs.length; i++){
		if(accs[i].username == tr[3].childNodes[1].value || tr[3].childNodes[1].value == ""){
			alert('Username is invalid or taken!');
			return;
		}
	}

    var formData = new FormData();
    formData.append('sId', tr[1].value);
    formData.append('sUser', tr[3].childNodes[1].value);
    formData.append('sPass', tr[5].childNodes[1].value);
    formData.append('sName', tr[7].childNodes[1].value);
    formData.append('sPhone', tr[9].childNodes[1].value);
    formData.append('sStas', tr[11].childNodes[1].value);

    $.ajax({
        type: "POST",
        url: "/updatestaff",
        data: formData,
        success: function (response) {
			alert('Account updated successfully.')
			window.location.reload();
        },
        error: function (response) {
			alert('An error orcured when updating account!');
        },
        cache: false,
        contentType: false,
        processData: false,
    });
})

for(var i = 0; i < inputUser.length; i++){
	inputUser[i].addEventListener('change', (e) => {
		for(var i = 0; i < accs.length; i++){
			if(accs[i].username == e.target.value || e.target.value == ""){
				alert('Username is invalid or taken!');
				e.target.value = e.target.nextSibling.nextSibling.value;
				e.target.focus();
				return;
			}
		}
	})
}

for(var i = 0; i < inputUser.length; i++){
	inputPhone[i].addEventListener('change', (e) => {
		for(var i = 0; i < accs.length; i++){
			if(accs[i].phone == e.target.value){
				alert('Phone number has been used in other account!');
				e.target.value = e.target.nextSibling.nextSibling.value;
				e.target.focus();
				return;
			}
      else if(isNaN(e.target.value)){
        alert('Phone number contain alphabet character!');
				e.target.value = e.target.nextSibling.nextSibling.value;
				e.target.focus();
				return;
      }
		}
	})
}

for(var i = 0; i < inputPass.length; i++){
	inputPass[i].addEventListener('change', (e) => {
		if(e.target.value == ""){
			alert('Please fill in password!')
			e.target.focus();
		}
	})
}

for(var i = 0; i < inputName.length; i++){
	inputName[i].addEventListener('change', (e) => {
		if(e.target.value == ""){
			alert('Please fill in name!')
			e.target.focus();
		}
	})
}

</script>

</body>
</html>
