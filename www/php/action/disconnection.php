<?php

require_once('../config.php');
include "../includedFiles.php";

$tabUser = getPostInfo($request);


$update_session_token = 'UPDATE users SET session_token = ""
WHERE id LIKE :id
AND lastname LIKE :lastname
AND firstname LIKE :firstname
AND pseudo LIKE :pseudo
AND mail LIKE :mail
AND session_token LIKE :session_token
AND active_account LIKE 1';
$update_session_token = $base->prepare($update_session_token);
$update_session_token->bindValue('id', $tabUser['id'], PDO::PARAM_INT);
$update_session_token->bindValue('lastname', $tabUser['lastname'], PDO::PARAM_STR);
$update_session_token->bindValue('firstname', $tabUser['firstname'], PDO::PARAM_STR);
$update_session_token->bindValue('pseudo', $tabUser['pseudo'], PDO::PARAM_STR);
$update_session_token->bindValue('mail', $tabUser['mail'], PDO::PARAM_STR);
$update_session_token->bindValue('session_token', $tabUser['session_token'], PDO::PARAM_STR);
$update_session_token->execute();
echo returnResponse($display_response_empty);
 ?>
