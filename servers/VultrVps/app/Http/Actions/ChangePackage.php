<?php

namespace ModulesGarden\Servers\VultrVps\App\Http\Actions;

use ModulesGarden\Servers\VultrVps\App\Api\InstanceFactory;
use ModulesGarden\Servers\VultrVps\App\Api\Models\Instance;
use ModulesGarden\Servers\VultrVps\App\Api\Models\ResourceGroup;
use ModulesGarden\Servers\VultrVps\App\Enum\ConfigurableOption;
use ModulesGarden\Servers\VultrVps\App\Enum\CustomField;
use ModulesGarden\Servers\VultrVps\App\Events\VmChangedPackage;
use ModulesGarden\Servers\VultrVps\App\Traits\ApiClient;
use ModulesGarden\Servers\VultrVps\App\Traits\ProductSetting;
use ModulesGarden\Servers\VultrVps\Core\UI\Traits\WhmcsParams;

/**
 * Class ChangePackage
 *
 * @author <slawomir@modulesgarden.com>
 */
class ChangePackage extends CreateAccount
{
    use WhmcsParams, ProductSetting, ApiClient;
    /**
     * @var Instance
     */
    protected $instance;

    public function execute($params = null)
    {
        try
        {
            $this->instance = (new InstanceFactory())->fromWhmcsParams();
            $this->instance->details();
            $config =[
                'enable_ipv6' => $this->isIpv6(),
                'backups'  => $this->getBackups(),
                'label' => $this->getWhmcsCustomField(CustomField::LABEL),
                'user_data' => $this->productSetting()->user_data,
            ];
            if($this->instance->getPlan() != $this->getWhmcsConfigOption(ConfigurableOption::PLAN, $this->productSetting()->plan)){
                $config['plan'] = $this->getWhmcsConfigOption(ConfigurableOption::PLAN, $this->productSetting()->plan);
            }
            $response = $this->instance->update($config);
            return 'success';
        }
        catch (\Exception $exc)
        {
            return $exc->getMessage();
        }
    }

    protected function planUpgrade(){

    }


}
