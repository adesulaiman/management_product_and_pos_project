<?php
require "../../config.php";
require "../base/security_login.php";
require "../base/db.php";


if (isset($_SESSION['userid'])) {

    if (!empty($_POST['id_receive']) and !empty($_POST['id_storage'])) {
        
        $updStock = $adeQ->query(
            "UPDATE data_stock_product s inner join 
            (
                select r.barcode, sum(qty_receive) qty, sum(r.qty_receive * p.gram) gram from data_detail_receive r
                inner join data_product p on r.barcode=p.barcode
                where r.id_receive=$_POST[id_receive]
                group by r.barcode
            ) as d
            ON s.barcode = d.barcode and s.id_category_storage = $_POST[id_storage]
            SET s.qty_stock = s.qty_stock + d.qty, s.gram = s.gram + d.gram, s.stock_date = '".date("Y-m-d H:i:s")."', created_by = 'Receive'"
        );

        $insStock = $adeQ->query(
            "insert data_stock_product (barcode, id_category_storage, qty_stock, gram, stock_date, created_by)
            select d.barcode, $_POST[id_storage], d.qty, d.gram, '".date("Y-m-d H:i:s")."', 'Receive' from 
            (
                select r.barcode, sum(qty_receive) qty, sum(r.qty_receive * p.gram) gram from data_detail_receive r
                inner join data_product p on r.barcode=p.barcode
                where r.id_receive=$_POST[id_receive]
                group by r.barcode
            ) as d
            left join data_stock_product s on d.barcode=s.barcode
            and s.id_category_storage=$_POST[id_storage]
            where s.barcode is null"
        );

        if($updStock and $insStock){

            //get product
            $updInfoReceive = $adeQ->query(
                "UPDATE data_detail_receive s inner join data_product d
                ON s.barcode = d.barcode 
                SET s.product_name = concat(d.product_name, ' (',d.kadar_product,' karat)')
                where s.id_receive = $_POST[id_receive] "
            );

            //get storage
            $storage = $adeQ->select("select * from data_category_storage where id=$_POST[id_storage]");

            $updReceive = $adeQ->query("update data_receive set status_receive='received' where id = $_POST[id_receive]");

            $updateStorage = $adeQ->query(
                "update data_detail_receive set id_storage='".$storage[0]['id']."' , storage_name='".$storage[0]['storage_name']."'
                 where id_receive = $_POST[id_receive] "
            );


            if($updReceive and $updInfoReceive and $updateStorage){
                echo json_encode(["status" => "success", "info" => "Success Receive Product to System"]);
            }else{
                echo json_encode(["status" => "error", "info" => "Error update status receive"]);
            }
            
        }else{
            echo json_encode(["status" => "error", "info" => "Error update stock"]);
        }
        
    }else{
        echo json_encode(["status" => "error", "data" => ""]);
    }
}
