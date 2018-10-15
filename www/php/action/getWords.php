<?php
require_once('../config.php');
include "../includedFiles.php";

$listWords = array();
// request to get every words in database (with every language)
try {
  $get_words_language = 'SELECT * FROM messages';
  $get_words_language = $base->prepare($get_words_language);
  $get_words_language->execute();
  while($words = $get_words_language->fetch())
  {
    $msg_name = $words['msg_name'];
    $msg_fr = $words['msg_fr'];
    $msg_en = $words['msg_en'];
    $page = $words['page'];

    // create a tab with the message name as index of the tab, and another tab with every language for the desired word
    array_push($listWords,array(
      'msg_name' => $msg_name,
      'msg_fr' => $msg_fr,
      'msg_en' => $msg_en,
      'page' => $page,
    ));
  }
} catch (\Exception $e) {
  return returnError($display_error_empty);
  exit();
}

// call our return function
echo returnResponse($listWords);

 ?>
