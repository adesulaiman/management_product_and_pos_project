<?php

    require "../../config.php";
    require "../base/db.php";
    require "../base/security_login.php";


    define('HOST',$dbHost);
    define('USER',$dbUser);
    define('PASS',$dbPassword);
    define('DB1', $dbName);
  
    $connect = new mysqli(HOST, USER, PASS, DB1);

    if(isset($_POST["action"])) {
        $action = $_POST["action"];

        switch ($action) {

            case 'add':
                $userId = $_SESSION["userid"];

                $output = '';
                $barcode = mysqli_real_escape_string($connect, $_POST["barcode"]);  
                $idStorage = mysqli_real_escape_string($connect, $_POST["storage"]);  
                $qtyStock = mysqli_real_escape_string($connect, $_POST["qtyStock"]);  
                $gram = mysqli_real_escape_string($connect, $_POST["gram"]); 

                $query = "INSERT INTO data_stock_product VALUES ('', '$barcode', $idStorage, $qtyStock, $gram, now(), '$userId', now())";

                if(mysqli_query($connect, $query))
                {
                    $fl = 'success';
                    $status = 'Stock Product successfully added';
                } else {
                    $fl = 'error';
                    $status = $output .= mysqli_error($connect);
                }
                
                echo $output;
                echo json_encode(['status' => $fl, 'text' => $status, 'page' => '']);
                break;
            
        }


  }



?>