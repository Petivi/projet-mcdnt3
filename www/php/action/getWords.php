<?php
require_once('../config.php');
include "../includedFiles.php";


$listWords = array();
// request to get every words in database (with every language)
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
  $listWords[$msg_name] = array(
    'msg_fr' => $msg_fr,
    'msg_en' => $msg_en,
    'page' => $page,
  );
}

// call our return function
echo returnResponse($listWords);

 ?>
