<?php

use Illuminate\Database\Capsule\Manager as Capsule;

$vCenterServers = Capsule::table('mod_vmware_server')->get();

$selected = '';

global $whmcs;

require_once __DIR__ . '/../../../servers/vmware/vmwarephp/Bootstrap.php';

require_once __DIR__ . '/../../../servers/vmware/vmclass.php';
$sid = null;
if ("" != count($vCenterServers)) {

    $selected = 'selected';

    $getip = explode('://', $vCenterServers[0]->vsphereip);

    if (!empty($getip[1])) {

        $decryptPw = $vmWare->vmwarePwEncryptDcrypt($vCenterServers[0]->vspherepassword);

        $vms = new vmware($getip[1], $vCenterServers[0]->vsphereusername, html_entity_decode($decryptPw['password']));
    }

    $sid = $vCenterServers[0]->id;
} 

if (isset($_POST['vcenterserver']) && !empty($_POST['vcenterserver'])) {

    $vCenterServer = Capsule::table('mod_vmware_server')->where('id', $whmcs->get_req_var('vcenterserver'))->first();

    $getip = explode('://', $vCenterServer->vsphereip);

    if (!empty($getip[1])) {

        $decryptPw = $vmWare->vmwarePwEncryptDcrypt($vCenterServer->vspherepassword);

        $vms = new vmware($getip[1], $vCenterServer->vsphereusername, html_entity_decode($decryptPw['password']));
    }

    $sid = $whmcs->get_req_var('vcenterserver');
}

if (!empty($whmcs->get_req_var('hostid'))) {

?>

    <script>
        var postHost = "<?php echo $whmcs->get_req_var('hostid'); ?>";
    </script>

<?php

} else {

?>

    <script>
        var postHost = "";
    </script>

<?php

}



$getAllVms = [];

if (isset($_POST['getVms']) && !empty($_POST['getVms']))

    $getAllVms = $vms->get_all_existing_vms_for_traffic();

if (!empty($sid)) {

?>

    <script>
        var serverid = '<?php echo $sid; ?>';

        var modulelink = '<?php echo $modulelink; ?>';
    </script>

    <script>
        getAllHosts();
    </script>

<?php

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

                        <h3><?php echo $LANG['trafficusage']; ?></h3>

                        <p>

                            <?php echo $LANG['trafficusagedesc']; ?>&nbsp;

                        </p>

                    </div>

                    <div class="usages-form">

                        <form action="" method="post" id="usageform">

                            <input type="hidden" name="hostname" id="usagehostname">

                            <div class="form_box">

                                <select class="form-control" name="vcenterserver" required="required" onchange="getAllHosts();">

                                    <option value=""><?php echo $LANG['selectserver']; ?></option>

                                    <?php

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

                                <select class="form-control" name="timerange">

                                    <option value=""><?php echo $LANG['timerange']; ?></option>

                                    <option value="300" <?php echo ($_POST['timerange']) == 300 ? 'selected' : ''; ?>><?php echo $LANG['timerange_oneday']; ?></option>

                                    <option value="1800" <?php echo ($_POST['timerange']) == 1800 ? 'selected' : ''; ?>><?php echo $LANG['timerange_oneweek']; ?></option>

                                    <option value="7200" <?php echo ($_POST['timerange']) == 7200 ? 'selected' : ''; ?>><?php echo $LANG['timerange_onemonth']; ?></option>

                                    <option value="86400" <?php echo ($_POST['timerange']) == 86400 ? 'selected' : ''; ?>><?php echo $LANG['timerange_oneyear']; ?></option>

                                </select>

                            </div>

                            <div class="form_box">

                                <select class="form-control" name="hostid" required="required" onchange="jQuery('#usagehostname').val(jQuery('option:selected', this).text());">

                                </select>

                            </div>

                            <div class="form_box">

                                <input type="submit" name="getVms" value="<?php echo $LANG['getvm']; ?>">

                            </div>

                        </form>

                    </div>

                    <div class="tablebg">

                        <table id="vmslist" class="datatable partnerdetail_table table table-striped table-bordered" cellspacing="0" width="100%" style="word-wrap:break-word; table-layout: fixed;">



                            <thead>

                                <tr>

                                    <th width="20%"><?php echo $LANG['clientname']; ?></th>

                                    <th width="30%"><?php echo $LANG['vmname']; ?></th>

                                    <th width="20%"><?php echo $LANG['hostname']; ?></th>

                                    <th width="15%"><?php echo $LANG['servicestatus']; ?></th>

                                    <th width="15%"><?php echo $LANG['serverstatus']; ?></th>

                                    <th width="15%"><?php echo $LANG['bandwidth']; ?></th>

                                </tr>

                            </thead>

                            <tbody>

                                <?php

                                foreach ($getAllVms as $key => $vm) {

                                    $vmName = $vm->name;

                                    $vmId = $vm->reference->_;

                                    $hostObj = $vm->runtime->host->reference->_;

                                    $pwrStatus = $vm->runtime->powerState;

                                    $clientDetail = Capsule::table('tblcustomfieldsvalues')->join('tblhosting', 'tblhosting.id', '=', 'tblcustomfieldsvalues.relid')->join('tblclients', 'tblclients.id', '=', 'tblhosting.userid')->where('tblcustomfieldsvalues.value', '=', $vmName)->first();

                                    if ($hostObj == $whmcs->get_req_var('hostid')) {

                                ?>

                                        <tr>

                                            <td><?php echo (!empty($clientDetail->firstname)) ? '<a href="clientssummary.php?userid=' . $clientDetail->userid . '" target="_blank">' . $clientDetail->firstname . ' ' . $clientDetail->lastname . '<a>' : '---'; ?></td>

                                            <td><?php echo (!empty($clientDetail->firstname)) ? '<a href="clientsservices.php?userid=' . $clientDetail->userid . '&id=' . $clientDetail->relid . '" target="_blank">' . $vmName . '<a>' : $vmName; ?></td>

                                            <!--td id="host_<?php echo $key; ?>"><script>getHost("host_<?php echo $key; ?>","<?php echo $hostObj; ?>")</script></td-->

                                            <td><?php echo $whmcs->get_req_var('hostname'); ?></td>

                                            <td><?php echo !empty($clientDetail->domainstatus) ? $clientDetail->domainstatus : '---'; ?></td>

                                            <td><?php echo $pwrStatus; ?></td>

                                            <td id="bw_<?php echo $key; ?>">
                                                <script>
                                                    getBw("bw_<?php echo $key; ?>", "<?php echo $vmId; ?>", "<?php echo $vmName; ?>", "<?php echo $sid; ?>", "<?php echo $clientDetail->relid; ?>")
                                                </script>
                                            </td>

                                        </tr>

                                <?php

                                    }
                                }

                                ?>

                            </tbody>

                        </table>

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

<link href="https://cdn.datatables.net/1.10.12/css/dataTables.bootstrap.min.css" type="text/css" />

<script src="https://cdn.datatables.net/1.10.12/js/jquery.dataTables.min.js"></script>

<script src="https://cdn.datatables.net/1.10.12/js/dataTables.bootstrap.min.js"></script>

<script>
    jQuery(document).ready(function() {

        jQuery('#vmslist').DataTable({
            "pageLength": 50
        });

    });
</script>