<?php
session_start();
if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true)
{    
    $user = $_SESSION["user"];
    $contractor_name = $_SESSION["contractor"];
     $level = $_SESSION["level"];
}
else 
{     
    echo '<script type="text/javascript"> document.location = "login.php";</script>'; 
}

$pkg = $_SESSION["pkg"];
include "db.php";
include "GetDays.php";




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

 <style>
 img:hover{
 cursor: pointer;
}
 </style>
 
      <script>
function checkForm(frm) {

if (frm.drop_wire.value == "" && frm.drop_wire_ser.value == "" && frm.drop_wire_acc.value == "") {
         alert("DROP WIRE Cannot be null");
         return false;     
     }
 }
 


function addmet(val, val2, val3){

    var sod = val;
    var cir = val3;
    var ser = document.getElementById("sertyp").value;


var r = confirm("Are you sure you want to update ");
    if (r == true) {  
	
     if(val2 == 'DW'){
        var a= document.getElementById("dw").value;
        var b= document.getElementById("dwP").value;
        var c= document.getElementById("dropwire").value;
        var sn= '';
        
        if(c == ''){
            alert('Dropwire Length cannot be empty');
            return;
        }
     }
     if(val2 == 'LH'){
        var a= document.getElementById("lh").value;
        var b= document.getElementById("lhP").value;
        var c= document.getElementById("lhook").value;
        var sn= '';
        
        if(c == ''){
            alert('LHOOK cannot be empty');
            return;
        }
        
     }
     if(val2 == 'PLC'){
        var a= document.getElementById("plch").value;
        var b= document.getElementById("plcP").value;
        var c= document.getElementById("plc").value;
        var sn= '';
        
        if(c == ''){
            alert('PLC-CON cannot be empty');
            return;
        }
        
     }
     if(val2 == 'PL'){
        var a= document.getElementById("pl").value;
        var b= document.getElementById("poleP").value;
        var c= '1';
        var sn= document.getElementById("sn").value;
        
        if(sn == ''){
            alert('Serial Number cannot be empty');
            return;
        }
        
     }
     if(val2 == 'OM'){
        var a= document.getElementById("othmet").value;
        var b= document.getElementById("othmetP").value;
        var c= '1';
        var sn= '';
 
     }
     
          var q = 'addmetqty';
          $.ajax({

            type:"post",
             url:"db.php",
             data:"sod="+sod+"&cir="+cir+"&q="+q+"&met="+a+"&sn="+sn+"&type="+b+"&ser="+ser+"&val="+c,
             success:function(data){
             
                   if(data == "success"){
                        
                        alert("Meterial Insert Successful");
                        location.reload();
                        
                    }else{
                        alert(data);
                        location.reload();
                    }
               
                }
          });
		
	}else {
    location.reload();
    return false;
    } 
}


 function deleteval(val,val2,val3,val4,val5,val6) {
    var sod = val3;
    var cir = "<?php echo $getcir[0]; ?>";
	
  var r = confirm("Are you sure you want to update ");
    if (r == true) {  
      var q = 'deletemetqty';
          $.ajax({

            type:"post",
             url:"db.php",
             data:"&ser="+val6+"&cir="+val4+"&metid="+val5+"&q="+q+"&sod="+sod+"&met="+val,
             success:function(data){
                    if(data == "success"){
                        
                        
                        alert("Meterial Delete Successful");
                        location.reload();
                    }else{
                        alert(data);
                        location.reload();
                    }
     
               
                }
          });  

	}else {
    location.reload();
    return false;
    } 	  
}

function approv(){
    var sod= "<?php echo $so_id; ?>";
   var cir= "<?php echo $getcir[0]; ?>";

          var q = 'updateapp';
          $.ajax({

            type:"post",
             url:"db.php",
             data:"sod="+sod+"&cir="+cir+"&q="+q,
             success:function(data){
                    if(data == "success"){
                        
                        alert("Connection Approved Successful");
                        window.location = "CO_SO_CON_Compap.php";
                        
                    }else{
                        alert(data);
                        location.reload();
                    }
                
                }
          });
}

function search() {
    var val = document.getElementById("cir").value;
	self.location='ncqtydet.php?cir=' + val ;
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
          <div class="col-sm-11">
            <h3>Circuit Details Update (Image Uploaded)</h3>

        </div>
        <div class="col-sm-1">
            <h6><a href="CO_SO_List"><img src="img/back.jpg" width="35" alt="back" height="35"></a></h6>
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content" style="font-size: 14px;">
      <div class="container-fluid">
<hr />
        <div class="row">
            <div class="col-sm-2"><span style="font-size:20px;">CIRCUIT</span></div>
            <div class="col-sm-3"><input type="text" id="cir" style="border-radius: 5px; width: 250px; height: 35px;"  class="form-control" value="<?php echo $_GET['cir']; ?>" /></div>
            <div class="col-sm-2 "><input type="submit" class="btn btn-primary"  style="height: 35px; "  onclick="search()" value="Search" />  </div><br/>
          
         </div>
<br /><br />
<?php
if(isset($_GET['cir']))
{

$cir=$_GET['cir'];
$so_id = getmaxsodqty($cir);

$getcir = oci_fetch_array(getcircabclose($so_id));
$getcon = oci_fetch_array(getcon($so_id));

$cunt= '0';
$a= '';
$pl=0;


 ?>


    <form name="form2" method="post" action="" id="form2" > 
      
      <input type="hidden" id="so_id" name="so_id" value="<?php echo $so_id ?>"/>
      <input type="hidden" id="con_type" name="con_type" value="<?php echo $con_type ?>"/>
      <input type="hidden" id="circuit" name="circuit" value="<?php echo $getcir[0] ?>"/>
      <input type="hidden" id="next" name="next" />
      <input type="hidden" id="next2" name="next2" />
      
       
        <?php
        
       $getftthcab= oci_fetch_array(getftthcabclosesltqty($so_id));
       $osp_date = oci_fetch_array(osp_date($so_id));
	   
	 if($getftthcab[0] != ""){  
       echo " <input type=\"hidden\" id=\"sertyp\" name=\"sertyp\" value=\"$getftthcab[1]\">";
        
	echo"	 <div id=\"page-wrapper\">
		
			
	<div class=\"panel-body\">
                            <div class=\"table-responsive\">
                                <table style=\"font-size:13px;\" class=\"table table-striped table-bordered table-hover\">
                                    
                                    <tbody>	";
                                    

    if($getcir[3] == 'AB-CAB'){  
           echo "<tr ><th colspan=\"6\"><span style=\"color:#178097 ;\">CAB SOD Details</span> </th></tr>";
           echo "<input type=\"hidden\" id=\"servtyp\" value=\"AB-CAB\">";
    }
    if($getcir[3] == 'AB-FTTH'){  
           echo "<tr ><th colspan=\"6\"><span style=\"color:#178097 ;\">FTTH SOD Details</span> </th></tr>";
           echo "<input type=\"hidden\" id=\"servtyp\" value=\"AB-FTTH\">";
            }
    
        echo "<tr>";
        
        if($getcir[3] == 'AB-CAB'){  
		echo"<th>CAB SOD</th>";
        }
        if($getcir[3] == 'AB-FTTH'){  
		echo"<th>FTTH SOD</th>";
        } 
         echo "<td>$getftthcab[0]</td>
            <th>Service Type</th>
            <td>$getftthcab[1]</td>
			<th>Contractor</th>
            <td>$getftthcab[20]</td>
            </tr>
            
            <tr>
			<th>VOICE NO</th>
            <td>$getftthcab[3]</td>
            <th>RTOM</th>
            <td>$getftthcab[4]</td>
			<th>Order Type</td>
            <td>$getftthcab[5]</td>
            </tr>
            
            <tr>
            <th>Customer Name</td>
            <td>$getftthcab[6]</td>
			<th>Customer Contact NO</td>
			<td>$getftthcab[7]</td>
            <th>SOD Status</td>
            <td >COMPLETED</td>
            </tr>
            
            <tr>
			<th>Service Address</th>
            <td colspan=\"3\">$getftthcab[8]</td>
            <th>Connection Type</td>
            <td>$getcon[0]</td>
            </tr>";

            
            

            
            

	
         $getiptv= oci_fetch_array(getiptv($getftthcab[3])); 
         if($getiptv[0]!= ''){ 
            echo "<tr ><th colspan=\"6\"><span style=\"color:#178097 ;\">IPTV SOD Details</span></th></tr>
            <tr>
			<th>IPTV SOD</th>
            <td>$getiptv[0]</td>
            <th>Service Type</th>
            <td>$getiptv[1]</td>
            <th>Recieve Date</td>
            <td>$getftthcab[2]</td>
            </tr>
            
            <tr>
			<th>IPTV NO</th>
            <td>$getiptv[3]</td>
            <th>Status</th>
            <td>$getiptv[11]</td>
			<th>Order Type</td>
            <td>$getiptv[5]</td>
            </tr>
            
            <tr>
			<th>MSAN Location</th>
            <td>$getiptv[7] - $getiptv[6]</td>
            <th>CARD PORT</th>
            <td>$getiptv[8] - $getiptv[9]</td>
			<th>ADSL Router Class</td>
            <td>$getiptv[10]</td>
            </tr>
            ";
            
            if($getiptv[11] == 'RETURNED'){
                
                $getreturn = getreturn($getiptv[0]);
                
                echo"<tr>
			<th>Returned Reason</th>
            <td colspan=\"5\">$getreturn</td>
            </tr>";
            
            }
            
            
            }
            
            
            
            

            
            
    echo "</table> 
	</div>";
    
    //=========================
if($getcir[3] == 'AB-CAB'){  
    $meterial = nc_meterialqty($so_id);
    
    $num =nc_meterial2qty($so_id) ;
    	         echo " 
	<table style=\"font-size:12px; width:700px;\" class=\"table table-striped table-bordered table-hover\">";
       if($num != '0'){   
          echo"<tr>
                <th>UNIT DESIGNATOR</th>
                <th>P0</th>
                <th>P1</th>
                <th>SN</th>
                <th>Delete</th><tr>"; }
                
         $i=0;                           
          while ($rowa= oci_fetch_array($meterial)){
        if($rowa[2]== 'PSTN-DW'){
            $aa ='YES';
        }
        if($rowa[2]== 'DW-LH'){
            $lh ='YES';
        }
        if($rowa[2]== 'PLC-CON'){
            $plcon ='YES';
        }
        if(strpos($rowa[2], 'PT-') !== false){
            $siu ='YES';
        }
        
        if (strpos($rowa[2], 'PL-') !== false) {
        $pl++;
        }
        
        echo "<tr>
            
                <td><span>$rowa[2]</span></td>
                <td><input type=\"text\" style=\"border-radius: 5px; width: 100px; height: 25px;\"   class=\"form-control\" id=\"P0$i\" name=\"P0$i\" value=\"$rowa[3]\" disabled></td>
                <td><input type=\"text\" style=\"border-radius: 5px; width: 100px; height: 25px;\"   class=\"form-control\" id=\"P1$i\" name=\"P1$i\" value=\"$rowa[4]\" disabled></td>
                <td><input type=\"text\" style=\"border-radius: 5px; width: 100px; height: 25px;\"   class=\"form-control\" id=\"SN$i\" name=\"SN$i\" value=\"$rowa[5]\" disabled></td>
                <td><img src=\"img/del.png\"  width=\"25\" height=\"25\" onclick=\"deleteval('$rowa[2]','$i','$so_id','$row[0]','$rowa[6]','AB-CAB')\"/> </td>
 
            </tr>";
            $i++;
            $cunt++;
            
          }
     
     echo"<input style=\"height: 30px; border-radius: 5px;\" id=\"plct\" name=\"plct\" type=\"hidden\" value=\"$pl\"  class=\"form-control\" >";     
    echo "</table>";
    
}

if($getcir[3] == 'AB-FTTH'){  
    $meterial = nc_meterialqty($so_id);
    
    $num =nc_meterial2qty($so_id) ;
    	         echo " 
	<table style=\"font-size:12px; width:700px;\" class=\"table table-striped table-bordered table-hover\">";
       if($num != '0'){   
          echo"<tr>
                <th>UNIT DESIGNATOR</th>
                <th>P0</th>
                <th>P1</th>
                <th>SN</th>
                <th>Delete</th><tr>"; }
                
         $i=0;                           
          while ($rowa= oci_fetch_array($meterial)){
        if($rowa[2]== 'FTTH-DW'){
            $aa ='YES';
        }
        if($rowa[2]== 'DW-LH'){
            $lh ='YES';
        }
        if($rowa[2]== 'PLC-CON'){
            $plcon ='YES';
        }
        if(strpos($rowa[2], 'FT-') !== false){
            $siu ='YES';
        }
        
        if (strpos($rowa[2], 'PL-') !== false) {
        $pl++;
        }
        
        echo "<tr>
            
                <td><span>$rowa[2]</span></td>
                <td><input type=\"text\" style=\"border-radius: 5px; width: 100px; height: 25px;\"   class=\"form-control\" id=\"P0$i\" name=\"P0$i\" value=\"$rowa[3]\" disabled></td>
                <td><input type=\"text\" style=\"border-radius: 5px; width: 100px; height: 25px;\"   class=\"form-control\" id=\"P1$i\" name=\"P1$i\" value=\"$rowa[4]\" disabled></td>
                <td><input type=\"text\" style=\"border-radius: 5px; width: 100px; height: 25px;\"   class=\"form-control\" id=\"SN$i\" name=\"SN$i\" value=\"$rowa[5]\" disabled></td>
                <td><img src=\"img/del.png\"  width=\"25\" height=\"25\" onclick=\"deleteval('$rowa[2]','$i','$so_id','$row[0]','$rowa[6]','AB-FTTH')\"/> </td>
 
            </tr>";
            $i++;
            $cunt++;
            
          }
     
     echo"<input style=\"height: 30px; border-radius: 5px;\" id=\"plct\" name=\"plct\" type=\"hidden\" value=\"$pl\"  class=\"form-control\" >";     
    echo "</table>";
    
}    
 //===========================

    


    
 if($aa == ''){   
	//DROP WIRE

echo "<br/><div class=\"row\">
    <div class=\"col-sm-3 \"><b><u>DROP WIRE *</u></b> </div>
    <div class=\"col-sm-3 \"> </div>
    <div class=\"col-sm-3 \"> </div>
    <div class=\"col-sm-3 \"> </div>
    </div>";
    echo "<div class=\"row\">
    <div class=\"col-sm-3 \">Unit Designator </div>
    <div class=\"col-sm-2 \">Length</div>
    <div class=\"col-sm-2 \">Type</div>
    <div class=\"col-sm-3 \"> </div>
    </div><br/>";

    echo "<tr><div class=\"row\">
<div class=\"col-sm-3 \">
<select name=\"dw\" id=\"dw\"style=\"width: 250px; height: 30px; border-radius: 5px;\">";

if($getcir[3] == 'AB-CAB'){  
echo "<option value=\"PSTN-DW\">PSTN-DW</option>";
}
if($getcir[3] == 'AB-FTTH'){  
echo" <option value=\"FTTH-DW\">FTTH-DW</option>";   
}    
echo"</select>
</div>
<div class=\"col-sm-2\">
<input style=\"height: 30px; border-radius: 5px;\" id=\"dropwire\" name=\"dropwire\" type=\"number\"  class=\"form-control\" >

</div>
<div class=\"col-sm-2 \">
<select id=\"dwP\" name=\"dwP\" style=\"width: 50px; height: 30px; border-radius: 5px;\">
<option value=\"P0\">P0</option>
<option value=\"P1\">P1</option>
</select>
</div>

<div class=\"form-group\">
  <div style=\"padding-left: 1px;\" class=\"col-sm-2\">
    <button style=\" width: 80px; height: 32px; border-radius: 5px;\" onclick=\"addmet('$so_id','DW','$cir')\" id=\"add-more\" name=\"add-more\" class=\"btn btn-primary\">Add</button>
  </div>

</div></div></tr>";
}

//====================================================
	//POlES
if($getcon[0] == 'WITH POLES' || $getcon[0] == 'WITH POLES VOICE ONLY'){
    
    
    $getpole= getpole();
    
echo "<br/><div class=\"row\">
    <div class=\"col-sm-3 \"><b><u>POLES</u></b> </div>
    <div class=\"col-sm-3 \"> </div>
    <div class=\"col-sm-3 \"> </div>
    <div class=\"col-sm-3 \"> </div>
    </div>";
    echo "<div class=\"row\">
    <div class=\"col-sm-3 \">Unit Designator </div>
    <div class=\"col-sm-2 \">Serial Number</div>
    <div class=\"col-sm-2 \">Type</div>
    <div class=\"col-sm-3 \"> </div>
    </div><br/>";

    echo "<tr><div class=\"row\">
<div class=\"col-sm-3 \">
<select name=\"pl\" id=\"pl\"style=\"width: 250px; height: 30px; border-radius: 5px;\">";
while($row= oci_fetch_array($getpole)){
    echo "<option value=\"$row[0]\">$row[0]</option>";
}
echo"</select>
</div>
<div class=\"col-sm-2\">
<input style=\"height: 30px; border-radius: 5px;\" id=\"sn\" name=\"sn\" type=\"test\"  class=\"form-control\" >

</div>
<div class=\"col-sm-2 \">
<select id=\"poleP\" name=\"poleP\" style=\"width: 50px; height: 30px; border-radius: 5px;\">
<option value=\"P0\">P0</option>
<option value=\"P1\">P1</option>
</select>
</div>

<div class=\"form-group\">
  <div style=\"padding-left: 1px;\" class=\"col-sm-2\">
    <button style=\" width: 80px; height: 32px; border-radius: 5px;\" onclick=\"addmet('$so_id','PL','$cir')\" id=\"add-more\" name=\"add-more\" class=\"btn btn-primary\">Add</button>
  </div>

</div>
</div></tr><br/>";

}

	//OTHER MET
    if($getcon[0] == 'WITH POLES VOICE ONLY' || $getcon[0] == 'WITHOUT POLES VOICE ONLY'){
    
    $pkg = 'Single Play';
    }
    
    
    if($getcir[3] == 'AB-CAB'){  
        
        $getothmetcab= getothmetcab($pkg);
        }
        
    if($getcir[3] == 'AB-FTTH'){  
        $getothmetcab= getothmetftth($pkg);
        
        }
    
    
    
    
    
echo "<br/><div class=\"row\">
    <div class=\"col-sm-3 \"><b><u>OTHER METERIALS</u></b> </div>
    <div class=\"col-sm-3 \"> </div>
    <div class=\"col-sm-3 \"> </div>
    <div class=\"col-sm-3 \"> </div>
    </div>";
    echo "<div class=\"row\">
    <div class=\"col-sm-3 \">Unit Designator </div>
    <div class=\"col-sm-2 \"></div>
    <div class=\"col-sm-2 \">Type</div>
    <div class=\"col-sm-3 \"> </div>
    </div><br/>";

if($siu == ''){
    echo "<tr><div class=\"row\">
<div class=\"col-sm-3 \">
<select name=\"othmet\" id=\"othmet\" style=\"width: 200px; height: 30px; border-radius: 5px;\">";
while($row= oci_fetch_array($getothmetcab)){
    echo "<option value=\"$row[0]\">$row[0]</option>";
}
echo"</select>
</div>
<div class=\"col-sm-2\">


</div>
<div class=\"col-sm-2 \">
<select id=\"othmetP\" name=\"othmetP\" style=\"width: 50px; height: 30px; border-radius: 5px;\">
<option value=\"P0\">P0</option>
<option value=\"P1\">P1</option>
</select>
</div>

<div class=\"form-group\">
  <div style=\"padding-left: 1px;\" class=\"col-sm-2\">
    <button style=\" width: 80px; height: 30px; border-radius: 5px;\" onclick=\"addmet('$so_id','OM','$cir')\" id=\"add-more\" name=\"add-more\" class=\"btn btn-primary\">Add</button>
  </div>

</div>
</div></tr><br/>";
}

//L HOOK
if($lh == ''){
echo"
<br/>
<tr><div class=\"row\">
<div class=\"col-sm-3 \">
<select name=\"lh\" id=\"lh\"style=\"width: 200px; height: 30px; border-radius: 5px;\">
<option value=\"DW-LH\">DW-LH</option>
</select>
</div>
<div class=\"col-sm-2\">
<input style=\"height: 30px; border-radius: 5px;\" id=\"lhook\" name=\"lhook\" type=\"number\"  class=\"form-control\" >

</div>
<div class=\"col-sm-2 \">
<select id=\"lhP\" name=\"lhP\" style=\"width: 50px; height: 30px; border-radius: 5px;\">
<option value=\"P0\">P0</option>
<option value=\"P1\">P1</option>
</select>
</div>

<div class=\"form-group\">
  <div style=\"padding-left: 1px;\" class=\"col-sm-2\">
    <button style=\" width: 80px; height: 30px; border-radius: 5px;\" onclick=\"addmet('$so_id','LH','$cir')\" id=\"add-more\" name=\"add-more\" class=\"btn btn-primary\">Add</button>
  </div>

</div>


</div></tr>
";
}

//PLC CON
if($plcon == ''){
echo"
<br/>
<tr><div class=\"row\">
<div class=\"col-sm-3 \">
<select name=\"plch\" id=\"plch\"style=\"width: 200px; height: 30px; border-radius: 5px;\">
<option value=\"PLC-CON\">PLC-CON</option>
</select>
</div>
<div class=\"col-sm-2\">
<input style=\"height: 30px; border-radius: 5px;\" id=\"plc\" name=\"plc\" type=\"number\"  class=\"form-control\" >

</div>
<div class=\"col-sm-2 \">
<select id=\"plcP\" name=\"plcP\" style=\"width: 50px; height: 30px; border-radius: 5px;\">
<option value=\"P0\">P0</option>
<option value=\"P1\">P1</option>
</select>
</div>

<div class=\"form-group\">
  <div style=\"padding-left: 1px;\" class=\"col-sm-2\">
    <button style=\" width: 80px; height: 30px; border-radius: 5px;\" onclick=\"addmet('$so_id','PLC','$cir')\" id=\"add-more\" name=\"add-more\" class=\"btn btn-primary\">Add</button>
  </div>

</div>


</div></tr>

<br/>";
}

if($getftthcab[19] == "" && $level == 5)
{
    echo " 
    <td><input type=\"button\" class=\"btn btn-primary\" id=\"confirm\" name=\"confirm\" onclick=\"approv()\" value=\"Approve\"></td>";
            
}
}else {
	
	echo "<h4 style=\"color:red\">No Data available for this circuit. Pls check.</h4>";
	
} }?> 
 
	</form>

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


</body>
</html>
