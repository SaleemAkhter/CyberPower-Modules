<?php

$userName = $params['clientsdetails']['fullname'];
$serverDetailForEmail .= '<br/><p><strong>' . $_LANG['vm_name'] . ':</strong>&nbsp;' . $newVmname . '</p>';
$serverDetailForEmail .= '<br/><p><strong>' . $_LANG['vm_username'] . ':</strong>&nbsp;' . $serviceUserName . '</p>';
if (!empty($vmPassword)) {
    $serverDetailForEmail .= '<p><strong>' . $_LANG['vm_email_systempw'] . ':</strong>&nbsp;' . $vmPassword . '.</p>';
}
$values["messagename"] = 'Server Reinstall Notification Email';
$values["customvars"] = base64_encode(serialize(array("user_name" => $userName, "reinstall_detail" => $serverDetailForEmail)));
$adminuser = '';
$command = "sendemail";
$values["id"] = $params['serviceid'];
$results = localAPI($command, $values, $adminuser);

?>
