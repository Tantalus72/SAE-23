<?php
session_start();

// Générer un code aléatoire
$code = substr(str_shuffle("ABCDEFGHJKLMNPQRSTUVWXYZ23456789"), 0, 5);
$_SESSION['code'] = $code;
$code = $code.$_GET["rand"];
// Créer l'image
$image = imagecreatetruecolor(150, 50);
$bg = imagecolorallocate($image, 255, 255, 255);
imagefilledrectangle($image, 0, 0, 150, 50, $bg);

// Chemin absolu de la police
$font = 'ressources/fonts/arial.ttf';

// Ajouter du bruit
for($i=0; $i<5; $i++) {
    $color = imagecolorallocate($image, rand(0,255), rand(0,255), rand(0,255));
    imageline($image, rand(0,150), rand(0,50), rand(0,150), rand(0,50), $color);
}

// Écrire le texte
$textColor = imagecolorallocate($image, 0, 0, 0);
imagettftext($image, 20, rand(-10,10), 20, 35, $textColor, $font, $code);

// Envoyer l'image
header('Content-Type: image/png');
imagepng($image);
imagedestroy($image);