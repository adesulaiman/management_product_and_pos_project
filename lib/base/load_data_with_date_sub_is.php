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
		$w = $_GET['w'];


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
			$where = "where $w";
		}else{
			$where = "where $w and $query";
		}
		

        $t = "(
            with base as (
                select `id` AS `id`,`invoice` AS `invoice`,
				case 
					when d.method = 'Emas Murni' then format(`d`.`nominal`,3) 
					else format(`d`.`nominal`,0) end 
				AS `nominal`,
                payment_date,
                method,
                bank,
                format(`gram_gold_price`,2) AS `gram_gold_price`,
                (select sum(emas_murni) from data_product_out_resaller_detail where invoice =d.invoice) total_emas_murni,
				case 
					when d.method = 'Emas Murni' then `d`.`nominal` 
					else nominal / gram_gold_price end 
				as kadar
                from `data_resaller_payment` d
            )
            select id, invoice,payment_date, 
            format(total_emas_murni - sum(kadar) over (order by id), 3),
            nominal, concat(method,  coalesce(concat(' - ', bank), '')) method, gram_gold_price, 
			format(kadar, 3) kadar,
			case when method = 'Emas Murni' 
				then concat('<span style=\"font-size:20px; color:green;font-weight:bolder\">', format(`base`.`total_emas_murni` - sum(`base`.`nominal`) over ( order by `base`.`id`),3),'</span>')
				else 
					concat('<span style=\"font-size:20px; color:green;font-weight:bolder\">', format(`base`.`total_emas_murni` - sum(`base`.`kadar`) over ( order by `base`.`id`),3),'</span>')
				end
			AS `reminder_rate_gold` 
            from base  $where
        ) t";
            

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
