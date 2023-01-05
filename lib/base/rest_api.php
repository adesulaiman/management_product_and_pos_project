<?php
require "../../config.php";
require "../base/security_login.php";
require "../base/db.php";


function curApi ($qApi){
   if($qApi[0]['header_api'] == null){
      $getHeader = array();
   }else if(preg_match('/~/', $getHeader)){
      $getHeader = explode('~', $qApi[0]['header_api']);
   }else{
      $getHeader = array($qApi[0]['header_api']);
   }

   $url = $qApi[0]['url_api'];
   $method = $qApi[0]['method_text'];
   $param_flag = $qApi[0]['with_param'];

   $data = array();

   if($param_flag == '1'){
      if(isset($_POST['param'])){
         $param = $_POST['param'];
         if(preg_match('/&/', $param)){
            $paramArr = explode("&", $param);
            for ($i=0; $i < count($paramArr); $i++) { 
               if(preg_match('/=/', $paramArr[$i])){
                  $paramAttr = explode("=", $paramArr[$i]);
                  $data[$paramAttr[0]] = $paramAttr[1];
               }
            }
         }else{
            if(preg_match('/=/', $param)){
               $paramAttr = explode("=", $param);
               $data[$paramAttr[0]] = $paramAttr[1];
            }
         }
      }
   }

   $curl = curl_init();
   switch ($method){
      case "POST":
         curl_setopt($curl, CURLOPT_POST, 1);
         if ($data)
            curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
         break;
      case "PUT":
         curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "PUT");
         if ($data)
            curl_setopt($curl, CURLOPT_POSTFIELDS, $data);			 					
         break;
      default:
         if ($data)
            $url = sprintf("%s?%s", $url, http_build_query($data));
   }
   // OPTIONS:
   curl_setopt($curl, CURLOPT_URL, $url);
   curl_setopt($curl, CURLOPT_HTTPHEADER, $getHeader);
   curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
   curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
   // EXECUTE:
   $result = curl_exec($curl);
   if(!$result){die("Connection Failure");}
   curl_close($curl);
   return $result;
}

if(isset($_SESSION['userid']))
{
	if(isset($_POST['id']) && isset($_POST['type']))
  	{        
      $id = $_POST['id'];


         switch($_POST['type']){
            case "test" : 
               
               $qApi = $adeQ->select($adeQ->prepare(
                  "select 
                  a.*, 
                  m.method_text,
                  p.method_text as with_param_text
                  from data_repo_api as a
                  left join data_method_api m on a.id_method_api=m.id
                  left join data_with_param p on a.with_param=p.id where a.id=%d", $id 
               ));
               echo json_encode(curApi($qApi));

            break;

            case "getField" :
               $getIDApi = $adeQ->select($adeQ->prepare(
                  "select * from data_web_consume where id=%d", $id
               ));
               
               $qApi = $adeQ->select($adeQ->prepare(
                  "select 
                  a.*, 
                  m.method_text,
                  p.method_text as with_param_text
                  from data_repo_api as a
                  left join data_method_api m on a.id_method_api=m.id
                  left join data_with_param p on a.with_param=p.id where a.id=%d", $getIDApi[0]['id_repo_api'] 
               ));

               $qFieldAccess = $adeQ->select($adeQ->prepare(
                  "select field_json from data_access_field where id_web_consume=%d", $id
               ));
               $dbFieldJson = array();
               foreach($qFieldAccess as $dbF){
                  array_push($dbFieldJson, $dbF['field_json']);
               }
               
               $dataApi = json_decode(curApi($qApi));
               $field = array();
               $i = 0;
               foreach($dataApi->data as $api){
                  if($i == 0){
                     foreach($api as $key => $val){
                        if(in_array($key, $dbFieldJson)){
                           array_push($field, ["check"=>"1", "field"=>$key]);
                        }else{
                           array_push($field, ["check"=>"0", "field"=>$key]);
                        }
                     }
                  }
                  $i++;
               } 
               echo json_encode($field);
            break;



            case "validasiFormat" :
               $qApi = $adeQ->select($adeQ->prepare(
                  "select 
                  a.*, 
                  m.method_text,
                  p.method_text as with_param_text
                  from data_repo_api as a
                  left join data_method_api m on a.id_method_api=m.id
                  left join data_with_param p on a.with_param=p.id where a.id=%d", $id 
               ));
               $dataApi = json_decode(curApi($qApi));
               
               try {
                  if(is_array($dataApi->data)){
                     if(count($dataApi->data) > 0){
                        echo json_encode(["status"=>"success", "massage"=>"Format Valid", "count"=>count($dataApi->data)]);
                     }else{
                        echo json_encode(["status"=>"error", "massage"=>"Format Not Valid"]);
                     }
                  }else{
                     echo json_encode(["status"=>"error", "massage"=>"Format Not Valid"]);
                  }
               } catch (Exception $e) {
                  echo json_encode(["status"=>"error", "massage"=>"Format Not Valid"]);
               }

               
            break;

            case "saveAccessField" :
               
               $field = $_POST['field'];
               $qDelAccess = $adeQ->query($adeQ->prepare(
                  "delete from data_access_field where id_web_consume=%d", $id
               ));

               $iCount = 0;
               $massage = "";
               $status = "";
               if($qDelAccess){
                  $fieldArr = explode("~", $field);
                  for ($i=0; $i < count($fieldArr); $i++) { 
                     $qIns = $adeQ->query(
                        "insert into data_access_field (id_web_consume, field_json) 
                        values ($id, '".$fieldArr[$i]."')
                        ");
                     if($qIns){
                        $iCount++;
                     }
                  }

                  if($iCount == count($fieldArr)){
                     $status = "success";
                     $massage = "Field Berhasil Di Access";
                  }else{
                     $status = "error";
                     $massage = "Error Insert";
                  }
               }else{
                  $status = "error";
                  $massage = "Error Delete";
               }

               echo json_encode(["status"=>$status, "massage"=>$massage]);
            break;
         }
         
   }

}

?>