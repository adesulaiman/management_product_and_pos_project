<?php
require "../../config.php";
require "../base/security_login.php";
require "../base/db.php";


if (isset($_SESSION['userid'])) {

    if (isset($_POST['id_stock']) && isset($_POST['iscommit'])) {
        if($_POST['iscommit']){

            $qRecapStock = $adeQ->select($adeQ->prepare("
            select 
            c.storage_name,
            sum((d.qty_phisycal - coalesce(s.qty_stock, 0)) + coalesce(d.qty_adjusment, 0)) as  gap_qty,
            sum(((d.qty_phisycal * coalesce(p.brutto_gram, 0) ) - coalesce(s.brutto_gram, 0)) + coalesce(d.brutto_gram_adjusment, 0)) as gap_gram
            from data_stock_opname_detail d
            left join data_product p on d.barcode=p.barcode
            left join data_category_storage c on d.id_category_storage = c.id
            left join data_stock_product s on d.id_category_storage = s.id_category_storage and d.barcode=s.barcode
            where d.id_stock_opname=%s
            group by c.storage_name
            ", $_POST['id_stock']));

        }else{

            $qRecapStock = $adeQ->select($adeQ->prepare("
            select 
            c.storage_name,
            sum((d.qty_phisycal - coalesce(d.qty_stock, 0)) + coalesce(d.qty_adjusment, 0)) as  gap_qty,
            sum(((d.brutto_gram_physycal ) - coalesce(d.brutto_gram_stock, 0)) + coalesce(d.brutto_gram_adjusment, 0)) as gap_gram
            from data_stock_opname_detail_report d
            left join data_category_storage c on d.id_category_storage = c.id
            where d.id_stock_opname=%s
            group by c.storage_name
            ", $_POST['id_stock']));

        }
        
        
        echo json_encode(["status" => "success", "data" => $qRecapStock]);
    }else{
        echo json_encode(["status" => "error", "data" => ""]);
    }
}
