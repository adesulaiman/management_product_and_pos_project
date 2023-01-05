<?php

require "../../plugins/dompdf/autoload.inc.php";
  // reference the Dompdf namespace
use Dompdf\Dompdf;

function GeneratePdf($urlHtml, $pdfName, $format){  

$curl = curl_init($urlHtml);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
$contents = curl_exec($curl);
curl_close($curl);


  // instantiate and use the dompdf class
  $dompdf = new Dompdf(array('enable_remote' => true));

  $dompdf->set_base_path("../../plugins/bootstrap/css/");

  $dompdf->loadHtml($contents);

  // (Optional) Setup the paper size and orientation
  $dompdf->setPaper('A4', $format);

  // Render the HTML as PDF
  $dompdf->render();

  // // Output the generated PDF to Browser
  $dompdf->stream($pdfName ,array('Attachment'=>false));
  
}


?>