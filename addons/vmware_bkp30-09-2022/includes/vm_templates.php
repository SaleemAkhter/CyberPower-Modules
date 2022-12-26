<?php



use Illuminate\Database\Capsule\Manager as Capsule;

Capsule::Schema()->table('mod_vmware_temp_list', function ($table) {
    if (Capsule::Schema()->hasColumn('mod_vmware_temp_list', 'datastore'))
        $table->dropColumn('datastore');
    if (Capsule::Schema()->hasColumn('mod_vmware_temp_list', 'resourcepool'))
        $table->dropColumn('resourcepool');
    if (Capsule::Schema()->hasColumn('mod_vmware_temp_list', 'network_adp'))
        $table->dropColumn('network_adp');
});


$edit_temp = filter_var($_GET['edit_temp'], FILTER_SANITIZE_STRING);

$delete_temp = filter_var($_GET['delete_temp'], FILTER_SANITIZE_STRING);

if (isset($_POST['create_temp'])) {

    Capsule::Schema()->table('mod_vmware_temp_list', function ($table) {

        if (Capsule::Schema()->hasColumn('mod_vmware_temp_list', 'dc_custom_name'))

            $table->dropColumn('dc_custom_name');

        if (!Capsule::Schema()->hasColumn('mod_vmware_temp_list', 'product_key'))

            $table->string('product_key');

        if (!Capsule::Schema()->hasColumn('mod_vmware_temp_list', 'fromtemplate'))

            $table->string('fromtemplate');
    });

    $datacenter = filter_var($_POST['datacenter'], FILTER_SANITIZE_STRING);

    $vmtemplate = filter_var($_POST['vmtemplate'], FILTER_SANITIZE_STRING);

    $customname = filter_var($_POST['customname'], FILTER_SANITIZE_STRING);

    $sys_pw = filter_var($_POST['sys_pw'], FILTER_SANITIZE_STRING);

    $server = filter_var($_POST['server'], FILTER_SANITIZE_STRING);

    $show_on_order_form = filter_var($_POST['show_on_order_form'], FILTER_SANITIZE_STRING);

    $autoconfig = filter_var($_POST['autoconfig'], FILTER_SANITIZE_STRING);

    $product_key = filter_var($_POST['product_key'], FILTER_SANITIZE_STRING);
    
    $port = filter_var($_POST['port'], FILTER_SANITIZE_STRING);

    $fromtemplate = filter_var($_POST['fromtemplate'], FILTER_SANITIZE_STRING);

    $resource_pool = filter_var($_POST['resource_pool'], FILTER_SANITIZE_STRING);

    // $guest_network_adptr = filter_var($_POST['guest_network_adptr'], FILTER_SANITIZE_STRING);

    $hostname = filter_var($_POST['hostname'], FILTER_SANITIZE_STRING);

    $os_family = filter_var($_POST['os_family'], FILTER_SANITIZE_STRING);

    // $datastore = filter_var($_POST['datastore'], FILTER_SANITIZE_STRING);

    $table = 'mod_vmware_temp_list';

    if (empty($datacenter))

        $error = 'Datacenter is required.';

    elseif (empty($vmtemplate))

        $error = 'Vm Template is required.';

    elseif (empty($customname))

        $error = 'Customname is required.';

    elseif (empty($sys_pw))

        $error = 'System password is required.';

    else {

        $cs_count = Capsule::table('mod_vmware_temp_list')->where('server_id', $server)->where('datacenter', $datacenter)->where('hostname', $hostname)->where('customname', $customname)->count();



        if (isset($_GET['edit_temp']))

            $cs_count = Capsule::table('mod_vmware_temp_list')->where('server_id', $server)->where('datacenter', $datacenter)->where('hostname', $hostname)->where('customname', $customname)->where('id', '!=', $edit_temp)->count();



        if ($cs_count > 0)

            $exist = true;

        $custom_name = $customname;



        if (isset($show_on_order_form))

            $showOnOrder = $show_on_order_form;

        else

            $showOnOrder = '';



        if (isset($autoconfig))

            $autoconfig = $autoconfig;

        else

            $autoconfig = '';



        if (isset($product_key))

            $product_key = $product_key;

        else

            $product_key = '';



        if (isset($fromtemplate))

            $fromtemplate = $fromtemplate;

        else

            $fromtemplate = '';

        $values = [

            'datacenter' => $datacenter,

            'vmtemplate' => $vmtemplate,

            'customname' => $custom_name,

            'server_id' => $server,

            'sys_pw' => $vmWare->vmwarePwencryption($sys_pw),

            'os_family' => $os_family,

            'hostname' => $hostname,

            // 'datastore' => $datastore,

            // 'resourcepool' => $resource_pool,

            // 'network_adp' => $guest_network_adptr,

            'status' => $showOnOrder,

            'autoconfig' => $autoconfig,

            'product_key' => $product_key,

            'fromtemplate' => $fromtemplate,
            'port' => $port,

        ];



        $productListArr = $vmWare->vmwareGetWHMCSProductList();



        if (!isset($exist)) {

            if (isset($_GET['edit_temp'])) {



                $cs_name = Capsule::table($table)->select('customname')->where('server_id', $server)->where('id', $edit_temp)->first();



                Capsule::table($table)->where('id', $edit_temp)->update($values);



                $cs_name = $cs_name->customname;



                foreach ($productListArr as $pKey => $productList) {

                    if ($productList['configoption1'] != '') {

                        $getServerIdArr = Capsule::table('mod_vmware_server')->select('id')->where('server_name', $productList['configoption3'])->get();
                        if (count($getServerIdArr) == 0)
                            $getServerIdArr = Capsule::table('mod_vmware_server')->select('id')->where('id', $productList['configoption3'])->get();

                        $getServerId = $getServerIdArr[0]->id;

                        if ($server == $getServerId) {

                            $pid = $productList['id'];

                            $getConfigurableGroupId = Capsule::table('tblproductconfiglinks')->where('pid', $pid)->first();

                            $getConfigurableGroupId = $getConfigurableGroupId->gid;



                            $configurableOsVesrionId = $vmWare->wgsvmwareGetConfigurableOptionId($getConfigurableGroupId, 'guest_os_version');

                            if (!empty($configurableOsVesrionId))

                                $AddProductConfigurableSubOption = $vmWare->wgsvmwareAddUpdateConfigurableSubOption($configurableOsVesrionId, $custom_name, $cs_name, true);
                        }
                    }
                }

                header('location:' . $modulelink . '&action=vm_templates&update=true');
            } else {

                Capsule::table($table)->insert($values);



                foreach ($productListArr as $pKey => $productList) {

                    if ($productList['configoption1'] != '') {

                        $getServerIdArr = Capsule::table('mod_vmware_server')->select('id')->where('server_name', $productList['configoption3'])->get();
                        if (count($getServerIdArr) == 0)
                            $getServerIdArr = Capsule::table('mod_vmware_server')->select('id')->where('id', $productList['configoption3'])->get();

                        $getServerId = $getServerIdArr[0]->id;

                        if ($server == $getServerId) {

                            $pid = $productList['id'];

                            $getConfigurableGroupId = Capsule::table('tblproductconfiglinks')->where('pid', $pid)->first();

                            $getConfigurableGroupId = $getConfigurableGroupId->gid;



                            $configurableOsVesrionId = $vmWare->wgsvmwareGetConfigurableOptionId($getConfigurableGroupId, 'guest_os_version');

                            if (!empty($configurableOsVesrionId))

                                $AddProductConfigurableSubOption = $vmWare->wgsvmwareAddUpdateConfigurableSubOption($configurableOsVesrionId, $custom_name);
                        }
                    }
                }

                header('location:' . $modulelink . '&action=vm_templates&add=true');
            }
        } else {

            $vars['add_error'] = $LANG['customname'] . ' "' . $customname . '"  already exist';
        }
    }
}



if (isset($_GET['delete_temp']) && !empty($_GET['delete_temp'])) {



    $cs_name = Capsule::table('mod_vmware_temp_list')->select('customname', 'server_id')->where('id', $delete_temp)->first();

    $cs_name = $cs_name->customname;



    $productListArr = $vmWare->vmwareGetWHMCSProductList();



    foreach ($productListArr as $pKey => $productList) {

        if ($productList['configoption1'] != '') {

            $getServerIdArr = Capsule::table('mod_vmware_server')->select('id')->where('server_name', $productList['configoption3'])->get();
            if (count($getServerIdArr) == 0)
                $getServerIdArr = Capsule::table('mod_vmware_server')->select('id')->where('id', $productList['configoption3'])->get();

            $getServerId = $getServerIdArr[0]->id;

            if ($cs_name->server_id == $getServerId) {

                $pid = $productList['id'];

                $getConfigurableGroupId = Capsule::table('tblproductconfiglinks')->where('pid', $pid)->first();

                $getConfigurableGroupId = $getConfigurableGroupId->gid;



                $configurableOsVesrionId = $vmWare->wgsvmwareGetConfigurableOptionId($getConfigurableGroupId, 'guest_os_version');

                if (!empty($configurableOsVesrionId))

                    $AddProductConfigurableSubOption = $vmWare->wgsvmwareDeleteConfigurableSubOption($configurableOsVesrionId, $cs_name);
            }
        }
    }

    Capsule::table('mod_vmware_temp_list')->where('id', $delete_temp)->delete();

    header('location:' . $modulelink . '&action=vm_templates&delete=true');
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

                        <h3><?php echo $LANG['vmosmapclone']; ?></h3>

                        <p>

                            <?php echo $LANG['vmclonemapdesc']; ?>&nbsp;

                        </p>

                        <p>

                            <?php echo $LANG['vmclonesamplevmdesc']; ?>&nbsp;

                        </p>

                    </div>



                    <?php

                    if (isset($vars['add_error']) && !empty($vars['add_error'])) {

                        echo '<div class="errorbox"><strong><span class="title">Error!</span></strong><br>' . $vars['add_error'] . '</div>';

                        $_GET['add'] = '';
                    }

                    if (isset($_GET['add']) && $_GET['add'] == 'true' && !isset($vars['add_error'])) {

                        echo '<div class="successbox"><strong><span class="title">Changes Saved Successfully!</span></strong><br>Your Vm Template have been added.</div>';
                    } elseif (isset($_GET['update']) && $_GET['update'] == 'true' && !isset($vars['add_error'])) {

                        $txt = '';

                        if (isset($_SESSION['dcexist']) || isset($_SESSION['csexist'])) {

                            $cls = 'warningbox';

                            $txt = ' But ';

                            if (isset($_SESSION['dcexist']))

                                $txt .= $LANG['datacenter_custom_name'] . ' "' . $_SESSION['dcexist'] . '", ';

                            if (isset($_SESSION['csexist']))

                                $txt .= $LANG['customname'] . ' "' . $_SESSION['csexist'] . '"';

                            $txt .= '" already exist.';
                        } else {

                            $cls = 'successbox';
                        }

                        unset($_SESSION['dcexist']);

                        unset($_SESSION['csexist']);

                        echo '<div class="' . $cls . '"><strong><span class="title">Changes Saved Successfully!</span></strong><br>Your Vm Template have been saved.' . $txt . '</div>';
                    } elseif (isset($_GET['delete']) && $_GET['delete'] == 'true' && !isset($vars['add_error'])) {

                        echo '<div class="successbox"><strong><span class="title">Changes Saved Successfully!</span></strong><br>Your Vm Template have been deleted.</div>';
                    } elseif (isset($error) && !isset($vars['add_error'])) {

                        echo '<div class="errorbox"><strong><span class="title">Error:</span></strong><br>' . $error . '!</div>';
                    }

                    ?>

                    <div class="vmosmap_button">

                        <a href="<?php echo $modulelink . '&action=guest_os_list'; ?>"><button><?php echo $LANG['vmosmapiso']; ?></button></a>

                        <a href="<?php echo $modulelink . '&action=vm_templates'; ?>"><button class="active"><?php echo $LANG['vmosmapclone']; ?></button></a>

                    </div>

                    <?php $tempData = array(); ?>

                    <?php if (!isset($_GET['edit_temp'])) { ?>

                        <div class="btn_section">

                            <button onclick="jQuery('#temp_form').fadeToggle(1000);"><?php echo $LANG['vm_addvm']; ?></button>

                        </div>

                    <?php

                    } else {

                        $tempData01 = Capsule::table('mod_vmware_temp_list')->where('id', $edit_temp)->get();
                        if (count($tempData01) > 0)
                            $tempData = (array) $tempData01[0];
                        else
                            $tempData01 = [];
                    }

                    ?>

                    <div id="temp_form" style="display:<?php if (!isset($_GET['edit_temp'])) { ?>none<?php } ?>;">

                        <!--<p style="color: #ff0000;"><?php echo $LANG['vmtemplatesdesc']; ?></p>-->

                        <form action="" method="post">

                            <input type="hidden" value="<?php echo $tempData['hostname']; ?>" id="get_os_list_hostname">

                            <!-- <input type="hidden" value="<?php echo $tempData['resourcepool']; ?>" id="get_os_list_resourcepool"> -->

                            <!-- <input type="hidden" value="<?php echo $tempData['network_adp']; ?>" id="getNetworkval"> -->

                            <!-- <input type="hidden" id="getDatastoreval" value="<?php echo $tempData['datastore']; ?>"> -->

                            <input type="hidden" value="<?php echo $tempData['datacenter']; ?>" id="getDatacenterval">

                            <input type="hidden" value="<?php echo $tempData['vmtemplate']; ?>" id="getExistingVm">

                            <table class="form partner-form" width="100%" border="0" cellspacing="2" cellpadding="3">

                                <tbody>



                                    <tr>

                                        <td width="15%" class="fieldlabel">

                                            <?php echo $LANG['selectserver']; ?>

                                        </td>

                                        <td class="fieldarea">

                                            <select name="server" id="vmserver" class="form-control select-inline" required="" link="<?php echo $modulelink; ?>">

                                                <option disable="disable" value="">Select</option>

                                                <?php

                                                $serverData = Capsule::table('mod_vmware_server')->get();

                                                foreach ($serverData as $serverData01) {

                                                ?>

                                                    <option value="<?php echo $serverData01->id; ?>" <?php

                                                                                                        if ($tempData['server_id'] == $serverData01->id) {

                                                                                                            echo 'selected="selected"';
                                                                                                        }

                                                                                                        ?>><?php echo $serverData01->server_name; ?></option>

                                                <?php

                                                }

                                                ?>

                                            </select>

                                        </td>

                                    </tr>

                                    <tr>

                                        <td width="20%" class="fieldlabel">

                                            <?php echo $LANG['datacenter']; ?>

                                        </td>

                                        <td width="80%" class="fieldarea">

                                            <select name="datacenter" class="form-control select-inline" id="os_list_datacenter" required="" link="<?php echo $modulelink; ?>">

                                                <option value="">Select</option>

                                            </select>

                                        </td>

                                    </tr>

                                    <tr>

                                        <td width="20%" class="fieldlabel">

                                            <?php echo $LANG['os_list_hostname']; ?>

                                        </td>

                                        <td width="80%" class="fieldarea">

                                            <select name="hostname" class="form-control select-inline" id="os_list_hostname" required="" link="<?php echo $modulelink; ?>">

                                                <option value="">Select</option>

                                            </select>

                                        </td>

                                    </tr>

                                    <!-- <tr>

                                        <td width="20%" class="fieldlabel">

                                            <?php echo $LANG['os_list_resourcepool']; ?>

                                        </td>

                                        <td width="80%" class="fieldarea">

                                            <select name="resource_pool" class="form-control select-inline" id="os_list_resourcepool" required="" link="<?php echo $modulelink; ?>">

                                                <option value="">Select</option>

                                            </select>

                                        </td>



                                    </tr> -->

                                    <!-- <tr>

                                        <td width="20%" class="fieldlabel">

                                            <?php echo $LANG['os_list_network_adaptor']; ?>

                                        </td>

                                        <td width="80%" class="fieldarea">

                                            <select name="guest_network_adptr" class="form-control select-inline" required="" link="<?php echo $modulelink; ?>" id="os_list_network_adaptor">

                                                <option value="">Select</option>

                                            </select>



                                        </td>



                                    </tr> -->

                                    <!-- <tr>

                                        <td class="fieldlabel" width="20%">

                                            <?php echo $LANG['guestosdatastore']; ?>

                                        </td>

                                        <td width="80%" class="fieldarea">

                                            <select name="datastore" class="form-control select-inline" required="" link="<?php echo $modulelink; ?>" id="guest_os_datastore">

                                                <option value="">Select</option>

                                            </select>

                                            <br />

                                    </td>

                                    </tr> -->

                                    <tr>

                                        <td width="15%" class="fieldlabel">

                                            <?php echo $LANG['guestosfamily']; ?>

                                        </td>

                                        <td class="fieldarea">

                                            <select name="os_family" id="os_family" class="form-control select-inline" required="">

                                                <option value="Windows" <?php

                                                                        if (!empty($tempData) && $tempData['os_family'] == 'Windows') {

                                                                            echo 'selected="selected"';
                                                                        }

                                                                        ?>>Windows</option>

                                                <option value="Linux" <?php

                                                                        if (!empty($tempData) && $tempData['os_family'] == 'Linux') {

                                                                            echo 'selected="selected"';
                                                                        }

                                                                        ?>>Linux</option>

                                                <option value="Others" <?php

                                                                        if (!empty($tempData) && $tempData['os_family'] == 'Others') {

                                                                            echo 'selected="selected"';
                                                                        }

                                                                        ?>>Others</option>

                                            </select>

                                        </td>

                                    </tr>

                                    <tr>

                                        <td class="fieldlabel" width="15%"><?php echo $LANG['customname']; ?></td>

                                        <td class="fieldarea">

                                            <input type="text" size="30" name="customname" id="customname" autocomplete="off" value="<?php

                                                                                                                                        if (!empty($tempData)) {

                                                                                                                                            echo $tempData['customname'];
                                                                                                                                        }

                                                                                                                                        ?>" required=""><br />

                                            <span style=""><?php echo $LANG['customnameplaceholder']; ?></span>

                                        </td>

                                    </tr>

                                    <tr>

                                        <td class="fieldlabel" width="15%">

                                            <?php echo $LANG['vmselectsamplevm']; ?>

                                        </td>

                                        <td class="fieldarea">

                                            <select name="vmtemplate" id="vmtemplate" required="" autocomplete="off" class="form-control select-inline">

                                                <option value="">Select</option>

                                            </select>

                                            <!--                                            <input type="text" size="30" name="vmtemplate" id="vmtemplate" value="<?php

                                                                                                                                                                    if (!empty($tempData)) {

                                                                                                                                                                        echo $tempData['vmtemplate'];
                                                                                                                                                                    }

                                                                                                                                                                    ?>" required="" placeholder="">-->

                                            <br />

                                            <span style=""><?php echo $LANG['vmnameplaceholder']; ?></span>

                                        </td>



                                    </tr>

                                    <tr>



                                        <td class="fieldlabel" width="15%"><?php echo $LANG['vmsystempw']; ?></td>

                                        <td class="fieldarea">

                                            <input type="password" size="30" name="sys_pw" autocomplete="off" id="sys_pw" value="<?php

                                                                                                                                    if (!empty($tempData)) {

                                                                                                                                        echo ($vmWare->vmwarePwdecryption($tempData['sys_pw']) == '') ? $tempData['sys_pw'] : $vmWare->vmwarePwdecryption($tempData['sys_pw']);
                                                                                                                                    }

                                                                                                                                    ?>" required=""><br />

                                            <span style=""><?php echo $LANG['sys_pw_desc']; ?></span>

                                        </td>

                                    </tr>

                                    <tr>



                                        <td class="fieldlabel" width="15%"><?php echo $LANG['product_key']; ?></td>

                                        <td class="fieldarea">

                                            <input type="text" size="30" name="product_key" autocomplete="off" id="product_key" value="<?php

                                                                                                                                        if (!empty($tempData)) {

                                                                                                                                            echo $tempData['product_key'];
                                                                                                                                        }

                                                                                                                                        ?>"><br />

                                            <span style=""><?php echo $LANG['product_key_desc']; ?></span>

                                        </td>

                                    </tr>
                                    <tr>
                                    <td class="fieldlabel" width="15%"><?php echo $LANG['temp_port']; ?></td>

                                    <td class="fieldarea">

                                        <input type="text" size="30" name="port" autocomplete="off" id="port" value="<?php

                                                                                                                                    if (!empty($tempData)) {

                                                                                                                                        echo $tempData['port'];
                                                                                                                                    }

                                                                                                                                    ?>"><br />

                                        <span style=""><?php echo $LANG['port_desc']; ?></span>

                                    </td>

                                    </tr>
                                    <tr>

                                        <td width="20%" class="fieldlabel">

                                            <?php echo $LANG['autoconfig']; ?>

                                        </td>

                                        <td width="80%" class="fieldarea">

                                            <input type="checkbox" name="autoconfig" <?php

                                                                                        if (!empty($tempData) && $tempData['autoconfig'] == '1') {

                                                                                            echo 'checked="checked"';
                                                                                        }

                                                                                        ?> value="1">&nbsp;&nbsp;

                                            <span><?php echo $LANG['autoconfigdesc']; ?></span>

                                        </td>





                                    </tr>

                                    <tr>

                                        <td width="20%" class="fieldlabel">

                                            <?php echo $LANG['fromtemplate']; ?>

                                        </td>

                                        <td width="80%" class="fieldarea">

                                            <input type="checkbox" name="fromtemplate" <?php

                                                                                        if (!empty($tempData) && $tempData['fromtemplate'] == '1') {

                                                                                            echo 'checked="checked"';
                                                                                        }

                                                                                        ?> value="1">&nbsp;&nbsp;

                                            <span><?php echo $LANG['fromtemplate_desc']; ?></span>

                                        </td>





                                    </tr>

                                    <tr>

                                        <td width="20%" class="fieldlabel">

                                            <?php echo $LANG['hidefromorderform']; ?>

                                        </td>

                                        <td width="80%" class="fieldarea">

                                            <input type="checkbox" name="show_on_order_form" <?php

                                                                                                if (!empty($tempData) && $tempData['status'] == '1') {

                                                                                                    echo 'checked="checked"';
                                                                                                }

                                                                                                ?> value="1">

                                        </td>





                                    </tr>

                                    <tr>

                                        <td colspan="100%">

                                            <div class="form_btn">

                                                <input type="submit" name="create_temp" value="<?php echo $LANG['submit']; ?>">

                                                &nbsp;&nbsp;

                                                <?php

                                                if (isset($_GET['edit_temp'])) {

                                                ?>

                                                    <a href="<?php echo $modulelink; ?>&action=vm_templates"><input type="button" name="cancel" value="<?php echo $LANG['cancel']; ?>"></a>

                                                <?php

                                                } else {

                                                ?>

                                                    <input type="reset" onclick="jQuery('#temp_form').fadeOut(1000);" value="<?php echo $LANG['cancel']; ?>">

                                                <?php

                                                }

                                                ?>



                                            </div>

                                        </td>

                                    </tr>

                                </tbody>

                            </table>

                        </form>

                    </div>

                    <?php if (!isset($_GET['edit_temp'])) { ?>

                        <form action="" method="post">

                            <span>Filter OS Family: </span>

                            <select name="osfamily" onchange="submit()">

                                <option value="">Select OS Family</option>

                                <option value="Windows" <?php if (isset($_POST['osfamily']) && $_POST['osfamily'] == 'Windows') echo 'selected="selected"'; ?>>Windows</option>

                                <option value="Linux" <?php if (isset($_POST['osfamily']) && $_POST['osfamily'] == 'Linux') echo 'selected="selected"'; ?>>Linux</option>

                                <option value="Others" <?php if (isset($_POST['osfamily']) && $_POST['osfamily'] == 'Others') echo 'selected="selected"'; ?>>Others</option>

                            </select>

                        </form>

                        <div class="tablebg">

                            <table id="sortabletbl0" class="datatable partnerdetail_table" width="100%" border="0" cellspacing="1" cellpadding="3">

                                <tbody>

                                    <tr>

                                        <th width="10%"><?php echo $LANG['guestosserver']; ?></th>

                                        <th width="10%"><?php echo $LANG['guestosfamily']; ?></th>

                                        <th width="10%"><?php echo $LANG['datacenter']; ?></th>

                                        <th width="10%"><?php echo $LANG['os_list_hostname']; ?></th>

                                        <!--<th><?php echo $LANG['vmtemplate']; ?></th>-->

                                        <!--<th width="15%"><?php echo $LANG['dc_custom_name']; ?></th>-->

                                        <th width="10%"><?php echo $LANG['vmsystempw']; ?></th>

                                        <th width="15%"><?php echo $LANG['customname']; ?></th>
                                        <th width="15%"><?php echo $LANG['temp_port']; ?></th>

                                        <th width="7%"><?php echo $LANG['autoconfigheading']; ?></th>

                                        <th width="7%"><?php echo $LANG['hide']; ?></th>

                                        <th width="10%"><?php echo $LANG['action']; ?></th>

                                    </tr>

                                    <?php

                                    if (isset($_POST['osfamily']) && $_POST['osfamily'] != '')

                                        $ipQuery = Capsule::table('mod_vmware_temp_list')->where('os_family', filter_var($_POST['osfamily'], FILTER_SANITIZE_STRING))->get();

                                    else

                                        $ipQuery = Capsule::table('mod_vmware_temp_list')->get();

                                    foreach ($ipQuery as $itempResult01) {

                                        $itempResult = (array) $itempResult01;

                                        $servername = Capsule::table('mod_vmware_server')->where('id', $itempResult['server_id'])->get();

                                    ?>

                                        <tr>

                                            <td width="10%"><?php echo $servername[0]->server_name; ?></td>

                                            <td width="10%"><?php echo $itempResult['os_family']; ?></td>

                                            <td width="10%"><?php echo $itempResult['datacenter']; ?></td>

                                            <td width="10%"><?php echo $itempResult['hostname']; ?></td>

                                            <!--<td width="15%"><?php echo $itempResult['dc_custom_name']; ?></td>-->

                                            <!--<td><?php echo $itempResult['vmtemplate']; ?></td>-->

                                            <td width="10%"><?php echo ($vmWare->vmwarePwdecryption($itempResult['sys_pw']) == '') ? $itempResult['sys_pw'] : $vmWare->vmwarePwdecryption($itempResult['sys_pw']); ?></td>

                                            <td width="15%"><?php echo $itempResult['customname']; ?></td>
                                            <td width="15%"><?php echo $itempResult['port']?$itempResult['port']:'-'; ?></td>

                                            <td width="7%"><?php

                                                            if ($itempResult['autoconfig'] == '1') {

                                                                echo 'ON';
                                                            }

                                                            ?></td>

                                            <td width="7%"><?php

                                                            if ($itempResult['status'] == '1') {

                                                                echo 'ON';
                                                            }

                                                            ?></td>

                                            <td width="10%">

                                                <a href="<?php echo $modulelink; ?>&action=vm_templates&edit_temp=<?php echo $itempResult['id']; ?>">

                                                    <img src="images/edit.gif" width="16" height="16" border="0" alt="Edit"></a>

                                                &nbsp;

                                                <a href="<?php echo $modulelink; ?>&action=vm_templates&delete_temp=<?php echo $itempResult['id']; ?>" onclick="return confirm('Are you sure delete this row ?');">

                                                    <img src="images/delete.gif" width="16" height="16" border="0" alt="Cancel &amp; Delete">

                                                </a>

                                            </td>

                                        </tr>

                                    <?php } ?>

                                </tbody>

                            </table>

                        </div>

                    <?php } ?>

                </div>

                <?php

                if (file_exists(dirname(__DIR__) . '/includes/footer.php'))

                    require_once dirname(__DIR__) . '/includes/footer.php';

                ?>

            </div>

        </div>

    </div>

</div>