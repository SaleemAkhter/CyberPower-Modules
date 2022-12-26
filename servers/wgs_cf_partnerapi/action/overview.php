<?php

use WGS\MODULES\CLOUDFLARE\wgs_cloudflare as cloudflare; 
global $whmcs;
 
//echo $zoneid = $params['customfields']['zone_id'];   

if (isset($_POST["pause"]) && isset($_POST['customajax']) && !empty($_POST['customajax'])) {
    $value = ($_POST["pause"] == 'false') ? false : true;
    $result = $CF->pauseUnpauseSite($value);
    $status = ($value == true) ? 'Paused' : 'Enabled';
    $statusArr = [];
    if ($result["success"]) {
        $statusArr = ['status' => 'success', 'msg' => 'Site has been successfully ' . $status];
    }
    if ($result['result'] == "error") {
        $error = 'Error(' . $result["data"]["info"] . '): ';
        if (!empty($result["data"]["error"])) {
            $error .= $result["data"]["error"] . ". ";
        }
        if (!empty($result["data"]["apierror"])) {
            $error .= ' cfError(' . $result["data"]["cferrorcode"] . '):' . $result["data"]["apierror"];
        }
        $statusArr = ['status' => 'error', 'msg' => $error];
    }
    print json_encode($statusArr);
    exit();
}
$actionResult = $CF->getZoneDetail();

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
        $vars[$value['id']] = $value['value'];
    }
}

$developmentmodesetting = $CF->getDevelopmentModeSettings();
if ($developmentmodesetting['result']['time_remaining'] > 0) {
    if (($developmentmodesetting['result']['time_remaining'] / 3600) > 1)
        $timeText = $language['cf_overview_hours'];
    elseif (($developmentmodesetting['result']['time_remaining'] / 3600) < 1)
        $timeText = $language['cf_overview_minutes'];
    elseif (($developmentmodesetting['result']['time_remaining'] / 3600) == 1)
        $timeText = $language['cf_overview_hour'];
    $developmentmodesetting['result']['time_remaining'] = gmdate("H:i:s", $developmentmodesetting['result']['time_remaining']) . ' ' . $timeText;
}
$vars["developmentmodesetting"] = $developmentmodesetting;
$templateFile = "template/overviewtab/overview";
?>
