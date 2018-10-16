<?php

require_once('../config.php');
include "../includedFiles.php";


if(isset($request->session_token)){
  $session_token = htmlspecialchars($request->session_token, ENT_QUOTES);
}else{
  $session_token = "";
}

if(isset($request->lang)){
  $lang = htmlspecialchars($request->lang, ENT_QUOTES);
}else {
  $lang = "en";
}

$user_exists = false;
$get_user_info = 'SELECT * FROM users WHERE session_token LIKE :session_token AND checked_mail LIKE 1 AND active_account LIKE 1';
$get_user_info = $base->prepare($get_user_info);
$get_user_info->bindValue('session_token', $session_token, PDO::PARAM_STR);
$get_user_info->execute();
while($user_info = $get_user_info->fetch())
{
  $user_exists = true;
  $id = $user_info['id'];
  $lastname = $user_info['lastname'];
  $firstname = $user_info['firstname'];
  $pseudo = $user_info['pseudo'];
  $mail = $user_info['mail'];
  $token_temp = $user_info['token_temp'];
}


if($user_exists){
  try {
    $request_unsubscribe_account = 'UPDATE users
    SET session_token = "",
    token_temp = "",
    active_account = 2
    WHERE active_account LIKE 1
    AND session_token LIKE :session_token';
    $request_unsubscribe_account = $base->prepare($request_unsubscribe_account);
    $request_unsubscribe_account->bindValue('session_token', $session_token, PDO::PARAM_STR);
    $request_unsubscribe_account->execute();

    $date_action = strtotime(date('d-m-Y H:i:s'));
    $token_temp = "";
    addToRequestsList($id, $lastname, $firstname, $pseudo, $mail, $token_temp, $request_type_unsubscribe, $date_action);
    sendMailUnsubscribe($lastname, $firstname, $pseudo, $mail, $token_temp, $lang);
    echo returnResponse($display_response_account_deleted);
  } catch (\Exception $e) {
    echo returnError($display_error_error_occured);
    exit();
  }
}else {
  echo returnError($display_error_empty);
  exit();
}

 ?>
