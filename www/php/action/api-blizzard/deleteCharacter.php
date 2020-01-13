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

    $request_delete_character = "DELETE FROM characters_list WHERE id LIKE :character_id AND user_id LIKE :user_id";
    $request_delete_character = $base->prepare($request_delete_character);
    $request_delete_character->bindValue('character_id', $tabInfo['character_id'], PDO::PARAM_INT);
    $request_delete_character->bindValue('user_id', $account_id, PDO::PARAM_INT);
    if($request_delete_character->execute()){
      echo returnResponse($display_response_empty);
    }else {
      echo returnResponse($display_error_error_occured);
    }
  }else {
    echo returnError($display_error_empty);
  }
}


 ?>
