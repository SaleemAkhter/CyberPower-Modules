<script>
    var modulelink = '<?php echo $modulelink; ?>';
</script>
<?php

use Illuminate\Database\Capsule\Manager as Capsule;

$vCenterServers = Capsule::table('mod_vmware_server')->get();

$selected = '';

global $whmcs;

require_once __DIR__ . '/../../../servers/vmware/vmwarephp/Bootstrap.php';

require_once __DIR__ . '/../../../servers/vmware/vmclass.php';

if (count($vCenterServers) == 1) {

    $selected = 'selected';

    $getip = explode('://', $vCenterServers[0]->vsphereip);

    if (!empty($getip[1])) {

        $decryptPw = $vmWare->vmwarePwEncryptDcrypt($vCenterServers[0]->vspherepassword);

        $vms = new vmware($getip[1], $vCenterServers[0]->vsphereusername, html_entity_decode($decryptPw['password']));
    }

    $sid = $vCenterServers[0]->id;
} elseif (count($vCenterServers) > 1) {

    if (isset($_POST['vcenterserver']) && !empty($_POST['vcenterserver'])) {

        $vCenterServer = Capsule::table('mod_vmware_server')->where('id', $whmcs->get_req_var('vcenterserver'))->first();

        $getip = explode('://', $vCenterServer->vsphereip);

        if (!empty($getip[1])) {

            $decryptPw = $vmWare->vmwarePwEncryptDcrypt($vCenterServer->vspherepassword);

            $vms = new vmware($getip[1], $vCenterServer->vsphereusername, html_entity_decode($decryptPw['password']));
        }

        $sid = $whmcs->get_req_var('vcenterserver');
    }
}

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

                    <div class="vmware_heading">

                        <h3><?php echo $LANG['provisioning_heade_setting']; ?></h3>

                        <p>
                            <?php echo $LANG['provisioning_heade__text']; ?>&nbsp;
                        </p>

                    </div>

                    <?php

                    if (isset($msg)) {

                        echo $msg;
                    }

                    ?>

                    <div class="sync-setting-form">

                        <form id="syncSettingServer">

                            <div class="form_box">

                                <select class="form-control" name="vcenterserver">

                                    <option value=""><?php echo $LANG['selectserver']; ?></option>

                                    <?php
                                    $selected = '';
                                    foreach ($vCenterServers as $vCenterServer) {

                                        if ($vCenterServer->id == $_POST['vcenterserver'] && !empty($_POST['vcenterserver'])) {
                                            $selected = 'selected';
                                        }

                                        echo '<option value="' . $vCenterServer->id . '" ' . $selected . '>' . $vCenterServer->server_name . '</option>';
                                    }

                                    ?>

                                </select>

                            </div>

                            <div class="form_box">
                                <input type="button" onclick="syncSetting(this);" value="<?php echo $LANG['syncSetting']; ?>">
                                <span id="syncloader" style="display: none;"><i class="fa fa-spinner fa-pulse fa-3x fa-fw"></i></span>
                            </div>

                        </form>

                    </div>
                    <div class="syncsection">
                        <div id="settingHtml"></div>
                    </div>

                    <?php

                    if (file_exists(dirname(__DIR__) . '/includes/footer.php'))

                        require_once dirname(__DIR__) . '/includes/footer.php';

                    ?>

                </div>

            </div>

        </div>

    </div>

</div>