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


function getItemInfo($req){
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
  if(isset($req->quality)){
    $item_quality = intval(htmlspecialchars($req->quality, ENT_QUOTES));
  }else{
    $item_quality = -1; // -1 means everything
  }


  $tabItemInfo = [
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
    "item_quality" => $item_quality,
  ];
  return $tabItemInfo;
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
  if(isset($req->character->race_id)){
    $character_race_id = htmlspecialchars($req->character->race_id, ENT_QUOTES);
  }else{
    $character_race_id = NULL;
  }
  if(isset($req->character->class_id)){
    $character_class_id = htmlspecialchars($req->character->class_id, ENT_QUOTES);
  }else{
    $character_class_id = NULL;
  }
  if(isset($req->character->head_id)){
    $character_head_id = htmlspecialchars($req->character->head_id, ENT_QUOTES);
  }else{
    $character_head_id = NULL;
  }
  if(isset($req->character->neck_id)){
    $character_neck_id = htmlspecialchars($req->character->neck_id, ENT_QUOTES);
  }else{
    $character_neck_id = NULL;
  }
  if(isset($req->character->shoulder_id)){
    $character_shoulder_id = htmlspecialchars($req->character->shoulder_id, ENT_QUOTES);
  }else{
    $character_shoulder_id = NULL;
  }
  if(isset($req->character->chest_id)){
    $character_chest_id = htmlspecialchars($req->character->chest_id, ENT_QUOTES);
  }else{
    $character_chest_id = NULL;
  }
  if(isset($req->character->waist_id)){
    $character_waist_id = htmlspecialchars($req->character->waist_id, ENT_QUOTES);
  }else{
    $character_waist_id = NULL;
  }
  if(isset($req->character->legs_id)){
    $character_legs_id = htmlspecialchars($req->character->legs_id, ENT_QUOTES);
  }else{
    $character_legs_id = NULL;
  }
  if(isset($req->character->feet_id)){
    $character_feet_id = htmlspecialchars($req->character->feet_id, ENT_QUOTES);
  }else{
    $character_feet_id = NULL;
  }
  if(isset($req->character->wrist_id)){
    $character_wrist_id = htmlspecialchars($req->character->wrist_id, ENT_QUOTES);
  }else{
    $character_wrist_id = NULL;
  }
  if(isset($req->character->hands_id)){
    $character_hands_id = htmlspecialchars($req->character->hands_id, ENT_QUOTES);
  }else{
    $character_hands_id = NULL;
  }
  if(isset($req->character->finger1_id)){
    $character_finger1_id = htmlspecialchars($req->character->finger1_id, ENT_QUOTES);
  }else{
    $character_finger1_id = NULL;
  }
  if(isset($req->character->finger2_id)){
    $character_finger2_id = htmlspecialchars($req->character->finger2_id, ENT_QUOTES);
  }else{
    $character_finger2_id = NULL;
  }
  if(isset($req->character->trinket1_id)){
    $character_trinket1_id = htmlspecialchars($req->character->trinket1_id, ENT_QUOTES);
  }else{
    $character_trinket1_id = NULL;
  }
  if(isset($req->character->trinket2_id)){
    $character_trinket2_id = htmlspecialchars($req->character->trinket2_id, ENT_QUOTES);
  }else{
    $character_trinket2_id = NULL;
  }
  if(isset($req->character->back_id)){
    $character_back_id = htmlspecialchars($req->character->back_id, ENT_QUOTES);
  }else{
    $character_back_id = NULL;
  }
  if(isset($req->character->main_hand_id)){
    $character_main_hand_id = htmlspecialchars($req->character->main_hand_id, ENT_QUOTES);
  }else{
    $character_main_hand_id = NULL;
  }
  if(isset($req->character->off_hand_id)){
    $character_off_hand_id = htmlspecialchars($req->character->off_hand_id, ENT_QUOTES);
  }else{
    $character_off_hand_id = NULL;
  }
  if(isset($req->character->attack)){
    $character_attack = htmlspecialchars($req->character->attack, ENT_QUOTES);
  }else{
    $character_attack = NULL;
  }
  if(isset($req->character->armour)){
    $character_armour = htmlspecialchars($req->character->armour, ENT_QUOTES);
  }else{
    $character_armour = NULL;
  }
  if(isset($req->character->stamina)){
    $character_stamina = htmlspecialchars($req->character->stamina, ENT_QUOTES);
  }else{
    $character_stamina = NULL;
  }
  if(isset($req->character->health)){
    $character_health = htmlspecialchars($req->character->health, ENT_QUOTES);
  }else{
    $character_health = NULL;
  }
  if(isset($req->character->critical_strike)){
    $character_critical_strike = htmlspecialchars($req->character->critical_strike, ENT_QUOTES);
  }else{
    $character_critical_strike = NULL;
  }
  if(isset($req->character->haste)){
    $character_haste = htmlspecialchars($req->character->haste, ENT_QUOTES);
  }else{
    $character_haste = NULL;
  }
  if(isset($req->character->mastery)){
    $character_mastery = htmlspecialchars($req->character->mastery, ENT_QUOTES);
  }else{
    $character_mastery = NULL;
  }
  if(isset($req->character->versatility)){
    $character_versatility = htmlspecialchars($req->character->versatility, ENT_QUOTES);
  }else{
    $character_versatility = NULL;
  }
  if(isset($req->character->head_icon)){
    $character_head_icon = htmlspecialchars($req->character->head_icon, ENT_QUOTES);
  }else{
    $character_head_icon = NULL;
  }
  if(isset($req->character->neck_icon)){
    $character_neck_icon = htmlspecialchars($req->character->neck_icon, ENT_QUOTES);
  }else{
    $character_neck_icon = NULL;
  }
  if(isset($req->character->shoulder_icon)){
    $character_shoulder_icon = htmlspecialchars($req->character->shoulder_icon, ENT_QUOTES);
  }else{
    $character_shoulder_icon = NULL;
  }
  if(isset($req->character->chest_icon)){
    $character_chest_icon = htmlspecialchars($req->character->chest_icon, ENT_QUOTES);
  }else{
    $character_chest_icon = NULL;
  }
  if(isset($req->character->waist_icon)){
    $character_waist_icon = htmlspecialchars($req->character->waist_icon, ENT_QUOTES);
  }else{
    $character_waist_icon = NULL;
  }
  if(isset($req->character->legs_icon)){
    $character_legs_icon = htmlspecialchars($req->character->legs_icon, ENT_QUOTES);
  }else{
    $character_legs_icon = NULL;
  }
  if(isset($req->character->feet_icon)){
    $character_feet_icon = htmlspecialchars($req->character->feet_icon, ENT_QUOTES);
  }else{
    $character_feet_icon = NULL;
  }
  if(isset($req->character->wrist_icon)){
    $character_wrist_icon = htmlspecialchars($req->character->wrist_icon, ENT_QUOTES);
  }else{
    $character_wrist_icon = NULL;
  }
  if(isset($req->character->hands_icon)){
    $character_hands_icon = htmlspecialchars($req->character->hands_icon, ENT_QUOTES);
  }else{
    $character_hands_icon = NULL;
  }
  if(isset($req->character->finger1_icon)){
    $character_finger1_icon = htmlspecialchars($req->character->finger1_icon, ENT_QUOTES);
  }else{
    $character_finger1_icon = NULL;
  }
  if(isset($req->character->finger2_icon)){
    $character_finger2_icon = htmlspecialchars($req->character->finger2_icon, ENT_QUOTES);
  }else{
    $character_finger2_icon = NULL;
  }
  if(isset($req->character->trinket1_icon)){
    $character_trinket1_icon = htmlspecialchars($req->character->trinket1_icon, ENT_QUOTES);
  }else{
    $character_trinket1_icon = NULL;
  }
  if(isset($req->character->trinket2_icon)){
    $character_trinket2_icon = htmlspecialchars($req->character->trinket2_icon, ENT_QUOTES);
  }else{
    $character_trinket2_icon = NULL;
  }
  if(isset($req->character->back_icon)){
    $character_back_icon = htmlspecialchars($req->character->back_icon, ENT_QUOTES);
  }else{
    $character_back_icon = NULL;
  }
  if(isset($req->character->main_hand_icon)){
    $character_main_hand_icon = htmlspecialchars($req->character->main_hand_icon, ENT_QUOTES);
  }else{
    $character_main_hand_icon = NULL;
  }
  if(isset($req->character->off_hand_icon)){
    $character_off_hand_icon = htmlspecialchars($req->character->off_hand_icon, ENT_QUOTES);
  }else{
    $character_off_hand_icon = NULL;
  }


  $tabCharacterInfo = [
    "session_token" => $session_token,
    "character_name" => $character_name,
    "character_race_id" => $character_race_id->id,
    "character_class_id" => $character_class_id->id,
    "character_head_id" => $character_head_id->id,
    "character_neck_id" => $character_neck_id->id,
    "character_shoulder_id" => $character_shoulder_id->id,
    "character_chest_id" => $character_chest_id->id,
    "character_waist_id" => $character_waist_id->id,
    "character_legs_id" => $character_legs_id->id,
    "character_feet_id" => $character_feet_id->id,
    "character_wrist_id" => $character_wrist_id->id,
    "character_hands_id" => $character_hands_id->id,
    "character_finger1_id" => $character_finger1_id->id,
    "character_finger2_id" => $character_finger2_id->id,
    "character_trinket1_id" => $character_trinket1_id->id,
    "character_trinket2_id" => $character_trinket2_id->id,
    "character_back_id" => $character_back_id->id,
    "character_main_hand_id" => $character_main_hand_id->id,
    "character_off_hand_id" => $character_off_hand_id->id,
    "character_attack" => $character_attack->id,
    "character_armour" => $character_armour->id,
    "character_stamina" => $character_stamina->id,
    "character_health" => $character_health->id,
    "character_critical_strike" => $character_critical_strike->id,
    "character_haste" => $character_haste->id,
    "character_mastery" => $character_mastery->id,
    "character_versatility" => $character_versatility->id,
    "character_head_icon" => $character_head_icon->id,
    "character_neck_icon" => $character_neck_icon->id,
    "character_shoulder_icon" => $character_shoulder_icon->id,
    "character_chest_icon" => $character_chest_icon->id,
    "character_waist_icon" => $character_waist_icon->id,
    "character_legs_icon" => $character_legs_icon->id,
    "character_feet_icon" => $character_feet_icon->id,
    "character_wrist_icon" => $character_wrist_icon->id,
    "character_hands_icon" => $character_hands_icon->id,
    "character_finger1_icon" => $character_finger1_icon->id,
    "character_finger2_icon" => $character_finger2_icon->id,
    "character_trinket1_icon" => $character_trinket1_icon->id,
    "character_trinket2_icon" => $character_trinket2_icon->id,
    "character_back_icon" => $character_back_icon->id,
    "character_main_hand_icon" => $character_main_hand_icon->id,
    "character_off_hand_icon" => $character_off_hand_icon->id
  ];
  return $tabCharacterInfo;
}


function retrieveCharactersInfo($req){
  if(isset($req->session_token)){
    $session_token = htmlspecialchars($req->session_token, ENT_QUOTES);
  }else{
    $session_token = NULL;
  }
  if(isset($req->characName)){
    $characName = htmlspecialchars($req->characName, ENT_QUOTES);
  }else{
    $characName = "";
  }
  if(isset($req->filter)){
    $filter = htmlspecialchars($req->filter, ENT_QUOTES);
  }else{
    $filter = "date";
  }
  if(isset($req->order)){
    $order = htmlspecialchars($req->order, ENT_QUOTES);
  }else{
    $order = "desc";
  }
  if(isset($req->data)){
    $data = htmlspecialchars($req->data, ENT_QUOTES);
  }else{
    $data = "all";
  }

  $tabFilterInfo = [
    "session_token" => $session_token,
    "characName" => $characName,
    "filter" => $filter,
    "order" => $order,
    "data" => $data
  ];

  return $tabFilterInfo;
}


function infoStatutLike($req){

  if(isset($req->session_token)){
    $session_token = htmlspecialchars($req->session_token, ENT_QUOTES);
  }else{
    $session_token = NULL;
  }
  if(isset($req->character_id)){
    $character_id = htmlspecialchars($req->character_id, ENT_QUOTES);
  }else{
    $character_id = NULL;
  }
  if(isset($req->statut)){
    $statut = htmlspecialchars($req->statut, ENT_QUOTES);
  }else{
    $statut = NULL;
  }

  $tabInfoStatutLike = [
    "session_token" => $session_token,
    "character_id" => intval($character_id),
    "statut" => intval($statut)
  ];

  return $tabInfoStatutLike;
}


function commentManagement($req){

  if(isset($req->session_token)){
    $session_token = htmlspecialchars($req->session_token, ENT_QUOTES);
  }else{
    $session_token = NULL;
  }
  if(isset($req->character_id)){
    $character_id = htmlspecialchars($req->character_id, ENT_QUOTES);
  }else{
    $character_id = NULL;
  }
  if(isset($req->comment)){
    $comment = htmlspecialchars($req->comment, ENT_QUOTES);
  }else{
    $comment = NULL;
  }
  if(isset($req->comment_id)){
    $comment_id = htmlspecialchars($req->comment_id, ENT_QUOTES);
  }else{
    $comment_id = NULL;
  }

  $tabCommentManagement = [
    "session_token" => $session_token,
    "character_id" => intval($character_id),
    "comment" => $comment,
    "comment_id" => intval($comment_id)
  ];

  return $tabCommentManagement;
}


function getOneCharacter($req){
  if(isset($req->session_token)){
    $session_token = htmlspecialchars($req->session_token, ENT_QUOTES);
  }else{
    $session_token = NULL;
  }
  if(isset($req->character_id)){
    $character_id = htmlspecialchars($req->character_id, ENT_QUOTES);
  }else{
    $character_id = NULL;
  }

  $tabOneCharacter = [
    "session_token" => $session_token,
    "character_id" => intval($character_id)
  ];

  return $tabOneCharacter;
}

 ?>
