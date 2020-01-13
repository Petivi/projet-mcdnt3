<?php
require_once('../../config.php');
include "../../includedFiles.php";

$tabUser = getPostInfo($request);

if(accessToAdminPermissions($tabUser['session_token'])){

  $admin_id = getIdFromSessionToken($tabUser['session_token']);

  if($admin_id){


    $tabLogsListFull = array();
    $nb_item = 0;
    $request_logs_list = 'SELECT * FROM fail_connection_list_account_blocked ORDER BY date_blocked DESC';
    $request_logs_list = $base->prepare($request_logs_list);
    $request_logs_list->execute();
    while($logs_list = $request_logs_list->fetch())
    {
      $user_id = $logs_list['user_id']; // get user id

      $request_if_still_blocked = "SELECT * FROM users WHERE id LIKE $user_id";
      $request_if_still_blocked = $base->prepare($request_if_still_blocked);
      $request_if_still_blocked->execute();
      while($request_blocked = $request_if_still_blocked->fetch())
      {
        // check if the user is still blocked
        if($request_blocked != 0){

          $user_ip = $logs_list['user_ip']; // get user id
          $login_tried = Chiffrement::decrypt($logs_list['login_tried']);
          $date_blocked = $logs_list['date_blocked'];
          $date_unblocked = $logs_list['date_unblocked'];
            $nb_item++;
            array_push($tabLogsListFull,array(
              'id' => $logs_list['id'],
              'user_id' => $logs_list['user_id'],
              'user_ip' => $logs_list['user_ip'],
              'login_tried' => htmlspecialchars_decode(Chiffrement::decrypt($logs_list['login_tried']), ENT_QUOTES),
              'date_blocked' => date('d/m/Y H:i:s',$logs_list['date_blocked']),
              'date_unblocked' => date('d/m/Y H:i:s',$logs_list['date_unblocked']),
            ));
        }
      }

    }

    $tabOutput['valeur'] = $tabLogsListFull;

    echo returnResponse($tabOutput);
  }else {
    echo returnError($display_error_empty);
    exit();
  }



}else {
  echo returnError($display_error_insufficient_permissions);
  exit();
}


 ?>
