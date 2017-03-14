<?php

require('../engine/fpdf/fpdf.php');

include("../connect.php");

	
	$readto_id = $_GET['id'];
	
	$getto = mysql_fetch_array(mysql_query("SELECT * FROM eqp_turnover WHERE to_id = '$readto_id'"))or die (mysql_error());
	
	$selctPs = "CONCAT(p.personnel_fname,' ',p.personnel_lname) AS full_name, pp.position_name";
	$fromPs= "personnel_work_info AS pwi LEFT JOIN personnel AS p ON p.personnel_id = pwi.personnel_id LEFT JOIN personnel_position AS pp ON pp.position_id = pwi.position_id";
	$toTo = mysql_fetch_array(mysql_query("SELECT ".$selctPs." FROM ".$fromPs." WHERE pwi.personnel_id = '$getto[toTo]' ")) or die(mysql_error());
	$toFr = mysql_fetch_array(mysql_query("SELECT ".$selctPs." FROM ".$fromPs." WHERE pwi.personnel_id = '$getto[toFrom]' ")) or die(mysql_error());
	
																	
	class PDF extends FPDF { 
    
	//Footer
	function Footer(){
	
    // Position at 1.5 cm from bottom
    $this->SetY(-15);
    // Arial italic 8
    $this->SetFont('Arial','I',8);
    // Page number
    $this->Cell(0,10,'Page '.$this->PageNo().'/{nb}',0,0,'C');
	}
	
	}
	
$pdf = new PDF('L','mm','Legal');
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->SetFont('Arial','B',14);
	//header
	$pdf->SetFont('Arial','B',12);
	$pdf->Cell(335,8,'TURN OVER FORM','LTR', 1, 'C');
	$pdf->SetFont('Arial','',10);
	$pdf->Cell(335,8,'Bicol University College of Nursing','LR', 1, 'C');
	$pdf->Cell(335,8,'Legazpi City','LR', 0, 'C');
	$pdf->Ln();
	
	$pdf->Cell(271.7,8,'','L', 0, '');
	$pdf->Cell(15,8,'Date: ','', 0, 'R');
	$pdf->Cell(48.3,8,date("M j, Y", strtotime($getto['date_acquired'])),'R', 1, '');
	$pdf->Cell(271.7,8,'','L', 0, '');
	$pdf->Cell(15,8,'Turn Over No.:','', 0, 'R');
	$pdf->Cell(48.3,8, $getto['tonum'],'R', 1, '');
	
	//TableHeader
	$pdf->SetFont('Arial','B',10);
	$pdf->Cell(20,8,'Item No.','TLB', 0, 'C');
	$pdf->Cell(20,8,'Unit','TLB', 0, 'C');
	$pdf->Cell(60,8,'Name','TLB', 0, 'C');
	$pdf->Cell(75,8,'Description','TLB', 0, 'C');
	$pdf->Cell(25,8,'Amount','TLB', 0, 'C');
	$pdf->Cell(35,8,'Serial No.','TLB', 0, 'C');
	$pdf->Cell(65,8,'Property No.','TLB', 0, 'C');
	$pdf->Cell(35,8,'Status','TLBR', 0, 'C');
	$pdf->Ln();
	//Table
	$pdf->SetFont('Arial','',10);
	$geteqpdetails = mysql_query("SELECT * FROM equipments WHERE ics_par_id = '$readto_id'")or die (mysql_error());
	$count = 1;
	while($getdata = mysql_fetch_array($geteqpdetails)){
		$selitemunit = mysql_fetch_array(mysql_query("SELECT item_unit_name FROM item_unit WHERE item_unit_id = '$getdata[item_unit_id]'"))or die(mysql_error());
		$selitem = mysql_fetch_array(mysql_query("SELECT item_name FROM items WHERE item_id = '$getdata[item_id]'"))or die(mysql_error());
	
			$pdf->Cell(20,8,$count,'TLB', 0, 'C');
			$pdf->Cell(20,8,$selitemunit['item_unit_name'],'TLB', 0, 'C');
			$pdf->Cell(30,8,$selitem['item_name'],'TLB', 0, 'C');
			$pdf->Cell(30,8,$getdata['brand'],'TB', 0, 'C');
			$pdf->Cell(75,8,$getdata['description'],'TLB', 0, 'C');
			$pdf->Cell(25,8,number_format($getdata['unit_value'], 2,'.',','),'TLB', 0, 'C');
			if($getdata['serialnum'] == ""){
				$pdf->Cell(35,8,'Not Available','TLB', 0, 'C');
			}else{
				$pdf->Cell(35,8,$getdata['serialnum'],'TLB', 0, 'C');
			}
			$pdf->Cell(65,8,$getdata['prop_num'],'TLB', 0, 'C');
			$pdf->Cell(35,8,$getdata['remarks'],'TLBR', 0, 'C');
			$pdf->Ln();
			$count++;
	}
	
	
	
	//footer
	$pdf->Cell(167.5,8,'Turned Over to: ','L', 0, 'L');
	$pdf->Cell(167.5,8,'Turned Over from: ','LR', 1, 'L');
	$pdf->SetFont('Arial','B',10);
	$pdf->Cell(167.5,8,$toTo['full_name'],'L', 0, 'C');
	$pdf->Cell(167.5,8,$toFr['full_name'],'LR', 1, 'C');
	$pdf->SetFont('Arial','',10);
	$pdf->Cell(167.5,8,$toTo['position_name'],'LB', 0, 'C');
	$pdf->Cell(167.5,8,$toFr['position_name'],'BLR', 1, 'C');
	

$pdf->Output();
?>