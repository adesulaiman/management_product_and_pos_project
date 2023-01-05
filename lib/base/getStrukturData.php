<?php

function getStrukturHirarki($idJabatan, $adeQ)
{

    $loop = true;
    $idStukrur = array();
    $idJStart = $idJabatan == null ? 0  : $idJabatan;
    while ($loop) {
        $get = $adeQ->select("select * from core_rules_level where id_user_level_down in ($idJStart)");
        $idJStart = array();
        if (count($get) > 0) {
            foreach ($get as $dt) {
                $idStukrur[] = $dt['id_user_level_top'];
                $idJStart[] = $dt['id_user_level_top'];
            }
            $idJStart = implode(",", $idJStart);
        } else {
            $loop = false;
        }
    }
    return implode(",", array_unique($idStukrur));
}

function getHirarkiWithLimit($idJabatan, $targetId, $adeQ)
{

    $loop = true;
    $idStukrur = array();
    $idJStart = $idJabatan == null ? 0  : $idJabatan;
    while ($loop) {
        $get = $adeQ->select("select id_user_level_top from core_rules_level where id_user_level_down in ($idJStart)");
        $idJStart = array();

        $findTarget = false;

        if (count($get) > 0) {

            foreach ($get as $dt) {
                if ($targetId == $dt['id_user_level_top']) {
                    $findTarget = true;
                }
            }

            if ($findTarget) {
                foreach ($get as $dt) {
                    if ($targetId == $dt['id_user_level_top']) {
                        $idStukrur[] = $dt['id_user_level_top'];
                        $idJStart[] = $dt['id_user_level_top'];
                        $loop = false;
                    }
                }
            } else {
                foreach ($get as $dt) {
                    $idStukrur[] = $dt['id_user_level_top'];
                    $idJStart[] = $dt['id_user_level_top'];
                }
            }

            $idJStart = implode(",", $idJStart);
        } else {
            $loop = false;
        }
    }
    return implode(",", array_unique($idStukrur));
}
