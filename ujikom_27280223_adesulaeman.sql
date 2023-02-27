-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 27 Feb 2023 pada 15.28
-- Versi server: 10.4.21-MariaDB
-- Versi PHP: 8.0.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `ujikom_27280223_adesulaeman`
--

DELIMITER $$
--
-- Fungsi
--
CREATE DEFINER=`u965363723_superuser`@`127.0.0.1` FUNCTION `getExt` (`Value` LONGTEXT, `delimeter` VARCHAR(10)) RETURNS TEXT CHARSET utf8mb4 BEGIN
DECLARE front TEXT DEFAULT NULL;
DECLARE frontlen INT DEFAULT NULL;
DECLARE TempValue TEXT DEFAULT NULL;
SET TempValue = '';
iterator:
LOOP  
IF LENGTH(TRIM(Value)) = 0 OR Value IS NULL THEN
LEAVE iterator;
END IF;
SET front = SUBSTRING_INDEX(Value,delimeter,1);
SET frontlen = LENGTH(front);
SET TempValue = TRIM(front);
SET Value = INSERT(Value,1,frontlen + 1,'');
END LOOP;
return TempValue;
END$$

CREATE DEFINER=`u965363723_superuser`@`127.0.0.1` FUNCTION `labelsplit` (`Value` LONGTEXT, `delimeter` VARCHAR(10)) RETURNS TEXT CHARSET utf8mb4 BEGIN
DECLARE front TEXT DEFAULT NULL;
DECLARE frontlen INT DEFAULT NULL;
DECLARE TempValue TEXT DEFAULT NULL;
SET TempValue = '';
iterator:
LOOP  
IF LENGTH(TRIM(Value)) = 0 OR Value IS NULL THEN
LEAVE iterator;
END IF;
SET front = SUBSTRING_INDEX(Value,delimeter,1);
SET frontlen = LENGTH(front);
SET TempValue = concat(TempValue, '<span style="margin-right:3px" class="label bg-blue">',TRIM(front),'</span>');
SET Value = INSERT(Value,1,frontlen + 1,'');
END LOOP;
return TempValue;
END$$

CREATE DEFINER=`u965363723_superuser`@`127.0.0.1` FUNCTION `SPLIT_PART` (`s` VARCHAR(1024), `del` CHAR(1), `i` INT(11)) RETURNS VARCHAR(1024) CHARSET latin1 BEGIN

        DECLARE n INT ;

        
        SET n = LENGTH(s) - LENGTH(REPLACE(s, del, '')) + 1;

        IF i > n THEN
            RETURN NULL ;
        ELSE
            RETURN SUBSTRING_INDEX(SUBSTRING_INDEX(s, del, i) , del , -1 ) ;        
        END IF;

    END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Struktur dari tabel `absen`
--

CREATE TABLE `absen` (
  `kd_absen` int(11) NOT NULL,
  `nm_bulan` varchar(50) DEFAULT NULL,
  `nis` int(10) NOT NULL,
  `nm_siswa` varchar(100) DEFAULT NULL,
  `jml_hadir` int(5) DEFAULT NULL,
  `alfa` int(5) DEFAULT NULL,
  `izin` int(5) DEFAULT NULL,
  `sakit` int(5) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Struktur dari tabel `admin`
--

CREATE TABLE `admin` (
  `username` varchar(50) NOT NULL,
  `password` varchar(50) NOT NULL,
  `nama` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Struktur dari tabel `core_fields`
--

CREATE TABLE `core_fields` (
  `id` bigint(20) NOT NULL,
  `id_form` bigint(20) NOT NULL,
  `name_field` varchar(250) NOT NULL,
  `validate` tinyint(1) NOT NULL,
  `msg_validate` text DEFAULT NULL,
  `type_input` varchar(250) NOT NULL,
  `link_type_input` varchar(500) DEFAULT NULL,
  `case_cade` bigint(20) DEFAULT NULL,
  `active` tinyint(1) NOT NULL,
  `type_field` varchar(50) DEFAULT NULL,
  `groupname` varchar(100) DEFAULT NULL,
  `format_type` varchar(100) DEFAULT NULL,
  `position_md` int(11) DEFAULT NULL,
  `validate_length` int(11) DEFAULT NULL,
  `placeholder` text DEFAULT NULL,
  `help_context` text DEFAULT NULL,
  `search_enable` int(11) DEFAULT NULL,
  `encrypt` int(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `core_fields`
--

INSERT INTO `core_fields` (`id`, `id_form`, `name_field`, `validate`, `msg_validate`, `type_input`, `link_type_input`, `case_cade`, `active`, `type_field`, `groupname`, `format_type`, `position_md`, `validate_length`, `placeholder`, `help_context`, `search_enable`, `encrypt`) VALUES
(1, 1, 'id', 1, NULL, 'number', NULL, NULL, 1, 'pk', 'header', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(2, 1, 'userid', 1, 'User ID Wajib Di Isi', 'text', NULL, NULL, 1, 'nm', 'header', NULL, NULL, NULL, 'Masukan User ID', NULL, 1, NULL),
(3, 1, 'userpass', 1, 'User Pass Wajib Di Isi', 'password', NULL, NULL, 1, 'nm', 'header', NULL, NULL, NULL, 'Masukan Password', NULL, 1, NULL),
(4, 1, 'username', 1, 'Username Wajib Di Isi', 'text', NULL, NULL, 1, 'nm', 'header', NULL, NULL, NULL, 'Masukan Username', NULL, 1, NULL),
(5, 1, 'email', 0, NULL, 'email', NULL, NULL, 1, 'nm', 'header', NULL, NULL, NULL, 'Masukan Email', NULL, 1, NULL),
(6, 1, 'firstname', 0, NULL, 'text', NULL, NULL, 1, 'nm', 'header', NULL, NULL, NULL, 'Masukan First Name', NULL, 1, NULL),
(7, 1, 'lastname', 0, NULL, 'text', NULL, NULL, 1, 'nm', 'header', NULL, NULL, NULL, 'Masukan Last Name', NULL, 1, NULL),
(8, 1, 'no_handphone', 1, 'Masukan No Whatsapp / Hanphone', 'text', NULL, NULL, 1, 'nm', 'header', '999999999999', NULL, NULL, 'Masukan No Whatsapp / Hanphone', NULL, 1, 1),
(44, 2, 'id', 1, NULL, 'number', NULL, NULL, 1, 'pk', 'header', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(45, 2, 'dokumen', 1, 'Upload Dokumen', 'file', NULL, NULL, 1, 'nm', 'header', NULL, NULL, NULL, 'Upload dokumen', NULL, 1, NULL),
(46, 2, 'tanggal_terima_dokumen', 0, 'Isi Tanggal Terima Dokumen', 'date', NULL, NULL, 1, 'default', 'header', NULL, NULL, NULL, 'Tanggal Terima Dokumen', NULL, 1, NULL),
(47, 2, 'tanggal_tte_dokumen', 0, 'Isi Tanggal TTE Dokumen', 'date', NULL, NULL, 1, 'default', 'header', NULL, NULL, NULL, 'Tanggal TTE Dokumen', NULL, 1, NULL),
(50, 2, 'created_by', 1, NULL, 'number', NULL, NULL, 1, 'sys_ins_usr', 'header', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(51, 2, 'created_date', 1, NULL, 'date', NULL, NULL, 1, 'sys_ins_time', 'header', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(200, 3, 'id', 1, NULL, 'number', NULL, NULL, 1, 'pk', 'header', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(201, 3, 'name_level', 1, 'Isi Nama Level Group', 'text', NULL, NULL, 1, 'nm', 'header', NULL, NULL, NULL, 'Masukan Nama Level', NULL, NULL, NULL),
(206, 3, 'level_id', 1, 'Isi Level ID', 'number', NULL, NULL, 1, 'nm', 'header', NULL, NULL, NULL, 'Level', NULL, NULL, NULL),
(207, 3, 'level_down_id', 0, NULL, 'number', NULL, NULL, 1, 'nm', 'header', NULL, NULL, NULL, 'Level Turunan', NULL, NULL, NULL),
(208, 3, 'created_by', 1, NULL, 'text', NULL, NULL, 1, 'sys_ins_usr', 'header', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(209, 3, 'created_date', 1, NULL, 'date', NULL, NULL, 1, 'sys_ins_time', 'header', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(210, 3, 'updated_date', 1, NULL, 'date', NULL, NULL, 1, 'sys_upd_time', 'header', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(211, 3, 'updated_by', 1, NULL, 'text', NULL, NULL, 1, 'sys_upd_usr', 'header', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(212, 4, 'id', 1, NULL, 'number', NULL, NULL, 1, 'pk', 'header', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(213, 4, 'opd', 1, 'Nama OPD', 'text', NULL, NULL, 1, 'nm', 'header', NULL, NULL, NULL, 'Masukan Nama OPD', NULL, 1, NULL),
(214, 4, 'created_by', 1, NULL, 'text', NULL, NULL, 1, 'sys_ins_usr', 'header', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(215, 4, 'created_date', 1, NULL, 'date', NULL, NULL, 1, 'sys_ins_time', 'header', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(216, 4, 'updated_date', 1, NULL, 'date', NULL, NULL, 1, 'sys_upd_time', 'header', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(217, 4, 'update_by', 1, NULL, 'text', NULL, NULL, 1, 'sys_upd_usr', 'header', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(218, 3, 'opd', 1, 'Isi OPD', 'select', './lib/base/select_data.php?t=vw_select_opd&filter=all', NULL, 1, 'nm', 'header', NULL, NULL, NULL, 'OPD', NULL, 1, NULL),
(223, 5, 'userid', 1, 'User ID Wajib Di Isi', 'text', NULL, NULL, 1, 'nm', 'header', NULL, NULL, NULL, 'Masukan User ID', NULL, 1, NULL),
(224, 5, 'username', 1, 'Username Wajib Di Isi', 'text', NULL, NULL, 1, 'nm', 'header', NULL, NULL, NULL, 'Masukan Username', NULL, 1, NULL),
(225, 5, 'roles', 1, 'Set Roles', 'select', './lib/base/select_data.php?t=vw_select_roles_jabatan&filter=all', NULL, 1, 'nm', 'header', NULL, NULL, NULL, 'Isi Roles', NULL, 1, NULL),
(226, 5, 'opd', 1, 'Isi OPD', 'select', './lib/base/select_data.php?t=vw_select_opd&filter=all', NULL, 1, 'nm', 'header', NULL, NULL, NULL, 'Isi OPD', NULL, 1, NULL),
(227, 5, 'jabatan', 1, 'Isi Jabatan', 'text', NULL, NULL, 1, 'nm', 'header', NULL, NULL, NULL, 'Isi Jabatan', NULL, 1, NULL),
(228, 6, 'id', 1, NULL, 'number', NULL, NULL, 1, 'pk', 'header', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(229, 6, 'template_name', 1, 'Isi Template Name', 'text', NULL, NULL, 1, 'nm', 'header', NULL, NULL, NULL, 'Template Name', NULL, 1, NULL),
(230, 6, 'created_by', 1, NULL, 'text', NULL, NULL, 1, 'sys_ins_usr', 'header', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(231, 6, 'created_date', 1, NULL, 'date', NULL, NULL, 1, 'sys_ins_time', 'header', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(232, 7, 'id', 1, NULL, 'number', NULL, NULL, 1, 'pk', 'header', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(233, 7, 'id_template', 1, 'Isi Template ID', 'number', NULL, NULL, 1, 'sub', 'header', NULL, NULL, NULL, 'Template Name', NULL, NULL, NULL),
(234, 7, 'id_user', 1, 'Isi User', 'select', './lib/base/select_data.php?t=vw_select_user&filter=all&sub=', NULL, 1, 'nm', 'header', NULL, NULL, NULL, 'Isi User', NULL, 1, NULL),
(235, 7, 'id_level', 1, 'Isi Level', 'select', './lib/base/select_data.php?t=vw_select_ref_custom&filter=all', NULL, 1, 'nm', 'header', NULL, NULL, NULL, 'Isi Level', NULL, 1, NULL),
(236, 7, 'action', 1, 'Isi Action', 'select', './lib/base/select_data.php?t=vw_select_action&filter=all', NULL, 1, 'nm', 'header', NULL, NULL, NULL, 'Isi Action', NULL, 1, NULL),
(237, 7, 'created_by', 1, NULL, 'text', NULL, NULL, 1, 'sys_ins_usr', 'header', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(238, 7, 'created_date', 1, NULL, 'date', NULL, NULL, 1, 'sys_ins_time', 'header', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(239, 8, 'id', 1, NULL, 'number', NULL, NULL, 1, 'pk', 'header', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(240, 8, 'group_name', 1, 'Isi Group Name', 'text', NULL, NULL, 1, 'nm', 'header', NULL, NULL, NULL, 'Group Name', NULL, 1, NULL),
(245, 1, 'id_group_management', 1, 'Isi Group Management', 'select', './lib/base/select_data.php?t=vw_select_group&filter=all', NULL, 1, 'nm', 'header', NULL, NULL, NULL, 'Isi Group Management', NULL, 1, NULL),
(246, 9, 'id', 1, NULL, 'number', NULL, NULL, 1, 'pk', 'header', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(247, 9, 'tanggal_terima_dokumen', 1, 'Tanggal Terima Dokumen', 'date', NULL, NULL, 1, 'nm', 'header', NULL, NULL, NULL, 'Tanggal Terima Dokumen', NULL, 1, NULL),
(248, 9, 'jenis_dokumen', 0, 'Jenis Dokumen', 'text', NULL, NULL, 1, 'nm', 'header', NULL, NULL, NULL, 'Jenis Dokumen', NULL, 1, NULL),
(249, 9, 'tanggal', 0, 'Tanggal', 'date', NULL, NULL, 1, 'nm', 'header', NULL, NULL, NULL, 'Tanggal', NULL, 1, NULL),
(250, 9, 'umur_dokumen', 0, 'Umur Dokumen', 'number', NULL, NULL, 1, 'nm', 'header', NULL, NULL, NULL, 'Umur Dokumen', NULL, 1, NULL),
(251, 9, 'tipe_pengiriman_dokumen', 0, 'Tipe Pengiriman Dokumen', 'text', NULL, NULL, 1, 'nm', 'header', NULL, NULL, NULL, 'Tipe Pengiriman Dokumen', NULL, 1, NULL),
(252, 10, 'id', 1, NULL, 'number', NULL, NULL, 1, 'pk', 'header', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(253, 10, 'jabatan', 1, 'Isi Jabatan', 'text', NULL, NULL, 1, 'nm', 'header', NULL, NULL, NULL, 'Isi Jabatan', NULL, 1, NULL),
(254, 10, 'code_bagian', 0, 'Isi Code Bagian', 'text', NULL, NULL, 1, 'nm', 'header', '99.999', NULL, NULL, 'Isi Code Bagian', NULL, 1, NULL),
(255, 10, 'id_level', 0, 'Isi Level', 'select', './lib/base/select_data.php?t=vw_select_level&filter=all', NULL, 1, 'nm', 'header', NULL, NULL, NULL, 'Isi Level', NULL, 1, NULL),
(257, 10, 'status_jabatan', 0, 'Isi Status Jabatan', 'select', './lib/base/select_data.php?t=vw_select_status_jabatan&filter=all', NULL, 1, 'nm', 'header', NULL, NULL, NULL, 'Isi Status Jabatan', NULL, 1, NULL),
(258, 10, 'opd', 0, 'Isi OPD', 'select', './lib/base/select_data.php?t=vw_select_opd&filter=all', NULL, 1, 'nm', 'header', NULL, NULL, NULL, 'Isi OPD', NULL, 1, NULL),
(259, 11, 'id', 1, NULL, 'number', NULL, NULL, 1, 'pk', 'header', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(260, 11, 'jenis_dokumen', 1, 'jenis_dokumen', 'text', NULL, NULL, 1, 'nm', 'header', NULL, NULL, NULL, 'Jenis Dokumen', NULL, 1, NULL),
(261, 11, 'nomor', 0, 'nomor', 'text', NULL, NULL, 0, 'nm', 'header', NULL, NULL, NULL, 'Nomor', NULL, 1, NULL),
(262, 11, 'tanggal', 1, 'tanggal', 'date', NULL, NULL, 1, 'nm', 'header', NULL, NULL, NULL, 'Tanggal', NULL, 1, NULL),
(263, 11, 'perihal', 1, 'perihal', 'text', NULL, NULL, 1, 'nm', 'header', NULL, NULL, NULL, 'Perihal', NULL, 1, NULL),
(264, 11, 'tujuan', 1, 'tujuan', 'text', NULL, NULL, 1, 'nm', 'header', NULL, NULL, NULL, 'Tujuan', NULL, 1, NULL),
(265, 11, 'code_opd_tahun', 1, 'code_opd_tahun', 'text', NULL, NULL, 1, 'nm', 'header', NULL, NULL, NULL, 'Nomor', NULL, 1, NULL),
(266, 11, 'no_urut', 1, 'no_urut', 'text', NULL, NULL, 1, 'nm', 'header', NULL, NULL, NULL, 'Nomor', NULL, 1, NULL),
(267, 11, 'ref_no_surat', 1, 'ref_no_surat', 'text', NULL, NULL, 1, 'nm', 'header', NULL, NULL, NULL, 'Nomor', NULL, 1, NULL),
(268, 11, 'created_by', 1, NULL, 'text', NULL, NULL, 1, 'sys_ins_usr', 'header', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(269, 4, 'id_identity', 1, 'Isi Identity', 'text', NULL, NULL, 1, 'nm', 'header', NULL, NULL, NULL, 'Masukan Identity', NULL, 1, NULL),
(270, 12, 'id', 1, NULL, 'number', NULL, NULL, 1, 'pk', 'header', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(271, 12, 'file_name', 1, 'Upload File', 'file', NULL, NULL, 1, 'nm', 'header', NULL, NULL, NULL, 'Upload File', NULL, 1, NULL),
(272, 12, 'description', 1, 'Description File', 'textarea', NULL, NULL, 1, 'nm', 'header', NULL, NULL, NULL, 'Description File', NULL, 1, NULL),
(273, 12, 'category', 1, 'Category', 'select', './lib/base/select_data.php?t=vw_select_category&filter=all', NULL, 1, 'nm', 'header', NULL, NULL, NULL, 'Category', NULL, 1, NULL),
(274, 12, 'tags', 1, 'Tags', 'checkbox', './lib/base/select_data.php?t=vw_select_tags&filter=all', NULL, 1, 'nm', 'header', NULL, NULL, NULL, 'Tags', NULL, 1, NULL),
(275, 12, 'created_by', 1, NULL, 'text', NULL, NULL, 1, 'sys_ins_usr', 'header', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(276, 12, 'created_date', 1, NULL, 'date', NULL, NULL, 1, 'sys_ins_time', 'header', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(277, 13, 'id', 1, NULL, 'number', NULL, NULL, 1, 'pk', 'header', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(278, 13, 'category', 1, 'Category', 'text', NULL, NULL, 1, 'nm', 'header', NULL, NULL, NULL, 'Category', NULL, 1, NULL),
(279, 13, 'created_by', 1, NULL, 'text', NULL, NULL, 1, 'sys_ins_usr', 'header', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(280, 13, 'created_date', 1, NULL, 'date', NULL, NULL, 1, 'sys_ins_time', 'header', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(281, 14, 'id', 1, NULL, 'number', NULL, NULL, 1, 'pk', 'header', NULL, NULL, NULL, 'Bill No', NULL, NULL, NULL),
(294, 14, 'payment_method', 0, 'Payment Method', 'text', NULL, NULL, 1, 'nm', 'header', NULL, NULL, NULL, 'Payment Method', NULL, 1, NULL),
(295, 14, 'change_payment', 0, 'Change', 'number', NULL, NULL, 1, 'nm', 'header', NULL, NULL, NULL, 'Change', NULL, 1, NULL),
(296, 14, 'payment_cash', 0, 'Payment Cash', 'number', NULL, NULL, 1, 'nm', 'header', NULL, NULL, NULL, 'Payment Cash', NULL, 1, NULL),
(297, 14, 'payment_trasnfer', 0, 'Payment Transfer', 'number', NULL, NULL, 1, 'nm', 'header', NULL, NULL, NULL, 'Payment Transfer', NULL, 1, NULL),
(298, 14, 'total_amount', 0, 'Total Amount', 'number', NULL, NULL, 1, 'nm', 'header', NULL, NULL, NULL, 'Total Amount', NULL, 1, NULL),
(299, 14, 'payment_dp', 0, 'Payment DP', 'number', NULL, NULL, 1, 'nm', 'header', NULL, NULL, NULL, 'Payment DP', NULL, 1, NULL),
(300, 14, 'payment_credit', 0, 'Payment Credit', 'number', NULL, NULL, 1, 'nm', 'header', NULL, NULL, NULL, 'Payment Credit', NULL, 1, NULL),
(301, 14, 'payment_debit', 0, 'Payment Debit', 'number', NULL, NULL, 1, 'nm', 'header', NULL, NULL, NULL, 'Payment Debit', NULL, 1, NULL),
(302, 14, 'card_no_debit', 0, 'Card No Debit', 'text', NULL, NULL, 1, 'nm', 'header', NULL, NULL, NULL, 'Card No Debit', NULL, 1, NULL),
(303, 14, 'card_no_credit', 0, 'Card No Credit', 'text', NULL, NULL, 1, 'nm', 'header', NULL, NULL, NULL, 'Card No Credit', NULL, 1, NULL),
(304, 14, 'no_rek_transfer', 0, 'No Rek Transfer', 'text', NULL, NULL, 1, 'nm', 'header', NULL, NULL, NULL, 'No Rek Transfer', NULL, 1, NULL),
(306, 14, 'id_struk', 0, 'Bill No', 'text', NULL, NULL, 0, 'nm', 'header', NULL, NULL, NULL, 'Bill No', NULL, 1, NULL),
(307, 14, 'sales_date', 0, NULL, 'date', NULL, NULL, 1, 'sys_upd_time', 'header', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(308, 15, 'no_struk', 0, 'Bill No', 'text', NULL, NULL, 1, 'sub', 'header', NULL, NULL, NULL, 'Bill No', NULL, 1, NULL),
(309, 15, 'barcode', 0, 'Barcode', 'text', NULL, NULL, 1, 'nm', 'header', NULL, NULL, NULL, 'Barcode', NULL, 1, NULL),
(310, 15, 'product_name', 0, 'Product Name', 'text', NULL, NULL, 1, 'nm', 'header', NULL, NULL, NULL, 'Product Name', NULL, 1, NULL),
(311, 15, 'qty', 0, 'Qty', 'text', NULL, NULL, 1, 'nm', 'header', NULL, NULL, NULL, 'Qty', NULL, 1, NULL),
(312, 15, 'netto_gram', 0, 'Netto Gram', 'text', NULL, NULL, 1, 'nm', 'header', NULL, NULL, NULL, 'Netto Gram', NULL, 1, NULL),
(316, 16, 'id', 1, NULL, 'number', NULL, NULL, 1, 'pk', 'header', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(317, 16, 'product_name', 0, 'Product Name', 'text', NULL, NULL, 1, 'nm', 'header', NULL, NULL, NULL, 'Product Name', NULL, 1, NULL),
(319, 16, 'kadar_product', 0, 'Kadar', 'number', NULL, NULL, 1, 'nm', 'header', NULL, NULL, NULL, 'Kadar', NULL, 1, NULL),
(320, 16, 'netto_gram', 0, 'Netto Gram', 'number', NULL, NULL, 1, 'nm', 'header', NULL, NULL, NULL, 'Netto Gram', NULL, 1, NULL),
(324, 17, 'id', 1, NULL, 'number', NULL, NULL, 1, 'pk', 'header', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(325, 17, 'stock_opname_info', 1, NULL, 'textarea', NULL, NULL, 1, 'nm', 'header', NULL, NULL, NULL, 'Stock Opname Info', NULL, 1, NULL),
(326, 17, 'stock_opname_time', 1, NULL, 'date', NULL, NULL, 1, 'nm', 'header', NULL, NULL, NULL, 'Stock Opname Time', NULL, 1, NULL),
(328, 17, 'created_by', 1, NULL, 'text', NULL, NULL, 1, 'sys_ins_usr', 'header', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(329, 17, 'created_date', 1, NULL, 'date', NULL, NULL, 1, 'sys_ins_time', 'header', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(330, 20, 'id', 1, NULL, 'number', NULL, NULL, 1, 'pk', 'header', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(331, 20, 'id_stock_opname', 1, NULL, 'number', NULL, NULL, 1, 'sub', 'header', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(332, 20, 'storage_name', 0, 'Storage Name', 'text', NULL, NULL, 1, 'nm', 'header', NULL, NULL, NULL, 'Storage Name', NULL, 1, NULL),
(333, 20, 'product', 0, 'Product', 'text', NULL, NULL, 1, 'nm', 'header', NULL, NULL, NULL, 'Product', NULL, 1, NULL),
(334, 20, 'physical_stock', 0, 'Physical Stock', 'text', NULL, NULL, 1, 'nm', 'header', NULL, NULL, NULL, 'Physical Stock', NULL, 1, NULL),
(335, 20, 'system_stock', 0, 'System Stock', 'text', NULL, NULL, 1, 'nm', 'header', NULL, NULL, NULL, 'System Stock', NULL, 1, NULL),
(336, 20, 'adjusment', 0, 'Adjusment', 'text', NULL, NULL, 1, 'nm', 'header', NULL, NULL, NULL, 'Adjusment', NULL, 1, NULL),
(337, 20, 'created_by', 1, NULL, 'text', NULL, NULL, 1, 'sys_ins_user', 'header', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(338, 20, 'created_date', 1, NULL, 'date', NULL, NULL, 1, 'sys_ins_time', 'header', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(339, 20, 'difference', 0, 'Difference', 'text', NULL, NULL, 1, 'nm', 'header', NULL, NULL, NULL, 'Difference', NULL, 1, NULL),
(340, 18, 'id', 1, NULL, 'number', NULL, NULL, 1, 'pk', 'header', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(341, 18, 'storage_name', 1, 'Storage Name', 'textarea', NULL, NULL, 1, 'nm', 'header', NULL, NULL, NULL, 'Storage Name', NULL, 1, NULL),
(342, 18, 'created_by', 1, NULL, 'text', NULL, NULL, 1, 'sys_ins_usr', 'header', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(343, 18, 'created_date', 1, NULL, 'date', NULL, NULL, 1, 'sys_ins_time', 'header', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(344, 19, 'id', 1, NULL, 'number', NULL, NULL, 1, 'pk', 'header', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(345, 19, 'storage_name', 1, 'Storage Name', 'text', NULL, NULL, 1, 'nm', 'header', NULL, NULL, NULL, 'Storage Name', NULL, 1, NULL),
(346, 19, 'barcode', 1, 'Barcode', 'text', NULL, NULL, 1, 'nm', 'header', NULL, NULL, NULL, 'Barcode', NULL, 1, NULL),
(347, 19, 'qty_stock', 1, 'Qty Stock', 'number', NULL, NULL, 1, 'nm', 'header', NULL, NULL, NULL, 'Qty Stock', NULL, 1, NULL),
(348, 19, 'netto_gram', 1, 'Netto Gram', 'number', NULL, NULL, 1, 'nm', 'header', NULL, NULL, NULL, 'Netto Gram', NULL, 1, NULL),
(350, 21, 'id', 1, NULL, 'number', NULL, NULL, 1, 'pk', 'header', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(351, 21, 'no_invoice', 1, 'No Invoice', 'text', NULL, NULL, 1, 'nm', 'header', NULL, NULL, NULL, 'No Invoice', NULL, 1, NULL),
(355, 22, 'id', 1, NULL, 'number', NULL, NULL, 1, 'pk', 'header', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(356, 22, 'id_receive', 1, 'Id Receive', 'number', NULL, NULL, 1, 'sub', 'header', NULL, NULL, NULL, 'Id Receive', NULL, NULL, NULL),
(357, 22, 'barcode', 1, 'Barcode Product', 'select', './lib/base/select_data.php?t=vw_select_product_receive&filter=all', NULL, 1, 'nm', 'header', NULL, NULL, NULL, 'Barcode Product', NULL, 1, NULL),
(358, 22, 'qty_receive', 1, 'Total Qty', 'number', NULL, NULL, 1, 'nm', 'header', NULL, NULL, NULL, 'Total Qty', NULL, 1, NULL),
(359, 22, 'total_gram', 1, 'Total Gram', 'number', NULL, NULL, 1, 'nm', 'header', NULL, NULL, NULL, 'Total Gram', NULL, 1, NULL),
(360, 22, 'total_price', 1, 'Total Price', 'number', NULL, NULL, 1, 'nm', 'header', NULL, NULL, NULL, 'Total Price', NULL, 1, NULL),
(361, 22, 'created_by', 1, NULL, 'text', NULL, NULL, 1, 'sys_ins_usr', 'header', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(362, 22, 'created_date', 1, NULL, 'date', NULL, NULL, 1, 'sys_ins_time', 'header', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(363, 23, 'id', 1, NULL, 'number', NULL, NULL, 1, 'pk', 'header', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(364, 23, 'bulan', 1, 'Bulan', 'text', NULL, NULL, 1, 'nm', 'header', NULL, NULL, NULL, 'Bulan', NULL, 1, NULL),
(365, 23, 'total_sales', 1, 'Total Sales', 'number', NULL, NULL, 1, 'nm', 'header', NULL, NULL, NULL, 'Total Sales', NULL, 1, NULL),
(366, 18, 'gram', 1, 'Gram', 'number', NULL, NULL, 1, 'nm', 'header', NULL, NULL, NULL, 'Gram', NULL, 1, NULL),
(367, 24, 'id', 1, NULL, 'number', NULL, NULL, 1, 'pk', 'header', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(368, 24, 'stock_info', 1, 'Stock Info', 'textarea', NULL, NULL, 1, 'nm', 'header', NULL, NULL, NULL, 'Stock Info', NULL, 1, NULL),
(369, 24, 'stock_date', 1, 'Stock Date', 'date', NULL, NULL, 1, 'nm', 'header', NULL, NULL, NULL, 'Stock Date', NULL, 1, NULL),
(370, 24, 'created_by', 1, NULL, 'text', NULL, NULL, 1, 'sys_ins_usr', 'header', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(371, 24, 'created_date', 1, NULL, 'date', NULL, NULL, 1, 'sys_ins_time', 'header', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(372, 25, 'id', 1, NULL, 'number', NULL, NULL, 1, 'pk', 'header', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(373, 25, 'id_stock_weight', 1, NULL, 'number', NULL, NULL, 1, 'sub', 'header', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(374, 25, 'id_category_storage', 1, 'Storage Name', 'select', './lib/base/select_data.php?t=vw_select_storage&filter=all', NULL, 1, 'nm', 'header', NULL, NULL, NULL, 'Storage Name', NULL, 1, NULL),
(375, 25, 'opening_weight', 1, 'Opening Weight', 'number', NULL, NULL, 1, 'nm', 'header', NULL, NULL, NULL, 'Opening Weight', NULL, 1, NULL),
(376, 25, 'closing_weight', 0, 'Closing Weight', 'number', NULL, NULL, 1, 'nm', 'header', NULL, NULL, NULL, 'Closing Weight', NULL, 1, NULL),
(377, 25, 'created_by', 1, NULL, 'text', NULL, NULL, 1, 'sys_ins_user', 'header', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(378, 25, 'created_date', 1, NULL, 'date', NULL, NULL, 1, 'sys_ins_time', 'header', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(380, 26, 'id', 1, NULL, 'number', NULL, NULL, 1, 'pk', 'header', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(381, 26, 'nama', 1, 'Nama', 'text', NULL, NULL, 1, 'nm', 'header', NULL, NULL, NULL, 'Nama', NULL, 1, NULL),
(382, 26, 'no_handphone', 1, 'No Handphone', 'text', NULL, NULL, 1, 'nm', 'header', NULL, NULL, NULL, 'No Handphone', NULL, 1, NULL),
(383, 26, 'alamat', 1, 'Alamat', 'text', NULL, NULL, 1, 'nm', 'header', NULL, NULL, NULL, 'Alamat', NULL, 1, NULL),
(384, 26, 'no_ktp', 1, 'KTP', 'text', NULL, NULL, 1, 'nm', 'header', NULL, NULL, NULL, 'KTP', NULL, 1, NULL),
(385, 26, 'created_by', 1, NULL, 'text', NULL, NULL, 1, 'sys_ins_user', 'header', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(386, 26, 'created_date', 1, NULL, 'date', NULL, NULL, 1, 'sys_ins_time', 'header', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(387, 27, 'id', 1, NULL, 'number', NULL, NULL, 1, 'pk', 'header', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(388, 27, 'invoice', 1, 'Invoice', 'text', NULL, NULL, 1, 'nm', 'header', NULL, NULL, NULL, 'Invoice', NULL, 1, NULL),
(389, 27, 'id_resaller', 1, 'Reseller', 'select', './lib/base/select_data.php?t=vw_select_reseller&filter=all', NULL, 1, 'nm', 'header', NULL, NULL, NULL, 'Reseller', NULL, 1, NULL),
(390, 27, 'tukar_pen', 0, 'Tuker Pen', 'number', NULL, NULL, 1, 'nm', 'header', NULL, NULL, NULL, 'Tuker Pen', NULL, 1, NULL),
(391, 27, 'kadar_total', 0, 'Kadar', 'number', NULL, NULL, 1, 'nm', 'header', NULL, NULL, NULL, 'Kadar', NULL, 1, NULL),
(392, 27, 'created_by', 1, NULL, 'text', NULL, NULL, 1, 'sys_ins_user', 'header', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(393, 27, 'created_date', 1, NULL, 'date', NULL, NULL, 1, 'sys_ins_time', 'header', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(394, 28, 'id', 1, NULL, 'number', NULL, NULL, 1, 'pk', 'header', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(395, 28, 'invoice', 1, 'Invoice', 'text', NULL, NULL, 1, 'sub', 'header', NULL, NULL, NULL, 'Invoice', NULL, 1, NULL),
(401, 16, 'brutto_gram', 0, 'Brutto Gram', 'number', NULL, NULL, 1, 'nm', 'header', NULL, NULL, NULL, 'Brutto Gram', NULL, 1, NULL),
(402, 16, 'sell_price', 0, 'Sell Price', 'number', NULL, NULL, 1, 'nm', 'header', NULL, NULL, NULL, 'Sell Price', NULL, 1, NULL),
(403, 16, 'created_by', 1, NULL, 'text', NULL, NULL, 1, 'sys_ins_usr', 'header', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(404, 16, 'created_date', 1, NULL, 'date', NULL, NULL, 1, 'sys_ins_time', 'header', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(405, 16, 'description', 0, 'Description', 'textarea', NULL, NULL, 1, 'nm', 'header', NULL, NULL, NULL, 'Description', NULL, 1, NULL),
(406, 19, 'brutto_gram', 1, 'Brutto Gram', 'number', NULL, NULL, 1, 'nm', 'header', NULL, NULL, NULL, 'Brutto Gram', NULL, 1, NULL),
(407, 19, 'stock_date', 1, 'Stock Date', 'date', NULL, NULL, 1, 'nm', 'header', NULL, NULL, NULL, 'Stock Date', NULL, 1, NULL),
(408, 15, 'brutto_gram', 0, 'Brutto Gram', 'text', NULL, NULL, 1, 'nm', 'header', NULL, NULL, NULL, 'Brutto Gram', NULL, 1, NULL),
(409, 15, 'price', 0, 'Price', 'text', NULL, NULL, 1, 'nm', 'header', NULL, NULL, NULL, 'Price', NULL, 1, NULL),
(410, 15, 'sales_date', 0, 'Sales Date', 'text', NULL, NULL, 1, 'nm', 'header', NULL, NULL, NULL, 'Sales Date', NULL, 1, NULL),
(411, 15, 'storage_name', 0, 'Storage Name', 'text', NULL, NULL, 1, 'nm', 'header', NULL, NULL, NULL, 'Storage Name', NULL, 1, NULL),
(416, 28, 'payment_date', 1, 'Payment Date', 'date', NULL, NULL, 1, 'nm', 'header', NULL, NULL, NULL, 'Payment Date', NULL, 1, NULL),
(423, 21, 'type_receive', 1, 'Type Receive', 'select', './lib/base/select_data.php?t=vw_select_typereceive&filter=all', NULL, 1, 'nm', 'header', NULL, NULL, NULL, 'Type Receive', NULL, 1, NULL),
(424, 21, 'receive_date', 1, 'Receive Date', 'date', NULL, NULL, 1, 'nm', 'header', NULL, NULL, NULL, 'Receive Date', NULL, 1, NULL),
(425, 21, 'created_by', 1, NULL, 'text', NULL, NULL, 1, 'sys_ins_usr', 'header', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(426, 21, 'created_date', 1, NULL, 'date', NULL, NULL, 1, 'sys_ins_time', 'header', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(427, 29, 'id', 1, NULL, 'number', NULL, NULL, 1, 'pk', 'header', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(428, 29, 'cost_date', 1, 'Cost Date', 'date', NULL, NULL, 1, 'nm', 'header', NULL, NULL, NULL, 'Cost Date', NULL, 1, NULL),
(429, 29, 'opening_cash', 1, 'Opening Cash', 'number', NULL, NULL, 1, 'nm', 'header', NULL, NULL, NULL, 'Opening Cash', NULL, 1, NULL),
(431, 29, 'created_by', 1, NULL, 'text', NULL, NULL, 1, 'sys_ins_usr', 'header', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(432, 29, 'created_date', 1, NULL, 'date', NULL, NULL, 1, 'sys_ins_time', 'header', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(433, 30, 'id', 1, NULL, 'number', NULL, NULL, 1, 'pk', 'header', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(434, 30, 'id_cost_daily', 1, NULL, 'number', NULL, NULL, 1, 'sub', 'header', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(435, 30, 'nominal', 1, 'Nominal', 'number', NULL, NULL, 1, 'nm', 'header', NULL, NULL, NULL, 'Nominal', NULL, 1, NULL),
(436, 30, 'information', 1, 'Information', 'textarea', NULL, NULL, 1, 'nm', 'header', NULL, NULL, NULL, 'Information', NULL, 1, NULL),
(437, 30, 'created_by', 1, NULL, 'text', NULL, NULL, 1, 'sys_ins_usr', 'header', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(438, 30, 'created_date', 1, NULL, 'date', NULL, NULL, 1, 'sys_ins_time', 'header', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(439, 32, 'id', 1, NULL, 'number', NULL, NULL, 1, 'pk', 'header', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(440, 32, 'invoice', 1, 'Invoice', 'text', NULL, NULL, 1, 'nm', 'header', NULL, NULL, NULL, 'Invoice', NULL, 1, NULL),
(441, 32, 'storename', 1, 'Nominal', 'text', NULL, NULL, 1, 'nm', 'header', NULL, NULL, NULL, 'Nominal', NULL, 1, NULL),
(442, 33, 'id', 1, NULL, 'number', NULL, NULL, 1, 'pk', 'header', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(443, 33, 'invoice', 1, 'Invoice', 'text', NULL, NULL, 1, 'sub', 'header', NULL, NULL, NULL, 'Invoice', NULL, 1, NULL),
(444, 33, 'storage_name', 1, 'Storage Name', 'text', NULL, NULL, 1, 'nm', 'header', NULL, NULL, NULL, 'Storage Name', NULL, 1, NULL),
(445, 33, 'pen', 1, 'Pen', 'number', NULL, NULL, 1, 'nm', 'header', NULL, NULL, NULL, 'Pen', NULL, 1, NULL),
(446, 33, 'product_name', 1, 'Product Name', 'text', NULL, NULL, 1, 'nm', 'header', NULL, NULL, NULL, 'Product Name', NULL, 1, NULL),
(447, 33, 'barcode', 1, 'Barcode', 'text', NULL, NULL, 1, 'nm', 'header', NULL, NULL, NULL, 'Barcode', NULL, 1, NULL),
(448, 33, 'qty_out', 1, 'Qty Out', 'number', NULL, NULL, 1, 'nm', 'header', NULL, NULL, NULL, 'Qty Out', NULL, 1, NULL),
(449, 33, 'gold_rate', 1, 'Gold Rate', 'number', NULL, NULL, 1, 'nm', 'header', NULL, NULL, NULL, 'Gold Rate', NULL, 1, NULL),
(450, 33, 'brutto_gram_out', 1, 'Brutto', 'number', NULL, NULL, 1, 'nm', 'header', NULL, NULL, NULL, 'Brutto', NULL, 1, NULL),
(451, 33, 'netto_gram_out', 1, 'Netto', 'number', NULL, NULL, 1, 'nm', 'header', NULL, NULL, NULL, 'Netto', NULL, 1, NULL),
(452, 28, 'method', 1, 'Method', 'select', './lib/base/select_data.php?t=vw_select_method&filter=all', NULL, 1, 'nm', 'header', NULL, NULL, NULL, 'Method', NULL, 1, NULL),
(453, 28, 'nominal', 1, 'Nominal Transfer', 'number', NULL, NULL, 1, 'nm', 'header', NULL, NULL, NULL, 'Nominal Transfer', NULL, 1, NULL),
(454, 28, 'gram_gold_price', 1, 'Gram Gold Price', 'number', NULL, NULL, 1, 'nm', 'header', NULL, NULL, NULL, 'Gram Gold Price', NULL, 1, NULL),
(455, 28, 'bank', 0, 'Bank', 'select', './lib/base/select_data.php?t=vw_select_bank&filter=all', NULL, 1, 'nm', 'header', NULL, NULL, NULL, 'Bank', NULL, 1, NULL),
(456, 28, 'created_by', 1, NULL, 'text', NULL, NULL, 1, 'sys_ins_usr', 'header', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(457, 28, 'created_date', 1, NULL, 'date', NULL, NULL, 1, 'sys_ins_time', 'header', NULL, NULL, NULL, NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Struktur dari tabel `core_filter`
--

CREATE TABLE `core_filter` (
  `id` bigint(20) NOT NULL,
  `name_field` varchar(150) NOT NULL,
  `name_input` varchar(150) NOT NULL,
  `type_input` varchar(100) DEFAULT NULL,
  `link_type_input` varchar(300) DEFAULT NULL,
  `case_cade` bigint(20) DEFAULT NULL,
  `logic` varchar(100) DEFAULT NULL,
  `gropname` varchar(100) DEFAULT NULL,
  `position_md` int(11) DEFAULT NULL,
  `idform` bigint(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Struktur dari tabel `core_forms`
--

CREATE TABLE `core_forms` (
  `idform` bigint(20) NOT NULL,
  `formcode` text DEFAULT NULL,
  `formname` text DEFAULT NULL,
  `description` text DEFAULT NULL,
  `withsearch` int(11) DEFAULT NULL,
  `withimport` int(11) DEFAULT NULL,
  `withexport` int(11) DEFAULT NULL,
  `withupload` int(11) DEFAULT NULL,
  `withparent` int(11) DEFAULT NULL,
  `parentkey` bigint(20) DEFAULT NULL,
  `processpage` varchar(100) DEFAULT NULL,
  `helpfile` varchar(50) DEFAULT NULL,
  `keyfield` varchar(50) DEFAULT NULL,
  `srcupload` varchar(50) DEFAULT NULL,
  `formview` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `core_forms`
--

INSERT INTO `core_forms` (`idform`, `formcode`, `formname`, `description`, `withsearch`, `withimport`, `withexport`, `withupload`, `withparent`, `parentkey`, `processpage`, `helpfile`, `keyfield`, `srcupload`, `formview`) VALUES
(1, 'core_user', 'users', 'Data User', 1, 1, 1, 1, 1, 1, NULL, NULL, NULL, NULL, 'vw_access'),
(5, 'core_user', 'core_user', 'Set Level Users', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'vw_user_level'),
(8, 'core_group', 'core_group', 'Group Management', 1, 1, 1, 1, 1, 1, NULL, NULL, NULL, NULL, 'vw_group_management');

-- --------------------------------------------------------

--
-- Struktur dari tabel `core_group`
--

CREATE TABLE `core_group` (
  `id` bigint(100) NOT NULL,
  `group_name` varchar(200) NOT NULL,
  `created_by` varchar(100) DEFAULT NULL,
  `created_date` timestamp NULL DEFAULT NULL,
  `update_by` varchar(100) DEFAULT NULL,
  `update_date` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `core_group`
--

INSERT INTO `core_group` (`id`, `group_name`, `created_by`, `created_date`, `update_by`, `update_date`) VALUES
(4, 'Super User', NULL, NULL, NULL, NULL),
(5, 'Cashier', NULL, NULL, NULL, NULL),
(6, 'Owner', NULL, NULL, NULL, NULL),
(7, 'Staff IT', NULL, NULL, NULL, NULL),
(8, 'staff gudang', NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Struktur dari tabel `core_level`
--

CREATE TABLE `core_level` (
  `id` bigint(100) NOT NULL,
  `level_id` int(5) NOT NULL,
  `name_level` varchar(200) DEFAULT NULL,
  `level_down_id` int(5) DEFAULT NULL,
  `opd` varchar(200) DEFAULT NULL,
  `created_by` varchar(100) DEFAULT NULL,
  `created_date` timestamp NULL DEFAULT NULL,
  `updated_by` varchar(100) DEFAULT NULL,
  `updated_date` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `core_level`
--

INSERT INTO `core_level` (`id`, `level_id`, `name_level`, `level_down_id`, `opd`, `created_by`, `created_date`, `updated_by`, `updated_date`) VALUES
(3, 3, 'Level 3', 2, 'Badan Kepegawaian dan Pengembangan Sumber Daya Manusia ', 'adesulaeman', NULL, 'adesulaeman', NULL),
(8, 2, 'Level 2', NULL, 'Badan Kepegawaian dan Pengembangan Sumber Daya Manusia ', 'adesulaeman', NULL, 'adesulaeman', NULL),
(14, 5, 'Level 5', 4, 'Dinas Komunikasi dan Informatika', 'adesulaeman', NULL, '', NULL),
(15, 4, 'Level 4', 3, 'Dinas Komunikasi dan Informatika', 'adesulaeman', NULL, '', NULL),
(16, 3, 'Level 3', 2, 'Dinas Komunikasi dan Informatika', 'adesulaeman', NULL, '', NULL),
(17, 2, 'Level 2', NULL, 'Dinas Komunikasi dan Informatika', 'adesulaeman', NULL, '', NULL),
(18, 5, 'Level 5', 4, 'Badan Kepegawaian dan Pengembangan Sumber Daya Manusia ', 'adesulaeman', NULL, '', NULL),
(19, 4, 'Level 4', 3, 'Badan Kepegawaian dan Pengembangan Sumber Daya Manusia ', 'adesulaeman', NULL, '', NULL);

-- --------------------------------------------------------

--
-- Struktur dari tabel `core_logic`
--

CREATE TABLE `core_logic` (
  `id` bigint(20) NOT NULL,
  `logic` text NOT NULL,
  `description` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `core_logic`
--

INSERT INTO `core_logic` (`id`, `logic`, `description`) VALUES
(1, 'like', 'Contains'),
(2, '>=', 'Greater Than'),
(3, '<=', 'Less Than'),
(4, '<>', 'Not Equal'),
(5, '=', 'Equal');

-- --------------------------------------------------------

--
-- Struktur dari tabel `core_menus`
--

CREATE TABLE `core_menus` (
  `id` bigint(20) NOT NULL,
  `idmodule` bigint(20) DEFAULT NULL,
  `menu` varchar(75) DEFAULT NULL,
  `links` varchar(255) DEFAULT NULL,
  `hasparent` int(11) DEFAULT NULL,
  `fromidchild` int(11) DEFAULT NULL,
  `toidchild` int(11) DEFAULT NULL,
  `onclick` varchar(5) DEFAULT NULL,
  `expand` varchar(5) DEFAULT NULL,
  `description` varchar(100) DEFAULT NULL,
  `parent` int(11) DEFAULT NULL,
  `withframe` int(11) DEFAULT NULL,
  `installed` int(11) DEFAULT NULL,
  `icon` varchar(50) DEFAULT NULL,
  `view_only` int(11) DEFAULT NULL,
  `helpfile` varchar(50) DEFAULT NULL,
  `helppage` varchar(5) DEFAULT NULL,
  `sub_page` int(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `core_menus`
--

INSERT INTO `core_menus` (`id`, `idmodule`, `menu`, `links`, `hasparent`, `fromidchild`, `toidchild`, `onclick`, `expand`, `description`, `parent`, `withframe`, `installed`, `icon`, `view_only`, `helpfile`, `helppage`, `sub_page`) VALUES
(3, 97, 'ACCOUNT MANAGEMENT', NULL, 0, 10414, 10417, 'false', 'false', NULL, 0, 0, 0, ' fa-users', NULL, 'Manual_Master_Data_Management_V_1_0_0.pdf', NULL, NULL),
(4, 97, 'Users', './lib/base/users.php?f=1', 1, 4205, 4205, 'true', 'true', NULL, 3, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(5, 97, 'Profile', './lib/base/config_person_user.php', 0, 64, 64, 'true', 'true', NULL, 3, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(7, 97, 'PRODUCTS', NULL, 0, 10414, 10417, 'false', 'false', NULL, 0, 0, 0, 'fa-fw fa-ge', NULL, 'Manual_Master_Data_Management_V_1_0_0.pdf', NULL, NULL),
(8, 97, 'Products Management', './lib/base/form_advance_product.php?f=16', NULL, NULL, NULL, NULL, NULL, NULL, 7, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(12, 97, 'Group Management', './lib/base/group.php?f=8', 1, 4205, 4205, 'true', 'true', NULL, 3, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(20, 97, 'Print Barcode', './lib/base/form_print_barcode.php?f=13', NULL, NULL, NULL, NULL, NULL, NULL, 7, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(21, 97, 'POS', NULL, 0, 10414, 10417, 'false', 'false', NULL, 0, 0, 0, 'fa-fw fa-credit-card', NULL, 'Manual_Master_Data_Management_V_1_0_0.pdf', NULL, NULL),
(22, 97, 'Cashier', './lib/base/cashier.php?f=13', NULL, NULL, NULL, NULL, NULL, NULL, 21, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(23, 97, 'Advance Payment', './lib/base/advance_payment.php?f=13', NULL, NULL, NULL, NULL, NULL, NULL, 21, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(24, 97, 'STOCK PRODUCT', NULL, 0, 10414, 10417, 'false', 'false', NULL, 0, 0, 0, 'fa-fw fa-archive', NULL, NULL, NULL, NULL),
(25, 97, 'Stock Opname', './lib/base/form_advance_stock_opname.php?f=17', NULL, NULL, NULL, NULL, NULL, NULL, 24, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(26, 97, 'Transaction Management', './lib/base/form_advance_transaction.php?f=14', NULL, NULL, NULL, NULL, NULL, NULL, 21, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(27, 97, 'Detail Trasaction', './lib/base/form_advance_sub_nocrud.php?f=15', NULL, NULL, NULL, NULL, NULL, NULL, 21, NULL, NULL, NULL, NULL, NULL, NULL, 1),
(28, 97, 'Storages', './lib/base/form_advance_storage.php?f=18', NULL, NULL, NULL, NULL, NULL, NULL, 24, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(29, 97, 'Stock System', './lib/base/form_advance_stock.php?f=19', NULL, NULL, NULL, NULL, NULL, NULL, 24, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(30, 97, 'Detail Stock Opname', './lib/base/form_advance_sub.php?f=20', NULL, NULL, NULL, NULL, NULL, NULL, 21, NULL, NULL, NULL, NULL, NULL, NULL, 1),
(31, 97, 'RECEIVE', NULL, 0, 10414, 10417, 'false', 'false', NULL, 0, 0, 0, 'fa-fw fa-dropbox', NULL, NULL, NULL, NULL),
(32, 97, 'Receive Product', './lib/base/form_advance_receive.php?f=21', NULL, NULL, NULL, NULL, NULL, NULL, 31, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(33, 97, 'Detail Receive Product', './lib/base/form_advance_sub.php?f=22', NULL, NULL, NULL, NULL, NULL, NULL, 31, NULL, NULL, NULL, NULL, NULL, NULL, 1),
(35, 97, 'Stock Weight', './lib/base/form_advance_sw.php?f=24', NULL, NULL, NULL, NULL, NULL, NULL, 24, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(36, 97, 'Detail Stock Weight', './lib/base/form_advance_sub.php?f=25', NULL, NULL, NULL, NULL, NULL, NULL, 24, NULL, NULL, NULL, NULL, NULL, NULL, 1),
(37, 97, 'Data Reseller', './lib/base/form_advance_soft_delete.php?f=26', NULL, NULL, NULL, NULL, NULL, NULL, 3, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(38, 97, 'OUT PRODUCTS', NULL, 0, 10414, 10417, 'false', 'false', NULL, 0, 0, 0, 'fa-fw fa-random', NULL, 'Manual_Master_Data_Management_V_1_0_0.pdf', NULL, NULL),
(39, 97, 'Process Out Product', './lib/base/outproduct.php?f=27', NULL, NULL, NULL, NULL, NULL, NULL, 38, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(40, 97, 'Installment Reseller', './lib/base/form_advance_installment.php?f=27', NULL, NULL, NULL, NULL, NULL, NULL, 38, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(41, 97, 'Detail Installment', './lib/base/form_advance_sub.php?f=28', NULL, NULL, NULL, NULL, NULL, NULL, 38, NULL, NULL, NULL, NULL, NULL, NULL, 1),
(42, 97, 'Cost Operation', './lib/base/form_advance_cost.php?f=29', 0, 10414, 10417, 'false', 'false', NULL, 0, 0, 0, 'fa-fw fa-cc', NULL, 'Manual_Master_Data_Management_V_1_0_0.pdf', NULL, NULL),
(43, 97, 'Cost Operation Details', './lib/base/form_advance_sub.php?f=30', 0, 10414, 10417, 'false', 'false', NULL, 0, 0, 0, 'fa-fw fa-cc', NULL, 'Manual_Master_Data_Management_V_1_0_0.pdf', NULL, 1),
(44, 97, 'REPORTS', NULL, 0, 10414, 10417, 'false', 'false', NULL, 0, 0, 0, 'fa-fw fa-file-pdf-o', NULL, NULL, NULL, NULL),
(45, 97, 'Sales Report', './lib/base/form_report.php?f=23', NULL, NULL, NULL, NULL, NULL, NULL, 44, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(46, 97, 'Cash Flow Report', './lib/base/form_report_cashflow.php?f=31', NULL, NULL, NULL, NULL, NULL, NULL, 44, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(47, 97, 'Out Other Store', './lib/base/form_report.php?f=32', NULL, NULL, NULL, NULL, NULL, NULL, 38, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(48, 97, 'Out Other Store Detail', './lib/base/form_advance_sub.php?f=33', NULL, NULL, NULL, NULL, NULL, NULL, 38, NULL, NULL, NULL, NULL, NULL, NULL, 1);

-- --------------------------------------------------------

--
-- Struktur dari tabel `core_module`
--

CREATE TABLE `core_module` (
  `idmodule` bigint(20) NOT NULL,
  `modulename` varchar(50) DEFAULT NULL,
  `prefixtbl` varchar(10) DEFAULT NULL,
  `description` varchar(50) DEFAULT NULL,
  `version` varchar(5) DEFAULT NULL,
  `installed` int(11) DEFAULT NULL,
  `menufrom` int(11) DEFAULT NULL,
  `menuto` int(11) DEFAULT NULL,
  `icon` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Struktur dari tabel `core_otp`
--

CREATE TABLE `core_otp` (
  `id` bigint(100) NOT NULL,
  `otp` int(6) NOT NULL,
  `no_handphone` varchar(1000) NOT NULL,
  `uniq_code` varchar(100) DEFAULT NULL,
  `created_date` timestamp NULL DEFAULT current_timestamp(),
  `expired_date` timestamp NULL DEFAULT NULL,
  `expired` int(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `core_otp`
--

INSERT INTO `core_otp` (`id`, `otp`, `no_handphone`, `uniq_code`, `created_date`, `expired_date`, `expired`) VALUES
(1, 421246, 'u987ZAFXfO48bRWUIt56pSTNwT49JlknxV50pCghgU56MENCLCMdH0t8bMA1HG53KVwOFu55hcTOWl53AlYDUE55SpkgBJ57OJBFDK506vEM', '4cd922c863d5142f6b311e924e6e67bd', '2021-11-19 10:27:48', '2021-11-19 10:29:48', 1),
(2, 755718, 'u987ZAFXfO48bRWUIt56pSTNwT49JlknxV50pCghgU56MENCLCMdH0t8bMA1HG53KVwOFu55hcTOWl53AlYDUE55SpkgBJ57OJBFDK506vEM', '4793b36d292847f84d6e6718d48f73b3', '2021-11-19 10:31:21', '2021-11-19 10:33:21', 1),
(3, 345330, 'u987ZAFXfO48bRWUIt56pSTNwT49JlknxV50pCghgU56MENCLCMdH0t8bMA1HG53KVwOFu55hcTOWl53AlYDUE55SpkgBJ57OJBFDK506vEM', '0cc599ecb400a81c8c41772ec1b3b38d', '2021-11-19 14:19:50', '2021-11-19 14:21:50', 1),
(4, 943472, 'u987ZAFXfO48bRWUIt56pSTNwT49JlknxV50pCghgU56MENCLCMdH0t8bMA1HG53KVwOFu55hcTOWl53AlYDUE55SpkgBJ57OJBFDK506vEM', 'eeabe70a083b6397ed16d1bc33ca4283', '2021-11-21 12:47:32', '2021-11-21 12:49:32', 1),
(5, 794439, 'CfB6StqOd3120LLACC3640pwHSE3185xFgaP3250qmYRq3640fqzSeeYEjz413vY65z3445KHODI3575EoNpy3445CkVLC3575ErSwZ3705euFhS3250X8kv', '84d328eadee5420090db1dec66b26d72', '2021-11-24 03:51:28', '2021-11-24 03:53:28', 1),
(6, 958063, 'CfB6StqOd3120LLACC3640pwHSE3185xFgaP3250qmYRq3640fqzSeeYEjz413vY65z3445KHODI3575EoNpy3445CkVLC3575ErSwZ3705euFhS3250X8kv', '09e0aa6b2335fd678e26ffbdf925761f', '2021-11-24 15:30:02', '2021-11-24 15:32:02', 1),
(7, 926306, 'CfB6StqOd3120LLACC3640pwHSE3185xFgaP3250qmYRq3640fqzSeeYEjz413vY65z3445KHODI3575EoNpy3445CkVLC3575ErSwZ3705euFhS3250X8kv', '700d21592715e7f474d427c6ea2451b2', '2021-11-25 13:19:34', '2021-11-25 13:21:34', 1),
(8, 937404, 'CfB6StqOd3120LLACC3640pwHSE3185xFgaP3250qmYRq3640fqzSeeYEjz413vY65z3445KHODI3575EoNpy3445CkVLC3575ErSwZ3705euFhS3250X8kv', 'c1b5cabcf4ccc9001da9f8dd1f570dc5', '2021-11-25 13:25:15', '2021-11-25 13:27:15', 1),
(9, 384055, 'CfB6StqOd3120LLACC3640pwHSE3185xFgaP3250qmYRq3640fqzSeeYEjz413vY65z3445KHODI3575EoNpy3445CkVLC3575ErSwZ3705euFhS3250X8kv', 'a00704a4c1b76130c48ffee623e3590b', '2021-11-25 13:30:11', '2021-11-25 13:32:11', 1),
(10, 197743, 'CfB6StqOd3120LLACC3640pwHSE3185xFgaP3250qmYRq3640fqzSeeYEjz413vY65z3445KHODI3575EoNpy3445CkVLC3575ErSwZ3705euFhS3250X8kv', '365bc0ffa223df38da4eca97b835d965', '2021-11-27 14:08:05', '2021-11-27 14:18:05', 1),
(11, 589467, 'CfB6StqOd3120LLACC3640pwHSE3185xFgaP3250qmYRq3640fqzSeeYEjz413vY65z3445KHODI3575EoNpy3445CkVLC3575ErSwZ3705euFhS3250X8kv', '6568f43392ed33c89033a19c6bd0e294', '2021-11-30 07:47:13', '2021-11-30 07:57:13', 1),
(12, 841449, 'CfB6StqOd3120LLACC3640pwHSE3185xFgaP3250qmYRq3640fqzSeeYEjz413vY65z3445KHODI3575EoNpy3445CkVLC3575ErSwZ3705euFhS3250X8kv', '7f4c0e80a914f58a28777e9b86a49333', '2021-11-30 09:34:34', '2021-11-30 09:44:34', 1),
(13, 693727, 'CfB6StqOd3120LLACC3640pwHSE3185xFgaP3250qmYRq3640fqzSeeYEjz413vY65z3445KHODI3575EoNpy3445CkVLC3575ErSwZ3705euFhS3250X8kv', '6e4cd9762acbff8e2c35fd71fbcc0d6c', '2021-11-30 10:25:26', '2021-11-30 10:35:26', 1),
(14, 463761, 'CfB6StqOd3120LLACC3640pwHSE3185xFgaP3250qmYRq3640fqzSeeYEjz413vY65z3445KHODI3575EoNpy3445CkVLC3575ErSwZ3705euFhS3250X8kv', '98318e544d717076028550717389288c', '2021-12-01 07:34:26', '2021-12-01 07:44:26', 1),
(15, 939481, 'RGI5p2352o2744A2401b2450a2744K2uVrh1J649597Q2695h2597j2695g2793P2450JXmC', '85d8272b738108d5b7f9f6db8d58c2d0', '2021-12-01 08:11:54', '2021-12-01 08:21:54', 1),
(16, 849843, 'RGI5p2352o2744A2401b2450a2744K2uVrh1J649597Q2695h2597j2695g2793P2450JXmC', '428279f6aec3781189d36eb039ff10e0', '2021-12-02 00:07:17', '2021-12-02 00:17:17', 1),
(17, 378797, 'RGI5p2352o2744A2401b2450a2744K2uVrh1J649597Q2695h2597j2695g2793P2450JXmC', '15f42eec6b2f2a06354b43a11ba7b4f2', '2021-12-02 00:44:39', '2021-12-02 00:54:39', 1),
(18, 216542, 'RGI5p2352o2744A2401b2450a2744K2uVrh1J649597Q2695h2597j2695g2793P2450JXmC', '0bdae3d3480790417942089eac279806', '2021-12-02 00:49:40', '2021-12-02 00:59:40', 1),
(19, 794209, 'RGI5p2352o2744A2401b2450a2744K2uVrh1J649597Q2695h2597j2695g2793P2450JXmC', 'ed6b1f46edb69474ec3e590c09a53ef4', '2021-12-02 00:51:11', '2021-12-02 01:01:11', 1),
(20, 566617, '27JHlcM2976hdl3472hiM3410LFO3472YTc3224AkW3534taTb411RY62IMA3472fOr3472bjP3472JaP3038KPJ3224SOD3472RFoS', '48387c8314c73c212bfc3035eb32746e', '2021-12-02 00:56:30', '2021-12-02 01:06:30', NULL),
(21, 232067, 'RGI5p2352o2744A2401b2450a2744K2uVrh1J649597Q2695h2597j2695g2793P2450JXmC', '0470db4769bc3765320306fa79bdea5e', '2021-12-02 00:59:28', '2021-12-02 01:09:28', 1),
(22, 657470, 'pxGKq2640W3080Z2695q2805s2805A2695Xtk4G9H55S2640d2805n2805q2695H2640L2695U9BR', 'a0cd25327c5e02d9b70276c11df222d0', '2021-12-02 01:02:25', '2021-12-02 01:12:25', 1),
(23, 777669, 'RGI5p2352o2744A2401b2450a2744K2uVrh1J649597Q2695h2597j2695g2793P2450JXmC', '2e25e17d34c632ce23859ff63a18e9fa', '2021-12-06 06:53:32', '2021-12-06 07:03:32', 1),
(24, 645613, 'V2grOywL1296CNFf1512Ybit1323jkjW1350nSKK1512MqfZoptJM412k271431ddzK1485hMaG1431njox1485IlKw1539IBmB13507lao', '1851f3785cd0115e36f0cb3add057db3', '2021-12-07 15:12:22', '2021-12-07 15:22:22', 1),
(25, 494498, 'V2grOywL1296CNFf1512Ybit1323jkjW1350nSKK1512MqfZoptJM412k271431ddzK1485hMaG1431njox1485IlKw1539IBmB13507lao', 'b028c01c10fdbddb48d848d086f4d6f9', '2021-12-07 15:12:52', '2021-12-07 15:22:52', 1),
(26, 302695, 'V2grOywL1296CNFf1512Ybit1323jkjW1350nSKK1512MqfZoptJM412k271431ddzK1485hMaG1431njox1485IlKw1539IBmB13507lao', 'f9fe2e2c7e70efac9e39d2b09f06eccd', '2021-12-08 16:50:52', '2021-12-08 17:00:52', 1),
(27, 839911, 'V2grOywL1296CNFf1512Ybit1323jkjW1350nSKK1512MqfZoptJM412k271431ddzK1485hMaG1431njox1485IlKw1539IBmB13507lao', 'd3dc0b2512f6361f7398a1fea1db02d0', '2022-03-01 08:54:21', '2022-03-01 09:04:21', 1),
(28, 282413, 'V2grOywL1296CNFf1512Ybit1323jkjW1350nSKK1512MqfZoptJM412k271431ddzK1485hMaG1431njox1485IlKw1539IBmB13507lao', 'd67e8a267c8eaf1152d11320c21abded', '2022-03-01 09:42:13', '2022-03-01 09:52:13', 1),
(29, 776959, 'V2grOywL1296CNFf1512Ybit1323jkjW1350nSKK1512MqfZoptJM412k271431ddzK1485hMaG1431njox1485IlKw1539IBmB13507lao', '6db54f191000636e7077ef2501d3095a', '2022-03-26 01:48:08', '2022-03-26 01:58:08', 1),
(30, 625460, 'V2grOywL1296CNFf1512Ybit1323jkjW1350nSKK1512MqfZoptJM412k271431ddzK1485hMaG1431njox1485IlKw1539IBmB13507lao', 'd1cb698198a3c229897cc60aac81abda', '2022-12-11 12:09:18', '2022-12-11 12:19:18', 1),
(31, 510517, 'V2grOywL1296CNFf1512Ybit1323jkjW1350nSKK1512MqfZoptJM412k271431ddzK1485hMaG1431njox1485IlKw1539IBmB13507lao', 'a4d870f1e913015745b2b4de5b80d82a', '2022-12-11 12:12:33', '2022-12-11 12:22:33', 1),
(32, 857011, 'V2grOywL1296CNFf1512Ybit1323jkjW1350nSKK1512MqfZoptJM412k271431ddzK1485hMaG1431njox1485IlKw1539IBmB13507lao', 'f54d935cb90583a0d5cfd52c2fa88427', '2022-12-11 21:45:47', '2022-12-11 21:55:47', 1),
(33, 836295, 'V2grOywL1296CNFf1512Ybit1323jkjW1350nSKK1512MqfZoptJM412k271431ddzK1485hMaG1431njox1485IlKw1539IBmB13507lao', '1dc925af768703aa43591877844ad23a', '2022-12-11 21:51:04', '2022-12-11 22:01:04', 1),
(34, 956671, 'V2grOywL1296CNFf1512Ybit1323jkjW1350nSKK1512MqfZoptJM412k271431ddzK1485hMaG1431njox1485IlKw1539IBmB13507lao', 'e3ade01ffccaae2873eb74406336ff19', '2022-12-12 08:59:03', '2022-12-12 09:09:03', 1),
(35, 261518, 'V2grOywL1296CNFf1512Ybit1323jkjW1350nSKK1512MqfZoptJM412k271431ddzK1485hMaG1431njox1485IlKw1539IBmB13507lao', 'ac26e622ab0b35556137740ef7f8787e', '2022-12-13 05:26:26', '2022-12-13 05:36:26', 1),
(36, 138266, 'V2grOywL1296CNFf1512Ybit1323jkjW1350nSKK1512MqfZoptJM412k271431ddzK1485hMaG1431njox1485IlKw1539IBmB13507lao', 'e31433414b659a0e266b360434691b85', '2022-12-13 05:38:00', '2022-12-13 05:48:00', 1),
(37, 254023, 'V2grOywL1296CNFf1512Ybit1323jkjW1350nSKK1512MqfZoptJM412k271431ddzK1485hMaG1431njox1485IlKw1539IBmB13507lao', 'c715069f6fef9b3728c6f8e3913fc44b', '2022-12-13 06:59:35', '2022-12-13 07:09:35', 1),
(38, 958315, 'SGLxcJ960W1120bW980m1000s1120W1kCjE0C520060F1100K1060D1100r1140W1000wqgv', '20b64896886f9063d439fe8e0f576bc8', '2022-12-13 10:44:01', '2022-12-13 10:54:01', 1);

-- --------------------------------------------------------

--
-- Struktur dari tabel `core_rolearea`
--

CREATE TABLE `core_rolearea` (
  `id` bigint(20) NOT NULL,
  `iduser` bigint(20) NOT NULL,
  `tablearea` text NOT NULL,
  `idarea` bigint(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Struktur dari tabel `core_rolemenu`
--

CREATE TABLE `core_rolemenu` (
  `id` bigint(20) NOT NULL,
  `iduser` bigint(20) NOT NULL,
  `idmenu` bigint(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `core_rolemenu`
--

INSERT INTO `core_rolemenu` (`id`, `iduser`, `idmenu`) VALUES
(5, 2, 10),
(6, 2, 1),
(7, 2, 12),
(8, 2, 6),
(9, 2, 8),
(10, 2, 5),
(11, 2, 9),
(12, 2, 11),
(13, 2, 4),
(236, 3, 33),
(237, 3, 30),
(238, 3, 20),
(239, 3, 8),
(240, 3, 5),
(241, 3, 32),
(242, 3, 25),
(243, 3, 29),
(244, 3, 28),
(276, 7, 23),
(277, 7, 20),
(278, 7, 8),
(279, 7, 5),
(280, 7, 32),
(281, 7, 25),
(282, 7, 29),
(283, 7, 28),
(284, 7, 4),
(313, 6, 23),
(314, 6, 22),
(315, 6, 33),
(316, 6, 30),
(317, 6, 27),
(318, 6, 20),
(319, 6, 8),
(320, 6, 5),
(321, 6, 32),
(322, 6, 34),
(323, 6, 25),
(324, 6, 29),
(325, 6, 28),
(326, 6, 26),
(327, 6, 4),
(328, 5, 23),
(329, 5, 22),
(330, 5, 5),
(544, 4, 23),
(545, 4, 46),
(546, 4, 22),
(547, 4, 42),
(548, 4, 43),
(549, 4, 37),
(550, 4, 41),
(551, 4, 33),
(552, 4, 30),
(553, 4, 36),
(554, 4, 27),
(555, 4, 12),
(556, 4, 40),
(557, 4, 47),
(558, 4, 48),
(559, 4, 20),
(560, 4, 39),
(561, 4, 8),
(562, 4, 5),
(563, 4, 32),
(564, 4, 45),
(565, 4, 25),
(566, 4, 29),
(567, 4, 35),
(568, 4, 28),
(569, 4, 26),
(570, 4, 4);

-- --------------------------------------------------------

--
-- Struktur dari tabel `core_roles_jabatan`
--

CREATE TABLE `core_roles_jabatan` (
  `id` bigint(100) NOT NULL,
  `roles` varchar(500) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `core_roles_jabatan`
--

INSERT INTO `core_roles_jabatan` (`id`, `roles`) VALUES
(1, 'Admin TTE'),
(2, 'Pejabat');

-- --------------------------------------------------------

--
-- Struktur dari tabel `core_rules_level`
--

CREATE TABLE `core_rules_level` (
  `id` bigint(100) NOT NULL,
  `id_user_level_top` bigint(20) DEFAULT NULL,
  `id_user_level_down` bigint(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `core_rules_level`
--

INSERT INTO `core_rules_level` (`id`, `id_user_level_top`, `id_user_level_down`) VALUES
(56, 26, 27),
(57, 26, 34),
(58, 26, 40),
(59, 26, 46),
(60, 26, 47),
(61, 26, 51),
(62, 26, 59),
(63, 27, 28),
(64, 27, 30),
(65, 28, 29),
(66, 30, 31),
(67, 30, 32),
(68, 30, 33),
(69, 34, 35),
(70, 34, 36),
(71, 34, 37),
(72, 37, 38),
(73, 37, 39),
(74, 40, 41),
(75, 40, 42),
(76, 40, 43),
(77, 40, 44),
(78, 40, 45),
(79, 47, 48),
(80, 47, 49),
(81, 47, 50),
(82, 51, 52),
(83, 51, 53),
(84, 51, 54),
(85, 54, 55),
(86, 54, 56),
(87, 54, 57),
(88, 54, 58);

-- --------------------------------------------------------

--
-- Struktur dari tabel `core_user`
--

CREATE TABLE `core_user` (
  `id` bigint(20) NOT NULL,
  `userid` varchar(100) NOT NULL,
  `userpass` varchar(150) NOT NULL,
  `username` varchar(150) NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `firstname` varchar(100) DEFAULT NULL,
  `lastname` varchar(100) DEFAULT NULL,
  `no_handphone` varchar(1000) DEFAULT NULL,
  `islogin` int(11) NOT NULL DEFAULT 0,
  `isactive` int(11) NOT NULL DEFAULT 1,
  `rolemenu` bigint(20) DEFAULT NULL,
  `rolearea` bigint(20) DEFAULT NULL,
  `createby` varchar(150) DEFAULT NULL,
  `createdate` date DEFAULT NULL,
  `approvedby` varchar(150) DEFAULT NULL,
  `approveddate` date DEFAULT NULL,
  `wrongpass` int(11) DEFAULT NULL,
  `id_group_management` bigint(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `core_user`
--

INSERT INTO `core_user` (`id`, `userid`, `userpass`, `username`, `email`, `firstname`, `lastname`, `no_handphone`, `islogin`, `isactive`, `rolemenu`, `rolearea`, `createby`, `createdate`, `approvedby`, `approveddate`, `wrongpass`, `id_group_management`) VALUES
(2, 'adesulaeman', '$P$BBtvIoC.GvjBEY5gAQhiyZkPpJFYmY.', 'Ade Sulaeman', 'adesulaiman@gmail.com', 'ade', 'sulaeman', 'SGLxcJ960W1120bW980m1000s1120W1kCjE0C520060F1100K1060D1100r1140W1000wqgv', 0, 1, NULL, NULL, NULL, NULL, NULL, NULL, 2, 4);

-- --------------------------------------------------------

--
-- Stand-in struktur untuk tampilan `core_vw_rolemenus`
-- (Lihat di bawah untuk tampilan aktual)
--
CREATE TABLE `core_vw_rolemenus` (
`idmenus` varchar(41)
,`idmodule` bigint(20)
,`menu` varchar(75)
,`icon` varchar(50)
,`withframe` int(11)
,`parent` varchar(32)
,`child` int(1)
,`links` varchar(255)
,`idmenu` bigint(20)
,`iduser` bigint(20)
,`sub_page` int(1)
);

-- --------------------------------------------------------

--
-- Stand-in struktur untuk tampilan `vw_access`
-- (Lihat di bawah untuk tampilan aktual)
--
CREATE TABLE `vw_access` (
`id` bigint(20)
,`userid` varchar(168)
,`userpass` varchar(150)
,`username` varchar(150)
,`email` varchar(100)
,`firstname` varchar(100)
,`lastname` varchar(100)
,`islogin` int(11)
,`isactive` int(11)
,`rolemenu` bigint(20)
,`rolearea` bigint(20)
,`createby` varchar(150)
,`createdate` date
,`approvedby` varchar(150)
,`approveddate` date
,`no_handphone` varchar(1000)
,`id_group_management` varchar(200)
);

-- --------------------------------------------------------

--
-- Stand-in struktur untuk tampilan `vw_group_management`
-- (Lihat di bawah untuk tampilan aktual)
--
CREATE TABLE `vw_group_management` (
`id` bigint(100)
,`group_name` varchar(200)
,`created_by` varchar(100)
,`created_date` timestamp
,`update_by` varchar(100)
,`update_date` timestamp
);

-- --------------------------------------------------------

--
-- Stand-in struktur untuk tampilan `vw_menu_set_role`
-- (Lihat di bawah untuk tampilan aktual)
--
CREATE TABLE `vw_menu_set_role` (
`id` bigint(20)
,`menu` varchar(75)
);

-- --------------------------------------------------------

--
-- Struktur untuk view `core_vw_rolemenus`
--
DROP TABLE IF EXISTS `core_vw_rolemenus`;

CREATE ALGORITHM=UNDEFINED DEFINER=`u965363723_superuser`@`%` SQL SECURITY DEFINER VIEW `core_vw_rolemenus`  AS SELECT concat(concat(`core_menus`.`idmodule`,'-'),`core_menus`.`id`) AS `idmenus`, `core_menus`.`idmodule` AS `idmodule`, `core_menus`.`menu` AS `menu`, `core_menus`.`icon` AS `icon`, `core_menus`.`withframe` AS `withframe`, CASE WHEN `core_menus`.`parent` = 0 THEN `core_menus`.`parent` ELSE concat(concat(`core_menus`.`idmodule`,'-'),`core_menus`.`parent`) END AS `parent`, 0 AS `child`, `core_menus`.`links` AS `links`, `rlm`.`idmenu` AS `idmenu`, `rlm`.`iduser` AS `iduser`, `core_menus`.`sub_page` AS `sub_page` FROM (`core_menus` left join `core_rolemenu` `rlm` on(`core_menus`.`id` = `rlm`.`idmenu`)) ;

-- --------------------------------------------------------

--
-- Struktur untuk view `vw_access`
--
DROP TABLE IF EXISTS `vw_access`;

CREATE ALGORITHM=UNDEFINED DEFINER=`u965363723_superuser`@`127.0.0.1` SQL SECURITY DEFINER VIEW `vw_access`  AS SELECT `u`.`id` AS `id`, CASE WHEN `u`.`wrongpass` >= 3 THEN concat('<small class="label bg-red"><i class="fa fa-lock"></i> Lock</small> ',`u`.`userid`) ELSE `u`.`userid` END AS `userid`, `u`.`userpass` AS `userpass`, `u`.`username` AS `username`, `u`.`email` AS `email`, `u`.`firstname` AS `firstname`, `u`.`lastname` AS `lastname`, `u`.`islogin` AS `islogin`, `u`.`isactive` AS `isactive`, `u`.`rolemenu` AS `rolemenu`, `u`.`rolearea` AS `rolearea`, `u`.`createby` AS `createby`, `u`.`createdate` AS `createdate`, `u`.`approvedby` AS `approvedby`, `u`.`approveddate` AS `approveddate`, `u`.`no_handphone` AS `no_handphone`, `gr`.`group_name` AS `id_group_management` FROM (`core_user` `u` left join `core_group` `gr` on(`u`.`id_group_management` = `gr`.`id`)) ;

-- --------------------------------------------------------

--
-- Struktur untuk view `vw_group_management`
--
DROP TABLE IF EXISTS `vw_group_management`;

CREATE ALGORITHM=UNDEFINED DEFINER=`u965363723_superuser`@`127.0.0.1` SQL SECURITY DEFINER VIEW `vw_group_management`  AS SELECT `core_group`.`id` AS `id`, `core_group`.`group_name` AS `group_name`, `core_group`.`created_by` AS `created_by`, `core_group`.`created_date` AS `created_date`, `core_group`.`update_by` AS `update_by`, `core_group`.`update_date` AS `update_date` FROM `core_group` ;

-- --------------------------------------------------------

--
-- Struktur untuk view `vw_menu_set_role`
--
DROP TABLE IF EXISTS `vw_menu_set_role`;

CREATE ALGORITHM=UNDEFINED DEFINER=`u965363723_superuser`@`127.0.0.1` SQL SECURITY DEFINER VIEW `vw_menu_set_role`  AS SELECT `core_menus`.`id` AS `id`, `core_menus`.`menu` AS `menu` FROM `core_menus` WHERE `core_menus`.`links` is not null ;

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `absen`
--
ALTER TABLE `absen`
  ADD PRIMARY KEY (`kd_absen`);

--
-- Indeks untuk tabel `core_fields`
--
ALTER TABLE `core_fields`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `core_forms`
--
ALTER TABLE `core_forms`
  ADD PRIMARY KEY (`idform`);

--
-- Indeks untuk tabel `core_group`
--
ALTER TABLE `core_group`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `core_level`
--
ALTER TABLE `core_level`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `core_menus`
--
ALTER TABLE `core_menus`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `core_otp`
--
ALTER TABLE `core_otp`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `core_rolemenu`
--
ALTER TABLE `core_rolemenu`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `core_roles_jabatan`
--
ALTER TABLE `core_roles_jabatan`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `core_rules_level`
--
ALTER TABLE `core_rules_level`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `core_user`
--
ALTER TABLE `core_user`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `absen`
--
ALTER TABLE `absen`
  MODIFY `kd_absen` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `core_fields`
--
ALTER TABLE `core_fields`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=458;

--
-- AUTO_INCREMENT untuk tabel `core_forms`
--
ALTER TABLE `core_forms`
  MODIFY `idform` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

--
-- AUTO_INCREMENT untuk tabel `core_group`
--
ALTER TABLE `core_group`
  MODIFY `id` bigint(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT untuk tabel `core_level`
--
ALTER TABLE `core_level`
  MODIFY `id` bigint(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT untuk tabel `core_menus`
--
ALTER TABLE `core_menus`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=49;

--
-- AUTO_INCREMENT untuk tabel `core_otp`
--
ALTER TABLE `core_otp`
  MODIFY `id` bigint(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=39;

--
-- AUTO_INCREMENT untuk tabel `core_rolemenu`
--
ALTER TABLE `core_rolemenu`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=571;

--
-- AUTO_INCREMENT untuk tabel `core_roles_jabatan`
--
ALTER TABLE `core_roles_jabatan`
  MODIFY `id` bigint(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT untuk tabel `core_rules_level`
--
ALTER TABLE `core_rules_level`
  MODIFY `id` bigint(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=89;

--
-- AUTO_INCREMENT untuk tabel `core_user`
--
ALTER TABLE `core_user`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=53;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
