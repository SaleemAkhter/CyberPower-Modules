<?php

use Illuminate\Database\Capsule\Manager as Capsule;

if (!defined("WHMCS"))
    die("This file cannot be accessed directly");

function manage_ips_vmware_appConfigArray() {
    if (file_exists(dirname(__DIR__) . "/manage_ips/classes/class.php"))
        require_once dirname(__DIR__) . "/manage_ips/classes/class.php";

    $vmWareovhapp = new VMWAREOVHCLASS();
    $emailTempArr = array(
        array(
            "type" => "general",
            'name' => 'Spam Detected',
            'subject' => 'Spam Detected',
            'message' => '<span>Dear Customer {$user_name},</span><br /><br /><p>We have spam detected on this IP {$ip_address}.</p><br />{$mail_content}<br /><br />Thank You.'
        )
    );

    foreach ($emailTempArr as $temp) {
        $vmWareovhapp->appCreateEmailTemplate($temp);
    }

    $vmWareovhapp->CreateDbTables();
    $configarray = array(
        "FriendlyName" => array("Type" => "System", "Value" => "Manage IPs"),
        "Description" => array("Type" => "System", "Value" => "This App will allow you to manage IPs with OVH and SoYou Start"),
        "Version" => array("Type" => "System", "Value" => "1.0"),
    );
    return $configarray;
}

function manage_ips_vmware_appOutput($params = NULL) {
    global $whmcs;
    $app = $whmcs->get_req_var('app');
    $appaction = $whmcs->get_req_var('appaction');
    $vmWareovhapp = new VMWAREOVHCLASS();
    $emailTempArr = array(
        array(
            "type" => "product",
            'name' => 'Spam Detected',
            'subject' => 'Spam Detected',
            'message' => '<span>Dear Customer {$user_name},</span><br /><br /><p>We have spam detected on this IP {$ip_address}.</p><br />{$mail_content}<br /><br />Thank You.'
        )
    );

    foreach ($emailTempArr as $temp) {
        $vmWareovhapp->appCreateEmailTemplate($temp);
    }
    $LANG = $params['lang'];
    $applink = $params['applink'];

    if (isset($_POST['appajax']) && $_POST['appajax'] = 'true') {
        require_once __DIR__ . "/includes/ajax.php";
        exit();
    }
    if (!empty($appaction)) {
        if (file_exists(__DIR__ . "/includes/" . $appaction . ".php"))
            require_once __DIR__ . "/includes/" . $appaction . ".php";
    }else {
        if (file_exists(__DIR__ . "/includes/home.php"))
            require_once __DIR__ . "/includes/home.php";
    }
}

function manage_ips_vmware_appClientarea($params = NULL) {
    global $whmcs;
    $app = $whmcs->get_req_var('app');
    $appaction = $whmcs->get_req_var('appaction');
    $vmWareovhapp = new VMWAREOVHCLASS();
    $LANG = $params['lang'];
    $applink = $params['applink'];
    $html = '';
    if (file_exists(__DIR__ . "/clientarea/" . $appaction . ".php"))
        require_once __DIR__ . "/clientarea/" . $appaction . ".php";
    else {
        if (file_exists(__DIR__ . "/clientarea/home.php"))
            require_once __DIR__ . "/clientarea/home.php";
    }
    $appajaxaction = $whmcs->get_req_var('appajaxaction');
    if (!empty($appajaxaction)) {
        if (file_exists(__DIR__ . "/clientarea/ajax.php"))
            require_once __DIR__ . "/clientarea/ajax.php";
        exit();
    }

    $params['output'] = $html;
    return $params;
}
