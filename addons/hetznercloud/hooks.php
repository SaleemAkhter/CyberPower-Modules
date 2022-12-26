<?php
use WHMCS\Database\Capsule;
use WHMCS\Createoptions\HetznerApi as HetznerApicall;

if (file_exists(__DIR__ . '/class.CreateFields.php'))
include_once __DIR__ . '/class.CreateFields.php';

add_hook('InvoicePaid', 1, function($vars) {				
		$invoiceID= $vars['invoiceid'];
		$invoiceIDdetails = Capsule::table('hetznercloud_additionalOrder_ips')->where('invoiceID', $invoiceID)->first();
		$apiKey= Capsule::table("tblhosting")->join("tblproducts","tblhosting.packageid","=","tblproducts.id")->select("tblproducts.configoption1")->where("tblhosting.id","=",$invoiceIDdetails->serviceID)->first();
		$HetznerApicall = new HetznerApicall($apiKey->configoption1);
		if($invoiceIDdetails->paid_status == 0){
			$no_floatingIP_id = $invoiceIDdetails->no_floatingIP_id;

			$currentTime=time();        
			$currentDate= date("Y-m-d",$currentTime);
			$floating_ips_post_data= json_encode( array(
				'type' => $invoiceIDdetails->protocol_type, 
				'server' => $invoiceIDdetails->serverID, 
				'home_location' => $invoiceIDdetails->home_location, 
				'description' => 'Floating IP'.$invoiceIDdetails->serviceID.'_'.$currentDate, 
			));

			for ($i=0; $i < $no_floatingIP_id; $i++) 
			{ 
				$create_floating_ip_apicall= $HetznerApicall->post('floating_ips', $floating_ips_post_data);
				logModuleCall('hetznercloud', 'Create Floating IP', $floating_ips_post_data, $create_floating_ip_apicall->floating_ip);

				if (!empty($create_floating_ip_apicall->action->error))
					return "Error : " . $create_server_response->action->error->message;
				$IP_array_data= array([
					'floatingIP_id' => $create_floating_ip_apicall->floating_ip->id,
					'IP_address' => $create_floating_ip_apicall->floating_ip->ip,
					'protocol_type' => $create_floating_ip_apicall->floating_ip->type,
					'description' => $create_floating_ip_apicall->floating_ip->description,
					'home_location' => $create_floating_ip_apicall->floating_ip->home_location->name,
					'serverID' => $invoiceIDdetails->serverID,
					'serviceID' => $invoiceIDdetails->serviceID,
				]);
					
				if (Capsule::table('hetznercloud_floating_ips')
					->where('floatingIP_id', $IP_array_data[0]['floatingIP_id'])
					->where('serverID', $IP_array_data[0]['serverID'])
					->where('serviceID',$IP_array_data[0]['serviceID'])
					->count() == 0) 
				{            
					Capsule::table('hetznercloud_floating_ips')->insert($IP_array_data);
				}else
				{
					Capsule::table('hetznercloud_floating_ips')
					->where('floatingIP_id', $IP_array_data[0]['floatingIP_id'])
					->where('serverID', $IP_array_data[0]['serverID'])
					->where('serviceID',$IP_array_data[0]['serviceID'])
					->update($IP_array_data);
				}  
				logModuleCall('hetznercloud', 'Insert additional Floating IP in Database', $floating_ips_post_data, $create_floating_ip_apicall);
			} 
			$insertData= array('paid_status' => 1); 
			Capsule::table('hetznercloud_additionalOrder_ips')
			->where('invoiceID', $invoiceID)
			->where('serverID', $invoiceIDdetails->serverID)
			->where('serviceID',$invoiceIDdetails->serviceID)
			->update($insertData); 
		}
});
