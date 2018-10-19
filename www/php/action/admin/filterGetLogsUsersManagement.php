<?php
require_once('../../config.php');
include "../../includedFiles.php";


$tabUser = getPostInfo($request);

if(accessToAdminPermissions($tabUser['session_token'])){

  $admin_id = getIdFromSessionToken($tabUser['session_token']);

  $offsetPage = calcOffsetPage($tabUser['nb_page']); // calc offset to return correct values


  if($admin_id){


    $tabLogsList = array();
    $tabLogsListFull = array();
    $nb_item = 0;
    $request_logs_management_list = 'SELECT * FROM admin_users_management WHERE user_id NOT LIKE :account_id ORDER BY date_action DESC LIMIT :items_per_page OFFSET :offsetPage';
    $request_logs_management_list = $base->prepare($request_logs_management_list);
    $request_logs_management_list->bindValue('account_id', $admin_id, PDO::PARAM_INT);
    $request_logs_management_list->bindValue('items_per_page', $items_per_page, PDO::PARAM_INT);
    $request_logs_management_list->bindValue('offsetPage', $offsetPage, PDO::PARAM_INT);
    $request_logs_management_list->execute();
    while($logs_management_list = $request_logs_management_list->fetch())
    {
      $user_id = $logs_management_list['user_id'];
      $action = $logs_management_list['action'];
      if((stristr($user_id, $tabUser['data'])) || (stristr($action, $tabUser['data']))){
        $nb_item++;
        array_push($tabLogsList,array(
          'id' => $logs_management_list['id'],
          'user_id' => $logs_management_list['user_id'],
          'action' => $logs_management_list['action'],
          'comment' => $logs_management_list['comment'],
          'date_action' => date('d/m/Y H:i:s', $logs_management_list['date_action']),
        ));
      }else {
        array_push($tabLogsListFull,array(
          'id' => $logs_management_list['id'],
          'user_id' => $logs_management_list['user_id'],
          'action' => $logs_management_list['action'],
          'comment' => $logs_management_list['comment'],
          'date_action' => date('d/m/Y H:i:s', $logs_management_list['date_action']),
        ));
      }
    }
    $total_page = calcNbPageWithoutTable($nb_item);

    $tabFinal = array();
    if($tabUser['data']){ // if we have a filter
      $tabFinal = $tabLogsList; // get filtered tab
    }else {
      $tabFinal = $tabLogsListFull; // else get the full tab
    }
    $tabOutput['valeur'] = array_slice($tabFinal, $offsetPage, $items_per_page); // only get items to display on the current page
    $tabOutput['total_page'] = $total_page; // add nb total page

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
