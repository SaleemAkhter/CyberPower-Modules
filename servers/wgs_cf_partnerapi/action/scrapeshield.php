<?php

if (isset($_POST["scrapeshieldaction"])) {
    switch ($_POST["scrapeshieldaction"]) {
        case "emailaddressobfuscation":
            $value = $_POST["emailaddressobfuscation"];
            $result = $CF->changeEmailObfuscationSetting($value);
            if ($result["success"]) {
                $vars["actionsucess"] = $language['cf_scrape_shield_email_add_obf_changed_success'];
            }
            if ($result['result'] == "error") {
                $error = 'Error(' . $result["data"]["info"] . '): ';
                if (!empty($result["data"]["error"])) {
                    $error .= $result["data"]["error"] . ". ";
                }
                if (!empty($result["data"]["apierror"])) {
                    $error .= ' cfError(' . $result["data"]["cferrorcode"] . '):' . $result["data"]["apierror"];
                }
                $vars["error"] = $error;
            }
            break;
        case "serversideexclude":
            $value = $_POST["serversideexclude"];
            $result = $CF->changeServerSideExclude($value);
            if ($result["success"]) {
                $vars["actionsucess"] = $language['cf_scrape_shield_server_side_mode_success'];
            }
            if ($result['result'] == "error") {
                $error = 'Error(' . $result["data"]["info"] . '): ';
                if (!empty($result["data"]["error"])) {
                    $error .= $result["data"]["error"] . ". ";
                }
                if (!empty($result["data"]["apierror"])) {
                    $error .= ' cfError(' . $result["data"]["cferrorcode"] . '):' . $result["data"]["apierror"];
                }
                $vars["error"] = $error;
            }
            break;
        case "hotlinkprotection":
            $value = $_POST["hotlinkprotection"];
            $result = $CF->changeHotlinkProtectionSetting($value);
            if ($result["success"]) {
                $vars["actionsucess"] = $language['cf_scrape_shield_hostlink_protect_success'];
            }
            if ($result['result'] == "error") {
                $error = 'Error(' . $result["data"]["info"] . '): ';
                if (!empty($result["data"]["error"])) {
                    $error .= $result["data"]["error"] . ". ";
                }
                if (!empty($result["data"]["apierror"])) {
                    $error .= ' cfError(' . $result["data"]["cferrorcode"] . '):' . $result["data"]["apierror"];
                }
                $vars["error"] = $error;
            }
            break;
    }
}
/*
 * Fetch Data
 */

$actionResult = $CF->getAllZoneSettings();

if ($actionResult["result"] == "error") {
    $error = 'Error(' . $actionResult["data"]["info"] . '): ';
    if (!empty($actionResult["data"]["error"])) {
        $error .= $actionResult["data"]["error"] . ". ";
    }
    if (!empty($actionResult["data"]["apierror"])) {
        $error .= ' cfError(' . $actionResult["data"]["cferrorcode"] . '):' . $actionResult["data"]["apierror"];
    }
    $vars["error"] = $error;
}

if ($actionResult['success']) {
    $vars["success"] = 1;
    foreach ($actionResult['result'] as $value) {
        $vars[$value['id']] = array("value" => $value['value'], "editable" => $value['editable']);
    }
}

$templateFile = "template/scrapeshieldtab/scrapeshield";
?>
