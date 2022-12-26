<?php

use Illuminate\Database\Capsule\Manager as Capsule;

include_once __DIR__ . "/../../../../init.php";

if (file_exists(__DIR__ . '/../class/class.php'))
	include_once __DIR__ . '/../class/class.php';

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

$allVms = wgsGetAllVms();

foreach ($allVms as $vm) {
	$getProductConfig = Capsule::table('tblhosting')->select('tblproducts.id as pid', 'tblproducts.configoption3', 'tblhosting.domainstatus')->join('tblproducts', 'tblhosting.packageid', '=', 'tblproducts.id')->where('tblhosting.id', $vm->sid)->first();
	$sName = $getProductConfig->configoption3;
	$serverData = Capsule::table('mod_vmware_server')->where('server_name', $sName)->first();

	if (count($serverData) == 0)
		$serverData = Capsule::table('mod_vmware_server')->where('id', $sName)->first();
	$getip = explode('://', $serverData->vsphereip);
	$WgsVmwareObj = new WgsVmware();
	$WgsVmwareObj->vmware_includes_files();
	$decryptPw = $WgsVmwareObj->wgsVmwarePwEncryptDcrypt($serverData->vspherepassword);

	$vms = new vmware($getip[1], $serverData->vsphereusername, $decryptPw);
	
	$getCfId = Capsule::table('tblcustomfields')->select('id')->where('relid', $getProductConfig->pid)->where('type', 'product')->where('fieldname', 'like', '%vm_name%')->first();

	$getVmName = Capsule::table('tblcustomfieldsvalues')->where('fieldid', $getCfId->id)->where('relid', $vm->sid)->first();
	$vmName = $getVmName->value;
	if ($vmName) {
		$vmInfo = $vms->get_vm_guest($vmName);
		if ($vmInfo != "" && $getProductConfig->domainstatus == "Active") {
			$netInfo = $vmInfo->getGuestInfo()->net;
			foreach ($netInfo as $net) {
				foreach ($net->ipConfig->ipAddress as $ip) {
					if ($ip->prefixLength == '24') {
						if (filter_var($ip->ipAddress, FILTER_VALIDATE_IP)) {
							Capsule::table('tblhosting')->where('id', $vm->sid)->update(['dedicatedip' => $ip->ipAddress]);
							Capsule::table("mod_vmware_vm_ip")->where('id', $vm->id)->update(['status' => '1']);
							logActivity("Successfully updated IP for service id: {$vm->sid}");
							break;
						}
					}
				}
			}
		}
	}
}
echo "Done";

function wgsGetAllVms()
{
	return Capsule::table("mod_vmware_vm_ip")->where('status', '0')->get();
}
