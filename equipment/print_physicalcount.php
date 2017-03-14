<?php

require('../engine/fpdf/fpdf.php');

include("../connect.php");

	date_default_timezone_set("Asia/Manila");
	$datetime = date("Y-m-d H:i:s");
	$date = date("M d, Y");
	$month = date("Y-m");
	
	$selectpersonnel_id = mysql_fetch_array(mysql_query("SELECT `personnel_id` FROM `personnel_work_info` WHERE `position_id` = 21"));
	$selctP = "CONCAT(personnel_fname,' ',personnel_lname) AS full_name";
	$personnel = mysql_fetch_array(mysql_query("SELECT ".$selctP." FROM personnel WHERE personnel_id = $selectpersonnel_id[personnel_id]"));
	
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
$pdf->SetFont('Arial','',12);

	$pdf->SetFont('Arial','',10);
	$pdf->Cell(335,8,'Bicol University','LTR', 1, 'C');
	$pdf->SetFont('Arial','B',10);
	$pdf->Cell(335,8,'COLLEGE OF NURSING','LR', 1, 'C');
	$pdf->Cell(335,8,'REPORT ON THE PHYSICAL COUNT OF PROPERTY, PLANT AND EQUIPMENT','LR', 1, 'C');
	$pdf->Cell(335,8,'Legazpi City','LR', 1, 'C');
	$pdf->Cell(335,8,"As of ".$date,'LR', 1, 'C');
	
	$pdf->SetFont('Arial','',10);
	$pdf->Cell(335,8,"for which ".$personnel['full_name'].", Supply Officer of BUCN is accountable, having assumed such accountability on ".$date,'LR', 1, 'C');
	
	$pdf->Cell(335,4,'','LR', 1, 'C');
	$pdf->SetFont('Arial','B',9);
	$pdf->Cell(50,8,'ARTICLE','LRTB', 0, 'C');
	$pdf->Cell(90,8,'DESCRIPTION','RTB', 0, 'C');
	$pdf->Cell(30,8,'DATE ACQUIRED','RTB', 0, 'C');
	$pdf->Cell(70,8,'PROP. NO.','RTB', 0, 'C');
	$pdf->Cell(20,8,'UNIT','RTB', 0, 'C');
	$pdf->Cell(35,8,'UNIT VALUE','RTB', 0, 'C');
	$pdf->Cell(40,8,'ISSUED TO','RTB', 0, 'C');
	$pdf->Ln();
	
	$getequipment = mysql_query("SELECT * FROM equipments");
				
	while ($getdata = mysql_fetch_array($getequipment)){
		
		$getitem = mysql_fetch_array(mysql_query("SELECT * FROM items WHERE item_id = $getdata[item_id]"));
		$getqty = mysql_fetch_array(mysql_query("SELECT * FROM stock_items WHERE item_id = $getdata[item_id]"));
		$getunit = mysql_fetch_array(mysql_query("SELECT * FROM item_unit WHERE item_unit_id = $getitem[item_unit_id]"));
		$selctPs = "CONCAT(personnel_fname,' ',personnel_lname) AS full_name";
		$getpersonnel = mysql_fetch_array(mysql_query("SELECT ".$selctPs." FROM personnel WHERE personnel_id = $getdata[received_by]"));
		$getdeptname = mysql_fetch_array(mysql_query("SELECT pwi.dept_id, d.dept_name, pwi.personnel_id FROM personnel_work_info AS pwi LEFT JOIN department AS d ON d.dept_id = pwi.dept_id WHERE pwi.personnel_id = $getdata[received_by]"));
		
		$pdf->SetFont('Arial','',9);
		$pdf->Cell(50,8,$getitem['item_name'],'LR', 0, 'C');
		$pdf->Cell(90,8,$getdata['brand'].", ".$getdata['description'],'R', 0, 'C');
		$pdf->Cell(30,8,date("M d, Y", strtotime($getdata['date_acquired'])),'R', 0, 'C');
		$pdf->Cell(70,8,$getdata['prop_num'],'R', 0, 'C');
		$pdf->Cell(20,8,$getunit['item_unit_name'],'R', 0, 'C');
		$pdf->Cell(35,8,number_format($getdata['unit_value'], 2,'.',','),'R', 0, 'C');
		$pdf->Cell(40,8,$getpersonnel['full_name'],'R', 1, 'C');
		
		$pdf->Cell(50,8,'','LRB', 0, 'C');
		$pdf->Cell(90,8,'','RB', 0, 'C');
		$pdf->Cell(30,8,'','RB', 0, 'C');
		$pdf->Cell(70,8,'','RB', 0, 'C');
		$pdf->Cell(20,8,'','RB', 0, 'C');
		$pdf->Cell(35,8,'','RB', 0, 'C');
		$pdf->Cell(40,8,"at ".$getdeptname['dept_name'],'RB', 1, 'C');
		
	
	}											
$pdf->Output();
?>