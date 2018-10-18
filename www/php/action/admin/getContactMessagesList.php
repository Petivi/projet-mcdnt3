<?php
require_once('../../config.php');
include "../../includedFiles.php";

$tabUser = getPostInfo($request);


if(accessToAdminPermissions($tabUser['session_token'])){

  if(isset($request->page)){
    $nb_page = htmlspecialchars($request->page, ENT_QUOTES);
  }else {
    $nb_page = 1;
  }

  $offsetPage = calcOffsetPage($nb_page);

  $tabMessagesList = array();
  $messages_exists = false;
  $request_messages_list = 'SELECT * FROM requests_contact_list WHERE request_closed = 0 OR 1 ORDER BY request_date ASC LIMIT :items_per_page OFFSET :offsetPage';
  $request_messages_list = $base->prepare($request_messages_list);
  $request_messages_list->bindValue('items_per_page', $items_per_page, PDO::PARAM_INT);
  $request_messages_list->bindValue('offsetPage', $offsetPage, PDO::PARAM_INT);
  $request_messages_list->execute();
  while($messages_list = $request_messages_list->fetch())
  {
    $messages_exists = true;
    array_push($tabMessagesList,array(
      'id' => $messages_list['id'],
      'user_mail' => Chiffrement::decrypt($messages_list['user_mail']),
      'request_subject' => Chiffrement::decrypt($messages_list['request_subject']),
      'request_text' => Chiffrement::decrypt($messages_list['request_text']),
      'request_ref' => $messages_list['request_ref'],
      'request_date' => date('d/m/Y H:i:s', $messages_list['request_date']),
      'request_closed' => $messages_list['request_closed'],
    ));
  }

  if($messages_exists){
    echo returnResponse($tabMessagesList);
  }else {
    echo returnError($display_error_empty);
    exit();
  }
}else {
  echo returnError($display_error_insufficient_permissions);
  exit();
}


 ?>
