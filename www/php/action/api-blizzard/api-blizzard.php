<?php
require_once('../../config.php');
include "../../includedFiles.php";
$curl = curl_init();
curl_setopt_array($curl, array(
    CURLOPT_RETURNTRANSFER => 1,
    CURLOPT_URL => 'https://eu.battle.net/oauth/token?grant_type=client_credentials&client_id='.$wow_api_client_id.'&client_secret='.$wow_api_client_secret,
));
$resp_token = json_decode(curl_exec($curl), true);
if(isset($resp_token['access_token'])){
  $access_token = $resp_token['access_token']; // token

  if(isset($request->url_missing)){
    $url_missing = htmlspecialchars($request->url_missing, ENT_QUOTES);
  }else{
    $url_missing = "";
  }
  if(isset($request->tabParam)){
    $tabParam = $request->tabParam;
  }else{
    $tabParam = [];
  }


  $url_redirect = 'https://eu.api.blizzard.com/wow/'.$url_missing.'?access_token='.$access_token;
  if(isset($tabParam)){
    if(sizeof($tabParam) > 0){
      foreach ($tabParam as &$param) {
          $url_redirect .= '&'.$param->key.'='.$param->value;
      }
      unset($param); // Kill ref on last element
    }
  }
  curl_setopt_array($curl, array(
    CURLOPT_RETURNTRANSFER => 1,
    CURLOPT_URL => $url_redirect, // url for request
  ));
  // $resp_request = json_decode(curl_exec($curl), true); // content of the request --> display on php side
  // var_dump($resp_request); --> display on php side
  $resp_request = curl_exec($curl); // content of the request --> display on front sid
  echo $resp_request; // --> display on front side
  curl_close($curl);
}else {
  echo returnError($display_error_empty);
  exit();
}



 ?>
