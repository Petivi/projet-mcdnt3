<?php
require_once('../config.php');
include "../includedFiles.php";


// need user's pseudo to be check his permissions
if(isset($request->pseudo)){
  $pseudo = htmlspecialchars($request->pseudo, ENT_QUOTES);
  $user_permissions = "";

  // check if user exists
  $check_user_info = 'SELECT * FROM users WHERE pseudo LIKE :pseudo AND active_account LIKE 1';
  $check_user_info = $base->prepare($check_user_info);
  $check_user_info->bindValue('pseudo', $pseudo, PDO::PARAM_STR);
  $check_user_info->execute();
  while($user_info = $check_user_info->fetch())
  {
    // get his permissions
    $user_permissions = $user_info['permissions'];
  }

  // if admin
  if($user_permissions == 'masterofpuppets'){

    // get message name and its translations + page to display on
    if(isset($request->msg_name)){
      $msg_name = htmlspecialchars($request->msg_name, ENT_QUOTES);
    }else{
      $msg_name = "";
    }
    if(isset($request->msg_fr)){
      $msg_fr = htmlspecialchars($request->msg_fr, ENT_QUOTES);
    }else{
      $msg_fr = "";
    }
    if(isset($request->msg_en)){
      $msg_en = htmlspecialchars($request->msg_en, ENT_QUOTES);
    }else{
      $msg_en = "";
    }
    if(isset($request->page)){
      $page = htmlspecialchars($request->page, ENT_QUOTES);
    }else{
      $page = "";
    }

    // insert the new word in database
    $add_new_message = 'INSERT INTO messages (msg_name, msg_fr, msg_en, page)
    VALUES (:msg_name, :msg_fr, :msg_en, :page)';
    $add_new_message = $base->prepare($add_new_message);
    $add_new_message->bindValue('msg_name', $msg_name, PDO::PARAM_STR);
    $add_new_message->bindValue('msg_fr', $msg_fr, PDO::PARAM_STR);
    $add_new_message->bindValue('msg_en', $msg_en, PDO::PARAM_STR);
    $add_new_message->bindValue('page', $page, PDO::PARAM_STR);
    $add_new_message->execute();

  }
}


 ?>
