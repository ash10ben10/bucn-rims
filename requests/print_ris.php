<?php


require('../engine/fpdf/fpdf.php');

include("../connect.php");

	global $year;
	$readris_id = $_GET['id'];
	
	$getrisdetails = mysql_fetch_array(mysql_query("SELECT * FROM request_issue_slip WHERE ris_id = '$readris_id'"));
	$getprdetails = mysql_fetch_array(mysql_query("SELECT pr.office_dept, d.dept_name FROM purchase_request AS pr LEFT JOIN department AS d ON d.dept_id = pr.dept_id WHERE pr_id = '$getrisdetails[pr_id]'"));
	
	$selctPs = "CONCAT(p.personnel_fname,' ',p.personnel_lname) AS full_name, pp.position_name";
	$fromPs= "personnel_work_info AS pwi LEFT JOIN personnel AS p ON p.personnel_id = pwi.personnel_id LEFT JOIN personnel_position AS pp ON pp.position_id = pwi.position_id";
	
	$requestedBy = mysql_fetch_array(mysql_query("SELECT ".$selctPs." FROM ".$fromPs." WHERE pwi.personnel_id = '$getrisdetails[requestedBy]'"));
	$approvedBy = mysql_fetch_array(mysql_query("SELECT ".$selctPs." FROM ".$fromPs." WHERE pwi.personnel_id = '$getrisdetails[approvedBy]'"));
	$issuedBy = mysql_fetch_array(mysql_query("SELECT ".$selctPs." FROM ".$fromPs." WHERE pwi.personnel_id = '$getrisdetails[issuedBy]'"));
	$receivedBy = mysql_fetch_array(mysql_query("SELECT ".$selctPs." FROM ".$fromPs." WHERE pwi.personnel_id = '$getrisdetails[receivedBy]'"));
	
	$readitems = mysql_query("SELECT * FROM request_items WHERE ris_id = '$readris_id'");
															
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
		$pdf->Cell(190,8,'REQUISITION AND ISSUE SLIP','LTR', 1, 'C');
		$pdf->SetFont('Arial','',10);
		$pdf->Cell(190,8,'BU College of Nursing','LR', 1, 'C');
		$pdf->SetFont('Arial','I',10);
		$pdf->Cell(190,8,'(Agency)','LRB', 0, 'C');
		$pdf->Ln();
		$pdf->SetFont('Arial','',10);
		$pdf->Cell(63.3,8,'Division: BUCN','LR', 0, '');
		$pdf->Cell(15,8,'RIS No.:','', 0, '');
		$pdf->Cell(48.3,8,$getrisdetails['risnum'],'', 0, '');
		$pdf->Cell(15,8,'Date:','', 0, '');
		$pdf->Cell(48.3,8,date("M d, Y", strtotime($getrisdetails['risdate'])),'R', 0, '');
		$pdf->Ln();
		$pdf->Cell(15,8,'Section: ','L', 0, '');
		$pdf->Cell(48.3,8,$getprdetails['dept_name'],'R', 0, '');
		$pdf->Cell(15,8,'SAI No.:','', 0, '');
		$pdf->Cell(48.3,8,$getrisdetails['sai_no'], 0, '');
		$pdf->Cell(15,8,'Date:','', 0, '');
			if($getrisdetails['sai_date'] == "0000-00-00"){
					$pdf->Cell(48.3,8,'','R', 0, '');
			}else{
					$pdf->Cell(48.3,8,date("M d, Y", strtotime($getrisdetails['sai_date'])),'R', 0, '');
			}
		$pdf->Ln();
		$pdf->SetFont('Arial','B',10);
		$pdf->Cell(126.6,8,'REQUISITION','TLB', 0, 'C');
		$pdf->Cell(63.3,8,'ISSUANCE','TBRL', 0, 'C');
		$pdf->Ln();
		//TableHeader
		$pdf->Cell(15,8,'Unit','LB', 0, 'C');
		$pdf->Cell(96.6,8,'Description','LB', 0, 'C');
		$pdf->Cell(15,8,'Qty','LB', 0, 'C');
		$pdf->Cell(15,8,'Qty','LB', 0, 'C');
		$pdf->Cell(24.15,8,'Unit Cost','LB', 0, 'C');
		$pdf->Cell(24.15,8,'Amount','LRB', 0, 'C');
		$pdf->Ln();
		//Table
		$pdf->SetFont('Arial','',10);
		while($getitemdetails = mysql_fetch_array($readitems)){
			$selitemunit = mysql_fetch_array(mysql_query("SELECT item_unit_name FROM item_unit WHERE item_unit_id = '$getitemdetails[item_unit_id]'"));
			$selitem = mysql_fetch_array(mysql_query("SELECT item_name FROM items WHERE item_id = '$getitemdetails[item_id]'"));
			$type = mysql_fetch_array(mysql_query("SELECT stock_type FROM stock_items WHERE description = '$getitemdetails[description]'"));
			
			$pdf->Cell(15,8,$selitemunit['item_unit_name'],'LB', 0, 'C');
			$pdf->Cell(96.6,8,$selitem['item_name'],'LB', 0, 'C');
			$pdf->Cell(15,8,$getitemdetails['quantity'],'LB', 0, 'C');
			$pdf->Cell(15,8,$getitemdetails['del_quantity'],'LB', 0, 'C');
			$pdf->Cell(24.15,8,$getitemdetails['est_unit_cost'],'LB', 0, 'C');
			$pdf->Cell(24.15,8,$getitemdetails['est_total_cost'],'LRB', 0, 'C');
			$pdf->Ln();
		}
		
		//Footer
		$pdf->SetFont('Arial','BI',9);
		$pdf->Cell(47.5,8,'Requested by:','L', 0, '');
		$pdf->Cell(47.5,8,'Approved by:','L', 0, '');
		$pdf->Cell(47.5,8,'Issued by:','L', 0, '');
		$pdf->Cell(47.5,8,'Received by:','LR', 0, '');
		$pdf->Ln();
		
		$pdf->SetFont('Arial','U',10);
		$pdf->Cell(47.5,8,$requestedBy['full_name'],'L', 0, 'C');
		$pdf->Cell(47.5,8,$approvedBy['full_name'],'L', 0, 'C');
		$pdf->Cell(47.5,8,$issuedBy['full_name'],'L', 0, 'C');
		$pdf->Cell(47.5,8,$receivedBy['full_name'],'LR', 0, 'C');
		$pdf->Ln();
		$pdf->SetFont('Arial','I',9);
		$pdf->Cell(47.5,8,$requestedBy['position_name'],'LB', 0, 'C');
		$pdf->Cell(47.5,8,$approvedBy['position_name'],'LB', 0, 'C');
		$pdf->Cell(47.5,8,$issuedBy['position_name'],'LB', 0, 'C');
		$pdf->Cell(47.5,8,$receivedBy['position_name'],'BLR', 0, 'C');
		$pdf->Ln();
		
		
		
$pdf->Output();


?>