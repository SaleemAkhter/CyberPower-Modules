<?php

/* * ****************************************************************
 *  WGS Vmware WHMCS Addon Module By whmcsglobalservices.com
 *  Copyright whmcsglobalservices, All Rights Reserved
 *
 *  Release: 01 May 2016
 *  WHMCS Version: v6/v7/v8
 *  Version: 4.0.2
 *  Update Date: 19 May, 2021
 *
 *  By WHMCSGLOBALSERVICES    https://whmcsglobalservices.com
 *  Contact                   info@whmcsglobalservices.com
 *
 *  This module is made under license issued by whmcsglobalservices.com
 *  and used under all terms and conditions of license.    Ownership of
 *  module can not be changed.     Title and copy of    module  is  not
 *  available to any other person.
 *
 *  @owner <whmcsglobalservices.com>
 *  @author <WHMCSGLOBALSERVICES>
 * ********************************************************** */

use Illuminate\Database\Capsule\Manager as Capsule;

if (!defined("WHMCS"))
    die("This file cannot be accessed directly");

if (file_exists(__DIR__ . '/classes/class.php'))
    require_once __DIR__ . '/classes/class.php';

function vmware_config()
{
    $configarray = array(
        "name" => "WGS VMware",
        "description" => "In this addon module you can manage the vmware configuration",
        "version" => "4.0.2",
        "author" => "WHMCS GLOBAL SERVICES",
        "language" => "english",
        "fields" => array(
            "license_key" => array("FriendlyName" => "License key", "Type" => "text", "Size" => "50"),
            "delete_db" => array("FriendlyName" => "Delete Database Table", "Type" => "yesno", "Default" => "yes", "Description" => "Tick this box to delete the addon module database table when deactivating the module."),
            "enable_log" => array("FriendlyName" => "Enable Log", "Type" => "yesno", "Default" => "", "Description" => "Tick this box to store logs activity with module."),
        )
    );
    return $configarray;
}

function vmware_activate()
{
    try {
        if (!Capsule::Schema()->hasTable('mod_vmware_os_list')) {
            Capsule::schema()->create(
                'mod_vmware_os_list',
                function ($table) {
                    $table->increments('id');
                    $table->string('os_family', '50');
                    $table->string('os_version', '100');
                    $table->string('os_version_id', '100');
                    $table->tinyInteger('status');
                    $table->string('isofile', '500');
                    $table->integer('server_id');
                    $table->string('datastore');
                    $table->string('hostname');
                    $table->string('datacenter');
                    $table->string('resourcepool');
                    $table->string('network_adp');
                }
            );
        }
    } catch (\Exception $e) {
        logActivity("Unable to create mod_vmware_os_list: {$e->getMessage()}");
    }

    try {

        if (!Capsule::Schema()->hasTable('mod_vmware_ip_list')) {

            Capsule::schema()->create(
                'mod_vmware_ip_list',
                function ($table) {
                    $table->increments('id');
                    $table->string('datacenter');
                    $table->string('ip');
                    $table->string('netmask');
                    $table->string('gateway');
                    $table->string('dns');
                    $table->tinyInteger('status');
                    $table->string('server');
                    $table->string('forvm', '255');
                    $table->string('macaddress', '255');
                    $table->integer('server_id');
                    $table->string('hostname');
                    $table->string('location');
                    $table->integer('mac_status');
                    $table->string('reversedns', '255')->nullable();
                    $table->integer('rdns_status')->nullable();
                    $table->integer('sid')->nullable();
                }
            );
        }
    } catch (\Exception $e) {
        logActivity("Unable to create mod_vmware_ip_list: {$e->getMessage()}");
    }

    try {
        if (!Capsule::Schema()->hasTable('mod_vmware_server')) {
            Capsule::schema()->create(
                'mod_vmware_server',
                function ($table) {
                    $table->increments('id');
                    $table->string('server_name');
                    $table->string('vsphereip');
                    $table->string('vsphereusername');
                    $table->string('vspherepassword');
                    $table->string('consoleusername');
                    $table->string('consolepassword');
                    $table->tinyInteger('esxi');
                }
            );
        }
    } catch (\Exception $e) {
        logActivity("Unable to create mod_vmware_server: {$e->getMessage()}");
    }

    try {
        if (!Capsule::Schema()->hasTable('mod_vmware_temp_list')) {
            Capsule::schema()->create(
                'mod_vmware_temp_list',
                function ($table) {
                    $table->increments('id');
                    $table->string('datacenter');
                    $table->string('vmtemplate');
                    $table->string('customname');
                    $table->string('server_id');
                    $table->string('sys_pw');
                    $table->string('os_family');
                    $table->string('hostname');
                    $table->string('datastore');
                    $table->string('resourcepool');
                    $table->string('network_adp');
                    $table->tinyInteger('status');
                    $table->tinyInteger('autoconfig');
                    $table->string('product_key');
                    $table->string('fromtemplate');                    
                    $table->string('port')->nullable();
                }
            );
        }
    } catch (\Exception $e) {
        logActivity("Unable to create mod_vmware_temp_list: {$e->getMessage()}");
    }

    try {

        if (!Capsule::Schema()->hasTable('mod_vmware_snapshot_counter')) {

            Capsule::schema()->create(
                'mod_vmware_snapshot_counter',
                function ($table) {
                    $table->increments('id');
                    $table->integer('sid');
                    $table->integer('uid');
                    $table->integer('count');
                }
            );
        }
    } catch (Exception $ex) {
        logActivity("Unable to create mod_vmware_snapshot_counter: {$ex->getMessage()}");
    }

    try {
        if (!Capsule::Schema()->hasTable('mod_vmware_migration_list')) {
            Capsule::schema()->create(
                'mod_vmware_migration_list',
                function ($table) {
                    $table->increments('id');
                    $table->integer('sid');
                    $table->integer('uid');
                    $table->integer('server_id');
                    $table->integer('status');
                    $table->string('user');
                    $table->string('from_host');
                    $table->string('r_pool');
                    $table->string('vmname');
                    $table->string('to_host');
                    $table->string('datacenter');
                    $table->string('reason');
                }
            );
        }
    } catch (\Exception $e) {
        logActivity("Unable to create mod_vmware_migration_list: {$e->getMessage()}");
    }



    try {
        if (!Capsule::Schema()->hasTable('mod_vmware_settings')) {
            Capsule::schema()->create(
                'mod_vmware_settings',
                function ($table) {
                    $table->increments('id');
                    $table->string('setting', '500');
                    $table->integer('uid');
                    $table->integer('sid');
                }
            );
        }
    } catch (\Exception $e) {
        logActivity("Unable to create mod_vmware_settings: {$e->getMessage()}");
    }

    try {
        if (!Capsule::Schema()->hasTable('mod_vmware_pw_linux_vm')) {
            Capsule::schema()->create(
                'mod_vmware_pw_linux_vm',
                function ($table) {
                    $table->increments('id');
                    $table->string('vm_name', '150');
                    $table->string('old_password', '255');
                    $table->string('password', '150');
                    $table->string('ip');
                    $table->integer('uid');
                    $table->integer('sid');
                    $table->integer('pid');
                    $table->string('os', '150');
                    $table->string('ram', '150');
                    $table->string('hdd', '150');
                    $table->string('cpu', '150');
                    $table->integer('status');
                    $table->string('os_version')->nullable();
                    $table->string('port')->nullable();
                }
            );
        }
    } catch (\Exception $e) {
        logActivity("Unable to create mod_vmware_pw_linux_vm: {$e->getMessage()}");
    }

    try {
        if (!Capsule::Schema()->hasTable('mod_vmware_apps')) {
            Capsule::schema()->create(
                'mod_vmware_apps',
                function ($table) {
                    $table->string('app_name')->nullable();
                    $table->string('setting')->nullable();
                    $table->string('value')->nullable();
                }
            );
        }
    } catch (\Exception $e) {
        logActivity("Unable to create mod_vmware_apps: {$e->getMessage()}");
    }

    try {
        Capsule::schema()->create('mod_vmware_cron_vm', function ($table) {
            $table->increments('id');
            $table->integer('sid');
            $table->integer('status');
        });
    } catch (Exception $ex) {
        logActivity("Unable to create mod_vmware_cron_vm: {$ex->getMessage()}");
    }

    try {
        Capsule::schema()->create('mod_vmware_hosts_vms', function ($table) {
            $table->increments('id');
            $table->integer('sid');
            $table->string('hostid');
            $table->string('vmname');
        });
    } catch (Exception $ex) {
        logActivity("Unable to create mod_vmware_hosts_vms: {$ex->getMessage()}");
    }

    try {
        Capsule::schema()->create('mod_vmware_host_setting', function ($table) {

            $table->increments('id');
            $table->string('dc_id')->nullable();
            $table->string('host_id')->nullable();
            $table->string('network')->nullable();
            $table->integer('serverid')->nullable();
            $table->integer('disable')->nullable();
            $table->integer('priority')->nullable();
            $table->integer('vm_num')->nullable();
        });
    } catch (Exception $ex) {

        logActivity("Unable to create mod_vmware_host_setting: {$ex->getMessage()}");
    }

    try {
        Capsule::schema()->create('mod_vmware_ds_setting', function ($table) {

            $table->increments('id');
            $table->string('ds_id')->nullable();
            $table->string('host_id')->nullable();
            $table->integer('disable')->nullable();
            $table->integer('priority')->nullable();
        });
    } catch (Exception $ex) {
        logActivity("Unable to create mod_vmware_ds_setting: {$ex->getMessage()}");
    }

    try {
        Capsule::schema()->create('mod_vmware_reinstall_vm', function ($table) {
            $table->increments('id');
            $table->string('userid')->nullable();
            $table->string('serviceid')->nullable();
            $table->longText('data')->nullable();
            $table->string('os_family')->nullable();
            $table->string('os_version')->nullable();
            $table->integer('status')->nullable();
        });
    } catch (Exception $ex) {
        logActivity("Unable to create mod_vmware_reinstall_vm: {$ex->getMessage()}");
    }
    try {

        Capsule::schema()->create('mod_vmware_logs', function ($table) {

            $table->increments('id');

            $table->integer('sid');

            $table->dateTime('date');

            $table->text('description');

            $table->text('username');

            $table->string('vmname');

            $table->string('ip');

            $table->string('status');
        });
    } catch (Exception $ex) {

        logActivity("Unable to create mod_vmware_logs: {$ex->getMessage()}");
    }

    try {
        if (!Capsule::Schema()->hasTable('mod_vmware_vm_ip')) {
            Capsule::schema()->create(
                'mod_vmware_vm_ip',
                function ($table) {
                    $table->increments('id');
                    $table->integer('sid');
                    $table->integer('uid');
                    $table->integer('status');
                }
            );
        }
    } catch (\Exception $e) {
        logActivity("Unable to create mod_vmware_vm_ip: {$e->getMessage()}");
    }

    if (!Capsule::Schema()->hasTable('mod_vmware_proxy_setup')) {
        Capsule::schema()->create('mod_vmware_proxy_setup', function ($table) {
            $table->increments('id');
            $table->string('server_ip');
        });
    }

    $emailTempArr = array(
        array(
            "type" => "product",
            'name' => 'VMware Welcome Email',
            'subject' => 'VPS Server Welcome Email',
            'message' => '<p>Dear {$client_name},</p><p>Your VPS server has been successfully configured. Your server detail as given below.</p><p><strong>Login Details:</strong></p><p>Username: {$service_username}<br/>System Password: {$service_password}</p><p><strong>Server Details:</strong></p><p>IP Address: {$service_dedicated_ip}<br/>Hostname: {$vmname}<br/>Operating System: {$vm_os}<br/>RAM: {$vm_ram}<br/>CPUs: {$vm_cpu}<br/>Disk Size: {$vm_hdd}</p>{if $vm_additional_network}<p>{$vm_additional_network}</p>{/if}<p>Thank you!</p>'
        ),
        array(
            "type" => "product",
            'name' => 'VMware Bandwidth Usage Notification Email',
            'subject' => 'Server Bandwidth Usage Notification',
            'message' => '<span>Dear {$user_name},<br /><br /> We want to inform to you. You have reached {$quota}  Quota of your Subscribed Data Plan.<br /> Thank you. '
        ),
        array(
            "type" => "product",
            'name' => 'VMware Service Suspension Notification Email',
            'subject' => 'VMware Service Suspension Notification',
            'message' => '<span>Dear {$user_name},<br /><br /> You have reached {$quota}  Quota of your Subscribed Data Plan.<br />So, your service has been suspended.<br />If you want to extend the bandwidth limit, please contact with us.<br /> Thank you. '
        ),
        array(
            "type" => "admin",
            'name' => 'VMware Migration Request Notification Email',
            'subject' => 'Server Migration Request Notification',
            'message' => '<span>Hello,<br /><br /> You have got a server migration user request notification.<br />{$user_migration_content}<br />Please accept the user request. <a href="{$addon_migrate_link}">Click Here</a> <br /> Thank you. '
        ),
        array(
            "type" => "product",
            'name' => 'VMware Migration Notification Email',
            'subject' => 'VMware Migration Notification',
            'message' => '<span>Hello,<br /><br />{$user_migration_content}<br /> Thank you. '
        ),
        array(
            "type" => "product",
            'name' => 'Server Reinstall Notification Email',
            'subject' => 'Server Reinstall Notification',
            'message' => '<span>Dear Customer {$user_name},<br /><br /> Your server has been successfully Reinstalled.<br />Your server detail as given below.<br />{$reinstall_detail}<br /> Thank you. '
        ),
        array(
            "type" => "product",
            'name' => 'Server Information',
            'subject' => 'Server Information',
            'message' => '<p>Dear {$client_name},</p><p>Your VPS server has been successfully configured. Your server detail as given below.</p><p><strong>Login Details:</strong><br/>Username: {$service_username}<br/>System Password: {$service_password}</p><p><strong>Server Details:</strong><br/>IP Address:{$service_dedicated_ip}<br/>Hostname: {$vmname}<br/>OS:{$vm_os}<br/>RAM:{$vm_ram}<br/>CPUs:{$vm_cpu}<br/>Disk Size:{$vm_hdd}</p>{if $vm_additional_network}<p>{$vm_additional_network}</p>{/if}<p>Thank you!</p>'
        ),
    );

    foreach ($emailTempArr as $temp) {
        vmWareCreateEmailTemplate($temp);
    }
    $getPortSettingQuery = Capsule::table('tblconfiguration')->where('setting', 'vmport')->count();

    if ($getPortSettingQuery == 0) {

        Capsule::table('tblconfiguration')->insert(['setting' => 'vmport', 'value' => '']);
    }
    $getVncPortSettingCount = Capsule::table('tblconfiguration')->where('setting', 'vmvncport')->count();

    if ($getVncPortSettingCount == 0) {

        Capsule::table('tblconfiguration')->insert(['setting' => 'vmvncport', 'value' => '']);
    }
    return array('status' => 'success', 'description' => 'Activated successfully.');
}

function vmware_deactivate()
{

    $deleteDbTable = Capsule::table('tbladdonmodules')->where('module', 'vmware')->where('setting', 'delete_db')->first();

    if ($deleteDbTable->value == 'on') {

        Capsule::schema()->dropIfExists('mod_vmware_os_list');
        Capsule::schema()->dropIfExists('mod_vmware_license');
        Capsule::schema()->dropIfExists('mod_vmware_ip_list');
        Capsule::schema()->dropIfExists('mod_vmware_temp_list');
        Capsule::schema()->dropIfExists('mod_vmware_server');
        Capsule::schema()->dropIfExists('mod_vmware_migration_list');
        Capsule::schema()->dropIfExists('mod_vmware_settings');
        Capsule::schema()->dropIfExists('mod_vmware_pw_linux_vm');
        Capsule::schema()->dropIfExists('mod_vmware_apps');
        Capsule::schema()->dropIfExists('mod_vmware_host_setting');
        Capsule::schema()->dropIfExists('mod_vmware_ds_setting');
        Capsule::schema()->dropIfExists('mod_vmware_hosts_vms');
        Capsule::schema()->dropIfExists('mod_vmware_cron_vm');
        Capsule::schema()->dropIfExists('mod_vmware_reinstall_vm');
        Capsule::schema()->dropIfExists('mod_vmware_logs');
        Capsule::schema()->dropIfExists('mod_vmware_proxy_setup');

        try {
            Capsule::delete("DELETE FROM tblemailtemplates WHERE type='product' AND custom='1' AND name IN('Vmware Welcome Email','VMware Bandwidth Usage Notification Email','VMware Service Suspension Notification Email','VMware Migration Request Notification Email','VMware Migration Notification Email','Server Reinstall Notification Email')");
        } catch (Exception $ex) {
        }
    }

    return array('status' => 'success', 'description' => 'Activated successfully.');
}

function vmware_upgrade($vars)
{

    $version = $vars['version'];
}

function vmware_output($vars)
{
    global $whmcs;

    $dirPath = str_replace('addons', 'servers', __DIR__);

    if (file_exists($dirPath . '/class/class.php'))
        require_once $dirPath . '/class/class.php';

    $WgsVmwareObj = new WgsVmware();

    $WgsVmwareObj->vmware_includes_files();

    $imgPath = '../modules/addons/vmware/images/';
    $cssPath = '../modules/addons/vmware/css/';
    $jsPath = '../modules/addons/vmware/js/';

    $action = !empty($_REQUEST['action']) ? $whmcs->get_req_var("action") : '';

    if ($action != 'ajax' && !isset($_POST['appajax'])) {
        echo '<script>var modulelink = "' . $modulelink . '"</script>';
        echo '<script type = "text/javascript" language = "javascript" src = "' . $jsPath . 'custom.js"></script>';
        echo '<script type="text/javascript" language="javascript" src="' . $jsPath . 'jquery.mCustomScrollbar.concat.min.js"></script>';
        echo '<link rel="stylesheet" type="text/css" href="' . $cssPath . 'style.css">';
        echo '<link rel="stylesheet" type="text/css" href="' . $cssPath . 'jquery.mCustomScrollbar.css">';
    }

    $modulelink = $vars['modulelink'];

    $LANG = $vars['_lang'];

    $vmWare = new VMWAREADDON($vars);

    if (!empty($action)) {
        include_once(__DIR__ . '/includes/' . $action . '.php');
        if ($action == 'ajax')
            exit();
    } else {
        include_once(__DIR__ . '/includes/homepage.php');
    }
}

function vmWareCreateEmailTemplate($tempData)
{
    if (Capsule::table('tblemailtemplates')->select('id')->where('name', $tempData['name'])->count() == 0) {
        try {
            Capsule::table('tblemailtemplates')->insert(
                [
                    "type" => $tempData['type'],
                    "name" => $tempData['name'],
                    "subject" => $tempData['subject'],
                    "message" => $tempData['message'],
                    "custom" => 1,
                    "plaintext" => 0
                ]
            );
        } catch (Exception $ex) {
            logActivity("Could't insert into table tblemailtemplates: {$ex->getMessage()}");
        }
    }
}
