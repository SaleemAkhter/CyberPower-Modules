<?php



use Illuminate\Database\Capsule\Manager as Capsule;



global $whmcs;

$admin = Capsule::table('tbladmins')->get();

if (!empty(filter_var($_POST['create_server'], FILTER_SANITIZE_STRING))) {

    $vmname = filter_var($_POST['vmname'], FILTER_SANITIZE_STRING);

    $checkVm = Capsule::table('mod_vmware_ip_list')->where('forvm', $vmname)->count();



    if ($checkVm == 0) {

        $serverid = filter_var($_POST['vmserver'], FILTER_SANITIZE_STRING);



        $pid = filter_var($_POST['product'], FILTER_SANITIZE_STRING);

        $productDetail = Capsule::table('tblproducts')->where('id', $pid)->get();

        $productDetail = (array) $productDetail[0];



        $customFieldVal = vmwareGetCustomFiledVal($productDetail, true);



        $vmCfId = vmGetWHMCSProductCfId($pid, $customFieldVal['vm_name_field']);

        $datacenterCfId = vmGetWHMCSProductCfId($pid, $customFieldVal['datacenter_field']);

        $OsCfId = vmGetWHMCSProductCfId($pid, $customFieldVal['os_type_field']);

        $OsVersionCfId = vmGetWHMCSProductCfId($pid, $customFieldVal['os_version_field']);

        $hostname_dcCfId = vmGetWHMCSProductCfId($pid, 'hostname_dc');

        $user = filter_var($_POST['user'], FILTER_SANITIZE_STRING);

        $command = "addorder";

        $adminuser = $admin[0]->id;

        $values["clientid"] = $user;

        $values["pid"] = $pid;

        $values["billingcycle"] = filter_var($_POST['billingcycle'], FILTER_SANITIZE_STRING);

        if (!empty(filter_var($_POST['stop_invoice'], FILTER_SANITIZE_STRING))) {

            $values["noinvoice"] = $values["noinvoiceemail"] = $values["noemail"] = true;
        }

        $datacenter = filter_var($_POST['datacenter'], FILTER_SANITIZE_STRING);

        $guest_os_version = filter_var($_POST['guest_os_version'], FILTER_SANITIZE_STRING);

        $values["customfields"] = base64_encode(serialize(array(

            $vmCfId => $vmname,

            $datacenterCfId => $datacenter,

            $OsCfId => filter_var($_POST['guest_os_family'], FILTER_SANITIZE_STRING),

            $OsVersionCfId => $guest_os_version,

            $hostname_dcCfId => filter_var($_POST['hostname'], FILTER_SANITIZE_STRING) . '&&' . $datacenter

        )));

        $bandwidth = filter_var($_POST['bandwidth'], FILTER_SANITIZE_STRING);

        if (!empty($bandwidth)) {

            $bwConfigFieldName = $customFieldVal['bandwith_field'];

            $aIpConfigFieldName = $customFieldVal['additional_ip_field'];

            $getConfigGid = Capsule::table("tblproductconfiggroups")->where('name', 'Vmware' . $pid)->get();



            $getBwConfigFieldId = Capsule::table("tblproductconfigoptions")->where('optionname', trim($bwConfigFieldName))->where('gid', $getConfigGid[0]->id)->get();

            $getBwConfigFieldId = (array) $getBwConfigFieldId[0];



            $getAIpConfigFieldId = Capsule::table("tblproductconfigoptions")->where('optionname', trim($aIpConfigFieldName))->where('gid', $getConfigGid[0]->id)->get();

            $getAIpConfigFieldId = (array) $getAIpConfigFieldId[0];

            $ip = $whmcs->get_req_var('ip');

            $values = array_merge($values, array('configoptions' => base64_encode(serialize(array(

                $getBwConfigFieldId['id'] => $bandwidth, $getAIpConfigFieldId['id'] => (count($ip) - 1)

            )))));
        }

        $values["paymentmethod"] = filter_var($_POST['paymentmethod'], FILTER_SANITIZE_STRING);



        $results = localAPI($command, $values, $adminuser);

        if ($results['result'] == 'success') {



            try {

                $serverData = Capsule::table('mod_vmware_server')->where('id', $serverid)->get();

                $path = str_replace("addons/vmware/includes", "", __DIR__);

                require_once $path . 'servers/vmware/vmwarephp/Bootstrap.php';

                require_once $path . 'servers/vmware/vmclass.php';

                $getip = explode('://', $serverData[0]->vsphereip);



                $decryptPw = $vmWare->vmwarePwEncryptDcrypt($serverData[0]->vspherepassword);

                $vms = new vmware($getip[1], $serverData[0]->vsphereusername, html_entity_decode($decryptPw['password']));

                //

                $vm_name = $vmname;

                //

                //                if ($vms->get_vm_info($vm_name)->summary->runtime->powerState != 'poweredOff') {

                //                    $response_obj = $vms->vm_power_off($vm_name, true);

                //                }

                $response = $vms->reconfigureExistingVm($vm_name, $results['productids'], $pid);

                if ($response == 'success') {



                    $ipListArr = $ip;

                    foreach ($ipListArr as $ipKey => $Ip) {

                        if ($ipKey == 0) {

                            $sts = 1;
                        } else {

                            $sts = 2;
                        }



                        try {

                            $updatedStatus = Capsule::table('mod_vmware_ip_list')

                                ->where('ip', $Ip)

                                ->update(

                                    [

                                        'status' => $sts,

                                        'forvm' => $vmname,

                                    ]

                                );
                        } catch (\Exception $e) {

                            logActivity("couldn't update mod_vmware_ip_list: {$e->getMessage()}");
                        }

                        try {

                            $updatedStatus = Capsule::table('tblcustomfields')

                                ->where('id', $OsVersionCfId)

                                ->update(

                                    [

                                        'fieldoptions' => $guest_os_version,

                                    ]

                                );
                        } catch (\Exception $e) {

                            logActivity("couldn't update tblcustomfields: {$e->getMessage()}");
                        }
                    }

                    try {

                        $updatedStatus = Capsule::table('tblhosting')

                            ->where('id', $results['productids'])

                            ->update(

                                [

                                    //                                    'domain' => $_POST['vmname'],

                                    'dedicatedip' => $ipListArr[0],

                                    'domainstatus' => 'Active',

                                ]

                            );
                    } catch (\Exception $e) {

                        logActivity("couldn't update tblhosting: {$e->getMessage()}");
                    }

                    $row = Capsule::table('mod_vmware_settings')->where('uid', $user)->where('sid', $results['productids'])->count();

                    $setting = 'migrate';

                    $settingValues = [

                        'setting' => $setting,

                        'uid' => $user,

                        'sid' => $results['productids'],

                    ];

                    if ($row == 0) {

                        try {

                            Capsule::table('mod_vmware_settings')->insert($settingValues);
                        } catch (Exception $ex) {

                            logActivity("could't insert into table mod_vmware_settings error: {$ex->getMessage()}");
                        }
                    } else {

                        try {

                            Capsule::table('mod_vmware_settings')->where('uid', $user)->where('sid', $results['productids'])->update($settingValues);
                        } catch (Exception $ex) {

                            logActivity("could't update into table mod_vmware_settings error: {$ex->getMessage()}");
                        }
                    }

                    $message = '<div class="successbox">' . $LANG['addedsucceessmsg'] . '</div><p style="padding: 12px 0;"><a style="border: 1px solid #1a4d80; background: #1a4d80;  padding: 8px; color: #fffff7;text-decoration: none;" href="clientsservices.php?userid=' . $user . '&id=' . $results['productids'] . '">Open New Service</a></p>';
                } else {

                    $message = '<div class="errorbox">' . $response . '</div>';
                }
            } catch (Exception $ex) {

                Capsule::table('tblhosting')->where('id', $results['productids'])->delete();

                $message = '<div class="errorbox">' . $results['message'] . '</div>';
            }
        } else {

            $message = '<div class="errorbox">' . $results['message'] . '</div>';
        }
    } else {

        $message = '<div class="errorbox">' . $vmname . ' ' . $LANG['vmexist'] . '</div>';
    }
}



function vmGetWHMCSProductCfId($pid, $field)
{

    $result = Capsule::table('tblcustomfields')->where('type', 'product')->where('relid', $pid)->where('fieldname', 'like', '%' . $field . '%')->get();

    return $result[0]->id;
}

?>



<script type="text/javascript" language="javascript" src="<?php echo $jsPath; ?>index.js"></script>

<script type="text/javascript" language="javascript" src="<?php echo $jsPath; ?>standalone/selectize.js"></script>

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

                        <h3><?php echo $LANG['assignexisting']; ?></h3>

                        <p>

                            <?php echo $LANG['vmassignexistingvmdesc']; ?>&nbsp;

                        </p>

                    </div>

                    <?php if (isset($message)) echo $message; ?>

                    <form action="" method="post">

                        <table class="form partner-form" width="100%" border="0" cellspacing="2" cellpadding="3">

                            <tbody>

                                <tr>



                                    <td width="15%" class="fieldlabel">

                                        <?php echo $LANG['vmselectuser']; ?>

                                    </td>

                                    <td class="fieldarea">

                                        <!--<input type="text" value="" onkeypress="searchUser(this, '<?php echo $modulelink; ?>');">-->

                                        <select name="user" required="" id="select-client">

                                            <option value=""><?php echo $LANG['select']; ?></option>

                                            <?php

                                            foreach (Capsule::table('tblclients')->orderby('id', 'ASC')->get() as $clients) {

                                                $clients = (array) $clients;

                                                $name = '#' . $clients['id'] . ' ' . $clients['firstname'] . ' ' . $clients['lastname'] . ' (' . $clients['companyname'] . ')';

                                                echo '<option value="' . $clients['id'] . '">' . $name . '</option>';
                                            }

                                            ?>

                                        </select>

                                    </td>

                                    <td class="fieldlabel" width="15%">

                                        <?php echo $LANG['vmselectproduct'];

                                        ?>

                                    </td>

                                    <td class="fieldarea">

                                        <select name="product" required="" id="product" link="<?php echo $modulelink; ?>">

                                            <option value=""><?php echo $LANG['select']; ?></option>

                                            <?php

                                            foreach (Capsule::table('tblproducts')->where('servertype', 'vmware')->orderby('id', 'ASC')->get() as $products) {

                                                $products = (array) $products;

                                                $name = $products['name'];

                                                echo '<option value="' . $products['id'] . '">' . $name . '</option>';
                                            }

                                            ?>

                                        </select>

                                    </td>

                                </tr>

                                <tr>

                                    <td class="fieldlabel"><?php echo $LANG['vmpm']; ?></td>

                                    <td class="fieldarea">

                                        <select name="paymentmethod" required="">

                                            <option value=""><?php echo $LANG['select']; ?></option>

                                            <?php

                                            $command = "getpaymentmethods";

                                            $adminuser = $admin[0]->id;



                                            $results = localAPI($command, $values, $adminuser);

                                            foreach ($results['paymentmethods']['paymentmethod'] as $key => $paymentMethod) {

                                                echo '<option value="' . $paymentMethod['module'] . '">' . $paymentMethod['displayname'] . '</option>';
                                            }

                                            ?>

                                        </select>

                                    </td>

                                    <td class="fieldlabel"><?php echo $LANG['vmbillingcycle']; ?></td>

                                    <td class="fieldarea">

                                        <select name="billingcycle" required="">

                                            <option value=""><?php echo $LANG['select']; ?></option>

                                            <option value="monthly"><?php echo $LANG['vmmonthly']; ?></option>

                                            <option value="quarterly"><?php echo $LANG['vmquartly']; ?></option>

                                            <option value="semiannually"><?php echo $LANG['vmsemiannually']; ?></option>

                                            <option value="annually"><?php echo $LANG['vmannually']; ?></option>

                                            <option value="biennially"><?php echo $LANG['vmbiennially']; ?></option>

                                            <option value="triennially"><?php echo $LANG['vmtriennially']; ?></option>

                                        </select>

                                    </td>



                                </tr>

                                <tr>

                                    <td width="15%" class="fieldlabel">

                                        <?php echo $LANG['selectserver']; ?>

                                    </td>

                                    <td class="fieldarea">

                                        <?php $serverData = Capsule::table('mod_vmware_server')->get(); ?>

                                        <select name="vmserver" id="exiting_vmservers" class="form-control select-inline" required="" link="<?php echo $modulelink; ?>">

                                            <option disable='disable' value=''>Select</option>

                                            <?php foreach ($serverData as $serverData01) { ?>

                                                <option value="<?php echo $serverData01->id; ?>"><?php echo $serverData01->server_name; ?></option>

                                            <?php }

                                            ?>

                                        </select>

                                    </td>



                                    <td width="15%" class="fieldlabel">

                                        <?php echo $LANG['datacenter']; ?>

                                    </td>

                                    <td class="fieldarea">

                                        <select name="datacenter" id="datacenter" required="" link="<?php echo $modulelink; ?>">

                                            <option value=""><?php echo $LANG['select']; ?></option>

                                        </select>

                                    </td>



                                </tr>



                                <tr>

                                    <td class="fieldlabel" width="15%">

                                        <?php echo $LANG['os_list_hostname']; ?>

                                    </td>

                                    <td class="fieldarea">

                                        <select name="hostname" required="" id="hostname">

                                            <option value=""><?php echo $LANG['select']; ?></option>

                                        </select>

                                    </td>

                                    <td class="fieldlabel" width="15%">

                                        <?php echo $LANG['vmselectvm']; ?>

                                    </td>

                                    <td class="fieldarea">

                                        <select name="vmname" required="" id="vmname">

                                            <option value=""><?php echo $LANG['select']; ?></option>

                                        </select>

                                    </td>

                                </tr>

                                <tr>



                                    <td width="20%" class="fieldlabel">

                                        <?php echo $LANG['guestosfamily']; ?>

                                    </td>

                                    <td width="80%" class="fieldarea">

                                        <select name="guest_os_family" id="guest_os_family" class="form-control select-inline" link="<?php echo $modulelink; ?>">

                                            <option value="Windows">Windows</option>

                                            <option value="Linux">Linux</option>

                                        </select>

                                    </td>

                                    <td class="fieldlabel" width="20%">

                                        <?php echo $LANG['guestosversion']; ?>

                                    </td>

                                    <td width="80%" class="fieldarea">

                                        <select name="guest_os_version" id="guest_os_version">

                                            <option value=""><?php echo $LANG['select']; ?></option>

                                        </select>

                                    </td>

                                </tr>

                                <tr>



                                    <td class="fieldlabel" width="15%">

                                        <?php echo $LANG['selectip']; ?>

                                    </td>

                                    <td class="fieldarea">

                                        <select name="ip[]" id="ips" required="" multiple="multiple" style="width: 148px;">

                                            <option value=""><?php echo $LANG['select']; ?></option>

                                            <?php

                                            foreach (Capsule::table('mod_vmware_ip_list')->where('status', '0')->orderby('id', 'ASC')->get() as $ip) {

                                                $ip = (array) $ip;

                                                echo '<option value="' . $ip['id'] . '">' . $ip['ip'] . '</option>';
                                            }

                                            ?>

                                        </select>

                                    </td>

                                    <td class="fieldlabel" width="20%">

                                        <?php echo $LANG['stop_invoice']; ?>

                                    </td>

                                    <td width="80%" class="fieldarea">

                                        <input type="checkbox" name="stop_invoice" id="stop_invoice" />

                                    </td>

                                </tr>

                                <tr>



                                    <td class="fieldlabel bandwidth" width="15%" style="display:none">

                                        <?php echo $LANG['vmbandwidth']; ?>

                                    </td>

                                    <td class="fieldarea bandwidth" style="display:none">

                                        <input type="number" value="" name="bandwidth" id="bandwidth">&nbsp;(GB)

                                    </td>

                                </tr>

                                <tr>

                                    <td colspan="100%">

                                        <div class="form_btn">

                                            <input type="submit" name="create_server" value="<?php echo $LANG['submit']; ?>">

                                            &nbsp;&nbsp;

                                            <input type="reset" onclick="jQuery('#ss_form').fadeOut(1000);" value="<?php echo $LANG['cancel']; ?>">



                                        </div>

                                    </td>

                                </tr>

                            </tbody>

                        </table>

                    </form>



                    <?php

                    if (file_exists(dirname(__DIR__) . '/includes/footer.php'))

                        require_once dirname(__DIR__) . '/includes/footer.php';

                    ?>

                </div>

            </div>

        </div>

    </div>

</div>

<script>
    $('#select-client').selectize({

        hideSelected: true,

        placeholder: "Select User",

        transitionEnter: true,

        transitionLeave: true

    });
</script>