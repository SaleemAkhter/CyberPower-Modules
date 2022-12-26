<?php

if (isset($_POST["cryptoaction"])) {
    switch ($_POST["cryptoaction"]) {
        case "ssl":
            $value = $_POST["ssl"];
            $result = $CF->changeSSLSetting($value);
            if ($result["success"]) {
                $vars["actionsucess"] = $language['cf_crypto_ssl_mode_success'];
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
        case "securityheader":
            $enabled = false;
            if (isset($_POST["securityheaderenabled"])) {
                $enabled = true;
            }
            $maxage = $_POST["securityheadermaxage"];
            $includesubdomain = $_POST["securityheadersubdomains"];
            $preloadhsts = $_POST["securityheaderpreload"];
            if ($includesubdomain == "true") {
                $includesubdomains = true;
            } elseif ($includesubdomain == "false") {
                $includesubdomains = false;
            }

            if ($preloadhsts == "true") {
                $preload = true;
            } elseif ($preloadhsts == "false") {
                $preload = false;
            }

            $result = $CF->changeSecurityHeaderSetting($enabled, $maxage, $includesubdomains, $preload);
            if ($result["success"]) {
                $vars["actionsucess"] = $language['cf_crypto_hsts_enable_success'];
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
        case "tlsclientauth":
            $value = $_POST["tlsclientauth"];
            $result = $CF->changeTLSclientAuthSetting($value);
            if ($result["success"]) {
                $vars["actionsucess"] = $language['cf_crypto_tls_auth_success'];
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
        case "alwaysusehttps":
            $value = $_POST["alwaysusehttps"];
            $result = $CF->changeAlwaysUseTttpsSetting($value);
            if ($result["success"]) {
                $vars["actionsucess"] = $language['cf_always_https_success'];
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
////disabled
//echo '<pre>';
////$actionResult = $wgs_cf_partnerapi->wgsCfEnableDnsSec('disabled');
//$actionResult1 = $wgs_cf_partnerapi->wgsCfGetDnsSec();
////print_r($actionResult);
//print_r($actionResult1);
//echo '</pre>';
//die();
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
        $vars[$value['id']] = array("value" => $value['value'], "editable" => $value['editable'], "certificate_status" => $value['certificate_status']);
    }
    $sslvalues = $CF->websiteSSLvalues($language);
    $vars["sslvalues"] = $sslvalues;
    $hstsmaxagevalues = $CF->hstsmaxageheader($language);
    $vars["hstsmaxagevalues"] = $hstsmaxagevalues;
}

$templateFile = "template/cryptotab/crypto";
?>