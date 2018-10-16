<?php
require_once('../config.php');
include "../includedFiles.php";

$tabUser = getPostInfo($request);

$get_user_info = 'SELECT * FROM users WHERE session_token LIKE :session_token AND active_account LIKE 1 AND checked_mail LIKE 1';
$get_user_info = $base->prepare($get_user_info);
$get_user_info->bindValue('session_token', $tabUser['session_token'], PDO::PARAM_STR);
$get_user_info->execute();
while($user_info = $get_user_info->fetch())
{
  // get his informations
  $account_id = $user_info['id'];
  $account_lastname = $user_info['lastname'];
  $account_firstname = $user_info['firstname'];
  $account_pseudo = $user_info['pseudo'];
  $account_mail = $user_info['mail'];
  $account_permissions = $user_info['permissions'];
}

  // if admin
if(checkIfAdmin($account_permissions)){
  echo returnResponse($display_response_empty);
}else {
  echo returnError($display_error_empty);
}




 ?>
