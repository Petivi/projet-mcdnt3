<?php
require_once('../config.php');
include "../includedFiles.php";

  if(isset($_GET['token'])){
    $token_temp = $_GET['token'];

    try {
      $get_user_info = 'SELECT * FROM users WHERE token_temp LIKE :token_temp';
      $get_user_info = $base->prepare($get_user_info);
      $get_user_info->bindValue('token_temp', $token_temp, PDO::PARAM_STR);
      $get_user_info->execute();
      while($user_info = $get_user_info->fetch())
      {
        $id = $user_info['id'];

        $token_temp = "";
        $update_checked_mail = 'UPDATE users SET checked_mail = 1, token_temp = :token_temp WHERE id LIKE :id';
        $update_checked_mail = $base->prepare($update_checked_mail);
        $update_checked_mail->bindValue('id', $id, PDO::PARAM_INT);
        $update_checked_mail->bindValue('token_temp', $token_temp, PDO::PARAM_STR);
        $update_checked_mail->execute();
      }
    } catch (\Exception $e) {

    }


  }

  $url_server = "http://" . $_SERVER['SERVER_NAME'];
  // check if we're in local or not
  if(strpos($url_server,  'localhost')){
    $url_server .= ":4200";
  };
  $url_server .= "/login/confirm";

 ?>


<meta http-equiv="refresh" content="0;url=<?php echo $url_server; ?>"/>
