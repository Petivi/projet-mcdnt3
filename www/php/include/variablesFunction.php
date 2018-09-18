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
 ?>
