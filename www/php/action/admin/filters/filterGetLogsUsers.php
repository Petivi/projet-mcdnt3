<?php
require_once('../../config.php');
include "../../includedFiles.php";

$tabUser = getPostInfo($request);

if(accessToAdminPermissions($tabUser['session_token'])){

  $admin_id = getIdFromSessionToken($tabUser['session_token']);

  $offsetPage = calcOffsetPage($tabUser['nb_page']); // calc offset to return correct values


  if($admin_id){


    $tabLogsList = array();
    $nb_item = 0;
    $request_logs_list = 'SELECT * FROM requests_list WHERE user_id NOT LIKE :account_id ORDER BY request_date DESC LIMIT :items_per_page OFFSET :offsetPage';
    $request_logs_list = $base->prepare($request_logs_list);
    $request_logs_list->bindValue('account_id', $admin_id, PDO::PARAM_INT);
    $request_logs_list->bindValue('items_per_page', $items_per_page, PDO::PARAM_INT);
    $request_logs_list->bindValue('offsetPage', $offsetPage, PDO::PARAM_INT);
    $request_logs_list->execute();
    while($logs_list = $request_logs_list->fetch())
    {
      $user_id = $logs_list['user_id'];
      $user_lastname = Chiffrement::decrypt($logs_list['user_lastname']);
      $user_firstname = Chiffrement::decrypt($logs_list['user_firstname']);
      $user_pseudo = Chiffrement::decrypt($logs_list['user_pseudo']);
      $user_mail = Chiffrement::decrypt($logs_list['user_mail']);
      $request_type = Chiffrement::decrypt($logs_list['request_type']);
      if((stripos($user_id, $tabUser['data'])) || (stripos($user_lastname, $tabUser['data'])) || (stripos($user_firstname, $tabUser['data'])) || (stripos($user_pseudo, $tabUser['data'])) || (stripos($user_mail, $tabUser['data'])) || (stripos($request_type, $tabUser['data']))){
        $nb_item++;
        array_push($tabLogsList,array(
          'id' => $logs_list['id'],
          'user_id' => $logs_list['user_id'],
          'user_lastname' => Chiffrement::decrypt($logs_list['user_lastname']),
          'user_firstname' => Chiffrement::decrypt($logs_list['user_firstname']),
          'user_pseudo' => Chiffrement::decrypt($logs_list['user_pseudo']),
          'user_mail' => Chiffrement::decrypt($logs_list['user_mail']),
          'request_token' => $logs_list['request_token'],
          'request_type' => $logs_list['request_type'],
          'request_date' => date('d/m/Y H:i:s', $logs_list['request_date']),
        ));
      }
    }

    $total_page = calcNbPageWithoutTable($nb_item);


    $tabFinal = array();
    $tabFinal['valeur'] = $tabLogsList;
    $tabFinal['total_page'] = $total_page;

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
