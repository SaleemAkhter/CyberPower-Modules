<?php

namespace WHMCS\Createoptions;

use WHMCS\Database\Capsule;

class HetznerApi 
{
    public $Auth_key;
    function __construct($Auth_key) 
    {
        $this->Auth_key = $Auth_key;
    }
    public function call($method, $url, $body = null) 
    {
        try {

            $headers = array(
                'Content-Type: application/json',
                'Authorization: Bearer ' . $this->Auth_key,
            );
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, 'https://api.hetzner.cloud/v1/' . $url);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);

            if ($body != null || $body != '') {
                curl_setopt($ch, CURLOPT_POST, 1);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $body);
            }

            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
                curl_setopt($ch, CURLOPT_TIMEOUT, 400); //timeout in seconds
                $response = curl_exec($ch);
                curl_close($ch);
                $resp = json_decode($response);
                return $resp;
            } catch (Exception $e) {
                logModuleCall(
                    'hetznercloud', __FUNCTION__, $params, $e->getMessage(), $e->getTraceAsString()
                );

                return $e->getMessage();
            }
        }
        
        public function get($url) 
        {
            return $this->call('GET', $url, null);
        }

        public function put($url, $body) {
            return $this->call('PUT', $url, $body);
        }

        public function post($url, $body) 
        {
            return $this->call('POST', $url, $body);
        }

        public function delete($url) 
        {
            return $this->call('DELETE', $url, null);
        }
        /* Configurable option create function */

        public function hetznercloud_configurableOption($pid) 
        {
            $addconfigrablegroupname = "Server_config" . $pid;
            $addconfigurabledescription = "Server user quota";
            $addconfigurableoptionname = array(
                "images|Operating System",
                "location|Location",
                "volume|Volume",
                "volformat|Disk Format",
//"datacenter|Datacenter",
                "floating_ips|Floating IPs",
//"floating_ips_location|IP's Location",
                "floating_ips_type|IP's Protocol",           
            );
            $configurableoptionlinkresult = Capsule::table('tblproductconfiggroups')->where('name', $addconfigrablegroupname)->first();
            $configurablegroup_id = $configurableoptionlinkresult->id;
            if (!$configurablegroup_id) 
            {   
                $configurablegroup_id = Capsule::table('tblproductconfiggroups')
                ->insertGetId(
                    [
                        "name" => $addconfigrablegroupname,
                        "description" => $addconfigurabledescription
                    ]
                );
                if (Capsule::table('tblproductconfiglinks')->where([['gid', '=', $configurablegroup_id], ['pid', '=', $pid]])->count() == 0) {

                    Capsule::table('tblproductconfiglinks')->insert(
                        [
                            "gid" => $configurablegroup_id,
                            "pid" => $pid,
                        ]
                    );
                }            
                foreach ($addconfigurableoptionname as $key => $addconfigurableoptionnameVal) 
                {
                    $addconfigurableoptionnameexp = explode("|", $addconfigurableoptionnameVal);
                    $checkoptionname = Capsule::table('tblproductconfigoptions')->where([['gid', '=', $configurablegroup_id], ['optionname', 'like', '%' . $addconfigurableoptionnameexp[0] . '%']])->first();
                    if (!$checkoptionname) 
                    {
                        $allowadminpermission = Capsule::table('tblproducts')->select()->where('id', $pid)->first();

                        if ($addconfigurableoptionnameexp[0] == 'images' || $addconfigurableoptionnameexp[0] == 'location' || $addconfigurableoptionnameexp[0] == 'datacenter' || $addconfigurableoptionnameexp[0] == 'volformat'|| $addconfigurableoptionnameexp[0] == 'floating_ips_location'|| $addconfigurableoptionnameexp[0] == 'floating_ips_type') 
                        {
                            if ( $allowadminpermission->configoption6 == 'on') {
                                if ($addconfigurableoptionnameexp[0] == 'location' || $addconfigurableoptionnameexp[0] == 'datacenter' || $addconfigurableoptionnameexp[0] == 'volformat'|| $addconfigurableoptionnameexp[0] == 'floating_ips_location'|| $addconfigurableoptionnameexp[0] == 'floating_ips_type')
                                    $configid = $this->insertIn_tblproductconfigoptions($configurablegroup_id,$addconfigurableoptionnameVal,1,'','','',1);

                            }else{

                                $configid = $this->insertIn_tblproductconfigoptions($configurablegroup_id,$addconfigurableoptionnameVal,1,'','','','');
                            }

                        }
                        else if ($addconfigurableoptionnameexp[0] == 'floating_ips') 
                        {
                            if ( $allowadminpermission->configoption6 == 'on') {
                                $configid = $this->insertIn_tblproductconfigoptions($configurablegroup_id,$addconfigurableoptionnameVal,4,0,10,'','1');                                

                            }else{

                                $configid = $this->insertIn_tblproductconfigoptions($configurablegroup_id,$addconfigurableoptionnameVal,4,0,10,'','');
                            }

                        }
                        else if ($addconfigurableoptionnameexp[0] == 'volume') 
                        {
                            if ( $allowadminpermission->configoption6 == 'on') {
                                $configid = $this->insertIn_tblproductconfigoptions($configurablegroup_id,$addconfigurableoptionnameVal,4,0,512,'','1');
                            }
                            else{
                                $configid = $this->insertIn_tblproductconfigoptions($configurablegroup_id,$addconfigurableoptionnameVal,4,0,512,'','');
                                $this->product_configoptions_sub($configid, $addconfigurableoptionnameexp[0]);
                        }
                    }
                    else if ($addconfigurableoptionnameexp[0] == 'volformat' )
                        {
                            if ( $allowadminpermission->configoption6 == 'on') {
                                $configid = $this->insertIn_tblproductconfigoptions($configurablegroup_id,$addconfigurableoptionnameVal,1,'','','',1);

                            }else{

                                $configid = $this->insertIn_tblproductconfigoptions($configurablegroup_id,$addconfigurableoptionnameVal,1,'','','','');
                            }

                        }

                    }    
                }
            }/* if configoption group already exists */ 
            else 
            {
                $pcos = Capsule::table('tblproductconfiggroups')->where([['name', 'like', '%' . $addconfigrablegroupname . '%'], ['description', '=', '' . $addconfigurabledescription . '']])->first();
                $configurablegroup_id = $pcos->id;
                foreach ($addconfigurableoptionname as $key => $addconfigurableoptionnameVal) 
                {
                    $addconfigurableoptionnameexp = explode("|", $addconfigurableoptionnameVal);

                    $checkoptionname = Capsule::table('tblproductconfigoptions')
                    ->where([['gid', '=', $configurablegroup_id], ['optionname', 'like', '%' . $addconfigurableoptionnameexp[0] . '%']])->get();
                    if (!$checkoptionname) {
                        if ($addconfigurableoptionnameexp[0] == 'images' || $addconfigurableoptionnameexp[0] == 'location' || $addconfigurableoptionnameexp[0] == 'datacenter' || $addconfigurableoptionnameexp[0] == 'volformat' || $addconfigurableoptionnameexp[0] == 'floating_ips_location'|| $addconfigurableoptionnameexp[0] == 'floating_ips_type') 
                        {

                            $configid = $this->insertIn_tblproductconfigoptions($configurablegroup_id,$addconfigurableoptionnameVal,1,'','','','');
                        }
                        else if ($addconfigurableoptionnameexp[0] == 'floating_ips') 
                        {
                            $configid = $this->insertIn_tblproductconfigoptions($configurablegroup_id,$addconfigurableoptionnameVal,4,0,10,'','');

                        }
                        else 
                        {                            
                            $configid = $this->insertIn_tblproductconfigoptions($configurablegroup_id,$addconfigurableoptionnameVal,4,0,512,'','');
                        }
                        $this->product_configoptions_sub($configid, $addconfigurableoptionnameexp[0]);
                    }else 
                    {
                        $allowadminpermission = Capsule::table('tblproducts')->select()->where('id', $pid)->first();
                        $existingpco = Capsule::table('tblproductconfigoptions')->where([['optionname', 'like', '%' . $addconfigurableoptionnameexp[0] . '%'], ['gid', '=', $configurablegroup_id]])->first();
                        $configid = $existingpco->id;
                        if ($addconfigurableoptionnameexp[0] == 'location' || $addconfigurableoptionnameexp[0] == 'datacenter' || $addconfigurableoptionnameexp[0] == 'volformat'|| $addconfigurableoptionnameexp[0] == 'floating_ips_location'|| $addconfigurableoptionnameexp[0] == 'floating_ips_type'||$addconfigurableoptionnameexp[0] == 'floating_ips' || $addconfigurableoptionnameexp[0] == 'volume') 
                        {
                            if ($allowadminpermission->configoption6 == 'on') {
                                $hidden= array('hidden' => 1 );
                            }else{
                                $hidden= array('hidden' => 0 );
                            }
                        $hidequery= Capsule::table('tblproductconfigoptions')->where('id', $configid)->update($hidden); 
                        }
                        $this->product_configoptions_sub($configid, $addconfigurableoptionnameexp[0]);
                    }
                }
            }
        }
        public function product_configoptions_sub($configid, $addconfigurableoptionnameexp) 
        {
            $images = $this->get('images?type=system');
            $locations = $this->get('locations');
            $datacenters = $this->get('datacenters');

            if ($addconfigurableoptionnameexp == 'images') 
            {
                foreach ($images->images as $key => $images_types_value) 
                {
                    $existingpcosub = Capsule::table('tblproductconfigoptionssub')->where([['optionname', 'like', '%' . $images_types_value->name . '%'], ['configid', '=', $configid]])->first();
                    if (!$existingpcosub) {
                        if ($images_types_value->type == 'system') 
                        {
                            $optionname = $images_types_value->name . "|" . $images_types_value->description;
                            $tblpricing_rel_id =  $this->insertIn_tblproductconfigoptionssub($configid,$optionname,'','');
                            $this->configoptions_pricing($tblpricing_rel_id);
                        }
                    }

                }
            }
            if ($addconfigurableoptionnameexp == 'location') 
            {
                foreach ($locations->locations as $key => $location_types_value) 
                {
                    $existingpcosub = Capsule::table('tblproductconfigoptionssub')->where([['optionname', 'like', '%' . $location_types_value->name . '%'], ['configid', '=', $configid]])->first();
                    if (!$existingpcosub) 
                    {
                        $optionname = $location_types_value->name . "|" . $location_types_value->city;
                        $tblpricing_rel_id =  $this->insertIn_tblproductconfigoptionssub($configid,$optionname,'','');
                        $this->configoptions_pricing($tblpricing_rel_id);
                    }
                }
            }
            if ($addconfigurableoptionnameexp == 'datacenter') 
            {           
                foreach ($datacenters->datacenters as $key => $datacenters_types_value) 
                {
                    $existingpcosub = Capsule::table('tblproductconfigoptionssub')->where([['optionname', 'like', '%' . $datacenters_types_value->name . '%'], ['configid', '=', $configid]])->first();
                    if (!$existingpcosub) 
                    {
                        $optionname = $datacenters_types_value->name . "|" . $datacenters_types_value->description;
                        $tblpricing_rel_id =  $this->insertIn_tblproductconfigoptionssub($configid,$optionname,'','');
                        $this->configoptions_pricing($tblpricing_rel_id);                            
                    }
                }
            }
            if ($addconfigurableoptionnameexp == 'volume') 
            {
                $existingpcosub = Capsule::table('tblproductconfigoptionssub')->where([['optionname', 'like', '%' . "GB" . '%'], ['configid', '=', $configid]])->first();
                if (!$existingpcosub) 
                {
                    $optionname = "GB";
                    $tblpricing_rel_id =  $this->insertIn_tblproductconfigoptionssub($configid,$optionname,'','');
                    $this->configoptions_pricing($tblpricing_rel_id);
                }
            }
            if ($addconfigurableoptionnameexp == 'volformat') 
            {
                $format = array("xfs|XFS", "ext4|EXT4");
                foreach ($format as $key => $volume_format_types_value) 
                {
                    $volume_format_types_value_explode= explode('|', $volume_format_types_value);
                    $existingpcosub = Capsule::table('tblproductconfigoptionssub')->where([['optionname', 'like', '%' . $volume_format_types_value_explode[0] . '%'], ['configid', '=', $configid]])->first();
                    if (!$existingpcosub) {
                        $optionname = $volume_format_types_value_explode[0] . "|" . $volume_format_types_value_explode[1];
                        $tblpricing_rel_id =  $this->insertIn_tblproductconfigoptionssub($configid,$optionname,'','');
                        $this->configoptions_pricing($tblpricing_rel_id);                            
                    }
                }
            }
            if ($addconfigurableoptionnameexp == 'floating_ips') {
                $existingpcosub = Capsule::table('tblproductconfigoptionssub')->where([['optionname', 'like', '%' . "IPs" . '%'], ['configid', '=', $configid]])->first();
                if (!$existingpcosub) 
                {
                    $optionname = "IPs";
                    $tblpricing_rel_id =  $this->insertIn_tblproductconfigoptionssub($configid,$optionname,'','');
                    $this->configoptions_pricing($tblpricing_rel_id);
                }
            }
            if ($addconfigurableoptionnameexp == 'floating_ips_location') 
            {
                foreach ($locations->locations as $key => $location_types_value) 
                {
                    $existingpcosub = Capsule::table('tblproductconfigoptionssub')->where([['optionname', 'like', '%' . $location_types_value->name . '%'], ['configid', '=', $configid]])->first();
                    if (!$existingpcosub) 
                    {
                        $optionname = $location_types_value->name . "|" . $location_types_value->description;
                        $tblpricing_rel_id =  $this->insertIn_tblproductconfigoptionssub($configid,$optionname,'','');
                        $this->configoptions_pricing($tblpricing_rel_id);
                    }
                }               
            }
            if ($addconfigurableoptionnameexp == 'floating_ips_type') 
            {
                $ips_types = array("ipv4|IPv4","ipv6|IPv6");
                foreach ($ips_types as $key => $floating_ips_types_value) 
                {
                    $floating_ips_types_value_explode= explode('|', $floating_ips_types_value);
                    $existingpcosub = Capsule::table('tblproductconfigoptionssub')->where([['optionname', 'like', '%' . $floating_ips_types_value_explode[0] . '%'], ['configid', '=', $configid]])->first();
                    if (!$existingpcosub) 
                    {
                        $optionname = $floating_ips_types_value_explode[0] . "|" . $floating_ips_types_value_explode[1];
                        $tblpricing_rel_id =  $this->insertIn_tblproductconfigoptionssub($configid,$optionname,'','');
                        $this->configoptions_pricing($tblpricing_rel_id);                            
                    }
                }
            }
        }
        public function configoptions_pricing($tblpricing_rel_id) 
        {
            //die('configoptions_pricing');
            $datas = Capsule::table('tblcurrencies')->orderBy('code', 'DESC')->get();

            foreach ($datas as $data) 
            {
                $curr_id = $data->id;
                Capsule::table('tblpricing')->insert(
                    [
                        'type' => 'configoptions',
                        'currency' => $curr_id,
                        'relid' => $tblpricing_rel_id,
                        'msetupfee' => '',
                        'qsetupfee' => '',
                        'annually' => '',
                        'biennially' => '',
                        'triennially' => ''
                    ]
                );
            }
        }
        public function createCustomFields($pid) 
        {
            $createCustomFieldsArr = [
                'volumeid' => [
                    'type' => 'product',
                    'fieldname' => 'volume_id|Volume ID',
                    'relid' => $pid,
                    'fieldtype' => 'text',
                    'description' => 'Only for admin',
                    'adminonly' => 'on',
                    'sortorder' => '0'
                ],
                'serverid' => [
                    'type' => 'product',
                    'fieldname' => 'server_id|Server ID',
                    'relid' => $pid,
                    'fieldtype' => 'text',
                    'description' => 'Only for admin',
                    'adminonly' => 'on',
                    'sortorder' => '0'
                ],

            ];
            foreach ($createCustomFieldsArr as $key => $createCustomFieldsArr) 
            {
                $fieldname = explode('|', $createCustomFieldsArr['fieldname']);
                if (Capsule::table('tblcustomfields')->where('type', $createCustomFieldsArr['type'])->where('fieldname', 'like', '%' . $fieldname['0'] . '%')->where('relid', $createCustomFieldsArr['relid'])->count() == 0) {
                    Capsule::table('tblcustomfields')->insert($createCustomFieldsArr);
                }
            }
        }
        public function getCustomFieldId($pid, $fieldname) 
        {
            $data = Capsule::table('tblcustomfields')->where('type', 'product')->where('fieldname', 'like', '%' . $fieldname . '%')->where('relid', $pid)->first();
            return $data->id;
        }
        public function saveCustomFieldsValue($relid, $fieldid, $value) 
        {

            if (Capsule::table('tblcustomfieldsvalues')->where('fieldid', $fieldid)->where('relid', $relid)->count() == 0)
                Capsule::table('tblcustomfieldsvalues')->insert([
                    'fieldid' => $fieldid,
                    'relid' => $relid,
                    'value' => $value,
                ]);
            else{
                Capsule::table('tblcustomfieldsvalues')->where('fieldid', $fieldid)->where('relid', $relid)->update([
                    'value' => $value,
                ]);
            }
        }
        public function deleteCustomFieldsValue($relid, $fieldid, $value) 
        {
            if (Capsule::table('tblcustomfieldsvalues')->where('relid', $relid)->where('fieldid', $fieldid)->where('value', $value)->count() != 0)
                $deleteCustomField = Capsule::table('tblcustomfieldsvalues')->where('fieldid', $fieldid)->where('value', $value)->where('relid', $relid)->delete();
        }
        public function createEmailTemplates()
        {
            $email_template_array= [
                [
                    'type' => 'product', 
                    'name' => 'Reset server root password Information', 
                    'subject' => 'Reset server root password Information', 
                    'message' => '<span>Dear {$client_name},</span><br /><p>You have successfully reset the root password for your server.</p><p><b>Credentials</b></p><p>Username: {$new_root_username}</p><p>Password: {$new_root_password}</p>', 
                    'custom' => 1,
                ],
                [
                    'type' => 'product', 
                    'name' => 'Server Rebuild Information', 
                    'subject' => 'New server credentials and information ', 
                    'message' => '<p><span>Dear {$client_name},</span></p><br /><p>Your server has been successfully rebuild. Your server detail as given below.</p><p><b>Operating System:</b>&nbsp; {$os_image_name}</p><p><b>Username:</b>&nbsp;{$new_root_username}</p><p><b>Password:</b>&nbsp;{$new_root_password}</p>', 


                    'custom' => 1,
                ],
                [
                    'type' => 'product', 
                    'name' => 'New Server Details', 
                    'subject' => 'New server credentials and information ', 
                    'message' => '<p><span>Dear {$client_name},</span></p><br /><p>Your server has been successfully created. Your server detail as given below.</p><p><b>Server Name:</b>&nbsp;{$serverName}</p><p><b>IPv4:</b>&nbsp;{$serverIp4}</p><p><b>IPv6:</b>&nbsp;{$serverIp6}</p><p><b>Datacenter:</b>&nbsp;{$serverDatacenter}</p><p><b>Server type:</b>&nbsp;{$serverType}</p><p><b>CPU core:</b>&nbsp;{$cpuCore}</p><p><b>RAM:</b>&nbsp;{$serverRam} GB</p><p><b>Disk local:</b>&nbsp;{$serverdisk} GB</p><p><b>Additional Volume:</b>&nbsp;{$servAddiVolume}</p><p><b>Operating System:</b>&nbsp; {$os_image_name}</p><p><b>Username:</b>&nbsp;{$new_root_username}</p><p><b>Password:</b>&nbsp;{$new_root_password}</p>', 

                    'custom' => 1,
                ],
            ];
            foreach ($email_template_array as $key => $value) 
            {
                $this->insertEmailTemp($value);
            }
        }
        private function insertEmailTemp($data)
        {
            $email_template_id= Capsule::table('tblemailtemplates')->where('name', $data['name'])->first();
            if(!$email_template_id){                
                $CreateTemplate_query= Capsule::table('tblemailtemplates')->insert($data);
            }
        }
        public function createProductAddon($pid,$addonName,$moduleName,$description)
        {

            $getAddonPackges_linked= Capsule::table('tbladdons')->where('name', 'like',$addonName)->first();

            if(empty($getAddonPackges_linked)){    

               $createCustomAddonArr = [
                'packages' => $pid,
                'name' => $addonName,
                'showorder' => 1,
                'description' => $description,
                'module' => $moduleName
            ];               

            $createAddon = Capsule::table('tbladdons')->insert($createCustomAddonArr);

    }/*else{
       $packages = $getAddonPackges_linked->packages;
       $packages_array = explode(',', $packages);          

       if (!in_array($pid, $packages_array)) {
        array_push($packages_array,$pid);
        $productID_added_packages= implode(',', $packages_array);
        Capsule::table('tbladdons')->where('name', 'like',$addonName)
        ->update(['packages' => $productID_added_packages,
            'showorder' => 1,
        ]);
    }
}*/
}

public function getproductconfigurableSuboptionID($pid,$images,$os_selected)
{
    $id= Capsule::table('tblproductconfiglinks')->where('pid',$pid)->select('gid')->first();
    $gid= $id->gid;
    $configOption = Capsule::table('tblproductconfigoptions')->where('gid', $gid)->where('optionname','like','%' . $images. '%')->select('id')->first();  

    $configid= $configOption->id;
    $configSuboption= Capsule::table('tblproductconfigoptionssub')->where('configid', $configid)->where('optionname','like', '%' . $os_selected . '%')->first();
    $new_os_selected_id=$configSuboption->id;

    $custom_field_info= array(
        'configid'=>$configid,
        'new_os_selected_id'=>$new_os_selected_id,
    );
    return $custom_field_info;
    
}
public function sethsotingconfigurableOptionID($sid,$configId,$subOptionId)
{
    try{
        if(Capsule::table('tblhostingconfigoptions')->where('relid',$sid)->where('configid',$configId)->count() == 0)
            Capsule::table('tblhostingconfigoptions')->insert(['relid'=>$sid,'configid'=>$configId,'optionid'=>$subOptionId]);
        else
            Capsule::table('tblhostingconfigoptions')->where('relid',$sid)->where('configid',$configId)->update(['optionid'=>$subOptionId]);                
    }catch(Exception $e){
        logActivity('Insert/Update Failed, Error: '.$e->getMessage());
    }
}
public function TimestampToIso8601($timestamp, $utc = true) 
{
   $datestr = date('Y-m-d\TH:i:sO', $timestamp);
   if ($utc) {
       $eregStr = '/([0-9]{4})-' .
       '([0-9]{2})-' .
       '([0-9]{2})' .
       'T' .
       '([0-9]{2}):' .
       '([0-9]{2}):' .
       '([0-9]{2})(\.[0-9]*)?' .
       '(Z|[+\-][0-9]{2}:?[0-9]{2})?/';

       if (preg_match($eregStr, $datestr, $regs)) 
       {
           return sprintf('%04d-%02d-%02dT%02d:%02d:%02dZ', $regs[1], $regs[2], $regs[3], $regs[4], $regs[5], $regs[6]);
       }
       return false;
   } else {
       return $datestr;
   }
}

public function insertIn_tblproductconfigoptions ($configurablegroup_id,$optionname,$optiontype,$qtyminimum,$qtymaximum,$order,$hidden)
{
    $configid = Capsule::table('tblproductconfigoptions')->insertGetId([
        "gid" => $configurablegroup_id,
        "optionname" => $optionname,
        "optiontype" => $optiontype,
        "qtyminimum" => $qtyminimum,
        "qtymaximum" => $qtymaximum,
        "order" => $order,
        "hidden" => $hidden,
    ]
);
    return $configid;

}
public function insertIn_tblproductconfigoptionssub($configid,$optionname,$sortorder,$hidden)
{
    $configSubId = Capsule::table('tblproductconfigoptionssub')
    ->insertGetId(
        [
            "configid" => $configid,
            "optionname" => $optionname,
            "sortorder" => $sortorder,
            "hidden" => $hidden
        ]
    );
    return $configSubId;
}
function create_clientarea_sectionDbTable() {
    try {
        if (!Capsule::Schema()->hasTable('hetz_clientarea_section')) {
            Capsule::schema()->create(
                'hetz_clientarea_section', function ($table) {
                    $table->increments('id');
                    $table->string('setting');
                    $table->string('value');
                    $table->string('pid');
                }
            );
        }
    } catch (\Exception $e) {
        logActivity("Unable to create hetz_clientarea_section: {$e->getMessage()}");
    }    

    try {
        if (Capsule::Schema()->hasColumn('hetz_clientarea_section', 'setting')) {
            $tables = Capsule::statement('ALTER TABLE hetz_clientarea_section MODIFY setting  VARCHAR(50)');
        }
    } catch (Exception $ex) {
        logActivity("Unable to create hetz_clientarea_section: {$ex->getMessage()}");
    }
}
}