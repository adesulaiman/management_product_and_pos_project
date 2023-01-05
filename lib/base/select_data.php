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


		if ($roleFilter == 'yes') {

			$getRoleArea = $adeQ->select("select * from core_rolearea where iduser=$_SESSION[userUniqId]");

			foreach ($getRoleArea as $value) {

				if ($value['tablearea'] == $t) {

					$roleFilter = ($value['idarea'] == 0) ? "id" : $value['idarea'];

					if ($f == 'all') {

						$qfind = (empty($search)) ? "" : "and lower(text) like '%" . strtolower($search) . "%'";
						$q = $adeQ->select("select * from $t where id=$roleFilter $qfind");


						foreach ($q as $data) {
							array_push($result, $data);
						}

						echo json_encode(['results' => $result]);
					} else {
						$qfind = (empty($search)) ? "" : "and lower(text) like '%" . strtolower($search) . "%'";
						$q = $adeQ->select("select * from $t where filter='$f' and id=$roleFilter $qfind");


						foreach ($q as $data) {
							array_push($result, $data);
						}

						echo json_encode(['results' => $result]);
					}
				}
			}
		} else {
			if ($f == 'all') {
				if(isset($_GET['sub'])){
					
					$qfind = (empty($search)) ? "" : "and lower(text) like '%" . strtolower($search) . "%'";
					$q = $adeQ->select("select * from $t where id not in 
					(select id_user from data_level_custom where id_template=$_GET[sub]) 
					$qfind limit 100");

					
				}else{
					$qfind = (empty($search)) ? "" : "where lower(text) like '%" . strtolower($search) . "%'";
					$q = $adeQ->select("select * from $t $qfind limit 100");
				}


				foreach ($q as $data) {
					array_push($result, $data);
				}

				echo json_encode(['results' => $result]);
			} else {
				$qfind = (empty($search)) ? "" : "and lower(text) like '%" . strtolower($search) . "%'";
				$q = $adeQ->select("select * from $t where filter='$f' $qfind limit 100");
				foreach ($q as $data) {
					array_push($result, $data);
				}

				echo json_encode(['results' => $result]);
			}
		}
	} //close $f
}//close session
