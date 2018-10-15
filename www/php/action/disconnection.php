<?php

require_once('../config.php');
include "../includedFiles.php";

if(isset($request->lastname)){
  $lastname = htmlspecialchars($request->lastname, ENT_QUOTES);
}else{
  $lastname = "";
}
if(isset($request->firstname)){
  $firstname = htmlspecialchars($request->firstname, ENT_QUOTES);
}else{
  $firstname = "";
}
if(isset($request->pseudo)){
  $pseudo = htmlspecialchars($request->pseudo, ENT_QUOTES);
}else{
  $pseudo = "";
}
if(isset($request->mail)){
  $mail = htmlspecialchars($request->mail, ENT_QUOTES);
}else{
  $mail = "";
}
if(isset($request->id)){
  $id = htmlspecialchars($request->id, ENT_QUOTES);
}else{
  $id = "";
}
if(isset($request->session_token)){
  $session_token = htmlspecialchars($request->session_token, ENT_QUOTES);
}else{
  $session_token = "";
}

$update_session_token = 'UPDATE users SET session_token = ""
WHERE id LIKE :id
AND lastname LIKE :lastname
AND firstname LIKE :firstname
AND pseudo LIKE :pseudo
AND mail LIKE :mail
AND session_token LIKE :session_token
AND active_account LIKE 1';
$update_session_token = $base->prepare($update_session_token);
$update_session_token->bindValue('id', $id, PDO::PARAM_INT);
$update_session_token->bindValue('lastname', $lastname, PDO::PARAM_STR);
$update_session_token->bindValue('firstname', $firstname, PDO::PARAM_STR);
$update_session_token->bindValue('pseudo', $pseudo, PDO::PARAM_STR);
$update_session_token->bindValue('mail', $mail, PDO::PARAM_STR);
$update_session_token->bindValue('session_token', $session_token, PDO::PARAM_STR);
$update_session_token->execute();

 ?>
