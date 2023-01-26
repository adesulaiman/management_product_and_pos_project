<?php

require "../../config.php";
require "../base/security_login.php";
require_once '../../plugins/vendor/autoload.php';

use Dompdf\Dompdf;


if (isset($_SESSION['userid'])) {

    if (!empty($_REQUEST['template'])) {

        $template = $_REQUEST['template'];
        $reportName = "";

        $dompdf = new Dompdf();


        switch ($template) {
            case "receive":

                $idreceive = $_REQUEST['id_receive'];
                ob_start();
                require_once("template_receive.php");
                $dompdf->loadHtml(ob_get_clean());
                $dompdf->setPaper('A4', 'potrait');
                break;

            case "nota_cashier":
                ob_start();
                require_once("template_receive.php");
                $dompdf->loadHtml(ob_get_clean());
                $dompdf->setPaper('A4', 'potrait');
                break;

            case "report_sales_daily":
                ob_start();
                require_once("template_receive.php");
                $dompdf->loadHtml(ob_get_clean());
                $dompdf->setPaper('A4', 'potrait');
                break;

            case "report_sales_monthly":
                ob_start();
                require_once("template_receive.php");
                $dompdf->loadHtml(ob_get_clean());
                $dompdf->setPaper('A4', 'potrait');
                break;

            case "report_sales_yearly":
                ob_start();
                require_once("template_receive.php");
                $dompdf->loadHtml(ob_get_clean());
                $dompdf->setPaper('A4', 'potrait');
                break;

            case "stockopname":
                ob_start();
                require_once("template_stockopname.php");
                $dompdf->loadHtml(ob_get_clean());
                $dompdf->setPaper('A4', 'potrait');
                break;
        }

        $dompdf->render();
        $dompdf->stream($reportName, array("Attachment" => 0));
    } else {
        echo json_encode(["status" => "error", "info" => "template not found !!"]);
    }
}
