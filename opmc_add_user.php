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
	$opmc = $_SESSION["opmc"];
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

	<link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
	<link rel="stylesheet" href="plugins/overlayScrollbars/css/OverlayScrollbars.min.css">
	<link rel="stylesheet" href="dist/css/adminlte.min.css">
	<link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
	<link rel="stylesheet" href="assets/css/jquery.dataTables.min.css" /> 
	<script src="assets/js/jquery-2.1.4.min.js"></script>
	<script src="assets/js/jquery.dataTables.min.js"></script>
	<script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
	<script src="plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js"></script>
	<script src="dist/js/adminlte.js"></script>
	<script src="dist/js/demo.js"></script>
	<script src="plugins/jquery-mousewheel/jquery.mousewheel.js"></script>
	<script src="plugins/raphael/raphael.min.js"></script>
	<script src="plugins/jquery-mapael/jquery.mapael.min.js"></script>
	<script src="plugins/jquery-mapael/maps/world_countries.min.js"></script>
	<script src="plugins/chart.js/Chart.min.js"></script>
	<link rel="stylesheet" href="js/jsDatePick_ltr.min.css" />
	<script type="text/javascript" src="js/jsDatePick.min.1.3.js"></script>
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
	
	<link href="https://fonts.googleapis.com/css2?family=Nanum+Myeongjo&display=swap" rel="stylesheet"> 
	
    <script type="text/javascript" src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/1.10.21/js/dataTables.bootstrap4.min.js"></script>
	
	
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.3/css/bootstrap.css" />
    
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.21/css/dataTables.bootstrap4.min.css" />

	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
	
	<!-- pop box resource files -->

    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

 
</head>

<body class="hold-transition sidebar-mini layout-fixed layout-navbar-fixed layout-footer-fixed">
<div class="wrapper">

  <nav class="main-header navbar navbar-expand border-bottom navbar-dark navbar-primary">
  
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" data-widget="pushmenu" href="#"><i class="fas fa-bars"></i></a>
      </li>

    </ul>

    <ul class="navbar-nav ml-auto">

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

  <aside class="main-sidebar sidebar-dark-primary elevation-4">

    <a href="index" class="brand-link">
      <img src="dist/img/slt.jpg" alt="" class="brand-image img-circle elevation-3"
           style="opacity: .8">
      <span class="brand-text font-weight-light" style="font-size: 25px; font-weight: bolder;">SLTCMS</span>
    </a>

    <div class="sidebar">

        <?php include("navbar.php"); ?>

    </div>

  </aside>

  <div class="content-wrapper">
	
	<div class="col-lg-12">
		
		<h2></h2>
		
		<ol class="breadcrumb">
		
			<li class="breadcrumb-item">
				Home
			</li>
			
			<li class="breadcrumb-item">
				 OPMC Staff Inbox
			</li>
			
			<li class="breadcrumb-item">
				<strong>Add User</strong>
			</li>
			
		</ol>
	</div>

    <!-- Main content -->

      <div class="container-fluid">

		<div class="card">	
           
		    <div class="card-body">
		            
				<div class="ibox-title">
					<h5><b>Add User</b></h5>
					<hr/>
				</div>
		   
				<div class="ibox-content">
					
						<div class="form-group  row">
						
							<label class="col-sm-2 col-form-label" style="font-size:14px;">Service ID</label>
							
							<div class="col-sm-4">
								<input type="text" id="service_id" class="form-control" style="font-size:13px;">
							</div>
							
							<label class="col-sm-2 col-form-label" style="font-size:14px;">Name</label>
							
							<div class="col-sm-4"><input type="text" id="emp_name" class="form-control" style="font-size:13px;"></div>
							
						</div>
						
						<div class="form-group  row">
						
							<label class="col-sm-2 col-form-label" style="font-size:14px;">Group</label>
							
							<div class="col-sm-4">

								<select class="form-control" id="cmb_group" style="font-size:13px;">
									
									<option></option>
									
									<?php 
									
									$sql="select * from OPMC_GROUP WHERE GROUP_OPMC = '$opmc' ORDER BY GROUP_NAME  ASC";
									
									$oraconn = OracleConnection();
									
									$stid=oci_parse($oraconn,$sql);
									
									oci_execute($stid);

									while($row = oci_fetch_array($stid)){

										echo '<option value="'.$row['GROUP_NAME'].'">'.$row['GROUP_NAME'].'</option>';
									
									}
									
									?>
								
								</select>
							</div>
							
							<label class="col-sm-2 col-form-label" style="font-size:14px;">Mobile No</label>
							
							<div class="col-sm-4"><input type="text" class="form-control" id="mobile_no" onkeypress="return IsAlphaNumeric(event);" style="font-size:13px;"></div>
							
						</div>

						<div class="form-group row">
						
							<div class="col-md-2">&nbsp;</div>
							
							<div class="col-md-6">
							
								<button class="btn btn-primary btn-md col-md-4" onclick="saveData();" style="font-size:14px;"><i class="fa fa-save"></i>&nbsp;&nbsp;Save</button>
							
							</div>
							
						</div>
						
						<hr/>
						
						<div class="form-group row">
						
							<div class="col-md-12">
							
								<table id="tbluser" class="table table-striped table-bordered" style="width:100%;font-family: 'Nanum Myeongjo', serif;font-size:13px;">
									<thead>
									
									<tr>
										<td><b>Service ID</b></td>
										<td><b>Name</b></td>
										<td><b>Group</b></td>
										<td><b>OPMC</b></td>
										<td><b>Mobile No</b></td>
										<td><b>EMP Supervisor</b></td>
										<td></td>
									</tr>
									
									</thead>
									
									<tbody>
									<?php 
									
									$sql = "SELECT DISTINCT * FROM OPMC_USER WHERE EMP_OPMC = '$opmc' ORDER BY EMP_NUM ASC";

									$oraconn = OracleConnection();
									$stid=oci_parse($oraconn,$sql);
									oci_execute($stid);
									
									$x=0;
									
									while($row = oci_fetch_array($stid)){
										
										$x++;
										
									?>
									
									<tr>
										<td><?php echo $row['EMP_NUM']; ?></td>
										<td><?php echo $row['EMP_NAME']; ?></td>
										<td><?php echo $row['EMP_GROUP']; ?></td>
										<td><?php echo $row['EMP_OPMC']; ?></td>
										<td><?php echo $row['EMP_MOB']; ?></td>
										<td><?php echo $row['EMP_SUPERVISOR']; ?></td>
										<td><i class="fa fa-edit" style="font-size:20px;color:orange"  data-toggle="modal" onclick="editUser(this)"></i></td>
									</tr>
									
									<?php } ?>
									
									</tbody>
									
								</table>
							
							</div>
						
				</div>

			</div>	

        </div>

  </div>

  <!-- Control Sidebar -->
  <aside class="control-sidebar control-sidebar-dark">
  </aside>

  <!-- Main Footer -->
  <footer class="main-footer" style="text-align: center;">
    <strong>Copyright &copy; 2019  <span style="color: red;">IT Solution & Devops.</span> </strong>
    All rights reserved.
  </footer>
</div>
<!-- ./wrapper -->

<!-- modal -->
<!-- The Modal -->
<div class="modal fade" id="myModal">
    <div class="modal-dialog">
      <div class="modal-content" style="width:800px;">
      
        <!-- Modal Header -->
        <div class="modal-header">
          <p class="modal-title" style="font-family: 'Nanum Myeongjo', serif;"><b>Edit User</b></p>
          <button type="button" class="close" data-dismiss="modal">×</button>
        </div>
        
        <!-- Modal body -->
        <div class="modal-body" id="modal_body">

        </div>
        
        <!-- Modal footer -->
        <div class="modal-footer">
		  <button type="button" class="btn btn-primary btn-md col-md-2" onclick="updateuser();"><i class="fa fa-save"></i>&nbsp;&nbsp;Save</button>
          <button type="button" class="btn btn-danger btn-md col-md-2" data-dismiss="modal">Close</button>
        </div>
        
      </div>
	</div>
</div>

<script type="text/javascript">
	
	
	$(document).ready(function() {
		
	  $('#tbluser').DataTable({
		  
		  "paging": false
		  
	  });

	});
	
    function IsAlphaNumeric(e) {
        
		var keyCode = e.keyCode == 0 ? e.charCode : e.keyCode;
		
        if(keyCode > 31 && (keyCode < 48 || keyCode > 57)){
          return false;
		}
		
		 return true;
    }  
	 
	function saveData(){
		
		var service_id = document.getElementById('service_id').value;
		var emp_name = document.getElementById('emp_name').value;
		var cmb_group = document.getElementById('cmb_group').value;
		var mobile_no = document.getElementById('mobile_no').value;
		
		if(service_id == ''){
			alert('service ID Required');
			return false;
		}else if(cmb_group == ''){
			alert('User Group Required');	
			return false;
		}else{
			
			$.ajax({
			 type: "POST",
			 data: {service_id:service_id,emp_name:emp_name,cmb_group:cmb_group,mobile_no:mobile_no},
			 url: "./function.php?r=23",
			 success: function(res){
				
				if(res == 'true'){
					
				alert("User Added Successfully");

				setInterval(location.reload(), 5000);
				
				}

			 }
		    });
	   
		}
	   
	}
	
	
	function updateuser(){
		
		var service_id = document.getElementById('sid').value;
		var emp_name = document.getElementById('ename').value;
		var cmb_group = document.getElementById('emp_group').value;
		var mobile_no = document.getElementById('emp_mob_no').value;
		
		if(service_id == ''){
			alert('service ID Required');
			return false;
		}else if(cmb_group == ''){
			alert('User Group Required');	
			return false;
		}else{
			
		$.ajax({
			 type: "POST",
			 data: {service_id:service_id,emp_name:emp_name,cmb_group:cmb_group,mobile_no:mobile_no},
			 url: "./function.php?r=28",
			 success: function(res){
				
				if(res == 'true'){
					
				alert("User Data Updated Successfully");
				
				$('#myModal').modal('hide');
				
				setInterval(location.reload(), 500);
				
				}

			 }
		    });
			
		}
			
	}
	
	function editUser(element){
		
	  var table = document.getElementById('tbluser');
	  var x = element.parentNode.parentNode.rowIndex; 
	  var emp_id = table.rows[x].cells[0].innerHTML;
	  var name = table.rows[x].cells[1].innerHTML;
	  var group = table.rows[x].cells[2].innerHTML;
	  var opmc = table.rows[x].cells[3].innerHTML;
	  var mobile_no = table.rows[x].cells[4].innerHTML;
	  
		$("#modal_body").html('	<div class="form-group  row">'+
								'<label class="col-sm-2 col-form-label" style="font-size:14px;">Service ID</label>'+
								'<div class="col-sm-4">'+
								'<input type="text" id="sid" class="form-control" value="'+emp_id+'" style="font-size:13px;" readonly>'+
								'</div>'+
								'<label class="col-sm-2 col-form-label" style="font-size:14px;">Name</label>'+
								'<div class="col-sm-4"><input type="text" id="ename" class="form-control" value="'+name+'" style="font-size:13px;"></div>'+	
								'</div>'+
								'<div class="form-group  row">'+
								'<label class="col-sm-2 col-form-label" style="font-size:14px;">Group</label>'+
								'<div class="col-sm-4" id="divgroup">'+
								'</div>'+
								'<label class="col-sm-2 col-form-label" style="font-size:14px;">Mobile No</label>'+
								'<div class="col-sm-4"><input type="text" id="emp_mob_no" class="form-control" value="'+mobile_no+'" style="font-size:13px;"></div>'+	
								'</div>');
								
					$.ajax({
					 type: "POST",
					 data:{group:group},
					 url: "./function.php?r=27",
					 success: function(res){

							document.getElementById("divgroup").innerHTML = res;

					 }
					});				
			
		
		$("#myModal").modal();

	}
	 
</script>

</body>
</html>
