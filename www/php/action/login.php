<?php
require_once('../config.php');
include "../convertAngularResponse.php";

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
  while($pseudo_user = $check_pseudo_user->fetch())
  {
    // get his informations
    $account_id = $pseudo_user['id'];
    $account_lastname = $pseudo_user['lastname'];
    $account_firstname = $pseudo_user['firstname'];
    $account_pseudo = $pseudo_user['pseudo'];
    $account_password = $pseudo_user['password'];
    $account_created_date = $pseudo_user['created_date'];
    $account_mail = $pseudo_user['mail'];
    $account_permissions = $pseudo_user['permissions'];
    $account_active_account = $pseudo_user['active_account'];
  }


  // check if the password linked to the account is correct
  if(password_verify($password_tried, $account_password)){
    // password correct

    $_SESSION['id'] = $account_id;
    $_SESSION['lastname'] = $account_lastname;
    $_SESSION['firstname'] = $account_firstname;
    $_SESSION['pseudo'] = $account_pseudo;
    $_SESSION['created_date'] = $account_created_date;
    $_SESSION['mail'] = $account_mail;
    $_SESSION['permissions'] = $account_permissions;
    $_SESSION['active_account'] = $account_active_account;

    $tabInfoUser = array(
      "userId" => $account_id,
      "lastname" => $account_lastname,
      "firstname" => $account_firstname,
      "pseudo" => $account_pseudo,
      "mail" => $account_mail,
      "active_account" => $account_active_account,
    );

    echo json_encode($tabInfoUser);
  }else {
    // password incorrect
    echo "Wrong pseudo/password";
    exit();
  }


 ?>
