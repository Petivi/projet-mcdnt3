<?php
// require_once('config.php');
// include "includedFiles.php";

$b1 = "";
$b2 = "";
$b3 = "";
$c1 = "";
$a1 = rand(0,9999);
$a2 = rand(0,9999);
$a3 = rand(0,9999);
$a4 = rand(0,9999);
$a5 = rand(0,9999);
$alphabet = "abcdefghijklmnopqrstuvwxyz=+*@0123456789";
for ($i = 0; $i<15; $i++){
  $b1 .= $alphabet[rand(0, strlen($alphabet)-1)];
  $b2 .= $alphabet[rand(0, strlen($alphabet)-1)];
  $b3 .= $alphabet[rand(0, strlen($alphabet)-1)];
}
for ($i = 0; $i<10; $i++){
  $c1 .= $alphabet[rand(0, strlen($alphabet)-1)];
}

$url = "http://" . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'];
$url2 = "http://" . $_SERVER['SERVER_NAME'];

$pass = $b1 . $b2 . $b3;

// echo $pass;
// echo $c1;
echo $url2;

 ?>
