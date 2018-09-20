<?php
require_once('../config.php');
include "../includedFiles.php";

$requestUser = $request->user;
$requestNewUser = $request->newUser;

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

if(isset($requestUser->userId)){
  $id = htmlspecialchars($requestUser->userId, ENT_QUOTES);
}else{
  $id = "";
}


$update_user_info = 'UPDATE users
 SET lastname = :newLastname,
     firstname = :newFirstname,
     pseudo = :newPseudo,
     mail = :newMail
 WHERE id LIKE :id
 AND active_account LIKE 1
 AND lastname LIKE :lastname
 AND firstname LIKE :firstname
 AND pseudo LIKE :pseudo
 AND mail LIKE :mail';
$update_user_info = $base->prepare($update_user_info);
$update_user_info->bindValue('id', $id, PDO::PARAM_INT);
$update_user_info->bindValue('newLastname', $newLastname, PDO::PARAM_STR);
$update_user_info->bindValue('newFirstname', $newFirstname, PDO::PARAM_STR);
$update_user_info->bindValue('newPseudo', $newPseudo, PDO::PARAM_STR);
$update_user_info->bindValue('newMail', $newMail, PDO::PARAM_STR);
$update_user_info->bindValue('lastname', $lastname, PDO::PARAM_STR);
$update_user_info->bindValue('firstname', $firstname, PDO::PARAM_STR);
$update_user_info->bindValue('pseudo', $pseudo, PDO::PARAM_STR);
$update_user_info->bindValue('mail', $mail, PDO::PARAM_STR);
$update_user_info->execute();

 ?>
