<?php
require "../../config.php";
require "../base/security_login.php";
require "../base/db.php";
require "../base/enc.php";

$enc = new EnDecryptText();

if(isset($_SESSION['userid']))
{

	if(isset($_GET['f']) and isset($_GET['type']))
	{

		$f = $_GET['f'];
		$type = $_GET['type'];
		$v = isset($_GET['v']) ? $_GET['v'] : null;

		$qForm = $adeQ->select($adeQ->prepare(
		    "select * from core_forms where idform=%d", $f
		));


		$qGroupField = $adeQ->select($adeQ->prepare(
		    "select distinct groupname from core_fields where id_form=%d and active is true and groupname is not null", $f
		));

		foreach($qForm as $valForm)
		{
		  $formName = $valForm['formname'];
		  $formCode = $valForm['formcode'];
		  $formDesc = $valForm['description'];
		}

		$type1 = array('text', 'email', 'password', 'number');
		$type2 = array('select');
		$type3 = array('checkbox');
		$type4 = array('date');
		$type5 = array('textarea');
		$type6 = array('image');
		$type7 = array('file');
		
		$form = '';
		if($type == 'add')
		{

			//create slide group expect header\
			$formHeader = '';
			$groupForm = "
			<div class='row col-md-12'>
			  <div class='nav-tabs-custom'>
				<ul class='nav nav-tabs'>
			";
			foreach($qGroupField as $group)
			{
				if($group['groupname'] != 'header')
				{
					$groupForm .= "
						<li class='nav-item'>
						    <a class='nav-link' id='$group[groupname]-tab' data-toggle='tab' href='#$group[groupname]' role='tab' aria-controls='$group[groupname]' aria-selected='true'>$group[groupname]</a>
						</li>
					";
				}
			}
			$groupForm .= "
					</ul>
					<div class='tab-content'>
			";


			foreach($qGroupField as $group)
			{
				$qField = $adeQ->select($adeQ->prepare(
				    "select * from core_fields where id_form=%d and groupname=%s and active is true order by id", $f, $group['groupname']
				));

				if($group['groupname'] == 'header')
				{
					$formHeader .= "<div class='row group-$group[groupname]'>";

					foreach($qField as $valField)
						{
							if($valField['type_field'] == 'nm')
							{

								$mustBeVall = ($valField['validate'] == 't') ? "<span style='color:red'>*</span>" : "";

								$descField = str_replace("_", " ", $valField['name_field']);
								$descField = str_replace("id ", "", $descField);
								$descField = ucfirst($descField);
								$placeholder = $valField['placeholder'];
								$help = $valField['help_context'] == null ? "" : "<i class='fa fa-fw fa-info-circle' data-toggle='tooltip' title='$valField[help_context]'></i>";
								$mask = ($valField['format_type'] != null) ? "data-inputmask='\"mask\": \"[$valField[format_type]]\"' data-mask" : null;

								if(in_array($valField['type_input'], $type1))
								{
									$formHeader .= "
										<div class='form-group grp$valField[name_field] col-md-$valField[position_md]'>
								            <label for='$valField[name_field]'>$descField $mustBeVall $help</label>
								            <input $mask type='$valField[type_input]' name='$valField[name_field]' class='form-control $valField[name_field]' placeholder='$placeholder'>
								            <span class='help-block err$valField[name_field]'></span>
								        </div>
									";
								}elseif(in_array($valField['type_input'], $type2))
								{
									$formHeader .= "
										<div class='form-group grp$valField[name_field] col-md-$valField[position_md]'>
								            <label for='$valField[name_field]'>$descField $mustBeVall $help</label>
								            <select class='form-control $valField[name_field]' name='$valField[name_field]' style='width: 100%;'>
								            	<option value=''>$placeholder</option>
								            </select>
								            <span class='help-block err$valField[name_field]'></span>
								        </div>
									";
								}elseif(in_array($valField['type_input'], $type3))
								{
									$formHeader .= "
										<div class='form-group grp$valField[name_field] col-md-$valField[position_md]'>
								            <label for='$valField[name_field]'>$descField $mustBeVall $help</label>
								            <select class='form-control $valField[name_field]' name='$valField[name_field][]' multiple='multiple' style='width: 100%;'>
								            	
								            </select>
								            <span class='help-block err$valField[name_field]'></span>
								        </div>
									";
								}elseif(in_array($valField['type_input'], $type4))
								{
									$formHeader .= "
										<div class='form-group grp$valField[name_field] col-md-$valField[position_md]'>
								            <label for='$valField[name_field]'>$descField $mustBeVall $help</label>
								            <input type='text' class='datepicker form-control $valField[name_field]' name='$valField[name_field]' placeholder='$placeholder'>
								            <span class='help-block err$valField[name_field]'></span>
								        </div>
									";
								}elseif(in_array($valField['type_input'], $type5))
								{
									$formHeader .= "
										<div class='form-group grp$valField[name_field] col-md-$valField[position_md]'>
											<label for='$valField[name_field]'>$descField $mustBeVall $help</label>
											<textarea class='form-control $valField[name_field]' name='$valField[name_field]' rows='3' placeholder='$placeholder'></textarea>
								            <span class='help-block err$valField[name_field]'></span>
								        </div>
									";
								}elseif(in_array($valField['type_input'], $type6))
								{
									$formHeader .= "
										<div class='form-group grp$valField[name_field] col-md-$valField[position_md]'>
											<label for='$valField[name_field]'>
												<h5>$descField $mustBeVall $help</h5>
												<img src='".$dir."assets/img/noimage.jpg' style='border: 2px dashed grey;margin: 10px 0px;width:100px' class='$valField[name_field]-img'/>
											</label>
											<input type='file' accept='image/*' class='form-control $valField[name_field]-input' name='$valField[name_field]' placeholder='$placeholder'/>
								            <span class='help-block err$valField[name_field]'></span>
								        </div>
									";
								}elseif(in_array($valField['type_input'], $type7))
								{
									$formHeader .= "
										<div class='form-group grp$valField[name_field] col-md-$valField[position_md]'>
										<label for='$valField[name_field]'>
											<h5>$descField $mustBeVall $help</h5>
											<img src='".$dir."assets/img/file.png' style='border: 2px dashed grey;margin: 10px 0px;width:100px' class='$valField[name_field]-img'/>
										</label>
										<input type='file' class='form-control $valField[name_field]-input' name='$valField[name_field]' placeholder='$placeholder'/>
										<span class='help-block err$valField[name_field]'></span>
									</div>
									";
								}
							}
						}
					//close group	
					$formHeader .= '</div>';

				}else{

					$groupForm .= "<div class='tab-pane row' id='$group[groupname]'>";

					foreach($qField as $valField)
						{
							if($valField['type_field'] == 'nm')
							{

								$mustBeVall = ($valField['validate'] == 't') ? "<span style='color:red'>*</span>" : "";

								$descField = str_replace("_", " ", $valField['name_field']);
								$descField = str_replace("id ", "", $descField);
								$descField = ucfirst($descField);
								$mask = ($valField['format_type'] != null) ? "data-inputmask='\"mask\": \"[$valField[format_type]]\"' data-mask" : null;
								$placeholder = $valField['placeholder'];
								$help = $valField['help_context'] == null ? "" : "<i class='fa fa-fw fa-info-circle' data-toggle='tooltip' title='$valField[help_context]'></i>";

								if(in_array($valField['type_input'], $type1))
								{
									$groupForm .= "
										<div class='form-group grp$valField[name_field] col-md-$valField[position_md]'>
								            <label for='$valField[name_field]'>$descField $mustBeVall $help</label>
								            <input $mask type='$valField[type_input]' name='$valField[name_field]' class='form-control $valField[name_field]' placeholder='$placeholder'>
								            <span class='help-block err$valField[name_field]'></span>
								        </div>
									";
								}elseif(in_array($valField['type_input'], $type2))
								{
									$groupForm .= "
										<div class='form-group grp$valField[name_field] col-md-$valField[position_md]'>
								            <label for='$valField[name_field]'>$descField $mustBeVall $help</label>
								            <select class='form-control $valField[name_field]' name='$valField[name_field]' style='width: 100%;'>
								            	<option value=''>$placeholder</option>
								            </select>
								            <span class='help-block err$valField[name_field]'></span>
								        </div>
									";
								}elseif(in_array($valField['type_input'], $type3))
								{
									$groupForm .= "
										<div class='form-group grp$valField[name_field] col-md-$valField[position_md]'>
								            <label for='$valField[name_field]'>$descField $mustBeVall $help</label>
								            <select class='form-control $valField[name_field]' name='$valField[name_field][]' multiple='multiple' style='width: 100%;'>
								            	
								            </select>
								            <span class='help-block err$valField[name_field]'></span>
								        </div>
									";
								}elseif(in_array($valField['type_input'], $type4))
								{
									$groupForm .= "
										<div class='form-group grp$valField[name_field] col-md-$valField[position_md]'>
								            <label for='$valField[name_field]'>$descField $mustBeVall $help</label>
								            <input type='text' class='datepicker form-control $valField[name_field]' name='$valField[name_field]' placeholder='$placeholder'>
								            <span class='help-block err$valField[name_field]'></span>
								        </div>
									";
								}elseif(in_array($valField['type_input'], $type5))
								{
									$groupForm .= "
										<div class='form-group grp$valField[name_field] col-md-$valField[position_md]'>
											<label for='$valField[name_field]'>$descField $mustBeVall $help</label>
											<textarea class='form-control $valField[name_field]' name='$valField[name_field]' rows='3' placeholder='$placeholder'></textarea>
								            <span class='help-block err$valField[name_field]'></span>
								        </div>
									";
								}elseif(in_array($valField['type_input'], $type6))
								{
									$groupForm .= "
										<div class='form-group grp$valField[name_field] col-md-$valField[position_md]'>
											<label for='$valField[name_field]'>
												<h5>$descField $mustBeVall $help</h5>
												<img src='".$dir."assets/img/noimage.jpg' style='border: 2px dashed grey;margin: 10px 0px;width:100px' class='$valField[name_field]-img'/>
											</label>
											<input type='file' accept='image/*' class='form-control $valField[name_field]-input' name='$valField[name_field]' placeholder='$placeholder'/>
								            <span class='help-block err$valField[name_field]'></span>
								        </div>
									";
								}elseif(in_array($valField['type_input'], $type7))
								{
									$groupForm .= "
										<div class='form-group grp$valField[name_field] col-md-$valField[position_md]'>
											<label for='$valField[name_field]'>
												<h5>$descField $mustBeVall $help</h5>
												<img src='".$dir."assets/img/file.jpg' style='border: 2px dashed grey;margin: 10px 0px;width:100px' class='$valField[name_field]-img'/>
											</label>
											<input type='file' class='form-control $valField[name_field]-input' name='$valField[name_field]' placeholder='$placeholder'/>
								            <span class='help-block err$valField[name_field]'></span>
								        </div>
									";
								}
							}
						}
					//close group	
					$groupForm .= '</div>';
				}

			} // close loop group
			
			$groupForm .= '</div></div></div>'; //close content tabs
			$form .= $formHeader;
			$form .= $groupForm;

			

		}
		else if($type == 'edit')
		{
			
			//create slide group expect header\
			$value = $adeQ->select($adeQ->prepare("select * from $formCode where id=%s", $v));

			$formHeader = '';
			$groupForm = "
			<div class='row col-md-12'>
			  <div class='nav-tabs-custom'>
				<ul class='nav nav-tabs'>
			";
			foreach($qGroupField as $group)
			{
				if($group['groupname'] != 'header')
				{
					$groupForm .= "
						<li class='nav-item'>
						    <a class='nav-link' id='$group[groupname]-tab' data-toggle='tab' href='#$group[groupname]' role='tab' aria-controls='$group[groupname]' aria-selected='true'>$group[groupname]</a>
						</li>
					";
				}
			}
			
			$groupForm .= "
					</ul>
					<div class='tab-content'>
			";


			foreach($qGroupField as $group)
			{
				$qField = $adeQ->select($adeQ->prepare(
				    "select * from core_fields where id_form=%d and groupname=%s and active is true order by id", $f, $group['groupname']
				));

				if($group['groupname'] == 'header')
				{
					$formHeader .= "<div class='row group-$group[groupname]'>";

					foreach($qField as $valField)
						{
							if($valField['type_field'] == 'nm')
							{

								$mustBeVall = ($valField['validate'] == 't') ? "<span style='color:red'>*</span>" : "";

								$descField = str_replace("_", " ", $valField['name_field']);
								$descField = str_replace("id ", "", $descField);
								$descField = ucfirst($descField);
								$mask = ($valField['format_type'] != null) ? "data-inputmask='\"mask\": \"[$valField[format_type]]\"' data-mask" : null;
								$placeholder = $valField['placeholder'];
								$help = $valField['help_context'] == null ? "" : "<i class='fa fa-fw fa-info-circle' data-toggle='tooltip' title='$valField[help_context]'></i>";

								if(in_array($valField['type_input'], $type1))
								{
									$dataText = $valField['encrypt'] == 1 ? $enc->Decrypt_Text($value[0][$valField['name_field']]) : $value[0][$valField['name_field']];
									$formHeader .= "
										<div class='form-group grp$valField[name_field] col-md-$valField[position_md]'>
								            <label for='$valField[name_field]'>$descField $mustBeVall $help</label>
								            <input $mask type='$valField[type_input]' name='$valField[name_field]' class='form-control $valField[name_field]' value='".$dataText."' placeholder='$placeholder'>
								            <span class='help-block err$valField[name_field]'></span>
								        </div>
									";
								}elseif(in_array($valField['type_input'], $type2))
								{
									preg_match('/(?<=t=).*/',$valField['link_type_input'], $dbSelect);
									$dbS = str_replace("&filter=all", "", $dbSelect[0]);
									$vSelectBox = $adeQ->select($adeQ->prepare("select * from $dbS where id=%s", $value[0][$valField['name_field']]));
									$valSelect = "<option value='".$value[0][$valField['name_field']]."' selected></option>";
									foreach($vSelectBox as $dataSelect){
										$valSelect = "<option value='".$value[0][$valField['name_field']]."' selected>".$vSelectBox[0]['text']."</option>";
									}

									$formHeader .= "
										<div class='form-group grp$valField[name_field] col-md-$valField[position_md]'>
								            <label for='$valField[name_field]'>$descField $mustBeVall $help</label>
								            <select class='form-control $valField[name_field]' name='$valField[name_field]' style='width: 100%;'>
													$valSelect
								            </select>
								            <span class='help-block err$valField[name_field]'></span>
								        </div>
									";
								}elseif(in_array($valField['type_input'], $type3))
								{
									preg_match('/(?<=t=).*/',$valField['link_type_input'], $dbSelect);
									$dbS = str_replace("&filter=all", "", $dbSelect[0]);
									$keyId = explode("|", $value[0][$valField['name_field']]);
									$vSelectBox = $adeQ->select("select * from $dbS where id in (".implode(",", $keyId).")");
									$sQ = '';
									foreach ($vSelectBox as $key) {
										$sQ .= "<option value='$key[id]' selected>$key[text]</option>";
									}

									$formHeader .= "
										<div class='form-group grp$valField[name_field]'>
								            <label for='$valField[name_field]'>$descField $mustBeVall $help</label>
								            <select class='form-control $valField[name_field]' name='$valField[name_field][]' multiple='multiple' style='width: 100%;'>
								     			$sQ       	
								            </select>
								            <span class='help-block err$valField[name_field]'></span>
								        </div>
									";
								}elseif(in_array($valField['type_input'], $type4))
								{
									$date = (empty($value[0][$valField['name_field']])) ? null : date($datePHPJS ,strtotime($value[0][$valField['name_field']]));
									$formHeader .= "
										<div class='form-group grp$valField[name_field] col-md-$valField[position_md]'>
								            <label for='$valField[name_field]'>$descField $mustBeVall $help</label>
								            <input type='text' class='datepicker form-control $valField[name_field]' name='$valField[name_field]' placeholder='$placeholder' value='$date'>
								            <span class='help-block err$valField[name_field]'></span>
								        </div>
									";
								}elseif(in_array($valField['type_input'], $type5))
								{
									$formHeader .= "
										<div class='form-group grp$valField[name_field] col-md-$valField[position_md]'>
											<label for='$valField[name_field]'>$descField $mustBeVall $help</label>
											<textarea class='form-control $valField[name_field]' name='$valField[name_field]' rows='3' placeholder='$placeholder'>".$value[0][$valField['name_field']]."</textarea>
								            <span class='help-block err$valField[name_field]'></span>
								        </div>
									";
								}elseif(in_array($valField['type_input'], $type6))
								{
									$formHeader .= "
										<div class='form-group grp$valField[name_field] col-md-$valField[position_md]'>
											<label for='$valField[name_field]'>
												<h5>$descField $mustBeVall $help</h5>
												<img src='".$dir."assets/upload/".$value[0][$valField['name_field']]."' style='border: 2px dashed grey;margin: 10px 0px;width:100px' class='$valField[name_field]-img'/>
											</label>
											<input type='file' accept='image/*' class='form-control $valField[name_field]-input' name='$valField[name_field]' placeholder='$placeholder'/>
								            <span class='help-block err$valField[name_field]'></span>
								        </div>
									";
								}elseif(in_array($valField['type_input'], $type7))
								{
									$formHeader .= "
										<div class='form-group grp$valField[name_field] col-md-$valField[position_md]'>
											<label for='$valField[name_field]'>
												<h5>$descField $mustBeVall $help</h5>
												<img src='".$dir."assets/img/file.png' style='border: 2px dashed grey;margin: 10px 0px;width:100px' class='$valField[name_field]-img'/>
											</label>
											<input type='file' class='form-control $valField[name_field]-input' name='$valField[name_field]' placeholder='$placeholder'/>
								            <span class='help-block err$valField[name_field]'></span>
								        </div>
									";
								}
							}
						}
					//close group	
					$formHeader .= '</div>';

				}else{

					$groupForm .= "<div class='tab-pane row' id='$group[groupname]'>";

					foreach($qField as $valField)
						{
							if($valField['type_field'] == 'nm')
							{

								$mustBeVall = ($valField['validate'] == 't') ? "<span style='color:red'>*</span>" : "";

								$descField = str_replace("_", " ", $valField['name_field']);
								$descField = str_replace("id ", "", $descField);
								$descField = ucfirst($descField);
								$mask = ($valField['format_type'] != null) ? "data-inputmask='\"mask\": \"[$valField[format_type]]\"' data-mask" : null;
								$help = $valField['help_context'] == null ? "" : "<i class='fa fa-fw fa-info-circle' data-toggle='tooltip' title='$valField[help_context]'></i>";

								if(in_array($valField['type_input'], $type1))
								{
									$dataText = $valField['encrypt'] == 1 ? $enc->Decrypt_Text($value[0][$valField['name_field']]) : $value[0][$valField['name_field']];
									$groupForm .= "
										<div class='form-group grp$valField[name_field] col-md-$valField[position_md]'>
								            <label for='$valField[name_field]'>$descField $mustBeVall $help</label>
								            <input $mask type='$valField[type_input]' name='$valField[name_field]' class='form-control $valField[name_field]' value='".$dataText."' placeholder='$placeholder'>
								            <span class='help-block err$valField[name_field]'></span>
								        </div>
									";
								}elseif(in_array($valField['type_input'], $type2))
								{
									preg_match('/(?<=t=).*/',$valField['link_type_input'], $dbSelect);
									$dbS = str_replace("&filter=all", "", $dbSelect[0]);
									$vSelectBox = $adeQ->select($adeQ->prepare("select * from $dbS where id=%s", $value[0][$valField['name_field']]));

									$groupForm .= "
										<div class='form-group grp$valField[name_field] col-md-$valField[position_md]'>
								            <label for='$valField[name_field]'>$descField $mustBeVall $help</label>
								            <select class='form-control $valField[name_field]' name='$valField[name_field]' style='width: 100%;'>
								            	<option value='".$value[0][$valField['name_field']]."' selected>".$vSelectBox[0]['text']."</option>
								            </select>
								            <span class='help-block err$valField[name_field]'></span>
								        </div>
									";
								}elseif(in_array($valField['type_input'], $type3))
								{
									preg_match('/(?<=t=).*/',$valField['link_type_input'], $dbSelect);
									$dbS = str_replace("&filter=all", "", $dbSelect[0]);
									$keyId = explode("|", $value[0][$valField['name_field']]);
									$vSelectBox = $adeQ->select("select * from $dbS where id in (".implode(",", $keyId).")");
									$sQ = '';
									foreach ($vSelectBox as $key) {
										$sQ .= "<option value='$key[id]' selected>$key[text]</option>";
									}

									$groupForm .= "
										<div class='form-group grp$valField[name_field] col-md-$valField[position_md]'>
								            <label for='$valField[name_field]'>$descField $mustBeVall $help</label>
								            <select class='form-control $valField[name_field]' name='$valField[name_field][]' multiple='multiple' style='width: 100%;'>
								     			$sQ       	
								            </select>
								            <span class='help-block err$valField[name_field]'></span>
								        </div>
									";
								}elseif(in_array($valField['type_input'], $type4))
								{
									$date = (empty($value[0][$valField['name_field']])) ? null : date($datePHP ,strtotime($value[0][$valField['name_field']]));
									$groupForm .= "
										<div class='form-group grp$valField[name_field] col-md-$valField[position_md]'>
								            <label for='$valField[name_field]'>$descField $mustBeVall $help</label>
								            <input type='text' class='datepicker form-control $valField[name_field]' name='$valField[name_field]' placeholder='$placeholder' value='$date'>
								            <span class='help-block err$valField[name_field]'></span>
								        </div>
									";
								}elseif(in_array($valField['type_input'], $type5))
								{
									$groupForm .= "
										<div class='form-group grp$valField[name_field] col-md-$valField[position_md]'>
											<label for='$valField[name_field]'>$descField $mustBeVall $help</label>
											<textarea class='form-control $valField[name_field]' name='$valField[name_field]' rows='3' placeholder='$placeholder'>".$value[0][$valField['name_field']]."</textarea>
								            <span class='help-block err$valField[name_field]'></span>
								        </div>
									";
								}elseif(in_array($valField['type_input'], $type6))
								{
									$groupForm .= "
										<div class='form-group grp$valField[name_field] col-md-$valField[position_md]'>
											<label for='$valField[name_field]'>
												<h5>$descField $mustBeVall $help</h5>
												<img src='".$dir."assets/upload/".$value[0][$valField['name_field']]."' style='border: 2px dashed grey;margin: 10px 0px;width:100px' class='$valField[name_field]-img'/>
											</label>
											<input type='file' accept='image/*' class='form-control $valField[name_field]-input' name='$valField[name_field]' placeholder='$placeholder'/>
								            <span class='help-block err$valField[name_field]'></span>
								        </div>
									";
								}elseif(in_array($valField['type_input'], $type7))
								{
									$groupForm .= "
										<div class='form-group grp$valField[name_field] col-md-$valField[position_md]'>
											<label for='$valField[name_field]'>
												<h5>$descField $mustBeVall $help</h5>
												<img src='".$dir."assets/img/file.png' style='border: 2px dashed grey;margin: 10px 0px;width:100px' class='$valField[name_field]-img'/>
											</label>
											<input type='file' accept='image/*' class='form-control $valField[name_field]-input' name='$valField[name_field]' placeholder='$placeholder'/>
								            <span class='help-block err$valField[name_field]'></span>
								        </div>
									";
								}
							}
						}
					//close group	
					$groupForm .= '</div>';
				}

			} // close loop group
			
			$qField = $adeQ->select($adeQ->prepare(
				    "select * from core_fields where id_form=%d and active is true and type_field = 'pk' order by id", $f
				));

			foreach ($qField as $field) {
				$formID = "
				   <input type='hidden' class='form-control $field[name_field]' name='$field[name_field]' value='".$value[0][$field['name_field']]."'>
					";
			}


			$groupForm .= '</div></div></div>'; //close content tabs
			$form .= $formHeader;
			$form .= $groupForm;
			$form .= $formID;




		}else if($type == 'delete')
		{
			$qField = $adeQ->select($adeQ->prepare(
			    "select * from core_fields where id_form=%d and active is true order by id", $f
			));

			$value = $adeQ->select($adeQ->prepare("select * from $formCode where id=%s", $v));

			$form .= "<p>Apakah anda yakin ingin menghapus data ini ?</p>";

			foreach($qField as $valField)
			{
				if($valField['type_field'] == 'pk')
				{
					$form .= "
					   <input type='hidden' class='form-control $valField[name_field]' name='$valField[name_field]' value='".$value[0][$valField['name_field']]."'>
						";
				}
			}
		}else if($type == 'search')
		{
			$qField = $adeQ->select($adeQ->prepare(
			    "select * from core_fields where id_form=%d and active is true and search_enable=1 order by id", $f
			));
			$selectForm = "<select class='form-control filter' name='filter[]'>";
			foreach($qField as $valField)
			{
				// if($valField['type_field'] == 'nm')
				// {
					$selectForm .= "<option value='$valField[name_field]'>$valField[placeholder]</option>";
				// }
			}
			$selectForm .= "</select>";

			$logicForm = "<select class='form-control logic' name='logic[]'>";
			$qLogic = $adeQ->select("select * from core_logic");
			foreach($qLogic as $logic)
			{
				$logicForm .= "<option value='$logic[logic]'>$logic[description]</option>";
			}
			$logicForm .= "</select>";

			$form .= "
				<button id='b1' type='button' class='btn add-more' type='button'>Klik untuk menambah filter </button>
				<br>
				<div class='formFilter'>
					<div class='row formRow'>
						<div class='col-md-4'>
							$selectForm
						</div>
						<div class='col-md-2'>
							$logicForm
						</div>
						<div class='col-md-4'>
							<input type='text' class='form-control valueFilter' name='valueFilter[]'/> 
						</div>
						
					</div>
				</div>
			";
		}

		$form .= "<input type='hidden' class='form-control form$type' name='formType' value='$type'>";

		$type = ucfirst($type) ." $formDesc";


		$arr = array(
			'type' => $type,
			'data' => $form
		);

		echo json_encode($arr);

	} // close $f and $type
}// close session
?>