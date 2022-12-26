<?php

namespace ModulesGarden\Servers\GoogleCloudVirtualMachines\App\UI\Admin\ProductConfig\Fields;

use Google_Service_Compute;
use ModulesGarden\Servers\GoogleCloudVirtualMachines\App\Repositories\ProductSettingRepository;
use ModulesGarden\Servers\GoogleCloudVirtualMachines\Core\UI\Interfaces\AdminArea;
use ModulesGarden\Servers\GoogleCloudVirtualMachines\Core\UI\Widget\Forms\AjaxFields\Select;
use function ModulesGarden\Servers\GoogleCloudVirtualMachines\Core\Helper\sl;

class NetworkTierSelect extends Select implements AdminArea
{

    public function prepareAjaxData()
    {
        //init setting
        $this->productSetting = new ProductSettingRepository($this->getRequestValue('id'));
        //load options
        $this->setOptions();
        $this->setSelectedValue($this->productSetting->networkTier);
    }

    protected function setOptions()
    {
        $options=[];
        $options[] = [
            'key' => 'PREMIUM',
            'value' => sl('lang')->absoluteT('networkTier',  "PREMIUM"),
        ];
        $options[] = [
            'key' => 'STANDARD',
            'value' => sl('lang')->absoluteT('networkTier',  "STANDARD"),
        ];

        $this->setAvailableValues($options);
    }

}
