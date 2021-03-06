<?php
require_once('../../config.php');
include "../../includedFiles.php";

ini_set('max_execution_time', 0);

$tabInfo = getItemInfo($request);
if (isset($request->lang)) {
    $lang = htmlspecialchars($request->lang, ENT_QUOTES);
} else {
    $lang = "en";
}

if ($lang == "fr") {
    $url_early_lang = "eu";
    $local_lang = "locale=fr_UE";
} else {
    $url_early_lang = "us";
    $local_lang = "locale=en_US";
}

if (!is_null($tabInfo['item_class']) && !is_null($tabInfo['item_subClass']) && !is_null($tabInfo['item_inventory_type'])) {

    if ($tabInfo['item_quality'] == -1) {
        $add_quality = "";
    } else {
        $quality = $tabInfo['item_quality'];
        $add_quality = "AND item_quality LIKE '$quality'";
    }

    $tabListItems = array();
    $get_item_info = "SELECT * FROM items_list WHERE item_class LIKE :item_class
  AND item_subclass LIKE :item_subclass
  AND item_inventory_type LIKE :item_inventory_type
  AND item_required_level BETWEEN :item_required_level_min AND :item_required_level_max
  $add_quality
  ORDER BY item_required_level DESC, item_level DESC";
    $get_item_info = $base->prepare($get_item_info);
    $get_item_info->bindValue('item_class', $tabInfo['item_class'], PDO::PARAM_INT);
    $get_item_info->bindValue('item_subclass', $tabInfo['item_subClass'], PDO::PARAM_INT);
    $get_item_info->bindValue('item_inventory_type', $tabInfo['item_inventory_type'], PDO::PARAM_INT);
    $get_item_info->bindValue('item_required_level_min', $tabInfo['item_required_level_min'], PDO::PARAM_INT);
    $get_item_info->bindValue('item_required_level_max', $tabInfo['item_required_level_max'], PDO::PARAM_INT);
    $get_item_info->execute();
    while ($item_info = $get_item_info->fetch()) {
        $item_id = intval($item_info['item_id']);
        $item_class = intval($item_info['item_class']);
        $item_subclass = intval($item_info['item_subclass']);
        $item_inventory_type = intval($item_info['item_inventory_type']);
        $item_icon = $item_info['item_icon'];
        $item_allowable_classes = $item_info['item_allowable_classes'];
        $item_allowable_races = $item_info['item_allowable_races'];
        $item_required_level = intval($item_info['item_required_level']);
        $item_level = intval($item_info['item_level']);
        $item_quality = $item_info['item_quality'];
        if ($lang == "fr") {
            $item_name = html_entity_decode($item_info['item_name_fr'], ENT_QUOTES);
        } else {
            $item_name = html_entity_decode($item_info['item_name_en'], ENT_QUOTES);
        }

        if ($item_info['item_allowable_classes']) {
            if (strpos($item_info['item_allowable_classes'], $tabInfo['allowable_classes'])) {
                $check_class = true;
            } else {
                $check_class = false;
            }
        } else {
            $check_class = true;
        }

        if ($item_info['item_allowable_races']) {
            if (strpos($item_info['item_allowable_races'], $tabInfo['allowable_races'])) {
                $check_race = true;
            } else {
                $check_race = false;
            }
        } else {
            $check_race = true;
        }

        if ($check_class && $check_race) {
            array_push($tabListItems, array(
                "item_id" => $item_id,
                "item_name" => $item_name,
                "item_class" => $item_class,
                "item_subclass" => $item_subclass,
                "item_inventory_type" => $item_inventory_type,
                "item_icon" => $item_icon,
                "item_allowable_classes" => $item_allowable_classes,
                "item_allowable_races" => $item_allowable_races,
                "item_required_level" => $item_required_level,
                "item_level" => $item_level,
                "item_quality" => $item_quality
            ));
        }
    }
    echo returnResponse($tabListItems); // --> display on front side


} else { // no informations given
    echo returnError($display_error_empty);
    exit();
}
