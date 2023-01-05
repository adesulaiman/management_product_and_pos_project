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
								$msg = 'Mohon lengkapi form yang di sediakan !!';
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
									$msg = 'Mohon lengkapi form yang di sediakan !!';
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
					//validation check not use request no surat
					$check = $adeQ->select("select * from data_agenda_no_surat where status is null and created_by='$_SESSION[userid]'");
					if (count($check) > 0) {
						$msg = 'No surat masih belum di gunakan, harap gunakan no surat yang sudah di request terlebih dahulu !!';
						$stt = false;
					} else {
						$q = "insert into $formCode (" . implode(",", $fieldNm) . ") values (" . implode(",", $ins) . ")";
						$ins = $adeQ->query($q);
						$msg = 'Data Berhasil Di Simpan';
						if (!$ins) {
							$msg = 'Error Insert Data : ' . $q;
							$stt = false;
						}
					}
				}





				echo json_encode(['status' => $stt, 'validate' => $validate, 'msg' => $msg]);
				break;
		}
	} // close $f
}// close session
