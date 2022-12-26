<?php

namespace ModulesGarden\Servers\GoogleCloudVirtualMachines\App\Helpers;

use ModulesGarden\Servers\GoogleCloudVirtualMachines\Core\UI\Traits\WhmcsParams;
use ModulesGarden\Servers\GoogleCloudVirtualMachines\App\Traits\ComputeTrait;
use ModulesGarden\Servers\GoogleCloudVirtualMachines\App\Traits\ProductSetting;
use \ModulesGarden\Servers\GoogleCloudVirtualMachines\App\Helpers\Utility;

class InstanceHelper {
   
    use WhmcsParams, ComputeTrait, ProductSetting;
    
    protected $project;
    protected $zone;
    protected $name;
    
    public function __construct() {
        $this->project = (new \ModulesGarden\Servers\GoogleCloudVirtualMachines\App\Api\ProjectFactory())->fromParams();
        $this->zone = $this->productSetting()->zone;
        
    }
    
    public function getInstanceList(){
        $list = $this->compute()->instances->listInstances($this->project, $this->zone);
        return $list;
    }
     
    public function generateHostname(){
        $domain = $this->getWhmcsParamByKey("domain");
        if (!$domain){
            return $this->productSetting()->hostnamePrefix.Utility::generatePassword(10, 'abcdefghijklmnopqrstuvwxyz').$this->getWhmcsParamByKey('serviceid');
        }
        $domain = str_replace(["."],["-"],$domain);
        return $this->productSetting()->hostnamePrefix.$domain;
    }
        
    public function instanceExists(){
        foreach($this->getInstanceList() as $instance){
            
            if($instance->id === $this->getWhmcsCustomField('instanceId')){
                return true;
            }
            
        }
        return false;
    }
}
