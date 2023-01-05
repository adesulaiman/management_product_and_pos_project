<?php
require "../../config.php";
require "security_login.php";
require "db.php";


if(isset($_SESSION['userid']))
{
    if(isset($_POST['id']))
  	{
        $id = $_POST['id'];
        $cekStatusCurr = $adeQ->select(
            $adeQ->prepare("select status_active from data_repo_api where id=%d", $id));
        
        $switchStatus = $cekStatusCurr[0]['status_active'] == 0 ? 1 : 0;

        $qUpdate = $adeQ->query($adeQ->prepare("update data_repo_api set status_active=%d where id=%d", $switchStatus, $id));
        if($qUpdate){
            $statusMsg = $switchStatus == 1 ? "success" : "error";
            $msg = $switchStatus == 1 ? "API Telah Di Aktifasi" : "API Telah Di Non Aktfikan";
        }else{
            $statusMsg = "error";
            $msg = "System Error";
        }

        echo json_encode(["status" => $statusMsg, "massage" => $msg]);
        
    }
}


?>