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

	<link rel="stylesheet" href="/admin/css/account/admin_staff_acc_add.css">

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
            <h1>Add staff account</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item">Accounts</li>
              <li class="breadcrumb-item"><a href="{{ route('a.s.list') }}">Staff</a></li>
              <li class="breadcrumb-item">Add</li>
            </ol>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">

		<div class="container-fluid" id="contentBlock">

			<div class="card" id="formcard">
              <div class="card-header">
                <h3 class="card-title">Add new staff account</h3>
              </div>
              <!-- /.card-header -->
              <div class="card-body p-0">
                <form method="post" action="{{ route('a.s.add') }}" id="addForm">
                    @csrf
                    <table class="table table-sm">
                    <colgroup>
                        <col style="width: 15%;" />
                        <col/>
                    </colgroup>
                    <tbody>
                        <tr>
                            <th>Username<b style="color: red;"> *</b></th>
                            <td>
                                <input type="text" name="nsUser" id="nsUser" required
                                oninvalid="this.setCustomValidity('Please fill in username.')"
                                oninput="this.setCustomValidity('')">
                            </td>
                        </tr>
                        <tr>
                            <th>Password<b style="color: red;"> *</b></th>
                            <td>
                                <input type="text" name="nsPass" id="nsPass" required
                                oninvalid="this.setCustomValidity('Please fill in password.')"
                                oninput="this.setCustomValidity('')">
                            </td>
                        </tr>
                        <tr>
                            <th>Name<b style="color: red;"> *</b></th>
                            <td>
                                <input type="text" name="nsName" id="nsName" required
                                oninvalid="this.setCustomValidity('Please fill in name.')"
                                oninput="this.setCustomValidity('')">
                            </td>
                        </tr>
                        <tr>
                            <th>Phone<b style="color: red;"> *</b></th>
                            <td>
                                <input type="text" inputmode="decimal" name="nsPhone" id="nsPhone" required
                                oninvalid="this.setCustomValidity('Please fill in phone number.')"
                                oninput="this.setCustomValidity('')">
                            </td>
                        </tr>
                    </tbody>
                    </table>
                    <div id="btncon">
                        <button type="submit" class="btn btn-block btn-success" id="savebtn">Save</button>
                        <button type="button" class="btn btn-block btn-warning" id="resetbtn">Reset</button>
                        <button type="button" class="btn btn-block btn-danger" id="cancelbtn">Cancel</button>
                    </div>
                </form>
              </div>
              <!-- /.card-body -->
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

<script src="/admin/js/account/admin_staff_acc_add.js"></script>

<script>

const accs = <?php echo json_encode($accs) ?>;
const nsUser = document.getElementById('nsUser');

nsUser.addEventListener('change', (e) => {
    for(var i = 0; i < accs.length; i++){
        if(e.target.value == accs[i].username){
            alert('Username "' + e.target.value + '" has been taken!');
            e.target.focus();
            return;
        }
    }
})

</script>

</body>
</html>
