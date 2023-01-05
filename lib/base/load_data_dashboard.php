<?php
require "../../config.php";
require "security_login.php";
require "db.php";


if(isset($_SESSION['userid']))
{
	if(isset($_GET['bulan'])){

		$bulan = $_GET['bulan'];

		$qSaldo = $adeQ->select(
			"select
			concat('Rp. ',FORMAT(
			  coalesce((select sum(nominal) from data_pemasukan where date_format(tanggal, \"%Y%m\") <= '$bulan'), 0)
			  +
			  coalesce((select sum(nominal) from data_pemasukan_mal where date_format(tanggal, \"%Y%m\") <= '$bulan'), 0)
			-
			coalesce((select sum(nominal) from data_pengeluaran_mal where date_format(tanggal, \"%Y%m\") <= '$bulan'), 0)
			-
			coalesce((select sum(nominal) from data_pengeluaran_lainnya where date_format(tanggal, \"%Y%m\") <= '$bulan'), 0)
			-
			coalesce((select sum(s.jumlah) from data_pengeluaran_spp_header h
			 inner join data_pengeluaran_spp s on h.id=s.id_spp_header
			 where date_format(h.tanggal, \"%Y%m\") <= '$bulan'), 0)
			-
			coalesce((
			select sum(s.banyak_barang * s.harga_satuan) from data_pengeluaran_belanja_header h
			inner join data_pengeluaran_belanja s on h.id=s.id_belanja_header
			where date_format(h.tanggal, \"%Y%m\") <= '$bulan'
			), 0)
			, 0)) as saldo,
			
			concat('Rp. ',FORMAT(
			coalesce((select sum(nominal) from data_pemasukan where date_format(tanggal, \"%Y%m\") = '$bulan'),0) 
			+
			  coalesce((select sum(nominal) from data_pemasukan_mal where date_format(tanggal, \"%Y%m\") <= '$bulan'), 0)
			, 0)) as total_pemasukan,
		  
			concat('Rp. ',FORMAT(
			coalesce((select sum(nominal) from data_pengeluaran_lainnya where date_format(tanggal, \"%Y%m\") = '$bulan'),0)
			+
			coalesce((select sum(nominal) from data_pengeluaran_mal where date_format(tanggal, \"%Y%m\") <= '$bulan'), 0)
			+
			coalesce((select sum(s.jumlah) from data_pengeluaran_spp_header h
			  inner join data_pengeluaran_spp s on h.id=s.id_spp_header
			  where date_format(h.tanggal, \"%Y%m\") = '$bulan'),0)
			+
			coalesce((
			select sum(s.banyak_barang * s.harga_satuan) from data_pengeluaran_belanja_header h
			inner join data_pengeluaran_belanja s on h.id=s.id_belanja_header
			where date_format(h.tanggal, \"%Y%m\") = '$bulan'
			),0)
			, 0))
			 as total_pengeluaran
			
			"
		  );


		  $qSaldoShodaqoh = $adeQ->select(
			"select
			concat('Rp. ',FORMAT(
			  coalesce((select sum(nominal) from data_pemasukan where date_format(tanggal, \"%Y%m\") <= '$bulan'), 0)
			-
			coalesce((select sum(nominal) from data_pengeluaran_lainnya where date_format(tanggal, \"%Y%m\") <= '$bulan'), 0)
			-
			coalesce((select sum(s.jumlah) from data_pengeluaran_spp_header h
			 inner join data_pengeluaran_spp s on h.id=s.id_spp_header
			 where date_format(h.tanggal, \"%Y%m\") <= '$bulan'), 0)
			-
			coalesce((
			select sum(s.banyak_barang * s.harga_satuan) from data_pengeluaran_belanja_header h
			inner join data_pengeluaran_belanja s on h.id=s.id_belanja_header
			where date_format(h.tanggal, \"%Y%m\") <= '$bulan'
			), 0)
			, 0)) as saldo,
			
			concat('Rp. ',FORMAT(
			coalesce((select sum(nominal) from data_pemasukan where date_format(tanggal, \"%Y%m\") = '$bulan'),0) 
			, 0)) as total_pemasukan,
		  
			concat('Rp. ',FORMAT(
			coalesce((select sum(nominal) from data_pengeluaran_lainnya where date_format(tanggal, \"%Y%m\") = '$bulan'),0)
			+
			coalesce((select sum(s.jumlah) from data_pengeluaran_spp_header h
			  inner join data_pengeluaran_spp s on h.id=s.id_spp_header
			  where date_format(h.tanggal, \"%Y%m\") = '$bulan'),0)
			+
			coalesce((
			select sum(s.banyak_barang * s.harga_satuan) from data_pengeluaran_belanja_header h
			inner join data_pengeluaran_belanja s on h.id=s.id_belanja_header
			where date_format(h.tanggal, \"%Y%m\") = '$bulan'
			),0)
			, 0))
			 as total_pengeluaran
			
			"
		  );


		  $qSaldoMal = $adeQ->select(
			"select
			concat('Rp. ',FORMAT(
			  coalesce((select sum(nominal) from data_pemasukan_mal where date_format(tanggal, \"%Y%m\") <= '$bulan'), 0)
			-
			coalesce((select sum(nominal) from data_pengeluaran_mal where date_format(tanggal, \"%Y%m\") <= '$bulan'), 0)
			, 0)) as saldo,
			
			concat('Rp. ',FORMAT(
			  coalesce((select sum(nominal) from data_pemasukan_mal where date_format(tanggal, \"%Y%m\") <= '$bulan'), 0)
			, 0)) as total_pemasukan,
		  
			concat('Rp. ',FORMAT(
			coalesce((select sum(nominal) from data_pengeluaran_mal where date_format(tanggal, \"%Y%m\") <= '$bulan'), 0)
			, 0))
			 as total_pengeluaran
			
			"
		  );


		$qColumnBar = $adeQ->select("
			select tipe, sum(nominal) nominal from (
				select 'pemasukan shodaqoh' as tipe, nominal  from data_pemasukan where date_format(tanggal, \"%Y%m\") = '$bulan'
				union all
				select 'pemasukan zakat mal' as tipe, nominal  from data_pemasukan_mal where date_format(tanggal, \"%Y%m\") = '$bulan'
				union all
				select 'pengeluaran zakat mal',  nominal from data_pengeluaran_mal where date_format(tanggal, \"%Y%m\") = '$bulan'
				union all
				select 'pengeluaran shodaqoh lainnya',  nominal from data_pengeluaran_lainnya where date_format(tanggal, \"%Y%m\") = '$bulan'
				union all
				select 'pengeluaran shodaqoh spp',  s.jumlah from data_pengeluaran_spp_header h
				inner join data_pengeluaran_spp s on h.id=s.id_spp_header
				where date_format(h.tanggal, \"%Y%m\") = '$bulan'
				union all
				select 'pengeluaran belanja shodaqoh',  s.banyak_barang * s.harga_satuan as nominal from data_pengeluaran_belanja_header h
				inner join data_pengeluaran_belanja s on h.id=s.id_belanja_header
				where date_format(h.tanggal, \"%Y%m\") = '$bulan'
				
				union all
				select 'pemasukan shodaqoh', 0
				union all
				select 'pemasukan zakat mal', 0
				union all
				select 'pengeluaran zakat mal', 0
				union all
				select 'pengeluaran shodaqoh lainnya', 0
				union all
				select 'pengeluaran shodaqoh spp', 0
				union all
				select 'pengeluaran belanja shodaqoh', 0
				
			) a
			group by tipe"
	);
	


		echo json_encode([
			"saldo" => $qSaldo,
			"saldoShodaqoh" => $qSaldoShodaqoh,
			"saldoMal" => $qSaldoMal,
			"columnBar" => $qColumnBar
		]);

	}	

}// close session

?>