<?php
require_once('../../config.php');
include "../../includedFiles.php";

$tabUser = getPostInfo($request);

if(accessToAdminPermissions($tabUser['session_token'])){

  $admin_id = getIdFromSessionToken($tabUser['session_token']);

  if($admin_id){


    $tabLogsList = array();
    $request_logs_management_list = 'SELECT * FROM admin_users_management WHERE user_id NOT LIKE :account_id ORDER BY date_action DESC';
    $request_logs_management_list = $base->prepare($request_logs_management_list);
    $request_logs_management_list->bindValue('account_id', $admin_id, PDO::PARAM_INT);
    $request_logs_management_list->execute();
    while($logs_management_list = $request_logs_management_list->fetch())
    {
      array_push($tabLogsList,array(
        'id' => $logs_management_list['id'],
        'user_id' => $logs_management_list['user_id'],
        'action' => htmlspecialchars_decode($logs_management_list['action'], ENT_QUOTES),
        'comment' => htmlspecialchars_decode($logs_management_list['comment'], ENT_QUOTES),
        'date_action' => date('d/m/Y H:i:s', $logs_management_list['date_action']),
      ));
    }

    $tabFinal = array();
    $tabFinal['valeur'] = $tabLogsList;

    echo returnResponse($tabFinal);
  }else {
    echo returnError($display_error_empty);
    exit();
  }



}else {
  echo returnError($display_error_insufficient_permissions);
  exit();
}



 ?>
