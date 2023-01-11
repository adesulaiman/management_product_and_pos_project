<?php
require "../../config.php";
require "security_login.php";
require "db.php";


if(isset($_SESSION['userid']))
{

	if(isset($_POST['nostruk'])){
		$nostruk = $_POST['nostruk'];
		$sales = $adeQ->select($adeQ->prepare("select * from data_sales where no_struk=%s", $nostruk));
		$detailsales = $adeQ->select($adeQ->prepare("select d.*, c.storage_name from data_sales_detail d 
		left join data_category_storage c on d.id_category_storage=c.id where no_struk=%s", $nostruk));

		if(!empty($sales) and !empty($detailsales)){
			echo json_encode([
				"status" => "success",
				"info" => "Transaction Detail Successfully Show",
				"data_sales" => [
					"total_amount" => $sales[0]['total_amount'],
					"payment_method" => $sales[0]['payment_method'],
					"change" => $sales[0]['change_payment'],
					"payment_dp" => $sales[0]['payment_dp'],
					"payment_cash" => $sales[0]['payment_cash'],
					"payment_trasnfer" => $sales[0]['payment_trasnfer'],
					"payment_debit" => $sales[0]['payment_debit'],
					"payment_credit" => $sales[0]['payment_credit'],
					"sales_date" => date("d F Y", strtotime($sales[0]['sales_date'])),
					"nobill" => $nostruk,
				],
				"data_detail_sales" => $detailsales
			]);
		}else{
			echo json_encode([
				"status" => "error",
				"info" => "No Bill Not Found !!",
				"product" => ""
			]);
		}
	}
} // close session

?>