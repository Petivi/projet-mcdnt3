<?php
require_once('../../config.php');
include "../../includedFiles.php";

$tabUser = getPostInfo($request);

if(accessToAdminPermissions($tabUser['session_token'])){

  $user_exists = false;
  $request_user_info = 'SELECT * FROM users WHERE session_token LIKE :session_token';
  $request_user_info = $base->prepare($request_user_info);
  $request_user_info->bindValue('session_token', $tabUser['session_token'], PDO::PARAM_STR);
  $request_user_info->execute();
  while($user_info = $request_user_info->fetch())
  {
    $user_exists = true;
    $account_id = $user_info['id'];
  }

  if($user_exists){

    $tabUserList = array();
    $request_user_list = 'SELECT * FROM users WHERE id NOT LIKE :account_id ORDER BY id DESC';
    $request_user_list = $base->prepare($request_user_list);
    $request_user_list->bindValue('account_id', $account_id, PDO::PARAM_INT);
    $request_user_list->execute();
    while($user_list = $request_user_list->fetch())
    {
      array_push($tabUserList,array(
        'id' => $user_list['id'],
        'lastname' => $user_list['lastname'],
        'firstname' => $user_list['firstname'],
        'mail' => $user_list['mail'],
        'pseudo' => $user_list['pseudo'],
        'created_date' => date('d/m/Y', $user_list['created_date']),
        'last_connection' => date('d/m/Y H:i:s', $user_list['last_connection']),
        'active_account' => $user_list['active_account'],
        'checked_mail' => $user_list['checked_mail']
      ));
    }

    echo returnResponse($tabUserList);
  }else {
    echo returnError($display_error_empty);
  }

}else {
  echo returnError($display_error_empty);
}


 ?>
