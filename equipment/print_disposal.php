<?php

require('../engine/fpdf/fpdf.php');

include("../connect.php");

	date_default_timezone_set("Asia/Manila");
	$datetime = date("Y-m-d H:i:s");
	$date = date("Y-m-d");
	$month = date("Y-m");
	
	$readdisp_id = $_GET['id'];
	
	$getdispinfo = mysql_fetch_array(mysql_query("SELECT * FROM `eqp_disposal` WHERE `eqpd_id` = '$readdisp_id'"))or die(mysql_error());
	
	
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
	
$pdf = new PDF('L','mm','Legal');
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->SetFont('Arial','B',14);

		$pdf->SetFont('Arial','B',14);
		$pdf->Cell(335,8,'Disposal Form','LTR', 1, 'C');
		$pdf->SetFont('Arial','',12);
		$pdf->Cell(335,8,'Bicol University College of Nursing','LR', 1, 'C');
		$pdf->SetFont('Arial','',10);
		$pdf->Cell(335,10,'Legazpi CIty','LR', 1, 'C');
		$pdf->Cell(167.5,8,"Decision: ".$getdispinfo['dispstatus'],'L', 0, 'L');
		$pdf->Cell(167.5,8,'Disposal No.: '.$getdispinfo['dispnum'],'R', 1, 'R');
		$pdf->Cell(335,8,'Date: '.date("M d, Y", strtotime($getdispinfo['dispdate'])),'LR', 1, 'R');
		$pdf->SetFont('Arial','B',10);
		$pdf->Cell(335,8,'Committee','LR', 1, 'C');
		$pdf->SetFont('Arial','',10);
		$pdf->Cell(335,8,"Chairman: ".$getdispinfo['disp_chairman'],'LR', 1, 'C');
		$pdf->Cell(335,8,"COA Representative: ".$getdispinfo['disp_coa'],'LR', 1, 'C');
		$pdf->Cell(111.6,8,"Member 1: ".$getdispinfo['disp_memberA'],'BL', 0, 'C');
		$pdf->Cell(111.7,8,"Member 2: ".$getdispinfo['disp_memberB'],'B', 0, 'C');
		$pdf->Cell(111.7,8,"Member 3: ".$getdispinfo['disp_memberC'],'BR', 1, 'C');
		
		$pdf->SetFont('Arial','B',10);
		$pdf->Cell(40,10,'Unit','LRB', 0, 'C');
		$pdf->Cell(105,10,'Equipment Name','BR', 0, 'C');
		$pdf->Cell(80,10,'Property No.','BR', 0, 'C');
		$pdf->Cell(60,10,'Issued to','RB', 0, 'C');
		$pdf->Cell(50,10,'Department','RB', 1, 'C');
		
		$dispitems = mysql_query("SELECT * FROM `eqp_disposal_items` WHERE `eqpd_id` = '$getdispinfo[eqpd_id]'") or die(mysql_error());
						
		while($getdata = mysql_fetch_array($dispitems)){
			$geteqpdetails = mysql_fetch_array(mysql_query("SELECT * FROM `equipments` WHERE `eqp_id` = '$getdata[eqp_id]'"))or die(mysql_error());
			$selitem = mysql_fetch_array (mysql_query("SELECT * FROM `items` WHERE `item_id` = '$geteqpdetails[item_id]'"))or die(mysql_error());
			$selitemunit = mysql_fetch_array (mysql_query("SELECT * FROM `item_unit` WHERE `item_unit_id` = '$geteqpdetails[item_unit_id]'"))or die(mysql_error());
			$getpersonnel = mysql_fetch_array(mysql_query("SELECT pwi.pwi_id, CONCAT(p.personnel_fname,' ',p.personnel_lname) AS full_name, d.dept_name, ps.position_name FROM `personnel_work_info` AS pwi LEFT JOIN personnel AS p ON p.personnel_id = pwi.personnel_id LEFT JOIN department AS d ON d.dept_id = pwi.dept_id LEFT JOIN personnel_position AS ps ON ps.position_id = pwi.position_id WHERE pwi.personnel_id = '$geteqpdetails[received_by]'"))or die(mysql_error());
		
		$pdf->SetFont('Arial','',10);
		$pdf->Cell(40,10,$selitemunit['item_unit_name'],'LRB', 0, 'C');
		$pdf->Cell(105,10,$selitem['item_name'].", ".$geteqpdetails['brand'],'BR', 0, 'C');
		$pdf->Cell(80,10,$geteqpdetails['prop_num'],'BR', 0, 'C');
		$pdf->Cell(60,10,$getpersonnel['full_name'],'RB', 0, 'C');
		$pdf->Cell(50,10,$getpersonnel['dept_name'],'RB', 1, 'C');
		}	
	
$pdf->Output();
?>