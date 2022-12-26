<?php



use Illuminate\Database\Capsule\Manager as Capsule;

Capsule::Schema()->table('mod_vmware_os_list', function ($table) {
    if (!Capsule::Schema()->hasColumn('mod_vmware_os_list', 'datastore'))
        $table->string('datastore');
});
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

                        <h3><?php echo $LANG['vmosmapiso']; ?></h3>

                        <p>

                            <?php echo $LANG['vmiosmapdesc']; ?>&nbsp;

                        </p>

                    </div>



                    <?php

                    #VM ware os list url

                    #https://www.vmware.com/support/developer/converter-sdk/conv43_apireference/vim.vm.GuestOsDescriptor.GuestOsIdentifier.html

                    $guest_os_version = filter_var($_POST['guest_os_version'], FILTER_SANITIZE_STRING);

                    $show_on_order_form = filter_var($_POST['show_on_order_form'], FILTER_SANITIZE_STRING);

                    $guest_os_server = filter_var($_POST['guest_os_server'], FILTER_SANITIZE_STRING);

                    $datacenter = filter_var($_POST['datacenter'], FILTER_SANITIZE_STRING);

                    $hostname = filter_var($_POST['hostname'], FILTER_SANITIZE_STRING);

                    $osfamily = filter_var($_POST['osfamily'], FILTER_SANITIZE_STRING);

                    $guest_os_family = filter_var($_POST['guest_os_family'], FILTER_SANITIZE_STRING);

                    $guest_os_version_id = filter_var($_POST['guest_os_version_id'], FILTER_SANITIZE_STRING);

                    $guest_os_iso = filter_var($_POST['guest_os_iso'], FILTER_SANITIZE_STRING);

                    $datastore = filter_var($_POST['datastore'], FILTER_SANITIZE_STRING);

                    $resource_pool = filter_var($_POST['resource_pool'], FILTER_SANITIZE_STRING);

                    $guest_network_adptr = filter_var($_POST['guest_network_adptr'], FILTER_SANITIZE_STRING);

                    if ($guest_os_version != '') {



                        Capsule::Schema()->table('mod_vmware_os_list', function ($table) {

                            if (Capsule::Schema()->hasColumn('mod_vmware_os_list', 'dc_custom_name'))

                                $table->dropColumn('dc_custom_name');
                        });



                        if (!empty($show_on_order_form))

                            $showOnOrder = $show_on_order_form;

                        else

                            $showOnOrder = '';



                        $gov_count = Capsule::table('mod_vmware_os_list')->where('server_id', $guest_os_server)->where('datacenter', $datacenter)->where('hostname', $hostname)->where('os_version', $guest_os_version)->count();



                        $edit_os = filter_var($_GET['edit_os'], FILTER_SANITIZE_STRING);

                        if (isset($_GET['edit_os']))

                            $gov_count = Capsule::table('mod_vmware_os_list')->where('server_id', $guest_os_server)->where('datacenter', $datacenter)->where('hostname', $hostname)->where('os_version', $guest_os_version)->where('id', '!=', $edit_os)->count();



                        if ($gov_count > 0)

                            $exist = true;

                        $gov_name = $guest_os_version;



                        if (!isset($exist)) {

                            $data = [

                                'os_family' => $guest_os_family,

                                'server_id' => $guest_os_server,

                                'os_version' => $gov_name,

                                'os_version_id' => $guest_os_version_id,

                                'isofile' => $guest_os_iso,

                                'status' => $showOnOrder,

                                'datastore' => $datastore,

                                'hostname' => $hostname,

                                'datacenter' => $datacenter,

                                // 'resourcepool' => $resource_pool,

                                // 'network_adp' => $guest_network_adptr,

                            ];



                            $productListArr = $vmWare->vmwareGetWHMCSProductList();



                            if (isset($_GET['edit_os'])) {

                                try {



                                    $cs_name = Capsule::table('mod_vmware_os_list')->select('os_version')->where('server_id', $guest_os_server)->where('id', $edit_os)->first();



                                    $cs_name = $cs_name->os_version;



                                    foreach ($productListArr as $pKey => $productList) {

                                        if ($productList['configoption1'] != '') {

                                            $getServerIdArr = Capsule::table('mod_vmware_server')->select('id')->where('server_name', $productList['configoption3'])->get();
                                            if (count($getServerIdArr) == 0)
                                                $getServerIdArr = Capsule::table('mod_vmware_server')->select('id')->where('id', $productList['configoption3'])->get();
                                            $getServerId = $getServerIdArr[0]->id;

                                            if ($guest_os_server == $getServerId) {

                                                $pid = $productList['id'];

                                                $getConfigurableGroupId = Capsule::table('tblproductconfiglinks')->where('pid', $pid)->first();

                                                $getConfigurableGroupId = $getConfigurableGroupId->gid;



                                                $configurableOsVesrionId = $vmWare->wgsvmwareGetConfigurableOptionId($getConfigurableGroupId, 'guest_os_version');

                                                if (!empty($configurableOsVesrionId))

                                                    $AddProductConfigurableSubOption = $vmWare->wgsvmwareAddUpdateConfigurableSubOption($configurableOsVesrionId, $gov_name, $cs_name, true);
                                            }
                                        }
                                    }



                                    Capsule::table('mod_vmware_os_list')->where('id', $edit_os)->update($data);

                                    $vars['upadte_success'] = 'success';
                                } catch (Exception $ex) {

                                    $vars['upadte_error'] = $ex->getMessage();
                                }



                                header('location:' . $modulelink . '&action=guest_os_list&update=true');
                            } else {

                                try {



                                    Capsule::table('mod_vmware_os_list')->insert($data);



                                    foreach ($productListArr as $pKey => $productList) {

                                        if ($productList['configoption1'] != '') {

                                            $getServerIdArr = Capsule::table('mod_vmware_server')->select('id')->where('server_name', $productList['configoption3'])->get();
                                            if (count($getServerIdArr) == 0)
                                                $getServerIdArr = Capsule::table('mod_vmware_server')->select('id')->where('id', $productList['configoption3'])->get();
                                            $getServerId = $getServerIdArr[0]->id;

                                            if ($guest_os_server == $getServerId) {

                                                $pid = $productList['id'];

                                                $getConfigurableGroupId = Capsule::table('tblproductconfiglinks')->where('pid', $pid)->first();

                                                $getConfigurableGroupId = $getConfigurableGroupId->gid;



                                                $configurableOsVesrionId = $vmWare->wgsvmwareGetConfigurableOptionId($getConfigurableGroupId, 'guest_os_version');

                                                if (!empty($configurableOsVesrionId))

                                                    $AddProductConfigurableSubOption = $vmWare->wgsvmwareAddUpdateConfigurableSubOption($configurableOsVesrionId, $gov_name);
                                            }
                                        }
                                    }

                                    $vars['add_success'] = 'success';
                                } catch (Exception $ex) {

                                    $vars['add_error'] = $ex->getMessage();
                                }

                                header('location:' . $modulelink . '&action=guest_os_list&add=true');
                            }
                        } elseif (isset($exist)) {

                            $vars['add_error'] = $LANG['guestosversion'] . ' "' . $guest_os_version . '" already exist.';
                        }
                    }

                    $delete_os = filter_var($_GET['delete_os'], FILTER_SANITIZE_STRING);

                    if (!empty($delete_os)) {

                        try {

                            $cs_name = Capsule::table('mod_vmware_os_list')->select('os_version', 'server_id')->where('id', $delete_os)->first();

                            $cs_name = $cs_name->os_version;

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

                            Capsule::table('mod_vmware_os_list')->where('id', $delete_os)->delete();

                            $vars['delete_success'] = 'success';
                        } catch (Exception $ex) {

                            $vars['delete_error'] = $ex->getMessage();
                        }

                        header('location:' . $modulelink . '&action=guest_os_list&delete=true');
                    }

                    $edit_os = filter_var($_GET['edit_os'], FILTER_VALIDATE_INT);

                    $osData = array();

                    ?>

                    <?php

                    $add = filter_var($_GET['add'], FILTER_SANITIZE_STRING);

                    $update = filter_var($_GET['update'], FILTER_SANITIZE_STRING);

                    $delete = filter_var($_GET['delete'], FILTER_SANITIZE_STRING);

                    if (isset($vars['add_error']) && !empty($vars['add_error'])) {

                        echo '<div class="errorbox"><strong><span class="title">Error!</span></strong><br>' . $vars['add_error'] . '</div>';

                        $_GET['add'] = '';
                    }

                    if (!empty($add) && !isset($vars['add_error'])) {

                        echo '<div class="successbox">' . $LANG['addedsucceessmsg'] . '</div>';
                    } elseif (!empty($update) && !isset($vars['add_error'])) {

                        $txt = '';

                        if (isset($_SESSION['govexist'])) {

                            $cls = 'warningbox';

                            $txt = ' But "';

                            if (isset($_SESSION['govexist']))

                                $txt .= $LANG['guestosversion'] . ' "' . $_SESSION['govexist'] . '"';

                            $txt .= '" already exist.';
                        } else {

                            $cls = 'successbox';
                        }

                        unset($_SESSION['govexist']);

                        echo '<div class="' . $cls . '">' . $LANG['updatesucceessmsg'] . $txt . '</div>';
                    } elseif (!empty($delete) && !isset($vars['add_error'])) {

                        echo '<div class="successbox">' . $LANG['deletesucceessmsg'] . '</div>';
                    }

                    ?>

                    <div class="vmosmap_button">

                        <a href="<?php echo $modulelink . '&action=guest_os_list'; ?>"><button class="active"><?php echo $LANG['vmosmapiso']; ?></button></a>

                        <a href="<?php echo $modulelink . '&action=vm_templates'; ?>"><button><?php echo $LANG['vmosmapclone']; ?></button></a>

                    </div>



                    <?php if (empty($edit_os)) { ?>

                        <div class="btn_section">

                            <button onclick="jQuery('#os_form').fadeToggle(1000);" id="addOS"><?php echo $LANG['addos']; ?></button>

                        </div>

                    <?php

                    } else {

                        $osData = Capsule::table('mod_vmware_os_list')->where('id', $edit_os)->get();

                        if (count($osData) > 0)
                            $osData = (array) $osData[0];
                        else
                            $osData = [];
                    }



                    $serverData = Capsule::table('mod_vmware_server')->get();

                    ?>

                    <form action="" method="post" id="os_form" style="display:<?php if (empty($edit_os)) { ?>none<?php } ?>;">



                        <input type="hidden" value="<?php echo $osData['datacenter']; ?>" id="get_os_listDatacenterval">

                        <input type="hidden" value="<?php echo $osData['hostname']; ?>" id="get_os_list_hostname">

                        <!-- <input type="hidden" value="<?php echo $osData['resourcepool']; ?>" id="get_os_list_resourcepool"> -->

                        <!-- <input type="hidden" value="<?php echo $osData['network_adp']; ?>" id="getNetworkval"> -->

                        <input type="hidden" id="getDatastoreval" value="<?php echo $osData['datastore']; ?>">

                        <input type="hidden" id="getisoval" value="<?php echo $osData['isofile']; ?>">

                        <input type="hidden" id="getosversionval" value="<?php echo $osData['os_version']; ?>">

                        <table class="form partner-form" width="100%" border="0" cellspacing="2" cellpadding="3">

                            <tbody>

                                <tr>

                                    <td width="20%" class="fieldlabel">

                                        <?php echo $LANG['selectserver']; ?>

                                    </td>

                                    <td width="80%" class="fieldarea">

                                        <select name="guest_os_server" id="guest_os_server" class="form-control select-inline" required="">

                                            <option disable="disable" value="">Select</option>

                                            <?php foreach ($serverData as $serverData01) { ?>

                                                <option value="<?php echo $serverData01->id; ?>" <?php

                                                                                                    if ($osData['server_id'] == $serverData01->id) {

                                                                                                        echo 'selected="selected"';
                                                                                                    }

                                                                                                    ?>><?php echo $serverData01->server_name; ?></option>

                                            <?php }

                                            ?>

                                        </select>

                                    </td>

                                </tr>

                                <tr>

                                    <td width="20%" class="fieldlabel">

                                        <?php echo $LANG['datacenter']; ?>

                                    </td>

                                    <td width="80%" class="fieldarea">

                                        <select name="datacenter" id="os_list_datacenter" required="" link="<?php echo $modulelink; ?>">

                                            <option value="">Select</option>

                                        </select>

                                    </td>

                                </tr>

                                <tr>

                                    <td width="20%" class="fieldlabel">

                                        <?php echo $LANG['os_list_hostname']; ?>

                                    </td>

                                    <td width="80%" class="fieldarea">

                                        <select name="hostname" id="os_list_hostname" required="" link="<?php echo $modulelink; ?>">

                                            <option value="">Select</option>

                                        </select>

                                    </td>

                                </tr>

                                <!-- <tr>

                                    <td width="20%" class="fieldlabel">

                                        <?php echo $LANG['os_list_resourcepool']; ?>

                                    </td>

                                    <td width="80%" class="fieldarea">

                                        <select name="resource_pool" id="os_list_resourcepool" required="" link="<?php echo $modulelink; ?>">

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

                                <tr>

                                    <td class="fieldlabel" width="20%">

                                        <?php echo $LANG['guestosdatastore']; ?>

                                    </td>

                                    <td width="80%" class="fieldarea">

                                        <select name="datastore" class="form-control select-inline" required="" link="<?php echo $modulelink; ?>" id="guest_os_datastore">

                                            <option value="">Select</option>

                                        </select>

                                        <br /><span style=""> <?php echo $LANG['datastore_desc']; ?> </span>

                                    </td>



                                </tr>

                                <tr>

                                    <td width="20%" class="fieldlabel">

                                        <?php echo $LANG['guestosfamily']; ?>

                                    </td>

                                    <td width="80%" class="fieldarea">

                                        <select name="guest_os_family" id="guest_os_family" class="form-control select-inline" required="" link="<?php echo $modulelink; ?>">

                                            <option value="Windows" <?php

                                                                    if (!empty($osData) && $osData['os_family'] == 'Windows') {

                                                                        echo 'selected="selected"';
                                                                    }

                                                                    ?>>Windows</option>

                                            <option value="Linux" <?php

                                                                    if (!empty($osData) && $osData['os_family'] == 'Linux') {

                                                                        echo 'selected="selected"';
                                                                    }

                                                                    ?>>Linux</option>

                                            <option value="Others" <?php

                                                                    if (!empty($osData) && $osData['os_family'] == 'Others') {

                                                                        echo 'selected="selected"';
                                                                    }

                                                                    ?>>Others</option>

                                        </select>

                                    </td>

                                </tr>

                                <tr>

                                    <td class="fieldlabel" width="20%">

                                        <?php echo $LANG['guestosversion']; ?>

                                    </td>

                                    <td width="80%" class="fieldarea">

                                        <select name="guest_os_version" id="guest_os_version" required="">

                                            <option value="">Select</option>

                                        </select><br>

                                        <a href="javascript:void(0);" onclick="jQuery('#ospopup').toggle();"><?php echo $LANG['vmaddmoreosversion']; ?></a>

                                        <div class="ospopup" id="ospopup" style="display: none;">

                                            <div class="ospopupbody">

                                                <a href="javascript:void(0)" onclick="jQuery('#ospopup').toggle();" style="background: #000;font-size: 14px;margin: 0;padding: 5px;position: absolute;right: -20px;top: -29px;font-weight: 600;color: #fff;width: 20px;height: 20px;line-height: 7px;cursor: pointer;text-decoration: none;border-radius: 13px;border: 1px solid #fff;" title="Close">X</a>

                                                <?php

                                                global $CONFIG;

                                                $CONFIG['SystemURL'];

                                                ?>

                                                <p><?php echo $LANG['guestaddmoreoslinkdesc']; ?>&nbsp;

                                                    <a target="_blank" href="http://www.virtualnebula.com/blog/2014/12/3/vcac-guest-os-ids-vmwarevirtualcenteroperatingsystem"><?php echo $LANG['guestaddmoreoslinkdesclinktext']; ?></a>

                                                    &nbsp;<?php echo $LANG['guestaddmoreoslinkdesc1']; ?>

                                                </p>

                                                <p><?php echo $LANG['guestaddmoreosdesc']; ?></p>

                                                <h3><?php echo $LANG['guestaddmoreosinstruction']; ?></h3>

                                                <p><?php echo $LANG['guestaddmoreosinstructiondesc']; ?></p>

                                            </div>

                                        </div>

                                        <!--href="http://www.virtualnebula.com/blog/2014/12/3/vcac-guest-os-ids-vmwarevirtualcenteroperatingsystem" target="_blank"-->

                                        <!--<a href="https://www.vmware.com/support/developer/converter-sdk/conv43_apireference/vim.vm.GuestOsDescriptor.GuestOsIdentifier.html" target="_blank"><?php echo $LANG['vmaddmoreosversion']; ?></a>-->

                                        <!--                                        <input type="text" size="30" name="guest_os_version" id="guest_os_version" value="<?php

                                                                                                                                                                        if (!empty($osData)) {

                                                                                                                                                                            echo $osData['os_version'];
                                                                                                                                                                        }

                                                                                                                                                                        ?>" required="">-->

                                    </td>

                                </tr>

                                <tr>

                                    <td width="20%" class="fieldlabel">

                                        <?php echo $LANG['guestosisofile']; ?>

                                    </td>

                                    <td width="80%" class="fieldarea">

                                        <select name="guest_os_iso" class="form-control select-inline" required="" link="<?php echo $modulelink; ?>" id="guest_os_iso">

                                            <option value="">Select</option>

                                        </select>



                                    </td>



                                </tr>



                                <tr>

                                    <td width="20%" class="fieldlabel">

                                        <?php echo $LANG['guestosversionid']; ?>

                                    </td>

                                    <td width="80%" class="fieldarea">

                                        <input type="text" size="30" name="guest_os_version_id" id="guest_os_version_id" value="<?php

                                                                                                                                if (!empty($osData)) {

                                                                                                                                    echo $osData['os_version_id'];
                                                                                                                                }

                                                                                                                                ?>" required="" readonly="">

                                    </td>

                                </tr>

                                <tr>

                                    <td width="20%" class="fieldlabel">

                                        <?php echo $LANG['hidefromorderform']; ?>

                                    </td>

                                    <td width="80%" class="fieldarea">

                                        <input type="checkbox" name="show_on_order_form" <?php

                                                                                            if (!empty($osData) && $osData['status'] == '1') {

                                                                                                echo 'checked="checked"';
                                                                                            }

                                                                                            ?> value="1">

                                    </td>





                                </tr>

                                <tr>

                                    <td colspan="100%">

                                        <div class="form_btn">

                                            <input type="submit" name="create_client" value="<?php echo $LANG['submit']; ?>">

                                            &nbsp;&nbsp;

                                            <?php

                                            if (!empty($edit_os)) {

                                            ?>

                                                <a href="<?php echo $modulelink; ?>&action=guest_os_list"><input type="button" name="cancel" value="<?php echo $LANG['cancel']; ?>"></a>

                                            <?php

                                            } else {

                                            ?>

                                                <input type="reset" onclick="jQuery('#os_form').fadeOut(1000);" value="<?php echo $LANG['cancel']; ?>">

                                            <?php

                                            }

                                            ?>

                                        </div>

                                    </td>

                                </tr>

                            </tbody>

                        </table>



                    </form>

                    <?php if (empty($edit_os)) { ?>

                        <form action="" method="post">

                            <span>Filter OS Family: </span>

                            <select name="osfamily" onchange="submit()">

                                <option value="">Select OS Family</option>

                                <option value="Windows" <?php if ($osfamily == 'Windows') echo 'selected="selected"'; ?>>Windows</option>

                                <option value="Linux" <?php if ($osfamily == 'Linux') echo 'selected="selected"'; ?>>Linux</option>

                                <option value="Others" <?php if ($osfamily == 'Others') echo 'selected="selected"'; ?>>Others</option>

                            </select>

                        </form>

                        <div class="tablebg">

                            <table id="sortabletbl0" class="datatable partnerdetail_table" width="100%" border="0" cellspacing="1" cellpadding="3">

                                <tbody>

                                    <tr>

                                        <th width="10%"><?php echo $LANG['guestosserver']; ?></th>

                                        <th width="10%"><?php echo $LANG['guestosfamily']; ?></th>

                                        <!--<th width="15%"><?php echo $LANG['guestosversionid']; ?></th>-->

                                        <th width="12%"><?php echo $LANG['guestosversion']; ?></th>

                                        <th width="13%"><?php echo $LANG['os_list_hostname']; ?></th>

                                        <th width="12%"><?php echo $LANG['datacenter']; ?></th>

                                        <!--<th width="20%"><?php echo $LANG['guestosisofile']; ?></th>-->

                                        <th width="10%"><?php echo $LANG['guestosdatastore']; ?></th>

                                        <th width="7%"><?php echo $LANG['hide']; ?></th>

                                        <th width="10%"><?php echo $LANG['action']; ?></th>

                                    </tr>

                                    <?php

                                    if ($osfamily != '')

                                        $osQuery = Capsule::table('mod_vmware_os_list')->where('os_family', $osfamily)->get();

                                    else

                                        $osQuery = Capsule::table('mod_vmware_os_list')->get();



                                    foreach ($osQuery as $osResult01) {

                                        $osResult = (array) $osResult01;



                                        $servername = Capsule::table('mod_vmware_server')->where('id', $osResult['server_id'])->get();

                                    ?>

                                        <tr>

                                            <td width="10%"><?php echo $servername[0]->server_name; ?></td>

                                            <td width="10%"><?php echo $osResult['os_family']; ?></td>

                                            <!--<td width="15%"><?php echo $osResult['os_version_id']; ?></td>-->

                                            <td width="12%"><?php echo $osResult['os_version']; ?></td>

                                            <td width="13%"><?php echo $osResult['hostname']; ?></td>

                                            <td width="12%"><?php echo $osResult['datacenter']; ?></td>

                                            <!--<td width="20%"><?php echo $osResult['isofile']; ?></td>-->

                                            <td width="10%"><?php echo $osResult['datastore']; ?></td>

                                            <td width="7%"><?php

                                                            if ($osResult['status'] == '1') {

                                                                echo 'ON';
                                                            }

                                                            ?></td>

                                            <td width="10%">

                                                <!--<i class="fa fa-eye" aria-hidden="true" onclick="viewDetail(obj, );"></i>-->

                                                <a href="<?php echo $modulelink; ?>&action=guest_os_list&edit_os=<?php echo $osResult['id']; ?>">

                                                    <img src="images/edit.gif" width="16" height="16" border="0" alt="Edit"></a>

                                                &nbsp;

                                                <a href="<?php echo $modulelink; ?>&action=guest_os_list&delete_os=<?php echo $osResult['id']; ?>" onclick="return confirm('Are you sure delete this row ?');">

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