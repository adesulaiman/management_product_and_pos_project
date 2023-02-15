-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Feb 15, 2023 at 06:02 AM
-- Server version: 10.5.16-MariaDB-cll-lve
-- PHP Version: 7.2.34

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `u965363723_management`
--

DELIMITER $$
--
-- Functions
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
-- Table structure for table `core_fields`
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
-- Dumping data for table `core_fields`
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
-- Table structure for table `core_filter`
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
-- Table structure for table `core_forms`
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
-- Dumping data for table `core_forms`
--

INSERT INTO `core_forms` (`idform`, `formcode`, `formname`, `description`, `withsearch`, `withimport`, `withexport`, `withupload`, `withparent`, `parentkey`, `processpage`, `helpfile`, `keyfield`, `srcupload`, `formview`) VALUES
(1, 'core_user', 'users', 'Data User', 1, 1, 1, 1, 1, 1, NULL, NULL, NULL, NULL, 'vw_access'),
(5, 'core_user', 'core_user', 'Set Level Users', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'vw_user_level'),
(8, 'core_group', 'core_group', 'Group Management', 1, 1, 1, 1, 1, 1, NULL, NULL, NULL, NULL, 'vw_group_management'),
(11, 'data_agenda_no_surat', 'data_agenda_no_surat', 'Agenda Nomor Surat', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'vw_agenda_no_surat'),
(12, 'data_file', 'data_file', 'Data Files', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'vw_data_file'),
(13, 'data_category', 'data_category', 'Category', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'data_category'),
(14, 'data_sales', 'data_sales', 'Transaction Management', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'data_vw_sales'),
(15, 'data_sales_detail', 'data_sales_detail', 'Detail Transaction', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'data_vw_detail_sales'),
(16, 'data_product', 'data_product', 'Product Management', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'data_vw_product'),
(17, 'data_stock_opname', 'data_stock_opname', 'Stock Opname', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'vw_data_stock_opname'),
(18, 'data_category_storage', 'data_category_storage', 'Storage', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'data_vw_storage'),
(19, 'data_stock_product', 'data_stock_product', 'Stock Product', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'data_vw_stock'),
(20, 'data_stock_opname_detail', 'data_stock_opname_detail', 'Detail Stock Opname', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'vw_data_detail_stock'),
(21, 'data_receive', 'data_receive', 'Receive Product', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'vw_data_receive'),
(22, 'data_detail_receive', 'data_detail_receive', 'Detail Receive Product', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'vw_data_detail_receive'),
(23, 'vw_data_sales_monthly', 'vw_data_sales_monthly', 'Report Sales', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'vw_data_sales_monthly'),
(24, 'data_stock_weight', 'data_stock_weight', 'Stock Weight', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'vw_data_sw'),
(25, 'data_stock_weight_detail', 'data_stock_weight_detail', 'Detail Stock Weight', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'vw_data_stock_weight'),
(26, 'data_resaller', 'data_resaller', 'Data Reseller', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'data_vw_reseller'),
(27, 'data_product_out_resaller', 'data_product_out_resaller', 'Product Out To Reseller', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'data_vw_product_out_reseller'),
(28, 'data_resaller_payment', 'data_resaller_payment', 'Installment Reseller', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'vw_data_installment_reseller'),
(29, 'data_cost_daily', 'data_cost_daily', 'Cost Daily Operation', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'data_vw_cost_daily'),
(30, 'data_cost_daily_detail', 'data_cost_daily_detail', 'Cost Daily Operation Detail', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'data_vw_cost_daily_detail'),
(31, 'data_vw_cashflow', 'data_vw_cashflow', 'Cash Flow Report', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'data_vw_cashflow'),
(32, 'data_vw_os', 'data_vw_os', 'Out Product Other Store', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'data_vw_os'),
(33, 'data_vw_os_detail', 'data_vw_os_detail', 'Out Product Other Store Detail', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'data_vw_os_detail');

-- --------------------------------------------------------

--
-- Table structure for table `core_group`
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
-- Dumping data for table `core_group`
--

INSERT INTO `core_group` (`id`, `group_name`, `created_by`, `created_date`, `update_by`, `update_date`) VALUES
(4, 'Super User', NULL, NULL, NULL, NULL),
(5, 'Cashier', NULL, NULL, NULL, NULL),
(6, 'Owner', NULL, NULL, NULL, NULL),
(7, 'Staff IT', NULL, NULL, NULL, NULL),
(8, 'staff gudang', NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `core_level`
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
-- Dumping data for table `core_level`
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
-- Table structure for table `core_logic`
--

CREATE TABLE `core_logic` (
  `id` bigint(20) NOT NULL,
  `logic` text NOT NULL,
  `description` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `core_logic`
--

INSERT INTO `core_logic` (`id`, `logic`, `description`) VALUES
(1, 'like', 'Contains'),
(2, '>=', 'Greater Than'),
(3, '<=', 'Less Than'),
(4, '<>', 'Not Equal'),
(5, '=', 'Equal');

-- --------------------------------------------------------

--
-- Table structure for table `core_menus`
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
-- Dumping data for table `core_menus`
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
-- Table structure for table `core_module`
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
-- Table structure for table `core_otp`
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
-- Dumping data for table `core_otp`
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
-- Table structure for table `core_rolearea`
--

CREATE TABLE `core_rolearea` (
  `id` bigint(20) NOT NULL,
  `iduser` bigint(20) NOT NULL,
  `tablearea` text NOT NULL,
  `idarea` bigint(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `core_rolemenu`
--

CREATE TABLE `core_rolemenu` (
  `id` bigint(20) NOT NULL,
  `iduser` bigint(20) NOT NULL,
  `idmenu` bigint(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `core_rolemenu`
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
-- Table structure for table `core_roles_jabatan`
--

CREATE TABLE `core_roles_jabatan` (
  `id` bigint(100) NOT NULL,
  `roles` varchar(500) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `core_roles_jabatan`
--

INSERT INTO `core_roles_jabatan` (`id`, `roles`) VALUES
(1, 'Admin TTE'),
(2, 'Pejabat');

-- --------------------------------------------------------

--
-- Table structure for table `core_rules_level`
--

CREATE TABLE `core_rules_level` (
  `id` bigint(100) NOT NULL,
  `id_user_level_top` bigint(20) DEFAULT NULL,
  `id_user_level_down` bigint(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `core_rules_level`
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
-- Table structure for table `core_user`
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
-- Dumping data for table `core_user`
--

INSERT INTO `core_user` (`id`, `userid`, `userpass`, `username`, `email`, `firstname`, `lastname`, `no_handphone`, `islogin`, `isactive`, `rolemenu`, `rolearea`, `createby`, `createdate`, `approvedby`, `approveddate`, `wrongpass`, `id_group_management`) VALUES
(2, 'adesulaeman', '$P$BBtvIoC.GvjBEY5gAQhiyZkPpJFYmY.', 'Ade Sulaeman', 'adesulaiman@gmail.com', 'ade', 'sulaeman', 'SGLxcJ960W1120bW980m1000s1120W1kCjE0C520060F1100K1060D1100r1140W1000wqgv', 0, 1, NULL, NULL, NULL, NULL, NULL, NULL, 2, 4),
(47, 'septi', '$P$BRY/HMDjDOVmdJ0CdW0O1N4B3VddPr0', 'septi', '', '', '', '5YSPVRH1056DtX1232WxM1078MSr1100sxy1232bQe1210vxbN0X7dx22wVy1232CVQ1210UNn1100qFH1232IKv1122iBm1100euRA', 0, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 4),
(51, 'Chandra', '$P$Bwtkn/fqkDfSOnBErZs9t9bjKhdc9l0', 'Chandra', 'chandra@gmail.com', 'chandra', 'hermansyah', '9B4NJjQk960emJ1120leIn980bJJ1020kLh1080qAZ1140CqEj2d9Jz20RgX1060gqC1040Gcz1040uFJ1020gXA1080RZU1140YZ21', 0, 1, NULL, NULL, NULL, NULL, NULL, NULL, 0, 6),
(52, 'Dea', '$P$BV4qSLxhzyrvY8mR38AaeDhbG/asc0/', 'Dea', 'Dea@gmail.com', 'Dea', 'Natasya', 'IhoPAB1824GP2128pI1862na1938Af2052Tc2166qhxS3C9Z38aR2014AY1976Ve1976ai1938Uf2052KN2166kZrl', 0, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 4);

-- --------------------------------------------------------

--
-- Stand-in structure for view `core_vw_rolemenus`
-- (See below for the actual view)
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
-- Table structure for table `data_category_storage`
--

CREATE TABLE `data_category_storage` (
  `id` bigint(20) NOT NULL,
  `storage_name` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `gram` decimal(10,2) DEFAULT NULL,
  `is_active` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_delete` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_by` varchar(80) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_date` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `data_category_storage`
--

INSERT INTO `data_category_storage` (`id`, `storage_name`, `gram`, `is_active`, `is_delete`, `created_by`, `created_date`) VALUES
(1, 'Talang', NULL, '', '1', 'septi', '2023-02-02 01:27:47'),
(2, 'Box', NULL, '', '1', 'septi', '2023-02-02 01:27:58'),
(3, 'Etalase ', NULL, '', '1', 'septi', '2023-02-02 01:27:53'),
(4, 'T1 / CINCIN', '100.00', '', '', 'Dea', '2023-02-09 01:16:32'),
(5, 'T2 / CINCIN', '100.00', '', '', 'Dea', '2023-02-08 15:33:51'),
(6, 'T3 / ANTING', '100.00', '', '', 'Dea', '2023-02-08 15:34:07'),
(7, 'T4 / GELANG RANTAI', '250.00', '', '', 'Dea', '2023-02-08 15:34:29'),
(8, 'T5 / GELANG AD', '270.00', '', '', 'Dea', '2023-02-08 15:35:30'),
(9, 'T6 / GELANG KR', '77.00', '', '', 'Dea', '2023-02-08 22:35:52'),
(10, 'T7 / KALUNG POLOS', '120.00', '', '', 'Dea', '2023-02-08 22:36:18'),
(11, 'Gold Repair', '1.00', '', '', 'adesulaeman', '2023-02-11 14:40:28');

-- --------------------------------------------------------

--
-- Table structure for table `data_cost_daily`
--

CREATE TABLE `data_cost_daily` (
  `id` bigint(20) NOT NULL,
  `cost_date` date DEFAULT NULL,
  `opening_cash` decimal(15,2) DEFAULT NULL,
  `status` varchar(200) COLLATE utf8mb4_unicode_ci DEFAULT 'open',
  `created_by` varchar(200) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_date` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `data_cost_daily`
--

INSERT INTO `data_cost_daily` (`id`, `cost_date`, `opening_cash`, `status`, `created_by`, `created_date`) VALUES
(1, '2023-02-11', '10500000.00', 'close', 'adesulaeman', '2023-02-11 14:53:02'),
(5, '2023-02-12', '10200000.00', 'open', 'adesulaeman', '2023-02-14 20:07:31');

-- --------------------------------------------------------

--
-- Table structure for table `data_cost_daily_detail`
--

CREATE TABLE `data_cost_daily_detail` (
  `id` bigint(20) NOT NULL,
  `id_cost_daily` bigint(20) DEFAULT NULL,
  `nominal` decimal(15,2) DEFAULT NULL,
  `information` varchar(1000) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_by` varchar(200) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_date` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `data_cost_daily_detail`
--

INSERT INTO `data_cost_daily_detail` (`id`, `id_cost_daily`, `nominal`, `information`, `created_by`, `created_date`) VALUES
(1, 1, '50000.00', 'Jajan', 'adesulaeman', '2023-02-11 15:07:00'),
(2, 1, '200000.00', 'Makan ', 'adesulaeman', '2023-02-11 15:07:10'),
(3, 1, '5000000.00', 'Arisan', 'adesulaeman', '2023-02-11 15:07:19'),
(4, 1, '2500000.00', 'Bahan', 'adesulaeman', '2023-02-11 15:07:28'),
(5, 4, '100000.00', 'jajan', 'Dea', '2023-02-13 13:19:28'),
(6, 5, '80000.00', 'Makan', 'adesulaeman', '2023-02-14 20:07:41');

-- --------------------------------------------------------

--
-- Table structure for table `data_detail_order`
--

CREATE TABLE `data_detail_order` (
  `id` bigint(20) NOT NULL,
  `no_order` bigint(20) NOT NULL,
  `id_product` bigint(20) NOT NULL,
  `product_name` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `id_supplier` bigint(20) NOT NULL,
  `qty` decimal(10,0) NOT NULL,
  `gram` decimal(10,0) NOT NULL,
  `buy_price` decimal(10,0) NOT NULL,
  `created_by` bigint(20) NOT NULL,
  `created_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `data_detail_receive`
--

CREATE TABLE `data_detail_receive` (
  `id` bigint(20) NOT NULL,
  `id_receive` bigint(20) NOT NULL,
  `barcode` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `product_name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `qty_receive` decimal(10,0) NOT NULL,
  `id_storage` bigint(20) DEFAULT NULL,
  `storage_name` varchar(500) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `total_gram` decimal(10,2) NOT NULL,
  `total_price` decimal(15,2) NOT NULL,
  `created_by` varchar(300) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `data_detail_receive`
--

INSERT INTO `data_detail_receive` (`id`, `id_receive`, `barcode`, `product_name`, `qty_receive`, `id_storage`, `storage_name`, `total_gram`, `total_price`, `created_by`, `created_date`) VALUES
(1, 1, '000019', 'BELI CINCIN 1 (700.00 karat)', '1', 5, 'T2 / CINCIN', '1.30', '2300000.00', 'adesulaeman', '2023-02-11 15:01:30'),
(2, 2, '000020', 'BELI CINCIN 2 (700.00 karat)', '1', 4, 'T1 / CINCIN', '1.10', '3200000.00', 'adesulaeman', '2023-02-11 15:05:35'),
(3, 5, '000005', 'CINCIN FUSION (700.00 karat)', '1', 6, 'T3 / ANTING', '5.00', '500000.00', 'adesulaeman', '2023-02-14 20:17:17');

-- --------------------------------------------------------

--
-- Table structure for table `data_order`
--

CREATE TABLE `data_order` (
  `no_order` bigint(20) NOT NULL,
  `order_date` date NOT NULL,
  `status_order` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_by` bigint(20) NOT NULL,
  `created_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `data_product`
--

CREATE TABLE `data_product` (
  `id` bigint(20) NOT NULL,
  `barcode` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `product_name` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` varchar(200) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `kadar_product` decimal(10,2) NOT NULL,
  `netto_gram` decimal(10,2) NOT NULL,
  `brutto_gram` decimal(10,2) NOT NULL,
  `sell_price` decimal(10,0) NOT NULL,
  `is_delete` int(1) DEFAULT 0,
  `created_by` varchar(500) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `data_product`
--

INSERT INTO `data_product` (`id`, `barcode`, `product_name`, `description`, `kadar_product`, `netto_gram`, `brutto_gram`, `sell_price`, `is_delete`, `created_by`, `created_date`) VALUES
(1, '000001', 'CINCIN BAYI BONEKA', '.', '700.00', '1.00', '1.30', '0', 0, 'Dea', '2023-02-09 08:18:40'),
(2, '000002', 'CINCIN BAYI', '.', '700.00', '0.50', '0.50', '0', 0, 'Dea', '2023-02-09 08:19:07'),
(3, '000003', 'CINCIN BAYI', '.', '700.00', '1.00', '0.00', '0', 0, 'Dea', '2023-02-09 08:19:23'),
(4, '000004', 'CINCIN ', '.', '700.00', '1.20', '0.00', '0', 0, 'Dea', '2023-02-09 08:20:29'),
(5, '000005', 'CINCIN FUSION', '.', '700.00', '15.00', '0.00', '0', 0, 'Dea', '2023-02-09 08:20:55'),
(6, '000006', 'CINCIN KR', '.', '700.00', '7.00', '0.00', '0', 0, 'Dea', '2023-02-09 08:21:20'),
(7, '000007', 'CINCIN KR', '.', '700.00', '12.00', '0.00', '0', 0, 'Dea', '2023-02-09 08:21:37'),
(8, '000008', 'ANTING', '.', '700.00', '1.00', '0.00', '0', 0, 'Dea', '2023-02-09 08:21:58'),
(9, '000009', 'ANTING', '.', '700.00', '1.00', '0.00', '0', 0, 'Dea', '2023-02-09 08:22:23'),
(10, '000010', 'ANTING WAYANG', '.', '700.00', '3.20', '0.00', '0', 0, 'Dea', '2023-02-09 08:22:52'),
(11, '000011', 'GELANG VENCY ', '.', '700.00', '5.00', '0.00', '0', 0, 'Dea', '2023-02-09 08:23:25'),
(12, '000012', 'GELANG HOLO', '.', '700.00', '3.00', '0.00', '0', 0, 'Dea', '2023-02-09 08:24:26'),
(13, '000013', 'GELANG CD', '.', '700.00', '5.00', '0.00', '0', 0, 'Dea', '2023-02-09 08:25:45'),
(14, '000014', 'GELANG BANGKOK', '.', '700.00', '3.00', '0.00', '0', 0, 'Dea', '2023-02-09 08:26:10'),
(15, '000015', 'GELANG BANGKOK', '.', '700.00', '3.00', '0.00', '0', 0, 'Dea', '2023-02-09 08:26:25'),
(16, '000016', 'GELANG BANGKOK', '.', '700.00', '3.00', '0.00', '0', 0, 'Dea', '2023-02-09 08:26:42'),
(17, '000017', 'CINCIN BAYI', '.', '700.00', '1.50', '1.75', '0', 0, 'Dea', '2023-02-09 23:40:26'),
(18, '999999', 'GOLD REPAIR', 'Item Gold Repair', '1.00', '1.00', '1.00', '0', 0, 'System', '2023-02-09 23:40:26'),
(19, '000019', 'BELI CINCIN 1', 'Beli cincin dari pelanggan', '700.00', '1.20', '1.50', '0', 0, 'adesulaeman', '2023-02-11 15:00:32'),
(20, '000020', 'BELI CINCIN 2', NULL, '700.00', '1.10', '1.50', '0', 0, 'adesulaeman', '2023-02-11 15:04:52');

-- --------------------------------------------------------

--
-- Table structure for table `data_product_out_os`
--

CREATE TABLE `data_product_out_os` (
  `id` bigint(20) NOT NULL,
  `invoice` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `storename` varchar(200) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_by` varchar(300) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_date` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `data_product_out_os`
--

INSERT INTO `data_product_out_os` (`id`, `invoice`, `storename`, `created_by`, `created_date`) VALUES
(1, 'INVR63e5f7d0538a7', 'Toko Emas Jaya', 'adesulaeman', '2023-02-10 14:55:32');

-- --------------------------------------------------------

--
-- Table structure for table `data_product_out_os_detail`
--

CREATE TABLE `data_product_out_os_detail` (
  `id` bigint(20) NOT NULL,
  `invoice` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `barcode` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `product_name` varchar(300) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `pen` decimal(10,2) DEFAULT NULL,
  `emas_murni` decimal(10,3) DEFAULT NULL,
  `id_category_storage` bigint(20) DEFAULT NULL,
  `qty_out` decimal(15,2) DEFAULT NULL,
  `netto_gram_out` decimal(15,2) DEFAULT NULL,
  `brutto_gram_out` decimal(10,2) DEFAULT NULL,
  `created_by` varchar(300) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_date` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `data_product_out_os_detail`
--

INSERT INTO `data_product_out_os_detail` (`id`, `invoice`, `barcode`, `product_name`, `pen`, `emas_murni`, `id_category_storage`, `qty_out`, `netto_gram_out`, `brutto_gram_out`, `created_by`, `created_date`) VALUES
(1, 'INVR63e5f7d0538a7', '000001', 'CINCIN BAYI BONEKA', '78.00', '1.560', 4, '2.00', '1.00', '1.30', 'adesulaeman', '2023-02-10 14:55:32'),
(2, 'INVR63e5f7d0538a7', '000002', 'CINCIN BAYI', '76.00', '1.140', 4, '3.00', '0.50', '0.50', 'adesulaeman', '2023-02-10 14:55:32');

-- --------------------------------------------------------

--
-- Table structure for table `data_product_out_resaller`
--

CREATE TABLE `data_product_out_resaller` (
  `id` bigint(20) NOT NULL,
  `invoice` varchar(300) COLLATE utf8mb4_unicode_ci NOT NULL,
  `id_resaller` bigint(20) NOT NULL,
  `status_payment` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT 'installment',
  `created_by` varchar(300) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_date` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `data_product_out_resaller`
--

INSERT INTO `data_product_out_resaller` (`id`, `invoice`, `id_resaller`, `status_payment`, `created_by`, `created_date`) VALUES
(1, 'INVR63e74a36688a0', 2, 'installment', 'adesulaeman', '2023-02-11 14:57:12'),
(2, 'INVR63e9d35ee43f7', 3, 'installment', 'Dea', '2023-02-13 13:07:37');

-- --------------------------------------------------------

--
-- Table structure for table `data_product_out_resaller_detail`
--

CREATE TABLE `data_product_out_resaller_detail` (
  `id` bigint(20) NOT NULL,
  `invoice` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `barcode` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `product_name` varchar(300) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `pen` decimal(10,2) DEFAULT NULL,
  `emas_murni` decimal(10,3) DEFAULT NULL,
  `id_category_storage` bigint(20) DEFAULT NULL,
  `qty_out` decimal(15,2) DEFAULT NULL,
  `netto_gram_out` decimal(15,2) DEFAULT NULL,
  `brutto_gram_out` decimal(10,2) DEFAULT NULL,
  `created_by` varchar(300) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_date` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `data_product_out_resaller_detail`
--

INSERT INTO `data_product_out_resaller_detail` (`id`, `invoice`, `barcode`, `product_name`, `pen`, `emas_murni`, `id_category_storage`, `qty_out`, `netto_gram_out`, `brutto_gram_out`, `created_by`, `created_date`) VALUES
(1, 'INVR63e74a36688a0', '000001', 'CINCIN BAYI BONEKA', '91.00', '9.100', 4, '10.00', '1.00', '1.30', 'adesulaeman', '2023-02-11 14:57:12'),
(2, 'INVR63e9d35ee43f7', '000010', 'ANTING WAYANG', '81.00', '2.592', 6, '1.00', '3.20', '0.00', 'Dea', '2023-02-13 13:07:37'),
(3, 'INVR63e9d35ee43f7', '000012', 'GELANG HOLO', '79.00', '2.370', 7, '1.00', '3.00', '0.00', 'Dea', '2023-02-13 13:07:37');

-- --------------------------------------------------------

--
-- Table structure for table `data_receive`
--

CREATE TABLE `data_receive` (
  `id` bigint(20) NOT NULL,
  `type_receive` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `no_invoice` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `no_order` bigint(20) DEFAULT NULL,
  `status_receive` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'open',
  `receive_date` date NOT NULL,
  `created_by` varchar(300) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `data_receive`
--

INSERT INTO `data_receive` (`id`, `type_receive`, `no_invoice`, `no_order`, `status_receive`, `receive_date`, `created_by`, `created_date`) VALUES
(1, 'Buy Gold', 'INV-5BZ132GBKH5', NULL, 'received', '2023-02-11', 'adesulaeman', '2023-02-11 14:58:37'),
(2, 'Buy Gold', 'INV-EO9EDRU8H2W', NULL, 'received', '2023-02-11', 'adesulaeman', '2023-02-11 15:05:13'),
(5, 'Buy Gold', 'INV-Z8DHFYE3DHR', NULL, 'received', '2023-02-12', 'adesulaeman', '2023-02-14 20:16:32');

-- --------------------------------------------------------

--
-- Table structure for table `data_reference`
--

CREATE TABLE `data_reference` (
  `id` bigint(20) NOT NULL,
  `description` varchar(200) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `filter` varchar(200) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `data_reference`
--

INSERT INTO `data_reference` (`id`, `description`, `filter`) VALUES
(1, 'Cash', 'method'),
(2, 'Debit', 'method'),
(3, 'Transfer', 'method'),
(4, 'Mandiri', 'bank'),
(5, 'BTN', 'bank'),
(6, 'BRI', 'bank'),
(7, 'BCA', 'bank'),
(8, 'Other', 'receive'),
(9, 'Buy Gold', 'receive'),
(10, 'Emas Murni', 'method');

-- --------------------------------------------------------

--
-- Table structure for table `data_register_barcode`
--

CREATE TABLE `data_register_barcode` (
  `id_product` bigint(20) NOT NULL,
  `barcode` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `data_register_barcode`
--

INSERT INTO `data_register_barcode` (`id_product`, `barcode`) VALUES
(8, '00008');

-- --------------------------------------------------------

--
-- Table structure for table `data_resaller`
--

CREATE TABLE `data_resaller` (
  `id` bigint(20) NOT NULL,
  `nama` varchar(400) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `no_handphone` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `alamat` varchar(1000) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `no_ktp` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_delete` int(11) NOT NULL DEFAULT 0,
  `created_by` varchar(300) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_date` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `data_resaller`
--

INSERT INTO `data_resaller` (`id`, `nama`, `no_handphone`, `alamat`, `no_ktp`, `is_delete`, `created_by`, `created_date`) VALUES
(1, 'Agung ', '08127632718', 'JL Setia Budi Blok C4', '7829136782132', 1, NULL, '2023-02-07 22:42:52'),
(2, 'Septi Yunita Sari', '0812878267', 'Jl Nirwana Indah', '878217832718932', 1, NULL, '2023-02-07 22:42:58'),
(3, 'kendari', '085399900046', 'lapai', '731300041205020005', 0, NULL, '2023-02-13 13:06:08');

-- --------------------------------------------------------

--
-- Table structure for table `data_resaller_payment`
--

CREATE TABLE `data_resaller_payment` (
  `id` bigint(20) NOT NULL,
  `invoice` varchar(200) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `payment_date` date DEFAULT NULL,
  `nominal` decimal(15,3) DEFAULT NULL,
  `method` varchar(200) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `bank` varchar(200) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `gram_gold_price` decimal(15,2) DEFAULT NULL,
  `created_by` varchar(300) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_date` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `data_resaller_payment`
--

INSERT INTO `data_resaller_payment` (`id`, `invoice`, `payment_date`, `nominal`, `method`, `bank`, `gram_gold_price`, `created_by`, `created_date`) VALUES
(14, 'INVR63e74a36688a0', '2023-02-11', '5500000.000', 'Cash', NULL, '980000.00', 'adesulaeman', '2023-02-11 14:57:56'),
(15, 'INVR63e74a36688a0', '2023-02-11', '500000.000', 'Debit', 'Mandiri', '980000.00', 'adesulaeman', '2023-02-11 19:58:13'),
(16, 'INVR63e9d35ee43f7', '2023-02-13', '1200000.000', 'Cash', NULL, '900000.00', 'Dea', '2023-02-13 13:10:42'),
(18, 'INVR63e9d35ee43f7', '2023-02-13', '2.987', 'Emas Murni', NULL, '980000.00', 'adesulaeman', '2023-02-13 17:31:58');

-- --------------------------------------------------------

--
-- Table structure for table `data_sales`
--

CREATE TABLE `data_sales` (
  `id` bigint(20) NOT NULL,
  `no_struk` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payment_method` varchar(300) COLLATE utf8mb4_unicode_ci NOT NULL,
  `change_payment` decimal(15,2) NOT NULL,
  `payment_cash` decimal(15,2) NOT NULL,
  `payment_trasnfer` decimal(15,2) DEFAULT NULL,
  `payment_debit` decimal(15,2) DEFAULT NULL,
  `payment_credit` decimal(15,2) DEFAULT NULL,
  `payment_dp` decimal(15,2) DEFAULT NULL,
  `total_amount` decimal(15,2) NOT NULL,
  `card_no_debit` varchar(80) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `card_no_credit` varchar(80) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `no_rek_transfer` varchar(80) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sales_date` timestamp NULL DEFAULT NULL,
  `created_by` varchar(500) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `data_sales`
--

INSERT INTO `data_sales` (`id`, `no_struk`, `payment_method`, `change_payment`, `payment_cash`, `payment_trasnfer`, `payment_debit`, `payment_credit`, `payment_dp`, `total_amount`, `card_no_debit`, `card_no_credit`, `no_rek_transfer`, `sales_date`, `created_by`, `created_date`) VALUES
(1, 'stk63e74962a0b7c', 'Cash', '20000.00', '9620000.00', '0.00', '0.00', '0.00', '0.00', '9600000.00', '', '', '', '2023-02-11 14:54:02', 'adesulaeman', '2023-02-11 14:54:02'),
(2, 'stk63e749a4abbce', 'Debit', '0.00', '0.00', '0.00', '920000.00', '0.00', '0.00', '920000.00', 'BCA12312', '', '', '2023-02-11 14:54:40', 'adesulaeman', '2023-02-11 14:54:40'),
(3, 'stk63e749cad1657', 'Cash', '20000.00', '550000.00', '0.00', '0.00', '0.00', '0.00', '530000.00', '', '', '', '2023-02-11 14:55:17', 'adesulaeman', '2023-02-11 14:55:17'),
(4, 'stk63e749eb31561', 'Cash', '30000.00', '150000.00', '0.00', '0.00', '0.00', '0.00', '120000.00', '', '', '', '2023-02-11 14:55:38', 'adesulaeman', '2023-02-11 14:55:38'),
(5, 'stk63e74a030d4c4', 'Cash', '10000.00', '220000.00', '0.00', '0.00', '0.00', '0.00', '210000.00', '', '', '', '2023-02-11 14:56:06', 'adesulaeman', '2023-02-11 14:56:06'),
(7, 'stk63e79077abf71', 'Transfer and Debit and Credit and DP', '0.00', '0.00', '200000.00', '200000.00', '200000.00', '200000.00', '800000.00', 'DSA2132', 'DSA2132', 'DSA2132', '2023-02-11 19:56:42', 'adesulaeman', '2023-02-11 19:56:42');

-- --------------------------------------------------------

--
-- Table structure for table `data_sales_detail`
--

CREATE TABLE `data_sales_detail` (
  `id` bigint(20) NOT NULL,
  `no_struk` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `barcode` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `product_name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `qty` decimal(10,0) NOT NULL,
  `netto_gram` decimal(10,2) NOT NULL,
  `brutto_gram` decimal(10,2) DEFAULT NULL,
  `price` decimal(10,0) NOT NULL,
  `sales_date` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `id_category_storage` bigint(20) NOT NULL,
  `created_by` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `data_sales_detail`
--

INSERT INTO `data_sales_detail` (`id`, `no_struk`, `barcode`, `product_name`, `qty`, `netto_gram`, `brutto_gram`, `price`, `sales_date`, `id_category_storage`, `created_by`, `created_date`) VALUES
(1, 'stk63e74962a0b7c', '000002', 'CINCIN BAYI', '2', '0.50', '0.50', '3000000', '2023-02-11 14:54:02', 4, 'adesulaeman', '2023-02-11 14:54:02'),
(2, 'stk63e74962a0b7c', '000001', 'CINCIN BAYI BONEKA', '2', '1.00', '1.30', '1800000', '2023-02-11 14:54:02', 4, 'adesulaeman', '2023-02-11 14:54:02'),
(3, 'stk63e749a4abbce', '000004', 'CINCIN ', '1', '1.20', '0.00', '920000', '2023-02-11 14:54:40', 4, 'adesulaeman', '2023-02-11 14:54:40'),
(4, 'stk63e749cad1657', '000002', 'CINCIN BAYI', '1', '0.50', '0.50', '530000', '2023-02-11 14:55:17', 4, 'adesulaeman', '2023-02-11 14:55:17'),
(5, 'stk63e749eb31561', '999999', 'GOLD REPAIR', '1', '1.00', '1.00', '120000', '2023-02-11 14:55:38', 11, 'adesulaeman', '2023-02-11 14:55:38'),
(6, 'stk63e74a030d4c4', '999999', 'GOLD REPAIR', '1', '1.00', '1.00', '210000', '2023-02-11 14:56:06', 11, 'adesulaeman', '2023-02-11 14:56:06'),
(8, 'stk63e79077abf71', '000001', 'CINCIN BAYI BONEKA', '1', '1.00', '1.30', '800000', '2023-02-11 19:56:42', 4, 'adesulaeman', '2023-02-11 19:56:42');

-- --------------------------------------------------------

--
-- Table structure for table `data_stock_opname`
--

CREATE TABLE `data_stock_opname` (
  `id` bigint(20) NOT NULL,
  `stock_opname_info` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `stock_opname_time` date DEFAULT NULL,
  `status_stock_opname` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'open',
  `created_by` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `data_stock_opname`
--

INSERT INTO `data_stock_opname` (`id`, `stock_opname_info`, `stock_opname_time`, `status_stock_opname`, `created_by`, `created_date`) VALUES
(1, 'testing', '2023-02-09', 'closed', 'Dea', '2023-02-09 10:08:13'),
(2, 'debug', '2023-02-09', 'closed', 'adesulaeman', '2023-02-09 20:31:49'),
(3, 'debug 2', '2023-02-24', 'canceled', 'adesulaeman', '2023-02-09 21:14:44'),
(4, 'add stock gold repair', '2023-02-11', 'closed', 'adesulaeman', '2023-02-11 14:39:59'),
(5, 'talang 1', '2023-02-12', 'canceled', 'adesulaeman', '2023-02-14 20:27:08');

-- --------------------------------------------------------

--
-- Table structure for table `data_stock_opname_detail`
--

CREATE TABLE `data_stock_opname_detail` (
  `id` bigint(20) NOT NULL,
  `id_stock_opname` bigint(20) NOT NULL,
  `id_category_storage` bigint(20) DEFAULT NULL,
  `barcode` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `product_name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `qty_phisycal` decimal(10,0) NOT NULL,
  `netto_gram_physycal` decimal(10,2) DEFAULT NULL,
  `brutto_gram_physycal` decimal(10,2) DEFAULT NULL,
  `qty_adjusment` decimal(10,0) DEFAULT NULL,
  `netto_gram_adjusment` decimal(10,2) DEFAULT NULL,
  `brutto_gram_adjusment` decimal(10,2) DEFAULT NULL,
  `created_by` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_date` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `data_stock_opname_detail`
--

INSERT INTO `data_stock_opname_detail` (`id`, `id_stock_opname`, `id_category_storage`, `barcode`, `product_name`, `qty_phisycal`, `netto_gram_physycal`, `brutto_gram_physycal`, `qty_adjusment`, `netto_gram_adjusment`, `brutto_gram_adjusment`, `created_by`, `created_date`) VALUES
(1, 1, 4, '000001', 'CINCIN BAYI BONEKA', '1', '1.00', NULL, NULL, NULL, NULL, 'Dea', '2023-02-09 10:08:25'),
(2, 1, 4, '000002', 'CINCIN BAYI', '1', '0.50', NULL, NULL, NULL, NULL, 'Dea', '2023-02-09 10:08:34'),
(3, 1, 4, '000003', 'CINCIN BAYI', '1', '1.00', NULL, NULL, NULL, NULL, 'Dea', '2023-02-09 10:08:44'),
(4, 1, 4, '000004', 'CINCIN ', '1', '1.20', NULL, NULL, NULL, NULL, 'Dea', '2023-02-09 10:09:31'),
(5, 1, 5, '000005', 'CINCIN FUSION', '1', '15.00', NULL, NULL, NULL, NULL, 'Dea', '2023-02-09 10:09:45'),
(6, 1, 5, '000006', 'CINCIN KR', '1', '7.00', NULL, NULL, NULL, NULL, 'Dea', '2023-02-09 10:10:00'),
(7, 1, 5, '000007', 'CINCIN KR', '1', '12.00', NULL, NULL, NULL, NULL, 'Dea', '2023-02-09 10:10:10'),
(8, 1, 6, '000008', 'ANTING', '1', '1.00', NULL, NULL, NULL, NULL, 'Dea', '2023-02-09 10:10:51'),
(9, 1, 6, '000009', 'ANTING', '1', '1.00', NULL, NULL, NULL, NULL, 'Dea', '2023-02-09 10:11:02'),
(10, 1, 6, '000010', 'ANTING WAYANG', '1', '3.20', NULL, NULL, NULL, NULL, 'Dea', '2023-02-09 10:11:39'),
(11, 1, 7, '000011', 'GELANG VENCY ', '1', '5.00', NULL, NULL, NULL, NULL, 'Dea', '2023-02-09 10:12:18'),
(12, 1, 7, '000012', 'GELANG HOLO', '1', '3.00', NULL, NULL, NULL, NULL, 'Dea', '2023-02-09 10:12:28'),
(13, 2, 4, '000001', 'CINCIN BAYI BONEKA', '12', NULL, NULL, NULL, NULL, NULL, 'adesulaeman', '2023-02-09 20:46:42'),
(16, 2, 4, '000002', 'CINCIN BAYI', '23', NULL, NULL, NULL, NULL, NULL, 'adesulaeman', '2023-02-09 20:49:16'),
(17, 3, 5, '000001', 'CINCIN BAYI BONEKA', '23', NULL, NULL, NULL, NULL, NULL, 'adesulaeman', '2023-02-09 21:14:51'),
(18, 3, 5, '000002', 'CINCIN BAYI', '12', NULL, NULL, NULL, NULL, NULL, 'adesulaeman', '2023-02-09 21:14:59'),
(19, 4, 11, '999999', 'GOLD REPAIR', '1000', NULL, NULL, NULL, NULL, NULL, 'adesulaeman', '2023-02-11 14:40:41');

-- --------------------------------------------------------

--
-- Table structure for table `data_stock_opname_detail_report`
--

CREATE TABLE `data_stock_opname_detail_report` (
  `id` bigint(20) NOT NULL,
  `id_stock_opname` bigint(20) NOT NULL,
  `id_category_storage` bigint(20) DEFAULT NULL,
  `barcode` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `product_name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `qty_phisycal` decimal(10,0) NOT NULL,
  `netto_gram_physycal` decimal(10,2) DEFAULT NULL,
  `brutto_gram_physycal` decimal(10,2) DEFAULT NULL,
  `qty_stock` decimal(10,0) NOT NULL,
  `netto_gram_stock` decimal(10,2) DEFAULT NULL,
  `brutto_gram_stock` decimal(10,2) DEFAULT NULL,
  `qty_adjusment` decimal(10,0) DEFAULT NULL,
  `netto_gram_adjusment` decimal(10,2) DEFAULT NULL,
  `brutto_gram_adjusment` decimal(10,2) DEFAULT NULL,
  `qty_diff` decimal(10,0) DEFAULT NULL,
  `netto_gram_diff` decimal(10,2) DEFAULT NULL,
  `brutto_gram_diff` decimal(10,2) DEFAULT NULL,
  `created_by` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_date` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `data_stock_opname_detail_report`
--

INSERT INTO `data_stock_opname_detail_report` (`id`, `id_stock_opname`, `id_category_storage`, `barcode`, `product_name`, `qty_phisycal`, `netto_gram_physycal`, `brutto_gram_physycal`, `qty_stock`, `netto_gram_stock`, `brutto_gram_stock`, `qty_adjusment`, `netto_gram_adjusment`, `brutto_gram_adjusment`, `qty_diff`, `netto_gram_diff`, `brutto_gram_diff`, `created_by`, `created_date`) VALUES
(1, 1, 4, '000001', 'CINCIN BAYI BONEKA', '1', '1.00', NULL, '0', '0.00', NULL, '0', '0.00', NULL, '1', '1.00', NULL, 'Dea', '2023-02-09 10:08:25'),
(2, 1, 4, '000002', 'CINCIN BAYI', '1', '0.50', NULL, '0', '0.00', NULL, '0', '0.00', NULL, '1', '0.50', NULL, 'Dea', '2023-02-09 10:08:34'),
(3, 1, 4, '000003', 'CINCIN BAYI', '1', '1.00', NULL, '0', '0.00', NULL, '0', '0.00', NULL, '1', '1.00', NULL, 'Dea', '2023-02-09 10:08:44'),
(4, 1, 4, '000004', 'CINCIN ', '1', '1.20', NULL, '0', '0.00', NULL, '0', '0.00', NULL, '1', '1.20', NULL, 'Dea', '2023-02-09 10:09:31'),
(5, 1, 5, '000005', 'CINCIN FUSION', '1', '15.00', NULL, '0', '0.00', NULL, '0', '0.00', NULL, '1', '15.00', NULL, 'Dea', '2023-02-09 10:09:45'),
(6, 1, 5, '000006', 'CINCIN KR', '1', '7.00', NULL, '0', '0.00', NULL, '0', '0.00', NULL, '1', '7.00', NULL, 'Dea', '2023-02-09 10:10:00'),
(7, 1, 5, '000007', 'CINCIN KR', '1', '12.00', NULL, '0', '0.00', NULL, '0', '0.00', NULL, '1', '12.00', NULL, 'Dea', '2023-02-09 10:10:10'),
(8, 1, 6, '000008', 'ANTING', '1', '1.00', NULL, '0', '0.00', NULL, '0', '0.00', NULL, '1', '1.00', NULL, 'Dea', '2023-02-09 10:10:51'),
(9, 1, 6, '000009', 'ANTING', '1', '1.00', NULL, '0', '0.00', NULL, '0', '0.00', NULL, '1', '1.00', NULL, 'Dea', '2023-02-09 10:11:02'),
(10, 1, 6, '000010', 'ANTING WAYANG', '1', '3.20', NULL, '0', '0.00', NULL, '0', '0.00', NULL, '1', '3.20', NULL, 'Dea', '2023-02-09 10:11:39'),
(11, 1, 7, '000011', 'GELANG VENCY ', '1', '5.00', NULL, '0', '0.00', NULL, '0', '0.00', NULL, '1', '5.00', NULL, 'Dea', '2023-02-09 10:12:18'),
(12, 1, 7, '000012', 'GELANG HOLO', '1', '3.00', NULL, '0', '0.00', NULL, '0', '0.00', NULL, '1', '3.00', NULL, 'Dea', '2023-02-09 10:12:28'),
(13, 2, 4, '000001', 'CINCIN BAYI BONEKA', '12', '12.00', '3.60', '0', '0.00', '0.00', '0', '0.00', '0.00', '12', '12.00', '3.60', 'adesulaeman', '2023-02-09 20:46:42'),
(16, 2, 4, '000002', 'CINCIN BAYI', '23', '11.50', '11.50', '1', '0.50', '0.00', '0', '0.00', '0.00', '22', '11.00', '11.50', 'adesulaeman', '2023-02-09 20:49:16'),
(13, 2, 4, '000001', 'CINCIN BAYI BONEKA', '12', '12.00', '3.60', '0', '0.00', '0.00', '0', '0.00', '0.00', '12', '12.00', '3.60', 'adesulaeman', '2023-02-09 20:46:42'),
(16, 2, 4, '000002', 'CINCIN BAYI', '23', '11.50', '11.50', '1', '0.50', '0.00', '0', '0.00', '0.00', '22', '11.00', '11.50', 'adesulaeman', '2023-02-09 20:49:16'),
(19, 4, 11, '999999', 'GOLD REPAIR', '1000', '1000.00', '1000.00', '0', '0.00', '0.00', '0', '0.00', '0.00', '1000', '1000.00', '1000.00', 'adesulaeman', '2023-02-11 14:40:41');

-- --------------------------------------------------------

--
-- Table structure for table `data_stock_product`
--

CREATE TABLE `data_stock_product` (
  `id` bigint(20) NOT NULL,
  `barcode` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `id_category_storage` bigint(20) NOT NULL,
  `qty_stock` decimal(10,0) NOT NULL,
  `netto_gram` decimal(15,2) NOT NULL,
  `brutto_gram` decimal(10,2) DEFAULT NULL,
  `stock_date` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `created_by` varchar(150) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `data_stock_product`
--

INSERT INTO `data_stock_product` (`id`, `barcode`, `id_category_storage`, `qty_stock`, `netto_gram`, `brutto_gram`, `stock_date`, `created_by`, `created_date`) VALUES
(1, '000001', 4, '-18', '-18.00', '-32.40', '2023-02-11 19:56:42', 'SO', '2023-02-09 21:07:55'),
(2, '000002', 4, '34', '17.00', '17.00', '2023-02-11 14:55:17', 'Receive', '2023-02-09 21:07:55'),
(3, '000003', 4, '1', '1.00', NULL, '2023-02-09 10:12:35', 'SO', '2023-02-09 10:12:35'),
(4, '000004', 4, '-1', '-1.20', NULL, '2023-02-11 14:54:40', 'SO', '2023-02-09 10:12:35'),
(5, '000005', 5, '1', '15.00', NULL, '2023-02-09 10:12:35', 'SO', '2023-02-09 10:12:35'),
(6, '000006', 5, '1', '7.00', NULL, '2023-02-09 10:12:35', 'SO', '2023-02-09 10:12:35'),
(7, '000007', 5, '1', '12.00', NULL, '2023-02-09 10:12:35', 'SO', '2023-02-09 10:12:35'),
(8, '000008', 6, '1', '1.00', NULL, '2023-02-09 10:12:35', 'SO', '2023-02-09 10:12:35'),
(9, '000009', 6, '1', '1.00', NULL, '2023-02-09 10:12:35', 'SO', '2023-02-09 10:12:35'),
(10, '000010', 6, '0', '0.00', NULL, '2023-02-13 13:07:37', 'SO', '2023-02-09 10:12:35'),
(11, '000011', 7, '1', '5.00', NULL, '2023-02-09 10:12:35', 'SO', '2023-02-09 10:12:35'),
(12, '000012', 7, '0', '0.00', NULL, '2023-02-13 13:07:37', 'SO', '2023-02-09 10:12:35'),
(16, '000001', 5, '12', '12.00', '3.60', '2023-02-09 21:27:21', 'Receive', '2023-02-09 14:27:21'),
(17, '999999', 11, '997', '997.00', '997.00', '2023-02-11 14:56:06', 'SO', '2023-02-11 14:40:48'),
(18, '000019', 5, '1', '1.20', '1.50', '2023-02-11 15:01:48', 'Receive', '2023-02-11 08:01:48'),
(19, '000020', 4, '1', '1.10', '1.50', '2023-02-11 15:05:39', 'Receive', '2023-02-11 08:05:39'),
(20, '000005', 6, '1', '15.00', '0.00', '2023-02-14 20:17:28', 'Receive', '2023-02-14 13:17:28');

-- --------------------------------------------------------

--
-- Table structure for table `data_stock_weight`
--

CREATE TABLE `data_stock_weight` (
  `id` bigint(20) NOT NULL,
  `stock_info` varchar(500) COLLATE utf8mb4_unicode_ci NOT NULL,
  `stock_date` date NOT NULL,
  `status` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT 'open',
  `created_date` timestamp NULL DEFAULT NULL,
  `created_by` varchar(200) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `data_stock_weight`
--

INSERT INTO `data_stock_weight` (`id`, `stock_info`, `stock_date`, `status`, `created_date`, `created_by`) VALUES
(1, 'pagi', '2023-02-09', 'closed', '2023-02-09 10:16:50', 'Dea'),
(2, 'stock ', '2023-02-09', 'closed', '2023-02-09 22:12:58', 'adesulaeman');

-- --------------------------------------------------------

--
-- Table structure for table `data_stock_weight_detail`
--

CREATE TABLE `data_stock_weight_detail` (
  `id` bigint(20) NOT NULL,
  `id_stock_weight` bigint(20) DEFAULT NULL,
  `category_storage` varchar(300) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `id_category_storage` bigint(20) DEFAULT NULL,
  `opening_weight` decimal(10,2) DEFAULT NULL,
  `closing_weight` decimal(10,2) DEFAULT NULL,
  `created_by` varchar(300) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_date` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `data_stock_weight_detail`
--

INSERT INTO `data_stock_weight_detail` (`id`, `id_stock_weight`, `category_storage`, `id_category_storage`, `opening_weight`, `closing_weight`, `created_by`, `created_date`) VALUES
(1, 1, NULL, 4, '103.70', '102.70', NULL, '2023-02-09 10:17:04'),
(2, 1, NULL, 5, '134.00', '134.00', NULL, '2023-02-09 10:17:30'),
(3, 1, NULL, 6, '105.20', '105.20', NULL, '2023-02-09 10:17:46'),
(4, 1, NULL, 7, '258.00', '258.00', NULL, '2023-02-09 10:18:06'),
(5, 2, NULL, 4, '1232.00', '108.20', NULL, '2023-02-09 22:13:07');

-- --------------------------------------------------------

--
-- Table structure for table `data_stock_weight_detail_hist`
--

CREATE TABLE `data_stock_weight_detail_hist` (
  `id` bigint(20) NOT NULL,
  `id_stock_weight` bigint(20) DEFAULT NULL,
  `id_category_storage` bigint(20) DEFAULT NULL,
  `storage_name` varchar(300) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `opening_gram` decimal(10,2) DEFAULT NULL,
  `closing_gram` decimal(10,2) DEFAULT NULL,
  `SS_gram_storage_update` decimal(10,2) DEFAULT NULL,
  `weight_closing_vs_SS_update` varchar(500) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_by` varchar(300) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_date` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `data_stock_weight_detail_hist`
--

INSERT INTO `data_stock_weight_detail_hist` (`id`, `id_stock_weight`, `id_category_storage`, `storage_name`, `opening_gram`, `closing_gram`, `SS_gram_storage_update`, `weight_closing_vs_SS_update`, `created_by`, `created_date`) VALUES
(1, 1, 4, 'T1 / CINCIN', '103.70', NULL, '103.70', '<span style=\"font-size:20px;color:red\">-103.70</span>', NULL, '2023-02-09 10:17:04'),
(2, 1, 5, 'T2 / CINCIN', '134.00', NULL, '134.00', '<span style=\"font-size:20px;color:red\">-134.00</span>', NULL, '2023-02-09 10:17:30'),
(3, 1, 6, 'T3 / ANTING', '105.20', NULL, '105.20', '<span style=\"font-size:20px;color:red\">-105.20</span>', NULL, '2023-02-09 10:17:46'),
(4, 1, 7, 'T4 / GELANG RANTAI', '258.00', NULL, '258.00', '<span style=\"font-size:20px;color:red\">-258.00</span>', NULL, '2023-02-09 10:18:06'),
(1, 1, 4, 'T1 / CINCIN', '103.70', '102.70', '102.70', '<span style=\"font-size:20px;color:green\">+ 0.00</span>', NULL, '2023-02-09 10:17:04'),
(2, 1, 5, 'T2 / CINCIN', '134.00', '134.00', '134.00', '<span style=\"font-size:20px;color:green\">+ 0.00</span>', NULL, '2023-02-09 10:17:30'),
(3, 1, 6, 'T3 / ANTING', '105.20', '105.20', '105.20', '<span style=\"font-size:20px;color:green\">+ 0.00</span>', NULL, '2023-02-09 10:17:46'),
(4, 1, 7, 'T4 / GELANG RANTAI', '258.00', '258.00', '258.00', '<span style=\"font-size:20px;color:green\">+ 0.00</span>', NULL, '2023-02-09 10:18:06'),
(5, 2, 4, 'T1 / CINCIN', '1232.00', '108.20', '108.20', '<span style=\"font-size:20px;color:green\">+ 0.00</span>', NULL, '2023-02-09 22:13:07');

-- --------------------------------------------------------

--
-- Table structure for table `data_supplier`
--

CREATE TABLE `data_supplier` (
  `id` bigint(20) NOT NULL,
  `nama_pt` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `no_telp` varchar(13) COLLATE utf8mb4_unicode_ci NOT NULL,
  `no_hp` varchar(13) COLLATE utf8mb4_unicode_ci NOT NULL,
  `address` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_by` bigint(20) NOT NULL,
  `created_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Stand-in structure for view `data_vw_cashflow`
-- (See below for the actual view)
--
CREATE TABLE `data_vw_cashflow` (
`id` date
,`cashflow_date` varchar(72)
,`status` varchar(93)
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `data_vw_cost_daily`
-- (See below for the actual view)
--
CREATE TABLE `data_vw_cost_daily` (
`id` bigint(20)
,`action` varchar(215)
,`cost_date` date
,`opening_cash` varchar(19)
,`status` varchar(264)
,`created_by` varchar(200)
,`created_date` timestamp
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `data_vw_cost_daily_detail`
-- (See below for the actual view)
--
CREATE TABLE `data_vw_cost_daily_detail` (
`id` bigint(20)
,`id_cost_daily` bigint(20)
,`nominal` varchar(19)
,`information` varchar(1000)
,`created_by` varchar(200)
,`created_date` timestamp
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `data_vw_detail_sales`
-- (See below for the actual view)
--
CREATE TABLE `data_vw_detail_sales` (
`no_struk` varchar(50)
,`barcode` varchar(30)
,`product_name` varchar(100)
,`qty` decimal(10,0)
,`netto_gram` decimal(10,2)
,`brutto_gram` decimal(10,2)
,`price` varchar(17)
,`sales_date` timestamp
,`storage_name` varchar(30)
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `data_vw_os`
-- (See below for the actual view)
--
CREATE TABLE `data_vw_os` (
`id` bigint(20)
,`invoice` varchar(231)
,`storename` varchar(200)
,`created_by` varchar(300)
,`created_date` timestamp
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `data_vw_os_detail`
-- (See below for the actual view)
--
CREATE TABLE `data_vw_os_detail` (
`id` bigint(20)
,`invoice` varchar(100)
,`storage_name` varchar(30)
,`barcode` varchar(30)
,`product_name` varchar(300)
,`pen` decimal(10,2)
,`gold_rate` varchar(14)
,`qty_out` decimal(15,2)
,`netto_gram_out` varchar(21)
,`brutto_gram_out` varchar(14)
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `data_vw_product`
-- (See below for the actual view)
--
CREATE TABLE `data_vw_product` (
`id` bigint(20)
,`barcode` varchar(30)
,`product_name` varchar(50)
,`description` varchar(200)
,`kadar_product` varchar(14)
,`netto_gram` varchar(14)
,`brutto_gram` varchar(14)
,`price` varchar(17)
,`created_by` varchar(500)
,`created_date` timestamp
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `data_vw_product_out_reseller`
-- (See below for the actual view)
--
CREATE TABLE `data_vw_product_out_reseller` (
`id` bigint(20)
,`invoice` text
,`id_invoice` varchar(300)
,`id_resaller` bigint(20)
,`reseller_name` varchar(400)
,`status_payment` varchar(114)
,`created_by` varchar(300)
,`created_date` timestamp
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `data_vw_reseller`
-- (See below for the actual view)
--
CREATE TABLE `data_vw_reseller` (
`id` bigint(20)
,`nama` varchar(400)
,`no_handphone` varchar(20)
,`alamat` varchar(1000)
,`no_ktp` varchar(20)
,`status` varchar(70)
,`created_by` varchar(300)
,`created_date` timestamp
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `data_vw_sales`
-- (See below for the actual view)
--
CREATE TABLE `data_vw_sales` (
`id` bigint(20)
,`id_struk` varchar(50)
,`no_struk` varchar(304)
,`payment_method` varchar(300)
,`change_payment` varchar(21)
,`payment_cash` varchar(21)
,`payment_trasnfer` varchar(21)
,`payment_debit` varchar(21)
,`payment_credit` varchar(21)
,`payment_dp` varchar(21)
,`total_amount` varchar(21)
,`card_no_debit` varchar(80)
,`card_no_credit` varchar(80)
,`no_rek_transfer` varchar(80)
,`transaction_date` timestamp
,`cashier` varchar(500)
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `data_vw_stock`
-- (See below for the actual view)
--
CREATE TABLE `data_vw_stock` (
`id` bigint(20)
,`id_category_storage` bigint(20)
,`storage_name` varchar(30)
,`barcode` varchar(30)
,`product` varchar(88)
,`description` varchar(200)
,`sell_price` varchar(17)
,`qty_stock` varchar(17)
,`netto_gram` decimal(15,2)
,`brutto_gram` decimal(10,2)
,`stock_date` timestamp
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `data_vw_storage`
-- (See below for the actual view)
--
CREATE TABLE `data_vw_storage` (
`id` bigint(20)
,`storage_name` varchar(30)
,`gram` decimal(10,2)
,`created_by` varchar(80)
,`created_date` timestamp
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `select_vw_dp`
-- (See below for the actual view)
--
CREATE TABLE `select_vw_dp` (
`id` varchar(50)
,`text` varchar(50)
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `vw_access`
-- (See below for the actual view)
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
-- Stand-in structure for view `vw_data_detail_receive`
-- (See below for the actual view)
--
CREATE TABLE `vw_data_detail_receive` (
`id` bigint(20)
,`id_receive` bigint(20)
,`storage_name` varchar(500)
,`barcode` varchar(20)
,`product_name` varchar(71)
,`qty_receive` varchar(17)
,`total_gram` varchar(14)
,`total_price` varchar(21)
,`created_by` varchar(300)
,`created_date` timestamp
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `vw_data_detail_stock`
-- (See below for the actual view)
--
CREATE TABLE `vw_data_detail_stock` (
`id` int(1)
,`id_stock_opname` int(1)
,`id_barcode` char(0)
,`id_gram_product` int(1)
,`storage_name` int(1)
,`product` char(0)
,`physical_stock` char(0)
,`system_stock` char(0)
,`adjusment` char(0)
,`difference` char(0)
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `vw_data_installment_reseller`
-- (See below for the actual view)
--
CREATE TABLE `vw_data_installment_reseller` (
`id` bigint(20)
,`invoice` varchar(200)
,`payment_date` date
,`nominal` varchar(21)
,`method` varchar(403)
,`gram_gold_price` varchar(21)
,`kadar` varchar(25)
,`reminder_rate_gold` varchar(486)
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `vw_data_receive`
-- (See below for the actual view)
--
CREATE TABLE `vw_data_receive` (
`id` bigint(20)
,`no_invoice` varchar(375)
,`type_receive` varchar(50)
,`receive_date` date
,`status_receive` varchar(84)
,`created_by` varchar(300)
,`created_date` timestamp
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `vw_data_sales_monthly`
-- (See below for the actual view)
--
CREATE TABLE `vw_data_sales_monthly` (
`id` varchar(6)
,`month` varchar(69)
,`total_sales` varchar(56)
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `vw_data_stock_opname`
-- (See below for the actual view)
--
CREATE TABLE `vw_data_stock_opname` (
`id` bigint(20)
,`stock_opname_info` varchar(396)
,`stock_opname_time` date
,`status` varchar(82)
,`created_by` varchar(100)
,`created_date` timestamp
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `vw_data_stock_weight`
-- (See below for the actual view)
--
CREATE TABLE `vw_data_stock_weight` (
`id` bigint(20)
,`id_stock_weight` bigint(20)
,`id_category_storage` bigint(20)
,`storage_name` varchar(30)
,`opening_gram` decimal(10,2)
,`closing_gram` decimal(10,2)
,`SS_gram_storage_update` decimal(33,2)
,`weight_closing_vs_SS_update` varchar(86)
,`created_by` varchar(300)
,`created_date` timestamp
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `vw_data_sw`
-- (See below for the actual view)
--
CREATE TABLE `vw_data_sw` (
`id` bigint(20)
,`stock_info` text
,`stock_date` date
,`status` varchar(112)
,`created_by` varchar(200)
,`created_date` timestamp
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `vw_group_management`
-- (See below for the actual view)
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
-- Stand-in structure for view `vw_menu_set_role`
-- (See below for the actual view)
--
CREATE TABLE `vw_menu_set_role` (
`id` bigint(20)
,`menu` varchar(75)
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `vw_select_bank`
-- (See below for the actual view)
--
CREATE TABLE `vw_select_bank` (
`id` varchar(200)
,`text` varchar(200)
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `vw_select_group`
-- (See below for the actual view)
--
CREATE TABLE `vw_select_group` (
`id` bigint(100)
,`text` varchar(200)
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `vw_select_method`
-- (See below for the actual view)
--
CREATE TABLE `vw_select_method` (
`id` varchar(200)
,`text` varchar(200)
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `vw_select_product`
-- (See below for the actual view)
--
CREATE TABLE `vw_select_product` (
`id` varchar(30)
,`text` varchar(126)
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `vw_select_product_receive`
-- (See below for the actual view)
--
CREATE TABLE `vw_select_product_receive` (
`id` varchar(30)
,`text` varchar(71)
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `vw_select_reseller`
-- (See below for the actual view)
--
CREATE TABLE `vw_select_reseller` (
`id` bigint(20)
,`text` varchar(400)
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `vw_select_roles_jabatan`
-- (See below for the actual view)
--
CREATE TABLE `vw_select_roles_jabatan` (
`id` varchar(500)
,`text` varchar(500)
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `vw_select_storage`
-- (See below for the actual view)
--
CREATE TABLE `vw_select_storage` (
`id` bigint(20)
,`text` varchar(30)
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `vw_select_typereceive`
-- (See below for the actual view)
--
CREATE TABLE `vw_select_typereceive` (
`id` varchar(200)
,`text` varchar(200)
);

-- --------------------------------------------------------

--
-- Structure for view `core_vw_rolemenus`
--
DROP TABLE IF EXISTS `core_vw_rolemenus`;

CREATE ALGORITHM=UNDEFINED DEFINER=`u965363723_superuser`@`%` SQL SECURITY DEFINER VIEW `core_vw_rolemenus`  AS SELECT concat(concat(`core_menus`.`idmodule`,'-'),`core_menus`.`id`) AS `idmenus`, `core_menus`.`idmodule` AS `idmodule`, `core_menus`.`menu` AS `menu`, `core_menus`.`icon` AS `icon`, `core_menus`.`withframe` AS `withframe`, CASE WHEN `core_menus`.`parent` = 0 THEN `core_menus`.`parent` ELSE concat(concat(`core_menus`.`idmodule`,'-'),`core_menus`.`parent`) END AS `parent`, 0 AS `child`, `core_menus`.`links` AS `links`, `rlm`.`idmenu` AS `idmenu`, `rlm`.`iduser` AS `iduser`, `core_menus`.`sub_page` AS `sub_page` FROM (`core_menus` left join `core_rolemenu` `rlm` on(`core_menus`.`id` = `rlm`.`idmenu`)) ;

-- --------------------------------------------------------

--
-- Structure for view `data_vw_cashflow`
--
DROP TABLE IF EXISTS `data_vw_cashflow`;

CREATE ALGORITHM=UNDEFINED DEFINER=`u965363723_superuser`@`%` SQL SECURITY DEFINER VIEW `data_vw_cashflow`  AS SELECT `data_cost_daily`.`cost_date` AS `id`, date_format(`data_cost_daily`.`cost_date`,'%d %M %Y') AS `cashflow_date`, CASE WHEN `data_cost_daily`.`status` = 'open' THEN concat('<span class="label bg-orange"><i class="fa fa-fw fa-retweet"></i> Cash flow still move</span>') ELSE '<span class="label bg-green"><i class="fa fa-check"></i> Cash flow closed</span>' END AS `status` FROM `data_cost_daily` ;

-- --------------------------------------------------------

--
-- Structure for view `data_vw_cost_daily`
--
DROP TABLE IF EXISTS `data_vw_cost_daily`;

CREATE ALGORITHM=UNDEFINED DEFINER=`u965363723_superuser`@`%` SQL SECURITY DEFINER VIEW `data_vw_cost_daily`  AS SELECT `data_cost_daily`.`id` AS `id`, concat('<a href="#" class="btn btn-primary btn-xs" onclick="HtmlLoad(\'./lib/base/form_advance_sub_cost.php?s=form_advance_cost.php&f=30&f_parent=29&sub=',`data_cost_daily`.`id`,'&sub_desc=Cost Date : ',`data_cost_daily`.`cost_date`,'\',\'\');">Details</a>') AS `action`, `data_cost_daily`.`cost_date` AS `cost_date`, format(`data_cost_daily`.`opening_cash`,0) AS `opening_cash`, CASE WHEN `data_cost_daily`.`status` = 'open' THEN concat('<span class="label bg-orange"><i class="fa fa-edit"></i> ',`data_cost_daily`.`status`,'</span>') ELSE '<span class="label bg-green"><i class="fa fa-check"></i> Closed</span>' END AS `status`, `data_cost_daily`.`created_by` AS `created_by`, `data_cost_daily`.`created_date` AS `created_date` FROM `data_cost_daily` ;

-- --------------------------------------------------------

--
-- Structure for view `data_vw_cost_daily_detail`
--
DROP TABLE IF EXISTS `data_vw_cost_daily_detail`;

CREATE ALGORITHM=UNDEFINED DEFINER=`u965363723_superuser`@`%` SQL SECURITY DEFINER VIEW `data_vw_cost_daily_detail`  AS SELECT `data_cost_daily_detail`.`id` AS `id`, `data_cost_daily_detail`.`id_cost_daily` AS `id_cost_daily`, format(`data_cost_daily_detail`.`nominal`,0) AS `nominal`, `data_cost_daily_detail`.`information` AS `information`, `data_cost_daily_detail`.`created_by` AS `created_by`, `data_cost_daily_detail`.`created_date` AS `created_date` FROM `data_cost_daily_detail` ;

-- --------------------------------------------------------

--
-- Structure for view `data_vw_detail_sales`
--
DROP TABLE IF EXISTS `data_vw_detail_sales`;

CREATE ALGORITHM=UNDEFINED DEFINER=`u965363723_superuser`@`%` SQL SECURITY DEFINER VIEW `data_vw_detail_sales`  AS SELECT `d`.`no_struk` AS `no_struk`, `d`.`barcode` AS `barcode`, `d`.`product_name` AS `product_name`, `d`.`qty` AS `qty`, `d`.`netto_gram` AS `netto_gram`, `d`.`brutto_gram` AS `brutto_gram`, format(`d`.`price`,2) AS `price`, `d`.`sales_date` AS `sales_date`, `s`.`storage_name` AS `storage_name` FROM (`data_sales_detail` `d` left join `data_category_storage` `s` on(`d`.`id_category_storage` = `s`.`id`)) ;

-- --------------------------------------------------------

--
-- Structure for view `data_vw_os`
--
DROP TABLE IF EXISTS `data_vw_os`;

CREATE ALGORITHM=UNDEFINED DEFINER=`u965363723_superuser`@`%` SQL SECURITY DEFINER VIEW `data_vw_os`  AS SELECT `data_product_out_os`.`id` AS `id`, concat('<a href="#" onclick="HtmlLoad(\'./lib/base/form_advance_sub_nocrud.php?s=form_report.php&f=33&f_parent=32&sub=',`data_product_out_os`.`invoice`,'&sub_desc=Invoice : ',`data_product_out_os`.`invoice`,'\',\'\');">',`data_product_out_os`.`invoice`,'</a>') AS `invoice`, `data_product_out_os`.`storename` AS `storename`, `data_product_out_os`.`created_by` AS `created_by`, `data_product_out_os`.`created_date` AS `created_date` FROM `data_product_out_os` ;

-- --------------------------------------------------------

--
-- Structure for view `data_vw_os_detail`
--
DROP TABLE IF EXISTS `data_vw_os_detail`;

CREATE ALGORITHM=UNDEFINED DEFINER=`u965363723_superuser`@`%` SQL SECURITY DEFINER VIEW `data_vw_os_detail`  AS SELECT `o`.`id` AS `id`, `o`.`invoice` AS `invoice`, `s`.`storage_name` AS `storage_name`, `o`.`barcode` AS `barcode`, `o`.`product_name` AS `product_name`, `o`.`pen` AS `pen`, format(`o`.`emas_murni`,3) AS `gold_rate`, `o`.`qty_out` AS `qty_out`, format(`o`.`netto_gram_out`,2) AS `netto_gram_out`, format(`o`.`brutto_gram_out`,2) AS `brutto_gram_out` FROM (`data_product_out_os_detail` `o` left join `data_category_storage` `s` on(`o`.`id_category_storage` = `s`.`id`)) ;

-- --------------------------------------------------------

--
-- Structure for view `data_vw_product`
--
DROP TABLE IF EXISTS `data_vw_product`;

CREATE ALGORITHM=UNDEFINED DEFINER=`u965363723_superuser`@`%` SQL SECURITY DEFINER VIEW `data_vw_product`  AS SELECT `data_product`.`id` AS `id`, `data_product`.`barcode` AS `barcode`, `data_product`.`product_name` AS `product_name`, `data_product`.`description` AS `description`, format(`data_product`.`kadar_product`,2) AS `kadar_product`, format(`data_product`.`netto_gram`,2) AS `netto_gram`, format(`data_product`.`brutto_gram`,2) AS `brutto_gram`, format(`data_product`.`sell_price`,2) AS `price`, `data_product`.`created_by` AS `created_by`, `data_product`.`created_date` AS `created_date` FROM `data_product` WHERE `data_product`.`is_delete` = 0 ;

-- --------------------------------------------------------

--
-- Structure for view `data_vw_product_out_reseller`
--
DROP TABLE IF EXISTS `data_vw_product_out_reseller`;

CREATE ALGORITHM=UNDEFINED DEFINER=`u965363723_superuser`@`%` SQL SECURITY DEFINER VIEW `data_vw_product_out_reseller`  AS SELECT `o`.`id` AS `id`, concat('<a href="#" onclick="HtmlLoad(\'./lib/base/form_advance_sub_rs.php?s=form_advance_installment.php&f=28&f_parent=27&sub=',`o`.`invoice`,'&sub_desc=Invoice : ',`o`.`invoice`,'\',\'\');">',`o`.`invoice`,'</a>') AS `invoice`, `o`.`invoice` AS `id_invoice`, `o`.`id_resaller` AS `id_resaller`, `r`.`nama` AS `reseller_name`, CASE WHEN `o`.`status_payment` = 'installment' THEN concat('<span class="label bg-orange"><i class="fa fa-edit"></i> ',`o`.`status_payment`,'</span>') ELSE '<span class="label bg-green"><i class="fa fa-check"></i> Paid Off</span>' END AS `status_payment`, `o`.`created_by` AS `created_by`, `o`.`created_date` AS `created_date` FROM (`data_product_out_resaller` `o` left join `data_resaller` `r` on(`o`.`id_resaller` = `r`.`id`)) ;

-- --------------------------------------------------------

--
-- Structure for view `data_vw_reseller`
--
DROP TABLE IF EXISTS `data_vw_reseller`;

CREATE ALGORITHM=UNDEFINED DEFINER=`u965363723_superuser`@`%` SQL SECURITY DEFINER VIEW `data_vw_reseller`  AS SELECT `data_resaller`.`id` AS `id`, `data_resaller`.`nama` AS `nama`, `data_resaller`.`no_handphone` AS `no_handphone`, `data_resaller`.`alamat` AS `alamat`, `data_resaller`.`no_ktp` AS `no_ktp`, CASE WHEN `data_resaller`.`is_delete` = 0 THEN '<span class="label bg-green"><i class="fa fa-check"></i> Active</span>' ELSE '<span class="label bg-red"><i class="fa fa-close"></i> Unactive</span>' END AS `status`, `data_resaller`.`created_by` AS `created_by`, `data_resaller`.`created_date` AS `created_date` FROM `data_resaller` ;

-- --------------------------------------------------------

--
-- Structure for view `data_vw_sales`
--
DROP TABLE IF EXISTS `data_vw_sales`;

CREATE ALGORITHM=UNDEFINED DEFINER=`u965363723_superuser`@`%` SQL SECURITY DEFINER VIEW `data_vw_sales`  AS SELECT `data_sales`.`id` AS `id`, `data_sales`.`no_struk` AS `id_struk`, concat('<a href="#" onclick="HtmlLoad(\'./lib/base/form_advance_sub_nocrud.php?s=form_advance_transaction.php&f=15&f_parent=14&sub=',`data_sales`.`no_struk`,'&sub_desc=Bill No : ',`data_sales`.`no_struk`,'\',\'\');">',`data_sales`.`no_struk`,'</a>') AS `no_struk`, `data_sales`.`payment_method` AS `payment_method`, format(`data_sales`.`change_payment`,2) AS `change_payment`, format(`data_sales`.`payment_cash`,2) AS `payment_cash`, format(`data_sales`.`payment_trasnfer`,2) AS `payment_trasnfer`, format(`data_sales`.`payment_debit`,2) AS `payment_debit`, format(`data_sales`.`payment_credit`,2) AS `payment_credit`, format(`data_sales`.`payment_dp`,2) AS `payment_dp`, format(`data_sales`.`total_amount`,2) AS `total_amount`, `data_sales`.`card_no_debit` AS `card_no_debit`, `data_sales`.`card_no_credit` AS `card_no_credit`, `data_sales`.`no_rek_transfer` AS `no_rek_transfer`, `data_sales`.`sales_date` AS `transaction_date`, `data_sales`.`created_by` AS `cashier` FROM `data_sales` ;

-- --------------------------------------------------------

--
-- Structure for view `data_vw_stock`
--
DROP TABLE IF EXISTS `data_vw_stock`;

CREATE ALGORITHM=UNDEFINED DEFINER=`u965363723_superuser`@`%` SQL SECURITY DEFINER VIEW `data_vw_stock`  AS SELECT `d`.`id` AS `id`, `d`.`id_category_storage` AS `id_category_storage`, `c`.`storage_name` AS `storage_name`, `d`.`barcode` AS `barcode`, concat(`p`.`product_name`,' (',`p`.`kadar_product`,') ',`p`.`brutto_gram`,' Brutto Gr') AS `product`, `p`.`description` AS `description`, format(`p`.`sell_price`,2) AS `sell_price`, format(`d`.`qty_stock`,2) AS `qty_stock`, `d`.`netto_gram` AS `netto_gram`, `d`.`brutto_gram` AS `brutto_gram`, `d`.`stock_date` AS `stock_date` FROM ((`data_stock_product` `d` left join `data_product` `p` on(`d`.`barcode` = `p`.`barcode`)) left join `data_category_storage` `c` on(`d`.`id_category_storage` = `c`.`id`)) ;

-- --------------------------------------------------------

--
-- Structure for view `data_vw_storage`
--
DROP TABLE IF EXISTS `data_vw_storage`;

CREATE ALGORITHM=UNDEFINED DEFINER=`u965363723_superuser`@`%` SQL SECURITY DEFINER VIEW `data_vw_storage`  AS SELECT `data_category_storage`.`id` AS `id`, `data_category_storage`.`storage_name` AS `storage_name`, `data_category_storage`.`gram` AS `gram`, `data_category_storage`.`created_by` AS `created_by`, `data_category_storage`.`created_date` AS `created_date` FROM `data_category_storage` WHERE coalesce(`data_category_storage`.`is_delete`) = 0 ;

-- --------------------------------------------------------

--
-- Structure for view `select_vw_dp`
--
DROP TABLE IF EXISTS `select_vw_dp`;

CREATE ALGORITHM=UNDEFINED DEFINER=`u965363723_superuser`@`%` SQL SECURITY DEFINER VIEW `select_vw_dp`  AS SELECT `data_sales`.`no_struk` AS `id`, `data_sales`.`no_struk` AS `text` FROM `data_sales` WHERE `data_sales`.`payment_method` like '%DP%' AND `data_sales`.`change_payment` < 0 ;

-- --------------------------------------------------------

--
-- Structure for view `vw_access`
--
DROP TABLE IF EXISTS `vw_access`;

CREATE ALGORITHM=UNDEFINED DEFINER=`u965363723_superuser`@`127.0.0.1` SQL SECURITY DEFINER VIEW `vw_access`  AS SELECT `u`.`id` AS `id`, CASE WHEN `u`.`wrongpass` >= 3 THEN concat('<small class="label bg-red"><i class="fa fa-lock"></i> Lock</small> ',`u`.`userid`) ELSE `u`.`userid` END AS `userid`, `u`.`userpass` AS `userpass`, `u`.`username` AS `username`, `u`.`email` AS `email`, `u`.`firstname` AS `firstname`, `u`.`lastname` AS `lastname`, `u`.`islogin` AS `islogin`, `u`.`isactive` AS `isactive`, `u`.`rolemenu` AS `rolemenu`, `u`.`rolearea` AS `rolearea`, `u`.`createby` AS `createby`, `u`.`createdate` AS `createdate`, `u`.`approvedby` AS `approvedby`, `u`.`approveddate` AS `approveddate`, `u`.`no_handphone` AS `no_handphone`, `gr`.`group_name` AS `id_group_management` FROM (`core_user` `u` left join `core_group` `gr` on(`u`.`id_group_management` = `gr`.`id`)) ;

-- --------------------------------------------------------

--
-- Structure for view `vw_data_detail_receive`
--
DROP TABLE IF EXISTS `vw_data_detail_receive`;

CREATE ALGORITHM=UNDEFINED DEFINER=`u965363723_superuser`@`%` SQL SECURITY DEFINER VIEW `vw_data_detail_receive`  AS SELECT `r`.`id` AS `id`, `r`.`id_receive` AS `id_receive`, `r`.`storage_name` AS `storage_name`, `r`.`barcode` AS `barcode`, concat(`p`.`product_name`,' (',`p`.`kadar_product`,' karat)') AS `product_name`, format(`r`.`qty_receive`,2) AS `qty_receive`, format(`r`.`total_gram`,2) AS `total_gram`, format(`r`.`total_price`,2) AS `total_price`, `r`.`created_by` AS `created_by`, `r`.`created_date` AS `created_date` FROM (`data_detail_receive` `r` left join `data_product` `p` on(`r`.`barcode` = `p`.`barcode`)) ;

-- --------------------------------------------------------

--
-- Structure for view `vw_data_detail_stock`
--
DROP TABLE IF EXISTS `vw_data_detail_stock`;

CREATE ALGORITHM=UNDEFINED DEFINER=`u965363723_superuser`@`%` SQL SECURITY DEFINER VIEW `vw_data_detail_stock`  AS SELECT 0 AS `id`, 0 AS `id_stock_opname`, '' AS `id_barcode`, 0 AS `id_gram_product`, 0 AS `storage_name`, '' AS `product`, '' AS `physical_stock`, '' AS `system_stock`, '' AS `adjusment`, '' AS `difference` ;

-- --------------------------------------------------------

--
-- Structure for view `vw_data_installment_reseller`
--
DROP TABLE IF EXISTS `vw_data_installment_reseller`;

CREATE ALGORITHM=UNDEFINED DEFINER=`u965363723_superuser`@`%` SQL SECURITY DEFINER VIEW `vw_data_installment_reseller`  AS   with base as (select `d`.`id` AS `id`,`d`.`invoice` AS `invoice`,case when `d`.`method` = 'Emas Murni' then format(`d`.`nominal`,3) else format(`d`.`nominal`,0) end AS `nominal`,`d`.`payment_date` AS `payment_date`,`d`.`method` AS `method`,`d`.`bank` AS `bank`,format(`d`.`gram_gold_price`,2) AS `gram_gold_price`,(select sum(`data_product_out_resaller_detail`.`emas_murni`) from `data_product_out_resaller_detail` where `data_product_out_resaller_detail`.`invoice` = `d`.`invoice`) AS `total_emas_murni`,`d`.`nominal` / `d`.`gram_gold_price` AS `kadar` from `data_resaller_payment` `d`)select `base`.`id` AS `id`,`base`.`invoice` AS `invoice`,`base`.`payment_date` AS `payment_date`,`base`.`nominal` AS `nominal`,concat(`base`.`method`,coalesce(concat(' - ',`base`.`bank`),'')) AS `method`,`base`.`gram_gold_price` AS `gram_gold_price`,format(`base`.`kadar`,3) AS `kadar`,case when `base`.`method` = 'Emas Murni' then concat('<span style="font-size:20px; color:green;font-weight:bolder">',format(`base`.`total_emas_murni` - sum(`base`.`nominal`) over ( order by `base`.`id`),3),'</span>') else concat('<span style="font-size:20px; color:green;font-weight:bolder">',format(`base`.`total_emas_murni` - sum(`base`.`kadar`) over ( order by `base`.`id`),3),'</span>') end AS `reminder_rate_gold` from `base`  ;

-- --------------------------------------------------------

--
-- Structure for view `vw_data_receive`
--
DROP TABLE IF EXISTS `vw_data_receive`;

CREATE ALGORITHM=UNDEFINED DEFINER=`u965363723_superuser`@`%` SQL SECURITY DEFINER VIEW `vw_data_receive`  AS SELECT `data_receive`.`id` AS `id`, concat('<a href="#" onclick="HtmlLoad(\'./lib/base/form_advance_sub_receive.php?s=form_advance_receive.php&f=22&f_parent=21&sub=',`data_receive`.`id`,'&sub_desc=No Invoice : ',`data_receive`.`no_invoice`,'\', \'\');">',`data_receive`.`no_invoice`,'</a>') AS `no_invoice`, `data_receive`.`type_receive` AS `type_receive`, `data_receive`.`receive_date` AS `receive_date`, CASE WHEN `data_receive`.`status_receive` = 'open' THEN '<span class="label bg-orange"><i class="fa fa-edit"></i> Open</span>' WHEN `data_receive`.`status_receive` = 'receive' THEN '<span class="label bg-green"><i class="fa fa-check"></i> Received</span>' ELSE concat('<span class="label bg-green"><i class="fa fa-check"></i> ',`data_receive`.`status_receive`,'</span>') END AS `status_receive`, `data_receive`.`created_by` AS `created_by`, `data_receive`.`created_date` AS `created_date` FROM `data_receive` ;

-- --------------------------------------------------------

--
-- Structure for view `vw_data_sales_monthly`
--
DROP TABLE IF EXISTS `vw_data_sales_monthly`;

CREATE ALGORITHM=UNDEFINED DEFINER=`u965363723_superuser`@`%` SQL SECURITY DEFINER VIEW `vw_data_sales_monthly`  AS SELECT date_format(`data_sales`.`sales_date`,'%m%Y') AS `id`, date_format(`data_sales`.`sales_date`,'%M %Y') AS `month`, format(sum(`data_sales`.`payment_cash` + `data_sales`.`payment_trasnfer` + `data_sales`.`payment_debit` + `data_sales`.`payment_credit` + `data_sales`.`payment_dp`),2) AS `total_sales` FROM `data_sales` GROUP BY date_format(`data_sales`.`sales_date`,'%M %Y') ;

-- --------------------------------------------------------

--
-- Structure for view `vw_data_stock_opname`
--
DROP TABLE IF EXISTS `vw_data_stock_opname`;

CREATE ALGORITHM=UNDEFINED DEFINER=`u965363723_superuser`@`%` SQL SECURITY DEFINER VIEW `vw_data_stock_opname`  AS SELECT `data_stock_opname`.`id` AS `id`, concat('<a href="#" onclick="HtmlLoad(\'./lib/base/form_advance_sub_so.php?s=form_advance_stock_opname.php&f=20&f_parent=17&sub=',`data_stock_opname`.`id`,'&sub_desc=Stock Opname : ',`data_stock_opname`.`stock_opname_info`,', period ',`data_stock_opname`.`stock_opname_time`,' \',\'\');">',`data_stock_opname`.`stock_opname_info`,'</a>') AS `stock_opname_info`, `data_stock_opname`.`stock_opname_time` AS `stock_opname_time`, CASE WHEN `data_stock_opname`.`status_stock_opname` = 'open' THEN '<span class="label bg-green"><i class="fa fa-edit"></i> Open</span>' ELSE concat('<span class="label bg-red"><i class="fa fa-close"></i> ',`data_stock_opname`.`status_stock_opname`,'</span>') END AS `status`, `data_stock_opname`.`created_by` AS `created_by`, `data_stock_opname`.`created_date` AS `created_date` FROM `data_stock_opname` ;

-- --------------------------------------------------------

--
-- Structure for view `vw_data_stock_weight`
--
DROP TABLE IF EXISTS `vw_data_stock_weight`;

CREATE ALGORITHM=UNDEFINED DEFINER=`u965363723_superuser`@`%` SQL SECURITY DEFINER VIEW `vw_data_stock_weight`  AS SELECT `w`.`id` AS `id`, `w`.`id_stock_weight` AS `id_stock_weight`, `w`.`id_category_storage` AS `id_category_storage`, `s`.`storage_name` AS `storage_name`, `w`.`opening_weight` AS `opening_gram`, `w`.`closing_weight` AS `closing_gram`, `s`.`gram`+ `h`.`total_gram` AS `SS_gram_storage_update`, CASE WHEN coalesce(`w`.`closing_weight`,0) - (`s`.`gram` + `h`.`total_gram`) < 0 THEN concat('<span style="font-size:20px;color:red">',coalesce(`w`.`closing_weight`,0) - (`s`.`gram` + `h`.`total_gram`),'</span>') ELSE concat('<span style="font-size:20px;color:green">+ ',coalesce(`w`.`closing_weight`,0) - (`s`.`gram` + `h`.`total_gram`),'</span>') END AS `weight_closing_vs_SS_update`, `w`.`created_by` AS `created_by`, `w`.`created_date` AS `created_date` FROM ((`data_stock_weight_detail` `w` left join `data_category_storage` `s` on(`w`.`id_category_storage` = `s`.`id`)) left join (select `s`.`id_category_storage` AS `id_category_storage`,sum(`s`.`brutto_gram`) AS `total_gram` from `data_stock_product` `s` group by `s`.`id_category_storage`) `h` on(`w`.`id_category_storage` = `h`.`id_category_storage`)) ;

-- --------------------------------------------------------

--
-- Structure for view `vw_data_sw`
--
DROP TABLE IF EXISTS `vw_data_sw`;

CREATE ALGORITHM=UNDEFINED DEFINER=`u965363723_superuser`@`%` SQL SECURITY DEFINER VIEW `vw_data_sw`  AS SELECT `data_stock_weight`.`id` AS `id`, concat('<a href="#" onclick="HtmlLoad(\'./lib/base/form_advance_sub_sw.php?s=form_advance_sw.php&f=25&f_parent=24&sub=',`data_stock_weight`.`id`,'&sub_desc=Stock Weight : ',`data_stock_weight`.`stock_info`,', period ',`data_stock_weight`.`stock_date`,' \',\'\');">',`data_stock_weight`.`stock_info`,'</a>') AS `stock_info`, `data_stock_weight`.`stock_date` AS `stock_date`, CASE WHEN `data_stock_weight`.`status` = 'open' THEN '<span class="label bg-green"><i class="fa fa-edit"></i> Open</span>' ELSE concat('<span class="label bg-red"><i class="fa fa-close"></i> ',`data_stock_weight`.`status`,'</span>') END AS `status`, `data_stock_weight`.`created_by` AS `created_by`, `data_stock_weight`.`created_date` AS `created_date` FROM `data_stock_weight` ;

-- --------------------------------------------------------

--
-- Structure for view `vw_group_management`
--
DROP TABLE IF EXISTS `vw_group_management`;

CREATE ALGORITHM=UNDEFINED DEFINER=`u965363723_superuser`@`127.0.0.1` SQL SECURITY DEFINER VIEW `vw_group_management`  AS SELECT `core_group`.`id` AS `id`, `core_group`.`group_name` AS `group_name`, `core_group`.`created_by` AS `created_by`, `core_group`.`created_date` AS `created_date`, `core_group`.`update_by` AS `update_by`, `core_group`.`update_date` AS `update_date` FROM `core_group` ;

-- --------------------------------------------------------

--
-- Structure for view `vw_menu_set_role`
--
DROP TABLE IF EXISTS `vw_menu_set_role`;

CREATE ALGORITHM=UNDEFINED DEFINER=`u965363723_superuser`@`127.0.0.1` SQL SECURITY DEFINER VIEW `vw_menu_set_role`  AS SELECT `core_menus`.`id` AS `id`, `core_menus`.`menu` AS `menu` FROM `core_menus` WHERE `core_menus`.`links` is not null ;

-- --------------------------------------------------------

--
-- Structure for view `vw_select_bank`
--
DROP TABLE IF EXISTS `vw_select_bank`;

CREATE ALGORITHM=UNDEFINED DEFINER=`u965363723_superuser`@`%` SQL SECURITY DEFINER VIEW `vw_select_bank`  AS SELECT `data_reference`.`description` AS `id`, `data_reference`.`description` AS `text` FROM `data_reference` WHERE `data_reference`.`filter` = 'bank' ;

-- --------------------------------------------------------

--
-- Structure for view `vw_select_group`
--
DROP TABLE IF EXISTS `vw_select_group`;

CREATE ALGORITHM=UNDEFINED DEFINER=`u965363723_superuser`@`127.0.0.1` SQL SECURITY DEFINER VIEW `vw_select_group`  AS SELECT `core_group`.`id` AS `id`, `core_group`.`group_name` AS `text` FROM `core_group` ;

-- --------------------------------------------------------

--
-- Structure for view `vw_select_method`
--
DROP TABLE IF EXISTS `vw_select_method`;

CREATE ALGORITHM=UNDEFINED DEFINER=`u965363723_superuser`@`%` SQL SECURITY DEFINER VIEW `vw_select_method`  AS SELECT `data_reference`.`description` AS `id`, `data_reference`.`description` AS `text` FROM `data_reference` WHERE `data_reference`.`filter` = 'method' ;

-- --------------------------------------------------------

--
-- Structure for view `vw_select_product`
--
DROP TABLE IF EXISTS `vw_select_product`;

CREATE ALGORITHM=UNDEFINED DEFINER=`u965363723_superuser`@`%` SQL SECURITY DEFINER VIEW `vw_select_product`  AS SELECT `data_product`.`barcode` AS `id`, concat(`data_product`.`barcode`,' - ',`data_product`.`product_name`,' (',`data_product`.`kadar_product`,' karat), Rp ',format(`data_product`.`sell_price`,2)) AS `text` FROM `data_product` WHERE coalesce(`data_product`.`is_delete`,0) = 0 ;

-- --------------------------------------------------------

--
-- Structure for view `vw_select_product_receive`
--
DROP TABLE IF EXISTS `vw_select_product_receive`;

CREATE ALGORITHM=UNDEFINED DEFINER=`u965363723_superuser`@`%` SQL SECURITY DEFINER VIEW `vw_select_product_receive`  AS SELECT `data_product`.`barcode` AS `id`, concat(`data_product`.`product_name`,' (',`data_product`.`kadar_product`,' karat)') AS `text` FROM `data_product` ;

-- --------------------------------------------------------

--
-- Structure for view `vw_select_reseller`
--
DROP TABLE IF EXISTS `vw_select_reseller`;

CREATE ALGORITHM=UNDEFINED DEFINER=`u965363723_superuser`@`%` SQL SECURITY DEFINER VIEW `vw_select_reseller`  AS SELECT `data_resaller`.`id` AS `id`, `data_resaller`.`nama` AS `text` FROM `data_resaller` ;

-- --------------------------------------------------------

--
-- Structure for view `vw_select_roles_jabatan`
--
DROP TABLE IF EXISTS `vw_select_roles_jabatan`;

CREATE ALGORITHM=UNDEFINED DEFINER=`u965363723_superuser`@`127.0.0.1` SQL SECURITY DEFINER VIEW `vw_select_roles_jabatan`  AS SELECT `core_roles_jabatan`.`roles` AS `id`, `core_roles_jabatan`.`roles` AS `text` FROM `core_roles_jabatan` ;

-- --------------------------------------------------------

--
-- Structure for view `vw_select_storage`
--
DROP TABLE IF EXISTS `vw_select_storage`;

CREATE ALGORITHM=UNDEFINED DEFINER=`u965363723_superuser`@`%` SQL SECURITY DEFINER VIEW `vw_select_storage`  AS SELECT `data_category_storage`.`id` AS `id`, `data_category_storage`.`storage_name` AS `text` FROM `data_category_storage` WHERE coalesce(`data_category_storage`.`is_delete`,0) = 0 ;

-- --------------------------------------------------------

--
-- Structure for view `vw_select_typereceive`
--
DROP TABLE IF EXISTS `vw_select_typereceive`;

CREATE ALGORITHM=UNDEFINED DEFINER=`u965363723_superuser`@`%` SQL SECURITY DEFINER VIEW `vw_select_typereceive`  AS SELECT `data_reference`.`description` AS `id`, `data_reference`.`description` AS `text` FROM `data_reference` WHERE `data_reference`.`filter` = 'receive' ;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `core_fields`
--
ALTER TABLE `core_fields`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `core_forms`
--
ALTER TABLE `core_forms`
  ADD PRIMARY KEY (`idform`);

--
-- Indexes for table `core_group`
--
ALTER TABLE `core_group`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `core_level`
--
ALTER TABLE `core_level`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `core_menus`
--
ALTER TABLE `core_menus`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `core_otp`
--
ALTER TABLE `core_otp`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `core_rolemenu`
--
ALTER TABLE `core_rolemenu`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `core_roles_jabatan`
--
ALTER TABLE `core_roles_jabatan`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `core_rules_level`
--
ALTER TABLE `core_rules_level`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `core_user`
--
ALTER TABLE `core_user`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `data_category_storage`
--
ALTER TABLE `data_category_storage`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `data_cost_daily`
--
ALTER TABLE `data_cost_daily`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `data_cost_daily_detail`
--
ALTER TABLE `data_cost_daily_detail`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `data_detail_order`
--
ALTER TABLE `data_detail_order`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `no_order` (`no_order`);

--
-- Indexes for table `data_detail_receive`
--
ALTER TABLE `data_detail_receive`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `data_order`
--
ALTER TABLE `data_order`
  ADD UNIQUE KEY `no_order` (`no_order`);

--
-- Indexes for table `data_product`
--
ALTER TABLE `data_product`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `barcode` (`barcode`),
  ADD KEY `barcode_2` (`barcode`);

--
-- Indexes for table `data_product_out_os`
--
ALTER TABLE `data_product_out_os`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `invoice` (`invoice`);

--
-- Indexes for table `data_product_out_os_detail`
--
ALTER TABLE `data_product_out_os_detail`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `data_product_out_resaller`
--
ALTER TABLE `data_product_out_resaller`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `invoice` (`invoice`);

--
-- Indexes for table `data_product_out_resaller_detail`
--
ALTER TABLE `data_product_out_resaller_detail`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `data_receive`
--
ALTER TABLE `data_receive`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `data_reference`
--
ALTER TABLE `data_reference`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `data_resaller`
--
ALTER TABLE `data_resaller`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `data_resaller_payment`
--
ALTER TABLE `data_resaller_payment`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `data_sales`
--
ALTER TABLE `data_sales`
  ADD PRIMARY KEY (`id`),
  ADD KEY `nostruk_sales` (`no_struk`),
  ADD KEY `sales_date` (`sales_date`);

--
-- Indexes for table `data_sales_detail`
--
ALTER TABLE `data_sales_detail`
  ADD PRIMARY KEY (`id`),
  ADD KEY `nostruk_sales_detail` (`no_struk`),
  ADD KEY `id_sales_detail` (`id`);

--
-- Indexes for table `data_stock_opname`
--
ALTER TABLE `data_stock_opname`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `data_stock_opname_detail`
--
ALTER TABLE `data_stock_opname_detail`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `data_stock_product`
--
ALTER TABLE `data_stock_product`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `data_stock_weight`
--
ALTER TABLE `data_stock_weight`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `data_stock_weight_detail`
--
ALTER TABLE `data_stock_weight_detail`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `data_supplier`
--
ALTER TABLE `data_supplier`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `core_fields`
--
ALTER TABLE `core_fields`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=458;

--
-- AUTO_INCREMENT for table `core_forms`
--
ALTER TABLE `core_forms`
  MODIFY `idform` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

--
-- AUTO_INCREMENT for table `core_group`
--
ALTER TABLE `core_group`
  MODIFY `id` bigint(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `core_level`
--
ALTER TABLE `core_level`
  MODIFY `id` bigint(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `core_menus`
--
ALTER TABLE `core_menus`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=49;

--
-- AUTO_INCREMENT for table `core_otp`
--
ALTER TABLE `core_otp`
  MODIFY `id` bigint(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=39;

--
-- AUTO_INCREMENT for table `core_rolemenu`
--
ALTER TABLE `core_rolemenu`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=571;

--
-- AUTO_INCREMENT for table `core_roles_jabatan`
--
ALTER TABLE `core_roles_jabatan`
  MODIFY `id` bigint(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `core_rules_level`
--
ALTER TABLE `core_rules_level`
  MODIFY `id` bigint(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=89;

--
-- AUTO_INCREMENT for table `core_user`
--
ALTER TABLE `core_user`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=53;

--
-- AUTO_INCREMENT for table `data_category_storage`
--
ALTER TABLE `data_category_storage`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `data_cost_daily`
--
ALTER TABLE `data_cost_daily`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `data_cost_daily_detail`
--
ALTER TABLE `data_cost_daily_detail`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `data_detail_order`
--
ALTER TABLE `data_detail_order`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `data_detail_receive`
--
ALTER TABLE `data_detail_receive`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `data_product`
--
ALTER TABLE `data_product`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `data_product_out_os`
--
ALTER TABLE `data_product_out_os`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `data_product_out_os_detail`
--
ALTER TABLE `data_product_out_os_detail`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `data_product_out_resaller`
--
ALTER TABLE `data_product_out_resaller`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `data_product_out_resaller_detail`
--
ALTER TABLE `data_product_out_resaller_detail`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `data_receive`
--
ALTER TABLE `data_receive`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `data_reference`
--
ALTER TABLE `data_reference`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `data_resaller`
--
ALTER TABLE `data_resaller`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `data_resaller_payment`
--
ALTER TABLE `data_resaller_payment`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `data_sales`
--
ALTER TABLE `data_sales`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `data_sales_detail`
--
ALTER TABLE `data_sales_detail`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `data_stock_opname`
--
ALTER TABLE `data_stock_opname`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `data_stock_opname_detail`
--
ALTER TABLE `data_stock_opname_detail`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `data_stock_product`
--
ALTER TABLE `data_stock_product`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `data_stock_weight`
--
ALTER TABLE `data_stock_weight`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `data_stock_weight_detail`
--
ALTER TABLE `data_stock_weight_detail`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `data_supplier`
--
ALTER TABLE `data_supplier`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
