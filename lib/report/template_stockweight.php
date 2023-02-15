<?php
require "../../config.php";
require "../base/db.php";

//cek if SO is commited
$cekSO = $adeQ->select($adeQ->prepare("select * from data_stock_weight where id=%s", $idstockopname));
$isCommit = 0;
if ($cekSO[0]['status'] == "" || $cekSO[0]['status'] == "open") {
  $isCommit = 1;
}

if ($isCommit) {
    $stockopname = $adeQ->select("
        select * from vw_data_stock_weight where id_stock_weight = $idstockopname
    ");
} else {
    $stockopname = $adeQ->select("
        select * from data_stock_weight_detail_hist where id_stock_weight = $idstockopname
    ");
}


$receiveDate = date("d F Y", strtotime($cekSO[0]['stock_date']));
$receiveby = $cekSO[0]['created_by'];
$status_receive = $cekSO[0]['status'];
$no_invoice = $cekSO[0]['stock_info'];
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Report Stock Opname</title>



    <?php
    $content = '<style>' . file_get_contents($dir . "/plugins/bootstrap/css/bootstrap-pdf.css") . '</style>';
    echo $content;
    ?>


    <style>
        body {
            font-family: 'Source-Sans' !important;
            font-size: 14px;
        }

        h1,
        h2,
        h3,
        h4,
        h5 {
            margin: 0 !important
        }

        .tablenoborder>thead>tr>th,
        .tablenoborder>tbody>tr>th,
        .tablenoborder>tfoot>tr>th,
        .tablenoborder>thead>tr>td,
        .tablenoborder>tbody>tr>td,
        .tablenoborder>tfoot>tr>td {
            border-top: 0px solid #ddd !important;
            padding: 0;
        }
    </style>

</head>

<body>

    <div class="row">
        <div class="col-xs-2 text-right">
            <img src="data:image/png;base64,<?php echo base64_encode(file_get_contents($dir . "assets/img/logo.png")) ?>" style="width: 70px;" />
        </div>
        <div class="col-xs-10 text-center">
            <h1 style="color:black;font-family:'Grand-Hotel'"><?php echo $organization ?> </h1>
            <h4><?php echo $addrOrg ?> </h4>
        </div>
    </div>
    <hr>
    <div class="row">
        <div class="col-xs-12 text-center">
            <h3>Report Stock Weight</h3>
        </div>
    </div>

    <div class="row">
        <div class="col-xs-4">
            <table class="table tablenoborder">
                <tr>
                    <td>Stock Weight Info </td>
                    <td>: <?php echo $no_invoice ?></td>
                </tr>
                <tr>
                    <td>Stock Weight Date</td>
                    <td>: <?php echo $receiveDate ?></td>
                </tr>
                <tr>
                    <td>Status</td>
                    <td>: <?php echo $status_receive ?></td>
                </tr>
            </table>
        </div>
    </div>

    <div class="row">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <td>No</td>
                    <td>Storage Name</td>
                    <td>Opening Gram</td>
                    <td>Closing Gram</td>
                    <td>SS Gram Storage</td>
                    <td>Difference with Closing Weight</td>
                </tr>
            </thead>
            <tbody>
                <?php
                $i = 1;
                $total = 0;
                $boxlast = "";
                foreach ($stockopname as $rec) {
                    $boxfirst = $rec["storage_name"];


                    echo "
                        <tr>
                            <td>$i</td>
                            <td>$rec[storage_name]</td>
                            <td>$rec[opening_gram]</td>
                            <td>$rec[closing_gram]</td>
                            <td>$rec[SS_gram_storage_update]</td>
                            <td>$rec[weight_closing_vs_SS_update]</td>
                        </tr>
                    ";

                }
                ?>
            </tbody>
        </table>

        <div class="row" style="margin-top:30px">
            <div class="col-xs-11 text-right">
                <h4>Management</h4>
                <br>
                <br>
                <p style="color:black;font-family:'Grand-Hotel'"><?php echo $organization ?></p>
            </div>
        </div>
    </div>


</body>

</html>