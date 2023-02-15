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

                $struk = $_REQUEST['struk'];
                ob_start();
                require_once("template_struk.php");
                $dompdf->loadHtml(ob_get_clean());
                $dompdf->setPaper('A4', 'potrait');
                break;

            case "nota_reseller":

                $struk = $_REQUEST['struk'];
                ob_start();
                require_once("template_reseller.php");
                $dompdf->loadHtml(ob_get_clean());
                $dompdf->setPaper('A4', 'potrait');
                break;

            case "nota_os":

                $struk = $_REQUEST['struk'];
                ob_start();
                require_once("template_os.php");
                $dompdf->loadHtml(ob_get_clean());
                $dompdf->setPaper('A4', 'potrait');
                break;

            case "cashflow":

                $tanggal = $_REQUEST['tanggal'];
                ob_start();
                require_once("template_cashflow.php");
                $dompdf->loadHtml(ob_get_clean());
                $dompdf->setPaper('A4', 'potrait');
                break;

            case "nota_installment":

                $struk = $_REQUEST['struk'];
                ob_start();
                require_once("template_installment.php");
                $dompdf->loadHtml(ob_get_clean());
                $dompdf->setPaper('A4', 'potrait');
                break;

            case "reportsales":
                $idbulan = $_REQUEST['idbulan'];
                $bulan = $_REQUEST['bulan'];
                ob_start();
                require_once("template_sales.php");
                $dompdf->loadHtml(ob_get_clean());
                $dompdf->setPaper('A4', 'potrait');
                break;

            case "stockopname":
                $idstockopname = $_REQUEST['idstockopname'];
                ob_start();
                require_once("template_stockopname.php");
                $dompdf->loadHtml(ob_get_clean());
                $dompdf->setPaper('A4', 'landscape');
                break;

            case "stockweight":
                $idstockopname = $_REQUEST['idstockopname'];
                ob_start();
                require_once("template_stockweight.php");
                $dompdf->loadHtml(ob_get_clean());
                $dompdf->setPaper('A4', 'landscape');
                break;
        }

        $dompdf->render();
        $dompdf->stream($reportName, array("Attachment" => 0));
    } else {
        echo json_encode(["status" => "error", "info" => "template not found !!"]);
    }
}
