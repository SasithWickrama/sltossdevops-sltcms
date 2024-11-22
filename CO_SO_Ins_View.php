<?php
session_start();
if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true)
{    
    $user = $_SESSION["user"];
    $contractor = $_SESSION["contractor"];
}
else 
{     
    echo '<script type="text/javascript"> document.location = "login.php";</script>'; 
}
$so_id = $_GET["id"];
include "db.php";
require('Numbers/Words.php');
//require('C:\xampp\php\PEAR\fpdf\fpdf.php');
require('fpdf/fpdf.php');



$con_name = $contractor;
$conTax_no = 'Provisioning of New connections through unit rate contractors';
$project_no = $_POST["project_no"];
$tender_no = $_POST["tender_no"];
//$invoice_no = $_POST["invoice_no"];
$area = $_POST["area"];
$sertype = $_POST["sertype"];
$todate = $_POST["todate"];
$fromdate = $_POST["fromdate"];
$pcatogary = $_POST["pcatogary"];

$dt = substr($project_no,0,2);

//Megaline Invoice
if($sertype == 'AB-CAB')
{
 $con_no = 'Provisioning of Megaline New conections'; 
 $ct= 'NC'; 
} 

if($sertype == 'AB-FTTH')
{
 $con_no = 'Provisioning of FTTH New conections';
 $ct= 'FN'; 
} 


if($sertype == 'IPTV')
{
 $con_no = 'Provisioning of IPTV New conections'; 
 $ct= 'PEO';
} 

//INVOCIE NO GEN
$get_contra = oci_fetch_array(get_contra($con_name));

$getareadesc = getareadesc($area);

$setinv= setinv($contractor,$sertype,$area,$dt);
/*if($setinv== ""){
    $invoice_no = 'S/'.$get_contra[6].'/'.$ct.'/'.$dt.'/'.str_replace("R-", "", $area).'/001';
    insertinv($contractor,$sertype,$area,'001',$invoice_no,$dt);
}
else{

    $invoice_no = 'S/'.$get_contra[6].'/'.$ct.'/'.$dt.'/'.str_replace("R-", "", $area).'/'.$setinv;
    insertinv($contractor,$sertype,$area,$setinv,$invoice_no,$dt);
}*/



$comp_count = comp_count($area,$con_name,$sertype,$todate,$fromdate);
$rwcount = oci_fetch_all($comp_count,$result);

$rowcount =oci_num_rows($comp_count); 

if($rowcount != '0')
{
 if($setinv== ""){
	 
	if($pcatogary == 'P') {
		$invoice_no = 'P/'.$get_contra[6].'/'.$ct.'/'.$dt.'/'.str_replace("R-", "", $area).'/001';	
	}
    if($pcatogary == 'S') {
		$invoice_no = 'S/'.$get_contra[6].'/'.$ct.'/'.$dt.'/'.str_replace("R-", "", $area).'/001';	
	}
	
    insertinv($contractor,$sertype,$area,'001',$invoice_no,$dt);
}
else{
	if($pcatogary == 'P') {
		$invoice_no = 'P/'.$get_contra[6].'/'.$ct.'/'.$dt.'/'.str_replace("R-", "", $area).'/'.$setinv;	
	}
    if($pcatogary == 'S') {
		$invoice_no = 'S/'.$get_contra[6].'/'.$ct.'/'.$dt.'/'.str_replace("R-", "", $area).'/'.$setinv;	
	}
    
    insertinv($contractor,$sertype,$area,$setinv,$invoice_no,$dt);
}   
    
    
$invoice_update = invoice_update($con_name,$con_no,$project_no,$tender_no,$invoice_no,$area,$sertype,$user);

    if($invoice_update == 0)
    {
    echo "<script type='text/javascript'>alert('Successfully Generated!')</script>";
    echo "<script type='text/javascript'>window.location='CO_SO_INV_VIEW.php'</script>";
    $update_cir_inv =update_cir_inv($invoice_no,$con_name,$area,$sertype,$todate,$fromdate);
    $update_cir_inv2 =update_cir_inv2($invoice_no,$con_name,$area,$sertype,$todate,$fromdate);
    
    $invdate = invdate($invoice_no,$fromdate,$todate,$user);
    
    $invno =str_replace('/', '', $invoice_no);
    
    $getexcel = getMetexcel($invoice_no);
    $HEADER = "VOCIE NO,SN,DW-EW,FTTH-DW,EX-IPTV,PL-C-5.6-CE,PL-C-6.7,PL-C-7.5,PT-SP-VO-ID,PT-2P-VB-ID,PT-3P-BP-ID,FT-DP-VP-ID,FT-SP-PO-ID,PL-C-5.6-L,DW-DF,TL-WM-D-25,SC-C5,PT-SP-PO-ID,FT-DP-VB-ID,FT-DP-V3P-ID,FT-3P-B2P-ID,PL-C6.7CE,PL-C-9,PT-2P-VP-ID,FT-3P-BP-ID,PSTN-DW,DW-ER,FT-SP-VO-ID,FT-DP-V2P-ID,PL-C-5.6-H,PL-C-8,DW-LH,B-16,PLC-CON,PL-GI-50\n";
    $records = array();
    while( $rows = oci_fetch_array($getexcel) ) {
    
    
    $HEADER = $HEADER . "{$rows[0]},{$rows[1]},{$rows[2]},{$rows[3]},{$rows[4]},{$rows[5]},{$rows[6]},{$rows[7]},{$rows[8]},{$rows[9]},{$rows[10]},{$rows[11]},{$rows[12]},{$rows[13]},{$rows[14]},{$rows[15]},{$rows[16]},{$rows[17]},{$rows[18]},{$rows[19]},{$rows[20]},{$rows[21]},{$rows[22]},{$rows[23]},{$rows[24]},{$rows[25]},{$rows[26]},{$rows[27]},{$rows[28]},{$rows[29]},{$rows[30]},{$rows[31]},{$rows[32]},{$rows[33]},{$rows[34]}\n";	

    
    }
    
        $File = "Invoice/{$area}/{$con_name}/{$invno}_met.csv";
		$FILE_WRITE = fopen($File, 'w') or die("can't open file");
		fwrite($FILE_WRITE, $HEADER);
		fclose($FILE_WRITE);

    $get_invoice = get_invoice($invoice_no);
    $inv_detail = oci_fetch_array($get_invoice);
   
   //Delay penalty 
    $getpenalty = delaypenelty($invoice_no);
    $getpenalty2 = delaypenelty2($invoice_no);
    
    $delaypenelty = $getpenalty + $getpenalty2;
    
    //delay num wise
    $delay = getdelay($invoice_no);
    while( $row= oci_fetch_array($delay)){
      $delaysum[] =array($row[0],$row[1],$row[2],$row[3],$row[4]);  
        }
        
  
  // Quality SEVERTY
    $getqtyprnalty = getqtyprnalty($invoice_no);
 
        while($row = oci_fetch_array($getqtyprnalty)){
            
            insertSEVERTY($row[0],$invoice_no );
        }


    
 
 //Number vise total   
    $getsoid = getsoid($invoice_no);
    while($row = oci_fetch_array($getsoid)){
        
        $sod= $row[0];
        $vno= $row[1];
        $getvoicemet = getvoicemet($row[0]);
        
        while ($row =oci_fetch_array($getvoicemet))
        {
        
            $unit = oci_fetch_array(getunitref($area,$row[2]));
            
            
            $sump0 =  ($row[3] *round($unit[6],2));
            $totsump0 = number_format($sump0, 2) ;
            
            $sump1 =  ($row[4] *round($unit[7],2));
            $totsump1 = number_format($sump1, 2);
            
            $grossum = $grossum+$sump0 + $sump1;
            
            
           
        }
            insertMet($sod,$vno,$invoice_no,$grossum );
        $grossum="";
    } 
    
    
    //Quality Num wise
    $severty = severty($invoice_no);
    while( $row= oci_fetch_array($severty)){
       $severtysum[] =array($row[0],$row[1],$row[2],$row[3]);     
        }
    
    
    $qtypenelty  = qtypenelty($invoice_no);
  
    while( $row= oci_fetch_array($qtypenelty)){
        if($row[1] == 'CRITICAL'){
            $getcirpen =getcirpen($invoice_no,$row[0]);
        }
        if($row[1] == 'MAJOR'){
            $getcirpen = '10000.00';
        }
        if($row[1] == 'MINOR'){
            $getcirpen = '5000.00';
        }
        if($row[1] == 'NA'){
            $getcirpen = '0.00';
        }
      $qualitypenalty =  $qualitypenalty+ $getcirpen;
    }
  //Quantity 
  
    $peneltyqun = '0';
  
  //Meterial Summery     
    $getmet = getmet($invoice_no);
    
    
    
class PDF extends FPDF {
    
    function BuildTable($header,$header1,$data,$inv_detail,$tot1,$totS1,$rowcount,$a,$gross,$penalty,$get_contra,$qtypenelty,$peneltyqun,$getareadesc,$fromdate,$todate) {

        //Colors, line width and bold font

        $this->SetFillColor(255,255,255);
        $this->SetTextColor(0,0,0);
        $this->SetDrawColor(0,0,0);
        $this->SetLineWidth(.3);
        $this->SetFont('Times','B','12');

        $this->SetTextColor(0);
        $this->SetFont('Times','B','9');
        $this->Cell(36,5,'Contract','LRT',0,'L',$fill);
        $this->SetFont('Times','B','9');
		$this->Cell(82,5,$inv_detail[1],'LRT',0,'L',$fill);
		$this->SetFont('Times','B','9');
		$this->Cell(72,5,'','LRT',0,'L',$fill);
        $this->Ln();
        
        $this->SetTextColor(0);
        $this->SetFont('Times','B','9');
        $this->Cell(36,5,'Project No','LRT',0,'L',$fill);
        $this->SetFont('Times','B','9');
		$this->Cell(82,5,$inv_detail[2],'LRT',0,'L',$fill);
		$this->SetFont('Times','B','14');
		$this->Cell(72,5,'INVOICE SUMMARY','LR',0,'C',$fill);
        $this->Ln();
        
        
        $this->SetTextColor(0);
        $this->SetFont('Times','B','9');
        $this->Cell(36,5,'Tender No','LRT',0,'L',$fill);
        $this->SetFont('Times','B','9');
        $this->Cell(82,5,$inv_detail[3],'LRT',0,'L',$fill);
        $this->SetFont('Times','B','9');
		$this->Cell(72,5,'','LR',0,'L',$fill);
        $this->Ln();
        
        
        $this->SetTextColor(0);
        $this->SetFont('Times','B','9');
        $this->Cell(36,5,'RTOM Area','LRT',0,'L',$fill);
        $this->SetFont('Times','B','9');
		$this->Cell(82,5,$getareadesc,'LRT',0,'L',$fill);
        $this->SetFont('Times','B','9');
		$this->Cell(72,5,'','LR',0,'L',$fill);
        $this->Ln();
        
        
        
        $this->SetTextColor(0);
        $this->SetFont('Times','B','9');
        $this->Cell(36,5,'Invoice No','LRT',0,'L',$fill);
        $this->SetFont('Times','B','9');
        if($a == 'A')
        {
		$this->Cell(82,5,$inv_detail[4].' A','LRT',0,'L',$fill);
        }
        if($a == 'B')
        {
		$this->Cell(82,5,$inv_detail[4].' B','LRT',0,'L',$fill);
        }
		$this->SetFont('Times','B','9');
		$this->Cell(72,5,'Invoice Date  :     '.$inv_detail[5],'LRT',0,'C',$fill);
        $this->Ln();
        
        $this->SetTextColor(0);
        $this->SetFont('Times','B','9');
        $this->Cell(36,5,'No of Connections','LRT',0,'L',$fill);
        $this->SetFont('Times','B','9');
		$this->Cell(82,5,$rowcount,'LRT',0,'L',$fill);
        $this->SetFont('Times','B','9');
		$this->Cell(36,5,'From : '.$fromdate,'LRT',0,'L',$fill);
		$this->SetFont('Times','B','9');
		$this->Cell(36,5,'To : '.$todate,'LRT',0,'L',$fill);
        $this->Ln();       
        //Header

        // make an array for the column widths
        $x=array(7,22,7,62,20,30,42);
        $w=array(7,22,7,62,11,9,15,15,21,21);

        // send the headers to the PDF document

        for($i=0;$i<count($header);$i++)
        $this->Cell($x[$i],8,$header[$i],1,0,'C',1);
        $this->Ln();

        for($i=0;$i<count($header1);$i++)
        $this->Cell($w[$i],5,$header1[$i],'LRT',0,'C',1);
        $this->Ln();
        //Color and font restoration

       // $this->SetFillColor(175);

        $this->SetTextColor(0);
        $this->SetFont('');



        //now spool out the data from the $data array

        $fill=true; // used to alternate row color backgrounds

        foreach($data as $row)

        {
        $this->SetTextColor(0);
        $this->SetFont('Times','','9');
        $this->Cell($w[0],10,$row[0],'LRT',0,'C',$fill);

        // set colors to show a URL style link

        $this->SetTextColor(0);
        $this->SetFont('Times','','9');
        $this->Cell($w[1],10,$row[1],'LRT',0,'L',$fill);

        // restore normal color settings

        $this->SetTextColor(0);
        $this->SetFont('Times','','9');
        $this->Cell($w[2],10,$row[2],'LRT',0,'C',$fill);

        $y = $this->GetY();
        $x = $this->GetX();

        $this->SetTextColor(0);
        $this->SetFont('Times','','6');
        $this->MultiCell($w[3],3,$row[3],'LRT','L',FALSE);
        $this->SetXY($x + $w[3], $y);
        
        
        $this->SetTextColor(0);
        $this->SetFont('Times','','9');
        $this->Cell($w[4],10,$row[4],'LRT',0,'R',$fill);
        
        $this->SetTextColor(0);
        $this->SetFont('Times','','9');
        $this->Cell($w[5],10,$row[5],'LRT',0,'R',$fill);
        
        $this->SetTextColor(0);
        $this->SetFont('Times','','9');
        $this->Cell($w[6],10,$row[6],'LRT',0,'R',$fill);
        
        
        $this->SetTextColor(0);
        $this->SetFont('Times','','9');
        $this->Cell($w[7],10,$row[7],'LRT',0,'R',$fill);
        
        $this->SetTextColor(0);
        $this->SetFont('Times','','9');
        $this->Cell($w[8],10,$row[8],'LRT',0,'R',$fill);
        
        $this->SetTextColor(0);
        $this->SetFont('Times','','9');
        $this->Cell($w[9],10,$row[9],'LRT',0,'R',$fill);

        $this->Ln();

        // flips from true to false and vise versa

        $fill =! $fill;

        }
        $this->SetFont('Times','B','9');
        $this->Cell(148,6,'Total','1',0,'L',$fill);
        $this->Cell(21,6,$tot1,'1',0,'R',$fill);
        $this->Cell(21,6,$totS1,'1',0,'R',$fill);
        $this->Ln();
        
        
        $this->Cell(148,6,'Total Gross Amount','1',0,'L',$fill);
        $this->Cell(42,6,number_format(round($gross, 2),2),'1',0,'R',$fill);
        $this->Ln();
        
        $opmtc = '0.05';
        $gros6 = (round($gross, 2) * 60)/ 100 ;
        $gros4 = (round($gross, 2) * 40)/ 100 ;   
        
        //Deduction 
        
        
        $totdeduct = round($penalty, 2)+round($qtypenelty, 2)+round($peneltyqun, 2) ;
        $deductgross = round($gros4, 2) - $totdeduct;

        if($a == 'A')
        {
          $nbt = (round($gros6, 2) * $get_contra[5])/ 100;
          $setgross = $gros6 + $nbt; 
          $vat = (round($setgross, 2) * $get_contra[4])/ 100;    
            
        $omc = (round($gross, 2) * $opmtc)/ 100;       
        $net =round($gros6,2) + round($vat, 2) + round($nbt, 2)  ;  
        $this->Cell(148,6,'60% of Gross Amount','1',0,'L',$fill);
        $this->Cell(42,6,number_format(round($gros6, 2),2),'1',0,'R',$fill);
        $this->Ln();
        
        }
        if($a == 'B')
        {
        $getgross= round($gros4, 2)-$totdeduct;
        $nbt = (round($getgross, 2) * $get_contra[5])/ 100; 
         
        $setgross = $getgross + $nbt;
        $vat = (round($setgross, 2) * $get_contra[4])/ 100; 
        
        $net =round($gros4, 2) + round($vat, 2) + round($nbt, 2) -round($totdeduct, 2) ;  
        $this->Cell(148,6,'40% of Gross Amount before deductions','1',0,'L',$fill);
        $this->Cell(42,6,number_format(round($gros4, 2),2),'1',0,'R',$fill);
        $this->Ln();
        
        $this->Cell(148,6,'Delay Penalty','1',0,'L',$fill);
        $this->Cell(21,6,number_format($penalty,2),'1',0,'R',$fill);
        $this->Cell(21,6,'','1',0,'R',$fill);
        $this->Ln();
        
        $this->Cell(148,6,'Quantity Deviations','1',0,'L',$fill);
        $this->Cell(21,6,number_format($peneltyqun,2),'1',0,'R',$fill);
        $this->Cell(21,6,'','1',0,'R',$fill);
        $this->Ln();
        
        $this->Cell(148,6,'Quality Issues','1',0,'L',$fill);
        $this->Cell(21,6,number_format($qtypenelty,2),'1',0,'R',$fill);
        $this->Cell(21,6,'','1',0,'R',$fill);
        $this->Ln();
        
        $this->Cell(148,6,'Total Deductions','1',0,'L',$fill);
        $this->Cell(21,6,'','1',0,'R',$fill);
        $this->Cell(21,6,number_format($totdeduct,2),'1',0,'R',$fill);
        $this->Ln();
        
        $this->Cell(148,6,'balance Gross Amount after deductions','1',0,'L',$fill);
        $this->Cell(42,6,number_format($deductgross,2),'1',0,'R',$fill);
        $this->Ln();
        }
        

	/*	$this->Cell(148,6,'NBT '.$get_contra[5].'% (ADD)','1',0,'L',$fill);
        $this->Cell(42,6,number_format(round($nbt, 2),2),'1',0,'R',$fill);
        $this->Ln(); */

        
        $this->Cell(148,6,'VAT '.$get_contra[4].'% (ADD)','1',0,'L',$fill);
        $this->Cell(42,6,number_format(round($vat, 2),2),'1',0,'R',$fill);
        $this->Ln();
                
        $this->Cell(148,6,'Net Amount','1',0,'L',$fill);
        $this->Cell(42,6,number_format(round($net, 2),2),'1',0,'R',$fill);
        $this->Ln();
        
        /*if($a == 'A')
        {
        $this->Cell(148,6,'OMC 0.05%','1',0,'L',$fill);
        $this->Cell(42,6,number_format(round($omc, 2),2),'1',0,'R',$fill);
        $this->Ln();
        }*/
        $this->Cell(95,6,$inv_detail[0],'1',0,'C',$fill);
        $this->Cell(95,6,'Sri Lanka Telecom PLC','1',0,'C',$fill);
        $this->Ln();
        
        $this->SetFont('Times','','9');
        $this->Cell(95,6,'Prepared By :','1',0,'L',$fill);
        $this->Cell(95,6,'Checked By :','1',0,'L',$fill);
        $this->Ln();
        
        $this->Cell(95,6,'Checked By :','1',0,'L',$fill);
        $this->Cell(95,6,'Recommended By :','1',0,'L',$fill);
        $this->Ln();
        
        $this->Cell(95,12,'Project Manager :','1',0,'L',$fill);
        $this->Cell(95,12,'Approved By :','1',0,'L',$fill);
        $this->Ln();
        
        
        
        //$this->Cell(array_sum($w),0,'','T');

    }
            
    function BuildTableTax($inv_detail,$totsump0,$totsump1,$a,$rowcount,$gross,$get_contra,$getpenalty,$qualitypenalty,$peneltyqun,$getareadesc){
            
            
        $this->SetFillColor(255,255,255);
        $this->SetTextColor(0,0,0);
        $this->SetDrawColor(0,0,0);
        $this->SetLineWidth(.3);
        $this->SetFont('Times','B','12');
        
        $this->SetTextColor(0);
        $this->SetFont('Times','B','12');
        $this->Cell(190,8,'TAX INVOICE','LRT',0,'C',$fill);
        $this->Ln();
        
        $this->SetTextColor(0);
        $this->SetFont('Times','B','9');
        $this->Cell(190,5,'Contract Name: '.$inv_detail[1],'LRT',0,'L',$fill);
        $this->Ln();
        
        $this->SetTextColor(0);
        $this->SetFont('Times','B','9');
        $this->Cell(190,4,'','LR',0,'L',$fill);
        $this->Ln();
        
        $this->SetTextColor(0);
        $this->SetFont('Times','B','9');
        $this->Cell(95,5,'Tender No        : '.$inv_detail[3],'L',0,'L',$fill);
        $this->SetTextColor(0);
        $this->SetFont('Times','B','9');
        $this->Cell(95,5,'RTOM Area: '.$getareadesc,'R',0,'L',$fill); 
        $this->Ln();       
        
        if($a == 'A')
        {
		$in =$inv_detail[4].' A';
        }
        if($a == 'B')
        {
		$in =$inv_detail[4].' B';
        }
        
        $this->SetTextColor(0);
        $this->SetFont('Times','B','9');
        $this->Cell(95,5,'','L',0,'L',$fill);
        $this->SetTextColor(0);
        $this->SetFont('Times','B','9');
        $this->Cell(95,5,'Invoice No    : '.$in,'R',0,'L',$fill);
        $this->Ln();        
          
        $this->SetTextColor(0);
        $this->SetFont('Times','B','9');
        $this->Cell(95,5,'','L',0,'L',$fill);
        $this->SetTextColor(0);
        $this->SetFont('Times','B','9');
        $this->Cell(95,5,'Date              : '.$inv_detail[5],'R',0,'L',$fill); 
        $this->Ln();   
        
        
        $this->SetTextColor(0);
        $this->SetFont('Times','B','9');
        $this->Cell(40,5,'Customer Reference','L',0,'L',$fill);
        $this->SetTextColor(0);
        $this->SetFont('Times','B','9');
        $this->Cell(150,5,': Mr. W.G.G. Gunatunga','R',0,'L',$fill);
        $this->Ln();
        
        $this->SetTextColor(0);
        $this->SetFont('Times','B','9');
        $this->Cell(40,5,'Contact ','L',0,'L',$fill);
        $this->SetTextColor(0);
        $this->SetFont('Times','B','9');
        $this->Cell(150,5,': Deputy General Manager [DGM-NC & FSP]','R',0,'L',$fill);
        $this->Ln();
        
        $this->SetTextColor(0);
        $this->SetFont('Times','B','9');
        $this->Cell(190,5,'','LR',0,'L',$fill);
        $this->Ln();
        
        $this->SetTextColor(0);
        $this->SetFont('Times','B','9');
        $this->Cell(190,8,'Total No of Connections:     '.$rowcount,'LRT',0,'L',$fill);
        $this->Ln();
        
        $this->SetTextColor(0);
        $this->SetFont('Times','B','9');
        $this->Cell(190,5,'','LRT',0,'L',$fill);
        $this->Ln();
        
        $this->SetTextColor(0);
        $this->SetFont('Times','B','9');
        $this->Cell(120,6,'Supplier           : '.$get_contra[1],'L',0,'L',$fill);
        $this->SetTextColor(0);
        $this->SetFont('Times','B','9');
        $this->Cell(70,6,'Recipient         : Sri lanka Telecom PLC','R',0,'L',$fill);
        $this->Ln();
        
        $this->SetTextColor(0);
        $this->SetFont('Times','B','9');
        $this->Cell(120,6,'V.A.T. Reg No: '.$get_contra[3],'L',0,'L',$fill);
        $this->SetTextColor(0);
        $this->SetFont('Times','B','9');
        $this->Cell(70,6,'V.A.T. Reg No: 294001727-7000','R',0,'L',$fill); 
        $this->Ln();      
        
        $y = $this->GetY();
        $x = $this->GetX();
        
        
        $this->SetTextColor(0);
        $this->SetFont('Times','B','9');
        $this->MultiCell(120,6,'Address           :'.$get_contra[2],'L','L',FALSE);
        $this->SetXY($x + '120', $y);
        $this->SetTextColor(0);
        $this->SetFont('Times','B','9');
        $this->Cell(70,6,'Address           : Lotus Road, Colombo 01','R',0,'L',$fill); 
        $this->Ln();  
        
        $this->SetFont('Times','B','9');
        $this->Cell(190,5,'','LR',0,'L',$fill);
        $this->Ln();
        
        
        $this->SetTextColor(0);
        $this->SetFont('Times','B','9');
        $this->Cell(130,7,'Description','LRT',0,'C',$fill);
        $this->SetTextColor(0);
        $this->SetFont('Times','B','9');
        $this->Cell(30,7,'Add/Deduct (Rs.)','LRT',0,'C',$fill);
        $this->SetTextColor(0);
        $this->SetFont('Times','B','9');
        $this->Cell(30,7,'Amount (Rs.)','LRT',0,'C',$fill); 
        $this->Ln();
        
        $this->SetTextColor(0);
        $this->SetFont('Times','B','9');
        $this->Cell(130,6,'Total Base Price - P0','LRT',0,'L',$fill);
        $this->SetTextColor(0);
        $this->SetFont('Times','B','9');
        $this->Cell(30,6,$totsump0,'LRT',0,'R',$fill);
        $this->SetTextColor(0);
        $this->SetFont('Times','B','9');
        $this->Cell(30,6,'','LRT',0,'L',$fill); 
        $this->Ln();
        
        $this->SetTextColor(0);
        $this->SetFont('Times','B','9');
        $this->Cell(130,6,'Total Base Price - P1','LRT',0,'L',$fill);
        $this->SetTextColor(0);
        $this->SetFont('Times','B','9');
        $this->Cell(30,6,$totsump1,'LRT',0,'R',$fill);
        $this->SetTextColor(0);
        $this->SetFont('Times','B','9');
        $this->Cell(30,6,'','LRT',0,'L',$fill); 
        $this->Ln();
        
        
        $this->SetTextColor(0);
        $this->SetFont('Times','B','9');
        $this->Cell(130,6,'Gross Invoice Amount ','LRT',0,'L',$fill);
        $this->SetTextColor(0);
        $this->SetFont('Times','B','9');
        $this->Cell(30,6,'','LRT',0,'L',$fill);
        $this->SetTextColor(0);
        $this->SetFont('Times','B','9');
        $this->Cell(30,6,number_format(round($gross, 2),2),'LRT',0,'R',$fill);
        $this->Ln();  
        
		
        $gros6 = (round($gross, 2) * 60)/ 100 ;
        $gros4 = (round($gross, 2) * 40)/ 100 ;   
        
        $grossdeduct = round($gross, 2) - round($getpenalty, 2);
        $this->SetTextColor(0);
        $this->SetFont('Times','B','9');
        if($a == 'A')
        {
        $nbt = (round($gros6, 2) * $get_contra[5])/ 100;     
        $setgross = $gros6 + $nbt;
        $vat = (round($setgross, 2) * $get_contra[4])/ 100;    
            
        
         
        $net =round($gros6,2) + round($vat, 2) + round($nbt, 2) ; 
        //$figure = convert_number_to_words(round($net, 2));
        $nw = new Numbers_Words();
        $figure = $nw->toCurrency(round($net, 2));
               
        $this->Cell(130,6,'60% of Gross Amount ','1',0,'L',$fill);
        $this->SetTextColor(0);
        $this->SetFont('Times','B','9');
        $this->Cell(30,6,'','LRT',0,'L',$fill);
        $this->SetTextColor(0);
        $this->SetFont('Times','B','9');
        $this->Cell(30,6,number_format(round($gros6, 2),2),'1',0,'R',$fill);
        $this->Ln();
        /*$this->SetTextColor(0);
        $this->SetFont('Times','B','9');
        $this->Cell(130,6,'NBT '.$get_contra[5].'%','LRT',0,'L',$fill);
        $this->SetTextColor(0);
        $this->SetFont('Times','B','9');
        $this->Cell(30,6,number_format(round($nbt, 2),2),'LRT',0,'R',$fill);
        $this->SetTextColor(0);
        $this->SetFont('Times','B','9');
        $this->Cell(30,6,'','LRT',0,'L',$fill);
        $this->Ln();*/
        $this->SetTextColor(0);
        $this->SetFont('Times','B','9');
        $this->Cell(130,6,'VAT '.$get_contra[4].'%','LRT',0,'L',$fill);
        $this->SetTextColor(0);
        $this->SetFont('Times','B','9');
        $this->Cell(30,6,number_format(round($vat, 2),2),'LRT',0,'R',$fill);
        $this->SetTextColor(0);
        $this->SetFont('Times','B','9');
        $this->Cell(30,6,'','LRT',0,'L',$fill);
        $this->Ln();
        }
        if($a == 'B')
        {
            
         $totdeduct = round($getpenalty, 2)+round($qualitypenalty, 2)+round($peneltyqun, 2) ;
        
        $getgross= round($gros4, 2)-$totdeduct;
        $nbt = (round($getgross, 2) * $get_contra[5])/ 100; 
         
        $setgross = $getgross + $nbt;
        $vat = (round($setgross, 2) * $get_contra[4])/ 100; 
        
        $net =round($gros4, 2) + round($vat, 2) + round($nbt, 2) -round($totdeduct, 2) ;
       // $figure = convert_number_to_words(round($net, 2));   
       $nw = new Numbers_Words();
        $figure = $nw->toCurrency(round($net, 2));
        
        $this->Cell(130,6,'40% of Gross Amount ','1',0,'L',$fill);
        $this->SetTextColor(0);
        $this->SetFont('Times','B','9');
        $this->Cell(30,6,'','LRT',0,'L',$fill);
        $this->SetTextColor(0);
        $this->SetFont('Times','B','9');
        $this->Cell(30,6,number_format(round($gros4, 2),2),'1',0,'R',$fill);
        $this->Ln();
        $this->Cell(130,6,'Balance Gross Invoice Amount after deductions','1',0,'L',$fill);
        $this->SetTextColor(0);
        $this->SetFont('Times','B','9');
        $this->Cell(30,6,'','LRT',0,'L',$fill);
        $this->SetTextColor(0);
        $this->SetFont('Times','B','9');
        $this->Cell(30,6,number_format($getgross,2),'1',0,'R',$fill);
        $this->Ln();
       /* $this->SetTextColor(0);
        $this->SetFont('Times','B','9');
        $this->Cell(130,6,'NBT '.$get_contra[5].'%','LRT',0,'L',$fill);
        $this->SetTextColor(0);
        $this->SetFont('Times','B','9');
        $this->Cell(30,6,number_format(round($nbt, 2),2),'LRT',0,'R',$fill);
        $this->SetTextColor(0);
        $this->SetFont('Times','B','9');
        $this->Cell(30,6,'','LRT',0,'L',$fill);
        $this->Ln(); */
        $this->SetTextColor(0);
        $this->SetFont('Times','B','9');
        $this->Cell(130,6,'VAT '.$get_contra[4].'%','LRT',0,'L',$fill);
        $this->SetTextColor(0);
        $this->SetFont('Times','B','9');
        $this->Cell(30,6,number_format(round($vat, 2),2),'LRT',0,'R',$fill);
        $this->SetTextColor(0);
        $this->SetFont('Times','B','9');
        $this->Cell(30,6,'','LRT',0,'L',$fill);
        $this->Ln();
        }
        
    
        $this->SetTextColor(0);
        $this->SetFont('Times','B','9');
        $this->Cell(130,6,'Net Payable','LRT',0,'L',$fill);
        $this->SetTextColor(0);
        $this->SetFont('Times','B','9');
        $this->Cell(30,6,'','LRT',0,'L',$fill);
        $this->SetTextColor(0);
        $this->SetFont('Times','B','9');
        $this->Cell(30,6,number_format(round($net, 2),2),'LRT',0,'R',$fill);
        $this->Ln(); 
        
        $this->SetTextColor(0);
        $this->SetFont('Times','B','9');
        $this->Cell(190,8,'Amount in Figures:    '.$figure,'LRT',0,'L',$fill);
        $this->Ln();
        
        $this->SetFont('Times','B','9');
        $this->Cell(190,8,'','LRT',0,'L',$fill);
        $this->Ln();
        
        $this->SetFont('Times','B','9');
        $this->Cell(190,6,'Signed By : .................................','LR',0,'L',$fill);
        $this->Ln();
        
        $this->SetFont('Times','B','9');
        $this->Cell(190,6,'[Project Manager]','LR',0,'L',$fill);
        $this->Ln();
        
        $this->SetFont('Times','B','9');
        $this->Cell(190,8,'','LR',0,'L',$fill);
        $this->Ln();
        
        $this->SetFont('Times','B','9');
        $this->Cell(190,8,'Official Frank  : .................................','LR',0,'L',$fill);
        $this->Ln();
        
        $this->SetFont('Times','B','9');
        $this->Cell(190,8,'','LR',0,'L',$fill);
        $this->Ln();
        
        $this->SetFont('Times','B','9');
        $this->Cell(190,6,'','T',0,'L',$fill);
        $this->Ln();
        

        //Color and font restoration

       // $this->SetFillColor(175);

        //now spool out the data from the $data array

        $fill=true; // used to alternate row color backgrounds

        
     }
        
    //Buid Front 
    
        function BuildTableFront($inv_detail,$get_contra,$a,$getareadesc) {
            
         $pageWidth = '190';
         $pageHeight = '280';


        $margin = 10;
        $this->Rect( $margin, $margin , $pageWidth , $pageHeight - $margin);
        
        if($inv_detail[6]== 'IPTV'){
        
            $st = 'PEO TV';
        }
        if($inv_detail[6]== 'AB-FTTH'){
        
            $st = 'FTTH';
        }
        if($inv_detail[6]== 'AB-CAB'){
        
            $st = 'Mega Line';
        }
        

            
        $this->SetFillColor(255,255,255);
        $this->SetTextColor(0,0,0);
        $this->SetDrawColor(0,0,0);
        $this->SetLineWidth(.3);
        $this->SetFont('Times','B','12');
        
        $this->SetTextColor(0);
        $this->SetFont('Times','B','12');
        $this->Cell(190,50,'','',0,'C',$fill);
        $this->Ln();

        $this->SetTextColor(0);
        $this->SetFont('Times','B','15');
        $this->Cell(190,8,$get_contra[1],'',0,'C',$fill);
        $this->Ln();
        
        $this->SetTextColor(0);
        $this->SetFont('Times','B','12');
        $this->Cell(190,8,'Project NO : '.$inv_detail[2],'',0,'C',$fill);
        $this->Ln();
        
        $this->SetTextColor(0);
        $this->SetFont('Times','B','12');
        $this->Cell(190,8,'Service Type : '.$st,'',0,'C',$fill);
        $this->Ln();
        
        $this->SetTextColor(0);
        $this->SetFont('Times','B','12');
        $this->Cell(190,8,'RTOM Area : '.$getareadesc,'',0,'C',$fill);
        $this->Ln();
        
        $this->SetTextColor(0);
        $this->SetFont('Times','B','12');
        $this->Cell(190,8,'','',0,'C',$fill);
        $this->Ln();
        
        if($a == 'A')
        {
		$in =$inv_detail[4].' A';
        }
        if($a == 'B')
        {
		$in =$inv_detail[4].' B';
        }
        
        
        
        $this->SetTextColor(0);
        $this->SetFont('Times','B','22');
        $this->Cell(190,12,$in,'',0,'C',$fill);
        $this->Ln();
        
        $this->SetTextColor(0);
        $this->SetFont('Times','B','12');
        $this->Cell(190,10,'','',0,'C',$fill);
        $this->Ln();
        
        $this->SetTextColor(0);
        $this->SetFont('Times','B','12');
        $this->Cell(190,10,'NC & FSP SECTION','LRT',0,'C',$fill);
        $this->Ln();
        
        $this->SetTextColor(0);
        $this->SetFont('Times','B','12');
        $this->Cell(190,20,'','LRT',0,'C',$fill);
        $this->Ln();
        
        $this->SetTextColor(0);
        $this->SetFont('Times','B','12');
        $this->Cell(160,8,'INVOICE CHECK FLOW MONITORING','RT',0,'C',$fill);
        $this->SetTextColor(0);
        $this->SetFont('Times','B','8');
        $this->Cell(30,8,'','',0,'C',$fill);
        $this->Ln();
        
        $this->SetTextColor(0);
        $this->SetFont('Times','B','10');
        $this->Cell(160,8,'New connection & Field Service Projects','R',0,'C',$fill);
        $this->SetTextColor(0);
        $this->SetFont('Times','B','8');
        $this->Cell(30,8,'','',0,'C',$fill);
        $this->Ln();
        
        $this->SetTextColor(0);
        $this->SetFont('Times','B','10');
        $this->Cell(160,8,'Operational Support Division','R',0,'C',$fill);
        $this->SetTextColor(0);
        $this->SetFont('Times','B','8');
        $this->Cell(30,8,'','',0,'C',$fill);
        $this->Ln();
        
        
        $this->SetTextColor(0);
        $this->SetFont('Times','B','8');
        $this->Cell(100,8,'Item','LRT',0,'C',$fill);
        $this->SetTextColor(0);
        $this->SetFont('Times','B','8');
        $this->Cell(30,8,'Date','LRT',0,'C',$fill);
        $this->SetTextColor(0);
        $this->SetFont('Times','B','8');
        $this->Cell(30,8,'Signature','LRT',0,'C',$fill);
        $this->SetTextColor(0);
        $this->SetFont('Times','B','8');
        $this->Cell(30,8,'','',0,'C',$fill);
        $this->Ln();
        
        $this->SetTextColor(0);
        $this->SetFont('Times','B','8');
        $this->Cell(100,8,'01) Check for Original Documents','LRT',0,'L',$fill);
        $this->SetTextColor(0);
        $this->SetFont('Times','B','8');
        $this->Cell(30,8,'','LRT',0,'C',$fill);
        $this->SetTextColor(0);
        $this->SetFont('Times','B','8');
        $this->Cell(30,8,'','LRT',0,'C',$fill);
        $this->SetTextColor(0);
        $this->SetFont('Times','B','8');
        $this->Cell(30,8,'','',0,'C',$fill);
        $this->Ln();
        
        $this->SetTextColor(0);
        $this->SetFont('Times','B','8');
        $this->Cell(100,8,'02) Enter to the database','LRT',0,'L',$fill);
        $this->SetTextColor(0);
        $this->SetFont('Times','B','8');
        $this->Cell(30,8,'','LRT',0,'C',$fill);
        $this->SetTextColor(0);
        $this->SetFont('Times','B','8');
        $this->Cell(30,8,'','LRT',0,'C',$fill);
        $this->SetTextColor(0);
        $this->SetFont('Times','B','8');
        $this->Cell(30,8,'','',0,'C',$fill);
        $this->Ln();
        
        $this->SetTextColor(0);
        $this->SetFont('Times','B','8');
        $this->Cell(100,8,'03) Recommendations for Payments','LRT',0,'l',$fill);
        $this->SetTextColor(0);
        $this->SetFont('Times','B','8');
        $this->Cell(30,8,'','LRT',0,'C',$fill);
        $this->SetTextColor(0);
        $this->SetFont('Times','B','8');
        $this->Cell(30,8,'','LRT',0,'C',$fill);
        $this->SetTextColor(0);
        $this->SetFont('Times','B','8');
        $this->Cell(30,8,'','',0,'C',$fill);
        $this->Ln();
        
        $this->SetTextColor(0);
        $this->SetFont('Times','B','8');
        $this->Cell(100,8,'04) Checking signatures & dates','LRT',0,'L',$fill);
        $this->SetTextColor(0);
        $this->SetFont('Times','B','8');
        $this->Cell(30,8,'','LRT',0,'C',$fill);
        $this->SetTextColor(0);
        $this->SetFont('Times','B','8');
        $this->Cell(30,8,'','LRT',0,'C',$fill);
        $this->SetTextColor(0);
        $this->SetFont('Times','B','8');
        $this->Cell(30,8,'','',0,'C',$fill);
        $this->Ln();
        
        
        
        
        
        
        $this->SetTextColor(0);
        $this->SetFont('Times','B','12');
        $this->Cell(160,10,'','T',0,'C',$fill);
        $this->Ln();
        
       $fill=true; // used to alternate row color backgrounds     
        }    

        function BuildTableCert($inv_detail,$get_contra,$a,$getareadesc,$grossum,$delaypenelty,$qualitypenalty,$peneltyqun) {
            
          $pageWidth = '190';
         $pageHeight = '280';
         
            $delaypenelty= '0';
            
        $totdeduct = round($delaypenelty, 2)+round($qualitypenalty, 2)+round($peneltyqun, 2) ;
        $deductgross = round($grossum, 2) - $totdeduct;
        
        $nbtA = ($get_contra[5]+100)/ 100;
        $vatA = ($get_contra[4]+100)/ 100;
          
        
        $net = $deductgross * $nbtA * $vatA;

        $margin = 10;
        $this->Rect( $margin, $margin , $pageWidth , $pageHeight - $margin);

            
        $this->SetFillColor(255,255,255);
        $this->SetTextColor(0,0,0);
        $this->SetDrawColor(0,0,0);
        $this->SetLineWidth(.3);
        $this->SetFont('Times','B','12');
        
        $this->SetTextColor(0);
        $this->SetFont('Times','B','12');
        $this->Cell(190,10,'','',0,'C',$fill);
        $this->Ln();
        
        $this->SetTextColor(0);
        $this->SetFont('Times','B','15');
        $this->Cell(190,15,'CERTIFICATION','',0,'C',$fill);
        $this->Ln();

        $in =$inv_detail[4].' B';

        $this->SetTextColor(0);
        $this->SetFont('Times','B','12');
        $this->Cell(10,10,'','',0,'L',$fill);
        $this->SetTextColor(0);
        $this->SetFont('Times','B','12');
        $this->Cell(50,10,'Invoice No','',0,'L',$fill);
        $this->SetTextColor(0);
        $this->SetFont('Times','B','12');
        $this->Cell(80,10,':'.$in,'',0,'L',$fill);
        $this->SetTextColor(0);
        $this->SetFont('Times','B','12');
        $this->Cell(50,10,'Date: '.$inv_detail[5],'R',0,'L',$fill); 
        $this->Ln();  
        
        $this->SetTextColor(0);
        $this->SetFont('Times','B','12');
        $this->Cell(10,10,'','',0,'L',$fill);
        $this->SetTextColor(0);
        $this->SetFont('Times','B','12');
        $this->Cell(50,10,'Contract Reference','',0,'L',$fill);
        $this->SetTextColor(0);
        $this->SetFont('Times','B','12');
        $this->Cell(130,10,':','',0,'L',$fill);
        $this->Ln();
        
        
        $this->SetTextColor(0);
        $this->SetFont('Times','B','12');
        $this->Cell(10,10,'','',0,'L',$fill);
        $this->SetTextColor(0);
        $this->SetFont('Times','B','12');
        $this->Cell(50,10,'Project NO','',0,'L',$fill);
        $this->SetTextColor(0);
        $this->SetFont('Times','B','12');
        $this->Cell(130,10,': '.$inv_detail[2],'',0,'L',$fill);
        $this->Ln();
        
        $this->SetTextColor(0);
        $this->SetFont('Times','B','12');
        $this->Cell(10,10,'','',0,'L',$fill);
        $this->SetTextColor(0);
        $this->SetFont('Times','B','12');
        $this->Cell(50,10,'Title of Contract','',0,'L',$fill);
        $this->SetTextColor(0);
        $this->SetFont('Times','B','12');
        $this->Cell(130,10,': '.$inv_detail[1],'',0,'L',$fill);
        $this->Ln();
        
        $this->SetTextColor(0);
        $this->SetFont('Times','B','12');
        $this->Cell(10,10,'','',0,'L',$fill);
        $this->SetTextColor(0);
        $this->SetFont('Times','B','12');
        $this->Cell(50,10,'Exchange Area','',0,'L',$fill);
        $this->SetTextColor(0);
        $this->SetFont('Times','B','12');
        $this->Cell(130,10,': '.$getareadesc,'',0,'L',$fill);
        $this->Ln();
        
        $this->SetTextColor(0);
        $this->SetFont('Times','B','12');
        $this->Cell(10,10,'','',0,'L',$fill);
        $this->SetTextColor(0);
        $this->SetFont('Times','B','12');
        $this->Cell(50,10,'Amount as per Invoice','',0,'L',$fill);
        $this->SetTextColor(0);
        $this->SetFont('Times','B','12');
        $this->Cell(130,10,': '.number_format($net,2),'',0,'L',$fill);
        $this->Ln();
        
        $this->SetTextColor(0);
        $this->SetFont('Times','B','12');
        $this->Cell(190,10,'','',0,'C',$fill);
        $this->Ln();
        
        $this->SetTextColor(0);
        $this->SetFont('Times','B','12');
        $this->Cell(10,10,'','',0,'L',$fill);
        $this->SetTextColor(0);
        $this->SetFont('Times','B','15');
        $this->Cell(180,10,'Provisional Acceptance Certification','',0,'C',$fill);
        
        $this->Ln();
        
        $this->SetTextColor(0);
        $this->SetFont('Times','B','12');
        $this->Cell(10,10,'','',0,'L',$fill);
        $this->SetTextColor(0);
        $this->SetFont('Times','B','12');
        $this->Cell(180,10,'The new Subscriber lines of which the details are given in this invoice are Provisionally accepted.','',0,'L',$fill);
        $this->Ln();
        
        $this->SetTextColor(0);
        $this->SetFont('Times','B','12');
        $this->Cell(190,15,'','',0,'C',$fill);
        $this->Ln();

        
        $this->SetTextColor(0);
        $this->SetFont('Times','B','12');
        $this->Cell(10,10,'','',0,'L',$fill);
        $this->SetTextColor(0);
        $this->SetFont('Times','B','12');
        $this->Cell(50,10,'Certified','',0,'L',$fill);
        $this->SetTextColor(0);
        $this->SetFont('Times','B','12');
        $this->Cell(140,10,':........................................','',0,'L',$fill);
        $this->Ln();
        
        $this->SetTextColor(0);
        $this->SetFont('Times','B','12');
        $this->Cell(190,15,'','',0,'C',$fill);
        $this->Ln();


        $this->SetTextColor(0);
        $this->SetFont('Times','B','12');
        $this->Cell(10,10,'','',0,'L',$fill);
        $this->SetTextColor(0);
        $this->SetFont('Times','B','12');
        $this->Cell(50,10,'Payment Approved','',0,'L',$fill);
        $this->SetTextColor(0);
        $this->SetFont('Times','B','12');
        $this->Cell(140,10,':........................................','',0,'L',$fill);
        $this->Ln();
        
        $this->SetTextColor(0);
        $this->SetFont('Times','B','12');
        $this->Cell(190,15,'','',0,'C',$fill);
        $this->Ln();

        $this->SetTextColor(0);
        $this->SetFont('Times','B','12');
        $this->Cell(10,10,'','',0,'L',$fill);
        $this->SetTextColor(0);
        $this->SetFont('Times','B','12');
        $this->Cell(50,10,'Date','',0,'L',$fill);
        $this->SetTextColor(0);
        $this->SetFont('Times','B','12');
        $this->Cell(140,10,':........................................','',0,'L',$fill);
        $this->Ln();
        
 
       $fill=true; // used to alternate row color backgrounds     
        }
        
        //Deduction
        function BuildTableDedu($inv_detail,$get_contra,$invno,$a,$getareadesc) {
            
        $this->SetFillColor(255,255,255);
        $this->SetTextColor(0,0,0);
        $this->SetDrawColor(0,0,0);
        $this->SetLineWidth(.3);
        $this->SetFont('Times','B','12');
        
        $y = $this->GetY();
        $x = $this->GetX();
        
        
        $this->SetTextColor(0);
        $this->SetFont('Times','B','8');
        $this->MultiCell(190,4,'Following detail report shows all applicable penalties for each new connection. However, in order to maintain uniformity, only one charge of particular quality penalty is actually applied and deducted. Overall deduction is mentioned in invoice summary sheet. Since this is a system generated report no signatory required','','L',FALSE);
        $this->SetXY($x + '190', $y);
        $this->Ln();  
        
        $this->SetTextColor(0);
        $this->SetFont('Times','B','12');
        $this->Cell(190,15,'','',0,'C',$fill);
        $this->Ln();
        
        $this->SetTextColor(0);
        $this->SetFont('Times','B','12');
        $this->Cell(110,15,'Deduction Information','LRT',0,'C',$fill);
        $this->SetTextColor(0);
        $this->SetFont('Times','B','12');
        $this->Cell(80,15,'Invoice No : '.$invno.' B','LRT',0,'C',$fill);
        $this->Ln();
        
        $this->SetTextColor(0);
        $this->SetFont('Times','B','12');
        $this->Cell(190,8,'Issue Type Quality','LRT',0,'C',$fill);
        $this->Ln();
        
        $this->SetTextColor(0);
        $this->SetFont('Times','B','10');
        $this->Cell(30,8,'Catagory','LRT',0,'C',$fill);
        $this->SetTextColor(0);
        $this->SetFont('Times','B','10');
        $this->Cell(30,8,'Telephone Number','LRT',0,'C',$fill);
        $this->SetTextColor(0);
        $this->SetFont('Times','B','10');
        $this->Cell(100,8,'Remark','LRT',0,'C',$fill);
        $this->SetTextColor(0);
        $this->SetFont('Times','B','10');
        $this->Cell(30,8,'Deduction Amount','LRT',0,'C',$fill);
        $this->Ln();
        
        
        foreach($get_contra as $row)

        {
        $this->SetTextColor(0);
        $this->SetFont('Times','','8');
        $this->Cell(30,8,$row[1],'LRT',0,'L',$fill);

        // set colors to show a URL style link

        $this->SetTextColor(0);
        $this->SetFont('Times','','8');
        $this->Cell(30,8,$row[0],'LRT',0,'L',$fill);

        // restore normal color settings

        $this->SetTextColor(0);
        $this->SetFont('Times','','8');
        $this->Cell(100,8,$row[2],'LRT',0,'L',$fill);

        
        $this->SetTextColor(0);
        $this->SetFont('Times','','8');
        $this->Cell(30,8,number_format($row[3],2),'LRT',0,'R',$fill);
        $this->Ln();

        // flips from true to false and vise versa

        }
        
        $this->SetTextColor(0);
        $this->SetFont('Times','B','12');
        $this->Cell(190,8,'Issue Type Delay','LRT',0,'C',$fill);
        $this->Ln();
        
        $this->SetTextColor(0);
        $this->SetFont('Times','B','10');
        $this->Cell(30,6,'Catagory','LRTB',0,'C',$fill);
        $this->SetTextColor(0);
        $this->SetFont('Times','B','10');
        $this->Cell(30,6,'Telephone Number','LRTB',0,'C',$fill);
        $this->SetTextColor(0);
        $this->SetFont('Times','B','10');
        $this->Cell(40,6,'Total no of Delay Days','LRTB',0,'C',$fill);
        $this->SetTextColor(0);
        $this->SetFont('Times','B','10');
        $this->Cell(30,6,'Deduction Amount','LRTB',0,'C',$fill);
        $this->SetTextColor(0);
        $this->SetFont('Times','B','10');
        $this->Cell(30,6,'Received Date','LRTB',0,'C',$fill);
        $this->SetTextColor(0);
        $this->SetFont('Times','B','10');
        $this->Cell(30,6,'Completed Date','LRTB',0,'C',$fill);
        $this->Ln();
        
        
        foreach($inv_detail as $row)

        {
        $this->SetTextColor(0);
        $this->SetFont('Times','','8');
        $this->Cell(30,6,'Delay','LRB',0,'L',$fill);

        // set colors to show a URL style link

        $this->SetTextColor(0);
        $this->SetFont('Times','','8');
        $this->Cell(30,6,$row[0],'LRTB',0,'L',$fill);

        // restore normal color settings

        $this->SetTextColor(0);
        $this->SetFont('Times','','8');
        $this->Cell(40,6,$row[1],'LRTB',0,'L',$fill);

        
        $this->SetTextColor(0);
        $this->SetFont('Times','','8');
        $this->Cell(30,6,number_format(round($row[2],2),2),'LRTB',0,'R',$fill);
        
        $this->SetTextColor(0);
        $this->SetFont('Times','','8');
        $this->Cell(30,6,$row[3],'LRTB',0,'L',$fill);
        
        $this->SetTextColor(0);
        $this->SetFont('Times','','8');
        $this->Cell(30,6,$row[4],'LRTB',0,'L',$fill);
        $this->Ln();
        // flips from true to false and vise versa

        } 
    
        $this->SetFont('Times','B','8');
        $this->Cell(190,6,'','T',0,'L',$fill);
        $this->Ln();
        
       $fill=true; // used to alternate row color backgrounds     
        }

} 
    

$i = 0;
$grossum = 0;
while ($row =oci_fetch_array($getmet))
{

    $unit = oci_fetch_array(getunitref($area,$row[0]));
    
    $unitp0 = number_format($unit[6], 2);
    $unitp1 = number_format($unit[7], 2);
    
    
    $sump0 =  ($row[1] *round($unit[6],2));
    $totsump0 = number_format($sump0, 2) ;
    
    $sump1 =  ($row[2] *round($unit[7],2));
    $totsump1 = number_format($sump1, 2);
    
    $totAp0 = $totAp0 +$sump0;
    $totp0 = number_format($totAp0, 2) ;
    
    
    $totAp1 = $totAp1 +$sump1;
    $totp1 = number_format($totAp1, 2) ;
    
    $grossum = $grossum+$sump0 + $sump1;
    
    $datasum[] =array($unit[2],$unit[3],$unit[5],$unit[4],$row[1],$row[2],$unitp0,$unitp1,$totsump0,$totsump1,$last_cha);

}

       
    
// start and build the PDF document
//Main Summary
$pdfA = new PDF();
$pdfA->SetLeftMargin(10);
$pdfA->SetTopMargin(60);
$pdfA->SetFont('Arial','',12);
$pdfA->AddPage();

$pdfB = new PDF();
$pdfB->SetLeftMargin(10);
$pdfB->SetTopMargin(60);
$pdfB->SetFont('Arial','',12);
$pdfB->AddPage();




//Column titles
$header=array('Code','Designator','Unit','Description','Quantity','Unit Price','Amount (Rs.)');
$header1=array('','','','','P0','P1','P0','P1','P0','P1');




// call the table creation method  MAIN
$pdfA->BuildTableFront($inv_detail,$get_contra,'A',$getareadesc);
$pdfA->AddPage();
$pdfA->BuildTableTax($inv_detail,$totp0,$totp1,'A',$rowcount,$grossum,$get_contra,$delaypenelty,$qualitypenalty,$peneltyqun,$getareadesc);
$pdfA->AddPage();
$pdfA->BuildTable($header,$header1,$datasum,$inv_detail,$totp0,$totp1,$rowcount,'A',$grossum,$delaypenelty,$get_contra,$qualitypenalty,$peneltyqun,$getareadesc,$fromdate,$todate);

$filenameA="Invoice/{$area}/{$con_name}/{$invno}A.pdf";
$pdfA->Output($filenameA,'F');

//$pdfB->BuildTable($header,$header1,$datasum,$inv_detail,$totsump0,$totsump1,$rowcount,'B',$grossum,$delaypenelty,$get_contra);
//$filenameB="Invoice/{$area}/{$con_name}/{$invno}B.pdf";
//$pdfB->Output($filenameB,'F');

// call the table creation method  Tax    
$pdfB->BuildTableFront($inv_detail,$get_contra,'B',$getareadesc);
$pdfB->AddPage();
$pdfB->BuildTableTax($inv_detail,$totp0,$totp1,'B',$rowcount,$grossum,$get_contra,$delaypenelty,$qualitypenalty,$peneltyqun,$getareadesc);
$pdfB->AddPage();
$pdfB->BuildTable($header,$header1,$datasum,$inv_detail,$totp0,$totp1,$rowcount,'B',$grossum,$delaypenelty,$get_contra,$qualitypenalty,$peneltyqun,$getareadesc,$fromdate,$todate);
$pdfB->AddPage();
$pdfB->BuildTableCert($inv_detail,$get_contra,'B',$getareadesc,$grossum,$delaypenelty,$qualitypenalty,$peneltyqun);
$pdfB->AddPage();
$pdfB->BuildTableDedu($delaysum,$severtysum,$invoice_no,'B',$getareadesc);

$filenameTaxA="Invoice/{$area}/{$con_name}/{$invno}B.pdf";
$pdfB->Output($filenameTaxA,'F');


//$pdftaxB->BuildTableTax($inv_detail,$totp0,$totp1,'B',$rowcount,$grossum,$get_contra,$delaypenelty);
//$filenameTaxB="Invoice/{$area}/{$con_name}/{$invno}TAXB.pdf";
//$pdftaxB->Output($filenameTaxB,'F');    
  

    }
}


?>


