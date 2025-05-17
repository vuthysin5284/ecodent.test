<?php
$data = $_GET['cid'];
$filename = $_GET['file'];
$size = '512x512';
$logo = '../assets/img/favicon/qr-logo.png';
header("Cache-Control: public");
header("Content-Description: FIle Transfer");
header("Content-Disposition: attachment; filename=".$filename.".png");
header("Content-Type: image/png");
header("Content-Transfer-Emcoding: binary");
$QR = imagecreatefrompng('https://qrcode.tec-it.com/API/QRCode?data='.urlencode('CUS'.$data));
imagepng($QR);
imagedestroy($QR);
?>