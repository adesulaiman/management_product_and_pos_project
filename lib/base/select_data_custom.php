<?php
require "../../config.php";
require "security_login.php";
require "db.php";

if (isset($_SESSION['userid'])) {
	if (isset($_GET['filter']) and isset($_GET['t'])) {
		$t = $_GET['t'];
		$f = $_GET['filter'];
		$flag = isset($_GET['flag']) ? $_GET['flag'] : null;
		$roleFilter = isset($_GET['rolefilter']) ? $_GET['rolefilter'] : null;


		$search = isset($_GET['search']) ? $_GET['search'] : null;
		$result = array();


		// $default = array('id' => '0', 'text' => 'Select All');
		// array_push($result, $default);


		if ($roleFilter == 'level_hirarki') {
			$qfind = (empty($search)) ? "" : "and lower(text) like '%" . strtolower($search) . "%'";
			$q = $adeQ->select("select * from vw_select_level_hirarki where id_jabatan in ($_SESSION[struktur_hirarki]) $qfind");


			foreach ($q as $data) {
				array_push($result, $data);
			}

			echo json_encode(['results' => $result]);
		} else {
			if ($f == 'all') {
				$qfind = (empty($search)) ? "" : "where lower(text) like '%" . strtolower($search) . "%'";
				$q = $adeQ->select("select * from $t $qfind");


				foreach ($q as $data) {
					array_push($result, $data);
				}

				echo json_encode(['results' => $result]);
			} else {
				$qfind = (empty($search)) ? "" : "and lower(text) like '%" . strtolower($search) . "%'";
				$q = $adeQ->select("select * from $t where filter='$f' $qfind");
				foreach ($q as $data) {
					array_push($result, $data);
				}

				echo json_encode(['results' => $result]);
			}
		}
	} //close $f
}//close session
