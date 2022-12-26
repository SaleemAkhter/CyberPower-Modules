<?php

namespace ModulesGarden\Servers\GoogleCloudVirtualMachines\App\Http\Actions;

use \Illuminate\Database\Capsule\Manager as Capsule;

use Google_Service_Compute_AccessConfig;
use Google_Service_Compute_Address;
use Google_Service_Compute_Instance;
use ModulesGarden\Servers\GoogleCloudVirtualMachines\App\Api\ProjectFactory;
use ModulesGarden\Servers\GoogleCloudVirtualMachines\App\Enum\ConfigurableOption;
use ModulesGarden\Servers\GoogleCloudVirtualMachines\App\Enum\CustomField;
use ModulesGarden\Servers\GoogleCloudVirtualMachines\App\Helpers\ErrorConveter;
use ModulesGarden\Servers\GoogleCloudVirtualMachines\App\Helpers\Utility;
use ModulesGarden\Servers\GoogleCloudVirtualMachines\App\Libs\GcpPasswordReset\GcpPasswordReset;
use ModulesGarden\Servers\GoogleCloudVirtualMachines\App\Traits\ComputeTrait;
use ModulesGarden\Servers\GoogleCloudVirtualMachines\App\Traits\CustomFieldUpdate;
use ModulesGarden\Servers\GoogleCloudVirtualMachines\App\Traits\ProductSetting;
use ModulesGarden\Servers\GoogleCloudVirtualMachines\Core\App\Controllers\Instances\AddonController;
use ModulesGarden\Servers\GoogleCloudVirtualMachines\Core\UI\Traits\WhmcsParams;
use \ModulesGarden\Servers\GoogleCloudVirtualMachines\Core\Traits\Smarty;

/**
 * Class CreateAccount
 *
 * @author <slawomir@modulesgarden.com>
 */
class CreateAccount extends AddonController
{

    use WhmcsParams, ProductSetting, CustomFieldUpdate, ComputeTrait, Smarty;

    /**
     * @var \Google_Service_Compute_Instance
     */
    protected $instance;

    protected function getDiskSizeByDiskType($type=false)
    {
        if($type){
            $params=$this->getWhmcsParamsByKeys(['serviceid']);
            $serviceid=$params['serviceid'];
            $opt=Capsule::table('tblproductconfigoptionssub')->where("optionname","LIKE",$type."|%")->first();
            if($opt){
                $name=explode("|",$opt->optionname);
                $diskopt=Capsule::table('tblproductconfigoptionssub')->where("optionname","=",$name[1])->first();
                if($diskopt){
                    $diskSize=Capsule::table('tblhostingconfigoptions')->join('tblproductconfigoptionssub','tblhostingconfigoptions.optionid','=','tblproductconfigoptionssub.id')->where(['tblproductconfigoptionssub.id'=>$diskopt->id,'tblhostingconfigoptions.relid'=>$serviceid])->value('qty');
                    if($diskSize>0){
                        return $diskSize;
                    }
                }
            }
        }
        return $this->getWhmcsConfigOption("diskSize");
    }
    public function execute($params = null)
    {

        // echo "<pre>";
        // print_r($params);
        // echo "</pre>";
        try
        {
            if ($this->getWhmcsCustomField(CustomField::INSTANCE_ID))
            {
                throw new \InvalidArgumentException("Custom Field \"Instance ID\" is not empty");
            }
            $project = (new ProjectFactory())->fromParams();
            //new instance
            $this->instance = new Google_Service_Compute_Instance();
            $hostname = $this->generateHostname();
            $this->instance->setName($hostname);

            /*diskType*/
            $diskType=$this->getWhmcsConfigOption("diskType");
            if(!$diskType){
                $diskType=$this->productSetting()->diskType;
            }

            $diskSize=$this->getDiskSizeByDiskType($diskType);

            if(!$diskSize){
                $diskSize=$this->productSetting()->diskSize;
            }

            /*region*/
            $region=$this->getWhmcsConfigOption("region");
            if(!$region){
                $region=$this->productSetting()->region;
            }

            /*zone*/
            $zone=$this->getWhmcsConfigOption("zone");
            if(!$zone){
                $zone=$this->productSetting()->zone;
            }



            if ($this->customMachineCheck()) {
                $machineType = $this->getCustomMachineType();
                $machineCpu = $this->getCustomMachineCpu();
                $machineRam = $this->getCustomMachineRam();
                $this->instance->setMachineType(sprintf("projects/%s/zones/%s/machineTypes/%scustom-%s-%s", $project, $zone, $machineType, $machineCpu, $machineRam));
            } else if ($this->getWhmcsConfigOption(ConfigurableOption::MACHINE_TYPE)) {
                $this->instance->setMachineType(sprintf("projects/%s/zones/%s/machineTypes/%s", $project, $zone, $this->getWhmcsConfigOption(ConfigurableOption::MACHINE_TYPE)));
            } else {
                $this->instance->setMachineType($this->productSetting()->getMachineTypeAsPath());
            }
            //metadata  
            $metadata = new \Google_Service_Compute_Metadata;
            $metadata->setKind("compute#metadata");
            $metaDataItems = [];
            if($this->getWhmcsCustomField(CustomField::SSH_KEY)){
                $itemssh = [
                    "key" => "ssh-keys",
                    "value" => $this->getWhmcsParamByKey("username").":".$this->getWhmcsCustomField(CustomField::SSH_KEY)
                ];
            array_push($metaDataItems, $itemssh);
            }
            if($this->productSetting()->userData){
                $template = \ModulesGarden\Servers\GoogleCloudVirtualMachines\App\Helpers\UserData::read($this->productSetting()->userData);
                $itemUserData = [
                    'key' => 'startup-script',
                    'value' => $this->getSmarty()->fetch($template, $this->whmcsParams->getWhmcsParams())
                ];
                array_push($metaDataItems, $itemUserData);
            }

            $metadata->setItems($metaDataItems);
            $this->instance->setMetadata($metadata);



            //sourceImage
            if($this->getWhmcsConfigOption(ConfigurableOption::IMAGE)){
                $ex = explode(":",$this->getWhmcsConfigOption(ConfigurableOption::IMAGE));
                if($ex['0'] === 'custom-images') {
                    $ex['0'] = $project;
                }
                $sourceImage = sprintf("projects/%s/global/images/%s",$ex[0], $ex[1]);
            }else{
                $sourceImage = $this->productSetting()->getSourceImageAsPath();
            }
            logActivity($this->productSetting()->getDiskTypeAsPath());
            logActivity($this->getDiskTypeAsPath($diskType,$zone));
            //disk
            $disk = [
                "kind"=> "compute#attachedDisk",
                "type" => "PERSISTENT",
                "boot" =>  true,
                "mode" => "READ_WRITE",
                "autoDelete" =>  true,
                "deviceName" =>  "disk0",
                "initializeParams" =>  [
                    "sourceImage" => $sourceImage,
                    "diskType" => $this->getDiskTypeAsPath($diskType,$zone),
                    "diskSizeGb" => $diskSize
                ],
                "diskEncryptionKey" => new \stdClass()
            ];
            $this->instance->setDisks([$disk]);
            $this->instance->setDescription("");
            if(!$region){
                throw new \InvalidArgumentException("Check if 'region' is set in product settings.");
            }
            if(!$this->productSetting()->network){
                throw new \InvalidArgumentException("Check if 'network' is set in product settings.");
            }
            $network = [
                "kind"=>  "compute#networkInterface",
                "subnetwork"=>  $this->getNetworkAsPath($this->productSetting()->network,$region),
            //    "accessConfigs"=>  [
            //        "kind"=>  "compute#accessConfig",
            //        "name"=>  "External NAT",
            //        "type"=>  "ONE_TO_ONE_NAT",
            //        "networkTier"=>  $this->productSetting()->networkTier
            //    ],
                "aliasIpRanges"=>  []
            ];
            $this->instance->setNetworkInterfaces([ $network]);
            /**
             * insert new instance
             * @var \Google_Service_Compute_Operation $insert
             */
            $insert = $this->compute()->instances->insert($project, $zone, $this->instance);
            //Operation
            // for($i =0; $i  < 340; $i++){
            //     sleep(1);
            //     $operation = $this->compute()->zoneOperations->get($project, $zone,  $insert->getId());
            //     if($operation->status == 'DONE'){
            //         break;
            //     }
            // }
            $operation = $this->compute()->zoneOperations->get($project, $zone,  $insert->getId());
              
            if($operation->getError()){
                (new ErrorConveter($operation->getError()))->convertToExceptionAndThrow();
            }
            //save
            $this->customFieldUpdate(CustomField::INSTANCE_ID, $insert->getTargetId());
            $this->customFieldUpdate(CustomField::ZONE, $zone);
            $this->customFieldUpdate(CustomField::REGION, $region);
            //read
            $this->instance = $this->compute()->instances->get($project,$zone, $insert->getTargetId());
            $this->instance->setZone($zone);

            // setTags        
            $this->compute()->instances->setTags($project, $zone, $this->instance->name, $this->getTags());
            //ipv4
            if($this->productSetting()->isIpv4()) {
                $this->addRegionalIpv4Address($region);
            }

            
            $serviceId =  $params['serviceid'];
            if(strpos($sourceImage, 'windows') !== false) {

                mysql_query("UPDATE tblhosting SET `username` = 'admin' where id='$serviceId'");
          
                $email = $this->getWhmcsParamByKey('clientsdetails')['email'];
                $productId = $this->getWhmcsParamByKey('serviceid');

                $passwordReseter = new GcpPasswordReset($this->instance, $this->compute, $project, $email);

                
                // $newPassword = $passwordReseter->getNewPassword(10);
                $newPassword = "Reset Required*";
                $passwordReseter->setNewPasswordForWhmcsOrder($serviceId, $newPassword);
                
            }
            
            

            return 'success';
        }
        catch (\Exception $ex)
        {
            logModuleCall(
                'GoogleCloudVirtualMachines',
                __CLASS__,
                $params,
                $ex->getMessage(),
                $ex->getTraceAsString()
            );
            return $ex->getMessage();
        }
    }
    protected function getDiskTypeAsPath($diskType,$zone){
        $poject = (new ProjectFactory())->fromParams();
        return sprintf("projects/%s/zones/%s/diskTypes/%s",$poject, $zone, $diskType);
    }
     public function getNetworkAsPath($network,$region){
        $poject = (new ProjectFactory())->fromParams();
        return sprintf("projects/%s/regions/%s/subnetworks/%s",$poject, $region, $network);
    }
    protected function getTags(){
        $tags = new \Google_Service_Compute_Tags();
        $tags->setFingerprint($this->instance->tags->fingerprint);
        if($this->productSetting()->tags){
            $tags->setItems($this->separateTags(strtolower($this->productSetting()->tags)));
        }
        return $tags;
    }


    protected function separateTags($tag){
        $pattern = '/(,\s+|,|\s+)/';
        $ar = preg_split($pattern, $tag);
        return $ar;
    }

    private function generateHostname(){
        $domain = $this->getWhmcsParamByKey("domain");
        if (!$domain){
            return $this->productSetting()->hostnamePrefix.Utility::generatePassword(10, 'abcdefghijklmnopqrstuvwxyz').$this->getWhmcsParamByKey('serviceid');
        }
        $domain = str_replace(["."],["-"],$domain);
        return $this->productSetting()->hostnamePrefix.$domain;
    }

    protected function addRegionalIpv4Address($region=''){
        $project = (new ProjectFactory())->fromParams();
        if(!$region){
            $region = $this->productSetting->region;
        }

        $address = new Google_Service_Compute_Address();
        $address->name = $this->instance->name;
        $address->networkTier = $this->productSetting->networkTier;
        $insert = $this->compute()->addresses->insert($project, $region, $address);
        for($i =0; $i  < 240; $i++){
            $ip = $this->compute()->addresses->get($project, $region, $insert->targetId);
            if($ip->address){
                break;
            }
            sleep(1);
        }
        //Dedicated IP
        $hosting = $this->getWhmcsParamByKey('model');
        $hosting->dedicatedIp = (string) $ip->address;
        $hosting->save();
        //add access config
        $networkInterface = $this->instance->getNetworkInterfaces()[0]->getName();
        $zone = $this->instance->getZone();
        $accessConfig = new Google_Service_Compute_AccessConfig();
        $accessConfig->name = "External NAT";
        $accessConfig->natIP = $ip->address;
        $accessConfig->networkTier  = (string) $this->productSetting->networkTier;
        $accessConfig->type =  "ONE_TO_ONE_NAT";
        /** $project, $zone, $instance, $networkInterface, Google_Service_Compute_AccessConfig $postBody */
        $addAccessConfig = $this->compute()->instances->addAccessConfig(
            $project,
            $zone,
            $this->instance->getName(),
            $networkInterface ,
            $accessConfig
        );
    }

    protected function deleteRegionalIpv4Address(){

        $project =  (new ProjectFactory())->fromParams();
        $zone = $this->instance->getZone();
        $instanceName = $this->instance->getName();
        $natIp = null;
        $region = $this->getWhmcsCustomField('region');
        foreach ($this->instance->getNetworkInterfaces() as $networkInterface) {
            foreach ($networkInterface->accessConfigs as $accessConfig){
                if($accessConfig->natIP){
                    $natIp = $accessConfig->natIP;
                    //$project, $zone, $instance, $accessConfig, $networkInterface,
                    $this->compute()->instances->deleteAccessConfig(
                        $project,
                        $zone,
                        $instanceName,
                        $accessConfig->name,
                        $networkInterface->name
                    );
                    break;
                }
            }
        }
        if(is_null($natIp)){
            return;
        }
        sleep(1);
        $optParams =['filter' =>  sprintf('address  = "%s" ', $natIp) ];
        $ips = $this->compute()->addresses->listAddresses($project, $region, $optParams);
        foreach ($ips->getItems() as $address){
            $this->compute()->addresses->delete($project, $region, $address->name);
        }

    }

    private function customMachineCheck(){
        $productMachineTypeSetting = $this->productSetting()->machineType;
        $configurableOptionMachineType = $this->getWhmcsConfigOption(ConfigurableOption::MACHINE_TYPE);

        return $productMachineTypeSetting === 'customMachine' ||
            $configurableOptionMachineType === 'customMachine';
    }

    private function getCustomMachineType(){
        $options = [
            [
                'key' => 'N1',
                'value' => ''
            ],
            [
                'key' => 'N2',
                'value' => 'n2-'
            ],
            [
                'key' => 'N2D',
                'value' => 'n2d-'
            ],
            [
                'key' => 'E2',
                'value' => 'e2-'
            ]
        ];

        if ($this->getWhmcsConfigOption(ConfigurableOption::CUSTOM_MACHINE_TYPE)) {
            return $options[$this->getWhmcsConfigOption(ConfigurableOption::CUSTOM_MACHINE_TYPE)];
        }

        return $options[$this->productSetting()->customMachineType];
    }

    private function getCustomMachineCpu(){
        if($this->getWhmcsConfigOption(ConfigurableOption::CUSTOM_MACHINE_CPU)) {
            return $this->getWhmcsConfigOption(ConfigurableOption::CUSTOM_MACHINE_CPU);
        }

        return $this->productSetting()->customMachineCpu;
    }

    private function getCustomMachineRam(){

        if($this->getWhmcsConfigOption(ConfigurableOption::CUSTOM_MACHINE_RAM)) {
            return $this->getWhmcsConfigOption(ConfigurableOption::CUSTOM_MACHINE_RAM);
        }

        return $this->productSetting()->customMachineRam;
    }
}
