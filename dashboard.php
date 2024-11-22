<?php
session_start();
include "db.php";

if(isset($_SESSION["user"]) && isset($_SESSION["con"]))
{
$usr =  $_SESSION["user"];
$con =   $_SESSION["con"];
$ar =$_SESSION["area"];
$pri = $_SESSION["prilev"];

$temp = explode('/',$ar);
$n = sizeof($temp);

$fromdate = date('Y-m').'-'.'01';
$toDate = date('Y-m-d');

}

?>

<!DOCTYPE html>
<html lang="en">

<head>

  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">

  <title>SLTQC</title>

  <!-- Custom fonts for this template-->
  <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
  <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

  <!-- Custom styles for this template-->
  <link href="css/sb-admin-2.min.css" rel="stylesheet">
  <!--<link href="vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet"> -->
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
  
    <!-- chart -->
    

  <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
  <link rel="stylesheet" href="/resources/demos/style.css">
  <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
  <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
  
  <script>
  $( function() {
    $( "#dfrom").datepicker({ dateFormat: 'yy-mm-dd' });
	$( "#dTo").datepicker({ dateFormat: 'yy-mm-dd' });
  } );
  </script>
  
  <!-- Chart -->
  <script src="js/chart.js"></script> 
  
</head>


<body id="page-top" onload="getChartData()">

  <!-- Page Wrapper -->
  <div id="wrapper">

    <!-- Sidebar -->
    <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

      <!-- Sidebar - Brand -->
      <a class="sidebar-brand d-flex align-items-center justify-content-center" href="index.html">
        <div class="sidebar-brand-icon rotate-n-15">
          
        </div>
        <div class="sidebar-brand-text mx-3"><h3 style="font-family:  serif; font-weight: bold;">SLTQC</h3></div>
      </a>

      <!-- Divider -->
      <hr class="sidebar-divider my-0">

      <!-- Nav Item - Dashboard -->
      <!-- Divider -->
      <hr class="sidebar-divider">

 <?php 
    if($_SESSION["con"] <> 'SLTSQC' || $_SESSION["con"] <> 'SLT')
    {
?> 
      <!-- Heading -->
      <div class="sidebar-heading">
        <span  style="font-size: medium;">Contractor</span>
      </div>

      <!-- Nav Item - Pages Collapse Menu -->
	  <li class="nav-item">
        <a class="nav-link collapsed" href="dashboard.php"  data-target="#" aria-expanded="true" aria-controls="collapseTwo">
          <i class="fas fa-fw fa-cog"></i>
          <span>Dashboard</span>
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="true" aria-controls="collapseTwo">
          <i class="fas fa-fw fa-cog"></i>
          <span>User</span>
        </a>
        <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
          <div class="bg-white py-2 collapse-inner rounded">
            <a class="collapse-item" href="index.php">Pending List</a>
            <a class="collapse-item" href="qty_approve_com.php">Approved List</a>
            <a class="collapse-item" href="qty_approve_rej.php">Approve Rejected List</a>
            <a class="collapse-item" href="qty_slt_rej.php">SLT Rejected List</a>
			<a class="collapse-item" href="qty_approve_pass.php">SLT Passed List</a>
          </div>
        </div>
      </li>
      <!-- Nav Item - Pages Collapse Menu -->
      <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapsethree" aria-expanded="true" aria-controls="collapseTwo">
          <i class="fas fa-fw fa-cog"></i>
          <span>Approver</span>
        </a>
        <div id="collapsethree" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
          <div class="bg-white py-2 collapse-inner rounded">
            <a class="collapse-item" href="qty_approve_pen.php">Approve Pending List</a>
            <a class="collapse-item" href="qty_approve_com.php">Approved List</a>
            <a class="collapse-item" href="qty_approve_rej.php">Approve Rejected List</a>
            <a class="collapse-item" href="qty_slt_rej.php">SLT Rejected List</a>
			<a class="collapse-item" href="qty_approve_pass.php">SLT Passed List</a>
          </div>
        </div>
      </li>

      <!-- Nav Item - Utilities Collapse Menu -->

<?php 
    }if($_SESSION["con"] == 'SLT')
    {
?>        
      <!-- Divider -->
      <hr class="sidebar-divider">

      <!-- Heading -->
      <div class="sidebar-heading">
        <span  style="font-size: medium;">SLT</span>
      </div>

      <!-- Nav Item - Pages Collapse Menu -->
      <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapse2" aria-expanded="true" aria-controls="collapseTwo">
          <i class="fas fa-fw fa-cog"></i>
          <span>User</span>
        </a>
        <div id="collapse2" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
          <div class="bg-white py-2 collapse-inner rounded">
            <a class="collapse-item" href="slt_qty_pen.php">Quality Pending List</a>
            <a class="collapse-item" href="slt_qty_rej.php">Quality Rejected List</a>
            <a class="collapse-item" href="slt_qty_pas.php">Quality Passed List</a>
          </div>
        </div>
      </li>
      <!-- Nav Item - Pages Collapse Menu -->
      <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapse3" aria-expanded="true" aria-controls="collapseTwo">
          <i class="fas fa-fw fa-cog"></i>
          <span>Admin</span>
        </a>
        <div id="collapse3" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
          <div class="bg-white py-2 collapse-inner rounded">
            <a class="collapse-item" href="slt_qty_pen.php">Quality Pending List</a>
            <a class="collapse-item" href="slt_qty_rej.php">Quality Rejected List</a>
            <a class="collapse-item" href="slt_qty_pas.php">Quality Passed List</a>
          </div>
        </div>
      </li>
 <?php 
    }if($_SESSION["con"] == 'SLTSQC')
    {
?>      
      <!-- Divider -->
      <hr class="sidebar-divider">

      <!-- Heading -->
      <div class="sidebar-heading">
        <span  style="font-size: medium;">SLTS</span>
      </div>

      <!-- Nav Item - Pages Collapse Menu -->
      <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapse5" aria-expanded="true" aria-controls="collapseTwo">
          <i class="fas fa-fw fa-cog"></i>
          <span>User</span>
        </a>
        <div id="collapse5" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
          <div class="bg-white py-2 collapse-inner rounded">
            <a class="collapse-item" href="slt_qty_pen.php">Quality Pending List</a>
            <a class="collapse-item" href="slt_qty_rej.php">Quality Rejected List</a>
            <a class="collapse-item" href="slt_qty_pas.php">Quality Passed List</a>
          </div>
        </div>
      </li>

<?php 
    }
?> 

      <!-- Divider -->
      <hr class="sidebar-divider d-none d-md-block">

      <!-- Sidebar Toggler (Sidebar) -->
      <div class="text-center d-none d-md-inline">
        <button class="rounded-circle border-0" id="sidebarToggle"></button>
      </div>

    </ul>
    <!-- End of Sidebar -->

    <!-- Content Wrapper -->
    <div id="content-wrapper" class="d-flex flex-column">

      <!-- Main Content -->
      <div id="content">

        <!-- Topbar -->
        <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">

          <!-- Sidebar Toggle (Topbar) -->
          <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
            <i class="fa fa-bars"></i>
          </button>

          <!-- Topbar Search -->
          <form class="d-none d-sm-inline-block form-inline mr-auto ml-md-3 my-2 my-md-0 mw-100 navbar-search">
            <div class="input-group">
             <!-- <h4>Dashboard</h4> -->
            </div>
          </form>

          <!-- Topbar Navbar -->
          <ul class="navbar-nav ml-auto">

            <div class="topbar-divider d-none d-sm-block"></div>

            <!-- Nav Item - User Information -->
            <li class="nav-item dropdown no-arrow">
              <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                 <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                <span class="mr-2 d-none d-lg-inline text-gray-600 small"><?php echo $_SESSION["con"].' - '.$_SESSION["user"]; ?></span>
               
              </a>
              <!-- Dropdown - User Information -->
              <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="userDropdown">
                
                <div class="dropdown-divider"></div>
                <a class="dropdown-item" href="#" data-toggle="modal" data-target="#logoutModal">
                  <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                  
                  Logout
                </a>
              </div>
            </li>

          </ul>

        </nav>
        <!-- End of Topbar -->

    <!-- Begin Page Content -->
    <div class="container-fluid">

  <!-- Page Heading -->
	<div class="content mt-3">

	<input type="hidden" id="contctr"  value="<?php echo $con; ?>">
            <div class="row">

                    <div class="col-lg-12">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="mb-3" ><b>Progress Chart</b></h5>
								<br/>
                                <form class="form-inline">
							
                                  <div class="form-group mx-sm-2 mb-2">
                                    <label for="inputPassword2"><b>RTOM:&nbsp;</b></label>
                                    <select style="width:120px" class="form-control" id="rtom">
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
                                    <div class="form-group mx-sm-2 mb-2">
                          
                                    <label><b>Service Type:&nbsp;</b></label>
                                    <select style="width:120px"  class="form-control" id="product" >
                                      <option value="">All</option>
                                      <option value="AB-CAB">AB-CAB</option>
                                      <option value="AB-FTTH">AB-FTTH</option>
                                      <option value="E-IPTV COPPER">E-IPTV COPPER</option>
                                      <option value="E-IPTV FTTH">E-IPTV FTTH</option>
                                    </select>
                                    </div>
									
									 <div class="form-group mx-sm-12 mb-2">
                          
                                    <label><b>Date From:</b>&nbsp;</label>
										<input type="text" style="width:120px" id="dfrom" value="<?php echo $fromdate;?>"   class="form-control">
                                    </div>
									
									 <div class="form-group mx-sm-12 mb-2">
                          
                                    <label>&nbsp<b>Date To:</b>&nbsp;</label>
										<input type="text" style="width:120px" id="dTo"  value="<?php echo $toDate;?>" class="form-control"> 
                                    </div>
									
									 <div class="form-group mx-sm-2 mb-2">
										<input type="button" style="width:100px" class="btn btn-primary" value="View Data" id="dTo" onClick="getChartData()" class="form-control"> 
                                    </div>
									
                                </form>
                                
                                <div id="chartcontainer" style="width:90%; margin-left:6%">
                                 
								 <canvas id="chartCanves"></canvas>
								
								<div id="tblcontainer"></div>
								
                                </div>
								
								<br/><br/>
                                
                            </div>
                        </div>
                    </div>
                </div>
				
				

    </div>

  </div>
  <!-- /.container-fluid -->

</div>
<!-- End of Main Content -->

      <!-- Footer -->
      <footer class="sticky-footer bg-white">
        <div class="container my-auto">
          <div class="copyright text-center my-auto">
            <span  style=" color: red; font-weight: bold;">Copyright &copy; IT Solution & DevOps 2019</span>
          </div>
        </div>
      </footer>
      <!-- End of Footer -->

    </div>
    <!-- End of Content Wrapper -->

<script type="text/javascript">

function getChartData(){

    var rtom = document.getElementById('rtom').value;
    var product = document.getElementById('product').value;
    var dfrom = document.getElementById('dfrom').value;
	var dTo = document.getElementById('dTo').value;
	
	waitingDialog.show();
	
    $.ajax({
           type: "POST",
           data: {rtom:rtom,product:product,dfrom:dfrom,dTo:dTo},
           url: "./function.php?r=5",
           success: function(res){
            loadchart(res,rtom,product);
           }
        });
}

function loadchart(res,rtom,product){

var contctr = document.getElementById('contctr').value;
var dataArr = new Array();

$('#dtrow').html('');
$('#chartCanves').remove(); 
$('#tblcontainer').remove(); 
$('#chartcontainer').append('<canvas id="chartCanves"></canvas>');
$('#chartcontainer').append('<div id="tblcontainer"><table cellspacing="2" class="text-center" style="margin-left:3%; min-width:98%" >'+
							'<tr id="dtrow"></tr><tr><td colspan="8"></td></tr><tr><td colspan="8" style="text-align:right; font-size:11px; color:red">*To be invoiced column not depend on dates</td></tr>'+
                            '</table></div>');


var resArr = res.split(',');

for(var i=0; i<resArr.length; i++){

  dataArr.push(resArr[i]);

}

var ctx = document.getElementById("chartCanves").getContext('2d');
   
var labels = ["Work Orders","Completed", "Pending Photos","Uploaded photos","Pending at QC", "QC Reject","QC Pass","To be invoiced"];
 dataset = [ 
                {
                  type: 'bar',
                  data: dataArr,                    
                  backgroundColor: [
                      '#76d7c4',
                      '#f0b27a',
                      '#bb8fce',
                      '#2237e6',
                      '#2c3e50',
                      '#ec7063',
                      '#186a3b',
					  '#34a1be'
                  ]
                }
            ];

var options = {
    scales: {
      xAxes: [{
        //stacked: true,
        barPercentage: 0.7
      }],
      yAxes: [{
        //stacked: true
        barPercentage: 0.7
      }]
    }
};

var content = {
    type: 'bar',
    data: {
        labels: labels,
        datasets: dataset
    },options
};

new Chart(ctx, content);

	
for(var i=0; i<resArr.length; i++){
	
	if(i=='0'){
		
		if(rtom != ''){

			$('#dtrow').append('<td id="dt'+i+'"><a href = "reports/'+contctr+'_'+rtom+'_work_orders.csv" class="btn btn-success" style="width:70px;" title="Report" download>'+resArr[i]+'</a></td>');

		}else{

			$('#dtrow').append('<td id="dt'+i+'"><a href = "reports/'+contctr+'_All_work_orders.csv" class="btn btn-success" style="width:70px;" title="Report" download>'+resArr[i]+'</a></td>');

		}
		
  
	}
	
	if(i=='1'){
		
		if(rtom != ''){

			$('#dtrow').append('<td id="dt'+i+'"><a href = "reports/'+contctr+'_'+rtom+'_completed.csv" class="btn btn-success" style="width:70px;" title="Report" download>'+resArr[i]+'</a></td>');

		}else{

			$('#dtrow').append('<td id="dt'+i+'"><a href = "reports/'+contctr+'_All_completed.csv" class="btn btn-success" style="width:70px;" title="Report" download>'+resArr[i]+'</a></td>');

		}
  
	}
	
	if(i=='2'){
		
		if(rtom != ''){

			$('#dtrow').append('<td id="dt'+i+'"><a href = "reports/'+contctr+'_'+rtom+'_pending_potos.csv" class="btn btn-success" style="width:70px;" title="Report" download>'+resArr[i]+'</a></td>');

		}else{

			$('#dtrow').append('<td id="dt'+i+'"><a href = "reports/'+contctr+'_All_pending_potos.csv" class="btn btn-success" style="width:70px;" title="Report" download>'+resArr[i]+'</a></td>');

		}
	
	}
	
	if(i=='3'){
		
		if(rtom != ''){

			$('#dtrow').append('<td id="dt'+i+'"><a href = "reports/'+contctr+'_'+rtom+'_uploaded_photos.csv" class="btn btn-success" style="width:70px;" title="Report" download>'+resArr[i]+'</a></td>');

		}else{

			$('#dtrow').append('<td id="dt'+i+'"><a href = "reports/'+contctr+'_All_uploaded_photos.csv" class="btn btn-success" style="width:70px;" title="Report" download>'+resArr[i]+'</a></td>');

		}

	}
	
	if(i=='4'){
		
		if(rtom != ''){

			$('#dtrow').append('<td id="dt'+i+'"><a href = "reports/'+contctr+'_'+rtom+'_pending_qc.csv" class="btn btn-success" style="width:70px;" title="Report" download>'+resArr[i]+'</a></td>');

		}else{

			$('#dtrow').append('<td id="dt'+i+'"><a href = "reports/'+contctr+'_All_pending_qc.csv" class="btn btn-success" style="width:70px;" title="Report" download>'+resArr[i]+'</a></td>');

		}
		
	}
	
	if(i=='5'){
		
		if(rtom != ''){

			$('#dtrow').append('<td id="dt'+i+'"><a href = "reports/'+contctr+'_'+rtom+'_reject.csv" class="btn btn-success" style="width:70px;" title="Report" download>'+resArr[i]+'</a></td>');

		}else{

			$('#dtrow').append('<td id="dt'+i+'"><a href = "reports/'+contctr+'_All_reject.csv" class="btn btn-success" style="width:70px;" title="Report" download>'+resArr[i]+'</a></td>');

		}
		
	}
	
	if(i=='6'){
		
		if(rtom != ''){

			$('#dtrow').append('<td id="dt'+i+'"><a href = "reports/'+contctr+'_'+rtom+'_pass.csv" class="btn btn-success" style="width:70px;" title="Report" download>'+resArr[i]+'</a></td>');

		}else{

			$('#dtrow').append('<td id="dt'+i+'"><a href = "reports/'+contctr+'_All_pass.csv" class="btn btn-success" style="width:70px;" title="Report" download>'+resArr[i]+'</a></td>');

		}
		
	}
	
	if(i=='7'){
		
		if(rtom != ''){

			$('#dtrow').append('<td id="dt'+i+'"><a href = "reports/'+contctr+'_'+rtom+'_to_be_invoiced.csv" class="btn btn-success" style="width:70px;" title="Report" download>'+resArr[i]+'</a></td>');

		}else{

			$('#dtrow').append('<td id="dt'+i+'"><a href = "reports/'+contctr+'_All_to_be_invoiced.csv" class="btn btn-success" style="width:70px;" title="Report" download>'+resArr[i]+'</a></td>');

		}
		
	}

}

createCSV();

}


function createCSV(){

    var rtom = document.getElementById('rtom').value;
    var product = document.getElementById('product').value;
    var dfrom = document.getElementById('dfrom').value;
	var dTo = document.getElementById('dTo').value;
	
	waitingDialog.hide();
	
    $.ajax({
           type: "POST",
           data: {rtom:rtom,product:product,dfrom:dfrom,dTo:dTo},
           url: "./function.php?r=6",
           success: function(res){
            waitingDialog.hide();
           }
        });
}


//Wating Window

var waitingDialog = waitingDialog || (function ($) {
    'use strict';

	// Creating modal dialog's DOM
	var $dialog = $(
		'<div class="modal fade" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-hidden="true" style="padding-top:15%; overflow-y:visible;">' +
		'<div class="modal-dialog modal-m">' +
		'<div class="modal-content">' +
			'<div class="modal-body">' +
				'<div style="text-align:center"><img src="img/loading.gif"></div>' +
			'</div>' +
		'</div></div></div>');

	return {

		show: function (message, options) {

			if (typeof options === 'undefined') {
				options = {};
			}
			if (typeof message === 'undefined') {
				message = 'Loading';
			}
			var settings = $.extend({
				dialogSize: 'm',
				progressType: '',
				onHide: null // This callback runs after the dialog was hidden
			}, options);

			// Configuring dialog
			$dialog.find('.modal-dialog').attr('class', 'modal-dialog').addClass('modal-' + settings.dialogSize);
			$dialog.find('.progress-bar').attr('class', 'progress-bar');
			if (settings.progressType) {
				$dialog.find('.progress-bar').addClass('progress-bar-' + settings.progressType);
			}
			$dialog.find('h3').text(message);
			// Adding callbacks
			if (typeof settings.onHide === 'function') {
				$dialog.off('hidden.bs.modal').on('hidden.bs.modal', function (e) {
					settings.onHide.call($dialog);
				});
			}
			// Opening dialog
			$dialog.modal();
		},
		/**
		 * Closes dialog
		 */
		hide: function () {
			$dialog.modal('hide');
		}
	};

})(jQuery);



</script>

  </div>
  <!-- End of Page Wrapper -->

  <!-- Scroll to Top Button-->
  <a class="scroll-to-top rounded" href="#page-top">
    <i class="fas fa-angle-up"></i>
  </a>

  <!-- Logout Modal-->
  <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Ready to Leave?</h5>
          <button class="close" type="button" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
          </button>
        </div>
        <div class="modal-body">Select "Logout" below if you are ready to end your current session.</div>
        <div class="modal-footer">
          <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
          <a class="btn btn-primary" href="login.php">Logout</a>
        </div>
      </div>
    </div>
  </div>

  <!-- Bootstrap core JavaScript-->
  <!--<script src="vendor/jquery/jquery.min.js"></script>-->
  <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

  <!-- Core plugin JavaScript-->
  <script src="vendor/jquery-easing/jquery.easing.min.js"></script>

  <!-- Custom scripts for all pages-->
  <script src="js/sb-admin-2.min.js"></script>

  <!-- Page level plugins -->
  <script src="vendor/datatables/jquery.dataTables.min.js"></script>
  <script src="vendor/datatables/dataTables.bootstrap4.min.js"></script>

  <!-- Page level custom scripts -->
  <script src="js/demo/datatables-demo.js"></script>
  
    <!-- Page level plugins -->
  <script src="vendor/datatables/jquery.dataTables.min.js"></script>
  <script src="vendor/datatables/dataTables.bootstrap4.min.js"></script>

  <!-- Page level custom scripts -->
  <script src="js/demo/datatables-demo.js"></script>

</body>

</html>
