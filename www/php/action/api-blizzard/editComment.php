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
    $comment_exists = false;
    // check if the comment exists
    $request_character_comment = 'SELECT * FROM characters_comment WHERE id LIKE :comment_id AND user_id LIKE :user_id AND character_id LIKE :character_id';
    $request_character_comment = $base->prepare($request_character_comment);
    $request_character_comment->bindValue('comment_id', $tabInfo['comment_id'], PDO::PARAM_INT);
    $request_character_comment->bindValue('user_id', $account_id, PDO::PARAM_INT);
    $request_character_comment->bindValue('character_id', $tabInfo['character_id'], PDO::PARAM_INT);
    $request_character_comment->execute();
    while($character_comment = $request_character_comment->fetch())
    {
      $comment_exists = true;
      $created_date = $character_comment['created_date'];
    }

    if($comment_exists){ // if comment exists, then we update the comment
      $update_character_comment = "UPDATE characters_comment
      SET comment = :comment,
      last_modified = :last_modified
      WHERE id LIKE :comment_id AND user_id LIKE :user_id AND character_id LIKE :character_id";
      $update_character_comment = $base->prepare($update_character_comment);
      $update_character_comment->bindValue('comment_id', $tabInfo['comment_id'], PDO::PARAM_INT);
      $update_character_comment->bindValue('user_id', $account_id, PDO::PARAM_INT);
      $update_character_comment->bindValue('character_id', $tabInfo['character_id'], PDO::PARAM_INT);
      $update_character_comment->bindValue('comment', $tabInfo['comment'], PDO::PARAM_STR);
      $update_character_comment->bindValue('last_modified', $date_today, PDO::PARAM_INT);
      $update_character_comment->execute();

      $tabInfoComment = [
        "comment" => htmlspecialchars_decode($tabInfo['comment'], ENT_QUOTES),
        "user_pseudo" => htmlspecialchars_decode($account_pseudo, ENT_QUOTES),
        "character_id" => $tabInfo['character_id'],
        "created_date" => date('d/m/Y H:i:s', $created_date),
        "last_modified" => date('d/m/Y H:i:s', $date_today),
        "editable" => true
      ];

      echo returnResponse($tabInfoComment);
    }else {  // comment doesn't exists
      echo returnError($display_error_error_occured);
    }
  }else { // token does not match or account not active or mail not checked
    echo returnError($display_error_empty);
  }


}else { // no session_token
  echo returnError($display_error_empty);
}

 ?>
