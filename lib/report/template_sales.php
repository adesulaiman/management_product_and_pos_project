<?php
require "../../config.php";
require "../base/db.php";

// $idbulan= '012023';
// $bulan= 'January 2023';

$sales = $adeQ->select("
select 
d.no_struk,
format((d.payment_cash + d.payment_trasnfer + d.payment_debit + d.payment_credit + d.payment_dp),2) total_sales,
concat(s.barcode, ' - ', s.product_name) product,
format(s.qty,2) qty, 
format(s.qty * s.gram,2) gram,
format(s.qty * s.price, 2) sales,
s.sales_date,
s.created_by cashier
from data_sales_detail s left join 
data_sales d  on d.no_struk=s.no_struk
where DATE_FORMAT(d.sales_date, \"%m%Y\") = '$idbulan'
order by d.no_struk, s.sales_date desc
");


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Report Sales <?php echo $bulan ?></title>



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
            <h3>Report Sales Period <?php echo $bulan?></h3>
        </div>
    </div>

    <div class="row" style="margin-top:30px">
        <table class="table table-bordered">
            <thead >
                <tr style="background:grey;color:white">
                    <td><b>Product</b></td>
                    <td><b>Qty</b></td>
                    <td><b>Gram</b></td>
                    <td><b>Sales</b></td>
                    <td><b>Sales Date</b></td>
                    <td><b>Cashier</b></td>
                </tr>
            </thead>
            <tbody>
                <?php
                $i = 1;
                $boxlast = "";
                foreach ($sales as $sel) {
                    $boxfirst = $sel["no_struk"];

                    if ($boxfirst != $boxlast) {
                        $i = 1;
                        echo "
                        <tr style='font-size:18px'>
                            <td colspan='6'>Bill No : $sel[no_struk] <p style='float:right'>Total : Rp $sel[total_sales]</p></td>
                        </tr>
                        ";
                    }

                    echo "
                        <tr>
                            <td>$sel[product]</td>
                            <td>$sel[qty]</td>
                            <td>$sel[gram]</td>
                            <td>$sel[sales]</td>
                            <td>$sel[sales_date]</td>
                            <td>$sel[cashier]</td>
                        </tr>
                    ";

                    $boxlast = $sel["no_struk"];
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