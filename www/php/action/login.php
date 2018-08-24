<?php
require_once('../config.php');
include "../convertAngularResponse.php";

// get user pseudo
// check if pseudo exists
  if(isset($request->pseudo)){
    $pseudo = htmlspecialchars($request->pseudo, ENT_QUOTES);
  }else{
    $pseudo = "";
  }

  // get password that user tried
  $password_tried = htmlspecialchars($request->password, ENT_QUOTES);


  // check if user exists
  $check_pseudo_user = 'SELECT * FROM users WHERE pseudo LIKE :pseudo';
  $check_pseudo_user = $base->prepare($check_pseudo_user);
  $check_pseudo_user->bindValue('pseudo', $pseudo, PDO::PARAM_STR);
  $check_pseudo_user->execute();
  while($pseudo_user = $check_pseudo_user->fetch())
  {
    // get his informations
    $account_id = $pseudo_user['id'];
    $account_lastname = $pseudo_user['lastname'];
    $account_firstname = $pseudo_user['firstname'];
    $account_pseudo = $pseudo_user['pseudo'];
    $account_password = $pseudo_user['password'];
    $account_birth_date = $pseudo_user['birth_date'];
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
    $_SESSION['birth_date'] = $account_birth_date;
    $_SESSION['mail'] = $account_mail;
    $_SESSION['permissions'] = $account_permissions;
    $_SESSION['active_account'] = $account_active_account;
    echo "Connection Accepted";
  }else {
    // password incorrect
    echo "Wrong pseudo/password";
    exit();
  }


 ?>
