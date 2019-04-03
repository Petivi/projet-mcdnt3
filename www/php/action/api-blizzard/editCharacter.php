<?php
require_once('../../config.php');
include "../../includedFiles.php";

$tabInfo = getCharacterInfo($request);


if(isset($tabInfo['session_token'])){
  $session_token = htmlspecialchars($tabInfo['session_token'], ENT_QUOTES);

  $user_exists = false;
  $request_user_info = 'SELECT * FROM users WHERE session_token LIKE :session_token';
  $request_user_info = $base->prepare($request_user_info);
  $request_user_info->bindValue('session_token', $session_token, PDO::PARAM_STR);
  $request_user_info->execute();
  while($user_info = $request_user_info->fetch())
  {
    // get his informations
    $account_id = $user_info['id'];
    $account_pseudo = Chiffrement::decrypt($user_info['pseudo']);
    $account_mail = Chiffrement::decrypt($user_info['mail']);
    $account_active_account = $user_info['active_account'];
    $account_checked_mail = $user_info['checked_mail'];
    if($account_active_account && $account_checked_mail){
      $user_exists = true;
    }
  }

  if($user_exists){
    if($tabInfo['character_name'] == ""){ // if no name is given for the character, then we use the user pseudo
      $character_name = $account_pseudo;
    }else {
      $character_name = $tabInfo['character_name'];
    }
    $date_today = strtotime(date('d-m-Y H:i:s'));

    foreach ($tabInfo as $key => $value) {
      var_dump($key " : " . $value);
    }


    $update_character = "UPDATE characters_list
    SET name = :name,
    head_id = :head_id,
    neck_id = :neck_id,
    shoulder_id = :shoulder_id,
    chest_id = :chest_id,
    waist_id = :waist_id,
    legs_id = :legs_id,
    feet_id = :feet_id,
    wrist_id = :wrist_id,
    hands_id = :hands_id,
    finger1_id = :finger1_id,
    finger2_id = :finger2_id,
    trinket1_id = :trinket1_id,
    trinket2_id = :trinket2_id,
    back_id = :back_id,
    main_hand_id = :main_hand_id,
    off_hand_id = :off_hand_id,
    attack = :attack,
    armour = :armour,
    stamina = :stamina,
    health = :health,
    critical_strike = :critical_strike,
    haste = :haste,
    mastery = :mastery,
    versatility = :versatility,
    head_icon = :head_icon,
    neck_icon = :neck_icon,
    shoulder_icon = :shoulder_icon,
    chest_icon = :chest_icon,
    waist_icon = :waist_icon,
    legs_icon = :legs_icon,
    feet_icon = :feet_icon,
    wrist_icon = :wrist_icon,
    hands_icon = :hands_icon,
    finger1_icon = :finger1_icon,
    finger2_icon = :finger2_icon,
    trinket1_icon = :trinket1_icon,
    trinket2_icon = :trinket2_icon,
    back_icon = :back_icon,
    main_hand_icon = :main_hand_icon,
    off_hand_icon = :off_hand_icon
    WHERE id LIKE :character_id AND user_id LIKE :user_id";
    $update_character = $base->prepare($update_character);
    $update_character->bindValue('character_id', $tabInfo['character_id'], PDO::PARAM_INT);
    $update_character->bindValue('user_id', $account_id, PDO::PARAM_INT);
    $update_character->bindValue('name', $character_name);
    $update_character->bindValue('head_id', $tabInfo['character_head']['id']);
    $update_character->bindValue('neck_id', $tabInfo['character_neck']['id']);
    $update_character->bindValue('shoulder_id', $tabInfo['character_shoulder']['id']);
    $update_character->bindValue('chest_id', $tabInfo['character_chest']['id']);
    $update_character->bindValue('waist_id', $tabInfo['character_waist']['id']);
    $update_character->bindValue('legs_id', $tabInfo['character_legs']['id']);
    $update_character->bindValue('feet_id', $tabInfo['character_feet']['id']);
    $update_character->bindValue('wrist_id', $tabInfo['character_wrist']['id']);
    $update_character->bindValue('hands_id', $tabInfo['character_hands']['id']);
    $update_character->bindValue('finger1_id', $tabInfo['character_finger1']['id']);
    $update_character->bindValue('finger2_id', $tabInfo['character_finger2']['id']);
    $update_character->bindValue('trinket1_id', $tabInfo['character_trinket1']['id']);
    $update_character->bindValue('trinket2_id', $tabInfo['character_trinket2']['id']);
    $update_character->bindValue('back_id', $tabInfo['character_back']['id']);
    $update_character->bindValue('main_hand_id', $tabInfo['character_main_hand']['id']);
    $update_character->bindValue('off_hand_id', $tabInfo['character_off_hand']['id']);
    $update_character->bindValue('attack', $tabInfo['character_attack']);
    $update_character->bindValue('armour', $tabInfo['character_armour']);
    $update_character->bindValue('stamina', $tabInfo['character_stamina']);
    $update_character->bindValue('health', $tabInfo['character_health']);
    $update_character->bindValue('critical_strike', $tabInfo['character_critical_strike']);
    $update_character->bindValue('haste', $tabInfo['character_haste']);
    $update_character->bindValue('mastery', $tabInfo['character_mastery']);
    $update_character->bindValue('versatility', $tabInfo['character_versatility']);
    $update_character->bindValue('head_icon', $tabInfo['character_head']['icon']);
    $update_character->bindValue('neck_icon', $tabInfo['character_neck']['icon']);
    $update_character->bindValue('shoulder_icon', $tabInfo['character_shoulder']['icon']);
    $update_character->bindValue('chest_icon', $tabInfo['character_chest']['icon']);
    $update_character->bindValue('waist_icon', $tabInfo['character_waist']['icon']);
    $update_character->bindValue('legs_icon', $tabInfo['character_legs']['icon']);
    $update_character->bindValue('feet_icon', $tabInfo['character_feet']['icon']);
    $update_character->bindValue('wrist_icon', $tabInfo['character_wrist']['icon']);
    $update_character->bindValue('hands_icon', $tabInfo['character_hands']['icon']);
    $update_character->bindValue('finger1_icon', $tabInfo['character_finger1']['icon']);
    $update_character->bindValue('finger2_icon', $tabInfo['character_finger2']['icon']);
    $update_character->bindValue('trinket1_icon', $tabInfo['character_trinket1']['icon']);
    $update_character->bindValue('trinket2_icon', $tabInfo['character_trinket2']['icon']);
    $update_character->bindValue('back_icon', $tabInfo['character_back']['icon']);
    $update_character->bindValue('main_hand_icon', $tabInfo['character_main_hand']['icon']);
    $update_character->bindValue('off_hand_icon', $tabInfo['character_off_hand']['icon']);
    // $update_character->execute();

    if($update_character->execute()){
      echo returnResponse($display_response_empty);
    }else {
      echo returnError($display_error_error_occured);
    }
  }else {
    echo returnError($display_error_error_occured);
  }




}else { // no session_token sent, so we can't do anything
  echo returnError($display_error_empty);
  exit();
}


 ?>
