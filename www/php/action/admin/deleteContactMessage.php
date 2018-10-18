<?php
require_once('../../config.php');
include "../../includedFiles.php";

$tabUser = getPostInfo($request);
$tabUser['id'] = intval($tabUser['id']);

if(accessToAdminPermissions($tabUser['session_token']) && $tabUser['id']){

  // request_closed = 2 --> request deleted
  if(editContactMessageStatut($tabUser['id'], 2)){
    echo returnResponse($display_response_empty);
  }else {
    echo returnError($display_error_error_occured);
    exit();
  }



}else {
  echo returnError($display_error_insufficient_permissions);
  exit();
}


 ?>
