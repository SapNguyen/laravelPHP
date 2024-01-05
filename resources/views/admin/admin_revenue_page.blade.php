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

  	<style>
		
		.ytotal, .mtotal, .dtotal {
			float: right;
			margin: 0 5px;
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
            <h1>Revenue</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item">Revenue</li>
            </ol>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">

    <div class="container-fluid" style="font-size: 20px;">

	<div class="row">
          <div class="col-12">
            <div class="card card-info">
              <div class="card-header">
                <h3 class="card-title">Revenue over year</h3>
              </div>
              <!-- ./card-header -->
              <div class="card-body p-0">

                <table class="table table-hover" id="revenueTable">
                  <tbody>
					@for($y = count($year) - 1; $y > -1; $y--)
					@if($y == count($year) - 1)
                    <tr data-widget="expandable-table" aria-expanded="true">
					@else
					<tr data-widget="expandable-table" aria-expanded="false">
					@endif
                      <td>
                        <i class="expandable-table-caret fas fa-caret-right fa-fw"></i>
                        {{ $year[$y]->year }}
						<span class="ytotal">Number of receipts: {{ $year[$y]->nor }}</span>
						<span class="ytotal">Total sale of year: {{ $year[$y]->value }} (VND)</span>
						
                      </td>
                    </tr>

                    <tr class="expandable-body">
                      <td>
                        <div class="p-0" style="display: none;">
                          <table class="table table-hover">
                            <tbody>
							@for ($i = 1; $i < 13; $i++)
								<?php $check = 0 ?>
								@foreach($month as $m)
								@if($m->month == $i && $m->year == $year[$y]->year)
								<tr data-widget="expandable-table" aria-expanded="false">
									<td style="background-color: #ccffcc;">
									<i class="expandable-table-caret fas fa-caret-right fa-fw"></i>
									@if($m->month == 1)
									January
									@elseif($m->month == 2)
									February
									@elseif($m->month == 3)
									March
									@elseif($m->month == 4)
									April
									@elseif($m->month == 5)
									May
									@elseif($m->month == 6)
									June
									@elseif($m->month == 7)
									July
									@elseif($m->month == 8)
									August
									@elseif($m->month == 9)
									September
									@elseif($m->month == 10)
									October
									@elseif($m->month == 11)
									November
									@elseif($m->month == 12)
									December
									@endif
									<span class="mtotal">Number of receipts: <i>{{ $m->nor }}<i></span>
									<span class="mtotal">Total sale of month: <i>{{ $m->value }}</i> (VND)</span>
									</td>
								</tr>
								<tr class="expandable-body">
									<td>
									<div class="p-0" style="display: none;">
										<table class="table table-hover">
										<tbody>
											@foreach($day as $d)
											@if($d->month == $m->month && $d->year == $year[$y]->year)
											<tr>
											<td style="background-color: #ffffcc;">
												<b>{{ $d->day.' / '.$m->month.' / '.$year[$y]->year }}</b>
												<span class="dtotal">Number of receipts: <b>{{ $d->nor }}</b></span>
												<span class="dtotal">Sum up of day: <b>{{ $d->value }}</b> (VND)</span>
											</td>
											</tr>
											@endif
											@endforeach
										</tbody>
										</table>
									</div>
									</td>
								</tr>
								<?php $check = 1; ?>
								@break
								@endif
								@endforeach
								@if($check == 0)
								<tr>
									<td style="background-color: #ffcccc;">
										<i class="expandable-table-caret fas fa-caret-down fa-fw"></i>
										@if($i == 1)
										January
										@elseif($i == 2)
										February
										@elseif($i == 3)
										March
										@elseif($i == 4)
										April
										@elseif($i == 5)
										May
										@elseif($i == 6)
										June
										@elseif($i == 7)
										July
										@elseif($i == 8)
										August
										@elseif($i == 9)
										September
										@elseif($i == 10)
										October
										@elseif($i == 11)
										November
										@elseif($i == 12)
										December
										@endif
										<span class="mtotal">Number of receipts: 0</span>
										<span class="mtotal">Total sale of month: 0 (VND)</span>
									</td>
								</tr>
								@endif
							@endfor
                            </tbody>
                          </table>
                        </div>
                      </td>
                    </tr>
                    @endfor
                  </tbody>
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
<script src="../../plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="../../plugins/bootstrap/js/bootstrap.bundle.min.js"></script>

<!-- AdminLTE App -->
<script src="../../dist/js/adminlte.min.js"></script>

<script>

console.log(document.getElementById('revenueTable').childNodes[1].childNodes[1])


</script>
</body>
</html>
