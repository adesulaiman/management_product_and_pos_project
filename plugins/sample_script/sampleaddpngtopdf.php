<?php
require_once('plugins/fpdf/fpdf.php');
require_once('plugins/fpdi/src/autoload.php');
$pdf = new \setasign\Fpdi\Fpdi();;

//Set the source PDF file
$pagecount = $pdf->setSourceFile("assets/upload/BALASAN  Surat Permohonan  Ijin Magang SMKN 2 Sampang.pdf");


$space = 7;
$startY = 60;
$startYDate = 45;
$startXValue = 45;
$startXDate = 140;
$startX = 30;

for ($pageNo = 1; $pageNo <= $pagecount; $pageNo++) {
    // import a page
    $templateId = $pdf->importPage($pageNo);
    // get the size of the imported page
    $size = $pdf->getTemplateSize($templateId);

    error_log("Size x ". $size[0] . " - Size y ". $size[1]);
    // create a page (landscape or portrait depending on the imported page size)
    if ($size[0] > $size[1]) {
        $pdf->AddPage('L', array($size[0], $size[1]));
    } else {
        $pdf->AddPage('P', array($size[0], $size[1]));
    }

    // use the imported page
    $pdf->useTemplate($templateId);

    $pdf->SetFont('Helvetica');
    $pdf->SetFontSize(10);
    $pdf->SetXY(5, 5);


    // set just in page 1
    $pdf->Image('assets/img/kop.png',0,0,$size[0],40);

    $pdf->SetXY( $startXDate, $startYDate); 
    $pdf->Write(0, 'Sampang, 12 November 2021');


    $pdf->SetXY( $startX, $startY); 
    $pdf->Write(0, 'Nomor');
    $pdf->SetXY( $startXValue, $startY); 
    $pdf->Write(0, ': 421.7/ 246 /434.213/2021');

    $startY += $space;
    $pdf->SetXY( $startX, $startY);
    $pdf->Write(0, 'Jenis Dokumen');
    $pdf->SetXY( $startXValue, $startY); 
    $pdf->Write(0, ': Penting');

    $startY += $space;
    $pdf->SetXY( $startX, $startY);
    $pdf->Write(0, 'Perihal');
    $pdf->SetXY( $startXValue, $startY); 
    $pdf->Write(0, ': ');
    $pdf->SetXY( $startXValue + 2, $startY - 2.5); 
    $pdf->MultiCell(80, 5, 'Pengajuan Praktek Kerja Lapangan Dalam Rangka Pendidikan Sistim Ganda', 0, 'L');

    //footer
    $pdf->Image('assets/img/footer_surat.png',45,$size[1] - 20 ,120,10);


    
}





$pdf->Output("modified_pdf.pdf", "F");

// http://www.powerlessdynamo.com/stack_exchange/answers/15638213_plot-coordinates-on-pdf-displayed-in-iframe.html
?>