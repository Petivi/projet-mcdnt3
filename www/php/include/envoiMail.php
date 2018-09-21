<?php

require_once('../config.php');

# ------------------
# Create a campaign\
# ------------------

# Include the SendinBlue library\
require_once("../vendor/autoload.php");

# Instantiate the client\
SendinBlue\Client\Configuration::getDefaultConfiguration()->setApiKey("api-key", $sendinblue_api_key);

$api_instance = new SendinBlue\Client\Api\EmailCampaignsApi();
$emailCampaigns = new \SendinBlue\Client\Model\CreateEmailCampaign();

// # Define the campaign settings\
// $emailCampaigns = array(
//     "name"=> "Campaign sent via the API",
//     "subject"=> "My subject",
//     "sender"=> array("name"=> "From name", "email"=> "wowplanner.noreply@gmail.com"),
//     "type"=> "classic",
//
//     # Content that will be sent\
//     "htmlContent"=> "Congratulations! You successfully sent this example campaign via the SendinBlue API.",
//
//     # Select the recipients\
//     "recipients"=> array("listIds"=> [2, 7]),
//
//     # Schedule the sending in one hour\
//     "scheduledAt"=> "2018-01-01 00:00:01"
// );
//
// # Make the call to the client\
// try {
//     $result = $api_instance->createEmailCampaign($emailCampaigns);
//     print_r($result);
// } catch (Exception $e) {
//     echo 'Exception when calling EmailCampaignsApi->createEmailCampaign: ', $e->getMessage(), PHP_EOL;
// }

function sendMailNewUser($lastname, $firstname, $pseudo, $mail, $token_temp, $lang){
  global $sendinblue_access_key;
  global $mail_no_reply;
  global $app_name;
  global $uri_activate_account;
  $urlServer = "http://" . $_SERVER['SERVER_NAME'];
  $activateLink = $urlServer.$uri_activate_account;
  if($lang == "fr"){
    $subject = "Création de compte";
    $html = "Bonjour ".$pseudo.", veuillez <a href='".$activateLink."?token=".$token_temp."'>Cliquez sur ce lien pour activer votre compte</a>";
  }else {
    $subject = "Account creation";
    $html = "Hello ".$pseudo.", please <a href='".$activateLink."?token=".$token_temp."'>Click on that link to activate your account</a>";
  }
  require('../PHPMail/V2.0/Mailin.php');
  $mailin = new Mailin('https://api.sendinblue.com/v2.0', $sendinblue_access_key, 5000);    //Optional parameter: Timeout in MS
  $data = array( "to" => array($mail=>$lastname." ".$firstname),
  "from" => array($mail_no_reply, $app_name),
  "subject" => $subject,
  "html" => $html
);

$mailin->send_email($data);
}
// var_dump($mailin->get_account());


function sendMailResetPass($lastname, $firstname, $pseudo, $mail, $token_temp, $lang){
  global $sendinblue_access_key;
  global $mail_no_reply;
  global $app_name;
  global $uri_reset_password;
  $urlServer = "http://" . $_SERVER['SERVER_NAME'];
  $activateLink = $urlServer.$uri_reset_password;
  if($lang == "fr"){
    $subject = "Réinitialisation de mot de passe";
    $html = "Bonjour ".$pseudo.", veuillez <a href='".$activateLink."/".$token_temp."'>Cliquez sur ce lien pour réinitialiser votre mot de passe</a>";
  }else {
    $subject = "Password reset";
    $html = "Hello ".$pseudo.", please <a href='".$activateLink."/".$token_temp."'>Click on that link to reset your password</a>";
  }
  require('../PHPMail/V2.0/Mailin.php');
  $mailin = new Mailin('https://api.sendinblue.com/v2.0', $sendinblue_access_key, 5000);    //Optional parameter: Timeout in MS
  $data = array( "to" => array($mail=>$lastname." ".$firstname),
  "from" => array($mail_no_reply, $app_name),
  "subject" => $subject,
  "html" => $html
);

$mailin->send_email($data);
}

 ?>
