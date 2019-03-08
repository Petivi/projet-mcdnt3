<?php
require_once('../../config.php');
include "../../includedFiles.php";

$tabInfo = commentManagement($request);


if($tabInfo['character_id']){

  $tabCharactersComment = array();
  $request_characters_comment = 'SELECT * FROM characters_comment WHERE character_id LIKE :character_id AND comment_statut LIKE 1';
  $request_characters_comment = $base->prepare($request_characters_comment);
  $request_characters_comment->bindValue('character_id', $tabInfo['character_id'], PDO::PARAM_INT);
  $request_characters_comment->execute();
  while($comment_info = $request_characters_comment->fetch())
  {
    array_push($tabCharactersComment, array(
      "comment_id" => $comment_info['id'],
      "user_id" => $comment_info['user_id'],
      "user_pseudo" => Chiffrement::decrypt($comment_info['user_pseudo']),
      "character_id" => $comment_info['character_id'],
      "comment" => $comment_info['comment'],
      "created_date" => date('d/m/Y H:i:s', $comment_info['created_date']),
      "last_modified" => date('d/m/Y H:i:s', $comment_info['last_modified']),
    ));
  }

  echo returnResponse($tabCharactersComment);
}else {
  echo returnError($display_error_empty);
}


 ?>
