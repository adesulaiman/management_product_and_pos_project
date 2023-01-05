<?php
require "../../config.php";
require "security_login.php";
require "db.php";

$filename = $_FILES['file']['name'];
$ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
$status = $_POST['status'];

switch($status){
    case "new" :
        $namaFile = $_POST['namaFile'];
        $deskripsi = $_POST['deskripsi'];
        $group = $_POST['group'];


        $cekhack = explode('.', $filename);
            
        $cekExt = array("php","PHP", "js", "JS");
        $Validate = 0;
        $hack = 0;
        for($l=1 ; $l < count($cekhack) ; $l++){
            for($i=0; $i < count($cekExt) ; $i++){
                $pos = strpos( $cekhack[$l], $cekExt[$i] );
                if($pos === false){
                    
                    }else{
                        $Validate += 1;
                    }
            }
        }

        if ($Validate > 0){
            $hack = 1;
        }

        $nama_file =  uniqid() . '_' . str_replace(" ", "_", $_FILES['file']['name']);
        $filepath = sprintf("../../assets/upload/%s" , $nama_file);

        if($hack == 0){
            
            if (!move_uploaded_file($_FILES['file']['tmp_name'],$filepath)){
                echo json_encode(array("status" => "error", "massage" =>"Gagal Upload"));
            }else{
                // chmod($filepath,0777);
                $data = explode("," , $group);
                $cek = 0;
                for($i=0; $i < count($data); $i++){
                    $filepathCopy = sprintf("../../assets/upload/%s" , "$data[$i]_$nama_file");
                    $date = date("Y-m-d H:i:s");
                    $ins = $adeQ->query("insert into data_file_store 
                        (id_user, name_file, send_group_id, date_created, id_user_created, deskripsi, link_file) values
                        ($_SESSION[userUniqId], '$namaFile', $data[$i], '$date', $_SESSION[userUniqId], '$deskripsi', '$data[$i]_$nama_file')
                    ");
                    if($ins)
                    {
                        $cek++;
                        copy( $filepath, $filepathCopy );
                    }
                }

                if($cek == count($data)){
                    unlink($filepath);
                    echo json_encode(array("status" => "success", "massage" =>"File Berhasil Di Kirim"));
                }else{
                    echo json_encode(array("status" => "error", "massage" =>"File Gagal Di Kirim"));
                }
                
            }
            
        }

    break;



    case "edit" :

        $namaFile = $_POST['namaFile'];
        $deskripsi = $_POST['deskripsi'];
        $idFile = $_POST['idFile'];


        $cekhack = explode('.', $filename);
            
        $cekExt = array("php","PHP", "js", "JS");
        $Validate = 0;
        $hack = 0;
        for($l=1 ; $l < count($cekhack) ; $l++){
            for($i=0; $i < count($cekExt) ; $i++){
                $pos = strpos( $cekhack[$l], $cekExt[$i] );
                if($pos === false){
                    
                    }else{
                        $Validate += 1;
                    }
            }
        }

        if ($Validate > 0){
            $hack = 1;
        }

        $nama_file =  uniqid() . '_' . str_replace(" ", "_", $_FILES['file']['name']);
        $filepath = sprintf("../../assets/upload/%s" , $nama_file);

        if($hack == 0){
            
            //cek old file and delete
            $qOld = $adeQ->select($adeQ->prepare("select * from data_file_store where id=%d", $idFile));
            foreach($qOld as $old){
                $fileOld = $old['link_file'];
                $id_user = $old['id_user'];
            }
            $id_user = (empty($id_user)) ? 'not_found' : $id_user;

            if($namaFile != null){

                if($id_user == $_SESSION['userUniqId']){

                    if (!move_uploaded_file($_FILES['file']['tmp_name'],$filepath)){
                        echo json_encode(array("status" => "error", "massage" =>"Gagal memindahkan data !"));
                    }else{
                        // chmod($filepath,0777);
        
                        $date = date("Y-m-d H:i:s");
                        $update = $adeQ->query($adeQ->prepare(
                            "update data_file_store set 
                                name_file = %s,  
                                date_updated = %s, 
                                id_user_updated = %d, 
                                deskripsi = %s, 
                                link_file = %s
                                where id=%d
                            ", $namaFile, $date, $_SESSION['userUniqId'], $deskripsi, $nama_file, $idFile
                        ));
                        if($update)
                        {
                            unlink("../../assets/upload/" . $fileOld);
                            echo json_encode(array("status" => "success", "massage" =>"Data berhasil di perbarui !"));
                        }else{
                            echo json_encode(array("status" => "error", "massage" =>"Error System !"));
                        }
                        
                    }
    
                }else{
                    echo json_encode(array("status" => "error", "massage" =>"Gagal Upload, Silakan Di Coba Kembali !"));
                }
            }else{
                echo json_encode(array("status" => "error", "massage" =>"Mohon Isi Data Dengan Benar !"));
            }


            

            
            
        }

    break;
}

?>