<?php

// access to angular response
header("Access-Control-Allow-Credentials: true"); 
 header('Content-type: application/json');
 header("Access-Control-Allow-Origin: ".((isset($_SERVER['HTTP_ORIGIN'])) ?                
 $_SERVER['HTTP_ORIGIN'] : "*"));
 header('Access-Control-Allow-Headers: X-Requested-With, content-type,           
 access-control-allow-origin, access-control-allow-methods, access-control- 
 allow-headers');
// convert response from angular
$postRequest = file_get_contents("php://input");
$request = json_decode($postRequest);

 ?>
