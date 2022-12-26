<?php


namespace ModulesGarden\Servers\GoogleCloudVirtualMachines\App\UI\Admin\ProductConfig\Fields;


use ModulesGarden\Servers\GoogleCloudVirtualMachines\App\Repositories\ProductSettingRepository;
use ModulesGarden\Servers\GoogleCloudVirtualMachines\Core\UI\Interfaces\AdminArea;
use ModulesGarden\Servers\GoogleCloudVirtualMachines\Core\UI\Widget\Forms\AjaxFields\Select;
use function ModulesGarden\Servers\GoogleCloudVirtualMachines\Core\Helper\sl;

class CustomMachineTypeSelect extends Select implements AdminArea
{
    public function prepareAjaxData()
    {
        //init setting
        $this->productSetting = new ProductSettingRepository($this->getRequestValue('id'));

        //load options
        $this->setOptions();
        $this->setSelectedValue($this->productSetting->customMachineType);
    }

    protected function setOptions()
    {

        $options = [
            [
                'key' => '',
                'value' => 'N1'
            ],
            [
                'key' => 'n2-',
                'value' => 'N2'
            ],
            [
                'key' => 'n2d-',
                'value' => 'N2D'
            ],
            [
                'key' => 'e2-',
                'value' => 'E2'
            ]
        ];

        $this->setAvailableValues($options);
    }
}