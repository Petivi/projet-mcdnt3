<?php
require_once('../config.php');
include "../includedFiles.php";

// check if we receive a mail
if(isset($request->mail)){
  $mail = htmlspecialchars($request->mail, ENT_QUOTES);
  if(isset($request->lang)){
    $lang = htmlspecialchars($request->lang, ENT_QUOTES);
  }else {
    $lang = "en";
  }

  // if mail ain't empty
  if($mail != ""){
    $user_exists = false;
    try {
      $get_user_info = 'SELECT * FROM users WHERE mail LIKE :mail AND checked_mail LIKE 1 AND active_account LIKE 1';
      $get_user_info = $base->prepare($get_user_info);
      $get_user_info->bindValue('mail', $mail, PDO::PARAM_STR);
      $get_user_info->execute();
      while($user_info = $get_user_info->fetch())
      {
        $user_exists = true;
        $firstname = $user_info['firstname'];
        $lastname = $user_info['lastname'];
        $pseudo = $user_info['pseudo'];
        $mail = $user_info['mail'];
        $id = $user_info['id'];
      }
    } catch (\Exception $e) {

    }

    // user found according to mail
    if($user_exists){
      $token_temp = generateTokenTemp();

      try {
        $update_token_temp = 'UPDATE users
        SET token_temp = :token_temp
        WHERE id LIKE :id
        AND lastname LIKE :lastname
        AND firstname LIKE :firstname
        AND pseudo LIKE :pseudo
        AND mail LIKE :mail';
        $update_token_temp = $base->prepare($update_token_temp);
        $update_token_temp->bindValue('id', $id, PDO::PARAM_INT);
        $update_token_temp->bindValue('token_temp', $token_temp, PDO::PARAM_STR);
        $update_token_temp->bindValue('lastname', $lastname, PDO::PARAM_STR);
        $update_token_temp->bindValue('firstname', $firstname, PDO::PARAM_STR);
        $update_token_temp->bindValue('pseudo', $pseudo, PDO::PARAM_STR);
        $update_token_temp->bindValue('mail', $mail, PDO::PARAM_STR);
        $update_token_temp->execute();
      } catch (\Exception $e) {

      }

      sendMailResetPass($lastname, $firstname, $pseudo, $mail, $token_temp, $lang);

    }

  }else {
    echo returnError("No mail");
  }
}elseif (isset($request->token_temp)) {

}else{ // no mail sent
  exit();
}


 ?>
