<?php
require_once('../config.php');
include "../includedFiles.php";

// get user pseudo
// check if pseudo exists
  if(isset($request->login)){
    $login = htmlspecialchars($request->login, ENT_QUOTES);
  }else{
    $login = "";
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
    $check_pseudo_user->bindValue('login1', Chiffrement::crypt($login), PDO::PARAM_STR);
    $check_pseudo_user->bindValue('login2', Chiffrement::crypt($login), PDO::PARAM_STR);
    $check_pseudo_user->execute();
    while($user_info = $check_pseudo_user->fetch())
    {
      // get his informations
      $account_id = $user_info['id'];
      $account_lastname = Chiffrement::decrypt($user_info['lastname']);
      $account_firstname = Chiffrement::decrypt($user_info['firstname']);
      $account_pseudo = Chiffrement::decrypt($user_info['pseudo']);
      $account_password = $user_info['password'];
      $account_created_date = $user_info['created_date'];
      $account_mail = Chiffrement::decrypt($user_info['mail']);
      $account_permissions = $user_info['permissions'];
      $account_active_account = $user_info['active_account'];
      $account_checked_mail = $user_info['checked_mail'];
    }
  } catch (\Exception $e) {
    echo returnError($display_error_error_occured);
    exit();
  }



  // check if the password linked to the account is correct
  if(password_verify($password_tried, $account_password)){
    // password correct

    if($account_active_account == 0){ // account suspended (by admin)
      echo returnError($display_error_account_suspended);
      exit();
    }elseif ($account_active_account == 2) { // account deleted by user
      echo returnError($display_error_account_deleted);
      exit();
    }else { // account is active

      if($account_checked_mail){ // mail is checked
        $session_token = generateSessionToken();
        $last_connection = strtotime(date('d-m-Y H:i:s'));
        $update_session_token = 'UPDATE users
        SET session_token = :session_token, last_connection = :last_connection
        WHERE id LIKE :id AND active_account LIKE 1';
        $update_session_token = $base->prepare($update_session_token);
        $update_session_token->bindValue('id', $account_id, PDO::PARAM_INT);
        $update_session_token->bindValue('last_connection', $last_connection, PDO::PARAM_INT);
        $update_session_token->bindValue('session_token', $session_token, PDO::PARAM_STR);
        $update_session_token->execute();

        $tabInfoUser = array(
          "session_token" => $session_token
        );

        // so we return user info
        echo returnResponse($tabInfoUser);
      }else { // mail not activated
        echo returnError($display_error_account_not_activated);
        exit();
      }
    }

  }else {
    // password incorrect
    echo returnError($display_error_wrong_pseudo_password);
    // echo "Wrong pseudo/password";
    exit();
  }


 ?>
