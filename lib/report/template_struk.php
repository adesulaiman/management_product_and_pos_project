<?php
require "../../config.php";
require "../base/db.php";


$sales_detail = $adeQ->select("
select 
concat(barcode, ' - ', product_name) product,
format(qty, 2 ) qty,
format(qty * gram, 2) gram,
format(qty * price, 2) price
from data_sales_detail
where no_struk='$struk'
");

$sales = $adeQ->select("
select 
(payment_cash + payment_trasnfer + payment_debit + payment_credit + payment_dp) total_sales,
d.*
from data_sales d
where no_struk='$struk'
");

$container = "";

if ($sales[0]['payment_cash'] > 0) {
    $container .= "
        <tr>
            <td>Cash </td>
            <td>Rp " . number_format($sales[0]['payment_cash'], 2) . "</td>
        </tr>
    ";
}


if ($sales[0]['payment_trasnfer'] > 0) {
    $container .= "
        <tr>
            <td>Transfer </td>
            <td>Rp " . number_format($sales[0]['payment_trasnfer'], 2) . "</td>
        </tr>
    ";
}

if ($sales[0]['payment_debit'] > 0) {
    $container .= "
        <tr>
            <td>Debit </td>
            <td>Rp " . number_format($sales[0]['payment_debit'], 2) . "</td>
        </tr>
    ";
}

if ($sales[0]['payment_credit'] > 0) {
    $container .= "
        <tr>
            <td>Credit </td>
            <td>Rp " . number_format($sales[0]['payment_credit'], 2) . "</td>
        </tr>
    ";
}

if ($sales[0]['payment_dp'] > 0) {
    $container .= "
        <tr>
            <td>DP </td>
            <td>Rp " . number_format($sales[0]['payment_dp'], 2) . "</td>
        </tr>
    ";
}

$container .= "
    <tr style='font-size:20px'>
        <td>Change </td>
        <td>Rp " . number_format($sales[0]['change_payment'], 2) . "</td>
    </tr>
";

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
            <h3>Bill No <?php echo $struk ?></h3>
        </div>
    </div>

    <div class="row" style="margin-top:30px">
        <table class="table table-bordered">
            <thead>
                <tr style="background:grey;color:white">
                    <td><b>Product</b></td>
                    <td><b>Qty</b></td>
                    <td><b>Gram</b></td>
                    <td><b>Price</b></td>
                </tr>
            </thead>
            <tbody>
                <?php
                $i = 1;
                foreach ($sales_detail as $sel) {


                    echo "
                        <tr>
                            <td>$sel[product]</td>
                            <td>$sel[qty]</td>
                            <td>$sel[gram]</td>
                            <td class='text-right'>$sel[price]</td>
                        </tr>
                    ";
                }
                ?>
                <tr style="font-size:20px">
                    <td colspan="3"><b>Total</b></td>
                    <td colspan="1" class="text-right"><b>Rp <?php echo number_format($sales[0]['total_amount'], 2) ?></b></td>
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
            <div class="col-xs-5">
                <table class="table tablenoborder text-right" style="font-size:18px">
                    <?php echo $container ?>
                </table>
            </div>
        </div>

    </div>


</body>

</html>