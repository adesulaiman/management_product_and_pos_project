<?php
require "../../config.php";
require "../base/db.php";

//cek if SO is commited
$cekSO = $adeQ->select($adeQ->prepare("select * from data_stock_opname where id=%s", $idstockopname));
$isCommit = 0;
if ($cekSO[0]['status_stock_opname'] == "" || $cekSO[0]['status_stock_opname'] == "open") {
    $isCommit = 1;
}

if ($isCommit) {
    $stockopname = $adeQ->select("
    select 
    p.id,
    p.id_stock_opname,
    p.barcode id_barcode,
    p.id_brutto_gram_product,
    p.storage_name,
    concat(p.barcode, ' - ', p.product_name) product,
    concat(p.qty_phisycal, ' pcs (',p.gram_phisycal,' Brutto Gr)') physical_stock,
    concat(p.qty_stock, ' pcs (',p.brutto_gram,' Brutto Gr)') system_stock,
    concat(p.qty_adjusment, ' pcs (',p.brutto_gram_adjusment,' Brutto Gr)') adjusment,
    case 
        when p.gap_qty < 0 then concat('<span style=\"color:red;font-size:17px\">',p.gap_qty, ' pcs (',p.gap_gram,' Gr)','</span>')
        when p.gap_qty = 0 then concat('<span style=\"color:blue;font-size:17px\">',p.gap_qty, ' pcs (',p.gap_gram,' Gr)','</span>')
        else concat('<span style=\"color:green;font-size:17px\">+ ',p.gap_qty, ' pcs (',p.gap_gram,' Gr)','</span>')
    end  difference
    from 
    (
        select 
        d.id,
        d.id_stock_opname,
        c.storage_name,
        d.barcode,
        d.product_name,
        d.qty_phisycal,
        p.netto_gram id_netto_gram_product,
        p.brutto_gram id_brutto_gram_product,
        d.qty_phisycal * coalesce(p.brutto_gram, 0) gram_phisycal,
        coalesce(s.qty_stock, 0) qty_stock,
        coalesce(s.brutto_gram, 0) brutto_gram,
        coalesce(d.qty_adjusment, 0) qty_adjusment,
        coalesce(d.qty_adjusment, 0) * coalesce(p.brutto_gram, 0) brutto_gram_adjusment,
        (d.qty_phisycal - coalesce(s.qty_stock, 0)) + coalesce(d.qty_adjusment, 0)  gap_qty,
        ((d.qty_phisycal * coalesce(p.brutto_gram, 0) ) - coalesce(s.brutto_gram, 0)) + (coalesce(d.qty_adjusment, 0) * coalesce(p.brutto_gram, 0)) gap_gram
        from data_stock_opname_detail d
        left join data_product p on d.barcode=p.barcode
        left join data_category_storage c on d.id_category_storage = c.id
        left join data_stock_product s on d.id_category_storage = s.id_category_storage and d.barcode=s.barcode
    ) p
    where p.id_stock_opname = $idstockopname
    order by storage_name
    ");
} else {
    $stockopname = $adeQ->select("
    select 
    p.id,
    id_stock_opname,
    p.barcode id_barcode,
    '' id_gram_product,
    p.storage_name,
    concat(p.barcode, ' - ', p.product_name) product,
    concat(p.qty_phisycal, ' pcs (',p.brutto_gram_physycal,' Brutto Gr)') physical_stock,
    concat(p.qty_stock, ' pcs (',p.brutto_gram_stock,' Brutto Gr)') system_stock,
    concat(p.qty_adjusment, ' pcs (',p.brutto_gram_adjusment,' Brutto Gr)') adjusment,
    case 
        when p.qty_diff < 0 then concat('<span style=\"color:red;font-size:17px\">',p.qty_diff, ' pcs (',p.gram_diff,' Gr)','</span>')
        when p.qty_diff = 0 then concat('<span style=\"color:blue;font-size:17px\">',p.qty_diff, ' pcs (',p.gram_diff,' Gr)','</span>')
        else concat('<span style=\"color:green;font-size:17px\">+ ',p.qty_diff, ' pcs (',p.gram_diff,' Gr)','</span>')
    end  difference
    from 
    (
        select 
        d.id,
        c.storage_name,
        d.id_stock_opname,
        d.id_category_storage,
        d.barcode,
        d.product_name,
        d.qty_phisycal,
        d.brutto_gram_physycal,
        d.qty_stock,
        d.brutto_gram_stock,
        d.qty_adjusment,
        d.brutto_gram_adjusment,
        d.qty_diff,
        d.brutto_gram_diff gram_diff,
        d.created_by,
        d.created_date
        from data_stock_opname_detail_report d
        left join data_category_storage c on d.id_category_storage = c.id
    ) p
    where p.id_stock_opname = $idstockopname
    order by storage_name
    ");
}


$receiveDate = date("d F Y", strtotime($cekSO[0]['stock_opname_time']));
$receiveby = $cekSO[0]['created_by'];
$status_receive = $cekSO[0]['status_stock_opname'];
$no_invoice = $cekSO[0]['stock_opname_info'];
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
            <h3>Report Stock Opname</h3>
        </div>
    </div>

    <div class="row">
        <div class="col-xs-4">
            <table class="table tablenoborder">
                <tr>
                    <td>Stock Opname Info </td>
                    <td>: <?php echo $no_invoice ?></td>
                </tr>
                <tr>
                    <td>Stock Opname Date</td>
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
                    <td>Product</td>
                    <td>Physical Stock</td>
                    <td>System Stock</td>
                    <td>Adjusment</td>
                    <td>Difference</td>
                </tr>
            </thead>
            <tbody>
                <?php
                $i = 1;
                $total = 0;
                $boxlast = "";
                foreach ($stockopname as $rec) {
                    $boxfirst = $rec["storage_name"];

                    if ($boxfirst != $boxlast) {
                        $i = 1;
                        echo "
                        <tr style='font-size:20px'>
                            <td colspan='6'>Storage : <b>$rec[storage_name]</b></td>
                        </tr>
                        ";
                    }

                    echo "
                        <tr>
                            <td>$i</td>
                            <td>$rec[product]</td>
                            <td>$rec[physical_stock]</td>
                            <td>$rec[system_stock]</td>
                            <td>$rec[adjusment]</td>
                            <td>$rec[difference]</td>
                        </tr>
                    ";

                    $boxlast = $rec["storage_name"];
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