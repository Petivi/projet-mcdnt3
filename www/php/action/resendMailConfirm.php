<?php
require_once('../config.php');
include "../includedFiles.php";



if(isset($request->mail)){
  $mail = htmlspecialchars($request->mail, ENT_QUOTES);
  $lang = htmlspecialchars($request->lang, ENT_QUOTES);
  $token_temp = "";

  $get_user_info = 'SELECT * FROM users WHERE mail LIKE :mail AND checked_mail LIKE 0 AND active_account LIKE 1';
  $get_user_info = $base->prepare($get_user_info);
  $get_user_info->bindValue('mail', $mail, PDO::PARAM_STR);
  $get_user_info->execute();
  while($user_info = $get_user_info->fetch())
  {
    $firstname = $user_info['firstname'];
    $lastname = $user_info['lastname'];
    $pseudo = $user_info['pseudo'];
    $mail = $user_info['mail'];
    $token_temp = $user_info['token_temp'];

    if($token_temp != ""){
      sendMailNewUser($lastname, $firstname, $pseudo, $mail, $token_temp, $lang);
    }
  }

}else{
  echo returnError("No mail");
}

 ?>
