<?php


require('../engine/fpdf/fpdf.php');

include("../connect.php");

	global $year;
	$readpr_id = $_GET['id'];
	$getpr = mysql_fetch_array(mysql_query("SELECT `pr_id`, `office_dept`, `dept_id`, CONCAT(prnum,' dtd ',(DATE_FORMAT(prdate, '%m/%d/%Y'))) AS PRnum, `sai_no`, `purpose`, `personnel_id`, `pwi_id` FROM purchase_request WHERE pr_id ='$readpr_id'"));
	$getpr_items = mysql_fetch_array(mysql_query("SELECT * FROM request_items WHERE pr_id = '$readpr_id' AND pr_status = 'approved'"));
	$getsection = mysql_fetch_array(mysql_query("SELECT * FROM department WHERE dept_id = $getpr[dept_id]"));
	
	$selctPs = "CONCAT(p.personnel_fname,' ',p.personnel_lname) AS full_name, pp.position_name";
	$fromPs= "personnel_work_info AS pwi LEFT JOIN personnel AS p ON p.personnel_id = pwi.personnel_id LEFT JOIN personnel_position AS pp ON pp.position_id = pwi.position_id";
	$getrequestor = mysql_fetch_array(mysql_query("SELECT ".$selctPs." FROM ".$fromPs." WHERE pwi.personnel_id = $getpr[personnel_id] "));
	
	$getpos = mysql_fetch_array(mysql_query("SELECT `position_id` FROM personnel_position WHERE position_name = 'Dean' OR position_name = 'OIC Dean' "));
	$getdean = mysql_fetch_array(mysql_query("SELECT ".$selctPs." FROM ".$fromPs." WHERE pwi.position_id = $getpos[position_id] LIMIT 1 "));
	
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
		$pdf->Cell(190,8,'PURCHASE REQUEST','LTR', 1, 'C');
		$pdf->SetFont('Arial','',12);
		$pdf->Cell(190,10,'Bicol University College of Nursing','LRB', 1, 'C');
		$pdf->Cell(120,10,'Department:   BUCN','L', 0, 'L');
		$pdf->Cell(15,10,'PR No.','L', 0, 'L');
		$pdf->Cell(55,10,$getpr['PRnum'],'R', 0, 'L');
		$pdf->Ln();
		$pdf->Cell(20,10,'Section:','LB', 0, 'L');
		$pdf->Cell(100,10,$getsection['dept_name'],'RB', 0, 'L');
		$pdf->Cell(17,10,'SAI No. ','LB', 0, 'L');
		$pdf->Cell(53,10,$getpr['sai_no'],'RB', 0, 'L');
		$pdf->Ln();
		
		

		//TableHeader
		$pdf->SetFont('Arial','B',11);
		$pdf->Cell(25,15,'Qty','LB', 0, 'C');
		$pdf->Cell(25,15,'Unit of Issue','LB', 0, 'C');
		$pdf->Cell(80,15,'ITEM DESCRIPTION','LB', 0, 'C');
		$pdf->SetFont('Arial','B',8);
		$pdf->Cell(30,15,'Estimated Unit Cost','LB', 0, 'C');
		$pdf->Cell(30,15,'Estimated Cost','LRB', 0, 'C');
		$pdf->Ln();
		//TableContent
		$query = mysql_query("SELECT * FROM request_items WHERE pr_id = '$readpr_id' AND pr_status = 'approved' ORDER BY req_item_id ASC");
		while($row = mysql_fetch_assoc($query)){
			$getunit = mysql_fetch_array(mysql_query("SELECT * FROM item_unit WHERE item_unit_id = $row[item_unit_id]"));
			$showitems = mysql_fetch_array(mysql_query("SELECT * FROM items WHERE item_id = $row[item_id]"));
																											
		$pdf->SetFont('Arial','', 10);
		$pdf->Cell(25,10,$row['quantity'],'L', 0, 'C');
		$pdf->Cell(25,10,$getunit['item_unit_name'],'L', 0, 'C');
		$pdf->Cell(80,10,$showitems['item_name'].", ".$row['description'],'L', 0, 'C');
		$pdf->Cell(30,10,number_format($row['est_unit_cost'], 2,'.',','),'L', 0, 'C');
		$pdf->Cell(30,10,number_format($row['est_total_cost'], 2,'.',','),'LR', 0, 'C');
		$pdf->Ln();
		}
		$pdf->Cell(190,0,'','T',1,'');

		
		$pdf->SetFont('Arial','', 10);
		$pdf->Cell(20,15,'Purpose:','LB',0,'L');
		$pdf->SetFont('Arial','', 12);
		$pdf->Cell(170,15, $getpr['purpose'],'RB',0,'L');
		$pdf->Ln();
		
		$pdf->SetFont('Arial','', 10);
		$pdf->Cell(95,10,'Requested by:','LR',0,'L');
		$pdf->Cell(95,10,'Approved by:','R',0,'L');
		$pdf->Ln();
		$pdf->Cell(95,10,'','LR',0,'L');
		$pdf->Cell(95,10,'','R',0,'L');
		$pdf->Ln();
		
		$pdf->SetFont('Arial','B', 12);
		$pdf->Cell(95,10,$getrequestor['full_name'],'L',0,'C');
		$pdf->Cell(95,10,$getdean['full_name'],'LR',0,'C');
		$pdf->Ln();
		
		$pdf->SetFont('Arial','', 10);
		$pdf->Cell(95,10,$getrequestor['position_name'],'LB',0,'C');
		$pdf->Cell(95,10,$getdean['position_name'],'LRB',0,'C');
		$pdf->Ln();
		
		$pdf->SetFont('Arial','',8);
		$pdf->Cell(190,5,'BU-F-USO-02');
		$pdf->Ln();
		$pdf->Cell(190,5,'Effectivity Date: June 3,2012');
		$pdf->Ln();
		$pdf->Cell(190,5,'Revision No: 0');
		$pdf->Ln();
		

$pdf->Output();


?>