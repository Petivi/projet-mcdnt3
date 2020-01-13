<?php
require_once('../config.php');
include "../includedFiles.php";


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


// get user mail
// check if mail exists
  if(isset($request->id)){
    $id = htmlspecialchars($request->id, ENT_QUOTES);
  }else{
    $id = "";
  }

  $adminAccess  = accessToAdminPermissions($id, $lastname, $firstname, $pseudo, $mail);

  if($adminAccess){
    echo returnResponse($adminAccess);
  }else {
    echo returnError($adminAccess);
  }

 ?>
