<?php
ini_set('max_execution_time', 300);
session_start();
if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true)
{    
    $user = $_SESSION["user"];
    $contractor_name = $_SESSION["contractor"];
	$area = $_SESSION["area"];
	$temp = explode('/',$area);
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
									  <th style="display:none"></th>
                                    </tr>
									
                                  </thead>
								  
                                  <tbody id="tblOrders">
								  
								  <?php 
								  
									$sql = "SELECT DISTINCT * FROM OPMC_SOLIST WHERE STATUS IN ('0','1') AND OPMC = '$opmc' ORDER BY VOICENUMBER,SO_NUM ASC";

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
										
										<td><?php if($row['STATUS'] == '0'){?><select style="background-color:green;color:#ffffff;width:130px;" id="<?php echo 'st'.$x?>" class="form-control" onchange="inprogress(this)"">
												<option style="background-color:green;" value="0" selected>ASSINGED</option>
												<option style="background-color:orange;" value="1">INPROGRESS</option>
												<option style="background-color: #712c1e;" value="4">RETURN</option>
											</select><?php }elseif($row['STATUS'] == '1') {?><select style="background-color:orange;color:#ffffff;width:130px;" id="<?php echo 'st'.$x?>" class="form-control" onchange="Complete(this)"">
												<option style="background-color:orange;" value="1" selected>INPROGRESS</option>
												<option style="background-color: green;" value="2">COMPLETED</option>
												<option style="background-color: #712c1e;" value="4">RETURN</option>
												<option style="background-color:red;" value="3">CANCEL</option>
											</select>
											
										<?php } ?>
									
										</td>

										<td><?php echo $row['S_TYPE']?></td>
										<td><?php echo $row['OPMC']?></td>		
										<td><button class="btn btn-info btn-sm" style="width:100px;"  data-toggle="modal" onclick="view_data(this);">VIEW</button></td>
										<td style="display:none"><?php echo $row['TASK_NAME']; ?></td>
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

<!-- The Modal -->
<div class="modal fade" id="myModal">
<div class="modal-dialog">
  <div class="modal-content" style="width:800px;">
  
	<!-- Modal Header -->
	<div class="modal-header">
	  <p class="modal-title" style="font-family: 'Nanum Myeongjo', serif;" id="modal_ttl"></p>
	  <button type="button" class="close" data-dismiss="modal">×</button>
	</div>
	
	<!-- Modal body -->
	<div class="modal-body" id="modal_body">

	</div>
	
  </div>
</div>
</div>

<script type="text/javascript">
	
	$(document).ready(function() {
		
	  $('#orders').DataTable({
		  
		  "paging": false
		  
	  });

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
	 
	 
	function inprogress(element) {
			
	  var table = document.getElementById('orders');
	  var x = element.parentNode.parentNode.rowIndex;  
	  var group = table.rows[x].cells[4].childNodes[0].value;
	  var voice_No = table.rows[x].cells[0].innerHTML;
	  var rtom = table.rows[x].cells[1].innerHTML;
	  var so_no = table.rows[x].cells[3].innerHTML;
	  var stype = table.rows[x].cells[6].innerHTML;
	  var status = table.rows[x].cells[5].childNodes[0].value;
	  var taskName = table.rows[x].cells[9].innerHTML;
		
	  if(status == '4'){
			
			$("#modal_ttl").html('<b>Return SOD</b>');
			
			$("#modal_body").html('<div class="form-group  row">'+
								'<label class="col-sm-2 col-form-label" style="font-size:14px;">Return Reason</label>'+
								'<div class="col-sm-7">'+
								'<input type="text" id="so_no" value="'+so_no+'" style="display:none">'+
								'<input type="text" id="voice_no" value="'+voice_No+'" style="display:none">'+
								'<input type="text" id="group" value="'+group+'" style="display:none">'+
								'<input type="text" id="taskName" value="'+taskName+'" style="display:none">'+
								'<select name="re_reasons" id="re_reasons" class="form-control">'+
								'<option value=""></option>'+
								'<option value="CUSTOMER NOT READY\">CUSTOMER NOT READY</option>'+
								'<option value="FAULTY OSP NW\">FAULTY OSP NW</option>'+
								'<option value="NO OSP NW/PRIMARY/SECONDARY\">NO OSP NW/PRIMARY/SECONDARY</option>'+
								'<option value="NO PORTS\">NO PORTS</option>'+
								'<option value="OVER DISTANCE\">OVER DISTANCE</option>'+
								'<option value="THIRD PARTY OBSTRUCTIONS\">THIRD PARTY OBSTRUCTIONS</option>'+
								'<option value="OSS DATA ERROR\">OSS DATA ERROR</option>'+ 
								'<option value="POLE ERECTION PROBLEM\">POLE ERECTION PROBLEM</option>'+
								'<option value="REFUSED BY CUSTOMER\">REFUSED BY CUSTOMER</option>'+
								'<option value="CANCELLED BY SLT\">CANCELLED BY SLT</option>'+
								'<option value="CANNOT CONTACT CUSTOMER\">CANNOT CONTACT CUSTOMER</option>'+
								'<option value="LOW SNR\">LOW SNR</option>'+
								'<option value="LINE NOT READY\">LINE NOT READY</option>'+
								'<option value="POLE COUNTS EXCEEDS THAN ALLOWABLE\">POLE COUNTS EXCEEDS THAN ALLOWABLE</option>'+
								'<option value="POLE COUNT EXCEEDS AND OVER DISTANCE\">POLE COUNT EXCEEDS AND OVER DISTANCE</option>'+
								'<option value="OTHER">OTHER</option>'+
								'</select>'+
								'</div>'+
								'<div class="form-group  row">'+
								'<label class="col-sm-2 col-form-label" style="font-size:14px;">&nbsp;</label>'+
								'<div class="col-sm-4">'+
								'<button type="button" class="btn btn-danger btn-md " onclick="returnSod();"><i class="fa fa-refresh"></i>&nbsp;&nbsp;Return</button>'+
								'</div>');

			$("#myModal").modal();
			
			$("#st"+x).val(0);
			 
	  }else{
	  
		  if(group == ''){
			  
			  alert('Group Required');
			  
			  $("#st"+x).val(0);
			  
			  return false;
			  
		  }else{
			  
			  	var r = confirm("Are you sure you want to Inprogress sod!");
	  
				if (r == true) {
		  
				$.ajax({
				 type: "POST",
				 data: {group:group,voice_No:voice_No,so_no:so_no,rtom:rtom,stype:stype,taskName:taskName},
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
		  
	  }

	}
	
	
	function Complete(element) {
	
	  var table = document.getElementById('orders');
	  var x = element.parentNode.parentNode.rowIndex;
	  var group = table.rows[x].cells[4].innerHTML;
	  var voice_No = table.rows[x].cells[0].innerHTML;
	  var rtom = table.rows[x].cells[1].innerHTML;
	  var so_no = table.rows[x].cells[3].innerHTML;
	  var stype = table.rows[x].cells[6].innerHTML;
	  var status = table.rows[x].cells[5].childNodes[0].value;
	  var taskName = table.rows[x].cells[9].innerHTML;

	  if(status == '3'){
		  
		var r = confirm("Are you sure you want to cancel sod!");
	  
		  if (r == true) {
			  
			$.ajax({
			 type: "POST",
			 data: {voice_No:voice_No,so_no:so_no,rtom:rtom,stype:stype,group:group,taskName:taskName},
			 url: "./function.php?r=25",
			 success: function(res){
				
					if(res == 'true'){
								  
					alert("SOD Canceled Successfully");

					setInterval(location.reload(), 5000);
					
					}

				 }
			   });
				
		  } 
		  
	  }else if(status == '4'){

			$("#modal_ttl").html('<b>Return SOD</b>');
			
			$("#modal_body").html('<div class="form-group  row">'+
								'<label class="col-sm-2 col-form-label" style="font-size:14px;">Return Reason</label>'+
								'<div class="col-sm-7">'+
								'<input type="text" id="so_no" value="'+so_no+'" style="display:none">'+
								'<input type="text" id="voice_no" value="'+voice_No+'" style="display:none">'+
								'<input type="text" id="group" value="'+group+'" style="display:none">'+
								'<input type="text" id="taskName" value="'+taskName+'" style="display:none">'+
								'<select name="re_reasons" id="re_reasons" class="form-control">'+
								'<option value=""></option>'+
								'<option value="CUSTOMER NOT READY\">CUSTOMER NOT READY</option>'+
								'<option value="FAULTY OSP NW\">FAULTY OSP NW</option>'+
								'<option value="NO OSP NW/PRIMARY/SECONDARY\">NO OSP NW/PRIMARY/SECONDARY</option>'+
								'<option value="NO PORTS\">NO PORTS</option>'+
								'<option value="OVER DISTANCE\">OVER DISTANCE</option>'+
								'<option value="THIRD PARTY OBSTRUCTIONS\">THIRD PARTY OBSTRUCTIONS</option>'+
								'<option value="OSS DATA ERROR\">OSS DATA ERROR</option>'+ 
								'<option value="POLE ERECTION PROBLEM\">POLE ERECTION PROBLEM</option>'+
								'<option value="REFUSED BY CUSTOMER\">REFUSED BY CUSTOMER</option>'+
								'<option value="CANCELLED BY SLT\">CANCELLED BY SLT</option>'+
								'<option value="CANNOT CONTACT CUSTOMER\">CANNOT CONTACT CUSTOMER</option>'+
								'<option value="LOW SNR\">LOW SNR</option>'+
								'<option value="LINE NOT READY\">LINE NOT READY</option>'+
								'<option value="POLE COUNTS EXCEEDS THAN ALLOWABLE\">POLE COUNTS EXCEEDS THAN ALLOWABLE</option>'+
								'<option value="POLE COUNT EXCEEDS AND OVER DISTANCE\">POLE COUNT EXCEEDS AND OVER DISTANCE</option>'+
								'<option value="OTHER">OTHER</option>'+
								'</select>'+
								'</div>'+
								'<div class="form-group  row">'+
								'<label class="col-sm-2 col-form-label" style="font-size:14px;">&nbsp;</label>'+
								'<div class="col-sm-4">'+
								'<button type="button" class="btn btn-danger btn-md " onclick="returnSod();"><i class="fa fa-refresh"></i>&nbsp;&nbsp;Return</button>'+
								'</div>');

			$("#myModal").modal();
			
			$("#st"+x).val(1);

		  
	  }else{
				
			$("#modal_ttl").html('<b>Complete SOD - '+stype+'</b>');
			
			if(stype == 'AB-CAB'){
				
			$("#modal_body").html('<div class="form-group  row">'+
								'<label class="col-sm-3 col-form-label" style="font-size:14px;">Connection Type</label>'+
								'<div class="col-sm-3">'+
								'<input type="text" id="so_no" value="'+so_no+'" style="display:none">'+
								'<input type="text" id="voice_no" value="'+voice_No+'" style="display:none">'+
								'<input type="text" id="group" value="'+group+'" style="display:none">'+
								'<input type="text" id="rtom" value="'+rtom+'" style="display:none">'+
								'<input type="text" id="stype" value="'+stype+'" style="display:none">'+
								'<input type="text" id="taskName" value="'+taskName+'" style="display:none">'+
								'<select name="con_type" id="con_type" class="form-control">'+
								'<option value=""></option>'+
								'<option value="WITH POLES">WITH POLES</option>'+
								'<option value="WITH POLES VOICE ONLY">WITH POLES VOICE ONLY</option>'+
								'<option value="WITHOUT POLES">WITHOUT POLES</option>'+
								'<option value="WITHOUT POLES VOICE ONLY">WITHOUT POLES VOICE ONLY</option>'+
								'</select>'+
								'</div>'+
								'<label class="col-sm-3 col-form-label" style="font-size:14px;">Drop Wire Length*</label>'+
								'<div class="col-sm-3">'+
								'<input type="number" id="dwlength" class="form-control">'+
								'</div>'+
								'</div>'+
								'<div class="form-group  row">'+
								'<label class="col-sm-3 col-form-label" style="font-size:14px;">Other Meterials</label>'+
								'<div class="col-sm-3">'+
								'<select name="other_mat" id="other_mat" class="form-control">'+
								'<option value=""></option>'+
								'<option value="PT-SP-VO-ID">PT-SP-VO-ID</option>'+
								'<option value="PT-2P-VB-ID">PT-2P-VB-ID</option>'+
								'<option value="PT-3P-BP-ID">PT-3P-BP-ID</option>'+
								'<option value="PT-SP-PO-ID">PT-SP-PO-ID</option>'+
								'<option value="PSTN-DW">PSTN-DW</option>'+
								'</select>'+
								'</div>'+
								'<label class="col-sm-3 col-form-label" style="font-size:14px;">&nbsp;</label>'+
								'<div class="col-sm-3">'+
								'</div>'+
								'</div>'+
								'<div class="form-group  row">'+
								'<label class="col-sm-3 col-form-label" style="font-size:14px;">&nbsp;</label>'+
								'<div class="col-sm-3">'+
								'<button type="button" class="btn btn-primary btn-md " onclick="complete_ABCAB();"><i class="fa fa-save"></i>&nbsp;&nbsp;Save</button>'+
								'</div>');
								
			}else if(stype == 'E-IPTV COPPER'){
				
			$("#modal_body").html('<div class="form-group  row">'+
								'<label class="col-sm-3 col-form-label" style="font-size:14px;">Other Meterials</label>'+
								'<div class="col-sm-3">'+
							    '<input type="text" id="so_no" value="'+so_no+'" style="display:none">'+
								'<input type="text" id="voice_no" value="'+voice_No+'" style="display:none">'+
								'<input type="text" id="group" value="'+group+'" style="display:none">'+
								'<input type="text" id="rtom" value="'+rtom+'" style="display:none">'+
								'<input type="text" id="stype" value="'+stype+'" style="display:none">'+
								'<input type="text" id="taskName" value="'+taskName+'" style="display:none">'+
								'<select name="other_mat" id="other_mat" class="form-control">'+
								'<option value=""></option>'+
								'<option value="DW-DF">DW-DF</option>'+
								'<option value="DW-ER">DW-ER</option>'+
								'<option value="EX-IPTV">EX-IPTV</option>'+
								'<option value="DW-EW">DW-EW</option>'+
								'</select>'+
								'</div>'+
								'<label class="col-sm-3 col-form-label" style="font-size:14px;">&nbsp;</label>'+
								'<div class="col-sm-3">'+
								'</div>'+
								'</div>'+
								'<div class="form-group  row">'+
								'<label class="col-sm-3 col-form-label" style="font-size:14px;">&nbsp;</label>'+
								'<div class="col-sm-3">'+
								'<button type="button" class="btn btn-primary btn-md " onclick="complete_copper();"><i class="fa fa-save"></i>&nbsp;&nbsp;Save</button>'+
								'</div>');
				
			}

			$("#myModal").modal();
			
			$("#st"+x).val(1);
		
		
	  }
		   
	}
	
	
	function complete_ABCAB(){
		
		var voice_no = $('#voice_no').val();
		var so_no = $('#so_no').val();
		var rtom = $('#rtom').val();
		var stype = $('#stype').val();
		var group = $('#group').val();
		var taskName = $('#taskName').val();
		var con_type = $('#con_type').val();
		var dwlength = $('#dwlength').val();
		var other_mat = $('#other_mat').val();
		
		
		if(con_type == ''){
			
			alert("Connection Type Required");
			
		}else if(dwlength == ''){
			
			alert("Drop Wire Length Required");
			
		}else{
		
		var r = confirm("Are you sure you want to Complete sod!");
  
		if (r == true) {
			
		$.ajax({
         type: "POST",
         data: {voice_no:voice_no,so_no:so_no,rtom:rtom,stype:stype,group:group,taskName:taskName,con_type:con_type,dwlength:dwlength,other_mat:other_mat},
         url: "./function.php?r=26",
         success: function(res){
			
				if(res == 'true'){
					
				alert("Order Complete Success");

				setInterval(location.reload(), 5000);	
				
	
				}

			 }
		});
		
		}
		
		}
		
	}
	
	function complete_copper(){
			
		var voice_no = $('#voice_no').val();
		var so_no = $('#so_no').val();
		var rtom = $('#rtom').val();
		var stype = $('#stype').val();
		var group = $('#group').val();
		var taskName = $('#taskName').val();
		var other_mat = $('#other_mat').val();
		var con_type = '';
		var dwlength = '';
		
		var r = confirm("Are you sure you want to Complete sod!");
  
		if (r == true) {
			
		$.ajax({
         type: "POST",
         data: {voice_no:voice_no,so_no:so_no,rtom:rtom,stype:stype,group:group,taskName:taskName,other_mat:other_mat,con_type:con_type,dwlength:dwlength},
         url: "./function.php?r=26",
         success: function(res){
			
				if(res == 'true'){
					
				alert("Order Complete Success");

				setInterval(location.reload(), 5000);	
				
				
				}

			 }
		});
		
		}
		
		
	}
	
	
	function view_data(element) {
	
	  var table = document.getElementById('orders');
	  var x = element.parentNode.parentNode.rowIndex; 
	  var voice_no = table.rows[x].cells[0].innerHTML;
	  var so_no = table.rows[x].cells[3].innerHTML;
	  var s_type = table.rows[x].cells[6].innerHTML;
	  
		$.ajax({
		 type: "POST",
		 data:{voice_no:voice_no,so_no:so_no,s_type:s_type},
		 url: "./function.php?r=29",
		 success: function(res){
				
				$("#modal_ttl").html('<b>SOD Details</b>');
				
				$("#modal_body").html(res);

				$("#myModal").modal();

		 }
		});	
	  
	}
	
	function returnSod() {
	
	  var so_no = document.getElementById('so_no').value;
	  var voice_no = document.getElementById('voice_no').value;
	  var re_reasons = document.getElementById('re_reasons').value;
	  var group = document.getElementById('group').value;
	  var taskName = document.getElementById('taskName').value;
		
		if(re_reasons == ''){
			
			alert("Return Reasion Required");
			return false;
			
		}else{
			
		var r = confirm("Are you sure you want to Return sod!");
  
		if (r == true) {
			
		$.ajax({
		 type: "POST",
		 data:{voice_no:voice_no,so_no:so_no,group:group,re_reasons:re_reasons,taskName:taskName},
		 url: "./function.php?r=30",
		 success: function(res){
			
			if(res == 'true'){
					
				alert("Order Return Success");

				setInterval(location.reload(), 5000);
				
			}

		 }
		});	
		
		}
		
		}
		
	  
	}
	
	 
</script>



</body>
</html>
