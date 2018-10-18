<?php
require_once('../config.php');
include "../includedFiles.php";

$tabUser = getPostInfo($request);

if($tabUser['session_token'] && $tabUser['lang']){ // if we have a session_token

  try {
    $update_user_lang = 'UPDATE users
    SET lang = :lang
    WHERE session_token LIKE :session_token';
    $update_user_lang = $base->prepare($update_user_lang);
    $update_user_lang->bindValue('lang', strtolower($tabUser['lang']), PDO::PARAM_INT);
    $update_user_lang->bindValue('session_token', $tabUser['session_token'], PDO::PARAM_STR);
    $update_user_lang->execute();
    returnResponse($display_response_empty);
  } catch (\Exception $e) {
    returnError($display_error_error_occured);
    exit();
  }



}else {
  returnError($display_error_empty);
  exit();
}



 ?>
