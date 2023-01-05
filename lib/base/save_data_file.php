<?php
require "../../config.php";
require "../base/security_login.php";
require "../base/db.php";


if(isset($_SESSION['userid']))
{
	$status_act = $_POST['status_act'];

	switch($status_act){
		case "edit" :
			$idFile = $_POST['idFile'];
			$nameDokumen = $_POST['nameDokumen'];
			$deskripsi = $_POST['deskripsi'];
			$status = "";
			$msg = "";

			//CEK UPDATE HANYA ID USER YG BUAT FILE
			$qCek = $adeQ->select($adeQ->prepare("select * from data_file_store where id=%d", $idFile));

			foreach($qCek as $file){
				$id_user = $file['id_user'];
			}

			//jika id user tidak ada di table di sebabkan hacker
			$id_user = (empty($id_user)) ? 'not_found' : $id_user;


			if($nameDokumen == null)
			{
				$status = "error";
				$msg = "Mohon untuk mengisi data dengan benar !";
			}else{
				if($id_user == $_SESSION['userUniqId']){
					$qUpd = $adeQ->query($adeQ->prepare("
						update data_file_store set 
						name_file=%s,
						deskripsi=%s
						where id=%d
					", $nameDokumen, $deskripsi, $idFile));

					if($qUpd){
						$status = "success";
						$msg = "Data berhasil di perbarui !";
					}else{
						$status = "error";
						$msg = "System Error !";
					}
				}else{
					$status = "error";
					$msg = "Anda bukan pemilik file ini !";
				}
				
			}

			echo json_encode(array("status" => $status, "massage" => $msg));


		break;
		case "delete" :
			$idFile = $_POST['idDelete'];
			//CEK UPDATE HANYA ID USER YG BUAT FILE
			$qCek = $adeQ->select($adeQ->prepare("select * from data_file_store where id=%d", $idFile));

			foreach($qCek as $file){
				$id_user = $file['id_user'];
				$link_file = $file['link_file'];
			}

			//jika id user tidak ada di table di sebabkan hacker
			$id_user = (empty($id_user)) ? 'not_found' : $id_user;
			if($id_user == $_SESSION['userUniqId']){
				$delete = $adeQ->query($adeQ->prepare("
						delete from data_file_store 
						where id=%d
					", $idFile));
				
				if($delete){
					unlink("../../assets/upload/" . $link_file);
					$status = "success";
					$msg = "Data berhasil di hapus !";
				}else{
					$status = "error";
					$msg = "System Error !";
				}
				
			}else{
				$status = "error";
				$msg = "Anda bukan pemilik file ini !";
			}

			echo json_encode(array("status" => $status, "massage" => $msg));

		break;
	}
}// close session

?>