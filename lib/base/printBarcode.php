<?php
require "../../config.php";
require "security_login.php";
require "db.php";
require '../../plugins/vendor/autoload.php';
require('../../plugins/fpdf/code128.php');


if (isset($_SESSION['userid'])) {

	if (isset($_POST['dataPrint'])) {
	$dataPrint = $_POST['dataPrint'];
	
	$data = [];
	foreach($dataPrint as $product){
		for($h=0 ; $h <= $product['qtyPrint']; $h++){
			$data[] = [
				"barcode" => $product['barcode'],
				"price" => "Rp " . number_format($product['sell_price'])
			];
		}
	}


	$pdf = new PDF_Code128('P','mm','A4');
	$pdf->AddPage();

	//margin left right
	// left right = 14mm
	// top bottom = 4.5mm


	$marginlr = 12;
	$margintb = 4.5;
	$gap = 0.5;
	$labelhight = 12;
	$labelwidth = 26;


	$looprow = ceil(count($data) / 7);
	$totrow = floor(count($data) / 7) * 7;
	$lastcnt = count($data) - $totrow;


	$margintbProses = $margintb;
	$loopRow = 0;
	//A set
	for($l = 1 ; $l <= $looprow; $l++){
		$columnloop = 7;

		if(($l % 21) == 0){
			$pdf->AddPage();
			$margintbProses = $margintb;
		}

		if($l == $looprow){
			$columnloop = $lastcnt;
		}

		$marginlrProses = $marginlr;
		for($i = 1 ; $i <= $columnloop; $i++){
			$code = $data[$loopRow]['barcode'];
			$harga = $data[$loopRow]['price'];
			$pdf->Code128($marginlrProses, $margintbProses, $code, $labelwidth - 5, $labelhight - 4);
			$pdf->SetFont('Arial', '', 8);
			$pdf->SetXY($marginlrProses -1, $margintbProses + 7.7);
			$pdf->Write(4,  $harga);
			$pdf->SetFont('Arial', '', 7);
			$pdf->TextWithDirection($marginlrProses + $labelwidth - 4.5, $margintbProses , $code ,'D');
			$marginlrProses = $marginlrProses + $labelwidth + $gap;
			$loopRow++;
		}
		$margintbProses = $margintbProses + $labelhight + $gap;
	}
	
	
	$pdfFile = $pdf->Output("","S");
	echo base64_encode($pdfFile);

	}
} // close session