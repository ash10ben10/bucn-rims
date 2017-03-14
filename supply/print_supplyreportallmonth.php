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
//$month = $_GET['month'];
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->SetFont('Arial','B',14);

	$getdates = mysql_query("SELECT * FROM stock_card WHERE YEAR(recdate) = '$year' AND issue_qty != '0' ORDER BY recdate DESC");
         

	$pdf->SetFont('Arial','',10);
	$pdf->Cell(335,8,'Bicol University College of Nursing','LTR', 1, 'C');
	$pdf->SetFont('Arial','B',12);
	$pdf->Cell(335,8,'ANNUAL REPORT OF ISSUED INVENTORY SUPPLIES','LR', 1, 'C');
		$pdf->SetFont('Arial','i',8);
	//$mont = DateTime::createFromFormat("m", $month);
	//$mon = $mont->format("F");
	$pdf->Cell(335,8,"for the Year ".$year,'LRB', 1, 'C');
	
	//Table Header
	$pdf->SetFont('Arial','B',10);
	$pdf->Cell(30,8,'','TL', 0, 'C');
	$pdf->Cell(130,8,'Stock','TLB', 0, 'C');
	$pdf->Cell(130,8,'Issuance','TLB', 0, 'C');
	$pdf->Cell(45,8,'','TLR', 1, 'C');
	$pdf->Cell(30,8,"Date",'LB', 0, 'C');
	$pdf->Cell(30,8,'Stock No.','LB', 0, 'C');
	$pdf->Cell(70,8,'Description','LB', 0, 'C');
	$pdf->Cell(30,8,'Amount','LB', 0, 'C');
	$pdf->Cell(60,8,'Issued to','LB', 0, 'C');
	$pdf->Cell(30,8,'Qty Issued','LB', 0, 'C');
	$pdf->Cell(40,8,'Department','LB', 0, 'C');
	$pdf->Cell(45,8,'Category','LBR', 1, 'C');
	
	
	while($retdata = mysql_fetch_array($getdates)){
           $stockitems = mysql_fetch_array(mysql_query("SELECT si.*, su.* FROM stock_units AS su LEFT JOIN stock_items AS si ON si.stock_id = su.stock_id WHERE su.su_id = '$retdata[su_id]'"));
           $items = mysql_fetch_array(mysql_query("SELECT * FROM `items` WHERE `item_id` = '$stockitems[item_id]'"));
           $itemunit = mysql_fetch_array(mysql_query("SELECT * FROM `item_unit` WHERE `item_unit_id` = '$stockitems[item_unit_id]'"));
           $cat = mysql_fetch_array(mysql_query("SELECT i.item_id, cat.category_name FROM items AS i LEFT JOIN category AS cat ON cat.category_id = i.category_id WHERE i.item_id = '$stockitems[item_id]'"));
           $getpersonnel = mysql_fetch_array(mysql_query("SELECT pwi.pwi_id, CONCAT(p.personnel_fname,' ',p.personnel_lname) AS full_name, d.dept_name, ps.position_name FROM `personnel_work_info` AS pwi LEFT JOIN personnel AS p ON p.personnel_id = pwi.personnel_id LEFT JOIN department AS d ON d.dept_id = pwi.dept_id LEFT JOIN personnel_position AS ps ON ps.position_id = pwi.position_id WHERE pwi.personnel_id = '$retdata[personnel_id]'"))or die(mysql_error());
      	
		$pdf->Cell(30,8,date("F d", strtotime($retdata['recdate'])),'LB', 0, 'C');
		$pdf->Cell(30,8,$stockitems['stock_no'],'LB', 0, 'C');
		$pdf->Cell(70,8,$items['item_name'].", ".$stockitems['description'],'LB', 0, 'C');
		$pdf->Cell(30,8,number_format($stockitems['unit_cost'], 2,'.',','),'LB', 0, 'C');
		$pdf->Cell(60,8,$getpersonnel['full_name'],'LB', 0, 'C');
		$pdf->Cell(30,8,$retdata['issue_qty']." ".$itemunit['item_unit_name'],'LB', 0, 'C');
		$pdf->Cell(40,8,$getpersonnel['dept_name'],'LB', 0, 'C');
		$pdf->Cell(45,8,$cat['category_name'],'LBR', 1, 'C');
	}
	
$pdf->Output();
?>