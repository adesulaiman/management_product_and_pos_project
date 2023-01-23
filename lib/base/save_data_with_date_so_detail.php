<?php
require "../../config.php";
require "../base/security_login.php";
require "../base/db.php";


if (isset($_SESSION['userid'])) {

	if (isset($_POST['f']) and isset($_POST['formType'])) {

		$f = $_POST['f'];
		$formType = $_POST['formType'];
		$qField = $adeQ->select($adeQ->prepare(
			"select * from core_fields where id_form=%d and active is true order by id",
			$f
		));

		$qForm = $adeQ->select($adeQ->prepare(
			"select * from core_forms where idform=%d",
			$f
		));

		foreach ($qForm as $valForm) {
			$formName = $valForm['formname'];
			$formDesc = $valForm['description'];
			$formCode = $valForm['formcode'];
		}

		$stt = true;
		$validate = array();
		$bypass = array();
		$ins = array();
		$fieldNm = array();
		$msg = '';

		switch ($formType) {
			case 'add':
				foreach ($qField as $field) {
					if ($field['type_field'] == 'nm' and !in_array($field['type_input'], ['image', 'file'])) {
						$data = isset($_POST[$field['name_field']]) ? $_POST[$field['name_field']] : null;

						if ($field['validate'] == '1') {
							if ($field['type_input'] != 'checkbox') {
								$validate_length = ($field['validate_length'] == null) ? strlen($data) : $field['validate_length'];
							}

							if (empty($data)) {
								$stt = false;
								$validate[] = array(
									'field' => $field['name_field'],
									'err' => 'validate',
									'msg' => $field['msg_validate']
								);
							} else {
								$validate[] = array(
									'field' => $field['name_field'],
									'err' => '',
									'msg' => $field['msg_validate']
								);
							}

							if ($field['type_input'] != 'checkbox') {
								if (strlen($data) != $validate_length) {
									$stt = false;
									$validate[] = array(
										'field' => $field['name_field'],
										'err' => 'validate',
										'msg' => $field['msg_validate']
									);
								}
							}
						}

						if ($field['type_input'] == 'number') {
							if ($data == null) {
								$ins[] = 'null';
							} else {
								$ins[] = $data;
							}
						} elseif ($field['type_input'] == 'date') {
							$date = date('Y-m-d', strtotime($data));
							$ins[] = "'$date'";
						} elseif ($field['type_input'] == 'checkbox') {
							$datarr = array();
							if ($data != null) {
								foreach ($data as $key => $value) {
									$datarr[] = $value;
								}
							}
							$ins[] = "'" . implode("|", $datarr) . "'";
						} else {
							if ($data == null) {
								$ins[] = 'null';
							} else {
								$ins[] = "'" . str_replace("'", "`", $data) . "'";
							}
						}
						$fieldNm[] = $field['name_field'];
					} else if ($field['type_field'] == 'nm' and in_array($field['type_input'], ['image', 'file'])) {

						$files = isset($_FILES[$field['name_field']]) ? $_FILES[$field['name_field']] : null;

						if ($field['validate'] == '1') {
							if (empty($files)) {
								$stt = false;
								$validate[] = array(
									'field' => $field['name_field'],
									'err' => 'validate',
									'msg' => $field['msg_validate']
								);
							}
						}

						if (empty($files)) {
							$ins[] = "NULL";
						} else {
							if ($field['type_input'] == 'image') {
								//CEK NAME AND EXT
								$nameFile = $files["name"];
								$cekhack = explode('.', $nameFile);
								$cekExt = array("php", "js", "php5");
								$isImg = @getimagesize($files['tmp_name']);
								$isExt = true;

								for ($l = 0; $l < count($cekhack); $l++) {
									if (in_array(strtolower($cekhack[$l]), $cekExt)) {
										$isExt = false;
									}
								}

								if ($files["size"] > $maxSizeUpload) {
									$stt = false;
									$validate[] = array(
										'field' => $field['name_field'],
										'err' => 'validate',
										'msg' => 'Error file size, size must lower then ' . $maxSizeUpload / 1000000 . 'MB'
									);
								} else if ($isImg == false) {
									$stt = false;
									$validate[] = array(
										'field' => $field['name_field'],
										'err' => 'validate',
										'msg' => 'This not image file'
									);
								} else if ($isExt == false) {
									$stt = false;
									$validate[] = array(
										'field' => $field['name_field'],
										'err' => 'validate',
										'msg' => 'Error file upload, inject detected'
									);
								} else {
									$nameFile = uniqid() . "_$nameFile";
									$updFile = "../../assets/upload/" . $nameFile;
									if (move_uploaded_file($files["tmp_name"], $updFile)) {
										$ins[] = "'$nameFile'";
										$validate[] = array(
											'field' => $field['name_field'],
											'err' => '',
											'msg' => $field['msg_validate']
										);
									} else {
										$stt = false;
										$validate[] = array(
											'field' => $field['name_field'],
											'err' => 'validate',
											'msg' => 'Error file upload, Upload file not allowed in your system'
										);
									}
								}
							} else if ($field['type_input'] == 'file') {
								//CEK NAME AND EXT
								$nameFile = $files["name"];
								$cekhack = explode('.', $nameFile);
								$cekExt = array("php", "js", "php5");
								$isExt = true;

								for ($l = 0; $l < count($cekhack); $l++) {
									if (in_array(strtolower($cekhack[$l]), $cekExt)) {
										$isExt = false;
									}
								}

								if ($files["size"] > $maxSizeUpload) {
									$stt = false;
									$validate[] = array(
										'field' => $field['name_field'],
										'err' => 'validate',
										'msg' => 'Error file size, size must lower then ' . $maxSizeUpload / 1000000 . 'MB'
									);
								} else if ($isExt == false) {
									$stt = false;
									$validate[] = array(
										'field' => $field['name_field'],
										'err' => 'validate',
										'msg' => 'Error file upload, inject detected'
									);
								} else {
									$nameFile = uniqid() . "_$nameFile";
									$updFile = "../../assets/upload/" . $nameFile;
									if (move_uploaded_file($files["tmp_name"], $updFile)) {
										$ins[] = "'$nameFile'";
										$validate[] = array(
											'field' => $field['name_field'],
											'err' => '',
											'msg' => $field['msg_validate']
										);
									} else {
										$stt = false;
										$validate[] = array(
											'field' => $field['name_field'],
											'err' => 'validate',
											'msg' => 'Error file upload, Upload file not allowed in your system'
										);
									}
								}
							}
						}

						$fieldNm[] = $field['name_field'];
					} else if ($field['type_field'] == 'sys_ins_usr') {

						$ins[] = "'" . $_SESSION['userid'] . "'";
						$fieldNm[] = $field['name_field'];
					} else if ($field['type_field'] == 'sys_ins_time') {

						$ins[] = "'" . date("Y-m-d H:i:s") . "'";
						$fieldNm[] = $field['name_field'];
					} else if ($field['type_field'] == 'sub') {
						$data = isset($_POST[$field['name_field']]) ? $_POST[$field['name_field']] : null;

						if ($field['type_input'] == 'number') {
							$ins[] = $data;
						} else if ($field['type_input'] == 'text') {
							$ins[] = "'" . str_replace("'", "`", $data) . "'";
						}

						$fieldNm[] = $field['name_field'];
					}
				}

				if ($stt) {
					$q = "insert into $formCode (" . implode(",", $fieldNm) . ") values (" . implode(",", $ins) . ")";
					$ins = $adeQ->query($q);
					$msg = 'Data Berhasil Di Simpan';
					if (!$ins) {
						$msg = 'Error Insert Data : ' . $q;
						$stt = false;
					}
				}





				echo json_encode(['status' => $stt, 'validate' => $validate, 'msg' => $msg]);
				break;

			case 'edit':

				$upd = array();
				$dtUpd = null;

				foreach ($qField as $field) {
					$data = isset($_POST[$field['name_field']]) ? $_POST[$field['name_field']] : null;

					if ($field['type_field'] == 'nm' and !in_array($field['type_input'], ['image', 'file'])) {
						if ($field['validate'] == '1') {
							if ($field['type_input'] != 'checkbox') {
								$validate_length = ($field['validate_length'] == null) ? strlen($data) : $field['validate_length'];
							}



							if (empty($data)) {
								$stt = false;
								$validate[] = array(
									'field' => $field['name_field'],
									'err' => 'validate',
									'msg' => $field['msg_validate']
								);
							} else {
								$validate[] = array(
									'field' => $field['name_field'],
									'err' => '',
									'msg' => $field['msg_validate']
								);
							}

							if ($field['type_input'] != 'checkbox') {
								if (strlen($data) != $validate_length) {
									$stt = false;
									$validate[] = array(
										'field' => $field['name_field'],
										'err' => 'validate',
										'msg' => $field['msg_validate']
									);
								}
							}
						}

						if ($field['type_input'] == 'number') {
							if ($data == null) {
								$dtUpd = 'null';
							} else {
								$dtUpd = $data;
							}
						} elseif ($field['type_input'] == 'date') {
							$date = date($datePHP, strtotime($data));
							$dtUpd = "'$date'";
						} elseif ($field['type_input'] == 'checkbox') {
							$datarr = array();
							if ($data != null) {
								foreach ($data as $key => $value) {
									$datarr[] = $value;
								}
							}
							$dtUpd = "'" . implode("|", $datarr) . "'";
						} else {
							if ($data == null) {
								$dtUpd = 'null';
							} else {
								$dtUpd = "'" . str_replace("'", "`", $data) . "'";
							}
						}

						$upd[] = $field['name_field'] . "= $dtUpd";
					} else if ($field['type_field'] == 'nm' and in_array($field['type_input'], ['image', 'file'])) {

						$files = isset($_FILES[$field['name_field']]) ? $_FILES[$field['name_field']] : null;

						if (empty($files)) {
						} else {
							if ($field['type_input'] == 'image') {
								//CEK NAME AND EXT
								$nameFile = $files["name"];
								$cekhack = explode('.', $nameFile);
								$cekExt = array("php", "js", "php5");
								$isImg = @getimagesize($files['tmp_name']);
								$isExt = true;

								for ($l = 0; $l < count($cekhack); $l++) {
									if (in_array(strtolower($cekhack[$l]), $cekExt)) {
										$isExt = false;
									}
								}

								if ($files["size"] > $maxSizeUpload) {
									$stt = false;
									$validate[] = array(
										'field' => $field['name_field'],
										'err' => 'validate',
										'msg' => 'Error file size, size must lower then ' . $maxSizeUpload / 1000000 . 'MB'
									);
								} else if ($isImg == false) {
									$stt = false;
									$validate[] = array(
										'field' => $field['name_field'],
										'err' => 'validate',
										'msg' => 'This not image file'
									);
								} else if ($isExt == false) {
									$stt = false;
									$validate[] = array(
										'field' => $field['name_field'],
										'err' => 'validate',
										'msg' => 'Error file upload, inject detected'
									);
								} else {
									$nameFile = uniqid() . "_$nameFile";
									$updFile = "../../assets/upload/" . $nameFile;
									if (move_uploaded_file($files["tmp_name"], $updFile)) {
										$dtUpd = "'$nameFile'";
										$upd[] = $field['name_field'] . "= $dtUpd";
										$validate[] = array(
											'field' => $field['name_field'],
											'err' => '',
											'msg' => $field['msg_validate']
										);
									} else {
										$stt = false;
										$validate[] = array(
											'field' => $field['name_field'],
											'err' => 'validate',
											'msg' => 'Error file upload, Upload file not allowed in your system'
										);
									}
								}
							} else if ($field['type_input'] == 'file') {
								//CEK NAME AND EXT
								$nameFile = $files["name"];
								$cekhack = explode('.', $nameFile);
								$cekExt = array("php", "js", "php5");
								$isExt = true;

								for ($l = 0; $l < count($cekhack); $l++) {
									if (in_array(strtolower($cekhack[$l]), $cekExt)) {
										$isExt = false;
									}
								}

								if ($files["size"] > $maxSizeUpload) {
									$stt = false;
									$validate[] = array(
										'field' => $field['name_field'],
										'err' => 'validate',
										'msg' => 'Error file size, size must lower then ' . $maxSizeUpload / 1000000 . 'MB'
									);
								} else if ($isExt == false) {
									$stt = false;
									$validate[] = array(
										'field' => $field['name_field'],
										'err' => 'validate',
										'msg' => 'Error file upload, inject detected'
									);
								} else {
									$nameFile = uniqid() . "_$nameFile";
									$updFile = "../../assets/upload/" . $nameFile;
									if (move_uploaded_file($files["tmp_name"], $updFile)) {
										$dtUpd = "'$nameFile'";
										$upd[] = $field['name_field'] . "= $dtUpd";
										$validate[] = array(
											'field' => $field['name_field'],
											'err' => '',
											'msg' => $field['msg_validate']
										);
									} else {
										$stt = false;
										$validate[] = array(
											'field' => $field['name_field'],
											'err' => 'validate',
											'msg' => 'Error file upload, Upload file not allowed in your system'
										);
									}
								}
							}
						}
					} else if ($field['type_field'] == 'pk') {
						$where = $field['name_field'] . "= $data";
					} else if ($field['type_field'] == 'sys_upd_usr') {

						$dtUpd = "'" . $_SESSION['userid'] . "'";
						$upd[] = $field['name_field'] . "= $dtUpd";
					} else if ($field['type_field'] == 'sys_upd_time') {

						$dtUpd = "'" . date("Y-m-d H:i:s") . "'";
						$upd[] = $field['name_field'] . "= $dtUpd";
					} else if ($field['type_field'] == 'sub') {
						$data = isset($_POST[$field['name_field']]) ? $_POST[$field['name_field']] : null;

						if ($field['type_input'] == 'number') {
							$dtUpd = $data;
						} else if ($field['type_input'] == 'text') {
							$dtUpd = "'" . str_replace("'", "`", $data) . "'";
						}

						$upd[] = $field['name_field'] . " = $dtUpd";
					}
				}

				if ($stt) {
					$q = "update $formCode set " . implode(",", $upd) . " where $where";
					error_log($q);
					$update = $adeQ->query($q);
					$msg = 'Data Berhasil Di Perbarui';
					if (!$update) {
						$msg = 'Error Edit Data';
						$stt = false;
					}
				}

				echo json_encode(['status' => $stt, 'validate' => $validate, 'msg' => $msg]);
				break;

			case 'delete':
				foreach ($qField as $field) {
					$data = isset($_POST[$field['name_field']]) ? $_POST[$field['name_field']] : null;

					if ($field['type_field'] == 'pk') {
						$where = $field['name_field'] . "= $data";
					}
				}

				if ($stt) {
					$q = "delete from $formCode where $where";
					$del = $adeQ->query($q);
					$msg = 'Data Berhasil Di Hapus';
					if (!$del) {
						$msg = 'Error Delete Data';
						$stt = false;
					}
				}

				echo json_encode(['status' => $stt, 'validate' => $validate, 'msg' => $msg]);

				break;

			case 'count':
				//get data
				if (
					!empty($_POST['id_category_storage']) &&
					!empty($_POST['qty_phisycal']) &&
					!empty($_POST['gram_product']) &&
					!empty($_POST['id_so']) &&
					!empty($_POST['product']) &&
					!empty($_POST['barcode'])
				) {

					//cek data if counted in storage
					$qCek = $adeQ->select($adeQ->prepare(
						"select count(1) cek from data_stock_opname_detail
						where id_stock_opname=%s and id_category_storage=%s and barcode=%s",
						$_POST['id_so'],
						$_POST['id_category_storage'],
						$_POST['barcode']
					));

					if ($qCek[0]['cek'] > 0) {
						echo json_encode(['status' => 'error', 'info' => 'Product Already Exist !!']);
						exit;
					}


					$data = [
						"id_stock_opname" => $_POST['id_so'],
						"id_category_storage" => $_POST['id_category_storage'],
						"barcode" => "'$_POST[barcode]'",
						"product_name" => "'$_POST[product]'",
						"qty_phisycal" => $_POST['qty_phisycal'],
						"gram_physycal" => $_POST['qty_phisycal'] * $_POST['gram_product'],
						"created_by" => "'$_SESSION[userid]'",
						"created_date" => "'" . date("Y-m-d H:i:s") . "'",
					];

					$column = [];
					$val = [];
					foreach ($data as $col => $value) {
						$column[] = $col;
						$val[] = $value;
					}

					$Qins = "insert into data_stock_opname_detail (" . implode(",", $column) . ") values (" . implode(",", $val) . ")";
					$ins = $adeQ->query($Qins);
					if ($ins) {
						echo json_encode(['status' => 'success', 'info' => 'Count data success !!']);
					} else {
						echo json_encode(['status' => 'error', 'info' => 'Error count data !!']);
					}
				} else {
					echo json_encode(['status' => 'error', 'info' => 'Please fill data !!']);
				}
				break;

			case 'adjusment':
				//get data
				if (
					!empty($_POST['id']) &&
					!empty($_POST['qty_adjusment']) &&
					!empty($_POST['gram_product'])
				) {

					$data = [
						"qty_adjusment" => $_POST['qty_adjusment'],
						"gram_adjusment" => $_POST['qty_adjusment'] * $_POST['gram_product'],
						"created_by" => "'$_SESSION[userid]'",
						"created_date" => "'" . date("Y-m-d H:i:s") . "'",
					];

					$update = [];
					foreach ($data as $col => $value) {
						$update[] = $col . " = $value";
					}

					$Qins = "update data_stock_opname_detail set " . implode(",", $update) . " where id=". $_POST['id'];
					$ins = $adeQ->query($Qins);
					if ($ins) {
						echo json_encode(['status' => 'success', 'info' => 'Adjusment data success !!']);
					} else {
						echo json_encode(['status' => 'error', 'info' => 'Error count data !!']);
					}
				} else {
					echo json_encode(['status' => 'error', 'info' => 'Please fill data !!']);
				}
				break;

			case "commit":

				if (
					!empty($_POST['id_stock'])
				) {

					//insert to report so
					$ins = $adeQ->query("
						insert into data_stock_opname_detail_report
						select 
						d.id,
						d.id_stock_opname,
						d.id_category_storage,
						d.barcode,
						d.product_name,
						d.qty_phisycal,
						coalesce(d.gram_physycal, 0) gram_phisycal,
						coalesce(s.qty_stock, 0) qty_stock,
						coalesce(s.gram, 0) gram_stock,
						coalesce(d.qty_adjusment, 0) qty_adjusment,
						coalesce(d.gram_adjusment, 0) gram_adjusment,
						(d.qty_phisycal - coalesce(s.qty_stock, 0)) + coalesce(d.qty_adjusment, 0)  qty_diff,
						((d.qty_phisycal * coalesce(p.gram, 0) ) - coalesce(s.gram, 0)) + (coalesce(d.qty_adjusment, 0) * coalesce(p.gram, 0)) gram_diff,
						d.created_by,
						d.created_date
						from data_stock_opname_detail d
						left join data_product p on d.barcode=p.barcode
						left join data_category_storage c on d.id_category_storage = c.id
						left join data_stock_product s on d.id_category_storage = s.id_category_storage and d.barcode=s.barcode
						where id_stock_opname=$_POST[id_stock]
					");

					if($ins){
						//update stock
						$updStock = $adeQ->query("
							UPDATE data_stock_product s inner join 
							(
								select 
								id_category_storage , 
								barcode,
								coalesce(qty_phisycal,0) + coalesce(qty_adjusment,0) qty,
								coalesce(gram_physycal,0) + coalesce(gram_adjusment,0) gram
								from data_stock_opname_detail
								where id_stock_opname=$_POST[id_stock]
							) as d
							ON s.barcode = d.barcode and s.id_category_storage = d.id_category_storage
							SET s.qty_stock = d.qty, s.gram = d.gram, s.stock_date = '".date("Y-m-d H:i:s")."' , s.created_by = 'SO', 
							s.created_date = '".date("Y-m-d H:i:s")."'
						");

						$insStock = $adeQ->query("
							insert into data_stock_product (barcode, id_category_storage, qty_stock, gram, stock_date, created_by, created_date)
							select 
							d.barcode,
							d.id_category_storage , 
							coalesce(d.qty_phisycal,0) + coalesce(d.qty_adjusment,0) qty,
							coalesce(d.gram_physycal,0) + coalesce(d.gram_adjusment,0) gram,
							'".date("Y-m-d H:i:s")."',
							'SO',
							'".date("Y-m-d H:i:s")."'
							from data_stock_opname_detail d
							left join data_stock_product s on d.barcode=s.barcode and d.id_category_storage=s.id_category_storage
							where id_stock_opname=$_POST[id_stock]
							and s.barcode is null
						");

						if($updStock && $insStock){
							$updStatus = $adeQ->query("update data_stock_opname  set status_stock_opname = 'closed' where id=$_POST[id_stock]");
							if($updStatus){
								echo json_encode(['status' => 'success', 'info' => 'Commit Stock Opname Successfully !!']);
							}else{
								echo json_encode(['status' => 'error', 'info' => 'Error commit stock opname !!']);
							}
						}else{
							echo json_encode(['status' => 'error', 'info' => 'Error update stock !!']);
						}
					}else{
						echo json_encode(['status' => 'error', 'info' => 'Error sync to report !!']);
					}

				}else{
					echo json_encode(['status' => 'error', 'info' => 'Please fill data !!']);
				}
				
		}
	} // close $f
}// close session
