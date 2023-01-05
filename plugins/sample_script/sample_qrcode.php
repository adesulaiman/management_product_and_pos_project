<?php
require "config.php";
include "plugins/phpqrcode/qrlib.php";

$tempdir = "assets/qrcode/";

$logopath = "assets/img/logo_for_qrcode.png";

$codeContents = 'https://www.facebook.com?v=sjdklajkdl sjakld jsakldj kasljdkasl jdklasjdklsajkdlsjakldjsakljdklsajdklsajdklasjkdlsajklsadjklsjdkla';

$back_color = "#00a859";
$fore_color = "#00a859";
QRcode::png($codeContents, $tempdir . 'qrwithlogo.png', QR_ECLEVEL_H, 4, 4, $back_color, $fore_color);


// ambil file qrcode
$QR = imagecreatefrompng($tempdir . 'qrwithlogo.png');

// memulai menggambar logo dalam file qrcode
$logo = imagecreatefromstring(file_get_contents($logopath));

imagecolortransparent($logo, imagecolorallocatealpha($logo, 0, 0, 0, 127));
imagealphablending($logo, false);
imagesavealpha($logo, true);

$QR_width = imagesx($QR);
$QR_height = imagesy($QR);

$logo_width = imagesx($logo);
$logo_height = imagesy($logo);

// Scale logo to fit in the QR Code
$logo_qr_width = $QR_width / 4;
$scale = $logo_width / $logo_qr_width;
$logo_qr_height = $logo_height / $scale;

imagecopyresampled($QR, $logo, $QR_width / 2.6, $QR_height / 2.8, 0, 0, $logo_qr_width, $logo_qr_height, $logo_width, $logo_height);

// Simpan kode QR lagi, dengan logo di atasnya
imagepng($QR, $tempdir . 'qrwithlogo.png');


?>

<!-- Bootstrap 3.3.6 -->
<link rel="stylesheet" href="<?php echo $dir ?>plugins/bootstrap/css/bootstrap.css">

<div id="container" class="row" style="width:800px">
        <img src="<?php echo  $tempdir . 'qrwithlogo.png' ?>" style="float: left;" />
    <div style="font-size: 25px;margin-top: 9px;float:left">
        Ditandatangani secara elektronik oleh<br>
        Plt. Kepala Dinas Komunikasi dan Informatika<br>
        Kabupaten Sampang<br>
        <br>
        <br>
        <b>AMRIN HIDAYAT, S.Kom.</b>
    </div>
</div>

<button class="click">Click</button>

<script src="<?php echo $dir ?>plugins/jQuery/jquery-2.2.3.min.js"></script>
<script src="plugins/htmltocanvas/htmltocanvas.js?v=1"></script>
<script>
    $(".click").on("click", function(){
        domtoimage.toPng(document.getElementById('container'))
            .then(function(dataUrl) {
                var link = document.createElement('a');
                link.download = 'my-image-name.jpeg';
                link.href = dataUrl;
                link.click();
            });
    })
</script>