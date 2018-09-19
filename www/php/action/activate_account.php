<?php
require_once('../config.php');
include "../includedFiles.php";

  if(isset($_GET['token'])){
    $code_activation = $_GET['token'];


    $get_user_info = 'SELECT * FROM users WHERE code_activation LIKE :code_activation';
    $get_user_info = $base->prepare($get_user_info);
    $get_user_info->bindValue('code_activation', $code_activation, PDO::PARAM_STR);
    $get_user_info->execute();
    while($user_info = $get_user_info->fetch())
    {
      $userId = $user_info['id'];

      $update_checked_mail = 'UPDATE users SET checked_mail = 1 WHERE id LIKE :id';
      $update_checked_mail = $base->prepare($update_checked_mail);
      $update_checked_mail->bindValue('id', $userId, PDO::PARAM_INT);
      $update_checked_mail->execute();
    }

  }

  $url_server = "http://" . $_SERVER['SERVER_NAME'];
  // check if we're in local or not
  if(strpos($url_server,  'localhost')){
    $url_server .= ":4200";
  };

 ?>


<meta http-equiv="refresh" content="0;url=<?php echo $url_server; ?>"/>
