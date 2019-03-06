<?php

require_once('../../config.php');
include "../../includedFiles.php";

$tabInfo = retrieveCharactersInfo($request);

$filter = $tabInfo['filter'];
$order = $tabInfo['order'];
if($filter == "like"){
  $orderBy = "ORDER BY total_like";
}else {
  $orderBy = "ORDER BY created_date";
}

if($order == "asc"){
  $sortBy = "ASC";
}else {
  $sortBy = "DESC";
}

if(isset($tabInfo['session_token'])){
  if($tabInfo['session_token']){ // if there is a session_token, we display only users' characters

    $user_exists = false;
    $request_user_info = 'SELECT * FROM users WHERE session_token LIKE :session_token';
    $request_user_info = $base->prepare($request_user_info);
    $request_user_info->bindValue('session_token', $tabInfo['session_token'], PDO::PARAM_STR);
    $request_user_info->execute();
    while($user_info = $request_user_info->fetch())
    {
      // get his informations
      $account_id = $user_info['id'];
      $account_pseudo = Chiffrement::decrypt($user_info['pseudo']);
      $account_mail = Chiffrement::decrypt($user_info['mail']);
      $account_active_account = $user_info['active_account'];
      $account_checked_mail = $user_info['checked_mail'];
      if($account_active_account == 1 && $account_checked_mail){
        $user_exists = true;
      }
    }

    if($user_exists){
      $tabListCharacters = array();
      $request_character_infos = "SELECT * FROM characters_list WHERE user_id LIKE :user_id $orderBy $sortBy";
      $request_character_infos = $base->prepare($request_character_infos);
      $request_character_infos->bindValue('user_id', $account_id, PDO::PARAM_INT);
      $request_character_infos->execute();
      while($character_infos = $request_character_infos->fetch())
      {
        $head_info = ["id" => $character_infos['head_id'], "icon" => getItemIcon($character_infos['head_id'])];
        $neck_info = ["id" => $character_infos['neck_id'], "icon" => getItemIcon($character_infos['neck_id'])];
        $shoulder_info = ["id" => $character_infos['shoulder_id'], "icon" => getItemIcon($character_infos['shoulder_id'])];
        $chest_info = ["id" => $character_infos['chest_id'], "icon" => getItemIcon($character_infos['chest_id'])];
        $waist_info = ["id" => $character_infos['waist_id'], "icon" => getItemIcon($character_infos['waist_id'])];
        $legs_info = ["id" => $character_infos['legs_id'], "icon" => getItemIcon($character_infos['legs_id'])];
        $feet_info = ["id" => $character_infos['feet_id'], "icon" => getItemIcon($character_infos['feet_id'])];
        $wrist_info = ["id" => $character_infos['wrist_id'], "icon" => getItemIcon($character_infos['wrist_id'])];
        $hands_info = ["id" => $character_infos['hands_id'], "icon" => getItemIcon($character_infos['hands_id'])];
        $finger1_info = ["id" => $character_infos['finger1_id'], "icon" => getItemIcon($character_infos['finger1_id'])];
        $finger2_info = ["id" => $character_infos['finger2_id'], "icon" => getItemIcon($character_infos['finger2_id'])];
        $trinket1_info = ["id" => $character_infos['trinket1_id'], "icon" => getItemIcon($character_infos['trinket1_id'])];
        $trinket2_info = ["id" => $character_infos['trinket2_id'], "icon" => getItemIcon($character_infos['trinket2_id'])];
        $back_info = ["id" => $character_infos['back_id'], "icon" => getItemIcon($character_infos['back_id'])];
        $main_hand_info = ["id" => $character_infos['main_hand_id'], "icon" => getItemIcon($character_infos['main_hand_id'])];
        $off_hand_info = ["id" => $character_infos['off_hand_id'], "icon" => getItemIcon($character_infos['off_hand_id'])];

        array_push($tabListCharacters, array(
          "character_id" => $character_infos['id'],
          "name" => $character_infos['name'],
          "race_id" => $character_infos['race_id'],
          "class_id" => $character_infos['class_id'],
          "head" => $head_info,
          "neck" => $neck_info,
          "shoulder" => $shoulder_info,
          "chest" => $chest_info,
          "waist" => $waist_info,
          "legs" => $legs_info,
          "feet" => $feet_info,
          "wrist" => $wrist_info,
          "hands" => $hands_info,
          "finger1" => $finger1_info,
          "finger2" => $finger2_info,
          "trinket1" => $trinket1_info,
          "trinket2" => $trinket2_info,
          "back" => $back_info,
          "main_hand" => $main_hand_info,
          "off_hand" => $off_hand_info,
          "attack" => $character_infos['attack'],
          "armour" => $character_infos['armour'],
          "stamina" => $character_infos['stamina'],
          "health" => $character_infos['health'],
          "critical_strike" => $character_infos['critical_strike'],
          "haste" => $character_infos['haste'],
          "mastery" => $character_infos['mastery'],
          "versatility" => $character_infos['versatility'],
          "created_date" => date('d/m/Y H:i:s',$character_infos['created_date']),
          "last_modified" => date('d/m/Y H:i:s',$character_infos['last_modified']),
        ));
      }
      echo returnResponse($tabListCharacters);
    }else {
      echo returnError($display_error_empty);
    }

  }else { // if no session_token, we display every characters

  }


}else {
  echo returnError($display_error_empty);
}


function getItemIcon($item_id){
  global $base;

  $request_item_infos = "SELECT * FROM items_list WHERE item_id LIKE :item_id";
  $request_item_infos = $base->prepare($request_item_infos);
  $request_item_infos->bindValue('item_id', $item_id, PDO::PARAM_INT);
  $request_item_infos->execute();
  while($item_infos = $request_item_infos->fetch())
  {
    return $item_info['item_icon'];
  }
}

 ?>
