<?php
require "../../config.php";
require "security_login.php";
require "db.php";

if(isset($_SESSION['userid']))
{
	if(isset($_GET['groupId']) and isset($_GET['method']))
  	{
		$groupId = $_GET['groupId'];
		$method = $_GET['method'];

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
			if($method == 'send'){
				$where = "where id_user=$_SESSION[userUniqId] and send_group_id=$groupId";
			}else if($method == 'receive'){
				$where = "where send_group_id=$_SESSION[id_group_rules]";
			}
			
		}else{
			if($method == 'send'){
				$where = "where id_user=$_SESSION[userUniqId] and send_group_id=$groupId and $query";
			}else if($method == 'receive'){
				$where = "where send_group_id=$_SESSION[id_group_rules] and $query";
			}
			
		}
		

		## Total number of records without filtering
		$sel = $adeQ->select("select count(*) as allcount from data_file_store $where");
		$totalRecords = $sel[0]['allcount'];

		## Total number of records with filtering
		$sel = $adeQ->select("select count(*) as allcount from data_file_store $where"/*.$searchQuery*/);
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
		
		$empQuery = "
		select 
		id,
		concat('<a target=\"_blank\" href=\"".$dir."assets/upload/' , link_file , '\">',name_file,'</a>') as name_file,
		deskripsi
		from data_file_store $where order by ".$columnName." ".$columnSortOrder.$limitQuery;
		$empRecords = $adeQ->select($empQuery);
		$data = array();

		$i = 1;
		foreach ($empRecords as $row ) {
			$dataArr = array();
			$dataArr['id'] = $row['id'];
			$dataArr['no'] = $i;
			$dataArr['name_file'] = $row['name_file'];
			$dataArr['deskripsi'] = $row['deskripsi'];
			$data[] = $dataArr;
			$i++;
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