<?php
require "../../config.php";
require "../base/security_login.php";
require "../base/db.php";


if(isset($_SESSION['userid']))
{

	if(isset($_POST['f']) and isset($_POST['formType']))
  	{

		$f = $_POST['f'];
		$formType = $_POST['formType'];
		$qField = $adeQ->select($adeQ->prepare(
		    "select * from core_fields where id_form=%d and active is true order by id", $f
		));

		$qForm = $adeQ->select($adeQ->prepare(
		    "select * from core_forms where idform=%d", $f
		));

		foreach($qForm as $valForm)
		{
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
				foreach($qField as $field)
				{
					if($field['type_field'] == 'nm' and !in_array($field['type_input'], ['image', 'file']))
					{
						$data = isset($_POST[$field['name_field']]) ? $_POST[$field['name_field']] : null;

						if($field['validate'] == '1')
						{
							if($field['type_input'] != 'checkbox'){
								$validate_length = ($field['validate_length'] == null) ? strlen($data) : $field['validate_length'];
							}
							
							if(empty($data))
							{
								$stt = false;
								$validate[] = array(
									'field' => $field['name_field'],
									'err' => 'validate',
									'msg' => $field['msg_validate']
								);
							}else{
								$validate[] = array(
									'field' => $field['name_field'],
									'err' => '',
									'msg' => $field['msg_validate']
								);
							}

							if($field['type_input'] != 'checkbox')
							{
								if(strlen($data) != $validate_length)
								{
									$stt = false;
									$validate[] = array(
										'field' => $field['name_field'],
										'err' => 'validate',
										'msg' => $field['msg_validate']
									);
								}
							}

						}

						if($field['type_input'] == 'number')
						{
							if($data == null){
								$ins[] = 'null';	
							}else{
								$ins[] = $data;
							}
						}elseif($field['type_input'] == 'date')
						{
							$date = date('Y-m-d', strtotime($data));
							$ins[] = "'$date'";
						}elseif($field['type_input'] == 'checkbox')
						{
							$datarr = array();
							if($data != null)
							{
								foreach ($data as $key => $value) {
									$datarr[] = $value;
								} 
							}
							$ins[] = "'".implode("|", $datarr)."'";	
						}else
						{
							if($data == null){
								$ins[] = 'null';	
							}else{
								$ins[] = "'".str_replace("'", "`", $data)."'";
							}
							
						}
						$fieldNm[] = $field['name_field'];

					}else if($field['type_field'] == 'nm' and in_array($field['type_input'], ['image', 'file'])){

						$files = isset($_FILES[$field['name_field']]) ? $_FILES[$field['name_field']] : null;

						if($field['validate'] == '1')
						{
							if(empty($files))
							{
								$stt = false;
								$validate[] = array(
									'field' => $field['name_field'],
									'err' => 'validate',
									'msg' => $field['msg_validate']
								);
							}
						}

						if(empty($files))
						{
							$ins[] = "NULL";
						}else{
							if($field['type_input'] == 'image'){
								//CEK NAME AND EXT
								$nameFile = $files["name"];
								$cekhack = explode('.', $nameFile);
								$cekExt = array("php", "js", "php5");
								$isImg = @getimagesize($files['tmp_name']);
								$isExt = true;
								
								for($l=0 ; $l < count($cekhack) ; $l++){
									if(in_array(strtolower($cekhack[$l]), $cekExt)){
										$isExt = false;
									}
								}
	
								if($files["size"] > $maxSizeUpload){
									$stt = false;
									$validate[] = array(
										'field' => $field['name_field'],
										'err' => 'validate',
										'msg' => 'Error file size, size must lower then ' . $maxSizeUpload / 1000000 . 'MB'
									);
								}else if($isImg == false){
									$stt = false;
									$validate[] = array(
										'field' => $field['name_field'],
										'err' => 'validate',
										'msg' => 'This not image file'
									);
								}else if($isExt == false){
									$stt = false;
									$validate[] = array(
										'field' => $field['name_field'],
										'err' => 'validate',
										'msg' => 'Error file upload, inject detected'
									);
								}else{
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
								
							}else if($field['type_input'] == 'file'){
								//CEK NAME AND EXT
								$nameFile = $files["name"];
								$cekhack = explode('.', $nameFile);
								$cekExt = array("php", "js", "php5");
								$isExt = true;
								
								for($l=0 ; $l < count($cekhack) ; $l++){
									if(in_array(strtolower($cekhack[$l]), $cekExt)){
										$isExt = false;
									}
								}
	
								if($files["size"] > $maxSizeUpload){
									$stt = false;
									$validate[] = array(
										'field' => $field['name_field'],
										'err' => 'validate',
										'msg' => 'Error file size, size must lower then ' . $maxSizeUpload / 1000000 . 'MB'
									);
								}else if($isExt == false){
									$stt = false;
									$validate[] = array(
										'field' => $field['name_field'],
										'err' => 'validate',
										'msg' => 'Error file upload, inject detected'
									);
								}else{
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

					}else if($field['type_field'] == 'sys_ins_usr')
					{

						$ins[] = "'".$_SESSION['userid']."'";
						$fieldNm[] = $field['name_field'];

					}else if($field['type_field'] == 'sys_ins_time')
					{

						$ins[] = "'".date("Y-m-d H:i:s")."'";
						$fieldNm[] = $field['name_field'];
					}else if($field['type_field'] == 'sub')
					{
						$data = isset($_POST[$field['name_field']]) ? $_POST[$field['name_field']] : null;

						if($field['type_input'] == 'number')
						{
							$ins[] = $data;
						}else if($field['type_input'] == 'text')
						{
							$ins[] = "'".str_replace("'", "`", $data)."'";
						}

						$fieldNm[] = $field['name_field'];
					}
				}

				if($stt)
				{
					//cek status open
					$qOpen = $adeQ->select("select count(1) cek from data_stock_opname where status_stock_opname='open'");
					if($qOpen[0]['cek'] > 0){
						$msg = 'Status stock opname still opened, please finish process status stock opname first !!';
						$stt = false;
					}else{
						$q = "insert into $formCode (".implode(",", $fieldNm).") values (".implode(",", $ins).")";
						error_log($q);
						$ins = $adeQ->query($q);
						$msg = 'Data Berhasil Di Simpan';
						if(!$ins)
						{
							$msg = 'Error Insert Data : '. $q;
							$stt = false;
						}
					}
					
				}

				echo json_encode(['status'=> $stt, 'validate' => $validate, 'msg' => $msg]);
				break;
			
			case 'edit' :

				$upd = array();
				$dtUpd = null;

				foreach($qField as $field)
				{
					$data = isset($_POST[$field['name_field']]) ? $_POST[$field['name_field']] : null;
					
					if($field['type_field'] == 'nm' and !in_array($field['type_input'], ['image', 'file']))
					{
						if($field['validate'] == '1')
						{
							if($field['type_input'] != 'checkbox'){
								$validate_length = ($field['validate_length'] == null) ? strlen($data) : $field['validate_length'];
							}

							
							
							if(empty($data))
							{
								$stt = false;
								$validate[] = array(
									'field' => $field['name_field'],
									'err' => 'validate',
									'msg' => $field['msg_validate']
								);
							}else{
								$validate[] = array(
									'field' => $field['name_field'],
									'err' => '',
									'msg' => $field['msg_validate']
								);
							}

							if($field['type_input'] != 'checkbox')
							{
								if(strlen($data) != $validate_length)
								{
									$stt = false;
									$validate[] = array(
										'field' => $field['name_field'],
										'err' => 'validate',
										'msg' => $field['msg_validate']
									);
								}
							}
						}

						if($field['type_input'] == 'number')
						{
							if($data == null){
								$dtUpd = 'null';
							}else{
								$dtUpd = $data;
							}
							
						}elseif($field['type_input'] == 'date')
						{
							$date = date($datePHP, strtotime($data));
							$dtUpd = "'$date'";
						}elseif($field['type_input'] == 'checkbox')
						{
							$datarr = array();
							if($data != null)
							{
								foreach ($data as $key => $value) {
									$datarr[] = $value;
								} 
							}
							$dtUpd = "'".implode("|", $datarr)."'";	
						}else
						{
							if($data == null){
								$dtUpd = 'null';
							}else{
								$dtUpd = "'".str_replace("'", "`", $data)."'";
							}
							
						}
						
						$upd[] = $field['name_field'] . "= $dtUpd";
					}
					else if($field['type_field'] == 'nm' and in_array($field['type_input'], ['image', 'file'])){

						$files = isset($_FILES[$field['name_field']]) ? $_FILES[$field['name_field']] : null;

						if(empty($files))
						{
							
						}else{
							if($field['type_input'] == 'image'){
								//CEK NAME AND EXT
								$nameFile = $files["name"];
								$cekhack = explode('.', $nameFile);
								$cekExt = array("php", "js", "php5");
								$isImg = @getimagesize($files['tmp_name']);
								$isExt = true;
								
								for($l=0 ; $l < count($cekhack) ; $l++){
									if(in_array(strtolower($cekhack[$l]), $cekExt)){
										$isExt = false;
									}
								}
	
								if($files["size"] > $maxSizeUpload){
									$stt = false;
									$validate[] = array(
										'field' => $field['name_field'],
										'err' => 'validate',
										'msg' => 'Error file size, size must lower then ' . $maxSizeUpload / 1000000 . 'MB'
									);
								}else if($isImg == false){
									$stt = false;
									$validate[] = array(
										'field' => $field['name_field'],
										'err' => 'validate',
										'msg' => 'This not image file'
									);
								}else if($isExt == false){
									$stt = false;
									$validate[] = array(
										'field' => $field['name_field'],
										'err' => 'validate',
										'msg' => 'Error file upload, inject detected'
									);
								}else{
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
								
							}else if($field['type_input'] == 'file'){
								//CEK NAME AND EXT
								$nameFile = $files["name"];
								$cekhack = explode('.', $nameFile);
								$cekExt = array("php", "js", "php5");
								$isExt = true;
								
								for($l=0 ; $l < count($cekhack) ; $l++){
									if(in_array(strtolower($cekhack[$l]), $cekExt)){
										$isExt = false;
									}
								}
	
								if($files["size"] > $maxSizeUpload){
									$stt = false;
									$validate[] = array(
										'field' => $field['name_field'],
										'err' => 'validate',
										'msg' => 'Error file size, size must lower then ' . $maxSizeUpload / 1000000 . 'MB'
									);
								}else if($isExt == false){
									$stt = false;
									$validate[] = array(
										'field' => $field['name_field'],
										'err' => 'validate',
										'msg' => 'Error file upload, inject detected'
									);
								}else{
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

					}
					else if ($field['type_field'] == 'pk')
					{
						$where = $field['name_field'] . "= $data";
					}
					else if($field['type_field'] == 'sys_upd_usr')
					{

						$dtUpd = "'".$_SESSION['userid']."'";
						$upd[] = $field['name_field'] . "= $dtUpd";

					}
					else if($field['type_field'] == 'sys_upd_time')
					{

						$dtUpd = "'".date("Y-m-d H:i:s")."'";
						$upd[] = $field['name_field'] . "= $dtUpd";
					}
					else if($field['type_field'] == 'sub')
					{
						$data = isset($_POST[$field['name_field']]) ? $_POST[$field['name_field']] : null;
						
						if($field['type_input'] == 'number')
						{
							$dtUpd = $data;
						}else if($field['type_input'] == 'text')
						{
							$dtUpd = "'".str_replace("'", "`", $data)."'";
						}

						$upd[] = $field['name_field'] . " = $dtUpd";
					}
				}

				if($stt)
				{
					$q = "update $formCode set ".implode(",", $upd)." where $where";
					error_log($q);
					$update = $adeQ->query($q);
					$msg = 'Data Berhasil Di Perbarui';
					if(!$update)
					{
						$msg = 'Error Edit Data';
						$stt = false;
					}
				}

				echo json_encode(['status'=> $stt, 'validate' => $validate, 'msg' => $msg]);
				break;

			case 'cancel' :
				foreach($qField as $field)
				{
					$data = isset($_POST[$field['name_field']]) ? $_POST[$field['name_field']] : null;
					
					if($field['type_field'] == 'pk')
					{
						$where = $field['name_field'] . "= $data";
					}
				}

				if($stt)
				{
					$q = "update $formCode set status_stock_opname = 'canceled' where $where";
					$del = $adeQ->query($q);
					$msg = 'Stock Opname Canceled Successfully';
					if(!$del)
					{
						$msg = 'Error Stock Opname Canceled';
						$stt = false;
					}
				}

				echo json_encode(['status'=> $stt, 'validate' => $validate, 'msg' => $msg]);

				break;

			case 'copy' : 
				$id = $_POST['id'];

				$copyData = $adeQ->query("insert into data_pengeluaran_spp_header (tanggal, info_pengeluaran, created_by, created_date)
				select DATE_ADD(tanggal, INTERVAL 1 DAY), concat(info_pengeluaran, ' Copy'), '$_SESSION[userid]', now() from data_pengeluaran_spp_header where id= $id");
				if($copyData){
					$getId = $adeQ->select("select h.id from data_pengeluaran_spp_header h
					inner join (select DATE_ADD(tanggal, INTERVAL 1 DAY) tgl, concat(info_pengeluaran, ' Copy') info from data_pengeluaran_spp_header where id=$id) dt
					on h.tanggal=dt.tgl and h.info_pengeluaran=dt.info");

					$copySppDetail = $adeQ->query("
					insert into data_pengeluaran_spp (id_spp_header, id_biodata_anak, jumlah, created_by, created_date)
					select ".$getId[0]['id'].", id_biodata_anak, jumlah, '$_SESSION[userid]', now() from data_pengeluaran_spp
					where id_spp_header=$id
					");

					if($copySppDetail){
						echo json_encode(['status'=> 'success', 'msg' => 'Success Copy Data !!']);
					}else{
						echo json_encode(['status'=> 'error', 'msg' => 'Error Copy Data Detail !!']);
					}
				}else{
					echo json_encode(['status'=> 'error', 'msg' => 'Error Copy Data !!']);
				}
				
		}
	} // close $f
}// close session

?>