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
	#topsale th, #topsale td{
		vertical-align: middle;
		font-size: 20px;
	}
	.rankingbg {
		width: 50px;
		height: 50px;
		border-radius: 25px;
		vertical-align: middle;
		font-size: 32px;
		text-align: center;
		padding: 8px 0;
	}
	.toppimg {
		max-width: 80px;
		max-height: 80px;
		border: 1px solid black;
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
            <h1>Home</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item">Home</li>
            </ol>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">

    <div class="container-fluid">
        <div class="row">
          <div class="col-md-6">

            <!-- DONUT CHART -->
            <div class="card card-danger">
              <div class="card-header">
                <h3 class="card-title">Trending</h3>

                <div class="card-tools">
                  <button type="button" class="btn btn-tool" data-card-widget="collapse">
                    <i class="fas fa-minus"></i>
                  </button>
                  <button type="button" class="btn btn-tool" data-card-widget="remove">
                    <i class="fas fa-times"></i>
                  </button>
                </div>
              </div>
              <div class="card-body"><div class="chartjs-size-monitor"><div class="chartjs-size-monitor-expand"><div class=""></div></div><div class="chartjs-size-monitor-shrink"><div class=""></div></div></div>
                <canvas id="donutChart" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%; display: block; width: 572px;" width="715" height="312" class="chartjs-render-monitor"></canvas>
              </div>
              <!-- /.card-body -->
            </div>
            <!-- /.card -->

            

          </div>
          <!-- /.col (LEFT) -->
          <div class="col-md-6">
            
			<!-- BAR CHART -->
			<div class="card card-warning">
              <div class="card-header">
                <h3 class="card-title">Today sales</h3>

                <div class="card-tools">
                  <button type="button" class="btn btn-tool" data-card-widget="collapse">
                    <i class="fas fa-minus"></i>
                  </button>
                  <button type="button" class="btn btn-tool" data-card-widget="remove">
                    <i class="fas fa-times"></i>
                  </button>
                </div>
              </div>
              <div class="card-body">
                <div class="chart">
                  <canvas id="barChart" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                </div>
              </div>
              <!-- /.card-body -->
            </div>
            <!-- /.card -->
            
            <div class="card card-primary">
              <div class="card-header">
                <h3 class="card-title">Top 10 most bought of month</h3>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                <table class="table table-bordered table-sm" id="topsale">
                  <thead>
                    <tr>
                      <th style="width: 50px;">Rank</th>
                      <th colspan="2">Product</th>
                      <th>Sold quantity</th>
                    </tr>
                  </thead>
                  <tbody>
					@for($i = 0; $i < count($topsale); $i++)
                    <tr>
                      	<td>
						  <span class="badge bg-light rankingbg">{{ $i + 1 }}</span>
					  	</td>
						<td>
							@if($topsale[$i]->product_image == 'No_image_2.png')
							<img src="/img/No_image_2.png" class="toppimg">
							@else
							<img src="/img/product/{{ $topsale[$i]->product_id }}/{{ $topsale[$i]->product_image }}" class="toppimg">
							@endif
						</td>
                      	<td>
							<a href="{{ route('a.p.view', ['pid'=>$topsale[$i]->product_id]) }}">
								{{ $topsale[$i]->product_name }}
							</a>
						</td>
                      	<td>
							{{ $topsale[$i]->totalq }}
						</td>
                    </tr>
					@endfor
                  </tbody>
                </table>
              </div>
              <!-- /.card-body -->
            </div>
            

          </div>
          <!-- /.col (RIGHT) -->
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
<script src="../../plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="../../plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- ChartJS -->
<script src="../../plugins/chart.js/Chart.min.js"></script>
<!-- AdminLTE App -->
<script src="../../dist/js/adminlte.min.js"></script>
<!-- Page specific script -->
<script>
  $(function () {
    /* ChartJS
     * -------
     * Here we will create a few charts using ChartJS
     */

    //--------------
    //- AREA CHART -
    //--------------

    // Get context with jQuery - using jQuery's .get() method.

    //-------------
    //- DONUT CHART -
    //-------------
    // Get context with jQuery - using jQuery's .get() method.
    var donutChartCanvas = $('#donutChart').get(0).getContext('2d')

    var donutData		 = {
      labels: [<?php echo($trendName) ?>],
      datasets: [
        {
          	data: [<?php echo($trendSale) ?>],
          	backgroundColor : [
              '#ff5050', '#ffff00', '#33cc33', '#33cccc', '#3366ff', '#ff00ff'
            ],
        }
      ]
    }
    var donutOptions     = {
      maintainAspectRatio : false,
      responsive : true,
    }
    //Create pie or douhnut chart
    // You can switch between pie and douhnut using the method below.
    new Chart(donutChartCanvas, {
      type: 'doughnut',
      data: donutData,
      options: donutOptions
    })

	//-------------
    //- BAR CHART -
    //-------------
    var barChartCanvas = $('#barChart').get(0).getContext('2d')

	var barChartData = {
      labels  : [<?php echo $saleBrand ?>],
      datasets: [
        {
          label               : 'Sold quantity',
          backgroundColor     : '#009933',
          borderColor         : 'rgba(60,141,188,0.8)',
          pointRadius          : false,
          pointColor          : '#3b8bba',
          pointStrokeColor    : 'rgba(60,141,188,1)',
          pointHighlightFill  : '#fff',
          pointHighlightStroke: 'rgba(60,141,188,1)',
          data                : [<?php echo $saleQuan ?>]
        },
      ]
    }

    var barChartOptions = {
      responsive              : true,
      maintainAspectRatio     : false,
      datasetFill             : false,
      scales: {
        yAxes: [{
            ticks: {
                beginAtZero: true,
                callback: function (value) { if (Number.isInteger(value)) { return value; } },
            }
        }]
      }
    }

    new Chart(barChartCanvas, {
      type: 'bar',
      data: barChartData,
      beginAtZero: true,
      options: barChartOptions
    })
    
  })

var rank = document.getElementsByClassName('badge');
for(var i = 0; i < rank.length; i++){
	if(i == 0){
		rank[i].setAttribute('class', 'badge bg-danger rankingbg');
	}
	else if(i == 1){
		rank[i].setAttribute('class', 'badge bg-success rankingbg');
	}
	else if(i == 2){
		rank[i].setAttribute('class', 'badge bg-info rankingbg');
	}
	else if(i == 3 || i == 4){
		rank[i].setAttribute('class', 'badge bg-waring rankingbg');
	}
}

</script>
</body>
</html>
