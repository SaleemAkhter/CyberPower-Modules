<?php
use Illuminate\Database\Capsule\Manager as Capsule;

$menu = '<form name="cfactionform" id="cfactionform" method="post" action="clientarea.php?action=productdetails&id=' . $params["serviceid"] . '">';
$menu .= '<input type="hidden" name="modop" value="custom">';
$menu .= '<input type="hidden" name="a" value="ManageCf">';
$menu .= '<input type="hidden" name="cfaction" id="cfaction" value="">';
$menu .= '<input type="hidden" name="cf_action" id="cf_action" value="manageWebsite">';
$menu .= '<input type="hidden" name="website" id="website" value="' . $website . '">';
$menu .= '</form>';

# Tab Permissions
$tabpermissions = array();
$tabpermissionResult = Capsule::table("mod_cloudflare__reseller_features")->where('product_id', $params['pid'])->first();

$featuresArr = explode(',', $tabpermissionResult->features);
foreach ($featuresArr as $tabpermissionData) {
    $tabpermissions[trim($tabpermissionData)] = trim($tabpermissionData);
}

$menu .= '<div id="cfnavcontainer">';
$menu .= '<ul>';
if (isset($tabpermissions['overview'])) { # Check Tab Permission#overview
    $menu .= '<li><a ';
    if (isset($_POST["cfaction"]) && $_POST["cfaction"] == "overview") {
        $menu .= 'class="active"';
    }
    $menu .= ' href="javascript:void(0);" onclick="callcfaction(';
    $menu .= "'overview'";
    $menu .= ');">' . $language['cf_menu_overview'] . '</a></li>';
}
if (isset($tabpermissions['analytics'])) { # Check Tab Permission#analytics
    $menu .= '<li><a ';
    if (isset($_POST["cfaction"]) && $_POST["cfaction"] == "analytics") {
        $menu .= 'class="active"';
    }
    $menu .= ' href="javascript:void(0);" onclick="callcfaction(';
    $menu .= "'analytics'";
    $menu .= ');">' . $language['cf_menu_analytics'] . '</a></li>';
}
if (isset($tabpermissions['dns'])) { # Check Tab Permission#dns
    $menu .= '<li><a ';
    if (isset($_POST["cfaction"]) && $_POST["cfaction"] == "dns") {
        $menu .= 'class="active"';
    }
    $menu .= ' href="javascript:void(0);" onclick="callcfaction(';
    $menu .= "'dns'";
    $menu .= ');">' . $language['cf_menu_dns'] . '</a></li>';
}
if (isset($tabpermissions['crypto'])) { # Check Tab Permission#crypto
    $menu .= '<li><a ';
    if (isset($_POST["cfaction"]) && $_POST["cfaction"] == "crypto") {
        $menu .= 'class="active"';
    }
    $menu .= ' href="javascript:void(0);" onclick="callcfaction(';
    $menu .= "'crypto'";
    $menu .= ');">' . $language['cf_menu_crypto'] . '</a></li>';
}
if (isset($tabpermissions['firewall'])) { # Check Tab Permission#firewall
    $menu .= '<li><a ';
    if (isset($_POST["cfaction"]) && $_POST["cfaction"] == "firewall") {
        $menu .= 'class="active"';
    }
    $menu .= ' href="javascript:void(0);" onclick="callcfaction(';
    $menu .= "'firewall'";
    $menu .= ');">' . $language['cf_menu_firewall'] . '</a></li>';
}
if (isset($tabpermissions['speed'])) { # Check Tab Permission#speed
    $menu .= '<li><a ';
    if (isset($_POST["cfaction"]) && $_POST["cfaction"] == "speed") {
        $menu .= 'class="active"';
    }
    $menu .= ' href="javascript:void(0);" onclick="callcfaction(';
    $menu .= "'speed'";
    $menu .= ');">' . $language['cf_menu_speed'] . '</a></li>';
}
//if (isset($tabpermissions['pagerules'])) { # Check Tab Permission#Page Rules
    $menu .= '<li><a ';
    if (isset($_POST["cfaction"]) && $_POST["cfaction"] == "pagerules") {
        $menu .= 'class="active"';
    }
    $menu .= ' href="javascript:void(0);" onclick="callcfaction(';
    $menu .= "'pagerules'";
    $menu .= ');">' . $language['cf_menu_pagerules'] . '</a></li>';
//}
if (isset($tabpermissions['caching'])) { # Check Tab Permission#caching
    $menu .= '<li><a ';
    if (isset($_POST["cfaction"]) && $_POST["cfaction"] == "caching") {
        $menu .= 'class="active"';
    }
    $menu .= ' href="javascript:void(0);" onclick="callcfaction(';
    $menu .= "'caching'";
    $menu .= ');">' . $language['cf_menu_caching'] . '</a></li>';
}
//$menu .= '<li><a href="javascript:void(0);" onclick="callcfaction(';
//$menu .= "'pagerules'";
//$menu .= ');">Page Rules</a></li>';
//$menu .= '<li><a href="javascript:void(0);" onclick="callcfaction(';
//$menu .= "'traffic'";
//$menu .= ')">Traffic</a></li>';
if (isset($tabpermissions['scrapeshield'])) { # Check Tab Permission#scrapeshield
    $menu .= '<li><a ';
    if (isset($_POST["cfaction"]) && $_POST["cfaction"] == "scrapeshield") {
        $menu .= 'class="active"';
    }
    $menu .= ' href="javascript:void(0);" onclick="callcfaction(';
    $menu .= "'scrapeshield'";
    $menu .= ');">' . $language['cf_menu_scrape_shield'] . '</a></li>';
}
if (isset($tabpermissions['plan'])) { # Check Tab Permission#plan
    $menu .= '<li><a ';
    if (isset($_POST["cfaction"]) && $_POST["cfaction"] == "changeplan") {
        $menu .= 'class="active"';
    }
    $menu .= ' href="javascript:void(0);" onclick="callcfaction(';
    $menu .= "'changeplan'";
    $menu .= ');">' . $language['cf_menu_plan'] . '</a></li>';
}
$menu .= '<li><a href="javascript:void(0);" onclick="window.location=';
$menu .= "'clientarea.php?action=productdetails&id=" . $_GET["id"] . "'";
$menu .= '">' . $language['cf_menu_back'] . '</a></li>';
$menu .= '</ul>';
$menu .= '</div>';
?>
