<?php


require('../engine/fpdf/fpdf.php');

include("../connect.php");

	$readstockunit_id = $_GET['id'];
	
	$selectme = "su.su_id, si.stock_id, su.stock_no, si.item_id, i.item_name, si.stock_type, si.description, si.order_point, su.item_unit_id, iu.item_unit_name, su.price, su.quantity, i.category_id, cat.category_name";
	$setfrom = "`stock_items` AS si LEFT JOIN stock_units AS su ON su.stock_id = si.stock_id LEFT JOIN items AS i ON i.item_id = si.item_id LEFT JOIN item_unit AS iu ON iu.item_unit_id = su.item_unit_id LEFT JOIN category AS cat ON cat.category_id = i.category_id";
	
	$getdesc = mysql_fetch_array(mysql_query("SELECT ".$selectme." FROM ".$setfrom." WHERE su.su_id = '$readstockunit_id' "));
	

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
		$pdf->Cell(190,8,'STOCK CARD','LTR', 1, 'C');
		$pdf->SetFont('Arial','',10);
		$pdf->Cell(190,8,'Bicol University College of Nursing','LRB', 1, 'C');
		
		$pdf->SetFont('Arial','B',10);
		$pdf->Cell(20,8,'Item Name:','LB', 0, 'L');
		$pdf->SetFont('Arial','',10);
		$pdf->Cell(35,8,$getdesc['item_name'],'RB', 0, 'L');
		$pdf->SetFont('Arial','B',10);
		$pdf->Cell(20,8,'Item Unit:','B', 0, 'L');
		$pdf->SetFont('Arial','',10);
		$pdf->Cell(25,8,$getdesc['item_unit_name'],'RB', 0, 'L');
		$pdf->SetFont('Arial','B',10);
		$pdf->Cell(20,8,'Stock No.:','B', 0, 'L');
		$pdf->SetFont('Arial','',10);
		$pdf->Cell(25,8,$getdesc['stock_no'],'RB', 0, 'L');
		$pdf->SetFont('Arial','B',10);
		$pdf->Cell(20,8,'Amount:','B', 0, 'L');
		$pdf->SetFont('Arial','',10);
		$pdf->Cell(25,8,number_format($getdesc['price'], 2,'.',','),'RB', 0, 'L');
		$pdf->Ln();
		
		$pdf->SetFont('Arial','B',10);
		$pdf->Cell(20,8,'Quantity:','LB', 0, 'L');
		$pdf->SetFont('Arial','',10);
		$pdf->Cell(15,8,$getdesc['quantity'],'B', 0, 'L');
		$pdf->SetFont('Arial','B',10);
		$pdf->Cell(20,8,'Category:','LB', 0, 'L');
		$pdf->SetFont('Arial','',10);
		$pdf->Cell(45,8,$getdesc['category_name'],'B', 0, 'L');
		$pdf->SetFont('Arial','B',10);
		$pdf->Cell(23,8,'Description:','LB', 0, 'L');
		$pdf->SetFont('Arial','',10);
		$pdf->Cell(67,8,$getdesc['description'],'RB', 0, 'L');
		$pdf->Ln();
		
		$pdf->SetFont('Arial','B',10);
		$pdf->Cell(30,8,'','L', 0, 'L');
		$pdf->Cell(30,8,'','L', 0, 'L');
		$pdf->Cell(20,8,'RIS Receipt','LB', 0, 'C');
		$pdf->Cell(90,8,'Stock Issuance','LB', 0, 'C');
		$pdf->Cell(20,8,'Balance','LBR', 0, 'C');
		$pdf->Ln();
		$pdf->SetFont('Arial','B',10);
		$pdf->Cell(30,8,'Date','LB', 0, 'C');
		$pdf->Cell(30,8,'Reference','LB', 0, 'C');
		$pdf->Cell(20,8,'Quantity','LB', 0, 'C');
		$pdf->Cell(20,8,'Quantity','LB', 0, 'C');
		$pdf->Cell(45,8,'Personnel','LB', 0, 'C');
		$pdf->Cell(25,8,'Office','LB', 0, 'C');
		$pdf->Cell(20,8,'Quantity','LBR', 0, 'C');
		$pdf->Ln();
		
		$retrivestock = mysql_query("SELECT * FROM stock_card WHERE su_id = '$readstockunit_id' ");
											
		while($getdata = mysql_fetch_array($retrivestock)){
			$selctPs = "pwi.personnel_id, CONCAT(p.personnel_fname,' ',p.personnel_lname) AS full_name, d.dept_name";
			$fromPs= "personnel_work_info AS pwi LEFT JOIN personnel AS p ON p.personnel_id = pwi.personnel_id LEFT JOIN department AS d ON d.dept_id = pwi.dept_id";
			$getrequestor = mysql_fetch_array(mysql_query("SELECT ".$selctPs." FROM ".$fromPs." WHERE pwi.personnel_id = $getdata[personnel_id] "));
			
		$pdf->SetFont('Arial','',10);
		$pdf->Cell(30,8,date("M d, Y", strtotime($getdata['recdate'])),'LB', 0, 'C');
		$pdf->Cell(30,8,$getdata['reference'],'LB', 0, 'C');
		if ($getdata['qty_receipt'] == 0){
			$pdf->Cell(20,8,'','LB', 0, 'C');
		}else{
			$pdf->Cell(20,8,$getdata['qty_receipt'],'LB', 0, 'C');
		}
		
		if($getdata['issue_qty'] == 0){
			$pdf->Cell(20,8,'','LB', 0, 'C');
		}else{
			$pdf->Cell(20,8,$getdata['issue_qty'],'LB', 0, 'C');
		}
		
		if($getdata['personnel_id'] == 0){
			$pdf->Cell(45,8,'','LB', 0, 'C');
		}else{
			$pdf->Cell(45,8,$getrequestor['full_name'],'LB', 0, 'C');
		}
		
		if($getdata['personnel_id'] == 0){
			$pdf->Cell(25,8,'','LB', 0, 'C');
		}else{
			$pdf->Cell(25,8,$getrequestor['dept_name'],'LB', 0, 'C');
		}	
		$pdf->Cell(20,8,$getdata['issue_stock_bal'],'LBR', 0, 'C');
		$pdf->Ln();
		}
		
$pdf->Output();
?>