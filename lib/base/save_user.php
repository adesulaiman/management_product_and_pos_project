<?php
require "../../config.php";
require "security_login.php";
require "db.php";
require "enc.php";


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
						} else {
							//custom validation for jabatan uniq by user id
							if ($field['name_field'] == 'jabatan') {
								if ($data != 0) {
									$checkJabatan  = $adeQ->select($adeQ->prepare("select * from core_user where jabatan=%d", $data));
									if (count($checkJabatan) > 0) {
										$stt = false;
										$validate[] = array(
											'field' => $field['name_field'],
											'err' => 'validate',
											'msg' => "Jabatan already use in other user !!"
										);
									}
								}
							}
						}

						if ($field['type_input'] == 'number') {
							$ins[] = $data;
						} elseif ($field['type_input'] == 'date') {
							$date = date($datePHP, strtotime($data));
							$ins[] = "'$date'";
						} elseif ($field['type_input'] == 'password') {
							$pass = $hasher->HashPassword($data);
							$ins[] = "'$pass'";
						} elseif ($field['type_input'] == 'checkbox') {
							$datarr = array();
							if ($data != null) {
								foreach ($data as $key => $value) {
									$datarr[] = $value;
								}
							}
							$ins[] = "'" . implode("|", $datarr) . "'";
						} else {
							if ($field['encrypt'] == 1) {
								$enc = new EnDecryptText();
								$ins[] = "'" . $enc->Encrypt_Text($data) . "'";
							} else {
								$ins[] = "'$data'";
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
							if ($field['type_input'] == 'file') {
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
									$updFile = "../../assets/certificate/" . $nameFile;
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
					}
				}

				if ($stt) {
					$q = "insert into $formCode (" . implode(",", $fieldNm) . ") values (" . implode(",", $ins) . ")";
					$ins = $adeQ->query($q);
					if (!$ins) {
						$msg = 'Error Insert Data';
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
						} else {
							//custom validation for jabatan uniq by user id
							if ($field['name_field'] == 'jabatan') {
								error_log($data);
								if ($data != 0) {
									$checkJabatan  = $adeQ->select($adeQ->prepare("select * from core_user where jabatan=%d and id<>%d", $data, $_POST['id']));
									if (count($checkJabatan) > 0) {
										$stt = false;
										$validate[] = array(
											'field' => $field['name_field'],
											'err' => 'validate',
											'msg' => "Jabatan already use in other user !!"
										);
									}
								}
							}
						}

						if ($field['type_input'] == 'number') {
							$dtUpd = $data;
						} elseif ($field['type_input'] == 'date') {
							$date = date($datePHP, strtotime($data));
							$dtUpd = "'$date'";
						} elseif ($field['type_input'] == 'password') {
							$cekCurrPass = $adeQ->select("select * from $formCode where id=$_POST[id]");
							if ($cekCurrPass[0]['userpass'] != $data) {
								$pass = $hasher->HashPassword($data);
								$dtUpd = "'$pass'";
							} else {
								$dtUpd = "'$data'";
							}
						} elseif ($field['type_input'] == 'checkbox') {
							$datarr = array();
							if ($data != null) {
								foreach ($data as $key => $value) {
									$datarr[] = $value;
								}
							}
							$dtUpd = "'" . implode("|", $datarr) . "'";
						} else {
							if ($field['encrypt'] == 1) {
								$enc = new EnDecryptText();
								$dtUpd =  "'" . $enc->Encrypt_Text($data) . "'";
							} else {
								$dtUpd = "'$data'";
							}
						}

						$upd[] = $field['name_field'] . "= $dtUpd";
					} else if ($field['type_field'] == 'nm' and in_array($field['type_input'], ['image', 'file'])) {

						$files = isset($_FILES[$field['name_field']]) ? $_FILES[$field['name_field']] : null;

						if (empty($files)) {
						} else {
							if ($field['type_input'] == 'file') {
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
									$updFile = "../../assets/certificate/" . $nameFile;
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
					}
				}

				if ($stt) {
					$q = "update $formCode set " . implode(",", $upd) . " where $where";
					$update = $adeQ->query($q);
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
					if (!$del) {
						$msg = 'Error Delete Data';
						$stt = false;
					}
				}

				echo json_encode(['status' => $stt, 'validate' => $validate, 'msg' => $msg]);

				break;

			case 'unlock':
				$msg = "Unlock user success !!";
				foreach ($qField as $field) {
					$data = isset($_POST[$field['name_field']]) ? $_POST[$field['name_field']] : null;

					if ($field['type_field'] == 'pk') {
						$where = $field['name_field'] . "= $data";
					}
				}

				if ($stt) {
					$q = "update $formCode set wrongpass=0 where $where";
					error_log($q);
					$unlock = $adeQ->query($q);
					if (!$unlock) {
						$msg = 'Error unlock Data';
						$stt = false;
					}
				}

				echo json_encode(['status' => $stt, 'validate' => $validate, 'msg' => $msg]);

				break;
		}
	} //close $f
}// close session
