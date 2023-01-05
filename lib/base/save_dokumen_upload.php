<?php
require "../../config.php";
require "../base/security_login.php";
require "../base/db.php";
require "../base/setQr_kop_PDF.php";
require "../base/enc.php";
require '../../plugins/phpoffice/autoload.php';


if (isset($_SESSION['userid']) and isset($_POST['tipe_send_dokumen'])) {




	$files = isset($_FILES['dokumen']) ? $_FILES['dokumen'] : null;

	if (empty($files)) {
		echo json_encode(["status" => "error", "msg" => "Please upload file !!"]);
		exit;
	} else {

		if ($_POST['dokumen_type'] == 'single') {

			//CEK NAME AND EXT
			$nameFile = $files["name"];
			$cekhack = explode('.', $nameFile);
			$cekExt = array("pdf");
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

					if (
						isset($_POST['jenis_dokumen']) and
						isset($_POST['nomor']) and
						isset($_POST['tanggal']) and
						isset($_POST['perihal']) and
						isset($_POST['embedPDF'])

					) {
						$qrcode = md5(uniqid());

						//get request nomor dokumen
						$getReqNo = $adeQ->select("select 
						*, concat(ref_no_surat, '/', no_urut, '/', code_opd_tahun) as nomor
						from data_agenda_no_surat where status is null and created_by='$_SESSION[userid]'");
						if (count($getReqNo) < 1) {
							echo json_encode(["status" => "error", "msg" => 'Mohon untuk membuat request nomor surat terlebih dahulu !!']);
							exit;
						}

						$jenis_dokumen = $getReqNo[0]['jenis_dokumen'];
						$nomor = $getReqNo[0]['nomor'];
						$tanggal = $getReqNo[0]['tanggal'];
						$perihal = $getReqNo[0]['perihal'];
						$tujuan = $getReqNo[0]['tujuan'];

						if ($_POST['tipe_send_dokumen'] == 'hirarki') {
							if (!empty($_POST['tte_oleh'])) {
								$ins['dokumen'] = "'$nameFile'";
								$ins['jenis_dokumen'] = "'$jenis_dokumen'";
								$ins['id_user_send_hirarki'] = "'$_POST[tte_oleh]'";
								$ins['id_user_create_dok'] = "'$_SESSION[userUniqId]'";
								$ins['tanggal'] = "'" . date($datePHP, strtotime($tanggal)) . "'";
								$ins['tipe_send_dokumen'] = "'$_POST[tipe_send_dokumen]'";
								$ins['asal_dokumen'] = "'$_SESSION[opd]'";
								$ins['tujuan'] = "'$tujuan'";
								$ins['opd'] = "'$_SESSION[opd]'";
								$ins['qr_code'] = "'$qrcode'";
								$ins['umur_dokumen'] = $_POST['umur'] == null ? 'null' : $_POST['umur'];
								$ins['nomor'] = "'" . str_replace("'", "`", $nomor) . "'";
								$ins['perihal'] = "'" . str_replace("'", "`", $perihal) . "'";
								$ins['created_by'] = "'$_SESSION[userid]'";
								$ins['created_date'] = "now()";
							} else {
								echo json_encode(["status" => "error", "msg" => 'Kelengkapan data harus di isi !!']);
								exit;
							}
						} else if ($_POST['tipe_send_dokumen'] == 'parallel') {

							if (!empty($_POST['template_parallel'])) {
								$ins['dokumen'] = "'$nameFile'";
								$ins['jenis_dokumen'] = "'$jenis_dokumen'";
								$ins['id_parallel_level'] = "'$_POST[template_parallel]'";
								$ins['tanggal'] = "'" . date($datePHP, strtotime($tanggal)) . "'";
								$ins['tipe_send_dokumen'] = "'$_POST[tipe_send_dokumen]'";
								$ins['asal_dokumen'] = "'$_SESSION[opd]'";
								$ins['opd'] = "'$_SESSION[opd]'";
								$ins['qr_code'] = "'$qrcode'";
								$ins['tujuan'] = "'$tujuan'";
								$ins['umur_dokumen'] = $_POST['umur'] == null ? 'null' : $_POST['umur'];
								$ins['nomor'] = "'" . str_replace("'", "`", $nomor) . "'";
								$ins['perihal'] = "'" . str_replace("'", "`", $perihal) . "'";
								$ins['created_by'] = "'$_SESSION[userid]'";
								$ins['created_date'] = "now()";
							} else {
								echo json_encode(["status" => "error", "msg" => 'Kelengkapan data harus di isi !!']);
								exit;
							}
						} else if ($_POST['tipe_send_dokumen'] == 'direct') {
							if (!empty($_POST['tte_oleh'])) {
								$ins['dokumen'] = "'$nameFile'";
								$ins['jenis_dokumen'] = "'$jenis_dokumen'";
								$ins['id_user_send_hirarki'] = "'$_POST[tte_oleh]'";
								$ins['id_user_create_dok'] = "'$_SESSION[userUniqId]'";
								$ins['tanggal'] = "'" . date($datePHP, strtotime($tanggal)) . "'";
								$ins['tipe_send_dokumen'] = "'$_POST[tipe_send_dokumen]'";
								$ins['asal_dokumen'] = "'$_SESSION[opd]'";
								$ins['opd'] = "'$_SESSION[opd]'";
								$ins['qr_code'] = "'$qrcode'";
								$ins['tujuan'] = "'$tujuan'";
								$ins['umur_dokumen'] = $_POST['umur'] == null ? 'null' : $_POST['umur'];
								$ins['nomor'] = "'" . str_replace("'", "`", $nomor) . "'";
								$ins['perihal'] = "'" . str_replace("'", "`", $perihal) . "'";
								$ins['created_by'] = "'$_SESSION[userid]'";
								$ins['created_date'] = "now()";
							} else {
								echo json_encode(["status" => "error", "msg" => 'Kelengkapan data harus di isi !!']);
								exit;
							}
						}



						//set kop, description, footer pdf file

						$setDefaultKop = new setQR_kop_PDF();
						$setDefaultKop->setTanggalSurat($tanggal);
						$setDefaultKop->setNomor($nomor);
						$setDefaultKop->setjenisDok($jenis_dokumen);
						$setDefaultKop->setperihal($perihal);
						$setDefaultKop->setPathPDFSource($updFile);
						$setDefaultKop->setEmbedPDF($_POST['embedPDF']);
						$setDefaultKop->setPathPDFOutput("../../assets/pdffiles/" . $nameFile);

						if ($setDefaultKop->setDefaultKopPDF()) {

							$enc = new EnDecryptText();
							$encrypt = $enc->Encrypt_Text($qrcode);

							$setDefaultKop->setOutQR("../../assets/qrcode/" . $qrcode . ".png");
							$setDefaultKop->setContentQR($dir . "verify.php?enc=" . $encrypt);

							if ($setDefaultKop->generateQR()) {

								$col = array();
								$val = array();
								foreach ($ins as $column => $value) {
									$col[] = $column;
									$val[] = $value;
								}

								$query = "insert into data_dokumen_tte (" . implode(",", $col) . ") select " . implode(",", $val);
								$insDok = $adeQ->query($query);
								if ($insDok) {
									$updReqNo = $adeQ->query("update data_agenda_no_surat set status='use' where status is null and created_by='$_SESSION[userid]'");
									if ($updReqNo) {
										echo json_encode(["status" => "success", "msg" => 'Dokumen berhasil di upload !!']);
									} else {
										echo json_encode(["status" => "error", "msg" => 'Data Error update request nomber surat to db !!']);
									}
								} else {
									echo json_encode(["status" => "error", "msg" => 'Data Error Insert to db !!']);
								}
							} else {
								echo json_encode(["status" => "error", "msg" => 'QR Code error generate !!']);
							}
						} else {
							echo json_encode(["status" => "error", "msg" => 'Default kop, footer, description error embeded !!']);
						}
					} else {
						echo json_encode(["status" => "error", "msg" => 'Kelengkapan data harus di isi !!']);
						exit;
					}
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
				$cekExt = array("pdf");
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

						if (
							isset($_POST['jenis_dokumen']) and
							isset($_POST['nomor']) and
							isset($_POST['tanggal']) and
							isset($_POST['perihal']) and
							isset($_POST['embedPDF'])

						) {


							//get request nomor dokumen
							$getReqNo = $adeQ->select("select 
						*, concat(ref_no_surat, '/', no_urut, '/', code_opd_tahun) as nomor
						from data_agenda_no_surat where status is null and created_by='$_SESSION[userid]'");
							if (count($getReqNo) < 1) {
								echo json_encode(["status" => "error", "msg" => 'Mohon untuk membuat request nomor surat terlebih dahulu !!']);
								exit;
							}

							$jenis_dokumen = $getReqNo[0]['jenis_dokumen'];
							$nomor = $getReqNo[0]['nomor'];
							$tanggal = $getReqNo[0]['tanggal'];
							$perihal = $getReqNo[0]['perihal'];
							$tujuan = $getReqNo[0]['tujuan'];

							if ($_POST['tipe_send_dokumen'] == 'hirarki') {
								if (!empty($_POST['tte_oleh'])) {
									$ins['dokumen'] = "'$nameFile'";
									$ins['jenis_dokumen'] = "'$jenis_dokumen'";
									$ins['id_user_send_hirarki'] = "'$_POST[tte_oleh]'";
									$ins['id_user_create_dok'] = "'$_SESSION[userUniqId]'";
									$ins['tanggal'] = "'" . date($datePHP, strtotime($tanggal)) . "'";
									$ins['tipe_send_dokumen'] = "'$_POST[tipe_send_dokumen]'";
									$ins['asal_dokumen'] = "'$_SESSION[opd]'";
									$ins['tujuan'] = "'$tujuan'";
									$ins['opd'] = "'$_SESSION[opd]'";
									$ins['qr_code'] = "'$qrcode'";
									$ins['umur_dokumen'] = $_POST['umur'] == null ? 'null' : $_POST['umur'];
									$ins['nomor'] = "'" . str_replace("'", "`", $nomor) . "'";
									$ins['perihal'] = "'" . str_replace("'", "`", $perihal) . "'";
									$ins['created_by'] = "'$_SESSION[userid]'";
									$ins['created_date'] = "now()";
								} else {
									echo json_encode(["status" => "error", "msg" => 'Kelengkapan data harus di isi !!']);
									exit;
								}
							} else if ($_POST['tipe_send_dokumen'] == 'parallel') {

								if (!empty($_POST['template_parallel'])) {
									$ins['dokumen'] = "'$nameFile'";
									$ins['jenis_dokumen'] = "'$jenis_dokumen'";
									$ins['id_parallel_level'] = "'$_POST[template_parallel]'";
									$ins['tanggal'] = "'" . date($datePHP, strtotime($tanggal)) . "'";
									$ins['tipe_send_dokumen'] = "'$_POST[tipe_send_dokumen]'";
									$ins['asal_dokumen'] = "'$_SESSION[opd]'";
									$ins['opd'] = "'$_SESSION[opd]'";
									$ins['qr_code'] = "'$qrcode'";
									$ins['tujuan'] = "'$tujuan'";
									$ins['umur_dokumen'] = $_POST['umur'] == null ? 'null' : $_POST['umur'];
									$ins['nomor'] = "'" . str_replace("'", "`", $nomor) . "'";
									$ins['perihal'] = "'" . str_replace("'", "`", $perihal) . "'";
									$ins['created_by'] = "'$_SESSION[userid]'";
									$ins['created_date'] = "now()";
								} else {
									echo json_encode(["status" => "error", "msg" => 'Kelengkapan data harus di isi !!']);
									exit;
								}
							} else if ($_POST['tipe_send_dokumen'] == 'direct') {
								if (!empty($_POST['tte_oleh'])) {
									$ins['dokumen'] = "'$nameFile'";
									$ins['jenis_dokumen'] = "'$jenis_dokumen'";
									$ins['id_user_send_hirarki'] = "'$_POST[tte_oleh]'";
									$ins['id_user_create_dok'] = "'$_SESSION[userUniqId]'";
									$ins['tanggal'] = "'" . date($datePHP, strtotime($tanggal)) . "'";
									$ins['tipe_send_dokumen'] = "'$_POST[tipe_send_dokumen]'";
									$ins['asal_dokumen'] = "'$_SESSION[opd]'";
									$ins['opd'] = "'$_SESSION[opd]'";
									$ins['qr_code'] = "'$qrcode'";
									$ins['tujuan'] = "'$tujuan'";
									$ins['umur_dokumen'] = $_POST['umur'] == null ? 'null' : $_POST['umur'];
									$ins['nomor'] = "'" . str_replace("'", "`", $nomor) . "'";
									$ins['perihal'] = "'" . str_replace("'", "`", $perihal) . "'";
									$ins['created_by'] = "'$_SESSION[userid]'";
									$ins['created_date'] = "now()";
								} else {
									echo json_encode(["status" => "error", "msg" => 'Kelengkapan data harus di isi !!']);
									exit;
								}
							}



							//set kop, description, footer pdf file

							$setDefaultKop = new setQR_kop_PDF();
							$setDefaultKop->setTanggalSurat($tanggal);
							$setDefaultKop->setNomor($nomor);
							$setDefaultKop->setjenisDok($jenis_dokumen);
							$setDefaultKop->setperihal($perihal);
							$setDefaultKop->setPathPDFSource($updFile);
							$setDefaultKop->setEmbedPDF($_POST['embedPDF']);
							$setDefaultKop->setPathPDFOutput("../../assets/pdffiles/" . $nameFile);

							if ($setDefaultKop->setDefaultKopPDF()) {

								$successUpload++;
								$nameMultiFile[] = $nameFile;
							} else {
								echo json_encode(["status" => "error", "msg" => 'Default kop, footer, description error embeded !!']);
							}
						} else {
							echo json_encode(["status" => "error", "msg" => 'Kelengkapan data harus di isi !!']);
							exit;
						}
					} else {
						echo json_encode(["status" => "error", "msg" => 'Error file upload, Upload file not allowed in your system']);
						exit;
					}
				}
			}

			if ($successUpload == $countFile) {

				$enc = new EnDecryptText();
				$encrypt = $enc->Encrypt_Text($qrcode);

				$setDefaultKop->setOutQR("../../assets/qrcode/" . $qrcode . ".png");
				$setDefaultKop->setContentQR($dir . "verify.php?enc=" . $encrypt);

				if ($setDefaultKop->generateQR()) {

					$col = array();
					$val = array();

					//set multiple file with delimeter |
					$ins['dokumen'] = "'" . implode("|", $nameMultiFile) . "'";

					foreach ($ins as $column => $value) {
						$col[] = $column;
						$val[] = $value;
					}

					$query = "insert into data_dokumen_tte (" . implode(",", $col) . ") select " . implode(",", $val);
					$insDok = $adeQ->query($query);
					if ($insDok) {
						$updReqNo = $adeQ->query("update data_agenda_no_surat set status='use' where status is null and created_by='$_SESSION[userid]'");
						if ($updReqNo) {
							echo json_encode(["status" => "success", "msg" => 'Dokumen berhasil di upload !!']);
						} else {
							echo json_encode(["status" => "error", "msg" => 'Data Error update request nomber surat to db !!']);
						}
					} else {
						echo json_encode(["status" => "error", "msg" => 'Data Error Insert to db !!']);
					}
				} else {
					echo json_encode(["status" => "error", "msg" => 'QR Code error generate !!']);
				}
			} else {
				echo json_encode(["status" => "error", "msg" => 'Error file upload, Any file cannot uploaded']);
				exit;
			}
		} else if ($_POST['dokumen_type'] == 'singleDocx') {

			if (
				isset($_POST['jenis_dokumen']) and
				isset($_POST['nomor']) and
				isset($_POST['tanggal']) and
				isset($_POST['perihal']) and
				isset($_POST['docx']) and
				isset($_POST['embedPDF'])

			) {

				$updFile = __DIR__ . "/../../assets/upload/" . $_POST['docx'];
				$pdfFileName = explode(".", $_POST['docx']);
				$pdfFileNameTemp = array();
				for ($i = 0; $i < count($pdfFileName) - 1; $i++) {
					$pdfFileNameTemp[] = $pdfFileName[$i];
				}
				$pdfFileName = implode(".", $pdfFileNameTemp);

				$FilePDF = __DIR__ . "/../../assets/upload/" . $pdfFileName . ".pdf";
				$qrcode = md5(uniqid());


				//get request nomor dokumen
				$getReqNo = $adeQ->select("select 
						*, concat(ref_no_surat, '/', no_urut, '/', code_opd_tahun) as nomor
						from data_agenda_no_surat where status is null and created_by='$_SESSION[userid]'");
				if (count($getReqNo) < 1) {
					echo json_encode(["status" => "error", "msg" => 'Mohon untuk membuat request nomor surat terlebih dahulu !!']);
					exit;
				}

				$jenis_dokumen = $getReqNo[0]['jenis_dokumen'];
				$nomor = $getReqNo[0]['nomor'];
				$tanggal = $getReqNo[0]['tanggal'];
				$perihal = $getReqNo[0]['perihal'];
				$tujuan = $getReqNo[0]['tujuan'];

				if ($_POST['tipe_send_dokumen'] == 'hirarki') {
					if (!empty($_POST['tte_oleh'])) {
						$ins['dokumen'] = "'$nameFile'";
						$ins['jenis_dokumen'] = "'$jenis_dokumen'";
						$ins['id_user_send_hirarki'] = "'$_POST[tte_oleh]'";
						$ins['id_user_create_dok'] = "'$_SESSION[userUniqId]'";
						$ins['tanggal'] = "'" . date($datePHP, strtotime($tanggal)) . "'";
						$ins['tipe_send_dokumen'] = "'$_POST[tipe_send_dokumen]'";
						$ins['asal_dokumen'] = "'$_SESSION[opd]'";
						$ins['tujuan'] = "'$tujuan'";
						$ins['opd'] = "'$_SESSION[opd]'";
						$ins['qr_code'] = "'$qrcode'";
						$ins['umur_dokumen'] = $_POST['umur'] == null ? 'null' : $_POST['umur'];
						$ins['nomor'] = "'" . str_replace("'", "`", $nomor) . "'";
						$ins['perihal'] = "'" . str_replace("'", "`", $perihal) . "'";
						$ins['created_by'] = "'$_SESSION[userid]'";
						$ins['created_date'] = "now()";
					} else {
						echo json_encode(["status" => "error", "msg" => 'Kelengkapan data harus di isi !!']);
						exit;
					}
				} else if ($_POST['tipe_send_dokumen'] == 'parallel') {

					if (!empty($_POST['template_parallel'])) {
						$ins['dokumen'] = "'$nameFile'";
						$ins['jenis_dokumen'] = "'$jenis_dokumen'";
						$ins['id_parallel_level'] = "'$_POST[template_parallel]'";
						$ins['tanggal'] = "'" . date($datePHP, strtotime($tanggal)) . "'";
						$ins['tipe_send_dokumen'] = "'$_POST[tipe_send_dokumen]'";
						$ins['asal_dokumen'] = "'$_SESSION[opd]'";
						$ins['opd'] = "'$_SESSION[opd]'";
						$ins['qr_code'] = "'$qrcode'";
						$ins['tujuan'] = "'$tujuan'";
						$ins['umur_dokumen'] = $_POST['umur'] == null ? 'null' : $_POST['umur'];
						$ins['nomor'] = "'" . str_replace("'", "`", $nomor) . "'";
						$ins['perihal'] = "'" . str_replace("'", "`", $perihal) . "'";
						$ins['created_by'] = "'$_SESSION[userid]'";
						$ins['created_date'] = "now()";
					} else {
						echo json_encode(["status" => "error", "msg" => 'Kelengkapan data harus di isi !!']);
						exit;
					}
				} else if ($_POST['tipe_send_dokumen'] == 'direct') {
					if (!empty($_POST['tte_oleh'])) {
						$ins['dokumen'] = "'$nameFile'";
						$ins['jenis_dokumen'] = "'$jenis_dokumen'";
						$ins['id_user_send_hirarki'] = "'$_POST[tte_oleh]'";
						$ins['id_user_create_dok'] = "'$_SESSION[userUniqId]'";
						$ins['tanggal'] = "'" . date($datePHP, strtotime($tanggal)) . "'";
						$ins['tipe_send_dokumen'] = "'$_POST[tipe_send_dokumen]'";
						$ins['asal_dokumen'] = "'$_SESSION[opd]'";
						$ins['opd'] = "'$_SESSION[opd]'";
						$ins['qr_code'] = "'$qrcode'";
						$ins['tujuan'] = "'$tujuan'";
						$ins['umur_dokumen'] = $_POST['umur'] == null ? 'null' : $_POST['umur'];
						$ins['nomor'] = "'" . str_replace("'", "`", $nomor) . "'";
						$ins['perihal'] = "'" . str_replace("'", "`", $perihal) . "'";
						$ins['created_by'] = "'$_SESSION[userid]'";
						$ins['created_date'] = "now()";
					} else {
						echo json_encode(["status" => "error", "msg" => 'Kelengkapan data harus di isi !!']);
						exit;
					}
				}

				// generate QR Code
				$enc = new EnDecryptText();
				$encrypt = $enc->Encrypt_Text($qrcode);

				$setDefaultKop->setOutQR("../../assets/qrcode/" . $qrcode . ".png");
				$setDefaultKop->setContentQR($dir . "verify.php?enc=" . $encrypt);

				if ($setDefaultKop->generateQR()) {

					//replace variable QR in word 
					$template = new \PhpOffice\PhpWord\TemplateProcessor($updFile);
					$template->setImageValue('qrCode', [
						"path" => __DIR__ . "../../assets/qrcode/" . $qrcode . ".png",
						"width" => "200",
						"height" => "200",
					]);
					$template->saveAs('edited.docx');

					shell_exec('unoconv -fpdf ' . $updFile);

					if (file_exists($FilePDF)) {

						$col = array();
						$val = array();
						foreach ($ins as $column => $value) {
							$col[] = $column;
							$val[] = $value;
						}

						//set kop, description, footer pdf file
						$setDefaultKop = new setQR_kop_PDF();
						$setDefaultKop->setTanggalSurat($tanggal);
						$setDefaultKop->setNomor($nomor);
						$setDefaultKop->setjenisDok($jenis_dokumen);
						$setDefaultKop->setperihal($perihal);
						$setDefaultKop->setPathPDFSource($FilePDF);
						$setDefaultKop->setEmbedPDF($_POST['embedPDF']);
						$setDefaultKop->setPathPDFOutput("../../assets/pdffiles/" . $nameFile);

						if ($setDefaultKop->setDefaultKopPDF()) {
							$query = "insert into data_dokumen_tte (" . implode(",", $col) . ") select " . implode(",", $val);
							$insDok = $adeQ->query($query);
							if ($insDok) {
								$updReqNo = $adeQ->query("update data_agenda_no_surat set status='use' where status is null and created_by='$_SESSION[userid]'");
								if ($updReqNo) {
									echo json_encode(["status" => "success", "msg" => 'Dokumen berhasil di upload !!']);
								} else {
									echo json_encode(["status" => "error", "msg" => 'Data Error update request nomber surat to db !!']);
								}
							} else {
								echo json_encode(["status" => "error", "msg" => 'Data Error Insert to db !!']);
							}
						} else {
							echo json_encode(["status" => "error", "msg" => 'Default kop, footer, description error embeded !!']);
						}
					} else {
						echo json_encode(["status" => "error", "msg" => 'Error convert docx to pdf !!']);
					}
				} else {
					echo json_encode(["status" => "error", "msg" => 'QR Code error generate !!']);
				}
			} else {
				echo json_encode(["status" => "error", "msg" => 'Kelengkapan data harus di isi !!']);
				exit;
			}
		} else {
			echo json_encode(["status" => "error", "msg" => 'Error file upload, please set dokumen type, group or single']);
			exit;
		}
	}
}// close session
