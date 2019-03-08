<?php
require_once('../../config.php');
include "../../includedFiles.php";

$tabInfo = commentManagement($request);

if($tabInfo['session_token']){
  $account_id = NULL;
  $request_user_info = 'SELECT * FROM users WHERE session_token LIKE :session_token';
  $request_user_info = $base->prepare($request_user_info);
  $request_user_info->bindValue('session_token', $tabInfo['session_token'], PDO::PARAM_STR);
  $request_user_info->execute();
  while($user_info = $request_user_info->fetch())
  {
    // get his informations
    $account_id = $user_info['id'];
  }
}


if($tabInfo['character_id']){

  $tabCharactersComment = array();
  $request_characters_comment = 'SELECT * FROM characters_comment WHERE character_id LIKE :character_id AND comment_statut LIKE 1 ORDER BY created_date DESC';
  $request_characters_comment = $base->prepare($request_characters_comment);
  $request_characters_comment->bindValue('character_id', $tabInfo['character_id'], PDO::PARAM_INT);
  $request_characters_comment->execute();
  while($comment_info = $request_characters_comment->fetch())
  {
    if($comment_info['user_id'] == $account_id){
      $editable = true;
    }else {
      $editable = false;
    }
    array_push($tabCharactersComment, array(
      "comment_id" => $comment_info['id'],
      "user_pseudo" => Chiffrement::decrypt($comment_info['user_pseudo']),
      "character_id" => $comment_info['character_id'],
      "comment" => $comment_info['comment'],
      "created_date" => date('d/m/Y H:i:s', $comment_info['created_date']),
      "last_modified" => date('d/m/Y H:i:s', $comment_info['last_modified']),
      "editable" => $editable
    ));
  }

  echo returnResponse($tabCharactersComment);
}else {
  echo returnError($display_error_empty);
}


 ?>
