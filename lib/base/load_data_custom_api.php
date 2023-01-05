<?php
require "../../config.php";
require "../base/security_login.php";
require "../base/db.php";

if(isset($_SESSION['userid']))
{
	if(isset($_GET['f']) and isset($_GET['t']))
  	{
		$t = $_GET['t'];
		$f = $_GET['f'];


		$qField = $adeQ->select($adeQ->prepare(
		    "select * from information_schema.columns where table_name=%s order by ordinal_position", $t
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
			$where = "";
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
				if($coll['DATA_TYPE'] == 'date')
				{
					$dataArr[$coll['COLUMN_NAME']] = date($datePHP, strtotime($row[$coll['COLUMN_NAME']]));
				}else if($coll['COLUMN_NAME'] == 'nama_web_consume'){
					//FOR GET DATA CHECKBOX WEB CONSUME
					$qCheckBox = $adeQ->select("select * from data_web_consume where id in (".str_replace("|",",", $row[$coll['COLUMN_NAME']]).")");
					$dataCheckbox = array();
					foreach($qCheckBox as $qBox){
						$dataCheckbox[] = $qBox['nama_web'];
					}
					$dataArr[$coll['COLUMN_NAME']] = implode("<br>", $dataCheckbox);

				}else if($coll['COLUMN_NAME'] == 'url_web_consume'){
					//FOR GET DATA CHECKBOX WEB CONSUME
					$qCheckBox = $adeQ->select("select * from data_web_consume where id in (".str_replace("|",",", $row[$coll['COLUMN_NAME']]).")");
					$dataCheckbox = array();
					foreach($qCheckBox as $qBox){
						$dataCheckbox[] = $qBox['url_web'];
					}
					$dataArr[$coll['COLUMN_NAME']] = implode("<br>", $dataCheckbox);
				
				}else{
					$dataArr[$coll['COLUMN_NAME']] = $row[$coll['COLUMN_NAME']];
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