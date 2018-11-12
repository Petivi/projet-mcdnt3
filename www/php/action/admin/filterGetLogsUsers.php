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
    $request_logs_list = 'SELECT * FROM requests_list WHERE user_id NOT LIKE :account_id ORDER BY request_date DESC';
    $request_logs_list = $base->prepare($request_logs_list);
    $request_logs_list->bindValue('account_id', $admin_id, PDO::PARAM_INT);
    $request_logs_list->execute();
    while($logs_list = $request_logs_list->fetch())
    {
      $user_id = $logs_list['user_id'];
      $user_lastname = Chiffrement::decrypt($logs_list['user_lastname']);
      $user_firstname = Chiffrement::decrypt($logs_list['user_firstname']);
      $user_pseudo = Chiffrement::decrypt($logs_list['user_pseudo']);
      $user_mail = Chiffrement::decrypt($logs_list['user_mail']);
      $request_type = $logs_list['request_type'];
      if($tabUser['data'] != ""){
        if((stristr($user_id, $tabUser['data'])) || (stristr($user_pseudo, $tabUser['data'])) || (stristr($request_type, $tabUser['data']))){
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
      }else {
        array_push($tabLogsListFull,array(
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

// TODO : CELUI LA MARCHE ET IL FAUT LE COPIER POUR LES AUTRES FILTRES

 ?>
