<?php
require_once('../config.php');

$postRequest = file_get_contents("php://input");
$request = json_decode($postRequest);
// $result = $request->coucou;

// get user lastname
  if($request->lastname){
    $lastname = htmlspecialchars($_POST['lastname'], ENT_QUOTES);
  }else{
    $lastname = "";
  }


// get user firstname
  if($request->firstname){
    $firstname = htmlspecialchars($_POST['firstname'], ENT_QUOTES);
  }else{
    $firstname = "";
  }


// get user pseudo
  if($request->pseudo){
    $pseudo = htmlspecialchars($_POST['pseudo'], ENT_QUOTES);
  }else{
    $pseudo = "";
  }


// get user birth_date
  if($request->birth_date){
    $birth_date = htmlspecialchars($_POST['birth_date'], ENT_QUOTES);
  }else{
    $birth_date = "";
  }


// get user mail
  if($request->mail){
    $mail = htmlspecialchars($_POST['mail'], ENT_QUOTES);
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
