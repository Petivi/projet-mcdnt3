<?php
require_once('../config.php');
include "../includedFiles.php";



if(isset($request)){
  $requestUser = $request->user;
}

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

if(isset($request->oldPassword)){
  $oldPassword = htmlspecialchars($request->oldPassword, ENT_QUOTES);
}else{
  $oldPassword = "";
}
if(isset($request->newPassword)){
  $newPassword = htmlspecialchars($request->newPassword, ENT_QUOTES);
}else{
  $newPassword = "";
}



$user_exists = false;
// check if user exists
try {
  $get_user_info = 'SELECT * FROM users
  WHERE lastname LIKE :lastname
  AND firstname LIKE :firstname
  AND pseudo LIKE :pseudo
  AND mail LIKE :mail
  AND id LIKE :id
  AND session_token LIKE :session_token
  AND active_account LIKE 1';
  $get_user_info = $base->prepare($get_user_info);
  $get_user_info->bindValue('lastname', $lastname, PDO::PARAM_STR);
  $get_user_info->bindValue('firstname', $firstname, PDO::PARAM_STR);
  $get_user_info->bindValue('pseudo', $pseudo, PDO::PARAM_STR);
  $get_user_info->bindValue('mail', $mail, PDO::PARAM_STR);
  $get_user_info->bindValue('id', $id, PDO::PARAM_INT);
  $get_user_info->bindValue('session_token', $session_token, PDO::PARAM_STR);
  $get_user_info->execute();
  while($user_info = $get_user_info->fetch())
  {
    $user_exists = true;
    $account_lastname = $user_info['lastname'];
    $account_firstname = $user_info['firstname'];
    $account_pseudo = $user_info['pseudo'];
    $account_mail = $user_info['mail'];
    $account_id = $user_info['id'];
    $account_password = $user_info['password'];
    $account_session_token = $user_info['session_token'];
  }
} catch (\Exception $e) {
  echo returnError($display_error_error_occured);
  exit();
}


if($user_exists){
  if(password_verify($oldPassword, $account_password)){
    $password = password_hash($newPassword, PASSWORD_DEFAULT);

    if(editUserPassword($account_lastname, $account_firstname, $account_pseudo, $account_mail, $account_id, $account_session_token, $password)){
      echo returnResponse($display_response_password_changed);
    }else {
      echo returnError($display_error_error_occured);
      exit();
    }
  }else {
    echo returnError($display_error_wrong_password);
    exit();
  }
}else { // trying to get someone's else info, so no response to this guy
  echo returnError($display_error_empty);
  exit();
}

 ?>
