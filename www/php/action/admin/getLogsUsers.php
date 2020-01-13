<?php
require_once('../../config.php');
include "../../includedFiles.php";

$tabUser = getPostInfo($request);

if(accessToAdminPermissions($tabUser['session_token'])){

  $admin_id = getIdFromSessionToken($tabUser['session_token']);


  if($admin_id){


    $tabLogsList = array();
    $request_logs_list = 'SELECT * FROM requests_list WHERE user_id NOT LIKE :account_id ORDER BY request_date DESC';
    $request_logs_list = $base->prepare($request_logs_list);
    $request_logs_list->bindValue('account_id', $admin_id, PDO::PARAM_INT);
    $request_logs_list->execute();
    while($logs_list = $request_logs_list->fetch())
    {
      array_push($tabLogsList,array(
        'id' => $logs_list['id'],
        'user_id' => $logs_list['user_id'],
        'user_lastname' => htmlspecialchars_decode(Chiffrement::decrypt($logs_list['user_lastname']), ENT_QUOTES),
        'user_firstname' => htmlspecialchars_decode(Chiffrement::decrypt($logs_list['user_firstname']), ENT_QUOTES),
        'user_pseudo' => htmlspecialchars_decode(Chiffrement::decrypt($logs_list['user_pseudo']), ENT_QUOTES),
        'user_mail' => htmlspecialchars_decode(Chiffrement::decrypt($logs_list['user_mail']), ENT_QUOTES),
        'request_token' => $logs_list['request_token'],
        'request_type' => $logs_list['request_type'],
        'request_date' => date('d/m/Y H:i:s', $logs_list['request_date']),
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
