<?php

# Zone Details
$result = $CF->getZoneDetails();

$vars["currentplan"] = $result["result"]["plan"];

# Zone Plans
$zoneplans = $CF->zonePlans();

$currentplan = $result["result"]["plan"]['legacy_id'];

foreach ($zoneplans['result'] as $key => $value) {
    if ($value['legacy_id'] == $currentplan) {
        $zonearraykey = $key;
        break;
    }
}

unset($zoneplans['result'][$zonearraykey]);

$vars["availablezoneplans"] = $zoneplans["result"];

$templateFile = "template/changeplantab/changeplan";
?>
