<?php



use Illuminate\Database\Capsule\Manager as Capsule;



$serverData = Capsule::table('mod_vmware_server')->get();

if (isset($_POST['update']) && !empty($_POST['update'])) {

    switch ($_POST['update']) {

        case 'update_datacenters':

            foreach (Capsule::table('tblproducts')->where('servertype', 'vmware')->get() as $productResult) {

                $productResult = (array) $productResult;



                $pid = $productResult['id'];

                $params = $productResult;



                try {

                    # get all cutsomfield value

                    $customFieldVal = vmwareGetCustomFiledVal($params, 'hook');
                    $serverName = $productResult['configoption3'];
                    $serverData = Capsule::table('mod_vmware_server')->where('server_name', $serverName)->get();
                    if (count($serverData) == 0)
                        $serverData = Capsule::table('mod_vmware_server')->where('id', $serverName)->get();
                    $serverid = $serverData[0]->id;



                    require_once __DIR__ . '/../../../servers/vmware/vmwarephp/Bootstrap.php';



                    require_once __DIR__ . '/../../../servers/vmware/vmclass.php';

                    $getip = explode('://', $serverData[0]->vsphereip);

                    if (!empty($getip[1])) {

                        $decryptPw = $vmWare->vmwarePwEncryptDcrypt($serverData[0]->vspherepassword);

                        $vms = new vmware($getip[1], $serverData[0]->vsphereusername, html_entity_decode($decryptPw['password']));



                        $datacenter = $vms->list_datacenters();



                        $dCenters = $WgsVmwareObj->vmware_object_to_array($datacenter[0]);



                        if (isset($dCenters['RetrievePropertiesResponse']['returnval'])) {

                            $dCentersName = '';

                            foreach ($dCenters['RetrievePropertiesResponse']['returnval'] as $key => $dCenterValue) {

                                if ($key == 'obj' && $key != '0') {

                                    $dCentersName .= $dCenters['RetrievePropertiesResponse']['returnval']['propSet'][0]['val'] . ',';
                                } else if ($key == 'propSet' && $key != '0') {
                                } else {

                                    $dCentersName .= $dCenterValue['propSet'][0]['val'] . ',';
                                }
                            }

                            $dCentersName = rtrim($dCentersName, ',');



                            $updatedDatacenterName = Capsule::table('tblcustomfields')

                                ->where('relid', $pid)

                                ->where('type', 'product')

                                ->where('fieldname', 'like', '%' . $customFieldVal['datacenter_field'] . '%')

                                ->update(

                                    [

                                        'fieldoptions' => $dCentersName,

                                    ]

                                );

                            $msg = '<div class="successbox"><strong><span class="title">Changes Saved Successfully!</span></strong><br>Your datacenters have been successfully updated.</div>';
                        }
                    }
                } catch (Exception $ex) {

                    $msg = '<div class="errorbox"><strong><span class="title">Error:</span></strong><br>' . $ex->getMessage() . '!</div>';
                }
            }

            break;
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

                        <h3><?php echo $LANG['setting']; ?></h3>

                        <p>

                            <?php echo $LANG['update_datacenter_text']; ?>&nbsp;

                        </p>

                    </div>

                    <?php

                    if (isset($msg)) {

                        echo $msg;
                    }

                    ?>

                    <div class="setting-button">

                        <!--<p><?php echo $LANG['update_datacenter_text']; ?></p>-->

                        <form action="" method="post">

                            <!--                            <select name="vmserver" id="vmserver" class="form-control select-inline" required="" onchange="getDatacenter(this.value);return false;">

                            <?php //foreach ($serverData as $serverData01) {
                            ?>

                            <?php // echo $serverData01->server_name;
                            ?></option>

                            <?php //}

                            ?>

                            </select>-->

                            <input type="hidden" value="update_datacenters" name="update">

                            <div class="form_btn"><input type="submit" name="submit" value="<?php echo $LANG['update_datacenter']; ?>"></div>

                        </form>

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