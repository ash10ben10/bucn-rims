<?php


require('../engine/fpdf/fpdf.php');

include("../connect.php");
	global $year;
		$readiar_id = $_GET['id'];
		$getiar = mysql_fetch_array(mysql_query("SELECT * FROM inspect_accept_report WHERE iar_id ='$readiar_id'"));
		$getins = mysql_fetch_array(mysql_query("SELECT * FROM inspection WHERE inspection_id ='$getiar[inspection_id]'"));
		
		$getpo = mysql_fetch_array(mysql_query("SELECT * FROM purchase_order WHERE po_id = '$getiar[po_id]'"));
		$getsupplier = mysql_fetch_array(mysql_query("SELECT * FROM supplier WHERE supplier_id ='$getpo[supplier_id]'"));
		$getpr = mysql_fetch_array(mysql_query("SELECT pr.pr_id, d.dept_name FROM purchase_request AS pr LEFT JOIN department AS d ON d.dept_id = pr.dept_id WHERE pr_id = '$getpo[pr_id]'"));
		
		$selctPs = "CONCAT(p.personnel_fname,' ',p.personnel_lname) AS full_name, pp.position_name";
		$fromPs= "personnel_work_info AS pwi LEFT JOIN personnel AS p ON p.personnel_id = pwi.personnel_id LEFT JOIN personnel_position AS pp ON pp.position_id = pwi.position_id";
		$getacceptor = mysql_fetch_array(mysql_query("SELECT ".$selctPs." FROM ".$fromPs." WHERE pwi.personnel_id = $getiar[personnel_id] "));
		$getinspector = mysql_fetch_array(mysql_query("SELECT ".$selctPs." FROM ".$fromPs." WHERE pwi.personnel_id = $getins[personnel_id] "));
		
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
		
		//header
		$pdf->SetFont('Arial','B',12);
		$pdf->Cell(190,8,'INSPECTION AND ACCEPTANCE REPORT','LTR', 1, 'C');
		$pdf->SetFont('Arial','',12);
		$pdf->Cell(190,10,'BU College of Nursing','LR', 1, 'C');
		$pdf->SetFont('Arial','I',10);
		$pdf->Cell(190,10,'(Agency)','LRB', 1, 'C');
		$pdf->SetFont('Arial','',12);
		$pdf->Cell(20,10,'Supplier:','L', 0, 'L');
		$pdf->Cell(100,10,$getsupplier['supplier_name'],'', 0, 'L');
		$pdf->Cell(20,10,'IAR No.','L', 0, 'L');
		$pdf->Cell(50,10,$getiar['iarnumber'],'R', 0, 'L');
		$pdf->Ln();
		$pdf->Cell(20,10,'PO No:','L', 0, 'L');
		$pdf->Cell(100,10,$getpo['ponumber'],'R', 0, 'L');
		$pdf->Cell(25,10,'Invoice No. ','L', 0, 'L');
		$pdf->Cell(45,10,$getiar['invoice_num'],'R', 0, 'L');
		$pdf->Ln();
		$pdf->Cell(40,10,'Requesting Office:','LB', 0, 'L');
		$pdf->Cell(80,10,$getpr['dept_name'],'RB', 0, 'L');
		$pdf->Cell(25,10,'','LB', 0, 'L');
		$pdf->Cell(45,10, date("M j, Y", strtotime($getiar['invoice_date'])),'RB', 0, 'L');
		$pdf->Ln();
		
		
		//TableHeader
		$pdf->SetFont('Arial','',12);
		$pdf->Cell(40,10,'Unit','LB', 0, 'C');
		$pdf->Cell(120,10,'ITEM DESCRIPTION','LB', 0, 'C');
		$pdf->Cell(30,10,'Qty','LBR', 0, 'C');
		$pdf->Ln();
		
		//TableContent
		$getitems = mysql_query("SELECT * FROM request_items WHERE iar_id = '$readiar_id' ");
		while($getdata = mysql_fetch_array($getitems)){
			$getunit = mysql_fetch_array(mysql_query("SELECT * FROM item_unit WHERE item_unit_id = $getdata[item_unit_id]"));
			$showitems = mysql_fetch_array(mysql_query("SELECT * FROM items WHERE item_id = $getdata[item_id]"));
			
			$pdf->Cell(40,10,$getunit['item_unit_name'],'LR', 0, 'C');
			$pdf->Cell(120,10,$showitems['item_name'].", ".$getdata['description'],'R', 0, 'C');
			$pdf->Cell(30,10,$getdata['del_quantity'],'R', 0, 'C');
			$pdf->Ln();
		}
		
		//Footer
		$pdf->SetFont('Arial','',10);
		$pdf->Cell(110,10,'INSPECTION','LRTB', 0, 'C');
		$pdf->Cell(80,10,'ACCEPTANCE','LRTB', 0, 'C');
		$pdf->Ln();
		$pdf->SetFont('Arial','',10);
		$pdf->Cell(30,10,'Date Inspected:','L', 0, 'L');
		$pdf->Cell(80,10,date("M j, Y", strtotime($getins['inspection_date'])),'R', 0, 'L');
		$pdf->Cell(30,10,'Date Accepted:','', 0, 'L');
		$pdf->Cell(50,10,date("M j, Y", strtotime($getiar['iardate'])),'R', 0, 'L');
		$pdf->Ln();
		$pdf->Cell(10,10,'','L', 0, 'C');
		$pdf->Cell(10,10,'X','LRTB', 0, 'C');
		$pdf->SetFont('Arial','I',8);
		$pdf->Cell(90,10,'Inspected, verified and found on order as to quantity and specifications.','', 0, 'C');
		$pdf->SetFont('Arial','',10);
		$pdf->Cell(10,10,'','L', 0, 'C');
		$pdf->Cell(10,10,'X','LRTB', 0, 'C');
		$pdf->Cell(60,10,'Completed','R', 0, 'C');
		$pdf->Ln();
		$pdf->SetFont('Arial','UB',11);
		$pdf->Cell(110,8,'','LR', 0, 'C');
		$pdf->Cell(80,8,'','R', 0, 'C');
		$pdf->Ln();
		$pdf->Cell(110,10,$getinspector['full_name'],'LR', 0, 'C');
		$pdf->Cell(80,10,$getacceptor['full_name'],'R', 0, 'C');
		$pdf->Ln();
		$pdf->SetFont('Arial','',10);
		$pdf->Cell(110,10,$getinspector['position_name'],'LRB', 0, 'C');
		$pdf->Cell(80,10,$getacceptor['position_name'],'RB', 0, 'C');
		$pdf->Ln();

$pdf->Output();
		
?>