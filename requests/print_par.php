<?php

require('../engine/fpdf/fpdf.php');

include("../connect.php");

	$readpar_id = $_GET['id'];
	
	$getpar = mysql_fetch_array(mysql_query("SELECT * FROM eqp_par WHERE par_id = '$readpar_id'"));
	$geteqpdetails = mysql_fetch_array(mysql_query("SELECT * FROM equipments WHERE icspar = 'PAR' AND ics_par_id = '$readpar_id'"));
	$getitemdetails = mysql_fetch_array(mysql_query("SELECT * FROM request_items WHERE req_item_id = '$geteqpdetails[req_item_id]'"));
	$selitemunit = mysql_fetch_array(mysql_query("SELECT item_unit_name FROM item_unit WHERE item_unit_id = '$geteqpdetails[item_unit_id]'"));
	$selitem = mysql_fetch_array(mysql_query("SELECT item_name FROM items WHERE item_id = '$geteqpdetails[item_id]'"));
	
	$getpr = mysql_fetch_array(mysql_query("SELECT * FROM purchase_request WHERE pr_id='$getpar[pr_id]'"));
	$getpo = mysql_fetch_array(mysql_query("SELECT * FROM purchase_order WHERE po_id='$getpar[po_id]'"));
	$getfunding = mysql_fetch_array(mysql_query("SELECT * FROM `funding` WHERE `fund_id`='$getpar[fund_id]'"));
	$getsupplier = mysql_fetch_array(mysql_query("SELECT * FROM supplier WHERE supplier_id='$getpar[supplier_id]'"));
	
	$selctPs = "CONCAT(p.personnel_fname,' ',p.personnel_lname) AS full_name, pp.position_name";
	$fromPs= "personnel_work_info AS pwi LEFT JOIN personnel AS p ON p.personnel_id = pwi.personnel_id LEFT JOIN personnel_position AS pp ON pp.position_id = pwi.position_id";
	$receivedBy = mysql_fetch_array(mysql_query("SELECT ".$selctPs." FROM ".$fromPs." WHERE pwi.personnel_id = '$getpar[receivedBy]' "));
	$receivedFr = mysql_fetch_array(mysql_query("SELECT ".$selctPs." FROM ".$fromPs." WHERE pwi.personnel_id = '$getpar[receivedFrom]' "));

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
	
$pdf = new PDF('P','mm','Letter');
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->SetFont('Arial','B',14);

	$pdf->SetFont('Arial','B',12);
	$pdf->Cell(190,8,'PROPERTY ACKNOWLEDGEMENT RECEIPT','LTR', 1, 'C');
	$pdf->SetFont('Arial','',10);
	$pdf->Cell(190,8,'Bicol University College of Nursing','LR', 1, 'C');
	$pdf->Cell(190,8,'Legazpi City','LR', 0, 'C');
	$pdf->Ln();
	
	$pdf->Cell(126.6,8,'','L', 0, '');
	$pdf->Cell(15,8,'Date: ','', 0, 'R');
	$pdf->Cell(48.3,8,$getpar['pardate'],'R', 1, '');
	$pdf->Cell(126.6,8,'','L', 0, '');
	$pdf->Cell(15,8,'PAR No.: ','', 0, '');
	$pdf->Cell(48.3,8,$getpar['parnum'],'R', 1, '');
	
	//TableHeader
	$pdf->SetFont('Arial','B',10);
	$pdf->Cell(15,8,'Qty','TLB', 0, 'C');
	$pdf->Cell(15,8,'Unit','TLB', 0, 'C');
	$pdf->Cell(85,8,'Description','TLB', 0, 'C');
	$pdf->Cell(25,8,'Date Acquired','TLB', 0, 'C');
	$pdf->Cell(25,8,'Unit Price','TLB', 0, 'C');
	$pdf->Cell(25,8,'Total','TLBR', 0, 'C');
	$pdf->Ln();
	
	//Table
	
	$pdf->SetFont('Arial','',10);
	$pdf->Cell(15,8,$getpar['quantity'],'L', 0, 'C');
	$pdf->Cell(15,8,$selitemunit['item_unit_name'],'L', 0, 'C');
	$pdf->Cell(50,8,$selitem['item_name'],'L', 0, 'R');
	$pdf->Cell(35,8,$geteqpdetails['brand'],'', 0, 'L');
	$pdf->Cell(25,8,$geteqpdetails['date_acquired'],'L', 0, 'C');
	$pdf->Cell(25,8,number_format($geteqpdetails['unit_value'], 2,'.',','),'L', 0, 'C');
	$pdf->Cell(25,8,number_format($geteqpdetails['unit_value'] * ($getpar['quantity']), 2,'.',','),'LR', 0, 'C');
	$pdf->Ln();
	
	$pdf->SetFont('Arial','',10);
	$pdf->Cell(15,8,'','LB', 0, 'C');
	$pdf->Cell(15,8,'','LB', 0, 'C');
	$pdf->Cell(85,8,$geteqpdetails['description'],'LB', 0, 'C');
	$pdf->Cell(25,8,'','LB', 0, 'C');
	$pdf->Cell(25,8,'','LB', 0, 'C');
	$pdf->Cell(25,8,'','LRB', 0, 'C');
	$pdf->Ln();
	
	
	
	
	
	$pdf->SetFont('Arial','B',10);
	$pdf->Cell(15,8,'Serial No.','LB', 0, 'L');
	$pdf->SetFont('Arial','',10);
	$pdf->Cell(80,8,$geteqpdetails['serialnum'],'BR', 0, 'C');
	
	$pdf->SetFont('Arial','B',10);
	$pdf->Cell(15,8,'Property No.','B', 0, 'L');
	$pdf->SetFont('Arial','',10);
	$pdf->Cell(80,8,$geteqpdetails['prop_num'],'BR', 0, 'C');
	
	$pdf->Ln();
	
	$pdf->SetFont('Arial','B',10);
	$pdf->Cell(15,8,'PR No.','LB', 0, 'C');
	$pdf->SetFont('Arial','',10);
	$pdf->Cell(32.5,8,$getpr['prnum'],'B', 0, 'R');
	$pdf->Cell(15,8,'dated','B', 0, 'C');
	$pdf->Cell(32.5,8,$getpr['prdate'],'BR', 0, 'L');
	$pdf->SetFont('Arial','B',10);
	$pdf->Cell(15,8,'PO No.','B', 0, 'C');
	$pdf->SetFont('Arial','',10);
	$pdf->Cell(32.5,8,$getpo['ponumber'],'B', 0, 'R');
	$pdf->Cell(15,8,'dated','B', 0, 'C');
	$pdf->Cell(32.5,8,$getpo['podate'],'BR', 0, 'L');
	$pdf->Ln();
	$pdf->SetFont('Arial','B',10);
	$pdf->Cell(20,8,'OS No.:','LB', 0, 'L');
	$pdf->SetFont('Arial','',10);
	$pdf->Cell(75,8,$getfunding['os_num'],'RB', 0, 'L');
	$pdf->SetFont('Arial','B',10);
	$pdf->Cell(20,8,'Supplier:','B', 0, 'R');
	$pdf->SetFont('Arial','',10);
	$pdf->Cell(75,8,$getsupplier['supplier_name'],'RB', 0, 'L');
	$pdf->Ln();
	
	$pdf->Cell(95,8,'Received by:','L', 0, 'L');
	$pdf->Cell(95,8,'Recieved from:','LR', 1, 'L');
	$pdf->SetFont('Arial','B',10);
	$pdf->Cell(95,8,$receivedBy['full_name'],'L', 0, 'C');
	$pdf->Cell(95,8,$receivedFr['full_name'],'LR', 1, 'C');
	$pdf->SetFont('Arial','',10);
	$pdf->Cell(95,8,$receivedBy['position_name'],'LB', 0, 'C');
	$pdf->Cell(95,8,$receivedFr['position_name'],'BLR', 1, 'C');
	

$pdf->Output();
?>