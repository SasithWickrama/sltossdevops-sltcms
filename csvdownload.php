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

$oraconn = OracleConnection();

     if(isset($_POST["submitData"])){

     	$fname='Data_'.$date.'.csv';
         
         
          header('Content-Type: text/csv; charset=utf-8');  
          header('Content-Disposition: attachment; filename='.$fname);  
          $output = fopen("php://output", "w"); 
          
         fputcsv($output, array('RTOM','LEA','SO NUM', 'SERVICE TYPE', 'RECIVE DATE', 'VOICE NUMBER','WO TYPE','CUSTOMER NAME','CUSTOMER CONTACT','ADDRESS','DP NAME','DP LOOP','PHONE CLASS','PHONE COLOUR','PHN PURCH','WORO DISCRIPTION','TASK NAME','CONTRATOR','IPTV'));
		
/*$sql="SELECT DISTINCT B.CON_AREA,A1.LEA,  A.SO_NUM,A.S_TYPE,TO_CHAR(B.CON_SO_DATE_RECEIVED, 'mm/dd/yyyy hh:mi:ss AM'),A.VOICENUMBER, 
		C.CON_WORO_ORDER_TYPE, C.CON_CUS_NAME,C.CON_TEC_CONTACT, 
		D.CON_ADDE_STREETNUMBER||' '||D.CON_ADDE_STRN_NAMEANDTYPE||' '||D.CON_ADDE_SUBURB||' '||D.CON_ADDE_CITY AS ADDRESS, 
		E.CON_OSP_DP_NAME,E.CON_OSP_DP_LOOP,E.CON_OSP_PHONE_CLASS,E.CON_OSP_PHONE_COLOUR,E.CON_PHN_PURCH,
		C.CON_WORO_DISCRIPTION,C.CON_WORO_TASK_NAME, A1.CONTRATOR ,A1.IPTV
		FROM CON_CLARITY_SOLIST A, CONTRACTOR_NEW_CON B, CONTRACTOR_WORK_ORDERS C,CONTRACTOR_SERVICE_ADDRESS D,CONTRACTOR_OSP_DATA E ,
		CON_CLARITY_SOLIST A1
		WHERE A.SO_NUM = C.CON_SERO_ID AND B.CON_SO_ID = C.CON_SERO_ID 
		AND B.CON_SO_ID = D.CON_ADDE_SERO_ID 
		AND B.CON_SO_ID = E.CON_OSP_SERO_ID(+) 
		AND A.SO_NUM = A1.SO_NUM
		AND A1.STATUS='0' AND A1.RTOM IN ($areas)";

		if(isset($_POST['lea']) && $_POST['lea'] != ''){

        $lea = $_POST['lea'];

        $sql.= " AND A1.LEA = '$lea'";

		}
		
		if(isset($_POST['stype']) && $_POST['stype'] != ''){

			$stype = $_POST['stype'];

			$sql.= " AND A.S_TYPE = '$stype'";

		}
	
	$sql.="UNION
		SELECT DISTINCT  B.CON_AREA, A1.LEA,A.SO_NUM,A.S_TYPE,TO_CHAR(B.CON_SO_DATE_RECEIVED, 'mm/dd/yyyy hh:mi:ss AM'),A.VOICENUMBER,
		C.CON_WORO_ORDER_TYPE, C.CON_CUS_NAME,C.CON_TEC_CONTACT, 
		D.CON_ADDE_STREETNUMBER||' '||D.CON_ADDE_STRN_NAMEANDTYPE||' '||D.CON_ADDE_SUBURB||' '||D.CON_ADDE_CITY AS ADDRESS, 
		E.CON_OSP_DP_NAME,E.CON_OSP_DP_LOOP,E.CON_OSP_PHONE_CLASS,E.CON_OSP_PHONE_COLOUR,E.CON_PHN_PURCH,
		C.CON_WORO_DISCRIPTION,C.CON_WORO_TASK_NAME, A1.CONTRATOR ,A1.IPTV
		FROM CON_CLARITY_SOLIST A, CONTRACTOR_NEW_CON B, CONTRACTOR_WORK_ORDERS C,CONTRACTOR_SERVICE_ADDRESS D,CONTRACTOR_OSP_DATA E ,
		CON_CLARITY_SOLIST A1
		WHERE A.SO_NUM = C.CON_SERO_ID AND B.CON_SO_ID = C.CON_SERO_ID 
		AND B.CON_SO_ID = D.CON_ADDE_SERO_ID 
		AND B.CON_SO_ID = E.CON_OSP_SERO_ID(+) 
		AND A.SO_NUM = A1.SO_NUM
		AND A1.STATUS='0' AND A1.RTOM IN ($areas) AND A1.IPTV = 1";
	
		if(isset($_POST['lea']) && $_POST['lea'] != ''){

        $lea = $_POST['lea'];

        $sql.= " AND A1.LEA = '$lea'";

		}
		
		if(isset($_POST['stype']) && $_POST['stype'] != ''){

			$stype = $_POST['stype'];

			$sql.= " AND A.S_TYPE = '$stype'";

		}*/
		
		
		$sql = "SELECT DISTINCT B.CON_AREA,A1.LEA, A.SO_NUM,A.S_TYPE,TO_CHAR(B.CON_SO_DATE_RECEIVED, 'mm/dd/yyyy hh:mi:ss AM'),
A.VOICENUMBER, C.CON_WORO_ORDER_TYPE, 
C.CON_CUS_NAME,C.CON_TEC_CONTACT, D.CON_ADDE_STREETNUMBER||' '||D.CON_ADDE_STRN_NAMEANDTYPE||' '||D.CON_ADDE_SUBURB||' '||D.CON_ADDE_CITY AS ADDRESS, 
E.CON_OSP_DP_NAME ||'-'||E.CON_OSP_DP_LOOP AS DPNAME,E.CON_OSP_PHONE_CLASS,E.CON_OSP_PHONE_COLOUR,E.CON_PHN_PURCH, 
C.CON_WORO_DISCRIPTION,C.CON_WORO_TASK_NAME, A1.CONTRATOR ,A1.IPTV,A1.EX_NO 
FROM CON_CLARITY_SOLIST A, CONTRACTOR_NEW_CON B, CONTRACTOR_WORK_ORDERS C,CONTRACTOR_SERVICE_ADDRESS D,CONTRACTOR_OSP_DATA E , CON_CLARITY_SOLIST A1 
WHERE A.SO_NUM = C.CON_SERO_ID AND B.CON_SO_ID = C.CON_SERO_ID AND B.CON_SO_ID = D.CON_ADDE_SERO_ID AND B.CON_SO_ID = E.CON_OSP_SERO_ID(+) 
AND A.SO_NUM = A1.SO_NUM AND A1.STATUS='0' AND A1.RTOM IN ($areas) AND A1.IPTV IS NOT NULL AND C.CON_STATUS = 'ASSIGNED' AND B.CON_SO_STATUS = 'ASSIGNED'";

		if(isset($_POST['lea']) && $_POST['lea'] != ''){

        $lea = $_POST['lea'];

        $sql.= " AND A1.LEA = '$lea'";

		}
		
		if(isset($_POST['stype']) && $_POST['stype'] != ''){

			$stype = $_POST['stype'];

			$sql.= " AND A.S_TYPE = '$stype'";

		}
		

		  $stid=oci_parse($oraconn,$sql);
		  oci_execute($stid);

		  while($row = oci_fetch_array($stid))  
		  {  
		   
			fputcsv($output, array($row[0],$row[1],$row[2],$row[3],$row[4],$row[5],$row[6],$row[7],$row[8],$row[9],$row[10],$row[11],$row[12],$row[13],$row[14],$row[15],$row[16],$row[17],$row[18]));

		  }
		  
		 fclose($output); 
    }
	
	//--------------------------------------------------------------------------------------------------------------------
	
	 if(isset($_POST["submitData1"])){

     	$fname='Data_'.$date.'.csv';
         
		header('Content-Type: text/csv; charset=utf-8');  
		header('Content-Disposition: attachment; filename='.$fname);  
		$output = fopen("php://output", "w"); 
          
        fputcsv($output, array('RTOM','LEA','SO NUM', 'SERVICE TYPE', 'RECIVE DATE', 'VOICE NUMBER','WO TYPE','CUSTOMER NAME','CUSTOMER CONTACT','ADDRESS','DP NAME','DP LOOP','PHONE CLASS','PHONE COLOUR','PHN PURCH','WORO DISCRIPTION','TASK NAME','CONTRATOR','IPTV'));
		
	/*$sql="SELECT DISTINCT B.CON_AREA,A1.LEA,  A.SO_NUM,A.S_TYPE,TO_CHAR(B.CON_SO_DATE_RECEIVED, 'mm/dd/yyyy hh:mi:ss AM'),A.VOICENUMBER, 
		C.CON_WORO_ORDER_TYPE, C.CON_CUS_NAME,C.CON_TEC_CONTACT, 
		D.CON_ADDE_STREETNUMBER||' '||D.CON_ADDE_STRN_NAMEANDTYPE||' '||D.CON_ADDE_SUBURB||' '||D.CON_ADDE_CITY AS ADDRESS, 
		E.CON_OSP_DP_NAME,E.CON_OSP_DP_LOOP,E.CON_OSP_PHONE_CLASS,E.CON_OSP_PHONE_COLOUR,E.CON_PHN_PURCH,
		C.CON_WORO_DISCRIPTION,C.CON_WORO_TASK_NAME, A1.CONTRATOR ,A1.IPTV
		FROM CON_CLARITY_SOLIST A, CONTRACTOR_NEW_CON B, CONTRACTOR_WORK_ORDERS C,CONTRACTOR_SERVICE_ADDRESS D,CONTRACTOR_OSP_DATA E ,
		CON_CLARITY_SOLIST A1
		WHERE A.SO_NUM = C.CON_SERO_ID AND B.CON_SO_ID = C.CON_SERO_ID 
		AND B.CON_SO_ID = D.CON_ADDE_SERO_ID 
		AND B.CON_SO_ID = E.CON_OSP_SERO_ID(+) 
		AND A.SO_NUM = A1.SO_NUM
		AND A1.STATUS IN('0','1','4','8') AND A1.RTOM IN ($areas)";

		if(isset($_POST['lea']) && $_POST['lea'] != ''){

        $lea = $_POST['lea'];

        $sql.= " AND A1.LEA = '$lea'";

		}
		
		if(isset($_POST['stype']) && $_POST['stype'] != ''){

			$stype = $_POST['stype'];

			$sql.= " AND A.S_TYPE = '$stype'";

		}
	
	$sql.="UNION
		SELECT DISTINCT  B.CON_AREA, A1.LEA,A.SO_NUM,A.S_TYPE,TO_CHAR(B.CON_SO_DATE_RECEIVED, 'mm/dd/yyyy hh:mi:ss AM'),A.VOICENUMBER,
		C.CON_WORO_ORDER_TYPE, C.CON_CUS_NAME,C.CON_TEC_CONTACT, 
		D.CON_ADDE_STREETNUMBER||' '||D.CON_ADDE_STRN_NAMEANDTYPE||' '||D.CON_ADDE_SUBURB||' '||D.CON_ADDE_CITY AS ADDRESS, 
		E.CON_OSP_DP_NAME,E.CON_OSP_DP_LOOP,E.CON_OSP_PHONE_CLASS,E.CON_OSP_PHONE_COLOUR,E.CON_PHN_PURCH,
		C.CON_WORO_DISCRIPTION,C.CON_WORO_TASK_NAME, A1.CONTRATOR ,A1.IPTV
		FROM CON_CLARITY_SOLIST A, CONTRACTOR_NEW_CON B, CONTRACTOR_WORK_ORDERS C,CONTRACTOR_SERVICE_ADDRESS D,CONTRACTOR_OSP_DATA E ,
		CON_CLARITY_SOLIST A1
		WHERE A.SO_NUM = C.CON_SERO_ID AND B.CON_SO_ID = C.CON_SERO_ID 
		AND B.CON_SO_ID = D.CON_ADDE_SERO_ID 
		AND B.CON_SO_ID = E.CON_OSP_SERO_ID(+) 
		AND A.SO_NUM = A1.SO_NUM
		AND A1.STATUS IN('0','1','4','8') AND A1.RTOM IN ($areas) AND A1.IPTV = 1";
	
		if(isset($_POST['lea']) && $_POST['lea'] != ''){

        $lea = $_POST['lea'];

        $sql.= " AND A1.LEA = '$lea'";

		}
		
		if(isset($_POST['stype']) && $_POST['stype'] != ''){

			$stype = $_POST['stype'];

			$sql.= " AND A.S_TYPE = '$stype'";

		}*/
		
		
		$sql = "SELECT DISTINCT B.CON_AREA,A1.LEA, A.SO_NUM,A.S_TYPE,TO_CHAR(B.CON_SO_DATE_RECEIVED, 'mm/dd/yyyy hh:mi:ss AM'),
A.VOICENUMBER, C.CON_WORO_ORDER_TYPE, 
C.CON_CUS_NAME,C.CON_TEC_CONTACT, D.CON_ADDE_STREETNUMBER||' '||D.CON_ADDE_STRN_NAMEANDTYPE||' '||D.CON_ADDE_SUBURB||' '||D.CON_ADDE_CITY AS ADDRESS, 
E.CON_OSP_DP_NAME ||'-'||E.CON_OSP_DP_LOOP AS DPNAME,E.CON_OSP_PHONE_CLASS,E.CON_OSP_PHONE_COLOUR,E.CON_PHN_PURCH, 
C.CON_WORO_DISCRIPTION,C.CON_WORO_TASK_NAME, A1.CONTRATOR ,A1.IPTV,A1.EX_NO 
FROM CON_CLARITY_SOLIST A, CONTRACTOR_NEW_CON B, CONTRACTOR_WORK_ORDERS C,CONTRACTOR_SERVICE_ADDRESS D,CONTRACTOR_OSP_DATA E , CON_CLARITY_SOLIST A1 
WHERE A.SO_NUM = C.CON_SERO_ID AND B.CON_SO_ID = C.CON_SERO_ID AND B.CON_SO_ID = D.CON_ADDE_SERO_ID AND B.CON_SO_ID = E.CON_OSP_SERO_ID(+) 
AND A.SO_NUM = A1.SO_NUM AND A1.STATUS IN('0','1','4','8')  AND A1.RTOM IN ($areas) AND A1.IPTV IS NOT NULL";

		if(isset($_POST['lea']) && $_POST['lea'] != ''){

        $lea = $_POST['lea'];

        $sql.= " AND A1.LEA = '$lea'";

		}
		
		if(isset($_POST['stype']) && $_POST['stype'] != ''){

			$stype = $_POST['stype'];

			$sql.= " AND A.S_TYPE = '$stype'";

		}

		  $stid=oci_parse($oraconn,$sql);
		  oci_execute($stid);

		  while($row = oci_fetch_array($stid))  
		  {  
		   
			fputcsv($output, array($row[0],$row[1],$row[2],$row[3],$row[4],$row[5],$row[6],$row[7],$row[8],$row[9],$row[10],$row[11],$row[12],$row[13],$row[14],$row[15],$row[16],$row[17],$row[18]));

		  }
		  
		 fclose($output); 
    }
	

?>