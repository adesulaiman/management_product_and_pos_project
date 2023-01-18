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

        case 'update':

            $output = ''; 
            $idDetail =  mysqli_real_escape_string($connect, $_POST["idDetail"]);  
            $stOpnameId = mysqli_real_escape_string($connect, $_POST["id"]);  
            // $productId = mysqli_real_escape_string($connect, $_POST["productId"]);  NON UPDATE
            // $productName = mysqli_real_escape_string($connect, $_POST["productName"]);  NON UPDATE
            $qtyPhisycal = mysqli_real_escape_string($connect, $_POST["qtyPhisycal"]);
            $gramPhisycal = mysqli_real_escape_string($connect, $_POST["gramPhisycal"]);
            $qtyAdjs = mysqli_real_escape_string($connect, $_POST["qtyAdjs"]);
            $gramAdjs = mysqli_real_escape_string($connect, $_POST["gramAdjs"]);
            
        
            $query = 
                "UPDATE data_stock_opname_detail SET 
                    id_stock_opname = $stOpnameId, 
                    -- id product
                    -- poroduct_name
                    qty_phisycal = $qtyPhisycal, 
                    gram_physycal = $gramPhisycal, 
                    qty_adjusment = $qtyAdjs, 
                    gram_adjusment = $gramAdjs WHERE id=$idDetail
                ";
        
            if(mysqli_query($connect, $query))
            {
                $fl = 'success';
                $status = 'Product edited successfully';
            } else {
                $fl = 'error';
                $status = $output .= mysqli_error($connect);
            }
            echo $output;
            echo json_encode(['status' => $fl, 'text' => $status, 'page' => '']);
            break;


        case 'delete':
            
            $output = ''; 
            $idDetail =  mysqli_real_escape_string($connect, $_POST["idDetail"]); 
            
        
            $query = "DELETE FROM data_stock_opname_detail  WHERE id=$idDetail";
        
            if(mysqli_query($connect, $query))
            {
                $fl = 'success';
                $status = 'Product has been removed';
            } else {
                $fl = 'error';
                $status = $output .= mysqli_error($connect);
            }
            echo $output;
            echo json_encode(['status' => $fl, 'text' => $status, 'page' => '']);
            break;


        case 'addSo':
            $userId = $_SESSION["userid"];
        
            $output = ''; 
            $soInfo =  mysqli_real_escape_string($connect, $_POST["soInfo"]);
            
        
            $query = "INSERT INTO data_stock_opname VALUES ('', '$soInfo', now(), 'open', '$userId', now())";
        
            if(mysqli_query($connect, $query))
            {
                $fl = 'success';
                $status = 'Stock Opname successfully added';
            } else {
                $fl = 'error';
                $status = $output .= mysqli_error($connect);
            }
            echo $output;
            echo json_encode(['status' => $fl, 'text' => $status, 'page' => '']);
            break;

        
        case 'statusUpdt':
            $output = ''; 
            $idSt =  mysqli_real_escape_string($connect, $_POST["idSt"]);

            $query = "UPDATE data_stock_opname SET status_stock_opname = 'closed' WHERE id=$idSt";

            if(mysqli_query($connect, $query))
            {
                $fl = 'success';
                $status = 'Stock Opname successfully Closed';
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