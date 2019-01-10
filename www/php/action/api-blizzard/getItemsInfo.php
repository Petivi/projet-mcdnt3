<?php
require_once('../../config.php');
include "../../includedFiles.php";

ini_set('max_execution_time', 0);

$tabInfo = getCharacterInfo($request);
if(isset($request->lang)){
  $lang = htmlspecialchars($request->lang, ENT_QUOTES);
}else{
  $lang = "en";
}

if($lang == "fr"){
  $url_early_lang = "eu";
  $local_lang = "locale=fr_UE";
}else {
  $url_early_lang = "us";
  $local_lang = "locale=en_US";
}

if($tabInfo['item_class'] && $tabInfo['item_subClass'] && $tabInfo['item_inventory_type']){

  $curl = curl_init();
  curl_setopt_array($curl, array(
      CURLOPT_RETURNTRANSFER => 1,
      CURLOPT_URL => 'https://'.$url_early_lang.'.battle.net/oauth/token?grant_type=client_credentials&client_id='.$wow_api_client_id.'&client_secret='.$wow_api_client_secret,
  ));
  $resp_token = json_decode(curl_exec($curl), true);
  if(isset($resp_token['access_token'])){
    $access_token = $resp_token['access_token']; // token

    $tabListItems = array();
    $get_item_info = 'SELECT * FROM items_list WHERE item_class LIKE :item_class AND item_subclass LIKE :item_subclass AND item_inventory_type LIKE :item_inventory_type';
    $get_item_info = $base->prepare($get_item_info);
    $get_item_info->bindValue('item_class', $tabInfo['item_class'], PDO::PARAM_INT);
    $get_item_info->bindValue('item_subclass', $tabInfo['item_subClass'], PDO::PARAM_INT);
    $get_item_info->bindValue('item_inventory_type', $tabInfo['item_inventory_type'], PDO::PARAM_INT);
    $get_item_info->execute();
    while($item_info = $get_item_info->fetch())
    {
      $item_id = $item_info['item_id'];

      $url_redirect = 'https://'.$url_early_lang.'.api.blizzard.com/wow/item/'.$item_id.'?'.$local_lang.'&access_token='.$access_token;
      curl_setopt_array($curl, array(
        CURLOPT_RETURNTRANSFER => 1,
        CURLOPT_URL => $url_redirect, // url for request
      ));
      $resp_request = curl_exec($curl); // content of the request --> display on front sid
      array_push($tabListItems, $resp_request);

    }
    echo returnResponse($tabListItems); // --> display on front side
    curl_close($curl);
  }


}else { // no informations given
  echo returnError($display_error_empty);
  exit();
}


 ?>
