<?php
require "../../config.php";
require "../base/security_login.php";
require "../base/db.php";
require "../base/setQr_kop_PDF.php";
require "../base/enc.php";


if (isset($_SESSION['userid'])) {


	$files = isset($_FILES['dokumen']) ? $_FILES['dokumen'] : null;

	if (empty($files)) {
		echo json_encode(["status" => "error", "msg" => "Please upload file !!"]);
		exit;
	} else {

		if ($_POST['dokumen_type'] == 'single') {

			//CEK NAME AND EXT
			$nameFile = $files["name"];
			$cekhack = explode('.', $nameFile);
			$cekExt = array("docx");
			$isExt = true;
			$ins = array();

			if (!in_array(strtolower($cekhack[count($cekhack) - 1]), $cekExt)) {
				$isExt = false;
			}

			if ($files["size"] > $maxSizeUpload) {
				echo json_encode(["status" => "error", "msg" => 'Error file size, size must lower then ' . $maxSizeUpload / 1000000 . 'MB']);
				exit;
			} else if ($isExt == false) {

				echo json_encode(["status" => "error", "msg" => 'Error file upload, inject detected']);
				exit;
			} else {
				$nameFile = str_replace("'", "`", $nameFile);
				$nameFile = uniqid() . "_$nameFile";
				$updFile = __DIR__ . "/../../assets/upload/" . $nameFile;
				if (move_uploaded_file($files["tmp_name"], $updFile)) {
					echo json_encode(["status" => "success", "msg" => 'Docx berhasil di upload !!', 'docx' => $nameFile]);
				} else {
					echo json_encode(["status" => "error", "msg" => 'Error file upload, Upload file not allowed in your system']);
					exit;
				}
			}
		} else if ($_POST['dokumen_type'] == 'group') {
			//UPLOAD GROUP DOKUMEN
			//CEK NAME AND EXT
			$countFile = count($files["name"]);
			$successUpload = 0;
			$nameMultiFile = [];
			$qrcode = md5(uniqid());

			for ($i = 0; $i < $countFile; $i++) {
				$nameFile = $files["name"][$i];
				$cekhack = explode('.', $nameFile);
				$cekExt = array("docx");
				$isExt = true;
				$ins = array();

				if (!in_array(strtolower($cekhack[count($cekhack) - 1]), $cekExt)) {
					$isExt = false;
				}

				if ($files["size"][$i] > $maxSizeUpload) {
					echo json_encode(["status" => "error", "msg" => 'Error file size, size must lower then ' . $maxSizeUpload / 1000000 . 'MB']);
					exit;
				} else if ($isExt == false) {

					echo json_encode(["status" => "error", "msg" => 'Error file upload, inject detected']);
					exit;
				} else {
					$nameFile = str_replace("'", "`", $nameFile);
					$nameFile = uniqid() . "_$nameFile";
					$updFile = __DIR__ . "/../../assets/upload/" . $nameFile;
					if (move_uploaded_file($files["tmp_name"][$i], $updFile)) {
						$successUpload++;
					} else {
						echo json_encode(["status" => "error", "msg" => 'Error file upload, Upload file not allowed in your system']);
						exit;
					}
				}
			}

			if ($successUpload == $countFile) {
				echo json_encode(["status" => "success", "msg" => 'Docx berhasil di upload !!']);
			} else {
				echo json_encode(["status" => "error", "msg" => 'Error file upload, Any file cannot uploaded']);
				exit;
			}
		} else {
			echo json_encode(["status" => "error", "msg" => 'Error file upload, please set dokumen type, group or single']);
			exit;
		}
	}
}// close session
