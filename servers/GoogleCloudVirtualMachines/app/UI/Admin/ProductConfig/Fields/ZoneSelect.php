<?php

namespace ModulesGarden\Servers\GoogleCloudVirtualMachines\App\UI\Admin\ProductConfig\Fields;

use Google_Service_Compute;
use ModulesGarden\Servers\GoogleCloudVirtualMachines\App\Repositories\ProductSettingRepository;
use ModulesGarden\Servers\GoogleCloudVirtualMachines\Core\UI\Interfaces\AdminArea;
use ModulesGarden\Servers\GoogleCloudVirtualMachines\Core\UI\Widget\Forms\AjaxFields\Select;
use function ModulesGarden\Servers\GoogleCloudVirtualMachines\Core\Helper\sl;

class ZoneSelect extends Select implements AdminArea
{

    private  $region;

    public function prepareAjaxData()
    {
        //init setting
        $this->productSetting = new ProductSettingRepository($this->getRequestValue('id'));

        $this->region = $this->request->get('mgpci')['region'] ? $this->request->get('mgpci')['region']  : $this->productSetting->region;
        if(!$this->region){
            return;
        }
        //load options
        $this->setOptions();
        $this->setSelectedValue($this->productSetting->zone);
    }

    protected function setOptions()
    {
        $options=[];
        $compute = new Google_Service_Compute(sl('ApiClient')->getGoogleClient());
        $project = sl("ApiClient")->getProject();
        $regionUrl = sprintf("https://www.googleapis.com/compute/v1/projects/%s/regions/%s",$project, $this->region);
        $reqOption = [
            'filter' => sprintf(' (region = "%s") ', $regionUrl)
        ];
        foreach ($compute->zones->listZones(sl('ApiClient')->getProject(),$reqOption)->getItems() as $entery)
        {
            /**
             * @var  \Google_Service_Compute_Zone $entery
             */
            $options[] = [
                'key' => $entery->getName(),
                'value' => sl('lang')->absoluteT('zone', $entery->getDescription())
            ];
        }
        $this->setAvailableValues($options);
    }

}
