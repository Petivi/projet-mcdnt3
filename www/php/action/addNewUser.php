<?php
require_once('../config.php');

// convert response from angular
$postRequest = file_get_contents("php://input");
$request = json_decode($postRequest);

// get user lastname
// check if lastname exists
  if(isset($request->lastname)){
    $lastname = htmlspecialchars($request->lastname, ENT_QUOTES);
  }else{
    $lastname = "";
  }


// get user firstname
// check if firstname exists
  if(isset($request->firstname)){
    $firstname = htmlspecialchars($request->firstname, ENT_QUOTES);
  }else{
    $firstname = "";
  }


// get user pseudo
// check if pseudo exists
  if(isset($request->pseudo)){
    $pseudo = htmlspecialchars($request->pseudo, ENT_QUOTES);
  }else{
    $pseudo = "";
  }


// get user birth_date
// check if birth_date exists
  if(isset($request->birth_date)){
    $birth_date = htmlspecialchars($request->birth_date, ENT_QUOTES);
  }else{
    $birth_date = "";
  }


// get user mail
// check if mail exists
  if(isset($request->mail)){
    $mail = htmlspecialchars($request->mail, ENT_QUOTES);
  }else{
    $mail = "";
  }


/***********************/
// TEST //
  // $lastname = 'Non';
  // $firstname = 'Oui';
  // $pseudo = "Yes";
  // $password = "test";
  // $birth_date = strtotime(date("d-m-Y"));
  // $mail = "ouiOui@non.fr";
/***********************/


// get user password + hash it
  $password = htmlspecialchars($request->password, ENT_QUOTES);
  $password = password_hash($password, PASSWORD_DEFAULT);


// sql request to check if mail is already taken
 $existing_user = false;
 $check_mail_user = 'SELECT * FROM users WHERE mail LIKE :mail';
 $check_mail_user = $base->prepare($check_mail_user);
 $check_mail_user->bindValue('mail', $mail, PDO::PARAM_STR);
 $check_mail_user->execute();
 while($mail_user = $check_mail_user->fetch())
 {
   // mail already taken
   $existing_user = true;
 }

 if($existing_user){
   // mail taken
   exit();
 }else {
   // mail not taken so we create new user in db
   $active_account = 1; // 1 = active account and 0 = locked account
   $insert_new_user = 'INSERT INTO users (lastname, firstname, pseudo, password, birth_date, mail, active_account)
                       VALUES (:lastname, :firstname, :pseudo, :password, :birth_date, :mail, :active_account)';
   $insert_new_user = $base->prepare($insert_new_user);
   $insert_new_user->bindValue('lastname', $lastname, PDO::PARAM_STR);
   $insert_new_user->bindValue('firstname', $firstname, PDO::PARAM_STR);
   $insert_new_user->bindValue('pseudo', $pseudo, PDO::PARAM_STR);
   $insert_new_user->bindValue('password', $password, PDO::PARAM_STR);
   $insert_new_user->bindValue('birth_date', $birth_date, PDO::PARAM_INT);
   $insert_new_user->bindValue('mail', $mail, PDO::PARAM_STR);
   $insert_new_user->bindValue('active_account', $active_account, PDO::PARAM_INT);
   $insert_new_user->execute();
 }

?>
