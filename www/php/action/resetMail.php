<?php
require_once('../config.php');
include "../includedFiles.php";

$tabUser = getPostInfo($request);
if(isset($request->lang)){
  $lang = htmlspecialchars($request->lang, ENT_QUOTES);
}else {
  $lang = "en";
}


// get password that user tried
if(isset($request->password)){
  $password_tried = htmlspecialchars($request->password, ENT_QUOTES);
}else {
  return returnError($display_error_empty_field);
  exit();
}
$account_password = "";


try {
  // check if user exists
  $check_pseudo_user = 'SELECT * FROM users WHERE (pseudo=:login1 OR mail=:login2)';
  $check_pseudo_user = $base->prepare($check_pseudo_user);
  $check_pseudo_user->bindValue('login1', $tabUser['login'], PDO::PARAM_STR);
  $check_pseudo_user->bindValue('login2', $tabUser['login'], PDO::PARAM_STR);
  $check_pseudo_user->execute();
  while($user_info = $check_pseudo_user->fetch())
  {
    // get his informations
    $account_id = $user_info['id'];
    $account_lastname = $user_info['lastname'];
    $account_firstname = $user_info['firstname'];
    $account_pseudo = $user_info['pseudo'];
    $account_password = $user_info['password'];
    $account_created_date = $user_info['created_date'];
    $account_mail = $user_info['mail'];
    $account_permissions = $user_info['permissions'];
    $account_active_account = $user_info['active_account'];
    $account_checked_mail = $user_info['checked_mail'];
    $account_token_temp = $user_info['token_temp'];
  }
} catch (\Exception $e) {
  echo returnError($display_error_error_occured);
  exit();
}

// check if password is verified
if(password_verify($password_tried, $account_password)){
// correct pass

  if($account_active_account == 0){ // account suspended (by admin)
    echo returnError($display_error_account_suspended);
    exit();
  }elseif ($account_active_account == 2) { // account deleted by user
    echo returnError($display_error_account_deleted);
    exit();
  }else { // account is active

    if($account_checked_mail == 0){
      if(returnCheckMail($account_mail, $account_id)){
        echo returnError($display_error_mail_taken);
      }else {
        $date_token_created = strtotime(date('d-m-Y H:i:s'));
        $update_mail_user = 'UPDATE users
        SET mail = :mail, date_token_created = :date_token_created
        WHERE id LIKE :id AND active_account LIKE 1 AND checked_mail LIKE 0';
        $update_mail_user = $base->prepare($update_mail_user);
        $update_mail_user->bindValue('id', $account_id, PDO::PARAM_INT);
        $update_mail_user->bindValue('mail', $tabUser['mail'], PDO::PARAM_STR);
        $update_mail_user->bindValue('date_token_created', $date_token_created, PDO::PARAM_INT);
        $update_mail_user->execute();

        addToRequestsList($account_id, $account_lastname, $account_firstname, $account_pseudo, $tabUser['mail'], $account_token_temp, $request_type_reset_mail, $date_token_created);
        sendMailEditMail($account_lastname, $account_firstname, $account_pseudo, $tabUser['mail'], $account_token_temp, $lang);
        echo returnResponse($display_response_mail_sent);
      }
    }else {
      echo returnError($display_error_mail_activated);
    }

  }

}else {
  echo returnError($display_error_wrong_pseudo_password);
  exit();
}

 ?>
