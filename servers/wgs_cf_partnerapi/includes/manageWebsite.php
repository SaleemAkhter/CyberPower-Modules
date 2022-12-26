<?php

$domain = $website;
$domainDetails = $CF->getZoneDetail();
$cloudflareNameservers = $domainDetails['result']['name_servers'];

$originalNameservers = $domainDetails['result']['original_name_servers'];
$originalregistrar = $domainDetails['result']['original_registrar'];

if ($domainDetails["result"]["paused"]) {
    $status = "Paused";
} else {
    $status = ucfirst($domainDetails["result"]["status"]);
}

$cfplan = $domainDetails["result"]["plan"]["legacy_id"];

require_once __DIR__ . '/../template/header.php';
require_once __DIR__ . '/../template/menu.php';
require_once __DIR__ . '/../template/footer.php';

$vars["systemURL"] = $systemURL;
$vars["moduleURL"] = $moduleURL;
$vars["domain"] = $domain;
$vars["status"] = $status;
$vars["cloudflarenameservers"] = $cloudflareNameservers;
$vars["originalregistrar"] = $originalregistrar;
$vars["originalnameservers"] = $originalNameservers;
$vars["headerhtml"] = $headerhtml;
$vars["menu"] = $menu;
$vars["cffooter"] = $footer;
$vars["plan"] = $cfplan;

if ($status == "Pending") {
    $templateFile = "template/home";
} else {
    require_once __DIR__ . '/../action/overview.php';
}

if (isset($_POST["cfaction"])) {
    if (file_exists(__DIR__ . '/../action/' . $_POST["cfaction"] . '.php')) {
        require_once __DIR__ . '/../action/' . $_POST["cfaction"] . '.php';
    }
}

$pagearray = array(
    'templatefile' => $templateFile,
    'breadcrumb' => '<a href="clientarea.php?action=productdetails&id=' . $params['serviceid'] . '&modop=custom&a=manage_page">' . $language['cf_manage_cf'] . '</a>',
    'vars' => $vars,
);
