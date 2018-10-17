<?php
require_once('../config.php');
include "../includedFiles.php";

$tabUser = getPostInfo($request);


if($tabUser['contact_mail'] && $tabUser['contact_subject'] && $tabUser['contact_text']){


  $request_ref = generateRefToken();
  $request_date = strtotime(date('d-m-Y H:i:s'));

  $add_request_contact = 'INSERT INTO requests_contact_list (user_mail, request_subject, request_text, request_ref, request_date)
  VALUES (:user_mail, :request_subject, :request_text, :request_ref, :request_date)';
  $add_request_contact = $base->prepare($add_request_contact);
  $add_request_contact->bindValue('user_mail', $tabUser['contact_mail'], PDO::PARAM_STR);
  $add_request_contact->bindValue('request_subject', $tabUser['request_subject'], PDO::PARAM_STR);
  $add_request_contact->bindValue('request_text', $tabUser['request_text'], PDO::PARAM_STR);
  $add_request_contact->bindValue('request_ref', $request_ref, PDO::PARAM_STR);
  $add_request_contact->bindValue('request_date', $request_date, PDO::PARAM_INT);
  $add_request_contact->execute();



}else {
  echo returnError($display_error_empty_field);
}



 ?>
