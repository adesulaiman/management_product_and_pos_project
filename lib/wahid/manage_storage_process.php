<?php

    require "../../config.php";
    require "../base/db.php";
    require "../base/security_login.php";


    define('HOST',$dbHost);
    define('USER',$dbUser);
    define('PASS',$dbPassword);
    define('DB1', $dbName);
  
    $connect = new mysqli(HOST, USER, PASS, DB1);

    if(isset($_POST["action"])) {
        $action = $_POST["action"];

        switch ($action) {

            case 'add':
                $userId = $_SESSION["userid"];

                $output = ''; 
                $strName =  mysqli_real_escape_string($connect, $_POST["strName"]); 

                $query = "INSERT INTO data_category_storage VALUES ('', '$strName', '1', '$userId', now())";

                if(mysqli_query($connect, $query))
                {
                    $fl = 'success';
                    $status = 'Storage successfully added';
                } else {
                    $fl = 'error';
                    $status = $output .= mysqli_error($connect);
                }
                
                echo $output;
                echo json_encode(['status' => $fl, 'text' => $status, 'page' => '']);
                break;
            
            case 'statusUpdt':

                $output = '';
                $id =  mysqli_real_escape_string($connect, $_POST["id"]); 

                $query = "UPDATE data_category_storage SET is_active='0' WHERE id=$id";

                if(mysqli_query($connect, $query))
                {
                    $fl = 'success';
                    $status = 'Storage deactivated successfully';
                } else {
                    $fl = 'error';
                    $status = $output .= mysqli_error($connect);
                }
                
                echo $output;
                echo json_encode(['status' => $fl, 'text' => $status, 'page' => '']);
                break;

        }


  }



?>