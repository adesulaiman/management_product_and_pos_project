<?php
require "../../config.php";
require "../base/security_login.php";
require "../base/db.php";

if (isset($_SESSION['userid']) and isset($_POST['id'])) {
    $id = $_POST['id'];

    $qStatus = $adeQ->select("select u.opd, u.username, concat(j.jabatan, ' ', j.opd) as jabatan, 
    case 
        when f.status = 'Pending Approval' then '<small class=\"label bg-orange\"><i class=\"fa fa-fw fa-hourglass-2\"></i> Pending Approve</small>'
        when f.status = 'Approval' then '<small class=\"label bg-green\"><i class=\"fa fa-check\"></i> Approved</small>'
        when f.status = 'Pending TTE' then '<small class=\"label bg-orange\"><i class=\"fa fa-fw fa-hourglass-2\"></i> Pending TTE</small>'
        when f.status = 'TTE' then '<small class=\"label bg-green\"><i class=\"fa fa-edit\"></i> TTE</small>'
        when f.status = 'Waiting Approval' then '<small class=\"label bg-red\"><i class=\"fa fa-close\"></i> Waiting Approve</small>'
        when f.status = 'Waiting TTE' then '<small class=\"label bg-red\"><i class=\"fa fa-close\"></i> Waiting TTE</small>'
    else ''
    end status
     from data_dokumen_forward_tte f
    left join core_user u on f.id_user=u.id
    left join data_jabatan j on u.jabatan = j.id
    where f.id_flow_dokumen=$id
    order by f.level_id asc
    ");

    $table = "<table class='table table-hover'>";

    $table .= "<thead>";
    $table .= "<tr>";
    $table .= "<th>Status</th> <th>OPD</th> <th>Nama</th> <th>Jabatan</th>";
    $table .= "</tr>";
    $table .= "<tbody>";

    foreach ($qStatus as $status) {
        $table .= "<tr>";
        $table .= "<td>$status[status]</td>";
        $table .= "<td>$status[opd]</td>";
        $table .= "<td>$status[username]</td>";
        $table .= "<td>$status[jabatan]</td>";
        $table .= "</tr>";
    }

    $table .= "</tbody>";
    $table .= "</thead>";
    $table .= "</table>";

    echo $table;
}
