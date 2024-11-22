<?php

$r = $_GET['r'];

ini_set('max_execution_time', 300);
session_start();

if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true)
{    
    $user = $_SESSION["user"];
    $contractor_name = $_SESSION["contractor"];
	$area = $_SESSION["area"];
	$opmc = $_SESSION["opmc"];
	
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

$oraconn = OracleConnection();


	
if ($r == "1" ){
	
	$sql = "SELECT DISTINCT B.CON_AREA,A.LEA, A.SO_NUM,A.S_TYPE,TO_CHAR(B.CON_SO_DATE_RECEIVED, 'mm/dd/yyyy hh:mi:ss AM'),
A.VOICENUMBER, C.CON_WORO_ORDER_TYPE, 
C.CON_CUS_NAME,C.CON_TEC_CONTACT, D.CON_ADDE_STREETNUMBER||' '||D.CON_ADDE_STRN_NAMEANDTYPE||' '||D.CON_ADDE_SUBURB||' '||D.CON_ADDE_CITY AS ADDRESS, 
E.CON_OSP_DP_NAME ||'-'||E.CON_OSP_DP_LOOP AS DPNAME,E.CON_OSP_PHONE_CLASS,E.CON_OSP_PHONE_COLOUR,E.CON_PHN_PURCH, 
C.CON_WORO_DISCRIPTION,C.CON_WORO_TASK_NAME, A.CONTRATOR ,A.IPTV,A.EX_NO 
FROM CONTRACTOR_NEW_CON B, CONTRACTOR_WORK_ORDERS C,CONTRACTOR_SERVICE_ADDRESS D,CONTRACTOR_OSP_DATA E , CON_CLARITY_SOLIST A
WHERE A.SO_NUM = C.CON_SERO_ID AND B.CON_SO_ID = C.CON_SERO_ID AND B.CON_SO_ID = D.CON_ADDE_SERO_ID AND B.CON_SO_ID = E.CON_OSP_SERO_ID(+) 
AND A.SO_NUM = A.SO_NUM AND A.STATUS='0' AND A.RTOM IN ($areas) AND A.IPTV IS NOT NULL AND C.CON_STATUS = 'ASSIGNED' AND B.CON_SO_STATUS = 'ASSIGNED'";

	if(isset($_POST['lea']) && $_POST['lea'] != ''){

        $lea = $_POST['lea'];

        $sql.= " AND A1.LEA = '$lea'";

    }
	
	if(isset($_POST['stype']) && $_POST['stype'] != ''){

        $stype = $_POST['stype'];

        $sql.= " AND A.S_TYPE = '$stype'";

    }
	
	$oraconn = OracleConnection();
    $stid=oci_parse($oraconn,$sql);
    oci_execute($stid);
	$result ="";

                                 
  	while($row = oci_fetch_array($stid)){
		
	 $result .='<tr>
				<td><input type="checkbox"></td>
				<td>'.$row['CON_AREA'].'</td>
				<td>'.$row['LEA'].'</td>
				<td>'.$row['SO_NUM'].'</td>
				<td>'.$row['S_TYPE'].'</td>
				<td>'.$row['CON_SO_DATE_RECEIVED'].'</td>
				<td>'.$row['VOICENUMBER'].'</td>
				<td>'.$row['CON_WORO_ORDER_TYPE'].'</td>
				<td>'.$row['CON_CUS_NAME'].'</td>
				<td>'.$row['CON_TEC_CONTACT'].'</td>
				<td>'.$row['ADDRESS'].'</td>
				<td>'.$row['DPNAME'].'</td>
				<td style="display:none">'.$row['CON_OSP_DP_LOOP'].'</td>
				<td style="display:none">'.$row['CON_OSP_PHONE_CLASS'].'</td>
				<td style="display:none">'.$row['CON_PHN_PURCH'].'</td>
				<td style="display:none">'.$row['CON_OSP_PHONE_COLOUR'].'</td>
				<td>'.$row['CON_WORO_DISCRIPTION'].'</td>
				<td>'.$row['CON_WORO_TASK_NAME'].'</td>
				<td>'.$row['CONTRATOR'].'</td>
				<td>'.$row['IPTV'].'</td>
				<td style="display:none">'.$row['EX_NO'].'</td>
				</tr>';
		
	}
	
}


if ($r == "2" ){

  $sql="SELECT CONTRATOR,count(VOICENUMBER) as REC_COUNT FROM CON_CLARITY_SOLIST WHERE CONTRATOR='$contractor' GROUP BY CONTRATOR";

	$oraconn = OracleConnection();
    $stid=oci_parse($oraconn,$sql);
    oci_execute($stid);
	$result ="";
  	while($row = oci_fetch_array($stid)){
		
	 $result .='<tr>
				<td>'.$row['CONTRATOR'].'</td>
				<td>'.$row['REC_COUNT'].'</td>
				</tr>';
		
	}

}

if ($r == "3" ){
	
	$info=$_POST["info"];
	
	$sod=$info[2];
	
	$sqlUpdate1 ="UPDATE CONTRACTOR_WORK_ORDERS SET CON_NAME = '$info[1]' , CON_WORO_DISCRIPTION= '$info[4]' , CON_DATE_TO_CONTRACTOR  = SYSDATE WHERE CON_SERO_ID IN (SELECT SO_NUM  FROM CON_CLARITY_SOLIST  WHERE EX_NO = '$info[3]'  AND STATUS = 0) ";

	$oraconn = OracleConnection();
    $stid2 = oci_parse($oraconn, $sqlUpdate1);
	
	if(oci_execute($stid2)){
		
		$result = 'true';
		
	}else{
		
		$result = 'false';
		
	}	
	
	$sqlUpdate2 ="UPDATE CONTRACTOR_NEW_CON SET CON_CONTRACTOR = '$info[1]' , CON_SO_DATE_RECEIVED  = SYSDATE WHERE CON_SO_ID IN (SELECT SO_NUM  FROM CON_CLARITY_SOLIST  WHERE EX_NO = '$info[3]'  AND STATUS = 0)";

	
	$oraconn = OracleConnection();
    $stid3 = oci_parse($oraconn, $sqlUpdate2);
	
	if(oci_execute($stid3)){
		
		$result = 'true';
		
	}else{
		
		$result = 'false';
		
	}	
	
	$sqlUpdate = "UPDATE CON_CLARITY_SOLIST SET CONTRATOR='$info[1]',STATUS='1',UPDATE_USER='$user',STATUS_DATE=sysdate WHERE VOICENUMBER = '$info[0]' AND STATUS='0'";
    $oraconn = OracleConnection();
    $stid = oci_parse($oraconn, $sqlUpdate);
	
	if(oci_execute($stid)){
		
		$result = 'true';
		
		$msg = 'Order has been assigned to: '.$info[1];
		
		log_all($user,$sod,$msg);
		
	}else{
		
		$result = 'false';
		
		$msg = 'Order has been fail assigned to : '.$info[1];
		
		log_all($user,$sod,$msg);
		
	}	
	
	
}


if ($r == "4" ){
	
	$sql = "SELECT DISTINCT B.CON_AREA,A1.LEA, A.SO_NUM,A.S_TYPE,TO_CHAR(B.CON_SO_DATE_RECEIVED, 'mm/dd/yyyy hh:mi:ss AM'),
A.VOICENUMBER, C.CON_WORO_ORDER_TYPE, 
C.CON_CUS_NAME,C.CON_TEC_CONTACT, D.CON_ADDE_STREETNUMBER||' '||D.CON_ADDE_STRN_NAMEANDTYPE||' '||D.CON_ADDE_SUBURB||' '||D.CON_ADDE_CITY AS ADDRESS, 
E.CON_OSP_DP_NAME ||'-'||E.CON_OSP_DP_LOOP AS DPNAME,E.CON_OSP_PHONE_CLASS,E.CON_OSP_PHONE_COLOUR,E.CON_PHN_PURCH, 
C.CON_WORO_DISCRIPTION,C.CON_WORO_TASK_NAME, A1.CONTRATOR ,A1.IPTV,A1.EX_NO 
FROM CON_CLARITY_SOLIST A, CONTRACTOR_NEW_CON B, CONTRACTOR_WORK_ORDERS C,CONTRACTOR_SERVICE_ADDRESS D,CONTRACTOR_OSP_DATA E , CON_CLARITY_SOLIST A1 
WHERE A.SO_NUM = C.CON_SERO_ID AND B.CON_SO_ID = C.CON_SERO_ID AND B.CON_SO_ID = D.CON_ADDE_SERO_ID AND B.CON_SO_ID = E.CON_OSP_SERO_ID(+) 
AND A.SO_NUM = A1.SO_NUM AND A1.STATUS IN('0','1','4','8') AND A1.RTOM IN ($areas) AND A1.IPTV IS NOT NULL";
	
	
	if(isset($_POST['lea']) && $_POST['lea'] != ''){

        $lea = $_POST['lea'];

        $sql.= " AND A1.LEA = '$lea'";

    }
	
	if(isset($_POST['stype']) && $_POST['stype'] != ''){

        $stype = $_POST['stype'];

        $sql.= " AND A.S_TYPE = '$stype'";

    }
	
	$oraconn = OracleConnection();
    $stid=oci_parse($oraconn,$sql);
    oci_execute($stid);
	
	$result ="";
								
  	while($row = oci_fetch_array($stid)){
		
	 $result .='<tr>
				<td>'.$row['CON_AREA'].'</td>
				<td>'.$row['LEA'].'</td>
				<td>'.$row['SO_NUM'].'</td>
				<td>'.$row['S_TYPE'].'</td>
				<td>'.$row['CON_SO_DATE_RECEIVED'].'</td>
				<td>'.$row['VOICENUMBER'].'</td>
				<td>'.$row['CON_WORO_ORDER_TYPE'].'</td>
				<td>'.$row['CON_CUS_NAME'].'</td>
				<td>'.$row['CON_TEC_CONTACT'].'</td>
				<td>'.$row['ADDRESS'].'</td>
				<td>'.$row['DPNAME'].'</td>
				<td style="display:none">'.$row['CON_OSP_DP_LOOP'].'</td>
				<td style="display:none">'.$row['CON_OSP_PHONE_CLASS'].'</td>
				<td style="display:none">'.$row['CON_PHN_PURCH'].'</td>
				<td style="display:none">'.$row['CON_OSP_PHONE_COLOUR'].'</td>
				<td>'.$row['CON_WORO_DISCRIPTION'].'</td>
				<td>'.$row['CON_WORO_TASK_NAME'].'</td>
				<td>'.$row['CONTRATOR'].'</td>
				<td>'.$row['IPTV'].'</td>
				</tr>';
		
	}

}


if ($r == "5" ){

    $info=$_POST["info"];
	
	$sod=$info[2];
	
    $sqlUpdate = "UPDATE CON_CLARITY_SOLIST SET CONTRATOR='$info[1]',STATUS='1',UPDATE_USER='$user',STATUS_DATE=sysdate WHERE VOICENUMBER = '$info[0]' AND STATUS='1'";
    $oraconn = OracleConnection();
    $stid = oci_parse($oraconn, $sqlUpdate);
	
	if(oci_execute($stid)){
		
		$result = 'true';
		
		$msg = 'Order has been changed to : '.$info[1];
		
		log_all($user,$sod,$msg);
		
	}else{
		
		$result = 'false';
		
		$msg = 'Order has been changed fail to: '.$info[1];
		
		log_all($user,$sod,$msg);
		
	}
	
	
	$sqlUpdate1 ="UPDATE CONTRACTOR_WORK_ORDERS SET CON_NAME = '$info[1]' , CON_DATE_TO_CONTRACTOR  = SYSDATE WHERE CON_SERO_ID IN (SELECT SO_NUM  FROM CON_CLARITY_SOLIST  WHERE EX_NO = '$info[3]'  AND STATUS = 1) ";

	$oraconn = OracleConnection();
    $stid2 = oci_parse($oraconn, $sqlUpdate1);
	
	if(oci_execute($stid2)){
		
		$result = 'true';
		
	}else{
		
		$result = 'false';
		
	}	
	
	$sqlUpdate2 ="UPDATE CONTRACTOR_NEW_CON SET CON_CONTRACTOR = '$info[1]' , CON_SO_DATE_RECEIVED  = SYSDATE WHERE CON_SO_ID IN (SELECT SO_NUM  FROM CON_CLARITY_SOLIST  WHERE EX_NO = '$info[3]'  AND STATUS = 1)";

	
	$oraconn = OracleConnection();
    $stid3 = oci_parse($oraconn, $sqlUpdate2);
	
	if(oci_execute($stid3)){
		
		$result = 'true';
		
	}else{
		
		$result = 'false';
		
	}	
	
	
}


if ($r == "6" ){
 
 $dateToArr = explode('/',$_POST['dTo']);
 $dTo = $dateToArr[2].'-'.$dateToArr[0].'-'.$dateToArr[1];
 
 $dateToArr = explode('/',$_POST['dfrom']);
 $dFrom = $dateToArr[2].'-'.$dateToArr[0].'-'.$dateToArr[1];

 $result ="";
 $HEADER1 = "";

$sql1="SELECT * FROM(SELECT CON_WORO_AREA, CON_NAME,CON_WORO_SERVICE_TYPE,CON_WORO_TASK_NAME,CON_SO_STATUS
FROM (select A.CON_SERO_ID ,A.CON_WORO_TASK_NAME, A.CON_DATE_TO_CONTRACTOR , A.CON_WORO_AREA, A.CON_NAME, B.CON_SO_STATUS, B.CON_SO_RTN_DATE, B.CON_SO_COM_DATE , A.CON_PSTN_NUMBER,A.CON_WORO_SERVICE_TYPE 
from CONTRACTOR_WORK_ORDERS a, CONTRACTOR_NEW_CON b
where A.CON_SERO_ID = B.CON_SO_ID
and A.CON_WORO_TASK_NAME = B.CON_TASK
and to_char(A.CON_DATE_TO_CONTRACTOR,'yyyy-mm-dd') BETWEEN '$dFrom' AND '$dTo') 
)
PIVOT
(
  COUNT(CON_SO_STATUS)
  FOR CON_SO_STATUS IN ('ASSIGNED','INPROGRESS','COMPLETED','RETURNED','REASSIGNED','CONFIRMATION','HOLD')
)
ORDER BY CON_WORO_AREA,CON_NAME";       


    $oraconn = OracleConnection();
    $stid1 = oci_parse($oraconn, $sql1);
    oci_execute($stid1);
	
	$HEADER1 = "RTOM,CONTRACTOR,SERVICE TYPE,TASK NAME,ASSIGNED,INPROGRESS,COMPLETED,RETURNED,REASSIGNED,CONFIRMATION,HOLD\n";
    
	while($row1 = oci_fetch_array($stid1)){
		
		 $HEADER1 = $HEADER1 . "{$row1[0]},{$row1[1]},{$row1[2]},{$row1[3]},{$row1[4]},{$row1[5]},{$row1[6]},{$row1[7]},{$row1[8]},{$row1[9]},{$row1[10]}\n";   
	}
	
	$rpt1 = 'reports/sod_count_wise.csv';
	
	$File = $rpt1;
	
	$FILE_WRITE = fopen($File, 'w') or die("can't open file");
	fwrite($FILE_WRITE, $HEADER1);
	fclose($FILE_WRITE);
	
	$result='done';
	
}

if ($r == "7" ){
 
 $dateToArr = explode('/',$_POST['dTo']);
 $dTo = $dateToArr[2].'-'.$dateToArr[0].'-'.$dateToArr[1];
 
 $dateToArr = explode('/',$_POST['dfrom']);
 $dFrom = $dateToArr[2].'-'.$dateToArr[0].'-'.$dateToArr[1];

 $result ="";
 $HEADER1 = "";

$sql1="  SELECT CON_WORO_AREA, CON_NAME,CON_WORO_SERVICE_TYPE,CON_PSTN_NUMBER,CON_WORO_TASK_NAME,CON_SO_STATUS,
  CASE WHEN CON_SO_STATUS IN ('RETURNED','HOLD') THEN
    (SELECT X.CON_COMM_TEXT FROM CONTRACTOR_ORDER_COMMENTS X WHERE X.CON_COMM_SERO_ID = CON_SERO_ID AND X.CON_COMM_TIME = 
    (SELECT   MAX(A.CON_COMM_TIME) FROM CONTRACTOR_ORDER_COMMENTS A WHERE A.CON_COMM_SERO_ID = CON_SERO_ID ))
  ELSE ''
  END 
  FROM (SELECT A.CON_SERO_ID ,A.CON_WORO_TASK_NAME, A.CON_DATE_TO_CONTRACTOR , A.CON_WORO_AREA, A.CON_NAME, B.CON_SO_STATUS, 
  B.CON_SO_RTN_DATE, B.CON_SO_COM_DATE , A.CON_PSTN_NUMBER,A.CON_WORO_SERVICE_TYPE
FROM CONTRACTOR_WORK_ORDERS A, CONTRACTOR_NEW_CON B
WHERE A.CON_SERO_ID = B.CON_SO_ID
AND A.CON_WORO_TASK_NAME = B.CON_TASK
and to_char(A.CON_DATE_TO_CONTRACTOR,'yyyy-mm-dd') BETWEEN '$dFrom' AND '$dTo')";       


    $oraconn = OracleConnection();
    $stid1 = oci_parse($oraconn, $sql1);
    oci_execute($stid1);
	
	$HEADER1 = "RTOM,CONTRACTOR,SERVICE TYPE,CIRCUIT,TASK NAME,STATUS, RETURN REASON\n";
    
	while($row1 = oci_fetch_array($stid1)){
		
		 $HEADER1 = $HEADER1 . "{$row1[0]},{$row1[1]},{$row1[2]},{$row1[3]},{$row1[4]},{$row1[5]},{$row1[6]}\n";   
	}
	
	$rpt1 = 'reports/sod_detail.csv';
	
	$File = $rpt1;
	
	$FILE_WRITE = fopen($File, 'w') or die("can't open file");
	fwrite($FILE_WRITE, $HEADER1);
	fclose($FILE_WRITE);
	
	$result='done';
	
}

if ($r == "8" ){

	$sql = "SELECT DISTINCT B.CON_AREA,A1.LEA, A.SO_NUM,A.S_TYPE,TO_CHAR(B.CON_SO_DATE_RECEIVED, 'mm/dd/yyyy hh:mi:ss AM'),
A.VOICENUMBER, C.CON_WORO_ORDER_TYPE, 
C.CON_CUS_NAME,C.CON_TEC_CONTACT, D.CON_ADDE_STREETNUMBER||' '||D.CON_ADDE_STRN_NAMEANDTYPE||' '||D.CON_ADDE_SUBURB||' '||D.CON_ADDE_CITY AS ADDRESS, 
E.CON_OSP_DP_NAME ||'-'||E.CON_OSP_DP_LOOP AS DPNAME,E.CON_OSP_PHONE_CLASS,E.CON_OSP_PHONE_COLOUR,E.CON_PHN_PURCH, 
C.CON_WORO_DISCRIPTION,C.CON_WORO_TASK_NAME, A1.CONTRATOR ,A1.IPTV,A1.EX_NO 
FROM CON_CLARITY_SOLIST A, CONTRACTOR_NEW_CON B, CONTRACTOR_WORK_ORDERS C,CONTRACTOR_SERVICE_ADDRESS D,CONTRACTOR_OSP_DATA E , CON_CLARITY_SOLIST A1 
WHERE A.SO_NUM = C.CON_SERO_ID AND B.CON_SO_ID = C.CON_SERO_ID AND B.CON_SO_ID = D.CON_ADDE_SERO_ID AND B.CON_SO_ID = E.CON_OSP_SERO_ID(+) 
AND A.SO_NUM = A1.SO_NUM AND A1.STATUS = '4' AND A1.RTOM IN ($areas) AND A1.IPTV IS NOT NULL";
	
	
	if(isset($_POST['lea']) && $_POST['lea'] != ''){

        $lea = $_POST['lea'];

        $sql.= " AND A1.LEA = '$lea'";

    }
	
	if(isset($_POST['stype']) && $_POST['stype'] != ''){

        $stype = $_POST['stype'];

        $sql.= " AND A.S_TYPE = '$stype'";

    }

	$oraconn = OracleConnection();
    $stid=oci_parse($oraconn,$sql);
    oci_execute($stid);
	
	$result ="";
								
  	while($row = oci_fetch_array($stid)){
		
	 $result .='<tr>
				<td>'.$row['CON_AREA'].'</td>
				<td>'.$row['LEA'].'</td>
				<td>'.$row['SO_NUM'].'</td>
				<td>'.$row['S_TYPE'].'</td>
				<td>'.$row['CON_SO_DATE_RECEIVED'].'</td>
				<td>'.$row['VOICENUMBER'].'</td>
				<td>'.$row['CON_WORO_ORDER_TYPE'].'</td>
				<td>'.$row['CON_CUS_NAME'].'</td>
				<td>'.$row['CON_TEC_CONTACT'].'</td>
				<td>'.$row['ADDRESS'].'</td>
				<td>'.$row['CON_OSP_DP_NAME'].'</td>
				<td>'.$row['CON_OSP_DP_LOOP'].'</td>
				<td>'.$row['CON_OSP_PHONE_CLASS'].'</td>
				<td>'.$row['CON_PHN_PURCH'].'</td>
				<td>'.$row['CON_OSP_PHONE_COLOUR'].'</td>
				<td>'.$row['CON_WORO_DISCRIPTION'].'</td>
				<td>'.$row['CON_WORO_TASK_NAME'].'</td>
				<td><select style="width:110px;margin-left:10%" class="form-control" id="asn_user">
					<option value=""></option>';

					   $sql2 = "SELECT DISTINCT CON_MGT_CONTRACTOR_NAME
							   FROM CONTRACTOR_MGT_USERS
							   WHERE CON_MGT_CONTRACTOR_NAME NOT IN ('SLTQC')";
					   
						$stid2=oci_parse($oraconn,$sql2);
						
						oci_execute($stid2);

						while ($row2 = oci_fetch_array($stid2))
						{
							
							if($row2['CON_MGT_CONTRACTOR_NAME'] == $row['CONTRATOR']){
								
								$result .= '<option value="'.$row2['CON_MGT_CONTRACTOR_NAME'].'" selected>'.$row2['CON_MGT_CONTRACTOR_NAME'].'</option>';
								
							}else{
								
								$result .= '<option value="'.$row2['CON_MGT_CONTRACTOR_NAME'].'">'.$row2['CON_MGT_CONTRACTOR_NAME'].'</option>';
							
							}
						}
         
				$result .='</select></td>
				<td>'.$row['IPTV'].'</td>
				<td style="display:none">'.$row['EX_NO'].'</td>
				<td><button type="button" class="btn btn-warning mb-2 float-right"  style="color:white" id="btnChange" onClick="reassignOrder(this);">Reassign</button></td>
				<td><button type="button" class="btn btn-danger mb-4 float-right" style="width:170px;" id="btnChange" onClick="cancleOrder(this);">Return To Clarity</button></td>
				</tr>';
		
	}

}


if ($r == "9" ){
	
	$info=$_POST["info"];
	
	$sod=$info[2];
	
	$sqlUpdate1 ="UPDATE CONTRACTOR_WORK_ORDERS SET CON_NAME = '$info[1]' , CON_WORO_DISCRIPTION= '$info[4]' , CON_DATE_TO_CONTRACTOR  = SYSDATE ,CON_STATUS = 'ASSIGNED' WHERE CON_SERO_ID IN (SELECT SO_NUM  FROM CON_CLARITY_SOLIST  WHERE EX_NO = '$info[3]'  AND STATUS = 4) ";

	$oraconn = OracleConnection();
    $stid2 = oci_parse($oraconn, $sqlUpdate1);
	
	if(oci_execute($stid2)){
		
		$result = 'true';
		
	}else{
		
		$result = 'false';
		
	}	
	
	$sqlUpdate2 ="UPDATE CONTRACTOR_NEW_CON SET CON_CONTRACTOR = '$info[1]' , CON_SO_DATE_RECEIVED  = SYSDATE ,CON_SO_STATUS = 'ASSIGNED' WHERE CON_SO_ID IN (SELECT SO_NUM  FROM CON_CLARITY_SOLIST  WHERE EX_NO = '$info[3]'  AND STATUS = 4)";

	
	$oraconn = OracleConnection();
    $stid3 = oci_parse($oraconn, $sqlUpdate2);
	
	if(oci_execute($stid3)){
		
		$result = 'true';
		
	}else{
		
		$result = 'false';
		
	}	
	
	$sqlUpdate = "UPDATE CON_CLARITY_SOLIST SET CONTRATOR='$info[1]',STATUS='1',UPDATE_USER='$user',STATUS_DATE=sysdate WHERE VOICENUMBER = '$info[0]' AND STATUS='4'";
    $oraconn = OracleConnection();
    $stid = oci_parse($oraconn, $sqlUpdate);
	
	if(oci_execute($stid)){
		
		$result = 'true';
		
		$msg = 'Order return to: '.$info[1];
		
		log_all($user,$sod,$msg);
		
	}else{
		
		$result = 'false';
		
		$msg = 'Order return error to : '.$info[1];
		
		log_all($user,$sod,$msg);
		
	}	
	
	
}


if ($r == "10" ){
	
	$info=$_POST["info"];
	
	$sod=$info[2];
	
	$sqlUpdate = "UPDATE CON_CLARITY_SOLIST SET STATUS='5',UPDATE_USER='$user',STATUS_DATE=sysdate WHERE VOICENUMBER = '$info[0]' AND STATUS='4' ";
    $oraconn = OracleConnection();
    $stid = oci_parse($oraconn, $sqlUpdate);
	
	if(oci_execute($stid)){
		
		$result = 'true';
		
		$msg = 'Order cancel: '.$info[1];
		
		log_all($user,$sod,$msg);
		
	}else{
		
		$result = 'false';
		
		$msg = 'Order cancel error : '.$info[1];
		
		log_all($user,$sod,$msg);
		
	}	
	
	
}

if ($r == "11" ){

$sql = "SELECT DISTINCT a.CON_SO_ID, a.CON_CIRCUIT_NO ,b.CON_WORO_SERVICE_TYPE,a.CON_AREA
        FROM CONTRACTOR_NEW_CON A, CONTRACTOR_WORK_ORDERS B
        WHERE  A.CON_SO_ID = B.CON_SERO_ID
        AND A.CON_TASK = B.CON_WORO_TASK_NAME
        and a.CON_CONTRACTOR = '".$contractor_name."'
        AND a.CON_SO_STATUS = 'INPROGRESS'
        and a.CON_SO_ID   in (        
        SELECT DISTINCT ax.CON_SO_ID
       FROM CONTRACTOR_NEW_CON Ax
        WHERE ax.CON_CONTRACTOR = '".$contractor_name."'
        AND ax.CON_SO_STATUS = 'INPROGRESS'
        minus
        SELECT SO FROM MOB_SERVICE_LIST) ";  

   
    if(isset($_POST['rtom'])){

        $rtom = $_POST['rtom'];

        if($rtom != ''){

        $sql.= " AND a.CON_AREA = '$rtom'";

        }

    }
	
    $stid=oci_parse($oraconn,$sql);
    oci_execute($stid);
	$result ="";
	$result .='<table class="fixed_header table" id="orders" style="font-size:13px; ">
							  <thead class="thead-light" >
								<tr style="text-align:center;">
								  <th scope="col" style="min-width:22%">Service Order ID</th>
								  <th scope="col" style="min-width:18%">Circuit No</th>
								  <th scope="col" style="min-width:16%">Service Type</th>
								  <th scope="col" style="min-width:10%">RTOM</th>
								  <th scope="col" style="min-width:18%">Select Order<input type="checkbox" id="chkAll"></th>
								</tr>
							  </thead>
							  <tbody>';

							
  	while($row = oci_fetch_array($stid)){
		
	 $result .='<tr style="text-align:left;">
				<td width="26%">'.$row['CON_SO_ID'].'</td>
				<td width="20%">'.$row['CON_CIRCUIT_NO'].'</td>
				<td width="16%">'.$row['CON_WORO_SERVICE_TYPE'].'</td>
				<td width="10%">'.$row['CON_AREA'].'</td>
				<td width="18%"><input type="checkbox"></td>
				</tr>';
		
	}
	
	 $result .='</tbody></table>';
	
}

if ($r == "12" ){

  $sql="SELECT USER_NAME,count(SO) as REC_COUNT FROM MOB_SERVICE_LIST WHERE CONTRACTOR = '$contractor'";
   
    if(isset($_POST['rtom'])){

        $rtom = $_POST['rtom'];

        if($rtom != ''){

        $sql.= " AND RTOM = '$rtom'";

        }

    }
	
	$sql.=" GROUP BY USER_NAME";

    $stid=oci_parse($oraconn,$sql);
    oci_execute($stid);
	$result ="";
  	while($row = oci_fetch_array($stid)){
		
	 $result .='<tr>
				<td>'.$row['USER_NAME'].'</td>
				<td>'.$row['REC_COUNT'].'</td>
				</tr>';
		
	}

}


if ($r == "13" ){

    $info=$_POST["info"];

	if($info[2] == 'E-IPTV COPPER'){
		
		$rescount = '4';
		
	}
	
	if($info[2] == 'E-IPTV FTTH'){
		
		$rescount = '2';
		
	}
	
	if($info[2] == 'AB-CAB'){
		
		$rescount = '8';
		
	}
	
	if($info[2] == 'AB-FTTH'){
		
		$rescount = '9';
		
	}
	
    $sqlInsert = "INSERT INTO MOB_SERVICE_LIST (CONTRACTOR, USER_NAME, TP, SO,S_TYPE,RES_COUNT,UP_COUNT,STATUS,RES_STRING,RTOM,POLE_COUNT) VALUES ('$contractor','$info[4]','$info[1]','$info[0]','$info[2]','$rescount','0','0','','$info[3]','0')";
    $oraconn = OracleConnection();
    $result = oci_parse($oraconn, $sqlInsert);
	
	if(oci_execute($result)){
		
		$result = 'done';
		
	}
	
	
}

if ($r == "14" ){

 $sql="SELECT * FROM MOB_SERVICE_LIST WHERE STATUS = '0' AND CONTRACTOR = '$contractor'";
   
    if(isset($_POST['rtom'])){

        $rtom = $_POST['rtom'];

        if($rtom != ''){

        $sql.= " AND RTOM = '$rtom'";

        }

    }
	
	$oraconn = OracleConnection();
    $stid=oci_parse($oraconn,$sql);
    oci_execute($stid);
	
	$result ="";
	
	$result .='<table class="fixed_header table" id="orders" style="font-size:13px;">
                                  <thead class="thead-light">
                                    <tr>
                                      <th scope="col" style="min-width:23%">Service Order ID</th>
                                      <th scope="col" style="max-width:18%">Circuit No</th>
                                      <th scope="col" style="max-width:18%">Service Type</th>
									  <th scope="col" style="max-width:18%">RTOM</th>
                                      <th scope="col" style="max-width:18%">User</th>
									  <th scope="col" style="max-width:18%">Action</th>
                                    </tr>
                                  </thead>
                                  <tbody >';

                               
								
  	while($row = oci_fetch_array($stid)){
		
	 $result .='<tr>
				<td style="min-width:23%">'.$row['SO'].'</td>
				<td style="min-width:16%">'.$row['TP'].'</td>
				<td style="min-width:16%">'.$row['S_TYPE'].'</td>
				<td>'.$row['RTOM'].'</td>
				<td><select style="width:120px" class="form-control" id="asn_user">
					<option value=""></option>';

					   $sql2 = "SELECT DISTINCT UNAME FROM MOB_USERLIST";
					   
						$stid2=oci_parse($oraconn,$sql2);
						
						oci_execute($stid2);

						while ($row2 = oci_fetch_array($stid2))
						{
							
							if($row2['UNAME'] == $row['USER_NAME']){
								
								$result .= '<option value="'.$row2['UNAME'].'" selected>'.$row2['UNAME'].'</option>';
								
							}else{
								
								$result .= '<option value="'.$row2['UNAME'].'">'.$row2['UNAME'].'</option>';
							
							}
						}
         
				$result .='</select></td>
				<td><button type="button" class="btn btn-warning mb-2 float-right"  id="btnChange" onClick="changeAssgnOrder(this);">Change</button></td>
				</tr>';
		
	}
	
	$result .='</tbody></table>';

}


if ($r == "15" ){

    $info=$_POST["info"];
	
    $sqlUpdate = "UPDATE MOB_SERVICE_LIST SET USER_NAME='$info[1]' WHERE SO = '$info[0]'";
    $oraconn = OracleConnection();
    $result = oci_parse($oraconn, $sqlUpdate);
	
	if(oci_execute($result)){
		
		$result = 'true';
		
	}else{
		
		$result = 'false';
		
	}	
	
}


if($r == "16"){
	
	$result="";
	
	$soid=$_POST["soid"];
	
	$s_order_no = "'".$soid."'";
	
	$result .='<!DOCTYPE html>
		<html>
		<head>
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">
		<link href="https://fonts.googleapis.com/css?family=Baskervville&display=swap" rel="stylesheet"> 
		</head>
		<body>
		<table class="table" style="width:100%;font-family:Baskervville, serif;">';
			
   $query = "SELECT DISTINCT A.SO_NUM,A.S_TYPE,TO_CHAR(B.CON_SO_DATE_RECEIVED, 'mm/dd/yyyy hh:mi:ss AM'),A.VOICENUMBER,B.CON_AREA, C.CON_WORO_ORDER_TYPE,
        C.CON_CUS_NAME,C.CON_TEC_CONTACT, D.CON_ADDE_STREETNUMBER||' '||D.CON_ADDE_STRN_NAMEANDTYPE||' '||D.CON_ADDE_SUBURB||' '||D.CON_ADDE_CITY,
        E.CON_OSP_DP_NAME,E.CON_OSP_DP_LOOP,E.CON_OSP_PHONE_CLASS,E.CON_OSP_PHONE_COLOUR,E.CON_PHN_PURCH,C.CON_WORO_DISCRIPTION,C.CON_WORO_TASK_NAME, CONTRATOR
        FROM CON_CLARITY_SOLIST A, CONTRACTOR_NEW_CON B, CONTRACTOR_WORK_ORDERS C,CONTRACTOR_SERVICE_ADDRESS D,CONTRACTOR_OSP_DATA E
        WHERE A.SO_NUM = C.CON_SERO_ID
        AND B.CON_SO_ID = C.CON_SERO_ID
        AND B.CON_SO_ID = D.CON_ADDE_SERO_ID
        AND B.CON_SO_ID = E.CON_OSP_SERO_ID(+)
        AND A.SO_NUM IN ($s_order_no)"; 

		  $stid=oci_parse($oraconn,$query);
		  oci_execute($stid);

		  $row = oci_fetch_array($stid); 
		
		  $result .='<tr><td>SO Number:</td><td>'.$row[0].'</td></tr>';
		  $result .='<tr><td>Service Type:</td><td>'.$row[1].'</td></tr>';
		  $result .='<tr><td>SO Date Recived:</td><td>'.$row[2].'</td></tr>';
		  $result .='<tr><td>Voice Number:</td><td>'.$row[3].'</td></tr>';
		  $result .='<tr><td>Area:</td><td>'.$row[4].'</td></tr>';
		  $result .='<tr><td>Work Order Type:</td><td>'.$row[5].'</td></tr>';
		  $result .='<tr><td>Customer Name:</td><td>'.$row[6].'</td></tr>';
		  $result .='<tr><td>TEC Contact:</td><td>'.$row[7].'</td></tr>';
		  $result .='<tr><td>Address:</td><td>'.$row[8].'</td></tr>';
		  $result .='<tr><td>OSP DP Name:</td><td>'.$row[9].'</td></tr>';
		  $result .='<tr><td>OSP DP Loop:</td><td>'.$row[10].'</td></tr>';
		  $result .='<tr><td>OSP Phone Class:</td><td>'.$row[11].'</td></tr>';
		  $result .='<tr><td>OSP Phone Colour:</td><td>'.$row[12].'</td></tr>';
		  $result .='<tr><td>PHN Purch:</td><td>'.$row[13].'</td></tr>';
		  $result .='<tr><td>WORO Discription:</td><td>'.$row[14].'</td></tr>';
		  $result .='<tr><td>WORO Task Name:</td><td>'.$row[15].'</td></tr>';


	$result .='</table>
			</body>
			</html>';
}


//-------------------------start chart1 data -----------------------------------//
if ($r == "17" ){

  $dFrom = date('Y').'-'.date('m').'-'.'01';

  $dTo = date("Y-m-d"); 
  
  $result ="";
  
  //---------------------------------------- work orders ----------------------------------------------//
   $sql1="SELECT count(*) AS REC_COUNT
		FROM CON_CLARITY_SOLIST
		WHERE STATUS = '0' AND
		S_TYPE='AB-CAB' AND
		RTOM IN ($areas)";
   
	//echo $sql1;
	$stid1=oci_parse($oraconn,$sql1);
    oci_execute($stid1);
  	$row1 = oci_fetch_array($stid1);
    $result .= $row1['REC_COUNT'].",";
	
	
  //-----------------------------------------Assign Orders--------------------------------------------------//
   $sql2="SELECT count(*) AS REC_COUNT
		FROM CON_CLARITY_SOLIST
		WHERE STATUS = '1' AND
		S_TYPE='AB-CAB' AND
		RTOM IN ($areas)";

    //echo $sql2;

    $stid2=oci_parse($oraconn,$sql2);
    oci_execute($stid2);
  	$row2 = oci_fetch_array($stid2);
    $result .= $row2['REC_COUNT'].",";

//-------------------------------------------Return Orders----------------------------------------------//
  $sql3="SELECT count(*) AS REC_COUNT
		FROM CON_CLARITY_SOLIST
		WHERE STATUS = '5' AND
		S_TYPE='AB-CAB' AND
		RTOM IN ($areas)";
  
    //echo $sql3;

    $stid3=oci_parse($oraconn,$sql3);
    oci_execute($stid3);
  	$row3 = oci_fetch_array($stid3);
    $result .= $row3['REC_COUNT'];
	
		
}
//-------------------------end chart1 data -----------------------------------//


//-------------------------start chart2 data -----------------------------------//
if ($r == "18" ){

  $dFrom = date('Y').'-'.date('m').'-'.'01';

  $dTo = date("Y-m-d"); 
  
  $result ="";
  
  //---------------------------------------- work orders ----------------------------------------------//
   $sql1="SELECT count(*) AS REC_COUNT
		FROM CON_CLARITY_SOLIST
		WHERE STATUS = '0' AND
		S_TYPE='E-IPTV COPPER' AND
		RTOM IN ($areas)";
   
	//echo $sql1;
	$stid1=oci_parse($oraconn,$sql1);
    oci_execute($stid1);
  	$row1 = oci_fetch_array($stid1);
    $result .= $row1['REC_COUNT'].",";
	
	
  //-----------------------------------------Assign Orders--------------------------------------------------//
   $sql2="SELECT count(*) AS REC_COUNT
		FROM CON_CLARITY_SOLIST
		WHERE STATUS = '1' AND
		S_TYPE='E-IPTV COPPER' AND
		RTOM IN ($areas)";

    //echo $sql2;

    $stid2=oci_parse($oraconn,$sql2);
    oci_execute($stid2);
  	$row2 = oci_fetch_array($stid2);
    $result .= $row2['REC_COUNT'].",";

//-------------------------------------------Return Orders----------------------------------------------//
  $sql3="SELECT count(*) AS REC_COUNT
		FROM CON_CLARITY_SOLIST
		WHERE STATUS = '5' AND
		S_TYPE='E-IPTV COPPER' AND
		RTOM IN ($areas)";
  
    //echo $sql3;

    $stid3=oci_parse($oraconn,$sql3);
    oci_execute($stid3);
  	$row3 = oci_fetch_array($stid3);
    $result .= $row3['REC_COUNT'];
	
		
}
//-------------------------end chart2 data -----------------------------------//


//-------------------------start chart3 data -----------------------------------//
if ($r == "19" ){

  $dFrom = date('Y').'-'.date('m').'-'.'01';

  $dTo = date("Y-m-d"); 
  
  $result ="";
  
  //---------------------------------------- work orders ----------------------------------------------//
   $sql1="SELECT count(*) AS REC_COUNT
		FROM CON_CLARITY_SOLIST
		WHERE STATUS = '0' AND
		S_TYPE='E-IPTV FTTH' AND
		RTOM IN ($areas)";
   
	//echo $sql1;
	$stid1=oci_parse($oraconn,$sql1);
    oci_execute($stid1);
  	$row1 = oci_fetch_array($stid1);
    $result .= $row1['REC_COUNT'].",";
	
	
  //-----------------------------------------Assign Orders--------------------------------------------------//
   $sql2="SELECT count(*) AS REC_COUNT
		FROM CON_CLARITY_SOLIST
		WHERE STATUS = '1' AND
		S_TYPE='E-IPTV FTTH' AND
		RTOM IN ($areas)";

    //echo $sql2;

    $stid2=oci_parse($oraconn,$sql2);
    oci_execute($stid2);
  	$row2 = oci_fetch_array($stid2);
    $result .= $row2['REC_COUNT'].",";

//-------------------------------------------Return Orders----------------------------------------------//
  $sql3="SELECT count(*) AS REC_COUNT
		FROM CON_CLARITY_SOLIST
		WHERE STATUS = '5' AND
		S_TYPE='E-IPTV FTTH' AND
		RTOM IN ($areas)";
  
    //echo $sql3;

    $stid3=oci_parse($oraconn,$sql3);
    oci_execute($stid3);
  	$row3 = oci_fetch_array($stid3);
    $result .= $row3['REC_COUNT'];
	
		
}
//-------------------------end chart3 data -----------------------------------//


//-------------------------start chart4 data -----------------------------------//
if ($r == "20" ){

  $dFrom = date('Y').'-'.date('m').'-'.'01';

  $dTo = date("Y-m-d"); 
  
  $result ="";
  
  //---------------------------------------- work orders ----------------------------------------------//
   $sql1="SELECT count(*) AS REC_COUNT
		FROM CON_CLARITY_SOLIST
		WHERE STATUS = '0' AND
		S_TYPE='AB-FTTH' AND
		RTOM IN ($areas)";
   
	//echo $sql1;
	$stid1=oci_parse($oraconn,$sql1);
    oci_execute($stid1);
  	$row1 = oci_fetch_array($stid1);
    $result .= $row1['REC_COUNT'].",";
	
	
  //-----------------------------------------Assign Orders--------------------------------------------------//
   $sql2="SELECT count(*) AS REC_COUNT
		FROM CON_CLARITY_SOLIST
		WHERE STATUS = '1' AND
		S_TYPE='AB-FTTH' AND
		RTOM IN ($areas)";

    //echo $sql2;

    $stid2=oci_parse($oraconn,$sql2);
    oci_execute($stid2);
  	$row2 = oci_fetch_array($stid2);
    $result .= $row2['REC_COUNT'].",";

//-------------------------------------------Return Orders----------------------------------------------//
  $sql3="SELECT count(*) AS REC_COUNT
		FROM CON_CLARITY_SOLIST
		WHERE STATUS = '5' AND
		S_TYPE='AB-FTTH' AND
		RTOM IN ($areas)";
  
    //echo $sql3;

    $stid3=oci_parse($oraconn,$sql3);
    oci_execute($stid3);
  	$row3 = oci_fetch_array($stid3);
    $result .= $row3['REC_COUNT'];
	
		
}
//-------------------------end chart4 data -----------------------------------//

//-------------------------start chart5 data -----------------------------------//
if ($r == "21" ){

  $dFrom = date('Y').'-'.date('m').'-'.'01';

  $dTo = date("Y-m-d"); 
  
  $result ="";

  //---------------------------------------------------qc pass-----------------------------//
$sql1="select  DISTINCT COUNT(CON_SO_ID) AS REC_COUT FROM (select distinct * from CONTRACTOR_INV_PASSCHK a,CONTRACTOR_WORK_ORDERS b WHERE A.CON_SO_ID = B.CON_SERO_ID AND A.CON_STATUS = '2' and a.CON_TASK = b.CON_WORO_TASK_NAME AND A.qty_st= '20' AND A.CON_AREA IN ($areas)
		union
        select distinct * from CONTRACTOR_INV_PASSED
        a,CONTRACTOR_WORK_ORDERS b WHERE A.CON_SO_ID = B.CON_SERO_ID and a.CON_TASK = b.CON_WORO_TASK_NAME AND A.qty_st= '10' AND A.CON_STATUS = '2' AND A.CON_AREA IN ($areas) )";

    //echo $sql1;
    $stid1=oci_parse($oraconn,$sql1);
    oci_execute($stid1);

    $row1 = oci_fetch_array($stid1);

    $result .= $row1['REC_COUT'].",";
	
	
	//---------------------------------------------------RTOM-----------------------------//
$sql2="select DISTINCT COUNT(CON_SO_ID) AS REC_COUT FROM (select distinct * from CONTRACTOR_INV_PASSCHK a,CONTRACTOR_WORK_ORDERS b WHERE A.CON_SO_ID = B.CON_SERO_ID AND A.CON_STATUS = '2' and a.CON_TASK = b.CON_WORO_TASK_NAME AND A.qty_st= '30' AND A.CON_AREA IN ($areas)
	   union
       select distinct * from CONTRACTOR_INV_PASSED
       a,CONTRACTOR_WORK_ORDERS b WHERE A.CON_SO_ID = B.CON_SERO_ID and a.CON_TASK = b.CON_WORO_TASK_NAME AND A.qty_st= '10' AND A.CON_STATUS = '2' AND A.CON_AREA IN ($areas) )";

    //echo $sql2;
    $stid2=oci_parse($oraconn,$sql2);
    oci_execute($stid2);

    $row2 = oci_fetch_array($stid2);

    $result .= $row2['REC_COUT'].",";
	
//-------------------------------------------------------to be invoiced------------------------------------------//
	$sql3="select  DISTINCT COUNT(CON_SO_ID) AS REC_COUT FROM (select distinct * from CONTRACTOR_INV_PASSCHK a,CONTRACTOR_WORK_ORDERS b WHERE A.CON_SO_ID = B.CON_SERO_ID AND A.CON_STATUS = '2' and a.CON_TASK = b.CON_WORO_TASK_NAME AND A.qty_st= '20' A.CON_AREA IN ($areas) AND a.INV_NO is null
		   union
           select distinct * from CONTRACTOR_INV_PASSED
           a,CONTRACTOR_WORK_ORDERS b WHERE A.CON_SO_ID = B.CON_SERO_ID and a.CON_TASK = b.CON_WORO_TASK_NAME AND A.qty_st= '10' AND A.CON_STATUS = '2' and  A.CON_AREA IN ($areas) AND  a.INV_NO is null)";

   // echo $sql3;
    $stid3=oci_parse($oraconn,$sql3);
    oci_execute($stid3);

    $row3 = oci_fetch_array($stid3);

    $result .= $row3['REC_COUT'];
		
	
		
}
//-------------------------end chart5 data -----------------------------------//


if ($r == "22" ){

    $group_name = $_POST["group_name"];
	
    $sql = "INSERT INTO OPMC_GROUP (GROUP_NAME, GROUP_OPMC, UPDATE_USER,UPDATE_DATE) VALUES ('$group_name', '$opmc', '$user',sysdate)";
    $oraconn = OracleConnection();
    $result = oci_parse($oraconn, $sql);
	
	$sql2 = "INSERT INTO OPMC_GROUP_CREATE_LOG (GROUP_NAME, CREATED_USER, CREATED_DATE) VALUES ('$group_name', '$user', sysdate)";
    $oraconn = OracleConnection();
    $result2 = oci_parse($oraconn, $sql2);
	oci_execute($result2);
	
	if(oci_execute($result)){
		
		$result = 'true';
		
	}else{
		
		$result = 'false';
		
	}	
	
}


if ($r == "23" ){

    $service_id = $_POST["service_id"];
	$emp_name = $_POST["emp_name"];
	$cmb_group = $_POST["cmb_group"];
	$mobile_no = $_POST["mobile_no"];
	
    $sql = "INSERT INTO OPMC_USER (EMP_NUM, EMP_NAME, EMP_GROUP,EMP_OPMC,EMP_MOB,EMP_SUPERVISOR,UPDATE_USER,UPDATE_DATE) VALUES ('$service_id', '$emp_name', '$cmb_group','$opmc','$mobile_no','$user','$user',sysdate)";
    $oraconn = OracleConnection();
    $result = oci_parse($oraconn, $sql);
	
	$txt = 'Create New user in to group - '.$cmb_group;
	
	$sql2 = "INSERT INTO OPMC_USER_LOG (SERVICE_ID, UPDATED_DATE, UPDATED_USER,REMARK) VALUES ('$service_id',sysdate,'$user','$txt')";

	$result2 = oci_parse($oraconn, $sql2);
	oci_execute($result2);
	
	if(oci_execute($result)){
		
		$result = 'true';
		
	}else{
		
		$result = 'false';
		
	}	
	
}


if ($r == "24" ){

    $group = $_POST["group"];
	$voice_No = $_POST["voice_No"];
	$rtom = $_POST["rtom"];
	$so_no = $_POST["so_no"];
	$stype = $_POST["stype"];	
	$taskName = $_POST["taskName"];	
	
	$txt = 'SOD Task ('.$taskName.') INPROGRESS in group - '.$group;
	
	$sql2 = "INSERT INTO OPMC_LOG (SO_NUM, STATUS_DATE, STATUS,SO_REMARK,UPDATED_USER,SERVICE_TYPE) VALUES ('$so_no',sysdate,'1','$txt','$user','$stype')";
    $oraconn = OracleConnection();
    $result2 = oci_parse($oraconn, $sql2);
	oci_execute($result2);
	
    $sql = "UPDATE OPMC_SOLIST SET USER_GP = '$group',UPDATE_USER = '$user',STATUS_DATE = sysdate,STATUS = '1' WHERE RTOM = '$rtom' and VOICENUMBER = '$voice_No' and SO_NUM = '$so_no' and STATUS = '0' and S_TYPE = '$stype'";
    $oraconn = OracleConnection();
    $result = oci_parse($oraconn, $sql);
	
	if(oci_execute($result)){
		
		$result = 'true';
		
	}else{
		
		$result = 'false';
		
	}	
	
}


if ($r == "25" ){
	
	$voice_No = $_POST["voice_No"];
	$rtom = $_POST["rtom"];
	$so_no = $_POST["so_no"];
	$stype = $_POST["stype"];
	$group = $_POST["group"];
	$taskName = $_POST["taskName"];	
	
	$txt = 'SOD Task ('.$taskName.') CANCELED in group - '.$group;
	
	$sql2 = "INSERT INTO OPMC_LOG (SO_NUM, STATUS_DATE, STATUS,SO_REMARK,UPDATED_USER,SERVICE_TYPE) VALUES ('$so_no',sysdate,'0','$txt','$user','$stype')";
    $oraconn = OracleConnection();
    $result2 = oci_parse($oraconn, $sql2);
	oci_execute($result2);
	
    $sql = "UPDATE OPMC_SOLIST SET USER_GP = '',UPDATE_USER = '$user',STATUS_DATE = sysdate,STATUS = '0' WHERE RTOM = '$rtom' and VOICENUMBER = '$voice_No' and SO_NUM = '$so_no' and STATUS = '1' and S_TYPE = '$stype'";
    $oraconn = OracleConnection();
    $result = oci_parse($oraconn, $sql);
	
	if(oci_execute($result)){
		
		$result = 'true';
		
	}else{
		
		$result = 'false';
		
	}	
	
}


if ($r == "26" ){
	
	$voice_no = $_POST["voice_no"];
	$rtom = $_POST["rtom"];
	$so_no = $_POST["so_no"];
	$stype = $_POST["stype"];
	$group = $_POST["group"];
	$taskName = $_POST["taskName"];	
	$dwlength = $_POST["dwlength"];	
	$unit_desig = $_POST["unit_desig"];	
	
	$sql4="SELECT * FROM OPMC_CONNECTION_GROSS WHERE STATUS = '1' AND SERVICE_TYPE = '$stype'";
   
    $oraconn = OracleConnection();
	$stid4=oci_parse($oraconn,$sql4);
    oci_execute($stid4);
  	$row4 = oci_fetch_array($stid4);
	
    $Ref_code = $row4['REF_CODE'];

	$txt = 'Ref_Code - '.$Ref_code.'/ SOD Task ('.$taskName.') COMPLETED in group - '.$group;
	
	$sql2 = "INSERT INTO OPMC_LOG (SO_NUM, STATUS_DATE, STATUS,SO_REMARK,UPDATED_USER,SERVICE_TYPE) VALUES ('$so_no',sysdate,'2','$txt','$user','$stype')";
	$oraconn = OracleConnection();
    $result2 = oci_parse($oraconn, $sql2);
	oci_execute($result2);
	
	if($stype == 'AB-CAB'){
		
	$sql3 = "INSERT INTO OPMC_METERIALS (SOID, VOICENO, UNIT_DESIG,MET_ID,P0) VALUES ('$so_no','$voice_no','$unit_desig',MET_SEQ.nextval,'$dwlength')";
    $oraconn = OracleConnection();
    $result3 = oci_parse($oraconn, $sql3);
	oci_execute($result3);
	
	}
	
	$sql = "UPDATE OPMC_SOLIST SET UPDATE_USER = '$user',STATUS_DATE = sysdate,STATUS = '2',GROSS_AMOUNT = '$Ref_code' WHERE RTOM = '$rtom' and VOICENUMBER = '$voice_no' and SO_NUM = '$so_no' and STATUS = '1' and S_TYPE = '$stype'";
    $oraconn = OracleConnection();
    $result = oci_parse($oraconn, $sql);
	
	$sql4 = "UPDATE OSS_DEV_01.CONTRACTOR_NEW_CON set CON_SO_COM_DATE = sysdate, CON_SO_STATUS = 'COMPLETED', CON_STATUS = '2' where CON_SO_ID = '$so_no' and CON_TASK = ('$taskName'";
    $oraconn = OracleConnection();
    $result4 = oci_parse($oraconn, $sql4);
	oci_execute($result4);
	
	$sql5 = "UPDATE OSS_DEV_01.CONTRACTOR_WORK_ORDERS set CON_STATUS_DATE = sysdate, CON_STATUS = 'COMPLETED' where CON_SERO_ID = '$so_no' and CON_WORO_TASK_NAME = '$taskName'";
    $oraconn = OracleConnection();
    $result5 = oci_parse($oraconn, $sql5);
	oci_execute($result5);

	if(oci_execute($result)){
		
		$result = 'true';
		
	}else{
		
		$result = 'false';
		
	}	
	
}


if ($r == "27" ){
  
   $group = $_POST["group"];
   
   $result ="";

   $sql1="SELECT * FROM OPMC_GROUP WHERE GROUP_OPMC = '$opmc' ";
	
	$oraconn = OracleConnection();
	 
	$stid1=oci_parse($oraconn,$sql1);
	
    oci_execute($stid1);

	$result .= '<select class="form-control" id="emp_group" style="font-size:13px;"><option></option>';
	
  	while($row1 = oci_fetch_array($stid1)){
		
		if($group == $row1['GROUP_NAME']){
			
			$result .= '<option value="'.$row1['GROUP_NAME'].'" selected>'.$row1['GROUP_NAME'].'</option>';
		
		}else{
			
			$result .= '<option value="'.$row1['GROUP_NAME'].'">'.$row1['GROUP_NAME'].'</option>';
			
		}
	
	}
		
	$result .= '</select>';
	
}


if ($r == "28" ){
	
	$service_id = $_POST["service_id"];
	$emp_name = $_POST["emp_name"];
	$cmb_group = $_POST["cmb_group"];
	$mobile_no = $_POST["mobile_no"];
	
	$result = '';
	
	$sql1="SELECT * FROM OPMC_USER where EMP_NUM = '$service_id'";
	
	$oraconn = OracleConnection();
	   
	$stid1=oci_parse($oraconn,$sql1);
	
    oci_execute($stid1);
	
  	$row1 = oci_fetch_array($stid1);
	
	if($row1['EMP_GROUP'] != $cmb_group){
		
		$txt = 'Change user group - '.$row1['EMP_GROUP']. ' in to - '.$cmb_group;
		
			$sql2 = "INSERT INTO OPMC_USER_LOG (SERVICE_ID, UPDATED_DATE, UPDATED_USER,REMARK) VALUES ('$service_id',sysdate,'$user','$txt')";

			$result2 = oci_parse($oraconn, $sql2);
			oci_execute($result2);
	}

	
    $sql = "UPDATE OPMC_USER SET EMP_GROUP = '$cmb_group',EMP_NAME = '$emp_name',EMP_MOB = '$mobile_no' WHERE EMP_NUM = '$service_id' ";
 
    $result = oci_parse($oraconn, $sql);
	
	if(oci_execute($result)){
		
		$result = 'true';
		
	}else{
		
		$result = 'false';
		
	}	
	
}



if ($r == "29"){
	
	$voice_no = $_POST["voice_no"];
	$so_no = $_POST["so_no"];
	$s_type = $_POST["s_type"];
	
	if($s_type == 'AB-CAB'){
		
	 $sql = "SELECT DISTINCT A.RTOM,A.LEA, A.SO_NUM,A.S_TYPE,TO_CHAR(A.INDATE, 'mm/dd/yyyy hh:mi:ss AM') as INDATE,
			 A.VOICENUMBER, C.CON_WORO_ORDER_TYPE, C.CON_CUS_NAME,C.CON_TEC_CONTACT, D.CON_ADDE_STREETNUMBER||' '||D.CON_ADDE_STRN_NAMEANDTYPE||' '||D.CON_ADDE_SUBURB||' '||D.CON_ADDE_CITY AS ADDRESS,
			 E.CON_OSP_DP_NAME ||'-'||E.CON_OSP_DP_LOOP AS DPNAME,E.CON_OSP_PHONE_CLASS,E.CON_OSP_PHONE_COLOUR,E.CON_PHN_PURCH,C.CON_WORO_TASK_NAME,C.CON_WORO_DISCRIPTION
			 FROM CONTRACTOR_WORK_ORDERS C,CONTRACTOR_SERVICE_ADDRESS D, OPMC_SOLIST A ,CONTRACTOR_OSP_DATA E 
			 WHERE A.SO_NUM = C.CON_SERO_ID 
			 AND A.SO_NUM  = D.CON_ADDE_SERO_ID 
			 AND A.SO_NUM  = E.CON_OSP_SERO_ID
			 and C.CON_NAME = A.OPMC
			 and A.SO_NUM = '$so_no' 
			 and A.VOICENUMBER = '$voice_no'";
			 
	}elseif($s_type == 'E-IPTV COPPER'){
		
	 $sql = "SELECT DISTINCT A.RTOM,A.LEA, A.SO_NUM,A.S_TYPE,TO_CHAR(A.INDATE, 'mm/dd/yyyy hh:mi:ss AM') as INDATE,
			 A.VOICENUMBER, C.CON_WORO_ORDER_TYPE, C.CON_CUS_NAME,C.CON_TEC_CONTACT, D.CON_ADDE_STREETNUMBER||' '||D.CON_ADDE_STRN_NAMEANDTYPE||' '||D.CON_ADDE_SUBURB||' '||D.CON_ADDE_CITY AS ADDRESS,
			 E.CON_EQ_CARD ||'-'||E.CON_EQ_PORT AS DPNAME,E.CON_EQ_LOC_NAME,E.CON_EQ_INDEX,E.CON_PHN_PURCH,C.CON_WORO_TASK_NAME,C.CON_WORO_DISCRIPTION
			 FROM CONTRACTOR_WORK_ORDERS C,CONTRACTOR_SERVICE_ADDRESS D, OPMC_SOLIST A ,CONTRACTOR_EQ_DATA E 
			 WHERE A.SO_NUM = C.CON_SERO_ID 
			 AND A.SO_NUM  = D.CON_ADDE_SERO_ID 
			 AND A.SO_NUM  = E.CON_EQ_SERO_ID
			 and C.CON_NAME = A.OPMC
			 and A.SO_NUM = '$so_no' 
			 and A.VOICENUMBER = '$voice_no'";
		
	}
	
	$oraconn = OracleConnection();
    $stid=oci_parse($oraconn,$sql);
    oci_execute($stid);
	
	$result ="";
                           
  	$row = oci_fetch_array($stid);
		
	 $result .='<div class="form-group  row">
				<label class="col-sm-3 col-form-label" style="font-size:13px;font-family: Nanum Myeongjo, serif;">RTOM<p style="float:right">:</p></label>
				<div class="col-sm-3" style="font-size:13px;">'.$row['RTOM'].'</div>
				<label class="col-sm-3 col-form-label" style="font-size:13px;">LEA<p style="float:right">:</p></label>
				<div class="col-sm-3" style="font-size:13px;">'.$row['LEA'].'</div>
				</div>
				<div class="form-group  row">
				<label class="col-sm-3 col-form-label" style="font-size:13px;font-family: Nanum Myeongjo, serif;">SO Number<p style="float:right">:</p></label>
				<div class="col-sm-3" style="font-size:13px;">'.$row['SO_NUM'].'</div>
				<label class="col-sm-3 col-form-label" style="font-size:13px;font-family: Nanum Myeongjo, serif;">Service Type<p style="float:right">:</p></label>
				<div class="col-sm-3" style="font-size:13px;">'.$row['S_TYPE'].'</div>
				</div>
				<div class="form-group  row">
				<label class="col-sm-3 col-form-label" style="font-size:13px;font-family: Nanum Myeongjo, serif;">Recive Date<p style="float:right">:</p></label>
				<div class="col-sm-3" style="font-size:13px;">'.$row['INDATE'].'</div>
				<label class="col-sm-3 col-form-label" style="font-size:13px;font-family: Nanum Myeongjo, serif;">Voice Number<p style="float:right">:</p></label>
				<div class="col-sm-3" style="font-size:13px;">'.$row['VOICENUMBER'].'</div>
				</div>
				<div class="form-group  row">
				<label class="col-sm-3 col-form-label" style="font-size:13px;font-family: Nanum Myeongjo, serif;">WO Type<p style="float:right">:</p></label>
				<div class="col-sm-3" style="font-size:13px;">'.$row['CON_WORO_ORDER_TYPE'].'</div>
				<label class="col-sm-3 col-form-label" style="font-size:13px;font-family: Nanum Myeongjo, serif;">Customer Name<p style="float:right">:</p></label>
				<div class="col-sm-3" style="font-size:13px;">'.$row['CON_CUS_NAME'].'</div>
				</div>
				<div class="form-group  row">
				<label class="col-sm-3 col-form-label" style="font-size:13px;font-family: Nanum Myeongjo, serif;">Customer Contact<p style="float:right">:</p></label>
				<div class="col-sm-3" style="font-size:13px;">'.$row['CON_TEC_CONTACT'].'</div>
				<label class="col-sm-3 col-form-label" style="font-size:13px;font-family: Nanum Myeongjo, serif;">Address<p style="float:right">:</p></label>
				<div class="col-sm-3" style="font-size:13px;">'.$row['ADDRESS'].'</div>
				</div>
				<div class="form-group  row">
				<label class="col-sm-3 col-form-label" style="font-size:13px;font-family: Nanum Myeongjo, serif;">DP Name<p style="float:right">:</p></label>
				<div class="col-sm-3" style="font-size:13px;">'.$row['DPNAME'].'</div>
				<label class="col-sm-3 col-form-label" style="font-size:13px;font-family: Nanum Myeongjo, serif;">WORO Desciption<p style="float:right">:</p></label>
				<div class="col-sm-3" style="font-size:13px;">'.$row['CON_WORO_DISCRIPTION'].'</div>
				</div>
				<div class="form-group  row">
				<label class="col-sm-3 col-form-label" style="font-size:13px;font-family: Nanum Myeongjo, serif;">Task Name<p style="float:right">:</p></label>
				<div class="col-sm-3" style="font-size:13px;">'.$row['CON_WORO_TASK_NAME'].'</div>
				<label class="col-sm-3 col-form-label" style="font-size:13px;font-family: Nanum Myeongjo, serif;"></label>
				<div class="col-sm-3" style="font-size:13px;"></div>
				</div>';
	
 $result .='<div class="form-group  row">
				<div class="col-sm-12" style="font-size:14px;font-family: Nanum Myeongjo, serif;">
				<hr/>
				<b>SOD Log Data</b>
				<hr/>
				</div>
				</div>';
				
	 $result .='<div class="form-group  row">
				<div class="col-sm-12" style="font-size:13px;">
				<table class="table table-striped table-bordered">';
					
			$sql2 = "SELECT DISTINCT * FROM OPMC_LOG WHERE SO_NUM = '$so_no' AND SERVICE_TYPE = '$s_type'";

			$stid2=oci_parse($oraconn,$sql2);
			oci_execute($stid2);
			
			
	$result .='<thead class="thead-light"><tr>
				<td>Service Order No</td>
				<td>Remark</td>
				</tr></thead><tbody>';
								   
			while($row2 = oci_fetch_array($stid2)){
				
	 $result .='<tr>
				<td>'.$row2['SO_NUM'].'</td>
				<td>'.$row2['SO_REMARK'].'</td>
				</tr>';
			}
	
	 $result .='</tbody></table>
				</div>
				</div>';
	
}


if ($r == "30" ){
	
	$voice_no = $_POST["voice_no"];
	$so_no = $_POST["so_no"];
	$group = $_POST["group"];
	$re_reasons = $_POST["re_reasons"];
	$taskName = $_POST["taskName"];
	
	$result = '';
	
	$txt = 'SOD Return';
	
	$sql2 = "INSERT INTO OPMC_LOG (SO_NUM, STATUS_DATE, STATUS,SO_REMARK,UPDATED_USER) VALUES ('$so_no',sysdate,'5','$txt','$user')";
	$oraconn = OracleConnection();
	$result2 = oci_parse($oraconn, $sql2);
	oci_execute($result2);

    $sql = "UPDATE OPMC_SOLIST SET STATUS = '5',STATUS_DATE = sysdate WHERE VOICENUMBER = '$voice_no' and SO_NUM = '$so_no'";
    $result = oci_parse($oraconn, $sql);
	
	$sql3 = "INSERT INTO CONTRACTOR_ORDER_COMMENTS (CON_COMM_SERO_ID, CON_COMM_TEXT, CON_COMM_TIME,CON_COMM_USER,CON_COMM_STATUS) VALUES ('$so_no','$re_reasons',sysdate,'$user','RETURNED')"; 
	$oraconn = OracleConnection();
	$result3 = oci_parse($oraconn, $sql3);
	oci_execute($result3);
	
	$sql4 = "UPDATE OSS_DEV_01.CONTRACTOR_NEW_CON set CON_SO_RTN_DATE = sysdate, CON_SO_STATUS = 'RETURNED', CON_STATUS = '2' where CON_SO_ID = '$so_no' and CON_TASK = '$taskName'"; 
	$oraconn = OracleConnection();
	$result4 = oci_parse($oraconn, $sql4);
	oci_execute($result4);
	
	$sql5 = "UPDATE  OSS_DEV_01.CONTRACTOR_WORK_ORDERS set CON_STATUS_DATE = sysdate, CON_STATUS = 'RETURNED' where CON_SERO_ID = '$so_no' and CON_WORO_TASK_NAME = '$taskName'"; 
	$oraconn = OracleConnection();
	$result5 = oci_parse($oraconn, $sql5);
	oci_execute($result5);

	
	if(oci_execute($result)){
		
		$result = 'true';
		
	}else{
		
		$result = 'false';
		
	}	
	
}


if ($r == "31" ){
	
	$voice_no = $_POST["voice_no"];
	$rtom = $_POST["rtom"];
	$so_no = $_POST["so_no"];
	$stype = $_POST["stype"];
	$group = $_POST["group"];
	$taskName = $_POST["taskName"];	
	$other_mat = $_POST["other_mat"];	
	
	$sql3 = "INSERT INTO OPMC_METERIALS (SOID, VOICENO, UNIT_DESIG,MET_ID,P0) VALUES ('$so_no','$voice_no','$other_mat',MET_SEQ.nextval,'1')";
    $oraconn = OracleConnection();
    $result3 = oci_parse($oraconn, $sql3);
	
	if(oci_execute($result3)){
		
		$result = 'true';
		
	}else{
		
		$result = 'false';
		
	}	
	
	
}

echo $result;

?>
