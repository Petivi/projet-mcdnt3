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

if(!is_null($tabInfo['item_class']) && !is_null($tabInfo['item_subClass']) && !is_null($tabInfo['item_inventory_type'])){

  if($tabInfo['item_quality'] == -1){
    $add_quality = "";
  }else {
    $quality = $tabInfo['item_quality'];
    $add_quality = "AND item_quality LIKE '$quality'";
  }

  $curl = curl_init();
  curl_setopt_array($curl, array(
      CURLOPT_RETURNTRANSFER => 1,
      CURLOPT_URL => 'https://'.$url_early_lang.'.battle.net/oauth/token?grant_type=client_credentials&client_id='.$wow_api_client_id.'&client_secret='.$wow_api_client_secret,
  ));
  $resp_token = json_decode(curl_exec($curl), true);
  if(isset($resp_token['access_token'])){
    $access_token = $resp_token['access_token']; // token

    $tabListItems = array();
    $get_item_info = "SELECT * FROM items_list WHERE item_class LIKE :item_class
    AND item_subclass LIKE :item_subclass
    AND item_inventory_type LIKE :item_inventory_type
    AND item_required_level BETWEEN :item_required_level_min AND :item_required_level_max
    $add_quality";
    $get_item_info = $base->prepare($get_item_info);
    $get_item_info->bindValue('item_class', $tabInfo['item_class'], PDO::PARAM_INT);
    $get_item_info->bindValue('item_subclass', $tabInfo['item_subClass'], PDO::PARAM_INT);
    $get_item_info->bindValue('item_inventory_type', $tabInfo['item_inventory_type'], PDO::PARAM_INT);
    $get_item_info->bindValue('item_required_level_min', $tabInfo['item_required_level_min'], PDO::PARAM_INT);
    $get_item_info->bindValue('item_required_level_max', $tabInfo['item_required_level_max'], PDO::PARAM_INT);
    $get_item_info->execute();
    while($item_info = $get_item_info->fetch())
    {
      $item_id = $item_info['item_id'];
      $item_class = $item_info['item_class'];
      $item_subclass = $item_info['item_subclass'];
      $item_inventory_type = $item_info['item_inventory_type'];
      $item_icon = $item_info['item_icon'];
      $item_allowable_classes = $item_info['item_allowable_classes'];
      $item_allowable_races = $item_info['item_allowable_races'];
      $item_required_level = $item_info['item_required_level'];
      $item_quality = $item_info['item_quality'];

      if($item_info['item_allowable_classes']){
        if(strpos($item_info['item_allowable_classes'],$tabInfo['allowable_classes'])){
          $check_class = true;
        }else {
          $check_class = false;
        }
      }else {
        $check_class = true;
      }

      if($item_info['item_allowable_races']){
        if(strpos($item_info['item_allowable_races'],$tabInfo['allowable_races'])){
          $check_race = true;
        }else {
          $check_race = false;
        }
      }else {
        $check_race = true;
      }

      if($check_class && $check_race){
        array_push($tabListItems, array(
          "item_id" => $item_id,
          "item_class" => $item_class,
          "item_subclass" => $item_subclass,
          "item_inventory_type" => $item_inventory_type,
          "item_icon" => $item_icon,
          "item_allowable_classes" => $item_allowable_classes,
          "item_allowable_races" => $item_allowable_races,
          "item_required_level" => $item_required_level,
          "item_quality" => $item_quality
        ));
      }

    }
    echo returnResponse($tabListItems); // --> display on front side
    curl_close($curl);
  }


}else { // no informations given
  echo returnError($display_error_empty);
  exit();
}


 ?>
