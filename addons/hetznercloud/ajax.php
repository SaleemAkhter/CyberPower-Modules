<?php 
use WHMCS\Database\Capsule;

global $whmcs;
$customaction = $whmcs->get_req_var('customaction'); 
$graphtime = $whmcs->get_req_var('time');

if ($customaction =='countFIPStatus') {
	$floatingIPcount= ($params['configoption6']=='on')?$params['configoption10']:$params['configoptions']['floating_ips'];
	$usedFloatIP= count(getFloatingIPdatabase($serverID, $params['serviceid']));
	$FIPcountStatus= json_encode(array(
		'floatingIPcount' => $floatingIPcount,
		'usedFloatIP'    => $usedFloatIP	
	));
	echo $FIPcountStatus; die();
}
if ($customaction =='rebuild') 
{
	if(preg_match("/[a-z]/i", $whmcs->get_req_var('os_image_selected')))
	{
		$newImageName= array('image'=> $whmcs->get_req_var('os_image_selected'));
		$body = json_encode($newImageName) ;
	}else
	{
		$getnewImageName=  $HetznerApicall->get('images/'.$whmcs->get_req_var('os_image_selected'));
		$newImageName= array('image'=>$getnewImageName->image->id);

		$body = json_encode($newImageName) ;
	}	 	
}
if ($customaction =='create_image') 
{
	if ( $snapshotAddon_selected !='yes') 
	{
		$error= array(
			'status' => 'error',
			'msg'    => 'Addon not in Package'	
		);	
		echo json_encode($error); die();				
	}
}

if ($serverID != '') 
{
	if ($whmcs->get_req_var('customaction') == 'gettable_backup') 
	{
		$get_backup_images= $HetznerApicall->get('images?type=backup');
		logModuleCall($params['moduletype'], 'Get backup Images Detail ', 'Server ID: ' . $serverID, $get_backup_images);
		$var='';
		foreach ($get_backup_images->images as $key =>  $backup_imagevalue) 
		{
			if ( $backup_imagevalue->created_from->id == $serverID) 
			{
				$var .= '<tr>
				<td id="'.$backup_imagevalue->id.'">'.$backup_imagevalue->description.'</td>
				<td>'.$backup_imagevalue->created.'</td>
				<td>'.$backup_imagevalue->disk_size.' GB</td>
				<td style="text-transform: uppercase;">'.$backup_imagevalue->status.'</td>
				<td>
				<div class="dropdown">
				<a  class=" dropdown-toggle" data-toggle="dropdown"></a>
				<div class="dropdown-menu">
				<a class="dropdown-item" style="cursor:pointer;text-transform: uppercase;" onclick="tableaction(\'rebuild\','.$backup_imagevalue->id.');">'.$moduleLangVar["rebuild"].'</a>';
				if ($snapshotAddon_selected == 'yes') 
				{
					$var .=	'<a class="dropdown-item" style="cursor:pointer;text-transform: uppercase;" onclick="tableaction(\'convertToSnapshot\','.$backup_imagevalue->id.',\'&type=snapshot\');">'.$moduleLangVar["convertTosnapModalHeading"].'</a>';

				}
				$var .= '<a class="dropdown-item" style="cursor:pointer;text-transform: uppercase;" onclick="tableaction(\'deleteImage\','.$backup_imagevalue->id.');">'.$moduleLangVar["delete"].'</a>
				</div>
				</div>
				</td>
				</tr>';
			}
		}
		echo $var ;die();
	}
	else if ($whmcs->get_req_var('customaction') == 'gettable_snapshot') 
	{
		$get_snapshot_images= $HetznerApicall->get('images?type=snapshot');
		logModuleCall($params['moduletype'], 'Get snapshot Images Detail ', 'Server ID: ' . $serverID, $get_snapshot_images);
		$var='';
		foreach ($get_snapshot_images->images as $key =>  $snapshot_imagevalue) 
		{
			if ( $snapshot_imagevalue->created_from->id == $serverID) 
			{
				$var .= '<tr>
				<td id="'.$snapshot_imagevalue->id.'">'.$snapshot_imagevalue->description.'</td>
				<td>'.$snapshot_imagevalue->created.'</td>
				<td>'.$snapshot_imagevalue->disk_size.' GB</td>
				<td style="text-transform: uppercase;">'.$snapshot_imagevalue->status.'</td>
				<td>
				<div class="dropdown">
				<a  class=" dropdown-toggle" data-toggle="dropdown"></a>
				<div class="dropdown-menu">
				<a class="dropdown-item" style="cursor:pointer;" onclick="tableaction(\'rebuild\','.$snapshot_imagevalue->id.');">'.$moduleLangVar["rebuild"].'</a>
				<a class="dropdown-item"  style="cursor:pointer;text-transform: uppercase;" onclick="tableaction(\'change_description\','.$snapshot_imagevalue->id.');">'.$moduleLangVar["changesDesc"].'</a>';
				if ($snapshot_imagevalue->protection->delete == true) 
				{
					$var .= '<a class="dropdown-item" onclick="tableaction(\'change_protection\','.$snapshot_imagevalue->id.',\'&delete=false\');" style="cursor: pointer;text-transform: uppercase;">'.$moduleLangVar["disableProtection"].'</a><a class="dropdown-item"  onclick="tableaction(\'deleteImage\','.$snapshot_imagevalue->id.');"  style="cursor: not-allowed;">'.$moduleLangVar["delete"].'</a>';
				}else
				{
					$var .= '<a class="dropdown-item"  style="cursor:pointer;text-transform: uppercase;" onclick="tableaction(\'change_protection\','.$snapshot_imagevalue->id.',\'&delete=true\');">'.$moduleLangVar["enableProtection"].'</a>
					<a class="dropdown-item" style="cursor:pointer;text-transform: uppercase;" onclick="tableaction(\'deleteImage\','.$snapshot_imagevalue->id.');"  >'.$moduleLangVar["delete"].'</a>';
				}
				$var .= '</div>
				</div>
				</td>
				</tr>';
			} 
			
		}           
		echo $var ;die();

	}

	
	else if ($whmcs->get_req_var('customaction') == 'change_description') 
	{

		$Selected_imageID= $whmcs->get_req_var('os_image_selected');

		$imageName= array('description'=> $whmcs->get_req_var('imageName'));
		$body = json_encode($imageName) ;
		try {
			$customaction_api_call_response= $HetznerApicall->put("images/".$Selected_imageID,$body);
			logModuleCall($params['moduletype'], $customaction , ' Image ID: ' . $Selected_imageID, $customaction_api_call_response);
			
			sleep(3);
			print_r(json_encode($customaction_api_call_response)) ;die();

		} catch (Exception $e) 
		{
			logModuleCall(
				'hetznercloud',
				__FUNCTION__,
				$params,
				$e->getMessage(),
				$e->getTraceAsString()
			);
			$error = array(
				'status' => 'error',
				'msg' => $e->getMessage(),
			);
			print_r(json_encode($error));die();
		}
	}
	
	else if ($customaction =='rebuild') 
	{
		try{$customaction_api_call_response= $HetznerApicall->post("servers/".$serverID."/actions/".$customaction,$body);
		logModuleCall($params['moduletype'], 'Server '.$customaction, ' Server ID: ' . $serverID.'- Request Parameter :'.$body, $customaction_api_call_response);
		$os_customfiled_data = $HetznerApicall->getproductconfigurableSuboptionID($params['pid'],'images',$newImageName['image']);

		if ($customaction_api_call_response->error)
		{
			$error = array(
				'status' => 'error',
				'msg' => $customaction_api_call_response->error->message,
			);

		}else
		{
			$os_customfiled_data= $HetznerApicall->sethsotingconfigurableOptionID($params['serviceid'],$os_customfiled_data['configid'],$os_customfiled_data['new_os_selected_id']);
			if (is_numeric($newImageName['image']))
			{
				$server_get_Newimage_os = $HetznerApicall->get('images/'.$newImageName['image']);
				logModuleCall($params['moduletype'], 'Get OS Image name while rebuild','OS image ID '.$newImageName['image'], $results);
				$os_image_name=  $server_get_Newimage_os->image->description;
			}else
			{				
				$os_image_name = strtoupper($newImageName['image']);
			}
			$command = 'SendEmail';
			$postData = array(				
				'messagename' => 'Server Rebuild Information',
				'id' => $params['serviceid'],						
				'customvars' => base64_encode(serialize(array("new_root_username"=> 'root', "new_root_password"=>$customaction_api_call_response->root_password,"os_image_name" => $os_image_name))),
			);
			$adminUsername = '';
			$results = localAPI($command, $postData, $adminUsername);
			logModuleCall($params['moduletype'], 'Server '.$customaction.'E-mail Log', $postData, $results);
		}
		sleep(10);
		print_r(json_encode($customaction_api_call_response)) ;die();
	}catch (Exception $e) 
	{
		logModuleCall(
			'hetznercloud',
			__FUNCTION__,
			$params,
			$e->getMessage(),
			$e->getTraceAsString()
		);
		$error = array(
			'status' => 'error',
			'msg' => $e->getMessage(),
		);
		print_r(json_encode($error));die();
	}
}
else if ($customaction =="metrics_live") 
{
	$datetime = date('y-m-d h:i:s');
	if ($graphtime == '12hr') 
	{	
		$end= $HetznerApicall->TimestampToIso8601(strtotime($datetime)); 	
		//$newtime = date('Y-m-d H:i:s',strtotime($end . ' -12 hours'));	
		$start= $HetznerApicall->TimestampToIso8601(strtotime($datetime . ' -12 hours'));	
	}
	else if ($graphtime == '24hr') 
	{
		$end= $HetznerApicall->TimestampToIso8601(strtotime($datetime)); 	
		//$newtime = date('Y-m-d H:i:s',strtotime($end . ' -24 hours'));	
		$start= $HetznerApicall->TimestampToIso8601(strtotime($datetime . ' -24 hours'));

	}else if ($graphtime == 'week') 
	{
		$end= $HetznerApicall->TimestampToIso8601(strtotime($datetime)); 	
		//$newtime = date('Y-m-d H:i:s',strtotime($end . ' -7 days'));	
		$start= $HetznerApicall->TimestampToIso8601(strtotime($datetime . ' -7 days'));

	}
	else if ($graphtime == 'month') 
	{
		$end= $HetznerApicall->TimestampToIso8601(strtotime($datetime)); 	
		//$newtime = date('Y-m-d H:i:s',strtotime($end . ' -30 days'));	
		$start= $HetznerApicall->TimestampToIso8601(strtotime($datetime . ' -30 days'));

	}else
	{
		$end= $HetznerApicall->TimestampToIso8601(strtotime($datetime)); 	
		//$newtime = date('Y-m-d H:i:s',strtotime($end . ' -5 minutes'));	
		$start= $HetznerApicall->TimestampToIso8601(strtotime($datetime . ' -5 minutes'));
	}	 


	/*graph section on CPU*/
	$resp_cpu= $HetznerApicall->get('servers/'.$serverID.'/metrics?type=cpu&start='.$start.'&end='.$end);
	logModuleCall($params['moduletype'], 'Get Server CPU Metrics ', 'Server ID: ' . $serverID, $resp_cpu);
	$resp_cpu = json_decode(json_encode($resp_cpu), true);
	$cpu_dataPoints = '';
	foreach ($resp_cpu['metrics']['time_series']['cpu']['values'] as $key => $value) 
	{ 		
		$cpu_dataPoints_datevalue2 = dateformatutc($value[0]);
		$cpu_dataPoints.= "[" . $cpu_dataPoints_datevalue2 . ", " . $value[1] . "],";
	}
	$cpu_dataPoints = rtrim($cpu_dataPoints, ",");
	$resp_cpu_script= datapointGenerator($cpu_dataPoints,null,' %','CPU',null,null,1);

	/*graph section on Disk*/
	$resp_disk= $HetznerApicall->get('servers/'.$serverID.'/metrics?type=disk&start='.$start.'&end='.$end);
	logModuleCall($params['moduletype'], 'Get Server Disk Metrics ', 'Server ID: ' . $serverID, $resp_disk);
	$resp_disk = json_decode(json_encode($resp_disk), true);

	$disk_dataPoints_iopsRead = '';
	$disk_dataPoints_iopsWrite = '';
	$disk_dataPoints_bandwidthRead = '';
	$disk_dataPoints_bandwidthWrite = '';

	/*DISK IOPS*/
	foreach ($resp_disk['metrics']['time_series']['disk.0.iops.read']['values'] as $keyread => $valueRead) 
	{ 
		//$disk_dataPoints_iopsRead[$keyread]= ["x" => $valueRead[0]*1000, "y" => $valueRead[1]];
		$disk_dataPoints_iopsRead_datevalue2 = dateformatutc($valueRead[0]);
		$disk_dataPoints_iopsRead.= "[" . $disk_dataPoints_iopsRead_datevalue2 . ", " . $valueRead[1] . "],";
	}
	$disk_dataPoints_iopsRead = rtrim($disk_dataPoints_iopsRead, ",");


	foreach ($resp_disk['metrics']['time_series']['disk.0.iops.write']['values'] as $keyWrite => $valueWrite) 
	{ 
		//$disk_dataPoints_iopsWrite[$keyWrite]= ["x" => $valueWrite[0]*1000, "y" => $valueWrite[1]];
		$disk_dataPoints_iopsWrite_datevalue2 = dateformatutc($valueWrite[0]);
		$disk_dataPoints_iopsWrite.= "[" . $disk_dataPoints_iopsWrite_datevalue2 . ", " . $valueWrite[1] . "],";
	}
	$disk_dataPoints_iopsWrite = rtrim($disk_dataPoints_iopsWrite, ",");

	$diskiops_script=  datapointGenerator($disk_dataPoints_iopsRead,$disk_dataPoints_iopsWrite,' iops',$moduleLangVar["diskiops"],$moduleLangVar["read"],$moduleLangVar["write"],3);

	/*DISK THROUGHPUT*/
	foreach ($resp_disk['metrics']['time_series']['disk.0.bandwidth.read']['values'] as $keyreadband => $valueReadbandwidth) 
	{ 
		//$disk_dataPoints_bandwidthRead[$keyreadband]= ["x" => $valueReadbandwidth[0]*1000, "y" => ($valueReadbandwidth[1] / 1024)];
		
		$disk_dataPoints_bandwidthRead_datevalue2 = dateformatutc($valueReadbandwidth[0]);
		$disk_dataPoints_bandwidthRead.= "[" . $disk_dataPoints_bandwidthRead_datevalue2 . ", " . $valueReadbandwidth[1] . "],";
	}
	$disk_dataPoints_bandwidthRead = rtrim($disk_dataPoints_bandwidthRead, ",");

	foreach ($resp_disk['metrics']['time_series']['disk.0.bandwidth.write']['values'] as $keyWriteband => $valueWritebandwidth) 
	{ 
		//$disk_dataPoints_bandwidthWrite[$keyWriteband]= ["x" => $valueWritebandwidth[0]*1000, "y" => ($valueWritebandwidth[1] / 1024)];
		
		$disk_dataPoints_bandwidthWrite_datevalue2 = dateformatutc($valueWritebandwidth[0]);
		$disk_dataPoints_bandwidthWrite.= "[" . $disk_dataPoints_bandwidthWrite_datevalue2 . ", " . $valueWritebandwidth[1] . "],";
	}
	$disk_dataPoints_bandwidthWrite = rtrim($disk_dataPoints_bandwidthWrite, ",");

	$diskthroughput_script=  datapointGenerator($disk_dataPoints_bandwidthRead,$disk_dataPoints_bandwidthWrite,' KBps',$moduleLangVar["diskthroughput"],$moduleLangVar["read"],$moduleLangVar["write"],2);

	/*graph section on Network*/
	$resp_network= $HetznerApicall->get('servers/'.$serverID.'/metrics?type=network&start='.$start.'&end='.$end);
	logModuleCall($params['moduletype'], 'Get Server Network Metrics ', 'Server ID: ' . $serverID, $resp_network);
	$resp_network = json_decode(json_encode($resp_network), true);

	$network_dataPoints_ppsIn = '';
	$network_dataPoints_ppsOut = '';
	$network_dataPoints_bandwidthIn = '';
	$network_dataPoints_bandwidthOut = '';
	$scriptArray=[];
	/*NETWORK PPS*/
	foreach ($resp_network['metrics']['time_series']['network.0.pps.in']['values'] as $keyPPSin => $valuePPSin) 
	{ 
		//$network_dataPoints_ppsIn[$keyPPSin]= ["x" => $valuePPSin[0]*1000, "y" => $valuePPSin[1]];
		
		$network_dataPoints_ppsIn_datevalue2 = dateformatutc($valuePPSin[0]);
		$network_dataPoints_ppsIn.= "[" . $network_dataPoints_ppsIn_datevalue2 . ", " . $valuePPSin[1] . "],";
	}
	$network_dataPoints_ppsIn = rtrim($network_dataPoints_ppsIn, ",");

	foreach ($resp_network['metrics']['time_series']['network.0.pps.out']['values'] as $keyPPSout => $valuePPSout) 
	{ 
		//$network_dataPoints_ppsOut[$keyPPSout]= ["x" => $valuePPSout[0]*1000, "y" => $valuePPSout[1]];
		
		$network_dataPoints_ppsOut_datevalue2 = dateformatutc($valuePPSout[0]);
		$network_dataPoints_ppsOut.= "[" . $network_dataPoints_ppsOut_datevalue2 . ", " . $valuePPSout[1] . "],";
	}
	$network_dataPoints_ppsOut = rtrim($network_dataPoints_ppsOut, ",");

	$networkPPS_script=  datapointGenerator($network_dataPoints_ppsIn,$network_dataPoints_ppsOut,' pps',$moduleLangVar["networkpps"],$moduleLangVar["netIn"],$moduleLangVar["netOut"],4);

	/*NETWORK TRAFFIC*/
	foreach ($resp_network['metrics']['time_series']['network.0.bandwidth.in']['values'] as $keyNetbandin => $valueNetbandin) 
	{ 
		//$network_dataPoints_bandwidthIn[$keyNetbandin]= ["x" => $valueNetbandin[0]*1000, "y" => $valueNetbandin[1]];
		
		$network_dataPoints_bandwidthIn_datevalue2 = dateformatutc($valueNetbandin[0]);
		$network_dataPoints_bandwidthIn.= "[" . $network_dataPoints_bandwidthIn_datevalue2 . ", " . $valueNetbandin[1] . "],";
	}
	$network_dataPoints_bandwidthIn = rtrim($network_dataPoints_bandwidthIn, ",");

	foreach ($resp_network['metrics']['time_series']['network.0.bandwidth.out']['values'] as $keyNetbandout => $valueNetbandout) 
	{ 
		//$network_dataPoints_bandwidthOut[$keyNetbandout]= ["x" => $valueNetbandout[0]*1000, "y" => $valueNetbandout[1]];
		$network_dataPoints_bandwidthOut_datevalue2 = dateformatutc($valueNetbandout[0]);
		$network_dataPoints_bandwidthOut.= "[" . $network_dataPoints_bandwidthOut_datevalue2 . ", " . $valueNetbandout[1] . "],";
	}
	$network_dataPoints_bandwidthOut = rtrim($network_dataPoints_bandwidthOut, ",");	
	$networkBandwidth_script=  datapointGenerator($network_dataPoints_bandwidthIn,$network_dataPoints_bandwidthOut,' Bps',$moduleLangVar["networktraffic"],$moduleLangVar["netIn"],$moduleLangVar["netOut"],5);

	$scriptArray = [
		'cpu'=> $resp_cpu_script,
		'disk_iops'=> $diskiops_script,
		'disk_throughput'=> $diskthroughput_script,
		'netPPS'=> $networkPPS_script,
		'netbandwidth'=> $networkBandwidth_script
	]; 

	print_r(json_encode($scriptArray));	die();
	

}
else if($customaction=="os_image_info")
{
	$getServerDetail_forImage= $HetznerApicall->get('servers/'.$serverID);
	logModuleCall($params['moduletype'], 'Get Sever info for OS Images Details', 'Server ID: ' . $serverID, $getServerDetail_forImage);
	$server_forImage= $getServerDetail_forImage->server->image;	
	print json_encode($server_forImage) ;
	die();
}
else if ($customaction =="getimages") 
{
	$get_images= $HetznerApicall->get('images');
	logModuleCall($params['moduletype'], 'Get OS Images Detail ', 'Server ID: ' . $serverID, $get_images);
	$server_images_array= $get_images->images; 
	print_r(json_encode($server_images_array)) ;die();
}else if ($customaction =="getisos") 
{
	$get_isos= $HetznerApicall->get('isos?page=1&per_page=50');
	logModuleCall($params['moduletype'], 'Get ISOs', 'Server ID: ' . $serverID, $get_isos);
	$server_iso_array = $get_isos->isos;
	$option = $option2 = '';
	foreach($server_iso_array as $iso){
		if (strchr(strtolower($iso->name), 'virtio'))
			$option2 .= '<option value="'.$iso->name.'">'.$iso->description.'</option>';
		else
			$option .= '<option value="'.$iso->name.'">'.$iso->description.'</option>';
	}
	echo $option2.$option;
	die();
}

else if ($customaction =="getvol_size") 
{
	$server_get_volumeSize= $HetznerApicall->get('volumes/'.$server_volumeID);
	logModuleCall($params['moduletype'], 'Get Volume Detail ', 'Volume ID: ' . $server_volumeID, $server_get_volumeSize);
	$server_volumeSize= $server_get_volumeSize->volume->size;
	print_r($server_volumeSize) ;die();
}
else if($customaction =="convertToSnapshot") 
{
	//echo "test"; die();
	$Selected_imageID= $whmcs->get_req_var('os_image_selected');

	$type= array('type'=> $whmcs->get_req_var('type'));
	$body = json_encode($type) ;
	try {
		$customaction_api_call_response= $HetznerApicall->put("images/".$Selected_imageID,$body);
		logModuleCall($params['moduletype'], $customaction , ' Image ID: ' . $Selected_imageID, $customaction_api_call_response);

		sleep(3);
		print_r(json_encode($customaction_api_call_response)) ;die();

	} catch (Exception $e) 
	{
		logModuleCall(
			'hetznercloud',
			__FUNCTION__,
			$params,
			$e->getMessage(),
			$e->getTraceAsString()
		);
		$error = array(
			'status' => 'error',
			'msg' => $e->getMessage(),
		);
		print_r(json_encode($error));die();
	}
}
else if($customaction == "deleteImage" )
{
	$Selected_imageID= $whmcs->get_req_var('os_image_selected');
	try {
		$customaction_api_call_response= $HetznerApicall->delete("images/".$Selected_imageID);
		logModuleCall($params['moduletype'], $customaction , ' Image ID: ' . $Selected_imageID, $customaction_api_call_response);

		sleep(3);
		print_r(json_encode($customaction_api_call_response)) ;die();

	} catch (Exception $e) 
	{
		logModuleCall(
			'hetznercloud',
			__FUNCTION__,
			$params,
			$e->getMessage(),
			$e->getTraceAsString()
		);
		$error = array(
			'status' => 'error',
			'msg' => $e->getMessage(),
		);
		print_r(json_encode($error));die();
	}

}
else if($customaction == "unmountiso" )
{
	try {
		$customaction_api_call_response = $HetznerApicall->post("servers/".$serverID."/actions/detach_iso", null);
		logModuleCall($params['moduletype'], "Unmount ISO" , ' Server ID: ' . $serverID, $customaction_api_call_response);

		sleep(3);
		if (!$customaction_api_call_response->error){
			print_r(json_encode(['status' => 'success']));
			die();
		}else{
			print_r(json_encode(['status' => 'error', 'message' => $customaction_api_call_response->error->message]));
			die();
		}

	} catch (Exception $e) 
	{
		logModuleCall(
			'hetznercloud',
			__FUNCTION__,
			$params,
			$e->getMessage(),
			$e->getTraceAsString()
		);
		$error = array(
			'status' => 'error',
			'msg' => $e->getMessage(),
		);
		print_r(json_encode($error));die();
	}

}
else if($customaction == "mountiso" )
{
	$isoId = $whmcs->get_req_var('iso');
	try {
		$parameter = array('iso'=> $isoId);
		$body = json_encode($parameter) ;
		$customaction_api_call_response = $HetznerApicall->post("servers/".$serverID."/actions/attach_iso", $body);
		logModuleCall($params['moduletype'], "Mount Iso" , ' ISO ID: ' . $isoId, $customaction_api_call_response);

		sleep(3);
		if (!$customaction_api_call_response->error){
			print_r(json_encode(['status' => 'success']));
			die();
		}else{
			print_r(json_encode(['status' => 'error', 'message' => $customaction_api_call_response->error->message]));
			die();
		}

	} catch (Exception $e) 
	{
		logModuleCall(
			'hetznercloud',
			__FUNCTION__,
			$params,
			$e->getMessage(),
			$e->getTraceAsString()
		);
		$error = array(
			'status' => 'error',
			'msg' => $e->getMessage(),
		);
		print_r(json_encode($error));die();
	}

}
else if($customaction == "change_protection" )
{
	$Selected_imageID= $whmcs->get_req_var('os_image_selected');
	$protection= array('delete'=> $whmcs->get_req_var('delete'));
	$body = json_encode($protection) ;

	try {
		$customaction_api_call_response= $HetznerApicall->post("images/".$Selected_imageID."/actions/change_protection",$body);
		logModuleCall($params['moduletype'], $customaction , ' Image ID: ' . $Selected_imageID, $customaction_api_call_response);			
		sleep(3);
		print_r(json_encode($customaction_api_call_response)) ;die();

	} catch (Exception $e) 
	{
		logModuleCall(
			'hetznercloud',
			__FUNCTION__,
			$params,
			$e->getMessage(),
			$e->getTraceAsString()
		);
		$error = array(
			'status' => 'error',
			'msg' => $e->getMessage(),
		);
		print_r(json_encode($error));die();
	}
}
else if ($whmcs->get_req_var('customaction')=='floating_ips') {
	$get_floatingIPs= $HetznerApicall->get('floating_ips');	
	$floatingIPsInDB= getFloatingIPdatabase($serverID, $params['serviceid']);
	logModuleCall($params['moduletype'], 'Get Floating IPs ', 'Server ID: ' . $serverID, $get_floatingIPs);
	foreach ($floatingIPsInDB as $fldbkey => $fldbvalue) {
		$fldbarray[]= $fldbvalue->IP_address;
	}		
	$var='';
	foreach ($get_floatingIPs->floating_ips as $key =>  $floating_ips_value) {
		if (in_array($floating_ips_value->ip, $fldbarray)) {
			$html .= '<tr>
			<td >'.$floating_ips_value->ip.'</td>
			<td id="'.$floating_ips_value->id.'">'.$floating_ips_value->description.'</td>
			<td id="dns_ptr'.$floating_ips_value->id.'">'.$floating_ips_value->dns_ptr[0]->dns_ptr.'</td>
			<td style="text-transform: capitalize;">'.$floating_ips_value->home_location->city.'</td>
			<td>
			<div class="dropdown">
			<a  class=" dropdown-toggle" data-toggle="dropdown"></a>
			<div class="dropdown-menu">
			<a class="dropdown-item" style="cursor:pointer;text-transform: uppercase;" data-toggle="modal" onclick="floatingIP_tableaction(\'floating_ip_instruction\',\''.$floating_ips_value->ip.'\');">'.$moduleLangVar["showInstruc"].'</a>
			<a class="dropdown-item"  style="cursor:pointer;text-transform: uppercase;" onclick="floatingIP_tableaction(\'floating_ip_change_description\','.$floating_ips_value->id.');">'.$moduleLangVar["changesDesc"].'</a>
			<a class="dropdown-item"  style="cursor:pointer;text-transform: uppercase;" onclick="floatingIP_tableaction(\'reverseDNSedit\','.$floating_ips_value->id.',\'&ip='.$floating_ips_value->ip.'\');">'.$moduleLangVar["fIpRevDnsModalHeading"].'</a>
			<a class="dropdown-item"  style="cursor:pointer;text-transform: uppercase;" onclick="floatingIP_tableaction(\'reverseDNSreset\','.$floating_ips_value->id.',\'&ip='.$floating_ips_value->ip.'\');">'.$moduleLangVar["resetRevDns"].'</a>';
			if ($floating_ips_value->protection->delete == true) {
				$html .= '<a class="dropdown-item" onclick="floatingIP_tableaction(\'change_IPprotection\','.$floating_ips_value->id.',\'&delete=false\');" style="cursor: pointer;text-transform: uppercase;">'.$moduleLangVar["disableProtection"].'</a>';
			}else{
				$html .= '<a class="dropdown-item"  style="cursor:pointer;text-transform: uppercase;" onclick="floatingIP_tableaction(\'change_IPprotection\','.$floating_ips_value->id.',\'&delete=true\');">'.$moduleLangVar["enableProtection"].'</a>';
			}
			if ($floating_ips_value->server != null) {
				$html .= '<a class="dropdown-item"  style="cursor:pointer;text-transform: uppercase;" onclick="floatingIP_tableaction(\'unassignFIP\','.$floating_ips_value->id.');">'.$moduleLangVar["flpunassign"].'</a>';
			} else {
				$html .= '<a class="dropdown-item"  style="cursor:pointer;text-transform: uppercase;" onclick="floatingIP_tableaction(\'assignFIP\','.$floating_ips_value->id.');">'.$moduleLangVar["flpassign"].'</a>';
			}			

			$html .= '</div>
			</div>
			</td>
			</tr>';
		} 

	}           
	echo $html ;die();

}
else if ($whmcs->get_req_var('customaction')=='floating_ip_change_description') {

	$Selected_floatingIPID= $whmcs->get_req_var('floatingIP_selected');

	$floating_ipName= array('description'=> $whmcs->get_req_var('floating_ipName'));
	$body = json_encode($floating_ipName) ;
	try {
		$customaction_api_call_response= $HetznerApicall->put("floating_ips/".$Selected_floatingIPID,$body);
		logModuleCall($params['moduletype'], $customaction , ' Floating IP ID: ' . $Selected_floatingIPID, $customaction_api_call_response);

		sleep(3);
		print_r(json_encode($customaction_api_call_response)) ;die();

	} catch (Exception $e) {
		logModuleCall(
			'hetznercloud',
			__FUNCTION__,
			$params,
			$e->getMessage(),
			$e->getTraceAsString()
		);
		$error = array(
			'status' => 'error',
			'msg' => $e->getMessage(),
		);
		print_r(json_encode($error));die();
	}
}  
else if ($whmcs->get_req_var('customaction')=='reverseDNSedit') {

	$Selected_floatingIPID= $whmcs->get_req_var('floatingIP_selected');

	$parameter= array('ip'=> $whmcs->get_req_var('ip'),'dns_ptr'=> $whmcs->get_req_var('dns_ptr'));
	$body = json_encode($parameter) ;
	try {
		$customaction_api_call_response= $HetznerApicall->post("floating_ips/".$Selected_floatingIPID."/actions/change_dns_ptr",$body);
		logModuleCall($params['moduletype'], $customaction , ' Edit Reverse DNS: ' . $Selected_floatingIPID, $customaction_api_call_response);

		sleep(3);
		print_r(json_encode($customaction_api_call_response)) ;die();

	} catch (Exception $e) {
		logModuleCall(
			'hetznercloud',
			__FUNCTION__,
			$params,
			$e->getMessage(),
			$e->getTraceAsString()
		);
		$error = array(
			'status' => 'error',
			'msg' => $e->getMessage(),
		);
		print_r(json_encode($error));die();
	}
}

else if ($whmcs->get_req_var('customaction')=='reverseDNSreset') {

	$Selected_floatingIPID= $whmcs->get_req_var('floatingIP_selected');

	$parameter= array('ip'=> $whmcs->get_req_var('ip'),'dns_ptr'=> null);
	$body = json_encode($parameter) ;
	try {
		$customaction_api_call_response= $HetznerApicall->post("floating_ips/".$Selected_floatingIPID."/actions/change_dns_ptr",$body);
		logModuleCall($params['moduletype'], $customaction , ' Reset Reverse DNS: ' . $Selected_floatingIPID, $customaction_api_call_response);

		sleep(3);
		print_r(json_encode($customaction_api_call_response)) ;die();

	} catch (Exception $e) {
		logModuleCall(
			'hetznercloud',
			__FUNCTION__,
			$params,
			$e->getMessage(),
			$e->getTraceAsString()
		);
		$error = array(
			'status' => 'error',
			'msg' => $e->getMessage(),
		);
		print_r(json_encode($error));die();
	}
}
else if($customaction == "change_IPprotection" ){

	$Selected_floatingIPID= $whmcs->get_req_var('floatingIP_selected');
	$protection= array('delete'=> $whmcs->get_req_var('delete'));
	$body = json_encode($protection) ;

	try {
		$customaction_api_call_response= $HetznerApicall->post("floating_ips/".$Selected_floatingIPID."/actions/change_protection",$body);
		logModuleCall($params['moduletype'], 'Change Floating IP Protection' , ' Floating ID: ' . $Selected_floatingIPID, $customaction_api_call_response);			
		sleep(3);
		print_r(json_encode($customaction_api_call_response)) ;die();

	} catch (Exception $e) {
		logModuleCall(
			'hetznercloud',
			__FUNCTION__,
			$params,
			$e->getMessage(),
			$e->getTraceAsString()
		);
		$error = array(
			'status' => 'error',
			'msg' => $e->getMessage(),
		);
		print_r(json_encode($error));die();
	}
}
else if ($whmcs->get_req_var('customaction')=='unassignFIP') {
	$Selected_floatingIPID = $whmcs->get_req_var('floatingIP_selected');
	$body=null;
	try {
		$customaction_api_call_response= $HetznerApicall->post("floating_ips/".$Selected_floatingIPID."/actions/unassign",$body);
		logModuleCall($params['moduletype'], 'Unassign Floating IP' , ' Floating ID: ' . $Selected_floatingIPID, $customaction_api_call_response);			
		sleep(3);
		if (!$customaction_api_call_response->error)
			print_r(json_encode(['status' => 'success']));	die();

	} catch (Exception $e) {
		logModuleCall(
			'hetznercloud',
			__FUNCTION__,
			$params,
			$e->getMessage(),
			$e->getTraceAsString()
		);
		$error = array(
			'status' => 'error',
			'msg' => $e->getMessage(),
		);
		print_r(json_encode($error));die();
	}
}
else if ($whmcs->get_req_var('customaction')=='assignFIP') {
	$Selected_floatingIPID = $whmcs->get_req_var('floatingIP_selected');
	$body= json_encode(array('server' => $serverID));
	try {
		$customaction_api_call_response = $HetznerApicall->post("floating_ips/".$Selected_floatingIPID."/actions/assign",$body);
		logModuleCall($params['moduletype'], 'Assign Floating IP' , ' Floating ID: ' . $Selected_floatingIPID, $customaction_api_call_response);			
		sleep(5);
		if (!$customaction_api_call_response->error)
			print_r(json_encode($customaction_api_call_response)) ;die();

	} catch (Exception $e) {
		logModuleCall(
			'hetznercloud',
			__FUNCTION__,
			$params,
			$e->getMessage(),
			$e->getTraceAsString()
		);
		$error = array(
			'status' => 'error',
			'msg' => $e->getMessage(),
		);
		print_r(json_encode($error));die();
	}
}
else if ($customaction == "addFloatingIP") {
	$serviceid = $params['serviceid'];
	$no_OF_floatIP= $whmcs->get_req_var('no_OF_floatIP');
	$floating_ips_type= $whmcs->get_req_var('floating_ips_type');
	//$currID= $whmcs->get_req_var('currID');
	$regdate = date('Y-m-d');
	$duedate = date('Y-m-d', strtotime($regdate . " +1 month"));
	//$currResult= Capsule::table('tblcurrencies')->where('id',$currID)->first();
	$productTaxStatus = Capsule::table('tblhosting')
            ->join('tblproducts', 'tblhosting.packageid', '=', 'tblproducts.id')
            ->select('tblproducts.tax')
            ->where('tblhosting.id', '=', $serviceid)
            ->get();

            try {
            	$htData = Capsule::table('tblhosting')->select('paymentmethod')->where('id', $params['serviceid'])->first();
            	$paymentmethod = $htData->paymentmethod;
            	$ipsProfitPrice= $no_OF_floatIP * $params["configoption5"];
            	$command = 'CreateInvoice';
            	$postData = array(
            		'userid' => $_SESSION['uid'],
            		'status' => 'Unpaid',
            		'paymentmethod' => $paymentmethod,
            		'sendinvoice' => '1',
            		'itemdescription1' => $no_OF_floatIP.' Additional Floating Ips  $ '. $ipsProfitPrice .'USD',
            		'itemamount1' => $ipsProfitPrice,
            		'itemtaxed1' => $productTaxStatus[0]->tax,
            		'date' => $regdate,
            		'duedate' => $duedate,
            	);
            	$adminUsername = '';
            	$results = localAPI($command, $postData, $adminUsername);
	

            	if (isset($results['invoiceid'])) {
            		$regdate = date('Y-m-d');
            		$duedate = date('Y-m-d', strtotime($regdate . " +1 month"));
            		$totalAmounts = $ipsProfitPrice;
            		$allDes = $no_OF_floatIP.' Additional Floating Ips  $ '. $ipsProfitPrice .'USD';
            		$insertData = array('hostingid' => $params['serviceid'], 'userid' => $_SESSION['uid'], 'billingcycle' => 'Monthly', 'recurring' => $totalAmounts, 'paymentmethod' => $paymentmethod, 'regdate' => $regdate, 'nextduedate' => $duedate, 'nextinvoicedate' => $duedate, 'name' => $allDes, 'status' => 'Pending');
            		$addonId = Capsule::table('tblhostingaddons')->insertGetId($insertData);
            		$invoiceQuery= Capsule::table('tblinvoiceitems')->where('invoiceid', $results['invoiceid'])->update(array('type' => 'Addon', 'relid' => $addonId));
            		if ($invoiceQuery){
            			$insertData= array([
            				'no_floatingIP_id' => $no_OF_floatIP,
            				'protocol_type' => $floating_ips_type,
            				'home_location' => ($params['configoption6']=='on')?$params['configoption7']:$params["configoptions"]["location"],
            				'serverID' => $serverID,
            				'serviceID' => $params['serviceid'],
            				'invoiceID' => $results['invoiceid'],
            				'userID' => $_SESSION['uid'],
            				'paid_status' => 0,]
            			);
            			insertAddFloatingIPDatabase($insertData);
            			$status = array('status' => 'success', 'msg' => '', 'invoiceid' => $results['invoiceid']);
            			echo json_encode($status);die();
            		}
            	}
            	else{
            		$error = array(
            			'status' => 'error',
            			'msg' => $e->getMessage(),
            		);
            		print_r(json_encode($error));die();
            	}

            } catch (Exception $e) {
            	logModuleCall(
            		'hetznercloud',
            		__FUNCTION__,
            		$params,
            		$e->getMessage(),
            		$e->getTraceAsString()
            	);
            	$error = array(
            		'status' => 'error',
            		'msg' => $e->getMessage(),
            	);
            	print_r(json_encode($error));die();

            }


}

else if($customaction == "currencyIDCode"){
	$currCode= $whmcs->get_req_var('currCode');
	$currResult= Capsule::table('tblcurrencies')->where('id',$currCode)->first();
	print_r(json_encode($currResult)); die();
}
else
{
	try {
		$customaction_api_call_response= $HetznerApicall->post("servers/".$serverID."/actions/".$customaction,null);
		logModuleCall($params['moduletype'], 'Server '.$customaction, ' Server ID: ' . $serverID, $customaction_api_call_response);
		if($customaction =='reset_password')
		{
			$command = 'SendEmail';
			$postData = array(				
				'messagename' => 'Reset server root password Information',
				'id' => $params['serviceid'],						
				'customvars' => base64_encode(serialize(array("new_root_username"=> 'root', "new_root_password"=>$customaction_api_call_response->root_password))),
			);
			$adminUsername = '';
			$results = localAPI($command, $postData, $adminUsername);
			logModuleCall($params['moduletype'], 'Server '.$customaction, $postData, $results);
		}		
		if ($customaction_api_call_response->error)
		{
			$error = array(
				'status' => 'error',
				'msg' => $customaction_api_call_response->error->message,
			);
			print_r(json_encode($error));die();

		}
		sleep(3);
		print_r(json_encode($customaction_api_call_response)) ;die();

	} catch (Exception $e) 
	{
		logModuleCall(
			'hetznercloud',
			__FUNCTION__,
			$params,
			$e->getMessage(),
			$e->getTraceAsString()
		);
		$error = array(
			'status' => 'error',
			'msg' => $e->getMessage(),
		);
		print_r(json_encode($error));die();
	}  
}           
}


function datapointGenerator($dataPoints1,$dataPoints2,$ysuffix,$graphtitle,$dname1,$dname2,$index)
{
	if ($dataPoints2 != null)
	{
		

		$script = '<script type="text/javascript">
		jQuery(function () 
		{
			jQuery("#container'.$index.'").highcharts({
				chart: {
					type: "spline"
					},
					title: {
						text: "'.$graphtitle.'"
						},					
						xAxis: {
							type: "datetime",
							dateTimeLabelFormats: { 
								hour: "%H:%M",
								day: "%e of %b",
								week: "%e. %b",
								month: "%b \"%y",
							}
							},
							yAxis: {
								title: {
									text: "'.$ysuffix.'"
									},
									min: 0
									},
									tooltip: {
										headerFormat: "<b>{series.name}</b><br>",
										pointFormat: "{point.x:%e. %b}: {point.y:.2f}",
										valueSuffix: "'.$ysuffix.'",
										},

										plotOptions: {
											spline: {
												marker: {
													enabled: true
												}
											}
											},

											colors: ["#6CF", "#39F", "#06C", "#036", "#000"],
											credits: {
												enabled: false
												},

												series: [{
													name: "'.$dname1.'",
													data: ['.$dataPoints1.'
													]
													}, {
														name: "'.$dname2.'",
														data: ['.$dataPoints2.'
														]
														}]
														});})

														</script>';
													}
													else
													{
														$script = '<script type="text/javascript">
														jQuery(function () 
														{
															jQuery("#container'.$index.'").highcharts({
																chart: {
																	type: "spline"
																	},
																	title: {
																		text: "'.$graphtitle.'"
																		},			
																		xAxis: {
																			type: "datetime",
																			dateTimeLabelFormats: { 
																				hour: "%H:%M",
																				day: "%e of %b",
																				week: "%e. %b",
																				month: "%b \"%y",
																			}
																			},
																			yAxis: {
																				title: {
																					text: "'.$ysuffix.'"
																					},
																					min: 0
																					},
																					tooltip: {
																						headerFormat: "<b>{series.name}</b><br>",
																						pointFormat: "{point.x:%e. %b}: {point.y:.2f}",
																						valueSuffix: "'.$ysuffix.'",
																						},

																						plotOptions: {
																							spline: {
																								marker: {
																									enabled: true
																								}
																							}
																							},

																							colors: ["#6CF", "#39F", "#06C", "#036", "#000"],
																							credits: {
																								enabled: false
																								},

																								series: [{
																									name: "'.$dname1.'",
																									data: ['.$dataPoints1.'
																									]
																									}]
																									});
																									})
																									</script>';

																								}
																								return $script;
																							}
																							function dateformatutc($ntime){
																								$newtime = date('Y-m-d H:i:s', $ntime);
																								$newtime01 = date('Y,m,d,H,i,s', strtotime($newtime . ' -1 months'));
																								$datevalue = 'Date.UTC(' . $newtime01 . ')';
																								return $datevalue;
																							}