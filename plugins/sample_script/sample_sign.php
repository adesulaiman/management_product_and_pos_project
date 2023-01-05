<?php

require "java/sign.php";

$sign = new signpdf();
$sign->inputPDF(__DIR__ . "/assets/pdffiles/5_6287508186320601747.pdf");
$sign->setPathOutput(__DIR__ . "/assets/pdffiles");
$sign->setCertFile(__DIR__ . "/assets/certificate/3EFF0C810BA0BA725ACB7185DDF4995B7EC0794F.p12", "*_*TTEyess1amFine*_*");
$sign->setAttribute("Kabupaten Sampang", "Dokumen sudah di TTE", "Amrin Sampang");
$sign->setTSA("https://tsa.govca.id");
$sign->setLVT(true);
$sign->sign();

echo "<br>" . $sign->getlog();
echo "<br>" . $sign->getStatus();
echo "<br>" . $sign->getInfoProcess();

?>