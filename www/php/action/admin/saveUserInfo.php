<?php
require_once('../../config.php');
include "../../includedFiles.php";


$tabUser = getPostInfo($request);
$tabVictim = getPostInfo($request->user);

if(accessToAdminPermissions($tabUser['session_token'])){
  if(isset($request->action)){
    $action = htmlspecialchars($request->action, ENT_QUOTES);
  }else {
    $action = "";
  }
  if(isset($request->comment)){
    $comment = htmlspecialchars($request->comment, ENT_QUOTES);
  }else {
    $comment = "";
  }

  $admin_id = getIdFromSessionToken($tabUser['session_token']);
  $user_lang = getLangFromId($tabVictim['id']);

  $user_exists = false;
  $request_user_info = 'SELECT * FROM users WHERE id LIKE :id';
  $request_user_info = $base->prepare($request_user_info);
  $request_user_info->bindValue('id', $tabVictim['id'], PDO::PARAM_INT);
  $request_user_info->execute();
  while($user_info = $request_user_info->fetch())
  {
    $user_exists = true;
  }

  if($user_exists){
    if(returnCheckPseudo($tabVictim['pseudo'], intval($tabVictim['id']))){
      echo returnError($display_error_pseudo_taken);
      exit();
    }else {
      $date_action = strtotime(date('d-m-Y H:i:s'));
      $update_user_infos = 'UPDATE users
      SET lastname = :lastname,
      firstname = :firstname,
      pseudo = :pseudo,
      active_account = :active_account,
      session_token = :session_token
      WHERE id LIKE :id';
      $update_user_infos = $base->prepare($update_user_infos);
      $update_user_infos->bindValue('id', $tabVictim['id'], PDO::PARAM_INT);
      $update_user_infos->bindValue('lastname', Chiffrement::crypt($tabVictim['lastname']), PDO::PARAM_STR);
      $update_user_infos->bindValue('firstname', Chiffrement::crypt($tabVictim['firstname']), PDO::PARAM_STR);
      $update_user_infos->bindValue('pseudo', Chiffrement::crypt($tabVictim['pseudo']), PDO::PARAM_STR);
      $update_user_infos->bindValue('active_account', $tabVictim['active_account'], PDO::PARAM_INT);
      $update_user_infos->bindValue('session_token', "", PDO::PARAM_STR);
      $update_user_infos->execute();
      addToAdminUsersManagement($admin_id, $tabVictim['id'], $action, $comment, $date_action);
      sendMailProfilEditedByAdmin($tabVictim['lastname'], $tabVictim['firstname'], $tabVictim['pseudo'], $tabVictim['mail'], $user_lang);
      echo returnResponse($display_response_empty);
    }


  }else {
    echo returnError($display_error_empty);
    exit();
  }



}else {
  echo returnError($display_error_insufficient_permissions);
  exit();
}


 ?>
