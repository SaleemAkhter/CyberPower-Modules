<?php

namespace ModulesGarden\Servers\GoogleCloudVirtualMachines\App\UI\Admin\ProductConfig\Fields;

use Google_Service_Compute;
use ModulesGarden\Servers\GoogleCloudVirtualMachines\App\Api\ApiClient;
use ModulesGarden\Servers\GoogleCloudVirtualMachines\App\Api\GoogleClientFactory;
use ModulesGarden\Servers\GoogleCloudVirtualMachines\App\Models\Whmcs\Product;
use ModulesGarden\Servers\GoogleCloudVirtualMachines\App\Repositories\ProductSettingRepository;
use ModulesGarden\Servers\GoogleCloudVirtualMachines\Core\UI\Interfaces\AdminArea;
use ModulesGarden\Servers\GoogleCloudVirtualMachines\Core\UI\Widget\Forms\AjaxFields\Select;
use ModulesGarden\Servers\GoogleCloudVirtualMachines\Core\FileReader\Reader\Json;
use ModulesGarden\Servers\GoogleCloudVirtualMachines\Core\ModuleConstants;

class ImageProjectSelect extends Select implements AdminArea
{

    public function prepareAjaxData()
    {
        //init setting
        $this->productSetting = new ProductSettingRepository($this->getRequestValue('id'));
        //load images
        $this->setOptions();
        $this->setSelectedValue($this->productSetting->imageProject);
    }

    protected function setOptions()
    {
        $options=[];
        $dataJson    = new Json('image_projects.json', ModuleConstants::getFullPath('storage', 'app'));
        foreach ($dataJson->get() as $k => $entity)
        {
            $options[]=   [
                'key' => $k,
                'value' => $entity
            ];
        }
        $this->setAvailableValues($options);
    }

}
