<?php

namespace ModulesGarden\Servers\VultrVps\App\Http\Actions;


use ModulesGarden\Servers\VultrVps\App\Api\Models\FirewallGroup;
use ModulesGarden\Servers\VultrVps\App\Api\Models\Image;
use ModulesGarden\Servers\VultrVps\App\Api\Models\Instance;
use ModulesGarden\Servers\VultrVps\App\Enum\ConfigurableOption;
use ModulesGarden\Servers\VultrVps\App\Enum\CustomField;
use ModulesGarden\Servers\VultrVps\App\Traits\ApiClient;
use ModulesGarden\Servers\VultrVps\App\Traits\CustomFieldUpdate;
use ModulesGarden\Servers\VultrVps\App\Traits\ProductSetting;
use ModulesGarden\Servers\VultrVps\Core\App\Controllers\Instances\AddonController;
use ModulesGarden\Servers\VultrVps\Core\UI\Traits\WhmcsParams;


/**
 * Class CreateAccount
 *
 * @author <slawomir@modulesgarden.com>
 */
class CreateAccount extends AddonController
{

    use WhmcsParams, ProductSetting, ApiClient, CustomFieldUpdate;

    /**
     * @var Instance
     */
    protected $instance;
    /**
     * @var FirewallGroup
     */
    protected $firewallGroup;

    public function execute($params = null)
    {
        try
        {
            if ($this->getWhmcsCustomField(CustomField::INSTANCE_ID))
            {
                throw new \InvalidArgumentException("Custom Field \"Instance ID\" is not empty");
            }
            //instance
            $this->instance = $this->apiClient()->instance();
            $this->firewallGroup = $this->apiClient()->firewallGroup();
            $this->firewallGroupCreate();
            $setting = [
                'region' => $this->getWhmcsConfigOption(ConfigurableOption::REGION, $this->productSetting()->region),
                'plan' => $this->getWhmcsConfigOption(ConfigurableOption::PLAN ,$this->productSetting()->plan),
                'enable_ipv6' => $this->isIpv6(),
                'label' => $this->getWhmcsCustomField(CustomField::LABEL),
                'backups'  => $this->getBackups(),
                'user_data' => $this->productSetting()->user_data,
                'hostname' => $this->getWhmcsParamByKey("domain"),
                'firewall_group_id' => $this->firewallGroup->getId()
            ];
            //OS
            $this->operatingSystemInsert($setting);
            //create
            $response = $this->instance->create($setting);
            //instance id
            $this->customFieldUpdate(CustomField::INSTANCE_ID, $this->instance->getId());
            //default_password & ip
            for($i =0; $i  < 60; $i++){
                sleep(2);
                if($this->instance->details()->instance->main_ip && $this->instance->details()->instance->main_ip != '0.0.0.0'){
                    break;
                }
            }
            $hosting              = $this->getWhmcsParamByKey('model');
            $hosting->password = encrypt((string)$response->instance->default_password);
            $hosting->dedicatedip = $this->instance->details()->instance->main_ip;
            $hosting->save();
            return 'success';
        }
        catch (\Exception $ex)
        {
            try{
                if($this->firewallGroup->getId()){
                    $this->firewallGroup->delete();
                    $this->customFieldUpdate(CustomField::FIREWALL_GROUP_ID, "");
                }
            }catch (\Exception $ex2){
            }
            return $ex->getMessage();
        }
    }

    protected function  firewallGroupCreate(){
        $description = $this->getWhmcsParamByKey("domain") ?:  sprintf('WHMCS #%s', $this->getWhmcsParamByKey('serviceid'));
        $this->firewallGroup->setDescription($description);
        $this->firewallGroup->create();
        $this->customFieldUpdate(CustomField::FIREWALL_GROUP_ID, $this->firewallGroup->getId());
    }

    protected function isIpv6(){
        if($this->isWhmcsConfigOption(ConfigurableOption::IPV6)){
            return $this->getWhmcsConfigOption(ConfigurableOption::IPV6) == 1 ;
        }
        return $this->productSetting()->isEnableIpv6();
    }

    protected function getBackups(){
        if($this->isWhmcsConfigOption(ConfigurableOption::BACKUPS)){
            return $this->getWhmcsConfigOption(ConfigurableOption::BACKUPS) == 1 ? "enabled" : "disabled" ;
        }
        return $this->productSetting()->getBackups();
    }

    protected function  operatingSystemInsert(&$setting){
        $options=['os_id','snapshot_id','iso_id'/*,'app_id'*/];
        foreach ($options as $option){
            if($this->getWhmcsConfigOption($option)){
                $setting[$option] = $this->getWhmcsConfigOption($option);
                return true;
            }
        }
        foreach ($options as $option){
            if($this->productSetting()->get($option)){
                $setting[$option] = $this->productSetting()->get($option);
                return true;
            }
        }
        throw new \InvalidArgumentException("Configuration OS - cannot be empty");
    }
}
