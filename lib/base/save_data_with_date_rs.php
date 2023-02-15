<?php
require "../../config.php";
require "../base/security_login.php";
require "../base/db.php";


if(isset($_SESSION['userid']))
{

	if(isset($_POST['f']) and isset($_POST['formType']))
  	{

		$f = $_POST['f'];
		$formType = $_POST['formType'];
		$qField = $adeQ->select($adeQ->prepare(
		    "select * from core_fields where id_form=%d and active is true order by id", $f
		));

		$qForm = $adeQ->select($adeQ->prepare(
		    "select * from core_forms where idform=%d", $f
		));

		foreach($qForm as $valForm)
		{
		  $formName = $valForm['formname'];
		  $formDesc = $valForm['description'];
		  $formCode = $valForm['formcode'];
		}

		$stt = "success";
		$validate = array();
		$bypass = array();
		$ins = array();
		$fieldNm = array();
		$msg = '';

		switch ($formType) {
			case 'commit' :
                $invoice = str_replace("'", "`", $_POST['invoice']);
                $q = "update data_product_out_resaller set status_payment='paid off' where invoice = '$invoice'";
                $del = $adeQ->query($q);
                $msg = 'Paid Off Successfully';
                if(!$del)
                {
                    $msg = 'Error Paid Off';
                    $stt = "error";
                }

				echo json_encode(['status'=> $stt, 'info' => $msg]);

				break;

				
		}
	} // close $f
}// close session

?>