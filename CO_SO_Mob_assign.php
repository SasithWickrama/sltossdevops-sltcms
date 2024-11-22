<?php
ini_set('max_execution_time', 300);
session_start();
if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true)
{    
    $user = $_SESSION["user"];
    $contractor_name = $_SESSION["contractor"];
	$area = $_SESSION["area"];
	$temp = explode('/',$area);
	$n = sizeof($temp);
    
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
  

  
  
  <!-- REQUIRED SCRIPTS -->
  
<!-- jQuery -->
<!-- PAGE SCRIPTS -->
<script src="assets/js/jquery-2.1.4.min.js"></script>
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
  
<script >
        
        $(document).ready(function(){
    $('#simple-table').DataTable();});
        
        </script>  
  
<script>
function search() {
    var val = document.getElementById("rt").value;
	self.location='CO_SO_List.php?rt=' + val ;
}
</script>   
</head>

<body class="hold-transition sidebar-mini layout-fixed layout-navbar-fixed layout-footer-fixed">
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
            <h4 class="m-0 text-dark">Mobile Assign Orders</h4>

        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content" style="font-size: 14px;">
    
    
        <div class="panel-body">
      
            <div class="row">

				<div class="col-lg-12">
					<div class="card">
						<div class="card-body">
							
							<form class="form-inline">
							
							  <div class="form-group mx-sm-3 mb-2">
							   
								<label for="inputPassword2">RTOM:&nbsp;</label>
							   
								<select style="width:150px" class="form-control" id="rtom">
									
									<option value="">All</option>
									
									<?php 

									   $area = con_area(); 

										while ($row = oci_fetch_array($area))
										{
											echo '<option value="'.$row[0].'">'.$row[0].'</option>';
										}

									?>
								
								</select>
								
							   </div>

							   <button type="button" class="btn btn-primary mb-2" onClick="loadOrdertblData();">Search</button>
							 
							</form>
							
							<div class="row">
							<div class="col-lg-8"  id="tblOrders">
							
							</div>
							
							<div class="col-lg-3">
							<table class="fixed_header table" id="assignOrders" style="font-size:13px; display:none">
							  <thead class="thead-light">
								<tr>
								  <th>Name</th>
								  <th>Number of Assign Orders</th>
								</tr>
							  </thead>
							  <tbody id="tblAssignOrders">

							 </tbody>
							</table>
							</div>
							
						  </div>
						  
						  <br>
						  
						  <form class="form-inline" id="divAssign" style="display:none;">
						  
						  <div class="row">
							
							  <div class="form-group mx-sm-3 mb-2">
							   
								<label for="inputPassword2">Assign User:&nbsp;</label>
							   
								<select style="width:150px" class="form-control" id="asn_user">
									
									<option value=""></option>
									
									<?php 

									   $sqlUsers = "SELECT DISTINCT UNAME FROM MOB_USERLIST WHERE UNAME NOT LIKE 'OSS%' AND  CONTRACTOR = '".$contractor_name."'";
									   
									    $oraconn = OracleConnection();
									   
										$stidu=oci_parse($oraconn,$sqlUsers);
										
										oci_execute($stidu);

										while ($rowu = oci_fetch_array($stidu))
										{
											echo '<option value="'.$rowu['UNAME'].'">'.$rowu['UNAME'].'</option>';
										}

									?>
								
								</select>
								
							   </div>
							   
								<button type="button" class="btn btn-success mb-2 float-right"  id="btnAssign">Assign</button>

						  </div>
						
						 </form>
						
						</div>
							
						</div>
					</div>
				</div>	  
  
      </div>
	
</div>
    
    

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


</body>

<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
<script src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.10.19/js/dataTables.bootstrap4.min.js"></script>
<script type="text/javascript" src="js/jquery.freezeheader.js"></script>

<script type="text/javascript">

$(document).ready(function() {
	
 // loadOrdertblData();
  
 $('body').addClass("open");
 
 $(document).on('click','#btnAssign',function(){
	 
  var Assgnuser = $('#asn_user').val();
   
  var table = document.getElementById('orders');

  var rowCount = $('#orders tr').length;
  
  var chkCount = 0;
  
  for(var n=1; n < rowCount; n++){

    if (table.rows[n].cells[4].childNodes[0].checked == true){
	
			chkCount++;
	}
	
  }
  
  if(chkCount == 0){
	  
	  swal({
          title: "",
          text: "check at least one record",
          icon: "warning",
          button: "Ok",
        });
	  
  }else if(Assgnuser == ''){
	  
	  swal({
          title: "",
          text: "select assign user",
          icon: "warning",
          button: "Ok",
        });
	  
	  
  }else{
  
  for(var n=1; n < rowCount; n++){

    if (table.rows[n].cells[4].childNodes[0].checked == true){

      var info =[];
      info[0] = table.rows[n].cells[0].innerHTML;
	  info[1] = table.rows[n].cells[1].innerHTML;
	  info[2] = table.rows[n].cells[2].innerHTML;
	  info[3] = table.rows[n].cells[3].innerHTML;
	  info[4] = $('#asn_user').val();

        $.ajax({
			
               type: "POST",
               data: {info:info},
               url: "./function.php?r=13",
               success: function(res){
				   
               swal("Order Assign Success");
			   
			   setTimeout(loadwindow, 1000);
                            
             }

            });
   
    } 

   }//end for loop
   
   }

});


$('#chkAll').click(function(event) {   
    if(this.checked) {
        $(':checkbox').each(function() {
            this.checked = true;                        
        });
    } else {
        $(':checkbox').each(function() {
            this.checked = false;                       
        });
    }
});


});

function loadwindow(){
	
	 window.location='CO_SO_Mob_assign.php'; 
	 
}

function loadOrdertblData(){


    var rtom = document.getElementById('rtom').value;
	
	$("#divAssign").css("display", "none");
	
	var tbl = $('#orders').DataTable();
	
	tbl.destroy();

    $.ajax({
           type: "POST",
           data: {rtom:rtom},
           url: "./function.php?r=11",
           success: function(res){
			
			$('#tblOrders').html('');
			
			$('#tblOrders').append(res);
					
			$('#orders').DataTable({
				
				"paging": false
			
			});
			
			if(res != ''){
				
			 $("#divAssign").css("display", "block");
			 
			}
			
			//$("#orders").freezeHeader({'height':'450px' });  
	
			loadAssignOrdertbl();	
		
           }
        });

}
	
function loadAssignOrdertbl(){

    var rtom = document.getElementById('rtom').value;

    $.ajax({
           type: "POST",
           data: {rtom:rtom},
           url: "./function.php?r=12",
           success: function(res){

			$('#tblAssignOrders').html('');
			
			$('#tblAssignOrders').append(res);
			
           }
        });
		
		$("#assignOrders").css("display", "block");

		
}

</script>
</html>
