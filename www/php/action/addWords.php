<?php
require_once('../config.php');
include "../includedFiles.php";

$tabUser = getPostInfo($request->pseudo);


  // if admin
  if(accessToAdminPermissions($tabUser['id'], $tabUser['lastname'], $tabUser['firstname'], $tabUser['pseudo'], $tabUser['mail'], $tabUser['session_token'])){
    // get message name and its translations + page to display on

    $tabUser = getPostInfo($request);


    $words = false;
    $check_msg_exists = 'SELECT * FROM messages WHERE msg_name LIKE :msg_name AND page LIKE :page';
    $check_msg_exists = $base->prepare($check_msg_exists);
    $check_msg_exists->bindValue('msg_name', $msg_name, PDO::PARAM_STR);
    $check_msg_exists->bindValue('page', $page, PDO::PARAM_STR);
    $check_msg_exists->execute();
    while($msg_exists = $check_msg_exists->fetch())
    {
      $words = true;
    }

    if(!$words){
      // insert the new word in database
      $add_new_message = 'INSERT INTO messages (msg_name, msg_fr, msg_en, page)
      VALUES (:msg_name, :msg_fr, :msg_en, :page)';
      $add_new_message = $base->prepare($add_new_message);
      $add_new_message->bindValue('msg_name', $msg_name, PDO::PARAM_STR);
      $add_new_message->bindValue('msg_fr', $msg_fr, PDO::PARAM_STR);
      $add_new_message->bindValue('msg_en', $msg_en, PDO::PARAM_STR);
      $add_new_message->bindValue('page', $page, PDO::PARAM_STR);
      $add_new_message->execute();
      echo returnResponse("Word Added");
    }else {
      echo returnError($display_error_error_occured);
      exit();
    }
  }else {
    echo returnError($display_error_insufficient_permissions);
    exit();
  }



 ?>
$tabUser['mail']
