<?php
require_once('../../config.php');
include "../../includedFiles.php";

$tabUser = getPostInfo($request);

// TODO FAIRE LE FILTRE COMME SUR filterGetLogsUsers.php


if(accessToAdminPermissions($tabUser['session_token'])){

  $admin_id = getIdFromSessionToken($tabUser['session_token']);

  $offsetPage = calcOffsetPage($tabUser['nb_page']); // calc offset to return correct values




  if($admin_id){

    $tabUserList = array();
    $tabUserListFull = array();
    $nb_item = 0;
    $request_user_list = 'SELECT * FROM users WHERE id NOT LIKE :account_id ORDER BY id DESC LIMIT :items_per_page OFFSET :offsetPage';
    $request_user_list = $base->prepare($request_user_list);
    $request_user_list->bindValue('account_id', $admin_id, PDO::PARAM_INT);
    $request_user_list->bindValue('items_per_page', $items_per_page, PDO::PARAM_INT);
    $request_user_list->bindValue('offsetPage', $offsetPage, PDO::PARAM_INT);
    $request_user_list->execute();
    while($user_list = $request_user_list->fetch())
    {
      $id = $user_list['id'];
      $lastname = Chiffrement::decrypt($user_list['lastname']);
      $firstname = Chiffrement::decrypt($user_list['firstname']);
      $mail = Chiffrement::decrypt($user_list['mail']);
      $pseudo = Chiffrement::decrypt($user_list['pseudo']);
      if((stristr($id, $tabUser['data'])) || (stristr($lastname, $tabUser['data'])) || (stristr($firstname, $tabUser['data'])) || (stristr($mail, $tabUser['data'])) || (stristr($pseudo, $tabUser['data']))){
        $nb_item++;
        array_push($tabUserList,array(
          'id' => $user_list['id'],
          'lastname' => Chiffrement::decrypt($user_list['lastname']),
          'firstname' => Chiffrement::decrypt($user_list['firstname']),
          'mail' => Chiffrement::decrypt($user_list['mail']),
          'pseudo' => Chiffrement::decrypt($user_list['pseudo']),
          'created_date' => date('d/m/Y', $user_list['created_date']),
          'last_connection' => date('d/m/Y H:i:s', $user_list['last_connection']),
          'active_account' => $user_list['active_account'],
          'checked_mail' => $user_list['checked_mail']
        ));
      }else {
        array_push($tabUserListFull,array(
          'id' => $user_list['id'],
          'lastname' => Chiffrement::decrypt($user_list['lastname']),
          'firstname' => Chiffrement::decrypt($user_list['firstname']),
          'mail' => Chiffrement::decrypt($user_list['mail']),
          'pseudo' => Chiffrement::decrypt($user_list['pseudo']),
          'created_date' => date('d/m/Y', $user_list['created_date']),
          'last_connection' => date('d/m/Y H:i:s', $user_list['last_connection']),
          'active_account' => $user_list['active_account'],
          'checked_mail' => $user_list['checked_mail']
        ));
      }
    }

    $total_page = calcNbPageWithoutTable($nb_item);

    $tabFinal = array();
    if($tabUser['data']){ // if we have a filter
      $tabFinal = $tabUserList; // get filtered tab
    }else {
      $tabFinal = $tabUserListFull; // else get the full tab
    }
    $tabOutput['valeur'] = array_slice($tabFinal, $offsetPage, $items_per_page); // only get items to display on the current page
    $tabOutput['total_page'] = $total_page; // add nb total page

    echo returnResponse($tabOutput);
  }else {
    echo returnError($display_error_empty);
  }

}else {
  echo returnError($display_error_insufficient_permissions);
}


 ?>
