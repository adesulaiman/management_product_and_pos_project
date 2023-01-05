<?php

require "../../config.php";
require "../base/db.php";
require "../base/security_login.php";
require "generatePdf.php";

if(isset($_SESSION['userid']))
{
    if(isset($_GET['link']))
  	{
        // GeneratePdf("http://localhost/application/apps_masjid/templatePdf/rincian_arus_kas.php?bulan=202109", "rincian arus kas");
        GeneratePdf($dir . "templatePdf/" . $_GET['link'] , $_GET['pdfname'], $_GET['format']);
    }
}

// echo file_get_contents("http://localhost/application/apps_masjid/templatePdf/rincian_arus_kas.php");
?>