<?php
require_once('../config.php');
include "../includedFiles.php";

// check if we receive a mail
if(isset($request->mail)){
  $mail = htmlspecialchars($request->mail, ENT_QUOTES);
  if(isset($request->lang)){
    $lang = htmlspecialchars($request->lang, ENT_QUOTES);
  }else {
    $lang = "en";
  }

  // if mail ain't empty
  if($mail != ""){
    $user_exists = false;
    try {
      $get_user_info = 'SELECT * FROM users WHERE mail LIKE :mail AND checked_mail LIKE 1 AND active_account LIKE 1';
      $get_user_info = $base->prepare($get_user_info);
      $get_user_info->bindValue('mail', $mail, PDO::PARAM_STR);
      $get_user_info->execute();
      while($user_info = $get_user_info->fetch())
      {
        $user_exists = true;
        $firstname = $user_info['firstname'];
        $lastname = $user_info['lastname'];
        $pseudo = $user_info['pseudo'];
        $mail = $user_info['mail'];
        $id = $user_info['id'];
      }
    } catch (\Exception $e) {
      echo returnError($display_error_error_occured);
      exit();
    }

    // user found according to mail
    if($user_exists){
      $token_temp = generateTokenTemp();
      $date_token_created = strtotime(date('d-m-Y'));

      try {
        $update_token_temp = 'UPDATE users
        SET token_temp = :token_temp, date_token_created = :date_token_created
        WHERE id LIKE :id
        AND lastname LIKE :lastname
        AND firstname LIKE :firstname
        AND pseudo LIKE :pseudo
        AND mail LIKE :mail';
        $update_token_temp = $base->prepare($update_token_temp);
        $update_token_temp->bindValue('id', $id, PDO::PARAM_INT);
        $update_token_temp->bindValue('token_temp', $token_temp, PDO::PARAM_STR);
        $update_token_temp->bindValue('date_token_created', $date_token_created, PDO::PARAM_INT);
        $update_token_temp->bindValue('lastname', $lastname, PDO::PARAM_STR);
        $update_token_temp->bindValue('firstname', $firstname, PDO::PARAM_STR);
        $update_token_temp->bindValue('pseudo', $pseudo, PDO::PARAM_STR);
        $update_token_temp->bindValue('mail', $mail, PDO::PARAM_STR);
        $update_token_temp->execute();


        $request_type = 'Password Reset';
        addToRequestsList($id, $lastname, $firstname, $pseudo, $mail, $token_temp, $request_type, $date_token_created);
        sendMailResetPass($lastname, $firstname, $pseudo, $mail, $token_temp, $lang);
      } catch (\Exception $e) {
        echo returnError($display_error_error_occured);
        exit();
      }

    }
    echo returnResponse($display_response_mail_sent);

  }else {
    echo returnError($display_error_empty_field);
    exit();
  }


/************Call to set the new password**********************/

}elseif (isset($request->token_temp)) {
  $token_temp = htmlspecialchars($request->token_temp, ENT_QUOTES);
  if(isset($request->password)){
    $password = htmlspecialchars($request->password, ENT_QUOTES);
  }else {
    $password = "";
  }
  $password = password_hash($password, PASSWORD_DEFAULT);

  $user_exists = false;
  $valid_link = false;
  // get user info
  try {
    $get_user_info = 'SELECT * FROM users WHERE token_temp LIKE :token_temp AND checked_mail LIKE 1 AND active_account LIKE 1';
    $get_user_info = $base->prepare($get_user_info);
    $get_user_info->bindValue('token_temp', $token_temp, PDO::PARAM_STR);
    $get_user_info->execute();
    while($user_info = $get_user_info->fetch())
    {
      $user_exists = true;
      $firstname = $user_info['firstname'];
      $lastname = $user_info['lastname'];
      $pseudo = $user_info['pseudo'];
      $mail = $user_info['mail'];
      $id = $user_info['id'];
      $date_token_created = $user_info['date_token_created'];
    }
  } catch (\Exception $e) {
    echo returnError($display_error_error_occured);
    exit();
  }

  // check if link has expired
  $valid_link = getLinkValidity($date_token_created);
  if($user_exists && $valid_link){
    $password_changed = false;
    try {
      $token_temp = "";
      $update_password = 'UPDATE users SET token_temp = :token_temp, password = :password
      WHERE id LIKE :id
      AND lastname LIKE :lastname
      AND firstname LIKE :firstname
      AND pseudo LIKE :pseudo
      AND mail LIKE :mail
      AND active_account LIKE 1';
      $update_password = $base->prepare($update_password);
      $update_password->bindValue('id', $id, PDO::PARAM_INT);
      $update_password->bindValue('token_temp', $token_temp, PDO::PARAM_STR);
      $update_password->bindValue('lastname', $lastname, PDO::PARAM_STR);
      $update_password->bindValue('firstname', $firstname, PDO::PARAM_STR);
      $update_password->bindValue('pseudo', $pseudo, PDO::PARAM_STR);
      $update_password->bindValue('mail', $mail, PDO::PARAM_STR);
      $update_password->bindValue('password', $password, PDO::PARAM_STR);
      $update_password->execute();
      $password_changed = true;
    } catch (\Exception $e) {
      echo returnError($display_error_error_occured);
      exit();
    }
    if($password_changed){
      echo returnResponse($display_response_password_changed);
    }else{
      echo returnError($display_error_error_occured);
      exit();
    }

  }else {
    echo returnError($display_error_link_expired);
    exit();
  }

}else{ // no mail sent
  exit();
}


 ?>
