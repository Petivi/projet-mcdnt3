<?php
require_once('../../config.php');
include "../../includedFiles.php";

$tabInfo = getPostInfo($request);


if(accessToAdminPermissions($tabInfo['session_token'])){

  if(isset($request->page)){
    $nb_page = htmlspecialchars($request->page, ENT_QUOTES);
  }else {
    $nb_page = 1;
  }

  $offsetPage = calcOffsetPage($nb_page); // calc offset to return correct values
  $nb_item = 0; // initialize total items

  $tabMessagesList = array();
  $tabNbPage = array();
  $messages_exists = false;
  $request_messages_list = 'SELECT * FROM requests_contact_list WHERE (request_closed = 0 OR request_closed = 1) ORDER BY request_date ASC LIMIT :items_per_page OFFSET :offsetPage';
  $request_messages_list = $base->prepare($request_messages_list);
  $request_messages_list->bindValue('items_per_page', $items_per_page, PDO::PARAM_INT);
  $request_messages_list->bindValue('offsetPage', $offsetPage, PDO::PARAM_INT);
  $request_messages_list->execute();
  while($messages_list = $request_messages_list->fetch())
  {
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
  }

  $total_page = calcNbPage($nb_item);
  var_dump($total_page);

  $tabFinal = array();
  $tabFinal['valeur'] = $tabMessagesList;
  $tabFinal['total_page'] = $total_page;

  if($messages_exists){

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
