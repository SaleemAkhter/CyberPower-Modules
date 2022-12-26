<?php

namespace ModulesGarden\Servers\GoogleCloudVirtualMachines\App\UI\Admin\ProductConfig\Fields;

use Google_Service_Compute;
use ModulesGarden\Servers\GoogleCloudVirtualMachines\App\Repositories\ProductSettingRepository;
use ModulesGarden\Servers\GoogleCloudVirtualMachines\Core\UI\Interfaces\AdminArea;
use ModulesGarden\Servers\GoogleCloudVirtualMachines\Core\UI\Widget\Forms\AjaxFields\Select;
use function ModulesGarden\Servers\GoogleCloudVirtualMachines\Core\Helper\sl;

class MachineTypeSelect extends Select implements AdminArea
{

    protected $zone;

    public function prepareAjaxData()
    {
        //init setting
        $this->productSetting = new ProductSettingRepository($this->getRequestValue('id'));
        $this->zone = $this->request->get('mgpci')['zone'] ? $this->request->get('mgpci')['zone']  : $this->productSetting->zone;
        if(!$this->zone){
            return;
        }
        //load options
        $this->setOptions();
        $this->setSelectedValue($this->productSetting->machineType);
    }

    protected function setOptions()
    {
        $this->availableValues[]=[
            'key' => 'customMachine',
            'value' => 'Custom Machine'
        ];

        $options=[];
        $compute = new Google_Service_Compute(sl('ApiClient')->getGoogleClient());
        $project = sl('ApiClient')->getProject();
        foreach ($compute->machineTypes->listMachineTypes($project,$this->zone)->getItems() as $entery)
        {
            /**
             * @var  \Google_Service_Compute_MachineType $entery
             */
            $options[$entery->getName()] = sprintf("%s %s ",$entery->getName(), $entery->getDescription())  ;
        }
        foreach ($options as $key => $value){
            $this->availableValues[]=[
                'key' => $key,
                'value' => $value
            ];
        }
        unset($options);
    }

}
