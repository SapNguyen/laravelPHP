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

  <link rel="stylesheet" href="/admin/css/account/admin_member_acc.css">

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
            <h1>Member accounts</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item">Accounts</li>
              <li class="breadcrumb-item">Member</li>
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
              <h3 class="card-title">List of member accounts</h3>
            </div>
            <!-- ./card-header -->
            <div class="card-body">
              <table class="table table-bordered table-hover">
                <thead>
                  <tr>
                    <th>Username</th>
                    <th>Password</th>
                    <th>Status</th>
                  </tr>
                </thead>
                <tbody>
                  @if(count($member) == 0)
                  <tr>
                    <td colspan="3">No data</td>
                  </tr>
                  @else
                  @foreach($member as $m)
                  <tr data-widget="expandable-table" aria-expanded="false">
                    <td>{{ $m->username }}</td>
                    <td>{{ $m->password }}</td>
                    <td>
                      @if($m->mem_active == 1)
                      <span style="color: green;">Active</span>
                      @else
                      <span style="color: red;">Banned</span>
                      @endif
                    </td>
                  </tr>
                  <tr class="expandable-body d-none">
                    <td colspan="3">
                      <p>Name:&nbsp;&nbsp;&nbsp;{{ $m->name }}</p>
                      <p>Contact:&nbsp;&nbsp;&nbsp;{{ $m->phone }}</p>
                      <p>Address:&nbsp;&nbsp;&nbsp;{{ $m->address }}</p>
                      <p>Number of receipts:&nbsp;&nbsp;&nbsp;
                        <?php $i = 0; ?>
                        @if(count($nor) == 0)
                          {{ $i }}
                        @else
                          @foreach($nor as $n)
                            @if($n->mem_id == $m->mem_id)
                              {{ $n->nor }}
                              <?php $i += 1; ?>
                              @break
                            @endif
                          @endforeach
                          @if($i == 0)
                            {{ $i }}
                          @endif
                        @endif
                      </p>
                      <div>
                        @if($m->mem_active == 1)
                        <button class="btn btn-block btn-danger debtn" data-mid="{{ $m->mem_id }}">Ban</button>
                        @else
                        <button class="btn btn-block btn-success acbtn" data-mid="{{ $m->mem_id }}">Unban</button>
                        @endif
                      </div>
                    </td>
                  </tr>
                  @endforeach
                  @endif
                </tbody>
                <tfoot>
                  <tr>
                    <td colspan="3">
                      <div id="page-link">
                        @if(count($member) > 0)
                        <p id="linkmess">Showing {{ $member->firstItem() }} to {{ $member->lastItem() }} of {{ $count }} accounts</p>
                        @else
                        <p id="linkmess">Showing 0 to 0 of 0 accounts</p>
                        @endif
                        <div id="link">
                          {{ $member->links() }}
                        </div>
                      </div>
                    </td>
                  </tr>
                </tfoot>
              </table>
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

<script src="/admin/js/account/admin_member_acc.js"></script>

</body>
</html>
