<?php
// cek status stock opname open
$cekSO = $adeQ->select("select count(1) cek from data_stock_opname where status_stock_opname='open'");
if($cekSO[0]['cek'] > 0){
	echo "
	<script>
	swal({
		title: 'Stock Opname still progress, please commit first !!',
		text: 'Please commit stock opname for use transaction',
		icon: 'warning',
	});
	</script>
	";
	exit;
}


?>