<?php
require "../../config.php";
require "../base/security_login.php";
require "../base/db.php";


if(isset($_SESSION['userid']))
{

	if(isset($_GET['id']))
	{
		$type = $_GET['type'];

		switch($type){
			case "edit":
				$f = $_GET['id'];
				$qFile = $adeQ->select($adeQ->prepare(
					"select * from data_file_store where id=%d", $f
				));
				
				$data = "";
				foreach($qFile as $file){
					$data = '
						<div class="form-group">
							<h5>Nama Dokumen : </h5>
							<input type="text" class="form-control" name="nameDokumen" id="nameDokumen" value="'.$file['name_file'].'" placeholder="Masukan Nama Dokumen">
						</div>

						<div class="form-group">
							<h5>Deskripsi : </h5>
							<input type="text" class="form-control" name="deskripsi" id="deskripsi" value="'.$file['deskripsi'].'" placeholder="Masukan Deskripsi">
						</div>

						<div class="form-group formDeleteFile">
							<h5>File : </h5>
							<a target="_blank" href="'.$dir.'assets/upload/'.$file['link_file'].'">
								<i style="font-size:50px" class="fa fa-fw fa-file-pdf-o"></i>
							</a>
							<button type="button" class="btn btn-danger btn-sm" id="delete_file">Delete File</button>
						</div>
						
						<div class="row uploadNew" id="drag-and-drop-zone" style="display:none">
						
							<div class="col-md-5  d-md-block  d-sm-none">
							<label for="file-input">
								<h5>Upload File : </h5>
								<img src="'.$dir.'assets/img/noimage.jpg" style="border: 2px dashed grey;margin: 10px 0px;width:100px"/>
							</label>
							
							<input id="file-input" type="file" />
							<div class="progress progress-sm active" style="margin:0;width:60%;height:15px">
								<div class="progress-bar progress-bar-success progress-bar-striped progress-upload" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%"></div>
							</div>
							<span class="nameFile" style="font-weight:bold; font-size:12px"></span>
							
							</div>
						</div>

						<input type="hidden" name="status_delete" value="0"/>
						<input type="hidden" name="statusUpload" value=""/>
						<input type="hidden" name="status_act" value="edit"/>
						<input type="hidden" name="idFile" value="'.$file['id'].'"/>
					
					';
				}

				echo json_encode(array("massage" => "success", "data" => $data));

			break;

			case "delete":
				
				$f = $_GET['id'];
				$value = $adeQ->select($adeQ->prepare("select * from data_file_store where id=%s", $f));

				$form = "<p>Apakah anda yakin ingin menghapus data ini ?</p>";

				$form .= "
						<input type='hidden' name='status_act' value='delete'/>
						<input type='hidden' class='form-control' name='idDelete' value='".$value[0]['id']."'>";
				
				echo json_encode(array("status"=>"success", "data"=>$form, "type"=>"Delete File"));
			break;
		}

	} // close $f and $type
}// close session
?>