<?php
require "../../config.php";
require "../base/security_login.php";
require "../base/db.php";


if (isset($_SESSION['userid'])) {

    if(isset($_POST['id_receive']))
    {
        $qRecapStock = $adeQ->select($adeQ->prepare("
        select format(sum(total_price),2) total from data_detail_receive where id_receive=%s", $_POST['id_receive']));
    
        echo json_encode(["status" => "success", "data" => $qRecapStock]);
    }else{
        echo json_encode(["status" => "error", "data" => null, "info" => "Error id receive not set !!"]);
    }
    
}