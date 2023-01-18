<?php

    require "../../config.php";
    require "../base/db.php";
    require "../base/security_login.php";

    define('HOST',$dbHost);
    define('USER',$dbUser);
    define('PASS',$dbPassword);
    define('DB1', $dbName);
    
    // Buat Koneksinya
    $db1 = new mysqli(HOST, USER, PASS, DB1);
 
    $i = 1;
    $query = "SELECT * FROM data_stock_opname ORDER BY stock_opname_time DESC";
    $dewan1 = $db1->prepare($query);
    $dewan1->execute();
    $res1 = $dewan1->get_result();
    while ($row = $res1->fetch_assoc()) {
        $data[$i]['id'] = $row['id'];
        $data[$i]['stock_opname_info'] = $row['stock_opname_info'];
        $data[$i]['stock_opname_time'] = $row['stock_opname_time'];
        $data[$i]['status_stock_opname'] = $row['status_stock_opname'];
        $data[$i]['created_by'] = $row['created_by'];
        $data[$i]['create_date'] = $row['created_date'];
 
        $i++;
	} 
 
    $out = array_values($data);
    echo json_encode($out);
 
?>