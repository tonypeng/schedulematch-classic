<?php
session_start();

$img = imagecreatetruecolor(300, 35);

$white = imagecolorallocate($img, 255, 255, 255);
$black = imagecolorallocate($img, 0, 0, 0);
$grey = imagecolorallocate($img,150,150,150);
$red = imagecolorallocate($img, 255, 0, 0);

$colors = array($black, $grey, $red);

imagefill($img, 0, 0, $white);

function randStr()
{
    $str = '';
    $len = rand(10, 12);
    
    
    $characters = 'abcdefghijklmnopqrstuvwxyz0123456789';
    srand(time());
    
    for($i = 0 ; $i < $len; $i++)
    {
        $index = rand() % strlen($characters);
        
        $str .= $characters[$index];
    }
    
    return $str;
}

$text = randStr();
$_SESSION['key'] = $text;

imagettftext($img, 24, rand(-2, 2), rand(0, 10), 25, $colors[rand() % 3], "goodtime.ttf", $text);

header("Content-type: image/png");
imagepng($img);
imagedestroy($img);

?>