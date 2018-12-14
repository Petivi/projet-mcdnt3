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
    if(!$tabInfo['character_name'] != ""){
      $character_name = $account_pseudo;
    }
    $date_today = strtotime(date('d-m-Y H:i:s'));

    $insert_new_character = 'INSERT INTO characters_list (user_id, name, race_id, class_id, created_date, last_modified)
    VALUES (:user_id, :name, :race_id, :class_id, :created_date, :created_date)';
    $insert_new_character = $base->prepare($insert_new_character);
    $insert_new_character->bindValue('user_id', $account_id, PDO::PARAM_INT);
    $insert_new_character->bindValue('name', $character_name, PDO::PARAM_STR);
    $insert_new_character->bindValue('race_id', $tabInfo['character_race_id'], PDO::PARAM_INT);
    $insert_new_character->bindValue('class_id', $tabInfo['character_class_id'], PDO::PARAM_INT);
    $insert_new_character->bindValue('created_date', $date_today, PDO::PARAM_INT);
    $insert_new_character->execute();

    echo returnResponse($display_response_empty);
  }else {
    echo returnError($display_error_error_occured);
  }




}else { // no session_token sent, so we can't do anything
  echo returnError($display_error_empty);
  exit();
}


 ?>
