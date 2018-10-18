<?php
require_once('../config.php');
include "../includedFiles.php";


if(isset($request->session_token)){
  $session_token = htmlspecialchars($request->session_token, ENT_QUOTES);
  $tabInfoUser = array();

  $user_exists = false;
  $request_user_info = 'SELECT * FROM users WHERE session_token LIKE :session_token';
  $request_user_info = $base->prepare($request_user_info);
  $request_user_info->bindValue('session_token', $session_token, PDO::PARAM_STR);
  $request_user_info->execute();
  while($user_info = $request_user_info->fetch())
  {
    $user_exists = true;
    // get his informations
    $account_id = $user_info['id'];
    $account_lastname = Chiffrement::decrypt($user_info['lastname']);
    $account_firstname = Chiffrement::decrypt($user_info['firstname']);
    $account_pseudo = Chiffrement::decrypt($user_info['pseudo']);
    $account_password = $user_info['password'];
    $account_created_date = $user_info['created_date'];
    $account_last_connection = $user_info['last_connection'];
    $account_mail = Chiffrement::decrypt($user_info['mail']);
    $account_permissions = $user_info['permissions'];
    $account_active_account = $user_info['active_account'];
    $account_checked_mail = $user_info['checked_mail'];
  }

  if($user_exists){
    $tabInfoUser = array(
      "id" => $account_id,
      "lastname" => $account_lastname,
      "firstname" => $account_firstname,
      "pseudo" => $account_pseudo,
      "mail" => $account_mail,
      "last_connection" => $account_last_connection
    );

    echo returnResponse($tabInfoUser);
  }else {
    echo returnError($display_error_error_occured);
  }






}else { // no session_token sent, so we can't do anything
  echo returnError($display_error_empty);
  exit();
}

 ?>
