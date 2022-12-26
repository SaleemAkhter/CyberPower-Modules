<?php

/*
 * To Save Changes for Caching *
 */
if (isset($_POST["cachingaction"])) {
    switch ($_POST["cachingaction"]) {
        case "cachelevel":
            $cachelevel = $_POST["cachelevel"];
            $result = $CF->changeCacheLevelSetting($cachelevel);
            if ($result["success"]) {
                $vars["actionsucess"] = $language['cf_caching_cache_level_update_success'];
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
        case "browsercachettl":
            $browsercachettl = $_POST["browsercachettl"];
            $result = $CF->changeBrowserCacheTTLSetting($browsercachettl);
            if ($result["success"]) {
                $vars["actionsucess"] = $language['cf_caching_browser_cache_update_success'];
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
        case "alwaysonline":
            $alwaysonline = $_POST["alwaysonline"];
            $result = $CF->changeAlwaysOnlineSetting($alwaysonline);
            if ($result["success"]) {
                $vars["actionsucess"] = $language['cf_caching_always_online_update_success'];
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
        case "developmentmode":
            $developmentmode = $_POST["developmentmode"];
            $result = $CF->changeDevelopmentModeSetting($developmentmode);
            if ($result["success"]) {
                $vars["actionsucess"] = $language['cf_caching_dev_mode_update_success'];
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
        case "purgesingle":
            $singleurls = explode(",", trim($_POST["singleurls"]));
            if (empty($_POST["singleurls"])) {
                $vars["error"] = $language['cf_caching_not_enter_single_file_url'];
            } else if (count($singleurls) > 30) {
                $vars["error"] = $language['cf_caching_purge_30_files'];
            } else {
                $result = $CF->purgeIndividualFiles($singleurls);
                if ($result["success"]) {
                    $vars["actionsucess"] = $language['cf_caching_purge_success'];
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
            }
            break;
        case "purgeall":
            $result = $CF->purgeAllFiles();
            if ($result["success"]) {
                $vars["actionsucess"] = $language['cf_caching_website_purge_success'];
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
    $TTLSettingValues = $CF->browserCacheTTLSettingValues();
    $vars["TTLSettingValues"] = $TTLSettingValues;
}

$templateFile = "template/cachingtab/caching";
?>