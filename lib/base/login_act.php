<?php
require "../../config.php";
require "db.php";
require "getStrukturData.php";
session_start();


if ($login_method == 'otp') {

	if (!empty($_GET['userid']) and !empty($_GET['otp']) and !empty($_GET['xCode'])) {
		$userid = $_GET['userid'];
		$otp = $_GET['otp'];
		$xCode = $_GET['xCode'];

		$q = $adeQ->select($adeQ->prepare("select u.* from core_user u
		where u.isactive=1 and u.userid=%s", $userid));

		$status = '';
		$page = '';
		$data = array();

		if (count($q) > 0) {
			if ($userid == $q[0]['userid']) {
				$getRF = $adeQ->select($adeQ->prepare("select 
									SPLIT_PART(SPLIT_PART(links, '=', 2), '&',1) as form
									from core_vw_rolemenus
									where iduser=%d", $q[0]['id_group_management']));

				$roleForm = array();
				foreach ($getRF as $RF) {
					array_push($roleForm, $RF['form']);
				}

				//if user lock
				if($q[0]['wrongpass'] >= $maxTryLogin){
					echo json_encode(['status' => 'error', 'text' => 'User locked, please contact administrator !!', 'page' => '']);
					exit;
				}

				//check OTP
				$qOTP = $adeQ->select($adeQ->prepare("select * from core_otp where no_handphone=%s
				 and coalesce(expired,0) = 0 and uniq_code=%s and otp=%d and expired_date > now()", $q[0]['no_handphone'], $xCode, $otp));

				if (count($qOTP) == 1) {

					//update OTP
					$updateOTP = $adeQ->query("update core_otp set expired = 1 where uniq_code='$xCode' and coalesce(expired,0) = 0 and 
					no_handphone='".$q[0]['no_handphone']."' and otp=$otp");


					if ($maxTryLogin != 99) {
						if ($q[0]['wrongpass'] <= $maxTryLogin) {
							$fl = 'success';
							$status = 'Login Berhasil';
							$page = './';
							$_SESSION['userid'] = $q[0]['userid'];
							$_SESSION['id_group_management'] = $q[0]['id_group_management'];
							$_SESSION['userUniqId'] = $q[0]['id'];
							$_SESSION['username'] = $q[0]['username'];
							$_SESSION['opd'] = $q[0]['opd'];
							$_SESSION['no_handphone'] = $q[0]['no_handphone'];
							$_SESSION['roleForm'] = $roleForm;
						} else {
							$fl = 'error';
							$status = 'Login Gagal, Akun Anda Terkunci, Mohon hubungi admin untuk unlock user !';
						}
					} else {
						$fl = 'success';
						$status = 'Login Berhasil';
						$page = './';
						$_SESSION['userid'] = $q[0]['userid'];
						$_SESSION['userUniqId'] = $q[0]['id'];
						$_SESSION['id_group_management'] = $q[0]['id_group_management'];
						$_SESSION['username'] = $q[0]['username'];
						$_SESSION['opd'] = $q[0]['opd'];
						$_SESSION['no_handphone'] = $q[0]['no_handphone'];
						$_SESSION['roleForm'] = $roleForm;
					}
				} else {

					$fl = 'error';
					$status = 'Login Gagal, OTP Salah atau Expired !';

					if ($maxTryLogin != 99) {
						$i = $q[0]['wrongpass'] + 1;
						$updWrongPass = $adeQ->query("update core_user set wrongpass=$i where id=" . $q[0]['id']);
					}
				}
			} else {
				$fl = 'error';
				$status = 'Login Gagal, User Tidak Terdaftar !';
			}
		} else {
			$fl = 'error';
			$status = 'Login Gagal, User Tidak Terdaftar !';
		}

		echo json_encode(['status' => $fl, 'text' => $status, 'page' => $page]);
	} else {
		echo json_encode(['status' => 'error', 'text' => 'Mohon isi userid dan password', 'page' => '']);
	}
} else if ($login_method == 'password') {

	if (!empty($_GET['userid']) and !empty($_GET['pass'])) {
		$userid = $_GET['userid'];
		$pass = $_GET['pass'];

		$q = $adeQ->select($adeQ->prepare("select * from core_user where isactive=1 and userid=%s", $userid));

		$status = '';
		$page = '';
		$data = array();

		if (count($q) > 0) {
			if ($userid == $q[0]['userid']) {
				$getRF = $adeQ->select($adeQ->prepare("select 
									SPLIT_PART(SPLIT_PART(links, '=', 2), '&',1) as form
									from core_vw_rolemenus
									where iduser=%d", $q[0]['id']));

				//if user lock
				if($q[0]['wrongpass'] >= $maxTryLogin){
					echo json_encode(['status' => 'error', 'text' => 'User locked, please contact administrator !!', 'page' => '']);
					exit;
				}

				$roleForm = array();
				foreach ($getRF as $RF) {
					array_push($roleForm, $RF['form']);
				}

				if ($hasher->CheckPassword($pass, $q[0]['userpass'])) {

					if ($maxTryLogin != 99) {
						if ($q[0]['wrongpass'] <= $maxTryLogin) {
							$fl = 'success';
							$status = 'Login Berhasil';
							$page = './';
							$_SESSION['userid'] = $q[0]['userid'];
							$_SESSION['userUniqId'] = $q[0]['id'];
							$_SESSION['username'] = $q[0]['username'];
							$_SESSION['no_handphone'] = $q[0]['no_handphone'];
							$_SESSION['roleForm'] = $roleForm;
						} else {
							$fl = 'error';
							$status = 'Login Gagal, Akun Anda Terkunci, Mohon hubungi admin untuk unlock user !';
						}
					} else {
						$fl = 'success';
						$status = 'Login Berhasil';
						$page = './';
						$_SESSION['userid'] = $q[0]['userid'];
						$_SESSION['userUniqId'] = $q[0]['id'];
						$_SESSION['username'] = $q[0]['username'];
						$_SESSION['no_handphone'] = $q[0]['no_handphone'];
						$_SESSION['roleForm'] = $roleForm;
					}
				} else {

					$fl = 'error';
					$status = 'Login Gagal, Password Salah !';

					if ($maxTryLogin != 99) {
						$i = $q[0]['wrongpass'] + 1;
						$updWrongPass = $adeQ->query("update core_user set wrongpass=$i where id=" . $q[0]['id']);
					}
				}
			} else {
				$fl = 'error';
				$status = 'Login Gagal, User Tidak Terdaftar !';
			}
		} else {
			$fl = 'error';
			$status = 'Login Gagal, User Tidak Terdaftar !';
		}

		echo json_encode(['status' => $fl, 'text' => $status, 'page' => $page]);
	} else {
		echo json_encode(['status' => 'error', 'text' => 'Mohon isi userid dan password', 'page' => '']);
	}
}
