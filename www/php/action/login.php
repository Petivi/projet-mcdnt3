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
  $password_tried = htmlspecialchars($request->password, ENT_QUOTES);
  $account_password = "";


  // check if user exists
  $check_pseudo_user = 'SELECT * FROM users WHERE (pseudo=:login1 OR mail=:login2) AND active_account LIKE 1';
  $check_pseudo_user = $base->prepare($check_pseudo_user);
  $check_pseudo_user->bindValue('login1', $login, PDO::PARAM_STR);
  $check_pseudo_user->bindValue('login2', $login, PDO::PARAM_STR);
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
  }


  // check if the password linked to the account is correct
  if(password_verify($password_tried, $account_password)){
    // password correct

    $tabInfoUser = array(
      "userId" => $account_id,
      "lastname" => $account_lastname,
      "firstname" => $account_firstname,
      "pseudo" => $account_pseudo,
      "mail" => $account_mail,
      "active_account" => $account_active_account,
    );

    // echo json_encode($tabInfoUser);
    echo returnResponse($tabInfoUser);
  }else {
    // password incorrect
    echo returnError("Wrong pseudo/password");
    // echo "Wrong pseudo/password";
    exit();
  }


 ?>
