<?php

use Illuminate\Database\Capsule\Manager as Capsule;

global $whmcs;

Capsule::Schema()->table('mod_vmware_ip_list', function ($table) {
    if (!Capsule::Schema()->hasColumn('mod_vmware_ip_list', 'mac_status'))
        $table->integer('mac_status');
    if (!Capsule::Schema()->hasColumn('mod_vmware_ip_list', 'desc'))
        $table->string('desc', '255')->nullable();
});
$create_ip = filter_var($_POST['create_ip'], FILTER_SANITIZE_STRING);

$edit_ip = filter_var($_GET['edit_ip'], FILTER_SANITIZE_STRING);

if (!empty($create_ip)) {
    $ipfrom = filter_var($_POST['ipfrom'], FILTER_SANITIZE_STRING);
    $ipfrom = trim(preg_replace('/\s+/', '', $ipfrom));
    $ipto = filter_var($_POST['ipto'], FILTER_SANITIZE_STRING);

    $ip = filter_var($_POST['ip'], FILTER_SANITIZE_STRING);
    $macaddress = filter_var($_POST['macaddress'], FILTER_SANITIZE_STRING);
    $ipArr = array();
    $ippost = true;
    if ($_POST['range'] == 'on') {
        $ipArr = explode(PHP_EOL, $ip);
        if (empty($ip)) {
            $ippost = false;
        }
    } elseif (!empty($ipfrom) && !empty($ipto)) {
        if (empty($ipfrom) || empty($ipto)) {
            $ippost = false;
        }
        $fromip = explode('.', $ipfrom);
        $lastkey = (count($fromip) - 1);
        $start = $fromip[$lastkey];
        $toip = explode('.', $ipto);
        $end = $toip[$lastkey];

        for ($i = $start; $i <= $end; $i++) {
            $fromip[$lastkey] = $i;
            $ipfrom = implode('.', $fromip);
            array_push($ipArr, $ipfrom);
        }
    } else
        $error = 'IP from and IP To are required.';
    $macArr = explode(PHP_EOL, $macaddress);
    $macCount = count($macArr);
    if (isset($_GET['edit_ip'])) {
        $ipArr = explode(PHP_EOL, $ip);
    }
    if (empty($_POST['datacenter']))
        $error = 'Datacenter is required.';
    elseif (!$ippost)
        $error = 'Ip is required.';
    elseif (empty($_POST['netmask']))
        $error = 'Netmask is required.';
    elseif (empty($_POST['gateway']))
        $error = 'Gateway is required.';
    elseif (empty($_POST['dns']))
        $error = 'DNS is required.';
    else {
        foreach ($ipArr as $key => $ip) {

            $macname = "";
            if ($macCount >= $key) {
                $macname = $macArr[$key];
            }
            if (isset($_GET['edit_ip']) && empty($macArr)) {
                $macname = '';
            }

            $table = 'mod_vmware_ip_list';
            $ip_row = Capsule::table($table)->where('ip', $ip)->count();
            $server = filter_var($_POST['server'], FILTER_SANITIZE_STRING);
            $server_row = Capsule::table($table)->where('server', $server)->count();

            $datacenter = filter_var($_POST['datacenter'], FILTER_SANITIZE_STRING);
            $netmask = filter_var($_POST['netmask'], FILTER_SANITIZE_STRING);
            $gateway = filter_var($_POST['gateway'], FILTER_SANITIZE_STRING);
            $dns = filter_var($_POST['dns'], FILTER_SANITIZE_STRING);
            $vmserver = filter_var($_POST['vmserver'], FILTER_SANITIZE_STRING);
            $hostname = filter_var($_POST['hostname'], FILTER_SANITIZE_STRING);
            $location = filter_var($_POST['location'], FILTER_SANITIZE_STRING);
            $desc = $whmcs->get_req_var('desc');
            $values = [
                'datacenter' => $datacenter,
                'ip' => trim(preg_replace('/\s+/', '', $ip)),
                'netmask' => $netmask,
                'gateway' => $gateway,
                'dns' => $dns,
                'macaddress' => trim(preg_replace('/\s+/', '', $macname)),
                'server_id' => $vmserver,
                'hostname' => $hostname,
                'location' => $location,
                'desc' => $desc
            ];
            if (isset($_GET['edit_ip']) && !empty($_GET['edit_ip']) && isset($_POST['free']) && $_POST['free'] == 'on') {
                $values = array_merge($values, ['status' => '0', 'forvm' => '']);
            }
            
            if (isset($_POST['reserved']) && $_POST['reserved'] == 'on') {
                $values = array_merge($values, ['status' => '3']);
            }

            if ($macname == "" && !isset($_GET['edit_ip'])) {
                unset($values['macaddress']);
            }

            if (empty($values['macaddress'])) {
                $values = array_merge($values, ['mac_status' => '0']);
            } elseif (!empty($values['macaddress'])) {
                $values = array_merge($values, ['mac_status' => '1']);
            }

            if (isset($_GET['edit_ip'])) {
                if ($ip_row > 1) {
                    unset($values['ip']);
                }
            }

            if (isset($_GET['edit_ip'])) {
                try {

                    Capsule::table($table)->where('id', $edit_ip)->update($values);
                    header('location:' . $modulelink . '&action=ip_map&update=true');
                } catch (Exception $ex) {
                    logActivity("Could't update table mod_vmware_ip_list error: " . $ex->getMessage());
                }
            } else {
                if ($ip_row < 1) {
                    Capsule::table($table)->insert($values);
                    header('location:' . $modulelink . '&action=ip_map&add=true');
                } else {
                    $error = "IP is already added!";
                }
            }
        }
    }
}
$delete_ip = filter_var($_GET['delete_ip'], FILTER_SANITIZE_STRING);

$deletemultiple = filter_var($_POST['deletemultiple'], FILTER_SANITIZE_STRING);

$ip_id = $whmcs->get_req_var('ip_id');
if (!empty($delete_ip)) {
    Capsule::table('mod_vmware_ip_list')->where('id', $_GET['delete_ip'])->delete();
    header('location:' . $modulelink . '&action=ip_map&delete=true');
}
if (!empty($deletemultiple)) {
    if (!empty($ip_id)) {
        foreach ($ip_id as $id) {
            $count = Capsule::table('mod_vmware_ip_list')->where('id', $id)->count();
            if ($count > 0) {
                Capsule::table('mod_vmware_ip_list')->where('id', $id)->delete();
                $_GET['delete'] = 'true';
            }
        }
    }
}

$freemultiple = filter_var($_POST['freemultiple'], FILTER_SANITIZE_STRING);
if (isset($_POST['freemultiple']) && !empty($freemultiple)) {
    $freeip_id = $whmcs->get_req_var('freeip_id');
    if (!empty($freeip_id)) {
        foreach ($freeip_id as $id) {
            $getVm = Capsule::table('mod_vmware_ip_list')->where('id', $id)->first();
            if (count($getVm) > 0) {
                if($getVm->forvm != ""){
                    Capsule::table('mod_vmware_ip_list')
                        ->where('forvm', $getVm->forvm)
                        ->update(
                            [
                                'status' => '0',
                                'forvm' => '',
                            ]
                        );
                }else{
                    Capsule::table('mod_vmware_ip_list')
                        ->where('id', $id)
                        ->update(
                            [
                                'status' => '0',
                                'forvm' => '',
                            ]
                        );
                }
                $_GET['markfree'] = 'true';
            }
        }
    }
}

$reservemultiple = $whmcs->get_req_var('reservemultiple');
if (isset($_POST['reservemultiple']) && !empty($reservemultiple) && $reservemultiple == "Reserved") {
    if (!empty($ip_id)) {
        foreach ($ip_id as $id) {
            $count = Capsule::table('mod_vmware_ip_list')->where('id', $id)->count();
            if ($count > 0) {
                Capsule::table('mod_vmware_ip_list')->where('id', $id)
                ->update(
                    [
                        'status' => '3',
                    ]
                );
                $_GET['reservefree'] = 'true';
            }
        }
    }
}
?>
<script>
    var modulelink = "<?php echo $modulelink; ?>";
</script>
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
                        <h3><?php echo $LANG['ipmapping']; ?></h3>
                        <p>
                            <?php echo $LANG['vmipmapdesc']; ?>&nbsp;
                        </p>
                    </div>
                    <?php
                    if (isset($error))
                        echo '<div class="errorbox"><strong><span class="title">Error:</span></strong><br>' . $error . '!</div>';
                    elseif (isset($_GET['add']) && $_GET['add'] == 'true')
                        echo '<div class="successbox"><strong><span class="title">Changes Saved Successfully!</span></strong><br>Your IP have been added.</div>';
                    elseif (isset($_GET['update']) && $_GET['update'] == 'true')
                        echo '<div class="successbox"><strong><span class="title">Changes Saved Successfully!</span></strong><br>Your IP have been saved.</div>';
                    elseif (isset($_GET['delete']) && $_GET['delete'] == 'true')
                        echo '<div class="successbox"><strong><span class="title">Changes Saved Successfully!</span></strong><br>Your IP have been deleted.</div>';
                    elseif (isset($_GET['markfree']) && $_GET['markfree'] == 'true')
                        echo '<div class="successbox"><strong><span class="title">Changes Saved Successfully!</span></strong><br>Your IP have been marked as free.</div>';
                    elseif (isset($_GET['reservefree']) && $_GET['reservefree'] == 'true')
                        echo '<div class="successbox"><strong><span class="title">Changes Saved Successfully!</span></strong><br>Your IP have been marked as reserved.</div>';
                    ?>
                    <?php $ipData = array(); ?>
                    <?php if (!isset($_GET['edit_ip']) && (!isset($_GET['datacenter']) || empty($_GET['datacenter']))) { ?>
                        <div class="btn_section">
                            <button onclick="jQuery('#ip_form').fadeToggle(1000);">Add IP</button>
                        </div>
                    <?php
                    } else {
                        $ipData01 = Capsule::table('mod_vmware_ip_list')->where('id', $edit_ip)->get();
                        if (count($ipData01) > 0)
                            $ipData = (array) $ipData01[0];
                        else
                            $ipData01 = [];
                    }

                    $serverData = Capsule::table('mod_vmware_server')->get();
                    ?>

                    <?php if (!isset($_GET['datacenter']) || empty($_GET['datacenter'])) { ?>
                        <form action="" method="post" id="ip_form" style="display:<?php if (!isset($_GET['edit_ip'])) { ?>none<?php } ?>;">
                            <?php if (isset($_GET['edit_ip'])) { ?>
                                <input type="hidden" value="<?php echo $ipData['hostname']; ?>" id="get_os_list_hostname">
                            <?php } ?>
                            <table class="form partner-form " width="100%" border="0" cellspacing="2" cellpadding="3">
                                <tbody>
                                    <tr>
                                        <td width="15%" class="fieldlabel">
                                            <?php echo $LANG['selectserver']; ?>
                                        </td>
                                        <td class="fieldarea">
                                            <select name="vmserver" id="vmserver" class="form-control select-inline" required="" link="<?php echo $modulelink; ?>">
                                                <option disable='disable' value=''>Select</option>
                                                <?php foreach ($serverData as $serverData01) { ?>
                                                    <option value="<?php echo $serverData01->id; ?>" <?php
                                                                                                        if ($ipData['server_id'] == $serverData01->id) {
                                                                                                            echo 'selected="selected"';
                                                                                                        }
                                                                                                        ?>><?php echo $serverData01->server_name; ?></option>
                                                <?php }
                                                ?>
                                            </select>
                                        </td>
                                        <td width="15%" class="fieldlabel">
                                            <?php echo $LANG['datacenter']; ?>
                                        </td>
                                        <td class="fieldarea">
                                            <input type="hidden" id="getDatacenterval" value="<?php echo $ipData['datacenter']; ?>">
                                            <select name="datacenter" id="datacenter" required>
                                                <option disable="disable" value="">Select</option>
                                            </select>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td width="15%" class="fieldlabel">
                                            <?php echo $LANG['os_list_hostname']; ?>
                                        </td>
                                        <td width="80%" class="fieldarea">
                                            <select name="hostname" did="ip" class="form-control select-inline" id="os_list_hostname" required="" link="<?php echo $modulelink; ?>">
                                                <option value="">Select</option>
                                            </select>
                                        </td>
                                        <td width="15%" class="fieldlabel">
                                            <?php echo $LANG['os_list_location']; ?>
                                        </td>
                                        <td width="80%" class="fieldarea">
                                            <?php
                                            $app = '';
                                            if (!empty($ipData)) {
                                                $app = $ipData['location'];
                                            }
                                            ?>
                                            <select name="location" did="ip" class="form-control select-inline" id="os_list_location" required="">
                                                <option value="none" <?php echo ($app == 'none') ? 'selected="selected"' : ''; ?>><?php echo $LANG['appnone']; ?></option>
                                                <option value="ovh" <?php echo ($app == 'ovh') ? 'selected="selected"' : ''; ?>><?php echo $LANG['app_ovh']; ?></option>
                                                <option value="soyoustart" <?php echo ($app == 'soyoustart') ? 'selected="selected"' : ''; ?>><?php echo $LANG['app_soyoustart']; ?></option>
                                            </select>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="fieldlabel" width="15%">
                                            <?php if (!isset($_GET['edit_ip'])) { ?>
                                                <label for="range"><?php echo $LANG['rangetxt']; ?></label>
                                            <?php } ?>
                                        </td>
                                        <td class="fieldarea">
                                            <?php if (!isset($_GET['edit_ip'])) { ?>
                                                <input type="checkbox" name="range" id="range" onclick="iptypeChange(this);">
                                            <?php } ?>
                                        </td>
                                        <td class="fieldlabel">
                                            <?php echo $LANG['ip']; ?>
                                        </td>
                                        <td class="fieldarea">
                                            <?php if (!isset($_GET['edit_ip'])) { ?>
                                                <div id="textarea" style="display:none;"><textarea name="ip" cols="28" rows="3"><?php
                                                                                                                                if (!empty($ipData)) {
                                                                                                                                    //echo $ipData['ip'];
                                                                                                                                }
                                                                                                                                ?></textarea><br><span>Enter IP with new line.</span></div>
                                                <div id="rangeinput">
                                                    <input type="text" name="ipfrom" placeholder="127.0.0.1"><br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;To<br>
                                                    <input type="text" name="ipto" placeholder="127.0.0.10">
                                                </div>
                                            <?php } else { ?>
                                                <input type="text" size="30" name="ip" id="ip" value="<?php
                                                                                                        if (!empty($ipData)) {
                                                                                                            echo $ipData['ip'];
                                                                                                        }
                                                                                                        ?>" required="">
                                            <?php } ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="fieldlabel">
                                            <?php echo $LANG['macaddress']; ?>
                                        </td>
                                        <td class="fieldarea">
                                            <?php if (!isset($_GET['edit_ip'])) { ?>
                                                <textarea name="macaddress" cols="28" rows="3"><?php
                                                                                                if (!empty($ipData)) {
                                                                                                    //echo $ipData['macaddress'];
                                                                                                }
                                                                                                ?></textarea><br><span>Enter Mac Address with new line.</span>
                                            <?php } else { ?>
                                                <input type="text" size="30" name="macaddress" id="macaddress" value="<?php
                                                                                                                        if (!empty($ipData)) {
                                                                                                                            echo $ipData['macaddress'];
                                                                                                                        }
                                                                                                                        ?>">
                                            <?php } ?>
                                        </td>
                                        <td class="fieldlabel">
                                            <?php echo $LANG['gateway']; ?>
                                        </td>
                                        <td class="fieldarea">
                                            <input type="text" size="30" name="gateway" id="gateway" value="<?php
                                                                                                            if (!empty($ipData)) {
                                                                                                                echo $ipData['gateway'];
                                                                                                            }
                                                                                                            ?>" required="" placeholder="192.100.0.1">
                                        </td>

                                    </tr>
                                    <tr>
                                        <td class="fieldlabel">
                                            <?php echo $LANG['dns']; ?>
                                        </td>
                                        <td class="fieldarea">
                                            <input type="text" size="30" name="dns" id="dns" value="<?php
                                                                                                    if (!empty($ipData)) {
                                                                                                        echo $ipData['dns'];
                                                                                                    }
                                                                                                    ?>" required="" placeholder="8.8.8.8">
                                        </td>
                                        <td class="fieldlabel">
                                            <?php echo $LANG['netmask']; ?>
                                        </td>
                                        <td class="fieldarea">
                                            <input type="text" size="30" name="netmask" id="netmask" value="<?php
                                                                                                            if (!empty($ipData)) {
                                                                                                                echo $ipData['netmask'];
                                                                                                            }
                                                                                                            ?>" required="" placeholder="255.255.255.0">
                                        </td>

                                    </tr>
                                    <tr>
                                        <td class="fieldlabel">
                                            <?php echo $LANG['markasfree']; ?>
                                        </td>
                                        <td class="fieldarea">
                                            <input type="checkbox" name="free" id="free">
                                        </td>
                                        <td class="fieldlabel">
                                            <?php echo $LANG['reserveseclected']; ?>
                                        </td>
                                        <td class="fieldarea">
                                            <input type="checkbox" name="reserved" id="reserved">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="fieldlabel">
                                            <?php echo $LANG['ipdescrition']; ?>
                                        </td>
                                        <td class="fieldarea">
                                            <textarea name="desc" placeholder="<?php echo $LANG['ipdescriptiontext'];?>"></textarea>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="100%">
                                            <div class="form_btn">
                                                <input type="submit" name="create_ip" value="<?php echo $LANG['submit']; ?>">
                                                &nbsp;&nbsp;
                                                <?php
                                                if (isset($_GET['edit_ip'])) {
                                                ?>
                                                    <a href="<?php echo $modulelink; ?>&action=ip_map"><input type="button" name="cancel" value="<?php echo $LANG['cancel']; ?>"></a>
                                                <?php
                                                } else {
                                                ?>
                                                    <input type="reset" onclick="jQuery('#ip_form').fadeOut(1000);" value="<?php echo $LANG['cancel']; ?>">
                                                <?php
                                                }
                                                ?>

                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </form>
                    <?php }
                    if (!isset($_GET['edit_ip'])) { ?>
                        <?php if (!isset($_GET['datacenter']) || empty($_GET['datacenter'])) { ?>
                            <div class="tablebg">
                                <table id="sortabletbl0" class="datatable partnerdetail_table" width="100%" border="0" cellspacing="1" cellpadding="3">
                                    <tbody>
                                        <tr>
                                            <th><?php echo $LANG['guestosserver']; ?></th>
                                            <th><?php echo $LANG['datacenter']; ?></th>
                                            <th><?php echo $LANG['os_list_hostname']; ?></th>
                                            <th><?php echo $LANG['noofips']; ?></th>
                                            <th><?php echo $LANG['assignedip']; ?></th>
                                            <th><?php echo $LANG['reservededip']; ?></th>
                                            <th><?php echo $LANG['freeip']; ?></th>
                                        </tr>
                                        <tr>
                                            <?php
                                            $getServer = Capsule::select('select * from mod_vmware_ip_list group by server_id, datacenter, hostname');

                                            $getIpListArr = array();

                                            foreach ($getServer as $key => $server) {
                                                $server = (array) $server;

                                                if (empty($server['hostname']))
                                                    $server['hostname'] = '';
                                                $totalIps = Capsule::table('mod_vmware_ip_list')->where('datacenter', $server['datacenter'])->where('hostname', $server['hostname'])->where('server_id', $server['server_id'])->count();
                                                $freeIps = Capsule::table('mod_vmware_ip_list')->where('datacenter', $server['datacenter'])->where('status', 0)->where('hostname', $server['hostname'])->where('server_id', $server['server_id'])->count();
                                                $assignIps = Capsule::table('mod_vmware_ip_list')->where('datacenter', $server['datacenter'])->where('status', 1)->where('hostname', $server['hostname'])->where('server_id', $server['server_id'])->count();
                                                $addtionalIps = Capsule::table('mod_vmware_ip_list')->where('datacenter', $server['datacenter'])->where('status', 2)->where('hostname', $server['hostname'])->where('server_id', $server['server_id'])->count();
                                                $reservedIps = Capsule::table('mod_vmware_ip_list')->where('datacenter', $server['datacenter'])->where('status', 3)->where('hostname', $server['hostname'])->where('server_id', $server['server_id'])->count();

                                                $getIpListArr[$key] = array('total' => $totalIps, 'free' => $freeIps, 'assign' => $assignIps + $addtionalIps, 'reserved' => $reservedIps, 'server_id' => $server['server_id'], 'hostname' => $server['hostname'], 'datacenter' => $server['datacenter']);
                                            }

                                            foreach ($getIpListArr as $key => $value) {
                                            ?>
                                        <tr>
                                            <td>
                                                <?php
                                                $servername = Capsule::table('mod_vmware_server')->where('id', $value['server_id'])->get();
                                                echo $servername[0]->server_name;
                                                ?></td>
                                            <td><a href="<?php echo $modulelink; ?>&action=ip_map&datacenter=<?php echo $value['datacenter']; ?>&server_id=<?php echo $value['server_id']; ?>"><?php echo $value['datacenter']; ?></a></td>
                                            <td>
                                                <?php
                                                if (!empty($value['hostname'])) {
                                                ?>
                                                    <a href="<?php echo $modulelink; ?>&action=ip_map&datacenter=<?php echo $value['datacenter']; ?>&hostname=<?php echo $value['hostname']; ?>&server_id=<?php echo $value['server_id']; ?>"><?php echo $value['hostname']; ?></a>
                                                <?php } ?>
                                            </td>
                                            <td><?php echo $value['total']; ?></td>
                                            <td><?php echo $value['assign']; ?></td>
                                            <td><?php echo $value['reserved']; ?></td>
                                            <td><?php echo $value['free']; ?></td>
                                        </tr>
                                    <?php
                                            }
                                    ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php }
                        if (isset($_GET['datacenter']) && !empty($_GET['datacenter'])) { ?>
                            <p style="float: left;width: 100%;">
                                <?php
                                $servername = Capsule::table('mod_vmware_server')->where('id', filter_var($_GET['server_id'], FILTER_SANITIZE_STRING))->get();
                                ?>
                                <b style="float: left;width: 50%;"><?php echo $LANG['guestosserver']; ?>: <?php echo $servername[0]->server_name; ?></b><br />
                                &nbsp;&nbsp;
                                <b style="float: left;width: 50%;"><?php echo $LANG['datacenter']; ?>: <?php echo filter_var($_GET['datacenter'], FILTER_SANITIZE_STRING); ?></b><br />
                                <?php if (isset($_GET['hostname']) && !empty($_GET['hostname'])) { ?>
                                    &nbsp;&nbsp;
                                    <b style="float: left;width: 50%;"><?php echo $LANG['os_list_hostname']; ?>: <?php
                                                                                                                    echo
                                                                                                                    filter_var($_GET['hostname'], FILTER_SANITIZE_STRING);
                                                                                                                    ?></b><br />
                                <?php } ?>
                                <a href="<?php echo $modulelink; ?>&action=ip_map" style="text-align: right;float: right;width: 50%;font-size: 16px;">« <?php echo $LANG['back']; ?></a>
                            </p>
                            <form method="post" action="" id="deleteMulitpleIps">
                                <input type="hidden" name="deletemultiple" value="<?php echo $LANG['deleteseclected']; ?>">
                                <div class="tablebg">
                                    <table id="iptable" class="datatable partnerdetail_table table table-striped table-bordered" cellspacing="0" width="100%" style="word-wrap:break-word; table-layout: fixed;">
                                        <thead>
                                            <tr>
                                                <th width="3%"><input type="checkbox" onclick="enableAllCheckbox(this);"></th>
                                                <th width="11%"><?php echo $LANG['forvm']; ?></th>
                                                <th width="9%"><?php echo $LANG['ip']; ?></th>
                                                <th width="12%"><?php echo $LANG['reversedns']; ?></th>
                                                <th width="8%"><?php echo $LANG['netmask']; ?></th>
                                                <th width="8%"><?php echo $LANG['gateway']; ?></th>
                                                <th width="8%"><?php echo $LANG['dns']; ?></th>
                                                <th width="8%"><?php echo $LANG['macaddress']; ?></th>
                                                <th width="12%"><?php echo $LANG['ipdescrition']; ?></th>
                                                <!--<th width="9%"><?php echo $LANG['datacenter']; ?></th>-->
                                                <!--<th width="10%"><?php echo $LANG['guestosserver']; ?></th>-->
                                                <!--<th width="9%"><?php echo $LANG['server']; ?></th>-->
                                                <th width="7%"><?php echo $LANG['status']; ?></th>
                                                <th width="7%"><?php echo $LANG['action']; ?></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $server_id = filter_var($_GET['server_id'], FILTER_SANITIZE_STRING);
                                            $datacenter = filter_var($_GET['datacenter'], FILTER_SANITIZE_STRING);
                                            $hostname = filter_var($_GET['hostname'], FILTER_SANITIZE_STRING);
                                            //                                    $ipQuery = Capsule::table('mod_vmware_ip_list')->where('status', 0)->where('status', 1)->get();
                                            if (!empty($hostname))
                                                $ipQuery = Capsule::select("select * from mod_vmware_ip_list where datacenter = '" . $datacenter . "' and hostname = '" . $hostname . "' and server_id = '" . $server_id . "' and (status = '0' or status = '1' or status = '3') ");
                                            else
                                                $ipQuery = Capsule::select("select * from mod_vmware_ip_list where datacenter = '" . $datacenter . "' and server_id = '" . $server_id . "' and (status = '0' or status = '1' or status = '3') ");
                                            foreach ($ipQuery as $ipResult01) {
                                                $ipResult = (array) $ipResult01;
                                                //                                        if ($ipResult['status'] == 2) {
                                                $additionalArr = array();
                                                foreach (Capsule::table('mod_vmware_ip_list')->where('status', 2)->where('forvm', $ipResult['forvm'])->get() as $additional) {
                                                    $additionalArr[$ipResult['forvm']][] = $additional;
                                                    //                                            }
                                                }
                                                $additionalArr = (array) $additionalArr;

                                                $getServiceId = Capsule::table('tblhosting')->where('dedicatedip', $ipResult['ip'])->get();
                                                if (count($getServiceId) > 0 && !empty($getServiceId)) {
                                                    $link = $getServiceId[0]->userid . '&id=' . $getServiceId[0]->id;
                                                    $seriviceLink = "<a target='_blank' href='clientsservices.php?userid={$link}'>" . $ipResult['forvm'] . "</a>";
                                                } else {
                                                    $seriviceLink = $ipResult['forvm'];
                                                }
                                                if ($additionalArr[$ipResult['forvm']])
                                                    $html = '<br/><span onclick="getAdditionalIP(this,\'' . $ipResult['id'] . '\',\'' . $ipResult['forvm'] . '\',\'' . $modulelink . '\')"><i class="fa fa-plus-circle" aria-hidden="true"></i></span>';
                                                else
                                                    $html = '';
                                            ?>
                                                <tr>
                                                    <td>
                                                        <?php
                                                        if ($ipResult['status'] != 1 && $ipResult['status'] != 3 ) {
                                                        ?>
                                                            <input type="checkbox" value="<?php echo $ipResult['id']; ?>" class="multicheckip" name="ip_id[<?php echo $ipResult['id']; ?>]">
                                                        <?php
                                                        } else {
                                                        ?>
                                                            <input type="checkbox" value="<?php echo $ipResult['id']; ?>" class="multicheckip" name="freeip_id[<?php echo $ipResult['id']; ?>]">
                                                        <?php
                                                        }
                                                        ?>
                                                    </td>
                                                    <td><?php echo $seriviceLink ?><?php echo $html; ?></td>
                                                    <td><?php echo $ipResult['ip']; ?></td>
                                                    <td style="position: relative;"><input type="text" value="<?php echo $ipResult['reversedns']; ?>" onblur="updateRDNS(this,'<?php echo $ipResult['id']; ?>');"><i class="fa fa-spinner fa-pulse fa-3x fa-fw" id="spinner<?php echo $ipResult['id']; ?>" style="display:none; font-size: 12px;position: absolute;top: 14px;right: 12px;top: 25%;"></i></td>
                                                    <td><?php echo $ipResult['netmask']; ?></td>
                                                    <td><?php echo $ipResult['gateway']; ?></td>
                                                    <td><?php echo $ipResult['dns']; ?></td>
                                                    <td><?php echo $ipResult['macaddress']; ?></td>
                                                    <!--<td><?php echo $ipResult['datacenter']; ?></td>-->
                                                    <!--<td><?php echo $servername[0]->server_name; ?></td>-->
                                                    <!--<td><?php echo $ipResult['server']; ?></td>-->
                                                    <td style="position: relative;">
                                                    <textarea name="desc" onblur="updateDesc(this,'<?php echo $ipResult['id']; ?>');"><?php echo $ipResult['desc']; ?></textarea><i class="fa fa-spinner fa-pulse fa-3x fa-fw" id="descspinner<?php echo $ipResult['id']; ?>" style="display:none; font-size: 12px;position: absolute;top: 14px;right: 12px;top: 25%;"></i></td>
                                                    <td>
                                                        <?php
                                                        if ($ipResult['status'] == 3)
                                                            echo '<span class="label reserved">' . $LANG['reserved'] . '</span>';
                                                        elseif ($ipResult['status'] != 1)
                                                            echo '<span class="label inactive">' . $LANG['notassigned'] . '</span>';
                                                        else
                                                            echo '<span class="label active">' . $LANG['assigned'] . '</span>';
                                                        ?>
                                                    </td>
                                                    <td>
                                                        <a href="<?php echo $modulelink; ?>&action=ip_map&edit_ip=<?php echo $ipResult['id']; ?>">
                                                            <img src="images/edit.gif" width="16" height="16" border="0" alt="Edit"></a>

                                                        &nbsp;
                                                        <?php if ($ipResult['status'] != 1 && $ipResult['status'] != 3) {
                                                        ?>
                                                            <a href="<?php echo $modulelink; ?>&action=ip_map&delete_ip=<?php echo $ipResult['id']; ?>" onclick="return confirm('Are you sure delete this row ?');">
                                                                <img src="images/delete.gif" width="16" height="16" border="0" alt="Cancel &amp; Delete">
                                                            </a>
                                                        <?php } ?>
                                                    </td>
                                                </tr>

                                            <?php } ?>
                                        </tbody>
                                    </table>
                                </div>
                                <div class="deleteselected">
                                    <input type="button" onclick="submitDeleteIpForm(this);" value="<?php echo $LANG['deleteseclected']; ?>">
                                    &nbsp;
                                    <input type="button" onclick="submitMarkIpAsFree(this);" value="<?php echo $LANG['freeseclected']; ?>" style="background: #46a546;border: 1px solid #46a546;">
                                    &nbsp;
                                    <input type="button" onclick="submitMarkIpAsReserve(this);" value="<?php echo $LANG['reserveseclected']; ?>" style="background: #f78800;border: 1px solid #f78800;">
                                </div>
                            </form>
                    <?php
                        }
                    }
                    ?>
                </div>
                <?php
                if (file_exists(dirname(__DIR__) . '/includes/footer.php'))
                    require_once dirname(__DIR__) . '/includes/footer.php';
                ?>
            </div>


        </div>
    </div>
</div>
<div id="growls" class="default"></div>
<script>
    function iptypeChange(obj) {
        if (obj.checked) {
            jQuery("#textarea").show();
            jQuery("#rangeinput").hide();
        } else {
            jQuery("#textarea").hide();
            jQuery("#rangeinput").show();
        }

    }
</script>
<link href="https://cdn.datatables.net/1.10.12/css/jquery.dataTables.min.css" type="text/css">
<script src=https://cdn.datatables.net/1.10.12/css/jquery.dataTables.min.css"> </script>
<script src="https://cdn.datatables.net/1.10.12/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.10.12/js/dataTables.bootstrap.min.js"></script>
<script>
    jQuery(document).ready(function() {
        jQuery('#iptable').DataTable({
            "pageLength": 50
        });
    });
</script>