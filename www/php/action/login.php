<?php
require_once('../config.php');
include "../includedFiles.php";

// get user pseudo
// check if pseudo exists
  if(isset($request->login)){
    $login = htmlspecialchars($request->login, ENT_QUOTES);
  }else{
    $login = "";
  }

  // get password that user tried
  if(isset($request->password)){
    $password_tried = htmlspecialchars($request->password, ENT_QUOTES);
  }else {
    return returnError($display_error_empty_field);
    exit();
  }
  $account_password = "";


  try {
    // check if user exists
    $check_pseudo_user = 'SELECT * FROM users WHERE (pseudo=:login1 OR mail=:login2)';
    $check_pseudo_user = $base->prepare($check_pseudo_user);
    $check_pseudo_user->bindValue('login1', Chiffrement::crypt($login), PDO::PARAM_STR);
    $check_pseudo_user->bindValue('login2', Chiffrement::crypt($login), PDO::PARAM_STR);
    $check_pseudo_user->execute();
    while($user_info = $check_pseudo_user->fetch())
    {
      // get his informations
      $account_id = $user_info['id'];
      $account_lastname = Chiffrement::decrypt($user_info['lastname']);
      $account_firstname = Chiffrement::decrypt($user_info['firstname']);
      $account_pseudo = Chiffrement::decrypt($user_info['pseudo']);
      $account_password = $user_info['password'];
      $account_created_date = $user_info['created_date'];
      $account_mail = Chiffrement::decrypt($user_info['mail']);
      $account_permissions = $user_info['permissions'];
      $account_active_account = $user_info['active_account'];
      $account_checked_mail = $user_info['checked_mail'];
      $account_blocked_account = $user_info['blocked_account'];
      $account_date_unblocked_account = $user_info['date_unblocked_account'];
      $account_lang = $user_info['lang'];
    }
  } catch (\Exception $e) {
    echo returnError($display_error_error_occured);
    exit();
  }

  if(isset($account_id)){
    $date_now = strtotime(date('d-m-Y H:i:s'));
    if($account_blocked_account != 0 && ($date_now < $account_date_unblocked_account)){ // if account is blocked
      echo returnError($display_error_account_blocked); // display an error with time for unblocked account
      exit();
    }else { // account is not blocked

      // check if the password linked to the account is correct
      if(password_verify($password_tried, $account_password)){
        // password correct


        if($account_blocked_account != 0){ // if account has been blocked (and is no longer blocked), then we clear the values in database
          $request_edit_user_account_unblocked = "UPDATE users
          SET blocked_account = 0,
          date_unblocked_account = 0
          WHERE id LIKE $account_id";
          $request_edit_user_account_unblocked = $base->prepare($request_edit_user_account_unblocked);
          $request_edit_user_account_unblocked->execute();
        }


        if($account_active_account == 0){ // account suspended (by admin)
          echo returnError($display_error_account_suspended);
          exit();
        }elseif ($account_active_account == 2) { // account deleted by user
          echo returnError($display_error_account_deleted);
          exit();
        }else { // account is active

          if($account_checked_mail){ // mail is checked
            $session_token = generateSessionToken();
            $last_connection = strtotime(date('d-m-Y H:i:s'));
            $update_session_token = 'UPDATE users
            SET session_token = :session_token, last_connection = :last_connection
            WHERE id LIKE :id AND active_account LIKE 1';
            $update_session_token = $base->prepare($update_session_token);
            $update_session_token->bindValue('id', $account_id, PDO::PARAM_INT);
            $update_session_token->bindValue('last_connection', $last_connection, PDO::PARAM_INT);
            $update_session_token->bindValue('session_token', $session_token, PDO::PARAM_STR);
            $update_session_token->execute();

            $tabInfoUser = array(
              "session_token" => $session_token
            );

            // so we return user info
            echo returnResponse($tabInfoUser);
          }else { // mail not activated
            echo returnError($display_error_account_not_activated);
            exit();
          }
        }

      }else {
        // password incorrect

        $date_action = strtotime(date('d-m-Y H:i:s'));
        $requete_add_log_fails = "INSERT INTO fail_connection_list (user_id, login_tried, date_action)
        VALUES (:user_id, :login_tried, :date_action)";
        $requete_add_log_fails = $base->prepare($requete_add_log_fails);
        $requete_add_log_fails->bindValue('user_id', $account_id, PDO::PARAM_INT);
        $requete_add_log_fails->bindValue('login_tried', Chiffrement::crypt($login), PDO::PARAM_STR);
        $requete_add_log_fails->bindValue('date_action', $date_action, PDO::PARAM_INT);
        $requete_add_log_fails->execute();

        $date_blocked_time = $date_action - $timing_blocked_account;
        $request_total_attempts = "SELECT COUNT(*) AS nb_attempts FROM fail_connection_list WHERE user_id LIKE :user_id AND date_action > :date_with_blocked_time";
        $request_total_attempts = $base->prepare($request_total_attempts);
        $request_total_attempts->bindValue('user_id', $account_id, PDO::PARAM_INT);
        $request_total_attempts->bindValue('date_with_blocked_time', $date_blocked_time, PDO::PARAM_INT);
        $request_total_attempts->execute();
        while($total_attempts = $request_total_attempts->fetch())
        {
          // count every failed connection that happened over the blocked time (which is 10min by default)
          $nb_attempts = $total_attempts['nb_attempts'];
          if($nb_attempts >= $max_amount_fail_connection){ // if there are too many fails
            $account_blocked_account++;
            if($account_blocked_account == 1){ // level 1 security blocked_account
              // there $account_blocked_account = 1, so it's useless using it as a multiplier, but in case we want to add the level 2 of security in the "if"
              // which would mean that at level 2 it would still be 10 min of account blocked
              $date_unblocked_time = ($date_action + $timing_blocked_account)*$account_blocked_account; // just add the block time
            }else {
              $date_unblocked_time = $date_action + (($timing_blocked_account*$blocked_account_multiplier)*$account_blocked_account); // ex with 10 min : ((600*1.5)*2) = 1800 = 30min for a lvl 2 security
            }
            $request_edit_user_account_blocked = "UPDATE users
            SET blocked_account = $account_blocked_account,
            date_unblocked_account = '$date_unblocked_time'
            WHERE id LIKE $account_id";
            $request_edit_user_account_blocked = $base->prepare($request_edit_user_account_blocked);
            $request_edit_user_account_blocked->execute();
            // we block the user's account


            // we get his ip (but not the full ip)
            $ip = get_ip();
            $ip = explode('.', $ip);
            $user_ip = "";
            for($i=0; $i<sizeof($ip)-2; $i++){
              $user_ip .= $ip[$i].".";
            }
            // $user_ip = substr($user_ip, 0, -1);
            $user_ip .= "0.0";
            $request_add_log_fails_account_blocked = "INSERT INTO fail_connection_list_account_blocked (user_id, user_ip, login_tried, date_blocked, date_unblocked)
            VALUES (:user_id, :user_ip, :login_tried, :date_blocked, :date_unblocked)";
            $request_add_log_fails_account_blocked = $base->prepare($request_add_log_fails_account_blocked);
            $request_add_log_fails_account_blocked->bindValue('user_id', $account_id, PDO::PARAM_INT);
            $request_add_log_fails_account_blocked->bindValue('user_ip', $user_ip, PDO::PARAM_STR);
            $request_add_log_fails_account_blocked->bindValue('login_tried', Chiffrement::crypt($login), PDO::PARAM_STR);
            $request_add_log_fails_account_blocked->bindValue('date_blocked', $date_action, PDO::PARAM_INT);
            $request_add_log_fails_account_blocked->bindValue('date_unblocked', $date_unblocked_time, PDO::PARAM_INT);
            $request_add_log_fails_account_blocked->execute();

            sendMailAccountBlocked($account_lastname, $account_firstname, $account_pseudo, $account_mail, $account_lang, $date_unblocked_time);
            echo returnError($display_error_account_blocked);
            exit();
          }
        }
        echo returnError($display_error_wrong_pseudo_password);
        exit();
      }

    }
  }else {
    echo returnError($display_error_wrong_pseudo_password);
    exit();
  }


  function get_ip() {
	// IP si internet partagé
	if (isset($_SERVER['HTTP_CLIENT_IP'])) {
		return $_SERVER['HTTP_CLIENT_IP'];
	}
	// IP derrière un proxy
	elseif (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
		return $_SERVER['HTTP_X_FORWARDED_FOR'];
	}
	// Sinon : IP normale
	else {
		return (isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : '');
	}
}


 ?>
