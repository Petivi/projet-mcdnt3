<?php
require_once('../../config.php');
include "../../includedFiles.php";

$tabInfo = getCharacterInfo($request);

if (isset($tabInfo['session_token'])) {
    $session_token = htmlspecialchars($tabInfo['session_token'], ENT_QUOTES);

    $user_exists = false;
    $request_user_info = 'SELECT * FROM users WHERE session_token LIKE :session_token';
    $request_user_info = $base->prepare($request_user_info);
    $request_user_info->bindValue('session_token', $session_token, PDO::PARAM_STR);
    $request_user_info->execute();
    while ($user_info = $request_user_info->fetch()) {
        // get his informations
        $account_id = $user_info['id'];
        $account_pseudo = Chiffrement::decrypt($user_info['pseudo']);
        $account_mail = Chiffrement::decrypt($user_info['mail']);
        $account_active_account = $user_info['active_account'];
        $account_checked_mail = $user_info['checked_mail'];
        if ($account_active_account && $account_checked_mail) {
            $user_exists = true;
        }
    }

    if ($user_exists) {
        if ($tabInfo['character_name'] == "") { // if no name is given for the character, then we use the user pseudo
            $character_name = $account_pseudo;
        } else {
            $character_name = $tabInfo['character_name'];
        }
        $date_today = strtotime(date('d-m-Y H:i:s'));

        $insert_new_character = 'INSERT INTO characters_list (user_id, name, race_id, class_id, head_id, neck_id, shoulder_id, chest_id, waist_id, legs_id, feet_id, wrist_id, hands_id, finger1_id, finger2_id, trinket1_id, trinket2_id, back_id, main_hand_id, off_hand_id, attack, armour, stamina, health, critical_strike, haste, mastery, versatility,
      head_icon, neck_icon, shoulder_icon, chest_icon, waist_icon, legs_icon, feet_icon, wrist_icon, hands_icon, finger1_icon, finger2_icon, trinket1_icon, trinket2_icon, back_icon, main_hand_icon, off_hand_icon,
       created_date, last_modified)
    VALUES (:user_id, :name, :race_id, :class_id, :head_id, :neck_id, :shoulder_id, :chest_id, :waist_id, :legs_id, :feet_id, :wrist_id, :hands_id, :finger1_id, :finger2_id, :trinket1_id, :trinket2_id, :back_id, :main_hand_id, :off_hand_id, :attack, :armour, :stamina, :health, :critical_strike, :haste, :mastery, :versatility,
      :head_icon, :neck_icon, :shoulder_icon, :chest_icon, :waist_icon, :legs_icon, :feet_icon, :wrist_icon, :hands_icon, :finger1_icon, :finger2_icon, :trinket1_icon, :trinket2_icon, :back_icon, :main_hand_icon, :off_hand_icon,
       :created_date, :last_modified)';
        $insert_new_character = $base->prepare($insert_new_character);
        $insert_new_character->bindValue('user_id', $account_id, PDO::PARAM_INT);
        $insert_new_character->bindValue('name', $character_name, PDO::PARAM_STR);
        $insert_new_character->bindValue('race_id', $tabInfo['character_race_id'], PDO::PARAM_INT);
        $insert_new_character->bindValue('class_id', $tabInfo['character_class_id'], PDO::PARAM_INT);
        $insert_new_character->bindValue('head_id', $tabInfo['character_head']['id'], PDO::PARAM_INT);
        $insert_new_character->bindValue('neck_id', $tabInfo['character_neck']['id'], PDO::PARAM_INT);
        $insert_new_character->bindValue('shoulder_id', $tabInfo['character_shoulder']['id'], PDO::PARAM_INT);
        $insert_new_character->bindValue('chest_id', $tabInfo['character_chest']['id'], PDO::PARAM_INT);
        $insert_new_character->bindValue('waist_id', $tabInfo['character_waist']['id'], PDO::PARAM_INT);
        $insert_new_character->bindValue('legs_id', $tabInfo['character_legs']['id'], PDO::PARAM_INT);
        $insert_new_character->bindValue('feet_id', $tabInfo['character_feet']['id'], PDO::PARAM_INT);
        $insert_new_character->bindValue('wrist_id', $tabInfo['character_wrist']['id'], PDO::PARAM_INT);
        $insert_new_character->bindValue('hands_id', $tabInfo['character_hands']['id'], PDO::PARAM_INT);
        $insert_new_character->bindValue('finger1_id', $tabInfo['character_finger1']['id'], PDO::PARAM_INT);
        $insert_new_character->bindValue('finger2_id', $tabInfo['character_finger2']['id'], PDO::PARAM_INT);
        $insert_new_character->bindValue('trinket1_id', $tabInfo['character_trinket1']['id'], PDO::PARAM_INT);
        $insert_new_character->bindValue('trinket2_id', $tabInfo['character_trinket2']['id'], PDO::PARAM_INT);
        $insert_new_character->bindValue('back_id', $tabInfo['character_back']['id'], PDO::PARAM_INT);
        $insert_new_character->bindValue('main_hand_id', $tabInfo['character_main_hand']['id'], PDO::PARAM_INT);
        $insert_new_character->bindValue('off_hand_id', $tabInfo['character_off_hand']['id'], PDO::PARAM_INT);
        $insert_new_character->bindValue('attack', $tabInfo['character_attack'], PDO::PARAM_INT);
        $insert_new_character->bindValue('armour', $tabInfo['character_armour'], PDO::PARAM_INT);
        $insert_new_character->bindValue('stamina', $tabInfo['character_stamina'], PDO::PARAM_INT);
        $insert_new_character->bindValue('health', $tabInfo['character_health'], PDO::PARAM_INT);
        $insert_new_character->bindValue('critical_strike', $tabInfo['character_critical_strike'], PDO::PARAM_INT);
        $insert_new_character->bindValue('haste', $tabInfo['character_haste'], PDO::PARAM_INT);
        $insert_new_character->bindValue('mastery', $tabInfo['character_mastery'], PDO::PARAM_INT);
        $insert_new_character->bindValue('versatility', $tabInfo['character_versatility'], PDO::PARAM_INT);
        $insert_new_character->bindValue('head_icon', $tabInfo['character_head']['icon'], PDO::PARAM_STR);
        $insert_new_character->bindValue('neck_icon', $tabInfo['character_neck']['icon'], PDO::PARAM_STR);
        $insert_new_character->bindValue('shoulder_icon', $tabInfo['character_shoulder']['icon'], PDO::PARAM_STR);
        $insert_new_character->bindValue('chest_icon', $tabInfo['character_chest']['icon'], PDO::PARAM_STR);
        $insert_new_character->bindValue('waist_icon', $tabInfo['character_waist']['icon'], PDO::PARAM_STR);
        $insert_new_character->bindValue('legs_icon', $tabInfo['character_legs']['icon'], PDO::PARAM_STR);
        $insert_new_character->bindValue('feet_icon', $tabInfo['character_feet']['icon'], PDO::PARAM_STR);
        $insert_new_character->bindValue('wrist_icon', $tabInfo['character_wrist']['icon'], PDO::PARAM_STR);
        $insert_new_character->bindValue('hands_icon', $tabInfo['character_hands']['icon'], PDO::PARAM_STR);
        $insert_new_character->bindValue('finger1_icon', $tabInfo['character_finger1']['icon'], PDO::PARAM_STR);
        $insert_new_character->bindValue('finger2_icon', $tabInfo['character_finger2']['icon'], PDO::PARAM_STR);
        $insert_new_character->bindValue('trinket1_icon', $tabInfo['character_trinket1']['icon'], PDO::PARAM_STR);
        $insert_new_character->bindValue('trinket2_icon', $tabInfo['character_trinket2']['icon'], PDO::PARAM_STR);
        $insert_new_character->bindValue('back_icon', $tabInfo['character_back']['icon'], PDO::PARAM_STR);
        $insert_new_character->bindValue('main_hand_icon', $tabInfo['character_main_hand']['icon'], PDO::PARAM_STR);
        $insert_new_character->bindValue('off_hand_icon', $tabInfo['character_off_hand']['icon'], PDO::PARAM_STR);
        $insert_new_character->bindValue('created_date', $date_today, PDO::PARAM_INT);
        $insert_new_character->bindValue('last_modified', $date_today, PDO::PARAM_INT);
        $insert_new_character->execute();

        $request_character_infos = 'SELECT * FROM characters_list WHERE user_id LIKE :user_id AND created_date LIKE :created_date';
        $request_character_infos = $base->prepare($request_character_infos);
        $request_character_infos->bindValue('user_id', $account_id, PDO::PARAM_INT);
        $request_character_infos->bindValue('created_date', $date_today, PDO::PARAM_INT);
        $request_character_infos->execute();
        while ($character_infos = $request_character_infos->fetch()) {
            $character_id = $character_infos['id'];
        }

        $log_type = "Nouveau personnage";
        addToCharactersLogs($character_id, $account_id, $log_type, $date_today);
        echo returnResponse($display_response_empty);
    } else {
        echo returnError($display_error_error_occured);
    }
} else { // no session_token sent, so we can't do anything
    echo returnError($display_error_empty);
    exit();
}
