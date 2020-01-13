<?php
require_once('../../config.php');
include "../../includedFiles.php";

ini_set('max_execution_time', 0);
$date_debut = strtotime(date('d-m-Y H:i:s'));

$total_items_counted=0;
$curl = curl_init();
curl_setopt_array($curl, array(
    CURLOPT_RETURNTRANSFER => 1,
    CURLOPT_URL => 'https://eu.battle.net/oauth/token?grant_type=client_credentials&client_id='.$wow_api_client_id.'&client_secret='.$wow_api_client_secret,
));
$resp_token = json_decode(curl_exec($curl), true);


$request_items_info = 'SELECT * FROM items_list WHERE checked LIKE 0';
$request_items_info = $base->prepare($request_items_info);
$request_items_info->execute();
while($items_info = $request_items_info->fetch())
{
  $total_items_counted++;
  $item_table_id = $items_info['id'];
  $item_id = $items_info['item_id'];


  if(isset($resp_token['access_token'])){ // if token
    $access_token = $resp_token['access_token']; // token


    $url_redirect = 'https://eu.api.blizzard.com/wow/item/'.$item_id.'?locale=fr_EU&access_token='.$access_token;
    curl_setopt_array($curl, array(
      CURLOPT_RETURNTRANSFER => 1,
      CURLOPT_URL => $url_redirect, // url for request
    ));
    $resp_request = json_decode(curl_exec($curl), true); // content of the request --> display on php side
    // var_dump($resp_request); // --> display on php side
    if(isset($resp_request['name'])){
      $item_name = htmlentities($resp_request['name'], ENT_QUOTES, "UTF-8");
    }else {
      $item_name = NULL;
    }

    if(isset($item_name)){
      $update_items_info = 'UPDATE items_list
      SET checked = 1,
      item_name_fr = :item_name
      WHERE id LIKE :id';
      $update_items_info = $base->prepare($update_items_info);
      $update_items_info->bindValue('id', $item_table_id, PDO::PARAM_INT);
      $update_items_info->bindValue('item_name', $item_name, PDO::PARAM_STR);
      $update_items_info->execute();
    }


    /********** Display timing ************************/
    $date_actuel = strtotime(date('d-m-Y H:i:s'));
    $timing = $date_actuel - $date_debut;

    $timing_heure = floor($timing/3600);
    $timing_minutes = date('i', $timing);
    $timing_secondes = date('s', $timing);
    echo "Item n°$total_items_counted (id : $item_id), temps total : $timing_heure H $timing_minutes min $timing_secondes sec<br>";
  }
}
    curl_close($curl);

$date_fin = strtotime(date('d-m-Y H:i:s'));
var_dump($date_fin - $date_debut . ' secondes');


 ?>
