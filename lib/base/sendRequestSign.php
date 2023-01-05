<?php
require "../../config.php";
require "../base/security_login.php";
require "../base/db.php";
require "../base/setQr_kop_PDF.php";
require "../base/wa_api.php";
require_once "../base/enc.php";

if (isset($_SESSION['userid']) and isset($_POST['id'])) {

    $encRypted = new EnDecryptText();


    //cek dokumen
    $id = $_POST['id'];
    $query = "select d.*, l.level_id as level_id_hirarki, j.id as id_user_send_jabatan from data_dokumen_tte d 
    left join core_user u on d.id_user_send_hirarki=u.id
    left join data_jabatan j on u.jabatan=j.id
    left join core_level l on u.id_level=l.id  where d.id=$id";

    $updateSendTTE = "update data_dokumen_tte set tanggal_kirim_tte=now() where id=$id";
    $updTTE = $adeQ->query($updateSendTTE);
    if (!$updTTE) {
        echo json_encode(["status" => "error", "msg" => "Error DB Update !!"]);
        exit;
    }

    $data = $adeQ->select($query);
    if (count($data) == 1) {
        $tipeSend = $data[0]['tipe_send_dokumen'];
        $opd = $data[0]['opd'];
        $level_id_hirarki = $data[0]['level_id_hirarki'];
        $id_user_send_jabatan = $data[0]['id_user_send_jabatan'];
        $id_user_send_hirarki = $data[0]['id_user_send_hirarki'];

        $jenis_dokumen = $data[0]['jenis_dokumen'];
        $nomor = $data[0]['nomor'];
        $asal_dokumen = $data[0]['asal_dokumen'];
        $perihal = $data[0]['perihal'];
        $tanggal = date("d F Y", strtotime($data[0]['tanggal']));


        if (isset($_POST['pdfSource']) and isset($_POST['QRCoor'])) {
            $dataCoor = json_decode($_POST['QRCoor']);
            $pdfSourceSplit = explode("<br>", $_POST['pdfSource']);
            $countPdf = count($pdfSourceSplit);
            $statusSuccess = 0;


            $QRPDF = new setQR_kop_PDF();

            foreach($pdfSourceSplit as $pdf){
                $QRPDF->setPathPDFSource("../../assets/pdffiles/" . $pdf);
                $QRPDF->setQRSetPage($dataCoor->$pdf);
                $QRPDF->setPathPDFOutput("../../assets/pdffiles/" . $pdf);
                if($QRPDF->combinePDFwithQR()){
                    $statusSuccess++;
                }    
            }

            if ($statusSuccess == $countPdf) {
                if ($tipeSend == 'hirarki') {

                    //hirarki in scope internal structural level
                    require "getStrukturData.php";
                    $dataHirarki = getHirarkiWithLimit($_SESSION['id_jabatan'], $id_user_send_jabatan, $adeQ);

                    $qLevelParallel = "
                    select u.id, u.username, l.level_id, u.no_handphone from core_user u
                    inner join data_jabatan j on u.jabatan=j.id
                    left join core_level l on j.id_level=l.id
                    where j.id in ($dataHirarki)
                    and j.status_jabatan = 'Sudah Di Jabat'
                    order by l.level_id asc";

                    $getStruct = $adeQ->select($qLevelParallel);

                    $maxCount = count($getStruct);
                    $i = 1;
                    $ins = array();
                    $levelMinim = 0;
                    foreach ($getStruct as $dt) {

                        $uniqID = uniqid();
                        $insTemp = array();
                        if ($i == 1) {
                            $levelMinim = $dt['level_id'];

                            if ($i == $maxCount) {
                                $insTemp["id_flow_dokumen"] = $id;
                                $insTemp["tanggal_terima_dokumen"] = "now()";
                                $insTemp["id_user"] = $dt['id'];
                                $insTemp["status"] = "'Pending TTE'";

                                $encrypt = $encRypted->Encrypt_Text($uniqID);
                                $link = $dir . "apptte.php?enc=" . $encrypt;

                                sendWA($dt['no_handphone'], "Berikut adalah permintaan *Tanda Tangan Elektronik* untuk dokumen dengan keterangan sebagai berikut :\n\nJenis Dokumen : *$jenis_dokumen*\nNomor : *$nomor*\nAsal Dokumen : *$asal_dokumen*\nPerihal : *$perihal*\nTanggal : *$tanggal*\n\nclick link \n$link\nUntuk proses dokumen !!\n\n:::TTE Sampang Hebat Bermartabat:::");
                            } else {
                                $insTemp["id_flow_dokumen"] = $id;
                                $insTemp["tanggal_terima_dokumen"] = "now()";
                                $insTemp["id_user"] = $dt['id'];
                                $insTemp["status"] = "'Pending Approval'";

                                $encrypt = $encRypted->Encrypt_Text($uniqID);
                                $link = $dir . "apptte.php?enc=" . $encrypt;

                                sendWA($dt['no_handphone'], "Berikut adalah permintaan *Approval* untuk dokumen dengan keterangan sebagai berikut :\n\nJenis Dokumen : *$jenis_dokumen*\nNomor : *$nomor*\nAsal Dokumen : *$asal_dokumen*\nPerihal : *$perihal*\nTanggal : *$tanggal*\n\nclick link \n$link\nUntuk proses dokumen !!  \n\n:::TTE Sampang Hebat Bermartabat:::");
                            }
                        } else if ($i == $maxCount) {
                            $insTemp["id_flow_dokumen"] = $id;
                            $insTemp["tanggal_terima_dokumen"] = "NULL";
                            $insTemp["id_user"] = $dt['id'];
                            $insTemp["status"] = "'Waiting TTE'";
                        } else {

                            if ($levelMinim == $dt['level_id']) {
                                $insTemp["id_flow_dokumen"] = $id;
                                $insTemp["tanggal_terima_dokumen"] = "now()";
                                $insTemp["id_user"] = $dt['id'];
                                $insTemp["status"] = "'Pending Approval'";

                                $encrypt = $encRypted->Encrypt_Text($uniqID);
                                $link = $dir . "apptte.php?enc=" . $encrypt;

                                sendWA($dt['no_handphone'], "Berikut adalah dokumen yang harus anda approve :\n\nJenis Dokumen : *$jenis_dokumen*\nNomor : *$nomor*\nAsal Dokumen : *$asal_dokumen*\nPerihal : *$perihal*\nTanggal : *$tanggal*\n\nclick link \n$link\nUntuk proses dokumen !!\n\n:::TTE Sampang Hebat Bermartabat:::");
                            } else {
                                $insTemp["id_flow_dokumen"] = $id;
                                $insTemp["tanggal_terima_dokumen"] = "NULL";
                                $insTemp["id_user"] = $dt['id'];
                                $insTemp["status"] = "'Waiting Approval'";
                            }
                        }

                        $insTemp["level_id"] = $dt['level_id'];
                        $insTemp["created_by"] = "'" . $_SESSION['userid'] . "'";
                        $insTemp["created_date"] = "now()";
                        $insTemp["uniq_id"] = "'$uniqID'";

                        $ins[] = $insTemp;
                        $i++;
                    }

                    $dtIns = array();
                    for ($i = 0; $i < count($ins); $i++) {
                        $col = array();
                        $dtTemp = array();
                        foreach ($ins[$i] as $column => $value) {
                            $col[] = $column;
                            $dtTemp[] = $value;
                        }
                        $dtIns[] = "(" . implode(",", $dtTemp) . ")";
                    }


                    $queryInsert = "insert into data_dokumen_forward_tte (" . implode(",", $col) . ") values " . implode(",", $dtIns);
                    if ($adeQ->query($queryInsert)) {
                        echo json_encode(["status" => "success", "msg" => "Permintaan TTE sudah di kirim !!"]);
                    } else {
                        echo json_encode(["status" => "error", "msg" => "Error insert db !!"]);
                        exit;
                    }
                } else if ($tipeSend == 'parallel') {
                    $id_parallel_level = $data[0]['id_parallel_level'];


                    //send parallel with structural dynamic 
                    $getStruc = "select lc.*, r.level_id, u.no_handphone from data_level_custom lc
                    left join data_level_reference_custom r on lc.id_level=r.id
                    left join core_user u on lc.id_user=u.id
                    where id_template=$id_parallel_level order by r.level_id asc";
                    $qGetStruc = $adeQ->select($getStruc);

                    $ins = array();
                    $i = 1;
                    foreach ($qGetStruc as $dt) {
                        $uniqID = uniqid();

                        $insTemp = array();
                        if ($i == 1) {
                            $levelMinim = $dt['level_id'];

                            $insTemp["id_flow_dokumen"] = $id;
                            $insTemp["tanggal_terima_dokumen"] = "now()";
                            $insTemp["id_user"] = $dt['id_user'];

                            if ($dt['action'] == 'Approval') {
                                $insTemp["status"] = "'Pending Approval'";

                                $encrypt = $encRypted->Encrypt_Text($uniqID);
                                $link = $dir . "apptte.php?enc=" . $encrypt;
                                sendWA($dt['no_handphone'], "Berikut adalah dokumen yang harus anda approve :\n\nJenis Dokumen : *$jenis_dokumen*\nNomor : *$nomor*\nAsal Dokumen : *$asal_dokumen*\nPerihal : *$perihal*\nTanggal : *$tanggal*\n\nclick link \n$link\nUntuk proses dokumen !!\n:::TTE Sampang Hebat Bermartabat:::");
                            } else if ($dt['action'] == 'Tanda Tangan Elektronik') {
                                $insTemp["status"] = "'Pending TTE'";

                                $encrypt = $encRypted->Encrypt_Text($uniqID);
                                $link = $dir . "apptte.php?enc=" . $encrypt;
                                sendWA($dt['no_handphone'], "Berikut adalah dokumen yang harus anda tanda tangani :\n\nJenis Dokumen : *$jenis_dokumen*\nNomor : *$nomor*\nAsal Dokumen : *$asal_dokumen*\nPerihal : *$perihal*\nTanggal : *$tanggal*\n\nclick link \n$link\nUntuk proses dokumen !!\n:::TTE Sampang Hebat Bermartabat:::");
                            }
                        } else {
                            $insTemp["id_flow_dokumen"] = $id;
                            $insTemp["tanggal_terima_dokumen"] = "NULL";
                            $insTemp["id_user"] = $dt['id_user'];
                            if ($dt['action'] == 'Approval') {

                                if ($dt['level_id'] == $levelMinim) {
                                    $insTemp["status"] = "'Pending Approval'";

                                    $encrypt = $encRypted->Encrypt_Text($uniqID);
                                    $link = $dir . "apptte.php?enc=" . $encrypt;
                                    sendWA($dt['no_handphone'], "Berikut adalah dokumen yang harus anda approve :\n\nJenis Dokumen : *$jenis_dokumen*\nNomor : *$nomor*\nAsal Dokumen : *$asal_dokumen*\nPerihal : *$perihal*\nTanggal : *$tanggal*\n\nclick link \n$link\nUntuk proses dokumen !!\n:::TTE Sampang Hebat Bermartabat:::");
                                } else {
                                    $insTemp["status"] = "'Waiting Approval'";
                                }
                            } else if ($dt['action'] == 'Tanda Tangan Elektronik') {
                                if ($dt['level_id'] == $levelMinim) {
                                    $insTemp["status"] = "'Pending TTE'";

                                    $encrypt = $encRypted->Encrypt_Text($uniqID);
                                    $link = $dir . "apptte.php?enc=" . $encrypt;
                                    sendWA($dt['no_handphone'], "Berikut adalah dokumen yang harus anda tanda tangani :\n\nJenis Dokumen : *$jenis_dokumen*\nNomor : *$nomor*\nAsal Dokumen : *$asal_dokumen*\nPerihal : *$perihal*\nTanggal : *$tanggal*\n\nclick link \n$link\nUntuk proses dokumen !!\n:::TTE Sampang Hebat Bermartabat:::");
                                } else {
                                    $insTemp["status"] = "'Waiting TTE'";
                                }
                            }
                        }

                        $insTemp["level_id"] = $dt['level_id'];
                        $insTemp["created_by"] = "'" . $_SESSION['userid'] . "'";
                        $insTemp["created_date"] = "now()";
                        $insTemp["uniq_id"] = "'$uniqID'";

                        $ins[] = $insTemp;
                        $i++;
                    }

                    $dtIns = array();
                    for ($i = 0; $i < count($ins); $i++) {
                        $col = array();
                        $dtTemp = array();
                        foreach ($ins[$i] as $column => $value) {
                            $col[] = $column;
                            $dtTemp[] = $value;
                        }
                        $dtIns[] = "(" . implode(",", $dtTemp) . ")";
                    }


                    $queryInsert = "insert into data_dokumen_forward_tte (" . implode(",", $col) . ") values " . implode(",", $dtIns);

                    if ($adeQ->query($queryInsert)) {
                        echo json_encode(["status" => "success", "msg" => "Permintaan TTE sudah di kirim !!"]);
                    } else {
                        echo json_encode(["status" => "error", "msg" => "Error insert db !!"]);
                        exit;
                    }
                } else if ($tipeSend == 'direct') {

                    $id_user_send_hirarki = $data[0]['id_user_send_hirarki'];
                    //hirarki in scope internal structural level

                    $qLevelParallel = "select u.id, u.username, u.no_handphone from core_user u where id=$id_user_send_hirarki ";

                    $getStruct = $adeQ->select($qLevelParallel);

                    $i = 1;
                    $ins = array();
                    foreach ($getStruct as $dt) {
                        $uniqID = uniqid();

                        $insTemp = array();
                        $insTemp["id_flow_dokumen"] = $id;
                        $insTemp["tanggal_terima_dokumen"] = "now()";
                        $insTemp["id_user"] = $dt['id'];
                        $insTemp["status"] = "'Pending TTE'";

                        $insTemp["level_id"] = "NULL";
                        $insTemp["created_by"] = "'" . $_SESSION['userid'] . "'";
                        $insTemp["created_date"] = "now()";
                        $insTemp["uniq_id"] = "'$uniqID'";

                        $encrypt = $encRypted->Encrypt_Text($uniqID);
                        $link = $dir . "apptte.php?enc=" . $encrypt;
                        sendWA($dt['no_handphone'], "Berikut adalah dokumen yang harus anda tanda tangani :\n\nJenis Dokumen : *$jenis_dokumen*\nNomor : *$nomor*\nAsal Dokumen : *$asal_dokumen*\nPerihal : *$perihal*\nTanggal : *$tanggal*\n\nclick link \n$link\nUntuk proses dokumen !!\n:::TTE Sampang Hebat Bermartabat:::");

                        $ins[] = $insTemp;
                        $i++;
                    }

                    $dtIns = array();
                    for ($i = 0; $i < count($ins); $i++) {
                        $col = array();
                        $dtTemp = array();
                        foreach ($ins[$i] as $column => $value) {
                            $col[] = $column;
                            $dtTemp[] = $value;
                        }
                        $dtIns[] = "(" . implode(",", $dtTemp) . ")";
                    }


                    $queryInsert = "insert into data_dokumen_forward_tte (" . implode(",", $col) . ") values " . implode(",", $dtIns);

                    if ($adeQ->query($queryInsert)) {
                        echo json_encode(["status" => "success", "msg" => "Permintaan TTE sudah di kirim !!"]);
                    } else {
                        echo json_encode(["status" => "error", "msg" => "Error insert db !!"]);
                        exit;
                    }
                } else {
                    echo json_encode(["status" => "error", "msg" => "Tipe Send Document Not Found !!"]);
                    exit;
                }
            } else {
                echo json_encode(["status" => "error", "msg" => "Error embeded QR Code to PDF !!"]);
                exit;
            }
        } else {
            echo json_encode(["status" => "error", "msg" => "PDF and Coordinate QR Not FOund !!"]);
            exit;
        }
    } else {
        echo json_encode(["status" => "error", "msg" => "Document not found !!"]);
        exit;
    }
}
