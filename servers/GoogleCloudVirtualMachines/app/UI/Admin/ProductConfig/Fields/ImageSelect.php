<?php

namespace ModulesGarden\Servers\GoogleCloudVirtualMachines\App\UI\Admin\ProductConfig\Fields;

use Google_Service_Compute;
use ModulesGarden\Servers\GoogleCloudVirtualMachines\App\Api\ProjectFactory;
use ModulesGarden\Servers\GoogleCloudVirtualMachines\App\Repositories\ProductSettingRepository;
use ModulesGarden\Servers\GoogleCloudVirtualMachines\Core\UI\Interfaces\AdminArea;
use ModulesGarden\Servers\GoogleCloudVirtualMachines\Core\UI\Widget\Forms\AjaxFields\Select;
use function ModulesGarden\Servers\GoogleCloudVirtualMachines\Core\Helper\sl;

class ImageSelect extends Select implements AdminArea
{
    protected $imageProject;

    public function prepareAjaxData()
    {
        //init setting
        $this->productSetting = new ProductSettingRepository($this->getRequestValue('id'));
        //load image project
        $this->imageProject = $this->request->get('mgpci')['imageProject'] ? $this->request->get('mgpci')['imageProject']  : $this->productSetting->imageProject;
        if(!$this->imageProject){
            return;
        }
        //load images
        $this->setOptions();
        $this->setSelectedValue($this->productSetting->image);
    }

    protected function setOptions()
    {
        $options=[];
        $compute = new Google_Service_Compute(sl('ApiClient')->getGoogleClient());
        $project = (new ProjectFactory())->fromParams();

        /**
         * Not working now
         */
        $reqOption = [
            'filter' => sprintf(' (deprecated.state != "%s") ', 'DEPRECATED')
        ];

        if($this->imageProject === 'custom-images'){
            $this->imageProject = $project;
        }

        foreach ($compute->images->listImages($this->imageProject)->getItems() as $entry)
        {
            if($entry->deprecated){
                continue;
            }

            /**
             * @var  \Google_Service_Compute_Image $entry
             */
            $options[] = [
                'key' => $entry->getName(),
                'value' => $entry->getDescription() ?? $entry->getName()
            ];
        }
        $this->setAvailableValues($options);
    }

}
