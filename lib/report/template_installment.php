<?php
require "../../config.php";
require "../base/db.php";


$sales_detail = $adeQ->select("
select * from 
(
    with base as (
        select `id` AS `id`,`invoice` AS `invoice`,
        case 
            when d.method = 'Emas Murni' then format(`d`.`nominal`,3) 
            else format(`d`.`nominal`,0) end 
        AS `nominal`,
        payment_date,
        method,
        bank,
        format(`gram_gold_price`,2) AS `gram_gold_price`,
        (select sum(emas_murni) from data_product_out_resaller_detail where invoice =d.invoice) total_emas_murni,
        case 
            when d.method = 'Emas Murni' then `d`.`nominal` 
            else nominal / gram_gold_price end 
        as kadar
        from `data_resaller_payment` d
    )
    select id, invoice,payment_date, 
    format(total_emas_murni - sum(kadar) over (order by id), 3),
    nominal, concat(method,  coalesce(concat(' - ', bank), '')) method, gram_gold_price, format(kadar, 3) kadar,
    case when method = 'Emas Murni' 
        then concat('<span style=\"font-size:20px; color:green;font-weight:bolder\">', format(`base`.`total_emas_murni` - sum(`base`.`nominal`) over ( order by `base`.`id`),3),'</span>')
        else 
            concat('<span style=\"font-size:20px; color:green;font-weight:bolder\">', format(`base`.`total_emas_murni` - sum(`base`.`kadar`) over ( order by `base`.`id`),3),'</span>')
        end
    AS `reminder_rate_gold` 
    from base where invoice='$struk'
) t
");

$sales = $adeQ->select("
select 
d.*,
r.nama
from data_product_out_resaller d
left join data_resaller r on d.id_resaller=r.id
where invoice='$struk'
");


$reseller_detail = $adeQ->select("
select 
sum(emas_murni) total_kadar
from data_product_out_resaller_detail d
where invoice='$struk'
");

$invoice = $sales[0]['invoice'];
$invoicedate = date("d F Y", strtotime($sales[0]['created_date']));
$installment = $sales[0]['status_payment'];

$kadar = 0;

$i = 1;
$total = 0;
$valtables = "";
foreach ($sales_detail as $sel) {


    $valtables .= "
        <tr>
            <td>$sel[payment_date]</td>
            <td>$sel[method]</td>
            <td>$sel[nominal]</td>
            <td>$sel[gram_gold_price]</td>
            <td>$sel[kadar]</td>
            <td class='text-right'>$sel[reminder_rate_gold]</td>
        </tr>
    ";
    $kadar += $sel["kadar"];
}

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
            <h3>Invoice Installment Reseller</h3>
        </div>
    </div>

    <div class="row">
        <div class="col-xs-4">
            <table class="table tablenoborder">
                <tr>
                    <td>Invoice No </td>
                    <td>: <?php echo $invoice?></td>
                </tr>
                <tr>
                    <td>Invoice Date</td>
                    <td>: <?php echo $invoicedate?></td>
                </tr>
            </table>
        </div>

        <div class="col-xs-1"></div>

        <div class="col-xs-4">
            <table class="table tablenoborder">
                <tr>
                    <td>Total Rate Gold</td>
                    <td>: <?php echo number_format($reseller_detail[0]['total_kadar'], 3)?> Gr</td>
                </tr>
                <tr>
                    <td>Reseller</td>
                    <td>: <?php echo $sales[0]['nama'] ?></td>
                </tr>
                <tr>
                    <td>Status</td>
                    <td>: <?php echo $installment?></td>
                </tr>
            </table>
        </div>
    </div>

    <div class="row" style="margin-top:30px">
        <table class="table table-bordered">
            <thead>
                <tr style="background:grey;color:white">
                    <td><b>Payment Date</b></td>
                    <td><b>Method</b></td>
                    <td><b>Nominal</b></td>
                    <td><b>Gram Gold Price</b></td>
                    <td><b>Kadar</b></td>
                    <td><b>Reminder Rate Gold</b></td>
                </tr>
            </thead>
            <tbody>
                <?php
                echo $valtables;
                ?>
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