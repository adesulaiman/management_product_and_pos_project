<?php
require "../../config.php";
require "db.php";
require "wa_api.php";
session_start();


if (!empty($_POST['method'])) {
	$method = $_POST['method'];
	$fl = '';
	$status = '';

	if ($method == 'login') {

		if (!empty($_POST['userid'])) {
			$userid = $_POST['userid'];
			$uniq_id = '';

			$q = $adeQ->select($adeQ->prepare("select * from core_user where isactive=1 and userid=%s", $userid));
			if (count($q) > 0) {
				$no_handphone  = $q[0]['no_handphone'];
				if (empty($no_handphone)) {
					$fl = 'error';
					$status = 'No handphone anda belum terdaftar !!';
				} else {

					//process OTP
					$otp = rand(111111, 999999);
					$uniq_id = md5(uniqid());
					$qUpd = $adeQ->query("update core_otp set expired = 1 where created_date < now() and coalesce(expired, 0)=0 and no_handphone='$no_handphone'");

					$qIns = $adeQ->query("insert into core_otp (otp, no_handphone, uniq_code, created_date, expired_date)
					select '$otp', '$no_handphone', '$uniq_id', now(), now() + INTERVAL $expired_otp MINUTE
					");

					if ($qIns) {
						if (sendWA($no_handphone, "Berikut adalah kode OTP anda untuk login \n\nKode OTP : \n\n*$otp*\n\njangan berikan kode OTP anda kepada siapapun !!")) {
							$fl = 'success';
							$status = 'OTP Telah Terkirim';
						} else {
							$uniq_id = ''; 
							$fl = 'error';
							$status = 'OTP Gagal Terkirim !';
						}
					} else {
						$uniq_id = '';
						$fl = 'error';
						$status = 'Error System !';
					}
				}
			} else {
				$fl = 'error';
				$status = 'Login Gagal, User Tidak Terdaftar !';
			}

			echo json_encode(['status' => $fl, 'text' => $status, 'uniqid' => $uniq_id]);
		} else {
			echo json_encode(['status' => 'error', 'text' => 'Mohon isi userid untuk mendapatkan OTP', 'uniqid' => '']);
		}
	} else if ($method == 'session') {
		// proses with session

		$no_handphone  = $_SESSION['no_handphone'];
		if (empty($no_handphone)) {
			$fl = 'error';
			$status = 'No handphone anda belum terdaftar !!';
		} else {

			//process OTP
			$otp = rand(111111, 999999);
			$uniq_id = md5(uniqid());
			$qUpd = $adeQ->query("update core_otp set expired = 1 where created_date < now() and coalesce(expired, 0)=0 and no_handphone='$no_handphone'");

			$qIns = $adeQ->query("insert into core_otp (otp, no_handphone, uniq_code, created_date, expired_date)
					select '$otp', '$no_handphone', '$uniq_id', now(), now() + INTERVAL $expired_otp MINUTE
					");

			if ($qIns) {
				if (sendWA($no_handphone, "Berikut adalah kode OTP anda untuk melakukan *Approval/Paraf* atau *Sign/Tanda Tangan* \n\nKode OTP : \n\n*$otp*\n\njangan berikan kode OTP anda kepada siapapun !!\n\n:::TTE Sampang Hebat Bermartabat:::")) {
					$fl = 'success';
					$status = 'OTP Telah Terkirim';
				} else {
					$uniq_id = '';
					$fl = 'error';
					$status = 'OTP Gagal Terkirim !';
				}
			} else {
				$uniq_id = '';
				$fl = 'error';
				$status = 'Error System !';
			}
		}


		echo json_encode(['status' => $fl, 'text' => $status, 'uniqid' => $uniq_id]);
	} else if ($method == 'uniqid_dokumen') {
		if (!empty($_POST['uniq_id'])) {
			$uniq_id_dok = $_POST['uniq_id'];
			$uniq_id = '';

			$q = $adeQ->select($adeQ->prepare("select 
			u.no_handphone
			from data_dokumen_forward_tte f
			left join core_user u on f.id_user=u.id
			where f.uniq_id=%s", $uniq_id_dok));

			if (count($q) > 0) {
				$no_handphone  = $q[0]['no_handphone'];
				if (empty($no_handphone)) {
					$fl = 'error';
					$status = 'No handphone anda belum terdaftar !!';
				} else {

					//process OTP
					$otp = rand(111111, 999999);
					$uniq_id = md5(uniqid());
					$qUpd = $adeQ->query("update core_otp set expired = 1 where created_date < now() and coalesce(expired, 0)=0 and no_handphone='$no_handphone'");

					$qIns = $adeQ->query("insert into core_otp (otp, no_handphone, uniq_code, created_date, expired_date)
					select '$otp', '$no_handphone', '$uniq_id', now(), now() + INTERVAL $expired_otp MINUTE
					");

					if ($qIns) {
						if (sendWA($no_handphone, "Berikut adalah kode OTP anda untuk melakukan *Approval/Paraf* atau *Sign/Tanda Tangan* \n\nKode OTP : \n\n*$otp*\n\njangan berikan kode OTP anda kepada siapapun !!\n\n:::TTE Sampang Hebat Bermartabat:::")) {
							$fl = 'success';
							$status = 'OTP Telah Terkirim';
						} else {
							$uniq_id = '';
							$fl = 'error';
							$status = 'OTP Gagal Terkirim !';
						}
					} else {
						$uniq_id = '';
						$fl = 'error';
						$status = 'Error System !';
					}
				}
			} else {
				$fl = 'error';
				$status = 'Login Gagal, User Tidak Terdaftar !';
			}

			echo json_encode(['status' => $fl, 'text' => $status, 'uniqid' => $uniq_id]);
		} else {
			echo json_encode(['status' => 'error', 'text' => 'Mohon isi userid untuk mendapatkan OTP', 'uniqid' => '']);
		}
	}
} else {
	echo json_encode(['status' => 'error', 'text' => 'Mohon isi userid untuk mendapatkan OTP', 'uniqid' => '']);
}
