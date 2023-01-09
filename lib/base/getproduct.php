<?php
require "../../config.php";
require "security_login.php";
require "db.php";


if(isset($_SESSION['userid']))
{

	if(isset($_POST['barcode'])){
		$barcode = $_POST['barcode'];

		$data = $adeQ->select($adeQ->prepare("select d.* from data_product d inner join data_stock_product s on d.barcode = s.barcode  where d.barcode = %s", $barcode));
		if(!empty($data)){
			echo json_encode([
				"status" => "success",
				"info" => "Product Added",
				"product" => $data
			]);
		}else{
			echo json_encode([
				"status" => "error",
				"info" => "Product out of stock or not found !!",
				"product" => ""
			]);
		}
	}
} // close session

?>