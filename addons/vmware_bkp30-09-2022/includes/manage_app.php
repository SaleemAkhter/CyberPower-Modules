<?php

use Illuminate\Database\Capsule\Manager as Capsule;

$appPath = (dirname(__DIR__) . "/apps/" . $app . "/" . $app . ".php");
if (!file_exists($appPath)) {
    echo '<div class="app-errorbox">Invalid APP name. Please try again.</div>';
} elseif (file_exists($appPath)) {
    require_once $appPath;

    $class = 'active';
    if (!empty($_GET['config']) && $_GET['config'] == 'true')
        $class = '';
    $getAdminLang = Capsule::table('tbladmins')->where('id', $_SESSION['adminid'])->first();
    $language = 'english';
    if (!empty($getAdminLang->language))
        $language = $getAdminLang->language;

    $appLink = $modulelink . '&action=apps&manage=true&app=' . $app . '&appoutput=true';
    $configarray = call_user_func($app . "_vmware_appConfigArray", '');
    $result = Capsule::table('mod_vmware_apps')->where('app_name', $app)->get();
    $appVars = [
        'name' => $configarray['FriendlyName']['Value'],
        'description' => $configarray['Description']['Value'],
        'version' => $configarray['Version']['Value'],
        'applink' => $appLink,
    ];
    foreach ($result as $data) {
        $data = (array) $data;
        $setting = $data['setting'];
        $value = $data['value'];
        $appVars[$setting] = decrypt($value);
    }
    $_APPLANG = [];
    $languagePath = (dirname(__DIR__) . "/apps/" . $app . "/lang/" . $language . ".php");
    if (file_exists($languagePath)) {
        require $languagePath;
    }
    $appVars['lang'] = $_APPLANG;

    if ($_POST['appajax'] == 'true') {
        if (function_exists($app . "_vmware_appOutput"))
            call_user_func($app . "_vmware_appOutput", $appVars);
    }
    ?>
    <div align="right">
        <a href="<?php echo $modulelink; ?>&action=apps"><b><?php echo $LANG['back']; ?></b></a>
    </div>
    <div class="app_links">
        <ul>
            <li><a href="<?php echo $modulelink; ?>&action=apps&manage=true&app=<?php echo $app; ?>&config=true" class="<?php echo ($_GET['config'] == true) ? 'active' : ''; ?>">Config</a></li>
            <li><a href="<?php echo $modulelink; ?>&action=apps&manage=true&app=<?php echo $app; ?>&appoutput=true" class="<?php echo $class; ?>">Output</a></li>
        </ul>
    </div>
    <?php
    if (isset($_GET['config']) && $_GET['config'] == true) {
        $appConfigData = wgsVmware_getAppConfigOptions($app);
        $configarray = call_user_func($app . "_vmware_appConfigArray", $params);
        echo "<div class=\"vmware_heading\"> <h3>" . $configarray['FriendlyName']['Value'] . "</h3> <p> " . $LANG['apps_heading_desc'] . " </p> </div>";
        echo "<form method=\"post\" action=\"" . $modulelink . "&action=apps&manage=true&app=" . $app . "&command=save\">";
        echo "<table class=\"form\" width=\"100%\" border=\"0\" cellspacing=\"2\" cellpadding=\"3\">";
        foreach ($configarray as $key => $values) {
            if ($values['Type'] != "System") {
                if (!$values['FriendlyName']) {
                    $values['FriendlyName'] = $key;
                }
                $values['Name'] = $key;
                $values['Value'] = htmlspecialchars($appConfigData[$key]);

                echo "<tr><td class=\"fieldlabel\" width=\"10%\">" . $values['FriendlyName'] . "</td><td class=\"fieldarea\">" . wgsVmware_appConfigFieldOutput($values) . "</td></tr>";
                continue;
            }
        }
        echo "</table>";
        echo "<div align=\"center\" class=\"form_btn\"><input type=\"submit\" name=\"save\" value=\"";
        echo $LANG['savechanges'];
        echo "\" class=\"btn primary\" /></div>";
        echo "</form>";
    } else {

        if (function_exists($app . "_vmware_appOutput")) {
            call_user_func($app . "_vmware_appOutput", $appVars);
        } else {
            echo "<p>" . $LANG['nooutput'] . "</p>";
        }
    }
}
?>