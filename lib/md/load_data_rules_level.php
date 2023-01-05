<?php
require "../../config.php";
require "../base/security_login.php";
require "../base/db.php";



if (isset($_SESSION['userid'])) {

	if (isset($_GET['user']) and isset($_GET['type'])) {
		$f = $_GET['user'];
		$type = $_GET['type'];
		switch ($type) {
			case 'load':

				$q = $adeQ->select($adeQ->prepare("
					select l.id, concat(g.code_bagian, ' - ',l.jabatan) as userid, l.id_jabatan_user, d.id as id_rule from vw_rules_group_level l
					left join core_rules_level d on l.id=d.id_user_level_down and l.id_jabatan_user=d.id_user_level_top
					left join data_jabatan g on l.id=g.id
					where id_jabatan_user=%d order by id_jabatan_user
				", $f));


				$dtRoleArea = array();


				$result = array();

				foreach ($q as $data) {

					array_push($result, $data);
				}

				echo json_encode(['rolemenu' => $result]);
				break;

			case 'save':
				$rlm = $_GET['rlm'];
				$Si = true;

				$dtRlm = explode('~', $rlm);
				$d = $adeQ->query("delete from core_rules_level where id_user_level_top=$f");
				if ($d) {
					for ($i = 0; $i < count($dtRlm); $i++) {
						$ins = $adeQ->query("insert into core_rules_level (id_user_level_top, id_user_level_down) values ($f, $dtRlm[$i])");
						if (!$ins) {
							$Si = false;
						}
					}
				} else {

					$Si = false;
				}

				$result = ($Si) ? 'Rules Level Berhasil Di Simpan' : 'Roles Gagal Di Simpan';
				echo json_encode(['result' => $result]);
				break;
		}
	} // close $f

} // close session
