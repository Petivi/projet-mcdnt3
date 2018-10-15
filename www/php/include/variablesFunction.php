<?php

// error
$display_error_error_occured = 'An Error Occured';
$display_error_link_expired = 'Link expired';
$display_error_mail_taken = 'Mail already taken';
$display_error_pseudo_taken = 'Pseudo already taken';
$display_error_insufficient_permissions = 'Insufficient permissions';
$display_error_wrong_password = 'Wrong password';
$display_error_account_suspended = 'Account Suspended';
$display_error_account_deleted = 'Account Deleted';
$display_error_account_not_activated = 'Account not activated';
$display_error_wrong_pseudo_password = 'Wrong pseudo/password';
$display_error_empty_field = 'Verify Empty Fields';
$display_error_empty = '';
// success
$display_response_mail_sent = 'Mail Sent';
$display_response_password_changed = 'Password changed';
$display_response_info_changed = 'Info Changed';
$display_response_empty = '';


// request_type
$request_type_new_account = "New Account";
$request_type_new_mail_confirm = "New Mail Confirm";
$request_type_password_reset = "Password Reset";


// function called when you want to response an error with a simple message
// index "error" will be considered as an error for angular (negative answer)
function returnError($errorMessage){
  $errorTab = array(
    "error" => $errorMessage
  );
  return json_encode($errorTab);
}


// function called when you want to response a tab or a message
// index "response" will be considered as a success for angular (positive answer)
function returnResponse($responseMessage){
  $responseTab = array(
    "response" => $responseMessage
  );
  return json_encode($responseTab);
}


//check if user has admin permissions
function accessToAdminPermissions($id, $lastname, $firstname, $pseudo, $mail, $session_token){
  global $base;
  $userExists = false;
  $get_user_info = 'SELECT * FROM users
   WHERE id LIKE :id
   AND lastname LIKE :lastname
   AND firstname LIKE :firstname
   AND pseudo LIKE :pseudo
   AND mail LIKE :mail
   AND session_token LIKE :session_token
   AND active_account LIKE 1';
  $get_user_info = $base->prepare($get_user_info);
  $get_user_info->bindValue('id', $id, PDO::PARAM_INT);
  $get_user_info->bindValue('lastname', $lastname, PDO::PARAM_STR);
  $get_user_info->bindValue('firstname', $firstname, PDO::PARAM_STR);
  $get_user_info->bindValue('pseudo', $pseudo, PDO::PARAM_STR);
  $get_user_info->bindValue('mail', $mail, PDO::PARAM_STR);
  $get_user_info->bindValue('session_token', $session_token, PDO::PARAM_STR);
  $get_user_info->execute();
  while($user_info = $get_user_info->fetch())
  {
    $userExists = true;
    $user_permissions = $user_info['permissions'];
  }
  if($userExists){
    return checkIfAdmin($user_permissions);
  }else {
    return false;
  }
}

// generate a token for mails
function generateTokenTemp(){
  $alphabet = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWYXZ0123456789-";
  $a1 = "";
  $a2 = "";
  $a3 = "";
  for ($i = 0; $i<3; $i++){
    $a1 .= $alphabet[rand(0, strlen($alphabet)-1)];
    $a3 .= $alphabet[rand(0, strlen($alphabet)-1)];
  }
  for ($i = 0; $i<6; $i++){
    $a2 .= $alphabet[rand(0, strlen($alphabet)-1)];
  }
  $token_temp = $a1 . "-" . $a2 . "-" . $a3;
  return $token_temp;
}

// generate a session token
function generateSessionToken(){
  $alphabet = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWYXZ0123456789-";
  $a1 = "";
  $a2 = "";
  $a3 = "";
  $a4 = "";
  for ($i = 0; $i<10; $i++){
    $a1 .= $alphabet[rand(0, strlen($alphabet)-1)];
    $a2 .= $alphabet[rand(0, strlen($alphabet)-1)];
    $a3 .= $alphabet[rand(0, strlen($alphabet)-1)];
    $a4 .= $alphabet[rand(0, strlen($alphabet)-1)];
  }
  $b1 = date('d');
  $b2 = date('m');
  $b3 = date('y');
  $b4 = date('H');
  $b5 = date('i');
  $session_token = $b1 . $b2 . $b3 .  $a1 . $a2 . $a3 . $a4 . $b4 . $b5;
  return $session_token;
}

function editUserPassword($lastname, $firstname, $pseudo, $mail, $id, $password, $session_token){
  global $base;
  global $display_error_error_occured;
  $password_changed = false;
  try {
    $update_new_password = 'UPDATE users SET password = :password
    WHERE id LIKE :id
    AND lastname LIKE :lastname
    AND firstname LIKE :firstname
    AND pseudo LIKE :pseudo
    AND mail LIKE :mail
    AND session_token LIKE :session_token
    AND active_account LIKE 1';
    $update_new_password = $base->prepare($update_new_password);
    $update_new_password->bindValue('lastname', $lastname, PDO::PARAM_STR);
    $update_new_password->bindValue('firstname', $firstname, PDO::PARAM_STR);
    $update_new_password->bindValue('pseudo', $pseudo, PDO::PARAM_STR);
    $update_new_password->bindValue('mail', $mail, PDO::PARAM_STR);
    $update_new_password->bindValue('id', $id, PDO::PARAM_INT);
    $update_new_password->bindValue('session_token', $session_token, PDO::PARAM_STR);
    $update_new_password->bindValue('password', $password, PDO::PARAM_STR);
    $update_new_password->execute();
    $password_changed = true;
  } catch (\Exception $e) {
    echo returnError($display_error_error_occured);
    exit();
  }
  if($password_changed){
    return true;
  }else {
    return false;
  }

}


function getLinkValidity($date_token_created){
  global $token_expiration_time;
  $today_date = strtotime(date('d-m-Y'));
  if($today_date < ($date_token_created + $token_expiration_time)){
    return true;
  }else {
    return false;
  }
}

function addToRequestsList($id, $lastname, $firstname, $pseudo, $mail, $token_temp, $request_type, $date_token_created){
  global $base;
  global $request_type_new_account;
  global $request_type_new_mail_confirm;
  global $request_type_password_reset;
  $add_to_requests_list = 'INSERT INTO requests_list (user_id, user_lastname, user_firstname, user_pseudo, user_mail, request_token, request_type, request_date)
  VALUES (:id, :lastname, :firstname, :pseudo, :mail, :token_temp, :request_type, :date_token_created)';
  $add_to_requests_list = $base->prepare($add_to_requests_list);
  $add_to_requests_list->bindValue('id', $id, PDO::PARAM_INT);
  $add_to_requests_list->bindValue('lastname', $lastname, PDO::PARAM_STR);
  $add_to_requests_list->bindValue('firstname', $firstname, PDO::PARAM_STR);
  $add_to_requests_list->bindValue('pseudo', $pseudo, PDO::PARAM_STR);
  $add_to_requests_list->bindValue('mail', $mail, PDO::PARAM_STR);
  $add_to_requests_list->bindValue('token_temp', $token_temp, PDO::PARAM_STR);
  $add_to_requests_list->bindValue('request_type', $request_type, PDO::PARAM_STR);
  $add_to_requests_list->bindValue('date_token_created', $date_token_created, PDO::PARAM_INT);
  $add_to_requests_list->execute();
}


 ?>
