<?php
$data = array();
$curl = curl_init();
curl_setopt($curl, CURLOPT_POST, 1);
$data['dokumen'] = new CurlFile('assets/upload/61739684ac9eb_BAB II.pdf', '');
$data['keterangan'] = 'tolong di kerjakan';
$data['userid'] = 'adesulaeman';
$url = 'https://tte.sampangkab.go.id/lib/api/api_dokumen.php';

curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
curl_setopt($curl, CURLOPT_URL, $url);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
// EXECUTE:
$result = curl_exec($curl);
if (!$result) {
    die("Connection Failure");
}
curl_close($curl);
echo $result;
?>
