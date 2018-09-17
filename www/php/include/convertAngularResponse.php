<?php

// access to angular response
header("Access-Control-Allow-Origin: *");
// convert response from angular
$postRequest = file_get_contents("php://input");
$request = json_decode($postRequest);

 ?>
