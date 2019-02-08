<?php

// require_once('../config.php');

if(is_file('../../config.php')){
  require_once('../../config.php');
}else if(is_file('../config.php')) {
  require_once('../config.php');
}else {
  require_once('config.php');
}

# ------------------
# Create a campaign\
# ------------------

# Include the SendinBlue library\
// require_once("../vendor/autoload.php");
if(is_file('../../vendor/autoload.php')){
  require_once('../../vendor/autoload.php');
}else if(is_file('../vendor/autoload.php')) {
  require_once('../vendor/autoload.php');
}else {
  require_once('vendor/autoload.php');
}

# Instantiate the client\
SendinBlue\Client\Configuration::getDefaultConfiguration()->setApiKey("api-key", $sendinblue_api_key);

$api_instance = new SendinBlue\Client\Api\EmailCampaignsApi();
$emailCampaigns = new \SendinBlue\Client\Model\CreateEmailCampaign();
if(is_file('../../PHPMail/V2.0/Mailin.php')){
  require_once('../../PHPMail/V2.0/Mailin.php');
}else if(is_file('../PHPMail/V2.0/Mailin.php')) {
  require_once('../PHPMail/V2.0/Mailin.php');
}else {
  require_once('PHPMail/V2.0/Mailin.php');
}
// require('../PHPMail/V2.0/Mailin.php');
$mailin = new Mailin('https://api.sendinblue.com/v2.0', $sendinblue_access_key, 5000);    //Optional parameter: Timeout in MS


$mail_header = "<body style='margin:0'>
  <table width='100%' border='0' cellspacing='0' cellpadding='0' style='background-color:#485967;padding: 40px 30px 40px 30px;'>
      <tr>
          <td style='padding-left: 20%;font-size:33px; line-height:38px;font-weight:bold;color:#fee208;'>
              Wow Planner
          </td>
      </tr>
  </table>
  <div style=width:80%;margin:auto;text-align:center;padding:40px 20px 40px 20px;>";


$mail_footer = "</div>
<table width='100%' border='0' cellspacing='0' cellpadding='0' style='background-color:#485967;padding: 10px 10px 10px 10px;'>
    <tr>
        <td style='padding-left: 5%; line-height:38px;font-weight:bold;color:#fee208;'>
            <a style='color:#fee208'; href='".$urlServerFront."'>Wow Planner</a>
        </td>
    </tr>
</table>
</body>";


function sendMailNewUser($lastname, $firstname, $pseudo, $mail, $token_temp, $lang){
  global $mail_no_reply;
  global $app_name;
  global $uri_activate_account;
  global $urlServerBack;
  global $mailin;
  global $mail_header;
  global $mail_footer;

  $activateLink = $urlServerBack.$uri_activate_account;
  if($lang == "fr"){
    $subject = "Création de compte";
    $html = $mail_header."Bonjour <span style=font-weight:bold;>".$pseudo."</span>, veuillez <a href='".$activateLink."?token=".$token_temp."'>Cliquez sur ce lien pour activer votre compte</a>".$mail_footer;
  }else {
    $subject = "Account creation";
    $html = $mail_header."Hello <span style=font-weight:bold;>".$pseudo."</span>, please <a href='".$activateLink."?token=".$token_temp."'>Click on that link to activate your account</a>".$mail_footer;
  }
  $data = array( "to" => array($mail=>$lastname." ".$firstname),
  "from" => array($mail_no_reply, $app_name),
  "subject" => $subject,
  "html" => $html
  );

  $mailin->send_email($data);
}


function sendMailEditMail($lastname, $firstname, $pseudo, $mail, $token_temp, $lang){
  global $mail_no_reply;
  global $app_name;
  global $uri_activate_account;
  global $urlServerBack;
  global $mailin;
  global $mail_header;
  global $mail_footer;

  $activateLink = $urlServerBack.$uri_activate_account;
  if($lang == "fr"){
    $subject = "Edition du mail";
    $html = $mail_header."Bonjour <span style=font-weight:bold;>".$pseudo."</span>, veuillez <a href='".$activateLink."?token=".$token_temp."'>Cliquez sur ce lien pour vérifier votre nouvelle adresse mail</a>".$mail_footer;
  }else {
    $subject = "Mail edit";
    $html = $mail_header."Hello <span style=font-weight:bold;>".$pseudo."</span>, please <a href='".$activateLink."?token=".$token_temp."'>Click on that link to verify your new mail adress</a>".$mail_footer;
  }
  $data = array( "to" => array($mail=>$lastname." ".$firstname),
  "from" => array($mail_no_reply, $app_name),
  "subject" => $subject,
  "html" => $html
  );

  $mailin->send_email($data);
}


function sendMailResetPass($lastname, $firstname, $pseudo, $mail, $token_temp, $lang){
  global $mail_no_reply;
  global $app_name;
  global $uri_reset_password;
  global $urlServerFront;
  global $nb_jour_token_expiration;
  global $mailin;
  global $mail_header;
  global $mail_footer;


  $activateLink = $urlServerFront.$uri_reset_password;
  if($lang == "fr"){
    $subject = "Réinitialisation de mot de passe";
    $html = $mail_header."Bonjour <span style=font-weight:bold;>".$pseudo."</span>, veuillez <a href='".$activateLink."/".$token_temp."'>Cliquez sur ce lien pour réinitialiser votre mot de passe</a>
    <br>Votre lien est valable pendant ".$nb_jour_token_expiration.$mail_footer;
  }else {
    $subject = "Password reset";
    $html = $mail_header."Hello <span style=font-weight:bold;>".$pseudo."</span>, please <a href='".$activateLink."/".$token_temp."'>Click on that link to reset your password</a>
    <br>Your link is available ".$nb_jour_token_expiration.$mail_footer;
  }
  $data = array( "to" => array($mail=>$lastname." ".$firstname),
  "from" => array($mail_no_reply, $app_name),
  "subject" => $subject,
  "html" => $html
  );

  $mailin->send_email($data);
}


function sendMailUnsubscribe($lastname, $firstname, $pseudo, $mail, $token_temp, $lang){
  global $mail_no_reply;
  global $app_name;
  global $mailin;
  global $mail_header;
  global $mail_footer;

  if($lang == "fr"){
    $subject = "Suppression de compte";
    $html = $mail_header."Bonjour <span style=font-weight:bold;>".$pseudo."</span>, nous vous confirmons la suppression de votre compte".$mail_footer;
  }else {
    $subject = "Password reset";
    $html = $mail_header."Hello <span style=font-weight:bold;>".$pseudo."</span>, we confirm you that your account has been successfully deleted".$mail_footer;
  }
  $data = array( "to" => array($mail=>$lastname." ".$firstname),
  "from" => array($mail_no_reply, $app_name),
  "subject" => $subject,
  "html" => $html
  );

  $mailin->send_email($data);
}

function sendMailProfilEditedByAdmin($lastname, $firstname, $pseudo, $mail, $lang){
  global $mail_no_reply;
  global $app_name;
  global $mailin;
  global $mail_header;
  global $mail_footer;

  if($lang == "fr"){
    $subject = "Compte modifié par un administrateur";
    $html = $mail_header ."
        Bonjour <span style=font-weight:bold;>".$pseudo."</span>, nous vous informons que votre compte a été modifié par un administrateur.
        <br>Pour de plus amples informations, connectez-vous sur le site et allez à la rubrique nous contacter.
        <br><br>Si votre pseudo a été modifié, veuillez utiliser le nouveau pour vous connecter, ou bien utiliser votre adresse mail.
        ".$mail_footer;
  }else {
    $subject = "Account edited by an administrator";
    $html = $mail_header."
    Hello <span style=font-weight:bold;>".$pseudo."</span>, we inform you that your account has been modified by an administrator.
    <br>For further information, connect to the site and go to the section contact us.
    <br><br>If your username has been changed, please use the new one to login, or use your e-mail.
    ".$mail_footer;
  }
  $data = array( "to" => array($mail=>$lastname." ".$firstname),
  "from" => array($mail_no_reply, $app_name),
  "subject" => $subject,
  "html" => $html
  );

  $mailin->send_email($data);
}


function sendMailAnswerContactMessage($mail, $data, $request_ref){
  global $mail_no_reply;
  global $app_name;
  global $mailin;
  global $mail_header;
  global $mail_footer;

    $subject = "Request : " . $request_ref;
    $html = $mail_header . $data .$mail_footer;

  $data = array( "to" => array($mail=>" "),
  "from" => array($mail_no_reply, $app_name),
  "subject" => $subject,
  "html" => $html
  );

  $mailin->send_email($data);
}


function sendMailAccountBlocked($lastname, $firstname, $pseudo, $mail, $lang, $date_unblocked_time){
  global $mail_no_reply;
  global $app_name;
  global $mailin;
  global $mail_header;
  global $mail_footer;

  $date = date('d/m/Y H:i:s', $date_unblocked_time);

  if($lang == "fr"){
    $subject = "Compte bloqué temporairement";
    $html = $mail_header ."
        Bonjour <span style=font-weight:bold;>".$pseudo."</span>, suite à de trop nombreuses tentatives de connexion et par mesure de sécurité, votre compte est bloqué jusqu'à : $date.
        <br>Si vous n'êtes pas à l'origine de ces tentatives, nous vous invitons à vous connecter et à modifier vos informations de connexion.
        <br><br>Veillez à ne jamais divulguer vos informations de connexion à qui que ce soit.
        ".$mail_footer;
  }else {
    $subject = "Account temporarily blocked";
    $html = $mail_header."
    Hello <span style=font-weight:bold;>".$pseudo."</span>, following too many login attempts and as a security measure, your account is blocked until : $date.
    <br>If you're not behind these attempts, we invite you to sign in and change your login information.
    <br><br>Make sure you never divulge your login information to anyone.
    ".$mail_footer;
  }
  $data = array( "to" => array($mail=>$lastname." ".$firstname),
  "from" => array($mail_no_reply, $app_name),
  "subject" => $subject,
  "html" => $html
  );

  $mailin->send_email($data);
}

 ?>
