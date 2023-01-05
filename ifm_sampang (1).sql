-- phpMyAdmin SQL Dump
-- version 5.0.4
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 05, 2023 at 01:13 AM
-- Server version: 10.4.17-MariaDB
-- PHP Version: 7.4.14

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `ifm_sampang`
--

DELIMITER $$
--
-- Functions
--
CREATE DEFINER=`root`@`localhost` FUNCTION `getExt` (`Value` LONGTEXT, `delimeter` VARCHAR(10)) RETURNS TEXT CHARSET utf8mb4 BEGIN
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

CREATE DEFINER=`root`@`localhost` FUNCTION `labelsplit` (`Value` LONGTEXT, `delimeter` VARCHAR(10)) RETURNS TEXT CHARSET utf8mb4 BEGIN
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

CREATE DEFINER=`root`@`localhost` FUNCTION `SPLIT_PART` (`s` VARCHAR(1024), `del` CHAR(1), `i` INT(11)) RETURNS VARCHAR(1024) CHARSET latin1 BEGIN

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
(281, 14, 'id', 1, NULL, 'number', NULL, NULL, 1, 'pk', 'header', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(282, 14, 'file_name', 1, 'Upload File', 'file', NULL, NULL, 1, 'nm', 'header', NULL, NULL, NULL, 'Upload File', NULL, 1, NULL),
(283, 14, 'description', 1, 'Description File', 'textarea', NULL, NULL, 1, 'nm', 'header', NULL, NULL, NULL, 'Description File', NULL, 1, NULL),
(284, 14, 'category', 1, 'Category', 'select', NULL, NULL, 1, 'nm', 'header', NULL, NULL, NULL, 'Category', NULL, 1, NULL),
(285, 14, 'tags', 1, 'Tags', 'checkbox', NULL, NULL, 1, 'nm', 'header', NULL, NULL, NULL, 'Tags', NULL, 1, NULL);

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
(14, 'data_file', 'data_file', 'Data Files All', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'vw_data_file_all');

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
(3, 'Users', NULL, NULL, NULL, NULL),
(4, 'Super User', NULL, NULL, NULL, NULL);

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
(1, '=', 'Equal'),
(2, '>=', 'Greater Than'),
(3, '<=', 'Less Than'),
(4, '<>', 'Not Equal'),
(5, 'like', 'Contains');

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
(3, 97, 'MANAJEMEN AKUN', NULL, 0, 10414, 10417, 'false', 'false', NULL, 0, 0, 0, ' fa-users', NULL, 'Manual_Master_Data_Management_V_1_0_0.pdf', NULL, NULL),
(4, 97, 'Users', './lib/base/users.php?f=1', 1, 4205, 4205, 'true', 'true', NULL, 3, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(5, 97, 'Profile', './lib/base/config_person_user.php', 0, 64, 64, 'true', 'true', NULL, 3, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(7, 97, 'FILES', NULL, 0, 10414, 10417, 'false', 'false', NULL, 0, 0, 0, ' fa-file-pdf-o', NULL, 'Manual_Master_Data_Management_V_1_0_0.pdf', NULL, NULL),
(8, 97, 'Manage Files', './lib/base/form_advance_ifm.php?f=12', NULL, NULL, NULL, NULL, NULL, NULL, 7, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(10, 97, 'Files', './lib/base/form_files.php?f=14', NULL, NULL, NULL, NULL, NULL, NULL, 7, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(12, 97, 'Group Management', './lib/base/group.php?f=8', 1, 4205, 4205, 'true', 'true', NULL, 3, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(20, 97, 'Category', './lib/base/form_advance.php?f=13', NULL, NULL, NULL, NULL, NULL, NULL, 7, NULL, NULL, NULL, NULL, NULL, NULL, NULL);

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
(1, 6, 10),
(2, 6, 1),
(3, 6, 5),
(4, 6, 11),
(5, 2, 10),
(6, 2, 1),
(7, 2, 12),
(8, 2, 6),
(9, 2, 8),
(10, 2, 5),
(11, 2, 9),
(12, 2, 11),
(13, 2, 4),
(103, 3, 10),
(104, 3, 8),
(105, 3, 5),
(106, 4, 20),
(107, 4, 10),
(108, 4, 12),
(109, 4, 8),
(110, 4, 5),
(111, 4, 4);

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
(13, '197210262005011007', '$P$BBtvIoC.GvjBEY5gAQhiyZkPpJFYmY.', 'Amrin Hidayat test', '', 'Amrin Hidayat', '', '90lVUVlBX432mBIbD504ZZerR441LlGSl441IBPqH459AITxYGYfQ210DVX9N441uMLvE441kqTma459bBXDJ468kPHde477LqBXZ4776Xqs', 0, 1, NULL, NULL, '', NULL, '', NULL, NULL, 3),
(14, '197812262006042017', '$P$BBtvIoC.GvjBEY5gAQhiyZkPpJFYmY.', 'Desy Christina Hadiyanti', '', 'Desy Christina Hadiyanti', '', 'MHsIRIDdLv2928aSzhNE3416mempbo2989dOhExV3111ISwGJX3111DqcGym2989YpCJvA6m4sWc61xVcePZ3050QxYzZn3477xuPKgJ3477cbNeFY3416CNUYKl3111MqMDZS3416Gr3o', 0, 1, NULL, NULL, '', NULL, '', NULL, NULL, 3),
(15, '199807092011022001', '$P$BBtvIoC.GvjBEY5gAQhiyZkPpJFYmY.', 'Nabila Maulina Pratamastiwi', '', 'Nabila Maulina Pratamastiwi', '', 'NAaquy3936mP4592bF4018TM4674FD4674JR4674VacV0C6Q82Rs4346MQ4018rg3936FO4510Xf4674eI46740Ri4', 0, 1, NULL, NULL, '', NULL, '', NULL, NULL, 3),
(16, '198207072002121004', '$P$BBtvIoC.GvjBEY5gAQhiyZkPpJFYmY.', 'Chusni Mubarok', '', 'Chusni Mubarok', '', 'FAPQMVWDQ2544HGnKH2968hDQZR2597JxTFs2968FrSIT2703IVWEE110pxJ53tzVuy2650nmnde2862fCAdf2650lMuQI2597nSqhZ3021Inu5', 0, 1, NULL, NULL, '', NULL, '', NULL, NULL, 3),
(17, '199506222019032016', '$P$BBtvIoC.GvjBEY5gAQhiyZkPpJFYmY.', 'Misbahud Dzurriyah', '', 'Misbahud Dzurriyah', '', '7vJzZdo768Eyx896CRZ848bvP816TXD816Yka832PUSf410a16dpV768XaZ912Azt912ids912USO880Kou880d69b', 0, 1, NULL, NULL, '', NULL, '', NULL, NULL, 3),
(18, '198311292010012017', '$P$BBtvIoC.GvjBEY5gAQhiyZkPpJFYmY.', 'Nailun Nusroh', '', 'Nailun Nusroh', '', 'fXUbQD384gU448Ir392MH448AF384fQ408ZgO1d6YW8Lk400FB448Ee416iV440OC448NE456y9HO', 0, 1, NULL, NULL, '', NULL, '', NULL, NULL, 3),
(19, '198607212015031001', '$P$BBtvIoC.GvjBEY5gAQhiyZkPpJFYmY.', 'Tontowi Jauhari', '', 'Tontowi Jauhari', '', '27JHlcM2976hdl3472hiM3410LFO3472YTc3224AkW3534taTb411RY62IMA3472fOr3472bjP3472JaP3038KPJ3224SOD3472RFoS', 0, 1, NULL, NULL, '', NULL, '', NULL, NULL, 3),
(20, '196807042002122002', '$P$BBtvIoC.GvjBEY5gAQhiyZkPpJFYmY.', 'Evi Hariati', '', 'Evi Hariati', '', 'Zld3ryjpFpM48SYPuSel56AFcMcYf49IXsgOQy50BCWBSGg49iPymBHOXCE5P4QUf1IPN55RvTOXpA48oqXPEuQ54liNcGYN48ZZGeNbx55bfRUBWJ50HZ27', 0, 1, NULL, NULL, '', NULL, '', NULL, NULL, 3),
(21, '197109051996021001', '$P$BBtvIoC.GvjBEY5gAQhiyZkPpJFYmY.', 'Akh. Jamaludin', '', 'Akh. Jamaludin', '', '6R04fEp1680xjX1960ssU1750RKj1785NOD1785fFd1960aSAJ2h9kK35HRL1680DuQ1925aIg1715ADF1855lwF1925EVH1715J5bR', 0, 1, NULL, NULL, '', NULL, '', NULL, NULL, 3),
(22, '196312311992121004', '$P$BBtvIoC.GvjBEY5gAQhiyZkPpJFYmY.', 'Abd. Kholiq', '', 'Abd. Kholiq', '', 'Y81gu2208D2576y2438n2300G2438S2622LBA0i5r46h2576v2576N2254X2346p2254i2300NMJC', 0, 1, NULL, NULL, '', NULL, '', NULL, NULL, 3),
(23, '197605282012121002', '$P$BBtvIoC.GvjBEY5gAQhiyZkPpJFYmY.', 'Hasanudin', '', 'Hasanudin', '', 'DLuqIpZ4704mxI5488Lhj4802CHs4998eFU4998BCK4900Jkkf7q0cK98msE4802Xdy4900DDl5194Hrm4802VmB4900Riq5096hgjF', 0, 1, NULL, NULL, '', NULL, '', NULL, NULL, 3),
(24, '199204272017012001', '$P$BBtvIoC.GvjBEY5gAQhiyZkPpJFYmY.', 'Khoirunnisak', '', 'Khoirunnisak', '', 'dQa1EIQ1248qVN1456tuV1300xBy1326JEM1326jMU1456PDJf6i1xg26VPG1404tQX1248CWf1482uSb1300JlB1352gjL13524acr', 0, 1, NULL, NULL, '', NULL, '', NULL, NULL, 3),
(25, '198001212011011002', '$P$BBtvIoC.GvjBEY5gAQhiyZkPpJFYmY.', 'Fery Wage Susanto', '', 'Fery Wage Susanto', '', 'IcbmYLKHFO2544ReNNca2968lattbr2809SBWQmD2703WfdSWB2703wZCEFc2544UKxBKc7z3Zji53HbBmyA2597rUBgOq2650ZeAiEH2968YVoLFY2809cpnVMS2544XVGUkE2544JwEN', 0, 1, NULL, NULL, '', NULL, '', NULL, NULL, 3),
(26, '198212312010011016', '$P$BBtvIoC.GvjBEY5gAQhiyZkPpJFYmY.', 'Mu`ammal Hamidy', '', 'Mu`ammal Hamidy', '', 'pxGKq2640W3080Z2695q2805s2805A2695Xtk4G9H55S2640d2805n2805q2695H2640L2695U9BR', 0, 1, NULL, NULL, '', NULL, '', NULL, NULL, 3),
(27, '198401142014062010', '$P$BBtvIoC.GvjBEY5gAQhiyZkPpJFYmY.', 'Riskawati', '', 'Riskawati', '', 'pUc7UV1536EL1792yG1760BT1760Ea1536eS1568YtDA5U1Y32KJ1760be1792Jy1728pJ1792nZ1664SG1600EqZS', 0, 1, NULL, NULL, '', NULL, '', NULL, NULL, 3),
(28, '199503062018042001', '$P$BBtvIoC.GvjBEY5gAQhiyZkPpJFYmY.', 'Retno Fitriya Ningsih', '', 'Retno Fitriya Ningsih', '', 'OZzBOLs96FE112rB100tW102bc102YtM96odI0X5KN2BJ104TI110aIk96My114aC104yc110vx1L', 0, 1, NULL, NULL, '', NULL, '', NULL, NULL, 3),
(29, '199312012019011001', '$P$BBtvIoC.GvjBEY5gAQhiyZkPpJFYmY.', 'Sultonul Hadi AM', '', 'Sultonul Hadi AM', '', 'hGIPAIWE1104lHPE1288EYhy1127MmCV1173GYCF1173dwDS1173ASBKN5C3mb23kppo1104WnfT1173Tnda1104iWaI1242hQpx1219BlxK1196M2mR', 0, 1, NULL, NULL, '', NULL, '', NULL, NULL, 3),
(30, '199504242018061001', '$P$BBtvIoC.GvjBEY5gAQhiyZkPpJFYmY.', 'Ahmad Rizeki', '', 'Ahmad Rizeki', '', 'jT8HGXLO1104RNAX1288oxwt1265JUMO1288IgKa1196DWhf1311DoAoE5s3Yh23UQSn1311CknB1242nSRa1265KTLm1173legs1173ZRDA1265jB7d', 0, 1, NULL, NULL, '', NULL, '', NULL, NULL, 3),
(31, '196402141987031010', '$P$BBtvIoC.GvjBEY5gAQhiyZkPpJFYmY.', 'Moh. Heriyanto', '', 'Moh. Heriyanto', '', '2REZb3312V3864M3381H3519w3519d3588Mzt2Y7N69n3450E3381U3588q3450f3519p3588j3Cu', 0, 1, NULL, NULL, '', NULL, '', NULL, NULL, 3),
(32, '197712152005011007', '$P$BBtvIoC.GvjBEY5gAQhiyZkPpJFYmY.', 'Prama Arta Wiraswasta', '', 'Prama Arta Wiraswasta', '', 'FZEBE1152H1344X1176X1224S1224A1176UfI3N8P24Z1152p1224v1224P1176D1152E117607HF', 0, 1, NULL, NULL, '', NULL, '', NULL, NULL, 3),
(33, '198601312011011005', '$P$BBtvIoC.GvjBEY5gAQhiyZkPpJFYmY.', 'Fery Isniady', '', 'Fery Isniady', '', '4N5nIkL2016QNm2352cjH2058rLn2100LvP2058hQb2058cQWi310cf42Qow2142FZd2268NOy2142SmZ2016fUI2100fKT2310eP7v', 0, 1, NULL, NULL, '', NULL, '', NULL, NULL, 3),
(34, '199703062021022001', '$P$BBtvIoC.GvjBEY5gAQhiyZkPpJFYmY.', 'Ismy Qorry Aini', '', 'Ismy Qorry Aini', '', '4XeFe1968a2296Y2255D2255u2255y1968HJU2y7n41E1968V1968g2255U2214I1968k2255cdS7', 0, 1, NULL, NULL, '', NULL, '', NULL, NULL, 3),
(35, '199707112021022001', '$P$BBtvIoC.GvjBEY5gAQhiyZkPpJFYmY.', 'Mamlu Atul Hasanah', '', 'Mamlu Atul Hasanah', '', 'QOmFoh384mO448oI440YS448ZO424NB384vuK4r9DL8LN424cQ408QU424NC392iA424pu392xCDJ', 0, 1, NULL, NULL, '', NULL, '', NULL, NULL, 3),
(36, '197105252006041015', '$P$BBtvIoC.GvjBEY5gAQhiyZkPpJFYmY.', 'Slamet Hatono', '', 'Slamet Hatono', '', 'd5r1J3600n4200Q3675Q4200Z3825vkB2F7F75R3825W4125C3675S3750W3975DCSq', 0, 1, NULL, NULL, '', NULL, '', NULL, NULL, 3),
(37, '196408241989031015', '$P$BBtvIoC.GvjBEY5gAQhiyZkPpJFYmY.', 'Moh. Mudakkir', '', 'Moh. Mudakkir', '', 'P1OkWfbL2016PLog2352lScC2058ioco2142jLWP2142iGya2016cMLXM6a2XN42OZXQ2226FPEJ2100btJP2226QMOI2352GUNK2352IXZw2310dnOo', 0, 1, NULL, NULL, '', NULL, '', NULL, NULL, 3),
(38, '196312311988011014', '$P$BBtvIoC.GvjBEY5gAQhiyZkPpJFYmY.', 'Mahmud', '', 'Mahmud', '', 'nC0FbhkA48csRR56ksmF50vzxu51PBcp51FEac54ThNL1j7oK1nYFM48EOTU55eirM50GuWX49NCbP54NiwH53I494', 0, 1, NULL, NULL, '', NULL, '', NULL, NULL, 3),
(39, '197409232007011005', '$P$BBtvIoC.GvjBEY5gAQhiyZkPpJFYmY.', 'Haris Purnawarman', '', 'Haris Purnawarman', '', 'xnCFuA4656Ht5432sc4753lM4947uM4947yW4753cdWw2q8P97Tp4753KP5141NJ5044kB4947BL4753JZ4753NwR5', 0, 1, NULL, NULL, '', NULL, '', NULL, NULL, 3),
(40, '198108042010011013', '$P$BBtvIoC.GvjBEY5gAQhiyZkPpJFYmY.', 'Adi Candra Kusuma', '', 'Adi Candra Kusuma', '', 'St9VVIAFB960SQJu1120ABJA1100OlEb1100HQBO1120ChZC1000UShix7p1db20vqLB1020Vfqo1100ArKV1100QdRn1060SUiM1080jVDz1060GRuX', 0, 1, NULL, NULL, '', NULL, '', NULL, NULL, 3),
(41, '199101012020121009', '$P$BBtvIoC.GvjBEY5gAQhiyZkPpJFYmY.', 'Billy Saputra', '', 'Billy Saputra', '', '7dOOTfE1008YIa1176IRt1050Wuh1050VyK1092oGV1092uXVy7h0ux21fTS1134QYB1113rLi1113LDV1029bgo1092ObA1155CHoB', 0, 1, NULL, NULL, '', NULL, '', NULL, NULL, 3),
(42, '199410172019051001', '$P$BBtvIoC.GvjBEY5gAQhiyZkPpJFYmY.', 'Dedi Adi Wijaya', '', 'Dedi Adi Wijaya', '', 'HAvwAQO4368dMA5096mMI4641XWL4459OKq4459PEP4732TEYI0y7pj91KcN5005dFB4732ZCQ4641Yjc4732FqY4641QPF50059112', 0, 1, NULL, NULL, '', NULL, '', NULL, NULL, 3),
(43, '199611142021022001', '$P$BBtvIoC.GvjBEY5gAQhiyZkPpJFYmY.', 'Mila Hendriana', '', 'Mila Hendriana', '', 'GjBZyodE2400TmNO2800hEQM2500PvcN2500PAIJ2600EJDJ2600vrFJF210WN50WHkG2850klhE2450bVbT2800egSe2500qRNl2450rSID2650NdRo', 0, 1, NULL, NULL, '', NULL, '', NULL, NULL, 3),
(44, '199606242018012001', '$P$BBtvIoC.GvjBEY5gAQhiyZkPpJFYmY.', 'Ira Widiastutik', '', 'Ira Widiastutik', '', 'z0hFvXMO1152VReg1344BgFK1224GAeO1344HVlG1272yQOm1200FsbPE6R2PH24pYAH1248inVi1248mPmj1368ESYb1176JcNg1176FRYI1368yfsO', 0, 1, NULL, NULL, '', NULL, '', NULL, NULL, 3);

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
-- Table structure for table `data_category`
--

CREATE TABLE `data_category` (
  `id` bigint(20) NOT NULL,
  `category` varchar(1000) DEFAULT NULL,
  `created_by` varchar(1000) DEFAULT NULL,
  `created_date` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `data_category`
--

INSERT INTO `data_category` (`id`, `category`, `created_by`, `created_date`) VALUES
(1, 'SPK', 'adesulaeman', '2022-12-12 03:02:49'),
(2, 'Surat Berharga', 'adesulaeman', '2022-12-12 03:06:20');

-- --------------------------------------------------------

--
-- Table structure for table `data_file`
--

CREATE TABLE `data_file` (
  `id` bigint(20) NOT NULL,
  `file_name` varchar(1000) DEFAULT NULL,
  `description` varchar(2000) DEFAULT NULL,
  `category` varchar(1000) DEFAULT NULL,
  `tags` varchar(2000) DEFAULT NULL,
  `created_by` varchar(1000) DEFAULT NULL,
  `created_date` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `data_file`
--

INSERT INTO `data_file` (`id`, `file_name`, `description`, `category`, `tags`, `created_by`, `created_date`) VALUES
(3, '6396ea261cd2b_rundown phase 3, resync update version eds sql, and recovery home phone.xlsx', 'rundown check', '4', 'cek1|cek2|cek3|cek4|cek5', 'adesulaeman', '2022-12-12 02:45:26'),
(4, '6396ead196899_Book1 (1).xlsx', 'cek1', '4', 'cek1|cek2|cek4|ckr4|crk5', 'adesulaeman', '2022-12-12 02:48:17'),
(5, '6396eb41dcd12_export (1).xlsx', 'cekjdlakjl', '3', 'hdsjakh|dshakjdas|dasjhdsad|asfhjdskfsd|fhdjskfsd|fsdhjkfsd', 'adesulaeman', '2022-12-12 02:50:09'),
(6, '6396ef8621ca2_4731262572Oct2022 (1).pdf', 'testing', 'SPK', 'testing|data penting|surat berharga', 'adesulaeman', '2022-12-12 03:08:22'),
(7, '63988881108a1_no-image-icon-13.png', 'image', 'Surat Berharga', 'testing', 'adesulaeman', '2022-12-13 08:13:21');

-- --------------------------------------------------------

--
-- Table structure for table `data_opd`
--

CREATE TABLE `data_opd` (
  `id` bigint(100) NOT NULL,
  `opd` varchar(500) NOT NULL,
  `id_identity` varchar(20) DEFAULT NULL,
  `created_by` varchar(100) DEFAULT NULL,
  `created_date` timestamp NULL DEFAULT NULL,
  `update_by` varchar(100) DEFAULT NULL,
  `update_date` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `data_opd`
--

INSERT INTO `data_opd` (`id`, `opd`, `id_identity`, `created_by`, `created_date`, `update_by`, `update_date`) VALUES
(1, 'Dinas Komunikasi dan Informatika', '434.213', 'adesulaeman', '2021-11-05 06:07:50', NULL, NULL),
(2, 'Sekertaris Daerah', '434.000', 'adesulaeman', '2021-11-05 09:08:29', NULL, NULL),
(9, 'Badan Perencanaan Pembangunan, Penelitian dan Pengembangan  Daerah', '434.301', 'adesulaeman', '2021-11-05 09:08:29', NULL, NULL),
(10, 'Sekretariat Dewan Perwakilan Rakyat', '434.070', 'adesulaeman', '2021-11-05 09:08:29', NULL, NULL),
(11, 'Inspektorat Daerah', '434.100', 'adesulaeman', '2021-11-05 09:08:29', NULL, NULL),
(12, 'Dinas Pendidikan', '434.201', 'adesulaeman', '2021-11-05 09:08:29', NULL, NULL),
(13, 'Dinas Pemuda, Olahraga, Kebudayaan dan Pariwisata', '434.202', 'adesulaeman', '2021-11-05 09:08:29', NULL, NULL),
(14, 'Dinas Kesehatan dan Keluarga Berencana', '434.203', 'adesulaeman', '2021-11-05 09:08:29', NULL, NULL),
(15, 'RSD dr. Mohammad Zyn', '434.203.100.01', 'adesulaeman', '2021-11-05 09:08:29', NULL, NULL),
(16, 'RSD Ketapang', '434.203.100.02', 'adesulaeman', '2021-11-05 09:08:29', NULL, NULL),
(17, 'UPTD Puskesmas Sreseh', '434.203.200.01', 'adesulaeman', '2021-11-05 09:08:29', NULL, NULL),
(18, 'UPTD Puskesmas Jrengik', '434.203.200.02', 'adesulaeman', '2021-11-05 09:08:29', NULL, NULL),
(19, 'UPTD Puskesmas Torjun', '434.203.200.03', 'adesulaeman', '2021-11-05 09:08:29', NULL, NULL),
(20, 'UPTD Puskesmas Tambelangan', '434.203.200.04', 'adesulaeman', '2021-11-05 09:08:29', NULL, NULL),
(21, 'UPTD Puskesmas Kedungdung', '434.203.200.05', 'adesulaeman', '2021-11-05 09:08:29', NULL, NULL),
(22, 'UPTD Puskesmas Robatal', '434.203.200.06', 'adesulaeman', '2021-11-05 09:08:29', NULL, NULL),
(23, 'UPTD Puskesmas Ketapang', '434.203.200.07', 'adesulaeman', '2021-11-05 09:08:29', NULL, NULL),
(24, 'UPTD Puskesmas Banyuates', '434.203.200.08', 'adesulaeman', '2021-11-05 09:08:29', NULL, NULL),
(25, 'UPTD Puskesmas Bringkoning', '434.203.200.09', 'adesulaeman', '2021-11-05 09:08:29', NULL, NULL),
(26, 'UPTD Puskesmas Batulenger', '434.203.200.10', 'adesulaeman', '2021-11-05 09:08:29', NULL, NULL),
(27, 'UPTD Puskesmas Tamberu Barat', '434.203.200.11', 'adesulaeman', '2021-11-05 09:08:29', NULL, NULL),
(28, 'UPTD Puskesmas Omben', '434.203.200.12', 'adesulaeman', '2021-11-05 09:08:29', NULL, NULL),
(29, 'UPTD Puskesmas Camplong', '434.203.200.13', 'adesulaeman', '2021-11-05 09:08:29', NULL, NULL),
(30, 'UPTD Puskesmas Tanjung', '434.203.200.14', 'adesulaeman', '2021-11-05 09:08:29', NULL, NULL),
(31, 'UPTD Puskesmas Karangpenang', '434.203.200.15', 'adesulaeman', '2021-11-05 09:08:29', NULL, NULL),
(32, 'UPTD Puskesmas Kamoning', '434.203.200.16', 'adesulaeman', '2021-11-05 09:08:29', NULL, NULL),
(33, 'UPTD Puskesmas Banyuanyar', '434.203.200.17', 'adesulaeman', '2021-11-05 09:08:29', NULL, NULL),
(34, 'UPTD Puskesmas Pangarengan', '434.203.200.18', 'adesulaeman', '2021-11-05 09:08:29', NULL, NULL),
(35, 'UPTD Puskesmas Banjar', '434.203.200.19', 'adesulaeman', '2021-11-05 09:08:29', NULL, NULL),
(36, 'UPTD Puskesmas Jrenguan ', '434.203.200.20', 'adesulaeman', '2021-11-05 09:08:29', NULL, NULL),
(37, 'UPTD Puskesmas Bunten Barat', '434.203.200.21', 'adesulaeman', '2021-11-05 09:08:29', NULL, NULL),
(38, 'UPTD Puskesmas Tapaan', '434.203.200.22', 'adesulaeman', '2021-11-05 09:08:29', NULL, NULL),
(39, 'UPTD Puskesmas Pulau Mandangin', '434.203.200.23', 'adesulaeman', '2021-11-05 09:08:29', NULL, NULL),
(40, 'Dinas Sosial, Pemberdayaan Perempuan dan Perlindungan Anak ', '434.204', 'adesulaeman', '2021-11-05 09:08:29', NULL, NULL),
(41, 'Dinas Kependudukan dan Pencatatan Sipil', '434.205', 'adesulaeman', '2021-11-05 09:08:29', NULL, NULL),
(42, 'Dinas Pemberdayaan Masyarakat dan Desa', '434.206', 'adesulaeman', '2021-11-05 09:08:29', NULL, NULL),
(43, 'Dinas Pekerjaan Umum dan Penataan Ruang', '434.207', 'adesulaeman', '2021-11-05 09:08:29', NULL, NULL),
(44, 'Dinas Perumahan Rakyat dan Kawasan Permukiman', '434.208', 'adesulaeman', '2021-11-05 09:08:29', NULL, NULL),
(45, 'Dinas Perhubungan', '434.209', 'adesulaeman', '2021-11-05 09:08:29', NULL, NULL),
(46, 'UPTD Pengujian Kendaraan Bermotor', '434.209.01', 'adesulaeman', '2021-11-05 09:08:29', NULL, NULL),
(47, 'Satuan Polisi Pamong Praja dan Perlindungan Masyarakat', '434.210', 'adesulaeman', '2021-11-05 09:08:29', NULL, NULL),
(48, 'Dinas Penanaman Modal, Pelayanan Terpadu Satu Pintu dan Tenaga Kerja', '434.211', 'adesulaeman', '2021-11-05 09:08:29', NULL, NULL),
(49, 'Dinas Koperasi, Perindustrian dan Perdagangan ', '434.212', 'adesulaeman', '2021-11-05 09:08:29', NULL, NULL),
(50, 'Lembaga Penyiaran Publik Lokali (LPPL) Radio   Suara Sampang', '434.213.01', 'adesulaeman', '2021-11-05 09:08:29', NULL, NULL),
(51, 'Dinas Pertanian dan Ketahanan Pangan ', '434.214', 'adesulaeman', '2021-11-05 09:08:29', NULL, NULL),
(52, 'UPTD Pusat Kesehatan Hewan Kecamatan Sampang', '434.214.01', 'adesulaeman', '2021-11-05 09:08:29', NULL, NULL),
(53, 'UPTD Pusat Kesehatan Hewan Kecamatan Omben', '434.214.02', 'adesulaeman', '2021-11-05 09:08:29', NULL, NULL),
(54, 'UPTD Pusat Kesehatan Hewan Kecamatan Ketapang', '434.214.03', 'adesulaeman', '2021-11-05 09:08:29', NULL, NULL),
(55, 'Dinas Perikanan', '434.215', 'adesulaeman', '2021-11-05 09:08:29', NULL, NULL),
(56, 'Dinas Lingkungan Hidup', '434.216', 'adesulaeman', '2021-11-05 09:08:29', NULL, NULL),
(57, 'UPTD Pengelolaan Sampah ', '434.216.01', 'adesulaeman', '2021-11-05 09:08:29', NULL, NULL),
(58, 'UPTD Laboratorium Lingkungan ', '434.216.02', 'adesulaeman', '2021-11-05 09:08:29', NULL, NULL),
(59, 'Dinas Perpustakaan dan Kearsipan  ', '434.217', 'adesulaeman', '2021-11-05 09:08:29', NULL, NULL),
(60, 'Badan Pendapatan, Pengelolaan Keuangan dan Aset   Daerah', '434.302', 'adesulaeman', '2021-11-05 09:08:29', NULL, NULL),
(61, 'Badan Kepegawaian dan Pengembangan Sumber Daya Manusia ', '434.303', 'adesulaeman', '2021-11-05 09:08:29', NULL, NULL),
(62, 'Badan Kesatuan Bangsa dan Politik', '434.304', 'adesulaeman', '2021-11-05 09:08:29', NULL, NULL),
(63, 'Badan Penanggulangan Bencana Daerah', '434.401', 'adesulaeman', '2021-11-05 09:08:29', NULL, NULL),
(64, 'Kecamatan Sreseh', '434.501', 'adesulaeman', '2021-11-05 09:08:29', NULL, NULL),
(65, 'Kecamatan Torjun', '434.502', 'adesulaeman', '2021-11-05 09:08:29', NULL, NULL),
(66, 'Kecamatan Sampang', '434.503', 'adesulaeman', '2021-11-05 09:08:29', NULL, NULL),
(67, 'Kelurahan Rongtengah', '434.503.13', 'adesulaeman', '2021-11-05 09:08:29', NULL, NULL),
(68, 'Kelurahan Dalpenang', '434.503.14', 'adesulaeman', '2021-11-05 09:08:29', NULL, NULL),
(69, 'Kelurahan Polagan', '434.503.15', 'adesulaeman', '2021-11-05 09:08:29', NULL, NULL),
(70, 'Kelurahan Gunung Sekar', '434.503.16', 'adesulaeman', '2021-11-05 09:08:29', NULL, NULL),
(71, 'Kelurahan Banyuanyar', '434.503.17', 'adesulaeman', '2021-11-05 09:08:29', NULL, NULL),
(72, 'Kelurahan Karang Dalem', '434.503.18', 'adesulaeman', '2021-11-05 09:08:29', NULL, NULL),
(73, 'Kecamatan Omben', '434.505', 'adesulaeman', '2021-11-05 09:08:29', NULL, NULL),
(74, 'Kecamatan Kedungdung', '434.506', 'adesulaeman', '2021-11-05 09:08:29', NULL, NULL),
(75, 'Kecamatan Jrengik', '434.507', 'adesulaeman', '2021-11-05 09:08:29', NULL, NULL),
(76, 'Kecamatan Tambelangan', '434.508', 'adesulaeman', '2021-11-05 09:08:29', NULL, NULL),
(77, 'Kecamatan Banyuates', '434.509', 'adesulaeman', '2021-11-05 09:08:29', NULL, NULL),
(78, 'Kecamatan Robatal', '434.510', 'adesulaeman', '2021-11-05 09:08:29', NULL, NULL),
(79, 'Kecamatan Sokobanah', '434.511', 'adesulaeman', '2021-11-05 09:08:29', NULL, NULL),
(80, 'Kecamatan Ketapang', '434.512', 'adesulaeman', '2021-11-05 09:08:29', NULL, NULL),
(81, 'Kecamatan Pangarengan', '434.513', 'adesulaeman', '2021-11-05 09:08:29', NULL, NULL),
(82, 'Kecamatan Karang Penang', '434.514', 'adesulaeman', '2021-11-05 09:08:29', NULL, NULL),
(83, 'PT. Geliat Sampang Mandiri', '434.602', 'adesulaeman', '2021-11-05 09:08:29', NULL, NULL),
(84, 'PT. Sampang Sarana Shorebase', '434.603.01', 'adesulaeman', '2021-11-05 09:08:29', NULL, NULL),
(85, 'PT. Sampang Mandiri Perkasa', '434.603.02', 'adesulaeman', '2021-11-05 09:08:29', NULL, NULL),
(86, 'PT. Sampang Mandiri Amanah', '434.603.03', 'adesulaeman', '2021-11-05 09:08:29', NULL, NULL),
(87, 'PT. Bakti Artha Sejahtera ', '434.604', 'adesulaeman', '2021-11-05 09:08:29', NULL, NULL),
(88, 'Palang Merah Indonesia (PMI) Kabupaten Sampang ', '434.701', 'adesulaeman', '2021-11-05 09:08:29', NULL, NULL),
(89, 'Komite Olah Raga Nasional (KONI) Kabupaten Sampang', '434.702', 'adesulaeman', '2021-11-05 09:08:29', NULL, NULL),
(90, 'Kwartir Cabang (Kwarcab) Pramuka Kabupaten Sampang', '434.703', 'adesulaeman', '2021-11-05 09:08:29', NULL, NULL),
(91, 'Komunitas Intelijen Daerah (KOMINDA) Kab. Sampang', '434.801', 'adesulaeman', '2021-11-05 09:08:29', NULL, NULL),
(92, 'Badan Penanggulangan Narkotika Kabupaten Sampang', '434.802', 'adesulaeman', '2021-11-05 09:08:29', NULL, NULL),
(93, 'Dewan Pengurus Korpri Kabupaten Sampang', '434.803', 'adesulaeman', '2021-11-05 09:08:29', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `data_tags`
--

CREATE TABLE `data_tags` (
  `id` bigint(20) NOT NULL,
  `tags` varchar(1000) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `data_tags`
--

INSERT INTO `data_tags` (`id`, `tags`) VALUES
(1, 'hdsjakh'),
(2, 'dshakjdas'),
(3, 'dasjhdsad'),
(4, 'asfhjdskfsd'),
(5, 'fhdjskfsd'),
(6, 'fsdhjkfsd'),
(7, 'testing'),
(8, 'data penting'),
(9, 'surat berharga');

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
-- Stand-in structure for view `vw_data_file`
-- (See below for the actual view)
--
CREATE TABLE `vw_data_file` (
`id` bigint(20)
,`file_name` varchar(1000)
,`description` varchar(2000)
,`category` varchar(1000)
,`tags` text
,`created_by` varchar(1000)
,`created_date` timestamp
,`id_user` varchar(1000)
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `vw_data_file_all`
-- (See below for the actual view)
--
CREATE TABLE `vw_data_file_all` (
`id` bigint(20)
,`file_name` mediumtext
,`id_file_name` varchar(1000)
,`id_description` varchar(2000)
,`id_category` varchar(1000)
,`id_tags` varchar(2000)
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
-- Stand-in structure for view `vw_select_category`
-- (See below for the actual view)
--
CREATE TABLE `vw_select_category` (
`id` varchar(1000)
,`text` varchar(1000)
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
-- Stand-in structure for view `vw_select_opd`
-- (See below for the actual view)
--
CREATE TABLE `vw_select_opd` (
`id` varchar(500)
,`text` varchar(500)
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
-- Stand-in structure for view `vw_select_tags`
-- (See below for the actual view)
--
CREATE TABLE `vw_select_tags` (
`id` varchar(1000)
,`text` varchar(1000)
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `vw_select_user`
-- (See below for the actual view)
--
CREATE TABLE `vw_select_user` (
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `vw_user_level`
-- (See below for the actual view)
--
CREATE TABLE `vw_user_level` (
);

-- --------------------------------------------------------

--
-- Structure for view `core_vw_rolemenus`
--
DROP TABLE IF EXISTS `core_vw_rolemenus`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `core_vw_rolemenus`  AS SELECT concat(concat(`core_menus`.`idmodule`,'-'),`core_menus`.`id`) AS `idmenus`, `core_menus`.`idmodule` AS `idmodule`, `core_menus`.`menu` AS `menu`, `core_menus`.`icon` AS `icon`, `core_menus`.`withframe` AS `withframe`, CASE WHEN `core_menus`.`parent` = 0 THEN `core_menus`.`parent` ELSE concat(concat(`core_menus`.`idmodule`,'-'),`core_menus`.`parent`) END AS `parent`, 0 AS `child`, `core_menus`.`links` AS `links`, `rlm`.`idmenu` AS `idmenu`, `rlm`.`iduser` AS `iduser`, `core_menus`.`sub_page` AS `sub_page` FROM (`core_menus` left join `core_rolemenu` `rlm` on(`core_menus`.`id` = `rlm`.`idmenu`)) ;

-- --------------------------------------------------------

--
-- Structure for view `vw_access`
--
DROP TABLE IF EXISTS `vw_access`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `vw_access`  AS SELECT `u`.`id` AS `id`, CASE WHEN `u`.`wrongpass` >= 3 THEN concat('<small class="label bg-red"><i class="fa fa-lock"></i> Lock</small> ',`u`.`userid`) ELSE `u`.`userid` END AS `userid`, `u`.`userpass` AS `userpass`, `u`.`username` AS `username`, `u`.`email` AS `email`, `u`.`firstname` AS `firstname`, `u`.`lastname` AS `lastname`, `u`.`islogin` AS `islogin`, `u`.`isactive` AS `isactive`, `u`.`rolemenu` AS `rolemenu`, `u`.`rolearea` AS `rolearea`, `u`.`createby` AS `createby`, `u`.`createdate` AS `createdate`, `u`.`approvedby` AS `approvedby`, `u`.`approveddate` AS `approveddate`, `u`.`no_handphone` AS `no_handphone`, `gr`.`group_name` AS `id_group_management` FROM (`core_user` `u` left join `core_group` `gr` on(`u`.`id_group_management` = `gr`.`id`)) ;

-- --------------------------------------------------------

--
-- Structure for view `vw_data_file`
--
DROP TABLE IF EXISTS `vw_data_file`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `vw_data_file`  AS SELECT `data_file`.`id` AS `id`, `data_file`.`file_name` AS `file_name`, `data_file`.`description` AS `description`, `data_file`.`category` AS `category`, `labelsplit`(`data_file`.`tags`,'|') AS `tags`, `data_file`.`created_by` AS `created_by`, `data_file`.`created_date` AS `created_date`, `data_file`.`created_by` AS `id_user` FROM `data_file` ;

-- --------------------------------------------------------

--
-- Structure for view `vw_data_file_all`
--
DROP TABLE IF EXISTS `vw_data_file_all`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `vw_data_file_all`  AS SELECT `data_file`.`id` AS `id`, CASE WHEN lcase(`getExt`(`data_file`.`file_name`,'.')) = 'pdf' THEN concat('<img src="assets/img/pdf.png" style="width: 16px;margin-right: 5px;"/> ',`data_file`.`file_name`,' <button style="margin-left:5px" class="btn btn-warning btn-xs showfile" data-id="',`data_file`.`file_name`,'"><i class="fa fa-fw fa-search-plus"></i> show</button><br>Desc : ',`data_file`.`description`,'<br>category : ',`data_file`.`category`,'<br>tags : ',`labelsplit`(`data_file`.`tags`,'|'),'<br>Date : ',`data_file`.`created_date`) WHEN lcase(`getExt`(`data_file`.`file_name`,'.')) in ('xlsx','xls') THEN concat('<img src="assets/img/excel.png" style="width: 16px;margin-right: 5px;"/> ',`data_file`.`file_name`,'<br>Desc : ',`data_file`.`description`,'<br>category : ',`data_file`.`category`,'<br>tags : ',`labelsplit`(`data_file`.`tags`,'|'),'<br>Date : ',`data_file`.`created_date`) WHEN lcase(`getExt`(`data_file`.`file_name`,'.')) in ('docx','doc') THEN concat('<img src="assets/img/word.png" style="width: 16px;margin-right: 5px;"/> ',`data_file`.`file_name`,'<br>Desc : ',`data_file`.`description`,'<br>category : ',`data_file`.`category`,'<br>tags : ',`labelsplit`(`data_file`.`tags`,'|'),'<br>Date : ',`data_file`.`created_date`) WHEN lcase(`getExt`(`data_file`.`file_name`,'.')) in ('png','jpg','gif','jpeg') THEN concat('<img src="assets/img/image.png" style="width: 16px;margin-right: 5px;"/> ',`data_file`.`file_name`,' <button style="margin-left:5px" class="btn btn-warning btn-xs showfile" data-id="',`data_file`.`file_name`,'"><i class="fa fa-fw fa-search-plus"></i> show</button><br>Desc : ',`data_file`.`description`,'<br>category : ',`data_file`.`category`,'<br>tags : ',`labelsplit`(`data_file`.`tags`,'|'),'<br>Date : ',`data_file`.`created_date`) ELSE concat('<img src="assets/img/unkown.png" style="width: 16px;margin-right: 5px;"/> ',`data_file`.`file_name`,'<br>Desc : ',`data_file`.`description`,'<br>category : ',`data_file`.`category`,'<br>tags : ',`labelsplit`(`data_file`.`tags`,'|'),'<br>Date : ',`data_file`.`created_date`) END AS `file_name`, `data_file`.`file_name` AS `id_file_name`, `data_file`.`description` AS `id_description`, `data_file`.`category` AS `id_category`, `data_file`.`tags` AS `id_tags` FROM `data_file` ;

-- --------------------------------------------------------

--
-- Structure for view `vw_group_management`
--
DROP TABLE IF EXISTS `vw_group_management`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `vw_group_management`  AS SELECT `core_group`.`id` AS `id`, `core_group`.`group_name` AS `group_name`, `core_group`.`created_by` AS `created_by`, `core_group`.`created_date` AS `created_date`, `core_group`.`update_by` AS `update_by`, `core_group`.`update_date` AS `update_date` FROM `core_group` ;

-- --------------------------------------------------------

--
-- Structure for view `vw_menu_set_role`
--
DROP TABLE IF EXISTS `vw_menu_set_role`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `vw_menu_set_role`  AS SELECT `core_menus`.`id` AS `id`, `core_menus`.`menu` AS `menu` FROM `core_menus` WHERE `core_menus`.`links` is not null ;

-- --------------------------------------------------------

--
-- Structure for view `vw_select_category`
--
DROP TABLE IF EXISTS `vw_select_category`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `vw_select_category`  AS SELECT `data_category`.`category` AS `id`, `data_category`.`category` AS `text` FROM `data_category` ;

-- --------------------------------------------------------

--
-- Structure for view `vw_select_group`
--
DROP TABLE IF EXISTS `vw_select_group`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `vw_select_group`  AS SELECT `core_group`.`id` AS `id`, `core_group`.`group_name` AS `text` FROM `core_group` ;

-- --------------------------------------------------------

--
-- Structure for view `vw_select_opd`
--
DROP TABLE IF EXISTS `vw_select_opd`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `vw_select_opd`  AS SELECT `data_opd`.`opd` AS `id`, `data_opd`.`opd` AS `text` FROM `data_opd` ;

-- --------------------------------------------------------

--
-- Structure for view `vw_select_roles_jabatan`
--
DROP TABLE IF EXISTS `vw_select_roles_jabatan`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `vw_select_roles_jabatan`  AS SELECT `core_roles_jabatan`.`roles` AS `id`, `core_roles_jabatan`.`roles` AS `text` FROM `core_roles_jabatan` ;

-- --------------------------------------------------------

--
-- Structure for view `vw_select_tags`
--
DROP TABLE IF EXISTS `vw_select_tags`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `vw_select_tags`  AS SELECT `data_tags`.`tags` AS `id`, `data_tags`.`tags` AS `text` FROM `data_tags` ;

-- --------------------------------------------------------

--
-- Structure for view `vw_select_user`
--
DROP TABLE IF EXISTS `vw_select_user`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `vw_select_user`  AS SELECT `u`.`id` AS `id`, concat(`u`.`userid`,' - ',`u`.`username`,' (',`j`.`jabatan`,' ',`j`.`opd`,')') AS `text` FROM (`core_user` `u` left join `data_jabatan` `j` on(`u`.`jabatan` = `j`.`id`)) ;

-- --------------------------------------------------------

--
-- Structure for view `vw_user_level`
--
DROP TABLE IF EXISTS `vw_user_level`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `vw_user_level`  AS SELECT `u`.`id` AS `id`, `l`.`id` AS `level`, CASE WHEN `l`.`id` is null THEN concat('<select data-id="',`u`.`id`,'" class="id-level" style="width:200px"><option selected value="">','Please Select','</option></select>') ELSE concat('<select data-id="',`u`.`id`,'" class="id-level" style="width:200px"><option selected value="',`l`.`id`,'">',`l`.`name_level`,' - ',`l`.`opd`,'</option></select>') END AS `level_id`, `u`.`userid` AS `userid`, `u`.`username` AS `username`, `u`.`roles` AS `roles`, `u`.`jabatan` AS `jabatan`, `u`.`opd` AS `opd` FROM (`core_user` `u` left join `core_level` `l` on(`u`.`id_level` = `l`.`id`)) WHERE `u`.`roles` in ('Admin TTE','Pejabat') ;

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
-- Indexes for table `data_category`
--
ALTER TABLE `data_category`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `data_file`
--
ALTER TABLE `data_file`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `data_opd`
--
ALTER TABLE `data_opd`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `data_tags`
--
ALTER TABLE `data_tags`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `tags` (`tags`) USING HASH;

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `core_fields`
--
ALTER TABLE `core_fields`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=286;

--
-- AUTO_INCREMENT for table `core_forms`
--
ALTER TABLE `core_forms`
  MODIFY `idform` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `core_group`
--
ALTER TABLE `core_group`
  MODIFY `id` bigint(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `core_level`
--
ALTER TABLE `core_level`
  MODIFY `id` bigint(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `core_menus`
--
ALTER TABLE `core_menus`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `core_otp`
--
ALTER TABLE `core_otp`
  MODIFY `id` bigint(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=39;

--
-- AUTO_INCREMENT for table `core_rolemenu`
--
ALTER TABLE `core_rolemenu`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=112;

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
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=45;

--
-- AUTO_INCREMENT for table `data_category`
--
ALTER TABLE `data_category`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `data_file`
--
ALTER TABLE `data_file`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `data_opd`
--
ALTER TABLE `data_opd`
  MODIFY `id` bigint(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=94;

--
-- AUTO_INCREMENT for table `data_tags`
--
ALTER TABLE `data_tags`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
