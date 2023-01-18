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

        // Load Process
        switch ($type) {

            case 'group':
                $query = "SELECT * FROM data_category_storage WHERE is_delete = '0' ORDER BY storage_name ASC";

                $dewan1 = $db1->prepare($query);
                $dewan1->execute();
                $res1 = $dewan1->get_result();
                while ($row = $res1->fetch_assoc()) {
                    $data[$i]['id'] = $row['id'];
                    $data[$i]['storage_name'] = $row['storage_name'];
                    $data[$i]['is_active'] = $row['is_active'];
                    $data[$i]['is_delete'] = $row['is_delete'];
                    $data[$i]['created_by'] = $row['created_by'];
                    $data[$i]['created_date'] = $row['created_date'];
                    $i++;
                } 
            
                $out = array_values($data);
                echo json_encode($out);
                break;


            case 'groupDetail':

                $query = "SELECT 
                            data_product.product_name, 
                            data_stock_product.* 
                            FROM data_stock_product 
                            LEFT JOIN data_product ON 
                            data_stock_product.barcode = data_product.barcode 
                            WHERE id_category_storage = $keyId 
                            ORDER BY data_product.product_name ASC";
                
                    $dewan1 = $db1->prepare($query);
                    $dewan1->execute();
                    $res1 = $dewan1->get_result();
                    while ($row = $res1->fetch_assoc()) {
                        $data[$i]['product_name'] = $row['product_name'];
                        $data[$i]['id'] = $row['id'];
                        $data[$i]['barcode'] = $row['barcode'];
                        $data[$i]['id_category_storage'] = $row['id_category_storage'];
                        $data[$i]['qty_stock'] = $row['qty_stock'];
                        $data[$i]['gram'] = $row['gram'];
                        $data[$i]['stock_date'] = $row['stock_date'];
                        $data[$i]['created_by'] = $row['created_by'];
                        $data[$i]['created_date'] = $row['created_date'];
                        $i++;
                    }
                    
                    $out = array_values($data);
                    echo json_encode(['view' => $out]);
                break;
            
        }
    }
?>