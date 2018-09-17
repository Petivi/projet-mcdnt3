<?php
require_once('../config.php');
include "../includedFiles.php";


$listWords = array();
$get_words_language = 'SELECT * FROM messages';
$get_words_language = $base->prepare($get_words_language);
$get_words_language->execute();
while($words = $get_words_language->fetch())
{
  $listWords[$words['msg_name']] = array(
    'fr' => $words['msg_fr'],
    'en' => $words['msg_en'],
    'page' => $words['page'],
  );
}

echo returnResponse($listWords);

 ?>
