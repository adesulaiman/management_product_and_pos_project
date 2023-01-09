<?php
require "../../config.php";
require "security_login.php";
require "db.php";


if (isset($_SESSION['userid'])) {
	if (
		isset($_POST['vListProduct']) and
		isset($_POST['cardNo']) and
		isset($_POST['nostruk']) and
		isset($_POST['payment']) and
		isset($_POST['typePayment'])
	) {
		$nostruk = $_POST['nostruk'];
		$vListProduct = $_POST['vListProduct'];
		$cardNo = $_POST['cardNo'];
		$payment = $_POST['payment'];
		$typePayment = $_POST['typePayment'];

		if (count($payment) == 0 || count($vListProduct) == 0) {
			echo json_encode([
				"status" => "error",
				"info" => "Payment or product not list !!"
			]);
			exit;
		}

		//process calc and generate to table structure
		if(is_array($typePayment) > 1){
			$typePayment = implode(" and ", $typePayment);
		}


		$payment_cash = 0;
		$payment_transfer = 0;
		$payment_debit = 0;
		$payment_credit = 0;
		$payment_dp = 0;
		$totalAmountAll = 0;

		$detailSalesIns = [];

		foreach ($payment as $key => $val) {
			if ($key == 'Cash') {
				$payment_cash = $val;
			}

			if ($key == 'Credit') {
				$payment_credit = $val;
			}

			if ($key == 'DP') {
				$payment_dp = $val;
			}

			if ($key == 'Debit') {
				$payment_debit = $val;
			}

			if ($key == 'Transfer') {
				$payment_transfer = $val;
			}
		}

		foreach ($vListProduct as $key1 => $val1) {
			$totalAmountAll += $val1['price'] * $val1['qty'];
			$detailSalesIns[] = "('$nostruk', '$val1[barcode]', '$val1[productName]', $val1[qty], $val1[gram], $val1[price], now(), '$_SESSION[userid]', now() )";
		}

		$totalPayment = $payment_cash + $payment_transfer +	$payment_debit + $payment_credit + $payment_dp;
		$change = $totalPayment - $totalAmountAll;

		$ins = $adeQ->query("INSERT INTO `data_sales`(`no_struk`, `payment_method`, `change_payment`, `payment_cash`, `payment_trasnfer`, `payment_debit`, `payment_credit`, `payment_dp`, `total_amount`, `card_no_debit`, `card_no_credit`, `no_rek_transfer`, `sales_date`, `created_by`, `created_date`) 
		VALUES ('$nostruk','$typePayment', $change, $payment_cash, $payment_transfer , $payment_debit , $payment_credit, $payment_dp , $totalAmountAll , '$cardNo[inputDebitNumber]','$cardNo[inputCreditNumber]','$cardNo[inputTransferNumber]' , now() , '$_SESSION[userid]', now() )");


		if ($ins) {

			$ins2 = $adeQ->query(
				"INSERT INTO `data_sales_detail`( `no_struk`, `barcode`, `product_name`, `qty`, `gram`, `price`, `sales_date`, `created_by`, `created_date`) 
				VALUES " . implode(", ", $detailSalesIns)
			);
			if ($ins2) {
				$updateStock = $adeQ->query("
					UPDATE data_stock_product s inner join 
					(select barcode, sum(qty) qty from data_sales_detail where no_struk='$nostruk' group by barcode) as d
					ON s.barcode = d.barcode
					SET s.qty_stock = s.qty_stock - d.qty,
					s.stock_date = now()
				");

				if ($updateStock) {
					echo json_encode([
						"status" => "success",
						"info" => "Transaction Successfully"
					]);
				} else {
					$adeQ->query("delete from data_sales where no_struk='$nostruk' ");
					$adeQ->query("delete from data_sales_detail where no_struk='$nostruk' ");
					echo json_encode([
						"status" => "error",
						"info" => "Error Update Stock"
					]);
				}
			} else {
				$adeQ->query("delete from data_sales where no_struk='$nostruk' ");
				echo json_encode([
					"status" => "error",
					"info" => "Error Insert Detail Sales"
				]);
			}
		} else {
			echo json_encode([
				"status" => "error",
				"info" => "Error Insert Sales"
			]);
		}
	} else {
		echo json_encode([
			"status" => "error",
			"info" => "Transaction not complete fill !!"
		]);
	}
} // close session
