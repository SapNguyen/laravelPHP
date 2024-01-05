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

  <link rel="stylesheet" href="/admin/css/account/admin_admin_acc.css">

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
            <h1>Admin account</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item">Accounts</li>
              <li class="breadcrumb-item">Admin</li>
            </ol>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">

    <div class="container-fluid">

      <div class="card">
        <div class="card-header">
          <h3 class="card-title">Admin account</h3>
        </div>
        <!-- /.card-header -->
        <div class="card-body">
          <form method="post" action="{{ route('a.a.update') }}">
            @csrf
            <input type="hidden" value="{{ $admin[0]->mem_id }}" name="aId">
            <table class="table table-bordered " id="adminInfo">
              <colgroup>
                <col style="width: 20%;"/>
                <col/>
              </colgroup>
              <tbody>
                <tr>
                  <th>Username</td>
                  <td>
                    <input type="text" name="aName" value="{{ $admin[0]->username }}" id="userInput" disabled
                    required oninvalid="this.setCustomValidity('')" oninput="this.setCustomValidity('')">
                  </td>
                </tr>
                <tr>
                  <th>Password</th>
                  <td>
                    <input type="password" name="aPass" value="{{ $admin[0]->password }}" id="passInput" disabled>
                  </td>
                </tr>
                <tr>
                  <td colspan="2">
                    <div id="btncon">
                      <button type="button" class="btn btn-block btn-primary" id="changebtn">Change</button>
                      <button type="submit" class="btn btn-block btn-success editbtn" id="savebtn">Save</button>
                      <input type="reset" value="Reset" class="editbtn" id="resetbtn">
                      <button type="button" class="btn btn-block btn-danger editbtn" id="cancelbtn">Cancel</button>
                    </div>
                  </td>
                </tr>
              </tbody>
            </table>
          </form>
        </div>
        <!-- /.card-body -->
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

<script src="/admin/js/account/admin_admin_acc.js"></script>
<script>

userInput.addEventListener('change', (e) => {
    var user = <?php echo json_encode($accs); ?>;
    for(var i = 0; i < user.length; i++){
      if(e.target.value == user[i].username){
        alert('Username "' + e.target.value + '" has been taken!');
        e.target.value = oldusername;
        break;
      }
    }
})

</script>

</body>
</html>
