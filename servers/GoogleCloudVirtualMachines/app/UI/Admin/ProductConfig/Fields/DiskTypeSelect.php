<?php

namespace ModulesGarden\Servers\GoogleCloudVirtualMachines\App\UI\Admin\ProductConfig\Fields;

use Google_Service_Compute;
use ModulesGarden\Servers\GoogleCloudVirtualMachines\App\Repositories\ProductSettingRepository;
use ModulesGarden\Servers\GoogleCloudVirtualMachines\Core\UI\Interfaces\AdminArea;
use ModulesGarden\Servers\GoogleCloudVirtualMachines\Core\UI\Widget\Forms\AjaxFields\Select;
use function ModulesGarden\Servers\GoogleCloudVirtualMachines\Core\Helper\sl;

class DiskTypeSelect extends Select implements AdminArea
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
        $this->setSelectedValue($this->productSetting->diskType);
    }

    protected function setOptions()
    {
        $options=[];
        $compute = new Google_Service_Compute(sl('ApiClient')->getGoogleClient());
        $project = sl('ApiClient')->getProject();
        foreach ($compute->diskTypes->listDiskTypes($project,$this->zone)->getItems() as $entery)
        {
            if(in_array($entery->getName(),['local-ssd'])){
                continue;
            }
            /**
             * @var  \Google_Service_Compute_DiskType $entery
             */
            $options[] = [
                'key' => $entery->getName(),
                'value' => $entery->getDescription()
            ];
        }
        $this->setAvailableValues($options);
    }

}
