<?php
require_once('../../config.php');
include "../../includedFiles.php";


$tabInfo = infoStatutLike($request);

if($tabInfo['session_token']){ // if we have a session token
  $newStatutLike = $tabInfo['statut'];
  $character_id = $tabInfo['character_id'];
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
    $statut_exists = false;
    $request_like_info = 'SELECT * FROM characters_likes WHERE user_id LIKE :user_id AND character_id LIKE :character_id LIMIT 1';
    $request_like_info = $base->prepare($request_like_info);
    $request_like_info->bindValue('user_id', $account_id, PDO::PARAM_INT);
    $request_like_info->bindValue('character_id', $character_id, PDO::PARAM_INT);
    $request_like_info->execute();
    while($like_info = $request_like_info->fetch())
    {
      $statut_exists = true;
      $like_id = $like_info['id'];
      if($newStatutLike == $like_info['statut']){ // new status is the same as the existing one, so we delete users' like
        $request_delete_like = "DELETE FROM characters_likes WHERE id LIKE :like_id";
        $request_delete_like = $base->prepare($request_delete_like);
        $request_delete_like->bindValue('like_id', $like_id, PDO::PARAM_INT);
        $request_delete_like->execute();

        if($like_info['statut'] == 1){ // like
          $column = "total_like";
        }elseif ($like_info['statut'] == 2) { // dislike
          $column = "total_dislike";
        }

        $update_character_info = "UPDATE characters_list
        SET $column = $column - 1
        WHERE id LIKE :id";
        $update_character_info = $base->prepare($update_character_info);
        $update_character_info->bindValue('id', $character_id, PDO::PARAM_INT);
        $update_character_info->execute();

        echo returnResponse($display_response_empty);
      }else { // changing the status
        $update_character_likes_info = "UPDATE characters_likes
        SET statut = :statut,
        last_modified = :last_modified
        WHERE id LIKE :id";
        $update_character_likes_info = $base->prepare($update_character_likes_info);
        $update_character_likes_info->bindValue('id', $like_id, PDO::PARAM_INT);
        $update_character_likes_info->bindValue('statut', $newStatutLike, PDO::PARAM_INT);
        $update_character_likes_info->bindValue('last_modified', $date_today, PDO::PARAM_INT);
        $update_character_likes_info->execute();



        if($newStatutLike == 1){ // like
          $column_increment = "total_like";
          $column_decrement = "total_dislike";
        }elseif ($newStatutLike == 2) { // dislike
          $column_increment = "total_dislike";
          $column_decrement = "total_like";
        }

        $update_character_info = "UPDATE characters_list
        SET $column_increment = $column_increment + 1,
        $column_decrement = $column_decrement - 1
        WHERE id LIKE :id";
        $update_character_info = $base->prepare($update_character_info);
        $update_character_info->bindValue('id', $character_id, PDO::PARAM_INT);
        $update_character_info->execute();

        echo returnResponse($display_response_empty);
      } // end changing status
    } // end existing like


    /******************************/

    if(!$statut_exists){ // if user has not liked / disliked this character yet
      $add_new_characters_likes = 'INSERT INTO characters_likes (user_id, character_id, statut, last_modified)
      VALUES (:user_id, :character_id, :statut, :last_modified)';
      $add_new_characters_likes = $base->prepare($add_new_characters_likes);
      $add_new_characters_likes->bindValue('user_id', $account_id, PDO::PARAM_INT);
      $add_new_characters_likes->bindValue('character_id', $character_id, PDO::PARAM_INT);
      $add_new_characters_likes->bindValue('statut', $newStatutLike, PDO::PARAM_INT);
      $add_new_characters_likes->bindValue('last_modified', $date_today, PDO::PARAM_INT);
      $add_new_characters_likes->execute();

      if($newStatutLike == 1){ // like
        $column = "total_like";
      }elseif ($newStatutLike == 2) { // dislike
        $column = "total_dislike";
      }

      $update_character_info = "UPDATE characters_list
      SET $column = $column + 1
      WHERE id LIKE :id";
      $update_character_info = $base->prepare($update_character_info);
      $update_character_info->bindValue('id', $character_id, PDO::PARAM_INT);
      $update_character_info->execute();

      echo returnResponse($display_response_empty);
    } // end creation new character like / dislike

  }else {  // user doesn't exist or session_token does not match
    echo returnError($display_error_empty);
    exit();
  }

}else { // no session token
  echo returnError($display_error_empty);
  exit();
}

 ?>
