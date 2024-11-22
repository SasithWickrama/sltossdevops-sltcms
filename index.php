<?php
ini_set('max_execution_time', 300);
session_start();
if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true)
{    
    $user = $_SESSION["user"];
    $contractor_name = $_SESSION["contractor"];
	$area = $_SESSION["area"];
	
	$areasArr = array();

	$areaArr = explode('/',$area);

	for($i=0; $i<sizeof($areaArr); $i++){
		
		$areasArr[$i]= "'".$areaArr[$i]."'";
		
	}
	
	$areas = implode(",",$areasArr);
    
    $refno=date("ymdHis");
}
else 
{     
    echo '<script type="text/javascript"> document.location = "login.php";</script>'; 
}
include "db.php";

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta http-equiv="x-ua-compatible" content="ie=edge">

  <title>SLTCMS</title>

  <!-- Font Awesome Icons -->
  <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
  <!-- overlayScrollbars -->
  <link rel="stylesheet" href="plugins/overlayScrollbars/css/OverlayScrollbars.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="dist/css/adminlte.min.css">
  <!-- Google Font: Source Sans Pro -->
  <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
  
  <link rel="stylesheet" href="assets/css/jquery.dataTables.min.css" />
  
  <script type="text/javascript" src="js/jquery.freezeheader.js"></script>

  <link href="https://fonts.googleapis.com/css?family=Baskervville&display=swap" rel="stylesheet"> 
	
	<style>
	
	.fixed_header{
    width: 100%;
    table-layout: fixed;
    border-collapse: collapse;
	}

	.fixed_header tbody{
	  display:block;
	  width: 100%;
	  overflow: auto;
	  height: 400px;
	}

	.fixed_header thead tr {
	   display: block;
	}

	.fixed_header thead {
	  background: black;
	  color:#fff;
	}

	<!--.fixed_header th, .fixed_header td {
	  padding: 5px;
	  text-align: center;
	  width: 200px;
	}-->
	
	</style>
  
  <!-- REQUIRED SCRIPTS -->
  
<!-- jQuery -->
<!-- PAGE SCRIPTS -->
<script src="assets/js/jquery-2.1.4.min.js"></script>
<script src="assets/js/jquery.dataTables.min.js"></script>
<!-- Bootstrap -->
<script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- overlayScrollbars -->
<script src="plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js"></script>
<!-- AdminLTE App -->
<script src="dist/js/adminlte.js"></script>

<!-- OPTIONAL SCRIPTS -->
<script src="dist/js/demo.js"></script>

<!-- PAGE PLUGINS -->
<!-- jQuery Mapael -->
<script src="plugins/jquery-mousewheel/jquery.mousewheel.js"></script>
<script src="plugins/raphael/raphael.min.js"></script>
<script src="plugins/jquery-mapael/jquery.mapael.min.js"></script>
<script src="plugins/jquery-mapael/maps/world_countries.min.js"></script>
<!-- ChartJS -->
<script src="https://cdn.jsdelivr.net/npm/chart.js@2.8.0"></script>

<script type="text/javascript">
        
        $(document).ready(function(){
    $('#simple-table').DataTable();});
        
        </script> 

</head>
<body class="hold-transition sidebar-mini layout-fixed layout-navbar-fixed layout-footer-fixed" onload="createChart();">
<div class="wrapper">
  <!-- Navbar -->
  <nav class="main-header navbar navbar-expand border-bottom navbar-dark navbar-primary">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" data-widget="pushmenu" href="#"><i class="fas fa-bars"></i></a>
      </li>

    </ul>



    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">
      <!-- Messages Dropdown Menu -->
      
      <!-- Notifications Dropdown Menu -->

        <li class="nav-item dropdown">
							<a data-toggle="dropdown" href="#" class="dropdown-toggle" style="color:white">
							<img class="nav-user-photo" src="dist/img/user.png" style="border-radius: 50%;" alt="" />
								<span class="user-info" style="color:white">
									<small>Welcome,
									<?php echo $_SESSION['user']; ?>
									</small>
								</span>
							</a>

							<ul class="user-menu dropdown-menu-right dropdown-menu dropdown-yellow dropdown-caret dropdown-close">
								<li class="divider"></li>
								<li>
									<a href="logout.php">
										<i class="ace-icon fa fa-power-off"></i>
										Logout
									</a>
								</li>
							</ul>
		</li>
    </ul>
  </nav>
  <!-- /.navbar -->

  <!-- Main Sidebar Container -->
  <aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="index" class="brand-link">
      <img src="dist/img/slt.jpg" alt="" class="brand-image img-circle elevation-3"
           style="opacity: .8">
      <span class="brand-text font-weight-light" style="font-size: 25px; font-weight: bolder;">SLTCMS</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">

      <!-- Sidebar Menu -->
        <?php include("navbar.php"); ?>
      <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
  </aside>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h4 class="m-0 text-dark">Dashboard</h4>

        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content" style="font-size: 14px;">
      
	<div class="container-fluid">
	
	<hr />

	<div class="card">		
		<div class="form-group row">
	

		<div class="col-sm-6">
		  <canvas  height="200" id="pieChart1" class="col-md-12"></canvas>
		</div>
		
		<div class="col-sm-6">
		  <canvas height="200" id="pieChart2" class="col-md-12"></canvas>
		</div>
	
		</div>
		
		<div class="form-group row">

		
		 <div class="col-sm-6">
		  <canvas height="200" id="pieChart3" class="col-md-12"></canvas>
		</div>
		
		 <div class="col-sm-6">
		  <canvas height="200" id="pieChart4" class="col-md-12"></canvas>
		</div>
	
	
		</div>
		
		
	</div>
	
	<div class="card">	
	
	<div class="form-group row">
	

		<div class="col-sm-12">
		
		  <canvas  height="400" id="barChart1" class="col-md-10" style="margin-left:8%"></canvas>
		  
		</div>
	
	
	</div>
	
	</div>	
		
      </div><!--/. container-fluid -->
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->

  <!-- Control Sidebar -->
  <aside class="control-sidebar control-sidebar-dark">
    <!-- Control sidebar content goes here -->
  </aside>
  <!-- /.control-sidebar -->

  <!-- Main Footer -->
  <footer class="main-footer" style="text-align: center;">
    <strong>Copyright &copy; 2019  <span style="color: red;">IT Solution & Devops.</span> </strong>
    All rights reserved.
  </footer>
</div>
<!-- ./wrapper -->

	<!-- popup box -->
	<div id="myModal" class="modal fade">
		<div class="modal-dialog" style="width: 500px;">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
					<h4 style="font-family:Baskervville, serif;" class="modal-title"><b>Service Order Detail</b></h4>
				</div>
				<div class="modal-body" id="frm_body">

				</div>
			</div>
		</div>
	</div>
	<!-- end popup box -->

  <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
  <script src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
  <script src="https://cdn.datatables.net/1.10.19/js/dataTables.bootstrap4.min.js"></script>
  
<script type="text/javascript">
	
function createChart(){
	
$.ajax({
   type: "POST",
   url: "./function.php?r=17",
   success: function(res){
	
	if(res != ''){
		
		loadchart1(res);
		
	}
	
   }
});
	
function loadchart1(res){
	
	var ctx = document.getElementById('pieChart1').getContext('2d');
	
	var dataArr1 = new Array();

	var resArr1 = res.split(',');

	for(var i=0; i<resArr1.length; i++){

	  dataArr1.push(resArr1[i]);

	}

	var chart = new Chart(ctx, {
    type: 'pie',
    data: {
        labels: ['Pending', 'Assign', 'Return'],
        datasets: [{
            //label: 'My First dataset',
            backgroundColor:['#48c9b0',' #16a085','#27ae60'],
           //borderColor: 'rgb(255, 99, 132)',
            data: dataArr1
			//data:[25,42,32]
        }]
    },
    options: {
		
		title:{display:true,text:'AB-CAB'}
		
		}
	});

}


$.ajax({
   type: "POST",
   url: "./function.php?r=18",
   success: function(res){
	
	if(res != ''){
		
		loadchart2(res);
	}
	
   }
});
	
function loadchart2(res){
	
	var ctx = document.getElementById('pieChart2').getContext('2d');
	
	var dataArr2 = new Array();

	var resArr2 = res.split(',');

	for(var i=0; i<resArr2.length; i++){

	  dataArr2.push(resArr2[i]);

	}

	var chart = new Chart(ctx, {
    type: 'pie',
    data: {
        labels: ['Pending', 'Assign', 'Return'],
        datasets: [{
            //label: 'My First dataset',
            backgroundColor:['#34495e',' #f9e79f ',' #fadbd8 '],
           //borderColor: 'rgb(255, 99, 132)',
            data: dataArr2
			//data:[25,42,32]
        }]
    },
    options: {
		
		title:{display:true,text:'E-IPTV COPPER'}
		
		}
	});

}


$.ajax({
   type: "POST",
   url: "./function.php?r=19",
   success: function(res){
	
	if(res != ''){
		
		loadchart3(res);
	
	}
	
   }
});
	
function loadchart3(res){
	
	var ctx = document.getElementById('pieChart3').getContext('2d');
	
	var dataArr3 = new Array();

	var resArr3 = res.split(',');

	for(var i=0; i<resArr3.length; i++){

	  dataArr3.push(resArr3[i]);

	}

	var chart = new Chart(ctx, {
    type: 'pie',
    data: {
        labels: ['Pending', 'Assign', 'Return'],
        datasets: [{
            //label: 'My First dataset',
              backgroundColor:[' #aed6f1 ','#151eaa','#cacfd2'],
           //borderColor: 'rgb(255, 99, 132)',
           data: dataArr3
		  //data:[35,40,50]
        }]
    },
    options: {
		
		title:{display:true,text:'E-IPTV FTTH'}
		
		}
	});

}


$.ajax({
   type: "POST",
   url: "./function.php?r=20",
   success: function(res){
	
	if(res != ''){
		
		loadchart4(res);
	
	}
	
   }
});
	
function loadchart4(res){
	
	var ctx = document.getElementById('pieChart4').getContext('2d');
	
	var dataArr4 = new Array();

	var resArr4 = res.split(',');

	for(var i=0; i<resArr4.length; i++){

	  dataArr4.push(resArr4[i]);

	}

	var chart = new Chart(ctx, {
    type: 'pie',
    data: {
        labels: ['Pending', 'Assign', 'Return'],
        datasets: [{
            //label: 'My First dataset',
            backgroundColor:['#f1948a','#a569bd','#f8c471'],
           //borderColor: 'rgb(255, 99, 132)',
            data: dataArr4
			//data:[25,60,50]
        }]
    },
    options: {
		
		title:{display:true,text:'AB-FTTH'}
		
		}
	});

}


$.ajax({
   type: "POST",
   url: "./function.php?r=21",
   success: function(res){
	
	if(res != ''){
		
		loadchart5(res);
		
	}
	
   }
});

function loadchart5(res){
	
	var dataArr5 = new Array();

	var resArr5 = res.split(',');

	for(var i=0; i<resArr5.length; i++){

	  dataArr5.push(resArr5[i]);

	}

	
	new Chart(document.getElementById("barChart1"), {
		type: 'bar',
		data: {
		  labels: ["QC Pass", "RTOM", "To be invoice"],
		  datasets: [
			{
			  label: "Population (millions)",
			  backgroundColor: ["#58d68d", " #a569bd","#f1c40f"],
			  //data: [30,15,25]
			  data:dataArr5
			}
		  ]
		},
		options: {
		  legend: { display: false },
		  title: {
			display: true,
			text: 'QC Data'
		  },
		  scales: {
		  xAxes: [{
			//stacked: true,
			barPercentage: 0.3
		  }],
		  yAxes: [{
			//stacked: true
			barPercentage: 0.3
		  }]
		}
		}
	});

}


}

</script>	

</body>
</html>
