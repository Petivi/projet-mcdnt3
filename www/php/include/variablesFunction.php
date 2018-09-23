<?php

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
function accessToAdminPermissions($id, $lastname, $firstname, $pseudo, $mail){
  global $base;
  $userExists = false;
  $get_user_info = 'SELECT * FROM users
   WHERE id LIKE :id
   AND lastname LIKE :lastname
   AND firstname LIKE :firstname
   AND pseudo LIKE :pseudo
   AND mail LIKE :mail
   AND active_account LIKE 1';
  $get_user_info = $base->prepare($get_user_info);
  $get_user_info->bindValue('id', $id, PDO::PARAM_INT);
  $get_user_info->bindValue('lastname', $lastname, PDO::PARAM_STR);
  $get_user_info->bindValue('firstname', $firstname, PDO::PARAM_STR);
  $get_user_info->bindValue('pseudo', $pseudo, PDO::PARAM_STR);
  $get_user_info->bindValue('mail', $mail, PDO::PARAM_STR);
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

function editUserPassword($lastname, $firstname, $pseudo, $mail, $id, $password){
  global $base;
  $password_changed = false;
  try {
    $update_new_password = 'UPDATE users SET password = :password
    WHERE id LIKE :id
    AND lastname LIKE :lastname
    AND firstname LIKE :firstname
    AND pseudo LIKE :pseudo
    AND mail LIKE :mail
    AND active_account LIKE 1';
    $update_new_password = $base->prepare($update_new_password);
    $update_new_password->bindValue('lastname', $lastname, PDO::PARAM_STR);
    $update_new_password->bindValue('firstname', $firstname, PDO::PARAM_STR);
    $update_new_password->bindValue('pseudo', $pseudo, PDO::PARAM_STR);
    $update_new_password->bindValue('mail', $mail, PDO::PARAM_STR);
    $update_new_password->bindValue('id', $id, PDO::PARAM_INT);
    $update_new_password->bindValue('password', $password, PDO::PARAM_STR);
    $update_new_password->execute();
    $password_changed = true;
  } catch (\Exception $e) {
    echo returnError("An Error Occured");
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
