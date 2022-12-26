<?php

use WHMCS\Database\Capsule;

global $whmcs;
$clodflare = new Manage_Cloudflare();

$getAllZones = $clodflare->getAllZones();

$data = [];
$i = 0;
if (count($getAllZones['result']) > 0) {
    foreach ($getAllZones['result'] as $key => $zone) {
        $name_servers = $or_name_servers = '';
        foreach ($zone['name_servers'] as $ns) {
            $name_servers .= $ns . ' ,';
        }
        $name_servers = rtrim($name_servers, ' ,');
        foreach ($zone['original_name_servers'] as $ons) {
            $or_name_servers .= $ons . ' ,';
        }
        $or_name_servers = rtrim($or_name_servers, ' ,');
        $data[$i][] = $zone['name'];
        $data[$i][] = ucfirst($zone['status']);
        $data[$i][] = ($zone['development_mode'] == 0) ? $_lang['off'] : $_lang['on'];
        $data[$i][] = $name_servers;
        $data[$i][] = $or_name_servers;
        $data[$i][] = $zone['plan']['name'];

        $i++;
    }
} else {
    $data[0][] = '';
    $data[0][] = '';
    $data[0][] = '';
    $data[0][] = '';
    $data[0][] = '';
    $data[0][] = '';
}
$newData["data"] = $data;
echo json_encode($newData);
exit();
