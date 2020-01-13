<?php
require_once('../config.php');
include "../includedFiles.php";

$tabUser = getPostInfo($request->user);


// get user password + hash it
if(isset($request->user->password)){
  $password = htmlspecialchars($request->user->password, ENT_QUOTES);
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


 if(returnCheckMail($tabUser['mail'], 0)){  // mail taken
   echo returnError($display_error_mail_taken);
   exit();
 }else {  // mail not taken

   if(returnCheckPseudo($tabUser['pseudo'], 0)){ // pseudo taken
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
         $insert_new_user->bindValue('lastname', Chiffrement::crypt($tabUser['lastname']), PDO::PARAM_STR);
         $insert_new_user->bindValue('firstname', Chiffrement::crypt($tabUser['firstname']), PDO::PARAM_STR);
         $insert_new_user->bindValue('pseudo', Chiffrement::crypt($tabUser['pseudo']), PDO::PARAM_STR);
         $insert_new_user->bindValue('password', $password, PDO::PARAM_STR);
         $insert_new_user->bindValue('created_date', $created_date, PDO::PARAM_INT);
         $insert_new_user->bindValue('mail', Chiffrement::crypt($tabUser['mail']), PDO::PARAM_STR);
         $insert_new_user->bindValue('active_account', $active_account, PDO::PARAM_INT);
         $insert_new_user->bindValue('token_temp', $token_temp, PDO::PARAM_STR);
         $insert_new_user->bindValue('date_token_created', $date_token_created, PDO::PARAM_INT);
         $insert_new_user->execute();

         $get_new_user_id = 'SELECT * FROM users WHERE pseudo LIKE :pseudo AND mail LIKE :mail AND active_account LIKE 1';
         $get_new_user_id = $base->prepare($get_new_user_id);
         $get_new_user_id->bindValue('pseudo', Chiffrement::crypt($tabUser['pseudo']), PDO::PARAM_STR);
         $get_new_user_id->bindValue('mail', Chiffrement::crypt($tabUser['mail']), PDO::PARAM_STR);
         $get_new_user_id->execute();
         while($user_id = $get_new_user_id->fetch())
         {
           $id = $user_id['id'];
         }

         // new account
         addToRequestsList($id, $tabUser['lastname'], $tabUser['firstname'], $tabUser['pseudo'], $tabUser['mail'], $token_temp, $request_type_new_account, $date_token_created);
         sendMailNewUser($tabUser['lastname'], $tabUser['firstname'], $tabUser['pseudo'], $tabUser['mail'], $token_temp, $lang);
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
