<?php

    require "../../config.php";
    require "../base/db.php";
    require "../base/security_login.php";
    
    define('HOST',$dbHost);
    define('USER',$dbUser);
    define('PASS',$dbPassword);
    define('DB1', $dbName);

    if (isset($_POST["keyId"]) and isset($_POST["type"])) {
        $keyId = $_POST["keyId"];
        $type = $_POST["type"];

        $db1 = new mysqli(HOST, USER, PASS, DB1);

        $i = 1;

        switch ($type) {
            case 'group':
                $query = "SELECT 
                            data_stock_opname.status_stock_opname, 
                            data_stock_opname.stock_opname_info,
                            data_stock_opname_detail.* FROM data_stock_opname_detail 
                            LEFT JOIN data_stock_opname ON 
                            data_stock_opname_detail.id_stock_opname = data_stock_opname.id 
                            WHERE data_stock_opname_detail.id_stock_opname=$keyId ORDER BY product_name ASC";
                break;
            
            case 'single':
                $idSo = mysqli_real_escape_string($db1, $_POST["idSo"]);
                $query = "SELECT 
                            data_stock_opname.status_stock_opname,
                            data_stock_opname.stock_opname_info,
                            data_stock_opname_detail.* FROM data_stock_opname_detail 
                            LEFT JOIN data_stock_opname ON 
                            data_stock_opname_detail.id_stock_opname = data_stock_opname.id 
                            WHERE data_stock_opname_detail.id_product = $keyId AND 
                            data_stock_opname_detail.id_stock_opname = $idSo";
                break;
        }

        // $query = "SELECT * FROM data_stock_opname_detail WHERE id_stock_opname=$keyId ORDER BY product_name DESC";
        $dewan1 = $db1->prepare($query);
        $dewan1->execute();
        $res1 = $dewan1->get_result();
        while ($row = $res1->fetch_assoc()) {
            $data[$i]['stock_opname_info'] = $row['stock_opname_info'];
            $data[$i]['status_stock_opname'] = $row['status_stock_opname'];
            $data[$i]['id'] = $row['id'];
            $data[$i]['id_stock_opname'] = $row['id_stock_opname'];
            $data[$i]['id_product'] = $row['id_product'];
            $data[$i]['product_name'] = $row['product_name'];
            $data[$i]['qty_phisycal'] = $row['qty_phisycal'];
            $data[$i]['gram_physycal'] = $row['gram_physycal'];
            $data[$i]['qty_adjusment'] = $row['qty_adjusment'];
            $data[$i]['gram_adjusment'] = $row['gram_adjusment'];
            $data[$i]['created_by'] = $row['created_by'];
            $data[$i]['created_date'] = $row['created_date'];
            $i++;
        } 
    
        $out = array_values($data);
        echo json_encode(['view' => $out]);
    }
?>