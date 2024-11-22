<?php
ini_set('max_execution_time', 300);
session_start();
if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true)
{    
    $user = $_SESSION["user"];
    $contractor_name = $_SESSION["contractor"];
	$area = $_SESSION["area"];
	$temp = explode('/',$area);
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
				opmc staff inbox
			</li>
			
			<li class="breadcrumb-item">
				<strong>SOD Assign</strong>
			</li>
			
		</ol>
	</div>

    <!-- Main content -->

      <div class="container-fluid">

		<div class="card">	
           
		    <div class="card-body">
		            
				<div class="ibox-title">
					<h5><b>SOD Assign</b></h5>
					<hr/>
				</div>
		   
				<div class="ibox-content">
					
					<div class="form-group  row">
						
						<div class="col-md-12">
                                <div style="width:100%;  overflow-x:auto; scrollbar-width: thin;">
								
								<table class="table table-striped table-bordered" id="orders" style="font-size:13px;">
                                  
								  <thead class="thead-light">
                                    
									<tr>
									  <th>VOICE NUMBER</th>
                                      <th>RTOM</th>
									  <th>LEA</th>
                                      <th>SO NUM</th>
									  <th>USER GROUP</th>
                                      <th>STATUS</th>
									  <th>SERVICE TYPE</th>
									  <th>OPMC</th>
									  <th></th>
									  <th></th>
									  <th></th>
                                    </tr>
									
                                  </thead>
								  
                                  <tbody id="tblOrders">
								  
								  <?php 
								  
									$sql = "SELECT DISTINCT * FROM OPMC_SOLIST WHERE STATUS IN ('0','1') ORDER BY VOICENUMBER,SO_NUM ASC";

										$oraconn = OracleConnection();
										$stid=oci_parse($oraconn,$sql);
										oci_execute($stid);
										
										$x=0;
							 
									while($row = oci_fetch_array($stid)){
										
										$x++;
									?>	
										<tr>
										<td><?php echo $row['VOICENUMBER'] ?></td>
										<td><?php echo $row['RTOM'] ?></td>
										<td><?php echo $row['LEA'] ?></td>
										<td><?php echo $row['SO_NUM'] ?></td>
										<td><?php if($row['STATUS'] == '0'){?><select class="form-control" id="cmb_group" style="font-size:13px;width:80px;">
									
										<option></option>
									
											<?php 
											
											$sql2="select * from OPMC_GROUP ORDER BY GROUP_NAME  ASC";
											
											$oraconn = OracleConnection();
											
											$stid2=oci_parse($oraconn,$sql2);
											
											oci_execute($stid2);

											while($row2 = oci_fetch_array($stid2)){

												echo '<option value="'.$row2['GROUP_NAME'].'">'.$row2['GROUP_NAME'].'</option>';
											
											}
											
											?>
								
										</select>
										
										<?php }else{ 
										
										echo $row['USER_GP'];
										
										}
										
										?>
										
										</td>
										
										<td>
										
										<?php 
										
										if($row['STATUS'] == '1'){
											
											echo '<font style="color:green;">INPROGRESS</font>';
											
										}else{
											
											echo '<font style="color:green;">ASSIGNED</font>';
										}
										
										?>
										</td>
										<td><?php echo $row['S_TYPE']?></td>
										<td><?php echo $row['OPMC']?></td>
										<td>
										<?php if($row['STATUS'] == '0'){?>
											
											<button class="btn btn-success btn-sm" style="width:100px;" onclick="inprogress(<?php echo $x; ?>)">INPROGRESS</button>
											
										<?php }elseif($row['STATUS'] == '1') {?>
										
											<button class="btn btn-warning btn-sm" style="width:100px; color:#ffffff" onclick="Complete(<?php echo $x; ?>);">COMPLETE</button>
										
										<?php } ?>
									
										</td>
										
										<td><?php if($row['STATUS'] == '1'){ ?>
										
											<button class="btn btn-danger btn-sm" style="width:100px;" onclick="del_validate(<?php echo $x; ?>);">CANCEL</button>
										
										<?php } ?>
										
										</td>
										
												
										<td><button class="btn btn-info btn-sm" style="width:100px;"  data-toggle="modal" onclick="view_data(<?php echo $x; ?>);">VIEW</button></td>
										
										</tr>
										
									<?php 
									
										}
										
									?>
									
								  </tbody>
								</table>
								
							</div> 
						
						</div>
						
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


<script type="text/javascript">
	
	$(document).ready(function() {
		
	  $('#orders').DataTable();

	});
	
	//-------------- validate input box --------------//
     var specialKeys = new Array();
     specialKeys.push(8);  //Backspace
     specialKeys.push(9);  //Tab
     specialKeys.push(46); //Delete
     specialKeys.push(36); //Home
     specialKeys.push(35); //End
     specialKeys.push(37); //Left
     specialKeys.push(39); //Right
 
     function IsAlphaNumeric(e) {
         var keyCode = e.keyCode == 0 ? e.charCode : e.keyCode;
         var ret = ((keyCode >= 48 && keyCode <= 57) || (keyCode >= 65 && keyCode <= 90) || keyCode == 32 || (keyCode >= 97 && keyCode <= 122) || (specialKeys.indexOf(e.keyCode) != -1 && e.charCode != e.keyCode));
         document.getElementById("error").style.display = ret ? "none" : "inline";
         return ret;
     }
	 
	 //--------- end validate input box ---------------//
	 
	 
	function saveData(){
		
		var group_name = document.getElementById('grp_name').value;
		
	     $.ajax({
         type: "POST",
         data: {group_name:group_name},
         url: "./function.php?r=22",
         success: function(res){
			
			if(res == 'true'){
				
            alert("Successfully Added Group");

            setInterval(location.reload(), 5000);
			
			}

         }
       });
	   
	 }
	 
	 
	function del_validate(x) {
	
	  var table = document.getElementById('orders');
	  var voice_No = table.rows[x].cells[0].innerHTML;
	  var rtom = table.rows[x].cells[1].innerHTML;
	  var so_no = table.rows[x].cells[3].innerHTML;
	  var stype = table.rows[x].cells[6].innerHTML;
	  
	  var r = confirm("Are you sure you want to cancel sod!");
	  
	  if (r == true) {
		  
		$.ajax({
         type: "POST",
         data: {voice_No:voice_No,so_no:so_no,rtom:rtom,stype:stype},
         url: "./function.php?r=25",
         success: function(res){
			
				if(res == 'true'){
					
						  
				alert("SOD Canceled Successfully");

				setInterval(location.reload(), 5000);
				
				}

			 }
		   });
			
	  } 

	}
	
	
	function inprogress(x) {
		
	  var table = document.getElementById('orders');
	  var group = table.rows[x].cells[4].childNodes[0].value;
	  var voice_No = table.rows[x].cells[0].innerHTML;
	  var rtom = table.rows[x].cells[1].innerHTML;
	  var so_no = table.rows[x].cells[3].innerHTML;
	  var stype = table.rows[x].cells[6].innerHTML;
	  
	  if(group == ''){
		  
		  alert('Group Required');
		  
		  return false;
		  
	  }else{
		  
		 $.ajax({
         type: "POST",
         data: {group:group,voice_No:voice_No,so_no:so_no,rtom:rtom,stype:stype},
         url: "./function.php?r=24",
         success: function(res){
			
				if(res == 'true'){
					
				alert("Inprogress Success");

				setInterval(location.reload(), 5000);
				
				}

			 }
		   });
	   
		  
	  }

	  
	}
	
	
	function Complete(x) {
	
	  var table = document.getElementById('orders');
	  var group = table.rows[x].cells[4].innerHTML;
	  var voice_No = table.rows[x].cells[0].innerHTML;
	  var rtom = table.rows[x].cells[1].innerHTML;
	  var so_no = table.rows[x].cells[3].innerHTML;
	  var stype = table.rows[x].cells[6].innerHTML;
	 
		$.ajax({
         type: "POST",
         data: {group:group,voice_No:voice_No,so_no:so_no,rtom:rtom,stype:stype},
         url: "./function.php?r=26",
         success: function(res){
			
				if(res == 'true'){
					
				alert("Order Complete Success");

				setInterval(location.reload(), 5000);
				
				}

			 }
		});
		   
	}
	
	function view_data(x) {
		
	  var table = document.getElementById('tbluser');
	  var emp_id = table.rows[x].cells[0].innerHTML;
	  var name = table.rows[x].cells[1].innerHTML;
	  var group = table.rows[x].cells[2].innerHTML;
	  var opmc = table.rows[x].cells[3].innerHTML;
	  var mobile_no = table.rows[x].cells[4].innerHTML;
	  
		$("#modal_body").html('	<div class="form-group  row">'+
								'<label class="col-sm-2 col-form-label" style="font-size:14px;">Service ID</label>'+
								'<div class="col-sm-4">'+
								'<input type="text" id="sid" class="form-control" value="'+emp_id+'" style="font-size:13px;">'+
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
