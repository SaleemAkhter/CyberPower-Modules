<?php

use Illuminate\Database\Capsule\Manager as Capsule;

$vmName = $params['moduleparams']['customfields']['vm_name'];

$getVmIps = $vmWareovhapp->getVmIps($vmName);
$datacenterLocation = $vmWareovhapp->getDatacenterLocation($vmName);
$appCredetail = $vmWareovhapp->getAPP_Detail($datacenterLocation);
$vmWareovhapp->setAPP_Detail($appCredetail);
$allIps = json_decode($vmWareovhapp->appGetAllIps(), true);
$ipArr = [];
foreach ($getVmIps as $ip) {
    $ipArr[] = $ip['ip'];
}
$reqIp = $whmcs->get_req_var('ip');

foreach ($allIps as $ipPool) {
    $ipBlock = (string) $ipPool;
    $ipBlockArr = explode('/', $ipBlock);
    if (in_array($reqIp, $ipArr)) {
        if ($vmWareovhapp->appIpInRange($reqIp, $ipBlock)) {
            if (preg_match('/^(?:25[0-5]|2[0-4]\d|1\d\d|[1-9]\d|\d)(?:[.](?:25[0-5]|2[0-4]\d|1\d\d|[1-9]\d|\d)){3}$/', $ipBlockArr[0])) {
                $assignedIpBlock = $ipBlock;
                $assignedIp = $ipBlockArr[0];
                $ipDetail = json_decode($vmWareovhapp->appGetIpDetail($ipBlock), true);
                $ipCountry = $ipDetail['country'];
                $ipServiceName = $ipDetail['routedTo']['serviceName'];
                $ipType = $ipDetail['type'];
            }
//        filter_var($ipBlock[0], FILTER_VALIDATE_IP);
        }
    }
}
$html = [
    'ipArr' => $getVmIps,
    'ovhIpDetail' => $ipDetail,
    'dc_location' => $datacenterLocation
];
