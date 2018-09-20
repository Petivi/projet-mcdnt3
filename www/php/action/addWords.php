<?php
require_once('../config.php');
include "../includedFiles.php";


  if(isset($request->pseudo->lastname)){
    $lastname = htmlspecialchars($request->pseudo->lastname, ENT_QUOTES);
  }else{
    $lastname = "";
  }
  if(isset($request->pseudo->firstname)){
    $firstname = htmlspecialchars($request->pseudo->firstname, ENT_QUOTES);
  }else{
    $firstname = "";
  }
  if(isset($request->pseudo->pseudo)){
    $pseudo = htmlspecialchars($request->pseudo->pseudo, ENT_QUOTES);
  }else{
    $pseudo = "";
  }
  if(isset($request->pseudo->mail)){
    $mail = htmlspecialchars($request->pseudo->mail, ENT_QUOTES);
  }else{
    $mail = "";
  }
  if(isset($request->pseudo->userId)){
    $userId = htmlspecialchars($request->pseudo->userId, ENT_QUOTES);
  }else{
    $userId = "";
  }


  // if admin
  if(accessToAdminPermissions($userId, $lastname, $firstname, $pseudo, $mail)){
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
      echo returnResponse("Mot ajouté");
    }else {
      echo returnError("Erreur, mot non ajouté");
    }
  }else {
    echo returnError("Droits insuffisants");
  }



 ?>