<?php

require "../../config.php";
require "../base/db.php";
require "../base/security_login.php";


define('HOST',$dbHost);
define('USER',$dbUser);
define('PASS',$dbPassword);
define('DB1', $dbName);

$connect = new mysqli(HOST, USER, PASS, DB1);
$userId = $_SESSION["userid"];

if(!empty($_POST))
{
    $output = '';
    $stOpnameId = mysqli_real_escape_string($connect, $_POST["id"]);  
    $productId = mysqli_real_escape_string($connect, $_POST["productId"]);  
    $productName = mysqli_real_escape_string($connect, $_POST["productName"]);  
    $qtyPhisycal = mysqli_real_escape_string($connect, $_POST["qtyPhisycal"]);
    $gramPhisycal = mysqli_real_escape_string($connect, $_POST["gramPhisycal"]);
    $qtyAdjs = mysqli_real_escape_string($connect, $_POST["qtyAdjs"]);
    $gramAdjs = mysqli_real_escape_string($connect, $_POST["gramAdjs"]);
    

    $query = 
    "INSERT INTO data_stock_opname_detail 
    VALUES('', $stOpnameId, $productId, '$productName', $qtyPhisycal, $gramPhisycal, $qtyAdjs, $gramAdjs, '$userId', now())
    ";

    if(mysqli_query($connect, $query))
    {
        $fl = 'success';
        $status = 'Input Product Success';

    } else {
        $output .= mysqli_error($connect);
    }
    echo $output;
    echo json_encode(['status' => $fl, 'text' => $status, 'page' => '']);
    
}
?>