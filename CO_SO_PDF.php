<?php
session_start();
if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true)
{    
    $user = $_SESSION["user"];
	$contractor_name = $_SESSION["contractor"];
}
else 
{     
    echo '<script type="text/javascript"> document.location = "login.php";</script>'; 
}
include "db.php";

require('fpdf/fpdf.php');
//require('C:\xampp\php\PEAR\fpdf\fpdf.php');  
	
	$so_id = $_GET['id'];

            $getftthcab= oci_fetch_array(getftthcab($so_id));
        if($getftthcab[1] == 'AB-CAB'){
            $osp_date = oci_fetch_array(osp_date($so_id));
        }
        else if($getftthcab[1] == 'AB-FTTH'){
            $ftth_data = oci_fetch_array(ftth_data($so_id));
            
                if($ftth_data[3]== 'VOICE_INT_IPTV'){
                    $aa= 'Triple Play';
                }
                if($ftth_data[3]== 'VOICE_IPTV'){
                    $aa= 'Double Play - PeoTV';
                }
                if($ftth_data[3]== '' || $ftth_data[3]== 'VOICE_INT'){
                    $aa= 'Single Play';
                }
            
        }
        else if($getftthcab[1] == 'E-IPTV COPPER' || $getftthcab[1] == 'E-IPTV FTTH' ){
            $eq_data = oci_fetch_array(eq_data($so_id));
        }
        else{
            $prio_approve = oci_fetch_array(priority($so_id));
            $osp_date = oci_fetch_array(osp_date($so_id));
            $service_add = oci_fetch_array(service_add($so_id));
            
            $so_details = so_detail($so_id);
            $row=oci_fetch_array($so_details);
     
            $ftth_data = oci_fetch_array(ftth_data($so_id));
        }
	  
        $get_contra = oci_fetch_array(get_contra($contractor_name));
   
   
    
class PDF extends FPDF {
    
        function BuildTablePeo($data,$dataPeo,$sys) {

        //Colors, line width and bold font

        $this->SetFillColor(255,255,255);
        $this->SetTextColor(0,0,0);
        $this->SetDrawColor(0,0,0);
        $this->SetLineWidth(.3);
        $this->SetFont('Times','B','12');
        
        $this->SetTextColor(0);
        $this->SetFont('Times','B','16');
        $this->Cell(180,6,'Customer Feedback Form','',0,'C',$fill);
        $this->Ln();
        
        $this->SetTextColor(0);
        $this->SetFont('Times','B','16');
        $this->Cell(180,6,'','',0,'C',$fill);
        $this->Ln();
        
        $this->SetTextColor(0);
        $this->SetFont('Times','B','10');
        $this->Cell(180,6,'SERVICE ORDER DETAILS','LRT',0,'L',$fill);
        $this->Ln();
        
        $this->SetTextColor(0);
        $this->SetFont('Times','B','10');
        $this->Cell(180,6,'','LRT',0,'',$fill);
        $this->Ln();
        
        
        $this->SetTextColor(0);
        $this->SetFont('Times','B','10');
        $this->Cell(5,6,'','L',0,'L',$fill);
        $this->SetTextColor(0);
        $this->SetFont('Times','B','10');
        $this->Cell(45,6,'Voice Service Order No ','',0,'L',$fill);
        $this->SetTextColor(0);
        $this->SetFont('Times','B','10');
        $this->Cell(45,6,': '.$data[0],'',0,'L',$fill);
        $this->SetTextColor(0);
        $this->SetFont('Times','B','10');
        $this->Cell(40,6,'Service Type ','',0,'L',$fill);
        $this->SetTextColor(0);
        $this->SetFont('Times','B','10');
        $this->Cell(45,6,': '.$data[1],'R',0,'L',$fill);
        $this->Ln();
        
        $this->SetTextColor(0);
        $this->SetFont('Times','B','10');
        $this->Cell(5,6,'','L',0,'L',$fill);
        $this->SetTextColor(0);
        $this->SetFont('Times','B','10');
        $this->Cell(45,6,'Voice No ','',0,'L',$fill);
        $this->SetTextColor(0);
        $this->SetFont('Times','B','10');
        $this->Cell(45,6,': '.$data[3],'',0,'L',$fill);
        $this->SetTextColor(0);
        $this->SetFont('Times','B','10');
        $this->Cell(40,6,'Recieved Date ','',0,'L',$fill);
        $this->SetTextColor(0);
        $this->SetFont('Times','B','10');
        $this->Cell(45,6,': '.$data[2],'R',0,'L',$fill);
        $this->Ln();
        
        $this->SetTextColor(0);
        $this->SetFont('Times','B','10');
        $this->Cell(5,6,'','L',0,'L',$fill);
        $this->SetTextColor(0);
        $this->SetFont('Times','B','10');
        $this->Cell(45,6,'RTOM ','',0,'L',$fill);
        $this->SetTextColor(0);
        $this->SetFont('Times','B','10');
        $this->Cell(45,6,': '.$data[4],'',0,'L',$fill);
        $this->SetTextColor(0);
        $this->SetFont('Times','B','10');
        $this->Cell(40,6,'Order Type ','',0,'L',$fill);
        $this->SetTextColor(0);
        $this->SetFont('Times','B','10');
        $this->Cell(45,6,': '.$data[5],'R',0,'L',$fill);
        $this->Ln();
        
        $this->SetTextColor(0);
        $this->SetFont('Times','B','10');
        $this->Cell(5,6,'','L',0,'L',$fill);
        $this->SetTextColor(0);
        $this->SetFont('Times','B','10');
        $this->Cell(45,6,'Customer Name ','',0,'L',$fill);
        $this->SetTextColor(0);
        $this->SetFont('Times','B','10');
        $this->Cell(45,6,': '.$data[6],'',0,'L',$fill);
        $this->SetTextColor(0);
        $this->SetFont('Times','B','10');
        $this->Cell(40,6,'Customer Contact ','',0,'L',$fill);
        $this->SetTextColor(0);
        $this->SetFont('Times','B','10');
        $this->Cell(45,6,': '.$data[7],'R',0,'L',$fill);
        $this->Ln();
        
		$this->SetTextColor(0);
        $this->SetFont('Times','B','10');
        $this->Cell(5,6,'','L',0,'L',$fill);
        $this->SetTextColor(0);
        $this->SetFont('Times','B','10');
        $this->Cell(45,6,'Megaline Package ','',0,'L',$fill);
        $this->SetTextColor(0);
        $this->SetFont('Times','B','10');
        $this->Cell(45,6,': '.$data[9],'',0,'L',$fill);
        $this->SetTextColor(0);
        $this->SetFont('Times','B','10');
        $this->Cell(40,6,'Phone Class ','',0,'L',$fill);
        $this->SetTextColor(0);
        $this->SetFont('Times','B','10');
        $this->Cell(45,6,': '.$data[10],'R',0,'L',$fill);
        $this->Ln();
        
        $this->SetTextColor(0);
        $this->SetFont('Times','B','10');
        $this->Cell(5,6,'','L',0,'L',$fill);
        $this->SetTextColor(0);
        $this->SetFont('Times','B','10');
        $this->Cell(45,6,'Phone Purchase From SLT ','',0,'L',$fill);
        $this->SetTextColor(0);
        $this->SetFont('Times','B','10');
        $this->Cell(45,6,': '.$data[11],'',0,'L',$fill);
        $this->SetTextColor(0);
        $this->SetFont('Times','B','10');
        $this->Cell(40,6,'DP LOOP ','',0,'L',$fill);
        $this->SetTextColor(0);
        $this->SetFont('Times','B','10');
        $this->Cell(45,6,': '.$data[12].' - '.$data[13],'R',0,'L',$fill);
        $this->Ln();
        
        $this->SetTextColor(0);
        $this->SetFont('Times','B','10');
        $this->Cell(5,6,'','L',0,'L',$fill);
        $this->SetTextColor(0);
        $this->SetFont('Times','B','10');
        $this->Cell(45,6,'Service Address ','',0,'L',$fill);
        $this->SetTextColor(0);
        $this->SetFont('Times','B','10');
        $this->Cell(130,6,': '.$data[8],'R',0,'L',$fill);
        $this->Ln();
        
        $this->SetTextColor(0);
        $this->SetFont('Times','B','10');
        $this->Cell(180,10,'','LR',0,'L',$fill);
        $this->Ln();
        
       if($sys == 'IPTV'){ 
        $this->SetTextColor(0);
        $this->SetFont('Times','B','10');
        $this->Cell(5,6,'','L',0,'L',$fill);
        $this->SetTextColor(0);
        $this->SetFont('Times','B','10');
        $this->Cell(45,6,'IPTV Service Order No ','',0,'L',$fill);
        $this->SetTextColor(0);
        $this->SetFont('Times','B','10');
        $this->Cell(45,6,': '.$dataPeo[0],'',0,'L',$fill);
        $this->SetTextColor(0);
        $this->SetFont('Times','B','10');
        $this->Cell(40,6,'Service Type ','',0,'L',$fill);
        $this->SetTextColor(0);
        $this->SetFont('Times','B','10');
        $this->Cell(45,6,': '.$dataPeo[1],'R',0,'L',$fill);
        $this->Ln();
        
        $this->SetTextColor(0);
        $this->SetFont('Times','B','10');
        $this->Cell(5,6,'','L',0,'L',$fill);
        $this->SetTextColor(0);
        $this->SetFont('Times','B','10');
        $this->Cell(45,6,'IPTV No ','',0,'L',$fill);
        $this->SetTextColor(0);
        $this->SetFont('Times','B','10');
        $this->Cell(45,6,': '.$dataPeo[3],'',0,'L',$fill);
        $this->SetTextColor(0);
        $this->SetFont('Times','B','10');
        $this->Cell(40,6,'Received Date ','',0,'L',$fill);
        $this->SetTextColor(0);
        $this->SetFont('Times','B','10');
        $this->Cell(45,6,': '.$dataPeo[2],'R',0,'L',$fill);
        $this->Ln();
        
        $this->SetTextColor(0);
        $this->SetFont('Times','B','10');
        $this->Cell(5,6,'','L',0,'L',$fill);
        $this->SetTextColor(0);
        $this->SetFont('Times','B','10');
        $this->Cell(45,6,'MSAN Location ','',0,'L',$fill);
        $this->SetTextColor(0);
        $this->SetFont('Times','B','10');
        $this->Cell(45,6,': '.$dataPeo[7],'',0,'L',$fill);
        $this->SetTextColor(0);
        $this->SetFont('Times','B','10');
        $this->Cell(40,6,'Card Port ','',0,'L',$fill);
        $this->SetTextColor(0);
        $this->SetFont('Times','B','10');
        $this->Cell(45,6,': '.$dataPeo[8].' - '.$dataPeo[9],'R',0,'L',$fill);
        $this->Ln();
        }
        
        $this->SetTextColor(0);
        $this->SetFont('Times','B','10');
        $this->Cell(180,10,'','LBR',0,'L',$fill);
        $this->Ln();
        
        $this->SetTextColor(0);
        $this->SetFont('Times','B','10');
        $this->Cell(180,6,'Customer Feedback','LRT',0,'L',$fill);
        $this->Ln();
        
        $this->SetTextColor(0);
        $this->SetFont('Times','B','10');
        $this->Cell(180,6,'','LRT',0,'',$fill);
        $this->Ln();
        
        $this->SetTextColor(0);
        $this->SetFont('Times','B','10');
        $this->Cell(5,8,'','L',0,'L',$fill);
        $this->SetTextColor(0);
        $this->SetFont('Times','B','10');
        $this->Cell(10,8,'S/N','LRT',0,'L',$fill);
        $this->SetTextColor(0);
        $this->SetFont('Times','B','10');
        $this->Cell(90,8,'Description','LRT',0,'L',$fill);
        $this->SetTextColor(0);
        $this->SetFont('Times','B','10');
        $this->Cell(10,8,'YES','LRT',0,'L',$fill);
        $this->SetTextColor(0);
        $this->SetFont('Times','B','10');
        $this->Cell(10,8,'NO','LRT',0,'L',$fill);
        $this->SetTextColor(0);
        $this->SetFont('Times','B','10');
        $this->Cell(50,8,'Remarks','LRT',0,'L',$fill);
        $this->SetTextColor(0);
        $this->SetFont('Times','B','10');
        $this->Cell(5,8,'','R',0,'L',$fill);
        $this->Ln();
        
        $this->SetTextColor(0);
        $this->SetFont('Times','B','10');
        $this->Cell(5,6,'','L',0,'L',$fill);
        $this->SetTextColor(0);
        $this->SetFont('Times','B','10');
        $this->Cell(10,6,'1','LRT',0,'C',$fill);
        $this->SetTextColor(0);
        $this->SetFont('Times','B','10');
        $this->Cell(90,6,'Have You received a leaflet briefing the information?','LRT',0,'L',$fill);
        $this->SetTextColor(0);
        $this->SetFont('Times','B','10');
        $this->Cell(10,6,'','LRT',0,'L',$fill);
        $this->SetTextColor(0);
        $this->SetFont('Times','B','10');
        $this->Cell(10,6,'','LRT',0,'L',$fill);
        $this->SetTextColor(0);
        $this->SetFont('Times','B','10');
        $this->Cell(50,6,'','LRT',0,'L',$fill);
        $this->SetTextColor(0);
        $this->SetFont('Times','B','10');
        $this->Cell(5,6,'','R',0,'L',$fill);
        $this->Ln();
        
        $this->SetTextColor(0);
        $this->SetFont('Times','B','10');
        $this->Cell(5,6,'','L',0,'L',$fill);
        $this->SetTextColor(0);
        $this->SetFont('Times','B','10');
        $this->Cell(10,6,'2','LRT',0,'C',$fill);
        $this->SetTextColor(0);
        $this->SetFont('Times','B','10');
        $this->Cell(90,6,'Do you satisfy with the neatness of wiring?','LRT',0,'L',$fill);
        $this->SetTextColor(0);
        $this->SetFont('Times','B','10');
        $this->Cell(10,6,'','LRT',0,'L',$fill);
        $this->SetTextColor(0);
        $this->SetFont('Times','B','10');
        $this->Cell(10,6,'','LRT',0,'L',$fill);
        $this->SetTextColor(0);
        $this->SetFont('Times','B','10');
        $this->Cell(50,6,'','LRT',0,'L',$fill);
        $this->SetTextColor(0);
        $this->SetFont('Times','B','10');
        $this->Cell(5,6,'','R',0,'L',$fill);
        $this->Ln();
        
        $this->SetTextColor(0);
        $this->SetFont('Times','B','10');
        $this->Cell(5,6,'','L',0,'L',$fill);
        $this->SetTextColor(0);
        $this->SetFont('Times','B','10');
        $this->Cell(10,6,'3','LRT',0,'C',$fill);
        $this->SetTextColor(0);
        $this->SetFont('Times','B','10');
        $this->Cell(90,6,'Did the developer provide you a friendly service?','LRT',0,'L',$fill);
        $this->SetTextColor(0);
        $this->SetFont('Times','B','10');
        $this->Cell(10,6,'','LRT',0,'L',$fill);
        $this->SetTextColor(0);
        $this->SetFont('Times','B','10');
        $this->Cell(10,6,'','LRT',0,'L',$fill);
        $this->SetTextColor(0);
        $this->SetFont('Times','B','10');
        $this->Cell(50,6,'','LRT',0,'L',$fill);
        $this->SetTextColor(0);
        $this->SetFont('Times','B','10');
        $this->Cell(5,6,'','R',0,'L',$fill);
        $this->Ln();
        
        $this->SetTextColor(0);
        $this->SetFont('Times','B','10');
        $this->Cell(5,6,'','L',0,'L',$fill);
        $this->SetTextColor(0);
        $this->SetFont('Times','B','10');
        $this->Cell(10,6,'4','LRT',0,'C',$fill);
        $this->SetTextColor(0);
        $this->SetFont('Times','B','10');
        $this->Cell(90,6,'Were they wearing the company identity card?','LRT',0,'L',$fill);
        $this->SetTextColor(0);
        $this->SetFont('Times','B','10');
        $this->Cell(10,6,'','LRT',0,'L',$fill);
        $this->SetTextColor(0);
        $this->SetFont('Times','B','10');
        $this->Cell(10,6,'','LRT',0,'L',$fill);
        $this->SetTextColor(0);
        $this->SetFont('Times','B','10');
        $this->Cell(50,6,'','LRT',0,'L',$fill);
        $this->SetTextColor(0);
        $this->SetFont('Times','B','10');
        $this->Cell(5,6,'','R',0,'L',$fill);
        $this->Ln();
        
        $this->SetTextColor(0);
        $this->SetFont('Times','B','10');
        $this->Cell(5,6,'','L',0,'L',$fill);
        $this->SetTextColor(0);
        $this->SetFont('Times','B','10');
        $this->Cell(10,6,'5','LRT',0,'C',$fill);
        $this->SetTextColor(0);
        $this->SetFont('Times','B','10');
        $this->Cell(90,6,'Were they wearing the Uniform?','LRT',0,'L',$fill);
        $this->SetTextColor(0);
        $this->SetFont('Times','B','10');
        $this->Cell(10,6,'','LRT',0,'L',$fill);
        $this->SetTextColor(0);
        $this->SetFont('Times','B','10');
        $this->Cell(10,6,'','LRT',0,'L',$fill);
        $this->SetTextColor(0);
        $this->SetFont('Times','B','10');
        $this->Cell(50,6,'','LRT',0,'L',$fill);
        $this->SetTextColor(0);
        $this->SetFont('Times','B','10');
        $this->Cell(5,6,'','R',0,'L',$fill);
        $this->Ln();
        
        $this->SetTextColor(0);
        $this->SetFont('Times','B','10');
        $this->Cell(5,6,'','L',0,'L',$fill);
        $this->SetTextColor(0);
        $this->SetFont('Times','B','10');
        $this->Cell(10,6,'6','LRT',0,'C',$fill);
        $this->SetTextColor(0);
        $this->SetFont('Times','B','10');
        $this->Cell(90,6,'have you received the letter about the team identity?','LRT',0,'L',$fill);
        $this->SetTextColor(0);
        $this->SetFont('Times','B','10');
        $this->Cell(10,6,'','LRT',0,'L',$fill);
        $this->SetTextColor(0);
        $this->SetFont('Times','B','10');
        $this->Cell(10,6,'','LRT',0,'L',$fill);
        $this->SetTextColor(0);
        $this->SetFont('Times','B','10');
        $this->Cell(50,6,'','LRT',0,'L',$fill);
        $this->SetTextColor(0);
        $this->SetFont('Times','B','10');
        $this->Cell(5,6,'','R',0,'L',$fill);
        $this->Ln();
        
        $this->SetTextColor(0);
        $this->SetFont('Times','B','10');
        $this->Cell(5,6,'','L',0,'L',$fill);
        $this->SetTextColor(0);
        $this->SetFont('Times','B','10');
        $this->Cell(10,6,'7','LRT',0,'C',$fill);
        $this->SetTextColor(0);
        $this->SetFont('Times','B','10');
        $this->Cell(90,6,'Did you get the awareness on how to enjoy/use the service?','LRT',0,'L',$fill);
        $this->SetTextColor(0);
        $this->SetFont('Times','B','10');
        $this->Cell(10,6,'','LRT',0,'L',$fill);
        $this->SetTextColor(0);
        $this->SetFont('Times','B','10');
        $this->Cell(10,6,'','LRT',0,'L',$fill);
        $this->SetTextColor(0);
        $this->SetFont('Times','B','10');
        $this->Cell(50,6,'','LRT',0,'L',$fill);
        $this->SetTextColor(0);
        $this->SetFont('Times','B','10');
        $this->Cell(5,6,'','R',0,'L',$fill);
        $this->Ln();
        
        $this->SetTextColor(0);
        $this->SetFont('Times','B','10');
        $this->Cell(5,6,'','L',0,'L',$fill);
        $this->SetTextColor(0);
        $this->SetFont('Times','B','10');
        $this->Cell(10,6,'8','LRTB',0,'C',$fill);
        $this->SetTextColor(0);
        $this->SetFont('Times','B','10');
        $this->Cell(90,6,'Are you happy with the overall service?','LRTB',0,'L',$fill);
        $this->SetTextColor(0);
        $this->SetFont('Times','B','10');
        $this->Cell(10,6,'','LRTB',0,'L',$fill);
        $this->SetTextColor(0);
        $this->SetFont('Times','B','10');
        $this->Cell(10,6,'','LRTB',0,'L',$fill);
        $this->SetTextColor(0);
        $this->SetFont('Times','B','10');
        $this->Cell(50,6,'','LRTB',0,'L',$fill);
        $this->SetTextColor(0);
        $this->SetFont('Times','B','10');
        $this->Cell(5,6,'','R',0,'L',$fill);
        $this->Ln();
        
        $this->SetTextColor(0);
        $this->SetFont('Times','B','10');
        $this->Cell(180,5,'','LBR',0,'L',$fill);
        $this->Ln();
        
        $this->SetTextColor(0);
        $this->SetFont('Times','B','10');
        $this->Cell(180,6,'Additional Customer comments:','LR',0,'L',$fill);
        $this->Ln();
        
        $this->SetTextColor(0);
        $this->SetFont('Times','B','10');
        $this->Cell(180,5,'','LR',0,'L',$fill);
        $this->Ln();
        
        $this->SetTextColor(0);
        $this->SetFont('Times','B','10');
        $this->Cell(5,6,'','L',0,'L',$fill);
        $this->SetTextColor(0);
        $this->SetFont('Times','B','10');
        $this->Cell(170,5,'............................................................................................................................................................................................','',0,'L',$fill);
        $this->SetTextColor(0);
        $this->SetFont('Times','B','10');
        $this->Cell(5,6,'','R',0,'L',$fill);
        $this->Ln();
        
        $this->SetTextColor(0);
        $this->SetFont('Times','B','10');
        $this->Cell(5,6,'','L',0,'L',$fill);
        $this->SetTextColor(0);
        $this->SetFont('Times','B','10');
        $this->Cell(170,6,'............................................................................................................................................................................................','',0,'L',$fill);
        $this->SetTextColor(0);
        $this->SetFont('Times','B','10');
        $this->Cell(5,6,'','R',0,'L',$fill);
        $this->Ln();
        
        $this->SetTextColor(0);
        $this->SetFont('Times','B','10');
        $this->Cell(5,6,'','L',0,'L',$fill);
        $this->SetTextColor(0);
        $this->SetFont('Times','B','10');
        $this->Cell(170,6,'............................................................................................................................................................................................','',0,'L',$fill);
        $this->SetTextColor(0);
        $this->SetFont('Times','B','10');
        $this->Cell(5,6,'','R',0,'L',$fill);
        $this->Ln();
        
        $this->SetTextColor(0);
        $this->SetFont('Times','B','10');
        $this->Cell(180,10,'','LR',0,'L',$fill);
        $this->Ln();
        
        
        $this->SetTextColor(0);
        $this->SetFont('Times','B','10');
        $this->Cell(5,6,'','L',0,'L',$fill);
        $this->SetTextColor(0);
        $this->SetFont('Times','B','10');
        $this->Cell(85,6,'......................................................','',0,'L',$fill);
        $this->SetTextColor(0);
        $this->SetFont('Times','B','10');
        $this->Cell(85,6,'......................................................','',0,'L',$fill);
        $this->SetTextColor(0);
        $this->SetFont('Times','B','10');
        $this->Cell(5,6,'','R',0,'L',$fill);
        $this->Ln();
        
        $this->SetTextColor(0);
        $this->SetFont('Times','B','10');
        $this->Cell(5,6,'','L',0,'L',$fill);
        $this->SetTextColor(0);
        $this->SetFont('Times','B','10');
        $this->Cell(85,6,'Signature of Customer','',0,'L',$fill);
        $this->SetTextColor(0);
        $this->SetFont('Times','B','10');
        $this->Cell(85,6,'Signature of Contractor team Leader','',0,'L',$fill);
        $this->SetTextColor(0);
        $this->SetFont('Times','B','10');
        $this->Cell(5,6,'','R',0,'L',$fill);
        $this->Ln();
        
        $this->SetTextColor(0);
        $this->SetFont('Times','B','10');
        $this->Cell(5,6,'','L',0,'L',$fill);
        $this->SetTextColor(0);
        $this->SetFont('Times','B','10');
        $this->Cell(85,6,'Customer Name : .....................................................','',0,'L',$fill);
        $this->SetTextColor(0);
        $this->SetFont('Times','B','10');
        $this->Cell(85,6,'Name : .....................................................','',0,'L',$fill);
        $this->SetTextColor(0);
        $this->SetFont('Times','B','10');
        $this->Cell(5,6,'','R',0,'L',$fill);
        $this->Ln();
        
        $this->SetTextColor(0);
        $this->SetFont('Times','B','10');
        $this->Cell(5,6,'','L',0,'L',$fill);
        $this->SetTextColor(0);
        $this->SetFont('Times','B','10');
        $this->Cell(85,6,'Date :.....................................','',0,'L',$fill);
        $this->SetTextColor(0);
        $this->SetFont('Times','B','10');
        $this->Cell(85,6,'NIC No :.....................................','',0,'L',$fill);
        $this->SetTextColor(0);
        $this->SetFont('Times','B','10');
        $this->Cell(5,6,'','R',0,'L',$fill);
        $this->Ln();
        
        $this->SetTextColor(0);
        $this->SetFont('Times','B','10');
        $this->Cell(180,5,'','LRB',0,'L',$fill);
        $this->Ln();
        
        $fill=true; // used to alternate row color backgrounds

        }
		
        
        
        function BuildContrator($con) {
            
        $this->SetFillColor(255,255,255);
        $this->SetTextColor(0,0,0);
        $this->SetDrawColor(0,0,0);
        $this->SetLineWidth(.3);
        $this->SetFont('Times','B','12');
        
        $this->SetTextColor(0);
        $this->SetFont('Times','B','16');
        $this->Cell(180,110,'','LRT',0,'C',$fill);
        $this->Ln(); 
        
        
        $this->SetTextColor(0);
        $this->SetFont('Times','B','20');
        $this->Cell(180,10,$con,'LR',0,'C',$fill);
        $this->Ln();
        
        $this->SetTextColor(0);
        $this->SetFont('Times','B','16');
        $this->Cell(180,110,'','LRB',0,'C',$fill);
        $this->Ln();  
            
            
          $fill=true;    
        }    

}


// start and build the PDF document

$pdf = new PDF();
$pdf->SetLeftMargin(15);
$pdf->SetTopMargin(30);

//Column titles

$pdf->SetFont('Arial','',14);

$pdf->AddPage();

// call the table creation method
if($getftthcab[1] == 'AB-CAB' && ($osp_date[4] == 'Triple Play' || $osp_date[4] == 'Double Play - PeoTV')){
 $getiptv= oci_fetch_array(getiptv($getftthcab[3]));
    
$detail = array("$getftthcab[0]","$getftthcab[1]","$getftthcab[2]","$getftthcab[3]","$getftthcab[4]","$getftthcab[5]","$getftthcab[6]","$getftthcab[7]","$getftthcab[8]","$osp_date[4]","$getftthcab[11]","$getftthcab[13]","$getftthcab[9]","$getftthcab[10]");
$detailIPTV = array("$getiptv[0]","$getiptv[1]","$getiptv[2]","$getiptv[3]","$getiptv[4]","$getiptv[5]","$getiptv[6]","$getiptv[7]","$getiptv[8]","$getiptv[9]","$getiptv[10]");			  
$pdf->BuildTablePeo($detail,$detailIPTV,'IPTV');
$pdf->AddPage();
$pdf->BuildContrator($get_contra[1]);
}

if($getftthcab[1] == 'AB-CAB' && ($osp_date[4] == 'Single Play' || $osp_date[4]== 'Double Play-BB with modem' || $osp_date[4]== 'Double Play-BB without modem')){  
$detail = array("$getftthcab[0]","$getftthcab[1]","$getftthcab[2]","$getftthcab[3]","$getftthcab[4]","$getftthcab[5]","$getftthcab[6]","$getftthcab[7]","$getftthcab[8]","$osp_date[4]","$getftthcab[11]","$getftthcab[13]","$getftthcab[9]","$getftthcab[10]");	  
$detailIPTV= array();
$pdf->BuildTablePeo($detail,$detailIPTV, 'VOICE');
$pdf->AddPage();
$pdf->BuildContrator($get_contra[1]);
}

if($getftthcab[1] == 'AB-FTTH' && ($aa == 'Triple Play' || $aa == 'Double Play - PeoTV')){
 $getiptv= oci_fetch_array(getiptv($getftthcab[3]));
    
$detail = array("$getftthcab[0]","$getftthcab[1]","$getftthcab[2]","$getftthcab[3]","$getftthcab[4]","$getftthcab[5]","$getftthcab[6]","$getftthcab[7]","$getftthcab[8]","$aa","$getftthcab[11]","$getftthcab[13]","$getftthcab[9]","$getftthcab[10]");
$detailIPTV = array("$getiptv[0]","$getiptv[1]","$getiptv[2]","$getiptv[3]","$getiptv[4]","$getiptv[5]","$getiptv[6]","$getiptv[7]","$getiptv[8]","$getiptv[9]","$getiptv[10]");			  
$pdf->BuildTablePeo($detail,$detailIPTV,'IPTV');
$pdf->AddPage();
$pdf->BuildContrator($get_contra[1]);
}

if($getftthcab[1] == 'AB-FTTH' && ($aa == 'Single Play')){  
$detail = array("$getftthcab[0]","$getftthcab[1]","$getftthcab[2]","$getftthcab[3]","$getftthcab[4]","$getftthcab[5]","$getftthcab[6]","$getftthcab[7]","$getftthcab[8]","$aa","$getftthcab[11]","$getftthcab[13]","$getftthcab[9]","$getftthcab[10]");	  
$detailIPTV= array();
$pdf->BuildTablePeo($detail,$detailIPTV, 'VOICE');
$pdf->AddPage();
$pdf->BuildContrator($get_contra[1]);
}

if($getftthcab[1] == 'E-IPTV COPPER' || $getftthcab[1] == 'E-IPTV FTTH'){  
$detail = array("$getftthcab[0]","$getftthcab[1]","$getftthcab[2]","$getftthcab[3]","$getftthcab[4]","$getftthcab[5]","$getftthcab[6]","$getftthcab[7]","$getftthcab[8]","$osp_date[4]","$getftthcab[11]","$getftthcab[13]","$getftthcab[9]","$getftthcab[10]");	  
$detailIPTV= array();
$pdf->BuildTablePeo($detail,$detailIPTV, 'VOICE');
$pdf->AddPage();
$pdf->BuildContrator($get_contra[1]);
}

$pdf->Output();
