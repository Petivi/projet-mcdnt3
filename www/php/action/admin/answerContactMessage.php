<?php
require_once('../../config.php');
include "../../includedFiles.php";

$tabInfo = getPostInfo($request);


if(accessToAdminPermissions($tabInfo['session_token'])){

  if($tabInfo['mail'] && $tabInfo['data'] && $tabInfo['id']){

    if(isset($request->request_ref)){
      $request_ref = htmlspecialchars($request->request_ref, ENT_QUOTES);
    }else {
      $request_ref = "";
    }

    // request_closed = 1 --> request done
    if(editContactMessageStatut($tabInfo['id'], 1)){

      try {
        sendMailAnswerContactMessage($tabInfo['mail'], $tabInfo['data'], $request_ref);
        echo returnResponse($display_response_empty);
      } catch (\Exception $e) {
        echo returnError($display_error_error_occured);
      }

    }else {
      echo returnError($display_error_error_occured);
      exit();
    }


  }else {
    echo returnError($display_error_empty);
    exit();
  }


}else {
  echo returnError($display_error_insufficient_permissions);
  exit();
}



 ?>
