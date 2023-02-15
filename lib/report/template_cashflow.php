<?php
require "../../config.php";
require "../base/db.php";

// $tanggal = '2023-02-11';
// $bulan= 'January 2023';

$cost = $adeQ->select("
    select c.cost_date, c.opening_cash, d.nominal, d.information from  data_cost_daily c 
    inner join data_cost_daily_detail d on c.id=d.id_cost_daily
    where c.cost_date = '$tanggal'
");

$sales = $adeQ->select("
    select 
    s.no_struk,
    (select group_concat(distinct case when barcode = '999999' then 'Gold Repair' else 'Gold Sales' end ) from data_sales_detail where no_struk = s.no_struk ) info,
    s.payment_cash
    from  data_sales s
    where CAST(s.sales_date AS DATE) = '$tanggal'
    and payment_method like '%Cash%'
");


$salesnontunai = $adeQ->select("
    select 
    s.no_struk,
    (select group_concat(distinct case when barcode = '999999' then 'Gold Repair' else 'Gold Sales' end ) from data_sales_detail where no_struk = s.no_struk ) info,
    s.payment_trasnfer + s.payment_debit + s.payment_credit + s.payment_dp nominal
    from  data_sales s
    where CAST(s.sales_date AS DATE) = '$tanggal'
    and (payment_method like '%Debit%' or payment_method like '%Trasnfer%' or payment_method like '%Credit%' or payment_method like '%DP%')
");

$installmentnontunai = $adeQ->select("
    select invoice, 'Installment Reseller' info, nominal from  data_resaller_payment 
    where payment_date = '$tanggal'
    and method not in ('Cash', 'Emas Murni')
");

$installment = $adeQ->select("
    select invoice, 'Installment Reseller' info, nominal from  data_resaller_payment 
    where payment_date = '$tanggal'
    and method = 'Cash'
");

$buygold = $adeQ->select("
    select concat(r.no_invoice, ' - ', d.barcode,' - ', d.product_name) invoice, r.type_receive, sum(d.total_price) price from  data_receive r 
    inner join data_detail_receive d on r.id=d.id_receive
    where r.type_receive='Buy Gold'
    and r.receive_date = '$tanggal'
    group by r.no_invoice, r.type_receive
");

$openingcash = $cost[0]['opening_cash'];
$runningcash = $openingcash;
$cashin = 0;
$cashout = 0;
$cashless = 0;




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
            <h3>Cost Recapitulation Report </h3>
            <h5>Period <?php echo date("d F Y", strtotime($tanggal)) ?></h5>
        </div>
    </div>

    <div class="row" style="margin-top:30px">
        <h4>Cash Flow</h4>
        <hr>
        <table class="table table-bordered">
            <thead>
                <tr style="background: grey;color:white">
                    <th>Invoice / Bill</th>
                    <th>Information</th>
                    <th>Nominal</th>
                    <th>Running Total</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td colspan="2"><b>Opening Cash</b></td>
                    <td colspan="2" class="text-right"><b>Rp <?php echo number_format($openingcash) ?></b></td>
                </tr>
                <tr style="background:#e0e0e0">
                    <td colspan="4" class="text-center">CASH IN</td>
                </tr>
                <?php
                foreach ($sales as $valsales) {
                    $runningcash += $valsales["payment_cash"];
                    $cashin += $valsales["payment_cash"];
                    echo "
                    <tr>
                        <td>$valsales[no_struk]</td>
                        <td>$valsales[info]</td>
                        <td class='text-right'>Rp " . number_format($valsales["payment_cash"]) . "</td>
                        <td class='text-right'>Rp " . number_format($runningcash) . "</td>
                    </tr>
                    ";
                }

                foreach ($installment as $ins) {
                    $runningcash += $ins["nominal"];
                    $cashin += $ins["nominal"];
                    echo "
                    <tr>
                        <td>$ins[invoice]</td>
                        <td>$ins[info]</td>
                        <td class='text-right'>Rp " . number_format($ins["nominal"]) . "</td>
                        <td class='text-right'>Rp " . number_format($runningcash) . "</td>
                    </tr>
                    ";
                }
                ?>
                <tr>
                    <td><b>TOTAL CASH IN</b></td>
                    <td colspan="2" class="text-right"><b>Rp <?php echo number_format($cashin) ?></b></td>
                    <td></td>
                </tr>

                <tr style="background:#e0e0e0">
                    <td colspan="4" class="text-center">CASH OUT</td>
                </tr>

                <?php
                foreach ($buygold as $buy) {
                    $runningcash -= $buy["price"];
                    $cashout += $buy["price"];
                    echo "
                    <tr>
                        <td>$buy[invoice]</td>
                        <td>$buy[type_receive]</td>
                        <td class='text-right'>Rp " . number_format($buy["price"]) . "</td>
                        <td class='text-right'>Rp " . number_format($runningcash) . "</td>
                    </tr>
                    ";
                }

                foreach ($cost as $val) {
                    $runningcash -= $val["nominal"];
                    $cashout += $val["nominal"];
                    echo "
                    <tr>
                        <td>Cost Operational</td>
                        <td>$val[information]</td>
                        <td class='text-right'>Rp " . number_format($val["nominal"]) . "</td>
                        <td class='text-right'>Rp " . number_format($runningcash) . "</td>
                    </tr>
                    ";
                }

                ?>
                <tr>
                    <td><b>TOTAL CASH OUT</b></td>
                    <td colspan="2" class="text-right"><b>Rp <?php echo number_format($cashout) ?></b></td>
                    <td></td>
                </tr>

                <tr>
                    <td colspan="2"><b>CASH IN - CASH OUT</b></td>
                    <td colspan="2" class="text-right" style="font-size:20px"><b>Rp <?php echo number_format($runningcash) ?></b></td>
                </tr>

            </tbody>
        </table>
        <br>
        <br>

        <h4>Cashless Flow</h4>
        <hr>
        <table class="table table-bordered">
            <thead>
                <tr style="background: grey;color:white">
                    <th>Invoice / Bill</th>
                    <th>Information</th>
                    <th>Nominal</th>
                </tr>
            </thead>
            <tbody>
                <?php

                foreach ($salesnontunai as $val) {
                    $cashless += $val["nominal"];
                    echo "
                    <tr>
                        <td>$buy[invoice]</td>
                        <td>$val[info]</td>
                        <td class='text-right'>Rp " . number_format($val["nominal"]) . "</td>
                    </tr>
                    ";
                }
                
                foreach ($installmentnontunai as $val) {
                    $cashless += $val["nominal"];
                    echo "
                    <tr>
                        <td>$buy[invoice]</td>
                        <td>$val[info]</td>
                        <td class='text-right'>Rp " . number_format($val["nominal"]) . "</td>
                    </tr>
                    ";
                }
                ?>
                <tr>
                    <td colspan="2"><b>TOTAL</b></td>
                    <td class="text-right" style="font-size:20px"><b>Rp <?php echo number_format($cashless) ?></b></td>
                </tr>

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