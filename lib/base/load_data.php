<?php
require "../../config.php";
require "security_login.php";
require "db.php";
require "enc.php";
$enc = new EnDecryptText();

if(isset($_SESSION['userid']))
{
	if(isset($_GET['f']) and isset($_GET['t']))
  	{
		$t = $_GET['t'];
		$f = $_GET['f'];


		$qField = $adeQ->select($adeQ->prepare(
		    "select * from core_fields where id_form=%d and active is true order by id", $f
		));

		## Read value
		$draw = $_GET['draw'];
		$row = $_GET['start'];
		$rowperpage = $_GET['length']; // Rows display per page
		$columnIndex = $_GET['order'][0]['column']; // Column index
		$columnName = $_GET['columns'][$columnIndex]['data']; // Column name
		$columnSortOrder = $_GET['order'][0]['dir']; // asc or desc
		$searchValue = $_GET['search']['value']; // Search value

		$query = isset($_GET['query']) ? $_GET['query'] : null;

		if(empty($query))
		{
			$where = '';
		}else{
			$where = "where $query";
		}
		

		## Total number of records without filtering
		$sel = $adeQ->select("select count(*) as allcount from $t $where");
		$totalRecords = $sel[0]['allcount'];

		## Total number of records with filtering
		$sel = $adeQ->select("select count(*) as allcount from $t $where"/*.$searchQuery*/);
		$totalRecordwithFilter = $sel[0]['allcount'];

		## Fetch records
		if($dbRDBMS == 'mysql')
		{
			if($rowperpage == -1)
			{
				$limitQuery = '';
			}else{
				$limitQuery = " limit ".$row.",".$rowperpage;
			}
		}elseif($dbRDBMS == 'pgsql')
		{
			if($rowperpage == -1)
			{
				$limitQuery = '';
			}else{
				$limitQuery = " limit ".$rowperpage." offset ".$row;
			}
			
		}

		$empQuery = "select * from $t $where order by ".$columnName." ".$columnSortOrder.$limitQuery;
		$empRecords = $adeQ->select($empQuery);
		$data = array();


		foreach ($empRecords as $row ) {
			$dataArr = array();
			foreach($qField as $coll)
			{
				if($coll['type_input'] == 'date')
				{
					$dataArr[$coll['name_field']] = date($datePHP, strtotime($row[$coll['name_field']]));
				}else{
					if($coll['encrypt'] == 1){
						$dataArr[$coll['name_field']] = $enc->Decrypt_Text($row[$coll['name_field']]);
					}else{
						$dataArr[$coll['name_field']] = $row[$coll['name_field']];
					}
				}
				
			}
			$data[] = $dataArr;
		}



		## Response
		$response = array(
		  "draw" => intval($draw),
		  "recordsTotal" => $totalRecords,
		  "recordsFiltered" => $totalRecordwithFilter,
		  "data" => $data
		);

		echo json_encode($response);
	}//close $f $t
}// close session

?>