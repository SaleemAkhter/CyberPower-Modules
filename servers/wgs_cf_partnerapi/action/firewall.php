<?php

if (isset($_POST["firewallaction"])) {
    switch ($_POST["firewallaction"]) {
        case "securitylevel":
            $value = $_POST["securitylevel"];
            $result = $CF->changeSecurityLevelSetting($value);
            if ($result["success"]) {
                $vars["actionsucess"] = $language['cf_firewall_security_level_updated_success'];
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
        case "challengettl":
            $value = $_POST["challengettl"];
            $result = $CF->changeChallengeTTLSetting($value);
            if ($result["success"]) {
                $vars["actionsucess"] = $language['cf_firewall_challenge_ttl_updated_success'];
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
        case "browsercheck":
            $value = $_POST["browsercheck"];
            $result = $CF->changeBrowserCheckSetting($value);
            if ($result["success"]) {
                $vars["actionsucess"] = $language['cf_firewall_browser_integrity_updated_success'];
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
        case "waf":
            $value = $_POST["waf"];
            $result = $CF->changeWebApplicationFirewallSetting($value);
            if ($result["success"]) {
                $vars["actionsucess"] = $language['cf_firewall_web_app_firewall_updated_success'];
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
        case "advanced_ddos":
            $value = $_POST["advanced_ddos"];
            $result = $CF->changeAdvancedDdosProtection($value);
            if ($result["success"]) {
                $vars["actionsucess"] = $language['cf_firewall_addvance_ddos_updated_success'];
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
        case "firewallipnotes":
            $id = $_POST["firewallipnotesid"];
            $value = $_POST["firewallipnotes"];
            $result = $CF->changeIpFirewallNotes($id, $value);
            if ($result["success"]) {
                $vars["actionsucess"] = $language['cf_firewall_ip_notes_updated_success'];
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
        case "firewallipmode":
            $id = $_POST["modeid"];
            $value = $_POST["mode"];
            $result = $CF->changeIpFirewalMode($id, $value);
            if ($result["success"]) {
                $vars["actionsucess"] = $language['cf_firewall_ip_mode_updated_success'];
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
        case "addfirewallip":
            $ip = $_POST["ip"];
            $mode = $_POST["mode"];
            $notes = $_POST["notes"];
            $result = $CF->addFirewallIp($ip, $mode, $notes);
            if ($result["success"]) {
                $vars["actionsucess"] = $language['cf_firewall_ip_added_success'];
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
        case "firewallipdelete":
            $id = $_POST["firewallipid"];
            $result = $CF->deleteFirewallIp($id);
            if ($result["success"]) {
                $vars["actionsucess"] = $language['cf_firewall_ip_delete_success'];
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
 * 
 * Fetch Data
 * 
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
    $securitylevelvalues = $CF->securitylevelvalues($language);
    $vars["securitylevelvalues"] = $securitylevelvalues;

    $challengettlvalues = $CF->challengettlvalues($language);
    $vars["challengettlvalues"] = $challengettlvalues;

    $firewallipslist = $CF->getIpFirewallList();

    $vars["firewallipslist"] = $firewallipslist;
}

$templateFile = 'template/firewalltab/firewall';
?>