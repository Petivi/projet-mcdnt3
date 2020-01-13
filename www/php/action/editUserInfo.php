<?php
require_once('../config.php');
include "../includedFiles.php";


$tabUser = getPostInfo($request->user);
$tabNewUser = getPostInfo($request->newUser);

if(isset($request->lang)){
  $lang = htmlspecialchars($request->lang, ENT_QUOTES);
}else {
  $lang = "en";
}


$user_exists = false;
try {
  $get_user_info = 'SELECT * FROM users WHERE lastname LIKE :lastname AND firstname LIKE :firstname AND pseudo LIKE :pseudo AND mail LIKE :mail AND id LIKE :id AND session_token LIKE :session_token AND active_account LIKE 1';
  $get_user_info = $base->prepare($get_user_info);
  $get_user_info->bindValue('lastname', Chiffrement::crypt($tabUser['lastname']), PDO::PARAM_STR);
  $get_user_info->bindValue('firstname', Chiffrement::crypt($tabUser['firstname']), PDO::PARAM_STR);
  $get_user_info->bindValue('pseudo', Chiffrement::crypt($tabUser['pseudo']), PDO::PARAM_STR);
  $get_user_info->bindValue('mail', Chiffrement::crypt($tabUser['mail']), PDO::PARAM_STR);
  $get_user_info->bindValue('session_token', $tabUser['session_token'], PDO::PARAM_STR);
  $get_user_info->bindValue('id', $tabUser['id'], PDO::PARAM_INT);
  $get_user_info->execute();
  while($user_info = $get_user_info->fetch())
  {
    $user_exists = true;
  }
} catch (\Exception $e) {
  return returnError($display_error_empty);
  exit();
}


if($user_exists){

  if(returnCheckPseudo($tabNewUser['pseudo'], $tabUser['id'])){
    echo returnError($display_error_pseudo_taken);
  }else { // pseudo is unique

    if (returnCheckMail($tabNewUser['mail'], $tabUser['id'])) {
      echo returnError($display_error_mail_taken);
    }else { // mail is unique

      if(returnCheckLogin($tabNewUser['login'], $tabUser['id'])){
        echo returnError($display_error_login_taken);
      }else { // login is unique

        try {
          $update_user_info = 'UPDATE users
          SET lastname = :newLastname,
          firstname = :newFirstname,
          pseudo = :newPseudo,
          login = :newLogin
          WHERE id LIKE :id
          AND active_account LIKE 1
          AND lastname LIKE :lastname
          AND firstname LIKE :firstname
          AND pseudo LIKE :pseudo
          AND mail LIKE :mail
          AND session_token LIKE :session_token';
          $update_user_info = $base->prepare($update_user_info);
          $update_user_info->bindValue('id', $tabUser['id'], PDO::PARAM_INT);
          $update_user_info->bindValue('newLastname', Chiffrement::crypt($tabNewUser['lastname']), PDO::PARAM_STR);
          $update_user_info->bindValue('newFirstname', Chiffrement::crypt($tabNewUser['firstname']), PDO::PARAM_STR);
          $update_user_info->bindValue('newPseudo', Chiffrement::crypt($tabNewUser['pseudo']), PDO::PARAM_STR);
          $update_user_info->bindValue('newLogin', Chiffrement::crypt($tabNewUser['login']), PDO::PARAM_STR);
          $update_user_info->bindValue('lastname', Chiffrement::crypt($tabUser['lastname']), PDO::PARAM_STR);
          $update_user_info->bindValue('firstname', Chiffrement::crypt($tabUser['firstname']), PDO::PARAM_STR);
          $update_user_info->bindValue('pseudo', Chiffrement::crypt($tabUser['pseudo']), PDO::PARAM_STR);
          $update_user_info->bindValue('mail', Chiffrement::crypt($tabUser['mail']), PDO::PARAM_STR);
          $update_user_info->bindValue('session_token', $tabUser['session_token'], PDO::PARAM_STR);
          $update_user_info->execute();

          if($tabUser['mail'] != $tabNewUser['mail']){
            $token_temp = generateTokenTemp();
            $date_token_created = strtotime(date('d-m-Y H:i:s'));
            $update_user_mail = 'UPDATE users
            SET mail = :newMail,
            checked_mail = 0,
            date_token_created = :date_token_created,
            token_temp = :token_temp
            WHERE id LIKE :id
            AND active_account LIKE 1
            AND mail LIKE :mail
            AND session_token LIKE :session_token';
            $update_user_mail = $base->prepare($update_user_mail);
            $update_user_mail->bindValue('id', $tabUser['id'], PDO::PARAM_INT);
            $update_user_mail->bindValue('newMail', Chiffrement::crypt($tabNewUser['mail']), PDO::PARAM_STR);
            $update_user_mail->bindValue('mail', Chiffrement::crypt($tabUser['mail']), PDO::PARAM_STR);
            $update_user_mail->bindValue('date_token_created', $date_token_created, PDO::PARAM_INT);
            $update_user_mail->bindValue('token_temp', $token_temp, PDO::PARAM_STR);
            $update_user_mail->bindValue('session_token', $tabUser['session_token'], PDO::PARAM_STR);
            $update_user_mail->execute();

            addToRequestsList($tabUser['id'], $tabNewUser['lastname'], $tabNewUser['firstname'], $tabNewUser['pseudo'], $tabNewUser['mail'], $token_temp, $request_type_edit_mail, $date_token_created);
            sendMailEditMail($tabNewUser['lastname'], $tabNewUser['firstname'], $tabNewUser['pseudo'], $tabNewUser['mail'], $token_temp, $lang);
          }
          echo returnResponse($display_response_info_changed);
        } catch (\Exception $e) {
          echo returnError($display_error_error_occured);
          echo $e;
          exit();
        }
      } // login unique
    } // mail unique
  } // pseudo unique

}else { // user doesn't exists
  echo returnError($display_error_empty);
  exit();
}



 ?>
