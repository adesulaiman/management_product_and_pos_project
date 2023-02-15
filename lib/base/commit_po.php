<?php
require "../../config.php";
require "security_login.php";
require "db.php";


if (isset($_SESSION['userid'])) {

    if (isset($_POST['method'])) {
        $method = $_POST['method'];
        if ($method == 'other_store') {
            if (
                isset($_POST['vListProduct']) and
                isset($_POST['resellerInfo']) and
                isset($_POST['nostruk'])
            ) {
                $nostruk = $_POST['nostruk'];
                $vListProduct = $_POST['vListProduct'];
                $resellerInfo = $_POST['resellerInfo'];
                $idreseller = $resellerInfo["reseller"];
                $storeName = $resellerInfo["storeName"];

                if (empty($storeName)) {
                    echo json_encode([
                        "status" => "error",
                        "info" => "Store Name must fill !!"
                    ]);
                    exit;
                }

                $detailSalesIns = [];


                foreach ($vListProduct as $key1 => $val1) {
                    $totalAmountAll += $val1['price'] * $val1['qty'];
                    $barcodeStorage = explode("-", $val1['barcode']);
                    $kadar = $val1["netto_gram"] * $val1["qty"] * $val1["price"] / 100;
                    $barcode = $barcodeStorage[1];
                    $storageid = $barcodeStorage[0];
                    $detailSalesIns[] = "('$nostruk', '$barcode', '$val1[productName]', $val1[price], $kadar,  $storageid, $val1[qty], $val1[netto_gram], $val1[brutto_gram], '$_SESSION[userid]', '" . date("Y-m-d H:i:s") . "' )";
                }


                $ins = $adeQ->query("INSERT INTO `data_product_out_os`
                (`invoice`,storename,  `created_by`, `created_date`) 
				VALUES ('$nostruk','$storeName', '$_SESSION[userid]', '" . date("Y-m-d H:i:s") . "' )");


                if ($ins) {

                    $ins2 = $adeQ->query(
                        "INSERT INTO `data_product_out_os_detail`
						(invoice, `barcode`, `product_name`, `pen`, emas_murni,  `id_category_storage`, `qty_out`, `netto_gram_out`, `brutto_gram_out`, `created_by`, `created_date` )
                        VALUES " . implode(", ", $detailSalesIns)
                    );
                    if ($ins2) {
                        $updateStock = $adeQ->query("
							UPDATE data_stock_product s inner join 
							(select barcode, id_category_storage, sum(qty_out) qty, sum(qty_out*netto_gram_out) netto_gram, sum(qty_out*brutto_gram_out) brutto_gram from data_product_out_os_detail where invoice='$nostruk' group by barcode, id_category_storage) as d
							ON s.barcode = d.barcode and s.id_category_storage = d.id_category_storage
							SET s.qty_stock = s.qty_stock - d.qty, s.netto_gram = s.netto_gram - d.netto_gram, s.brutto_gram = s.brutto_gram - d.brutto_gram, 
							s.stock_date = '" . date("Y-m-d H:i:s") . "'
						");

                        if ($updateStock) {
                            echo json_encode([
                                "status" => "success",
                                "info" => "Transaction Successfully"
                            ]);
                        } else {
                            $adeQ->query("delete from data_product_out_resaller_detail where invoice='$nostruk' ");
                            $adeQ->query("delete from data_product_out_resaller where invoice='$nostruk' ");
                            echo json_encode([
                                "status" => "error",
                                "info" => "Error Update Stock"
                            ]);
                        }
                    } else {
                        $adeQ->query("delete from data_product_out_resaller where no_struk='$nostruk' ");
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
        } else if ($method == 'reseller') {

            if (
                isset($_POST['vListProduct']) and
                isset($_POST['resellerInfo']) and
                isset($_POST['nostruk'])
            ) {
                $nostruk = $_POST['nostruk'];
                $vListProduct = $_POST['vListProduct'];
                $resellerInfo = $_POST['resellerInfo'];
                $idreseller = $resellerInfo["reseller"];
                $storeName = $resellerInfo["storeName"];

                if (empty($resellerInfo["reseller"])) {
                    echo json_encode([
                        "status" => "error",
                        "info" => "Reseller must fill !!"
                    ]);
                    exit;
                }


                $totalAmountAll = 0;

                $detailSalesIns = [];


                foreach ($vListProduct as $key1 => $val1) {
                    $totalAmountAll += $val1['price'] * $val1['qty'];
                    $barcodeStorage = explode("-", $val1['barcode']);
                    $kadar = $val1["netto_gram"] * $val1["qty"] * $val1["price"] / 100;
                    $barcode = $barcodeStorage[1];
                    $storageid = $barcodeStorage[0];
                    $detailSalesIns[] = "('$nostruk', '$barcode', '$val1[productName]', $val1[price], $kadar,  $storageid, $val1[qty], $val1[netto_gram], $val1[brutto_gram], '$_SESSION[userid]', '" . date("Y-m-d H:i:s") . "' )";
                }


                $ins = $adeQ->query("INSERT INTO `data_product_out_resaller`
                (`invoice`, `id_resaller`,  `created_by`, `created_date`) 
				VALUES ('$nostruk','$idreseller',  '$_SESSION[userid]', '" . date("Y-m-d H:i:s") . "' )");


                if ($ins) {

                    $ins2 = $adeQ->query(
                        "INSERT INTO `data_product_out_resaller_detail`
                        (invoice, `barcode`, `product_name`, `pen`, emas_murni,  `id_category_storage`, `qty_out`, `netto_gram_out`, `brutto_gram_out`, `created_by`, `created_date` )
						VALUES " . implode(", ", $detailSalesIns)
                    );
                    if ($ins2) {
                        $updateStock = $adeQ->query("
							UPDATE data_stock_product s inner join 
							(select barcode, id_category_storage, sum(qty_out) qty, sum(qty_out*netto_gram_out) netto_gram, sum(qty_out*brutto_gram_out) brutto_gram from data_product_out_resaller_detail where invoice='$nostruk' group by barcode, id_category_storage) as d
							ON s.barcode = d.barcode and s.id_category_storage = d.id_category_storage
							SET s.qty_stock = s.qty_stock - d.qty, s.netto_gram = s.netto_gram - d.netto_gram, s.brutto_gram = s.brutto_gram - d.brutto_gram, 
							s.stock_date = '" . date("Y-m-d H:i:s") . "'
						");

                        if ($updateStock) {
                            echo json_encode([
                                "status" => "success",
                                "info" => "Transaction Successfully"
                            ]);
                        } else {
                            $adeQ->query("delete from data_product_out_resaller_detail where invoice='$nostruk' ");
                            $adeQ->query("delete from data_product_out_resaller where invoice='$nostruk' ");
                            echo json_encode([
                                "status" => "error",
                                "info" => "Error Update Stock"
                            ]);
                        }
                    } else {
                        $adeQ->query("delete from data_product_out_resaller where no_struk='$nostruk' ");
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
