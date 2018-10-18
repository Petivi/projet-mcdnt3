<?php

// access to angular response
header("Access-Control-Allow-Origin: *");
// convert response from angular
$postRequest = file_get_contents("php://input");
$request = json_decode($postRequest);


function getPostInfo($req){
  if(isset($req->lastname)){
    $lastname = htmlspecialchars($req->lastname, ENT_QUOTES);
  }else {
    $lastname = "";
  }
  if(isset($req->firstname)){
    $firstname = htmlspecialchars($req->firstname, ENT_QUOTES);
  }else {
    $firstname = "";
  }
  if(isset($req->pseudo)){
    $pseudo = htmlspecialchars($req->pseudo, ENT_QUOTES);
  }else {
    $pseudo = "";
  }
  if(isset($req->mail)){
    $mail = htmlspecialchars($req->mail, ENT_QUOTES);
  }else {
    $mail = "";
  }
  if(isset($req->id)){
    $id = htmlspecialchars($req->id, ENT_QUOTES);
  }else {
    $id = "";
  }
  if(isset($req->lang)){
    $lang = htmlspecialchars($req->lang, ENT_QUOTES);
  }else {
    $lang = "en";
  }
  if(isset($req->session_token)){
    $session_token = htmlspecialchars($req->session_token, ENT_QUOTES);
  }else{
    $session_token = "";
  }
  if(isset($req->login)){
    $login = htmlspecialchars($req->login, ENT_QUOTES);
  }else{
    $login = "";
  }
  if(isset($req->active_account)){
    $active_account = htmlspecialchars($req->active_account, ENT_QUOTES);
  }else{
    $active_account = "";
  }


  if(isset($req->data)){
    $data = htmlspecialchars($req->data, ENT_QUOTES);
  }else{
    $data = "";
  }



  if(isset($req->contact_mail)){
    $contact_mail = htmlspecialchars($req->contact_mail, ENT_QUOTES);
  }else{
    $contact_mail = "";
  }
  if(isset($req->contact_subject)){
    $contact_subject = htmlspecialchars($req->contact_subject, ENT_QUOTES);
  }else{
    $contact_subject = "";
  }
  if(isset($req->contact_text)){
    $contact_text = htmlspecialchars($req->contact_text, ENT_QUOTES);
  }else{
    $contact_text = "";
  }

  $tabInfoUser = [
    "lastname" => $lastname,
    "firstname" => $firstname,
    "pseudo" => $pseudo,
    "mail" => $mail,
    "id" => $id,
    "lang" => $lang,
    "session_token" => $session_token,
    "login" => $login,
    "active_account" => $active_account,
    "data" => $data,
    "contact_mail" => $contact_mail,
    "contact_subject" => $contact_subject,
    "contact_text" => $contact_text,

  ];
  return $tabInfoUser;
}

 ?>
