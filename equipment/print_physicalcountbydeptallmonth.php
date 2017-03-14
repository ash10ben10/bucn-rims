<?php

require('../engine/fpdf/fpdf.php');

include("../connect.php");

	class PDF extends FPDF{

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
$year = $_GET['year'];
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->SetFont('Arial','B',14);

/* $mont = DateTime::createFromFormat("m", $month);
$mon = $mont->format("F"); */
//$deptname = mysql_fetch_array(mysql_query("SELECT `dept_name` FROM `department`"));
$selectpersonnel_id = mysql_fetch_array(mysql_query("SELECT ps.position_name, pwi.personnel_id FROM `personnel_work_info` AS pwi LEFT JOIN personnel_position AS ps ON ps.position_id = pwi.position_id WHERE ps.position_name = 'Supply Officer'"));
$selctP = "CONCAT(personnel_fname,' ',personnel_lname) AS full_name";
$personnel = mysql_fetch_array(mysql_query("SELECT ".$selctP." FROM personnel WHERE personnel_id = $selectpersonnel_id[personnel_id]"));
//$getdates = mysql_query("SELECT * FROM stock_card WHERE YEAR(recdate) = '$year' AND MONTH(recdate) = '$month' AND issue_qty != '0' ORDER BY recdate DESC");
$geteqps = mysql_query("SELECT * FROM `equipments` WHERE YEAR(eqpdate) = '$year' AND remarks = 'Working' ORDER BY date_acquired DESC");
           	
	$pdf->SetFont('Arial','',10);
	$pdf->Cell(335,8,'Bicol University','LTR', 1, 'C');
	$pdf->SetFont('Arial','B',10);
	$pdf->Cell(335,8,'COLLEGE OF NURSING','LR', 1, 'C');
	$pdf->Cell(335,8,'REPORT ON THE PHYSICAL COUNT OF PROPERTY, PLANT AND EQUIPMENT','LR', 1, 'C');
	$pdf->Cell(335,8,'Legazpi City','LR', 1, 'C');
	$pdf->Cell(335,8,"As of ".$year." in All Offices",'LR', 1, 'C');
	$pdf->SetFont('Arial','',10);
	$pdf->Cell(335,8,"for which ".$personnel['full_name'].", Supply Officer of BUCN is accountable, having assumed such accountability on year ".$year.".",'LR', 1, 'C');
	
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
	
	  while($eqpdata = mysql_fetch_array($geteqps)){
          $items = mysql_fetch_array(mysql_query("SELECT * FROM `items` WHERE `item_id` = '$eqpdata[item_id]'"));
          $itemunit = mysql_fetch_array(mysql_query("SELECT * FROM `item_unit` WHERE `item_unit_id` = '$eqpdata[item_unit_id]'"));
          $getpersonnel = mysql_fetch_array(mysql_query("SELECT pwi.pwi_id, CONCAT(p.personnel_fname,' ',p.personnel_lname) AS full_name, d.dept_name, ps.position_name FROM `personnel_work_info` AS pwi LEFT JOIN personnel AS p ON p.personnel_id = pwi.personnel_id LEFT JOIN department AS d ON d.dept_id = pwi.dept_id LEFT JOIN personnel_position AS ps ON ps.position_id = pwi.position_id WHERE pwi.personnel_id = '$eqpdata[received_by]'"));
          
	
		$pdf->SetFont('Arial','',9);
		$pdf->Cell(50,8,$items['item_name'],'BLR', 0, 'C');
		$pdf->Cell(90,8,$eqpdata['brand'].", ".$eqpdata['description'],'BR', 0, 'C');
		$pdf->Cell(30,8,date("M d, Y", strtotime($eqpdata['date_acquired'])),'BR', 0, 'C');
		$pdf->Cell(70,8,$eqpdata['prop_num'],'RB', 0, 'C');
		$pdf->Cell(20,8,$itemunit['item_unit_name'],'BR', 0, 'C');
		$pdf->Cell(35,8,number_format($eqpdata['unit_value'], 2,'.',','),'BR', 0, 'C');
		$pdf->Cell(40,8,$getpersonnel['full_name'],'BR', 1, 'C');
		
		
		
	
	}
	
	
$pdf->Output();
?>