<?php
require_once('../config.php');
include "../includedFiles.php";

// convert angular response (with more paramaters)
if(isset($request)){
$requestUser = $request->user;
}


// get user lastname
// check if lastname exists
  if(isset($requestUser->lastname)){
    $lastname = htmlspecialchars($requestUser->lastname, ENT_QUOTES);
  }else{
    $lastname = "";
  }


// get user firstname
// check if firstname exists
  if(isset($requestUser->firstname)){
    $firstname = htmlspecialchars($requestUser->firstname, ENT_QUOTES);
  }else{
    $firstname = "";
  }


// get user pseudo
// check if pseudo exists
  if(isset($requestUser->pseudo)){
    $pseudo = htmlspecialchars($requestUser->pseudo, ENT_QUOTES);
  }else{
    $pseudo = "";
  }



// get user mail
// check if mail exists
  if(isset($requestUser->mail)){
    $mail = htmlspecialchars($requestUser->mail, ENT_QUOTES);
  }else{
    $mail = "";
  }



// get user password + hash it
if(isset($requestUser->password)){
  $password = htmlspecialchars($requestUser->password, ENT_QUOTES);
  $password = password_hash($password, PASSWORD_DEFAULT);
}else {
  exit();
}

  // get user lang
  // check if lang exists
    if(isset($request->lang)){
      $lang = htmlspecialchars($request->lang, ENT_QUOTES);
    }else{
      $lang = "";
    }

// sql request to check if mail is already taken
 $existing_mail = false;
 $check_mail_user = 'SELECT * FROM users WHERE mail LIKE :mail AND active_account LIKE (0 OR 1)';
 $check_mail_user = $base->prepare($check_mail_user);
 $check_mail_user->bindValue('mail', $mail, PDO::PARAM_STR);
 $check_mail_user->execute();
 while($mail_user = $check_mail_user->fetch())
 {
   // mail already taken
   $existing_mail = true;
 }

 if($existing_mail){  // mail taken
   echo returnError("Mail already taken");
   exit();
 }else {  // mail not taken

   $existing_pseudo = false;
   $check_pseudo_user = 'SELECT * FROM users WHERE pseudo LIKE :pseudo AND active_account LIKE (0 OR 1)';
   $check_pseudo_user = $base->prepare($check_pseudo_user);
   $check_pseudo_user->bindValue('pseudo', $pseudo, PDO::PARAM_STR);
   $check_pseudo_user->execute();
   while($pseudo_user = $check_pseudo_user->fetch())
   {
     // pseudo already taken
     $existing_pseudo = true;
   }

   if($existing_pseudo){ // pseudo taken
     echo returnError("Pseudo already taken");
     exit();
   }else { // pseudo not taken
     $token_temp = generateTokenTemp();

     $token_unique = true;
     // check if activation code is unique
     $check_token_temp = 'SELECT * FROM users WHERE token_temp LIKE :token_temp';
     $check_token_temp = $base->prepare($check_token_temp);
     $check_token_temp->bindValue('token_temp', $token_temp, PDO::PARAM_STR);
     $check_token_temp->execute();
     while($check_code = $check_token_temp->fetch())
     {
       $token_unique = false;
     }

     // activation code is unique
     if($token_unique){
       $mail_sent = false;
       try {
         $created_date = strtotime(date('d-m-Y'));
         $active_account = 1; // 1 = active account and 0 = locked account
         $date_token_created = strtotime(date('d-m-Y'));
         $insert_new_user = 'INSERT INTO users (lastname, firstname, pseudo, password, created_date, mail, active_account, token_temp, date_token_created)
         VALUES (:lastname, :firstname, :pseudo, :password, :created_date, :mail, :active_account, :token_temp, :date_token_created)';
         $insert_new_user = $base->prepare($insert_new_user);
         $insert_new_user->bindValue('lastname', $lastname, PDO::PARAM_STR);
         $insert_new_user->bindValue('firstname', $firstname, PDO::PARAM_STR);
         $insert_new_user->bindValue('pseudo', $pseudo, PDO::PARAM_STR);
         $insert_new_user->bindValue('password', $password, PDO::PARAM_STR);
         $insert_new_user->bindValue('created_date', $created_date, PDO::PARAM_INT);
         $insert_new_user->bindValue('mail', $mail, PDO::PARAM_STR);
         $insert_new_user->bindValue('active_account', $active_account, PDO::PARAM_INT);
         $insert_new_user->bindValue('token_temp', $token_temp, PDO::PARAM_STR);
         $insert_new_user->bindValue('date_token_created', $date_token_created, PDO::PARAM_INT);
         $insert_new_user->execute();

         $get_new_user_id = 'SELECT * FROM users WHERE pseudo LIKE :pseudo AND mail LIKE :mail AND active_account LIKE 1';
         $get_new_user_id = $base->prepare($get_new_user_id);
         $get_new_user_id->bindValue('pseudo', $pseudo, PDO::PARAM_STR);
         $get_new_user_id->bindValue('mail', $mail, PDO::PARAM_STR);
         $get_new_user_id->execute();
         while($user_id = $get_new_user_id->fetch())
         {
           $id = $user_id['id'];
         }

         $request_type = "New Account";
         addToRequestsList($id, $lastname, $firstname, $pseudo, $mail, $token_temp, $request_type, $date_token_created);
         sendMailNewUser($lastname, $firstname, $pseudo, $mail, $token_temp, $lang);
         $mail_sent = true;
       } catch (\Exception $e) {
         echo returnError("An Error Occured");
         exit();
       }
       if($mail_sent){
         echo returnResponse("Mail Sent");
       }else {
         echo returnError("An Error Occured");
       }

     }else {
       echo returnError("An Error Occured");
       exit();
     }
   }
 }


?>
