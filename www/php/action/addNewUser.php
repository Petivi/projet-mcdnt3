<?php
require_once('../config.php');
include "../convertAngularResponse.php";



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



// get user mail
// check if mail exists
  if(isset($request->mail)){
    $mail = htmlspecialchars($request->mail, ENT_QUOTES);
  }else{
    $mail = "";
  }



// get user password + hash it
  $password = htmlspecialchars($request->password, ENT_QUOTES);
  $password = password_hash($password, PASSWORD_DEFAULT);


// sql request to check if mail is already taken
 $existing_mail = false;
 $check_mail_user = 'SELECT * FROM users WHERE mail LIKE :mail';
 $check_mail_user = $base->prepare($check_mail_user);
 $check_mail_user->bindValue('mail', $mail, PDO::PARAM_STR);
 $check_mail_user->execute();
 while($mail_user = $check_mail_user->fetch())
 {
   // mail already taken
   $existing_mail = true;
 }

 if($existing_mail){  // mail taken
   echo "Mail already taken";
   exit();
 }else {  // mail not taken

   $existing_pseudo = false;
   $check_pseudo_user = 'SELECT * FROM users WHERE pseudo LIKE :pseudo';
   $check_pseudo_user = $base->prepare($check_pseudo_user);
   $check_pseudo_user->bindValue('pseudo', $pseudo, PDO::PARAM_STR);
   $check_pseudo_user->execute();
   while($pseudo_user = $check_pseudo_user->fetch())
   {
     // pseudo already taken
     $existing_pseudo = true;
   }

   if($existing_pseudo){ // pseudo taken
     echo "Pseudo already taken";
     exit();
   }else { // pseudo not taken
     $created_date = strtotime(date('d-m-Y'));
     $active_account = 1; // 1 = active account and 0 = locked account
     $insert_new_user = 'INSERT INTO users (lastname, firstname, pseudo, password, created_date, mail, active_account)
     VALUES (:lastname, :firstname, :pseudo, :password, :created_date, :mail, :active_account)';
     $insert_new_user = $base->prepare($insert_new_user);
     $insert_new_user->bindValue('lastname', $lastname, PDO::PARAM_STR);
     $insert_new_user->bindValue('firstname', $firstname, PDO::PARAM_STR);
     $insert_new_user->bindValue('pseudo', $pseudo, PDO::PARAM_STR);
     $insert_new_user->bindValue('password', $password, PDO::PARAM_STR);
     $insert_new_user->bindValue('created_date', $created_date, PDO::PARAM_INT);
     $insert_new_user->bindValue('mail', $mail, PDO::PARAM_STR);
     $insert_new_user->bindValue('active_account', $active_account, PDO::PARAM_INT);
     $insert_new_user->execute();
   }
 }


?>
