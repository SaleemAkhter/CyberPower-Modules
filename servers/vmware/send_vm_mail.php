<?php

if (empty($reinstall)) {
    $vm_additional_network = '';
    if (empty($dhcp) && count($ipListArr) > 0) {
        $vm_additional_network = '<p>' . $_LANG['vm_email_additionaliptext'] . '</p><p>';
        foreach ($ipListArr as $ipKey => $additionalIP) {
            $vm_additional_network .= $additionalIP['ip'] . ' <br/>';
        }
        $vm_additional_network .= '</p>';
    }
    $totalDisk = 0;
    foreach ($disk_drives as $key => $disk_info) {
        $totalDisk = $totalDisk + $disk_info['capacity'];
    }
    $values["messagename"] = 'VMware Welcome Email';
    $values["customvars"] = base64_encode(serialize(array("vmname" => $new_vm_name, "vm_os" => $guetOsVersion, "vm_ram" => $ram, "vm_cpu" => $numCPUs, "vm_hdd" => $totalDisk . ' GB', "vm_additional_network" => $vm_additional_network)));
}

$adminuser = $admin;
$command = "sendemail";
$values["id"] = $serviceId;
$results = localAPI($command, $values, $adminuser);
?>