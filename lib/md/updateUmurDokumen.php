<?php

require "../../config.php";
require "../base/db.php";
require "../base/security_login.php";

if (isset($_SESSION['userid'])) {
    if (isset($_POST['id']) and isset($_POST['umur'])) {

        $umur = $_POST['umur'] == null ? 'NULL' : $_POST['umur'];

        $q = "update data_dokumen_tte set umur_dokumen = $umur, update_by='$_SESSION[userid]', update_date=now()
        where id = (select distinct id_flow_dokumen from data_dokumen_forward_tte where id=$_POST[id])";
        
        $upd = $adeQ->query($q);

        if (!$upd) {
            $msg = 'Error update masa berlaku dokumen !!';
            $stt = "error";
        }else{
            $msg = 'Masa berlaku dokumen berhasil di perbarui !!';
            $stt = "success";
        }
        echo json_encode(["status" => $stt, "msg" => $msg]);
    } else {
        echo json_encode(["status" => "error", "info" => "please set level !!"]);
    }
}
