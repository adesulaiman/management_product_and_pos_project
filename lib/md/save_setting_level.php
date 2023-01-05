<?php

require "../../config.php";
require "../base/db.php";
require "../base/security_login.php";

if (isset($_SESSION['userid'])) {
    if (isset($_POST['id']) and isset($_POST['level'])) {

        $q = "update core_user set id_level= $_POST[level] where id=$_POST[id]";
        $ins = $adeQ->query($q);

        if (!$ins) {
            $msg = 'Error Set Level !!';
            $stt = "error";
        }else{
            $msg = 'Level set successfully !!';
            $stt = "success";
        }
        echo json_encode(["status" => $stt, "info" => $msg]);
    } else {
        echo json_encode(["status" => "error", "info" => "please set level !!"]);
    }
}
