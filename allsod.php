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
    echo '<script type="text/javascript"> document.location = "Login.php";</script>'; 
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
<script src="plugins/chart.js/Chart.min.js"></script>

<script type="text/javascript">
        
        $(document).ready(function(){
    $('#simple-table').DataTable();});
        
        </script> 

</head>
<body class="hold-transition sidebar-mini layout-fixed layout-navbar-fixed layout-footer-fixed" onload="loadOrdertblData();">
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
            <h4 class="m-0 text-dark">Service Order Inbox</h4>

        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content" style="font-size: 14px;">
      <div class="container-fluid">
<hr />
		
		<!--  ///////////////////////////////////////////    -->
		
		    <div class="row">

                    <div class="col-lg-12">
                        <div class="card">
                            <div class="card-body">
								
								<form method="POST" action="csvdownload.php" class="form-inline">
								
                                  <div class="form-group mx-sm-3 mb-2">
                                   
								    <label for="inputPassword2">LEA:&nbsp;</label>
								   
                                    <select style="width:150px" class="form-control" id="lea">
                                        
										<option value=""></option>
									
										
                                        <?php 

											$sql1 = "SELECT AR.AREA_CODE FROM CLARITY.AREAS AR WHERE AR.AREA_AREA_CODE IN ($areas)";
											$oraconn = OracleConnection();
											$stid1=oci_parse($oraconn,$sql1);
											oci_execute($stid1);
 
                                            while ($row1 = oci_fetch_array($stid1))
                                            {
                                                echo '<option value="'.$row1['AREA_CODE'].'">'.$row1['AREA_CODE'].'</option>';
                                            }

                                        ?>
                                    
									</select>
									
                                   </div>
								   
								   <div class="form-group mx-sm-3 mb-2">
                                   
								    <label for="inputPassword2">Service Type:&nbsp;</label>
								  
                                    <select style="width:150px" class="form-control" id="service_type">
                                        
										<option value=""></option>
										<option value="AB-CAB">AB-CAB</option>
										<option value="AB-FTTH">AB-FTTH</option>
										<option value="E-IPTV COPPER">E-IPTV COPPER</option>
										<option value="E-IPTV FTTH">E-IPTV FTTH</option>

									</select>
									
                                   </div>

								   <button type="button" class="btn btn-primary mb-2" onClick="loadOrdertblData();">Search</button>
								   
								   <div class="form-group mx-sm-3 mb-2">
								   
								   <button type="Submit" name="submitData1" id="submitData1" style="float:right; display:none"><img src="img/excel.png" style=" width:50px; height:50px" ></button>
									
									</div>

                                </form>
								
								<div class="row">
								<div class="col-lg-12">
									 <div style="width:100%; height:500px; overflow:auto">
									<table class="table" id="orders" style="font-size:13px;">
									  <thead class="thead-light">
										<tr>
										  <th>RTOM</th>
										  <th>LEA</th>
										  <th>SO NUM</th>
										  <th>SERVICE TYPE</th>
										  <th>RECIVE DATE</th>
										  <th>VOICE NUMBER</th>
										  <th>WO TYPE</th>
										  <th>CUSTOMER NAME</th>
										  <th>CUSTOMER CONTACT</th>
										  <th>ADDRESS</th>
										  <th>DP NAME</th>
										  <th style="display:none">DP LOOP</th>
										  <th style="display:none">PHONE CLASS</th>
										  <th style="display:none">PHONE COLOUR</th>
										  <th style="display:none">PHN PURCH</th>
										  <th>WORO DISCRIPTION</th>
										  <th>TASK NAME</th>
										  <th>CONTRATOR</th>
										  <th>IPTV</th>
										</tr>
									  </thead>
									  <tbody id="tblOrders">
									  </tbody>
									</table>
									
								  </div>
								</div>
                              </div>
							
							</div>
                                
                            </div>
                        </div>
                    </div>
		
		
		<!-- //////////////////////////////////////////// -->
		
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

function changeAssgnOrder(element){
	
	var table = document.getElementById('orders');
	
	var row = element.parentNode.parentNode.rowIndex;
	
	  var info =[];
      info[0] = table.rows[row].cells[0].innerHTML;
	  info[1] = table.rows[row].cells[4].childNodes[0].value;
	  info[2] = table.rows[row].cells[5].innerHTML;
	  info[3] = table.rows[row].cells[6].innerHTML;

        $.ajax({
			
               type: "POST",
               data: {info:info},
               url: "./function.php?r=5",
               success: function(res){
				   
               swal("Order Assign Success");
			   
			   setTimeout(loadwindow, 1000);
                            
             }

            });
	
}

function loadwindow(){
	
	 window.location='SLT_change_work_order.php'; 
	 
}

function loadOrdertblData(){
	
	var lea = document.getElementById('lea').value;
	var stype = document.getElementById('service_type').value;
	
	$('#submitData1').css("display", "none");
	
	var tbl = $('#orders').DataTable();
	
	tbl.destroy();
	
    $.ajax({
           type: "POST",
           data: {lea:lea,stype:stype},
           url: "./function.php?r=4",
           success: function(res){

			if(res != ''){
					
				$('#submitData1').css("display", "block");
			 
			} 
			
			$('#tblOrders').html('');
			
			$('#tblOrders').append(res);
					
			$('#orders').DataTable({
				
				"paging": false
			
			}); 
		
           }
        });

}

/*function loadPoup(btn) 
{
	var soid = btn.id;
	
	$("#frm_body").html('')
	
	$.ajax({
           type: "POST",
           data: {soid:soid},
           url: "./function.php?r=16",
           success: function(res){
			
			$('#frm_body').append(res);
			
			$("#myModal").modal('show');
			
           }
    });

}*/

</script>

</body>
</html>
