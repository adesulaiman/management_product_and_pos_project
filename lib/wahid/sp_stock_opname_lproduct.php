<?php
    require "../../config.php";
    require "../base/db.php";
    require "../base/security_login.php";

    $productId = $_POST["productId"];
    $con = mysqli_connect($dbHost, $dbUser, $dbPassword, $dbName);
    $query = mysqli_query($con, "SELECT product_name FROM data_product WHERE id=$productId");


    // Show Product by ID
    while ($prd = mysqli_fetch_assoc($query)){
        echo $prd["product_name"];
    }




