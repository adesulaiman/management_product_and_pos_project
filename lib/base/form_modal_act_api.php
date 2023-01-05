<?php
require "../../config.php";
require "../base/security_login.php";
require "../base/db.php";

if(isset($_SESSION['userid']))
{

	if(isset($_GET['id']) && isset($_GET['type']))
	{

        switch($_GET['type']){
            case "testing" :

                $qForm = $adeQ->select($adeQ->prepare(
                    "select * from data_repo_api where id=%d", $_GET['id']
                ));
                
                if($qForm[0]['with_param'] == '1'){
                    $paramEnable = '<div class="col-md-8"><input class="form-control paramApi" type="text" placeholder="Masukan Parameter, contoh : nip=1293012&data=sampang"></div>';
                }else{
                    $paramEnable = '';
                }
        
                $html = '
                <div class="col-md-12">
                    
                        '.$paramEnable .'
                    
                
                    <div class="col-md-4">
                        <button type="button" class="btn btn-success run-test">Test API!</button>
                        <button type="button" class="btn btn-success validasi-format">Validasi Format</button>
                    </div>
                </div>

                <div class="row" style="margin-bottom:20px"></div>
                
                <div class="col-md-12">
                    <div class="nav-tabs-custom">
                        <ul class="nav nav-tabs">
                        <li class="active"><a href="#tab_1" data-toggle="tab">Respon</a></li>
                        <li><a href="#tab_2" data-toggle="tab">Format Standard JSON</a></li>
                        </ul>
                        <div class="tab-content">
                        <div class="tab-pane active" id="tab_1">
                            <pre  class="return-api"></pre >
                        </div>
                        <div class="tab-pane" id="tab_2">
                            <pre>
{
    "status": true,
    "data": [
        {
            "field_1": "value_1",
            "field_2": "value_2",
            "field_3": "value_3",
            "field_4": "value_4"
        }]
}
                            </pre >
                        </div>
                        <!-- /.tab-pane -->
                        </div>
                        <!-- /.tab-content -->
                    </div>
                </div>
                ';

                $data = ["html" => $html, "nameRepo" => $qForm[0]['nama_api'], "urlApi" => $qForm[0]['url_api']];

                echo json_encode(["status" => "success", "data" => $data]);


            break;

            case "getRestAPI" :

                $qForm = $adeQ->select($adeQ->prepare(
                    "select 
                    concat(replace(w.nama_web, ' ', '-'), w.id) as nama_web,
                    concat(replace(r.nama_api, ' ', '-'), r.id) as repo_api,
                    r.with_param
                    from data_web_consume as w 
                    inner join data_repo_api as r on w.id_repo_api=r.id
                    where w.id=%d", $_GET['id']
                ));

                if($qForm[0]['with_param'] == '1'){
                    $param = "/?[param]=[valueparam]";
                }else{
                    $param = '';
                }

                $token = $hasher->HashPassword($qForm[0]['repo_api'] . $qForm[0]['nama_web']);

                $html = '
                <span>URL Rest API : <b>'.$qForm[0]['nama_web'].'</b> untuk Repo Api <b>'.$qForm[0]['repo_api'].'</b></span>
                <pre>'.$dir.'api/'.$qForm[0]['repo_api'].'/'.$qForm[0]['nama_web'].$param.'</pre>
    
                <span>Header Properties</span>
                <pre>Authorization : sampang '.$token.'</pre>
                ';

                echo json_encode(["status" => "success", "html" => $html]);

            break;
        }

       
    };

}

?>


