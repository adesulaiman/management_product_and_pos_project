<?php
require "../../config.php";
require "security_login.php";
require "db.php";


if(isset($_SESSION['userid']))
{

	if(isset($_POST['barcode'])){
		
		$barcode = $_POST['barcode'];

		if(isset($_POST['mode'])){
			$mode = $_POST['mode'];
			if($mode == 'stockopname'){
				$data = $adeQ->select($adeQ->prepare("select d.* 
				from data_product d where d.barcode = %s ", $barcode));
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
			}else{
				echo json_encode([
					"status" => "error",
					"info" => "Mode not define",
					"product" => ""
				]);
			}

		}else{
			$data = $adeQ->select($adeQ->prepare("select d.*, concat(s.id_category_storage, '-', d.barcode) uniq_barcode , c.storage_name
			from data_product d inner join data_stock_product s on d.barcode = s.barcode  
			inner join data_category_storage c on s.id_category_storage=c.id
			where d.barcode = %s and d.is_delete = 0", $barcode));
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
		

		
	}
} // close session

?>