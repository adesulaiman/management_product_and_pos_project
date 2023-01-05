<?php
require "../../config.php";
require "security_login.php";
require "db.php";


if(isset($_SESSION['userid']))
{

	if(isset($_GET['user']) and isset($_GET['type']))
  	{
		$f = $_GET['user'];
		$type = $_GET['type'];


		switch ($type) {
			case 'load':
				

				$q = $adeQ->select($adeQ->prepare("
						select 
							mn.id,
							mn.menu,
							rlm.id as id_role,
							rlm.iduser,
							case when rlm.id is null then 1 else 0 end flag
							from core_menus as mn left join core_rolemenu as rlm
							on mn.id=rlm.idmenu and rlm.iduser = %d
							where mn.links is not null
							order by mn.menu
					", $f));

				$dtRoleArea = array();

				


				$result = array();
				foreach ($q as $data ) {
					array_push($result, $data);
				}

				echo json_encode(['rolemenu'=>$result]);


				break;
			
			case 'save':
				
				$rlm = $_GET['rlm'];
				$Si = true;

				$dtRlm = explode('~', $rlm);
				$d = $adeQ->query("delete from core_rolemenu where iduser=$f");
				if($d)
				{
					for($i=0; $i < count($dtRlm); $i++)
					{
						$ins = $adeQ->query("insert into core_rolemenu (iduser, idmenu) values ($f, $dtRlm[$i])");
						if(!$ins)
						{
							$Si = false;
						}
					}
				}else{
					$Si = false;
				}
				
				
				$result = ($Si) ? 'Roles Berhasil Di Simpan' : 'Roles Gagal Di Simpan';

				echo json_encode(['result'=>$result]);

				break;
		}
	} // close $f
} // close session

?>