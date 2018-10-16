<?php
require_once('../config.php');
include "../includedFiles.php";

if(isset($request)){
$requestUser = $request->user;
$requestNewUser = $request->newUser;
}

/************New User Info*************************/
if(isset($requestNewUser->lastname)){
  $newLastname = htmlspecialchars($requestNewUser->lastname, ENT_QUOTES);
}else{
  $newLastname = "";
}
if(isset($requestNewUser->firstname)){
  $newFirstname = htmlspecialchars($requestNewUser->firstname, ENT_QUOTES);
}else{
  $newFirstname = "";
}
if(isset($requestNewUser->pseudo)){
  $newPseudo = htmlspecialchars($requestNewUser->pseudo, ENT_QUOTES);
}else{
  $newPseudo = "";
}
if(isset($requestNewUser->mail)){
  $newMail = htmlspecialchars($requestNewUser->mail, ENT_QUOTES);
}else{
  $newMail = "";
}



/*************Old User Info**************************/
if(isset($requestUser->lastname)){
  $lastname = htmlspecialchars($requestUser->lastname, ENT_QUOTES);
}else{
  $lastname = "";
}
if(isset($requestUser->firstname)){
  $firstname = htmlspecialchars($requestUser->firstname, ENT_QUOTES);
}else{
  $firstname = "";
}
if(isset($requestUser->pseudo)){
  $pseudo = htmlspecialchars($requestUser->pseudo, ENT_QUOTES);
}else{
  $pseudo = "";
}
if(isset($requestUser->mail)){
  $mail = htmlspecialchars($requestUser->mail, ENT_QUOTES);
}else{
  $mail = "";
}

if(isset($requestUser->id)){
  $id = htmlspecialchars($requestUser->id, ENT_QUOTES);
}else{
  $id = "";
}
if(isset($requestUser->session_token)){
  $session_token = htmlspecialchars($requestUser->session_token, ENT_QUOTES);
}else{
  $session_token = "";
}

if(isset($request->lang)){
  $lang = htmlspecialchars($request->lang, ENT_QUOTES);
}else {
  $lang = "en";
}


$user_exists = false;
try {
  $get_user_info = 'SELECT * FROM users WHERE lastname LIKE :lastname AND firstname LIKE :firstname AND pseudo LIKE :pseudo AND mail LIKE :mail AND id LIKE :id AND session_token LIKE :session_token AND active_account LIKE 1';
  $get_user_info = $base->prepare($get_user_info);
  $get_user_info->bindValue('lastname', $lastname, PDO::PARAM_STR);
  $get_user_info->bindValue('firstname', $firstname, PDO::PARAM_STR);
  $get_user_info->bindValue('pseudo', $pseudo, PDO::PARAM_STR);
  $get_user_info->bindValue('mail', $mail, PDO::PARAM_STR);
  $get_user_info->bindValue('session_token', $session_token, PDO::PARAM_STR);
  $get_user_info->bindValue('id', $id, PDO::PARAM_INT);
  $get_user_info->execute();
  while($user_info = $get_user_info->fetch())
  {
    $user_exists = true;
  }
} catch (\Exception $e) {
  return returnError($display_error_empty);
  exit();
}


if($user_exists){

  if(returnCheckPseudo($newPseudo, $id)){
    echo returnError($display_error_pseudo_taken);
  }else {

    if (returnCheckMail($newMail, $id)) {
      echo returnError($display_error_mail_taken);
    }else {

      try {
        $update_user_info = 'UPDATE users
        SET lastname = :newLastname,
        firstname = :newFirstname,
        pseudo = :newPseudo
        WHERE id LIKE :id
        AND active_account LIKE 1
        AND lastname LIKE :lastname
        AND firstname LIKE :firstname
        AND pseudo LIKE :pseudo
        AND mail LIKE :mail
        AND session_token LIKE :session_token';
        $update_user_info = $base->prepare($update_user_info);
        $update_user_info->bindValue('id', $id, PDO::PARAM_INT);
        $update_user_info->bindValue('newLastname', $newLastname, PDO::PARAM_STR);
        $update_user_info->bindValue('newFirstname', $newFirstname, PDO::PARAM_STR);
        $update_user_info->bindValue('newPseudo', $newPseudo, PDO::PARAM_STR);
        $update_user_info->bindValue('lastname', $lastname, PDO::PARAM_STR);
        $update_user_info->bindValue('firstname', $firstname, PDO::PARAM_STR);
        $update_user_info->bindValue('pseudo', $pseudo, PDO::PARAM_STR);
        $update_user_info->bindValue('mail', $mail, PDO::PARAM_STR);
        $update_user_info->bindValue('session_token', $session_token, PDO::PARAM_STR);
        $update_user_info->execute();
        echo returnResponse($display_response_info_changed);
      } catch (\Exception $e) {
        echo returnError($display_error_error_occured);
        exit();
      }

      if($mail != $newMail){
        try {
          $token_temp = generateTokenTemp();
          $date_token_created = strtotime(date('d-m-Y H:i:s'));
          $update_user_mail = 'UPDATE users
          SET mail = :newMail,
          checked_mail = 0,
          date_token_created = :date_token_created,
          token_temp = :token_temp
          WHERE id LIKE :id
          AND active_account LIKE 1
          AND mail LIKE :mail
          AND session_token LIKE :session_token';
          $update_user_mail = $base->prepare($update_user_mail);
          $update_user_mail->bindValue('id', $id, PDO::PARAM_INT);
          $update_user_mail->bindValue('newMail', $newMail, PDO::PARAM_STR);
          $update_user_mail->bindValue('mail', $oldMail, PDO::PARAM_STR);
          $update_user_mail->bindValue('date_token_created', $date_token_created, PDO::PARAM_INT);
          $update_user_mail->bindValue('token_temp', $token_temp, PDO::PARAM_STR);
          $update_user_mail->bindValue('session_token', $session_token, PDO::PARAM_STR);
          $update_user_mail->execute();
          
          addToRequestsList($account_id, $account_lastname, $account_firstname, $account_pseudo, $newMail, $token_temp, $request_type_edit_mail, $date_token_created);
          sendMailEditMail($account_lastname, $account_firstname, $account_pseudo, $newMail, $token_temp, $lang);
          echo returnResponse($display_response_mail_sent);
        } catch (\Exception $e) {
          echo returnError($display_error_error_occured);
          exit();
        }
      }


    } // mail unique
  } // pseudo unique

}else { // user doesn't exists
  echo returnError($display_error_empty);
  exit();
}



 ?>
