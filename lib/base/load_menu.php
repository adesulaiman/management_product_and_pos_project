<?php
//require('../../security/session.php');
require('../../config.php');
require "security_login.php";
require('db.php');

$idUser = $_SESSION['id_group_management'];
//echo $idrolegroup;
$menus=""; 


$qParent = $adeQ->select("
	select * from core_vw_rolemenus
	where idmenus in (
	    select distinct parent from core_vw_rolemenus
	    where iduser=$idUser
	)
	order by cast(replace(idmenus, '-', '') as decimal)
");





foreach($qParent as $row) {
  $menus.='{"idmenus":"'.$row['idmenus'].'", "idmodule":"'.$row['idmodule'].'","menu":"'.$row['menu'].'",  "icon":"'.$row['icon'].'","withframe":"'.$row['withframe'].'", "parent":"'.$row['parent'].'", "child":"'.$row['child'].'", "links":"'.$row['links'].'"},';
}

$SQL = "select * from core_vw_rolemenus where iduser=$idUser and coalesce(sub_page, 0) <> 1 order by parent,idmenus ";
$db = $adeQ->select($SQL);

foreach($db as $row) {
  $menus.='{"idmenus":"'.$row['idmenus'].'", "idmodule":"'.$row['idmodule'].'","menu":"'.$row['menu'].'",  "icon":"'.$row['icon'].'","withframe":"'.$row['withframe'].'", "parent":"'.$row['parent'].'", "child":"'.$row['child'].'", "links":"'.$row['links'].'"},';
}


$menus    = substr($menus,0, strlen($menus)-1); 
echo '['.$menus.']';
//logInfo($menus);
?>
