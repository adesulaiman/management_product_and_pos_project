<?php
require "../../config.php";
require "../base/db.php";


$sales_detail = $adeQ->select("
select 
concat(barcode, ' - ', product_name) product,
format(qty_out, 2 ) qty,
format(qty_out * netto_gram_out, 2) gram,
format(pen, 2) pen,
format(qty_out * netto_gram_out * pen / 100, 3)  kadar
from data_product_out_resaller_detail
where invoice='$struk'
");

$sales = $adeQ->select("
select 
*
from data_product_out_resaller d
where invoice='$struk'
");


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice</title>



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
            <h3>Invoice Reseller : <?php echo $struk ?></h3>
            <h5>Date : <?php echo date("d F Y", strtotime($sales[0]['created_date'])) ?></h5>
        </div>
    </div>

    <div class="row" style="margin-top:30px">
        <table class="table table-bordered">
            <thead>
                <tr style="background:grey;color:white">
                    <td><b>Product</b></td>
                    <td><b>Qty</b></td>
                    <td><b>Netto Gram</b></td>
                    <td><b>Pen</b></td>
                    <td><b>Emas Murni</b></td>
                </tr>
            </thead>
            <tbody>
                <?php
                $i = 1;
                $total = 0;
                foreach ($sales_detail as $sel) {


                    echo "
                        <tr>
                            <td>$sel[product]</td>
                            <td>$sel[qty]</td>
                            <td>$sel[gram]</td>
                            <td>$sel[pen]%</td>
                            <td class='text-right'>$sel[kadar]</td>
                        </tr>
                    ";
                    $total += $sel["kadar"];
                }
                ?>
                <tr style="font-size:20px">
                    <td colspan="4"><b>Total Emas Murni</b></td>
                    <td colspan="1" class="text-right"><b><?php echo number_format($total, 3) ?></b></td>
                </tr>
            </tbody>
        </table>

        <div class="row" style="margin-top:20px">
            <div class="col-xs-6">
                <h4>Staff</h4>
                <br>
                <br>
                <p style="color:black;"><?php echo $sales[0]['created_by'] ?></p>
                <p style="color:black;font-family:'Grand-Hotel';margin:0"><?php echo $organization ?></p>
            </div>
        </div>

    </div>


</body>

</html>