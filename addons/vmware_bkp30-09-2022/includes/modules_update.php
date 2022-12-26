<?php

use Illuminate\Database\Capsule\Manager as Capsule;

try {

    global $templates_compiledir;

    $cacheFiles = glob($templates_compiledir . '/*');

    foreach ($cacheFiles as $file) {
        if (is_file($file) && strpos($file, 'index.php') == false)
            unlink($file);
    }

    $getPortSettingQuery = Capsule::table('tblconfiguration')->where('setting', 'vmport')->count();
    if ($getPortSettingQuery == 0) {
        Capsule::table('tblconfiguration')->insert(['setting' => 'vmport', 'value' => '']);
    }

    $getVncPortSettingCount = Capsule::table('tblconfiguration')->where('setting', 'vmvncport')->count();
    if ($getVncPortSettingCount == 0) {
        Capsule::table('tblconfiguration')->insert(['setting' => 'vmvncport', 'value' => '']);
    }

    Capsule::Schema()->table('mod_vmware_temp_list', function ($table) {
        if (Capsule::Schema()->hasColumn('mod_vmware_temp_list', 'dc_custom_name'))
            $table->dropColumn('dc_custom_name');
    });

    Capsule::Schema()->table('mod_vmware_os_list', function ($table) {
        if (Capsule::Schema()->hasColumn('mod_vmware_os_list', 'dc_custom_name'))
            $table->dropColumn('dc_custom_name');
    });
    if (!Capsule::Schema()->hasTable('mod_vmware_host_setting')) {
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
    }

    if (!Capsule::Schema()->hasTable('mod_vmware_hosts_vms')) {
        Capsule::schema()->create('mod_vmware_hosts_vms', function ($table) {
            $table->increments('id');
            $table->integer('sid');
            $table->string('hostid');
            $table->string('vmname');
        });
    }
    if (!Capsule::Schema()->hasTable('mod_vmware_ds_setting')) {
        Capsule::schema()->create('mod_vmware_ds_setting', function ($table) {

            $table->increments('id');
            $table->string('ds_id')->nullable();
            $table->string('host_id')->nullable();
            $table->integer('disable')->nullable();
            $table->integer('priority')->nullable();
        });
    }
    if (!Capsule::Schema()->hasTable('mod_vmware_reinstall_vm')) {
        Capsule::schema()->create('mod_vmware_reinstall_vm', function ($table) {
            $table->increments('id');
            $table->string('userid')->nullable();
            $table->string('serviceid')->nullable();
            $table->longText('data')->nullable();
            $table->string('os_family')->nullable();
            $table->string('os_version')->nullable();
            $table->integer('status')->nullable();
        });
    }
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
    Capsule::Schema()->table('mod_vmware_os_list', function ($table) {
        if (!Capsule::Schema()->hasColumn('mod_vmware_os_list', 'hostname'))
            $table->string('hostname');
        if (!Capsule::Schema()->hasColumn('mod_vmware_os_list', 'datacenter'))
            $table->string('datacenter');
        if (!Capsule::Schema()->hasColumn('mod_vmware_os_list', 'resourcepool'))
            $table->string('resourcepool');
        if (!Capsule::Schema()->hasColumn('mod_vmware_os_list', 'network_adp'))
            $table->string('network_adp');
    });

    Capsule::Schema()->table('mod_vmware_server', function ($table) {
        if (!Capsule::Schema()->hasColumn('mod_vmware_server', 'esxi'))
            $table->tinyInteger('esxi');
        if (!Capsule::Schema()->hasColumn('mod_vmware_server', 'consoleusername'))
            $table->string('consoleusername');
        if (!Capsule::Schema()->hasColumn('mod_vmware_server', 'consolepassword'))
            $table->string('consolepassword');
    });

    Capsule::Schema()->table('mod_vmware_ip_list', function ($table) {
        if (!Capsule::Schema()->hasColumn('mod_vmware_ip_list', 'hostname'))
            $table->string('hostname');
        if (!Capsule::Schema()->hasColumn('mod_vmware_ip_list', 'location'))
            $table->string('location');
        if (!Capsule::Schema()->hasColumn('mod_vmware_ip_list', 'mac_status'))
            $table->integer('mac_status');
        if (!Capsule::Schema()->hasColumn('mod_vmware_ip_list', 'reversedns'))
            $table->string('reversedns', '255')->nullable();
        if (!Capsule::Schema()->hasColumn('mod_vmware_ip_list', 'rdns_status'))
            $table->integer('rdns_status')->nullable();
        if (!Capsule::Schema()->hasColumn('mod_vmware_ip_list', 'sid'))
            $table->integer('sid')->nullable();
        if (!Capsule::Schema()->hasColumn('mod_vmware_ip_list', 'desc'))
            $table->string('desc', '255')->nullable();
    });

    Capsule::Schema()->table('mod_vmware_temp_list', function ($table) {
        if (!Capsule::Schema()->hasColumn('mod_vmware_temp_list', 'hostname'))
            $table->string('hostname');
        if (!Capsule::Schema()->hasColumn('mod_vmware_temp_list', 'datastore'))
            $table->string('datastore');
        if (!Capsule::Schema()->hasColumn('mod_vmware_temp_list', 'resourcepool'))
            $table->string('resourcepool');
        if (!Capsule::Schema()->hasColumn('mod_vmware_temp_list', 'network_adp'))
            $table->string('network_adp');
        if (!Capsule::Schema()->hasColumn('mod_vmware_temp_list', 'status'))
            $table->tinyInteger('status');
        if (!Capsule::Schema()->hasColumn('mod_vmware_temp_list', 'autoconfig'))
            $table->tinyInteger('autoconfig');
        if (!Capsule::Schema()->hasColumn('mod_vmware_temp_list', 'product_key'))
            $table->string('product_key');
        if (!Capsule::Schema()->hasColumn('mod_vmware_temp_list', 'fromtemplate'))
            $table->string('fromtemplate');
        if (!Capsule::Schema()->hasColumn('mod_vmware_temp_list', 'port'))
            $table->string('port')->nullable();
    });

    if (!Capsule::Schema()->hasTable('mod_vmware_settings')) {
        Capsule::schema()->create('mod_vmware_settings', function ($table) {
            $table->increments('id');
            $table->string('setting', '500');
            $table->integer('uid');
            $table->integer('sid');
        });
    }
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
    Capsule::Schema()->table('mod_vmware_pw_linux_vm', function ($table) {
        if (!Capsule::Schema()->hasColumn('mod_vmware_pw_linux_vm', 'os_version'))
            $table->string('os_version')->nullable();
        if (!Capsule::Schema()->hasColumn('mod_vmware_pw_linux_vm', 'port'))
            $table->string('port')->nullable();
    });
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

    if (!Capsule::Schema()->hasTable('mod_vmware_cron_vm')) {
        Capsule::schema()->create('mod_vmware_cron_vm', function ($table) {
            $table->increments('id');
            $table->integer('sid');
            $table->integer('status');
        });
    }

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

    if (!Capsule::Schema()->hasTable('mod_vmware_logs')) {
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
    }

    if (!Capsule::Schema()->hasTable('mod_vmware_proxy_setup')) {
        Capsule::schema()->create('mod_vmware_proxy_setup', function ($table) {
            $table->increments('id');
            $table->string('server_ip');
        });
    }
} catch (Exception $ex) {
}

$addonDataQuery = Capsule::table('mod_vmware_server')->get();
foreach ($addonDataQuery as $addonData) {
    $addonData = (array) $addonData;
    $decryptPw = $vmWare->vmwarePwEncryptDcrypt($addonData['vspherepassword']);
    if (empty($decryptPw['password'])) {
        $encryptPw = $vmWare->vmwarePwEncryptDcrypt($addonData['vspherepassword'], true);
        Capsule::table('mod_vmware_server')->where('id', $addonData['id'])->update(['vspherepassword' => $encryptPw['password']]);
    }
}

if (Capsule::table('tblemailtemplates')->select('id')->where('name', 'Vmware Welcome Email')->count() == 1) {
    Capsule::table('tblemailtemplates')->select('id')->where('name', 'Vmware Welcome Email')->update([
        'name' => 'VMware Welcome Email',
        'subject' => 'VPS Server Welcome Email',
        'message' => '<p>Dear {$client_name},</p><p>Your VPS server has been successfully configured. Your server detail as given below.</p><p><strong>Login Details:</strong></p><p>Username: {$service_username}<br/>System Password: {$service_password}</p><p><strong>Server Details:</strong></p><p>IP Address: {$service_dedicated_ip}<br/>Hostname: {$vmname}<br/>Operating System: {$vm_os}<br/>RAM: {$vm_ram}<br/>CPUs: {$vm_cpu}<br/>Disk Size: {$vm_hdd}</p>{if $vm_additional_network}<p>{$vm_additional_network}</p>{/if}<p>Thank you!</p>'
    ]);
}

# email tempalte array
$emailTempArr = array(
    array(
        "type" => "product",
        'name' => 'VMware Welcome Email',
        'subject' => 'VPS Server Welcome Email',
        'message' => '<p>Dear {$client_name},</p><p>Your VPS server has been successfully configured. Your server detail as given below.</p><p><strong>Login Details:</strong></p><p>Username: {$service_username}<br/>System Password: {$service_password}</p><p><strong>Server Details:</strong></p><p>IP Address: {$service_dedicated_ip}<br/>Hostname: {$vmname}<br/>Operating System: {$vm_os}<br/>RAM: {$vm_ram}<br/>CPUs: {$vm_cpu}<br/>Disk Size: {$vm_hdd}</p>{if $vm_additional_network}<p>{$vm_additional_network}</p>{/if}<p>Thank you!</p>'
    ),
    array(
        "type" => "admin",
        'name' => 'VMware Migration Request Notification Email',
        'subject' => 'VMware Migration Request Notification',
        'message' => '<span>Hello,<br /><br /> You have got a server migration user request notification.<br />{$user_migration_content}<br />Please accept the user request. <a href="{$addon_migrate_link}">Click Here</a> <br /> Thank you. '
    ),
    array(
        "type" => "product",
        'name' => 'VMware Migration Notification Email',
        'subject' => 'VMware Migration Notification',
        'message' => '<span>Hello,<br /><br />{$user_migration_content}<br /> Thank you. '
    ),
    array(
        'type' => "product",
        'name' => 'Server Reinstall Notification Email',
        'subject' => 'Server Reinstall Notification',
        'message' => '<span>Dear Customer {$user_name},<br /><br /> Your server has been successfully Reinstalled.<br />Your server detail as given below.<br />{$reinstall_detail}<br /> Thank you. '
    ),
    array(
        "type" => "product",
        'name' => 'Server Information',
        'subject' => 'Server Information',
        'message' => '<p>Dear {$client_name},</p><p>Your VPS server has been successfully configured. Your server detail as given below.</p><p><strong>Login Details:</strong></p><p>Username: {$service_username}<br/>System Password: {$service_password}</p><p><strong>Server Details:</strong></p><p>IP Address:{$service_dedicated_ip}<br/>Hostname: {$vmname}<br/>OS:{$vm_os}<br/>RAM:{$vm_ram}<br/>CPUs:{$vm_cpu}<br/>Disk Size:{$vm_hdd}</p>{if $vm_additional_network}<p>{$vm_additional_network}</p>{/if}<p>Thank you!</p>'
    ),
    array(
        "type" => "product",
        'name' => 'VMware Server Password Reset Email',
        'subject' => 'Password Reset Successfully',
        'message' => '<p>Dear {$client_name},</p><p>Password has been successfully reset for your server.</p><p>Hostname: {$vmname}</p><p>IP Address: {$service_dedicated_ip}</p><p>New Password: {$service_password}</p><p>Thank you!</p>'
    ),
);

foreach ($emailTempArr as $temp) {
    vmWareCreateEmailTemplateNew($temp);
}

function vmWareCreateEmailTemplateNew($tempData)
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
            $ex->getMessage();
            logActivity("Could't insert into table tblemailtemplates: {$ex->getMessage()}");
        }
    }
}

$WHMCSproductList = $vmWare->vmwareGetWHMCSProductList();

foreach ($WHMCSproductList as $productResult) {
    $pid = $productResult['id'];
    if (empty($productResult['configoption1']) && empty($productResult['configoption3'])) {
        $createConfigOption = false;
    } elseif (empty($productResult['configoption1']) && !empty($productResult['configoption3'])) {
        $createConfigOption = true;
    } else {
        $createConfigOption = false;
    }

    vmwareCreateProductConfigurableOption($pid, $createConfigOption, $productResult['configoption3']);   # Create product configurable options

    $WgsVmwareObj->vmware_manageCustomFields($pid, $createConfigOption);
}

header('location:' . $modulelink . '&success=true');
