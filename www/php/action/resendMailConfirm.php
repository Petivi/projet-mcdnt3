<?php
require_once('../config.php');
include "../includedFiles.php";



if(isset($request->mail)){
  $mail = htmlspecialchars($request->mail, ENT_QUOTES);
  if(isset($request->lang)){
    $lang = htmlspecialchars($request->lang, ENT_QUOTES);
  }else {
    $lang = "en";
  }

  $get_user_info = 'SELECT * FROM users WHERE mail LIKE :mail AND checked_mail LIKE 0 AND active_account LIKE 1';
  $get_user_info = $base->prepare($get_user_info);
  $get_user_info->bindValue('mail', Chiffrement::crypt($mail), PDO::PARAM_STR);
  $get_user_info->execute();
  while($user_info = $get_user_info->fetch())
  {
    $id = $user_info['id'];
    $lastname = Chiffrement::decrypt($user_info['lastname']);
    $firstname = Chiffrement::decrypt($user_info['firstname']);
    $pseudo = Chiffrement::decrypt($user_info['pseudo']);
    $mail = Chiffrement::decrypt($user_info['mail']);
    $token_temp = $user_info['token_temp'];
    $date_token_created = $user_info['date_token_created'];

    if($token_temp != ""){
      // new mail confirm
      addToRequestsList($id, $lastname, $firstname, $pseudo, $mail, $token_temp, $request_type_new_mail_confirm, $date_token_created);
      sendMailNewUser($lastname, $firstname, $pseudo, $mail, $token_temp, $lang);
      echo returnResponse($display_response_mail_sent);
    }
  }

}else{
  echo returnError($display_error_empty_field);
  exit();
}

 ?>
