<?php
require "../../config.php";
require "../base/db.php";

$receive = $adeQ->select("
    select 
    concat(d.barcode, ' - ', d.product_name) product,
    format(qty_receive,2) qty_receive,
    format(total_gram,2) total_gram,
    total_price,
    format(total_price/qty_receive, 2) price_per_pcs,
    r.receive_date,
    r.created_by,
    r.status_receive,
    r.no_invoice
    from data_detail_receive d
    inner join data_receive r on d.id_receive=r.id
    where d.id_receive=$idreceive
");

$receiveDate = date("d F Y", strtotime($receive[0]['receive_date']));
$receiveby = $receive[0]['created_by'];
$status_receive = $receive[0]['status_receive'];
$no_invoice = $receive[0]['no_invoice'];
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Report Receive</title>



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
            <h3>Report Receive Product</h3>
        </div>
    </div>

    <div class="row">
        <div class="col-xs-4">
            <table class="table tablenoborder">
                <tr>
                    <td>Invoice No </td>
                    <td>: <?php echo $no_invoice?></td>
                </tr>
                <tr>
                    <td>Receive Date</td>
                    <td>: <?php echo $receiveDate?></td>
                </tr>
                <tr>
                    <td>Received By</td>
                    <td>: <?php echo $receiveby?></td>
                </tr>
                <tr>
                    <td>Status</td>
                    <td>: <?php echo $status_receive?></td>
                </tr>
            </table>
        </div>
    </div>

    <div class="row">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <td>No</td>
                    <td>Product</td>
                    <td>Qty</td>
                    <td>Total Gram</td>
                    <td>Total Price</td>
                    <td>Price / Pcs</td>
                </tr>
            </thead>
            <tbody>
                <?php
                $i=1;
                $total = 0;
                foreach($receive as $rec){
                    echo "
                        <tr>
                            <td>$i</td>
                            <td>$rec[product]</td>
                            <td class='text-right'>$rec[qty_receive]</td>
                            <td class='text-right'>$rec[total_gram]</td>
                            <td class='text-right'>Rp ".number_format($rec["total_price"], 2)."</td>
                            <td class='text-right'>Rp $rec[price_per_pcs]</td>
                        </tr>
                    ";
                    $total += $rec["total_price"];
                    $i++;
                }
                ?>
                <tr style="font-size:20px">
                    <td colspan="4"><b>Total Receive</b></td>
                    <td colspan="2" class="text-right"><b>Rp <?php echo number_format($total, 2)?></b></td>
                </tr>
            </tbody>
        </table>

        <div class="row" style="margin-top:30px">
            <div class="col-xs-11 text-right">
                <h4>Management</h4>
                <br>
                <br>
                <p style="color:black;font-family:'Grand-Hotel'"><?php echo $organization?></p>
            </div>
        </div>
    </div>


</body>

</html>