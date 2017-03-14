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

$pdf = new PDF('P','mm','A4');
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->SetFont('Arial','',10);

		$statsupply = mysql_query("SELECT su.su_id, si.stock_id, su.stock_no, si.item_id, si.stock_type, si.description, si.order_point, su.item_unit_id, su.price, su.quantity FROM `stock_items` AS si LEFT JOIN stock_units AS su ON su.stock_id = si.stock_id WHERE si.stock_type = 'Supply'");
	
	$pdf->SetFont('Arial','B',12);
	$pdf->Cell(190,10,'REPORT OF ALL INVENTORY SUPPLIES','LTR', 1, 'C');
	$pdf->SetFont('Arial','',10);
	$pdf->Cell(190,8,'Bicol University College of Nursing','LR', 1, 'C');
	$pdf->Cell(190,10,'','LR', 1, 'C');
	//Table header
	$pdf->SetFont('Arial','B',10);
	$pdf->Cell(25,10,'Stock No.','BTLR', 0, 'C');
	$pdf->Cell(85,10,'Description','BTR', 0, 'C');
	$pdf->Cell(20,10,'Quantity','BRT', 0, 'C');
	$pdf->Cell(60,10,'Category','TBRL', 1, 'C');
	$pdf->SetFont('Arial','',10);
	
	while($retdata = mysql_fetch_array($statsupply)){
					$stockitems = mysql_fetch_array(mysql_query("SELECT si.*, su.* FROM stock_units AS su LEFT JOIN stock_items AS si ON si.stock_id = su.stock_id WHERE su.su_id = '$retdata[su_id]'"));
					$items = mysql_fetch_array(mysql_query("SELECT * FROM `items` WHERE `item_id` = '$stockitems[item_id]'"));
					$itemunit = mysql_fetch_array(mysql_query("SELECT * FROM `item_unit` WHERE `item_unit_id` = '$stockitems[item_unit_id]'"));
					$cat = mysql_fetch_array(mysql_query("SELECT i.item_id, cat.category_name FROM items AS i LEFT JOIN category AS cat ON cat.category_id = i.category_id WHERE i.item_id = '$stockitems[item_id]'"));
	
	$pdf->Cell(25,10,$stockitems['stock_no'],'BLR', 0, 'C');
	$pdf->Cell(85,10,$items['item_name'].", ".$stockitems['description'],'BR', 0, 'C');
	$pdf->Cell(20,10,$stockitems['quantity'],'BR', 0, 'C');
	$pdf->Cell(60,10,$cat['category_name'],'BRL', 1, 'C');
	
	}			
	
			
			
$pdf->Output();
?>