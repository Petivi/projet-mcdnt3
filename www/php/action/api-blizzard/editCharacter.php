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

    var_dump($tabInfo['character_head']['id']);
    var_dump($tabInfo['character_head']['icon']);


    $update_character = "UPDATE characters_list
    SET name = :name,
    head_id = :head_id,
    head_icon = :head_icon
    WHERE id LIKE :character_id AND user_id LIKE :user_id";
    $update_character = $base->prepare($update_character);
    $update_character->bindValue('character_id', $tabInfo['character_id'], PDO::PARAM_INT);
    $update_character->bindValue('user_id', $account_id, PDO::PARAM_INT);
    $update_character->bindValue('name', $character_name);
    $update_character->bindValue('head_id', $tabInfo['character_head']['id']);
    $update_character->bindValue('head_icon', $tabInfo['character_head']['icon']);
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
