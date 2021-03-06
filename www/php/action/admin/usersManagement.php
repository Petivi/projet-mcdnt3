<?php
require_once('../../config.php');
include "../../includedFiles.php";

$tabUser = getPostInfo($request);

if(accessToAdminPermissions($tabUser['session_token'])){

  $admin_id = getIdFromSessionToken($tabUser['session_token']);



  if($admin_id){

    $tabUserList = array();
    $request_user_list = 'SELECT * FROM users WHERE id NOT LIKE :account_id AND is_admin LIKE 0 ORDER BY id DESC';
    $request_user_list = $base->prepare($request_user_list);
    $request_user_list->bindValue('account_id', $admin_id, PDO::PARAM_INT);
    $request_user_list->execute();
    while($user_list = $request_user_list->fetch())
    {
      array_push($tabUserList,array(
        'id' => $user_list['id'],
        'lastname' => htmlspecialchars_decode(Chiffrement::decrypt($user_list['lastname']), ENT_QUOTES),
        'firstname' => htmlspecialchars_decode(Chiffrement::decrypt($user_list['firstname']), ENT_QUOTES),
        'mail' => htmlspecialchars_decode(Chiffrement::decrypt($user_list['mail']), ENT_QUOTES),
        'pseudo' => htmlspecialchars_decode(Chiffrement::decrypt($user_list['pseudo']), ENT_QUOTES),
        'created_date' => date('d/m/Y', $user_list['created_date']),
        'last_connection' => date('d/m/Y H:i:s', $user_list['last_connection']),
        'active_account' => $user_list['active_account'],
        'checked_mail' => $user_list['checked_mail']
      ));
    }

    $tabFinal = array();
    $tabFinal['valeur'] = $tabUserList;

    echo returnResponse($tabFinal);
  }else {
    echo returnError($display_error_empty);
  }

}else {
  echo returnError($display_error_insufficient_permissions);
}


 ?>
