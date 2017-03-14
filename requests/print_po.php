<?php
require('../engine/fpdf/fpdf.php');

include("../connect.php");
include ("../engine/converter.php");

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
$pdf = new PDF('P','mm','A4');
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->SetFont('Arial','B',14);



		$readpo_id = $_GET['id'];
		$getpo = mysql_fetch_array(mysql_query("SELECT * FROM purchase_order WHERE po_id ='$readpo_id'"));
		$getsupplier = mysql_fetch_array(mysql_query("SELECT * FROM supplier WHERE supplier_id ='$getpo[supplier_id]'"));
		$getpoitems = mysql_query("SELECT * FROM request_items WHERE po_id = '$readpo_id'");
		
		$getpos = mysql_fetch_array(mysql_query("SELECT `position_id` FROM personnel_position WHERE position_name = 'Dean' OR position_name = 'OIC Dean' "));
		$getdean = mysql_fetch_array(mysql_query("SELECT `pwi_id`, `personnel_id` FROM personnel_work_info WHERE position_id = '$getpos[position_id]' LIMIT 1 "));
		$getdeanname = mysql_fetch_array(mysql_query("SELECT CONCAT(personnel_fname,' ',personnel_lname) AS full_name FROM personnel WHERE personnel_id = '$getdean[personnel_id]' "));
		
		$getposbo = mysql_fetch_array(mysql_query("SELECT `position_id` FROM personnel_position WHERE position_name = 'Budget Officer' "));
		$getbo = mysql_fetch_array(mysql_query("SELECT `pwi_id`, `personnel_id` FROM personnel_work_info WHERE position_id = '$getposbo[position_id]'"));
		$getbudgetofficer = mysql_fetch_array(mysql_query("SELECT CONCAT(personnel_fname,' ',personnel_lname) AS full_name FROM personnel WHERE personnel_id = '$getbo[personnel_id]' "));
		$funding = mysql_fetch_array(mysql_query("SELECT * FROM funding WHERE po_id = '$readpo_id' "));
		//header
		$pdf->SetFont('Arial','B',12);
		$pdf->Cell(190,8,'PURCHASE ORDER','LTR', 1, 'C');
		$pdf->SetFont('Arial','',12);
		$pdf->Cell(190,6,'Bicol University College of Nursing','LR', 1, 'C');
		$pdf->SetFont('Arial','I',10);
		$pdf->Cell(190,6,'(Agency)','LR', 1, 'C');
		
		$pdf->SetFont('Arial','B',11);
		$pdf->Cell(18,8,'Supplier: ','LT', 0, 'L');
		$pdf->SetFont('Arial','',11);
		$pdf->Cell(92,8,$getsupplier['supplier_name'],'TR', 0, 'L');
		$pdf->SetFont('Arial','B',11);
		$pdf->Cell(15,8,'PO No.: ','T', 0, 'L');
		$pdf->SetFont('Arial','',11);
		$pdf->Cell(65,8,$getpo['ponumber'],'TR', 1, 'L');
		
		$pdf->SetFont('Arial','B',11);
		$pdf->Cell(18,8,'Address: ','L', 0, 'L');
		$pdf->SetFont('Arial','',11);
		$pdf->Cell(92,8,$getsupplier['supplier_address'],'R', 0, 'L');
		$pdf->SetFont('Arial','B',11);
		$pdf->Cell(15,8,'Date: ','', 0, 'L');
		$pdf->SetFont('Arial','',11);
		$pdf->Cell(65,8,$getpo['podate'],'R', 0, 'L');
		$pdf->Ln();
		$pdf->SetFont('Arial','B',11);
		$pdf->Cell(18,8,'TIN: ','L', 0, 'L');
		$pdf->SetFont('Arial','',11);
		$pdf->Cell(92,8,$getsupplier['supplier_tin_no'],'R', 0, 'L');
		$pdf->SetFont('Arial','B',11);
		$pdf->Cell(35,8,'Mode of Payment: ','', 0, 'L');
		$pdf->SetFont('Arial','',11);
		$pdf->Cell(45,8,$getpo['modepayment'],'R', 0, 'L');
		$pdf->Ln();
		$pdf->SetFont('Arial','',10);
		$pdf->Cell(190,15,'Gentlemen: Please furnish this office the following articles subject to the terms and conditions contained herein.','LTR', 0, 'L');
		$pdf->Ln();
		$pdf->SetFont('Arial','B',11);
		$pdf->Cell(33,8,'Place of Delivery: ','LT', 0, 'L');
		$pdf->SetFont('Arial','',11);
		$pdf->Cell(62,8,$getpo['delivery_place'],'TR', 0, 'L');
		$pdf->SetFont('Arial','B',11);
		$pdf->Cell(31,8,'Delivery Term: ','T', 0, 'L');
		$pdf->SetFont('Arial','',11);
		$pdf->Cell(6,8,$getpo['orig_deliveryterm'],'T', 0, 'L');
		$pdf->Cell(58,8,' cd after received PO','TR', 0, 'L');
		$pdf->Ln();
		$pdf->SetFont('Arial','B',11);
		$pdf->Cell(31,8,'Date of Delivery: ','L', 0, 'L');
		$pdf->SetFont('Arial','',11);
		$pdf->Cell(64,8,$getpo['orig_deliverydate'],'R', 0, 'L');
		$pdf->SetFont('Arial','B',11);
		$pdf->Cell(31,8,'Payment of Term: ','', 0, 'L');
		$pdf->SetFont('Arial','',11);
		$pdf->Cell(64,8,$getpo['payment_term'],'R', 0, 'L');
		$pdf->Ln();
		
		//Table Header
		$pdf->SetFont('Arial','B',11);
		$pdf->Cell(20,15,'Unit','LTRB', 0, 'C');
		$pdf->Cell(95,15,'Description','TRB', 0, 'C');
		$pdf->Cell(20,15,'Qty','TLB', 0, 'C');
		$pdf->Cell(27.5,15,'Unit Cost','TLB', 0, 'C');
		$pdf->Cell(27.5,15,'Amount','LTRB', 0, 'C');
		$pdf->Ln();
		
		//Table Body
		while($getdata = mysql_fetch_array($getpoitems)){
			$getunit = mysql_fetch_array(mysql_query("SELECT * FROM item_unit WHERE item_unit_id = $getdata[item_unit_id]"));
			$showitems = mysql_fetch_array(mysql_query("SELECT * FROM items WHERE item_id = $getdata[item_id]"));
			
			$pdf->SetFont('Arial','', 10);
			//$this->Cell(20,10,$showitems['item_stock'],'LBR', 0, 'C');
			$pdf->Cell(20,10,$getunit['item_unit_name'],'LBR', 0, 'C');
			$pdf->Cell(95,10,$showitems['item_name'].", ".$getdata['description'],'BR', 0, 'C');
			$pdf->Cell(20,10,$getdata['quantity'],'RB', 0, 'C');
			$pdf->Cell(27.5,10,number_format($getdata['est_unit_cost'], 2,'.',','),'RB', 0, 'C');
			$pdf->Cell(27.5,10,number_format($getdata['est_total_cost'], 2,'.',','),'RB', 0, 'C');
			$pdf->Ln();
			}
		$pdf->Cell(40,15,'(Amount in words)','LB', 0, 'C');
		$pdf->SetFont('Arial','B', 10);
		$gTotalToWord = number_format($getpo['orig_allitemnums'], 2,'.',',');
		$gTotalWord = explode(".", $getpo['orig_allitemnums']);
																	
		$gTotalPeso = $gTotalWord[0];
		$gTotalCents = (COUNT($gTotalWord) > 1) ? number_format($gTotalWord[1]) : 0;
		$gTotalWord = convert_number_to_words($gTotalPeso)." Peso". (($gTotalPeso > 1) ? "s" : "");
		$gTotalWord .= ($gTotalCents > 0 ) ? " & ".convert_number_to_words($gTotalCents)." Cent" . ($gTotalCents > 1 ? "s" : ""): "";
		$gTotalWord .= " Only"; 
		$pdf->Cell(122.5,15,$gTotalWord,'LB', 0, 'L');
		$pdf->Cell(27.5,15,$gTotalToWord,'LRB', 0, 'C');
		$pdf->Ln();
		
		//Footer
		$pdf->SetFont('Arial','', 10);
		$pdf->MultiCell(190,8,'        In case of failure to make the full delivery within the time specified above, a penalty of one-tenth (1/10) of one percent for every day of delay shall be imposed.','LR');
		$pdf->MultiCell(190,8,'','LR');
		$pdf->Cell(116,10,'','L');
		$pdf->Cell(74,10,'Very truly Yours,','R');
		$pdf->Ln();
		$pdf->Cell(135,8,'','L',0,'C');
		$pdf->SetFont('Arial','B',12);
		$pdf->Cell(55,8,$getdeanname['full_name'],'R',0,'C');
		$pdf->Ln();
		$pdf->Cell(135,6,'','L',0,'C');
		$pdf->SetFont('Arial','',10);
		$pdf->Cell(55,6,'Authorized Official','R',0,'C');
		$pdf->Ln();
		$pdf->SetFont('Arial','',11);
		$pdf->Cell(190,8,'Conforme:','LR',0,'L');
		$pdf->Ln();
		$pdf->SetFont('Arial','B',12);
		$pdf->Cell(85,6,$getsupplier['supplier_name'],'L',0,'C');
		$pdf->Cell(105,6,'','R',0,'L');
		$pdf->Ln();
		$pdf->SetFont('Arial','U',10);
		$pdf->Cell(85,6,'(Signature Over Printed Name)','L',0,'C');
		$pdf->Cell(105,6,'','R',0,'L');
		$pdf->Ln();
		$pdf->Cell(190,10,'','LBR', 0, 'C');
		$pdf->Ln();
		
		$pdf->SetFont('Arial','',11);
		$pdf->Cell(120,7,'Funds Available:','LR', 0, 'L');
		$pdf->Cell(15,7,'OS No.','', 0, 'L');
		$pdf->Cell(55,7,$funding['os_num'],'R', 0, '');
		$pdf->Ln();
		$pdf->SetFont('Arial','',12);
		$pdf->Cell(120,7,$getbudgetofficer['full_name'],'LR', 0, 'C');
		$pdf->Cell(18,7,'Amount: ','', 0, 'L');
		if($funding['amount'] == 0 | $funding['amount'] == null | $funding['amount'] == ""){
			$pdf->Cell(52,7,'','R', 0, '');
		}else{
			$pdf->Cell(52,7,number_format($funding['amount'], 2,'.',','),'R', 0, '');
		}
		$pdf->Ln();
		$pdf->SetFont('Arial','U',10);
		$pdf->Cell(120,7,'Budget Officer','BLR', 0, 'C');
		$pdf->Cell(70,7,'','RB', 0, 'L');
		
	

$pdf->Output();

?>