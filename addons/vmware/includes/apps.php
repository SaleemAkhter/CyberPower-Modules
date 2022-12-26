<?php



use Illuminate\Database\Capsule\Manager as Capsule;



if (file_exists(dirname(__DIR__) . '/classes/appfunctions.php'))

    include_once dirname(__DIR__) . '/classes/appfunctions.php';



$action = $whmcs->get_req_var('command');

$manage = $whmcs->get_req_var('manage');



if ($action == 'activate') {

    check_token("WHMCS.admin.default");

    $app = $whmcs->get_req_var('app');

    if ($app) {

        Capsule::table('mod_vmware_apps')->where('app_name', $app)->delete();

        Capsule::table('mod_vmware_apps')->insert(['app_name' => $app, 'setting' => '', 'value' => '']);
    }

    redir("module=vmware&action=apps&activated=true#" . $app);

    exit();
}

if ($action == 'deactivate') {

    check_token("WHMCS.admin.default");

    $app = $whmcs->get_req_var('app');

    if ($app) {

        Capsule::table('mod_vmware_apps')->where('app_name', $app)->delete();
    }

    redir("module=vmware&action=apps&deactivated=true#" . $app);

    exit();
}



if ($action == 'save') {

    check_token("WHMCS.admin.default");

    $app = $whmcs->get_req_var('app');

    unset($_POST['token']);

    unset($_POST['save']);



    if ($app && !empty($_POST)) {

        Capsule::table('mod_vmware_apps')->where('app_name', $app)->delete();

        foreach ($_POST as $key => $value) {

            Capsule::table('mod_vmware_apps')->insert(['app_name' => $app, 'setting' => $key, 'value' => encrypt(html_entity_decode(trim($value)))]);
        }
    }

    redir("module=vmware&action=apps&app=" . $app . "&config=true&savechanges=true");

    exit();
}

if ($manage == 'true' && $_POST['appajax'] == 'true') {

    $app = $whmcs->get_req_var('app');

    require_once __DIR__ . '/manage_app.php';
}

$appsArray = array();

$directory = opendir(dirname(__DIR__) . "/apps/");

while (false !== $file = readdir($directory)) {

    $fileExt = explode(".", $file, 2);

    if ((trim($file) && $file != "index.php")) {

        if (is_file(dirname(__DIR__) . "/apps/" . $file . "/" . $file . ".php")) {

            $appsArray[] = $file;
        }
    }
}

closedir($directory);

sort($appsArray);

?>

<div id="wrapper">

    <div class="addon_container">

        <div class="ad_content_area">

            <?php

            if (file_exists(dirname(__DIR__) . '/includes/header.php'))

                require_once dirname(__DIR__) . '/includes/header.php';

            ?>

            <div class="addon_inner">

                <div class="dashoboard-container">



                    <?php

                    if ($manage == 'true') {

                        $app = $whmcs->get_req_var('app');

                        require_once __DIR__ . '/manage_app.php';
                    } else {

                        if (isset($_GET['activated']) && $_GET['activated'] == 'true') {

                            echo '<div class="successbox"><strong><span class="title">Changes Saved Successfully!</span></strong><br>Your app have been successfully activated.</div>';
                        }
                        if (isset($_GET['savechanges']) && $_GET['savechanges'] == 'true') {

                            echo '<div class="successbox"><strong><span class="title">Changes Saved Successfully!</span></strong><br>Your changes have been successfully saved.</div>';
                        } elseif (isset($_GET['deactivated']) && $_GET['deactivated'] == 'true') {

                            echo '<div class="successbox"><strong><span class="title">Changes Saved Successfully!</span></strong><br>Your app have been successfully deactivated.</div>';
                        }

                    ?>

                        <div class="vmware_heading">

                            <h3><?php echo $LANG['manage_apps']; ?></h3>

                            <p>

                                <?php echo $LANG['appsdesc']; ?>&nbsp;

                            </p>

                        </div>

                        <div class="tablebg">

                            <div class="apps_cont">

                                <div class="row">

                                    <?php

                                    $appLogoUrl = '';

                                    foreach ($appsArray as $app) {

                                        if (file_exists("../modules/addons/vmware/apps/" . $app . "/logo.gif")) {

                                            $appLogoUrl = "../modules/addons/vmware/apps/" . $app . "/logo.gif";
                                        } else {

                                            if (file_exists("../modules/addons/vmware/apps/" . $app . "/logo.jpg")) {

                                                $appLogoUrl = "../modules/addons/vmware/apps/" . $app . "/logo.jpg";
                                            } else {

                                                if (file_exists("../modules/addons/vmware/apps/" . $app . "/logo.png")) {

                                                    $appLogoUrl = "../modules/addons/vmware/apps/" . $app . "/logo.png";
                                                } else {

                                                    $appLogoUrl = "../modules/addons/vmware/images/spacer.gif";
                                                }
                                            }
                                        }

                                        $appActive = false;

                                        $appActiveButton = $appManageButton = $appDeactiveButton = '';

                                        $appConfigData = wgsVmware_getAppConfigOptions($app);

                                        if (count($appConfigData) > 0 && !empty($appConfigData)) {

                                            $appActive = true;

                                            $appDeactiveButton = "<input type=\"button\" value=\"" . $LANG['deactivate'] . "\" onclick=\"deactivateApp('" . $app . "','" . generate_token("link") . "');return false\" class=\"btn-danger\" />&nbsp;";

                                            $appManageButton = "<input type=\"button\" value=\"" . $LANG['manage'] . "\" class=\"btn\" onclick=\"window.location='" . $modulelink . "&action=apps&manage=true&app=" . $app . "'\" />";
                                        } else {

                                            $appActiveButton = "<input type=\"button\" value=\"" . $LANG['activate'] . "\" onclick=\"window.location='" . $modulelink . "&action=apps&command=activate&app=" . $app . generate_token("link") . "'\" class=\"btn-success\" />";
                                        }

                                        $appPath = (dirname(__DIR__) . "/apps/" . $app . "/" . $app . ".php");



                                        if (file_exists($appPath)) {

                                            require_once $appPath;
                                        }

                                        $configarray = call_user_func($app . "_vmware_appConfigArray", $params);

                                    ?>

                                        <div class="col-sm-4">

                                            <div class="app_box">

                                                <div class="row">

                                                    <div class="col-sm-4">

                                                        <img src="<?php echo $appLogoUrl; ?>" />

                                                    </div>

                                                    <div class="col-sm-8">

                                                        <h3><?php echo $configarray['FriendlyName']['Value']; ?> <span>(V<?php echo $configarray['Version']['Value']; ?>)</span></h3>

                                                        <p><?php echo $configarray['Description']['Value']; ?></p>

                                                        <div class="app_footer">

                                                            <?php

                                                            if ($appActiveButton)

                                                                echo $appActiveButton;

                                                            if ($appDeactiveButton)

                                                                echo $appDeactiveButton;

                                                            if ($appManageButton)

                                                                echo $appManageButton;

                                                            ?>

                                                        </div>

                                                    </div>

                                                </div>

                                            </div>

                                        </div>

                                    <?php

                                    }

                                    ?>

                                </div>

                            </div>

                        </div>

                    <?php

                    }

                    if (file_exists(dirname(__DIR__) . '/includes/footer.php'))

                        require_once dirname(__DIR__) . '/includes/footer.php';

                    ?>

                </div>

            </div>

        </div>

    </div>

</div>