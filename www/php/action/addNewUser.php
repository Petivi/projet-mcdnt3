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
  return returnError($display_error_empty_field);
  exit();
}

  // get user lang
  // check if lang exists
  if(isset($request->lang)){
    $lang = htmlspecialchars($request->lang, ENT_QUOTES);
  }else {
    $lang = "en";
  }


 if(returnCheckMail($mail, 0)){  // mail taken
   echo returnError($display_error_mail_taken);
   exit();
 }else {  // mail not taken

   if(returnCheckPseudo($pseudo, 0)){ // pseudo taken
     echo returnError($display_error_pseudo_taken);
     exit();
   }else { // pseudo not taken
     $token_temp = generateTokenTemp();

     // activation code is unique
     if($token_temp){
       $mail_sent = false;
       try {
         $created_date = strtotime(date('d-m-Y'));
         $active_account = 1; // 1 = active account and 0 = locked account
         $date_token_created = strtotime(date('d-m-Y H:i:s'));
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

         // new account
         addToRequestsList($id, $lastname, $firstname, $pseudo, $mail, $token_temp, $request_type_new_account, $date_token_created);
         sendMailNewUser($lastname, $firstname, $pseudo, $mail, $token_temp, $lang);
         $mail_sent = true;
       } catch (\Exception $e) {
         echo returnError($display_error_error_occured);
         exit();
       }
       if($mail_sent){
         echo returnResponse($display_response_mail_sent);
       }else {
         echo returnError($display_error_error_occured);
       }

     }else {
       echo returnError($display_error_error_occured);
       exit();
     }
   }
 }


?>
