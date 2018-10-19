<?php
require_once('../../config.php');
include "../../includedFiles.php";

$tabInfo = getPostInfo($request);


if(accessToAdminPermissions($tabInfo['session_token'])){


  $offsetPage = calcOffsetPage($tabInfo['nb_page']); // calc offset to return correct values



  $tabMessagesList = array();
  $tabMessagesListFull = array();
  $messages_exists = false;
  $nb_item = 0;
  $request_messages_list = 'SELECT * FROM requests_contact_list WHERE (request_closed = 0 OR request_closed = 1) ORDER BY request_date ASC LIMIT :items_per_page OFFSET :offsetPage';
  $request_messages_list = $base->prepare($request_messages_list);
  $request_messages_list->bindValue('items_per_page', $items_per_page, PDO::PARAM_INT);
  $request_messages_list->bindValue('offsetPage', $offsetPage, PDO::PARAM_INT);
  $request_messages_list->execute();
  while($messages_list = $request_messages_list->fetch())
  {
    $id = $messages_list['id'];
    $user_mail = htmlspecialchars_decode(Chiffrement::decrypt($messages_list['user_mail']));
    $request_subject = htmlspecialchars_decode(Chiffrement::decrypt($messages_list['request_subject']));
    $request_ref = $messages_list['request_ref'];
    if((stristr($id, $tabInfo['data'])) || (stristr($user_mail, $tabInfo['data'])) || (stristr($request_subject, $tabInfo['data'])) || (stristr($request_ref, $tabInfo['data']))){
      $messages_exists = true;
      $nb_item++;
      array_push($tabMessagesList,array(
        'id' => $messages_list['id'],
        'user_mail' => htmlspecialchars_decode(Chiffrement::decrypt($messages_list['user_mail']), ENT_QUOTES),
        'request_subject' => htmlspecialchars_decode(Chiffrement::decrypt($messages_list['request_subject']), ENT_QUOTES),
        'request_text' => htmlspecialchars_decode(Chiffrement::decrypt($messages_list['request_text']), ENT_QUOTES),
        'request_ref' => $messages_list['request_ref'],
        'request_date' => date('d/m/Y H:i:s', $messages_list['request_date']),
        'request_closed' => $messages_list['request_closed'],
      ));
    }else {
      array_push($tabMessagesListFull,array(
        'id' => $messages_list['id'],
        'user_mail' => htmlspecialchars_decode(Chiffrement::decrypt($messages_list['user_mail']), ENT_QUOTES),
        'request_subject' => htmlspecialchars_decode(Chiffrement::decrypt($messages_list['request_subject']), ENT_QUOTES),
        'request_text' => htmlspecialchars_decode(Chiffrement::decrypt($messages_list['request_text']), ENT_QUOTES),
        'request_ref' => $messages_list['request_ref'],
        'request_date' => date('d/m/Y H:i:s', $messages_list['request_date']),
        'request_closed' => $messages_list['request_closed'],
      ));
    }
  }
  $total_page = calcNbPageWithoutTable($nb_item);


  $tabFinal = array();
  if($tabUser['data']){ // if we have a filter
    $tabFinal = $tabMessagesList; // get filtered tab
  }else {
    $tabFinal = $tabMessagesListFull; // else get the full tab
  }
  $tabOutput['valeur'] = array_slice($tabFinal, $offsetPage, $items_per_page); // only get items to display on the current page
  $tabOutput['total_page'] = $total_page; // add nb total page

  if($messages_exists){

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
