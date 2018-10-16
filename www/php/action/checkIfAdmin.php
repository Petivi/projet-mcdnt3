<?php
require_once('../config.php');
include "../includedFiles.php";

$tabUser = getPostInfo($request);

if(accessToAdminPermissions($tabUser['session_token'])){
  echo returnResponse($display_response_empty);
}else {
  echo returnError($display_error_empty);
}




 ?>
