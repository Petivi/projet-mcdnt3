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


if($tabInfo['data'] == "perso"){ // if data == perso, we display only users' characters

  if($user_exists){
    $tabListCharacters = array();
    $request_character_infos = "SELECT * FROM characters_list WHERE user_id LIKE :user_id $orderBy $sortBy";
    $request_character_infos = $base->prepare($request_character_infos);
    $request_character_infos->bindValue('user_id', $account_id, PDO::PARAM_INT);
    $request_character_infos->execute();
    while($character_infos = $request_character_infos->fetch())
    {
      $statut_like = getStatutLikeDislike($account_id, $character_infos['id']);
      $head_info = ["id" => $character_infos['head_id'], "icon" => $character_infos['head_icon']];
      $neck_info = ["id" => $character_infos['neck_id'], "icon" => $character_infos['neck_icon']];
      $shoulder_info = ["id" => $character_infos['shoulder_id'], "icon" => $character_infos['shoulder_icon']];
      $chest_info = ["id" => $character_infos['chest_id'], "icon" => $character_infos['chest_icon']];
      $waist_info = ["id" => $character_infos['waist_id'], "icon" => $character_infos['waist_icon']];
      $legs_info = ["id" => $character_infos['legs_id'], "icon" => $character_infos['legs_icon']];
      $feet_info = ["id" => $character_infos['feet_id'], "icon" => $character_infos['feet_icon']];
      $wrist_info = ["id" => $character_infos['wrist_id'], "icon" => $character_infos['wrist_icon']];
      $hands_info = ["id" => $character_infos['hands_id'], "icon" => $character_infos['hands_icon']];
      $finger1_info = ["id" => $character_infos['finger1_id'], "icon" => $character_infos['finger1_icon']];
      $finger2_info = ["id" => $character_infos['finger2_id'], "icon" => $character_infos['finger2_icon']];
      $trinket1_info = ["id" => $character_infos['trinket1_id'], "icon" => $character_infos['trinket1_icon']];
      $trinket2_info = ["id" => $character_infos['trinket2_id'], "icon" => $character_infos['trinket2_icon']];
      $back_info = ["id" => $character_infos['back_id'], "icon" => $character_infos['back_icon']];
      $main_hand_info = ["id" => $character_infos['main_hand_id'], "icon" => $character_infos['main_hand_icon']];
      $off_hand_info = ["id" => $character_infos['off_hand_id'], "icon" => $character_infos['off_hand_icon']];

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
        "total_like" => $character_infos['total_like'],
        "total_dislike" => $character_infos['total_dislike'],
        "statut_like" => $statut_like
      ));
    }
    echo returnResponse($tabListCharacters);
  }else {
    echo returnError($display_error_empty);
  }

}else { // if data != perso, we display every characters
  $tabListCharactersFull = array();
  $request_character_infos = "SELECT * FROM characters_list $orderBy $sortBy";
  $request_character_infos = $base->prepare($request_character_infos);
  $request_character_infos->execute();
  while($character_infos = $request_character_infos->fetch())
  {
    if(isset($account_id)){
      $statut_like = getStatutLikeDislike($account_id, $character_infos['id']);
    }else {
      $statut_like = NULL;
    }
    $head_info = ["id" => $character_infos['head_id'], "icon" => $character_infos['head_icon']];
    $neck_info = ["id" => $character_infos['neck_id'], "icon" => $character_infos['neck_icon']];
    $shoulder_info = ["id" => $character_infos['shoulder_id'], "icon" => $character_infos['shoulder_icon']];
    $chest_info = ["id" => $character_infos['chest_id'], "icon" => $character_infos['chest_icon']];
    $waist_info = ["id" => $character_infos['waist_id'], "icon" => $character_infos['waist_icon']];
    $legs_info = ["id" => $character_infos['legs_id'], "icon" => $character_infos['legs_icon']];
    $feet_info = ["id" => $character_infos['feet_id'], "icon" => $character_infos['feet_icon']];
    $wrist_info = ["id" => $character_infos['wrist_id'], "icon" => $character_infos['wrist_icon']];
    $hands_info = ["id" => $character_infos['hands_id'], "icon" => $character_infos['hands_icon']];
    $finger1_info = ["id" => $character_infos['finger1_id'], "icon" => $character_infos['finger1_icon']];
    $finger2_info = ["id" => $character_infos['finger2_id'], "icon" => $character_infos['finger2_icon']];
    $trinket1_info = ["id" => $character_infos['trinket1_id'], "icon" => $character_infos['trinket1_icon']];
    $trinket2_info = ["id" => $character_infos['trinket2_id'], "icon" => $character_infos['trinket2_icon']];
    $back_info = ["id" => $character_infos['back_id'], "icon" => $character_infos['back_icon']];
    $main_hand_info = ["id" => $character_infos['main_hand_id'], "icon" => $character_infos['main_hand_icon']];
    $off_hand_info = ["id" => $character_infos['off_hand_id'], "icon" => $character_infos['off_hand_icon']];

    array_push($tabListCharactersFull, array(
      "character_id" => $character_infos['id'],
      "user_id" => $character_infos['user_id'],
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
      "total_like" => $character_infos['total_like'],
      "total_dislike" => $character_infos['total_dislike'],
      "statut_like" => $statut_like
    ));
  }
    echo returnResponse($tabListCharactersFull);
}



// function getItemIcon($item_id){
//   global $base;
//
//   $request_item_infos = "SELECT * FROM items_list WHERE item_id LIKE :item_id";
//   $request_item_infos = $base->prepare($request_item_infos);
//   $request_item_infos->bindValue('item_id', $item_id, PDO::PARAM_INT);
//   $request_item_infos->execute();
//   while($item_infos = $request_item_infos->fetch())
//   {
//     return $item_infos['item_icon'];
//   }
// }


function getStatutLikeDislike($user_id, $character_id){
  global $base;

    $user_statut_exists = false;
    $statut_like = NULL;
    $request_characters_likes_infos = "SELECT * FROM characters_likes WHERE user_id LIKE :user_id AND character_id LIKE :character_id";
    $request_characters_likes_infos = $base->prepare($request_characters_likes_infos);
    $request_characters_likes_infos->bindValue('user_id', $user_id, PDO::PARAM_INT);
    $request_characters_likes_infos->bindValue('character_id', $character_id, PDO::PARAM_INT);
    $request_characters_likes_infos->execute();
    while($characters_likes_infos = $request_characters_likes_infos->fetch())
    {
      $user_statut_exists = true;
      if($characters_likes_infos['statut'] == 1){ // like
        $statut_like = "like";
      }elseif ($characters_likes_infos['statut'] == 2) { // dislike
        $statut_like = "dislike";
      }
    }
    return $statut_like;
}

 ?>
