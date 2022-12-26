<?php

use WHMCS\Database\Capsule;

if (isset($_POST["dnsaction"])) {
    switch ($_POST["dnsaction"]) {
        case "dnssec":
            $result = $CF->wgsCfEnableDnsSec($_POST['dnssec']);
            if ($result['result'] == "error") {
                $error = 'Error(' . $result["data"]["info"] . '): ';
                if (!empty($result["data"]["error"])) {
                    $error .= $result["data"]["error"] . ". ";
                }
                if (!empty($result["data"]["apierror"])) {
                    $error .= ' cfError(' . $result["data"]["cferrorcode"] . '):' . $result["data"]["apierror"];
                }
                $status = ['status' => 'error', 'msg' => $error];
            }
            if ($result["success"]) {
                $msg = $_POST['dnssec'] == 'active' ? $language['cf_dns_dnssec_enabled_status'] : $language['cf_dns_dnssec_disabled_status'];
                $status = ['status' => 'success', 'msg' => $msg, 'result' => $result["result"]];
            }
            print json_encode($status);
            exit();
            break;
        case "dnsseclist":
            $result = $CF->wgsCfGetDnsSec();
            if ($result['result'] == "error") {
                $error = 'Error(' . $result["data"]["info"] . '): ';
                if (!empty($result["data"]["error"])) {
                    $error .= $result["data"]["error"] . ". ";
                }
                if (!empty($result["data"]["apierror"])) {
                    $error .= ' cfError(' . $result["data"]["cferrorcode"] . '):' . $result["data"]["apierror"];
                }
                $status = ['status' => 'error', 'msg' => $error];
            }
            if ($result["success"]) {
                $status = ['status' => 'success', 'msg' => $msg, 'result' => $result["result"]];
            }
            print json_encode($status);
            exit();
            break;
        case "cnameflattern":
            $result = $CF->wgsCfManageCnameFlattern($_POST['cname_flattener']);
            if ($result['result'] == "error") {
                $error = 'Error(' . $result["data"]["info"] . '): ';
                if (!empty($result["data"]["error"])) {
                    $error .= $result["data"]["error"] . ". ";
                }
                if (!empty($result["data"]["apierror"])) {
                    $error .= ' cfError(' . $result["data"]["cferrorcode"] . '):' . $result["data"]["apierror"];
                }
//                $status = ['status' => 'error', 'msg' => $error];
                $vars["error"] = $error;
            }
            if ($result["success"]) {
//                $msg = $language['cf_dns_cname_flattener_success_msg'];
//                $status = ['status' => 'success', 'msg' => $msg, 'result' => $result["result"]];
                $vars["actionsucess"] = $language['cf_dns_cname_flattener_success_msg'];
            }
//            print json_encode($status);
//            exit();
            break;
        case "ipv6":
            $value = $_POST["ipv6"];
            $result = $CF->changeIPv6Setting($value);
            if ($result["success"]) {
                $vars["actionsucess"] = $language['cf_dns_ipv6_modify_success'];
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
        case "ipv4":
            $value = $_POST["ipv4"];
            $result = $CF->changePseudoIPv4($value);
            if ($result["success"]) {
                $vars["actionsucess"] = $language['cf_dns_pseudo_modify_success'];
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
        case "deleteadddnsrecords":
            $messagetype = $_POST["messagetype"];
            $message = $_POST["message"];
            if ($messagetype == "success") {
                $vars["actionsucess"] = $message;
            }
            break;
    }
}
/*
 * Fetch Data
 */
$actionResult = $CF->listDNSRecords();

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

    $vars["dnsrecords"] = $actionResult["result"];

    $actionResult2 = $CF->getAllZoneSettings();
    foreach ($actionResult2['result'] as $value) {
        $vars[$value['id']] = array("value" => $value['value'], "editable" => $value['editable']);
    }
    $ipv4settingvalues = $CF->IPv4SettingValues($language);
    $vars["ipv4settingvalues"] = $ipv4settingvalues;

    $dnsttlvalues = $CF->dnsttlvalues($language);
    $vars["dnsttlvalues"] = $dnsttlvalues;

    $getSetting = Capsule::table('mod_cloudflare__reseller_settings')->first();

    $cloudflare_username = $getSetting->email;
    $cloudflare_user_api_key = $getSetting->api_key;

    $params["cloudflare_api_url"] = $apiurl;
    $params["cloudflare_username"] = $CF->cfencrypt($cloudflare_username);
    $params["cloudflare_user_api_key"] = $CF->cfencrypt($cloudflare_user_api_key);

    $result = $CF->wgsCfGetDnsSec();

    if ($result["result"] == "error") {
        $error = 'Error(' . $result["data"]["info"] . '): ';
        if (!empty($result["data"]["error"])) {
            $error .= $result["data"]["error"] . ". ";
        }
        if (!empty($result["data"]["apierror"])) {
            $error .= ' cfError(' . $result["data"]["cferrorcode"] . '):' . $result["data"]["apierror"];
        }
        $vars["error"] = $error;
    } else {
        $vars["dnssec"] = $result['result'];
    }

    $cnameFlattern = $CF->wgsCfGetCnameFlattern();
    if ($cnameFlattern["success"] == 1)
        $vars["cname_flattern"] = $cnameFlattern['result']['value'];
    $vars["params"] = $params;
    $vars["salt"] = base64_encode($CF->salt);
    $vars["zoneid"] = $CF->cfencrypt($zoneid);

    # Fetch DNS record types
    $dnsrecordtypes = $CF->dnsrecordtypes();
    $vars["dnsrecordtypes"] = $dnsrecordtypes;
}

$templateFile = "template/dnstab/dns";
?>