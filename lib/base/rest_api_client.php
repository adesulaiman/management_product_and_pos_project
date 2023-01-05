<?php
require "../../config.php";
// require "../base/security_login.php";
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


function get_client_ip() {
   $ipaddress = '';
   if (isset($_SERVER['HTTP_CLIENT_IP']))
       $ipaddress = $_SERVER['HTTP_CLIENT_IP'];
   else if(isset($_SERVER['HTTP_X_FORWARDED_FOR']))
       $ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
   else if(isset($_SERVER['HTTP_X_FORWARDED']))
       $ipaddress = $_SERVER['HTTP_X_FORWARDED'];
   else if(isset($_SERVER['HTTP_FORWARDED_FOR']))
       $ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
   else if(isset($_SERVER['HTTP_FORWARDED']))
       $ipaddress = $_SERVER['HTTP_FORWARDED'];
   else if(isset($_SERVER['REMOTE_ADDR']))
       $ipaddress = $_SERVER['REMOTE_ADDR'];
   else
       $ipaddress = 'UNKNOWN';
   return $ipaddress;
}


if(isset($_GET['repo_api']) && isset($_GET['name_web']))
{        
   

   $massage = "";
   $status = "";
   $data = array();
   $ip_client = get_client_ip();
   $webAccess = $_SERVER['SERVER_NAME'];

   $qApi = $adeQ->select($adeQ->prepare(
      "select 
      w.nama_web as nama_web_ori,
      r.nama_api as nama_api_ori,
      concat(replace(w.nama_web, ' ', '-'), w.id) as nama_web,
      concat(replace(r.nama_api, ' ', '-'), r.id) as repo_api,
      r.*,
      d.method_text,
      w.id as id_web_consume,
      w.url_web
      from data_web_consume as w 
      inner join data_repo_api as r on w.id_repo_api=r.id
      inner join data_method_api as d on r.id_method_api=d.id
      where 
      concat(replace(w.nama_web, ' ', '-'), w.id)=%s and
      concat(replace(r.nama_api, ' ', '-'), r.id)=%s
      ", $_GET['name_web'], $_GET['repo_api']
   ));

   header('Access-Control-Allow-Origin: ' . $qApi[0]['url_web']);
   header("Content-Type: application/json; charset=UTF-8");
   header("Access-Control-Max-Age: 3600");
   // header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");


   $qFieldAccess = $adeQ->select($adeQ->prepare(
      "select field_json from data_access_field where id_web_consume=%d", $qApi[0]['id_web_consume']
   ));
   $dbFieldJson = array();
   foreach($qFieldAccess as $dbF){
      array_push($dbFieldJson, $dbF['field_json']);
   }

   $token = $qApi[0]['repo_api'] . $qApi[0]['nama_web'];

   $headers = apache_request_headers();
   // print_r($headers);
   if(isset($headers['Authorization'])){
      try {
         $format = explode(" ", $headers['Authorization']);
         if($format[0] == 'sampang'){
            if($hasher->CheckPassword($token, $format[1])){

               if($qApi[0]['status_active'] == 1){
                  $status = "Success";
                  $massage = "Token Approve";
                  $dataApi = json_decode(curApi($qApi));
   
                  foreach($dataApi->data as $api){
                     foreach($api as $key => $val){
                        if(in_array($key, $dbFieldJson)){
                           $dataArr[$key] = $val;
                        }
                     }
                     $data[] = $dataArr;
                  }
   
                  $msg = json_encode(["status"=>$status,"massage" => $massage,"data" => $data]);
   
                  $qInsLog = $adeQ->query("
                     insert into data_log_api (repo_api, ip_client, web_access, massage, web_consume)
                     values ('".$qApi[0]['nama_api_ori']."', '$ip_client', '$webAccess', '$msg', '".$qApi[0]['nama_web_ori']."')
                  ");
               }else{
                  $status = "Error";
                  $massage = "API Not Active !!";
               }
               

            }else{
               $status = "Error";
               $massage = "Token Failed !!";
            }
         }else{
            $status = "Error";
            $massage = "Wrong Format Authorization !!";
         }
      } catch (Exception $e) {
         $status = "Error";
         $massage = "Authorization failed !!";
      }
      
    }
    
    echo json_encode([
      "status"=>$status,
      "massage" => $massage,
      "data" => $data
      ]);
      
}

?>