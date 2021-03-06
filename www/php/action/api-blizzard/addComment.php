<?php
require_once('../../config.php');
include "../../includedFiles.php";


$tabInfo = commentManagement($request);

if($tabInfo['session_token']){
  $date_today = strtotime(date('d-m-Y H:i:s'));
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
    if($tabInfo['character_id']){

      $request_add_new_comment = 'INSERT INTO characters_comment (user_id, character_id, comment, created_date, last_modified)
      VALUES (:user_id, :character_id, :comment, :created_date, :last_modified)';
      $request_add_new_comment = $base->prepare($request_add_new_comment);
      $request_add_new_comment->bindValue('user_id', $account_id, PDO::PARAM_INT);
      $request_add_new_comment->bindValue('character_id', $tabInfo['character_id'], PDO::PARAM_INT);
      $request_add_new_comment->bindValue('comment', $tabInfo['comment'], PDO::PARAM_STR);
      $request_add_new_comment->bindValue('created_date', $date_today, PDO::PARAM_INT);
      $request_add_new_comment->bindValue('last_modified', $date_today, PDO::PARAM_INT);
      $request_add_new_comment->execute();

      $request_last_comment = "SELECT id FROM characters_comment WHERE id = LAST_INSERT_ID() AND user_id LIKE :user_id AND character_id LIKE :character_id";
      $request_last_comment = $base->prepare($request_last_comment);
      $request_last_comment->bindValue('user_id', $account_id, PDO::PARAM_INT);
      $request_last_comment->bindValue('character_id', $tabInfo['character_id'], PDO::PARAM_INT);
      $request_last_comment->execute();
      while($last_comment = $request_last_comment->fetch())
      {
        $comment_id = $last_comment['id'];
      }

      $tabInfoComment = [
        "comment" => htmlspecialchars_decode($tabInfo['comment'], ENT_QUOTES),
        "comment_id" => $comment_id,
        "user_pseudo" => htmlspecialchars_decode($account_pseudo, ENT_QUOTES),
        "character_id" => $tabInfo['character_id'],
        "created_date" => date('d/m/Y H:i:s', $date_today),
        "last_modified" => date('d/m/Y H:i:s', $date_today),
        "editable" => true
      ];

      echo returnResponse($tabInfoComment);
    }else {
      echo returnError($display_error_error_occured);
    }
  }else { // token does not match or account not active or mail not checked
    echo returnError($display_error_empty);
  }


}else { // no session_token
  echo returnError($display_error_empty);
}

 ?>
