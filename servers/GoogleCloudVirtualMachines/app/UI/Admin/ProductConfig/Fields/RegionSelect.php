<?php

namespace ModulesGarden\Servers\GoogleCloudVirtualMachines\App\UI\Admin\ProductConfig\Fields;

use Google_Service_Compute;
use ModulesGarden\Servers\GoogleCloudVirtualMachines\App\Repositories\ProductSettingRepository;
use ModulesGarden\Servers\GoogleCloudVirtualMachines\Core\UI\Interfaces\AdminArea;
use ModulesGarden\Servers\GoogleCloudVirtualMachines\Core\UI\Widget\Forms\AjaxFields\Select;
use function ModulesGarden\Servers\GoogleCloudVirtualMachines\Core\Helper\sl;

class RegionSelect extends Select implements AdminArea
{

    public function prepareAjaxData()
    {
        //init setting
        $this->productSetting = new ProductSettingRepository($this->getRequestValue('id'));
        //load options
        $this->setOptions();
        $this->setSelectedValue($this->productSetting->region);
    }

    protected function setOptions()
    {
        $options=[];
        $compute = new Google_Service_Compute(sl('ApiClient')->getGoogleClient());
        foreach ($compute->regions->listRegions(sl('ApiClient')->getProject())->getItems() as $entery)
        {
            /**
             * @var  \Google_Service_Compute_Region $entery
             */
            $options[] = [
                'key' => $entery->getName(),
                'value' => html_entity_decode(sl('lang')->absoluteT('region', $entery->getDescription()), ENT_QUOTES)
            ];
        }
        $this->setAvailableValues($options);
    }

}
