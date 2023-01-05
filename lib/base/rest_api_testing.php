<?php
require "../../config.php";
// require "../base/security_login.php";
require "../base/db.php";

$curl = curl_init();
$url = "https://sampangkab.go.id/gpr-custum/";

// OPTIONS:
curl_setopt($curl, CURLOPT_URL, $url);
// curl_setopt($curl, CURLOPT_HTTPHEADER, $getHeader);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
// EXECUTE:
$result = curl_exec($curl);
if (curl_errno($curl)) {
    $error_msg = curl_error($curl);
    echo $error_msg;
}
// if(!$result){die("Connection Failure");}
echo $result;
curl_close($curl);

?>

