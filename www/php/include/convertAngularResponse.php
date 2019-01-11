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
  if(isset($req->page)){
    $nb_page = htmlspecialchars($req->page, ENT_QUOTES);
  }else {
    $nb_page = 1;
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
    "nb_page" => $nb_page,
    "contact_mail" => $contact_mail,
    "contact_subject" => $contact_subject,
    "contact_text" => $contact_text,
  ];
  return $tabInfoUser;
}


function getCharacterInfo($req){
  if(isset($req->session_token)){
    $session_token = htmlspecialchars($req->session_token, ENT_QUOTES);
  }else{
    $session_token = "";
  }
  if(isset($req->character->name)){
    $character_name = htmlspecialchars($req->character->name, ENT_QUOTES);
  }else{
    $character_name = "";
  }
  if(isset($req->character->class_id)){
    $character_class_id = htmlspecialchars($req->character->class_id, ENT_QUOTES);
  }else{
    $character_class_id = NULL;
  }
  if(isset($req->character->race_id)){
    $character_race_id = htmlspecialchars($req->character->race_id, ENT_QUOTES);
  }else{
    $character_race_id = NULL;
  }
  if(isset($req->class)){
    $item_class = intval(htmlspecialchars($req->class, ENT_QUOTES));
  }else{
    $item_class = NULL;
  }
  if(isset($req->subClass)){
    $item_subClass = intval(htmlspecialchars($req->subClass, ENT_QUOTES));
  }else{
    $item_subClass = NULL;
  }
  if(isset($req->inventory_type)){
    $item_inventory_type = intval(htmlspecialchars($req->inventory_type, ENT_QUOTES));
  }else{
    $item_inventory_type = "";
  }
  if(isset($req->class_id)){
    $item_allowable_classes = strval(htmlspecialchars($req->class_id, ENT_QUOTES));
  }else{
    $item_allowable_classes = "";
  }
  if(isset($req->race_id)){
    $item_allowable_races = strval(htmlspecialchars($req->race_id, ENT_QUOTES));
  }else{
    $item_allowable_races = "";
  }
  if(isset($req->required_level_min)){
    $item_required_level_min = intval(htmlspecialchars($req->required_level_min, ENT_QUOTES));
  }else{
    $item_required_level_min = 0;
  }
  if(isset($req->required_level_max)){
    $item_required_level_max = intval(htmlspecialchars($req->required_level_max, ENT_QUOTES));
  }else{
    $item_required_level_max = 999;
  }


  $tabCharacterInfo = [
    "session_token" => $session_token,
    "character_name" => $character_name,
    "character_class_id" => $character_class_id,
    "character_race_id" => $character_race_id,
    "item_class" => $item_class,
    "item_subClass" => $item_subClass,
    "item_inventory_type" => $item_inventory_type,
    "allowable_classes" => $item_allowable_classes,
    "allowable_races" => $item_allowable_races,
    "item_required_level_min" => $item_required_level_min,
    "item_required_level_max" => $item_required_level_max,
  ];
  return $tabCharacterInfo;
}

 ?>
