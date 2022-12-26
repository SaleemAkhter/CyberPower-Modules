<?php

if (isset($_POST["speedaction"])) {
    switch ($_POST["speedaction"]) {
        case "autominify":
            $css = "off";
            $html = "off";
            $js = "off";
            if (isset($_POST["autominifyjs"])) {
                $js = $_POST["autominifyjs"];
            }
            if (isset($_POST["autominifycss"])) {
                $css = $_POST["autominifycss"];
            }
            if (isset($_POST["autominifyhtml"])) {
                $html = $_POST["autominifyhtml"];
            }

            $result = $CF->changeMinifySetting($css, $html, $js);
            if ($result["success"]) {
                $vars["actionsucess"] = $language['cf_speed_auto_minify_success'];
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
        case "rocketloader":
            $value = $_POST["rocketloader"];
            $result = $CF->changeRocketLoaderSetting($value);
            if ($result["success"]) {
                $vars["actionsucess"] = $language['cf_speed_rocket_loader_success'];
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
        case "mobileredirect":
            $mode = $_POST["mobileredirectmode"];
            $subdomain = $_POST["mobileredirectsubdomain"];
            $stripuri = $_POST["mobileredirectstripuri"];
            if ($stripuri == "true") {
                $stripuri = true;
            } elseif ($stripuri == "false") {
                $stripuri = false;
            }
            $result = $CF->changeMobileRedirectSetting($mode, $subdomain, $stripuri);
            if ($result["success"]) {
                $vars["actionsucess"] = $language['cf_speed_mobile_redirect_success'];
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
        case "ipgeolocation":
            $value = $_POST["ipgeolocation"];
            $result = $CF->changeIPGeolocationSetting($value);
            if ($result["success"]) {
                $vars["actionsucess"] = $language['cf_speed_ip_geo_success'];
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
        case "polish":
            $value = $_POST["polish"];
            $result = $CF->changePolishSetting($value);
            if ($result["success"]) {
                $vars["actionsucess"] = $language['cf_speed_polish_setting_success'];
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
        case "mirage":
            $value = $_POST["mirage"];
            $result = $CF->changeMirageSetting($value);
            if ($result["success"]) {
                $vars["actionsucess"] = $language['cf_speed_migrate_setting_success'];
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
        case "maxupload":
            $value = $_POST["maxupload"];
            $result = $CF->changeMaxUploadSize($value);
            if ($result["success"]) {
                $vars["actionsucess"] = $language['cf_speed_maxupload_setting_success'];
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
    $uploadsizes = $CF->uploadFileSizes();
    $vars["uploadsizes"] = $uploadsizes;
}

$templateFile = "template/speedtab/speed";
?>