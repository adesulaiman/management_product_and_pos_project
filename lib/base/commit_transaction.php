<?php
require "../../config.php";
require "security_login.php";
require "db.php";


if (isset($_SESSION['userid'])) {

	if (isset($_POST['method'])) {
		$method = $_POST['method'];
		if ($method == 'pelunasandp') {
			if (
				isset($_POST['cardNo']) and
				isset($_POST['nostruk']) and
				isset($_POST['payment']) and
				isset($_POST['typePayment'])
			) {
				$nostruk = $_POST['nostruk'];
				$cardNo = $_POST['cardNo'];
				$payment = $_POST['payment'];
				$typePayment = $_POST['typePayment'];

				if (count($payment) == 0) {
					echo json_encode([
						"status" => "error",
						"info" => "Please fill payment !!"
					]);
					exit;
				}

				$payment_cash = 0;
				$payment_transfer = 0;
				$payment_debit = 0;
				$payment_credit = 0;

				foreach ($payment as $key => $val) {
					if ($key == 'Cash') {
						$payment_cash = $val;
					}

					if ($key == 'Credit') {
						$payment_credit = $val;
					}

					if ($key == 'Debit') {
						$payment_debit = $val;
					}

					if ($key == 'Transfer') {
						$payment_transfer = $val;
					}
				}

				//get data sales
				$sales = $adeQ->select($adeQ->prepare("select * from data_sales where no_struk=%s", $nostruk));
				$compare = ($payment_cash + $payment_transfer + $payment_debit + $payment_credit + $sales[0]['payment_dp']) - $sales[0]['total_amount'];
				$typePayment = $sales[0]['payment_method'] . " and " . $typePayment;


				if ($compare >= 0) {
					$updSales = $adeQ->query("update data_sales set 
						`payment_method`='$typePayment',
						`change_payment`=$compare,
						`payment_cash`=$payment_cash,
						`payment_trasnfer`=$payment_transfer,
						`payment_debit`=$payment_debit,
						`payment_credit`=$payment_credit,
						`card_no_debit`='$cardNo[inputDebitNumber]',
						`card_no_credit`='$cardNo[inputCreditNumber]',
						`no_rek_transfer`='$cardNo[inputTransferNumber]',
						`sales_date`= '".date("Y-m-d H:i:s")."'
						where no_struk = '$nostruk'
					");

					if ($updSales) {
						echo json_encode([
							"status" => "success",
							"info" => "Trunsaction Paid Off"
						]);
					} else {
						echo json_encode([
							"status" => "error",
							"info" => "Error connect to db"
						]);
					}
				} else {

					echo json_encode([
						"status" => "error",
						"info" => "Trunsaction Still Not Paid Off !!"
					]);
				}
			} else {
				echo json_encode([
					"status" => "error",
					"info" => "Transaction not complete fill !!"
				]);
			}
		} else if ($method == 'cashier') {

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
				if (is_array($typePayment) > 1) {
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
					$barcodeStorage = explode("-", $val1['barcode']);
					$barcode = $barcodeStorage[1];
					$storageid = $barcodeStorage[0];
					$detailSalesIns[] = "('$nostruk', '$barcode', '$val1[productName]', $val1[qty], $val1[netto_gram], $val1[brutto_gram], $val1[price], '".date("Y-m-d H:i:s")."', $storageid,  '$_SESSION[userid]', '".date("Y-m-d H:i:s")."' )";
				}

				$totalPayment = $payment_cash + $payment_transfer +	$payment_debit + $payment_credit + $payment_dp;
				$change = $totalPayment - $totalAmountAll;

				$ins = $adeQ->query("INSERT INTO `data_sales`(`no_struk`, `payment_method`, `change_payment`, `payment_cash`, `payment_trasnfer`, `payment_debit`, `payment_credit`, `payment_dp`, `total_amount`, `card_no_debit`, `card_no_credit`, `no_rek_transfer`, `sales_date`, `created_by`, `created_date`) 
				VALUES ('$nostruk','$typePayment', $change, $payment_cash, $payment_transfer , $payment_debit , $payment_credit, $payment_dp , $totalAmountAll , '$cardNo[inputDebitNumber]','$cardNo[inputCreditNumber]','$cardNo[inputTransferNumber]' , '".date("Y-m-d H:i:s")."' , '$_SESSION[userid]', '".date("Y-m-d H:i:s")."' )");


				if ($ins) {

					$ins2 = $adeQ->query(
						"INSERT INTO `data_sales_detail`( `no_struk`, `barcode`, `product_name`, `qty`, `netto_gram`, brutto_gram, `price`, `sales_date`, id_category_storage,  `created_by`, `created_date`) 
						VALUES " . implode(", ", $detailSalesIns)
					);
					if ($ins2) {
						$updateStock = $adeQ->query("
							UPDATE data_stock_product s inner join 
							(select barcode, id_category_storage, sum(qty) qty, sum(qty*netto_gram) netto_gram, sum(qty*brutto_gram) brutto_gram from data_sales_detail where no_struk='$nostruk' group by barcode, id_category_storage) as d
							ON s.barcode = d.barcode and s.id_category_storage = d.id_category_storage
							SET s.qty_stock = s.qty_stock - d.qty, s.brutto_gram = s.brutto_gram - d.brutto_gram, s.netto_gram = s.netto_gram - d.netto_gram, 
							s.stock_date = '".date("Y-m-d H:i:s")."'
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
		} else {
			echo json_encode([
				"status" => "error",
				"info" => "method not found !!"
			]);
		}
	} else {
		echo json_encode([
			"status" => "error",
			"info" => "method not found !!"
		]);
	}
} // close session
