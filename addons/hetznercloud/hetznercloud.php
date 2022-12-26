<?php

/* * *****************************************
 *   HetznerCloud WGS provisioning Module v1.0.5
 *   Release Date: 12-06-2018
 *   Last Update : 21 Dec, 2021
 * ***************************************** */

use WHMCS\Database\Capsule;
use WHMCS\Createoptions\HetznerApi as HetznerApicall;

if (!defined("WHMCS")) {
    die("This file cannot be accessed directly");
}
if (file_exists(__DIR__ . '/class.CreateFields.php'))
    include_once __DIR__ . '/class.CreateFields.php';

if (file_exists(__DIR__ . '/license.php'))
    include_once __DIR__ . '/license.php';

function hetznercloud_MetaData()
{
    return array(
        'DisplayName' => 'Hetzner Cloud Provisioning Module',
    );
}

function hetznercloud_ConfigOptions()
{
    if (!file_exists(__DIR__ . '/license_key_file.php')) {
        $array_paramsconfig_all = array(
            'Error' => array(
                'Type' => 'system',
                'Description' => 'License file is missing. Rename license_key_file.php.rename to license.php!',
            ),
        );
        return $array_paramsconfig_all;
    }
    require __DIR__ . '/license_key_file.php';

    if (empty($license_key)) {
        $array_paramsconfig_all = array(
            'Error' => array(
                'Type' => 'system',
                'Description' => 'License key is missing. Enter license key in file ' . __DIR__ . '/license_key_file.php!',
            ),
        );
        return $array_paramsconfig_all;
    }
    global $whmcs;
    $clearField = array(
        'configoption8' => '',
        'configoption10' => '',
    );

    $pid = $whmcs->get_req_var('id');
    $moduleName = $whmcs->get_req_var('moduletype');
    $api_token_configo = Capsule::table('tblproducts')->select()->where('id', $pid)->first();
    $HetznerApicall = new HetznerApicall($api_token_configo->configoption1);
    $HetznerApicall->hetznercloud_configurableOption($pid); /* configurable option create */
    $HetznerApicall->createCustomFields($pid);
    $HetznerApicall->createEmailTemplates();
    $HetznerApicall->create_clientarea_sectionDbTable();
    createFloatingIPdatabase();
    createAddFloatingIPDatabase();
    try {
        $server_types = $HetznerApicall->get('server_types');

        logModuleCall($params['moduletype'], 'Get Server Types', null, $server_types);
        foreach ($server_types->server_types as $key => $server_types_value) {
            $servertvalue[$server_types_value->name] = $server_types_value->name . " (vCPU:" . $server_types_value->cores . ", Disk:" . $server_types_value->disk . "GB, Memory:" . $server_types_value->memory . "GB)";
        }
    } catch (Exception $e) {
        $server_types_all = array(
            'Error' => array(
                'Type' => 'system',
                'Description' => $e->getMessage(),
            ),
        );
        return $server_types_all;
    }
    try {
        $locations = $HetznerApicall->get('locations');
        logModuleCall($params['moduletype'], 'Get Locations', null, $locations);
        foreach ($locations->locations as $key => $location_types_value) {
            $locationValue[$location_types_value->name] = $location_types_value->city;
        }
    } catch (Exception $e) {
        $locations = array(
            'Error' => array(
                'Type' => 'system',
                'Description' => $e->getMessage(),
            ),
        );
        return $locations;
    }

    $volformat = array(
        'xfs' => 'XFS',
        'ext4' => 'EXT4',
    );
    $ips_types = array(
        'ipv4' => 'IPv4',
        'ipv6' => 'IPv6',
    );
    $CAsections = array(
        'bandUsageSection' => 'Bandwidth Usage section',
        'rescueSection' => 'Rescue section',
        'rebuildSection' => 'Rebuild section',
        'graphSection' => 'Graph section',
    );
    if (isset($_POST["hideCAsection"])) {
        Capsule::table("hetz_clientarea_section")->delete();
        foreach ($_POST["hideCAsection"] as $hideCAsectionkey => $hideCAsectionvalue) {
            Capsule::table("hetz_clientarea_section")->insert(["setting" => $hideCAsectionkey, "value" => $hideCAsectionvalue, "pid" => $pid]);
        }
    }
    $tabpermission = array();
    $tabpermissionResult = Capsule::table("hetz_clientarea_section")->get();
    foreach ($tabpermissionResult as $tabpermissionData) {
        $tabpermissionData = (array) $tabpermissionData;
        $tabpermission[$tabpermissionData['setting']] = $tabpermissionData['value'];
    }
    $CAsectionhtml = '';
    $CAsectionhtml .= '<table style="width:100%">';
    $CAsectionhtml .= '<tr><th colspan="100%" bgcolor="#FFFFD5" style="text-align:center;height: 30px;">Client Area management<small> (Select the section to be hide at client area)</small></th></tr>';
    $CAsectionhtml .= '<tr>';
    foreach ($CAsections as $CAsectionskey => $CAsectionsVal) {
        $CAsectionhtml .= '<td style="text-align:right"><input type="checkbox" name="hideCAsection[' . $CAsectionskey . ']" value="1" ';
        if (isset($tabpermission[$CAsectionskey])) {
            $CAsectionhtml .= 'checked';
        }
        $CAsectionhtml .= '></td><td>' . $CAsectionsVal . '</td>';
    }
    $CAsectionhtml .= '</tr>';
    $CAsectionhtml .= '</table>';

    $array_paramsconfig = array(
        'API Token' => array(
            'Type' => 'text',
            'Size' => '155',
            'Description' => '<script>jQuery(\'input[name="packageconfigoption[2]"]\').parent().parent().parent().find("tr:eq(1)").after(\'<tr><td colspan=4>' . $CAsectionhtml . '</td></tr>\');</script>',
        )
    );
    $array_paramsconfig2 = array(
        'Server Name Prefix' => array(
            'Type' => 'text',
            'Size' => '50',
            'Default' => 'whmcs',
        ),
        'Server type' => array(
            'Type' => 'dropdown',
            'Options' => $servertvalue,
        ),
        'Auto Mount' => array(
            'Type' => 'yesno',
            'Default' => 'true'
        ),
        'Price per additional floating IP' => array(
            'Type' => 'text',
            'Size' => '50',
        ),
        'Allow admin to manage resources' => array(
            'Type' => 'yesno',
            'Default' => 'false',
            'Description' => "Check and save to view options",
        ),
    );
    $array_paramsconfig3 = array(
        'Locations' => array(
            'Type' => 'dropdown',
            'Options' => $locationValue,
        ),
        'Volume in GB' => array(
            'Type' => 'text',
            'Size' => '50',
            'value' => 0,
        ),
        'Volume Format' => array(
            'Type' => 'dropdown',
            'Options' => $volformat,
        ),
        'Floating IPs' => array(
            'Type' => 'text',
            'Size' => '50',
            'value' => 0,
        ),
        'Floating IPs Protocol' => array(
            'Type' => 'dropdown',
            'Options' => $ips_types,
        ),
    );
    if ($api_token_configo->configoption1 == '' || $api_token_configo->configoption1 == "None") {
        $array_paramsconfig_all = $array_paramsconfig;
        return $array_paramsconfig_all;
    } else if ($api_token_configo->configoption6 == '' || $api_token_configo->configoption6 == 'None') {
        $description = [
            'backup' => 'Backups are automatic copies of your servers disks.For every server there are seven slots for backups. If all slots are full and an additional one is created, then the oldest backup will be deleted.We recommend that you power down your server before creating a backup to ensure data consistency on the disks.Enabling Backups for your server will cost 20 % of your server plan per month.',
            'snapshot' => 'Snapshots are instant copies of your servers disks.You can create a new server from a snapshot and even transfer them to a different project.We recommend that you power down your server before taking a snapshot to ensure data consistency.Snapshots cost €0.012/GB/month (incl. 20 % VAT).',
        ];
        $HetznerApicall->createProductAddon($pid, 'Server Backup', 'hetznercloud', $description['backup']);
        $HetznerApicall->createProductAddon($pid, 'Server Snapshot', 'hetznercloud', $description['snapshot']);

        try {
            $array_paramsconfig_all = array_merge($array_paramsconfig, $array_paramsconfig2);
        } catch (Exception $e) {
            $array_paramsconfig2 = array(
                'Error' => array(
                    'Type' => 'system',
                    'Description' => $e->getMessage(),
                ),
            );
        }

        return $array_paramsconfig_all;
    } else if ($api_token_configo->configoption6 == 'on') {
        try {
            //Capsule::table("tblproducts")->where('id',$pid)->update($clearField);
            $array_paramsconfig_all = array_merge($array_paramsconfig, $array_paramsconfig2, $array_paramsconfig3);
        } catch (Exception $e) {
            $array_paramsconfig_all = array(
                'Error' => array(
                    'Type' => 'system',
                    'Description' => $e->getMessage(),
                ),
            );
        }
        return $array_paramsconfig_all;
    } else {
        $array_paramsconfig_all = array_merge($array_paramsconfig, $array_paramsconfig2, $array_paramsconfig3);
        return $array_paramsconfig_all;
    }
}

function hetznercloud_CreateAccount(array $params)
{
    if (!file_exists(__DIR__ . '/license_key_file.php')) {
        return 'License file is missing. Rename license_key_file.php.rename to license.php!';
    }
    require __DIR__ . '/license_key_file.php';

    if (empty($license_key)) {
        return 'License key is missing.';
    }

    $checkLicense = hetznercloud_checkLicense($license_key);
    $checkLicense['status'] = 'Active';
    if ($checkLicense['status'] != 'Active')
        return 'Your license status is ' . $checkLicense['status'];

    if (empty($params['configoption1']))
        return "Error: API Token is missing!";

    $HetznerApicall = new HetznerApicall($params['configoption1']);

    $pid = $params['pid'];

    $serviceid = $params['serviceid'];
    $serverData = [
        'name' => $params["configoption2"] . $params["serviceid"],
        'server_type' => $params["configoption3"],
        'location' => ($params['configoption6'] == 'on') ? $params['configoption7'] : $params["configoptions"]["location"],
        'start_after_create' => true,
        'image' => $params["configoptions"]["images"],
        'automount' => ($params['configoption4'] == 'on') ? true : false
    ];
    $volSize = ($params['configoption6'] == 'on') ? $params['configoption8'] : $params['configoptions']['volume'];
    if ($volSize != 0 && $volSize >= 10) {
        $volumeId = $params['customfields']['volume_id'];
        if (empty($volumeId)) {
            $volumeData = [
                'size' => $volSize,
                'name' => "disk" . $params["serviceid"],
                'location' => ($params['configoption6'] == 'on') ? $params['configoption7'] : $params["configoptions"]["location"],
                'automount' => false,
                'format' => ($params['configoption6'] == 'on') ? $params['configoption9'] : $params["configoptions"]["volformat"]
            ];
            $volumebody = json_encode(($volumeData));
            $create_volume_response = $HetznerApicall->post('volumes', $volumebody);
            logModuleCall($params['moduletype'], 'Create Volume', $volumeData, $create_volume_response);
            if (!empty($create_volume_response->error))
                return "Error: " . $create_volume_response->error->message;
            $volumeCfId = $HetznerApicall->getCustomFieldId($pid, 'volume_id');
            $volumeId = $create_volume_response->volume->id;
            $HetznerApicall->saveCustomFieldsValue($serviceid, $volumeCfId, $volumeId);
            sleep(12);
        }
        if (empty($params["configoptions"]["images"]))
            return "Error: Image should not be null!";

        $serverData = array_merge($serverData, ['volumes' => [$volumeId]]);
    }
    $createServerData = json_encode(($serverData));
    $create_server_response = $HetznerApicall->post('servers', $createServerData);
    logModuleCall($params['moduletype'], 'Create Server', $serverData, $create_server_response->server);
   
    if (!empty($create_server_response->error))
        return "Error : " . $create_server_response->error->message;
    sleep(12);
    $serverID = $create_server_response->server->id;
    $getServerCF_ID = $HetznerApicall->getCustomFieldId($pid, 'server_id');
    $HetznerApicall->saveCustomFieldsValue($serviceid, $getServerCF_ID, $serverID);

    /* create floation ips */
    $no_of_floatingIP = ($params['configoption6'] == 'on') ? $params['configoption10'] : $params['configoptions']['floating_ips'];
    if ($no_of_floatingIP != 0) {
        $currentTime = time();
        $currentDate = date("Y-m-d", $currentTime);
        $floating_ips_post_data = json_encode(array(
            'type' => ($params['configoption6'] == 'on') ? $params['configoption11'] : $params['configoptions']['floating_ips_type'],
            'server' => $serverID,
            'home_location' => ($params['configoption6'] == 'on') ? $params['configoption7'] : $params["configoptions"]["location"],
            'description' => 'Floating IP' . $params['serviceid'] . '_' . $currentDate,
        ));
        for ($i = 0; $i < $no_of_floatingIP; $i++) {
            $create_floating_ip_apicall = $HetznerApicall->post('floating_ips', $floating_ips_post_data);
            logModuleCall($params['moduletype'], 'Create Floating IP', $floating_ips_post_data, $create_floating_ip_apicall->floating_ip);
            sleep(10);

            if (!empty($create_floating_ip_apicall->action->error))
                return "Error : " . $create_server_response->action->error->message;
            $IP_array_data = array([
                'floatingIP_id' => $create_floating_ip_apicall->floating_ip->id,
                'IP_address' => $create_floating_ip_apicall->floating_ip->ip,
                'protocol_type' => $create_floating_ip_apicall->floating_ip->type,
                'description' => $create_floating_ip_apicall->floating_ip->description,
                'home_location' => $create_floating_ip_apicall->floating_ip->home_location->name,
                'serverID' => $serverID,
                'serviceID' => $params['serviceid'],
            ]);
            insertFloatingIPdatabase($IP_array_data);
            logModuleCall($params['moduletype'], 'Insert Floating IP in Database', $floating_ips_post_data, $create_floating_ip_apicall);
        }
    }

    /* Enable Backup or Snapshot */

    $addondata = Capsule::table('tblhostingaddons')->where('hostingid', $params['serviceid'])->where('userid', $params['userid'])->select('addonid')->get();
    foreach ($addondata as $addonid) {
        $addonName = Capsule::table('tbladdons')->where('id', $addonid->addonid)->where('module', 'hetznercloud')->select('name')->get();
        $addName_result = $addonName[0]->name;
        if ($addName_result == 'Server Backup') {
            $customaction_api_call_response = $HetznerApicall->post("servers/" . $serverID . "/actions/enable_backup", null);
            logModuleCall($params['moduletype'], 'Server enable_backup', ' Server ID: ' . $serverID, $customaction_api_call_response);
            if ($customaction_api_call_response->error)
                return $customaction_api_call_response->error->message;
        }
    }

    if ($create_server_response->root_password || $create_floating_ip_apicall->floating_ip->ip) {
        $command = 'UpdateClientProduct';
        $postData = array(
            'serviceid' => $params['serviceid'],
            "dedicatedip" => $create_server_response->server->public_net->ipv4->ip,
            "serviceusername" => "root",
            "servicepassword" => $create_server_response->root_password,
        );
        $adminUsername = '';
        $results = localAPI($command, $postData, $adminUsername);
    }

    $command = 'SendEmail';
    $postData = array(
        'messagename' => 'New Server Details',
        'id' => $params['serviceid'],
        'customvars' => base64_encode(serialize(array(
            "serverName" => $create_server_response->server->name,
            "os_image_name" => $create_server_response->server->image->description,
            "serverIp4" => $create_server_response->server->public_net->ipv4->ip,
            "serverIp6" => $create_server_response->server->public_net->ipv6->ip,
            "serverDatacenter" => $create_server_response->server->datacenter->description,
            "serverType" => $create_server_response->server->server_type->description,
            "cpuCore" => $create_server_response->server->server_type->cores,
            "serverRam" => $create_server_response->server->server_type->memory,
            "serverdisk" => $create_server_response->server->server_type->disk,
            "servAddiVolume" => $params["configoptions"]["volume"],
            "new_root_username" => "root",
            "new_root_password" => $create_server_response->root_password,
        ))),
    );
    $adminUsername = '';
    $results = localAPI($command, $postData, $adminUsername);
    logModuleCall($params['moduletype'], 'Create Server E-mail Log', $postData, $results);
    return 'success';
}

function hetznercloud_TerminateAccount(array $params)
{
    if (empty($params['configoption1']))
        return "Error: API Token is missing!";

    $HetznerApicall = new HetznerApicall($params['configoption1']);
    $pid = $params['pid'];
    $serviceid = $params['serviceid'];
    $serverID = $params['customfields']['server_id'];
    $volumeID = $params['customfields']['volume_id'];

    if (empty($serverID))
        return "Error: Server ID is missing!";

    if ($serverID != '' && $volumeID != '') {
        $detachVolumeAction = $HetznerApicall->post("volumes/" . $volumeID . "/actions/detach", null);
        logModuleCall($params['moduletype'], 'Detach volume', 'Volume ID: ' . $volumeID, $detachVolumeAction);
        if ($detachVolumeAction->error)
            return "Volume Detach Error: " . $detachVolumeAction->error->message;
        sleep(5);
        $deleteVolumeaction = $HetznerApicall->delete("volumes/" . $volumeID);
        logModuleCall($params['moduletype'], 'Delete Volume', 'Volume ID: ' . $serverID, $deleteVolumeaction);
        if ($deleteVolumeaction->error)
            return "Volume Delete Error: " . $deleteVolumeaction->error->message;
        $volumeCfId = $HetznerApicall->getCustomFieldId($pid, 'volume_id');
        $HetznerApicall->deleteCustomFieldsValue($serviceid, $volumeCfId, $volumeID);
        sleep(5);
    }
    $deleteServeraction = $HetznerApicall->delete("servers/" . $serverID);
    logModuleCall($params['moduletype'], 'Delete Server', 'Server ID: ' . $serverID, $deleteServeraction);
    if ($deleteServeraction->error)
        return "Server Delete Error: " . $deleteServeraction->error->message;
    $getServerCF_ID = $HetznerApicall->getCustomFieldId($pid, 'server_id');
    $HetznerApicall->deleteCustomFieldsValue($serviceid, $getServerCF_ID, $serverID);
    $fip_ID = getFloatingIPdatabase($serverID, $serviceid);
    if (!empty($fip_ID)) {
        foreach ($fip_ID as $fip_ID) {
            $deletedFloatingIP = $HetznerApicall->delete('floating_ips/' . $fip_ID->floatingIP_id, null);
            logModuleCall($params['moduletype'], 'Delete Floating IP', 'Floating IP ID: ' . $fip_ID->floatingIP_id, $deletedFloatingIP);
        }
        deleteFloatingIPdatabase($serverID, $serviceid);
        logModuleCall($params['moduletype'], 'Delete Floating IP in Database', null, 'success');
    }
    return 'success';
}

function hetznercloud_SuspendAccount(array $params)
{
    if (empty($params['configoption1']))
        return "Error: API Token is missing!";

    $HetznerApicall = new HetznerApicall($params['configoption1']);
    $serverID = $params['customfields']['server_id'];

    if (empty($serverID))
        return "Error: Server ID is missing!";

    if ($serverID != '') {
        try {
            $serverPowerOff = $HetznerApicall->post("servers/" . $serverID . "/actions/poweroff", null);
            logModuleCall($params['moduletype'], 'Server Suspend', 'Server ID: ' . $serverID, $serverPowerOff);
            if ($serverPowerOff->error)
                return "Error on Server Poweroff : " . $serverPowerOff->error->message;
        } catch (Exception $e) {
            // Record the error in WHMCS's module log.
            logModuleCall(
                'hetznercloud',
                __FUNCTION__,
                $params,
                $e->getMessage(),
                $e->getTraceAsString()
            );
            return $e->getMessage();
        }
        return 'success';
    }
}

function hetznercloud_UnsuspendAccount(array $params)
{
    if (empty($params['configoption1']))
        return "Error: API Token is missing!";

    $HetznerApicall = new HetznerApicall($params['configoption1']);
    $serverID = $params['customfields']['server_id'];

    if (empty($serverID))
        return "Error: Server ID is missing!";

    if ($serverID != '') {
        try {
            $serverPowerOn = $HetznerApicall->post("servers/" . $serverID . "/actions/poweron", null);
            logModuleCall($params['moduletype'], 'Server Unsuspend', 'Server ID: ' . $serverID, $serverPowerOn);
            if ($serverPowerOn->error)
                return "Error on Server Poweroff : " . $serverPowerOn->error->message;
        } catch (Exception $e) {
            logModuleCall(
                'hetznercloud',
                __FUNCTION__,
                $params,
                $e->getMessage(),
                $e->getTraceAsString()
            );
            return $e->getMessage();
        }
        return 'success';
    }
}

function hetznercloud_ChangePackage(array $params)
{
    if (empty($params['configoption1']))
        return "Error: API Token is missing!";

    $HetznerApicall = new HetznerApicall($params['configoption1']);
    $volumeID = $params['customfields']['volume_id'];
    $volumeSize = json_encode(["size" => $params["configoptions"]["volume"]]);
    $serverID = $params['customfields']['server_id'];
    $serviceid = $params['serviceid'];

    if ($volumeID != '' && !empty($params['configoptions']['volume'])) {
        try {
            $volumeChangePackage = $HetznerApicall->post("volumes/" . $volumeID . "/actions/resize", $volumeSize);
            logModuleCall($params['moduletype'], 'Volume Change Package ', 'Volume ID: ' . $volumeID, $volumeChangePackage);
            if ($volumeChangePackage->error)
                return "Error on volume change package : " . $volumeChangePackage->error->message;
        } catch (Exception $e) {
            logModuleCall('hetznercloud', __FUNCTION__, $params, $e->getMessage(), $e->getTraceAsString());
            return $e->getMessage();
        }
        return 'success';
    } else if ($volumeID == '' && !empty($params['configoptions']['volume'])) {
        if ($params['configoptions']['volume'] != 0 && $params['configoptions']['volume'] >= 10) {
            $volumeId = $params['customfields']['volume_id'];
            if (empty($volumeId)) {
                $volumeData = [
                    'size' => $params["configoptions"]["volume"],
                    'name' => "disk" . $params["serviceid"],
                    'location' => $params["configoptions"]["location"],
                    'automount' => false,
                    'format' => $params["configoptions"]["volformat"]
                ];

                $volumebody = json_encode(($volumeData));
                $create_volume_response = $HetznerApicall->post('volumes', $volumebody);
                logModuleCall($params['moduletype'], 'Create Volume on package change', $volumebody, $create_volume_response);
                if (!empty($create_volume_response->error))
                    return "Error: " . $create_volume_response->error->message;
                $volumeCfId = $HetznerApicall->getCustomFieldId($pid, 'volume_id');
                $volumeId = $create_volume_response->volume->id;
                $HetznerApicall->saveCustomFieldsValue($serviceid, $volumeCfId, $volumeId);
                sleep(10);
            }
            $AttachVolumeData = json_encode(["server" => $serverID]);
            $attachVolumeAction = $HetznerApicall->post("volumes/" . $volumeId . "/actions/attach", $AttachVolumeData);
            logModuleCall($params['moduletype'], 'Attach volume to server on package chanage', 'Volume ID: ' . $volumeId, $attachVolumeAction);
            if ($attachVolumeAction->error)
                return "Volume Detach Error: " . $detachVolumeAction->error->message;
            sleep(5);
        }
    }
    if (empty($serverID))
        return "Error: Server ID is missing!";
    $new_serverType = json_encode(["upgrade_disk" => "true", "server_type" => $params["configoption3"]]);
    if ($params['configoption3'] != '' && empty($params['configoptions'])) {
        try {
            $response = adminCustombutton_action($params, 'poweroff');
            if ($response == 'success') {
                $serverTypeChangePackage = $HetznerApicall->post("servers/" . $serverID . "/actions/change_type", $new_serverType);
                logModuleCall($params['moduletype'], 'ServerType Change Package ', 'Server Type name: ' . $params["configoption3"] . " and Server ID :" . $serverID, $volumeChangePackage);
                if ($serverTypeChangePackage->error)
                    return "Error on ServerType Change Package : " . $serverTypeChangePackage->error->message;
            } else {
                return $response;
            }
        } catch (Exception $e) {
            logModuleCall('hetznercloud', __FUNCTION__, $params, $e->getMessage(), $e->getTraceAsString());
            return $e->getMessage();
        }
        return 'success';
    }
}

function hetznercloud_ClientArea(array $params)
{


    if (!file_exists(__DIR__ . '/license_key_file.php')) {
        return 'License file is missing. Rename license_key_file.php.rename to license.php!';
    }
    require __DIR__ . '/license_key_file.php';
    if (empty($license_key)) {
        return 'License key is missing.';
    }
    $checkLicense = hetznercloud_checkLicense($license_key);
    $checkLicense['status'] = 'Active';

    if ($checkLicense['status'] != 'Active')
        return 'Your license status is ' . $checkLicense['status'];

    $HetznerApicall = new HetznerApicall($params['configoption1']);
    $serverID = $params['customfields']['server_id'];

    if (empty($serverID))
        return "Error: Server ID is missing!";

    /* Client Area section management variables */
    $CAsectionOptions = Capsule::table("hetz_clientarea_section")->where('pid', $params['pid'])->get();
    foreach ($CAsectionOptions as $CAsectionOptionskey => $CAsectionOptionsvalue) {
        $hetz_clientarea_section[$CAsectionOptionsvalue->setting] = $CAsectionOptionsvalue->value;
    }

    /* Check for snapshot addon selection */
    $snapshotAddon = Capsule::table('tbladdons')->where('name', 'Server Snapshot')->where('module', 'hetznercloud')->select('id')->get();
    $snapshotaddonID = $snapshotAddon[0]->id;
    $snapshotaddonExist = Capsule::table('tblhostingaddons')->where('hostingid', $params['serviceid'])->where('userid', $params['userid'])->where('addonid', $snapshotaddonID)->count();
    if ($snapshotaddonExist == 0) {
        $snapshotAddon_selected = 'no';
    } else {
        $snapshotAddon_selected = 'yes';
    }

    $getServerDetail = $HetznerApicall->get('servers/' . $serverID);
    logModuleCall($params['moduletype'], 'Get Sever Detail', 'Server ID: ' . $serverID, $getServerDetail);

    $server_name = $getServerDetail->server->name;
    $server_status = $getServerDetail->server->status;
    $server_ipv4 = $getServerDetail->server->public_net->ipv4->ip;
    $server_ipv6 = $getServerDetail->server->public_net->ipv6->ip;
    $server_type = $getServerDetail->server->server_type->description;
    $server_cores = $getServerDetail->server->server_type->cores;
    $server_memory = $getServerDetail->server->server_type->memory;
    $server_disk = $getServerDetail->server->server_type->disk;
    $server_datacenter = $getServerDetail->server->datacenter->description;
    $server_location_city = $getServerDetail->server->datacenter->location->city;
    $server_location_country = $getServerDetail->server->datacenter->location->country;
    $server_image_name = $getServerDetail->server->image->description;
    $server_outgoing_traffic = $getServerDetail->server->outgoing_traffic;
    $server_ingoing_traffic = $getServerDetail->server->ingoing_traffic;
    $rescue_enabled = $getServerDetail->server->rescue_enabled;
    $included_traffic = $getServerDetail->server->included_traffic;
    $server_image_id = $getServerDetail->server->image->id;
    $lastupdate_date = date("d/m/Y") . " " . date("h:i a");
    $usageSize = formatSizeUnits($server_ingoing_traffic + $server_outgoing_traffic);
    $server_volumeID = $getServerDetail->server->volumes[0];

    $numof_floatIPbuyed = ($params['configoption6'] == 'on') ? $params['configoption10'] : $params['configoptions']['floating_ips'];
    $numof_floatIPused = count(getFloatingIPdatabase($serverID, $params['serviceid']));
    $FLPprice = $params["configoption5"];
    $moduleLangVar = hetznercloud_getLang($params);
    $langVarJson = json_encode($moduleLangVar);
    
    if (isset($_POST['customaction'])) {
        if (file_exists(__DIR__ . '/ajax.php'))
            include_once __DIR__ . '/ajax.php';
        exit();
    }
    try {
        $response = array();
        if ($params['status'] != 'Active') {
            return "Service is not active. Service status is " . $params['status'];
        }
        return array(
            'tabOverviewReplacementTemplate' => 'templates/hetznerclientarea.tpl',
            'templateVariables' => array(
                'server_name' => $server_name,
                'server_status' => $server_status,
                'server_ipv4' => $server_ipv4,
                'server_ipv6' => $server_ipv6,
                'server_type' => $server_type,
                'server_cores' => $server_cores,
                'server_memory' => $server_memory,
                'server_disk' => $server_disk,
                'server_datacenter' => $server_datacenter,
                'server_location_city' => $server_location_city,
                'server_location_country' => $server_location_country,
                'server_image_name' => $server_image_name,
                'server_id' => $serverID,
                'server_outgoing_traffic' => round((($server_outgoing_traffic) / (1024 * 1024)), 2),
                'server_ingoing_traffic' => round((($server_ingoing_traffic) / (1024 * 1024)), 2),
                'snapshotAddon_selected' => $snapshotAddon_selected,
                'server_backup_images_array' => $server_backup_images_array,
                'server_snapshot_images_array' => $server_snapshot_images_array,
                'rescue_enabled' => $rescue_enabled,
                'included_traffic' => round((($included_traffic) / (1024 * 1024 * 1024 * 1024)), 2),
                'lastupdate_date' => $lastupdate_date,
                'usageSize' => $usageSize,
                '_language' => $moduleLangVar,
                'langVarJson' => $langVarJson,
                'number_of_floatingIP' => $numof_floatIPbuyed,
                'number_of_floatingIP_used' => $numof_floatIPused,
                'FLPprice' => $FLPprice,
                'bandUsageSection' => $hetz_clientarea_section['bandUsageSection'],
                'rescueSection' => $hetz_clientarea_section['rescueSection'],
                'rebuildSection' => $hetz_clientarea_section['rebuildSection'],
                'graphSection' => $hetz_clientarea_section['graphSection'],
                'iso' => $getServerDetail->server->iso
            ),
        );
    } catch (Exception $e) {
        logModuleCall('hetznercloud', __FUNCTION__, $params, $e->getMessage(), $e->getTraceAsString());
        return array(
            'tabOverviewReplacementTemplate' => 'error.tpl',
            'templateVariables' => array(
                'usefulErrorHelper' => $e->getMessage(),
            ),
        );
    }
}

function hetznercloud_AdminCustomButtonArray()
{
    return array(
        "Poweroff" => "poweroffFunction",
        "Poweron" => "poweronFunction",
        "Shutdown" => "shutdownFunction",
        "Reboot" => "rebootFunction",
        "Reset" => "reset",
        "Reset Root Password" => "resetRootPassword"
    );
}

function hetznercloud_AdminServicesTabFields($params)
{
    $HetznerApicall = new HetznerApicall($params['configoption1']);
    $serverID = $params['customfields']['server_id'];

    global $whmcs;
    $customaction = $whmcs->get_req_var('customaction'); 
    
    $script = '<script>
        jQuery(document).ready(function(){
            jQuery.post("", {"customaction":"server_info", "productid":' . (int) $params['pid'] . ' , "serverid": '.$serverID.'}, function(res){
                jQuery("#ajaxresponse").html(res);
                jQuery("#server_info").html(jQuery("#ajaxresponse #admin_server_info").html());
                jQuery("#ajaxresponse").html("");
            });
        });
    </script>';

    $detailHtml = $script.'<script>jQuery(document).ready(function() {
        jQuery(document).change("#os_images", function() {
        if (jQuery(this).val() !== "0") {
            jQuery("input[value=REBUILD]").attr("disabled", false);
        } else {
            jQuery("input[value=REBUILD]").attr("disabled", true);
        }
    });
});</script>
<link rel="stylesheet" href="../modules/servers/hetznercloud/templates/css/style.css">
                <div class="admin_server_info">
                    <div class="server-details-inner">
                        <div id="server_info">Loading...</div>
                        <div id="ajaxresponse" style="display:none;"></div>
                    </div>
                </div>';
    if($_POST['customaction'] != "" && $_POST['customaction'] == "server_info"){
        try {
            $getServerDetail = $HetznerApicall->get('servers/' . $serverID);
            $get_images = $HetznerApicall->get('images');
            
	        logModuleCall($params['moduletype'], 'Get OS Images Detail ', 'Server ID: ' . $serverID, $get_images);
            
            $server_images_array = $get_images->images; 

            $templateImages = '';
            foreach($server_images_array as $img){
                $templateImages .= '<option value="'.$img->name.'">'.$img->description.'</option>';
            }
            logModuleCall($params['moduletype'], 'server detail', ' Server ID: ' . $serverID, $getServerDetail);
             if ($getServerDetail->error) {            
                 $detailHtml = '<font color="red">' . $getServerDetail->error->message . '</font>';
             }else{
                $server_name = $getServerDetail->server->name;
                $server_status = $getServerDetail->server->status;
                $server_ipv4 = $getServerDetail->server->public_net->ipv4->ip;
                $server_ipv6 = $getServerDetail->server->public_net->ipv6->ip;
                $server_type = $getServerDetail->server->server_type->description;
                $server_cores = $getServerDetail->server->server_type->cores;
                $server_memory = $getServerDetail->server->server_type->memory;
                $server_disk = $getServerDetail->server->server_type->disk;
                $server_datacenter = $getServerDetail->server->datacenter->description;
                $server_location_city = $getServerDetail->server->datacenter->location->city;
                $server_location_country = $getServerDetail->server->datacenter->location->country;
                $server_image_name = $getServerDetail->server->image->description;
                $server_outgoing_traffic = $getServerDetail->server->outgoing_traffic;
                $server_ingoing_traffic = $getServerDetail->server->ingoing_traffic;
                $rescue_enabled = $getServerDetail->server->rescue_enabled;
                $included_traffic = $getServerDetail->server->included_traffic;
                $server_image_id = $getServerDetail->server->image->id;
                $lastupdate_date = date("d/m/Y") . " " . date("h:i a");
                $usageSize = formatSizeUnits($server_ingoing_traffic + $server_outgoing_traffic);
                $server_volumeID = $getServerDetail->server->volumes[0];
                $server_get_volumeSize = $HetznerApicall->get('volumes/'.$server_volumeID);
                $server_volumeSize = $server_get_volumeSize->volume->size;

                $moduleLangVar = hetznercloud_getLang($params);
                
                //$btn = 'window.open("/modules/servers/hetznercloud/console.php?query='.$params['serviceid'].'", "'.$server_name.'", "width=800,height=800")';

                
                $detailHtml = '<link rel="stylesheet" href="../modules/servers/hetznercloud/templates/css/style.css"><div id="admin_server_info"><div class="admin_server_info">
                                <div class="server-details-inner">
                                    <div id="server_info"></div>
                                    <ul>
                                        <li class="server-name"><i class="fa fa-check" aria-hidden="true"></i><span>'.$moduleLangVar['servername'].'</span></li>
                                        <li class="server-version">'.$server_name.'</li>
                                    </ul>
                                    <ul>
                                        <li class="server-name"><i class="fa fa-check" aria-hidden="true"></i><span> IPv4:</span></li>
                                        <li class="server-version">'.$server_ipv4.'</li>
                                    </ul>
                                    <ul>
                                        <li class="server-name"><i class="fa fa-check" aria-hidden="true"></i><span> IPv6:</span></li>
                                        <li class="server-version">'.$server_ipv6.'</li>
                                    </ul>
                                    <ul>
                                        <li class="server-name"><i class="fa fa-check" aria-hidden="true"></i><span>'.$moduleLangVar['servertype'].'</span></li>
                                        <li class="server-version">'.$server_type.'</li>
                                    </ul>
                                    <ul>
                                        <li class="server-name"><i class="fa fa-check" aria-hidden="true"></i><span>vCPU</span></li>
                                        <li class="server-version">'.$server_cores.'</li>
                                    </ul>
                                    <ul>
                                        <li class="server-name"><i class="fa fa-check" aria-hidden="true"></i><span>RAM</span></li>
                                        <li class="server-version">'.$server_memory.' GB</li>
                                    </ul>
                                    <ul>
                                        <li class="server-name"><i class="fa fa-check" aria-hidden="true"></i><span>'.$moduleLangVar['disklocal'].'</span></li>
                                        <li class="server-version">'.$server_disk.'GB + '.$server_volumeSize.'GB</li>
                                    </ul>
                                    <ul>
                                        <li class="server-name"><i class="fa fa-check" aria-hidden="true"></i><span>'.$moduleLangVar['operatingSystem'].' </span></li>
                                        <li class="server-version">'.$server_image_name.'</li>
                                    </ul>
                                    <ul>
                                        <li class="server-name"><i class="fa fa-check" aria-hidden="true"></i><span>'.$moduleLangVar['datacenter'].'</span></li>
                                        <li class="server-version"> '.$server_datacenter.'</li>
                                    </ul>
                                    <ul>
                                        <li class="server-name"><i class="fa fa-check" aria-hidden="true"></i><span>'.$moduleLangVar['city'].'</span></li>
                                        <li class="server-version"> '.$server_location_city.'</li>
                                    </ul>
                                    <ul class="m-b-0">
                                        <li class="server-name"><i class="fa fa-check" aria-hidden="true"></i><span>'.$moduleLangVar['country'].' </span></li>
                                        <li class="server-version">'.$server_location_country.'</li>
                                    </ul>
                                    <ul class="m-b-0">
                                        <li class="server-name"><i class="fa fa-check" aria-hidden="true"></i><span>'.$moduleLangVar['bandwidthUsage'].' </span></li>
                                        <li class="server-version">'.$usageSize.'/ '.round((($included_traffic) / (1024 * 1024 * 1024 * 1024)), 2).' TB</li>
                                    </ul>
                                    <div class="server_btns"><a  class="btn btn-default" href="../modules/servers/hetznercloud/console.php?query='.$params['serviceid'].'&admin=true" target="_blank"><i class="fa fa-desktop" style="font-size:15px"></i> VNC</a></div>
                                </div>

                                <div class="server-details-wrapper">
                                <div class="rescue-container-right">
                                    <div class="wandwidth-title">
                                        <h3>'.$moduleLangVar['rebuild'].'</h3>
                                    </div>
                                    <div class="rescue-container-inner">
                                        <p>'.$moduleLangVar['rebuildContainerText'].'</p>
                                        <div><span id="rebuildloader"><i class="fa" style="font-size: 50px"></i></span></div>
                                        <select class="oprator-select" id="os_images" name="os_image_selected">
                                            <option value="0">'.$moduleLangVar['noneSelected'].'</option>
                                            '.$templateImages.'
                                        </select>
                                        <input type="hidden" name="customaction" value="rebuild">
                                        <button class="rebuild-btn adminrebuild" type="button" onclick="rebuildVM(this);">REBUILD</button>
                                        
                                        <div id="adminajaxresult"></div>
                                    </div>
                                </div>
                            </div>
                </div></div>';
                $detailHtml .= '<script> 
                        function rebuildVM(obj){                              
                            if(jQuery("#os_images").val() == "0"){
                                jQuery("#os_images").focus().css("border", "1px solid #ff0000");
                            }else{
                                
                                if (!confirm("Are you sure want to rebuild this server?")) return false;  
                                jQuery("#os_images").css("border", "none");
                                jQuery.ajax({
                                    url: "",
                                    type: "POST",
                                    data: "customaction=rebuild&os_image="+jQuery("#os_images").val(),
                                    beforeSend: function() {
                                        jQuery(obj).css("pointer-events", "none").html("Rebuild <i class=\"fa fa-spinner fa-spin\" style=\"font-size: 50px\"></i>");
                            
                                    },
                                    success: function(response) {
                                        jQuery(obj).css("pointer-events", "auto").html("Rebuild");
                                        jQuery("#adminajaxresult").html(response);
                                        jQuery("#adminajaxresult").html(jQuery("#adminajaxresult #rebuildresp").html());
                                        setTimeout(function() {
                                           window.location.reload();
                                        }, 3000);     
                                    },
                                });
                            }
                        }
                </script>';

            }

        } catch (Exception $ex) {
            logModuleCall('hetznercloud', __FUNCTION__, $params, $ex->getMessage(), $ex->getTraceAsString());
            $detailHtml = '<font color="red">' . $ex->getMessage() . '</font>';
        }
    
    }
    if($_POST['customaction'] != "" && $_POST['customaction'] == "rebuild"){
        if(preg_match("/[a-z]/i", $whmcs->get_req_var('os_image')))
        {
            $newImageName = array('image'=> $whmcs->get_req_var('os_image'));
            $body = json_encode($newImageName) ;
        }else
        {
            $getnewImageName =  $HetznerApicall->get('images/'.$whmcs->get_req_var('os_image'));
            $newImageName = array('image'=>$getnewImageName->image->id);
            $body = json_encode($newImageName) ;
        }
        try{
            $customaction_api_call_response= $HetznerApicall->post("servers/". $serverID ."/actions/". $customaction, $body);
            logModuleCall($params['moduletype'], 'Server Rebuild', ' Server ID: ' . $serverID.'- Request Parameter :'.$body, $customaction_api_call_response);
            $os_customfiled_data = $HetznerApicall->getproductconfigurableSuboptionID($params['pid'], 'images',$newImageName['image']);

            if ($customaction_api_call_response->error) {
                echo '<div id="rebuildresp"><div class="alert alert-warning alert-dismissible fade in"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a><b>Error</b>' .$customaction_api_call_response->error->message.'</div></div>';

            }else {
                $os_customfiled_data = $HetznerApicall->sethsotingconfigurableOptionID($params['serviceid'], $os_customfiled_data['configid'], $os_customfiled_data['new_os_selected_id']);
                if (is_numeric($newImageName['image']))
                {
                    $server_get_Newimage_os = $HetznerApicall->get('images/'.$newImageName['image']);
                    logModuleCall($params['moduletype'], 'Get OS Image name while rebuild','OS image ID '.$newImageName['image'], $server_get_Newimage_os);
                    $os_image_name =  $server_get_Newimage_os->image->description;
                }else
                {				
                    $os_image_name = strtoupper($newImageName['image']);
                }
                $command = 'SendEmail';
                $postData = array(				
                    'messagename' => 'Server Rebuild Information',
                    'id' => $params['serviceid'],						
                    'customvars' => base64_encode(serialize(array("new_root_username"=> 'root', "new_root_password" => $customaction_api_call_response->root_password, "os_image_name" => $os_image_name))),
                );
                $adminUsername = '';
                $results = localAPI($command, $postData, $adminUsername);
                logModuleCall($params['moduletype'], 'Server Rebuild E-mail Log', $postData, $results);

                $command = 'UpdateClientProduct';
                $postData = array(
                    'serviceid' => $params['serviceid'],
                    'servicepassword' => $customaction_api_call_response->root_password,
                );

                $results = localAPI($command, $postData, $adminUsername);
                logModuleCall($params['moduletype'], 'Server Rebuild service update password', $postData, $results);

                sleep(10);

                echo '<div id="rebuildresp"><div class="alert alert-success alert-dismissible fade in"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a><b>Success</b>Server successfully rebuilt. Please wait it is under progress!</div></div>';
            }
        } catch (Exception $e) {
            logModuleCall(
                'hetznercloud',
                __FUNCTION__,
                $params,
                $e->getMessage(),
                $e->getTraceAsString()
            );
            echo '<div id="rebuildresp"><div class="alert alert-warning alert-dismissible fade in"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a><b>Error</b>' .$e->getMessage().'</div></div>';
        }
        exit();
    }elseif($_POST['customaction'] != "" && $_POST['customaction'] == "server_info"){
        echo $detailHtml;
        exit();
    }else{
        $fieldsarray = array(
            '' => $detailHtml
        );
        return $fieldsarray;
    }

}

function hetznercloud_poweroffFunction(array $params)
{
    $response = adminCustombutton_action($params, 'poweroff');
    return $response;
}

function hetznercloud_reset(array $params)
{
    $response = adminCustombutton_action($params, 'reset');
    return $response;
}

function hetznercloud_resetRootPassword(array $params)
{
    $HetznerApicall = new HetznerApicall($params['configoption1']);
    $serverID = $params['customfields']['server_id'];

    try {
        $customaction_api_call_response = $HetznerApicall->post("servers/" . $serverID . "/actions/reset_password", null);
        logModuleCall($params['moduletype'], 'Server Reset Password', ' Server ID: ' . $serverID, $customaction_api_call_response);
        if ($customaction_api_call_response->error) {
            $error = array(
                'status' => 'error',
                'msg' => $customaction_api_call_response->error->message,
            );
            return $error;
        }else{
            $command = 'SendEmail';
			$postData = array(				
				'messagename' => 'Reset server root password Information',
				'id' => $params['serviceid'],						
				'customvars' => base64_encode(serialize(array("new_root_username"=> 'root', "new_root_password"=>$customaction_api_call_response->root_password))),
			);
			$adminUsername = '';
			$results = localAPI($command, $postData, $adminUsername);

            $command = 'UpdateClientProduct';
            $postData = array(
                'serviceid' => $params['serviceid'],
                'servicepassword' => $customaction_api_call_response->root_password,
            );

            $results = localAPI($command, $postData, $adminUsername);
            logModuleCall($params['moduletype'], 'Server Rebuild service update password', $postData, $results);
        }
    } catch (Exception $e) {
        logModuleCall('hetznercloud', __FUNCTION__, $params, $e->getMessage(), $e->getTraceAsString());
        return $e->getMessage();
    }
    sleep(3);

    return 'success';
}

function hetznercloud_poweronFunction(array $params)
{
    $HetznerApicall = new HetznerApicall($params['configoption1']);
    $serverID = $params['customfields']['server_id'];

    try {
        $customaction_api_call_response = $HetznerApicall->post("servers/" . $serverID . "/actions/" . $customaction, null);
        logModuleCall($params['moduletype'], 'Server ' . $customaction, ' Server ID: ' . $serverID, $customaction_api_call_response);
        if ($customaction_api_call_response->error) {
            $error = array(
                'status' => 'error',
                'msg' => $customaction_api_call_response->error->message,
            );
            return $error;
        }
    } catch (Exception $e) {

        logModuleCall('hetznercloud', __FUNCTION__, $params, $e->getMessage(), $e->getTraceAsString());
        return $e->getMessage();
    }
    sleep(3);

    return 'success';
    return $response;
}

function hetznercloud_shutdownFunction(array $params)
{
    $response = adminCustombutton_action($params, 'shutdown');
    return $response;
}

function hetznercloud_rebootFunction(array $params)
{
    $response = adminCustombutton_action($params, 'reboot');
    return $response;
}

function createFloatingIPdatabase()
{
    if (!Capsule::Schema()->hasTable('hetznercloud_floating_ips')) {
        try {
            Capsule::schema()->create('hetznercloud_floating_ips', function ($table) {
                $table->increments('id');
                $table->string('floatingIP_id');
                $table->string('IP_address');
                $table->string('protocol_type');
                $table->string('description');
                $table->string('home_location');
                $table->string('serverID');
                $table->string('serviceID');
                $table->timestamps();
            });
        } catch (Exception $e) {
            throw new Exception("Unable to create my_table: {$e->getMessage()}");
        }
    }
}

function insertFloatingIPdatabase($IP_array_data = array())
{
    $insertData = array(
        'floatingIP_id' => $IP_array_data[0]['floatingIP_id'],
        'IP_address' => $IP_array_data[0]['IP_address'],
        'protocol_type' => $IP_array_data[0]['protocol_type'],
        'description' => $IP_array_data[0]['description'],
        'home_location' => $IP_array_data[0]['home_location'],
        'serverID' => $IP_array_data[0]['serverID'],
        'serviceID' => $IP_array_data[0]['serviceID'],
    );
    if (
        Capsule::table('hetznercloud_floating_ips')
        ->where('floatingIP_id', $IP_array_data[0]['floatingIP_id'])
        ->where('serverID', $IP_array_data[0]['serverID'])
        ->where('serviceID', $IP_array_data[0]['serviceID'])
        ->count() == 0
    ) {
        Capsule::table('hetznercloud_floating_ips')->insert($insertData);
    } else {
        Capsule::table('hetznercloud_floating_ips')
            ->where('floatingIP_id', $IP_array_data[0]['floatingIP_id'])
            ->where('serverID', $IP_array_data[0]['serverID'])
            ->where('serviceID', $IP_array_data[0]['serviceID'])
            ->update($insertData);
    }
}

function getFloatingIPdatabase($serverID, $serviceID)
{
    $data = Capsule::table('hetznercloud_floating_ips')->where('serverID', $serverID)->where('serviceID', $serviceID)->get();
    return $data;
}

function deleteFloatingIPdatabase($serverID, $serviceID)
{

    if (Capsule::table('hetznercloud_floating_ips')->where('serverID', $serverID)->where('serviceID', $serviceID)->count() != 0)
        Capsule::table('hetznercloud_floating_ips')->where('serverID', $serverID)->where('serviceID', $serviceID)->delete();
}

function adminCustombutton_action(array $params, $customaction)
{

    $HetznerApicall = new HetznerApicall($params['configoption1']);
    $serverID = $params['customfields']['server_id'];

    try {
        $customaction_api_call_response = $HetznerApicall->post("servers/" . $serverID . "/actions/" . $customaction, null);
        logModuleCall($params['moduletype'], 'Server ' . $customaction, ' Server ID: ' . $serverID, $customaction_api_call_response);
        if ($customaction_api_call_response->error) {
            $error = array(
                'status' => 'error',
                'msg' => $customaction_api_call_response->error->message,
            );
            return $error;
        }
    } catch (Exception $e) {

        logModuleCall('hetznercloud', __FUNCTION__, $params, $e->getMessage(), $e->getTraceAsString());
        return $e->getMessage();
    }
    sleep(3);

    return 'success';
}

function formatSizeUnits($bytes)
{
    if ($bytes >= 1099511627776) {
        $bytes = number_format($bytes / 1099511627776, 2) . ' TB';
    } elseif ($bytes >= 1073741824) {
        $bytes = number_format($bytes / 1073741824, 2) . ' GB';
    } elseif ($bytes >= 1048576) {
        $bytes = number_format($bytes / 1048576, 2) . ' MB';
    } elseif ($bytes >= 1024) {
        $bytes = number_format($bytes / 1024, 2) . ' KB';
    } elseif ($bytes > 1) {
        $bytes = $bytes . ' bytes';
    } elseif ($bytes == 1) {
        $bytes = $bytes . ' byte';
    } else {
        $bytes = '0 bytes';
    }

    return $bytes;
}

function hetznercloud_getLang($params)
{
    global $CONFIG;
    if (!empty($_SESSION['Language']))
        $language = strtolower($_SESSION['Language']);
    else if (strtolower($params['clientsdetails']['language']) != '')
        $language = strtolower($params['clientsdetails']['language']);
    else
        $language = $CONFIG['Language'];

    $langfilename = dirname(__FILE__) . '/lang/' . $language . '.php';
    if (file_exists($langfilename))
        require_once($langfilename);
    else
        require_once(dirname(__FILE__) . '/lang/english.php');

    if (isset($_LANG))
        return $_LANG;
}

/* addition IP purchased database */

function createAddFloatingIPDatabase()
{
    if (!Capsule::Schema()->hasTable('hetznercloud_additionalOrder_ips')) {
        try {
            Capsule::schema()->create('hetznercloud_additionalOrder_ips', function ($table) {
                $table->increments('id');
                $table->string('no_floatingIP_id');
                $table->string('protocol_type');
                $table->string('home_location');
                $table->string('serverID');
                $table->string('serviceID');
                $table->string('invoiceID');
                $table->string('userID');
                $table->string('paid_status');
                $table->timestamps();
            });
        } catch (Exception $e) {
            throw new Exception("Unable to create hetznercloud_additionalOrder_ips: {$e->getMessage()}");
        }
    }
}

function insertAddFloatingIPDatabase($IP_array_data = array())
{
    $insertData = array(
        'no_floatingIP_id' => $IP_array_data[0]['no_floatingIP_id'],
        'protocol_type' => $IP_array_data[0]['protocol_type'],
        'home_location' => $IP_array_data[0]['home_location'],
        'serverID' => $IP_array_data[0]['serverID'],
        'serviceID' => $IP_array_data[0]['serviceID'],
        'invoiceID' => $IP_array_data[0]['invoiceID'],
        'userID' => $IP_array_data[0]['userID'],
        'paid_status' => $IP_array_data[0]['paid_status'],
    );
    if (
        Capsule::table('hetznercloud_additionalOrder_ips')
        ->where('invoiceID', $IP_array_data[0]['invoiceID'])
        ->where('serverID', $IP_array_data[0]['serverID'])
        ->where('serviceID', $IP_array_data[0]['serviceID'])
        ->count() == 0
    ) {
        Capsule::table('hetznercloud_additionalOrder_ips')->insert($insertData);
    } else {
        Capsule::table('hetznercloud_additionalOrder_ips')
            ->where('invoiceID', $IP_array_data[0]['invoiceID'])
            ->where('serverID', $IP_array_data[0]['serverID'])
            ->where('serviceID', $IP_array_data[0]['serviceID'])
            ->update($insertData);
    }
}

function getAddFloatingIPDatabase($serverID, $serviceID, $invoiceID)
{
    $data = Capsule::table('hetznercloud_additionalOrder_ips')->where('serverID', $serverID)->where('serviceID', $serviceID)->where('invoiceID', $invoiceID)->get();
    return $data;
}

function deleteAddFloatingIPDatabase($serverID, $serviceID, $invoiceID)
{

    if (Capsule::table('hetznercloud_additionalOrder_ips')->where('serverID', $serverID)->where('serviceID', $serviceID)->where('invoiceID', $invoiceID)->count() != 0)
        Capsule::table('hetznercloud_additionalOrder_ips')->where('serverID', $serverID)->where('serviceID', $serviceID)->delete();
}
