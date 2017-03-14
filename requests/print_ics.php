<?php

require('../engine/fpdf/fpdf.php');

include("../connect.php");

	$readics_id = $_GET['id'];
	
	$getics = mysql_fetch_array(mysql_query("SELECT * FROM eqp_ics WHERE ics_id = '$readics_id'"));
	$geteqpdetails = mysql_fetch_array(mysql_query("SELECT * FROM equipments WHERE icspar = 'ICS' AND ics_par_id = '$readics_id'"));
	$getitemdetails = mysql_fetch_array(mysql_query("SELECT * FROM request_items WHERE req_item_id = '$geteqpdetails[req_item_id]'"));
	$getstocknum = mysql_fetch_array(mysql_query("SELECT stock_no FROM stock_units WHERE su_id = '$geteqpdetails[su_id]'"));
	$selitemunit = mysql_fetch_array(mysql_query("SELECT item_unit_name FROM item_unit WHERE item_unit_id = '$geteqpdetails[item_unit_id]'"));
	$selitem = mysql_fetch_array(mysql_query("SELECT item_name FROM items WHERE item_id = '$geteqpdetails[item_id]'"));
	
	$getpr = mysql_fetch_array(mysql_query("SELECT * FROM purchase_request WHERE pr_id='$getics[pr_id]'"));
	$getiar = mysql_fetch_array(mysql_query("SELECT * FROM inspect_accept_report WHERE iar_id='$getics[iar_id]'"));
	$getfunding = mysql_fetch_array(mysql_query("SELECT * FROM `funding` WHERE `fund_id`='$getics[fund_id]'"));
	$getsupplier = mysql_fetch_array(mysql_query("SELECT * FROM supplier WHERE supplier_id='$getics[supplier_id]'"));
	
	$selctPs = "CONCAT(p.personnel_fname,' ',p.personnel_lname) AS full_name, pp.position_name";
	$fromPs= "personnel_work_info AS pwi LEFT JOIN personnel AS p ON p.personnel_id = pwi.personnel_id LEFT JOIN personnel_position AS pp ON pp.position_id = pwi.position_id";
	$receivedBy = mysql_fetch_array(mysql_query("SELECT ".$selctPs." FROM ".$fromPs." WHERE pwi.personnel_id = '$getics[receivedBy]' "));
	$receivedFr = mysql_fetch_array(mysql_query("SELECT ".$selctPs." FROM ".$fromPs." WHERE pwi.personnel_id = '$getics[receivedFrom]' "));

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
	$pdf->Cell(190,8,'INVENTORY CUSTODIAN SLIP','LTR', 1, 'C');
	$pdf->SetFont('Arial','',10);
	$pdf->Cell(190,8,'Bicol University College of Nursing','LR', 1, 'C');
	$pdf->Cell(190,8,'Legazpi City','LR', 0, 'C');
	$pdf->Ln();
	
	$pdf->Cell(126.6,8,'','L', 0, '');
	$pdf->Cell(15,8,'Date: ','', 0, 'R');
	$pdf->Cell(48.3,8,date("M j, Y", strtotime($getics['icsdate'])),'R', 1, '');
	$pdf->Cell(126.6,8,'','L', 0, '');
	$pdf->Cell(15,8,'ICS No.: ','', 0, '');
	$pdf->Cell(48.3,8,$getics['icsnum'],'R', 1, '');
	
		//TableHeader
	$pdf->SetFont('Arial','B',10);
	$pdf->Cell(15,8,'Qty','TLB', 0, 'C');
	$pdf->Cell(30,8,'Unit','TLB', 0, 'C');
	$pdf->Cell(75,8,'Description','TLB', 0, 'C');
	$pdf->Cell(30,8,'Amount','TLB', 0, 'C');
	$pdf->Cell(40,8,'Estimated Useful Life','TLBR', 0, 'C');
	$pdf->Ln();
		//Table
	$pdf->SetFont('Arial','',10);
	$pdf->Cell(15,8,$getics['quantity'],'TL', 0, 'C');
	$pdf->Cell(30,8,$selitemunit['item_unit_name'],'TL', 0, 'C');
	$pdf->Cell(75,8,$selitem['item_name'],'TL', 0, 'C');
	$pdf->Cell(30,8,number_format($geteqpdetails['unit_value'], 2,'.',','),'TL', 0, 'C');
	$pdf->Cell(40,8,$getics['est_useful_life'],'TLR', 0, 'C');
	$pdf->Ln();
	$pdf->Cell(15,8,'','LB', 0, 'C');
	$pdf->Cell(30,8,'','LB', 0, 'C');
	$pdf->Cell(75,8,$geteqpdetails['description'],'LB', 0, 'C');
	$pdf->Cell(30,8,'','LB', 0, 'C');
	$pdf->Cell(40,8,'','LRB', 0, 'C');
	$pdf->Ln();
		//tableheader2
	$pareqps = mysql_query("SELECT * FROM equipments WHERE icspar = 'ICS' AND ics_par_id = '$readics_id'");
	if(mysql_num_rows($pareqps) == 0){
		}else{
		$pdf->SetFont('Arial','B',10);
		$pdf->Cell(20,8,'Item No.','LB', 0, 'C');
		$pdf->Cell(47.5,8,'Serial No.','LB', 0, 'C');
		$pdf->Cell(75,8,'Property No.','LB', 0, 'C');
		$pdf->Cell(47.5,8,'Status','LBR', 0, 'C');
		$pdf->Ln();
			//table
		$pdf->SetFont('Arial','',10);
		$count = 1;
		while($getdata = mysql_fetch_array($pareqps)){
			$pdf->Cell(20,8,$count,'LB', 0, 'C');
			if ($getdata['serialnum'] == ""){
				$pdf->Cell(47.5,8,'Not Available','LB', 0, 'C');
			}else{
				$pdf->Cell(47.5,8,$getdata['serialnum'],'LB', 0, 'C');
			}
			$pdf->Cell(75,8,$getdata['prop_num'],'LB', 0, 'C');
			$pdf->Cell(47.5,8,$getdata['remarks'],'LBR', 0, 'C');
			$pdf->Ln();
			$count++;
		}
	}
	$pdf->SetFont('Arial','B',10);
	$pdf->Cell(15,8,'PR No.','LB', 0, 'C');
	$pdf->SetFont('Arial','',10);
	$pdf->Cell(32.5,8,$getpr['prnum'],'B', 0, 'R');
	$pdf->Cell(15,8,'dated','B', 0, 'C');
	$pdf->Cell(32.5,8,$getpr['prdate'],'BR', 0, 'L');
	$pdf->SetFont('Arial','B',10);
	$pdf->Cell(15,8,'SI No.','B', 0, 'C');
	$pdf->SetFont('Arial','',10);
	$pdf->Cell(32.5,8,$getiar['invoice_num'],'B', 0, 'R');
	$pdf->Cell(15,8,'dated','B', 0, 'C');
	$pdf->Cell(32.5,8,$getiar['invoice_date'],'BR', 0, 'L');
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
	$pdf->SetFont('Arial','I',10);
	$pdf->Cell(95,8,$receivedBy['position_name'],'LB', 0, 'C');
	$pdf->Cell(95,8,$receivedFr['position_name'],'BLR', 1, 'C');
	
	

$pdf->Output();
?>