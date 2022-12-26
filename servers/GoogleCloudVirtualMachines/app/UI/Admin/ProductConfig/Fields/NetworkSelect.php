<?php

namespace ModulesGarden\Servers\GoogleCloudVirtualMachines\App\UI\Admin\ProductConfig\Fields;

use Google_Service_Compute;
use ModulesGarden\Servers\GoogleCloudVirtualMachines\App\Repositories\ProductSettingRepository;
use ModulesGarden\Servers\GoogleCloudVirtualMachines\Core\UI\Interfaces\AdminArea;
use ModulesGarden\Servers\GoogleCloudVirtualMachines\Core\UI\Widget\Forms\AjaxFields\Select;
use function ModulesGarden\Servers\GoogleCloudVirtualMachines\Core\Helper\sl;

class NetworkSelect extends Select implements AdminArea
{

    public function prepareAjaxData()
    {
        //init setting
        $this->productSetting = new ProductSettingRepository($this->getRequestValue('id'));
        //load options
        $this->setOptions();
        $this->setSelectedValue($this->productSetting->network);
    }

    protected function setOptions()
    {
        $options=[];
        $compute = new Google_Service_Compute(sl('ApiClient')->getGoogleClient());
        $project = sl('ApiClient')->getProject();
        foreach ($compute->networks->listNetworks($project)->getItems() as $entery)
        {
            /**
             * @var  \Google_Service_Compute_Network $entery
             */
            $options[] = [
                'key' => $entery->getName(),
                'value' => $entery->getDescription()
            ];
        }
        $this->setAvailableValues($options);
    }

}
