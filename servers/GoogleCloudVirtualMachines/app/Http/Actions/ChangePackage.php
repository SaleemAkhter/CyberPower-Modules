<?php

namespace ModulesGarden\Servers\GoogleCloudVirtualMachines\App\Http\Actions;

use ModulesGarden\Servers\GoogleCloudVirtualMachines\App\Api\InstanceFactory;
use ModulesGarden\Servers\GoogleCloudVirtualMachines\App\Api\Models\Instance;
use ModulesGarden\Servers\GoogleCloudVirtualMachines\App\Api\ProjectFactory;
use ModulesGarden\Servers\GoogleCloudVirtualMachines\App\Enum\ConfigurableOption;
use ModulesGarden\Servers\GoogleCloudVirtualMachines\App\Enum\CustomField;
use ModulesGarden\Servers\GoogleCloudVirtualMachines\App\Events\VmChangedPackage;
use ModulesGarden\Servers\GoogleCloudVirtualMachines\App\Traits\ApiClient;
use ModulesGarden\Servers\GoogleCloudVirtualMachines\App\Traits\ComputeTrait;
use ModulesGarden\Servers\GoogleCloudVirtualMachines\App\Traits\CustomFieldUpdate;
use ModulesGarden\Servers\GoogleCloudVirtualMachines\App\Traits\ProductSetting;
use ModulesGarden\Servers\GoogleCloudVirtualMachines\Core\UI\Traits\WhmcsParams;

/**
 * Class ChangePackage
 *
 * @author <slawomir@modulesgarden.com>
 */
class ChangePackage extends CreateAccount
{
    use WhmcsParams, ProductSetting, CustomFieldUpdate, ComputeTrait;



    public function execute($params = null)
    {
        try
        {
            $instance = (new InstanceFactory())->fromParams();
            $poject = (new ProjectFactory())->fromParams();
            $this->instance = $this->compute()->instances->get($poject,$instance->getZone(), $instance->getId());
            $this->instance->setZone($this->getWhmcsCustomField(CustomField::ZONE));
            //machine type
            if($this->isMachineTypeUpdate()){
                $this->machineTypeUpdate();
            }
            //add public ip
            if($this->productSetting()->isIpv4() &&
                !$this->instance->getNetworkInterfaces()[0]->accessConfigs[0]->natIP
            ){
                $this->addRegionalIpv4Address();
            }
            //delete  public ip
            if(!$this->productSetting()->isIpv4() &&
                $this->instance->getNetworkInterfaces()[0]->accessConfigs[0]->natIP){
                $this->deleteRegionalIpv4Address();
                //Dedicated IP
                $hosting = $this->getWhmcsParamByKey('model');
                $hosting->dedicatedIp = "";
                $hosting->save();
            }
            //setTags       
            $this->compute()->instances->setTags($poject, $this->productSetting()->zone, $this->instance->name, $this->getTags());           
            return 'success';
        }
        catch (\Exception $exc)
        {
            return $exc->getMessage();
        }
    }

    private function isMachineTypeUpdate(){
        $machineType = explode("/",$this->instance->machineType);
        $machineType = end($machineType);
        if($machineType && $this->getWhmcsConfigOption(ConfigurableOption::MACHINE_TYPE)){
            return $this->getWhmcsConfigOption(ConfigurableOption::MACHINE_TYPE) != $machineType;
        }else if($machineType){
            return $this->productSetting()->machineType != $machineType;
        }
        return  false;
    }

    private function machineTypeUpdate(){
        $project = (new ProjectFactory())->fromParams();
        $zone = $this->instance->getZone();
        //Stop
        $isRunning = $this->instance->getStatus() =="RUNNING";
        if($isRunning){
            $stop = $this->compute()->instances->stop($project , $this->instance->getZone(), $this->instance->getId() );
            sleep(1);
        }
        $newMachineType = new \Google_Service_Compute_InstancesSetMachineTypeRequest();
        if ($this->getWhmcsConfigOption(ConfigurableOption::MACHINE_TYPE)) {
            $newMachineType->setMachineType(sprintf("projects/%s/zones/%s/machineTypes/%s", $project, $this->productSetting()->zone, $this->getWhmcsConfigOption(ConfigurableOption::MACHINE_TYPE)));
        } else if ($this->getWhmcsConfigOption(ConfigurableOption::CUSTOM_MACHINE_TYPE)) {
            $machineType = $this->getCustomMachineType();
            $machineCpu = $this->getCustomMachineCpu();
            $machineRam = $this->getCustomMachineRam();
            $newMachineType->setMachineType(sprintf("projects/%s/zones/%s/machineTypes/%scustom-%s-%s", $project, $this->productSetting()->zone, $machineType, $machineCpu, $machineRam));
        } else {
            $newMachineType->setMachineType($this->productSetting()->getMachineTypeAsPath());
        }

        $response = $this->compute()->instances->setMachineType( $project,$zone,$this->instance->getName(), $newMachineType);
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

        return $options[$this->getWhmcsConfigOption(ConfigurableOption::CUSTOM_MACHINE_TYPE)];
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
