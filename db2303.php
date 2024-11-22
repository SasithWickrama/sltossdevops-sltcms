<?php
session_start();
if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true)
{    
    $user = $_SESSION["user"];
}

function OracleConnection(){
   /*  $db = "(DESCRIPTION=
    (ADDRESS=
      (PROTOCOL=TCP)
      (HOST=172.25.16.243)
      (PORT=1521)
    )
    (CONNECT_DATA=
      (SERVER=dedicated)
      (SERVICE_NAME=clty)
    )
  )

";*/

$db = "(DESCRIPTION =
    (ADDRESS_LIST =
      (ADDRESS = (PROTOCOL = TCP)(HOST = 172.25.1.172)(PORT = 1521))
    )
    (CONNECT_DATA = (SID=clty))
  )
";

   // if($c = oci_connect("SLT_CON_MGT", "SLT_TEST_123", $db))
   if($c = oci_connect("OSS_DEV_01", "pass123#", $db))        
    {
    //  echo "Successfully connected to Oracle.\n";
            return $c;
        //ocilogoff($c);
    }
    else
    {
        //$err = OCIError();
       // echo "Connection failed." . $err[text];
        echo "<script type='text/javascript'>alert('Connection failed')</script>";
    }
}

function getcomnt($a)
{
    $sql= "select CON_COMM_TEXT
            from (select   *
            from OSS_DEV_01.CONTRACTOR_ORDER_COMMENTS cm1
            where cm1.CON_COMM_SERO_ID = '$a'
            order by CON_COMM_TIME desc )
            where ROWNUM = 1";

    $oraconn = OracleConnection();
    $con_comp= oci_parse($oraconn, $sql);
    if(oci_execute($con_comp))
    {
    return $con_comp;
    }
    else
    {
        $err = oci_error($con_comp);
        $e =  $err['message'];
        echo "<script type='text/javascript'>alert('$e')</script>";
    }
}


/*function OracleConnection(){
     $db = "(DESCRIPTION =
    (ADDRESS_LIST =
      (ADDRESS = (PROTOCOL = TCP)(HOST = 172.25.1.172)(PORT = 1521))
    )
    (CONNECT_DATA = (SID=clty))
  )
";*/

/* $db = "(DESCRIPTION =
    (ADDRESS_LIST =
      (ADDRESS = (PROTOCOL = TCP)(HOST = 172.25.1.168)(PORT = 1521))
    )
    (CONNECT_DATA = (SID=clty1))
  )";


        
   // if($c = oci_connect("SLT_CON_MGT", "SLT_TEST_123", $db))
   if($c = oci_connect("OSS_DEV_01", "pass123#", $db))        
    {
    //  echo "Successfully connected to Oracle.\n";
            return $c;
        //ocilogoff($c);
    }
    else
    {
        //$err = OCIError();
       // echo "Connection failed." . $err[text];
        echo "<script type='text/javascript'>alert('Connection failed')</script>";
    }
}*/

function OracleConnectionSMS(){
       $db = $db = "(DESCRIPTION =
    (ADDRESS_LIST =
      (ADDRESS = (PROTOCOL = TCP)(HOST = 172.25.1.172)(PORT = 1521))
    )
    (CONNECT_DATA = (SID=clty))
  )
";
        
    if($c = oci_connect("OSSRPT", "ossrpt123", $db))
   // if($c = oci_connect("OSS_DEV_01", "pass123#", $db))        
    {
    //  echo "Successfully connected to Oracle.\n";
            return $c;
        //ocilogoff($c);
    }
    else
    {
        //$err = OCIError();
       // echo "Connection failed." . $err[text];
        echo "<script type='text/javascript'>alert('Connection failed')</script>";
    }
}

function Contractor()
{
    $sql = "select * from CONTRACTOR_MGT_USERS where CON_MGT_CONTRACTOR <> 'SLT' order by CON_MGT_CONTRACTOR";
    $oraconn = OracleConnection();
    $cont = oci_parse($oraconn, $sql);
    if ( oci_execute($cont))
    {
    return $cont;
    }
    else
    {
        $err = oci_error($cont);
        $e =  $err['message'];
        echo "<script type='text/javascript'>alert('$e')</script>";
    }

}

function con_cont($a)
{
    $sql = "select CON_MGT_CONTRACTOR,CON_MGT_USER_NAME,CON_MGT_USER,CON_MGT_USER_EMAIL,CON_MGT_MOBILE,SLT_AREA,CON_MGT_USER_PRV_LEVEL from CONTRACTOR_MGT_USERS where CON_MGT_CONTRACTOR = '$a'";
    $oraconn = OracleConnection();
    $cont = oci_parse($oraconn, $sql);
    if ( oci_execute($cont))
    {
    return $cont;
    }
    else
    {
        $err = oci_error($cont);
        $e =  $err['message'];
        echo "<script type='text/javascript'>alert('$e')</script>";
    }

}

function Get_email($contractor,$usr_name)
{
    /*$sql ="select CON_MGT_USER_EMAIL from CONTRACTOR_MGT_USERS where CON_MGT_USER_NAME= '{$usr_name}' and 
            CON_MGT_CONTRACTOR = '{$contractor}'";
    $oraconn = OracleConnection();
    $mail = oci_parse($oraconn, $sql);
 
    if (oci_execute($mail))*/
    $sql ="select CON_MGT_USER_EMAIL from CONTRACTOR_MGT_USERS where CON_MGT_USER_NAME= :CON_MGT_USER_NAME and 
            CON_MGT_CONTRACTOR = :CON_MGT_CONTRACTOR";
    $oraconn = OracleConnection();
    $mail = oci_parse($oraconn, $sql);
    oci_bind_by_name($mail, ":CON_MGT_USER_NAME", $usr_name);
    oci_bind_by_name($mail, ":CON_MGT_CONTRACTOR", $contractor);
    if(oci_execute($mail))
    {    
    $row=oci_fetch_array($mail);
    return $row[0];
    }
    else
    {
        $err = oci_error($mail);
        $e =  $err['message'];
        echo "<script type='text/javascript'>alert('$e')</script>";
    }
}


/*function SO_List($user)
{
    $sql = "select cw.CON_SERO_ID,cw.CON_PSTN_NUMBER,cw.CON_WORO_ID,to_char(cw.CON_WORO_DATE_CREATED, 'mm/dd/yyyy hh:mi:ss AM'),to_char(cw.CON_DATE_TO_CONTRACTOR, 'mm/dd/yyyy hh:mi:ss AM'),
        cw.CON_STATUS,cw.CON_WORO_ORDER_TYPE,cw.CON_WORO_AREA, to_char(CO.CON_SO_DUE_DATE, 'mm/dd/yyyy hh:mi:ss AM')
from CONTRACTOR_WORK_ORDERS cw, CONTRACTOR_NEW_CON co
 where cw.CON_NAME =  '{$user}'
and CON_AREA IN('R-WT','R-MD')
 and CW.CON_SERO_ID = CO.CON_SO_ID order by cw.CON_STATUS";
    $oraconn = OracleConnection();
    $so_list = oci_parse($oraconn, $sql);
    if (oci_execute($so_list))
    {    
    return $so_list;
    }
    else
    {
        $err = oci_error($so_list);
        $e =  $err['message'];
        echo "<script type='text/javascript'>alert('$e')</script>";
    }
}*/

function SO_Listall($user,$a)
{
    $sql = " select distinct cw.CON_SERO_ID,cw.CON_PSTN_NUMBER,to_char(cw.CON_WORO_DATE_CREATED, 'mm/dd/yyyy hh:mi:ss AM'),
to_char(cw.CON_DATE_TO_CONTRACTOR, 'mm/dd/yyyy hh:mi:ss AM'), cw.CON_STATUS,cw.CON_WORO_SERVICE_TYPE,cw.CON_WORO_ORDER_TYPE,cw.CON_WORO_AREA,
co.APPOIN_FLAG,cw.CON_WORO_TASK_NAME from CONTRACTOR_WORK_ORDERS cw, CONTRACTOR_NEW_CON co
 where cw.CON_NAME =  '{$user}'
 and cw.CON_STATUS IN ('ASSIGNED','INPROGRESS', 'REASSIGNED', 'DELAYED')
 and CW.CON_SERO_ID = CO.CON_SO_ID order by cw.CON_STATUS";
    $oraconn = OracleConnection();
    $so_list = oci_parse($oraconn, $sql);
    if (oci_execute($so_list))
    {    
    return $so_list;
    }
    else
    {
        $err = oci_error($so_list);
        $e =  $err['message'];
        echo "<script type='text/javascript'>alert('$e')</script>";
    }
}

function SO_List($user,$a)
{
    $sql = "SELECT DISTINCT CW.CON_SERO_ID,CW.CON_PSTN_NUMBER,
TO_CHAR(CW.CON_DATE_TO_CONTRACTOR, 'mm/dd/yyyy hh:mi:ss AM'), CW.CON_STATUS,CW.CON_WORO_SERVICE_TYPE,CW.CON_WORO_ORDER_TYPE,CW.CON_WORO_AREA,
CW.CON_WORO_TASK_NAME  ,
CASE WHEN CON_WORO_SERVICE_TYPE = 'AB-FTTH' THEN 
   ( SELECT DISTINCT CON_FTTH_PKG FROM CONTRACTOR_FTTH_DATA WHERE CON_FTTH_SERO_ID = CON_SERO_ID)
    WHEN CON_WORO_SERVICE_TYPE = 'AB-CAB' THEN 
   (SELECT DISTINCT CON_OSP_PHONE_COLOUR FROM CONTRACTOR_OSP_DATA WHERE CON_OSP_SERO_ID = CON_SERO_ID)
   ELSE ''
   END AS PKG
FROM CONTRACTOR_WORK_ORDERS CW, CONTRACTOR_NEW_CON CO, CON_CLARITY_SOLIST CS
WHERE CW.CON_NAME =  '$user'
AND CO.CON_SO_STATUS = CW.CON_STATUS
AND CW.CON_STATUS IN ('ASSIGNED','INPROGRESS', 'REASSIGNED', 'DELAYED')
AND CON_AREA = '$a'
AND CW.CON_SERO_ID = CO.CON_SO_ID 
AND CO.CON_SO_ID = CS.SO_NUM
AND CW.CON_SERO_ID = CS.SO_NUM
and CS.IPTV is not null
union all
select distinct cw.CON_SERO_ID,cw.CON_PSTN_NUMBER,
to_char(cw.CON_DATE_TO_CONTRACTOR, 'mm/dd/yyyy hh:mi:ss AM'), cw.CON_STATUS,cw.CON_WORO_SERVICE_TYPE,cw.CON_WORO_ORDER_TYPE,cw.CON_WORO_AREA,
cw.CON_WORO_TASK_NAME ,CASE WHEN CON_WORO_SERVICE_TYPE = 'AB-FTTH' THEN 
   ( SELECT DISTINCT CON_FTTH_PKG FROM CONTRACTOR_FTTH_DATA WHERE CON_FTTH_SERO_ID = CON_SERO_ID)
    WHEN CON_WORO_SERVICE_TYPE = 'AB-CAB' THEN 
   (SELECT DISTINCT CON_OSP_PHONE_COLOUR FROM CONTRACTOR_OSP_DATA WHERE CON_OSP_SERO_ID = CON_SERO_ID)
   ELSE ''
   END AS PKG
from CONTRACTOR_WORK_ORDERS cw, CONTRACTOR_NEW_CON co
 where cw.CON_NAME =  '{$user}'
 and co.CON_SO_STATUS = cw.CON_STATUS
and cw.CON_STATUS IN ('ASSIGNED','INPROGRESS', 'REASSIGNED', 'DELAYED')
 and CON_AREA = '$a'
 and CW.CON_SERO_ID = CO.CON_SO_ID
 and cw.CON_WORO_ID is not null
";
 
    $oraconn = OracleConnection();
    $so_list = oci_parse($oraconn, $sql);
    if (oci_execute($so_list))
    {    
    return $so_list;
    }
    else
    {
        $err = oci_error($so_list);
        $e =  $err['message'];
        echo "<script type='text/javascript'>alert('$e')</script>";
    }
}
function SO_List2($user,$a,$a1)
{
    $sql = "select distinct cw.CON_SERO_ID,cw.CON_PSTN_NUMBER,to_char(cw.CON_WORO_DATE_CREATED, 'mm/dd/yyyy hh:mi:ss AM'),
to_char(cw.CON_DATE_TO_CONTRACTOR, 'mm/dd/yyyy hh:mi:ss AM'), cw.CON_STATUS,cw.CON_WORO_SERVICE_TYPE,cw.CON_WORO_ORDER_TYPE,cw.CON_WORO_AREA,
co.APPOIN_FLAG,cw.CON_WORO_TASK_NAME from CONTRACTOR_WORK_ORDERS cw, CONTRACTOR_NEW_CON co
 where cw.CON_NAME =  '{$user}'
 and cw.CON_STATUS IN ('ASSIGNED','INPROGRESS', 'REASSIGNED', 'DELAYED')
 and CON_AREA IN( '$a','$a1')
 and CW.CON_SERO_ID = CO.CON_SO_ID order by cw.CON_STATUS";
    $oraconn = OracleConnection();
    $so_list = oci_parse($oraconn, $sql);
    if (oci_execute($so_list))
    {    
    return $so_list;
    }
    else
    {
        $err = oci_error($so_list);
        $e =  $err['message'];
        echo "<script type='text/javascript'>alert('$e')</script>";
    }
}
function SO_List3($user,$a,$a1,$a2)
{
    $sql = "select distinct cw.CON_SERO_ID,cw.CON_PSTN_NUMBER,to_char(cw.CON_WORO_DATE_CREATED, 'mm/dd/yyyy hh:mi:ss AM'),
to_char(cw.CON_DATE_TO_CONTRACTOR, 'mm/dd/yyyy hh:mi:ss AM'), cw.CON_STATUS,cw.CON_WORO_SERVICE_TYPE,cw.CON_WORO_ORDER_TYPE,cw.CON_WORO_AREA,
co.APPOIN_FLAG,cw.CON_WORO_TASK_NAME from CONTRACTOR_WORK_ORDERS cw, CONTRACTOR_NEW_CON co
 where cw.CON_NAME =  '{$user}'
 and cw.CON_STATUS IN ('ASSIGNED','INPROGRESS', 'REASSIGNED', 'DELAYED')
 and CON_AREA IN('$a','$a1','$a2')
 and CW.CON_SERO_ID = CO.CON_SO_ID order by cw.CON_STATUS";
    $oraconn = OracleConnection();
    $so_list = oci_parse($oraconn, $sql);
    if (oci_execute($so_list))
    {    
    return $so_list;
    }
    else
    {
        $err = oci_error($so_list);
        $e =  $err['message'];
        echo "<script type='text/javascript'>alert('$e')</script>";
    }
}
function SO_List4($user,$a,$a1,$a2,$a3)
{
    $sql = "select distinct cw.CON_SERO_ID,cw.CON_PSTN_NUMBER,to_char(cw.CON_WORO_DATE_CREATED, 'mm/dd/yyyy hh:mi:ss AM'),
to_char(cw.CON_DATE_TO_CONTRACTOR, 'mm/dd/yyyy hh:mi:ss AM'), cw.CON_STATUS,cw.CON_WORO_SERVICE_TYPE,cw.CON_WORO_ORDER_TYPE,cw.CON_WORO_AREA,
co.APPOIN_FLAG,cw.CON_WORO_TASK_NAME from CONTRACTOR_WORK_ORDERS cw, CONTRACTOR_NEW_CON co
 where cw.CON_NAME =  '{$user}'
 and cw.CON_STATUS IN ('ASSIGNED','INPROGRESS', 'REASSIGNED', 'DELAYED')
 and CON_AREA IN('$a','$a1','$a2','$a3')
 and CW.CON_SERO_ID = CO.CON_SO_ID order by cw.CON_STATUS";
    $oraconn = OracleConnection();
    $so_list = oci_parse($oraconn, $sql);
    if (oci_execute($so_list))
    {    
    return $so_list;
    }
    else
    {
        $err = oci_error($so_list);
        $e =  $err['message'];
        echo "<script type='text/javascript'>alert('$e')</script>";
    }
}
function SO_List5($user,$a,$a1,$a2,$a3,$a4)
{
    $sql = "select distinct cw.CON_SERO_ID,cw.CON_PSTN_NUMBER,to_char(cw.CON_WORO_DATE_CREATED, 'mm/dd/yyyy hh:mi:ss AM'),
to_char(cw.CON_DATE_TO_CONTRACTOR, 'mm/dd/yyyy hh:mi:ss AM'), cw.CON_STATUS,cw.CON_WORO_SERVICE_TYPE,cw.CON_WORO_ORDER_TYPE,cw.CON_WORO_AREA,
co.APPOIN_FLAG,cw.CON_WORO_TASK_NAME from CONTRACTOR_WORK_ORDERS cw, CONTRACTOR_NEW_CON co
 where cw.CON_NAME =  '{$user}'
 and cw.CON_STATUS IN ('ASSIGNED','INPROGRESS', 'REASSIGNED', 'DELAYED')
 and CON_AREA IN('$a','$a1','$a3','$a3','$a4')
 and CW.CON_SERO_ID = CO.CON_SO_ID order by cw.CON_STATUS";
    $oraconn = OracleConnection();
    $so_list = oci_parse($oraconn, $sql);
    if (oci_execute($so_list))
    {    
    return $so_list;
    }
    else
    {
        $err = oci_error($so_list);
        $e =  $err['message'];
        echo "<script type='text/javascript'>alert('$e')</script>";
    }
}


//=============================
/*function SO_List($user)
{
    $sql = "select cw.CON_SERO_ID,cw.CON_PSTN_NUMBER,cw.CON_WORO_ID,to_char(cw.CON_WORO_DATE_CREATED, 'mm/dd/yyyy hh:mi:ss AM'),to_char(cw.CON_DATE_TO_CONTRACTOR, 'mm/dd/yyyy hh:mi:ss AM'),
        cw.CON_STATUS,cw.CON_WORO_ORDER_TYPE,cw.CON_WORO_AREA, to_char(CO.CON_SO_DUE_DATE, 'mm/dd/yyyy hh:mi:ss AM')
from CONTRACTOR_WORK_ORDERS cw, CONTRACTOR_NEW_CON co
 where cw.CON_NAME =  '{$user}'
 and CW.CON_SERO_ID = CO.CON_SO_ID order by cw.CON_STATUS";
    $oraconn = OracleConnection();
    $so_list = oci_parse($oraconn, $sql);
    if (oci_execute($so_list))
    {
    return $so_list;
    }
    else
    {
        $err = oci_error($so_list);
        $e =  $err['message'];
        echo "<script type='text/javascript'>alert('$e')</script>";
    }
}*/


function update_passwd($hash, $usr_name,$contractor)
{
    $sql = "update CONTRACTOR_MGT_USERS set CON_MGT_PW_HASH = '{$hash}', CON_MGT_PW_ASSIGN_DATE = sysdate
        where CON_MGT_CONTRACTOR = '{$contractor}' and CON_MGT_USER_NAME = '{$usr_name}'";
    $oraconn = OracleConnection();
    $sr2 = oci_parse($oraconn, $sql);
    if(oci_execute($sr2))
    {
    return $sr2;
    }
    else
    {
        $err = oci_error($sr2);
        $e =  $err['message'];
        echo "<script type='text/javascript'>alert('$e')</script>";
    }
}

function change_hash($user , $contractor)
{
    $sql = "update CONTRACTOR_MGT_USERS set CON_MGT_PW_HASH = '' where CON_MGT_USER_NAME = '{$user}'
            and CON_MGT_CONTRACTOR = '{$contractor}'";
    $oraconn = OracleConnection();
    $sr2 = oci_parse($oraconn, $sql);
    if(oci_execute($sr2))
    {
        return 0;
    }
    else
    {
        $err = oci_error($sr2);
        $e =  $err['message'];
        echo "<script type='text/javascript'>alert('$e')</script>";
    }
}

function validate_user($uname, $con)
{
   /* $sql = "select CON_MGT_USER_NAME,CON_MGT_CONTRACTOR, CON_MGT_PW_HASH, 
    * CON_MGT_USER_PRV_LEVEL,CON_MGT_USER_KEY from CONTRACTOR_MGT_USERS where CON_MGT_USER_NAME = '{$uname}'";
    $oraconn = OracleConnection();
    $auth = oci_parse($oraconn, $sql);*/
    
 
    $sql ="select CON_MGT_USER_NAME,CON_MGT_CONTRACTOR, CON_MGT_PW_HASH, 
     CON_MGT_USER_PRV_LEVEL,SLT_AREA  from CONTRACTOR_MGT_USERS where CON_MGT_CONTRACTOR = '{$con}' AND CON_MGT_USER_NAME = :CON_MGT_USER_NAME";


    $oraconn = OracleConnection();
    $auth = oci_parse($oraconn, $sql);
    oci_bind_by_name($auth, ":CON_MGT_USER_NAME", $uname);
    if(oci_execute($auth))
    {
    return $auth;
    }
    else
    {
        $err = oci_error($auth);
        $e =  $err['message'];
        echo "<script type='text/javascript'>alert('$e')</script>";
    }
}

function add_contractor($con_name,$con_user_name,$con_cor_name,$email,$con_mobile,$con_ofz)
{
    $sql = "INSERT INTO CONTRACTOR_MGT_USERS (CON_MGT_CONTRACTOR_NAME,CON_MGT_CONTRACTOR,CON_MGT_CON_COODINATOR,
            CON_MGT_USER_PRV_LEVEL,CON_MGT_USER_EMAIL,CON_MGT_MOBILE,CON_MGT_OFCENO)
            VALUES('$con_name','$con_user_name','$con_cor_name','0','$email','$con_mobile','$con_ofz')";
    $oraconn = OracleConnection();
    $add_con = oci_parse($oraconn, $sql);
    
    if(oci_execute($add_con))
    {
        return $add_con;
    }
    else
    {
        $err = oci_error($add_con);
        $e =  $err['message'];
        echo "<script type='text/javascript'>alert('$e')</script>";
    }
  
    
  
}

function add_contractor_user($con_name,$con_user_name,$login_user_name,$pre_level,$email,$con_mobile,$area)
{
    $sql = "INSERT INTO CONTRACTOR_MGT_USERS (CON_MGT_CONTRACTOR_NAME,CON_MGT_CONTRACTOR,CON_MGT_USER_NAME,CON_MGT_USER_PRV_LEVEL,
            CON_MGT_USER_EMAIL,CON_MGT_MOBILE,CON_MGT_USER,SLT_AREA)
            VALUES('$con_name','$con_name','$login_user_name','$pre_level','$email','$con_mobile','$con_user_name','$area')";
    $oraconn = OracleConnection();
    $add_con = oci_parse($oraconn, $sql);

    if(oci_execute($add_con))
    {
        return $add_con;
    }
    else
    {
        $err = oci_error($add_con);
        $e =  $err['message'];
        echo "<script type='text/javascript'>alert('$e')</script>";
    }
}

function sta_update($so_id)
{
    $sql = "update CONTRACTOR_NEW_CON set CON_SO_STATUS = 'INPROGRESS', CON_SO_STATUS_DATE = sysdate ,
            CON_STATUS = '1' where CON_SO_ID = '{$so_id}' and CON_SO_STATUS = 'ASSIGNED'";
    
    $oraconn = OracleConnection();
    $sta = oci_parse($oraconn, $sql);
   if(oci_execute($sta))
   {
    
    $sql = "update CONTRACTOR_WORK_ORDERS set CON_STATUS = 'INPROGRESS' where CON_SERO_ID = '{$so_id}' and CON_STATUS = 'ASSIGNED'";
    
    $oraconn = OracleConnection();
    $sta1 = oci_parse($oraconn, $sql);
    if(oci_execute($sta1))
    {
    return $sta1;
    }
    else
    {
        $err = oci_error($sta1);
        $e =  $err['message'];
        echo "<script type='text/javascript'>alert('$e')</script>";
    }
   }
   else
    {
        $err = oci_error($sta);
        $e =  $err['message'];
        echo "<script type='text/javascript'>alert('$e')</script>";
    }
}

function priority($so_id)
{
    $sql = "select CON_PRIORITY,CON_WORO_APPROVEDBY,CON_PSTN_NUMBER,CON_CUS_NAME,CON_TEC_CONTACT,CON_WORO_SERVICE_TYPE,CON_WORO_ORDER_TYPE,CON_WORO_DISCRIPTION,DP_LOOP_COMMENT
from CONTRACTOR_WORK_ORDERS
where CON_SERO_ID = '$so_id' and CON_STATUS <> 'RETURNED'";
    $oraconn = OracleConnection();
    $sta = oci_parse($oraconn, $sql);
    if(oci_execute($sta))
    {
    return $sta;
    }
    else
    {
        $err = oci_error($sta);
        $e =  $err['message'];
        echo "<script type='text/javascript'>alert('$e')</script>";
    }
    
}

function service_add($so_id)
{
    $sql = "select  distinct CON_ADDE_STREETNUMBER,CON_ADDE_STRN_NAMEANDTYPE, CON_ADDE_SUBURB, CON_ADDE_CITY
from CONTRACTOR_SERVICE_ADDRESS
where CON_ADDE_SERO_ID  = '$so_id' ";
    $oraconn = OracleConnection();
    $sta = oci_parse($oraconn, $sql);
    if(oci_execute($sta))
    {
    return $sta;
    }
    else
    {
        $err = oci_error($sta);
        $e =  $err['message'];
        echo "<script type='text/javascript'>alert('$e')</script>";
    }
}

function osp_date($so_id)
{
    $sql = "select distinct CON_OSP_DP_NAME,CON_OSP_DP_LOOP,CON_EX_AREA,CON_OSP_PHONE_CLASS,CON_OSP_PHONE_COLOUR,CON_PHN_PURCH,CON_SALES
from CONTRACTOR_OSP_DATA
where CON_OSP_SERO_ID = '$so_id'";
    $oraconn = OracleConnection();
    $sta = oci_parse($oraconn, $sql);
    if(oci_execute($sta))
    {
    return $sta;
    }
    else
    {
        $err = oci_error($sta);
        $e =  $err['message'];
        echo "<script type='text/javascript'>alert('$e')</script>";
    }
}

/*function update_due_date($so_id,$val)
{
    
    $sql ="select to_char(CON_DATE_TO_CONTRACTOR, 'mm/dd/yyyy hh:mi:ss AM') from CONTRACTOR_WORK_ORDERS where CON_SERO_ID = '{$so_id}'";
    $oraconn = OracleConnection();
    $rdate = oci_parse($oraconn, $sql);
   if( oci_execute($rdate))
   {
    $row=oci_fetch_array($rdate);
    
    $d_time =$row[0];
    
    $sql = "update  CONTRACTOR_NEW_CON set CON_SO_DUE_DATE = TO_DATE('$d_time','mm,dd,yyyy:hh:mi:ss pm')+$val where CON_SO_ID = '{$so_id}'";
    $oraconn = OracleConnection();
    $sta = oci_parse($oraconn, $sql);
    if(oci_execute($sta))
    {
    return $sta;
    }
    else
    {
        $err = oci_error($rdate);
        $e =  $err['message'];
        echo "<script type='text/javascript'>alert('$e')</script>";
    }
   }
   else
    {
        $err = oci_error($rdate);
        $e =  $err['message'];
        echo "<script type='text/javascript'>alert('$e')</script>";
    }
} */

function so_detail($so_id)
{
    $sql ="select to_char(c.CON_SO_DATE_RECEIVED, 'mm/dd/yyyy hh:mi:ss AM'),w.CON_WORO_ORDER_TYPE
        ,to_char(c.CON_SO_STATUS_DATE, 'mm/dd/yyyy hh:mi:ss AM'),w.CON_WORO_SERVICE_TYPE,c.CON_SO_STATUS,c.CON_CIRCUIT_NO,
        w.CON_WORO_DISCRIPTION,to_char(c.CON_SO_RTN_DATE, 'mm/dd/yyyy hh:mi:ss AM'),c.CON_AREA,to_char(c.CON_REASSIGNED_DATE, 'mm/dd/yyyy hh:mi:ss AM'),w.CON_WORO_TASK_NAME
        from CONTRACTOR_NEW_CON c, CONTRACTOR_WORK_ORDERS w
        where c.CON_SO_ID = '{$so_id}'
        and c.CON_SO_ID = w.CON_SERO_ID
	and w.CON_WORO_TASK_NAME = C.CON_TASK
        and w.CON_STATUS IN ('ASSIGNED', 'INPROGRESS', 'REASSIGNED','DELAYED','HOLD')";
    $oraconn = OracleConnection();
    $so_list = oci_parse($oraconn, $sql);
   if( oci_execute($so_list))
   {
    return $so_list;
   }
   else
    {
        $err = oci_error($so_list);
        $e =  $err['message'];
        echo "<script type='text/javascript'>alert('$e')</script>";
    }
}

function nc_met($so_id)                                              
{
    $sql ="select POLES_5_6_L,POLES_5_6_H,POLES_6_7,POLES_7_5,POLES_8,POLES_9,DROP_WIRE,INTERNAL_WIRE,EARTH_WIRE,DISCHARGER
            ,EARTH_ROD,RETAINERS,L_HOOK,C_HOOK,TELEPHONE,PVC_CONDUIT from CONTRACTOR_NEW_CON where CON_SO_ID = '{$so_id}'";
    $oraconn = OracleConnection();
    $ncm = oci_parse($oraconn, $sql);
   if(oci_execute($ncm))
   {
    return $ncm;
   }
   else
    {
        $err = oci_error($ncm);
        $e =  $err['message'];
        echo "<script type='text/javascript'>alert('$e')</script>";
    }
}


function so_detail_insert($so_id,$pole5_6L,$pole5_6H,$pole5_6_CE,$pole6_7_CE,$pole6_7,$pole7_5,$pole8,$pole9,
        $pole6_7_con,$pole7_5_con,$pole8_con,$pole9_con,
        $drop_wire,$earth_wire,$internal_wire,$discharger,$earth_rod,$retainers,$L_hook,$C_hook,$telephone,
        $pvc_conduit,$guy,$pole_strut,$spun_pole_5_6,$spun_pole_6_7,$spun_pole_5_6_slt,$spun_pole_6_7_slt, 
        $spun_pole_5_6_con, $spun_pole_6_7_con, $pole_5_6_con_ce ,$pole_6_7_con_ce, $pole_5_6_con ,$pole_s_con,
        $iptv_n ,$nc_bb, $cat5)
{
    /*$sql = "insert into CONTRACTOR_MERETIAL (MET_SO_ID,POLES_5_6_L, POLES_5_6_H ,POLES_5_6_CE,POLES_6_7_CE, POLES_6_7, POLES_7_5, 
        POLES_8, POLES_9, DROP_WIRE, INTERNAL_WIRE, EARTH_WIRE ,POLES_6_7_CON, POLES_7_5_CON,POLES_8_CON, POLES_9_CON,
		DISCHARGER, EARTH_ROD, RETAINERS, L_HOOK, C_HOOK, TELEPHONE, PVC_CONDUIT,GUY,POLE_STRUT,SPUN_POLE_5_6,SPUN_POLE_6_7)
values ('{$so_id}','{$pole5_6L}','{$pole5_6H}','$pole5_6_CE','$pole6_7_CE','{$pole6_7}','{$pole7_5}','{$pole8}',
		'{$pole9}','{$drop_wire}','{$internal_wire}','{$earth_wire}','{$pole6_7_con}','{$pole7_5_con}','{$pole8_con}',
		'{$pole9_con}','{$discharger}','{$earth_rod}','{$retainers}','{$L_hook}','{$C_hook}','{$telephone}','{$pvc_conduit}',
                  '{$guy}', '{$pole_strut}','{$spun_pole_5_6}','{$spun_pole_6_7}')";*/
    $sql = "insert into CONTRACTOR_MERETIAL values ('{$so_id}','{$pole5_6L}','{$pole5_6H}','{$pole5_6_CE}','{$pole6_7_CE}','{$pole6_7}','{$pole7_5}','{$pole8}',
		'{$pole9}','{$pole6_7_con}','{$pole7_5_con}','{$pole8_con}',
		'{$pole9_con}','{$drop_wire}','{$internal_wire}','{$earth_wire}','{$discharger}','{$earth_rod}','{$retainers}','{$L_hook}','{$C_hook}','{$telephone}','{$pvc_conduit}',
        '{$guy}', '{$pole_strut}','{$spun_pole_5_6}','{$spun_pole_6_7}','{$spun_pole_5_6_slt}','{$spun_pole_6_7_slt}','{$spun_pole_5_6_con}','{$spun_pole_6_7_con}',
		'{$cat5}','{$iptv_n}','{$nc_bb}','{$pole_5_6_con}','{$pole_5_6_con_ce}','{$pole_6_7_con_ce}','{$pole_s_con}')";
    
    $oraconn = OracleConnection();
    $so_list = oci_parse($oraconn, $sql);
    if(oci_execute($so_list))
    {
    return $so_list;
    }
    else
    {
        $err = oci_error($so_list);
        $e =  $err['message'];
        echo "<script type='text/javascript'>alert('$e')</script>";
    }
}




function so_detail_ser_insert($so_id,$pole5_6L_ser,$pole5_6H_ser,$pole5_6_CE_ser,$pole6_7_CE_ser,$pole6_7_ser,$pole7_5_ser,$pole8_ser,$pole9_ser,
        $pole6_7_con_ser,$pole7_5_con_ser,$pole8_con_ser,$pole9_con_ser,
        $drop_wire_ser,$earth_wire_ser,$internal_wire_ser,$discharger_ser,$earth_rod_ser,$retainers_ser,$L_hook_ser,$C_hook_ser,$telephone_ser,
        $pvc_conduit_ser,$guy_ser,$pole_strut_ser,$spun_pole_5_6_ser,$spun_pole_6_7_ser,$spun_pole_5_6_slt_ser,$spun_pole_6_7_slt_ser, 
        $spun_pole_5_6_con_ser, $spun_pole_6_7_con_ser, $pole_5_6_con_ce_ser ,$pole_6_7_con_ce_ser, $pole_5_6_con_ser ,$pole_s_con_ser, 
        $iptv_n_ser ,$nc_bb_ser, $cat5_ser)
{
    /*$sql = "insert into CONTRACTOR_MERETIAL_SER (MET_SO_ID,POLES_5_6_L_SER,POLES_5_6_H_SER,POLES_5_6_CE_SER,POLES_6_7_CE_SER,POLES_6_7_SER,
		POLES_7_5_SER,POLES_8_SER,POLES_9_SER,DROP_WIRE_SER,INTERNAL_WIRE_SER,EARTH_WIRE_SER,
		POLES_6_7_CON_SER,POLES_7_5_CON_SER,POLES_8_CON_SER,POLES_9_CON_SER,DISCHARGER_SER,EARTH_ROD_SER,
		RETAINERS_SER,L_HOOK_SER,C_HOOK_SER,TELEPHONE_SER,PVC_CONDUIT_SER,GUY_SER,POLE_STRUT_SER,
                SPUN_POLE_5_6_SER,SPUN_POLE_6_7_SER) 		
values ('{$so_id}','{$pole5_6L_ser}','{$pole5_6H_ser}','$pole5_6_CE_ser','$pole6_7_CE_ser','{$pole6_7_ser}',
		'{$pole7_5_ser}','{$pole8_ser}','{$pole9_ser}','{$drop_wire_ser}','{$internal_wire_ser}','{$earth_wire_ser}',
		'{$pole6_7_con_ser}','{$pole7_5_con_ser}','{$pole8_con_ser}','{$pole9_con_ser}','{$discharger_ser}','{$earth_rod_ser}',
		'{$retainers_ser}','{$L_hook_ser}','{$C_hook_ser}','{$telephone_ser}','{$pvc_conduit_ser}','{$guy_ser}', '{$pole_strut_ser}',
                 '{$spun_pole_5_6_ser}','{$spun_pole_6_7_ser}')";*/
    
    $sql = "insert into CONTRACTOR_MERETIAL_SER values ('{$so_id}','{$pole5_6L_ser}','{$pole5_6H_ser}','{$pole5_6_CE_ser}','{$pole6_7_CE_ser}','{$pole6_7_ser}',
		'{$pole7_5_ser}','{$pole8_ser}','{$pole9_ser}',
		'{$pole6_7_con_ser}','{$pole7_5_con_ser}','{$pole8_con_ser}','{$pole9_con_ser}',
                '{$drop_wire_ser}','{$internal_wire_ser}','{$earth_wire_ser}','{$discharger_ser}','{$earth_rod_ser}',
		'{$retainers_ser}','{$L_hook_ser}','{$C_hook_ser}','{$telephone_ser}','{$pvc_conduit_ser}','{$guy_ser}', '{$pole_strut_ser}',
       '{$spun_pole_5_6_ser}','{$spun_pole_6_7_ser}','{$spun_pole_5_6_slt_ser}','{$spun_pole_6_7_slt_ser}','{$spun_pole_5_6_con_ser}',
	   '{$spun_pole_6_7_con_ser}','{$cat5_ser}','{$iptv_n_ser}','{$nc_bb_ser}','{$pole_5_6_con_ser}','{$pole_5_6_con_ce_ser}','{$pole_6_7_con_ce_ser}',
           '{$pole_s_con_ser}')";
     
            
    $oraconn = OracleConnection();
    $so_list = oci_parse($oraconn, $sql);
    if(oci_execute($so_list))
    {
    return $so_list;
    }
    else
    {
        $err = oci_error($so_list);
        $e =  $err['message'];
        echo "<script type='text/javascript'>alert('$e')</script>";
    }
}

function so_detail_acc_insert($so_id,$pole5_6L_acc,$pole5_6H_acc,$pole5_6_CE_acc,$pole6_7_CE_acc,$pole6_7_acc,$pole7_5_acc,$pole8_acc,$pole9_acc,
        $pole6_7_con_acc,$pole7_5_con_acc,$pole8_con_acc,$pole9_con_acc,
        $drop_wire_acc,$earth_wire_acc,$internal_wire_acc,$discharger_acc,$earth_rod_acc,$retainers_acc,$L_hook_acc,$C_hook_acc,$telephone_acc,
        $pvc_conduit_acc,$guy_acc,$pole_strut_acc,$spun_pole_5_6_acc,$spun_pole_6_7_acc,$spun_pole_5_6_slt_acc,$spun_pole_6_7_slt_acc, $spun_pole_5_6_con_acc,
        $spun_pole_6_7_con_acc, $pole_5_6_con_ce_acc ,$pole_6_7_con_ce_acc, $pole_5_6_con_acc ,$pole_s_con_acc, $iptv_n_acc ,$nc_bb_acc, $cat5_acc)
{
    /*$sql = "insert into CONTRACTOR_MERETIAL_ACC (MET_SO_ID,POLES_5_6_L_ACC,POLES_5_6_H_ACC,POLES_5_6_CE_ACC,POLES_6_7_CE_ACC,POLES_6_7_ACC,
		POLES_7_5_ACC,POLES_8_ACC,POLES_9_ACC,DROP_WIRE_ACC,INTERNAL_WIRE_ACC,EARTH_WIRE_ACC,
		POLES_6_7_CON_ACC,POLES_7_5_CON_ACC,POLES_8_CON_ACC,POLES_9_CON_ACC,DISCHARGER_ACC,EARTH_ROD_ACC,
		RETAINERS_ACC,L_HOOK_ACC,C_HOOK_ACC,TELEPHONE_ACC,PVC_CONDUIT_ACC,GUY_ACC,POLE_STRUT_ACC,SPUN_POLE_5_6_ACC,SPUN_POLE_6_7_ACC) 		
values ('{$so_id}','{$pole5_6L_acc}','{$pole5_6H_acc}','$pole5_6_CE_acc','$pole6_7_CE_acc','{$pole6_7_acc}',
		'{$pole7_5_acc}','{$pole8_acc}','{$pole9_acc}','{$drop_wire_acc}','{$internal_wire_acc}','{$earth_wire_acc}',
		'{$pole6_7_con_acc}','{$pole7_5_con_acc}','{$pole8_con_acc}','{$pole9_con_acc}','{$discharger_acc}','{$earth_rod_acc}',
		'{$retainers_acc}','{$L_hook_acc}','{$C_hook_acc}','{$telephone_acc}','{$pvc_conduit_acc}','{$guy_acc}', '{$pole_strut_acc}',
                 '{$spun_pole_5_6_acc}','{$spun_pole_6_7_acc}'   )";*/
    
     $sql = "insert into CONTRACTOR_MERETIAL_ACC values ('{$so_id}','{$pole5_6L_acc}','{$pole5_6H_acc}','{$pole5_6_CE_acc}','{$pole6_7_CE_acc}','{$pole6_7_acc}',
		'{$pole7_5_acc}','{$pole8_acc}','{$pole9_acc}',
		'{$pole6_7_con_acc}','{$pole7_5_con_acc}','{$pole8_con_acc}','{$pole9_con_acc}','{$drop_wire_acc}','{$internal_wire_acc}','{$earth_wire_acc}','{$discharger_acc}','{$earth_rod_acc}',
		'{$retainers_acc}','{$L_hook_acc}','{$C_hook_acc}','{$telephone_acc}','{$pvc_conduit_acc}','{$guy_acc}', '{$pole_strut_acc}',
                 '{$spun_pole_5_6_acc}','{$spun_pole_6_7_acc}' ,'{$spun_pole_5_6_slt_acc}','{$spun_pole_6_7_slt_acc}','{$spun_pole_5_6_con_acc}',
		'{$spun_pole_6_7_con_acc}','{$cat5_acc}','{$iptv_n_acc}','{$nc_bb_acc}','{$pole_5_6_con_acc}','{$pole_5_6_con_ce_acc}','{$pole_6_7_con_ce_acc}','{$pole_s_con_acc}'  )";      
    $oraconn = OracleConnection();
    $so_list = oci_parse($oraconn, $sql);
    if(oci_execute($so_list))
    {
    return $so_list;
    }
    else
    {
        $err = oci_error($so_list);
        $e =  $err['message'];
        echo "<script type='text/javascript'>alert('$e')</script>";
    }
}

function so_detail_update($so_id,$pole5_6L,$pole5_6H,$pole5_6_CE,$pole6_7_CE,$pole6_7,$pole7_5,$pole8,$pole9,
        $pole6_7_con,$pole7_5_con,$pole8_con,$pole9_con,
        $drop_wire,$earth_wire,$internal_wire,$discharger,$earth_rod,$retainers,$L_hook,$C_hook,$telephone,
        $pvc_conduit,$guy,$pole_strut,$spun_pole_5_6,$spun_pole_6_7,$spun_pole_5_6_slt,$spun_pole_6_7_slt, 
        $spun_pole_5_6_con, $spun_pole_6_7_con, $pole_5_6_con_ce ,$pole_6_7_con_ce, $pole_5_6_con ,$pole_s_con,
        $iptv_n ,$nc_bb, $cat5)
{
    $sql = "update CONTRACTOR_MERETIAL set POLES_5_6_L = '{$pole5_6L}', POLES_5_6_H = '{$pole5_6H}',POLES_5_6_CE='$pole5_6_CE',POLES_6_7_CE='$pole6_7_CE', POLES_6_7 = '{$pole6_7}', POLES_7_5 = '{$pole7_5}'
        ,POLES_8 = '{$pole8}', POLES_9 = '{$pole9}', DROP_WIRE = '{$drop_wire}', INTERNAL_WIRE = '{$internal_wire}', EARTH_WIRE = '{$earth_wire}'
        ,POLES_6_7_CON = '{$pole6_7_con}', POLES_7_5_CON = '{$pole7_5_con}',POLES_8_CON = '{$pole8_con}', POLES_9_CON = '{$pole9_con}'
        , DISCHARGER = '{$discharger}', EARTH_ROD   = '{$earth_rod}', RETAINERS = '{$retainers}', L_HOOK = '{$L_hook}'
        , C_HOOK = '{$C_hook}', TELEPHONE = '{$telephone}', PVC_CONDUIT = '{$pvc_conduit}', GUY = '{$guy}', POLE_STRUT = '{$pole_strut}'
           , SPUN_POLE_5_6 = '{$spun_pole_5_6}',SPUN_POLE_6_7 = '{$spun_pole_6_7}', SPUN_POLE_5_6_SLT = '{$spun_pole_5_6_slt}',SPUN_POLE_6_7_SLT = '{$spun_pole_6_7_slt}'
            ,SPUN_POLE_5_6_CON = '{$spun_pole_5_6_con}',SPUN_POLE_6_7_CON = '{$spun_pole_6_7_con}',CAT5 = '{$cat5}',IPTV_N= '{$iptv_n}',NC_BB_PEO = '{$nc_bb}',POLES_5_6_CON     = '{$pole_5_6_con}',POLES_5_6_CON_CE  = '{$pole_5_6_con_ce}'
           ,POLES_6_7_CON_CE  = '{$pole_6_7_con_ce}',POLES_S_CON= '{$pole_s_con}'  where MET_SO_ID = '{$so_id}'";
    
     
    $oraconn = OracleConnection();
    $so_list = oci_parse($oraconn, $sql);
    if(oci_execute($so_list))
    {
    return $so_list;
    }
    else
    {
        $err = oci_error($so_list);
        $e =  $err['message'];
        echo "<script type='text/javascript'>alert('$e')</script>";
    }
}

function so_detail_acc_update($so_id,$pole5_6L_acc,$pole5_6H_acc,$pole5_6_CE_acc,$pole6_7_CE_acc,$pole6_7_acc,$pole7_5_acc,$pole8_acc,$pole9_acc,
        $pole6_7_con_acc,$pole7_5_con_acc,$pole8_con_acc,$pole9_con_acc,
        $drop_wire_acc,$earth_wire_acc,$internal_wire_acc,$discharger_acc,$earth_rod_acc,$retainers_acc,$L_hook_acc,$C_hook_acc,$telephone_acc,
        $pvc_conduit_acc,$guy_acc,$pole_strut_acc,$spun_pole_5_6_acc,$spun_pole_6_7_acc,$spun_pole_5_6_slt_acc,$spun_pole_6_7_slt_acc, $spun_pole_5_6_con_acc,
        $spun_pole_6_7_con_acc, $pole_5_6_con_ce_acc ,$pole_6_7_con_ce_acc, $pole_5_6_con_acc ,$pole_s_con_acc, $iptv_n_acc ,$nc_bb_acc, $cat5_acc)
{
    $sql = "update CONTRACTOR_MERETIAL_ACC set POLES_5_6_L_ACC  = '{$pole5_6L_acc}', POLES_5_6_H_ACC  = '{$pole5_6H_acc}',POLES_5_6_CE_ACC ='$pole5_6_CE_acc',POLES_6_7_CE_ACC ='$pole6_7_CE_acc', POLES_6_7_ACC  = '{$pole6_7_acc}', POLES_7_5_ACC = '{$pole7_5_acc}'
        ,POLES_8_ACC  = '{$pole8_acc}', POLES_9_ACC  = '{$pole9_acc}', DROP_WIRE_ACC  = '{$drop_wire_acc}',INTERNAL_WIRE_acc = '{$internal_wire_acc}', EARTH_WIRE_ACC  = '{$earth_wire_acc}'
        ,POLES_6_7_CON_ACC  = '{$pole6_7_con_acc}', POLES_7_5_CON_ACC  = '{$pole7_5_con_acc}',POLES_8_CON_ACC  = '{$pole8_con_acc}', POLES_9_CON_ACC  = '{$pole9_con_acc}'
        , DISCHARGER_ACC  = '{$discharger_acc}', EARTH_ROD_ACC = '{$earth_rod_acc}', RETAINERS_ACC  = '{$retainers_acc}', L_HOOK_ACC  = '{$L_hook_acc}'
        , C_HOOK_ACC  = '{$C_hook_acc}', TELEPHONE_ACC  = '{$telephone_acc}', PVC_CONDUIT_ACC  = '{$pvc_conduit_acc}', GUY_ACC  = '{$guy_acc}', POLE_STRUT_ACC ='{$pole_strut_acc}',
            SPUN_POLE_5_6_ACC = '{$spun_pole_5_6_acc}',SPUN_POLE_6_7_ACC = '{$spun_pole_6_7_acc}',SPUN_POLE_5_6_SLT_ACC = '{$spun_pole_5_6_slt_acc}',SPUN_POLE_6_7_SLT_ACC = '{$spun_pole_6_7_slt_acc}',
SPUN_POLE_5_6_CON_ACC = '{$spun_pole_5_6_con_acc}',SPUN_POLE_6_7_CON_ACC = '{$spun_pole_6_7_con_acc}',
CAT5_ACC = '{$cat5_acc}',IPTV_N_ACC = '{$iptv_n_acc}',NC_BB_PEO_ACC  = '{$nc_bb_acc}',POLES_5_6_CON_ACC  = '{$pole_5_6_con_acc}',
POLES_5_6_CON_CE_ACC  = '{$pole_5_6_con_ce_acc}',POLES_6_7_CON_CE_ACC  = '{$pole_6_7_con_ce_acc}',POLES_S_CON_ACC = '{$pole_s_con_acc}' where MET_SO_ID = '{$so_id}'";
    
    
    $oraconn = OracleConnection();
    $so_list = oci_parse($oraconn, $sql);
   if( oci_execute($so_list))
   {
    return $so_list;
   }
   else
    {
        $err = oci_error($so_list);
        $e =  $err['message'];
        echo "<script type='text/javascript'>alert('$e')</script>";
    }
}




function so_detail_ser_update($so_id,$pole5_6L_ser,$pole5_6H_ser,$pole5_6_CE_ser,$pole6_7_CE_ser,$pole6_7_ser,$pole7_5_ser,$pole8_ser,$pole9_ser,
        $pole6_7_con_ser,$pole7_5_con_ser,$pole8_con_ser,$pole9_con_ser,
        $drop_wire_ser,$earth_wire_ser,$internal_wire_ser,$discharger_ser,$earth_rod_ser,$retainers_ser,$L_hook_ser,$C_hook_ser,$telephone_ser,
        $pvc_conduit_ser,$guy_ser,$pole_strut_ser,$spun_pole_5_6_ser,$spun_pole_6_7_ser,$spun_pole_5_6_slt_ser,$spun_pole_6_7_slt_ser, 
        $spun_pole_5_6_con_ser, $spun_pole_6_7_con_ser, $pole_5_6_con_ce_ser ,$pole_6_7_con_ce_ser, $pole_5_6_con_ser ,$pole_s_con_ser, 
        $iptv_n_ser ,$nc_bb_ser, $cat5_ser)
{
    $sql = "update CONTRACTOR_MERETIAL_SER set POLES_5_6_L_SER = '{$pole5_6L_ser}', POLES_5_6_H_SER = '{$pole5_6H_ser}',POLES_5_6_CE_SER='$pole5_6_CE_ser',POLES_6_7_CE_SER='$pole6_7_CE_ser', POLES_6_7_SER = '{$pole6_7_ser}', POLES_7_5_SER = '{$pole7_5_ser}'
        ,POLES_8_SER = '{$pole8_ser}', POLES_9_SER = '{$pole9_ser}', DROP_WIRE_SER = '{$drop_wire_ser}',INTERNAL_WIRE_SER = '{$internal_wire_ser}', EARTH_WIRE_SER = '{$earth_wire_ser}'
        ,POLES_6_7_CON_SER = '{$pole6_7_con_ser}', POLES_7_5_CON_SER = '{$pole7_5_con_ser}',POLES_8_CON_SER = '{$pole8_con_ser}', POLES_9_CON_SER = '{$pole9_con_ser}'
        , DISCHARGER_SER = '{$discharger_ser}', EARTH_ROD_SER= '{$earth_rod_ser}', RETAINERS_SER = '{$retainers_ser}', L_HOOK_SER = '{$L_hook_ser}'
        , C_HOOK_SER = '{$C_hook_ser}', TELEPHONE_SER = '{$telephone_ser}', PVC_CONDUIT_SER = '{$pvc_conduit_ser}', GUY_SER = '{$guy_ser}', POLE_STRUT_SER='{$pole_strut_ser}',
        SPUN_POLE_5_6_SER = '{$spun_pole_5_6_ser}',SPUN_POLE_6_7_SER = '{$spun_pole_6_7_ser}',SPUN_POLE_5_6_SLT_SER = '{$spun_pole_5_6_slt_ser}',SPUN_POLE_6_7_SLT_SER = '{$spun_pole_6_7_slt_ser}',
SPUN_POLE_5_6_CON_SER = '{$spun_pole_5_6_con_ser}',SPUN_POLE_6_7_CON_SER = '{$spun_pole_6_7_con_ser}',
CAT5_SER = '{$cat5_ser}',IPTV_N_SER = '{$iptv_n_ser}',NC_BB_PEO_SER = '{$nc_bb_ser}',POLES_5_6_CON_SER = '{$pole_5_6_con_ser}',
POLES_5_6_CON_CE_SER  = '{$pole_5_6_con_ce_ser}',POLES_6_7_CON_CE_SER  = '{$pole_6_7_con_ce_ser}',POLES_S_CON_SER ='{$pole_s_con_ser}' where MET_SO_ID = '{$so_id}'";
    

    
    $oraconn = OracleConnection();
    $so_list = oci_parse($oraconn, $sql);
   if( oci_execute($so_list))
   {
    return $so_list;
   }
   else
    {
        $err = oci_error($so_list);
        $e =  $err['message'];
        echo "<script type='text/javascript'>alert('$e')</script>";
    }
}

function so_comp($so_id)
{
    $sql = "select to_char(CON_SO_DATE_RECEIVED, 'mm/dd/yyyy hh:mi:ss AM'),to_char(CON_SO_RTN_DATE, 'mm/dd/yyyy hh:mi:ss AM')
        ,to_char(CON_SO_STATUS_DATE, 'mm/dd/yyyy hh:mi:ss AM'),to_char(CON_REASSIGNED_DATE, 'mm/dd/yyyy hh:mi:ss AM')
        ,to_char(CON_SO_DATE_RECEIVED, 'mm/dd/yyyy hh:mi:ss AM'),to_char(CON_REASSIGNED_DATE, 'mm/dd/yyyy hh:mi:ss AM') from CONTRACTOR_NEW_CON
        where CON_SO_ID = '{$so_id}' and CON_TASK like 'INS%'";
    $oraconn = OracleConnection();
    $so_com = oci_parse($oraconn, $sql);
    if(oci_execute($so_com))
    {
    return $so_com;
    }
    else
    {
        $err = oci_error($so_com);
        $e =  $err['message'];
        echo "<script type='text/javascript'>alert('$e')</script>";
    }
    
}

function so_compCab($so_id)
{
    $sql = "select to_char(CON_SO_DATE_RECEIVED, 'mm/dd/yyyy hh:mi:ss AM'),to_char(CON_SO_RTN_DATE, 'mm/dd/yyyy hh:mi:ss AM')
        ,to_char(CON_SO_STATUS_DATE, 'mm/dd/yyyy hh:mi:ss AM'),to_char(CON_REASSIGNED_DATE, 'mm/dd/yyyy hh:mi:ss AM')
        ,to_char(CON_SO_COM_DATE, 'mm/dd/yyyy hh:mi:ss AM') from CONTRACTOR_NEW_CON
        where CON_SO_ID = '{$so_id}' and (CON_TASK = 'CONSTRUCT OSP' or CON_TASK = 'RECONSTRUCT OSP') order by  CON_SO_DATE_RECEIVED DESC";
    $oraconn = OracleConnection();
    $so_com = oci_parse($oraconn, $sql);
    if(oci_execute($so_com))
    {
    return $so_com;
    }
    else
    {
        $err = oci_error($so_com);
        $e =  $err['message'];
        echo "<script type='text/javascript'>alert('$e')</script>";
    }
    
}

function so_compCons($so_id)
{
    $sql = "select to_char(CON_SO_DATE_RECEIVED, 'mm/dd/yyyy hh:mi:ss AM'),to_char(CON_SO_RTN_DATE, 'mm/dd/yyyy hh:mi:ss AM')
        ,to_char(CON_SO_STATUS_DATE, 'mm/dd/yyyy hh:mi:ss AM'),to_char(CON_REASSIGNED_DATE, 'mm/dd/yyyy hh:mi:ss AM')
        ,to_char(CON_SO_COM_DATE, 'mm/dd/yyyy hh:mi:ss AM'),CON_TASK from CONTRACTOR_NEW_CON
        where CON_SO_ID = '{$so_id}' and (CON_TASK = 'CONSTRUCT OSP' or CON_TASK = 'RECONSTRUCT OSP') order by  CON_SO_DATE_RECEIVED DESC";
    $oraconn = OracleConnection();
    $so_com = oci_parse($oraconn, $sql);
    if(oci_execute($so_com))
    {
    return $so_com;
    }
    else
    {
        $err = oci_error($so_com);
        $e =  $err['message'];
        echo "<script type='text/javascript'>alert('$e')</script>";
    }
    
}


function so_dly($so_id)
{
    $sql = "select to_char(w.CON_SO_DATE_RECEIVED, 'mm/dd/yyyy hh:mi:ss AM'),to_char(w.CON_SO_DUE_DATE, 'mm/dd/yyyy hh:mi:ss AM')
,to_char(w.CON_SO_STATUS_DATE, 'mm/dd/yyyy hh:mi:ss AM'),w.CON_NEW_CON_TYPE , C.CON_COMM_TEXT
from CONTRACTOR_NEW_CON w ,CONTRACTOR_ORDER_COMMENTS c
where w.CON_SO_ID = '{$so_id}'       
and C.CON_COMM_SERO_ID  = W.CON_SO_ID";
    $oraconn = OracleConnection();
    $so_com = oci_parse($oraconn, $sql);
    if(oci_execute($so_com))
    {
    return $so_com;
    }
    else
    {
        $err = oci_error($so_com);
        $e =  $err['message'];
        echo "<script type='text/javascript'>alert('$e')</script>";
    }
}

function so_com_update($so_id,$days,$penalty,$con_type,$pl_count,$dploopcom,$tstnum,$polesn,$delday)
{
    
    $sql = "update  CONTRACTOR_NEW_CON set CON_SO_COM_DATE = sysdate, CON_SO_STATUS = 'COMPLETED', CON_DLY_DAYS = '{$days}',
            CON_PENALTY = '{$penalty}', CON_NEW_CON_TYPE = '{$con_type}', CON_STATUS = '2' , POLE_COUNT='{$pl_count}' ,
            DELAY_DATE= '{$delday}' where CON_SO_ID = '{$so_id}' and CON_SO_STATUS <> 'RETURNED' and  (CON_TASK ='RECONSTRUCT OSP' or CON_TASK= 'CONSTRUCT OSP') ";

    $oraconn = OracleConnection();
    $com = oci_parse($oraconn, $sql);
   if( oci_execute($com))
   {       
    //$sql = "update  CONTRACTOR_WORK_ORDERS set CON_STATUS_DATE = TO_DATE('$date','mm,dd,yyyy:hh:mi:ss pm'), CON_STATUS = 'COMPLETED' where CON_SERO_ID = '{$so_id}'";
    $sql = "update  CONTRACTOR_WORK_ORDERS set CON_STATUS_DATE = sysdate, CON_STATUS = 'COMPLETED' , DP_LOOP_COMMENT='$dploopcom'
     where CON_SERO_ID = '{$so_id}' and CON_STATUS <> 'RETURNED' and (CON_WORO_TASK_NAME ='RECONSTRUCT OSP' or CON_WORO_TASK_NAME= 'CONSTRUCT OSP')";

    if($polesn != "")
    {
        $sql ="insert into CONTRACTOR_POLE_SN values ('$so_id','$polesn')";
        $oraconn = OracleConnection();
        $con = oci_parse($oraconn, $sql);
        oci_execute($con);
    }
    
    $oraconn = OracleConnection();
    $com1 = oci_parse($oraconn, $sql);
   if(oci_execute($com1))
   {
    return $com1;
   }
   else
    {
        $err = oci_error($com1);
        $e =  $err['message'];
        echo "<script type='text/javascript'>alert('$e')</script>";
    }
 }
   else
    {
        $err = oci_error($com);
        $e =  $err['message'];
        echo "<script type='text/javascript'>alert('$e')</script>";
    }
}

function so_com_updateftth($so_id,$days,$penalty,$con_type,$pl_count,$dploopcom,$tstnum,$polesn,$delday)
{
    
    $sql = "update  CONTRACTOR_NEW_CON set CON_SO_COM_DATE = sysdate, CON_SO_STATUS = 'COMPLETED', CON_DLY_DAYS = '{$days}',
            CON_PENALTY = '{$penalty}', CON_NEW_CON_TYPE = '{$con_type}', CON_STATUS = '2' , POLE_COUNT='{$pl_count}' ,
            DELAY_DATE= '{$delday}' where CON_SO_ID = '{$so_id}' and CON_SO_STATUS = 'INPROGRESS' and CON_TASK like 'INS%'";
    
    $oraconn = OracleConnection();
    $com = oci_parse($oraconn, $sql);
   if( oci_execute($com))
   {       
    //$sql = "update  CONTRACTOR_WORK_ORDERS set CON_STATUS_DATE = TO_DATE('$date','mm,dd,yyyy:hh:mi:ss pm'), CON_STATUS = 'COMPLETED' where CON_SERO_ID = '{$so_id}'";
    $sql = "update  CONTRACTOR_WORK_ORDERS set CON_STATUS_DATE = sysdate, CON_STATUS = 'COMPLETED' , DP_LOOP_COMMENT='$dploopcom'
     where CON_SERO_ID = '{$so_id}' and CON_STATUS = 'INPROGRESS' and CON_WORO_TASK_NAME like 'INS%'";
    
    if($polesn != "")
    {
        $sql ="insert into CONTRACTOR_POLE_SN values ('$so_id','$polesn')";
        $oraconn = OracleConnection();
        $con = oci_parse($oraconn, $sql);
        oci_execute($con);
    }
    
    $oraconn = OracleConnection();
    $com1 = oci_parse($oraconn, $sql);
   if(oci_execute($com1))
   {
    return $com1;
   }
   else
    {
        $err = oci_error($com1);
        $e =  $err['message'];
        echo "<script type='text/javascript'>alert('$e')</script>";
    }
 }
   else
    {
        $err = oci_error($com);
        $e =  $err['message'];
        echo "<script type='text/javascript'>alert('$e')</script>";
    }
}


function so_delay($so_id,$dly_reason,$user)
{
    $sql = "update  CONTRACTOR_NEW_CON set CON_SO_STATUS = 'DELAYED', CON_SO_DLY_DATE = sysdate where CON_SO_ID = '{$so_id}' and CON_SO_STATUS <> 'RETURNED'";
    $oraconn = OracleConnection();
    $com = oci_parse($oraconn, $sql);
    if(oci_execute($com))
    { 
    $sql = "update  CONTRACTOR_WORK_ORDERS set CON_STATUS = 'DELAYED', CON_STATUS_DATE =sysdate where CON_SERO_ID = '{$so_id}' and CON_STATUS <> 'RETURNED'";
    $oraconn = OracleConnection();
    $com1 = oci_parse($oraconn, $sql);
    if(oci_execute($com1))
    {
    $sql = "insert into CONTRACTOR_ORDER_COMMENTS (CON_COMM_SERO_ID,CON_COMM_TEXT,CON_COMM_TIME,CON_COMM_USER,CON_COMM_STATUS) 
        values ('$so_id', '$dly_reason', sysdate , '$user','DELAYED' )";
    $oraconn = OracleConnection();
    $delay = oci_parse($oraconn, $sql);
    if(oci_execute($delay))
    {
    return $delay;
    }
    else
    {
        $err = oci_error($delay);
        $e =  $err['message'];
        echo "<script type='text/javascript'>alert('$e')</script>";
    }
    }
    else
    {
        $err = oci_error($com1);
        $e =  $err['message'];
        echo "<script type='text/javascript'>alert('$e')</script>";
    }
    }
    else
    {
        $err = oci_error($com);
        $e =  $err['message'];
        echo "<script type='text/javascript'>alert('$e')</script>";
    }
}


function so_return($so_id,$reason,$user)
{
    $sql = "insert into CONTRACTOR_ORDER_COMMENTS (CON_COMM_SERO_ID,CON_COMM_TEXT,CON_COMM_TIME,CON_COMM_USER,CON_COMM_STATUS) 
        values ('$so_id', '$reason', sysdate , '$user','RETURNED' )";
    $oraconn = OracleConnection();
    $return2 = oci_parse($oraconn, $sql);
    if(oci_execute($return2))
    {
    $sql = "update  CONTRACTOR_NEW_CON set CON_SO_STATUS = 'RETURNED', CON_SO_RTN_DATE = sysdate  where CON_SO_ID = '{$so_id}'";
    $oraconn = OracleConnection();
    $return = oci_parse($oraconn, $sql);
    if(oci_execute($return))
    {
    $sql = "update  CONTRACTOR_WORK_ORDERS set CON_STATUS = 'RETURNED', CON_STATUS_DATE = sysdate where CON_SERO_ID = '{$so_id}'";
    $oraconn = OracleConnection();
    $return1 = oci_parse($oraconn, $sql);
    if(oci_execute($return1))
    {
    return $return1;
    }
    else
    {
        $err = oci_error($return1);
        $e =  $err['message'];
        echo "<script type='text/javascript'>alert('$e')</script>";
    }
    }
    else
    {
        $err = oci_error($return);
        $e =  $err['message'];
        echo "<script type='text/javascript'>alert('$e')</script>";
    }
    }
    else
    {
        $err = oci_error($return2);
        $e =  $err['message'];
        echo "<script type='text/javascript'>alert('$e')</script>";
    }
}

function return_list($fromdate,$todate,$area,$sertyp,$ortyp)
{
     if($sertyp == 'AB-CAB'){
    $sql = "select   c.CON_SO_ID,to_char(c.CON_SO_DATE_RECEIVED, 'mm/dd/yyyy hh:mi:ss AM'),c.CON_NEW_CON_TYPE, c.CON_CIRCUIT_NO,
        to_char( cm.CON_COMM_TIME, 'mm/dd/yyyy hh:mi:ss AM'),c.CON_DLY_DAYS,c.CON_PENALTY, cm.CON_COMM_TEXT,c.CON_CONTRACTOR
from CONTRACTOR_NEW_CON c, CONTRACTOR_ORDER_COMMENTS cm, CONTRACTOR_WORK_ORDERS b
where c.CON_SO_ID = b.CON_SERO_ID  
and   c.CON_SO_STATUS = 'RETURNED'
and c.CON_AREA = '$area' 
and b.CON_WORO_SERVICE_TYPE IN ( '$sertyp','PSTN')
and b.CON_WORO_ORDER_TYPE = '$ortyp'
and C.CON_SO_ID = CM.CON_COMM_SERO_ID
and b.CON_SERO_ID  = CM.CON_COMM_SERO_ID
and c.CON_SO_RTN_DATE BETWEEN  TO_DATE('$fromdate 12:00:00 AM','mm,dd,yyyy:hh:mi:ss pm') AND TO_DATE('$todate 11:59:59 PM','mm,dd,yyyy:hh:mi:ss pm')
and cm.CON_COMM_TIME = 
(select max(cm1.CON_COMM_TIME) from OSS_DEV_01.CONTRACTOR_ORDER_COMMENTS cm1
where cm1.CON_COMM_SERO_ID = C.CON_SO_ID)";
    
   }
   else if($sertyp == 'E-COPPER IPTV')
   {
    $sql = "select   c.CON_SO_ID,to_char(c.CON_SO_DATE_RECEIVED, 'mm/dd/yyyy hh:mi:ss AM'),c.CON_NEW_CON_TYPE, c.CON_CIRCUIT_NO,
        to_char( cm.CON_COMM_TIME, 'mm/dd/yyyy hh:mi:ss AM'),c.CON_DLY_DAYS,c.CON_PENALTY, cm.CON_COMM_TEXT,c.CON_CONTRACTOR
from CONTRACTOR_NEW_CON c, CONTRACTOR_ORDER_COMMENTS cm, CONTRACTOR_WORK_ORDERS b
where c.CON_SO_ID = b.CON_SERO_ID  
and   c.CON_SO_STATUS = 'RETURNED'
and c.CON_AREA = '$area' 
and b.CON_WORO_SERVICE_TYPE IN ('$sertyp','IPTV')
and b.CON_WORO_ORDER_TYPE = '$ortyp'
and C.CON_SO_ID = CM.CON_COMM_SERO_ID
and b.CON_SERO_ID  = CM.CON_COMM_SERO_ID
and c.CON_SO_RTN_DATE BETWEEN  TO_DATE('$fromdate 12:00:00 AM','mm,dd,yyyy:hh:mi:ss pm') AND TO_DATE('$todate 11:59:59 PM','mm,dd,yyyy:hh:mi:ss pm')
and cm.CON_COMM_TIME = 
(select max(cm1.CON_COMM_TIME) from OSS_DEV_01.CONTRACTOR_ORDER_COMMENTS cm1
where cm1.CON_COMM_SERO_ID = C.CON_SO_ID)";
   }
   else{
    $sql = "select   c.CON_SO_ID,to_char(c.CON_SO_DATE_RECEIVED, 'mm/dd/yyyy hh:mi:ss AM'),c.CON_NEW_CON_TYPE, c.CON_CIRCUIT_NO,
        to_char( cm.CON_COMM_TIME, 'mm/dd/yyyy hh:mi:ss AM'),c.CON_DLY_DAYS,c.CON_PENALTY, cm.CON_COMM_TEXT,c.CON_CONTRACTOR
from CONTRACTOR_NEW_CON c, CONTRACTOR_ORDER_COMMENTS cm, CONTRACTOR_WORK_ORDERS b
where c.CON_SO_ID = b.CON_SERO_ID  
and   c.CON_SO_STATUS = 'RETURNED'
and c.CON_AREA = '$area' 
and b.CON_WORO_SERVICE_TYPE = '$sertyp'
and b.CON_WORO_ORDER_TYPE = '$ortyp'
and C.CON_SO_ID = CM.CON_COMM_SERO_ID
and b.CON_SERO_ID  = CM.CON_COMM_SERO_ID
and c.CON_SO_RTN_DATE BETWEEN  TO_DATE('$fromdate 12:00:00 AM','mm,dd,yyyy:hh:mi:ss pm') AND TO_DATE('$todate 11:59:59 PM','mm,dd,yyyy:hh:mi:ss pm')
and cm.CON_COMM_TIME = 
(select max(cm1.CON_COMM_TIME) from OSS_DEV_01.CONTRACTOR_ORDER_COMMENTS cm1
where cm1.CON_COMM_SERO_ID = C.CON_SO_ID)";
}
   

 $oraconn = OracleConnection();
    $relist = oci_parse($oraconn, $sql);
    if(oci_execute($relist))
    {
    return $relist;
    }
    else
    {
        $err = oci_error($relist);
        $e =  $err['message'];
        echo "<script type='text/javascript'>alert('$e')</script>";
    }
}

function delay_list($fromdate,$todate,$area,$sertyp,$ortyp)
{
    $sql = "select c.CON_SO_ID,to_char(c.CON_SO_DATE_RECEIVED, 'mm/dd/yyyy hh:mi:ss AM'),c.CON_NEW_CON_TYPE, c.CON_CIRCUIT_NO,
         to_char(c.CON_SO_DLY_DATE, 'mm/dd/yyyy hh:mi:ss AM'),c.CON_DLY_DAYS,c.CON_PENALTY, cm.CON_COMM_TEXT,c.CON_CONTRACTOR
from CONTRACTOR_NEW_CON c, CONTRACTOR_ORDER_COMMENTS cm, CONTRACTOR_WORK_ORDERS b
where c.CON_SO_ID = b.CON_SERO_ID
and b.CON_SERO_ID =cm.CON_COMM_SERO_ID
and c.CON_SO_STATUS = 'DELAYED'
and c.CON_AREA = '$area'
and b.CON_WORO_SERVICE_TYPE = '$sertyp' 
and b.CON_WORO_ORDER_TYPE = '$ortyp'
and C.CON_SO_ID = CM.CON_COMM_SERO_ID
and c.CON_SO_DLY_DATE BETWEEN  TO_DATE('$fromdate 12:00:00 AM','mm,dd,yyyy:hh:mi:ss pm') AND TO_DATE('$todate 11:59:59 PM','mm,dd,yyyy:hh:mi:ss pm')";
	$oraconn = OracleConnection();
    $dlylist = oci_parse($oraconn, $sql);
    if(oci_execute($dlylist))
    {
    return $dlylist;
    }
    else
    {
        $err = oci_error($dlylist);
        $e =  $err['message'];
        echo "<script type='text/javascript'>alert('$e')</script>";
    }
}

function completed_list($fromdate,$todate,$area,$sertyp,$ortyp)
{
    if($sertyp == 'AB-CAB')
    {
           $sql = "select c.CON_SO_ID,to_char(c.CON_SO_DATE_RECEIVED, 'mm/dd/yyyy hh:mi:ss AM'),c.CON_NEW_CON_TYPE, to_char(c.CON_SO_STATUS_DATE, 'mm/dd/yyyy hh:mi:ss AM'),
         to_char(c.CON_SO_COM_DATE, 'mm/dd/yyyy hh:mi:ss AM'),c.CON_DLY_DAYS,c.CON_PENALTY,c.CON_CIRCUIT_NO,c.CON_CONTRACTOR,c.CON_STATUS,c.POLE_COUNT
        from CONTRACTOR_NEW_CON c, CONTRACTOR_WORK_ORDERS b
		where c.CON_SO_ID = b.CON_SERO_ID  
		and c.CON_SO_STATUS = 'COMPLETED'
	and c.CON_AREA = '$area'
	and b.CON_WORO_SERVICE_TYPE IN ( '$sertyp','PSTN')
	and b.CON_WORO_ORDER_TYPE = '$ortyp'
        and c.CON_SO_COM_DATE BETWEEN  TO_DATE('$fromdate 12:00:00 AM','mm,dd,yyyy:hh:mi:ss pm') AND TO_DATE('$todate 11:59:59 PM','mm,dd,yyyy:hh:mi:ss pm')"; 
  
    }
    else if($sertyp == 'E-IPTV COPPER')
    {
           $sql = "select c.CON_SO_ID,to_char(c.CON_SO_DATE_RECEIVED, 'mm/dd/yyyy hh:mi:ss AM'),c.CON_NEW_CON_TYPE, to_char(c.CON_SO_STATUS_DATE, 'mm/dd/yyyy hh:mi:ss AM'),
         to_char(c.CON_SO_COM_DATE, 'mm/dd/yyyy hh:mi:ss AM'),c.CON_DLY_DAYS,c.CON_PENALTY,c.CON_CIRCUIT_NO,c.CON_CONTRACTOR,c.CON_STATUS,c.POLE_COUNT
        from CONTRACTOR_NEW_CON c, CONTRACTOR_WORK_ORDERS b
		where c.CON_SO_ID = b.CON_SERO_ID  
		and c.CON_SO_STATUS = 'COMPLETED'
	and c.CON_AREA = '$area'
	and b.CON_WORO_SERVICE_TYPE IN ( '$sertyp','IPTV')
	and b.CON_WORO_ORDER_TYPE = '$ortyp'
        and c.CON_SO_COM_DATE BETWEEN  TO_DATE('$fromdate 12:00:00 AM','mm,dd,yyyy:hh:mi:ss pm') AND TO_DATE('$todate 11:59:59 PM','mm,dd,yyyy:hh:mi:ss pm')"; 
  
    }
    else
    {
            $sql = "select c.CON_SO_ID,to_char(c.CON_SO_DATE_RECEIVED, 'mm/dd/yyyy hh:mi:ss AM'),c.CON_NEW_CON_TYPE, to_char(c.CON_SO_STATUS_DATE, 'mm/dd/yyyy hh:mi:ss AM'),
         to_char(c.CON_SO_COM_DATE, 'mm/dd/yyyy hh:mi:ss AM'),c.CON_DLY_DAYS,c.CON_PENALTY,c.CON_CIRCUIT_NO,c.CON_CONTRACTOR,c.CON_STATUS,c.POLE_COUNT
        from CONTRACTOR_NEW_CON c, CONTRACTOR_WORK_ORDERS b
		where c.CON_SO_ID = b.CON_SERO_ID  
		and c.CON_SO_STATUS = 'COMPLETED'
	and c.CON_AREA = '$area'
	and b.CON_WORO_SERVICE_TYPE = '$sertyp'
	and b.CON_WORO_ORDER_TYPE = '$ortyp'
        and c.CON_SO_COM_DATE BETWEEN  TO_DATE('$fromdate 12:00:00 AM','mm,dd,yyyy:hh:mi:ss pm') AND TO_DATE('$todate 11:59:59 PM','mm,dd,yyyy:hh:mi:ss pm')";
        
    }
 
     $oraconn = OracleConnection();
    $comlist = oci_parse($oraconn, $sql);
    if(oci_execute($comlist))
    {
    return $comlist;
    }
    else
    {
        $err = oci_error($comlist);
        $e =  $err['message'];
        echo "<script type='text/javascript'>alert('$e')</script>";
    }
}

function completed_list2($contractor,$fromdate,$todate,$sertyp,$ortyp)
{
    $sql = "select c.CON_SO_ID,to_char(c.CON_SO_DATE_RECEIVED, 'mm/dd/yyyy hh:mi:ss AM'),c.CON_NEW_CON_TYPE, to_char(c.CON_SO_STATUS_DATE, 'mm/dd/yyyy hh:mi:ss AM'),
         to_char(c.CON_SO_COM_DATE, 'mm/dd/yyyy hh:mi:ss AM'),c.CON_DLY_DAYS,c.CON_PENALTY,c.CON_CIRCUIT_NO,c.CON_CONTRACTOR,c.CON_STATUS,c.POLE_COUNT,c.CON_AREA
        from CONTRACTOR_NEW_CON c, CONTRACTOR_WORK_ORDERS b
		where c.CON_SO_ID = b.CON_SERO_ID  
		and c.CON_SO_STATUS = 'COMPLETED'
	and c.CON_CONTRACTOR = '$contractor'
	and b.CON_WORO_SERVICE_TYPE = '$sertyp'
	and b.CON_WORO_ORDER_TYPE = '$ortyp'
        and c.CON_SO_COM_DATE BETWEEN  TO_DATE('$fromdate 12:00:00 AM','mm,dd,yyyy:hh:mi:ss pm') AND TO_DATE('$todate 11:59:59 PM','mm,dd,yyyy:hh:mi:ss pm')
        order by c.CON_AREA";
    $oraconn = OracleConnection();
    $comlist = oci_parse($oraconn, $sql);
    if(oci_execute($comlist))
    {
    return $comlist;
    }
    else
    {
        $err = oci_error($comlist);
        $e =  $err['message'];
        echo "<script type='text/javascript'>alert('$e')</script>";
    }
}

function completed_list3($fromdate,$todate,$sertyp,$ortyp)
{
    $sql = "select c.CON_SO_ID,to_char(c.CON_SO_DATE_RECEIVED, 'mm/dd/yyyy hh:mi:ss AM'),c.CON_NEW_CON_TYPE, to_char(c.CON_SO_STATUS_DATE, 'mm/dd/yyyy hh:mi:ss AM'),
         to_char(c.CON_SO_COM_DATE, 'mm/dd/yyyy hh:mi:ss AM'),c.CON_DLY_DAYS,c.CON_PENALTY,c.CON_CIRCUIT_NO,c.CON_CONTRACTOR,c.CON_STATUS,c.POLE_COUNT,c.CON_AREA
        from CONTRACTOR_NEW_CON c, CONTRACTOR_WORK_ORDERS b
		where c.CON_SO_ID = b.CON_SERO_ID  
		and c.CON_SO_STATUS = 'COMPLETED'
	    and b.CON_WORO_SERVICE_TYPE = '$sertyp'
	    and b.CON_WORO_ORDER_TYPE = '$ortyp'
        and c.CON_SO_COM_DATE BETWEEN  TO_DATE('$fromdate 12:00:00 AM','mm,dd,yyyy:hh:mi:ss pm') AND TO_DATE('$todate 11:59:59 PM','mm,dd,yyyy:hh:mi:ss pm')
        order by c.CON_AREA,c.CON_CONTRACTOR";
    $oraconn = OracleConnection();
    $comlist = oci_parse($oraconn, $sql);
    if(oci_execute($comlist))
    {
    return $comlist;
    }
    else
    {
        $err = oci_error($comlist);
        $e =  $err['message'];
        echo "<script type='text/javascript'>alert('$e')</script>";
    }
}


function pending_list($fromdate,$todate,$area,$sertyp,$ortyp)
{
    
    
    if($sertyp == 'E-IPTV COPPER' || $sertyp == 'E-IPTV FTTH' )
    {
    $sql = "select distinct * 
from (select a.CON_SO_ID,a.CON_CIRCUIT_NO, to_char(a.CON_SO_DATE_RECEIVED, 'mm/dd/yyyy hh:mi:ss AM'),a.CON_SO_STATUS, a.CON_CONTRACTOR,
    c.CON_EX_AREA
from CONTRACTOR_NEW_CON a, CONTRACTOR_WORK_ORDERS b,CONTRACTOR_EQ_DATA c
where a.CON_SO_ID = b.CON_SERO_ID
and a.CON_SO_ID = c.CON_EQ_SERO_ID
and b.CON_SERO_ID = c.CON_EQ_SERO_ID
and a.CON_SO_STATUS IN ('ASSIGNED', 'INPROGRESS','REASSIGNED','DELAYED')
and a.CON_AREA = '$area'
and b.CON_WORO_SERVICE_TYPE = '$sertyp'
and b.CON_WORO_ORDER_TYPE = '$ortyp' 
 and a.CON_SO_DATE_RECEIVED BETWEEN  TO_DATE('$fromdate 12:00:00 AM','mm,dd,yyyy:hh:mi:ss pm') 
 AND TO_DATE('$todate 11:59:59 PM','mm,dd,yyyy:hh:mi:ss pm')
order by  a.CON_AREA,c.CON_EX_AREA)";
}

if($sertyp == 'AB-CAB' || $sertyp=='AB-FTTH' )
  {
    $sql = "select distinct * 
from (select a.CON_SO_ID,a.CON_CIRCUIT_NO, to_char(a.CON_SO_DATE_RECEIVED, 'mm/dd/yyyy hh:mi:ss AM'),a.CON_SO_STATUS, a.CON_CONTRACTOR,
    REPLACE(c.CON_EX_AREA,'S-','')
from CONTRACTOR_NEW_CON a, CONTRACTOR_WORK_ORDERS b,CONTRACTOR_OSP_DATA c
where a.CON_SO_ID = b.CON_SERO_ID
and a.CON_SO_ID = c.CON_OSP_SERO_ID
and b.CON_SERO_ID = c.CON_OSP_SERO_ID
and a.CON_SO_STATUS IN ('ASSIGNED', 'INPROGRESS','REASSIGNED','DELAYED')
and a.CON_AREA = '$area'
and b.CON_WORO_SERVICE_TYPE = '$sertyp'
and b.CON_WORO_ORDER_TYPE = '$ortyp' 
 and a.CON_SO_DATE_RECEIVED BETWEEN  TO_DATE('$fromdate 12:00:00 AM','mm,dd,yyyy:hh:mi:ss pm') 
 AND TO_DATE('$todate 11:59:59 PM','mm,dd,yyyy:hh:mi:ss pm')
order by  a.CON_AREA,c.CON_EX_AREA)";
}

    $oraconn = OracleConnection();
    $penlist = oci_parse($oraconn, $sql);
    if(oci_execute($penlist))
    {
    return $penlist;
    }
    else
    {
        $err = oci_error($penlist);
        $e =  $err['message'];
        echo "<script type='text/javascript'>alert('$e')</script>";
    }
}


function con_contractor()
{
    $sql = "select distinct  CON_MGT_CONTRACTOR from CONTRACTOR_MGT_USERS where CON_MGT_CONTRACTOR <> 'SLT' order by CON_MGT_CONTRACTOR";
    $oraconn = OracleConnection();
    $contractor= oci_parse($oraconn, $sql);
    if(oci_execute($contractor))
    {
    return $contractor;
    }
    else
    {
        $err = oci_error($contractor);
        $e =  $err['message'];
        echo "<script type='text/javascript'>alert('$e')</script>";
    }
}

function con_contractor_name()
{
    $sql = "select distinct CON_MGT_CONTRACTOR from CONTRACTOR_MGT_USERS order by CON_MGT_CONTRACTOR";
    $oraconn = OracleConnection();
    $contractor= oci_parse($oraconn, $sql);
    if(oci_execute($contractor))
    {
    return $contractor;
    }
    else
    {
        $err = oci_error($contractor);
        $e =  $err['message'];
        echo "<script type='text/javascript'>alert('$e')</script>";
    }
}

function con_contractor_name_1()
{
    $sql = "select distinct CON_MGT_CONTRACTOR from CONTRACTOR_MGT_USERS where CON_MGT_CONTRACTOR  NOT IN ( 'SLT' , 'ADMIN')  order by CON_MGT_CONTRACTOR";
    $oraconn = OracleConnection();
    $contractor= oci_parse($oraconn, $sql);
    if(oci_execute($contractor))
    {
    return $contractor;
    }
    else
    {
        $err = oci_error($contractor);
        $e =  $err['message'];
        echo "<script type='text/javascript'>alert('$e')</script>";
    }
}


function con_user_name($con)
{
    $sql = "select CON_MGT_USER_NAME from CONTRACTOR_MGT_USERS where CON_MGT_CONTRACTOR = :CON_MGT_CONTRACTOR order by CON_MGT_USER_NAME";
    $oraconn = OracleConnection();
    $con_user= oci_parse($oraconn, $sql);
    oci_bind_by_name($con_user, ":CON_MGT_CONTRACTOR", $con);
    if(oci_execute($con_user))
    {
    return $con_user;
    }
    else
    {
        $err = oci_error($con_user);
        $e =  $err['message'];
        echo "<script type='text/javascript'>alert('$e')</script>";
    }
    
}


function con_comp($contractor,$fromdate,$todate,$area,$sertyp,$ortyp)
{
    $sql = "select c.CON_SO_ID,to_char(c.CON_SO_DATE_RECEIVED, 'mm/dd/yyyy hh:mi:ss AM'),c.CON_NEW_CON_TYPE, to_char(c.CON_SO_STATUS_DATE, 'mm/dd/yyyy hh:mi:ss AM'),
         to_char(CON_SO_COM_DATE, 'mm/dd/yyyy hh:mi:ss AM'),c.CON_DLY_DAYS,c.CON_PENALTY,c.CON_CIRCUIT_NO,c.POLE_COUNT
from CONTRACTOR_NEW_CON c, CONTRACTOR_WORK_ORDERS b
where  c.CON_SO_ID = b.CON_SERO_ID
and c.CON_SO_STATUS = 'COMPLETED'
and c.CON_AREA = '$area'
and b.CON_WORO_ORDER_TYPE = '$ortyp' 
and b.CON_WORO_SERVICE_TYPE = '$sertyp'
and c.CON_CONTRACTOR = '$contractor'  
and c.CON_SO_COM_DATE BETWEEN  TO_DATE('$fromdate 12:00:00 AM','mm,dd,yyyy:hh:mi:ss pm') AND TO_DATE('$todate 11:59:59 PM','mm,dd,yyyy:hh:mi:ss pm')";
    $oraconn = OracleConnection();
    $con_comp= oci_parse($oraconn, $sql);
if(oci_execute($con_comp))
{
    return $con_comp;
}
else
    {
        $err = oci_error($con_comp);
        $e =  $err['message'];
        echo "<script type='text/javascript'>alert('$e')</script>";
    }
}


function con_dlyd($contractor,$fromdate,$todate,$area,$sertyp,$ortyp)
{
    
   $sql = "select c.CON_SO_ID,to_char(c.CON_SO_DATE_RECEIVED, 'mm/dd/yyyy hh:mi:ss AM'),c.CON_NEW_CON_TYPE, c.CON_CIRCUIT_NO,
         to_char(c.CON_SO_DLY_DATE, 'mm/dd/yyyy hh:mi:ss AM'),c.CON_DLY_DAYS,c.CON_PENALTY, cm.CON_COMM_TEXT
from CONTRACTOR_NEW_CON c, CONTRACTOR_ORDER_COMMENTS cm, CONTRACTOR_WORK_ORDERS b
where  c.CON_SO_ID = b.CON_SERO_ID
and b.CON_SERO_ID =cm.CON_COMM_SERO_ID
and c.CON_SO_STATUS = 'DELAYED'
and c.CON_AREA = '$area' 
and C.CON_SO_ID = CM.CON_COMM_SERO_ID
and b.CON_WORO_SERVICE_TYPE = '$sertyp'
and b.CON_WORO_ORDER_TYPE = '$ortyp' 
and c.CON_CONTRACTOR = '$contractor'  
and c.CON_SO_DLY_DATE BETWEEN  TO_DATE('$fromdate 12:00:00 AM','mm,dd,yyyy:hh:mi:ss pm') AND TO_DATE('$todate 11:59:59 PM','mm,dd,yyyy:hh:mi:ss pm')";
    $oraconn = OracleConnection();
    $con_dlyd= oci_parse($oraconn, $sql);
    if(oci_execute($con_dlyd))
    {
    return $con_dlyd; 
    }
    else
    {
        $err = oci_error($con_dlyd);
        $e =  $err['message'];
        echo "<script type='text/javascript'>alert('$e')</script>";
    }
}

function con_dlyd2($contractor,$fromdate,$todate,$sertyp,$ortyp)
{
    
   $sql = "select c.CON_SO_ID,to_char(c.CON_SO_DATE_RECEIVED, 'mm/dd/yyyy hh:mi:ss AM'),c.CON_NEW_CON_TYPE, c.CON_CIRCUIT_NO,
         to_char(c.CON_SO_DLY_DATE, 'mm/dd/yyyy hh:mi:ss AM'),c.CON_DLY_DAYS,c.CON_PENALTY, cm.CON_COMM_TEXT,c.CON_AREA
from CONTRACTOR_NEW_CON c, CONTRACTOR_ORDER_COMMENTS cm, CONTRACTOR_WORK_ORDERS b
where  c.CON_SO_ID = b.CON_SERO_ID
and b.CON_SERO_ID =cm.CON_COMM_SERO_ID
and c.CON_SO_STATUS = 'DELAYED'
and C.CON_SO_ID = CM.CON_COMM_SERO_ID
and b.CON_WORO_SERVICE_TYPE = '$sertyp'
and b.CON_WORO_ORDER_TYPE = '$ortyp' 
and c.CON_CONTRACTOR = '$contractor'  
and c.CON_SO_DLY_DATE BETWEEN  TO_DATE('$fromdate 12:00:00 AM','mm,dd,yyyy:hh:mi:ss pm') AND TO_DATE('$todate 11:59:59 PM','mm,dd,yyyy:hh:mi:ss pm')
order by c.CON_AREA";
    $oraconn = OracleConnection();
    $con_dlyd= oci_parse($oraconn, $sql);
    if(oci_execute($con_dlyd))
    {
    return $con_dlyd; 
    }
    else
    {
        $err = oci_error($con_dlyd);
        $e =  $err['message'];
        echo "<script type='text/javascript'>alert('$e')</script>";
    }
}


function con_ret($contractor,$fromdate,$todate,$area,$sertyp,$ortyp)
{
    $sql = "select c.CON_SO_ID,to_char(c.CON_SO_DATE_RECEIVED, 'mm/dd/yyyy hh:mi:ss AM'),c.CON_NEW_CON_TYPE, c.CON_CIRCUIT_NO,
         to_char(c.CON_SO_RTN_DATE, 'mm/dd/yyyy hh:mi:ss AM'),c.CON_DLY_DAYS,c.CON_PENALTY, cm.CON_COMM_TEXT
from CONTRACTOR_NEW_CON c, CONTRACTOR_ORDER_COMMENTS cm, CONTRACTOR_WORK_ORDERS b
where  c.CON_SO_ID = b.CON_SERO_ID
and b.CON_SERO_ID =cm.CON_COMM_SERO_ID
and c.CON_SO_STATUS = 'RETURNED'
and c.CON_AREA = '$area' 
and b.CON_WORO_SERVICE_TYPE = '$sertyp'
and b.CON_WORO_ORDER_TYPE = '$ortyp' 
and C.CON_SO_ID = CM.CON_COMM_SERO_ID
and c.CON_CONTRACTOR = '$contractor'  
and c.CON_SO_RTN_DATE BETWEEN  TO_DATE('$fromdate 12:00:00 AM','mm,dd,yyyy:hh:mi:ss pm') AND TO_DATE('$todate 11:59:59 PM','mm,dd,yyyy:hh:mi:ss pm')";
    $oraconn = OracleConnection();
    $con_retd= oci_parse($oraconn, $sql);
    if(oci_execute($con_retd))
    {
    return $con_retd;
    }
    else
    {
        $err = oci_error($con_retd);
        $e =  $err['message'];
        echo "<script type='text/javascript'>alert('$e')</script>";
    }
}

function con_ret2($contractor,$fromdate,$todate,$sertyp,$ortyp)
{
    $sql = "select c.CON_SO_ID,to_char(c.CON_SO_DATE_RECEIVED, 'mm/dd/yyyy hh:mi:ss AM'),c.CON_NEW_CON_TYPE, c.CON_CIRCUIT_NO,
         to_char(c.CON_SO_RTN_DATE, 'mm/dd/yyyy hh:mi:ss AM'),c.CON_DLY_DAYS,c.CON_PENALTY, cm.CON_COMM_TEXT,c.CON_AREA
from CONTRACTOR_NEW_CON c, CONTRACTOR_ORDER_COMMENTS cm, CONTRACTOR_WORK_ORDERS b
where  c.CON_SO_ID = b.CON_SERO_ID
and b.CON_SERO_ID =cm.CON_COMM_SERO_ID
and c.CON_SO_STATUS = 'RETURNED'
and b.CON_WORO_SERVICE_TYPE = '$sertyp'
and b.CON_WORO_ORDER_TYPE = '$ortyp' 
and C.CON_SO_ID = CM.CON_COMM_SERO_ID
and c.CON_CONTRACTOR = '$contractor'  
and c.CON_SO_RTN_DATE BETWEEN  TO_DATE('$fromdate 12:00:00 AM','mm,dd,yyyy:hh:mi:ss pm') AND TO_DATE('$todate 11:59:59 PM','mm,dd,yyyy:hh:mi:ss pm')
order by c.CON_AREA";
    $oraconn = OracleConnection();
    $con_retd= oci_parse($oraconn, $sql);
    if(oci_execute($con_retd))
    {
    return $con_retd;
    }
    else
    {
        $err = oci_error($con_retd);
        $e =  $err['message'];
        echo "<script type='text/javascript'>alert('$e')</script>";
    }
}


function con_pen($contractor,$fromdate,$todate,$area,$sertyp,$ortyp)
{
    
    if($sertyp == 'E-IPTV COPPER' || $sertyp == 'E-IPTV FTTH')
    {
    $sql = "select distinct * 
from (select a.CON_SO_ID,CON_CIRCUIT_NO, to_char(a.CON_SO_DATE_RECEIVED, 'mm/dd/yyyy hh:mi:ss AM'),  a.CON_SO_STATUS,c.CON_EX_AREA
from CONTRACTOR_NEW_CON a, CONTRACTOR_WORK_ORDERS b,CONTRACTOR_EQ_DATA c
 where a.CON_SO_ID = b.CON_SERO_ID
 and a.CON_SO_ID = c.CON_EQ_SERO_ID
and b.CON_SERO_ID = c.CON_EQ_SERO_ID
 and a.CON_SO_STATUS IN ('ASSIGNED', 'INPROGRESS','REASSIGNED','DELAYED')
and a.CON_AREA = '$area'
and b.CON_WORO_SERVICE_TYPE = '$sertyp'
and b.CON_WORO_ORDER_TYPE = '$ortyp' 
and a.CON_SO_DATE_RECEIVED BETWEEN  TO_DATE('$fromdate 12:00:00 AM','mm,dd,yyyy:hh:mi:ss pm') AND TO_DATE('$todate 11:59:59 PM','mm,dd,yyyy:hh:mi:ss pm')
and a.CON_CONTRACTOR = '$contractor'
order by a.CON_AREA,c.CON_EX_AREA)";
}

if($sertyp == 'AB-CAB' || $sertyp=='AB-FTTH')
    {
    $sql = "select distinct * 
from (select a.CON_SO_ID,CON_CIRCUIT_NO, to_char(a.CON_SO_DATE_RECEIVED, 'mm/dd/yyyy hh:mi:ss AM'),  a.CON_SO_STATUS,REPLACE(c.CON_EX_AREA,'S-','')
from CONTRACTOR_NEW_CON a, CONTRACTOR_WORK_ORDERS b,CONTRACTOR_OSP_DATA c
 where a.CON_SO_ID = b.CON_SERO_ID
 and a.CON_SO_ID = c.CON_OSP_SERO_ID
and b.CON_SERO_ID = c.CON_OSP_SERO_ID
 and a.CON_SO_STATUS IN ('ASSIGNED', 'INPROGRESS','REASSIGNED')
and a.CON_AREA = '$area'
and b.CON_WORO_SERVICE_TYPE = '$sertyp'
and b.CON_WORO_ORDER_TYPE = '$ortyp' 
and a.CON_SO_DATE_RECEIVED BETWEEN  TO_DATE('$fromdate 12:00:00 AM','mm,dd,yyyy:hh:mi:ss pm') AND TO_DATE('$todate 11:59:59 PM','mm,dd,yyyy:hh:mi:ss pm')
and a.CON_CONTRACTOR = '$contractor'
order by a.CON_AREA,c.CON_EX_AREA)";}

    $oraconn = OracleConnection();
    $con_pen= oci_parse($oraconn, $sql);
    if(oci_execute($con_pen))
    {
    return $con_pen; 
    }
    else
    {
        $err = oci_error($con_pen);
        $e =  $err['message'];
        echo "<script type='text/javascript'>alert('$e')</script>";
    }
}

function con_pen2($contractor,$fromdate,$todate,$sertyp,$ortyp)
{

    if($sertyp == 'E-IPTV COPPER' || $sertyp == 'E-IPTV FTTH')
    {
    $sql = "select distinct * 
from (select a.CON_SO_ID,CON_CIRCUIT_NO, to_char(a.CON_SO_DATE_RECEIVED, 'mm/dd/yyyy hh:mi:ss AM'),  a.CON_SO_STATUS,c.CON_EX_AREA
from CONTRACTOR_NEW_CON a, CONTRACTOR_WORK_ORDERS b,CONTRACTOR_EQ_DATA c
 where a.CON_SO_ID = b.CON_SERO_ID
 and a.CON_SO_ID = c.CON_EQ_SERO_ID
and b.CON_SERO_ID = c.CON_EQ_SERO_ID
 and a.CON_SO_STATUS IN ('ASSIGNED', 'INPROGRESS','REASSIGNED','DELAYED')
and b.CON_WORO_SERVICE_TYPE = '$sertyp'
and b.CON_WORO_ORDER_TYPE = '$ortyp' 
and a.CON_SO_DATE_RECEIVED BETWEEN  TO_DATE('$fromdate 12:00:00 AM','mm,dd,yyyy:hh:mi:ss pm') AND TO_DATE('$todate 11:59:59 PM','mm,dd,yyyy:hh:mi:ss pm')
and a.CON_CONTRACTOR = '$contractor'
order by a.CON_AREA,c.CON_EX_AREA)";
}

if($sertyp == 'AB-CAB' || $sertyp=='AB-FTTH')
    {
    $sql = "select distinct * 
from (select a.CON_SO_ID,CON_CIRCUIT_NO, to_char(a.CON_SO_DATE_RECEIVED, 'mm/dd/yyyy hh:mi:ss AM'),  a.CON_SO_STATUS,REPLACE(c.CON_EX_AREA,'S-','')
from CONTRACTOR_NEW_CON a, CONTRACTOR_WORK_ORDERS b,CONTRACTOR_OSP_DATA c
 where a.CON_SO_ID = b.CON_SERO_ID
 and a.CON_SO_ID = c.CON_OSP_SERO_ID
and b.CON_SERO_ID = c.CON_OSP_SERO_ID
 and a.CON_SO_STATUS IN ('ASSIGNED', 'INPROGRESS','REASSIGNED','DELAYED')
and b.CON_WORO_SERVICE_TYPE = '$sertyp'
and b.CON_WORO_ORDER_TYPE = '$ortyp' 
and a.CON_SO_DATE_RECEIVED BETWEEN  TO_DATE('$fromdate 12:00:00 AM','mm,dd,yyyy:hh:mi:ss pm') AND TO_DATE('$todate 11:59:59 PM','mm,dd,yyyy:hh:mi:ss pm')
and a.CON_CONTRACTOR = '$contractor'
order by a.CON_AREA,c.CON_EX_AREA)";}


    $oraconn = OracleConnection();
    $con_pen= oci_parse($oraconn, $sql);
    if(oci_execute($con_pen))
    {
    return $con_pen; 
    }
    else
    {
        $err = oci_error($con_pen);
        $e =  $err['message'];
        echo "<script type='text/javascript'>alert('$e')</script>";
    }
}

function con_pay($contractor,$fromdate,$todate)
{
    $sql = "select count (*) from CONTRACTOR_NEW_CON
where CON_CONTRACTOR = '$contractor'  
and CON_SO_COM_DATE BETWEEN TO_DATE('$fromdate 12:00:00 AM','mm,dd,yyyy:hh:mi:ss pm') AND TO_DATE('$todate 11:59:59 PM','mm,dd,yyyy:hh:mi:ss pm')";
    $oraconn = OracleConnection();
    $con_pay= oci_parse($oraconn, $sql);
    if(oci_execute($con_pay))
    {
    return $con_pay; 
    }
    else
    {
        $err = oci_error($con_pay);
        $e =  $err['message'];
        echo "<script type='text/javascript'>alert('$e')</script>";
    }
}

function con_panelty($contractor,$fromdate,$todate)
{
    $sql = "select sum(CON_PENALTY),sum(count(*))
from CONTRACTOR_NEW_CON
where CON_CONTRACTOR = '{$contractor}'
and CON_SO_STATUS ='COMPLETED'     
and CON_SO_COM_DATE BETWEEN TO_DATE('$fromdate 12:00:00 AM','mm,dd,yyyy:hh:mi:ss pm') AND TO_DATE('$todate 11:59:59 PM','mm,dd,yyyy:hh:mi:ss pm')
and CON_PENALTY  is not null    
group by CON_PENALTY";
    $oraconn = OracleConnection();
    $con_panelty= oci_parse($oraconn, $sql);
    if(oci_execute($con_panelty))
    {
    return $con_panelty; 
    }
    else
    {
        $err = oci_error($con_panelty);
        $e =  $err['message'];
        echo "<script type='text/javascript'>alert('$e')</script>";
    }
}

function payments($contractor,$fromdate,$todate)
{
    $sql = "select CON_CIRCUIT_NO,CON_SO_ID,CON_PENALTY 
        from CONTRACTOR_NEW_CON
        where CON_CONTRACTOR = '{$contractor}'
and CON_SO_STATUS ='COMPLETED'     
and CON_SO_COM_DATE BETWEEN TO_DATE('$fromdate 12:00:00 AM','mm,dd,yyyy:hh:mi:ss pm') AND TO_DATE('$todate 11:59:59 PM','mm,dd,yyyy:hh:mi:ss pm')
        ";
    $oraconn = OracleConnection();
    $payments= oci_parse($oraconn, $sql);
    if(oci_execute($payments))
    {
    return $payments;
    }
    else
    {
        $err = oci_error($payments);
        $e =  $err['message'];
        echo "<script type='text/javascript'>alert('$e')</script>";
    }
}

function tot_pay($fromdate,$todate)
{
    $sql = "select count (*) from CONTRACTOR_NEW_CON
where CON_SO_COM_DATE BETWEEN TO_DATE('$fromdate','mm,dd,yyyy') AND TO_DATE('$todate','mm,dd,yyyy')";
    $oraconn = OracleConnection();
    $tot_pay= oci_parse($oraconn, $sql);
    if(oci_execute($tot_pay))
    {
    return $tot_pay;
    }
    else
    {
        $err = oci_error($tot_pay);
        $e =  $err['message'];
        echo "<script type='text/javascript'>alert('$e')</script>";
    }
}

function tot_panelty($fromdate,$todate)
{
    $sql = "select sum(CON_PENALTY),sum(count(*))
from CONTRACTOR_NEW_CON
where  CON_SO_STATUS ='COMPLETED'
and CON_SO_COM_DATE BETWEEN TO_DATE('$fromdate 12:00:00 AM','mm,dd,yyyy:hh:mi:ss pm') AND TO_DATE('$todate 11:59:59 PM','mm,dd,yyyy:hh:mi:ss pm')
and CON_PENALTY  is not null
group by CON_PENALTY ";
    $oraconn = OracleConnection();
    $tot_panelty= oci_parse($oraconn, $sql);
    if(oci_execute($tot_panelty))
    {
    return $tot_panelty; 
    }
    else
    {
        $err = oci_error($tot_panelty);
        $e =  $err['message'];
        echo "<script type='text/javascript'>alert('$e')</script>";
    }
}

function tot_payments($fromdate,$todate)
{
    $sql = "select CON_CIRCUIT_NO,CON_SO_ID,CON_PENALTY,CON_CONTRACTOR 
        from CONTRACTOR_NEW_CON
        where CON_SO_STATUS ='COMPLETED'     
and CON_SO_COM_DATE BETWEEN TO_DATE('$fromdate 12:00:00 AM','mm,dd,yyyy:hh:mi:ss pm') AND TO_DATE('$todate 11:59:59 PM','mm,dd,yyyy:hh:mi:ss pm')
        ";
    $oraconn = OracleConnection();
    $tot_payments= oci_parse($oraconn, $sql);
    if(oci_execute($tot_payments))
    {
    return $tot_payments;
    }
    else
    {
        $err = oci_error($tot_payments);
        $e =  $err['message'];
        echo "<script type='text/javascript'>alert('$e')</script>";
    }
}

/*function so_con_comp_list($user)
{
    $sql= "select CON_CIRCUIT_NO,to_char(CON_SO_DATE_RECEIVED, 'mm/dd/yyyy hh:mi:ss AM'),
to_char(CON_SO_STATUS_DATE, 'mm/dd/yyyy hh:mi:ss AM'),to_char(CON_SO_COM_DATE, 'mm/dd/yyyy hh:mi:ss AM'),
CON_DLY_DAYS,CON_PENALTY,CON_NEW_CON_TYPE,CON_SO_ID,CON_APPROVE
from CONTRACTOR_NEW_CON
where CON_CONTRACTOR = '$user'
and CON_SO_STATUS ='COMPLETED'";
    
    $oraconn = OracleConnection();
    $con_comp= oci_parse($oraconn, $sql);
    if(oci_execute($con_comp))
    {
    return $con_comp;
    }
    else
    {
        $err = oci_error($con_comp);
        $e =  $err['message'];
        echo "<script type='text/javascript'>alert('$e')</script>";
    }
}*/

//=========================
function so_con_comp_all($fromdate,$todate,$sertyp,$ortyp,$user,$area)
{
if ($ortyp = 'ALL' && $area == 'ALL'){
   $sql= "select distinct a.CON_CIRCUIT_NO,a.CON_SO_ID,to_char(a.CON_SO_DATE_RECEIVED, 'mm/dd/yyyy hh:mi:ss AM'), to_char(a.CON_SO_COM_DATE, 'mm/dd/yyyy hh:mi:ss AM'),
B.CON_WORO_TASK_NAME, a.CON_DLY_DAYS,a.CON_PENALTY,a.CON_APPROVE,b.CON_WORO_ORDER_TYPE 
from CONTRACTOR_NEW_CON a,CONTRACTOR_WORK_ORDERS b
where a.CON_SO_ID = b.CON_SERO_ID
and A.CON_TASK = B.CON_WORO_TASK_NAME
AND a.CON_SO_STATUS ='COMPLETED'
AND b.CON_WORO_SERVICE_TYPE = '$sertyp'
AND a.CON_CONTRACTOR = '$user'
and a.CON_STATUS = '2'
AND a.CON_SO_COM_DATE BETWEEN TO_DATE('$fromdate 12:00:00 AM','mm,dd,yyyy:hh:mi:ss pm') 
AND TO_DATE('$todate 11:59:59 PM','mm,dd,yyyy:hh:mi:ss pm')
and a.CON_APPROVE is null "; 
    
}
else if ($ortyp = 'ALL' && $area != 'ALL'){
   $sql= "select distinct a.CON_CIRCUIT_NO,a.CON_SO_ID,to_char(a.CON_SO_DATE_RECEIVED, 'mm/dd/yyyy hh:mi:ss AM'), to_char(a.CON_SO_COM_DATE, 'mm/dd/yyyy hh:mi:ss AM'),
B.CON_WORO_TASK_NAME, a.CON_DLY_DAYS,a.CON_PENALTY,a.CON_APPROVE,b.CON_WORO_ORDER_TYPE 
from CONTRACTOR_NEW_CON a,CONTRACTOR_WORK_ORDERS b
where a.CON_SO_ID = b.CON_SERO_ID
and A.CON_TASK = B.CON_WORO_TASK_NAME
AND a.CON_SO_STATUS ='COMPLETED'
AND b.CON_WORO_SERVICE_TYPE = '$sertyp'
AND a.CON_CONTRACTOR = '$user'
and a.CON_STATUS = '2'
and a.CON_AREA ='$area'
AND a.CON_SO_COM_DATE BETWEEN TO_DATE('$fromdate 12:00:00 AM','mm,dd,yyyy:hh:mi:ss pm') 
AND TO_DATE('$todate 11:59:59 PM','mm,dd,yyyy:hh:mi:ss pm')
and a.CON_APPROVE is null "; 
    
}
else{
$sql= "select distinct a.CON_CIRCUIT_NO,a.CON_SO_ID,to_char(a.CON_SO_DATE_RECEIVED, 'mm/dd/yyyy hh:mi:ss AM'), to_char(a.CON_SO_COM_DATE, 'mm/dd/yyyy hh:mi:ss AM'),
B.CON_WORO_TASK_NAME, a.CON_DLY_DAYS,a.CON_PENALTY,a.CON_APPROVE,b.CON_WORO_ORDER_TYPE 
from CONTRACTOR_NEW_CON a,CONTRACTOR_WORK_ORDERS b
where a.CON_SO_ID = b.CON_SERO_ID
and A.CON_TASK = B.CON_WORO_TASK_NAME
AND a.CON_SO_STATUS ='COMPLETED'
AND b.CON_WORO_SERVICE_TYPE = '$sertyp'
AND b.CON_WORO_ORDER_TYPE = '$ortyp'
AND a.CON_CONTRACTOR = '$user'
and a.CON_STATUS = '2'
AND a.CON_SO_COM_DATE BETWEEN TO_DATE('$fromdate 12:00:00 AM','mm,dd,yyyy:hh:mi:ss pm') 
AND TO_DATE('$todate 11:59:59 PM','mm,dd,yyyy:hh:mi:ss pm')
and a.CON_APPROVE is null ";
}
    
    $oraconn = OracleConnection();
    $con_comp= oci_parse($oraconn, $sql);
    if(oci_execute($con_comp))
    {
    return $con_comp;
    }
    else
    {
        $err = oci_error($con_comp);
        $e =  $err['message'];
        echo "<script type='text/javascript'>alert('$e')</script>";
    }
}

function so_con_comp_allap($fromdate,$todate,$sertyp,$ortyp,$user,$area)
{
if ($ortyp = 'ALL' && $area == 'ALL'){
   $sql= "select a.CON_CIRCUIT_NO,a.CON_SO_ID,to_char(a.CON_SO_DATE_RECEIVED, 'mm/dd/yyyy hh:mi:ss AM'), to_char(a.CON_SO_COM_DATE, 'mm/dd/yyyy hh:mi:ss AM'),
B.CON_WORO_TASK_NAME, a.CON_DLY_DAYS,a.CON_PENALTY,a.CON_APPROVE,b.CON_WORO_ORDER_TYPE 
from CONTRACTOR_NEW_CON a,CONTRACTOR_WORK_ORDERS b
where a.CON_SO_ID = b.CON_SERO_ID
and A.CON_TASK = B.CON_WORO_TASK_NAME
AND a.CON_SO_STATUS ='COMPLETED'
AND b.CON_WORO_SERVICE_TYPE = '$sertyp'
AND a.CON_CONTRACTOR = '$user'
and a.CON_STATUS = '2'
AND a.CON_SO_COM_DATE BETWEEN TO_DATE('$fromdate 12:00:00 AM','mm,dd,yyyy:hh:mi:ss pm') 
AND TO_DATE('$todate 11:59:59 PM','mm,dd,yyyy:hh:mi:ss pm')
and a.CON_APPROVE ='YES'"; 
    
}
else if ($ortyp = 'ALL' && $area != 'ALL'){
   $sql= "select a.CON_CIRCUIT_NO,a.CON_SO_ID,to_char(a.CON_SO_DATE_RECEIVED, 'mm/dd/yyyy hh:mi:ss AM'), to_char(a.CON_SO_COM_DATE, 'mm/dd/yyyy hh:mi:ss AM'),
B.CON_WORO_TASK_NAME, a.CON_DLY_DAYS,a.CON_PENALTY,a.CON_APPROVE,b.CON_WORO_ORDER_TYPE 
from CONTRACTOR_NEW_CON a,CONTRACTOR_WORK_ORDERS b
where a.CON_SO_ID = b.CON_SERO_ID
and A.CON_TASK = B.CON_WORO_TASK_NAME
AND a.CON_SO_STATUS ='COMPLETED'
AND b.CON_WORO_SERVICE_TYPE = '$sertyp'
AND a.CON_CONTRACTOR = '$user'
and a.CON_STATUS = '2'
and a.CON_AREA ='$area'
AND a.CON_SO_COM_DATE BETWEEN TO_DATE('$fromdate 12:00:00 AM','mm,dd,yyyy:hh:mi:ss pm') 
AND TO_DATE('$todate 11:59:59 PM','mm,dd,yyyy:hh:mi:ss pm')
and a.CON_APPROVE ='YES' "; 
    
}
else{
$sql= "select a.CON_CIRCUIT_NO,a.CON_SO_ID,to_char(a.CON_SO_DATE_RECEIVED, 'mm/dd/yyyy hh:mi:ss AM'), to_char(a.CON_SO_COM_DATE, 'mm/dd/yyyy hh:mi:ss AM'),
B.CON_WORO_TASK_NAME, a.CON_DLY_DAYS,a.CON_PENALTY,a.CON_APPROVE,b.CON_WORO_ORDER_TYPE 
from CONTRACTOR_NEW_CON a,CONTRACTOR_WORK_ORDERS b
where a.CON_SO_ID = b.CON_SERO_ID
and A.CON_TASK = B.CON_WORO_TASK_NAME
AND a.CON_SO_STATUS ='COMPLETED'
AND b.CON_WORO_SERVICE_TYPE = '$sertyp'
AND b.CON_WORO_ORDER_TYPE = '$ortyp'
AND a.CON_CONTRACTOR = '$user'
and a.CON_STATUS = '2'
AND a.CON_SO_COM_DATE BETWEEN TO_DATE('$fromdate 12:00:00 AM','mm,dd,yyyy:hh:mi:ss pm') 
AND TO_DATE('$todate 11:59:59 PM','mm,dd,yyyy:hh:mi:ss pm')
and a.CON_APPROVE ='YES'";
}
    
    $oraconn = OracleConnection();
    $con_comp= oci_parse($oraconn, $sql);
    if(oci_execute($con_comp))
    {
    return $con_comp;
    }
    else
    {
        $err = oci_error($con_comp);
        $e =  $err['message'];
        echo "<script type='text/javascript'>alert('$e')</script>";
    }
}


function so_con_comp_li($fromdate,$todate,$area,$sertyp,$ortyp,$user)
{
$sql= "select a.CON_CIRCUIT_NO,a.CON_SO_ID,to_char(a.CON_SO_DATE_RECEIVED, 'mm/dd/yyyy hh:mi:ss AM'), to_char(a.CON_SO_COM_DATE, 'mm/dd/yyyy hh:mi:ss AM'),
B.CON_WORO_TASK_NAME, a.CON_DLY_DAYS,a.CON_PENALTY,a.CON_APPROVE,b.CON_WORO_ORDER_TYPE 
from CONTRACTOR_NEW_CON a,CONTRACTOR_WORK_ORDERS b
where a.CON_SO_ID = b.CON_SERO_ID
and A.CON_TASK = B.CON_WORO_TASK_NAME
AND a.CON_SO_STATUS ='COMPLETED'
AND b.CON_WORO_SERVICE_TYPE = '$sertyp'
AND b.CON_WORO_ORDER_TYPE = '$ortyp'
AND a.CON_CONTRACTOR = '$user'
and CON_AREA ='$area'
and a.CON_STATUS = '2'
AND a.CON_SO_COM_DATE BETWEEN TO_DATE('$fromdate 12:00:00 AM','mm,dd,yyyy:hh:mi:ss pm') 
AND TO_DATE('$todate 11:59:59 PM','mm,dd,yyyy:hh:mi:ss pm')
and a.CON_APPROVE is null ";
    
    $oraconn = OracleConnection();
    $con_comp= oci_parse($oraconn, $sql);
    if(oci_execute($con_comp))
    {
    return $con_comp;
    }
    else
    {
        $err = oci_error($con_comp);
        $e =  $err['message'];
        echo "<script type='text/javascript'>alert('$e')</script>";
    }
}

function so_con_comp_liap($fromdate,$todate,$area,$sertyp,$ortyp,$user)
{
$sql= "select a.CON_CIRCUIT_NO,a.CON_SO_ID,to_char(a.CON_SO_DATE_RECEIVED, 'mm/dd/yyyy hh:mi:ss AM'), to_char(a.CON_SO_COM_DATE, 'mm/dd/yyyy hh:mi:ss AM'),
B.CON_WORO_TASK_NAME, a.CON_DLY_DAYS,a.CON_PENALTY,a.CON_APPROVE,b.CON_WORO_ORDER_TYPE 
from CONTRACTOR_NEW_CON a,CONTRACTOR_WORK_ORDERS b
where a.CON_SO_ID = b.CON_SERO_ID
and A.CON_TASK = B.CON_WORO_TASK_NAME
AND a.CON_SO_STATUS ='COMPLETED'
AND b.CON_WORO_SERVICE_TYPE = '$sertyp'
AND b.CON_WORO_ORDER_TYPE = '$ortyp'
AND a.CON_CONTRACTOR = '$user'
and CON_AREA ='$area'
and a.CON_STATUS = '2'
AND a.CON_SO_COM_DATE BETWEEN TO_DATE('$fromdate 12:00:00 AM','mm,dd,yyyy:hh:mi:ss pm') 
AND TO_DATE('$todate 11:59:59 PM','mm,dd,yyyy:hh:mi:ss pm')
and a.CON_APPROVE ='YES' ";
    
    $oraconn = OracleConnection();
    $con_comp= oci_parse($oraconn, $sql);
    if(oci_execute($con_comp))
    {
    return $con_comp;
    }
    else
    {
        $err = oci_error($con_comp);
        $e =  $err['message'];
        echo "<script type='text/javascript'>alert('$e')</script>";
    }
}

function so_con_ret_all($fromdate,$todate,$sertyp,$ortyp,$user)
{
  if($ortyp == 'ALL'){
    
    $sql= "select a.CON_CIRCUIT_NO, a.CON_SO_ID, to_char(a.CON_SO_DATE_RECEIVED, 'mm/dd/yyyy hh:mi:ss AM'),
to_char(a.CON_SO_RTN_DATE, 'mm/dd/yyyy hh:mi:ss AM'),c.CON_COMM_TEXT, B.CON_WORO_ORDER_TYPE
from CONTRACTOR_NEW_CON a,CONTRACTOR_WORK_ORDERS b,CONTRACTOR_ORDER_COMMENTS c
where a.CON_SO_ID = b.CON_SERO_ID
and  a.CON_SO_ID = c.CON_COMM_SERO_ID
and b.CON_SERO_ID = c.CON_COMM_SERO_ID
AND a.CON_SO_STATUS ='RETURNED'
AND b.CON_WORO_SERVICE_TYPE = '$sertyp'
AND a.CON_CONTRACTOR = '$user'
AND a.CON_SO_RTN_DATE BETWEEN TO_DATE('$fromdate 12:00:00 AM','mm,dd,yyyy:hh:mi:ss pm') 
AND TO_DATE('$todate 11:59:59 PM','mm,dd,yyyy:hh:mi:ss pm')";
  }  
  else{  
    $sql= "select a.CON_CIRCUIT_NO, a.CON_SO_ID, to_char(a.CON_SO_DATE_RECEIVED, 'mm/dd/yyyy hh:mi:ss AM'),
to_char(a.CON_SO_RTN_DATE, 'mm/dd/yyyy hh:mi:ss AM'),c.CON_COMM_TEXT, B.CON_WORO_ORDER_TYPE
from CONTRACTOR_NEW_CON a,CONTRACTOR_WORK_ORDERS b,CONTRACTOR_ORDER_COMMENTS c
where a.CON_SO_ID = b.CON_SERO_ID
and  a.CON_SO_ID = c.CON_COMM_SERO_ID
and b.CON_SERO_ID = c.CON_COMM_SERO_ID
AND a.CON_SO_STATUS ='RETURNED'
AND b.CON_WORO_SERVICE_TYPE = '$sertyp'
AND b.CON_WORO_ORDER_TYPE = '$ortyp'
AND a.CON_CONTRACTOR = '$user'
AND a.CON_SO_RTN_DATE BETWEEN TO_DATE('$fromdate 12:00:00 AM','mm,dd,yyyy:hh:mi:ss pm') 
AND TO_DATE('$todate 11:59:59 PM','mm,dd,yyyy:hh:mi:ss pm')";
   } 
   
    $oraconn = OracleConnection();
    $con_comp= oci_parse($oraconn, $sql);
    if(oci_execute($con_comp))
    {
    return $con_comp;
    }
    else
    {
        $err = oci_error($con_comp);
        $e =  $err['message'];
        echo "<script type='text/javascript'>alert('$e')</script>";
    }
}


function so_con_ret_li($fromdate,$todate,$area,$sertyp,$ortyp,$user)
{
    $sql= "select a.CON_CIRCUIT_NO, a.CON_SO_ID, to_char(a.CON_SO_DATE_RECEIVED, 'mm/dd/yyyy hh:mi:ss AM'),
to_char(a.CON_SO_RTN_DATE, 'mm/dd/yyyy hh:mi:ss AM'),c.CON_COMM_TEXT, B.CON_WORO_ORDER_TYPE
from CONTRACTOR_NEW_CON a,CONTRACTOR_WORK_ORDERS b,CONTRACTOR_ORDER_COMMENTS c
where a.CON_SO_ID = b.CON_SERO_ID
and  a.CON_SO_ID = c.CON_COMM_SERO_ID
and b.CON_SERO_ID = c.CON_COMM_SERO_ID
AND a.CON_SO_STATUS ='RETURNED'
AND b.CON_WORO_SERVICE_TYPE = '$sertyp'
AND b.CON_WORO_ORDER_TYPE = '$ortyp'
AND a.CON_CONTRACTOR = '$user'
and CON_AREA ='$area'
AND a.CON_SO_RTN_DATE BETWEEN TO_DATE('$fromdate 12:00:00 AM','mm,dd,yyyy:hh:mi:ss pm') 
AND TO_DATE('$todate 11:59:59 PM','mm,dd,yyyy:hh:mi:ss pm')";
    
    $oraconn = OracleConnection();
    $con_comp= oci_parse($oraconn, $sql);
    if(oci_execute($con_comp))
    {
    return $con_comp;
    }
    else
    {
        $err = oci_error($con_comp);
        $e =  $err['message'];
        echo "<script type='text/javascript'>alert('$e')</script>";
    }
}

function so_con_compap_all($fromdate,$todate,$sertyp,$ortyp,$user)
{
 if($ortyp == 'ALL'){
    $sql= "select a.CON_CIRCUIT_NO,a.CON_SO_ID ,to_char(a.CON_SO_DATE_RECEIVED, 'mm/dd/yyyy hh:mi:ss AM'),
to_char(a.CON_SO_STATUS_DATE, 'mm/dd/yyyy hh:mi:ss AM'),to_char(a.CON_SO_COM_DATE, 'mm/dd/yyyy hh:mi:ss AM'),
a.CON_DLY_DAYS,a.CON_PENALTY,a.CON_APPROVE,b.CON_WORO_ORDER_TYPE
from CONTRACTOR_NEW_CON a,CONTRACTOR_WORK_ORDERS b
where a.CON_SO_ID = b.CON_SERO_ID
AND a.CON_SO_STATUS ='COMPLETED'
AND b.CON_WORO_SERVICE_TYPE = '$sertyp'
AND a.CON_CONTRACTOR = '$user'
AND a.CON_SO_COM_DATE BETWEEN TO_DATE('$fromdate 12:00:00 AM','mm,dd,yyyy:hh:mi:ss pm') 
AND TO_DATE('$todate 11:59:59 PM','mm,dd,yyyy:hh:mi:ss pm')
and a.CON_APPROVE = 'YES' ";
    
    
 }else{
    
    $sql= "select a.CON_CIRCUIT_NO,a.CON_SO_ID ,to_char(a.CON_SO_DATE_RECEIVED, 'mm/dd/yyyy hh:mi:ss AM'),
to_char(a.CON_SO_STATUS_DATE, 'mm/dd/yyyy hh:mi:ss AM'),to_char(a.CON_SO_COM_DATE, 'mm/dd/yyyy hh:mi:ss AM'),
a.CON_DLY_DAYS,a.CON_PENALTY,a.CON_APPROVE,b.CON_WORO_ORDER_TYPE
from CONTRACTOR_NEW_CON a,CONTRACTOR_WORK_ORDERS b
where a.CON_SO_ID = b.CON_SERO_ID
AND a.CON_SO_STATUS ='COMPLETED'
AND b.CON_WORO_SERVICE_TYPE = '$sertyp'
AND b.CON_WORO_ORDER_TYPE = '$ortyp'
AND a.CON_CONTRACTOR = '$user'
AND a.CON_SO_COM_DATE BETWEEN TO_DATE('$fromdate 12:00:00 AM','mm,dd,yyyy:hh:mi:ss pm') 
AND TO_DATE('$todate 11:59:59 PM','mm,dd,yyyy:hh:mi:ss pm')
and a.CON_APPROVE = 'YES' ";
    
 }
    
    $oraconn = OracleConnection();
    $con_comp= oci_parse($oraconn, $sql);
    if(oci_execute($con_comp))
    {
    return $con_comp;
    }
    else
    {
        $err = oci_error($con_comp);
        $e =  $err['message'];
        echo "<script type='text/javascript'>alert('$e')</script>";
    }
}


function so_con_compap_li($fromdate,$todate,$area,$sertyp,$ortyp,$user)
{
    $sql= "select a.CON_CIRCUIT_NO,a.CON_SO_ID ,to_char(a.CON_SO_DATE_RECEIVED, 'mm/dd/yyyy hh:mi:ss AM'),
to_char(a.CON_SO_STATUS_DATE, 'mm/dd/yyyy hh:mi:ss AM'),to_char(a.CON_SO_COM_DATE, 'mm/dd/yyyy hh:mi:ss AM'),
a.CON_DLY_DAYS,a.CON_PENALTY,a.CON_APPROVE,b.CON_WORO_ORDER_TYPE
from CONTRACTOR_NEW_CON a,CONTRACTOR_WORK_ORDERS b
where a.CON_SO_ID = b.CON_SERO_ID
AND a.CON_SO_STATUS ='COMPLETED'
AND b.CON_WORO_SERVICE_TYPE = '$sertyp'
AND b.CON_WORO_ORDER_TYPE = '$ortyp'
AND a.CON_CONTRACTOR = '$user'
and CON_AREA ='$area'
AND a.CON_SO_COM_DATE BETWEEN TO_DATE('$fromdate 12:00:00 AM','mm,dd,yyyy:hh:mi:ss pm') 
AND TO_DATE('$todate 11:59:59 PM','mm,dd,yyyy:hh:mi:ss pm')
and a.CON_APPROVE = 'YES' ";
   
    $oraconn = OracleConnection();
    $con_comp= oci_parse($oraconn, $sql);
    if(oci_execute($con_comp))
    {
    return $con_comp;
    }
    else
    {
        $err = oci_error($con_comp);
        $e =  $err['message'];
        echo "<script type='text/javascript'>alert('$e')</script>";
    }
}

function so_con_comp_li6($user,$a,$a1,$a2,$a3,$a4,$a5,$a6)
{
    $sql= "select CON_CIRCUIT_NO,to_char(CON_SO_DATE_RECEIVED, 'mm/dd/yyyy hh:mi:ss AM'),
to_char(CON_SO_STATUS_DATE, 'mm/dd/yyyy hh:mi:ss AM'),to_char(CON_SO_COM_DATE, 'mm/dd/yyyy hh:mi:ss AM'),
CON_DLY_DAYS,CON_PENALTY,CON_NEW_CON_TYPE,CON_SO_ID,CON_APPROVE
from CONTRACTOR_NEW_CON
where CON_CONTRACTOR = '$user'
and CON_AREA IN ('$a','$a1','$a2','$a3','$a4','$a5','$a6')
and CON_SO_STATUS ='COMPLETED'";
    
    $oraconn = OracleConnection();
    $con_comp= oci_parse($oraconn, $sql);
    if(oci_execute($con_comp))
    {
    return $con_comp;
    }
    else
    {
        $err = oci_error($con_comp);
        $e =  $err['message'];
        echo "<script type='text/javascript'>alert('$e')</script>";
    }
}

//========================

function so_con_comp_list2($pstn_no)
{
    $sql= "select distinct a.CON_CIRCUIT_NO,A.CON_TASK ,to_char(a.CON_SO_DATE_RECEIVED, 'mm/dd/yyyy hh:mi:ss AM'),
to_char(a.CON_SO_COM_DATE, 'mm/dd/yyyy hh:mi:ss AM'),CON_NEW_CON_TYPE
from CONTRACTOR_NEW_CON a,CONTRACTOR_WORK_ORDERS b
where a.CON_SO_ID = B.CON_SERO_ID
and b.CON_SERO_ID = '$pstn_no'
and b.CON_STATUS ='COMPLETED'
order by to_char(a.CON_SO_DATE_RECEIVED, 'mm/dd/yyyy hh:mi:ss AM') DESC ";

    $oraconn = OracleConnection();
    $con_comp= oci_parse($oraconn, $sql);
    if(oci_execute($con_comp))
    {
    return $con_comp;
    }
    else
    {
        $err = oci_error($con_comp);
        $e =  $err['message'];
        echo "<script type='text/javascript'>alert('$e')</script>";
    }
}

function so_con_comp_listFTTH($pstn_no)
{
    $sql= "select distinct a.CON_CIRCUIT_NO,A.CON_TASK ,to_char(a.CON_SO_DATE_RECEIVED, 'mm/dd/yyyy hh:mi:ss AM'),
to_char(a.CON_SO_COM_DATE, 'mm/dd/yyyy hh:mi:ss AM'),a.CON_NEW_CON_TYPE
from CONTRACTOR_NEW_CON a,CONTRACTOR_WORK_ORDERS b
where a.CON_SO_ID = B.CON_SERO_ID
and b.CON_SERO_ID = '$pstn_no'
and b.CON_STATUS ='COMPLETED'
and A.CON_TASK like 'INST%'";

    $oraconn = OracleConnection();
    $con_comp= oci_parse($oraconn, $sql);
    if(oci_execute($con_comp))
    {
    return $con_comp;
    }
    else
    {
        $err = oci_error($con_comp);
        $e =  $err['message'];
        echo "<script type='text/javascript'>alert('$e')</script>";
    }
}

function getser($pstn_no)
{
    $sql= "select  CON_WORO_SERVICE_TYPE, CON_WORO_ORDER_TYPE
    from CONTRACTOR_WORK_ORDERS 
    where CON_SERO_ID = '$pstn_no'
    and CON_STATUS ='COMPLETED' order by  CON_DATE_TO_CONTRACTOR DESC";

    $oraconn = OracleConnection();
    $con_comp= oci_parse($oraconn, $sql);
    if(oci_execute($con_comp))
    {
    return $con_comp;
    }
    else
    {
        $err = oci_error($con_comp);
        $e =  $err['message'];
        echo "<script type='text/javascript'>alert('$e')</script>";
    }
}


function so_con_comp_list3($so_id)
{
    $sql= "select CON_CIRCUIT_NO,to_char(CON_SO_DATE_RECEIVED, 'mm/dd/yyyy hh:mi:ss AM'),
to_char(CON_SO_STATUS_DATE, 'mm/dd/yyyy hh:mi:ss AM'),to_char(CON_SO_COM_DATE, 'mm/dd/yyyy hh:mi:ss AM'),
CON_DLY_DAYS,CON_PENALTY,CON_NEW_CON_TYPE,CON_SO_ID,CON_APPROVE
from CONTRACTOR_NEW_CON
where CON_SO_ID = '$so_id'
and CON_SO_STATUS ='COMPLETED'";
    
    $oraconn = OracleConnection();
    $con_comp= oci_parse($oraconn, $sql);
    if(oci_execute($con_comp))
    {
    return $con_comp;
    }
    else
    {
        $err = oci_error($con_comp);
        $e =  $err['message'];
        echo "<script type='text/javascript'>alert('$e')</script>";
    }
}


function nc_meterial($so_id)
{
    $sql ="select * from CONTRACTOR_COPPER_MET where SOID = '{$so_id}'";
    $oraconn = OracleConnection();
    $ncm = oci_parse($oraconn, $sql);
    if(oci_execute($ncm))
    {
    return $ncm;
    }
    else
    {
        $err = oci_error($ncm);
        $e =  $err['message'];
        echo "<script type='text/javascript'>alert('$e')</script>";
    }
}

function nc_meterialpeo($so_id)
{
    $sql ="select * from CONTRACTOR_IPTV_MET where SOID = '{$so_id}'";
    $oraconn = OracleConnection();
    $ncm = oci_parse($oraconn, $sql);
    if(oci_execute($ncm))
    {
    return $ncm;
    }
    else
    {
        $err = oci_error($ncm);
        $e =  $err['message'];
        echo "<script type='text/javascript'>alert('$e')</script>";
    }
}

function nc_meterialfth($so_id)
{
    $sql ="select * from CONTRACTOR_FTTH_MET where SOID = '{$so_id}'";
    $oraconn = OracleConnection();
    $ncm = oci_parse($oraconn, $sql);
    if(oci_execute($ncm))
    {
    return $ncm;
    }
    else
    {
        $err = oci_error($ncm);
        $e =  $err['message'];
        echo "<script type='text/javascript'>alert('$e')</script>";
    }
}

function nc_meterial2($so_id)
{
    $sql ="select count(*) from CONTRACTOR_COPPER_MET where SOID = '{$so_id}'";
    $oraconn = OracleConnection();
    $ncm = oci_parse($oraconn, $sql);
    if(oci_execute($ncm))
    {
    $row= oci_fetch_array($ncm);
    return $row[0];
    }
    else
    {
        $err = oci_error($ncm);
        $e =  $err['message'];
        echo "<script type='text/javascript'>alert('$e')</script>";
    }
}

function nc_meterialfth2($so_id)
{
    $sql ="select count(*) from CONTRACTOR_FTTH_MET where SOID = '{$so_id}'";
    $oraconn = OracleConnection();
    $ncm = oci_parse($oraconn, $sql);
    if(oci_execute($ncm))
    {
    $row= oci_fetch_array($ncm);
    return $row[0];
    }
    else
    {
        $err = oci_error($ncm);
        $e =  $err['message'];
        echo "<script type='text/javascript'>alert('$e')</script>";
    }
}

function nc_meterialpeo2($so_id)
{
    $sql ="select count(*) from CONTRACTOR_IPTV_MET where SOID = '{$so_id}'";
    $oraconn = OracleConnection();
    $ncm = oci_parse($oraconn, $sql);
    if(oci_execute($ncm))
    {
    $row= oci_fetch_array($ncm);
    return $row[0];
    }
    else
    {
        $err = oci_error($ncm);
        $e =  $err['message'];
        echo "<script type='text/javascript'>alert('$e')</script>";
    }
}


function nc_meterialftth($so_id)
{
    $sql ="select * from CONTRACTOR_FTTH_MET where SOID = '{$so_id}'";
    $oraconn = OracleConnection();
    $ncm = oci_parse($oraconn, $sql);
    if(oci_execute($ncm))
    {
    return $ncm;
    }
    else
    {
        $err = oci_error($ncm);
        $e =  $err['message'];
        echo "<script type='text/javascript'>alert('$e')</script>";
    }
}

function nc_meterialftth2($so_id)
{
    $sql ="select count(*) from CONTRACTOR_FTTH_MET where SOID = '{$so_id}'";
    $oraconn = OracleConnection();
    $ncm = oci_parse($oraconn, $sql);
    if(oci_execute($ncm))
    {
    $row= oci_fetch_array($ncm);
    return $row[0];
    }
    else
    {
        $err = oci_error($ncm);
        $e =  $err['message'];
        echo "<script type='text/javascript'>alert('$e')</script>";
    }
}
function nc_meterialiptv($so_id)
{
    $sql ="select * from CONTRACTOR_IPTV_MET where SOID = '{$so_id}'";
    $oraconn = OracleConnection();
    $ncm = oci_parse($oraconn, $sql);
    if(oci_execute($ncm))
    {
    return $ncm;
    }
    else
    {
        $err = oci_error($ncm);
        $e =  $err['message'];
        echo "<script type='text/javascript'>alert('$e')</script>";
    }
}


function completed_met_list($fromdate,$todate,$area,$contractor)
{
   
        $sql = "select sum (cm.POLES_5_6_L),sum(cm.POLES_5_6_H),sum( cm.POLES_5_6_CE),sum(cm.POLES_6_7_CE),sum(cm.POLES_6_7),sum(cm.POLES_7_5),
        sum(cm.POLES_8),sum(cm.POLES_9),sum(cm.POLES_6_7_CON),sum(cm.POLES_7_5_CON),sum(cm.POLES_8_CON),sum(cm.POLES_9_CON),sum(cm.DROP_WIRE),
        sum(cm.INTERNAL_WIRE),sum(cm.EARTH_WIRE),sum(cm.DISCHARGER) ,sum(cm.EARTH_ROD),sum(cm.RETAINERS),sum(cm.L_HOOK),sum(cm.C_HOOK),sum(cm.TELEPHONE),
        sum(cm.PVC_CONDUIT),sum (cm.GUY), sum(cm.POLE_STRUT), sum(cm.SPUN_POLE_5_6), sum(cm.SPUN_POLE_6_7),
        sum(cm.SPUN_POLE_5_6_SLT), sum(cm.SPUN_POLE_6_7_SLT), sum(cm.SPUN_POLE_5_6_CON), sum(cm.SPUN_POLE_6_7_CON), sum(cm.CAT5), sum(cm.IPTV_N), 
        sum(cm.NC_BB_PEO), sum(cm.POLES_5_6_CON), sum(cm.POLES_5_6_CON_CE),  sum(cm.POLES_6_7_CON_CE), sum(cm.POLES_S_CON),
         sum(csm.POLES_5_6_L_SER),sum(csm.POLES_5_6_H_SER),sum( csm.POLES_5_6_CE_SER),sum(csm.POLES_6_7_CE_SER),sum(csm.POLES_6_7_SER),sum(csm.POLES_7_5_SER),
        sum(csm.POLES_8_SER),sum(csm.POLES_9_SER),sum(csm.POLES_6_7_CON_SER),sum(csm.POLES_7_5_CON_SER),sum(csm.POLES_8_CON_SER),sum(csm.POLES_9_CON_SER),sum(csm.DROP_WIRE_SER),
        sum(csm.INTERNAL_WIRE_SER),sum(csm.EARTH_WIRE_SER),sum(csm.DISCHARGER_SER) ,sum(csm.EARTH_ROD_SER),sum(csm.RETAINERS_SER),sum(csm.L_HOOK_SER),sum(csm.C_HOOK_SER),sum(csm.TELEPHONE_SER),
        sum(csm.PVC_CONDUIT_SER),sum (csm.GUY_SER), sum(csm.POLE_STRUT_SER), sum(csm.SPUN_POLE_5_6_SER), sum(csm.SPUN_POLE_6_7_SER),
        sum(csm.SPUN_POLE_5_6_SLT_SER), sum(csm.SPUN_POLE_6_7_SLT_SER), sum(csm.SPUN_POLE_5_6_CON_SER), 
        sum(csm.SPUN_POLE_6_7_CON_SER), sum(csm.CAT5_SER), sum(csm.IPTV_N_SER), sum(csm.NC_BB_PEO_SER), sum(csm.POLES_5_6_CON_SER), sum(csm.POLES_5_6_CON_CE_SER), 
         sum(csm.POLES_6_7_CON_CE_SER), sum(csm.POLES_S_CON_SER),
         sum(cam.POLES_5_6_L_ACC),sum(cam.POLES_5_6_H_ACC),sum( cam.POLES_5_6_CE_ACC),sum(cam.POLES_6_7_CE_ACC),sum(cam.POLES_6_7_ACC),sum(cam.POLES_7_5_ACC),
        sum(cam.POLES_8_ACC),sum(cam.POLES_9_ACC),sum(cam.POLES_6_7_CON_ACC),sum(cam.POLES_7_5_CON_ACC),sum(cam.POLES_8_CON_ACC),sum(cam.POLES_9_CON_ACC),sum(cam.DROP_WIRE_ACC),
        sum(cam.INTERNAL_WIRE_ACC),sum(cam.EARTH_WIRE_ACC),sum(cam.DISCHARGER_ACC) ,sum(cam.EARTH_ROD_ACC),sum(cam.RETAINERS_ACC),sum(cam.L_HOOK_ACC),sum(cam.C_HOOK_ACC),sum(cam.TELEPHONE_ACC),
        sum(cam.PVC_CONDUIT_ACC),sum (cam.GUY_ACC), sum(cam.POLE_STRUT_ACC), sum(cam.SPUN_POLE_5_6_ACC), sum(cam.SPUN_POLE_6_7_ACC),
        sum(cam.SPUN_POLE_5_6_SLT_ACC), sum(cam.SPUN_POLE_6_7_SLT_ACC), sum(cam.SPUN_POLE_5_6_CON_ACC), 
        sum(cam.SPUN_POLE_6_7_CON_ACC), sum(cam.CAT5_ACC), sum(cam.IPTV_N_ACC), sum(cam.NC_BB_PEO_ACC), sum(cam.POLES_5_6_CON_ACC), sum(cam.POLES_5_6_CON_CE_ACC), 
         sum(cam.POLES_6_7_CON_CE_ACC), sum(cam.POLES_S_CON_ACC)
        from CONTRACTOR_MERETIAL cm,CONTRACTOR_MERETIAL_SER csm,CONTRACTOR_MERETIAL_ACC cam
        where cm.MET_SO_ID IN (select CON_SO_ID from CONTRACTOR_NEW_CON
            where CON_SO_STATUS = 'COMPLETED'
            and CON_APPROVE = 'YES'
            and CON_CONTRACTOR = '$contractor'
            and CON_AREA = '$area'
			and INV_FLAG is null
			and INV_NO is null
            and CON_SO_COM_DATE BETWEEN TO_DATE('$fromdate 12:00:00 AM','mm,dd,yyyy:hh:mi:ss pm') AND TO_DATE('$todate 11:59:59 PM','mm,dd,yyyy:hh:mi:ss pm'))
        AND CM.MET_SO_ID =  CSM.MET_SO_ID
        AND CSM.MET_SO_ID = CAM.MET_SO_ID";

       
        $oraconn = OracleConnection();
    $met_list = oci_parse($oraconn, $sql);
    if(oci_execute($met_list))
    {
        return $met_list;
    }
    else
    {
        $err = oci_error($met_list);
        $e =  $err['message'];
        echo "<script type='text/javascript'>alert('$e')</script>";
    } 
    
    
}


function delaypenelty ($inv)
{
    $sql ="select  sum(a.CON_PENALTY)
            from CONTRACTOR_INV_PASSCHK a
            where INV_NO = '$inv'";
            	
    $oraconn = OracleConnection();
    $penelty = oci_parse($oraconn, $sql);
    if(oci_execute($penelty))
    {
     $row = oci_fetch_array($penelty);   
    return $row[0];
    }
    else
    {
        $err = oci_error($penelty);
        $e =  $err['message'];
        echo "<script type='text/javascript'>alert('$e')</script>";
    }
    
}

function delaypenelty2 ($inv)
{
    $sql ="select  sum(a.CON_PENALTY)
            from CONTRACTOR_INV_PASSED a
            where INV_NO = '$inv'";
            	
    $oraconn = OracleConnection();
    $penelty = oci_parse($oraconn, $sql);
    if(oci_execute($penelty))
    {
     $row = oci_fetch_array($penelty);   
    return $row[0];
    }
    else
    {
        $err = oci_error($penelty);
        $e =  $err['message'];
        echo "<script type='text/javascript'>alert('$e')</script>";
    }
    
}

function peneltyNC ($inv)
{
    $sql ="select  sum(a.CON_PENALTY)
            from CONTRACTOR_NEW_CON a,CONTRACTOR_WORK_ORDERS b
            where A.CON_SO_ID =B.CON_SERO_ID
            and INV_NO = '$inv'
            and B.CON_WORO_ORDER_TYPE = 'CREATE'";
	
    $oraconn = OracleConnection();
    $penelty = oci_parse($oraconn, $sql);
    if(oci_execute($penelty))
    {
     $row = oci_fetch_array($penelty);   
    return $row[0];
    }
    else
    {
        $err = oci_error($penelty);
        $e =  $err['message'];
        echo "<script type='text/javascript'>alert('$e')</script>";
    }
    
}

function peneltyRM ($inv)
{
    $sql ="select  sum(a.CON_PENALTY)
            from CONTRACTOR_NEW_CON a,CONTRACTOR_WORK_ORDERS b
            where A.CON_SO_ID =B.CON_SERO_ID
            and INV_NO = '$inv'
            and B.CON_WORO_ORDER_TYPE IN ('MODIFY-LOCATION','MODIFY-LOC SAMEDP','CREATE-OR')";
	
    $oraconn = OracleConnection();
    $penelty = oci_parse($oraconn, $sql);
    if(oci_execute($penelty))
    {
     $row = oci_fetch_array($penelty);   
    return $row[0];
    }
    else
    {
        $err = oci_error($penelty);
        $e =  $err['message'];
        echo "<script type='text/javascript'>alert('$e')</script>";
    }
    
}

function peneltyRC ($inv)
{
    $sql ="select  sum(a.CON_PENALTY)
            from CONTRACTOR_NEW_CON a,CONTRACTOR_WORK_ORDERS b
            where A.CON_SO_ID =B.CON_SERO_ID
            and INV_NO = '$inv'
            and B.CON_WORO_ORDER_TYPE = 'CREATE-RECON'";
	
    $oraconn = OracleConnection();
    $penelty = oci_parse($oraconn, $sql);
    if(oci_execute($penelty))
    {
     $row = oci_fetch_array($penelty);   
    return $row[0];
    }
    else
    {
        $err = oci_error($penelty);
        $e =  $err['message'];
        echo "<script type='text/javascript'>alert('$e')</script>";
    }
    
}


function con_area()
{
    $sql ="select RTOM  from CONTRACTOR_REGION order by RTOM";
    $oraconn = OracleConnection();
    $area = oci_parse($oraconn, $sql);
    if(oci_execute($area))
    {
    return $area;
    }
    else
    {
        $err = oci_error($area);
        $e =  $err['message'];
        echo "<script type='text/javascript'>alert('$e')</script>";
    }
}


function opmc_area()
{
    $sql ="select distinct OPMC  from CONTRACTOR_REGION order by OPMC";
    $oraconn = OracleConnection();
    $area = oci_parse($oraconn, $sql);
    if(oci_execute($area))
    {
    return $area;
    }
    else
    {
        $err = oci_error($area);
        $e =  $err['message'];
        echo "<script type='text/javascript'>alert('$e')</script>";
    }
}

function invoice($area)
{
   $sql="select C_HOOK,L_HOOK,RETAINERS,DROP_WIRE,INTERNAL_WIRE,DISCHARGER,EARTH_ROD,EARTH_WIRE,
	TELEPHONE,CAT5,IPTV_N, NC_BB_PEO,PVC_CONDUIT,POLE_STRUT,SPUN_POLE_5_6_SLT, SPUN_POLE_6_7_SLT,
	SPUN_POLE_5_6, SPUN_POLE_6_7,SPUN_POLE_5_6_CON,SPUN_POLE_6_7_CON,POLES_5_6_L,POLES_5_6_H,
	POLES_5_6_CE,POLES_5_6_CON, POLES_5_6_CON_CE,POLES_6_7,POLES_6_7_CON,POLES_6_7_CE,POLES_6_7_CON_CE,
	POLES_7_5,POLES_7_5_CON,POLES_8,POLES_8_CON,POLES_9_CON,POLES_9,POLES_S_CON,GUY
	from CONTRACTOR_UNIT_RATE
	where IND = (select IND from CONTRACTOR_REGION where RTOM = '$area')";
    
    $oraconn = OracleConnection();
    $invoice = oci_parse($oraconn, $sql);
    if(oci_execute($invoice))
    {
    return $invoice;
    }
    else
    {
        $err = oci_error($invoice);
        $e =  $err['message'];
        echo "<script type='text/javascript'>alert('$e')</script>";
    }
}

function invoice1($area)
{
    $sql="select C_HOOK_SER,L_HOOK_SER,RETAINERS_SER,DROP_WIRE_SER,INTERNAL_WIRE_SER,DISCHARGER_SER,EARTH_ROD_SER,EARTH_WIRE_SER,
	TELEPHONE_SER,CAT5_SER,IPTV_N_SER, NC_BB_PEO_SER,PVC_CONDUIT_SER,POLE_STRUT_SER,SPUN_POLE_5_6_SLT_SER, SPUN_POLE_6_7_SLT_SER,
	SPUN_POLE_5_6_SER, SPUN_POLE_6_7_SER,SPUN_POLE_5_6_CON_SER,SPUN_POLE_6_7_CON_SER,POLES_5_6_L_SER,POLES_5_6_H_SER,
	POLES_5_6_CE_SER,POLES_5_6_CON_SER, POLES_5_6_CON_CE_SER,POLES_6_7_SER,POLES_6_7_CON_SER,POLES_6_7_CE_SER,POLES_6_7_CON_CE_SER,
	POLES_7_5_SER,POLES_7_5_CON_SER,POLES_8_SER,POLES_8_CON_SER,POLES_9_CON_SER,POLES_9_SER,POLES_S_CON_SER,GUY
	from CONTRACTOR_UNIT_RATE
	where IND = (select IND from CONTRACTOR_REGION where RTOM = '$area') ";
    
    $oraconn = OracleConnection();
    $invoice = oci_parse($oraconn, $sql);
    if(oci_execute($invoice))
    {
    return $invoice;
    }
    else
    {
        $err = oci_error($invoice);
        $e =  $err['message'];
        echo "<script type='text/javascript'>alert('$e')</script>";
    }
}

function invoice2($area)
{
    $sql="select C_HOOK_ACC,L_HOOK_ACC,RETAINERS_ACC,DROP_WIRE_ACC,INTERNAL_WIRE_ACC,DISCHARGER_ACC,EARTH_ROD_ACC,EARTH_WIRE_ACC,
	TELEPHONE_ACC,CAT5_ACC,IPTV_N_ACC, NC_BB_PEO_ACC,PVC_CONDUIT_ACC,POLE_STRUT_ACC,SPUN_POLE_5_6_SLT_ACC, SPUN_POLE_6_7_SLT_ACC,
	SPUN_POLE_5_6_ACC, SPUN_POLE_6_7_ACC,SPUN_POLE_5_6_CON_ACC,SPUN_POLE_6_7_CON_ACC,POLES_5_6_L_ACC,POLES_5_6_H_ACC,
	POLES_5_6_CE_ACC,POLES_5_6_CON_ACC, POLES_5_6_CON_CE_ACC,POLES_6_7_ACC,POLES_6_7_CON_ACC,POLES_6_7_CE_ACC,POLES_6_7_CON_CE_ACC,
	POLES_7_5_ACC,POLES_7_5_CON_ACC,POLES_8_ACC,POLES_8_CON_ACC,POLES_9_CON_ACC,POLES_9_ACC,POLES_S_CON_ACC,GUY
	from CONTRACTOR_UNIT_RATE
	where IND = (select IND from CONTRACTOR_REGION where RTOM = '$area') ";
    
    $oraconn = OracleConnection();
    $invoice = oci_parse($oraconn, $sql);
    if(oci_execute($invoice))
    {
    return $invoice;
    }
    else
    {
        $err = oci_error($invoice);
        $e =  $err['message'];
        echo "<script type='text/javascript'>alert('$e')</script>";
    }
}

function subscriber_met($invno)
{
    $sql = "select nc.CON_CIRCUIT_NO,(cm.C_HOOK+csm.C_HOOK_SER+cam.C_HOOK_ACC),(cm.L_HOOK+csm.L_HOOK_SER+cam.L_HOOK_ACC),
            (cm.RETAINERS+csm.RETAINERS_SER+cam.RETAINERS_ACC),(cm.DROP_WIRE+csm.DROP_WIRE_SER+cam.DROP_WIRE_ACC),
            (cm.INTERNAL_WIRE+csm.INTERNAL_WIRE_SER+cam.INTERNAL_WIRE_ACC),(cm.DISCHARGER+csm.DISCHARGER_SER+cam.DISCHARGER_ACC),
            (cm.EARTH_ROD+csm.EARTH_ROD_SER+cam.EARTH_ROD_ACC),(cm.EARTH_WIRE+csm.EARTH_WIRE_SER+cam.EARTH_WIRE_ACC),
            (cm.TELEPHONE+csm.TELEPHONE_SER+cam.TELEPHONE_ACC),(cm.CAT5+csm.CAT5_SER+cam.CAT5_ACC),(cm.IPTV_N+csm.IPTV_N_SER+cam.IPTV_N_ACC),
            (cm.NC_BB_PEO+csm.NC_BB_PEO_SER+cam.NC_BB_PEO_ACC),(cm.PVC_CONDUIT+csm.PVC_CONDUIT_SER+cam.PVC_CONDUIT_ACC),
            (cm.POLE_STRUT+csm.POLE_STRUT_SER+cam.POLE_STRUT_ACC),(cm.SPUN_POLE_5_6_SLT+csm.SPUN_POLE_5_6_SLT_SER+cam.SPUN_POLE_5_6_SLT_ACC),
            (cm.SPUN_POLE_6_7_SLT+csm.SPUN_POLE_6_7_SLT_SER+cam.SPUN_POLE_6_7_SLT_ACC),(cm.SPUN_POLE_5_6+csm.SPUN_POLE_5_6_SER+cam.SPUN_POLE_5_6_ACC),
            (cm.SPUN_POLE_6_7+csm.SPUN_POLE_6_7_SER+cam.SPUN_POLE_6_7_ACC),(cm.SPUN_POLE_5_6_CON+csm.SPUN_POLE_5_6_CON_SER+cam.SPUN_POLE_5_6_CON_ACC),
            (cm.SPUN_POLE_6_7_CON+csm.SPUN_POLE_6_7_CON_SER+cam.SPUN_POLE_6_7_CON_ACC),(cm.POLES_5_6_L+csm.POLES_5_6_L_SER+cam.POLES_5_6_L_ACC),
            (cm.POLES_5_6_H+csm.POLES_5_6_H_SER+cam.POLES_5_6_H_ACC),(cm.POLES_5_6_CE+csm.POLES_5_6_CE_SER+cam.POLES_5_6_CE_ACC),
            (cm.POLES_5_6_CON+csm.POLES_5_6_CON_SER+cam.POLES_5_6_CON_ACC),(cm.POLES_5_6_CON_CE+csm.POLES_5_6_CON_CE_SER+cam.POLES_5_6_CON_CE_ACC),
            (cm.POLES_6_7+csm.POLES_6_7_SER+cam.POLES_6_7_ACC),(cm.POLES_6_7_CON+csm.POLES_6_7_CON_SER+cam.POLES_6_7_CON_ACC),
            (cm.POLES_6_7_CE+csm.POLES_6_7_CE_SER+cam.POLES_6_7_CE_ACC),(cm.POLES_6_7_CON_CE+csm.POLES_6_7_CON_CE_SER+cam.POLES_6_7_CON_CE_ACC),
            (cm.POLES_7_5+csm.POLES_7_5_SER+cam.POLES_7_5_ACC),(cm.POLES_7_5_CON+csm.POLES_7_5_CON_SER+cam.POLES_7_5_CON_ACC),
            (cm.POLES_8+csm.POLES_8_SER+cam.POLES_8_ACC),(cm.POLES_8_CON+csm.POLES_8_CON_SER+cam.POLES_8_CON_ACC),
            (cm.POLES_9+csm.POLES_9_SER+csm.POLES_9_CON_SER),(cm.POLES_9_CON+cam.POLES_9_ACC+cam.POLES_9_CON_ACC),
            (cm.POLES_S_CON+csm.POLES_S_CON_SER+cam.POLES_S_CON_ACC),(cm.GUY+csm.GUY_SER+cam.GUY_ACC)    
            from CONTRACTOR_MERETIAL cm,CONTRACTOR_MERETIAL_SER csm,CONTRACTOR_MERETIAL_ACC cam,CONTRACTOR_NEW_CON nc
            where cm.MET_SO_ID IN (select CON_SO_ID from CONTRACTOR_NEW_CON
            where INV_NO = '$invno')
            AND cm.MET_SO_ID = nc.CON_SO_ID
            AND CM.MET_SO_ID =  CSM.MET_SO_ID
            AND CSM.MET_SO_ID = CAM.MET_SO_ID";
    
    
    $oraconn = OracleConnection();
    $sub_met = oci_parse($oraconn, $sql);
    if(oci_execute($sub_met))
    {
    return $sub_met;
    }
    else
    {
        $err = oci_error($sub_met);
        $e =  $err['message'];
        echo "<script type='text/javascript'>alert('$e')</script>";
    }
    
}


function slt_region()
{
    $sql = "select distinct REGION from CONTRACTOR_REGION";
    
    $oraconn = OracleConnection();
    $region = oci_parse($oraconn, $sql);
    if(oci_execute($region))
    {
    return $region;
    }
    else
    {
        $err = oci_error($region);
        $e =  $err['message'];
        echo "<script type='text/javascript'>alert('$e')</script>";
    }
}

function slt_unit_rate($region)
{
    $sql = "select * from CONTRACTOR_UNIT_RATE
where IND = (select distinct IND from CONTRACTOR_REGION where REGION = '$region')";
   
    $oraconn = OracleConnection();
    $unit_rate = oci_parse($oraconn, $sql);
    if(oci_execute($unit_rate))
    {  
    return $unit_rate;
    }
    else
    {
        $err = oci_error($unit_rate);
        $e =  $err['message'];
        echo "<script type='text/javascript'>alert('$e')</script>";
    }
}

function con_name()
{
    $sql = "select DISTINCT CON_MGT_CONTRACTOR from CONTRACTOR_MGT_USERS where CON_MGT_CONTRACTOR <> 'SLT' order by CON_MGT_CONTRACTOR";
   
    $oraconn = OracleConnection();
    $con_name = oci_parse($oraconn, $sql);
    if(oci_execute($con_name))
    { 
    return $con_name;
    }
    else
    {
        $err = oci_error($con_name);
        $e =  $err['message'];
        echo "<script type='text/javascript'>alert('$e')</script>";
    }
}

function thvalue_update($con_no,$con_name,$fromdate,$todate,$amount)
{
    $sql = "INSERT INTO CONTRACTOR_VALUE_LIMIT (CONTRACT_NO,CONTRACTOR_NAME,TRH_PRD_FROM,TRH_PRD_TO,THR_VALUE_LIMIT)
         VALUES ('$con_no','$con_name',TO_DATE('$fromdate','mm,dd,yyyy'),TO_DATE('$todate','mm,dd,yyyy'),'$amount')";
  
  
    $oraconn = OracleConnection();
    $thvalue = oci_parse($oraconn, $sql);
if(oci_execute($thvalue))
{
    return $thvalue;
}
    else
    {
        $err = oci_error($thvalue);
        $e =  $err['message'];
        echo "<script type='text/javascript'>alert('$e')</script>";
    }
}

function th_con_amount()
{
    $sql = "select * from CONTRACTOR_VALUE_LIMIT";
   
    $oraconn = OracleConnection();
    $thr_amt = oci_parse($oraconn, $sql);
    if(oci_execute($thr_amt))
    {   
    return $thr_amt;
    }
    else
    {
        $err = oci_error($thr_amt);
        $e =  $err['message'];
        echo "<script type='text/javascript'>alert('$e')</script>";
    }
}

function invoice_update($con_name,$con_no,$project_no,$tender_no,$invoice_no,$area,$sertype,$user)
{
    $sql = "INSERT INTO CONTRACTOR_INVOICE_DATA (CONTRACTOR_NAME,CONTRACT,PROJECT_NO,TENDER_NO,INVOICE_NO,INVOICE_DATE,SER_TYPE,RTOM_AREA,INVOICE_REF,CON_USER,REPORT )
        VALUES ('$con_name','$con_no','$project_no','$tender_no','$invoice_no',sysdate,'$sertype','$area','SLT-REF/$invoice_no','$user','0')";

    $oraconn = OracleConnection();
    $in_update = oci_parse($oraconn, $sql);
    if(oci_execute($in_update))
    {      
    return 0;
    }
    else
    {
        $err = oci_error($in_update);
        $e =  $err['message'];
        echo "<script type='text/javascript'>alert('$e')</script>";
    }
    
}


function con_invoice_update($tot1,$invoice_no)
{
    $sql = "UPDATE CONTRACTOR_INVOICE SET AMOUNT = '$tot1' WHERE INVOICE_NO = '$invoice_no'";

    $oraconn = OracleConnection();
    $inv_amt = oci_parse($oraconn, $sql);
    if(oci_execute($inv_amt))
    {

    $sql1 = "select CURRENT_AMOUNT from CONTRACTOR_VALUE_LIMIT
where CONTRACTOR_USER_NAME = (select CONTRACTOR_NAME from CONTRACTOR_INVOICE WHERE INVOICE_NO = '$invoice_no')";
    $oraconn = OracleConnection();
    $inv_th = oci_parse($oraconn, $sql1);
    if(oci_execute($inv_th))
    {
    $cu_val = oci_fetch_array($inv_th) ;
    $cu_val_up = $cu_val[0] + $tot1;
    
    $sql2 = "update CONTRACTOR_VALUE_LIMIT set CURRENT_AMOUNT = '$cu_val_up'
where CONTRACTOR_USER_NAME = (select CONTRACTOR_NAME from CONTRACTOR_INVOICE WHERE INVOICE_NO = '$invoice_no')";
    
    $oraconn = OracleConnection();
    $cur_amt = oci_parse($oraconn, $sql2);
    if(oci_execute($cur_amt))
    {
        return 0;
    }
    else
    {
        $err = oci_error($cur_amt);
        $e =  $err['message'];
        echo "<script type='text/javascript'>alert('$e')</script>";
    }
    }
    else
    {
        $err = oci_error($inv_th);
        $e =  $err['message'];
        echo "<script type='text/javascript'>alert('$e')</script>";
    }
    }
    else
    {
        $err = oci_error($inv_amt);
        $e =  $err['message'];
        echo "<script type='text/javascript'>alert('$e')</script>";
    }
}

function inv_list()
{
    $sql = "SELECT * FROM CONTRACTOR_INVOICE ";

    $oraconn = OracleConnection();
    $inv_list = oci_parse($oraconn, $sql);
    if(oci_execute($inv_list))
    {
    return $inv_list;
    }
    else
    {
        $err = oci_error($inv_list);
        $e =  $err['message'];
        echo "<script type='text/javascript'>alert('$e')</script>";
    }
}

function co_inv_met_list($user)
{
    $sql = "SELECT * FROM CONTRACTOR_INVOICE where CONTRACTOR_NAME = '$user' and INVOICE_NO like '%-A' ";

    $oraconn = OracleConnection();
    $inv_list = oci_parse($oraconn, $sql);
    if(oci_execute($inv_list))
    {    
    return $inv_list;
    }
    else
    {
        $err = oci_error($inv_list);
        $e =  $err['message'];
        echo "<script type='text/javascript'>alert('$e')</script>";
    }
}

function co_inv_list($user,$area)
{
    $sql = "SELECT * FROM CONTRACTOR_INVOICE_DATA where CONTRACTOR_NAME = '$user'";

    $oraconn = OracleConnection();
    $inv_list = oci_parse($oraconn, $sql);
    if(oci_execute($inv_list))
    {    
    return $inv_list;
    }
    else
    {
        $err = oci_error($inv_list);
        $e =  $err['message'];
        echo "<script type='text/javascript'>alert('$e')</script>";
    }
}

function co_inv_listslt()
{
    $sql = "SELECT * FROM CONTRACTOR_INVOICE_DATA ";

    $oraconn = OracleConnection();
    $inv_list = oci_parse($oraconn, $sql);
    if(oci_execute($inv_list))
    {    
    return $inv_list;
    }
    else
    {
        $err = oci_error($inv_list);
        $e =  $err['message'];
        echo "<script type='text/javascript'>alert('$e')</script>";
    }
}

function slt_inv_list($user,$area,$from,$to)
{
    $sql = "SELECT * FROM CONTRACTOR_INVOICE_DATA where CONTRACTOR_NAME = '$user' and RTOM_AREA ='$area'
            and INVOICE_DATE BETWEEN TO_DATE('$from 12:00:00 AM','mm,dd,yyyy:hh:mi:ss pm') 
            AND TO_DATE('$to 11:59:59 PM','mm,dd,yyyy:hh:mi:ss pm')";

    $oraconn = OracleConnection();
    $inv_list = oci_parse($oraconn, $sql);
    if(oci_execute($inv_list))
    {    
    return $inv_list;
    }
    else
    {
        $err = oci_error($inv_list);
        $e =  $err['message'];
        echo "<script type='text/javascript'>alert('$e')</script>";
    }
}

function invoice_detail($invoice_id)
{
    $sql = "SELECT CONTRACT,PROJECT_NO,TENDER_NO, to_char(INVOICE_DATE, 'mm/dd/yyyy'), to_char(INVOICE_PRD_FROM, 'mm/dd/yyyy'),
 to_char(INVOICE_PRD_TO, 'mm/dd/yyyy'), RTOM_AREA,CONTRACTOR_NAME
 FROM CONTRACTOR_INVOICE where INVOICE_NO = '$invoice_id'";

    $oraconn = OracleConnection();
    $inv_detail = oci_parse($oraconn, $sql);
    if(oci_execute($inv_detail))
    {   
    return $inv_detail;
    }
    else
    {
        $err = oci_error($inv_detail);
        $e =  $err['message'];
        echo "<script type='text/javascript'>alert('$e')</script>";
    }
}

function slt_inv_met_list()
{
    $sql = "SELECT * FROM CONTRACTOR_INVOICE where INVOICE_NO like '%-A' order by INVOICE_DATE";

    $oraconn = OracleConnection();
    $inv_list = oci_parse($oraconn, $sql);
    if(oci_execute($inv_list))
    {
    return $inv_list;
    }
    else
    {
        $err = oci_error($inv_list);
        $e =  $err['message'];
        echo "<script type='text/javascript'>alert('$e')</script>";
    }
}

function qua_chk_completed($inv)
{
    
    $sql = "select CON_SO_ID ,CON_CIRCUIT_NO,CON_NEW_CON_TYPE,CON_CONTRACTOR,to_char(CON_SO_COM_DATE, 'mm/dd/yyyy hh:mi:ss AM')
            from CONTRACTOR_NEW_CON
            where  INV_NO = '$inv'
	    and CON_STATUS = '2'";

    $oraconn = OracleConnection();
    $completed = oci_parse($oraconn, $sql);
    if(oci_execute($completed))
    {
    return $completed;
    }
    else
    {
        $err = oci_error($completed);
        $e =  $err['message'];
        echo "<script type='text/javascript'>alert('$e')</script>";
    }
    
}

function quality_mark()
{
    
    $sql = " select distinct nc.CON_SO_ID ,nc.CON_CIRCUIT_NO,nc.CON_CONTRACTOR,
            OD.CON_OSP_DP_NAME,OD.CON_OSP_DP_LOOP ,SA.CON_ADDE_STREETNUMBER,SA.CON_ADDE_STRN_NAMEANDTYPE,
            SA.CON_ADDE_SUBURB,SA.CON_ADDE_CITY, WO.CON_CUS_NAME,NC.CON_AREA,NC.INV_NO
            from CONTRACTOR_NEW_CON nc, CONTRACTOR_OSP_DATA od  ,CONTRACTOR_SERVICE_ADDRESS sa,CONTRACTOR_WORK_ORDERS wo
            where NC.CON_SO_ID = OD.CON_OSP_SERO_ID
            and nc.CON_APPROVE = 'YES'
            and nc.CON_STATUS = '4'
            and nc.CON_SO_STATUS = 'COMPLETED'
            and NC.CON_SO_ID = SA.CON_ADDE_SERO_ID
            and NC.CON_SO_ID = WO.CON_SERO_ID";

    $oraconn = OracleConnection();
    $qty_mark = oci_parse($oraconn, $sql);
    if(oci_execute($qty_mark))
    {
    return $qty_mark;
    }
    else
    {
        $err = oci_error($qty_mark);
        $e =  $err['message'];
        echo "<script type='text/javascript'>alert('$e')</script>";
    }
    
}


function quantity_mark()
{
    
    $sql = " select distinct nc.CON_SO_ID ,nc.CON_CIRCUIT_NO,nc.CON_CONTRACTOR,
            OD.CON_OSP_DP_NAME,OD.CON_OSP_DP_LOOP ,SA.CON_ADDE_STREETNUMBER,SA.CON_ADDE_STRN_NAMEANDTYPE,
            SA.CON_ADDE_SUBURB,SA.CON_ADDE_CITY, WO.CON_CUS_NAME,NC.CON_AREA,NC.INV_NO
            from CONTRACTOR_NEW_CON nc, CONTRACTOR_OSP_DATA od  ,CONTRACTOR_SERVICE_ADDRESS sa,CONTRACTOR_WORK_ORDERS wo
            where NC.CON_SO_ID = OD.CON_OSP_SERO_ID
            and nc.CON_APPROVE = 'YES'
            and nc.CON_STATUS = '3'
            and nc.CON_SO_STATUS = 'COMPLETED'
            and NC.CON_SO_ID = SA.CON_ADDE_SERO_ID
            and NC.CON_SO_ID = WO.CON_SERO_ID";

    $oraconn = OracleConnection();
    $qty_mark = oci_parse($oraconn, $sql);
    if(oci_execute($qty_mark))
    {
    return $qty_mark;
    }
    else
    {
        $err = oci_error($qty_mark);
        $e =  $err['message'];
        echo "<script type='text/javascript'>alert('$e')</script>";
    }
    
}

function quantity_remark($a)
{
    
    $sql = " select distinct nc.CON_SO_ID ,nc.CON_CIRCUIT_NO,nc.CON_CONTRACTOR,
            OD.CON_OSP_DP_NAME,OD.CON_OSP_DP_LOOP ,SA.CON_ADDE_STREETNUMBER,SA.CON_ADDE_STRN_NAMEANDTYPE,
            SA.CON_ADDE_SUBURB,SA.CON_ADDE_CITY, WO.CON_CUS_NAME,NC.CON_AREA,NC.INV_NO
            from CONTRACTOR_NEW_CON nc, CONTRACTOR_OSP_DATA od  ,CONTRACTOR_SERVICE_ADDRESS sa,CONTRACTOR_WORK_ORDERS wo
            where NC.CON_SO_ID = OD.CON_OSP_SERO_ID
            and nc.CON_APPROVE = 'YES'
            and nc.CON_STATUS = '6'
            and nc.CON_SO_STATUS = 'COMPLETED'
            and NC.CON_SO_ID = SA.CON_ADDE_SERO_ID
            and NC.CON_SO_ID = WO.CON_SERO_ID
            and nc.INV_NO ='$a'" ;

    $oraconn = OracleConnection();
    $qty_mark = oci_parse($oraconn, $sql);
    if(oci_execute($qty_mark))
    {
    return $qty_mark;
    }
    else
    {
        $err = oci_error($qty_mark);
        $e =  $err['message'];
        echo "<script type='text/javascript'>alert('$e')</script>";
    }
    
}

function quality_remark($a)
{
    
    $sql = " select distinct nc.CON_SO_ID ,nc.CON_CIRCUIT_NO,nc.CON_CONTRACTOR,
            OD.CON_OSP_DP_NAME,OD.CON_OSP_DP_LOOP ,SA.CON_ADDE_STREETNUMBER,SA.CON_ADDE_STRN_NAMEANDTYPE,
            SA.CON_ADDE_SUBURB,SA.CON_ADDE_CITY, WO.CON_CUS_NAME,NC.CON_AREA,NC.INV_NO
            from CONTRACTOR_NEW_CON nc, CONTRACTOR_OSP_DATA od  ,CONTRACTOR_SERVICE_ADDRESS sa,CONTRACTOR_WORK_ORDERS wo
            where NC.CON_SO_ID = OD.CON_OSP_SERO_ID
            and nc.CON_APPROVE = 'YES'
            and nc.CON_STATUS = '7'
            and nc.CON_SO_STATUS = 'COMPLETED'
            and NC.CON_SO_ID = SA.CON_ADDE_SERO_ID
            and NC.CON_SO_ID = WO.CON_SERO_ID
            and nc.INV_NO ='$a'" ;

    $oraconn = OracleConnection();
    $qty_mark = oci_parse($oraconn, $sql);
    if(oci_execute($qty_mark))
    {
    return $qty_mark;
    }
    else
    {
        $err = oci_error($qty_mark);
        $e =  $err['message'];
        echo "<script type='text/javascript'>alert('$e')</script>";
    }
    
}

function qty_inspec($so_id,$pstn,$DROP_WIRE_A,$DROP_WIRE_B,$DROP_WIRE_C,$DROP_WIRE_D,$DROP_WIRE_E,
                            $ERACTING_POLES_A,$ERACTING_POLES_B,$ERACTING_POLES_C,$ERACTING_POLES_D,
                            $DISCHARGER_A,$DISCHARGER_B,$DISCHARGER_C,$DISCHARGER_D,$INTERNAL_WIRE_A,
                            $INTERNAL_WIRE_B,$EARTHING_A,$EARTHING_B,$EARTHING_C,$EARTHING_D,
                            $ROSSETE_A,$ROSSETE_B,$comment,$quality_pass)
{
    
    $sql = "insert into CONTRACTOR_QUALITY (SO_ID,PSTN_NO,DROP_WIRE_A,DROP_WIRE_B,DROP_WIRE_C,DROP_WIRE_D,DROP_WIRE_E,ERACTING_POLES_A,
  ERACTING_POLES_B,ERACTING_POLES_C,DISCHARGER_A,DISCHARGER_B,DISCHARGER_C,DISCHARGER_D,INTERNAL_WIRE_A,INTERNAL_WIRE_B,
  EARTHING_A,EARTHING_B,EARTHING_C,EARTHING_D,ROSSETE_A,ROSSETE_B,INSPECTION_COMMENT,QUALITY_PASS)
  values ('$so_id','$pstn','$DROP_WIRE_A','$DROP_WIRE_B','$DROP_WIRE_C','$DROP_WIRE_D','$DROP_WIRE_E',
                            '$ERACTING_POLES_A','$ERACTING_POLES_B','$ERACTING_POLES_C',
                            '$DISCHARGER_A','$DISCHARGER_B','$DISCHARGER_C','$DISCHARGER_D','$INTERNAL_WIRE_A',
                            '$INTERNAL_WIRE_B','$EARTHING_A','$EARTHING_B','$EARTHING_C','$EARTHING_D',
                            '$ROSSETE_A','$ROSSETE_B','$comment','$quality_pass')";

    
    $oraconn = OracleConnection();
    $qty_ins = oci_parse($oraconn, $sql);
    if(oci_execute($qty_ins))
    {
	$sql ="update CONTRACTOR_NEW_CON set CON_STATUS = '5', QC_DATE = sysdate where CON_CIRCUIT_NO='$pstn'
            and CON_SO_ID = '$so_id'";
    $oraconn = OracleConnection();
    $up_st = oci_parse($oraconn, $sql);
    if(oci_execute($up_st))
    {
        return $qty_ins;
    }
    else
    {
        $err = oci_error($up_st);
        $e =  $err['message'];
        echo "<script type='text/javascript'>alert('$e')</script>";
    }    
        
    

    }
    else
    {
        $err = oci_error($qty_ins);
        $e =  $err['message'];
        echo "<script type='text/javascript'>alert('$e')</script>";
    }
    
}

function met_count($so_id)

{
    
    $sql = "select(cm.POLES_5_6_L+csm.POLES_5_6_L_SER+cam.POLES_5_6_L_ACC),(cm.POLES_5_6_H+csm.POLES_5_6_H_SER+cam.POLES_5_6_H_ACC),
(cm.POLES_5_6_CE+csm.POLES_5_6_CE_SER+cam.POLES_5_6_CE_ACC),(cm.POLES_6_7_CE+csm.POLES_6_7_CE_SER+cam.POLES_6_7_CE_ACC),
(cm.POLES_6_7+csm.POLES_6_7_SER+cam.POLES_6_7_ACC),(cm.POLES_7_5+csm.POLES_7_5_SER+cam.POLES_7_5_ACC),
(cm.POLES_8+csm.POLES_8_SER+cam.POLES_8_ACC),(cm.POLES_9+csm.POLES_9_SER+cam.POLES_9_ACC),
(cm.POLES_6_7_CON+csm.POLES_6_7_CON_SER+cam.POLES_6_7_CON_ACC),(cm.POLES_7_5_CON+csm.POLES_7_5_CON_SER+cam.POLES_7_5_CON_ACC),
(cm.POLES_8_CON+csm.POLES_8_CON_SER+cam.POLES_8_CON_ACC),(cm.POLES_9_CON+csm.POLES_9_CON_SER+cam.POLES_9_CON_ACC),
(cm.DROP_WIRE+csm.DROP_WIRE_SER+cam.DROP_WIRE_ACC),(cm.INTERNAL_WIRE+csm.INTERNAL_WIRE_SER+cam.INTERNAL_WIRE_ACC),
(cm.EARTH_WIRE+csm.EARTH_WIRE_SER+cam.EARTH_WIRE_ACC),(cm.DISCHARGER+csm.DISCHARGER_SER+cam.DISCHARGER_ACC),
(cm.EARTH_ROD+csm.EARTH_ROD_SER+cam.EARTH_ROD_ACC),(cm.RETAINERS+csm.RETAINERS_SER+cam.RETAINERS_ACC),
(cm.L_HOOK+csm.L_HOOK_SER+cam.L_HOOK_ACC),(cm.C_HOOK+csm.C_HOOK_SER+cam.C_HOOK_ACC),
(cm.TELEPHONE+csm.TELEPHONE_SER+cam.TELEPHONE_ACC), (PVC_CONDUIT+csm.PVC_CONDUIT_SER+cam.PVC_CONDUIT_ACC),
(GUY+csm.GUY_SER+cam.GUY_ACC), (POLE_STRUT+csm.POLE_STRUT_SER+cam.POLE_STRUT_ACC),
(cm.SPUN_POLE_5_6+csm.SPUN_POLE_5_6_SER+cam.SPUN_POLE_5_6_ACC),(cm.SPUN_POLE_6_7+csm.SPUN_POLE_6_7_SER+cam.SPUN_POLE_6_7_ACC),
(cm.SPUN_POLE_5_6_SLT+csm.SPUN_POLE_5_6_SLT_SER+cam.SPUN_POLE_5_6_SLT_ACC),(cm.SPUN_POLE_6_7_SLT+csm.SPUN_POLE_6_7_SLT_SER+cam.SPUN_POLE_6_7_SLT_ACC),
(cm.SPUN_POLE_5_6_CON+csm.SPUN_POLE_5_6_CON_SER+cam.SPUN_POLE_5_6_CON_ACC),(cm.SPUN_POLE_6_7_CON+csm.SPUN_POLE_6_7_CON_SER+cam.SPUN_POLE_6_7_CON_ACC),
(cm.CAT5+csm.CAT5_SER+cam.CAT5_ACC),(cm.IPTV_N+csm.IPTV_N_SER+cam.IPTV_N_ACC),
(cm.NC_BB_PEO+csm.NC_BB_PEO_SER+cam.NC_BB_PEO_ACC),(cm.POLES_5_6_CON+csm.POLES_5_6_CON_SER+cam.POLES_5_6_CON_ACC),
(cm.POLES_5_6_CON_CE+csm.POLES_5_6_CON_CE_SER+cam.POLES_5_6_CON_CE_ACC),(cm.POLES_6_7_CON_CE+csm.POLES_6_7_CON_CE_SER+cam.POLES_6_7_CON_CE_ACC),
(cm.POLES_S_CON+csm.POLES_S_CON_SER+cam.POLES_S_CON_ACC)
 from CONTRACTOR_MERETIAL cm,CONTRACTOR_MERETIAL_SER csm,CONTRACTOR_MERETIAL_ACC cam
        where cm.MET_SO_ID = '$so_id'
        AND CM.MET_SO_ID =  CSM.MET_SO_ID
        AND CSM.MET_SO_ID = CAM.MET_SO_ID";
        
    $oraconn = OracleConnection();
    $met_count = oci_parse($oraconn, $sql);
    if(oci_execute($met_count))
    {
    return $met_count;
    }
    else
    {
        $err = oci_error($met_count);
        $e =  $err['message'];
        echo "<script type='text/javascript'>alert('$e')</script>";
    }
    
}

function met_quality($so_id)
{
    
    $sql = "select cm.POLES_5_6_L,cm.POLES_5_6_H,cm.POLES_5_6_CE,cm.POLES_6_7_CE,cm.POLES_6_7,cm.POLES_7_5,
cm.POLES_8,cm.POLES_9,cm.POLES_6_7_CON,cm.POLES_7_5_CON,cm.POLES_8_CON,cm.POLES_9_CON,
cm.DROP_WIRE,cm.INTERNAL_WIRE,cm.EARTH_WIRE,cm.DISCHARGER,cm.EARTH_ROD,cm.RETAINERS,cm.L_HOOK,cm.C_HOOK,
cm.TELEPHONE, PVC_CONDUIT,cm.GUY, POLE_STRUT,cm.SPUN_POLE_5_6,cm.SPUN_POLE_6_7,cm.SPUN_POLE_5_6_SLT,cm.SPUN_POLE_6_7_SLT,
cm.SPUN_POLE_5_6_CON,cm.SPUN_POLE_6_7_CON,cm.CAT5,cm.IPTV_N,cm.NC_BB_PEO,cm.POLES_5_6_CON,cm.POLES_5_6_CON_CE,
cm.POLES_6_7_CON_CE,cm.POLES_S_CON
from CONTRACTOR_QTY_CHK_MERETIAL cm
where cm.MET_SO_ID = '$so_id'";
        
    $oraconn = OracleConnection();
    $met_count = oci_parse($oraconn, $sql);
    if(oci_execute($met_count))
    {
    return $met_count;
    }
    else
    {
        $err = oci_error($met_count);
        $e =  $err['message'];
        echo "<script type='text/javascript'>alert('$e')</script>";
    }
    
}

function met_devi($so_id)
{
    
    $sql = "select cm.POLES_5_6_L,cm.POLES_5_6_H,cm.POLES_5_6_CE,cm.POLES_6_7_CE,cm.POLES_6_7,cm.POLES_7_5,
cm.POLES_8,cm.POLES_9,cm.POLES_6_7_CON,cm.POLES_7_5_CON,cm.POLES_8_CON,cm.POLES_9_CON,
cm.DROP_WIRE,cm.INTERNAL_WIRE,cm.EARTH_WIRE,cm.DISCHARGER,cm.EARTH_ROD,cm.RETAINERS,cm.L_HOOK,cm.C_HOOK,
cm.TELEPHONE, PVC_CONDUIT,cm.GUY, POLE_STRUT,cm.SPUN_POLE_5_6,cm.SPUN_POLE_6_7,cm.SPUN_POLE_5_6_SLT,cm.SPUN_POLE_6_7_SLT,
cm.SPUN_POLE_5_6_CON,cm.SPUN_POLE_6_7_CON,cm.CAT5,cm.IPTV_N,cm.NC_BB_PEO,cm.POLES_5_6_CON,cm.POLES_5_6_CON_CE,
cm.POLES_6_7_CON_CE,cm.POLES_S_CON
from CONTRACTOR_QTY_CHK_MERETIALDIV cm
where cm.MET_SO_ID = '$so_id'";
        
    $oraconn = OracleConnection();
    $met_count = oci_parse($oraconn, $sql);
    if(oci_execute($met_count))
    {
    return $met_count;
    }
    else
    {
        $err = oci_error($met_count);
        $e =  $err['message'];
        echo "<script type='text/javascript'>alert('$e')</script>";
    }
    
}
function chk_quantity($so_id,$pstn,$pole5_6L,$pole5_6H,$pole5_6_CE,$pole6_7_CE,$pole6_7,$pole7_5,$pole8,$pole9,
        $pole6_7_con,$pole7_5_con,$pole8_con,$pole9_con,
        $drop_wire,$earth_wire,$internal_wire,$discharger,$earth_rod,$retainers,$L_hook,$C_hook,$telephone,
        $pvc_conduit,$guy,$pole_strut,$spun_pole_5_6,$spun_pole_6_7,$spun_pole_5_6_slt,$spun_pole_6_7_slt,
        $spun_pole_5_6_con,$spun_pole_6_7_con,$cat5,$iptvn,$ncbb,$pole_5_6_con,$pole_5_6_ce,$pole_6_7_ce,$pole_con)
{
    
    $sql = "insert into CONTRACTOR_QTY_CHK_MERETIAL (MET_SO_ID,PSTN_NO,POLES_5_6_L, POLES_5_6_H ,POLES_5_6_CE,POLES_6_7_CE, POLES_6_7, POLES_7_5, 
        POLES_8, POLES_9, DROP_WIRE, INTERNAL_WIRE, EARTH_WIRE ,POLES_6_7_CON, POLES_7_5_CON,POLES_8_CON, POLES_9_CON,
		DISCHARGER, EARTH_ROD, RETAINERS, L_HOOK, C_HOOK, TELEPHONE, PVC_CONDUIT,GUY,POLE_STRUT,SPUN_POLE_5_6,SPUN_POLE_6_7,
                SPUN_POLE_5_6_SLT,SPUN_POLE_6_7_SLT,SPUN_POLE_5_6_CON,SPUN_POLE_6_7_CON,CAT5,IPTV_N,NC_BB_PEO,
                POLES_5_6_CON,POLES_5_6_CON_CE,POLES_6_7_CON_CE,POLES_S_CON)
values ('{$so_id}','{$pstn}','{$pole5_6L}','{$pole5_6H}','$pole5_6_CE','$pole6_7_CE','{$pole6_7}','{$pole7_5}','{$pole8}',
		'{$pole9}','{$drop_wire}','{$internal_wire}','{$earth_wire}','{$pole6_7_con}','{$pole7_5_con}','{$pole8_con}',
		'{$pole9_con}','{$discharger}','{$earth_rod}','{$retainers}','{$L_hook}','{$C_hook}','{$telephone}','{$pvc_conduit}','{$guy}',
                    '{$pole_strut}','{$spun_pole_5_6}','{$spun_pole_6_7}','{$spun_pole_5_6_slt}','{$spun_pole_6_7_slt}','{$spun_pole_5_6_con}',
                    '{$spun_pole_6_7_con}','{$cat5}','{$iptvn}','{$ncbb}','{$pole_5_6_con}','{$pole_5_6_ce}','{$pole_6_7_ce}','{$pole_con}')";
    
    $oraconn = OracleConnection();
    $qty = oci_parse($oraconn, $sql);
    if(oci_execute($qty))
    {
    $sql ="update CONTRACTOR_NEW_CON set CON_STATUS = '4' where CON_CIRCUIT_NO='$pstn'
            and CON_SO_ID = '$so_id'";
    $oraconn = OracleConnection();
    $up_st = oci_parse($oraconn, $sql);
    if(oci_execute($up_st))
    {
        return $qty;
    }
    else
    {
        $err = oci_error($up_st);
        $e =  $err['message'];
        echo "<script type='text/javascript'>alert('$e')</script>";
    }
    }
    else
    {
        $err = oci_error($qty);
        $e =  $err['message'];
        echo "<script type='text/javascript'>alert('$e')</script>";
    }  
}
function chk_requantity($inv,$pstn,$pole5_6L,$pole5_6H,$pole5_6_CE,$pole6_7_CE,$pole6_7,$pole7_5,$pole8,$pole9,
        $pole6_7_con,$pole7_5_con,$pole8_con,$pole9_con,
        $drop_wire,$earth_wire,$internal_wire,$discharger,$earth_rod,$retainers,$L_hook,$C_hook,$telephone,
        $pvc_conduit,$guy,$pole_strut,$spun_pole_5_6,$spun_pole_6_7,$spun_pole_5_6_slt,$spun_pole_6_7_slt,
        $spun_pole_5_6_con,$spun_pole_6_7_con,$cat5,$iptvn,$ncbb,$pole_5_6_con,$pole_5_6_ce,$pole_6_7_ce,$pole_con)
{
    
    $sql = "insert into CONTRACTOR_QTY_RECHK_MERETIAL (INV_NO,PSTN_NO,POLES_5_6_L, POLES_5_6_H ,POLES_5_6_CE,POLES_6_7_CE, POLES_6_7, POLES_7_5, 
        POLES_8, POLES_9, DROP_WIRE, INTERNAL_WIRE, EARTH_WIRE ,POLES_6_7_CON, POLES_7_5_CON,POLES_8_CON, POLES_9_CON,
		DISCHARGER, EARTH_ROD, RETAINERS, L_HOOK, C_HOOK, TELEPHONE, PVC_CONDUIT,GUY,POLE_STRUT,SPUN_POLE_5_6,SPUN_POLE_6_7,
                SPUN_POLE_5_6_SLT,SPUN_POLE_6_7_SLT,SPUN_POLE_5_6_CON,SPUN_POLE_6_7_CON,CAT5,IPTV_N,NC_BB_PEO,
                POLES_5_6_CON,POLES_5_6_CON_CE,POLES_6_7_CON_CE,POLES_S_CON)
values ('{$inv}','{$pstn}','{$pole5_6L}','{$pole5_6H}','$pole5_6_CE','$pole6_7_CE','{$pole6_7}','{$pole7_5}','{$pole8}',
		'{$pole9}','{$drop_wire}','{$internal_wire}','{$earth_wire}','{$pole6_7_con}','{$pole7_5_con}','{$pole8_con}',
		'{$pole9_con}','{$discharger}','{$earth_rod}','{$retainers}','{$L_hook}','{$C_hook}','{$telephone}','{$pvc_conduit}','{$guy}',
                    '{$pole_strut}','{$spun_pole_5_6}','{$spun_pole_6_7}','{$spun_pole_5_6_slt}','{$spun_pole_6_7_slt}','{$spun_pole_5_6_con}',
                    '{$spun_pole_6_7_con}','{$cat5}','{$iptvn}','{$ncbb}','{$pole_5_6_con}','{$pole_5_6_ce}','{$pole_6_7_ce}','{$pole_con}')";
    
    $oraconn = OracleConnection();
    $qty = oci_parse($oraconn, $sql);
    if(oci_execute($qty))
    {
    $sql ="update CONTRACTOR_NEW_CON set CON_STATUS = '7' where CON_CIRCUIT_NO='$pstn'
            and INV_NO = '$inv'";
    $oraconn = OracleConnection();
    $up_st = oci_parse($oraconn, $sql);
    if(oci_execute($up_st))
    {
        return $qty;
    }
    else
    {
        $err = oci_error($up_st);
        $e =  $err['message'];
        echo "<script type='text/javascript'>alert('$e')</script>";
    }      
    }
    else
    {
        $err = oci_error($qty);
        $e =  $err['message'];
        echo "<script type='text/javascript'>alert('$e')</script>";
    }  
}

function chk_quantity_devia($so_id,$pstn,$pole5_6L,$pole5_6H,$pole5_6_CE,$pole6_7_CE,$pole6_7,$pole7_5,$pole8,$pole9,
        $pole6_7_con,$pole7_5_con,$pole8_con,$pole9_con,
        $drop_wire,$earth_wire,$internal_wire,$discharger,$earth_rod,$retainers,$L_hook,$C_hook,$telephone,
        $pvc_conduit,$guy,$pole_strut,$spun_pole_5_6,$spun_pole_6_7,$spun_pole_5_6_slt,$spun_pole_6_7_slt,
        $spun_pole_5_6_con,$spun_pole_6_7_con,$cat5,$iptvn,$ncbb,$pole_5_6_con,$pole_5_6_ce,$pole_6_7_ce,$pole_con)
{
    
    $sql = "insert into CONTRACTOR_QTY_CHK_MERETIALDIV (MET_SO_ID,PSTN_NO,POLES_5_6_L, POLES_5_6_H ,POLES_5_6_CE,POLES_6_7_CE, POLES_6_7, POLES_7_5, 
        POLES_8, POLES_9, DROP_WIRE, INTERNAL_WIRE, EARTH_WIRE ,POLES_6_7_CON, POLES_7_5_CON,POLES_8_CON, POLES_9_CON,
		DISCHARGER, EARTH_ROD, RETAINERS, L_HOOK, C_HOOK, TELEPHONE, PVC_CONDUIT,GUY,POLE_STRUT,SPUN_POLE_5_6,SPUN_POLE_6_7,
                SPUN_POLE_5_6_SLT,SPUN_POLE_6_7_SLT,SPUN_POLE_5_6_CON,SPUN_POLE_6_7_CON,CAT5,IPTV_N,NC_BB_PEO,
                POLES_5_6_CON,POLES_5_6_CON_CE,POLES_6_7_CON_CE,POLES_S_CON)
values ('{$so_id}','{$pstn}','{$pole5_6L}','{$pole5_6H}','$pole5_6_CE','$pole6_7_CE','{$pole6_7}','{$pole7_5}','{$pole8}',
		'{$pole9}','{$drop_wire}','{$internal_wire}','{$earth_wire}','{$pole6_7_con}','{$pole7_5_con}','{$pole8_con}',
		'{$pole9_con}','{$discharger}','{$earth_rod}','{$retainers}','{$L_hook}','{$C_hook}','{$telephone}','{$pvc_conduit}','{$guy}',
                    '{$pole_strut}','{$spun_pole_5_6}','{$spun_pole_6_7}','{$spun_pole_5_6_slt}','{$spun_pole_6_7_slt}','{$spun_pole_5_6_con}',
                    '{$spun_pole_6_7_con}','{$cat5}','{$iptvn}','{$ncbb}','{$pole_5_6_con}','{$pole_5_6_ce}','{$pole_6_7_ce}','{$pole_con}')";
    
    $oraconn = OracleConnection();
    $qty = oci_parse($oraconn, $sql);

    if(oci_execute($qty))
    {
        return $qty;
    }
    else
    {
        $err = oci_error($qty);
        $e =  $err['message'];
        echo "<script type='text/javascript'>alert('$e')</script>";
    }    
    


}

function chk_requantity_devia($inv,$pstn,$pole5_6L,$pole5_6H,$pole5_6_CE,$pole6_7_CE,$pole6_7,$pole7_5,$pole8,$pole9,
        $pole6_7_con,$pole7_5_con,$pole8_con,$pole9_con,
        $drop_wire,$earth_wire,$internal_wire,$discharger,$earth_rod,$retainers,$L_hook,$C_hook,$telephone,
        $pvc_conduit,$guy,$pole_strut,$spun_pole_5_6,$spun_pole_6_7,$spun_pole_5_6_slt,$spun_pole_6_7_slt,
        $spun_pole_5_6_con,$spun_pole_6_7_con,$cat5,$iptvn,$ncbb,$pole_5_6_con,$pole_5_6_ce,$pole_6_7_ce,$pole_con)
{
    
    $sql = "insert into CONTRACTOR_QTY_RECHK_METDIV (INV_NO,PSTN_NO,POLES_5_6_L, POLES_5_6_H ,POLES_5_6_CE,POLES_6_7_CE, POLES_6_7, POLES_7_5, 
        POLES_8, POLES_9, DROP_WIRE, INTERNAL_WIRE, EARTH_WIRE ,POLES_6_7_CON, POLES_7_5_CON,POLES_8_CON, POLES_9_CON,
		DISCHARGER, EARTH_ROD, RETAINERS, L_HOOK, C_HOOK, TELEPHONE, PVC_CONDUIT,GUY,POLE_STRUT,SPUN_POLE_5_6,SPUN_POLE_6_7,
                SPUN_POLE_5_6_SLT,SPUN_POLE_6_7_SLT,SPUN_POLE_5_6_CON,SPUN_POLE_6_7_CON,CAT5,IPTV_N,NC_BB_PEO,
                POLES_5_6_CON,POLES_5_6_CON_CE,POLES_6_7_CON_CE,POLES_S_CON)
values ('{$inv}','{$pstn}','{$pole5_6L}','{$pole5_6H}','$pole5_6_CE','$pole6_7_CE','{$pole6_7}','{$pole7_5}','{$pole8}',
		'{$pole9}','{$drop_wire}','{$internal_wire}','{$earth_wire}','{$pole6_7_con}','{$pole7_5_con}','{$pole8_con}',
		'{$pole9_con}','{$discharger}','{$earth_rod}','{$retainers}','{$L_hook}','{$C_hook}','{$telephone}','{$pvc_conduit}','{$guy}',
                    '{$pole_strut}','{$spun_pole_5_6}','{$spun_pole_6_7}','{$spun_pole_5_6_slt}','{$spun_pole_6_7_slt}','{$spun_pole_5_6_con}',
                    '{$spun_pole_6_7_con}','{$cat5}','{$iptvn}','{$ncbb}','{$pole_5_6_con}','{$pole_5_6_ce}','{$pole_6_7_ce}','{$pole_con}')";
    
    $oraconn = OracleConnection();
    $qty = oci_parse($oraconn, $sql);

    if(oci_execute($qty))
    {
        return $qty;
    }
    else
    {
        $err = oci_error($qty);
        $e =  $err['message'];
        echo "<script type='text/javascript'>alert('$e')</script>";
    }    
    


}

function opmc($opmc)
{
    $sql = "select RTOM from CONTRACTOR_REGION where OPMC = '$opmc'";
    $oraconn = OracleConnection();
    $rtom = oci_parse($oraconn, $sql);
    if(oci_execute($rtom))
    {
        return $rtom;
    }
    else
    {
        $err = oci_error($rtom);
        $e =  $err['message'];
        echo "<script type='text/javascript'>alert('$e')</script>";
    }
}


function quality_report($rtom,$contractor,$from,$to)
{
    $HEADER = "PSTN NO,SERVICE ORDER NO,Sag of Drop wire,Drop wire connectors  < 500m-No Joints, >500m-one Joint with Drop wire Connector,C-hook firmly fixed,Drop wire staple (1 m),Construction of poles with struts,Buried depth of the pole,Permissible Drop wire Clearance,fixed firmly reachable height,Wires to be without spirals,Drop wire and internal wire connected in clockwise and insulation is not excessively removed,Protectors are available,Internal wire to be stapled at every 40 cm point,PVC protection used where ever necessary to protect the wire,Earth rod to be completely buried 50cm below the ground,earth wire to be connected to the discharger straight without allowing any excess spiral wire,Earth Resistance <100 ohm,Earth wire should be joint free,For Screw type terminal wire to be terminated in clock wise manner,Rosette to be fixed on the wall vertically and at a minimum of 0.5m above the ground level and no excess internal wire,Inspection Comments\n";
    $empty_result_set = true;
    $sql = "select CON_CIRCUIT_NO,CON_SO_ID from CONTRACTOR_NEW_CON 
            where CON_CONTRACTOR like '$contractor%'
            and CON_AREA like '$rtom%'    
            and CON_SO_COM_DATE BETWEEN TO_DATE('$from 12:00:00 AM','mm,dd,yyyy:hh:mi:ss pm') AND TO_DATE('$to 11:59:59 PM','mm,dd,yyyy:hh:mi:ss pm')
            and CON_STATUS = '5'";


    $oraconn = OracleConnection();
    $pstn = oci_parse($oraconn, $sql);
    if(oci_execute($pstn))
    {
        
        while($row = oci_fetch_array($pstn))
        {
            $empty_result_set = false;
            $sql = "select * from CONTRACTOR_QUALITY where SO_ID= '$row[1]' and PSTN_NO = '$row[0]' ";
            
            
            $oraconn = OracleConnection();
            $quality = oci_parse($oraconn, $sql);
            
            if(oci_execute($quality))
            {
               
                $row = oci_fetch_array($quality);
                $HEADER = $HEADER . "{$row[1]},{$row[0]},{$row[2]},{$row[3]},{$row[4]},{$row[5]},{$row[6]},{$row[7]},{$row[8]},{$row[9]},{$row[10]},{$row[11]},{$row[12]},{$row[13]},{$row[14]},{$row[15]},{$row[16]},{$row[17]},{$row[18]},{$row[19]},{$row[20]},{$row[21]},{$row[22]},{$row[23]}\n";

            }
         else
        {
        $err = oci_error($quality);
        $e =  $err['message'];
        echo "<script type='text/javascript'>alert('$e')</script>";
             }
            
        }
        
        if ($empty_result_set) {
    // No rows in the result set.
            echo "<script type='text/javascript'>alert('No Data Found')</script>";
            echo "<script type='text/javascript'>document.location = \"SLT_QUALITY_REP.php\";</script>";
        }
        
        return $HEADER;
    }
    else
    {
        $err = oci_error($pstn);
        $e =  $err['message'];
        echo "<script type='text/javascript'>alert('$e')</script>";
    }
    
    
    
}


function quantity_report($rtom,$contractor,$from,$to)
{
    
    $HEADER = "PSTN NO,SERVICE ORDER NO,POLES 5.6L,POLES 5.6H,POLES 5.6_CE,POLES 6.7_CE,POLES 6.7,POLES 7.5,POLES 8,POLES 9,POLES 6.7 CON,POLES 7.5 CON,POLES 8 CON,POLES 9 CON,DROP WIRE,INTERNAL WIRE,EARTH WIRE,DISCHARGER,EARTH ROD,RETAINERS,L HOOK,C HOOK,TELEPHONE,PVC CONDUIT,GUY,POLE STRUT,SPUN POLE 5.6,SPUN POLE 6.7,SPUN_POLE_5_6_SLT,SPUN_POLE_6_7_SLT,SPUN_POLE_5_6_CON,SPUN_POLE_6_7_CON,CAT5,IPTV_N,NC_BB_PEO,POLES_5_6_CON,POLES_5_6_CON_CE,POLES_6_7_CON_CE,POLES_S_CON\n";
    $empty_result_set = true;
    $sql = "select CON_CIRCUIT_NO,CON_SO_ID from CONTRACTOR_NEW_CON 
            where CON_CONTRACTOR like '$contractor%'
            and CON_AREA like '$rtom%'    
            and CON_SO_COM_DATE BETWEEN TO_DATE('$from 12:00:00 AM','mm,dd,yyyy:hh:mi:ss pm') AND TO_DATE('$to 11:59:59 PM','mm,dd,yyyy:hh:mi:ss pm')
            and CON_STATUS = '5'";
    

    $oraconn = OracleConnection();
    $pstn = oci_parse($oraconn, $sql);
    if(oci_execute($pstn))
    {
        
        while($row = oci_fetch_array($pstn))
        {
            $empty_result_set = false;
            $sql = "select * from CONTRACTOR_QTY_CHK_MERETIAL where MET_SO_ID= '$row[1]' and PSTN_NO = '$row[0]'";
            $oraconn = OracleConnection();
            $quantity = oci_parse($oraconn, $sql);
            
            if(oci_execute($quantity))
            {
               $row = oci_fetch_array($quantity);
                $HEADER = $HEADER . "{$row[1]},{$row[0]},{$row[2]},{$row[3]},{$row[4]},{$row[5]},{$row[6]},{$row[7]},{$row[8]},{$row[9]},{$row[10]},{$row[11]},{$row[12]},{$row[13]},{$row[14]},{$row[15]},{$row[16]},{$row[17]},{$row[18]},{$row[19]},{$row[20]},{$row[21]},{$row[22]},{$row[23]},{$row[24]},{$row[25]},{$row[26]},{$row[27]},{$row[28]},{$row[29]},{$row[30]},{$row[31]},{$row[32]},{$row[33]},{$row[34]},{$row[35]},{$row[36]},{$row[37]},{$row[38]}\n";
	
            }
         else
        {
        $err = oci_error($quantity);
        $e =  $err['message'];
        echo "<script type='text/javascript'>alert('$e')</script>";
             }
            
        }
        
        if ($empty_result_set) {
    // No rows in the result set.
            echo "<script type='text/javascript'>alert('No Data Found')</script>";
            echo "<script type='text/javascript'>document.location = \"SLT_QUALITY_REP.php\";</script>";
        }
        
        return $HEADER;
    }
    else
    {
        $err = oci_error($pstn);
        $e =  $err['message'];
        echo "<script type='text/javascript'>alert('$e')</script>";
    }
    
    
}

function approve($so_id)
{
    $sql = "update CONTRACTOR_NEW_CON set CON_APPROVE = 'YES', CON_APPROVE_DATE = sysdate where CON_SO_ID = '$so_id' and CON_STATUS = '2'";
    $oraconn = OracleConnection();
    $approve = oci_parse($oraconn, $sql);
    if(oci_execute($approve))
    {
    return 0;
    }
    else
    {
        $err = oci_error($approve);
        $e =  $err['message'];
        echo "<script type='text/javascript'>alert('$e')</script>";
    } 
}

function quality_select($pstn)
{
    $sql = "update CONTRACTOR_NEW_CON set CON_STATUS = '3',SAMPLE_DATE = sysdate where CON_CIRCUIT_NO = '$pstn'";
    $oraconn = OracleConnection();
    $qty_slc = oci_parse($oraconn, $sql);
    if(oci_execute($qty_slc))
    {
    return 0;
    }
    else
    {
        $err = oci_error($qty_slc);
        $e =  $err['message'];
        echo "<script type='text/javascript'>alert('$e')</script>";
    } 
}

function unit_rate_update1($pole5_6L,$pole5_6L_SER,$pole5_6L_ACC,$reg_ind)
{
    $sql = "update CONTRACTOR_UNIT_RATE set POLES_5_6_L = '$pole5_6L', POLES_5_6_L_SER = '$pole5_6L_SER', POLES_5_6_L_ACC ='$pole5_6L_ACC'
            where IND = '{$reg_ind}'";
    $oraconn = OracleConnection();
    $unit_rate_update = oci_parse($oraconn, $sql);
    if(oci_execute($unit_rate_update))
    {
    return 0;
    }
    else
    {
        $err = oci_error($unit_rate_update);
        $e =  $err['message'];
        echo "<script type='text/javascript'>alert('$e')</script>";
    } 
}

function unit_rate_update2($pole5_6H,$pole5_6H_SER,$pole5_6H_ACC,$reg_ind)
{
       $sql = "update CONTRACTOR_UNIT_RATE set POLES_5_6_H = '$pole5_6H', POLES_5_6_H_SER = '$pole5_6H_SER', POLES_5_6_H_ACC = '$pole5_6H_ACC'
           where IND = '{$reg_ind}'";
    $oraconn = OracleConnection();
    $unit_rate_update = oci_parse($oraconn, $sql);
    if(oci_execute($unit_rate_update))
    {
    return 0;
    }
    else
    {
        $err = oci_error($unit_rate_update);
        $e =  $err['message'];
        echo "<script type='text/javascript'>alert('$e')</script>";
    }  
}

function unit_rate_update3($pole6_7,$pole6_7_SER,$pole6_7_ACC,$reg_ind)
{
        $sql = "update CONTRACTOR_UNIT_RATE set POLES_6_7 = '$pole6_7', POLES_6_7_SER = '$pole6_7_SER', POLES_6_7_ACC = '$pole6_7_ACC'             
            where IND = '{$reg_ind}'";
    $oraconn = OracleConnection();
    $unit_rate_update = oci_parse($oraconn, $sql);
    if(oci_execute($unit_rate_update))
    {
    return 0;
    }
    else
    {
        $err = oci_error($unit_rate_update);
        $e =  $err['message'];
        echo "<script type='text/javascript'>alert('$e')</script>";
    } 
}

function unit_rate_update4($pole7_5,$pole7_5_SER,$pole7_5_ACC,$reg_ind)
{
        $sql = "update CONTRACTOR_UNIT_RATE set POLES_7_5 = '$pole7_5', POLES_7_5_SER = '$pole7_5_SER', POLES_7_5_ACC = '$pole7_5_ACC'             
            where IND = '{$reg_ind}'";
    $oraconn = OracleConnection();
    $unit_rate_update = oci_parse($oraconn, $sql);
    if(oci_execute($unit_rate_update))
    {
    return 0;
    }
    else
    {
        $err = oci_error($unit_rate_update);
        $e =  $err['message'];
        echo "<script type='text/javascript'>alert('$e')</script>";
    } 
}

function unit_rate_update5($pole8,$pole8_SER,$pole8_ACC,$reg_ind)
{
        $sql = "update CONTRACTOR_UNIT_RATE set POLES_8 = '$pole8', POLES_8_SER = '$pole8_SER', POLES_8_ACC = '$pole8_ACC'             
            where IND = '{$reg_ind}'";
    $oraconn = OracleConnection();
    $unit_rate_update = oci_parse($oraconn, $sql);
    if(oci_execute($unit_rate_update))
    {
    return 0;
    }
    else
    {
        $err = oci_error($unit_rate_update);
        $e =  $err['message'];
        echo "<script type='text/javascript'>alert('$e')</script>";
    } 
}

function unit_rate_update6($pole9,$pole9_SER,$pole9_ACC,$reg_ind)
{
        $sql = "update CONTRACTOR_UNIT_RATE set POLES_9 = '$pole9', POLES_9_SER = '$pole9_SER', POLES_9_ACC = '$pole9_ACC'             
            where IND = '{$reg_ind}'";
    $oraconn = OracleConnection();
    $unit_rate_update = oci_parse($oraconn, $sql);
    if(oci_execute($unit_rate_update))
    {
    return 0;
    }
    else
    {
        $err = oci_error($unit_rate_update);
        $e =  $err['message'];
        echo "<script type='text/javascript'>alert('$e')</script>";
    } 
}

function unit_rate_update7($pole5_6_CE,$pole5_6_CE_SER,$pole5_6_CE_ACC,$reg_ind)
{
        $sql = "update CONTRACTOR_UNIT_RATE set POLES_5_6_CE = '$pole5_6_CE', POLES_5_6_CE_SER = '$pole5_6_CE_SER', POLES_5_6_CE_ACC ='$pole5_6_CE_ACC'             
            where IND = '{$reg_ind}'";
    $oraconn = OracleConnection();
    $unit_rate_update = oci_parse($oraconn, $sql);
    if(oci_execute($unit_rate_update))
    {
    return 0;
    }
    else
    {
        $err = oci_error($unit_rate_update);
        $e =  $err['message'];
        echo "<script type='text/javascript'>alert('$e')</script>";
    } 
}

function unit_rate_update8($pole6_7_CE,$pole6_7_CE_SER,$pole6_7_CE_ACC,$reg_ind)
{
        $sql = "update CONTRACTOR_UNIT_RATE set POLES_6_7_CE = '$pole6_7_CE', POLES_6_7_CE_SER = '$pole6_7_CE_SER', POLES_6_7_CE_ACC ='$pole6_7_CE_ACC'             
            where IND = '{$reg_ind}'";
    $oraconn = OracleConnection();
    $unit_rate_update = oci_parse($oraconn, $sql);
    if(oci_execute($unit_rate_update))
    {
    return 0;
    }
    else
    {
        $err = oci_error($unit_rate_update);
        $e =  $err['message'];
        echo "<script type='text/javascript'>alert('$e')</script>";
    } 
}

function unit_rate_update9($pole6_7_con,$pole6_7_con_SER,$pole6_7_con_ACC,$reg_ind)
{
        $sql = "update CONTRACTOR_UNIT_RATE set  POLES_6_7_CON = '$pole6_7_con', POLES_6_7_CON_SER = '$pole6_7_con_SER', POLES_6_7_CON_ACC ='$pole6_7_con_ACC'             
            where IND = '{$reg_ind}'";
    $oraconn = OracleConnection();
    $unit_rate_update = oci_parse($oraconn, $sql);
    if(oci_execute($unit_rate_update))
    {
    return 0;
    }
    else
    {
        $err = oci_error($unit_rate_update);
        $e =  $err['message'];
        echo "<script type='text/javascript'>alert('$e')</script>";
    } 
}

function unit_rate_update10($pole7_5_con,$pole7_5_con_SER,$pole7_5_con_ACC,$reg_ind)
{
        $sql = "update CONTRACTOR_UNIT_RATE set  POLES_7_5_CON = '$pole7_5_con', POLES_7_5_CON_SER = '$pole7_5_con_SER', POLES_7_5_CON_ACC ='$pole7_5_con_ACC'            
            where IND = '{$reg_ind}'";
    $oraconn = OracleConnection();
    $unit_rate_update = oci_parse($oraconn, $sql);
    if(oci_execute($unit_rate_update))
    {
    return 0;
    }
    else
    {
        $err = oci_error($unit_rate_update);
        $e =  $err['message'];
        echo "<script type='text/javascript'>alert('$e')</script>";
    } 
}

function unit_rate_update11($pole8_con,$pole8_con_SER,$pole8_con_ACC,$reg_ind)
{
        $sql = "update CONTRACTOR_UNIT_RATE set  POLES_8_CON = '$pole8_con', POLES_8_CON_SER = '$pole8_con_SER', POLES_8_CON_ACC ='$pole8_con_ACC'            
            where IND = '{$reg_ind}'";
    $oraconn = OracleConnection();
    $unit_rate_update = oci_parse($oraconn, $sql);
    if(oci_execute($unit_rate_update))
    {
    return 0;
    }
    else
    {
        $err = oci_error($unit_rate_update);
        $e =  $err['message'];
        echo "<script type='text/javascript'>alert('$e')</script>";
    } 
}

function unit_rate_update12($pole9_con,$pole9_con_SER,$pole9_con_ACC,$reg_ind)
{
        $sql = "update CONTRACTOR_UNIT_RATE set  POLES_9_CON = '$pole9_con', POLES_9_CON_SER = '$pole9_con_SER', POLES_9_CON_ACC ='$pole9_con_ACC'            
            where IND = '{$reg_ind}'";
    $oraconn = OracleConnection();
    $unit_rate_update = oci_parse($oraconn, $sql);
    if(oci_execute($unit_rate_update))
    {
    return 0;
    }
    else
    {
        $err = oci_error($unit_rate_update);
        $e =  $err['message'];
        echo "<script type='text/javascript'>alert('$e')</script>";
    } 
}

function unit_rate_update13($telephone,$telephone_SER,$telephone_ACC,$reg_ind)
{
        $sql = "update CONTRACTOR_UNIT_RATE set  TELEPHONE = '$telephone', TELEPHONE_SER = '$telephone_SER',TELEPHONE_ACC = '$telephone_ACC'           
            where IND = '{$reg_ind}'";
    $oraconn = OracleConnection();
    $unit_rate_update = oci_parse($oraconn, $sql);
    if(oci_execute($unit_rate_update))
    {
    return 0;
    }
    else
    {
        $err = oci_error($unit_rate_update);
        $e =  $err['message'];
        echo "<script type='text/javascript'>alert('$e')</script>";
    } 
}

function unit_rate_update14($discharger,$discharger_SER,$discharger_ACC,$reg_ind )
{
        $sql = "update CONTRACTOR_UNIT_RATE set  DISCHARGER = '$discharger', DISCHARGER_SER = '$discharger_SER', DISCHARGER_ACC = '$discharger_ACC'            
            where IND = '{$reg_ind}'";
    $oraconn = OracleConnection();
    $unit_rate_update = oci_parse($oraconn, $sql);
    if(oci_execute($unit_rate_update))
    {
    return 0;
    }
    else
    {
        $err = oci_error($unit_rate_update);
        $e =  $err['message'];
        echo "<script type='text/javascript'>alert('$e')</script>";
    } 
}

function unit_rate_update15($earth_rod,$earth_rod_SER,$earth_rod_ACC,$reg_ind )
{
        $sql = "update CONTRACTOR_UNIT_RATE set   EARTH_ROD = '$earth_rod', EARTH_ROD_SER = '$earth_rod_SER',EARTH_ROD_ACC = '$earth_rod_ACC'          
            where IND = '{$reg_ind}'";
    $oraconn = OracleConnection();
    $unit_rate_update = oci_parse($oraconn, $sql);
    if(oci_execute($unit_rate_update))
    {
    return 0;
    }
    else
    {
        $err = oci_error($unit_rate_update);
        $e =  $err['message'];
        echo "<script type='text/javascript'>alert('$e')</script>";
    } 
}

function unit_rate_update16($retainers,$retainers_SER,$retainers_ACC,$reg_ind)
{
        $sql = "update CONTRACTOR_UNIT_RATE set  RETAINERS = '$retainers', RETAINERS_SER = '$retainers_SER', RETAINERS_ACC = '$retainers_ACC'            
            where IND = '{$reg_ind}'";
    $oraconn = OracleConnection();
    $unit_rate_update = oci_parse($oraconn, $sql);
    if(oci_execute($unit_rate_update))
    {
    return 0;
    }
    else
    {
        $err = oci_error($unit_rate_update);
        $e =  $err['message'];
        echo "<script type='text/javascript'>alert('$e')</script>";
    } 
}

function unit_rate_update17($L_hook,$L_hook_SER,$L_hook_ACC,$reg_ind)
{
        $sql = "update CONTRACTOR_UNIT_RATE set L_HOOK =  '$L_hook', L_HOOK_SER =  '$L_hook_SER', L_HOOK_ACC =  '$L_hook_ACC'           
            where IND = '{$reg_ind}'";
    $oraconn = OracleConnection();
    $unit_rate_update = oci_parse($oraconn, $sql);
    if(oci_execute($unit_rate_update))
    {
    return 0;
    }
    else
    {
        $err = oci_error($unit_rate_update);
        $e =  $err['message'];
        echo "<script type='text/javascript'>alert('$e')</script>";
    } 
}

function unit_rate_update18($C_hook,$C_hook_SER,$C_hook_ACC,$reg_ind)
{
        $sql = "update CONTRACTOR_UNIT_RATE set  C_HOOK = '$C_hook', C_HOOK_SER = '$C_hook_SER',C_HOOK_ACC = '$C_hook_ACC'           
            where IND = '{$reg_ind}'";
    $oraconn = OracleConnection();
    $unit_rate_update = oci_parse($oraconn, $sql);
    if(oci_execute($unit_rate_update))
    {
    return 0;
    }
    else
    {
        $err = oci_error($unit_rate_update);
        $e =  $err['message'];
        echo "<script type='text/javascript'>alert('$e')</script>";
    } 
}

function unit_rate_update19($earth_wire,$earth_wire_SER,$earth_wire_ACC,$reg_ind)
{
        $sql = "update CONTRACTOR_UNIT_RATE set EARTH_WIRE = '$earth_wire',EARTH_WIRE_SER = '$earth_wire_SER',EARTH_WIRE_ACC = '$earth_wire_ACC'             
            where IND = '{$reg_ind}'";
    $oraconn = OracleConnection();
    $unit_rate_update = oci_parse($oraconn, $sql);
    if(oci_execute($unit_rate_update))
    {
    return 0;
    }
    else
    {
        $err = oci_error($unit_rate_update);
        $e =  $err['message'];
        echo "<script type='text/javascript'>alert('$e')</script>";
    } 
}

function unit_rate_update20($pvc_conduit,$pvc_conduit_SER,$pvc_conduit_ACC,$reg_ind)
{
        $sql = "update CONTRACTOR_UNIT_RATE set PVC_CONDUIT = '$pvc_conduit', PVC_CONDUIT_SER = '$pvc_conduit_SER',PVC_CONDUIT_ACC = '$pvc_conduit_ACC'            
            where IND = '{$reg_ind}'";
    $oraconn = OracleConnection();
    $unit_rate_update = oci_parse($oraconn, $sql);
    if(oci_execute($unit_rate_update))
    {
    return 0;
    }
    else
    {
        $err = oci_error($unit_rate_update);
        $e =  $err['message'];
        echo "<script type='text/javascript'>alert('$e')</script>";
    } 
}

function unit_rate_update21($drop_wire,$drop_wire_SER,$drop_wire_ACC,$reg_ind)
{
        $sql = "update CONTRACTOR_UNIT_RATE set DROP_WIRE = '$drop_wire',  DROP_WIRE_SER = '$drop_wire_SER', DROP_WIRE_ACC = '$drop_wire_ACC'             
            where IND = '{$reg_ind}'";
    $oraconn = OracleConnection();
    $unit_rate_update = oci_parse($oraconn, $sql);
    if(oci_execute($unit_rate_update))
    {
    return 0;
    }
    else
    {
        $err = oci_error($unit_rate_update);
        $e =  $err['message'];
        echo "<script type='text/javascript'>alert('$e')</script>";
    } 
}

function unit_rate_update22($internal_wire,$internal_wire_SER,$internal_wire_ACC,$reg_ind)
{
        $sql = "update CONTRACTOR_UNIT_RATE set INTERNAL_WIRE = '$internal_wire', INTERNAL_WIRE_SER = '$internal_wire_SER',INTERNAL_WIRE_ACC = '$internal_wire_ACC'            
            where IND = '{$reg_ind}'";
    $oraconn = OracleConnection();
    $unit_rate_update = oci_parse($oraconn, $sql);
    if(oci_execute($unit_rate_update))
    {
    return 0;
    }
    else
    {
        $err = oci_error($unit_rate_update);
        $e =  $err['message'];
        echo "<script type='text/javascript'>alert('$e')</script>";
    } 
}

function unit_rate_update23($pole_strut,$pole_strut_SER,$pole_strut_ACC,$reg_ind)
{
        $sql = "update CONTRACTOR_UNIT_RATE set POLE_STRUT = '$pole_strut', POLE_STRUT_SER = '$pole_strut_SER',POLE_STRUT_ACC = '$pole_strut_ACC'            
            where IND = '{$reg_ind}'";
    $oraconn = OracleConnection();
    $unit_rate_update = oci_parse($oraconn, $sql);
    if(oci_execute($unit_rate_update))
    {
    return 0;
    }
    else
    {
        $err = oci_error($unit_rate_update);
        $e =  $err['message'];
        echo "<script type='text/javascript'>alert('$e')</script>";
    } 
}

function unit_rate_update24($guy,$guy_SER,$guy_ACC,$reg_ind)
{
        $sql = "update CONTRACTOR_UNIT_RATE set  GUY = '$guy',  GUY_SER = '$guy_SER', GUY_ACC = '$guy_ACC'      
            where IND = '{$reg_ind}'";
    $oraconn = OracleConnection();
    $unit_rate_update = oci_parse($oraconn, $sql);
    if(oci_execute($unit_rate_update))
    {
    return 0;
    }
    else
    {
        $err = oci_error($unit_rate_update);
        $e =  $err['message'];
        echo "<script type='text/javascript'>alert('$e')</script>";
    } 
}

function unit_rate_update25($spun_pole_5_6,$spun_pole_5_6_SER,$spun_pole_5_6_ACC,$reg_ind)
{
        $sql = "update CONTRACTOR_UNIT_RATE set SPUN_POLE_5_6 = '$spun_pole_5_6',  SPUN_POLE_5_6_SER = '$spun_pole_5_6_SER', SPUN_POLE_5_6_ACC = '$spun_pole_5_6_ACC'           
            where IND = '{$reg_ind}'";
    $oraconn = OracleConnection();
    $unit_rate_update = oci_parse($oraconn, $sql);
    if(oci_execute($unit_rate_update))
    {
    return 0;
    }
    else
    {
        $err = oci_error($unit_rate_update);
        $e =  $err['message'];
        echo "<script type='text/javascript'>alert('$e')</script>";
    } 
}

function unit_rate_update26($spun_pole_6_7,$spun_pole_6_7_SER,$spun_pole_6_7_ACC,$reg_ind)
{
        $sql = "update CONTRACTOR_UNIT_RATE set  SPUN_POLE_6_7 = '$spun_pole_6_7', SPUN_POLE_6_7_SER = '$spun_pole_6_7_SER', SPUN_POLE_6_7_ACC = '$spun_pole_6_7_ACC'           
            where IND = '{$reg_ind}'";
    $oraconn = OracleConnection();
    $unit_rate_update = oci_parse($oraconn, $sql);
    if(oci_execute($unit_rate_update))
    {
    return 0;
    }
    else
    {
        $err = oci_error($unit_rate_update);
        $e =  $err['message'];
        echo "<script type='text/javascript'>alert('$e')</script>";
    } 
}

function unit_rate_update27($spun_pole_5_6_slt,$spun_pole_5_6_slt_SER,$spun_pole_5_6_slt_ACC,$reg_ind)
{
        $sql = "update CONTRACTOR_UNIT_RATE set  SPUN_POLE_5_6_SLT = '$spun_pole_5_6_slt', SPUN_POLE_5_6_SLT_SER = '$spun_pole_5_6_slt_SER', SPUN_POLE_5_6_SLT_ACC = '$spun_pole_5_6_slt_ACC'           
            where IND = '{$reg_ind}'";
    $oraconn = OracleConnection();
    $unit_rate_update = oci_parse($oraconn, $sql);
    if(oci_execute($unit_rate_update))
    {
    return 0;
    }
    else
    {
        $err = oci_error($unit_rate_update);
        $e =  $err['message'];
        echo "<script type='text/javascript'>alert('$e')</script>";
    } 
}

function unit_rate_update28($spun_pole_6_7_slt,$spun_pole_6_7_slt_SER,$spun_pole_6_7_slt_ACC,$reg_ind)
{
        $sql = "update CONTRACTOR_UNIT_RATE set  SPUN_POLE_6_7_SLT = '$spun_pole_6_7_slt', SPUN_POLE_6_7_SLT_SER = '$spun_pole_6_7_slt_SER', SPUN_POLE_6_7_SLT_ACC = '$spun_pole_6_7_slt_ACC'           
            where IND = '{$reg_ind}'";
    $oraconn = OracleConnection();
    $unit_rate_update = oci_parse($oraconn, $sql);
    if(oci_execute($unit_rate_update))
    {
    return 0;
    }
    else
    {
        $err = oci_error($unit_rate_update);
        $e =  $err['message'];
        echo "<script type='text/javascript'>alert('$e')</script>";
    } 
}

function unit_rate_update29($spun_pole_5_6_con,$spun_pole_5_6_con_SER,$spun_pole_5_6_con_ACC,$reg_ind)
{
        $sql = "update CONTRACTOR_UNIT_RATE set  SPUN_POLE_5_6_CON = '$spun_pole_5_6_con', SPUN_POLE_5_6_CON_SER = '$spun_pole_5_6_con_SER', SPUN_POLE_5_6_CON_ACC = '$spun_pole_5_6_con_ACC'           
            where IND = '{$reg_ind}'";
    $oraconn = OracleConnection();
    $unit_rate_update = oci_parse($oraconn, $sql);
    if(oci_execute($unit_rate_update))
    {
    return 0;
    }
    else
    {
        $err = oci_error($unit_rate_update);
        $e =  $err['message'];
        echo "<script type='text/javascript'>alert('$e')</script>";
    } 
}

function unit_rate_update30($spun_pole_6_7_con,$spun_pole_6_7_con_SER,$spun_pole_6_7_con_ACC,$reg_ind)
{
        $sql = "update CONTRACTOR_UNIT_RATE set  SPUN_POLE_6_7_CON = '$spun_pole_6_7_con', SPUN_POLE_6_7_CON_SER = '$spun_pole_6_7_con_SER', SPUN_POLE_6_7_CON_ACC = '$spun_pole_6_7_con_ACC'           
            where IND = '{$reg_ind}'";
    $oraconn = OracleConnection();
    $unit_rate_update = oci_parse($oraconn, $sql);
    if(oci_execute($unit_rate_update))
    {
    return 0;
    }
    else
    {
        $err = oci_error($unit_rate_update);
        $e =  $err['message'];
        echo "<script type='text/javascript'>alert('$e')</script>";
    } 
}

function unit_rate_update31($cat5,$cat5_SER,$cat5_ACC,$reg_ind)
{
        $sql = "update CONTRACTOR_UNIT_RATE set  CAT5 = '$cat5', CAT5_SER = '$cat5_SER', CAT5_ACC = '$cat5_ACC'           
            where IND = '{$reg_ind}'";
    $oraconn = OracleConnection();
    $unit_rate_update = oci_parse($oraconn, $sql);
    if(oci_execute($unit_rate_update))
    {
    return 0;
    }
    else
    {
        $err = oci_error($unit_rate_update);
        $e =  $err['message'];
        echo "<script type='text/javascript'>alert('$e')</script>";
    } 
}

function unit_rate_update32($iptvn,$iptvn_SER,$iptvn_ACC,$reg_ind)
{
        $sql = "update CONTRACTOR_UNIT_RATE set  IPTV_N = '$iptvn', IPTV_N_SER = '$iptvn_SER', IPTV_N_ACC = '$iptvn_ACC'           
            where IND = '{$reg_ind}'";
    $oraconn = OracleConnection();
    $unit_rate_update = oci_parse($oraconn, $sql);
    if(oci_execute($unit_rate_update))
    {
    return 0;
    }
    else
    {
        $err = oci_error($unit_rate_update);
        $e =  $err['message'];
        echo "<script type='text/javascript'>alert('$e')</script>";
    } 
}

function unit_rate_update33($nc_bb,$nc_bb_SER,$nc_bb_ACC,$reg_ind)
{
        $sql = "update CONTRACTOR_UNIT_RATE set  NC_BB_PEO = '$nc_bb', NC_BB_PEO_SER = '$nc_bb_SER', NC_BB_PEO_ACC = '$nc_bb_ACC'           
            where IND = '{$reg_ind}'";
    $oraconn = OracleConnection();
    $unit_rate_update = oci_parse($oraconn, $sql);
    if(oci_execute($unit_rate_update))
    {
    return 0;
    }
    else
    {
        $err = oci_error($unit_rate_update);
        $e =  $err['message'];
        echo "<script type='text/javascript'>alert('$e')</script>";
    } 
}

function unit_rate_update34($pole_5_6_con,$pole_5_6_con_SER,$pole_5_6_con_ACC,$reg_ind)
{
        $sql = "update CONTRACTOR_UNIT_RATE set  POLES_5_6_CON = '$pole_5_6_con', POLES_5_6_CON_SER = '$pole_5_6_con_SER', POLES_5_6_CON_ACC = '$pole_5_6_con_ACC'           
            where IND = '{$reg_ind}'";
    $oraconn = OracleConnection();
    $unit_rate_update = oci_parse($oraconn, $sql);
    if(oci_execute($unit_rate_update))
    {
    return 0;
    }
    else
    {
        $err = oci_error($unit_rate_update);
        $e =  $err['message'];
        echo "<script type='text/javascript'>alert('$e')</script>";
    } 
}

function unit_rate_update35($pole_5_6_con_ce,$pole_5_6_con_ce_SER,$pole_5_6_con_ce_ACC,$reg_ind)
{
        $sql = "update CONTRACTOR_UNIT_RATE set  POLES_5_6_CON_CE = '$pole_5_6_con_ce', POLES_5_6_CON_CE_SER = '$pole_5_6_con_ce_SER', POLES_5_6_CON_CE_ACC = '$pole_5_6_con_ce_ACC'           
            where IND = '{$reg_ind}'";
    $oraconn = OracleConnection();
    $unit_rate_update = oci_parse($oraconn, $sql);
    if(oci_execute($unit_rate_update))
    {
    return 0;
    }
    else
    {
        $err = oci_error($unit_rate_update);
        $e =  $err['message'];
        echo "<script type='text/javascript'>alert('$e')</script>";
    } 
}

function unit_rate_update36($pole_6_7_con_ce,$pole_6_7_con_ce_SER,$pole_6_7_con_ce_ACC,$reg_ind)
{
        $sql = "update CONTRACTOR_UNIT_RATE set  POLES_6_7_CON_CE = '$pole_6_7_con_ce', POLES_6_7_CON_CE_SER = '$pole_6_7_con_ce_SER', POLES_6_7_CON_CE_ACC = '$pole_6_7_con_ce_ACC'           
            where IND = '{$reg_ind}'";
    $oraconn = OracleConnection();
    $unit_rate_update = oci_parse($oraconn, $sql);
    if(oci_execute($unit_rate_update))
    {
    return 0;
    }
    else
    {
        $err = oci_error($unit_rate_update);
        $e =  $err['message'];
        echo "<script type='text/javascript'>alert('$e')</script>";
    } 
}

function unit_rate_update37($pole_s_con,$pole_s_con_SER,$pole_s_con_ACC,$reg_ind)
{
        $sql = "update CONTRACTOR_UNIT_RATE set  POLES_S_CON = '$pole_s_con', POLES_S_CON_SER = '$pole_s_con_SER', POLES_S_CON_ACC = '$pole_s_con_ACC'           
            where IND = '{$reg_ind}'";
    $oraconn = OracleConnection();
    $unit_rate_update = oci_parse($oraconn, $sql);
    if(oci_execute($unit_rate_update))
    {
    return 0;
    }
    else
    {
        $err = oci_error($unit_rate_update);
        $e =  $err['message'];
        echo "<script type='text/javascript'>alert('$e')</script>";
    } 
}

function log_logging($usr, $ip)
{
    $sql = "INSERT INTO CONTRACTOR_LOG (CON_USER,LOG_DATE,MSG ) 
        VALUES ('$usr',sysdate,'Successfully Logging to the System using ip : $ip ')";

    $oraconn = OracleConnection();
    $log_usr = oci_parse($oraconn, $sql);
    if(oci_execute($log_usr))
    {
        return 0;
    }
    else
    {
        $err = oci_error($log_usr);
        $e =  $err['message'];
        echo "<script type='text/javascript'>alert('$e')</script>";
    }
   
}

function log_fail($usr, $ip)
{
    $sql = "INSERT INTO CONTRACTOR_LOG (CON_USER,LOG_DATE,MSG ) 
        VALUES ('$usr',sysdate,'Logging Attempt Fail, logging ip : $ip ')";

    $oraconn = OracleConnection();
    $log_usr = oci_parse($oraconn, $sql);
    if(oci_execute($log_usr))
    {
        return 0;
    }
    else
    {
        $err = oci_error($log_usr);
        $e =  $err['message'];
        echo "<script type='text/javascript'>alert('$e')</script>";
    }
   
}

function log_index($ip)
{
    $sql = "INSERT INTO CONTRACTOR_LOG (LOG_DATE,MSG ) 
        VALUES (sysdate,'Access the Index page, logging ip : $ip ')";

    $oraconn = OracleConnection();
    $log_usr = oci_parse($oraconn, $sql);
    if(oci_execute($log_usr))
    {
        return 0;
    }
    else
    {
        $err = oci_error($log_usr);
        $e =  $err['message'];
        echo "<script type='text/javascript'>alert('$e')</script>";
    }
    
}

function log_logout($usr)
{
    $sql = "INSERT INTO CONTRACTOR_LOG (CON_USER,LOG_DATE,MSG ) 
        VALUES ('$usr',sysdate,'Log out from the System')";

    $oraconn = OracleConnection();
    $log_usr = oci_parse($oraconn, $sql);
    if(oci_execute($log_usr))
    {
    return $log_usr;
    }
    else
    {
        $err = oci_error($log_usr);
        $e =  $err['message'];
        echo "<script type='text/javascript'>alert('$e')</script>";
    }
}

function log_completed($usr,$so_id)
{
    $sql = "INSERT INTO CONTRACTOR_LOG (CON_USER,LOG_DATE,SO_ID,MSG ) 
        VALUES ('$usr',sysdate,'$so_id','Work Order Completed')";

    $oraconn = OracleConnection();
    $log_usr = oci_parse($oraconn, $sql);
    if(oci_execute($log_usr))
    {
    return $log_usr;
    }
    else
    {
        $err = oci_error($log_usr);
        $e =  $err['message'];
        echo "<script type='text/javascript'>alert('$e')</script>";
    }
}

function log_completed_update($usr,$so_id)
{
    $sql = "INSERT INTO CONTRACTOR_LOG (CON_USER,LOG_DATE,SO_ID,MSG ) 
        VALUES ('$usr',sysdate,'$so_id','Work Order Meterial Updated')";

    $oraconn = OracleConnection();
    $log_usr = oci_parse($oraconn, $sql);
    if(oci_execute($log_usr))
    {
    return $log_usr;
    }
    else
    {
        $err = oci_error($log_usr);
        $e =  $err['message'];
        echo "<script type='text/javascript'>alert('$e')</script>";
    }
}

function log_approve_wo($usr,$so_id)
{
    $sql = "INSERT INTO CONTRACTOR_LOG (CON_USER,LOG_DATE,SO_ID,MSG ) 
        VALUES ('$usr',sysdate,'$so_id','Work Order Meterial Approved')";

    $oraconn = OracleConnection();
    $log_usr = oci_parse($oraconn, $sql);
    if(oci_execute($log_usr))
    {
    return $log_usr;
    }
    else
    {
        $err = oci_error($log_usr);
        $e =  $err['message'];
        echo "<script type='text/javascript'>alert('$e')</script>";
    }
}

function log_delayed($usr,$so_id,$reason)
{
    $sql = "INSERT INTO CONTRACTOR_LOG (CON_USER,LOG_DATE,SO_ID,MSG ) 
        VALUES ('$usr',sysdate,'$so_id','Work Order Status changed to DELAYED Due to $reason')";

    $oraconn = OracleConnection();
    $log_usr = oci_parse($oraconn, $sql);
    if(oci_execute($log_usr))
    {
    return $log_usr;
    }
    else
    {
        $err = oci_error($log_usr);
        $e =  $err['message'];
        echo "<script type='text/javascript'>alert('$e')</script>";
    }
}

function log_returned($usr,$so_id,$reason)
{
    $sql = "INSERT INTO CONTRACTOR_LOG (CON_USER,LOG_DATE,SO_ID,MSG ) 
        VALUES ('$usr',sysdate,'$so_id','Work Order Status changed to RETURNED Due to $reason')";

    $oraconn = OracleConnection();
    $log_usr = oci_parse($oraconn, $sql);
    if(oci_execute($log_usr))
    {
    return $log_usr;
    }
    else
    {
        $err = oci_error($log_usr);
        $e =  $err['message'];
        echo "<script type='text/javascript'>alert('$e')</script>";
    }
}

function log_inprogress($usr,$so_id)
{
    $sql = "INSERT INTO CONTRACTOR_LOG (CON_USER,LOG_DATE,SO_ID,MSG ) 
        VALUES ('$usr',sysdate,'$so_id','Work Order Status changed to INPROGRESS')";

    $oraconn = OracleConnection();
    $log_usr = oci_parse($oraconn, $sql);
    if(oci_execute($log_usr))
    {
    return $log_usr;
    }
    else
    {
        $err = oci_error($log_usr);
        $e =  $err['message'];
        echo "<script type='text/javascript'>alert('$e')</script>";
    }
}

function log_add_con($usr,$con_name)
{
    $sql = "INSERT INTO CONTRACTOR_LOG (CON_USER,LOG_DATE,MSG ) 
        VALUES ('$usr',sysdate,'Add New Contractor as $con_name, to the System')";

    $oraconn = OracleConnection();
    $log_usr = oci_parse($oraconn, $sql);
    if(oci_execute($log_usr))
    {
    return $log_usr;
    }
    else
    {
        $err = oci_error($log_usr);
        $e =  $err['message'];
        echo "<script type='text/javascript'>alert('$e')</script>";
    }
}

function log_add_con_user($user,$con_name,$con_user_name)
{
    $sql = "INSERT INTO CONTRACTOR_LOG (CON_USER,LOG_DATE,MSG ) 
        VALUES ('$user',sysdate,'Add New User as $con_user_name for Contractor $con_name, to the System')";

    $oraconn = OracleConnection();
    $log_usr = oci_parse($oraconn, $sql);
    if(oci_execute($log_usr))
    {
    return $log_usr;
    }
    else
    {
        $err = oci_error($log_usr);
        $e =  $err['message'];
        echo "<script type='text/javascript'>alert('$e')</script>";
    }
}


function log_unit_rate($usr, $reg_ind)
{
    $sql1 = "Select distinct REGION from CONTRACTOR_REGION where IND = '$reg_ind'";
    $oraconn = OracleConnection();
    $log_rgn = oci_parse($oraconn, $sql1);
    if(oci_execute($log_rgn))
    {
    $rgn = oci_fetch_array($log_rgn);
    
    $sql = "INSERT INTO CONTRACTOR_LOG (CON_USER,LOG_DATE,MSG ) 
        VALUES ('$usr',sysdate,'Updated the Unit rate in $rgn[0] Region')";

    $oraconn = OracleConnection();
    $log_usr = oci_parse($oraconn, $sql);
    if(oci_execute($log_usr))
    {
    return $log_usr;
    }
    else
    {
        $err = oci_error($log_usr);
        $e =  $err['message'];
        echo "<script type='text/javascript'>alert('$e')</script>";
    }
    }
    else
    {
        $err = oci_error($log_rgn);
        $e =  $err['message'];
        echo "<script type='text/javascript'>alert('$e')</script>";
    }
}

function log_thresh_value($usr, $con_name,$fromdate,$todate,$amount)
{
    $sql = "INSERT INTO CONTRACTOR_LOG (CON_USER,LOG_DATE,MSG ) 
        VALUES ('$usr',sysdate,'Updated the Threshold Value of $con_name, for period from $fromdate to $todate')";

    $oraconn = OracleConnection();
    $log_usr = oci_parse($oraconn, $sql);
    if(oci_execute($log_usr))
    {
    return $log_usr;
    }
    else
    {
        $err = oci_error($log_usr);
        $e =  $err['message'];
        echo "<script type='text/javascript'>alert('$e')</script>";
    }
}

function log_unit_rate_update($user,$msg)
{
    $sql = "INSERT INTO CONTRACTOR_LOG (CON_USER,LOG_DATE,MSG ) 
        VALUES ('$user',sysdate,'$msg')";
   

    $oraconn = OracleConnection();
    $log_usr = oci_parse($oraconn, $sql);
    if(oci_execute($log_usr))
    {
    return $log_usr;
    }
    else
    {
        $err = oci_error($log_usr);
        $e =  $err['message'];
        echo "<script type='text/javascript'>alert('$e')</script>";
    }
    
}

function log_invoice_create($user,$fromdate,$todate,$area,$invoice_no)
{
    $sql = "INSERT INTO CONTRACTOR_LOG (CON_USER,LOG_DATE,MSG ) 
        VALUES ('$user',sysdate,'Create Invoice for Area : $area, Time Period : $fromdate - $todate, Invoice NO : $invoice_no ' )";
   

    $oraconn = OracleConnection();
    $log_usr = oci_parse($oraconn, $sql);
    if(oci_execute($log_usr))
    {
    return $log_usr;
    }
    else
    {
        $err = oci_error($log_usr);
        $e =  $err['message'];
        echo "<script type='text/javascript'>alert('$e')</script>";
    }
    
}

function log_passwd($usr_name,$to)
{
    $sql = "INSERT INTO CONTRACTOR_LOG (CON_USER,LOG_DATE,MSG ) 
        VALUES ('$usr_name',sysdate,'User Name , Password Sent to : $to ' )";
   

    $oraconn = OracleConnection();
    $log_usr = oci_parse($oraconn, $sql);
    if(oci_execute($log_usr))
    {
    return $log_usr;
    }
    else
    {
        $err = oci_error($log_usr);
        $e =  $err['message'];
        echo "<script type='text/javascript'>alert('$e')</script>";
    }
}

function log_qty_select($user,$value)
{
    $sql = "INSERT INTO CONTRACTOR_LOG (CON_USER,LOG_DATE,MSG ) 
        VALUES ('$user',sysdate,'$value seleted for Quality Check' )";
   

    $oraconn = OracleConnection();
    $log_usr = oci_parse($oraconn, $sql);
    if(oci_execute($log_usr))
    {
    return $log_usr;
    }
    else
    {
        $err = oci_error($log_usr);
        $e =  $err['message'];
        echo "<script type='text/javascript'>alert('$e')</script>";
    }
}

function log_qunatity($user, $pstn)
{
    $sql = "INSERT INTO CONTRACTOR_LOG (CON_USER,LOG_DATE,MSG ) 
        VALUES ('$user',sysdate,'$pstn Checked Quantity Updated' )";
   

    $oraconn = OracleConnection();
    $log_usr = oci_parse($oraconn, $sql);
    if(oci_execute($log_usr))
    {
    return $log_usr;
    }
    else
    {
        $err = oci_error($log_usr);
        $e =  $err['message'];
        echo "<script type='text/javascript'>alert('$e')</script>";
    }
}

function log_qunatity2($user, $pstn)
{
    $sql = "INSERT INTO CONTRACTOR_LOG (CON_USER,LOG_DATE,MSG ) 
        VALUES ('$user',sysdate,'$pstn Re Checked Quantity Updated' )";
   

    $oraconn = OracleConnection();
    $log_usr = oci_parse($oraconn, $sql);
    if(oci_execute($log_usr))
    {
    return $log_usr;
    }
    else
    {
        $err = oci_error($log_usr);
        $e =  $err['message'];
        echo "<script type='text/javascript'>alert('$e')</script>";
    }
}
function log_quality($user, $pstn)
{
    $sql = "INSERT INTO CONTRACTOR_LOG (CON_USER,LOG_DATE,MSG ) 
        VALUES ('$user',sysdate,'$pstn Checked Quality Updated' )";
   

    $oraconn = OracleConnection();
    $log_usr = oci_parse($oraconn, $sql);
    if(oci_execute($log_usr))
    {
    return $log_usr;
    }
    else
    {
        $err = oci_error($log_usr);
        $e =  $err['message'];
        echo "<script type='text/javascript'>alert('$e')</script>";
    }
}

function log_quality2($user, $pstn)
{
    $sql = "INSERT INTO CONTRACTOR_LOG (CON_USER,LOG_DATE,MSG ) 
        VALUES ('$user',sysdate,'$pstn Re Checked Quality Updated' )";
   

    $oraconn = OracleConnection();
    $log_usr = oci_parse($oraconn, $sql);
    if(oci_execute($log_usr))
    {
    return $log_usr;
    }
    else
    {
        $err = oci_error($log_usr);
        $e =  $err['message'];
        echo "<script type='text/javascript'>alert('$e')</script>";
    }
}
function getPhn($contractor,$usr_name)
{

    $sql ="select CON_MGT_MOBILE from CONTRACTOR_MGT_USERS where CON_MGT_USER_NAME= :CON_MGT_USER_NAME and 
            CON_MGT_CONTRACTOR = :CON_MGT_CONTRACTOR";
    $oraconn = OracleConnection();
    $mail = oci_parse($oraconn, $sql);
    oci_bind_by_name($mail, ":CON_MGT_USER_NAME", $usr_name);
    oci_bind_by_name($mail, ":CON_MGT_CONTRACTOR", $contractor);
    if(oci_execute($mail))
    {    
    $row=oci_fetch_array($mail);
    return $row[0];
    }
    else
    {
        $err = oci_error($mail);
        $e =  $err['message'];
        echo "<script type='text/javascript'>alert('$e')</script>";
    }
}

function SendSMS($phn,$passwd,$uname)
{
    $sql = "INSERT INTO OSSRPT.SEND_SMS_DATA_CON (SMS_MSG, SMS_STATUS, SMS_ID,MOB_NUMBER,SEND_USER) VALUES 
	( 'your username : ".$uname." and Password : ".$passwd."','0','$phn','$phn','SLTCMS' )";
    $oraconn = OracleConnectionSMS();
    $phn = oci_parse($oraconn, $sql);
    if(oci_execute($phn))
    {
    return 0;
    }
    else
    {
        $err = oci_error($phn);
        $e =  $err['message'];
        echo "<script type='text/javascript'>alert('$e')</script>";
    }
}

function oh_dis($dis)
{

    $sql ="select (M.DROP_WIRE+MS.DROP_WIRE_SER+MA.DROP_WIRE_ACC)
            from CONTRACTOR_MERETIAL m,CONTRACTOR_MERETIAL_SER ms,CONTRACTOR_MERETIAL_ACC ma
            where M.MET_SO_ID = MA.MET_SO_ID
            and MA.MET_SO_ID = MS.MET_SO_ID
            and M.MET_SO_ID = '$dis'";
    $oraconn = OracleConnection();
    $dist = oci_parse($oraconn, $sql);
    
    if(oci_execute($dist))
    {    
    $row=oci_fetch_array($dist);
    return $row[0];
    }
    else
    {
        $err = oci_error($dist);
        $e =  $err['message'];
        echo "<script type='text/javascript'>alert('$e')</script>";
    }
}

function area_name()
{

    $sql ="select distinct OPMC from CONTRACTOR_REGION order by OPMC";
    $oraconn = OracleConnection();
    $area = oci_parse($oraconn, $sql);

    if(oci_execute($area))
    {
    return $area;
    }
    else
    {
        $err = oci_error($area);
        $e =  $err['message'];
        echo "<script type='text/javascript'>alert('$e')</script>";
    }
}

function pstn_detail($pstn)
{

    $sql ="select * from CONTRACTOR_NEW_CON where CON_CIRCUIT_NO = '$pstn' and CON_SO_STATUS = 'ASSIGNED'";
    $oraconn = OracleConnection();
    $pstn_de = oci_parse($oraconn, $sql);

    if(oci_execute($pstn_de))
    {
    return $pstn_de;
    }
    else
    {
        $err = oci_error($pstn_de);
        $e =  $err['message'];
        echo "<script type='text/javascript'>alert('$e')</script>";
    }
}

function timeSlot($pstn,$contractor,$times,$date,$area)
{
    $sql = "INSERT INTO SLT_CON_MGT.CONTRACTOR_SCHEDULE (PSTN_NO,CONTRACTOR,CON_DATE,TIME_SLOT,ST_FLAG,AREA) 
			VALUES ( '$pstn','$contractor','$date','$times','0','$area')";
			
		
    $oraconn = OracleConnection();
    $phn = oci_parse($oraconn, $sql);
    if(oci_execute($phn))
    {
    return 0;
    }
    else
    {
        $err = oci_error($phn);
        $e =  $err['message'];
        echo "<script type='text/javascript'>alert('$e')</script>";
    }
}


function time_detail($date)
{

    $sql ="select * from CONTRACTOR_SCHEDULE where CON_DATE = '$date'";
    $oraconn = OracleConnection();
    $pstn_de = oci_parse($oraconn, $sql);

    if(oci_execute($pstn_de))
    {
    return $pstn_de;
    }
    else
    {
        $err = oci_error($pstn_de);
        $e =  $err['message'];
        echo "<script type='text/javascript'>alert('$e')</script>";
    }
}

function get_appoint($soid)
{

    $sql ="select APPOINT_DATE,TIME_SLOT,CON_TEAM,ST_FLAG from CONTRACTOR_SCHEDULE where SO_ID = '$soid'";
    $oraconn = OracleConnection();
    $apoint = oci_parse($oraconn, $sql);

    if(oci_execute($apoint))
    {
    return $apoint;
    }
    else
    {
        $err = oci_error($apoint);
        $e =  $err['message'];
        echo "<script type='text/javascript'>alert('$e')</script>";
    }
}


function to_appoint($contractor_name)
{

    $sql ="select * from CONTRACTOR_SCHEDULE where CONTRACTOR = '$contractor_name'
		and APPOINT_DATE = (select TO_CHAR(SYSDATE, 'MM/DD/YYYY') from dual) order by CON_TEAM";
    $oraconn = OracleConnection();
    $apoint = oci_parse($oraconn, $sql);

    if(oci_execute($apoint))
    {
    return $apoint;
    }
    else
    {
        $err = oci_error($apoint);
        $e =  $err['message'];
        echo "<script type='text/javascript'>alert('$e')</script>";
    }
}


function sltto_appoint($area)
{

    $sql ="select * from CONTRACTOR_SCHEDULE where  AREA = '$area'
        and APPOINT_DATE = (select TO_CHAR(SYSDATE, 'MM/DD/YYYY') from dual)";
    $oraconn = OracleConnection();
    $apoint = oci_parse($oraconn, $sql);

    if(oci_execute($apoint))
    {
    return $apoint;
    }
    else
    {
        $err = oci_error($apoint);
        $e =  $err['message'];
        echo "<script type='text/javascript'>alert('$e')</script>";
    }
}


function serto_appoint($area,$date)
{

    $sql ="select * from CONTRACTOR_SCHEDULE where  AREA = '$area' and APPOINT_DATE = '$date'";
    $oraconn = OracleConnection();
    $apoint = oci_parse($oraconn, $sql);

    if(oci_execute($apoint))
    {
    return $apoint;
    }
    else
    {
        $err = oci_error($apoint);
        $e =  $err['message'];
        echo "<script type='text/javascript'>alert('$e')</script>";
    }
}
function appoint_repo($area,$fromdate,$todate )
{

    $sql ="select * from CONTRACTOR_SCHEDULE where  AREA = '$area' and APPOINT_DATE BETWEEN
	'$fromdate'  AND '$todate'";
    $oraconn = OracleConnection();
    $apoint = oci_parse($oraconn, $sql);

    if(oci_execute($apoint))
    {
    return $apoint;
    }
    else
    {
        $err = oci_error($apoint);
        $e =  $err['message'];
        echo "<script type='text/javascript'>alert('$e')</script>";
    }
}

function appoint_repo_all($fromdate,$todate )
{

    $sql ="select * from CONTRACTOR_SCHEDULE where  APPOINT_DATE BETWEEN
	'$fromdate'  AND '$todate'";
    $oraconn = OracleConnection();
    $apoint = oci_parse($oraconn, $sql);

    if(oci_execute($apoint))
    {
    return $apoint;
    }
    else
    {
        $err = oci_error($apoint);
        $e =  $err['message'];
        echo "<script type='text/javascript'>alert('$e')</script>";
    }
}


function appoint_repo_co($area,$fromdate,$todate,$con )
{

    $sql ="select * from CONTRACTOR_SCHEDULE where  AREA = '$area' and CONTRACTOR = '$con' and APPOINT_DATE BETWEEN
	'$fromdate'  AND '$todate'";
    $oraconn = OracleConnection();
    $apoint = oci_parse($oraconn, $sql);

    if(oci_execute($apoint))
    {
    return $apoint;
    }
    else
    {
        $err = oci_error($apoint);
        $e =  $err['message'];
        echo "<script type='text/javascript'>alert('$e')</script>";
    }
}

function search_appoint($contractor_name, $area)
{

    $sql ="select DISTINCT TEAM_NAME from CONTRACTOR_TEAMS WHERE AREA = '$area' order by TEAM_NAME";
    $oraconn = OracleConnection();
    $apoint = oci_parse($oraconn, $sql);

    if(oci_execute($apoint))
    {
	 //echo "<script type='text/javascript'>alert('$apoint')</script>";
    return $apoint;
    }
    else
    {
        $err = oci_error($apoint);
        $e =  $err['message'];
        echo "<script type='text/javascript'>alert('$e')</script>";
    }
}


function search_appoint_date($contractor_name, $date)
{

    $sql ="select *   from CONTRACTOR_SCHEDULE WHERE APPOINT_DATE = '$date' and CONTRACTOR = '$contractor_name' ";
    $oraconn = OracleConnection();
    $apoint = oci_parse($oraconn, $sql);

    if(oci_execute($apoint))
    {
	 //echo "<script type='text/javascript'>alert('$apoint')</script>";
    return $apoint;
    }
    else
    {
        $err = oci_error($apoint);
        $e =  $err['message'];
        echo "<script type='text/javascript'>alert('$e')</script>";
    }
}

function search_appoint1($contractor_name,$date, $area , $TEAM)
{

  /*  $sql ="select * from CONTRACTOR_SCHEDULE where CONTRACTOR = '$contractor_name'
                                and APPOINT_DATE = '$date' and AREA = '$area' AND CON_TEAM = '$TEAM'";
 */ 

 
 $sql = "select distinct AREA , CON_TEAM ,
(select  SO_ID||'-'||PSTN_NO
from CONTRACTOR_SCHEDULE where  TIME_SLOT = '8.30AM-10.30AM'
and CONTRACTOR = '$contractor_name'
and APPOINT_DATE = '$date' and AREA = '$area'  AND CON_TEAM =  '$TEAM') a,
(select SO_ID||'-'||PSTN_NO
from CONTRACTOR_SCHEDULE where  TIME_SLOT = '11.00AM-1.00PM'
and CONTRACTOR = '$contractor_name'
and APPOINT_DATE = '$date' and AREA = '$area'  AND CON_TEAM = '$TEAM')b,
(select SO_ID||'-'||PSTN_NO
from CONTRACTOR_SCHEDULE where  TIME_SLOT = '1.30PM-3.30PM'
and CONTRACTOR = '$contractor_name'
and APPOINT_DATE = '$date' and AREA = '$area'  AND CON_TEAM =  '$TEAM') c,
(select SO_ID||'-'||PSTN_NO
from CONTRACTOR_SCHEDULE where  TIME_SLOT = '4.00PM-6.00PM'
and CONTRACTOR = '$contractor_name'
and APPOINT_DATE = '$date' and AREA = '$area'  AND CON_TEAM =  '$TEAM')d
from CONTRACTOR_SCHEDULE where   CONTRACTOR = 'SIERRA'
and APPOINT_DATE = '$date' and AREA = '$area'  AND CON_TEAM =  '$TEAM'";



	$oraconn = OracleConnection();
    $apoint = oci_parse($oraconn, $sql);

    if(oci_execute($apoint))
    {
    return $apoint;
    }
    else
    {
        $err = oci_error($apoint);
        $e =  $err['message'];
        echo "<script type='text/javascript'>alert('$e')</script>";
    }
}


function teamsel($area,$con_slt)
{

    $sql ="select TEAM_NAME from CONTRACTOR_TEAMS where CONTRACTOR = '$con_slt' and AREA = '$area'";
    $oraconn = OracleConnection();
    $team = oci_parse($oraconn, $sql);
    if(oci_execute($team))
    {
    return $team;
    }
    else
    {
        $err = oci_error($team);
        $e =  $err['message'];
        echo "<script type='text/javascript'>alert('$e')</script>";
    }
}

function chkarea($con_slt)
{

    $sql ="select * from CONTRACTOR_SCHEDULE where CONTRACTOR = '$contractor_name'
		and APPOINT_DATE = (select TO_CHAR(SYSDATE, 'MM/DD/YYYY') from dual)";
    $oraconn = OracleConnection();
    $apoint = oci_parse($oraconn, $sql);

    if(oci_execute($apoint))
    {
    return $apoint;
    }
    else
    {
        $err = oci_error($apoint);
        $e =  $err['message'];
        echo "<script type='text/javascript'>alert('$e')</script>";
    }
}


function chkteam($area,$con_slt)
{

    $sql ="select count(*) from CONTRACTOR_TEAMS where CONTRACTOR = '$con_slt'
		and AREA = '$area'";
    $oraconn = OracleConnection();
    $apoint = oci_parse($oraconn, $sql);

    if(oci_execute($apoint))
    {
    return $apoint;
    }
    else
    {
        $err = oci_error($apoint);
        $e =  $err['message'];
        echo "<script type='text/javascript'>alert('$e')</script>";
    }
}

function getteam($area,$con_slt)
{

    $sql ="select * from CONTRACTOR_TEAMS where CONTRACTOR = '$con_slt'
		and AREA = '$area'";
    $oraconn = OracleConnection();
    $team = oci_parse($oraconn, $sql);

    if(oci_execute($team))
    {
    return $team;
    }
    else
    {
        $err = oci_error($team);
        $e =  $err['message'];
        echo "<script type='text/javascript'>alert('$e')</script>";
    }
}


function so_count_pen($area,$ser_type,$or_type)
{

    $sql ="select
				(select count(pr.CON_SERO_ID)  
				from CONTRACTOR_WORK_ORDERS pr
				where  (sysdate - pr.CON_DATE_TO_CONTRACTOR) <3    and  pr.CON_WORO_AREA = x.CON_WORO_AREA
				and  pr.CON_WORO_SERVICE_TYPE = '$ser_type' and pr.CON_WORO_ORDER_TYPE  ='$or_type' 
				and  pr.CON_STATUS IN ('ASSIGNED','INPROGRESS','REASSIGNED','DELAYED')),
				(select count(pr1.CON_SERO_ID)  
				from CONTRACTOR_WORK_ORDERS pr1
				where  (sysdate - pr1.CON_DATE_TO_CONTRACTOR) between 3 and 5  and  pr1.CON_WORO_AREA = x.CON_WORO_AREA
				and pr1.CON_WORO_SERVICE_TYPE =  '$ser_type' and pr1.CON_WORO_ORDER_TYPE  = '$or_type' 
				and pr1.CON_STATUS IN ('ASSIGNED','INPROGRESS','REASSIGNED','DELAYED')),
				(select count(pr2.CON_SERO_ID)  
				from CONTRACTOR_WORK_ORDERS pr2
				where  (sysdate - pr2.CON_DATE_TO_CONTRACTOR)  > 5 and  pr2.CON_WORO_AREA = x.CON_WORO_AREA
				and pr2.CON_WORO_SERVICE_TYPE = '$ser_type' and pr2.CON_WORO_ORDER_TYPE  = '$or_type'
				and pr2.CON_STATUS IN ('ASSIGNED','INPROGRESS','REASSIGNED','DELAYED'))
				from CONTRACTOR_WORK_ORDERS  x
				where x.CON_WORO_AREA = '$area'
				and x.CON_WORO_SERVICE_TYPE = '$ser_type'
				and X.CON_WORO_ORDER_TYPE = '$or_type'
				and X.CON_STATUS IN ('ASSIGNED','INPROGRESS','REASSIGNED','DELAYED')
				group by x.CON_WORO_AREA";
				
    $oraconn = OracleConnection();
    $area = oci_parse($oraconn, $sql);
    
    if(oci_execute($area))
    {    
    return $area;
    }
    else
    {
        $err = oci_error($area);
        $e =  $err['message'];
        echo "<script type='text/javascript'>alert('$e')</script>";
    }
}


function so_all_pen($area,$ser_type,$or_type)
{

    $sql ="select x.CON_SERO_ID ,x.CON_PSTN_NUMBER,x.CON_DATE_TO_CONTRACTOR,x.CON_STATUS,x.CON_NAME
				from CONTRACTOR_WORK_ORDERS  x
				where x.CON_WORO_AREA = '$area'
				and x.CON_WORO_SERVICE_TYPE = '$ser_type'
				and X.CON_WORO_ORDER_TYPE = '$or_type'
				and X.CON_STATUS IN ('ASSIGNED','INPROGRESS','REASSIGNED','DELAYED')
				order by x.CON_DATE_TO_CONTRACTOR";
	
    $oraconn = OracleConnection();
    $all = oci_parse($oraconn, $sql);
    
    if(oci_execute($all))
    {    
    return $all;
    }
    else
    {
        $err = oci_error($all);
        $e =  $err['message'];
        echo "<script type='text/javascript'>alert('$e')</script>";
    }
}

function so_all_twodays($area,$sertyp,$ortyp)
{			
	$sql ="select x.CON_SERO_ID ,x.CON_PSTN_NUMBER,x.CON_DATE_TO_CONTRACTOR,x.CON_STATUS,x.CON_NAME
				from CONTRACTOR_WORK_ORDERS  x
				where x.CON_WORO_AREA = '$area'
				and x.CON_WORO_SERVICE_TYPE = '$sertyp'
				and X.CON_WORO_ORDER_TYPE = '$ortyp'
				and X.CON_STATUS IN ('ASSIGNED','INPROGRESS','REASSIGNED','DELAYED')
				and (sysdate - x.CON_DATE_TO_CONTRACTOR) <3 
				order by x.CON_DATE_TO_CONTRACTOR";			
	
    $oraconn = OracleConnection();
    $all = oci_parse($oraconn, $sql);
    
    if(oci_execute($all))
    {    
    return $all;
    }
    else
    {
        $err = oci_error($all);
        $e =  $err['message'];
        echo "<script type='text/javascript'>alert('$e')</script>";
    }
}

function so_all_sixdays($area,$sertyp,$ortyp)
{
	$sql ="select x.CON_SERO_ID ,x.CON_PSTN_NUMBER,x.CON_DATE_TO_CONTRACTOR,x.CON_STATUS,x.CON_NAME
				from CONTRACTOR_WORK_ORDERS  x
				where x.CON_WORO_AREA = '$area'
				and x.CON_WORO_SERVICE_TYPE = '$sertyp'
				and X.CON_WORO_ORDER_TYPE = '$ortyp'
				and X.CON_STATUS IN ('ASSIGNED','INPROGRESS','REASSIGNED','DELAYED')
				and (sysdate - x.CON_DATE_TO_CONTRACTOR) between 3 and 5
				order by x.CON_DATE_TO_CONTRACTOR";	
	
    $oraconn = OracleConnection();
    $all = oci_parse($oraconn, $sql);
    
    if(oci_execute($all))
    {    
    return $all;
    }
    else
    {
        $err = oci_error($all);
        $e =  $err['message'];
        echo "<script type='text/javascript'>alert('$e')</script>";
    }
}

function so_all_moreseven($area,$sertyp,$ortyp)
{
	$sql ="select x.CON_SERO_ID ,x.CON_PSTN_NUMBER,x.CON_DATE_TO_CONTRACTOR,x.CON_STATUS,x.CON_NAME
				from CONTRACTOR_WORK_ORDERS  x
				where x.CON_WORO_AREA = '$area'
				and x.CON_WORO_SERVICE_TYPE = '$sertyp'
				and X.CON_WORO_ORDER_TYPE = '$ortyp'
				and X.CON_STATUS IN ('ASSIGNED','INPROGRESS','REASSIGNED','DELAYED')
				and (sysdate - x.CON_DATE_TO_CONTRACTOR) > 5
				order by x.CON_DATE_TO_CONTRACTOR";
    
	
    $oraconn = OracleConnection();
    $all = oci_parse($oraconn, $sql);
    
    if(oci_execute($all))
    {    
    return $all;
    }
    else
    {
        $err = oci_error($all);
        $e =  $err['message'];
        echo "<script type='text/javascript'>alert('$e')</script>";
    }
}



function getcircuit($pstn)
{

    $sql ="select a.CON_SO_ID,A.CON_SO_DATE_RECEIVED,A.CON_SO_STATUS_DATE,A.CON_SO_COM_DATE,A.CON_SO_RTN_DATE,A.CON_REASSIGNED_DATE,
B.CON_WORO_SERVICE_TYPE,B.CON_WORO_ORDER_TYPE,B.CON_CUS_NAME,B.CON_TEC_CONTACT,
C.CON_ADDE_STREETNUMBER||' '||' '||C.CON_ADDE_STRN_NAMEANDTYPE||' '||' '||C.CON_ADDE_SUBURB||' '||' '||C.CON_ADDE_CITY,
A.CON_CONTRACTOR,A.CON_APPROVE,A.CON_STATUS ,CON_OSP_DP_NAME||'-'||CON_OSP_DP_LOOP, b.DP_LOOP_COMMENT,a.CON_SO_STATUS
from CONTRACTOR_NEW_CON a,CONTRACTOR_WORK_ORDERS b ,CONTRACTOR_SERVICE_ADDRESS c,CONTRACTOR_OSP_DATA e
where a.CON_SO_ID = b.CON_SERO_ID
and a.CON_SO_ID = C.CON_ADDE_SERO_ID
and a.CON_SO_ID =e.CON_OSP_SERO_ID
and a.CON_CIRCUIT_NO = '$pstn'
union
select a.CON_SO_ID,A.CON_SO_DATE_RECEIVED,A.CON_SO_STATUS_DATE,A.CON_SO_COM_DATE,A.CON_SO_RTN_DATE,A.CON_REASSIGNED_DATE,
B.CON_WORO_SERVICE_TYPE,B.CON_WORO_ORDER_TYPE,B.CON_CUS_NAME,B.CON_TEC_CONTACT,
C.CON_ADDE_STREETNUMBER||' '||' '||C.CON_ADDE_STRN_NAMEANDTYPE||' '||' '||C.CON_ADDE_SUBURB||' '||' '||C.CON_ADDE_CITY,
A.CON_CONTRACTOR,A.CON_APPROVE,A.CON_STATUS, e.CON_EQ_LOC_NAME||'  '||e.CON_EQ_CARD||'-'||e.CON_EQ_PORT, b.DP_LOOP_COMMENT,a.CON_SO_STATUS
from CONTRACTOR_NEW_CON a,CONTRACTOR_WORK_ORDERS b ,CONTRACTOR_SERVICE_ADDRESS c,CONTRACTOR_EQ_DATA e
where a.CON_SO_ID = b.CON_SERO_ID
and a.CON_SO_ID = C.CON_ADDE_SERO_ID
and a.CON_SO_ID =e.CON_EQ_SERO_ID
and a.CON_CIRCUIT_NO = '$pstn'";

    $oraconn = OracleConnection();
    $team = oci_parse($oraconn, $sql);

    if(oci_execute($team))
    {
    return $team;
    }
    else
    {
        $err = oci_error($team);
        $e =  $err['message'];
        echo "<script type='text/javascript'>alert('$e')</script>";
    }
}

//=============================

function so_con_wait_all($user,$a)
{
    $sql= "select CON_CIRCUIT_NO,to_char(CON_SO_DATE_RECEIVED, 'mm/dd/yyyy hh:mi:ss AM'),
to_char(CON_SO_STATUS_DATE, 'mm/dd/yyyy hh:mi:ss AM'),to_char(CON_SO_COM_DATE, 'mm/dd/yyyy hh:mi:ss AM'),
CON_DLY_DAYS,CON_PENALTY,CON_SO_STATUS
from CONTRACTOR_NEW_CON
where CON_CONTRACTOR = '$user'
and CON_SO_STATUS ='CONFIRMATION'";
    
    $oraconn = OracleConnection();
    $con_comp= oci_parse($oraconn, $sql);
    if(oci_execute($con_comp))
    {
    return $con_comp;
    }
    else
    {
        $err = oci_error($con_comp);
        $e =  $err['message'];
        echo "<script type='text/javascript'>alert('$e')</script>";
    }
}

function so_con_wait_li($user,$a)
{
    $sql= "select CON_CIRCUIT_NO,to_char(CON_SO_DATE_RECEIVED, 'mm/dd/yyyy hh:mi:ss AM'),
to_char(CON_SO_STATUS_DATE, 'mm/dd/yyyy hh:mi:ss AM'),to_char(CON_SO_COM_DATE, 'mm/dd/yyyy hh:mi:ss AM'),
CON_DLY_DAYS,CON_PENALTY,CON_SO_STATUS
from CONTRACTOR_NEW_CON
where CON_CONTRACTOR = '$user'
and CON_AREA = '$a'
and CON_SO_STATUS ='CONFIRMATION'";
    
    $oraconn = OracleConnection();
    $con_comp= oci_parse($oraconn, $sql);
    if(oci_execute($con_comp))
    {
    return $con_comp;
    }
    else
    {
        $err = oci_error($con_comp);
        $e =  $err['message'];
        echo "<script type='text/javascript'>alert('$e')</script>";
    }
}

function so_con_wait_li2($user,$a,$a1)
{
    $sql= "select CON_CIRCUIT_NO,to_char(CON_SO_DATE_RECEIVED, 'mm/dd/yyyy hh:mi:ss AM'),
to_char(CON_SO_STATUS_DATE, 'mm/dd/yyyy hh:mi:ss AM'),to_char(CON_SO_COM_DATE, 'mm/dd/yyyy hh:mi:ss AM'),
CON_DLY_DAYS,CON_PENALTY,CON_SO_STATUS
from CONTRACTOR_NEW_CON
where CON_CONTRACTOR = '$user'
and CON_AREA IN ('$a','$a1')
and CON_SO_STATUS ='CONFIRMATION'";
    
    $oraconn = OracleConnection();
    $con_comp= oci_parse($oraconn, $sql);
    if(oci_execute($con_comp))
    {
    return $con_comp;
    }
    else
    {
        $err = oci_error($con_comp);
        $e =  $err['message'];
        echo "<script type='text/javascript'>alert('$e')</script>";
    }
}

function so_con_wait_li3($user,$a,$a1,$a2)
{
    $sql= "select CON_CIRCUIT_NO,to_char(CON_SO_DATE_RECEIVED, 'mm/dd/yyyy hh:mi:ss AM'),
to_char(CON_SO_STATUS_DATE, 'mm/dd/yyyy hh:mi:ss AM'),to_char(CON_SO_COM_DATE, 'mm/dd/yyyy hh:mi:ss AM'),
CON_DLY_DAYS,CON_PENALTY,CON_SO_STATUS
from CONTRACTOR_NEW_CON
where CON_CONTRACTOR = '$user'
and CON_AREA IN ('$a','$a1','$a2')
and CON_SO_STATUS ='CONFIRMATION'";
    
    $oraconn = OracleConnection();
    $con_comp= oci_parse($oraconn, $sql);
    if(oci_execute($con_comp))
    {
    return $con_comp;
    }
    else
    {
        $err = oci_error($con_comp);
        $e =  $err['message'];
        echo "<script type='text/javascript'>alert('$e')</script>";
    }
}


function so_con_wait_li4($user,$a,$a1,$a2,$a3,$a4)
{
    $sql= "select CON_CIRCUIT_NO,to_char(CON_SO_DATE_RECEIVED, 'mm/dd/yyyy hh:mi:ss AM'),
to_char(CON_SO_STATUS_DATE, 'mm/dd/yyyy hh:mi:ss AM'),to_char(CON_SO_COM_DATE, 'mm/dd/yyyy hh:mi:ss AM'),
CON_DLY_DAYS,CON_PENALTY,CON_SO_STATUS
from CONTRACTOR_NEW_CON
where CON_CONTRACTOR = '$user'
and CON_AREA IN ('$a','$a1','$a2','$a3','$a4')
and CON_SO_STATUS ='CONFIRMATION'";
    
    $oraconn = OracleConnection();
    $con_comp= oci_parse($oraconn, $sql);
    if(oci_execute($con_comp))
    {
    return $con_comp;
    }
    else
    {
        $err = oci_error($con_comp);
        $e =  $err['message'];
        echo "<script type='text/javascript'>alert('$e')</script>";
    }
}

function so_con_wait_li5($user,$a,$a1,$a2,$a3,$a4,$a5)
{
    $sql= "select CON_CIRCUIT_NO,to_char(CON_SO_DATE_RECEIVED, 'mm/dd/yyyy hh:mi:ss AM'),
to_char(CON_SO_STATUS_DATE, 'mm/dd/yyyy hh:mi:ss AM'),to_char(CON_SO_COM_DATE, 'mm/dd/yyyy hh:mi:ss AM'),
CON_DLY_DAYS,CON_PENALTY,CON_SO_STATUS
from CONTRACTOR_NEW_CON
where CON_CONTRACTOR = '$user'
and CON_AREA IN ('$a','$a1','$a2','$a3','$a4','$a5')
and CON_SO_STATUS ='CONFIRMATION'";
    
    $oraconn = OracleConnection();
    $con_comp= oci_parse($oraconn, $sql);
    if(oci_execute($con_comp))
    {
    return $con_comp;
    }
    else
    {
        $err = oci_error($con_comp);
        $e =  $err['message'];
        echo "<script type='text/javascript'>alert('$e')</script>";
    }
}

function so_con_wait_li6($user,$a,$a1,$a2,$a3,$a4,$a5,$a6)
{
    $sql= "select CON_CIRCUIT_NO,to_char(CON_SO_DATE_RECEIVED, 'mm/dd/yyyy hh:mi:ss AM'),
to_char(CON_SO_STATUS_DATE, 'mm/dd/yyyy hh:mi:ss AM'),to_char(CON_SO_COM_DATE, 'mm/dd/yyyy hh:mi:ss AM'),
CON_DLY_DAYS,CON_PENALTY,CON_SO_STATUS
from CONTRACTOR_NEW_CON
where CON_CONTRACTOR = '$user'
and CON_AREA IN ('$a','$a1','$a2','$a3','$a4','$a5','$a6')
and CON_SO_STATUS ='CONFIRMATION'";
    
    $oraconn = OracleConnection();
    $con_comp= oci_parse($oraconn, $sql);
    if(oci_execute($con_comp))
    {
    return $con_comp;
    }
    else
    {
        $err = oci_error($con_comp);
        $e =  $err['message'];
        echo "<script type='text/javascript'>alert('$e')</script>";
    }
}

function con_wait($area)
{
    $sql = "select a.CON_SO_ID,a.CON_CIRCUIT_NO,a.CON_NEW_CON_TYPE ,to_char(a.CON_SO_DATE_RECEIVED, 'mm/dd/yyyy hh:mi:ss AM'),to_char(a.CON_SO_COM_DATE, 'mm/dd/yyyy hh:mi:ss AM')
    ,b.CON_TEC_CONTACT,b.CON_NAME
from CONTRACTOR_NEW_CON a,CONTRACTOR_WORK_ORDERS b
where a.CON_SO_ID = b.CON_SERO_ID
and a.CON_SO_STATUS ='CONFIRMATION'
and a.CON_AREA = '$area'
order by a.CON_SO_ID";
    $oraconn = OracleConnection();
    $con_pen= oci_parse($oraconn, $sql);
    if(oci_execute($con_pen))
    {
    return $con_pen; 
    }
    else
    {
        $err = oci_error($con_pen);
        $e =  $err['message'];
        echo "<script type='text/javascript'>alert('$e')</script>";
    }
}


function wait_confirm($value,$user)
{
    $sql = "update CONTRACTOR_NEW_CON set CON_CONFIRM_DATE = sysdate,CON_CONFIRM_USER='{$user}',CON_SO_STATUS = 'COMPLETED'  where CON_SO_ID  = '{$value}'";
	
    $oraconn = OracleConnection();
    $con_wait= oci_parse($oraconn, $sql);
    if(oci_execute($con_wait))
    {
		$sql = "update  CONTRACTOR_WORK_ORDERS set CON_CONFIRM_DATE = sysdate,CON_CONFIRM_USER='{$user}', CON_STATUS = 'COMPLETED' where CON_SERO_ID = '{$value}'";

    $oraconn = OracleConnection();
    $com1 = oci_parse($oraconn, $sql);
   if(oci_execute($com1))
   {
    return $com1;
   }
   else
    {
        $err = oci_error($com1);
        $e =  $err['message'];
        echo "<script type='text/javascript'>alert('$e')</script>";
    }

    }
    else
    {
        $err = oci_error($con_wait);
        $e =  $err['message'];
        echo "<script type='text/javascript'>alert('$e')</script>";
    }

 	
}

function update_cir_inv($invoice_no,$con_name,$area,$sertype,$todate,$fromdate)
{
    if($sertype == 'IPTV'){
        
        $sql = "update CONTRACTOR_INV_PASSCHK
    set INV_NO = '$invoice_no' ,INV_FLAG = '1'
    where CON_SO_ID IN(
    select distinct a.CON_SO_ID 
    from CONTRACTOR_INV_PASSCHK a,CONTRACTOR_WORK_ORDERS b
    where A.CON_SO_ID = B.CON_SERO_ID
    and a.CON_SO_STATUS = 'COMPLETED' 
    and a.QC_STAT = 'PASSED'
    and CON_CONTRACTOR = '$con_name' 
    and a.CON_AREA = '$area' 
    and B.CON_WORO_SERVICE_TYPE IN ('E-IPTV COPPER','E-IPTV FTTH')
    and a.CON_SO_COM_DATE BETWEEN TO_DATE('$fromdate 11:59:59 PM','mm,dd,yyyy:hh:mi:ss pm') 
    and TO_DATE('$todate 11:59:59 PM','mm,dd,yyyy:hh:mi:ss pm')
    and a.INV_FLAG is null 
    and a.INV_NO is null )";
        
    }else{
        $sql = "update CONTRACTOR_INV_PASSCHK
    set INV_NO = '$invoice_no' ,INV_FLAG = '1'
    where CON_SO_ID IN(
    select distinct a.CON_SO_ID 
    from CONTRACTOR_INV_PASSCHK a,CONTRACTOR_WORK_ORDERS b
    where A.CON_SO_ID = B.CON_SERO_ID
    and a.CON_SO_STATUS = 'COMPLETED' 
    and a.QC_STAT = 'PASSED'
    and CON_CONTRACTOR = '$con_name' 
    and a.CON_AREA = '$area' 
    and B.CON_WORO_SERVICE_TYPE = '$sertype'
    and a.CON_SO_COM_DATE BETWEEN TO_DATE('$fromdate 11:59:59 PM','mm,dd,yyyy:hh:mi:ss pm') 
    and TO_DATE('$todate 11:59:59 PM','mm,dd,yyyy:hh:mi:ss pm')
    and a.INV_FLAG is null 
    and a.INV_NO is null )";
    }
    
    $oraconn = OracleConnection();
    $con_pen= oci_parse($oraconn, $sql);
    if(oci_execute($con_pen))
    {
    return 0; 
    }
    else
    {
        $err = oci_error($con_pen);
        $e =  $err['message'];
        echo "<script type='text/javascript'>alert('$e')</script>";
    }
}

function update_cir_inv2($invoice_no,$con_name,$area,$sertype,$todate,$fromdate)
{
    if($sertype == 'IPTV'){
        
        $sql = "update CONTRACTOR_INV_PASSED
    set INV_NO = '$invoice_no' ,INV_FLAG = '1'
    where CON_SO_ID IN(
    select distinct a.CON_SO_ID 
        from CONTRACTOR_INV_PASSED a,CONTRACTOR_WORK_ORDERS b
    where A.CON_SO_ID = B.CON_SERO_ID
    and a.CON_SO_STATUS = 'COMPLETED' 
    and CON_CONTRACTOR = '$con_name' 
    and a.CON_AREA = '$area' 
    and a.CON_STATUS = '2'
    and a.CON_APPROVE = 'YES'
    and B.CON_WORO_SERVICE_TYPE IN ('E-IPTV COPPER','E-IPTV FTTH')
    and a.CON_SO_COM_DATE BETWEEN TO_DATE('$fromdate 11:59:59 PM','mm,dd,yyyy:hh:mi:ss pm') 
    and TO_DATE('$todate 11:59:59 PM','mm,dd,yyyy:hh:mi:ss pm')
    and a.INV_FLAG is null 
    and a.INV_NO is null )";
        
    }else{
        
        $sql = "update CONTRACTOR_INV_PASSED
    set INV_NO = '$invoice_no' ,INV_FLAG = '1'
    where CON_SO_ID IN(
    select distinct a.CON_SO_ID 
        from CONTRACTOR_INV_PASSED a,CONTRACTOR_WORK_ORDERS b
    where A.CON_SO_ID = B.CON_SERO_ID
    and a.CON_SO_STATUS = 'COMPLETED' 
    and CON_CONTRACTOR = '$con_name' 
    and a.CON_AREA = '$area' 
    and a.CON_STATUS = '2'
    and a.CON_APPROVE = 'YES'
    and B.CON_WORO_SERVICE_TYPE = '$sertype'
    and a.CON_SO_COM_DATE BETWEEN TO_DATE('$fromdate 11:59:59 PM','mm,dd,yyyy:hh:mi:ss pm') 
    and TO_DATE('$todate 11:59:59 PM','mm,dd,yyyy:hh:mi:ss pm')
    and a.INV_FLAG is null 
    and a.INV_NO is null )";
    
    }
    
    $oraconn = OracleConnection();
    $con_pen= oci_parse($oraconn, $sql);
    if(oci_execute($con_pen))
    {
    return 0; 
    }
    else
    {
        $err = oci_error($con_pen);
        $e =  $err['message'];
        echo "<script type='text/javascript'>alert('$e')</script>";
    }
}

function meterial_0($a,$b,$c)
{
    $sql = "update CONTRACTOR_INVOICE_DATA0
			set $b = '$c'
			where INV_NO = '$a'";

    $oraconn = OracleConnection();
    $con_pen= oci_parse($oraconn, $sql);
    if(oci_execute($con_pen))
    {
    return 0; 
    }
    else
    {
        $err = oci_error($con_pen);
        $e =  $err['message'];
        echo "<script type='text/javascript'>alert('$e')</script>";
    }
}

function meterial_1($a,$b,$c)
{
    $sql = "update CONTRACTOR_INVOICE_DATA1
			set $b = '$c'
			where INV_NO = '$a'";

    $oraconn = OracleConnection();
    $con_pen= oci_parse($oraconn, $sql);
    if(oci_execute($con_pen))
    {
    return 0; 
    }
    else
    {
        $err = oci_error($con_pen);
        $e =  $err['message'];
        echo "<script type='text/javascript'>alert('$e')</script>";
    }
}

function meterial_2($a,$b,$c)
{
    $sql = "update CONTRACTOR_INVOICE_DATA2
			set $b = '$c'
			where INV_NO = '$a'";

    $oraconn = OracleConnection();
    $con_pen= oci_parse($oraconn, $sql);
    if(oci_execute($con_pen))
    {
    return 0; 
    }
    else
    {
        $err = oci_error($con_pen);
        $e =  $err['message'];
        echo "<script type='text/javascript'>alert('$e')</script>";
    }
}


function gross_amta($a,$b,$c,$d)
{
    $sql = "update CONTRACTOR_INVOICE
			set TOT_AMOUNT = '$b',AMOUNT_60 = '$c',VAT = '$d'
			where INVOICE_NO = '$a'";

    $oraconn = OracleConnection();
    $con_pen= oci_parse($oraconn, $sql);
    if(oci_execute($con_pen))
    {
    return 0; 
    }
    else
    {
        $err = oci_error($con_pen);
        $e =  $err['message'];
        echo "<script type='text/javascript'>alert('$e')</script>";
    }
}

function gross_amtb($a,$b,$c,$d,$e)
{
    $sql = "update CONTRACTOR_INVOICE
			set TOT_AMOUNT = '$b',AMOUNT_40 = '$c',PENALTY = '$d',VAT = '$e'
			where INVOICE_NO = '$a'";

    $oraconn = OracleConnection();
    $con_pen= oci_parse($oraconn, $sql);
    if(oci_execute($con_pen))
    {
    return 0; 
    }
    else
    {
        $err = oci_error($con_pen);
        $e =  $err['message'];
        echo "<script type='text/javascript'>alert('$e')</script>";
    }
}

function comp_count($area,$con_name,$sertype,$todate,$fromdate)
{
   if($sertype == 'IPTV'){
    $sql ="select distinct CON_SO_ID
    from(
    select distinct a.CON_SO_ID 
    from CONTRACTOR_INV_PASSCHK a,CONTRACTOR_WORK_ORDERS b
    where A.CON_SO_ID = B.CON_SERO_ID
    and a.CON_SO_STATUS = 'COMPLETED' 
    and a.QC_STAT = 'PASSED'
    and CON_CONTRACTOR = '$con_name' 
    and a.CON_AREA = '$area' 
    and B.CON_WORO_SERVICE_TYPE IN ('E-IPTV COPPER','E-IPTV FTTH')
    and a.CON_SO_COM_DATE BETWEEN TO_DATE('$fromdate 11:59:59 PM','mm,dd,yyyy:hh:mi:ss pm') 
    and TO_DATE('$todate 11:59:59 PM','mm,dd,yyyy:hh:mi:ss pm')
    and a.INV_FLAG is null 
    and a.INV_NO is null 
    union all
    select distinct a.CON_SO_ID 
    from CONTRACTOR_INV_PASSED a,CONTRACTOR_WORK_ORDERS b
    where A.CON_SO_ID = B.CON_SERO_ID
    and a.CON_SO_STATUS = 'COMPLETED' 
    and a.CON_STATUS = '2'
    and a.CON_APPROVE = 'YES'
    and CON_CONTRACTOR = '$con_name' 
    and a.CON_AREA = '$area' 
    and B.CON_WORO_SERVICE_TYPE IN ('E-IPTV COPPER','E-IPTV FTTH')
    and a.CON_SO_COM_DATE BETWEEN TO_DATE('$fromdate 11:59:59 PM','mm,dd,yyyy:hh:mi:ss pm') 
    and TO_DATE('$todate 11:59:59 PM','mm,dd,yyyy:hh:mi:ss pm')
    and a.INV_FLAG is null 
    and a.INV_NO is null)";
    
   }else{
    
        $sql ="select distinct CON_SO_ID
    from(
    select distinct a.CON_SO_ID 
    from CONTRACTOR_INV_PASSCHK a,CONTRACTOR_WORK_ORDERS b
    where A.CON_SO_ID = B.CON_SERO_ID
    and a.CON_SO_STATUS = 'COMPLETED' 
    and a.QC_STAT = 'PASSED'
    and CON_CONTRACTOR = '$con_name' 
    and a.CON_AREA = '$area' 
    and B.CON_WORO_SERVICE_TYPE = '$sertype'
    and a.CON_SO_COM_DATE BETWEEN TO_DATE('$fromdate 11:59:59 PM','mm,dd,yyyy:hh:mi:ss pm') 
    and TO_DATE('$todate 11:59:59 PM','mm,dd,yyyy:hh:mi:ss pm')
    and a.INV_FLAG is null 
    and a.INV_NO is null 
    union all
    select distinct a.CON_SO_ID 
    from CONTRACTOR_INV_PASSED a,CONTRACTOR_WORK_ORDERS b
    where A.CON_SO_ID = B.CON_SERO_ID
    and a.CON_SO_STATUS = 'COMPLETED' 
    and a.CON_STATUS = '2'
    and a.CON_APPROVE = 'YES'
    and CON_CONTRACTOR = '$con_name' 
    and a.CON_AREA = '$area' 
    and B.CON_WORO_SERVICE_TYPE = '$sertype'
    and a.CON_SO_COM_DATE BETWEEN TO_DATE('$fromdate 11:59:59 PM','mm,dd,yyyy:hh:mi:ss pm') 
    and TO_DATE('$todate 11:59:59 PM','mm,dd,yyyy:hh:mi:ss pm')
    and a.INV_FLAG is null 
    and a.INV_NO is null)";
      
   }

    $oraconn = OracleConnection();
    $com_list = oci_parse($oraconn, $sql);
    if(oci_execute($com_list))
    {
     return $com_list;
    }
    else
    {
        $err = oci_error($com_list);
        $e =  $err['message'];
        echo "<script type='text/javascript'>alert('$e')</script>";
    }
    
}


function get_invoice($inv)
{
    $sql = "select * from CONTRACTOR_INVOICE_DATA where INVOICE_NO = '$inv'";
    $oraconn = OracleConnection();
    $inv_detail= oci_parse($oraconn, $sql);
    if(oci_execute($inv_detail))
    {
    return $inv_detail; 
    }
    else
    {
        $err = oci_error($inv_detail);
        $e =  $err['message'];
        echo "<script type='text/javascript'>alert('$e')</script>";
    }
}

function get_met($inv)
{
    $sql = "select * from CONTRACTOR_INVOICE_DATA0 where INV_NO = '$inv'";
    $oraconn = OracleConnection();
    $inv_detail= oci_parse($oraconn, $sql);
    if(oci_execute($inv_detail))
    {
    return $inv_detail; 
    }
    else
    {
        $err = oci_error($inv_detail);
        $e =  $err['message'];
        echo "<script type='text/javascript'>alert('$e')</script>";
    }
}

function get_mets($inv)
{
    $sql = "select * from CONTRACTOR_INVOICE_DATA1 where INV_NO = '$inv'";
    $oraconn = OracleConnection();
    $inv_detail= oci_parse($oraconn, $sql);
    if(oci_execute($inv_detail))
    {
    return $inv_detail; 
    }
    else
    {
        $err = oci_error($inv_detail);
        $e =  $err['message'];
        echo "<script type='text/javascript'>alert('$e')</script>";
    }
}

function get_meta($inv)
{
    $sql = "select * from CONTRACTOR_INVOICE_DATA2 where INV_NO = '$inv'";
    $oraconn = OracleConnection();
    $inv_detail= oci_parse($oraconn, $sql);
    if(oci_execute($inv_detail))
    {
    return $inv_detail; 
    }
    else
    {
        $err = oci_error($inv_detail);
        $e =  $err['message'];
        echo "<script type='text/javascript'>alert('$e')</script>";
    }
}

function concount($invid)
{
    $sql = "SELECT count(*) FROM CONTRACTOR_NEW_CON where INV_NO = '$invid' ";

    $oraconn = OracleConnection();
    $inv_list = oci_parse($oraconn, $sql);
    if(oci_execute($inv_list))
    {
    $row =oci_fetch_array($inv_list);
	return $row [0];
    }
    else
    {
        $err = oci_error($inv_list);
        $e =  $err['message'];
        echo "<script type='text/javascript'>alert('$e')</script>";
    }
}

function eq_data($so_id)
{
    $sql = "select * from CONTRACTOR_EQ_DATA where CON_EQ_SERO_ID = '$so_id'";
    $oraconn = OracleConnection();
    $sta = oci_parse($oraconn, $sql);
    if(oci_execute($sta))
    {
    return $sta;
    }
    else
    {
        $err = oci_error($sta);
        $e =  $err['message'];
        echo "<script type='text/javascript'>alert('$e')</script>";
    }
}

function ftth_data($so_id)
{
    $sql = "select * from CONTRACTOR_FTTH_DATA where CON_FTTH_SERO_ID = '$so_id'";
    $oraconn = OracleConnection();
    $sta = oci_parse($oraconn, $sql);
    if(oci_execute($sta))
    {
    return $sta;
    }
    else
    {
        $err = oci_error($sta);
        $e =  $err['message'];
        echo "<script type='text/javascript'>alert('$e')</script>";
    }
}
function so_iptv_update($so_id,$days,$penalty,$delday)
{
    
    $sql = "update  CONTRACTOR_NEW_CON set CON_SO_COM_DATE = sysdate, CON_SO_STATUS = 'COMPLETED', CON_DLY_DAYS = '{$days}',
            CON_PENALTY = '{$penalty}', CON_NEW_CON_TYPE = 'E-IPTV COPPER', CON_STATUS = '2' ,DELAY_DATE= '{$delday}' where CON_SO_ID = '{$so_id}' ";

    $oraconn = OracleConnection();
    $com = oci_parse($oraconn, $sql);
   if( oci_execute($com))
   {       
    //$sql = "update  CONTRACTOR_WORK_ORDERS set CON_STATUS_DATE = TO_DATE('$date','mm,dd,yyyy:hh:mi:ss pm'), CON_STATUS = 'COMPLETED' where CON_SERO_ID = '{$so_id}'";
    $sql = "update  CONTRACTOR_WORK_ORDERS set CON_STATUS_DATE = sysdate, CON_STATUS = 'COMPLETED' where CON_SERO_ID = '{$so_id}'";

    $oraconn = OracleConnection();
    $com1 = oci_parse($oraconn, $sql);
   if(oci_execute($com1))
   {
    return $com1;
   }
   else
    {
        $err = oci_error($com1);
        $e =  $err['message'];
        echo "<script type='text/javascript'>alert('$e')</script>";
    }
 }
   else
    {
        $err = oci_error($com);
        $e =  $err['message'];
        echo "<script type='text/javascript'>alert('$e')</script>";
    }
}

function iptv_data($so_id,$setsereial,$setmanufac,$setmodel,$setwarrent,$iptvhw,$iptvhwser)
{
    $sql = "INSERT INTO CONTRACTOR_IPTV_DATA VALUES ( '$so_id',
			'$setsereial','$setmanufac','$setmodel','$setwarrent','$iptvhw','$iptvhwser')";
 
    $oraconn = OracleConnection();
    $sta = oci_parse($oraconn, $sql);
    if(oci_execute($sta))
    {
    return $sta;
    }
    else
    {
        $err = oci_error($sta);
        $e =  $err['message'];
        echo "<script type='text/javascript'>alert('$e')</script>";
    }
}

function nc_count($invoice_no)
{
    $sql = "select a.* 
            from CONTRACTOR_NEW_CON a , CONTRACTOR_WORK_ORDERS b
            where a.CON_SO_ID = B.CON_SERO_ID
            and B.CON_WORO_ORDER_TYPE = 'CREATE'
            and a.INV_NO = '$invoice_no'";
 
    $oraconn = OracleConnection();
    $sta = oci_parse($oraconn, $sql);
    if(oci_execute($sta))
    {
    return $sta;
    }
    else
    {
        $err = oci_error($sta);
        $e =  $err['message'];
        echo "<script type='text/javascript'>alert('$e')</script>";
    }
}

function rm_count($invoice_no)
{
    $sql = "select a.* 
            from CONTRACTOR_NEW_CON a , CONTRACTOR_WORK_ORDERS b
            where a.CON_SO_ID = B.CON_SERO_ID
            and B.CON_WORO_ORDER_TYPE IN ('MODIFY-LOCATION','MODIFY-LOC SAMEDP','CREATE-OR')
            and a.INV_NO = '$invoice_no'";
 
    $oraconn = OracleConnection();
    $sta = oci_parse($oraconn, $sql);
    if(oci_execute($sta))
    {
    return $sta;
    }
    else
    {
        $err = oci_error($sta);
        $e =  $err['message'];
        echo "<script type='text/javascript'>alert('$e')</script>";
    }
}

function rc_count($invoice_no)
{
    $sql = "select a.* 
            from CONTRACTOR_NEW_CON a , CONTRACTOR_WORK_ORDERS b
            where a.CON_SO_ID = B.CON_SERO_ID
            and B.CON_WORO_ORDER_TYPE = 'CREATE-RECON'
            and a.INV_NO = '$invoice_no'";
 
    $oraconn = OracleConnection();
    $sta = oci_parse($oraconn, $sql);
    if(oci_execute($sta))
    {
    return $sta;
    }
    else
    {
        $err = oci_error($sta);
        $e =  $err['message'];
        echo "<script type='text/javascript'>alert('$e')</script>";
    }
}

function getmet($invno)
{
    $sql = "select UNIT_DESIG, sum(P0),sum(P1)
            from (
            select  distinct SOID, VOICENO,UNIT_DESIG, P0,P1,SN
            from CONTRACTOR_INV_MET
            where SOID IN (select distinct a.CON_SO_ID 
                from CONTRACTOR_INV_PASSCHK a
                where INV_NO = '$invno'  
            union all
            select distinct a.CON_SO_ID 
                from CONTRACTOR_INV_PASSED a
                where INV_NO = '$invno' ) )
            group by UNIT_DESIG ";
     

    $oraconn = OracleConnection();
    $inv = oci_parse($oraconn, $sql);
    if(oci_execute($inv))
    {
    return $inv;
    }
    else
    {
        $err = oci_error($inv);
        $e =  $err['message'];
        echo "<script type='text/javascript'>alert('$e')</script>";
    }
}

function metnc($invno)
{
    $sql = "select sum(cm.C_HOOK),sum(cm.L_HOOK),sum(cm.RETAINERS),sum(cm.DROP_WIRE),sum(cm.INTERNAL_WIRE),sum(cm.DISCHARGER),sum(cm.EARTH_ROD),
    sum(cm.EARTH_WIRE),sum(cm.TELEPHONE),sum(cm.CAT5),sum(cm.IPTV_N),sum(cm.NC_BB_PEO),sum(cm.PVC_CONDUIT),sum(cm.POLE_STRUT),
    sum(cm.SPUN_POLE_5_6_SLT),sum(cm.SPUN_POLE_6_7_SLT),sum(cm.SPUN_POLE_5_6),sum(cm.SPUN_POLE_6_7),sum(cm.SPUN_POLE_5_6_CON),
    sum(cm.SPUN_POLE_6_7_CON),sum(cm.POLES_5_6_L),sum(cm.POLES_5_6_H),sum(cm.POLES_5_6_CE),sum(cm.POLES_5_6_CON),sum(cm.POLES_5_6_CON_CE),
    sum(cm.POLES_6_7),sum(cm.POLES_6_7_CON),sum(cm.POLES_6_7_CE),sum(cm.POLES_6_7_CON_CE),sum(cm.POLES_7_5),sum(cm.POLES_7_5_CON),
    sum(cm.POLES_8),sum(cm.POLES_8_CON),sum(cm.POLES_9_CON),sum(cm.POLES_9),sum(cm.POLES_S_CON),sum (cm.GUY),
    sum(csm.C_HOOK_SER),sum(csm.L_HOOK_SER),sum(csm.RETAINERS_SER),sum(csm.DROP_WIRE_SER),sum(csm.INTERNAL_WIRE_SER),sum(csm.DISCHARGER_SER),
    sum(csm.EARTH_ROD_SER),sum(csm.EARTH_WIRE_SER),sum(csm.TELEPHONE_SER),sum(csm.CAT5_SER),sum(csm.IPTV_N_SER),sum(csm.NC_BB_PEO_SER),
    sum(csm.PVC_CONDUIT_SER),sum(csm.POLE_STRUT_SER),sum(csm.SPUN_POLE_5_6_SLT_SER),sum(csm.SPUN_POLE_6_7_SLT_SER),sum(csm.SPUN_POLE_5_6_SER),
    sum(csm.SPUN_POLE_6_7_SER),sum(csm.SPUN_POLE_5_6_CON_SER),sum(csm.SPUN_POLE_6_7_CON_SER),sum(csm.POLES_5_6_L_SER),sum(csm.POLES_5_6_H_SER),
    sum(csm.POLES_5_6_CE_SER),sum(csm.POLES_5_6_CON_SER),sum(csm.POLES_5_6_CON_CE_SER),sum(csm.POLES_6_7_SER),sum(csm.POLES_6_7_CON_SER),
    sum(csm.POLES_6_7_CE_SER),sum(csm.POLES_6_7_CON_CE_SER),sum(csm.POLES_7_5_SER),sum(csm.POLES_7_5_CON_SER),
    sum(csm.POLES_8_SER),sum(csm.POLES_8_CON_SER),sum(csm.POLES_9_CON_SER),sum(csm.POLES_9_SER),sum(csm.POLES_S_CON_SER),sum (csm.GUY_SER),
    sum(cam.C_HOOK_ACC),sum(cam.L_HOOK_ACC),sum(cam.RETAINERS_ACC),sum(cam.DROP_WIRE_ACC),sum(cam.INTERNAL_WIRE_ACC),sum(cam.DISCHARGER_ACC),
    sum(cam.EARTH_ROD_ACC),sum(cam.EARTH_WIRE_ACC),sum(cam.TELEPHONE_ACC),sum(cam.CAT5_ACC),sum(cam.IPTV_N_ACC),sum(cam.NC_BB_PEO_ACC),
    sum(cam.PVC_CONDUIT_ACC),sum(cam.POLE_STRUT_ACC),sum(cam.SPUN_POLE_5_6_SLT_ACC),sum(cam.SPUN_POLE_6_7_SLT_ACC),sum(cam.SPUN_POLE_5_6_ACC),
    sum(cam.SPUN_POLE_6_7_ACC),sum(cam.SPUN_POLE_5_6_CON_ACC),sum(cam.SPUN_POLE_6_7_CON_ACC),sum(cam.POLES_5_6_L_ACC),sum(cam.POLES_5_6_H_ACC),
    sum(cam.POLES_5_6_CE_ACC),sum(cam.POLES_5_6_CON_ACC),sum(cam.POLES_5_6_CON_CE_ACC),sum(cam.POLES_6_7_ACC),sum(cam.POLES_6_7_CON_ACC),
    sum(cam.POLES_6_7_CE_ACC),sum(cam.POLES_6_7_CON_CE_ACC),sum(cam.POLES_7_5_ACC),sum(cam.POLES_7_5_CON_ACC),sum(cam.POLES_8_ACC),
    sum(cam.POLES_8_CON_ACC),sum(cam.POLES_9_CON_ACC),sum(cam.POLES_9_ACC),sum(cam.POLES_S_CON_ACC),sum (cam.GUY_ACC)
    from CONTRACTOR_MERETIAL cm,CONTRACTOR_MERETIAL_SER csm,CONTRACTOR_MERETIAL_ACC cam
    where CM.MET_SO_ID =  CSM.MET_SO_ID
    AND CSM.MET_SO_ID = CAM.MET_SO_ID
    AND  cm.MET_SO_ID IN (
    select a.CON_SO_ID from CONTRACTOR_NEW_CON a,CONTRACTOR_WORK_ORDERS b
    where A.CON_SO_ID = B.CON_SERO_ID
    and a.INV_NO = '$invno'
    and B.CON_WORO_ORDER_TYPE = 'CREATE')";
 
    
    $oraconn = OracleConnection();
    $inv = oci_parse($oraconn, $sql);
    if(oci_execute($inv))
    {
    return $inv;
    }
    else
    {
        $err = oci_error($inv);
        $e =  $err['message'];
        echo "<script type='text/javascript'>alert('$e')</script>";
    }
}

function metrm($invno)
{
    $sql = "select sum(cm.C_HOOK),sum(cm.L_HOOK),sum(cm.RETAINERS),sum(cm.DROP_WIRE),sum(cm.INTERNAL_WIRE),sum(cm.DISCHARGER),sum(cm.EARTH_ROD),
    sum(cm.EARTH_WIRE),sum(cm.TELEPHONE),sum(cm.CAT5),sum(cm.IPTV_N),sum(cm.NC_BB_PEO),sum(cm.PVC_CONDUIT),sum(cm.POLE_STRUT),
    sum(cm.SPUN_POLE_5_6_SLT),sum(cm.SPUN_POLE_6_7_SLT),sum(cm.SPUN_POLE_5_6),sum(cm.SPUN_POLE_6_7),sum(cm.SPUN_POLE_5_6_CON),
    sum(cm.SPUN_POLE_6_7_CON),sum(cm.POLES_5_6_L),sum(cm.POLES_5_6_H),sum(cm.POLES_5_6_CE),sum(cm.POLES_5_6_CON),sum(cm.POLES_5_6_CON_CE),
    sum(cm.POLES_6_7),sum(cm.POLES_6_7_CON),sum(cm.POLES_6_7_CE),sum(cm.POLES_6_7_CON_CE),sum(cm.POLES_7_5),sum(cm.POLES_7_5_CON),
    sum(cm.POLES_8),sum(cm.POLES_8_CON),sum(cm.POLES_9_CON),sum(cm.POLES_9),sum(cm.POLES_S_CON),sum (cm.GUY),
    sum(csm.C_HOOK_SER),sum(csm.L_HOOK_SER),sum(csm.RETAINERS_SER),sum(csm.DROP_WIRE_SER),sum(csm.INTERNAL_WIRE_SER),sum(csm.DISCHARGER_SER),
    sum(csm.EARTH_ROD_SER),sum(csm.EARTH_WIRE_SER),sum(csm.TELEPHONE_SER),sum(csm.CAT5_SER),sum(csm.IPTV_N_SER),sum(csm.NC_BB_PEO_SER),
    sum(csm.PVC_CONDUIT_SER),sum(csm.POLE_STRUT_SER),sum(csm.SPUN_POLE_5_6_SLT_SER),sum(csm.SPUN_POLE_6_7_SLT_SER),sum(csm.SPUN_POLE_5_6_SER),
    sum(csm.SPUN_POLE_6_7_SER),sum(csm.SPUN_POLE_5_6_CON_SER),sum(csm.SPUN_POLE_6_7_CON_SER),sum(csm.POLES_5_6_L_SER),sum(csm.POLES_5_6_H_SER),
    sum(csm.POLES_5_6_CE_SER),sum(csm.POLES_5_6_CON_SER),sum(csm.POLES_5_6_CON_CE_SER),sum(csm.POLES_6_7_SER),sum(csm.POLES_6_7_CON_SER),
    sum(csm.POLES_6_7_CE_SER),sum(csm.POLES_6_7_CON_CE_SER),sum(csm.POLES_7_5_SER),sum(csm.POLES_7_5_CON_SER),
    sum(csm.POLES_8_SER),sum(csm.POLES_8_CON_SER),sum(csm.POLES_9_CON_SER),sum(csm.POLES_9_SER),sum(csm.POLES_S_CON_SER),sum (csm.GUY_SER),
    sum(cam.C_HOOK_ACC),sum(cam.L_HOOK_ACC),sum(cam.RETAINERS_ACC),sum(cam.DROP_WIRE_ACC),sum(cam.INTERNAL_WIRE_ACC),sum(cam.DISCHARGER_ACC),
    sum(cam.EARTH_ROD_ACC),sum(cam.EARTH_WIRE_ACC),sum(cam.TELEPHONE_ACC),sum(cam.CAT5_ACC),sum(cam.IPTV_N_ACC),sum(cam.NC_BB_PEO_ACC),
    sum(cam.PVC_CONDUIT_ACC),sum(cam.POLE_STRUT_ACC),sum(cam.SPUN_POLE_5_6_SLT_ACC),sum(cam.SPUN_POLE_6_7_SLT_ACC),sum(cam.SPUN_POLE_5_6_ACC),
    sum(cam.SPUN_POLE_6_7_ACC),sum(cam.SPUN_POLE_5_6_CON_ACC),sum(cam.SPUN_POLE_6_7_CON_ACC),sum(cam.POLES_5_6_L_ACC),sum(cam.POLES_5_6_H_ACC),
    sum(cam.POLES_5_6_CE_ACC),sum(cam.POLES_5_6_CON_ACC),sum(cam.POLES_5_6_CON_CE_ACC),sum(cam.POLES_6_7_ACC),sum(cam.POLES_6_7_CON_ACC),
    sum(cam.POLES_6_7_CE_ACC),sum(cam.POLES_6_7_CON_CE_ACC),sum(cam.POLES_7_5_ACC),sum(cam.POLES_7_5_CON_ACC),sum(cam.POLES_8_ACC),
    sum(cam.POLES_8_CON_ACC),sum(cam.POLES_9_CON_ACC),sum(cam.POLES_9_ACC),sum(cam.POLES_S_CON_ACC),sum (cam.GUY_ACC)
    from CONTRACTOR_MERETIAL cm,CONTRACTOR_MERETIAL_SER csm,CONTRACTOR_MERETIAL_ACC cam
    where CM.MET_SO_ID =  CSM.MET_SO_ID
    AND CSM.MET_SO_ID = CAM.MET_SO_ID
    AND  cm.MET_SO_ID IN (
    select a.CON_SO_ID from CONTRACTOR_NEW_CON a,CONTRACTOR_WORK_ORDERS b
    where A.CON_SO_ID = B.CON_SERO_ID
    and a.INV_NO = '$invno'
    and B.CON_WORO_ORDER_TYPE IN ('MODIFY-LOCATION','MODIFY-LOC SAMEDP','CREATE-OR'))";
     
     
    $oraconn = OracleConnection();
    $inv = oci_parse($oraconn, $sql);
    if(oci_execute($inv))
    {
    return $inv;
    }
    else
    {
        $err = oci_error($inv);
        $e =  $err['message'];
        echo "<script type='text/javascript'>alert('$e')</script>";
    }
}

function metrc($invno)
{
    $sql = " select sum(cm.C_HOOK),sum(cm.L_HOOK),sum(cm.RETAINERS),sum(cm.DROP_WIRE),sum(cm.INTERNAL_WIRE),sum(cm.DISCHARGER),sum(cm.EARTH_ROD),
    sum(cm.EARTH_WIRE),sum(cm.TELEPHONE),sum(cm.CAT5),sum(cm.IPTV_N),sum(cm.NC_BB_PEO),sum(cm.PVC_CONDUIT),sum(cm.POLE_STRUT),
    sum(cm.SPUN_POLE_5_6_SLT),sum(cm.SPUN_POLE_6_7_SLT),sum(cm.SPUN_POLE_5_6),sum(cm.SPUN_POLE_6_7),sum(cm.SPUN_POLE_5_6_CON),
    sum(cm.SPUN_POLE_6_7_CON),sum(cm.POLES_5_6_L),sum(cm.POLES_5_6_H),sum(cm.POLES_5_6_CE),sum(cm.POLES_5_6_CON),sum(cm.POLES_5_6_CON_CE),
    sum(cm.POLES_6_7),sum(cm.POLES_6_7_CON),sum(cm.POLES_6_7_CE),sum(cm.POLES_6_7_CON_CE),sum(cm.POLES_7_5),sum(cm.POLES_7_5_CON),
    sum(cm.POLES_8),sum(cm.POLES_8_CON),sum(cm.POLES_9_CON),sum(cm.POLES_9),sum(cm.POLES_S_CON),sum (cm.GUY),
    sum(csm.C_HOOK_SER),sum(csm.L_HOOK_SER),sum(csm.RETAINERS_SER),sum(csm.DROP_WIRE_SER),sum(csm.INTERNAL_WIRE_SER),sum(csm.DISCHARGER_SER),
    sum(csm.EARTH_ROD_SER),sum(csm.EARTH_WIRE_SER),sum(csm.TELEPHONE_SER),sum(csm.CAT5_SER),sum(csm.IPTV_N_SER),sum(csm.NC_BB_PEO_SER),
    sum(csm.PVC_CONDUIT_SER),sum(csm.POLE_STRUT_SER),sum(csm.SPUN_POLE_5_6_SLT_SER),sum(csm.SPUN_POLE_6_7_SLT_SER),sum(csm.SPUN_POLE_5_6_SER),
    sum(csm.SPUN_POLE_6_7_SER),sum(csm.SPUN_POLE_5_6_CON_SER),sum(csm.SPUN_POLE_6_7_CON_SER),sum(csm.POLES_5_6_L_SER),sum(csm.POLES_5_6_H_SER),
    sum(csm.POLES_5_6_CE_SER),sum(csm.POLES_5_6_CON_SER),sum(csm.POLES_5_6_CON_CE_SER),sum(csm.POLES_6_7_SER),sum(csm.POLES_6_7_CON_SER),
    sum(csm.POLES_6_7_CE_SER),sum(csm.POLES_6_7_CON_CE_SER),sum(csm.POLES_7_5_SER),sum(csm.POLES_7_5_CON_SER),
    sum(csm.POLES_8_SER),sum(csm.POLES_8_CON_SER),sum(csm.POLES_9_CON_SER),sum(csm.POLES_9_SER),sum(csm.POLES_S_CON_SER),sum (csm.GUY_SER),
    sum(cam.C_HOOK_ACC),sum(cam.L_HOOK_ACC),sum(cam.RETAINERS_ACC),sum(cam.DROP_WIRE_ACC),sum(cam.INTERNAL_WIRE_ACC),sum(cam.DISCHARGER_ACC),
    sum(cam.EARTH_ROD_ACC),sum(cam.EARTH_WIRE_ACC),sum(cam.TELEPHONE_ACC),sum(cam.CAT5_ACC),sum(cam.IPTV_N_ACC),sum(cam.NC_BB_PEO_ACC),
    sum(cam.PVC_CONDUIT_ACC),sum(cam.POLE_STRUT_ACC),sum(cam.SPUN_POLE_5_6_SLT_ACC),sum(cam.SPUN_POLE_6_7_SLT_ACC),sum(cam.SPUN_POLE_5_6_ACC),
    sum(cam.SPUN_POLE_6_7_ACC),sum(cam.SPUN_POLE_5_6_CON_ACC),sum(cam.SPUN_POLE_6_7_CON_ACC),sum(cam.POLES_5_6_L_ACC),sum(cam.POLES_5_6_H_ACC),
    sum(cam.POLES_5_6_CE_ACC),sum(cam.POLES_5_6_CON_ACC),sum(cam.POLES_5_6_CON_CE_ACC),sum(cam.POLES_6_7_ACC),sum(cam.POLES_6_7_CON_ACC),
    sum(cam.POLES_6_7_CE_ACC),sum(cam.POLES_6_7_CON_CE_ACC),sum(cam.POLES_7_5_ACC),sum(cam.POLES_7_5_CON_ACC),sum(cam.POLES_8_ACC),
    sum(cam.POLES_8_CON_ACC),sum(cam.POLES_9_CON_ACC),sum(cam.POLES_9_ACC),sum(cam.POLES_S_CON_ACC),sum (cam.GUY_ACC)
    from CONTRACTOR_MERETIAL cm,CONTRACTOR_MERETIAL_SER csm,CONTRACTOR_MERETIAL_ACC cam
    where CM.MET_SO_ID =  CSM.MET_SO_ID
    AND CSM.MET_SO_ID = CAM.MET_SO_ID
    AND  cm.MET_SO_ID IN (
    select a.CON_SO_ID from CONTRACTOR_NEW_CON a,CONTRACTOR_WORK_ORDERS b
    where A.CON_SO_ID = B.CON_SERO_ID
    and a.INV_NO = '$invno'
    and B.CON_WORO_ORDER_TYPE = 'CREATE-RECON')";

    
    $oraconn = OracleConnection();
    $inv = oci_parse($oraconn, $sql);
    if(oci_execute($inv))
    {
    return $inv;
    }
    else
    {
        $err = oci_error($inv);
        $e =  $err['message'];
        echo "<script type='text/javascript'>alert('$e')</script>";
    }
}

function chkqulity()
{
    $sql = "select distinct INV_NO from CONTRACTOR_NEW_CON where CON_STATUS = '5'";
 
    $oraconn = OracleConnection();
    $sta = oci_parse($oraconn, $sql);
    if(oci_execute($sta))
    {
    return $sta;
    }
    else
    {
        $err = oci_error($sta);
        $e =  $err['message'];
        echo "<script type='text/javascript'>alert('$e')</script>";
    }
}

function rechkqulity()
{
    $sql = "select distinct INV_NO from CONTRACTOR_NEW_CON where CON_STATUS = '8'";

    $oraconn = OracleConnection();
    $sta = oci_parse($oraconn, $sql);
    if(oci_execute($sta))
    {
    return $sta;
    }
    else
    {
        $err = oci_error($sta);
        $e =  $err['message'];
        echo "<script type='text/javascript'>alert('$e')</script>";
    }
}
function getquality($inv)
{
    $sql = "select distinct nc.CON_SO_ID ,nc.CON_CIRCUIT_NO,nc.CON_CONTRACTOR,
            OD.CON_OSP_DP_NAME,OD.CON_OSP_DP_LOOP ,SA.CON_ADDE_STREETNUMBER,SA.CON_ADDE_STRN_NAMEANDTYPE,
            SA.CON_ADDE_SUBURB,SA.CON_ADDE_CITY, WO.CON_CUS_NAME,NC.CON_AREA,NC.INV_NO
            from CONTRACTOR_NEW_CON nc, CONTRACTOR_OSP_DATA od  ,CONTRACTOR_SERVICE_ADDRESS sa,CONTRACTOR_WORK_ORDERS wo
            where NC.CON_SO_ID = OD.CON_OSP_SERO_ID
            and NC.CON_SO_ID = SA.CON_ADDE_SERO_ID
            and NC.CON_SO_ID = WO.CON_SERO_ID
            and nc.INV_NO = '$inv'
            and nc.CON_STATUS = '5'";
 
    $oraconn = OracleConnection();
    $sta = oci_parse($oraconn, $sql);
    if(oci_execute($sta))
    {
    return $sta;
    }
    else
    {
        $err = oci_error($sta);
        $e =  $err['message'];
        echo "<script type='text/javascript'>alert('$e')</script>";
    }
}

function getrequality($inv)
{
    $sql = "select distinct nc.CON_SO_ID ,nc.CON_CIRCUIT_NO,nc.CON_CONTRACTOR,
            OD.CON_OSP_DP_NAME,OD.CON_OSP_DP_LOOP ,SA.CON_ADDE_STREETNUMBER,SA.CON_ADDE_STRN_NAMEANDTYPE,
            SA.CON_ADDE_SUBURB,SA.CON_ADDE_CITY, WO.CON_CUS_NAME,NC.CON_AREA,NC.INV_NO
            from CONTRACTOR_NEW_CON nc, CONTRACTOR_OSP_DATA od  ,CONTRACTOR_SERVICE_ADDRESS sa,CONTRACTOR_WORK_ORDERS wo
            where NC.CON_SO_ID = OD.CON_OSP_SERO_ID
            and NC.CON_SO_ID = SA.CON_ADDE_SERO_ID
            and NC.CON_SO_ID = WO.CON_SERO_ID
            and nc.INV_NO = '$inv'
            and nc.CON_STATUS = '8'";
 
    $oraconn = OracleConnection();
    $sta = oci_parse($oraconn, $sql);
    if(oci_execute($sta))
    {
    return $sta;
    }
    else
    {
        $err = oci_error($sta);
        $e =  $err['message'];
        echo "<script type='text/javascript'>alert('$e')</script>";
    }
}
function getqc($sod)
{
    $sql = "select * from CONTRACTOR_QUALITY where SO_ID = '$sod'";
 
    $oraconn = OracleConnection();
    $sta = oci_parse($oraconn, $sql);
    if(oci_execute($sta))
    {
    return $sta;
    }
    else
    {
        $err = oci_error($sta);
        $e =  $err['message'];
        echo "<script type='text/javascript'>alert('$e')</script>";
    }
}

function met_invcount($a,$b)
{
    
    $sql = "select(cm.POLES_5_6_L+csm.POLES_5_6_L_SER+cam.POLES_5_6_L_ACC),(cm.POLES_5_6_H+csm.POLES_5_6_H_SER+cam.POLES_5_6_H_ACC),
(cm.POLES_5_6_CE+csm.POLES_5_6_CE_SER+cam.POLES_5_6_CE_ACC),(cm.POLES_6_7_CE+csm.POLES_6_7_CE_SER+cam.POLES_6_7_CE_ACC),
(cm.POLES_6_7+csm.POLES_6_7_SER+cam.POLES_6_7_ACC),(cm.POLES_7_5+csm.POLES_7_5_SER+cam.POLES_7_5_ACC),
(cm.POLES_8+csm.POLES_8_SER+cam.POLES_8_ACC),(cm.POLES_9+csm.POLES_9_SER+cam.POLES_9_ACC),
(cm.POLES_6_7_CON+csm.POLES_6_7_CON_SER+cam.POLES_6_7_CON_ACC),(cm.POLES_7_5_CON+csm.POLES_7_5_CON_SER+cam.POLES_7_5_CON_ACC),
(cm.POLES_8_CON+csm.POLES_8_CON_SER+cam.POLES_8_CON_ACC),(cm.POLES_9_CON+csm.POLES_9_CON_SER+cam.POLES_9_CON_ACC),
(cm.DROP_WIRE+csm.DROP_WIRE_SER+cam.DROP_WIRE_ACC),(cm.INTERNAL_WIRE+csm.INTERNAL_WIRE_SER+cam.INTERNAL_WIRE_ACC),
(cm.EARTH_WIRE+csm.EARTH_WIRE_SER+cam.EARTH_WIRE_ACC),(cm.DISCHARGER+csm.DISCHARGER_SER+cam.DISCHARGER_ACC),
(cm.EARTH_ROD+csm.EARTH_ROD_SER+cam.EARTH_ROD_ACC),(cm.RETAINERS+csm.RETAINERS_SER+cam.RETAINERS_ACC),
(cm.L_HOOK+csm.L_HOOK_SER+cam.L_HOOK_ACC),(cm.C_HOOK+csm.C_HOOK_SER+cam.C_HOOK_ACC),
(cm.TELEPHONE+csm.TELEPHONE_SER+cam.TELEPHONE_ACC), (PVC_CONDUIT+csm.PVC_CONDUIT_SER+cam.PVC_CONDUIT_ACC),
(GUY+csm.GUY_SER+cam.GUY_ACC), (POLE_STRUT+csm.POLE_STRUT_SER+cam.POLE_STRUT_ACC),
(cm.SPUN_POLE_5_6+csm.SPUN_POLE_5_6_SER+cam.SPUN_POLE_5_6_ACC),(cm.SPUN_POLE_6_7+csm.SPUN_POLE_6_7_SER+cam.SPUN_POLE_6_7_ACC),
(cm.SPUN_POLE_5_6_SLT+csm.SPUN_POLE_5_6_SLT_SER+cam.SPUN_POLE_5_6_SLT_ACC),(cm.SPUN_POLE_6_7_SLT+csm.SPUN_POLE_6_7_SLT_SER+cam.SPUN_POLE_6_7_SLT_ACC),
(cm.SPUN_POLE_5_6_CON+csm.SPUN_POLE_5_6_CON_SER+cam.SPUN_POLE_5_6_CON_ACC),(cm.SPUN_POLE_6_7_CON+csm.SPUN_POLE_6_7_CON_SER+cam.SPUN_POLE_6_7_CON_ACC),
(cm.CAT5+csm.CAT5_SER+cam.CAT5_ACC),(cm.IPTV_N+csm.IPTV_N_SER+cam.IPTV_N_ACC),
(cm.NC_BB_PEO+csm.NC_BB_PEO_SER+cam.NC_BB_PEO_ACC),(cm.POLES_5_6_CON+csm.POLES_5_6_CON_SER+cam.POLES_5_6_CON_ACC),
(cm.POLES_5_6_CON_CE+csm.POLES_5_6_CON_CE_SER+cam.POLES_5_6_CON_CE_ACC),(cm.POLES_6_7_CON_CE+csm.POLES_6_7_CON_CE_SER+cam.POLES_6_7_CON_CE_ACC),
(cm.POLES_S_CON+csm.POLES_S_CON_SER+cam.POLES_S_CON_ACC)
 from CONTRACTOR_MERETIAL cm,CONTRACTOR_MERETIAL_SER csm,CONTRACTOR_MERETIAL_ACC cam, CONTRACTOR_NEW_CON nc
 where cm.MET_SO_ID = nc.CON_SO_ID
 AND CM.MET_SO_ID =  CSM.MET_SO_ID
 AND CSM.MET_SO_ID = CAM.MET_SO_ID
 and nc.INV_NO = '$b'
 and nc.CON_CIRCUIT_NO = '$a' ";
        
    $oraconn = OracleConnection();
    $met_count = oci_parse($oraconn, $sql);
    if(oci_execute($met_count))
    {
    return $met_count;
    }
    else
    {
        $err = oci_error($met_count);
        $e =  $err['message'];
        echo "<script type='text/javascript'>alert('$e')</script>";
    }
    
}

function met_chkcount($a,$b)
{
    
    $sql = "select cm.POLES_5_6_L,cm.POLES_5_6_H,cm.POLES_5_6_CE,cm.POLES_6_7_CE,cm.POLES_6_7,cm.POLES_7_5,
cm.POLES_8,cm.POLES_9,cm.POLES_6_7_CON,cm.POLES_7_5_CON,cm.POLES_8_CON,cm.POLES_9_CON,cm.DROP_WIRE,cm.INTERNAL_WIRE,
cm.EARTH_WIRE,cm.DISCHARGER,cm.EARTH_ROD,cm.RETAINERS,cm.L_HOOK,cm.C_HOOK,cm.TELEPHONE, PVC_CONDUIT,cm.GUY, POLE_STRUT,
cm.SPUN_POLE_5_6,cm.SPUN_POLE_6_7,cm.SPUN_POLE_5_6_SLT,cm.SPUN_POLE_6_7_SLT,cm.SPUN_POLE_5_6_CON,cm.SPUN_POLE_6_7_CON,
cm.CAT5,cm.IPTV_N,cm.NC_BB_PEO,cm.POLES_5_6_CON,cm.POLES_5_6_CON_CE,cm.POLES_6_7_CON_CE,cm.POLES_S_CON
 from CONTRACTOR_QTY_CHK_MERETIAL cm, CONTRACTOR_NEW_CON nc
 where cm.MET_SO_ID = nc.CON_SO_ID
 and nc.INV_NO = '$b'
 and nc.CON_CIRCUIT_NO = '$a' ";
        
    $oraconn = OracleConnection();
    $met_count = oci_parse($oraconn, $sql);
    if(oci_execute($met_count))
    {
    return $met_count;
    }
    else
    {
        $err = oci_error($met_count);
        $e =  $err['message'];
        echo "<script type='text/javascript'>alert('$e')</script>";
    }
    
}

function met_divcount($a,$b)
{
    
    $sql = "select cm.POLES_5_6_L,cm.POLES_5_6_H,cm.POLES_5_6_CE,cm.POLES_6_7_CE,cm.POLES_6_7,cm.POLES_7_5,
cm.POLES_8,cm.POLES_9,cm.POLES_6_7_CON,cm.POLES_7_5_CON,cm.POLES_8_CON,cm.POLES_9_CON,cm.DROP_WIRE,cm.INTERNAL_WIRE,
cm.EARTH_WIRE,cm.DISCHARGER,cm.EARTH_ROD,cm.RETAINERS,cm.L_HOOK,cm.C_HOOK,cm.TELEPHONE, PVC_CONDUIT,cm.GUY, POLE_STRUT,
cm.SPUN_POLE_5_6,cm.SPUN_POLE_6_7,cm.SPUN_POLE_5_6_SLT,cm.SPUN_POLE_6_7_SLT,cm.SPUN_POLE_5_6_CON,cm.SPUN_POLE_6_7_CON,
cm.CAT5,cm.IPTV_N,cm.NC_BB_PEO,cm.POLES_5_6_CON,cm.POLES_5_6_CON_CE,cm.POLES_6_7_CON_CE,cm.POLES_S_CON
 from CONTRACTOR_QTY_CHK_MERETIALDIV cm, CONTRACTOR_NEW_CON nc
 where cm.MET_SO_ID = nc.CON_SO_ID
 and nc.INV_NO = '$b'
 and nc.CON_CIRCUIT_NO = '$a' ";
        
    $oraconn = OracleConnection();
    $met_count = oci_parse($oraconn, $sql);
    if(oci_execute($met_count))
    {
    return $met_count;
    }
    else
    {
        $err = oci_error($met_count);
        $e =  $err['message'];
        echo "<script type='text/javascript'>alert('$e')</script>";
    }
    
}

function get_qut($a,$b)
{
    
    $sql = "select cm.*
 from CONTRACTOR_QUALITY cm, CONTRACTOR_NEW_CON nc
 where cm.SO_ID = nc.CON_SO_ID
 and nc.INV_NO = '$b'
 and nc.CON_CIRCUIT_NO = '$a' ";
        
    $oraconn = OracleConnection();
    $met_count = oci_parse($oraconn, $sql);
    if(oci_execute($met_count))
    {
    return $met_count;
    }
    else
    {
        $err = oci_error($met_count);
        $e =  $err['message'];
        echo "<script type='text/javascript'>alert('$e')</script>";
    }
    
}

function qty_reinspec($inv,$pstn,$DROP_WIRE_A,$DROP_WIRE_B,$DROP_WIRE_C,$DROP_WIRE_D,$DROP_WIRE_E,
                            $ERACTING_POLES_A,$ERACTING_POLES_B,$ERACTING_POLES_C,$ERACTING_POLES_D,
                            $DISCHARGER_A,$DISCHARGER_B,$DISCHARGER_C,$DISCHARGER_D,$INTERNAL_WIRE_A,
                            $INTERNAL_WIRE_B,$EARTHING_A,$EARTHING_B,$EARTHING_C,$EARTHING_D,
                            $ROSSETE_A,$ROSSETE_B,$comment,$quality_pass)
{
    
    $sql = "insert into CONTRACTOR_REQUALITY (INV_NO,PSTN_NO,DROP_WIRE_A,DROP_WIRE_B,DROP_WIRE_C,DROP_WIRE_D,DROP_WIRE_E,ERACTING_POLES_A,
  ERACTING_POLES_B,ERACTING_POLES_C,DISCHARGER_A,DISCHARGER_B,DISCHARGER_C,DISCHARGER_D,INTERNAL_WIRE_A,INTERNAL_WIRE_B,
  EARTHING_A,EARTHING_B,EARTHING_C,EARTHING_D,ROSSETE_A,ROSSETE_B,INSPECTION_COMMENT,QUALITY_PASS)
  values ('$inv','$pstn','$DROP_WIRE_A','$DROP_WIRE_B','$DROP_WIRE_C','$DROP_WIRE_D','$DROP_WIRE_E',
                            '$ERACTING_POLES_A','$ERACTING_POLES_B','$ERACTING_POLES_C',
                            '$DISCHARGER_A','$DISCHARGER_B','$DISCHARGER_C','$DISCHARGER_D','$INTERNAL_WIRE_A',
                            '$INTERNAL_WIRE_B','$EARTHING_A','$EARTHING_B','$EARTHING_C','$EARTHING_D',
                            '$ROSSETE_A','$ROSSETE_B','$comment','$quality_pass')";

    
    $oraconn = OracleConnection();
    $qty_ins = oci_parse($oraconn, $sql);
    if(oci_execute($qty_ins))
    {
    $sql ="update CONTRACTOR_NEW_CON set CON_STATUS = '8', REQC_DATE = sysdate where CON_CIRCUIT_NO='$pstn'
            and INV_NO = '$inv'";
    $oraconn = OracleConnection();
    $up_st = oci_parse($oraconn, $sql);
    if(oci_execute($up_st))
    {
        return $qty_ins;
    }
    else
    {
        $err = oci_error($up_st);
        $e =  $err['message'];
        echo "<script type='text/javascript'>alert('$e')</script>";
    }    
        
    

    }
    else
    {
        $err = oci_error($qty_ins);
        $e =  $err['message'];
        echo "<script type='text/javascript'>alert('$e')</script>";
    }
    
}

function getcount($a)
{
    $sql= "select count(*) from CONTRACTOR_NEW_CON where INV_NO = '$a'";
    
    $oraconn = OracleConnection();
    $con_comp= oci_parse($oraconn, $sql);
    if(oci_execute($con_comp))
    {
    return $con_comp;
    }
    else
    {
        $err = oci_error($con_comp);
        $e =  $err['message'];
        echo "<script type='text/javascript'>alert('$e')</script>";
    }
}

function uprep($a,$b)
{
    $sql= "update CONTRACTOR_NEW_CON
    set CON_STATUS = '6'
    where CON_CIRCUIT_NO = '$b'
    and INV_NO ='$a'";
    
    $oraconn = OracleConnection();
    $con_comp= oci_parse($oraconn, $sql);
    if(oci_execute($con_comp))
    {
    return $con_comp;
    }
    else
    {
        $err = oci_error($con_comp);
        $e =  $err['message'];
        echo "<script type='text/javascript'>alert('$e')</script>";
    }
}

function reuprep($a,$b)
{
    $sql= "update CONTRACTOR_NEW_CON
    set CON_STATUS = '9'
    where CON_CIRCUIT_NO = '$b'
    and INV_NO ='$a'";
    
    $oraconn = OracleConnection();
    $con_comp= oci_parse($oraconn, $sql);
    if(oci_execute($con_comp))
    {
    return $con_comp;
    }
    else
    {
        $err = oci_error($con_comp);
        $e =  $err['message'];
        echo "<script type='text/javascript'>alert('$e')</script>";
    }
}

function get_contra($a)
{
    $sql= "select * from CONTRACTOR_DETAIL where CON_NAME = '$a' ";
    
    $oraconn = OracleConnection();
    $con_comp= oci_parse($oraconn, $sql);
    if(oci_execute($con_comp))
    {
    return $con_comp;
    }
    else
    {
        $err = oci_error($con_comp);
        $e =  $err['message'];
        echo "<script type='text/javascript'>alert('$e')</script>";
    }
}


function so_com_ospupdate($a)
{
    $sql= "update CONTRACTOR_WORK_ORDERS 
        set CON_STATUS = 'OSP_CLOSED',CON_STATUS_DATE= sysdate
        where CON_SERO_ID ='$a'
        and CON_STATUS = 'INPROGRESS'
        and (CON_WORO_TASK_NAME = 'CONSTRUCT OSP' OR CON_WORO_TASK_NAME ='RECONSTRUCT OSP')";
   
    $oraconn = OracleConnection();
    $con_comp= oci_parse($oraconn, $sql);
    if(oci_execute($con_comp))
    {
    
        $sql= "update CONTRACTOR_NEW_CON 
            set CON_SO_STATUS = 'OSP_CLOSED',CON_SO_COM_DATE = sysdate
            where CON_SO_ID ='$a'
            and CON_SO_STATUS = 'INPROGRESS'
            and (CON_TASK = 'CONSTRUCT OSP' OR CON_TASK ='RECONSTRUCT OSP')";
            
        $oraconn = OracleConnection();
        $con= oci_parse($oraconn, $sql);
        if(oci_execute($con)){
            
            return $con;
        }
    
    }
    else
    {
        $err = oci_error($con_comp);
        $e =  $err['message'];
        echo "<script type='text/javascript'>alert('$e')</script>";
    }
}


function getcir($a)
{
    $sql= "select CON_PSTN_NUMBER,CON_WORO_TASK_NAME,CON_WORO_ORDER_TYPE from CONTRACTOR_WORK_ORDERS where CON_SERO_ID = '$a'
         and CON_WORO_TASK_NAME like 'INS%'
         and CON_STATUS = 'INPROGRESS'";
    
    $oraconn = OracleConnection();
    $con_comp= oci_parse($oraconn, $sql);
    if(oci_execute($con_comp))
    {
    return $con_comp;
    }
    else
    {
        $err = oci_error($con_comp);
        $e =  $err['message'];
        echo "<script type='text/javascript'>alert('$e')</script>";
    }
}

function getcircab($a)
{
    $sql= "select CON_PSTN_NUMBER,CON_WORO_TASK_NAME,CON_WORO_ORDER_TYPE from CONTRACTOR_WORK_ORDERS where CON_SERO_ID = '$a'
         and CON_STATUS = 'INPROGRESS'";
    
    $oraconn = OracleConnection();
    $con_comp= oci_parse($oraconn, $sql);
    if(oci_execute($con_comp))
    {
    return $con_comp;
    }
    else
    {
        $err = oci_error($con_comp);
        $e =  $err['message'];
        echo "<script type='text/javascript'>alert('$e')</script>";
    }
}

function getcircabclose($a)
{
    $sql= "select CON_PSTN_NUMBER,CON_WORO_TASK_NAME,CON_WORO_ORDER_TYPE,CON_WORO_SERVICE_TYPE from CONTRACTOR_WORK_ORDERS where CON_SERO_ID = '$a'
         and CON_STATUS = 'COMPLETED'";
    
    $oraconn = OracleConnection();
    $con_comp= oci_parse($oraconn, $sql);
    if(oci_execute($con_comp))
    {
    return $con_comp;
    }
    else
    {
        $err = oci_error($con_comp);
        $e =  $err['message'];
        echo "<script type='text/javascript'>alert('$e')</script>";
    }
}

function getconosp($a)
{
    $sql= "select * from (select CON_WORO_TASK_NAME,to_char(CON_DATE_TO_CONTRACTOR, 'mm/dd/yyyy hh:mi:ss AM') from CONTRACTOR_WORK_ORDERS where CON_SERO_ID = '$a'
         and CON_WORO_TASK_NAME not like 'INS%' order by CON_DATE_TO_CONTRACTOR DESC) where ROWNUM = 1";
    
    $oraconn = OracleConnection();
    $con_comp= oci_parse($oraconn, $sql);
    if(oci_execute($con_comp))
    {
    return $con_comp;
    }
    else
    {
        $err = oci_error($con_comp);
        $e =  $err['message'];
        echo "<script type='text/javascript'>alert('$e')</script>";
    }
}



function updateftthmet($a,$b,$c,$d,$e,$user)
{
    if($e == 'P0'){
    $sql= "INSERT INTO OSS_DEV_01.CONTRACTOR_FTTH_MET(SOID, VOICENO,UNIT_DESIG,P0,P1,SN,MET_ID)
         VALUES ('$a','$b','$c','$d','0','',MET_SEQ.nextval)";
    }
    if($e == 'P1'){
    $sql= "INSERT INTO OSS_DEV_01.CONTRACTOR_FTTH_MET(SOID, VOICENO,UNIT_DESIG,P0,P1,SN,MET_ID)
         VALUES ('$a','$b','$c','0','$d','',MET_SEQ.nextval)";
    }
    $msg= 'Meterial Insert UNIT_DESIG: '.$c;
    $oraconn = OracleConnection();
    $con_comp= oci_parse($oraconn, $sql);
    if(oci_execute($con_comp))
    {
        log_all($user,$a,$msg);    
    return $con_comp;
    }
    else
    {
        $err = oci_error($con_comp);
        $e =  $err['message'];
        echo "<script type='text/javascript'>alert('$e')</script>";
    }
}


function updateftthmetPole($a,$b,$c,$d,$e,$user)
{
    if($e == 'P0'){
    $sql= "INSERT INTO OSS_DEV_01.CONTRACTOR_FTTH_MET(SOID, VOICENO,UNIT_DESIG,P0,P1,SN,MET_ID)
         VALUES ('$a','$b','$c','1','0','$d',MET_SEQ.nextval)";
    }
    if($e == 'P1'){
    $sql= "INSERT INTO OSS_DEV_01.CONTRACTOR_FTTH_MET(SOID, VOICENO,UNIT_DESIG,P0,P1,SN,MET_ID)
         VALUES ('$a','$b','$c','0','1','$d',MET_SEQ.nextval)";
    }
    $msg= 'Meterial Insert UNIT_DESIG: '.$c;
    $oraconn = OracleConnection();
    $con_comp= oci_parse($oraconn, $sql);
    if(oci_execute($con_comp))
    {
        log_all($user,$a,$msg);    
    return $con_comp;
    }
    else
    {
        $err = oci_error($con_comp);
        $e =  $err['message'];
        echo "<script type='text/javascript'>alert('$e')</script>";
    }
}

function updateftthmetrollback($a)
{
    
    $sql= "DELETE FROM  OSS_DEV_01.CONTRACTOR_FTTH_MET WHERE SOID = '$a'";
        

    $oraconn = OracleConnection();
    $con_comp= oci_parse($oraconn, $sql);
    if(oci_execute($con_comp))
    {
    return $con_comp;
    }
    else
    {
        $err = oci_error($con_comp);
        $e =  $err['message'];
        echo "<script type='text/javascript'>alert('$e')</script>";
    }
}


function getexcel($a)
{
    $sql= "select distinct A.CON_SERO_ID,to_char(a.CON_DATE_TO_CONTRACTOR, 'mm/dd/yyyy hh:mi:ss AM'),A.CON_PSTN_NUMBER, A.CON_WORO_AREA, D.CON_EX_AREA ,A.CON_WORO_SERVICE_TYPE,A.CON_WORO_ORDER_TYPE,a.CON_WORO_TASK_NAME,A.CON_CUS_NAME,
REPLACE(A.CON_TEC_CONTACT,',','') , REPLACE(c.CON_ADDE_STREETNUMBER||' '||c.CON_ADDE_STRN_NAMEANDTYPE||' '||c.CON_ADDE_SUBURB||' '||c.CON_ADDE_CITY,',',''), B.CON_FTTH_ONT_SN,
B.CON_FTTH_PKG,d.CON_OSP_PHONE_COLOUR,D.CON_OSP_DP_NAME ,D.CON_OSP_DP_LOOP, E.CON_EQ_LOC_NAME,E.CON_EQ_INDEX,E.CON_EQ_CARD,E.CON_EQ_PORT,d.CON_OSP_PHONE_CLASS,replace(a.CON_WORO_DISCRIPTION,',', ' '),d.CON_PHN_PURCH, e.CON_PHN_PURCH
from CONTRACTOR_WORK_ORDERS a ,CONTRACTOR_FTTH_DATA b, CONTRACTOR_SERVICE_ADDRESS c,CONTRACTOR_OSP_DATA d,CONTRACTOR_EQ_DATA e
where A.CON_SERO_ID = B.CON_FTTH_SERO_ID(+)
and A.CON_SERO_ID = C.CON_ADDE_SERO_ID(+)
and A.CON_SERO_ID = d.CON_OSP_SERO_ID(+)
and A.CON_SERO_ID = E.CON_EQ_SERO_ID (+)
and a.CON_STATUS IN ('ASSIGNED','INPROGRESS','REASSIGNED','DELAYED')
and  A.CON_SERO_ID  = '$a'";

    $oraconn = OracleConnection();
    $con_comp= oci_parse($oraconn, $sql);
    if(oci_execute($con_comp))
    {
        
      $row  =oci_fetch_array($con_comp);
    return $row;
    }
    else
    {
        $err = oci_error($con_comp);
        $e =  $err['message'];
        echo "<script type='text/javascript'>alert('$e')</script>";
    }
}

function updatepeo($a,$b,$c)
{

    $sql= "INSERT INTO OSS_DEV_01.CONTRACTOR_IPTV_DATA(SO_ID, SETTOPBOX_SERIAL_NO,SETTOPBOX_MODEL)
         VALUES ('$a','$b','$c')";

    $oraconn = OracleConnection();
    $con_comp= oci_parse($oraconn, $sql);
    if(oci_execute($con_comp))
    {
    return $con_comp;
    }
    else
    {
        $err = oci_error($con_comp);
        $e =  $err['message'];
        echo "<script type='text/javascript'>alert('$e')</script>";
    }
}


function updateftthatt($a,$b,$c,$d,$e)
{

    $sql= "INSERT INTO OSS_DEV_01.CONTRACTOR_FTTH_ATT(SOID, CIRCUIT,LAST_POWER,FDP_POWER,BB)
         VALUES ('$a','$b','$c','$d','$e')";
    
    $oraconn = OracleConnection();
    $con_comp= oci_parse($oraconn, $sql);
    if(oci_execute($con_comp))
    {
    return $con_comp;
    }
    else
    {
        $err = oci_error($con_comp);
        $e =  $err['message'];
        echo "<script type='text/javascript'>alert('$e')</script>";
    }
}


function updateiptvmetPole($a,$b,$c,$d,$e,$user)
{
    if($e == 'P0'){
    $sql= "INSERT INTO OSS_DEV_01.CONTRACTOR_IPTV_MET(SOID, VOICENO,UNIT_DESIG,P0,P1,SN,MET_ID)
         VALUES ('$a','$b','$c','1','0','$d',MET_SEQ.nextval)";
    }
    if($e == 'P1'){
    $sql= "INSERT INTO OSS_DEV_01.CONTRACTOR_IPTV_MET(SOID, VOICENO,UNIT_DESIG,P0,P1,SN,MET_ID)
         VALUES ('$a','$b','$c','0','1','$d',MET_SEQ.nextval)";
    }
    $msg= 'Meterial Insert UNIT_DESIG: '.$c;
    $oraconn = OracleConnection();
    $con_comp= oci_parse($oraconn, $sql);
    if(oci_execute($con_comp))
    {
    log_all($user,$a,$msg);
    return $con_comp;
    }
    else
    {
        $err = oci_error($con_comp);
        $e =  $err['message'];
        echo "<script type='text/javascript'>alert('$e')</script>";
    }
}

function updateiptvmet($a,$b,$c,$d,$e,$user)
{
    if($e == 'P0'){
    $sql= "INSERT INTO OSS_DEV_01.CONTRACTOR_IPTV_MET(SOID, VOICENO,UNIT_DESIG,P0,P1,SN,MET_ID)
         VALUES ('$a','$b','$c','$d','0','',MET_SEQ.nextval)";
    }
    if($e == 'P1'){
    $sql= "INSERT INTO OSS_DEV_01.CONTRACTOR_IPTV_MET(SOID, VOICENO,UNIT_DESIG,P0,P1,SN,MET_ID)
         VALUES ('$a','$b','$c','0','$d','',MET_SEQ.nextval)";
    }
    $msg= 'Meterial Insert UNIT_DESIG: '.$c;
    $oraconn = OracleConnection();
    $con_comp= oci_parse($oraconn, $sql);
    if(oci_execute($con_comp))
    {
        log_all($user,$a,$msg);
    return $con_comp;
    }
    else
    {
        $err = oci_error($con_comp);
        $e =  $err['message'];
        echo "<script type='text/javascript'>alert('$e')</script>";
    }
}

function updatecoppermet($a,$b,$c,$d,$e,$user)
{
    if($e == 'P0'){
    $sql= "INSERT INTO OSS_DEV_01.CONTRACTOR_COPPER_MET(SOID, VOICENO,UNIT_DESIG,P0,P1,SN,MET_ID)
         VALUES ('$a','$b','$c','$d','0','',MET_SEQ.nextval)";
    }
    if($e == 'P1'){
    $sql= "INSERT INTO OSS_DEV_01.CONTRACTOR_COPPER_MET(SOID, VOICENO,UNIT_DESIG,P0,P1,SN,MET_ID)
         VALUES ('$a','$b','$c','0','$d','',MET_SEQ.nextval)";
    }
    $msg= 'Meterial Insert UNIT_DESIG: '.$c;
    $oraconn = OracleConnection();
    $con_comp= oci_parse($oraconn, $sql);
    if(oci_execute($con_comp))
    {
    log_all($user,$a,$msg);    
    return $con_comp;
    }
    else
    {
        $err = oci_error($con_comp);
        $e =  $err['message'];
        echo "<script type='text/javascript'>alert('$e')</script>";
    }
}


function updatecoppermetPole($a,$b,$c,$d,$e,$user)
{
    if($e == 'P0'){
    $sql= "INSERT INTO OSS_DEV_01.CONTRACTOR_COPPER_MET(SOID, VOICENO,UNIT_DESIG,P0,P1,SN,MET_ID)
         VALUES ('$a','$b','$c','1','0','$d',MET_SEQ.nextval)";
    }
    if($e == 'P1'){
    $sql= "INSERT INTO OSS_DEV_01.CONTRACTOR_COPPER_MET(SOID, VOICENO,UNIT_DESIG,P0,P1,SN,MET_ID)
         VALUES ('$a','$b','$c','0','1','$d',MET_SEQ.nextval)";
    }
    $msg= 'Meterial Insert UNIT_DESIG: '.$c;
    $oraconn = OracleConnection();
    $con_comp= oci_parse($oraconn, $sql);
    if(oci_execute($con_comp))
    {
    log_all($user,$a,$msg);    
    return $con_comp;
    }
    else
    {
        $err = oci_error($con_comp);
        $e =  $err['message'];
        echo "<script type='text/javascript'>alert('$e')</script>";
    }
}

function updatecoppermetrollback($a)
{
    
    $sql= "DELETE FROM  OSS_DEV_01.CONTRACTOR_COPPER_MET WHERE SOID = '$a'";
        

    $oraconn = OracleConnection();
    $con_comp= oci_parse($oraconn, $sql);
    if(oci_execute($con_comp))
    {  
    return $con_comp;
    }
    else
    {
        $err = oci_error($con_comp);
        $e =  $err['message'];
        echo "<script type='text/javascript'>alert('$e')</script>";
    }
}

if(isset($_POST["q"]) && $_POST["q"]== 'delsltuser' ){
$uid=$_POST["id"];

    $sql= "DELETE FROM  OSS_DEV_01.CONTRACTOR_MGT_USERS WHERE CON_MGT_USER_NAME = '$uid' and CON_MGT_CONTRACTOR_NAME = 'SLT'";
        

    $oraconn = OracleConnection();
    $con_comp= oci_parse($oraconn, $sql);
    if(oci_execute($con_comp))
    {
    echo  "success";
    }
    else
    {
        $err = oci_error($con_comp);
        $e =  $err['message'];
        echo $e;
    }

}

if(isset($_POST["q"]) && $_POST["q"]== 'chksltuser' ){
$uid=$_POST["id"];

    $sql= "SELECT * FROM  OSS_DEV_01.CONTRACTOR_MGT_USERS WHERE CON_MGT_USER_NAME = '$uid' and CON_MGT_CONTRACTOR_NAME = 'SLT'";
        

    $oraconn = OracleConnection();
    $con_comp= oci_parse($oraconn, $sql);
    oci_execute($con_comp);
    $row = oci_fetch_array($con_comp);
    
   $mob = $row["CON_MGT_USER"].'@'.$row["SLT_AREA"];
    echo $mob;
}

if(isset($_POST["q"]) && $_POST["q"]== 'modsltuser' ){
$uid=$_POST["id"];
$area=$_POST["area"];

    $sql= "UPDATE  OSS_DEV_01.CONTRACTOR_MGT_USERS
            SET SLT_AREA = '$area'
     WHERE CON_MGT_USER_NAME = '$uid' and CON_MGT_CONTRACTOR_NAME = 'SLT'";
        

    $oraconn = OracleConnection();
    $con_comp= oci_parse($oraconn, $sql);
    if(oci_execute($con_comp))
    {
    echo  "success";
    }
    else
    {
        $err = oci_error($con_comp);
        $e =  $err['message'];
        echo $e;
    }

}

if(isset($_POST["q"]) && $_POST["q"]== 'addsltuser' ){
$uid=$_POST["id"];
$area=$_POST["area"];
$namea=$_POST["namea"];

    $sql= "INSERT INTO OSS_DEV_01.CONTRACTOR_MGT_USERS (CON_MGT_CONTRACTOR_NAME,CON_MGT_CONTRACTOR,CON_MGT_USER_NAME,CON_MGT_USER_PRV_LEVEL,CON_MGT_USER,SLT_AREA) 
            VALUES('SLT','SLT','$uid','9','$namea', '$area')";
        

    $oraconn = OracleConnection();
    $con_comp= oci_parse($oraconn, $sql);
    if(oci_execute($con_comp))
    {
    echo  "success";
    }
    else
    {
        $err = oci_error($con_comp);
        $e =  $err['message'];
        echo $e;
    }

}

if(isset($_POST["q"]) && $_POST["q"]== 'updatemet' ){
$met=$_POST["met"];
$P0=$_POST["P0"];
$P1=$_POST["P1"];
$ser=$_POST["ser"];
$cir=$_POST["cir"];
$metid=$_POST["metid"];
$sod=$_POST["sod"];

if($ser == 'AB-FTTH')
{
  $sql= "update OSS_DEV_01.CONTRACTOR_FTTH_MET set P0 = '$P0' ,P1 = '$P1' where   MET_ID = '$metid' and UNIT_DESIG = '$met' ";
      
}
if($ser == 'AB-CAB')
{
  $sql= "update OSS_DEV_01.CONTRACTOR_COPPER_MET set P0 = '$P0' ,P1 = '$P1' where  MET_ID = '$metid' and UNIT_DESIG = '$met' ";
      
}
if($ser == 'E-IPTV COPPER' || $ser == 'E-IPTV FTTH')
{
  $sql= "update OSS_DEV_01.CONTRACTOR_IPTV_MET set P0 = '$P0' ,P1 = '$P1' where  MET_ID = '$metid' and UNIT_DESIG = '$met' ";
      
}
     $msg =  'meterial update UNIT_DESIG: '.$met.' P0: '.$P0.' P1: '.$P1;  

    $oraconn = OracleConnection();
    $con_comp= oci_parse($oraconn, $sql);
    if(oci_execute($con_comp))
    {
        log_all($user,$sod,$msg);
    echo  "success";
   
    }
    else
    {
        $err = oci_error($con_comp);
        $e =  $err['message'];
        echo $e;
    }

}


if(isset($_POST["q"]) && $_POST["q"]== 'deletemet' ){
$met=$_POST["met"];    
$ser=$_POST["ser"];
$cir=$_POST["cir"];
$metid=$_POST["metid"];
$sod=$_POST["sod"];

if($ser == 'AB-FTTH')
{
  $sql= "delete from  OSS_DEV_01.CONTRACTOR_FTTH_MET  where   MET_ID = '$metid' ";
      
}
if($ser == 'AB-CAB')
{
  $sql= "delete from OSS_DEV_01.CONTRACTOR_COPPER_MET  where  MET_ID = '$metid'";
      
}
if($ser == 'IPTV')
{
  $sql= "delete from OSS_DEV_01.CONTRACTOR_IPTV_MET where  MET_ID = '$metid' ";
      
}
   $msg =  'meterial delete UNIT_DESIG: '.$met;      

    $oraconn = OracleConnection();
    $con_comp= oci_parse($oraconn, $sql);
    if(oci_execute($con_comp))
    {
        log_all($user,$sod,$msg);
    echo  "success";
   
    }
    else
    {
        $err = oci_error($con_comp);
        $e =  $err['message'];
        echo $e;
    }

}

if(isset($_POST["q"]) && $_POST["q"]== 'updateapp' ){

$sod=$_POST["sod"];
$cir=$_POST["cir"];

  $sql= "update CONTRACTOR_NEW_CON set CON_APPROVE = 'YES', CON_APPROVE_DATE = sysdate 
        where CON_SO_ID = '$sod' and CON_CIRCUIT_NO= '$cir' and CON_SO_STATUS = 'COMPLETED' and CON_STATUS = '2'";
    
    $oraconn = OracleConnection();
    $con_comp= oci_parse($oraconn, $sql);
    if(oci_execute($con_comp))
    {
      log_all($user,$sod,'Meterial details Approved');
    echo  "success";
   
    }
    else
    {
        $err = oci_error($con_comp);
        $e =  $err['message'];
        echo $e;
    }

}

if(isset($_POST["q"]) && $_POST["q"]== 'addmet' ){
$met=$_POST["met"];
$sn=$_POST["sn"];
$type=$_POST["type"];
$ser=$_POST["ser"];
$cir=$_POST["cir"];
$sod=$_POST["sod"];
$val=$_POST["val"];

    if($ser == 'AB-FTTH')
    {
      
      if($type == 'P0'){
        $sql= "INSERT INTO OSS_DEV_01.CONTRACTOR_FTTH_MET(SOID, VOICENO,UNIT_DESIG,P0,P1,SN,MET_ID)
             VALUES ('$sod','$cir','$met','$val','0','$sn',MET_SEQ.nextval)";
        }
        if($type == 'P1'){
        $sql= "INSERT INTO OSS_DEV_01.CONTRACTOR_FTTH_MET(SOID, VOICENO,UNIT_DESIG,P0,P1,SN,MET_ID)
             VALUES ('$sod','$cir','$met','0','$val','$sn',MET_SEQ.nextval)";
        }
      
    
    }
    if($ser == 'AB-CAB')
    {
      if($type == 'P0'){
        $sql= "INSERT INTO OSS_DEV_01.CONTRACTOR_COPPER_MET(SOID, VOICENO,UNIT_DESIG,P0,P1,SN,MET_ID)
             VALUES ('$sod','$cir','$met','$val','0','$sn',MET_SEQ.nextval)";
        }
        if($type == 'P1'){
        $sql= "INSERT INTO OSS_DEV_01.CONTRACTOR_COPPER_MET(SOID, VOICENO,UNIT_DESIG,P0,P1,SN,MET_ID)
             VALUES ('$sod','$cir','$met','0','$val','$sn',MET_SEQ.nextval)";
        }
          
    }
    if($ser == 'IPTV')
    {
      if($type == 'P0'){
    $sql= "INSERT INTO OSS_DEV_01.CONTRACTOR_IPTV_MET(SOID, VOICENO,UNIT_DESIG,P0,P1,SN,MET_ID)
         VALUES ('$sod','$cir','$met','1','0','$sn',MET_SEQ.nextval)";
    }
    if($type == 'P1'){
    $sql= "INSERT INTO OSS_DEV_01.CONTRACTOR_IPTV_MET(SOID, VOICENO,UNIT_DESIG,P0,P1,SN,MET_ID)
         VALUES ('$sod','$cir','$met','0','1','$sn',MET_SEQ.nextval)";
    }
          
    }
        $msg= 'Meterial Insert UNIT_DESIG: '.$met;

    $oraconn = OracleConnection();
    $con_comp= oci_parse($oraconn, $sql);
    if(oci_execute($con_comp))
    {
    log_all($user,$sod,$msg);
    echo  "success";

    }
    else
    {
        $err = oci_error($con_comp);
        $e =  $err['message'];
        echo $e;
    }

}

function so_rereturn($so_id,$reason,$user)
{
    $sql = "insert into CONTRACTOR_ORDER_COMMENTS (CON_COMM_SERO_ID,CON_COMM_TEXT,CON_COMM_TIME,CON_COMM_USER,CON_COMM_STATUS) 
        values ('$so_id', '$reason', sysdate , '$user','HOLD' )";
    $oraconn = OracleConnection();
    $return2 = oci_parse($oraconn, $sql);
    if(oci_execute($return2))
    {
    $sql = "update  CONTRACTOR_NEW_CON set CON_SO_STATUS = 'HOLD', CON_SO_RTN_DATE = sysdate  where CON_SO_ID = '{$so_id}' and  CON_TASK ='RECONSTRUCT OSP'";
    $oraconn = OracleConnection();
    $return = oci_parse($oraconn, $sql);
    if(oci_execute($return))
    {
    $sql = "update  CONTRACTOR_WORK_ORDERS set CON_STATUS = 'HOLD', CON_STATUS_DATE = sysdate where CON_SERO_ID = '{$so_id}' and CON_WORO_TASK_NAME = 'RECONSTRUCT OSP'";
    $oraconn = OracleConnection();
    $return1 = oci_parse($oraconn, $sql);
    if(oci_execute($return1))
    {
    return $return1;
    }
    else
    {
        $err = oci_error($return1);
        $e =  $err['message'];
        echo "<script type='text/javascript'>alert('$e')</script>";
    }
    }
    else
    {
        $err = oci_error($return);
        $e =  $err['message'];
        echo "<script type='text/javascript'>alert('$e')</script>";
    }
    }
    else
    {
        $err = oci_error($return2);
        $e =  $err['message'];
        echo "<script type='text/javascript'>alert('$e')</script>";
    }
}

function getreret($a)
{
    $sql= "select distinct  c.CON_SO_ID, c.CON_CIRCUIT_NO, B.CON_WORO_SERVICE_TYPE,c.CON_CONTRACTOR,
to_char( c.CON_SO_RTN_DATE, 'mm/dd/yyyy hh:mi:ss AM'), cm.CON_COMM_TEXT
from CONTRACTOR_NEW_CON c, CONTRACTOR_ORDER_COMMENTS cm, CONTRACTOR_WORK_ORDERS b
where c.CON_SO_ID = b.CON_SERO_ID  
and   c.CON_SO_STATUS = 'HOLD'
and C.CON_SO_ID = CM.CON_COMM_SERO_ID
and b.CON_SERO_ID  = CM.CON_COMM_SERO_ID
and cm.CON_COMM_TIME = 
(select max(cm1.CON_COMM_TIME) from OSS_DEV_01.CONTRACTOR_ORDER_COMMENTS cm1
where cm1.CON_COMM_SERO_ID = C.CON_SO_ID)
and B.CON_WORO_AREA = '$a'";

    $oraconn = OracleConnection();
    $con_comp= oci_parse($oraconn, $sql);
    if(oci_execute($con_comp))
    {
    return $con_comp;
    }
    else
    {
        $err = oci_error($con_comp);
        $e =  $err['message'];
        echo "<script type='text/javascript'>alert('$e')</script>";
    }
}


if(isset($_POST["q"]) && $_POST["q"]== 'updatecancel' ){

$sod=$_POST["sod"];


  $sql= "update CONTRACTOR_NEW_CON set CON_SO_STATUS = 'CANCELLED', CON_SO_STATUS_DATE = sysdate 
        where CON_SO_ID = '$sod' and CON_TASK= 'RECONSTRUCT OSP' ";
    
    $oraconn = OracleConnection();
    $con_comp= oci_parse($oraconn, $sql);
    if(oci_execute($con_comp))
    {
        $sql= "update CONTRACTOR_WORK_ORDERS set CON_STATUS = 'CANCELLED', CON_STATUS_DATE = sysdate 
            where CON_SERO_ID = '$sod' and CON_WORO_TASK_NAME= 'RECONSTRUCT OSP' ";
        $oraconn = OracleConnection();
        $con= oci_parse($oraconn, $sql);
        if(oci_execute($con))
        {
            
            echo "success";
        }
        else
        {
            $err = oci_error($con);
            $e =  $err['message'];
            echo $e;
        }    
    }
    else
    {
        $err = oci_error($con_comp);
        $e =  $err['message'];
        echo $e;
    }

}

if(isset($_POST["q"]) && $_POST["q"]== 'updatereassign' ){

$sod=$_POST["sod"];


  $sql= "update CONTRACTOR_WORK_ORDERS set CON_STATUS = 'REASSIGNED', CON_STATUS_DATE = sysdate 
            where CON_SERO_ID = '$sod' and CON_WORO_TASK_NAME= 'RECONSTRUCT OSP'";
    
    $oraconn = OracleConnection();
    $con_comp= oci_parse($oraconn, $sql);
    if(oci_execute($con_comp))
    {
        $sql2= "update CONTRACTOR_NEW_CON set CON_SO_STATUS = 'REASSIGNED', CON_SO_STATUS_DATE = sysdate,CON_REASSIGNED_DATE=sysdate 
        where CON_SO_ID = '$sod' and CON_TASK= 'RECONSTRUCT OSP' and CON_TASK <> 'RETURNED' ";
        $oraconn = OracleConnection();
        $con= oci_parse($oraconn, $sql2);
        if(oci_execute($con))
        {
            
           echo "success";
        }
        else
        {
            $err = oci_error($con);
            $e =  $err['message'];
            echo $e;
        }    
    }
    else
    {
        $err = oci_error($con_comp);
        $e =  $err['message'];
        echo $e;
    }

}


function getunitref($a,$b)
{
    $sql= "select * 
        from CONTRACTOR_UNIT_RATE
        where RTOM  = '$a'
        and UNIT_DESIG = '$b'";

    $oraconn = OracleConnection();
    $con_comp= oci_parse($oraconn, $sql);
    if(oci_execute($con_comp))
    {
    return $con_comp;
    }
    else
    {
        $err = oci_error($con_comp);
        $e =  $err['message'];
        echo "<script type='text/javascript'>alert('$e')</script>";
    }
}


function getMetexcel($a)
{
    $sql= "SELECT * FROM (select  distinct c.VOICENO,UNIT_DESIG , P0,P1 ,SN
            from CONTRACTOR_INV_PASSCHK a,CONTRACTOR_WORK_ORDERS b, CONTRACTOR_INV_MET c     
            where A.CON_SO_ID = B.CON_SERO_ID
            and A.CON_SO_ID = C.SOID
            and b.CON_SERO_ID = C.SOID
            and a.INV_NO='$a')  X
            PIVOT
            (
            SUM(P0+P1)
            FOR UNIT_DESIG
            IN ('DW-EW',
            'FTTH-DW',
            'EX-IPTV',
            'PL-C-5.6-CE',
            'PL-C-6.7',
            'PL-C-7.5',
            'PT-SP-VO-ID',
            'PT-2P-VB-ID',
            'PT-3P-BP-ID',
            'FT-DP-VP-ID',
            'FT-SP-PO-ID',
            'PL-C-5.6-L',
            'DW-DF',
            'TL-WM-D-25',
            'SC-C5',
            'PT-SP-PO-ID',
            'FT-DP-VB-ID',
            'FT-DP-V3P-ID',
            'FT-3P-B2P-ID',
            'PL-C6.7CE',
            'PL-C-9',
            'PT-2P-VP-ID',
            'FT-3P-BP-ID',
            'PSTN-DW',
            'DW-ER',
            'FT-SP-VO-ID',
            'FT-DP-V2P-ID',
            'PL-C-5.6-H',
            'PL-C-8',
            'DW-LH',
            'B-16',
            'PLC-CON',
            'PL-GI-50')
            )
            union all
            SELECT * FROM (select  distinct c.VOICENO,UNIT_DESIG , P0,P1,SN
            from CONTRACTOR_INV_PASSED a,CONTRACTOR_WORK_ORDERS b, CONTRACTOR_INV_MET c     
            where A.CON_SO_ID = B.CON_SERO_ID
            and A.CON_SO_ID = C.SOID
            and b.CON_SERO_ID = C.SOID
            and a.INV_NO='$a')  X
            PIVOT
            (
            SUM(P0+P1)
            FOR UNIT_DESIG
            IN ('DW-EW',
            'FTTH-DW',
            'EX-IPTV',
            'PL-C-5.6-CE',
            'PL-C-6.7',
            'PL-C-7.5',
            'PT-SP-VO-ID',
            'PT-2P-VB-ID',
            'PT-3P-BP-ID',
            'FT-DP-VP-ID',
            'FT-SP-PO-ID',
            'PL-C-5.6-L',
            'DW-DF',
            'TL-WM-D-25',
            'SC-C5',
            'PT-SP-PO-ID',
            'FT-DP-VB-ID',
            'FT-DP-V3P-ID',
            'FT-3P-B2P-ID',
            'PL-C6.7CE',
            'PL-C-9',
            'PT-2P-VP-ID',
            'FT-3P-BP-ID',
            'PSTN-DW',
            'DW-ER',
            'FT-SP-VO-ID',
            'FT-DP-V2P-ID',
            'PL-C-5.6-H',
            'PL-C-8',
            'DW-LH',
            'B-16',
            'PLC-CON',
            'PL-GI-50')
            )";

    $oraconn = OracleConnection();
    $con_comp= oci_parse($oraconn, $sql);
    if(oci_execute($con_comp))
    {
    return $con_comp;
    }
    else
    {
        $err = oci_error($con_comp);
        $e =  $err['message'];
        echo "<script type='text/javascript'>alert('$e')</script>";
    }
}


function getqtyprnalty($a)
{
    $sql= "select distinct a.CON_SO_ID 
    from CONTRACTOR_INV_PASSCHK a, QUALITY_CHECK b 
    where A.CON_SO_ID = B.SOID
    and a.INV_NO = '$a'  
    and a.QTY_REJ_RE = 'REJECTED'  
    and B.STATUS = 'true'";

    $oraconn = OracleConnection();
    $con_comp= oci_parse($oraconn, $sql);
    if(oci_execute($con_comp))
    {
    return $con_comp;
    }
    else
    {
        $err = oci_error($con_comp);
        $e =  $err['message'];
        echo "<script type='text/javascript'>alert('$e')</script>";
    }
}


function insertSEVERTY($a,$b)
{
    $sql= "insert into QUALITY_SEVERTY
            select * from (
            select  A.CON_SO_ID ,A.CON_CIRCUIT_NO ,C.SEVERTY ,a.INV_NO 
            from CONTRACTOR_INV_PASSCHK a, QUALITY_CHECK b , QUALITY_CHK_REF c
            where A.CON_SO_ID = B.SOID
            and  A.CON_SO_ID = '$a'
            and B.REF_ID = C.REF_CODE
            and a.INV_NO = '$b'  
            and a.QTY_REJ_RE = 'REJECTED'  
            and B.STATUS = 'true' 
            order by  A.CON_SO_ID ,case when SEVERTY = 'CRITICAL' then 1
            when SEVERTY = 'MAJOR' then 2
            when SEVERTY = 'MINOR' then 3
            end )
              WHERE ROWNUM = 1";
    
    $oraconn = OracleConnection();
    $con_comp= oci_parse($oraconn, $sql);
    if(oci_execute($con_comp))
    {
    return 0;
    }
    else
    {
        $err = oci_error($con_comp);
        $e =  $err['message'];
        echo "<script type='text/javascript'>alert('$e')</script>";
    }
}


function getsoid($a)
{
    $sql= "select  distinct SOID,VOICENO
            from CONTRACTOR_INV_MET
            where SOID IN (select distinct a.CON_SO_ID 
                from CONTRACTOR_INV_PASSCHK a
                where INV_NO = '$a'  
            union all
            select distinct a.CON_SO_ID 
                from CONTRACTOR_INV_PASSED a
                where INV_NO = '$a' )";

    $oraconn = OracleConnection();
    $con_comp= oci_parse($oraconn, $sql);
    if(oci_execute($con_comp))
    {
    return $con_comp;
    }
    else
    {
        $err = oci_error($con_comp);
        $e =  $err['message'];
        echo "<script type='text/javascript'>alert('$e')</script>";
    }
}


function getvoicemet($a)
{
    $sql= "select  distinct SOID, VOICENO,UNIT_DESIG, P0,P1,SN
            from CONTRACTOR_INV_MET
            where  SOID = '$a'";

    $oraconn = OracleConnection();
    $con_comp= oci_parse($oraconn, $sql);
    if(oci_execute($con_comp))
    {
    return $con_comp;
    }
    else
    {
        $err = oci_error($con_comp);
        $e =  $err['message'];
        echo "<script type='text/javascript'>alert('$e')</script>";
    }
}



function insertMet($a,$b,$c,$d){

    $sql= "INSERT INTO OSS_DEV_01.CONTRACTOR_INVOICE_NUMWISE (SOID,VOICENO,INVNO,GROSS) 
            VALUES('$a','$b','$c','$d')";
     

    $oraconn = OracleConnection();
    $con_comp= oci_parse($oraconn, $sql);
    if(oci_execute($con_comp))
    {
    return 0;
    }
    else
    {
        $err = oci_error($con_comp);
        $e =  $err['message'];
        echo $e;
    }

}


function qtypenelty($a)
{
    $sql= "select CON_CIRCUIT_NO, SEVERTY 
        from QUALITY_SEVERTY
        where INV_NO  = '$a'";

    $oraconn = OracleConnection();
    $con_comp= oci_parse($oraconn, $sql);
    if(oci_execute($con_comp))
    {
    return $con_comp;
    }
    else
    {
        $err = oci_error($con_comp);
        $e =  $err['message'];
        echo "<script type='text/javascript'>alert('$e')</script>";
    }
}


function getcirpen($a,$b)
{
    $sql= "select GROSS
        from CONTRACTOR_INVOICE_NUMWISE
        where INVNO  = '$a' and VOICENO = '$b'";

    $oraconn = OracleConnection();
    $con_comp= oci_parse($oraconn, $sql);
    if(oci_execute($con_comp))
    {
        $row= oci_fetch_array($con_comp);
    return $row[0];
    }
    else
    {
        $err = oci_error($con_comp);
        $e =  $err['message'];
        echo "<script type='text/javascript'>alert('$e')</script>";
    }
}


function setinv($a,$b,$c)
{
    $sql= "select trim(TO_CHAR(max(SEQNO)+1,'000'))
        from CONTRACTOR_INVNO_REF
        where CON_NAME  = '$a' and SER_TYPE = '$b' and AREA= '$c'";

    $oraconn = OracleConnection();
    $con_comp= oci_parse($oraconn, $sql);
    if(oci_execute($con_comp))
    {
        $row= oci_fetch_array($con_comp);
    return $row[0];
    }
    else
    {
        $err = oci_error($con_comp);
        $e =  $err['message'];
        echo "<script type='text/javascript'>alert('$e')</script>";
    }
}


function insertinv($a,$b,$c,$d,$e,$f){

    $sql= "INSERT INTO OSS_DEV_01.CONTRACTOR_INVNO_REF( CON_NAME, SER_TYPE, AREA,SEQNO, INVNO,INVYEAR) VALUES ('$a','$b','$c','$d','$e','$f')";
     

    $oraconn = OracleConnection();
    $con_comp= oci_parse($oraconn, $sql);
    if(oci_execute($con_comp))
    {
    return 0;
    }
    else
    {
        $err = oci_error($con_comp);
        $e =  $err['message'];
        echo $e;
    }

}


function getdelay($a)
{
    $sql= "select distinct CON_CIRCUIT_NO, CON_DLY_DAYS, CON_PENALTY,DELAY_DATE,to_char(CON_SO_COM_DATE, 'mm/dd/yyyy hh:mi:ss AM')
            from CONTRACTOR_INV_PASSCHK a
            where INV_NO = '$a'
            and  CON_DLY_DAYS > 0
            union all
            select  distinct CON_CIRCUIT_NO, CON_DLY_DAYS, CON_PENALTY,DELAY_DATE,to_char(CON_SO_COM_DATE, 'mm/dd/yyyy hh:mi:ss AM')
            from CONTRACTOR_INV_PASSED a
            where INV_NO = '$a'
            and  CON_DLY_DAYS > 0";

    $oraconn = OracleConnection();
    $con_comp= oci_parse($oraconn, $sql);
    if(oci_execute($con_comp))
    {
    return $con_comp;
    }
    else
    {
        $err = oci_error($con_comp);
        $e =  $err['message'];
        echo "<script type='text/javascript'>alert('$e')</script>";
    }
}


function severty($a)
{
    $sql= "select distinct b.CIRCUIT, a.SEVERTY  , C.REF_DESCRIPTION,
        case when A.SEVERTY = 'CRITICAL' THEN d.GROSS WHEN A.SEVERTY = 'MAJOR' THEN '10000.00' WHEN A.SEVERTY = 'MINOR' THEN '5000'  END GROSS
        from QUALITY_SEVERTY  a, QUALITY_CHECK b , QUALITY_CHK_REF c,CONTRACTOR_INVOICE_NUMWISE d
        where a.CON_CIRCUIT_NO = b.CIRCUIT
        and B.REF_ID = C.REF_CODE
        and a.CON_CIRCUIT_NO = d.VOICENO
        and a.SEVERTY = c.SEVERTY
        and b.STATUS = 'true'
        and a.SEVERTY <> 'NA'
        and INV_NO = '$a'
        order by A.SEVERTY";

    $oraconn = OracleConnection();
    $con_comp= oci_parse($oraconn, $sql);
    if(oci_execute($con_comp))
    {
    return $con_comp;
    }
    else
    {
        $err = oci_error($con_comp);
        $e =  $err['message'];
        echo "<script type='text/javascript'>alert('$e')</script>";
    }
}

function getareadesc($a)
{
    $sql ="select distinct DESCR  from CONTRACTOR_REGION where  RTOM = '$a'";
    $oraconn = OracleConnection();
    $area = oci_parse($oraconn, $sql);
    if(oci_execute($area))
    {
        $row= oci_fetch_array($area);
    return $row[0];
    }
    else
    {
        $err = oci_error($area);
        $e =  $err['message'];
        echo "<script type='text/javascript'>alert('$e')</script>";
    }
}

function log_all($usr,$so_id,$msg)
{
    $sql = "INSERT INTO CONTRACTOR_LOG (CON_USER,LOG_DATE,SO_ID,MSG ) 
        VALUES ('$usr',sysdate,'$so_id','$msg')";

    $oraconn = OracleConnection();
    $log_usr = oci_parse($oraconn, $sql);
    if(oci_execute($log_usr))
    {
    return $log_usr;
    }
    else
    {
        $err = oci_error($log_usr);
        $e =  $err['message'];
        echo "<script type='text/javascript'>alert('$e')</script>";
    }
}

function getmetlist($a)
{
    $sql= "SELECT *  FROM (select CON_CIRCUIT_NO ,UNIT_DESIG ,sum(P0) P0 , sum(P1) P1 from (
    SELECT  CON_CIRCUIT_NO ,UNIT_DESIG , P0,P1,SN  FROM CONTRACTOR_INV_MET , 
                            (SELECT CON_SO_ID ,  CON_CIRCUIT_NO FROM CONTRACTOR_INV_PASSCHK   WHERE CON_SO_ID = '$a'
                            UNION
                            SELECT CON_SO_ID ,  CON_CIRCUIT_NO   FROM CONTRACTOR_INV_PASSED   WHERE CON_SO_ID  = '$a'
                            )
                            WHERE CON_SO_ID = SOID(+)
                            AND CON_CIRCUIT_NO = VOICENO(+)
                            ORDER BY VOICENO ) group by CON_CIRCUIT_NO ,UNIT_DESIG)  X
                            PIVOT
                            (
                            SUM(P0+P1)
                            FOR UNIT_DESIG
                            IN ('DW-EW',
    'FTTH-DW',
    'EX-IPTV',
    'PL-C-5.6-CE',
    'PL-C-6.7',
    'PL-C-7.5',
    'PT-SP-VO-ID',
    'PT-2P-VB-ID',
    'PT-3P-BP-ID',
    'FT-DP-VP-ID',
    'FT-SP-PO-ID',
    'PL-C-5.6-L',
    'DW-DF',
    'TL-WM-D-25',
    'SC-C5',
    'PT-SP-PO-ID',
    'FT-DP-VB-ID',
    'FT-DP-V3P-ID',
    'FT-3P-B2P-ID',
    'PL-C6.7CE',
    'PL-C-9',
    'PT-2P-VP-ID',
    'FT-3P-BP-ID',
    'PSTN-DW',
    'DW-ER',
    'FT-SP-VO-ID',
    'FT-DP-V2P-ID',
    'PL-C-5.6-H',
    'PL-C-8',
    'DW-LH',
    'B-16',
    'PLC-CON',
    'PL-GI-50'))ORDER BY CON_CIRCUIT_NO";

    $oraconn = OracleConnection();
    $con_comp= oci_parse($oraconn, $sql);
    if(oci_execute($con_comp))
    {
    return $con_comp;
    }
    else
    {
        $err = oci_error($con_comp);
        $e =  $err['message'];
        echo "<script type='text/javascript'>alert('$e')</script>";
    }
}

function inv_meterial($so_id)
{
    $sql ="select * from CONTRACTOR_INV_MET where SOID = '{$so_id}'";
    $oraconn = OracleConnection();
    $ncm = oci_parse($oraconn, $sql);
    if(oci_execute($ncm))
    {
    return $ncm;
    }
    else
    {
        $err = oci_error($ncm);
        $e =  $err['message'];
        echo "<script type='text/javascript'>alert('$e')</script>";
    }
}

if(isset($_POST["q"]) && $_POST["q"]== 'updatemetinv' ){
$met=$_POST["met"];
$P0=$_POST["P0"];
$P1=$_POST["P1"];
$ser=$_POST["ser"];
$cir=$_POST["cir"];
$metid=$_POST["metid"];
$sod=$_POST["sod"];


  $sql= "update OSS_DEV_01.CONTRACTOR_INV_MET set P0 = '$P0' ,P1 = '$P1' where  MET_ID = '$metid' and UNIT_DESIG = '$met' ";
      

     $msg =  'invoice meterial update UNIT_DESIG: '.$met.' P0: '.$P0.' P1: '.$P1;  

    $oraconn = OracleConnection();
    $con_comp= oci_parse($oraconn, $sql);
    if(oci_execute($con_comp))
    {
        log_all($user,$sod,$msg);
    echo  "success";
   
    }
    else
    {
        $err = oci_error($con_comp);
        $e =  $err['message'];
        echo $e;
    }

}


if(isset($_POST["q"]) && $_POST["q"]== 'deletemetinv' ){
$met=$_POST["met"];    
$ser=$_POST["ser"];
$cir=$_POST["cir"];
$metid=$_POST["metid"];
$sod=$_POST["sod"];


  $sql= "delete from OSS_DEV_01.CONTRACTOR_INV_MET where  MET_ID = '$metid' ";
      

   $msg =  'invoice meterial delete UNIT_DESIG: '.$met;      

    $oraconn = OracleConnection();
    $con_comp= oci_parse($oraconn, $sql);
    if(oci_execute($con_comp))
    {
        log_all($user,$sod,$msg);
    echo  "success";
   
    }
    else
    {
        $err = oci_error($con_comp);
        $e =  $err['message'];
        echo $e;
    }

}

if(isset($_POST["q"]) && $_POST["q"]== 'addmetinv' ){
$met=$_POST["met"];
$sn=$_POST["sn"];
$type=$_POST["type"];
$ser=$_POST["ser"];
$cir=$_POST["cir"];
$sod=$_POST["sod"];


    if($type == 'P0'){
    $sql= "INSERT INTO OSS_DEV_01.CONTRACTOR_INV_MET(SOID, VOICENO,UNIT_DESIG,P0,P1,SN,MET_ID)
         VALUES ('$sod','$cir','$met','1','0','$sn',MET_SEQ.nextval)";
    }
    if($type == 'P1'){
    $sql= "INSERT INTO OSS_DEV_01.CONTRACTOR_INV_MET(SOID, VOICENO,UNIT_DESIG,P0,P1,SN,MET_ID)
         VALUES ('$sod','$cir','$met','1','0','$sn',MET_SEQ.nextval)";
    }
          
    
        $msg= 'invocie Meterial Insert UNIT_DESIG: '.$met;

    $oraconn = OracleConnection();
    $con_comp= oci_parse($oraconn, $sql);
    if(oci_execute($con_comp))
    {
    log_all($user,$sod,$msg);
    echo  "success";

    }
    else
    {
        $err = oci_error($con_comp);
        $e =  $err['message'];
        echo $e;
    }

}



function getftthcab($a)
{
    $sql ="select distinct a.SO_NUM,a.S_TYPE,to_char(b.CON_SO_DATE_RECEIVED, 'mm/dd/yyyy hh:mi:ss AM'),A.VOICENUMBER,B.CON_AREA, C.CON_WORO_ORDER_TYPE,
        C.CON_CUS_NAME,C.CON_TEC_CONTACT, d.CON_ADDE_STREETNUMBER||' '||d.CON_ADDE_STRN_NAMEANDTYPE||' '||d.CON_ADDE_SUBURB||' '||d.CON_ADDE_CITY,
        e.CON_OSP_DP_NAME,e.CON_OSP_DP_LOOP,e.CON_OSP_PHONE_CLASS,e.CON_OSP_PHONE_COLOUR,e.CON_PHN_PURCH,c.CON_WORO_DISCRIPTION,c.CON_WORO_TASK_NAME
        from CON_CLARITY_SOLIST a, CONTRACTOR_NEW_CON b, CONTRACTOR_WORK_ORDERS c,CONTRACTOR_SERVICE_ADDRESS d,CONTRACTOR_OSP_DATA e
        where A.SO_NUM = C.CON_SERO_ID
        and B.CON_SO_ID = C.CON_SERO_ID
        and B.CON_SO_ID = D.CON_ADDE_SERO_ID(+)
        and B.CON_SO_ID = E.CON_OSP_SERO_ID(+)
        and A.SO_NUM ='$a'
        and C.CON_STATUS IN ('ASSIGNED', 'INPROGRESS', 'REASSIGNED','DELAYED','HOLD')
        union all
select distinct B.CON_SO_ID ,c.CON_WORO_SERVICE_TYPE ,to_char(b.CON_SO_DATE_RECEIVED, 'mm/dd/yyyy hh:mi:ss AM'),b.CON_CIRCUIT_NO ,B.CON_AREA, C.CON_WORO_ORDER_TYPE,
        C.CON_CUS_NAME,C.CON_TEC_CONTACT, d.CON_ADDE_STREETNUMBER||' '||d.CON_ADDE_STRN_NAMEANDTYPE||' '||d.CON_ADDE_SUBURB||' '||d.CON_ADDE_CITY,
        e.CON_OSP_DP_NAME,e.CON_OSP_DP_LOOP,e.CON_OSP_PHONE_CLASS,e.CON_OSP_PHONE_COLOUR,e.CON_PHN_PURCH,c.CON_WORO_DISCRIPTION,c.CON_WORO_TASK_NAME
        from CONTRACTOR_NEW_CON b, CONTRACTOR_WORK_ORDERS c,CONTRACTOR_SERVICE_ADDRESS d,CONTRACTOR_OSP_DATA e
        where B.CON_SO_ID = C.CON_SERO_ID
        and B.CON_SO_ID = D.CON_ADDE_SERO_ID(+)
        and B.CON_SO_ID = E.CON_OSP_SERO_ID(+)
        and B.CON_SO_ID ='$a'
        and C.CON_STATUS IN ('ASSIGNED', 'INPROGRESS', 'REASSIGNED','DELAYED','HOLD')";
        
    $oraconn = OracleConnection();
    $area = oci_parse($oraconn, $sql);
    if(oci_execute($area))
    {
    return $area;
    }
    else
    {
        $err = oci_error($area);
        $e =  $err['message'];
        echo "<script type='text/javascript'>alert('$e')</script>";
    }
}


function getftthcabque($a)
{
    $sql ="select distinct * from ( 
    select distinct a.SO_NUM,a.S_TYPE,to_char(b.CON_SO_DATE_RECEIVED, 'mm/dd/yyyy hh:mi:ss AM') receive,B.CON_CIRCUIT_NO,B.CON_AREA, C.CON_WORO_ORDER_TYPE,
        C.CON_CUS_NAME,C.CON_TEC_CONTACT, d.CON_ADDE_STREETNUMBER||' '||d.CON_ADDE_STRN_NAMEANDTYPE||' '||d.CON_ADDE_SUBURB||' '||d.CON_ADDE_CITY,
        e.CON_OSP_DP_NAME,e.CON_OSP_DP_LOOP,e.CON_OSP_PHONE_CLASS,e.CON_OSP_PHONE_COLOUR,e.CON_PHN_PURCH,C.CON_STATUS,c.CON_WORO_DISCRIPTION,c.CON_WORO_TASK_NAME,b.CON_CONTRACTOR,b.CON_APPROVE,
        to_char(b.CON_SO_COM_DATE, 'mm/dd/yyyy hh:mi:ss AM'),to_char(b.CON_SO_RTN_DATE, 'mm/dd/yyyy hh:mi:ss AM') 
        from CON_CLARITY_SOLIST a, CONTRACTOR_NEW_CON b, CONTRACTOR_WORK_ORDERS c,CONTRACTOR_SERVICE_ADDRESS d,CONTRACTOR_OSP_DATA e
        where A.SO_NUM = C.CON_SERO_ID
        and B.CON_SO_ID = C.CON_SERO_ID
        and B.CON_SO_ID = D.CON_ADDE_SERO_ID
        and c.CON_WORO_TASK_NAME = b.CON_TASK
        and B.CON_SO_ID = E.CON_OSP_SERO_ID(+)
        and A.SO_NUM ='$a'
        and C.CON_STATUS IN ('ASSIGNED', 'INPROGRESS', 'COMPLETED', 'RETURNED', 'RE_RETURNED')
        and b.CON_SO_DATE_RECEIVED = (SELECT MAX(CON_SO_DATE_RECEIVED)
              FROM CONTRACTOR_NEW_CON
              WHERE CON_SO_ID='$a')
        union all
        select distinct B.CON_SO_ID ,c.CON_WORO_SERVICE_TYPE ,to_char(b.CON_SO_DATE_RECEIVED, 'mm/dd/yyyy hh:mi:ss AM') receive,b.CON_CIRCUIT_NO ,B.CON_AREA, C.CON_WORO_ORDER_TYPE,
        C.CON_CUS_NAME,C.CON_TEC_CONTACT, d.CON_ADDE_STREETNUMBER||' '||d.CON_ADDE_STRN_NAMEANDTYPE||' '||d.CON_ADDE_SUBURB||' '||d.CON_ADDE_CITY,
        e.CON_OSP_DP_NAME,e.CON_OSP_DP_LOOP,e.CON_OSP_PHONE_CLASS,e.CON_OSP_PHONE_COLOUR,e.CON_PHN_PURCH,C.CON_STATUS,c.CON_WORO_DISCRIPTION,c.CON_WORO_TASK_NAME,b.CON_CONTRACTOR,b.CON_APPROVE,
        to_char(b.CON_SO_COM_DATE, 'mm/dd/yyyy hh:mi:ss AM'),to_char(b.CON_SO_RTN_DATE, 'mm/dd/yyyy hh:mi:ss AM')
        from CONTRACTOR_NEW_CON b, CONTRACTOR_WORK_ORDERS c,CONTRACTOR_SERVICE_ADDRESS d,CONTRACTOR_OSP_DATA e
        where B.CON_SO_ID = C.CON_SERO_ID
        and B.CON_SO_ID = D.CON_ADDE_SERO_ID
        and c.CON_WORO_TASK_NAME = b.CON_TASK
        and B.CON_SO_ID = E.CON_OSP_SERO_ID(+)
        and B.CON_SO_ID ='$a'
        and C.CON_STATUS IN ('ASSIGNED', 'INPROGRESS', 'COMPLETED', 'RETURNED', 'RE_RETURNED')
        and b.CON_SO_DATE_RECEIVED = (SELECT MAX(CON_SO_DATE_RECEIVED)
              FROM CONTRACTOR_NEW_CON
              WHERE CON_SO_ID='$a'))
        ";
    
        
    $oraconn = OracleConnection();
    $area = oci_parse($oraconn, $sql);
    if(oci_execute($area))
    {
    return $area;
    }
    else
    {
        $err = oci_error($area);
        $e =  $err['message'];
        echo "<script type='text/javascript'>alert('$e')</script>";
    }
}

function getftthcabclose($a)
{
    $sql ="select distinct a.SO_NUM,a.S_TYPE,to_char(b.CON_SO_DATE_RECEIVED, 'mm/dd/yyyy hh:mi:ss AM'),A.VOICENUMBER,B.CON_AREA, C.CON_WORO_ORDER_TYPE,
        C.CON_CUS_NAME,C.CON_TEC_CONTACT, d.CON_ADDE_STREETNUMBER||' '||d.CON_ADDE_STRN_NAMEANDTYPE||' '||d.CON_ADDE_SUBURB||' '||d.CON_ADDE_CITY,
        e.CON_OSP_DP_NAME,e.CON_OSP_DP_LOOP,e.CON_OSP_PHONE_CLASS,e.CON_OSP_PHONE_COLOUR,e.CON_PHN_PURCH,c.CON_WORO_DISCRIPTION,c.CON_WORO_TASK_NAME,
        b.CON_PENALTY,b.CON_DLY_DAYS,to_char(b.CON_SO_COM_DATE, 'mm/dd/yyyy hh:mi:ss AM'),b.CON_APPROVE
        from CON_CLARITY_SOLIST a, CONTRACTOR_NEW_CON b, CONTRACTOR_WORK_ORDERS c,CONTRACTOR_SERVICE_ADDRESS d,CONTRACTOR_OSP_DATA e
        where A.SO_NUM = C.CON_SERO_ID
        and B.CON_SO_ID = C.CON_SERO_ID
        and B.CON_SO_ID = D.CON_ADDE_SERO_ID
        and B.CON_SO_ID = E.CON_OSP_SERO_ID(+)
        and A.SO_NUM ='$a'
        and b.CON_STATUS = '2'
        and b.CON_APPROVE is null
        and C.CON_STATUS = 'COMPLETED'
        union all
        select distinct B.CON_SO_ID ,c.CON_WORO_SERVICE_TYPE ,to_char(b.CON_SO_DATE_RECEIVED, 'mm/dd/yyyy hh:mi:ss AM'),b.CON_CIRCUIT_NO ,B.CON_AREA, C.CON_WORO_ORDER_TYPE,
        C.CON_CUS_NAME,C.CON_TEC_CONTACT, d.CON_ADDE_STREETNUMBER||' '||d.CON_ADDE_STRN_NAMEANDTYPE||' '||d.CON_ADDE_SUBURB||' '||d.CON_ADDE_CITY,
        e.CON_OSP_DP_NAME,e.CON_OSP_DP_LOOP,e.CON_OSP_PHONE_CLASS,e.CON_OSP_PHONE_COLOUR,e.CON_PHN_PURCH,c.CON_WORO_DISCRIPTION,c.CON_WORO_TASK_NAME ,b.CON_PENALTY,b.CON_DLY_DAYS,to_char(b.CON_SO_COM_DATE, 'mm/dd/yyyy hh:mi:ss AM'),b.CON_APPROVE
        from CONTRACTOR_NEW_CON b, CONTRACTOR_WORK_ORDERS c,CONTRACTOR_SERVICE_ADDRESS d,CONTRACTOR_OSP_DATA e
        where B.CON_SO_ID = C.CON_SERO_ID
        and B.CON_SO_ID = D.CON_ADDE_SERO_ID
        and B.CON_SO_ID = E.CON_OSP_SERO_ID(+)
        and B.CON_SO_ID ='$a'
        and C.CON_STATUS = 'COMPLETED'
        and b.CON_STATUS = '2'
        and b.CON_APPROVE is null";
        
    $oraconn = OracleConnection();
    $area = oci_parse($oraconn, $sql);
    if(oci_execute($area))
    {
    return $area;
    }
    else
    {
        $err = oci_error($area);
        $e =  $err['message'];
        echo "<script type='text/javascript'>alert('$e')</script>";
    }
}


function getftthcabcloseslt($a)
{
    $sql ="select distinct a.SO_NUM,a.S_TYPE,to_char(b.CON_SO_DATE_RECEIVED, 'mm/dd/yyyy hh:mi:ss AM'),A.VOICENUMBER,B.CON_AREA, C.CON_WORO_ORDER_TYPE,
        C.CON_CUS_NAME,C.CON_TEC_CONTACT, d.CON_ADDE_STREETNUMBER||' '||d.CON_ADDE_STRN_NAMEANDTYPE||' '||d.CON_ADDE_SUBURB||' '||d.CON_ADDE_CITY,
        e.CON_OSP_DP_NAME,e.CON_OSP_DP_LOOP,e.CON_OSP_PHONE_CLASS,e.CON_OSP_PHONE_COLOUR,e.CON_PHN_PURCH,c.CON_WORO_DISCRIPTION,c.CON_WORO_TASK_NAME,
        b.CON_PENALTY,b.CON_DLY_DAYS,to_char(b.CON_SO_COM_DATE, 'mm/dd/yyyy hh:mi:ss AM'),b.CON_APPROVE
        from CON_CLARITY_SOLIST a, CONTRACTOR_NEW_CON b, CONTRACTOR_WORK_ORDERS c,CONTRACTOR_SERVICE_ADDRESS d,CONTRACTOR_OSP_DATA e
        where A.SO_NUM = C.CON_SERO_ID
        and B.CON_SO_ID = C.CON_SERO_ID
        and B.CON_SO_ID = D.CON_ADDE_SERO_ID
        and B.CON_SO_ID = E.CON_OSP_SERO_ID(+)
        and A.SO_NUM ='$a'
        and b.CON_STATUS = '2'
        and C.CON_STATUS = 'COMPLETED'
		and b.QTY_ST is null
        union all
        select distinct B.CON_SO_ID ,c.CON_WORO_SERVICE_TYPE ,to_char(b.CON_SO_DATE_RECEIVED, 'mm/dd/yyyy hh:mi:ss AM'),b.CON_CIRCUIT_NO ,B.CON_AREA, C.CON_WORO_ORDER_TYPE,
        C.CON_CUS_NAME,C.CON_TEC_CONTACT, d.CON_ADDE_STREETNUMBER||' '||d.CON_ADDE_STRN_NAMEANDTYPE||' '||d.CON_ADDE_SUBURB||' '||d.CON_ADDE_CITY,
        e.CON_OSP_DP_NAME,e.CON_OSP_DP_LOOP,e.CON_OSP_PHONE_CLASS,e.CON_OSP_PHONE_COLOUR,e.CON_PHN_PURCH,c.CON_WORO_DISCRIPTION,c.CON_WORO_TASK_NAME ,b.CON_PENALTY,b.CON_DLY_DAYS,to_char(b.CON_SO_COM_DATE, 'mm/dd/yyyy hh:mi:ss AM'),b.CON_APPROVE
        from CONTRACTOR_NEW_CON b, CONTRACTOR_WORK_ORDERS c,CONTRACTOR_SERVICE_ADDRESS d,CONTRACTOR_OSP_DATA e
        where B.CON_SO_ID = C.CON_SERO_ID
        and B.CON_SO_ID = D.CON_ADDE_SERO_ID
        and B.CON_SO_ID = E.CON_OSP_SERO_ID(+)
        and B.CON_SO_ID ='$a'
        and C.CON_STATUS = 'COMPLETED'
        and b.CON_STATUS = '2'
		and b.QTY_ST is null";
        
    $oraconn = OracleConnection();
    $area = oci_parse($oraconn, $sql);
    if(oci_execute($area))
    {
    return $area;
    }
    else
    {
        $err = oci_error($area);
        $e =  $err['message'];
        echo "<script type='text/javascript'>alert('$e')</script>";
    }
}

function getiptv($a)
{
    $sql ="select a.SO_NUM,a.S_TYPE,to_char(b.CON_SO_DATE_RECEIVED, 'mm/dd/yyyy hh:mi:ss AM'),B.CON_CIRCUIT_NO,B.CON_AREA, C.CON_WORO_ORDER_TYPE,
            e.CON_EQ_INDEX,e.CON_EQ_LOC_NAME,e.CON_EQ_CARD,e.CON_EQ_PORT,e.CON_PHN_PURCH,b.CON_SO_STATUS
            from CON_CLARITY_SOLIST a, CONTRACTOR_NEW_CON b, CONTRACTOR_WORK_ORDERS c,CONTRACTOR_EQ_DATA e
            where A.SO_NUM = C.CON_SERO_ID
            and B.CON_SO_ID = C.CON_SERO_ID
            and B.CON_SO_ID = E.CON_EQ_SERO_ID
            and A.VOICENUMBER ='$a'
            and A.S_TYPE like '%IPTV%'
            and a.STATUS = '1'";
    $oraconn = OracleConnection();
    $area = oci_parse($oraconn, $sql);
    if(oci_execute($area))
    {
    return $area;
    }
    else
    {
        $err = oci_error($area);
        $e =  $err['message'];
        echo "<script type='text/javascript'>alert('$e')</script>";
    }
}


function chksod($a)
{
    $sql ="select IPTV from CON_CLARITY_SOLIST where SO_NUM = '$a' and STATUS = '1' and S_TYPE like '%IPTV%'";
    $oraconn = OracleConnection();
    $area = oci_parse($oraconn, $sql);
    if(oci_execute($area))
    {
        $row = oci_fetch_array($area);
    return $row[0];
    }
    else
    {
        $err = oci_error($area);
        $e =  $err['message'];
        echo "<script type='text/javascript'>alert('$e')</script>";
    }
}

function chkvoice($a)
{
    $sql ="select VOICENUMBER
            from CON_CLARITY_SOLIST
            where SO_NUM = '$a'
            and IPTV is null
            and S_TYPE like '%IPTV%'";
    $oraconn = OracleConnection();
    $area = oci_parse($oraconn, $sql);
    if(oci_execute($area))
    {
        $row = oci_fetch_array($area);
    return $row[0];
    }
    else
    {
        $err = oci_error($area);
        $e =  $err['message'];
        echo "<script type='text/javascript'>alert('$e')</script>";
    }
}

function getftthcabiptv($a)
{
    $sql ="select a.SO_NUM
        from CON_CLARITY_SOLIST a
        where A.VOICENUMBER ='$a'
        and (a.S_TYPE = 'AB-CAB' or  a.S_TYPE = 'AB-FTTH')
        and a.STATUS = '1'";
    $oraconn = OracleConnection();
    $area = oci_parse($oraconn, $sql);
    if(oci_execute($area))
    {
    $row = oci_fetch_array($area);
    return $row[0];
    }
    else
    {
        $err = oci_error($area);
        $e =  $err['message'];
        echo "<script type='text/javascript'>alert('$e')</script>";
    }
}

function getsod($a)
{
    $sql ="select distinct S_TYPE from CON_CLARITY_SOLIST where SO_NUM = '$a' and STATUS IN ( '1','8')";
    $oraconn = OracleConnection();
    $area = oci_parse($oraconn, $sql);
    if(oci_execute($area))
    {
        $row = oci_fetch_array($area);
    return $row[0];
    }
    else
    {
        $err = oci_error($area);
        $e =  $err['message'];
        echo "<script type='text/javascript'>alert('$e')</script>";
    }
}

function chkwo($a)
{
    $sql ="select CON_WORO_ID from CONTRACTOR_WORK_ORDERS where CON_SERO_ID = '$a' order by CON_DATE_TO_CONTRACTOR desc";
    $oraconn = OracleConnection();
    $area = oci_parse($oraconn, $sql);
    if(oci_execute($area))
    {
        $row = oci_fetch_array($area);
    return $row[0];
    }
    else
    {
        $err = oci_error($area);
        $e =  $err['message'];
        echo "<script type='text/javascript'>alert('$e')</script>";
    }
}


function getcon($a)
{
    $sql ="select CON_NEW_CON_TYPE from CONTRACTOR_NEW_CON where CON_SO_ID = '$a' and CON_SO_STATUS IN ( 'INPROGRESS', 'COMPLETED') order by CON_SO_DATE_RECEIVED desc";
    $oraconn = OracleConnection();
    $area = oci_parse($oraconn, $sql);
    if(oci_execute($area))
    {
        
    return $area;
    }
    else
    {
        $err = oci_error($area);
        $e =  $err['message'];
        echo "<script type='text/javascript'>alert('$e')</script>";
    }
}

if(isset($_POST["q"]) && $_POST["q"]== 'updatecon' ){

$sod=$_POST["sod"];
$contype=$_POST["contype"];

  $sql= "update CONTRACTOR_NEW_CON set CON_NEW_CON_TYPE = '$contype' where CON_SO_ID = '$sod' and CON_SO_STATUS IN ( 'INPROGRESS','REASSIGNED') ";
    
    $oraconn = OracleConnection();
    $con_comp= oci_parse($oraconn, $sql);
    if(oci_execute($con_comp))
    {
      log_all($user,$sod,'Connection Type Updated');
   // echo  "success";
echo $sql;
    }
    else
    {
        $err = oci_error($con_comp);
        $e =  $err['message'];
        echo $e;
    }

}

if(isset($_POST["q"]) && $_POST["q"]== 'updateconclose' ){

$sod=$_POST["sod"];
$contype=$_POST["contype"];

  $sql= "update CONTRACTOR_NEW_CON set CON_NEW_CON_TYPE = '$contype' where CON_SO_ID = '$sod' and CON_SO_STATUS = 'COMPLETED' and CON_STATUS = '2' ";
    
    $oraconn = OracleConnection();
    $con_comp= oci_parse($oraconn, $sql);
    if(oci_execute($con_comp))
    {
      log_all($user,$sod,'Connection Type Updated');
   // echo  "success";
    }
    else
    {
        $err = oci_error($con_comp);
        $e =  $err['message'];
        echo $e;
    }

}



function getpole()
{
    $sql ="select UNIT_DESIG from CONTRACTOR_UD where UNIT_DESIG like 'PL-%'";
    $oraconn = OracleConnection();
    $area = oci_parse($oraconn, $sql);
    if(oci_execute($area))
    {
        
    return $area;
    }
    else
    {
        $err = oci_error($area);
        $e =  $err['message'];
        echo "<script type='text/javascript'>alert('$e')</script>";
    }
}


function getothmetcab($a)
{
    
    if($a == 'Triple Play'){
        $sql ="select UNIT_DESIG from CONTRACTOR_UD where STYPE = 'CAB' and UNIT_DESIG <> 'PSTN-DW' and  CAB_T = 'Y'"; 
    }else if($a == 'Double Play - PeoTV' || $a == 'Double Play - BB'){
        $sql ="select UNIT_DESIG from CONTRACTOR_UD where STYPE = 'CAB' and UNIT_DESIG <> 'PSTN-DW' and  CAB_D = 'Y'"; 
    }else{
        $sql ="select UNIT_DESIG from CONTRACTOR_UD where STYPE = 'CAB' and UNIT_DESIG <> 'PSTN-DW' and  CAB_S = 'Y'"; 
    }
    
    $oraconn = OracleConnection();
    $area = oci_parse($oraconn, $sql);
    if(oci_execute($area))
    {
        
    return $area;
    }
    else
    {
        $err = oci_error($area);
        $e =  $err['message'];
        echo "<script type='text/javascript'>alert('$e')</script>";
    }
}

function getothmetiptv()
{
    
        $sql ="select UNIT_DESIG from CONTRACTOR_UD where STYPE = 'IPTV' and UNIT_DESIG <> 'EX-IPTV'"; 
    
    
    $oraconn = OracleConnection();
    $area = oci_parse($oraconn, $sql);
    if(oci_execute($area))
    {
        
    return $area;
    }
    else
    {
        $err = oci_error($area);
        $e =  $err['message'];
        echo "<script type='text/javascript'>alert('$e')</script>";
    }
}

function getothmetftth($a)
{
    if($a == 'Triple Play'){
        $sql ="select UNIT_DESIG from CONTRACTOR_UD where STYPE = 'FTTH' and UNIT_DESIG <> 'FTTH-DW' and  FTTH_T = 'Y'"; 
    }else if($a == 'Double Play - PeoTV' || $a == 'Double Play - BB'){
        $sql ="select UNIT_DESIG from CONTRACTOR_UD where STYPE = 'FTTH' and UNIT_DESIG <> 'FTTH-DW' and  FTTH_D = 'Y'"; 
    }else{
        $sql ="select UNIT_DESIG from CONTRACTOR_UD where STYPE = 'FTTH' and UNIT_DESIG <> 'FTTH-DW' and  FTTH_S = 'Y'"; 
    }
    
    $oraconn = OracleConnection();
    $area = oci_parse($oraconn, $sql);
    if(oci_execute($area))
    {
        
    return $area;
    }
    else
    {
        $err = oci_error($area);
        $e =  $err['message'];
        echo "<script type='text/javascript'>alert('$e')</script>";
    }
}


//function so_com_update($so_id,$days,$penalty,$con_type,$pl_count,$dploopcom,$tstnum,$polesn,$delday)
if(isset($_POST["q"]) && $_POST["q"]== 'updatesod' ){

$so_id = $_POST["sod"];
$days = $_POST["delay"];
$penalty = $_POST["penalty"];
$dploopcom = $_POST["dploopcom"];
$delday = $_POST["delday"];
$plcnt = $_POST["plcnt"];
  
    $sql = "update  CONTRACTOR_NEW_CON set CON_SO_COM_DATE = sysdate, CON_SO_STATUS = 'COMPLETED', CON_DLY_DAYS = '{$days}',
            CON_PENALTY = '{$penalty}',  CON_STATUS = '2' , POLE_COUNT = '{$plcnt}',
            DELAY_DATE= '{$delday}' where CON_SO_ID = '{$so_id}' and CON_SO_STATUS = 'INPROGRESS'";

    $oraconn = OracleConnection();
    $com = oci_parse($oraconn, $sql);
   if( oci_execute($com))
   {       
    $sql = "update  CONTRACTOR_WORK_ORDERS set CON_STATUS_DATE = sysdate, CON_STATUS = 'COMPLETED' , DP_LOOP_COMMENT='$dploopcom'
     where CON_SERO_ID = '{$so_id}' and CON_STATUS = 'INPROGRESS'";

    $oraconn = OracleConnection();
    $com1 = oci_parse($oraconn, $sql);
   if(oci_execute($com1))
   {
    log_all($user,$so_id,'SOD Completed');
    echo  "success";
   }
   else
    {
        $err = oci_error($com1);
        $e =  $err['message'];
        echo "<script type='text/javascript'>alert('$e')</script>";
    }
 }
   else
    {
        $err = oci_error($com);
        $e =  $err['message'];
        echo "<script type='text/javascript'>alert('$e')</script>";
    }
}


if(isset($_POST["q"]) && $_POST["q"]== 'updateIPTVsod' ){

$so_id = $_POST["sod"];
$setsereial = $_POST["setsereial"];
$setmanufac = $_POST["setmanufac"];
$setmodel = $_POST["setmodel"];
$setwarrent = $_POST["setwarrent"];
$iptvhw = $_POST["iptvhw"];
$iptvhwser = $_POST["iptvhwser"];
  
     $iptv_data = iptv_data($so_id,$setsereial,$setmanufac,$setmodel,$setwarrent,$iptvhw,$iptvhwser);
  
    $sql = "update  CONTRACTOR_NEW_CON set CON_SO_COM_DATE = sysdate, CON_SO_STATUS = 'COMPLETED', CON_STATUS = '2' where CON_SO_ID = '{$so_id}' and (CON_SO_STATUS = 'INPROGRESS' or CON_SO_STATUS = 'ASSIGNED')";

    $oraconn = OracleConnection();
    $com = oci_parse($oraconn, $sql);
   if( oci_execute($com))
   {       
    $sql = "update  CONTRACTOR_WORK_ORDERS set CON_STATUS_DATE = sysdate, CON_STATUS = 'COMPLETED' where CON_SERO_ID = '{$so_id}' and (CON_STATUS = 'INPROGRESS' or  CON_STATUS = 'ASSIGNED')";

    $oraconn = OracleConnection();
    $com1 = oci_parse($oraconn, $sql);
   if(oci_execute($com1))
   {
   
    log_all($user,$so_id,'IPTV SOD Completed');
    echo  "success";
   }
   else
    {
        $err = oci_error($com1);
        $e =  $err['message'];
        echo "<script type='text/javascript'>alert('$e')</script>";
    }
 }
   else
    {
        $err = oci_error($com);
        $e =  $err['message'];
        echo "<script type='text/javascript'>alert('$e')</script>";
    }
}


if(isset($_POST["q"]) && $_POST["q"]== 'ReturnSOD' ){

$so_id = $_POST["sod"];
$othreason= $_POST["othreason"];
$reason = $_POST["reason"];
  
  
    $sql = "";

    $oraconn = OracleConnection();
    $com = oci_parse($oraconn, $sql);
   if( oci_execute($com))
   {       
    log_all($user,$so_id,'SOD Returned');
    echo  "success";
     }
   else
    {
        $err = oci_error($com);
        $e =  $err['message'];
        echo "<script type='text/javascript'>alert('$e')</script>";
    }
}


if(isset($_POST["q"]) && $_POST["q"]== 'RETSODIPTV' ){

$so_id = $_POST["sod"];
$reason = $_POST["reason"];
$task = $_POST["task"];
  
$sql = "insert into CONTRACTOR_ORDER_COMMENTS (CON_COMM_SERO_ID,CON_COMM_TEXT,CON_COMM_TIME,CON_COMM_USER,CON_COMM_STATUS) 
        values ('$so_id', '$reason', sysdate , '$user','RETURNED' )";
    $oraconn = OracleConnection();
    $return2 = oci_parse($oraconn, $sql);
    if(oci_execute($return2))
    {
		if($task == 'RE_INSTALL STB')
		{
			$sql = "update  CONTRACTOR_NEW_CON set CON_SO_STATUS = 'RE_RETURNED', CON_SO_RTN_DATE = sysdate  where CON_SO_ID = '{$so_id}' and CON_TASK = 'RE_INSTALL STB'";
		}else{
			$sql = "update  CONTRACTOR_NEW_CON set CON_SO_STATUS = 'RETURNED', CON_SO_RTN_DATE = sysdate  where CON_SO_ID = '{$so_id}' and CON_TASK = 'INSTALL STBS'";
		}
		
    
    $oraconn = OracleConnection();
    $return = oci_parse($oraconn, $sql);
    if(oci_execute($return))
    {
		if($task == 'RE_INSTALL STB')
		{
			$sql = "update  CONTRACTOR_WORK_ORDERS set CON_STATUS = 'RE_RETURNED', CON_STATUS_DATE = sysdate where CON_SERO_ID = '{$so_id}' and CON_WORO_TASK_NAME = 'RE_INSTALL STB'";
		}else{
			$sql = "update  CONTRACTOR_WORK_ORDERS set CON_STATUS = 'RETURNED', CON_STATUS_DATE = sysdate where CON_SERO_ID = '{$so_id}' and CON_WORO_TASK_NAME = 'INSTALL STBS'";
		}	
    
    $oraconn = OracleConnection();
    $return1 = oci_parse($oraconn, $sql);
    if(oci_execute($return1))
    {
    echo  "success";
    }
    else
    {
        $err = oci_error($return1);
        $e =  $err['message'];
        echo "<script type='text/javascript'>alert('$e')</script>";
    }
    }
    else
    {
        $err = oci_error($return);
        $e =  $err['message'];
        echo "<script type='text/javascript'>alert('$e')</script>";
    }
    }
    else
    {
        $err = oci_error($return2);
        $e =  $err['message'];
        echo "<script type='text/javascript'>alert('$e')</script>";
    }
}



if(isset($_POST["q"]) && $_POST["q"]== 'RETSOD' ){

$so_id = $_POST["sod"];
$reason = $_POST["reason"];
$othreason = $_POST["othreason"];
$task = $_POST["task"];


if($reason == "Other" || $reason == "OVER DISTANCE")
{
$reason = $reason.' - '.$othreason;
}
else 
{
     $return = so_return($so_id,$reason,$user);
 log_returned($user,$so_id,$reason);
}


if($task == 'RECONSTRUCT OSP'){  
$sql = "insert into CONTRACTOR_ORDER_COMMENTS (CON_COMM_SERO_ID,CON_COMM_TEXT,CON_COMM_TIME,CON_COMM_USER,CON_COMM_STATUS) 
        values ('$so_id', '$reason', sysdate , '$user','RE_RETURNED' )";
}else{
    $sql = "insert into CONTRACTOR_ORDER_COMMENTS (CON_COMM_SERO_ID,CON_COMM_TEXT,CON_COMM_TIME,CON_COMM_USER,CON_COMM_STATUS) 
        values ('$so_id', '$reason', sysdate , '$user','RETURNED' )";
    
}        
    $oraconn = OracleConnection();
    $return2 = oci_parse($oraconn, $sql);
   if($task == 'RECONSTRUCT OSP'){
            if(oci_execute($return2))
            {
            $sql = "update  CONTRACTOR_NEW_CON set CON_SO_STATUS = 'RE_RETURNED', CON_SO_RTN_DATE = sysdate  where CON_SO_ID = '{$so_id}' and CON_TASK = 'RECONSTRUCT OSP'";
            $oraconn = OracleConnection();
            $return = oci_parse($oraconn, $sql);
            if(oci_execute($return))
            {
            $sql = "update  CONTRACTOR_WORK_ORDERS set CON_STATUS = 'RE_RETURNED', CON_STATUS_DATE = sysdate where CON_SERO_ID = '{$so_id}' and CON_WORO_TASK_NAME = 'RECONSTRUCT OSP'";
            $oraconn = OracleConnection();
            $return1 = oci_parse($oraconn, $sql);
            if(oci_execute($return1))
            {
            echo  "success";
            }
            else
            {
                $err = oci_error($return1);
                $e =  $err['message'];
                echo "<script type='text/javascript'>alert('$e')</script>";
            }
            }
            else
            {
                $err = oci_error($return);
                $e =  $err['message'];
                echo "<script type='text/javascript'>alert('$e')</script>";
            }
            }
            else
            {
                $err = oci_error($return2);
                $e =  $err['message'];
                echo "<script type='text/javascript'>alert('$e')</script>";
            }
   }else{
    
            if(oci_execute($return2))
            {
            $sql = "update  CONTRACTOR_NEW_CON set CON_SO_STATUS = 'RETURNED', CON_SO_RTN_DATE = sysdate  where CON_SO_ID = '{$so_id}'  and CON_TASK = 'CONSTRUCT OSP'";
            $oraconn = OracleConnection();
            $return = oci_parse($oraconn, $sql);
            if(oci_execute($return))
            {
            $sql = "update  CONTRACTOR_WORK_ORDERS set CON_STATUS = 'RETURNED', CON_STATUS_DATE = sysdate where CON_SERO_ID = '{$so_id}' and CON_WORO_TASK_NAME = 'CONSTRUCT OSP'";
            $oraconn = OracleConnection();
            $return1 = oci_parse($oraconn, $sql);
            if(oci_execute($return1))
            {
            echo  "success";
            }
            else
            {
                $err = oci_error($return1);
                $e =  $err['message'];
                echo "<script type='text/javascript'>alert('$e')</script>";
            }
            }
            else
            {
                $err = oci_error($return);
                $e =  $err['message'];
                echo "<script type='text/javascript'>alert('$e')</script>";
            }
            }
            else
            {
                $err = oci_error($return2);
                $e =  $err['message'];
                echo "<script type='text/javascript'>alert('$e')</script>";
            }
    
   }
    
}



if(isset($_POST["q"]) && $_POST["q"]== 'returnIPTVsod' ){

$so_id = $_POST["sod"];
$reason = $_POST["reason"];
  
$sql = "insert into CONTRACTOR_ORDER_COMMENTS (CON_COMM_SERO_ID,CON_COMM_TEXT,CON_COMM_TIME,CON_COMM_USER,CON_COMM_STATUS) 
        values ('$so_id', '$reason', sysdate , '$user','RETURNED' )";
    $oraconn = OracleConnection();
    $return2 = oci_parse($oraconn, $sql);
    if(oci_execute($return2))
    {
    $sql = "update  CONTRACTOR_NEW_CON set CON_SO_STATUS = 'RETURNED', CON_SO_RTN_DATE = sysdate  where CON_SO_ID = '{$so_id}'";
    $oraconn = OracleConnection();
    $return = oci_parse($oraconn, $sql);
    if(oci_execute($return))
    {
    $sql = "update  CONTRACTOR_WORK_ORDERS set CON_STATUS = 'RETURNED', CON_STATUS_DATE = sysdate where CON_SERO_ID = '{$so_id}'";
    $oraconn = OracleConnection();
    $return1 = oci_parse($oraconn, $sql);
    if(oci_execute($return1))
    {
    echo  "success";
    }
    else
    {
        $err = oci_error($return1);
        $e =  $err['message'];
        echo "<script type='text/javascript'>alert('$e')</script>";
    }
    }
    else
    {
        $err = oci_error($return);
        $e =  $err['message'];
        echo "<script type='text/javascript'>alert('$e')</script>";
    }
    }
    else
    {
        $err = oci_error($return2);
        $e =  $err['message'];
        echo "<script type='text/javascript'>alert('$e')</script>";
    }
}


function penso_slt($a,$b,$c,$d)
{
    $sql ="SELECT DISTINCT CW.CON_WORO_AREA,CS.LEA,
   CW.CON_SERO_ID,CW.CON_PSTN_NUMBER,CW.CON_WORO_SERVICE_TYPE,CW.CON_WORO_ORDER_TYPE,CW.CON_WORO_TASK_NAME,
CASE WHEN CON_WORO_SERVICE_TYPE = 'AB-FTTH' THEN 
   ( SELECT DISTINCT CON_FTTH_PKG FROM CONTRACTOR_FTTH_DATA WHERE CON_FTTH_SERO_ID = CON_SERO_ID)
    WHEN CON_WORO_SERVICE_TYPE = 'AB-CAB' THEN 
   (SELECT DISTINCT CON_OSP_PHONE_COLOUR FROM CONTRACTOR_OSP_DATA WHERE CON_OSP_SERO_ID = CON_SERO_ID)
   ELSE ''
   END AS PKG,CW.CON_NAME, TO_CHAR(CW.CON_DATE_TO_CONTRACTOR, 'mm/dd/yyyy hh:mi:ss AM'), CW.CON_STATUS
FROM CONTRACTOR_WORK_ORDERS CW, CONTRACTOR_NEW_CON CO, CON_CLARITY_SOLIST CS
WHERE CO.CON_SO_STATUS = CW.CON_STATUS
AND CW.CON_STATUS IN ('ASSIGNED','INPROGRESS')
AND CON_AREA = '$a'
AND CW.CON_SERO_ID = CO.CON_SO_ID 
and CW.CON_WORO_SERVICE_TYPE = '$d'
AND CO.CON_SO_ID = CS.SO_NUM
AND CW.CON_SERO_ID = CS.SO_NUM
and CS.IPTV is not null
AND CW.CON_DATE_TO_CONTRACTOR BETWEEN TO_DATE('$b 12:00:00 AM','mm,dd,yyyy:hh:mi:ss pm') 
AND TO_DATE('$c 11:59:59 PM','mm,dd,yyyy:hh:mi:ss pm')
union all
select distinct cw.CON_WORO_AREA,op.CON_EX_AREA,cw.CON_SERO_ID,cw.CON_PSTN_NUMBER,cw.CON_WORO_SERVICE_TYPE,
cw.CON_WORO_ORDER_TYPE,cw.CON_WORO_TASK_NAME ,op.CON_OSP_PHONE_COLOUR,cw.CON_NAME ,to_char(cw.CON_DATE_TO_CONTRACTOR, 'mm/dd/yyyy hh:mi:ss AM'), cw.CON_STATUS
from CONTRACTOR_WORK_ORDERS cw, CONTRACTOR_NEW_CON co ,CONTRACTOR_OSP_DATA op
 where co.CON_SO_STATUS = cw.CON_STATUS
and cw.CON_STATUS IN ('ASSIGNED','INPROGRESS', 'REASSIGNED', 'DELAYED')
 and CON_AREA = '$a'
 and CW.CON_WORO_SERVICE_TYPE = '$d'
 and CW.CON_SERO_ID = CO.CON_SO_ID
 and CW.CON_SERO_ID = op.CON_OSP_SERO_ID
 and CO.CON_SO_ID = op.CON_OSP_SERO_ID
 and cw.CON_WORO_ID is not null
 AND CW.CON_DATE_TO_CONTRACTOR BETWEEN TO_DATE('$b 12:00:00 AM','mm,dd,yyyy:hh:mi:ss pm') 
AND TO_DATE('$c 11:59:59 PM','mm,dd,yyyy:hh:mi:ss pm')";
        
    $oraconn = OracleConnection();
    $area = oci_parse($oraconn, $sql);
    if(oci_execute($area))
    {
    return $area;
    }
    else
    {
        $err = oci_error($area);
        $e =  $err['message'];
        echo "<script type='text/javascript'>alert('$e')</script>";
    }
}


function comso_slt($a,$b,$c,$d)
{
    $sql ="SELECT DISTINCT CW.CON_WORO_AREA,CS.LEA,
   CW.CON_SERO_ID,CW.CON_PSTN_NUMBER,CW.CON_WORO_SERVICE_TYPE,CW.CON_WORO_ORDER_TYPE,CW.CON_WORO_TASK_NAME,
CASE WHEN CON_WORO_SERVICE_TYPE = 'AB-FTTH' THEN 
   ( SELECT DISTINCT CON_FTTH_PKG FROM CONTRACTOR_FTTH_DATA WHERE CON_FTTH_SERO_ID = CON_SERO_ID)
    WHEN CON_WORO_SERVICE_TYPE = 'AB-CAB' THEN 
   (SELECT DISTINCT CON_OSP_PHONE_COLOUR FROM CONTRACTOR_OSP_DATA WHERE CON_OSP_SERO_ID = CON_SERO_ID)
   ELSE ''
   END AS PKG,CW.CON_NAME, TO_CHAR(CO.CON_SO_COM_DATE, 'mm/dd/yyyy hh:mi:ss AM'), CW.CON_STATUS,
   TO_CHAR(CO.CON_SO_DATE_RECEIVED , 'mm/dd/yyyy hh:mi:ss AM'), CON_OSP_DP_NAME||' - '||CON_OSP_DP_LOOP
FROM CONTRACTOR_WORK_ORDERS CW, CONTRACTOR_NEW_CON CO, CON_CLARITY_SOLIST CS,CONTRACTOR_OSP_DATA op
WHERE CO.CON_SO_STATUS = CW.CON_STATUS
AND CW.CON_STATUS IN ('COMPLETED')
AND CON_AREA = '$a'
and CW.CON_WORO_SERVICE_TYPE = '$d'
AND CW.CON_SERO_ID = CO.CON_SO_ID 
and CW.CON_SERO_ID = op.CON_OSP_SERO_ID
 and CO.CON_SO_ID = op.CON_OSP_SERO_ID
AND CW.CON_WORO_TASK_NAME = CO.CON_TASK
 and CO.CON_STATUS = '2'
AND CO.CON_SO_ID = CS.SO_NUM
AND CW.CON_SERO_ID = CS.SO_NUM
and CS.IPTV is not null
AND CO.CON_SO_COM_DATE BETWEEN TO_DATE('$b 12:00:00 AM','mm,dd,yyyy:hh:mi:ss pm') 
AND TO_DATE('$c 11:59:59 PM','mm,dd,yyyy:hh:mi:ss pm')
union all
select distinct cw.CON_WORO_AREA,op.CON_EX_AREA,cw.CON_SERO_ID,cw.CON_PSTN_NUMBER,cw.CON_WORO_SERVICE_TYPE,
cw.CON_WORO_ORDER_TYPE,cw.CON_WORO_TASK_NAME ,op.CON_OSP_PHONE_COLOUR,cw.CON_NAME ,to_char(CO.CON_SO_COM_DATE, 'mm/dd/yyyy hh:mi:ss AM'), cw.CON_STATUS,
TO_CHAR(CO.CON_SO_DATE_RECEIVED , 'mm/dd/yyyy hh:mi:ss AM'), CON_OSP_DP_NAME||' - '||CON_OSP_DP_LOOP
from CONTRACTOR_WORK_ORDERS cw, CONTRACTOR_NEW_CON co ,CONTRACTOR_OSP_DATA op
 where co.CON_SO_STATUS = cw.CON_STATUS
and cw.CON_STATUS IN ('COMPLETED')
 and CON_AREA = '$a'
 and CW.CON_WORO_SERVICE_TYPE = '$d'
 and CW.CON_SERO_ID = CO.CON_SO_ID
 and CW.CON_SERO_ID = op.CON_OSP_SERO_ID
 AND CW.CON_WORO_TASK_NAME = CO.CON_TASK
 and CO.CON_STATUS = '2'
 and CO.CON_SO_ID = op.CON_OSP_SERO_ID
 and cw.CON_WORO_ID is not null
 AND CO.CON_SO_COM_DATE BETWEEN TO_DATE('$b 12:00:00 AM','mm,dd,yyyy:hh:mi:ss pm') 
AND TO_DATE('$c 11:59:59 PM','mm,dd,yyyy:hh:mi:ss pm')";
  
  
  if($d == 'E-IPTV COPPER' || $d == 'E-IPTV FTTH'){
    
 $sql="select distinct b.CON_WORO_AREA,c.CON_EX_AREA,b.CON_SERO_ID,b.CON_PSTN_NUMBER,b.CON_WORO_SERVICE_TYPE,
   b.CON_WORO_ORDER_TYPE, b.CON_WORO_TASK_NAME,'' ,b.CON_NAME ,to_char(a.CON_SO_COM_DATE, 'mm/dd/yyyy hh:mi:ss AM'),
    b.CON_STATUS, TO_CHAR(a.CON_SO_DATE_RECEIVED , 'mm/dd/yyyy hh:mi:ss AM')
from CONTRACTOR_NEW_CON a,CONTRACTOR_WORK_ORDERS b, CONTRACTOR_EQ_DATA c
where a.CON_SO_ID = b.CON_SERO_ID
and CON_AREA = '$a'
and a.CON_SO_ID = c.CON_EQ_SERO_ID
and b.CON_SERO_ID = c.CON_EQ_SERO_ID
and A.CON_TASK = B.CON_WORO_TASK_NAME
AND a.CON_SO_STATUS ='COMPLETED'
AND b.CON_WORO_SERVICE_TYPE = '$d'
and a.CON_STATUS = '2'
AND a.CON_SO_COM_DATE BETWEEN TO_DATE('$b 12:00:00 AM','mm,dd,yyyy:hh:mi:ss pm') 
AND TO_DATE('$c 11:59:59 PM','mm,dd,yyyy:hh:mi:ss pm')";
    
  }

    $oraconn = OracleConnection();
    $area = oci_parse($oraconn, $sql);
    if(oci_execute($area))
    {
    return $area;
    }
    else
    {
        $err = oci_error($area);
        $e =  $err['message'];
        echo "<script type='text/javascript'>alert('$e')</script>";
    }
}


function retso_slt($a,$b,$c,$d)
{
    $sql ="select distinct cw.CON_WORO_AREA,op.CON_EX_AREA,cw.CON_SERO_ID,cw.CON_PSTN_NUMBER,cw.CON_WORO_SERVICE_TYPE,
        cw.CON_WORO_ORDER_TYPE,cw.CON_WORO_TASK_NAME ,cw.CON_NAME, TO_CHAR(co.CON_SO_RTN_DATE , 'mm/dd/yyyy hh:mi:ss AM'), CM.CON_COMM_TEXT
        from CONTRACTOR_WORK_ORDERS cw, CONTRACTOR_NEW_CON co , CONTRACTOR_ORDER_COMMENTS CM,CONTRACTOR_OSP_DATA op
         where co.CON_SO_STATUS = cw.CON_STATUS
        and cw.CON_STATUS IN ('RETURNED','RE_RETURNED')
         and CON_AREA = '$a'
         and CW.CON_SERO_ID = CO.CON_SO_ID
         and  CW.CON_SERO_ID = CM.CON_COMM_SERO_ID
         and  CO.CON_SO_ID = CM.CON_COMM_SERO_ID
         and CW.CON_SERO_ID = op.CON_OSP_SERO_ID
         and cw.CON_WORO_TASK_NAME = co.CON_TASK
         and cw.CON_STATUS  = cm.CON_COMM_STATUS
         and CW.CON_WORO_SERVICE_TYPE = '$d'
         and CO.CON_SO_ID = op.CON_OSP_SERO_ID
         AND CO.CON_SO_RTN_DATE BETWEEN TO_DATE('$b 12:00:00 AM','mm,dd,yyyy:hh:mi:ss pm') 
        AND TO_DATE('$c 11:59:59 PM','mm,dd,yyyy:hh:mi:ss pm') ";
        
    $oraconn = OracleConnection();
    $area = oci_parse($oraconn, $sql);
    if(oci_execute($area))
    {
    return $area;
    }
    else
    {
        $err = oci_error($area);
        $e =  $err['message'];
        echo "<script type='text/javascript'>alert('$e')</script>";
    }
}



if(isset($_POST["q"]) && $_POST["q"]== 'addconuser' ){

$con=$_POST["con"];
$uname=$_POST["uname"];
$nam=$_POST["nam"];
$mob=$_POST["mob"];
$mail=$_POST["mail"];
$area=$_POST["area"];
$sup=$_POST["sup"];

  $sql= "INSERT INTO CONTRACTOR_MGT_USERS VALUES ( '$con','$con','$uname','','$sup','$mail','$mob','','','','$nam','$area')";

    $oraconn = OracleConnection();
    $con_comp= oci_parse($oraconn, $sql);
    if(oci_execute($con_comp))
    {
     log_all($user,$sod,'New User Add '.$con.' - '.$uname);
    echo  "success";

    }
    else
    {
        $err = oci_error($con_comp);
        $e =  $err['message'];
        echo $e;     
    }

}


if(isset($_POST["q"]) && $_POST["q"]== 'deluser' ){

$con=$_POST["con"];
$uname=$_POST["uname"];
$mob=$_POST["mob"];


  $sql= "DELETE FROM CONTRACTOR_MGT_USERS WHERE CON_MGT_CONTRACTOR= '$con' and CON_MGT_USER_NAME = '$uname' and CON_MGT_MOBILE = '$mob'";

    $oraconn = OracleConnection();
    $con_comp= oci_parse($oraconn, $sql);
    if(oci_execute($con_comp))
    {
     log_all($user,$sod,'Deleted user '.$con.' - '.$uname );
    echo  "success";

    }
    else
    {
        $err = oci_error($con_comp);
        $e =  $err['message'];
        echo $e;     
    }

}



function getvat(){


  $sql= "select distinct VAT from CONTRACTOR_DETAIL";

    $oraconn = OracleConnection();
    $area = oci_parse($oraconn, $sql);
    if(oci_execute($area))
    {
    return $area;
    }
    else
    {
        $err = oci_error($area);
        $e =  $err['message'];
        echo "<script type='text/javascript'>alert('$e')</script>";
    }

}

function getsample(){


  $sql= "select distinct SRATE from QUALITY_SAMPLE_RATE";

    $oraconn = OracleConnection();
    $area = oci_parse($oraconn, $sql);
    if(oci_execute($area))
    {
    return $area;
    }
    else
    {
        $err = oci_error($area);
        $e =  $err['message'];
        echo "<script type='text/javascript'>alert('$e')</script>";
    }

}


if(isset($_POST["q"]) && $_POST["q"]== 'updatevat' ){

$vat=$_POST["vat"];




  $sql= "update CONTRACTOR_DETAIL set VAT= '$vat'";

    $oraconn = OracleConnection();
    $con_comp= oci_parse($oraconn, $sql);
    if(oci_execute($con_comp))
    {
     log_all($user,$sod,'Deleted user '.$con.' - '.$uname );
    echo  "success";

    }
    else
    {
        $err = oci_error($con_comp);
        $e =  $err['message'];
        echo $e;     
    }

}


if(isset($_POST["q"]) && $_POST["q"]== 'updatesample' ){

$srate=$_POST["srate"];




  $sql= "update QUALITY_SAMPLE_RATE set SRATE= '$srate', UDATE = sysdate, UPUSER = '$user'";

    $oraconn = OracleConnection();
    $con_comp= oci_parse($oraconn, $sql);
    if(oci_execute($con_comp))
    {
     log_all($user,$sod,'Deleted user '.$con.' - '.$uname );
    echo  "success";

    }
    else
    {
        $err = oci_error($con_comp);
        $e =  $err['message'];
        echo $e;     
    }

}



function getmaxsod($a){


  $sql= " select CON_SO_ID from CONTRACTOR_NEW_CON 
        where CON_CIRCUIT_NO = '$a' and 
        CON_SO_STATUS IN ('ASSIGNED', 'INPROGRESS', 'COMPLETED', 'RETURNED', 'RE_RETURNED')
        order by CON_SO_DATE_RECEIVED DESC";

    $oraconn = OracleConnection();
    $area = oci_parse($oraconn, $sql);
    if(oci_execute($area))
    {
        $row= oci_fetch_array($area);
    return $row[0];
    }
    else
    {
        $err = oci_error($area);
        $e =  $err['message'];
        echo "<script type='text/javascript'>alert('$e')</script>";
    }

}


if(isset($_POST["q"]) && $_POST["q"]== 'upconuser' ){

$con=$_POST["con"];
$uname=$_POST["uname"];
$mob=$_POST["mob"];
$area=$_POST["area"];
$sup=$_POST["sup"];

  $sql= "UPDATE CONTRACTOR_MGT_USERS set CON_MGT_MOBILE = '$mob' ,CON_MGT_USER_PRV_LEVEL= '$sup',SLT_AREA= '$area' where CON_MGT_CONTRACTOR= '$con' and CON_MGT_USER_NAME = '$uname'";

    $oraconn = OracleConnection();
    $con_comp= oci_parse($oraconn, $sql);
    if(oci_execute($con_comp))
    {
     log_all($user,$sod,'User detail update '.$con.' - '.$uname);
    echo  "success";

    }
    else
    {
        $err = oci_error($con_comp);
        $e =  $err['message'];
        echo $e;     
    }

}



function getunit($a){


  $sql= " select * from CONTRACTOR_UNIT_RATE where RTOM = '$a'";

    $oraconn = OracleConnection();
    $area = oci_parse($oraconn, $sql);
    if(oci_execute($area))
    {
        return $area;
    }
    else
    {
        $err = oci_error($area);
        $e =  $err['message'];
        echo "<script type='text/javascript'>alert('$e')</script>";
    }

}

function getreturn($a)
{

    $sql = "select CON_COMM_TEXT  from CONTRACTOR_ORDER_COMMENTS a where CON_COMM_SERO_ID = '$a' ";
            
    $oraconn = OracleConnection();
    $user = oci_parse($oraconn, $sql);
    if ( oci_execute($user))
    {
        $row = oci_fetch_array($user);
    return $row[0];
    }
    else
    {
        $err = oci_error($user);
        $e =  $err['message'];
        echo "<script type='text/javascript'>alert('$e')</script>";
    }

}


function getre($a,$b)
{

    $sql = "select CON_COMM_TEXT  from CONTRACTOR_ORDER_COMMENTS a where CON_COMM_SERO_ID = '$a' and CON_COMM_STATUS = '$b' ";
            
    $oraconn = OracleConnection();
    $user = oci_parse($oraconn, $sql);
    if ( oci_execute($user))
    {
        $row = oci_fetch_array($user);
    return $row[0];
    }
    else
    {
        $err = oci_error($user);
        $e =  $err['message'];
        echo "<script type='text/javascript'>alert('$e')</script>";
    }

}
?>
