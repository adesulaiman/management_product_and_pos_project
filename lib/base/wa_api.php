<?php

function sendWA($no_handphone, $massage)
{

    require_once "enc.php";

    $enc = new EnDecryptText();
    $nohp = $enc->Decrypt_Text($no_handphone);

    $curl = curl_init();
    curl_setopt_array($curl, array(
        CURLOPT_URL => "https://md.fonnte.com/api/send_message.php",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "POST",
        CURLOPT_POSTFIELDS => array(
            'phone' => $nohp,
            'type' => 'text',
            'text' => $massage,
            'delay' => '1',
            'schedule' => '0'
        ),
        CURLOPT_HTTPHEADER => array(
            "Authorization: gjtsFLZij8zWA29ykQ5x"
        ),
    ));
    $response = curl_exec($curl);
    curl_close($curl);
    $status = json_decode($response);
    if($status->status){
        return true;
    }else{
        return false;
    }
}
