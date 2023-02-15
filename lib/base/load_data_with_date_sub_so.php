<?php
require "../../config.php";
require "../base/security_login.php";
require "../base/db.php";

if(isset($_SESSION['userid']))
{
	if(isset($_GET['f']) and isset($_GET['t']))
  	{
		
		$f = $_GET['f'];
		$w = $_GET['w'];
		$iscommit = $_GET['iscommit'];

		if($iscommit){

			$t = "(
				select 
				p.id,
				p.id_stock_opname,
				p.barcode id_barcode,
				p.id_brutto_gram_product,
				p.storage_name,
				concat(p.barcode, ' - ', p.product_name) product,
				concat(p.qty_phisycal, ' pcs (',p.gram_phisycal,' Brutto Gr)') physical_stock,
				concat(p.qty_stock, ' pcs (',p.brutto_gram,' Brutto Gr)') system_stock,
				concat(p.qty_adjusment, ' pcs (',p.brutto_gram_adjusment,' Brutto Gr)') adjusment,
				case 
					when p.gap_qty < 0 then concat('<span style=\"color:red;font-size:17px\">',p.gap_qty, ' pcs (',p.gap_gram,' Gr)','</span>')
					when p.gap_qty = 0 then concat('<span style=\"color:blue;font-size:17px\">',p.gap_qty, ' pcs (',p.gap_gram,' Gr)','</span>')
					else concat('<span style=\"color:green;font-size:17px\">+ ',p.gap_qty, ' pcs (',p.gap_gram,' Gr)','</span>')
				end  difference
				from 
				(
					select 
					d.id,
					d.id_stock_opname,
					c.storage_name,
					d.barcode,
					d.product_name,
					d.qty_phisycal,
					p.netto_gram id_netto_gram_product,
					p.brutto_gram id_brutto_gram_product,
					d.qty_phisycal * coalesce(p.brutto_gram, 0) gram_phisycal,
					coalesce(s.qty_stock, 0) qty_stock,
					coalesce(s.brutto_gram, 0) brutto_gram,
					coalesce(d.qty_adjusment, 0) qty_adjusment,
					coalesce(d.qty_adjusment, 0) * coalesce(p.brutto_gram, 0) brutto_gram_adjusment,
					(d.qty_phisycal - coalesce(s.qty_stock, 0)) + coalesce(d.qty_adjusment, 0)  gap_qty,
					((d.qty_phisycal * coalesce(p.brutto_gram, 0) ) - coalesce(s.brutto_gram, 0)) + (coalesce(d.qty_adjusment, 0) * coalesce(p.brutto_gram, 0)) gap_gram
					from data_stock_opname_detail d
					left join data_product p on d.barcode=p.barcode
					left join data_category_storage c on d.id_category_storage = c.id
					left join data_stock_product s on d.id_category_storage = s.id_category_storage and d.barcode=s.barcode
				) p
			) x";

			
		}else{
			$t = "(
				select 
				p.id,
				id_stock_opname,
				p.barcode id_barcode,
				'' id_gram_product,
				p.storage_name,
				concat(p.barcode, ' - ', p.product_name) product,
				concat(p.qty_phisycal, ' pcs (',p.brutto_gram_physycal,' Brutto Gr)') physical_stock,
				concat(p.qty_stock, ' pcs (',p.brutto_gram_stock,' Brutto Gr)') system_stock,
				concat(p.qty_adjusment, ' pcs (',p.brutto_gram_adjusment,' Brutto Gr)') adjusment,
				case 
					when p.qty_diff < 0 then concat('<span style=\"color:red;font-size:17px\">',p.qty_diff, ' pcs (',p.gram_diff,' Gr)','</span>')
					when p.qty_diff = 0 then concat('<span style=\"color:blue;font-size:17px\">',p.qty_diff, ' pcs (',p.gram_diff,' Gr)','</span>')
					else concat('<span style=\"color:green;font-size:17px\">+ ',p.qty_diff, ' pcs (',p.gram_diff,' Gr)','</span>')
				end  difference
				from 
				(
					select 
					d.id,
					c.storage_name,
					d.id_stock_opname,
					d.id_category_storage,
					d.barcode,
					d.product_name,
					d.qty_phisycal,
					d.brutto_gram_physycal,
					d.qty_stock,
					d.brutto_gram_stock,
					d.qty_adjusment,
					d.brutto_gram_adjusment,
					d.qty_diff,
					d.brutto_gram_diff gram_diff,
					d.created_by,
					d.created_date
					from data_stock_opname_detail_report d
					left join data_category_storage c on d.id_category_storage = c.id
				) p
			) x";
		}
		

		$qField = $adeQ->select($adeQ->prepare(
		    "select * from information_schema.columns where table_name=%s order by ordinal_position", 'vw_data_detail_stock'
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

?>