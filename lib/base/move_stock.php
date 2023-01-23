<?php
require "../../config.php";
require "../base/security_login.php";
require "../base/db.php";


if(isset($_SESSION['userid']))
{
	if(
		!empty($_POST['qtymove']) &&
		!empty($_POST['id']) &&
		!empty($_POST['barcode']) &&
		!empty($_POST['id_storage'])
	){

		// check storage
		$stock = $adeQ->select($adeQ->prepare("select * from data_stock_product where id=%s", $_POST['id']));
		if($_POST['qtymove'] > $stock[0]['qty_stock'])
		{
			echo json_encode([
				"status" => "error",
				"info" => "Qty move grether then qty stock !!"
			]);
			exit;
		}else{

			//getdetail product
			$product = $adeQ->select($adeQ->prepare("select * from data_product where barcode=%s", $_POST['barcode']));

			//check update or insert data
			$cekData = $adeQ->select("select count(1) as cek from data_stock_product 
			where id_category_storage=$_POST[id_storage]
			and barcode='$_POST[barcode]'
			");

			
			if($cekData[0]['cek'] > 0){
				$move = $adeQ->query("update data_stock_product set 
				qty_stock= qty_stock + $_POST[qtymove] , 
				created_by = 'Movement',
				gram= gram + ($_POST[qtymove] * ".$product[0]['gram'].")  
				where id_category_storage=$_POST[id_storage]
				and barcode='$_POST[barcode]'");
			}else{
				$move = $adeQ->query("insert into data_stock_product 
				(barcode, id_category_storage, qty_stock, gram, stock_date, created_by, created_date) VALUES
				('$_POST[barcode]', $_POST[id_storage], $_POST[qtymove], ".$_POST['qtymove'] * $product[0]['gram'].", '".date("Y-m-d H:i:s")."','Movement', '".date("Y-m-d H:i:s")."' )");
			}

			if($move){
				$updStock = $adeQ->query(
					"update data_stock_product set 
					qty_stock= qty_stock - $_POST[qtymove] , 
					created_by = 'Movement',
					gram= gram - ($_POST[qtymove] * ".$product[0]['gram'].")  
					where id=$_POST[id]"
				);

				if($updStock){
					echo json_encode([
						"status" => "success",
						"info" => "Success Move Product !!"
					]);
				}else{
					echo json_encode([
						"status" => "error",
						"info" => "Error move product !!"
					]);
				}
			}else{
				echo json_encode([
					"status" => "error",
					"info" => "Error move product !!"
				]);
			}
			
		}

	}else{
		echo json_encode([
			"status" => "error",
			"info" => "Please fill data !!"
		]);
	}
}// close session
