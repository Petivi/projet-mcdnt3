<?php
require_once('../../config.php');
include "../../includedFiles.php";

$tabUser = getPostInfo($request);
$tabUser['id'] = intval($tabUser['id']);

if(accessToAdminPermissions($tabUser['session_token']) && $tabUser['id']){

  // request_closed = 2 --> request deleted
  $update_messages_statut = 'UPDATE requests_contact_list
  SET request_closed = 2
  WHERE id LIKE :id';
  $update_messages_statut = $base->prepare($update_messages_statut);
  $update_messages_statut->bindValue('id', $tabUser['id'], PDO::PARAM_INT);
  $update_messages_statut->execute();
  echo returnResponse($display_response_empty);



}else {
  echo returnError($display_error_insufficient_permissions);
  exit();
}


 ?>
