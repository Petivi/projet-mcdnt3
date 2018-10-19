<?php
require_once('../../config.php');
include "../../includedFiles.php";

$tabUser = getPostInfo($request);

if(accessToAdminPermissions($tabUser['session_token'])){

  $admin_id = getIdFromSessionToken($tabUser['session_token']);

  if(isset($request->page)){
    $nb_page = htmlspecialchars($request->page, ENT_QUOTES);
  }else {
    $nb_page = 1;
  }

  
  $offsetPage = calcOffsetPage($nb_page); // calc offset to return correct values
  $table_name = 'users'; // name of our table name (in our db)
  $total_page = calcNbPage($table_name); // send the table name (in our db) and we'll have the number of page to display



  if($admin_id){

    $tabUserList = array();
    $request_user_list = 'SELECT * FROM users WHERE id NOT LIKE :account_id ORDER BY id DESC LIMIT :items_per_page OFFSET :offsetPage';
    $request_user_list = $base->prepare($request_user_list);
    $request_user_list->bindValue('account_id', $admin_id, PDO::PARAM_INT);
    $request_user_list->bindValue('items_per_page', $items_per_page, PDO::PARAM_INT);
    $request_user_list->bindValue('offsetPage', $offsetPage, PDO::PARAM_INT);
    $request_user_list->execute();
    while($user_list = $request_user_list->fetch())
    {
      array_push($tabUserList,array(
        'id' => $user_list['id'],
        'lastname' => Chiffrement::decrypt($user_list['lastname']),
        'firstname' => Chiffrement::decrypt($user_list['firstname']),
        'mail' => Chiffrement::decrypt($user_list['mail']),
        'pseudo' => Chiffrement::decrypt($user_list['pseudo']),
        'created_date' => date('d/m/Y', $user_list['created_date']),
        'last_connection' => date('d/m/Y H:i:s', $user_list['last_connection']),
        'active_account' => $user_list['active_account'],
        'checked_mail' => $user_list['checked_mail']
      ));
    }

    $tabFinal = array();
    $tabFinal['valeur'] = $tabUserList;
    $tabFinal['total_page'] = $total_page;

    echo returnResponse($tabFinal);
  }else {
    echo returnError($display_error_empty);
  }

}else {
  echo returnError($display_error_empty);
}


 ?>
