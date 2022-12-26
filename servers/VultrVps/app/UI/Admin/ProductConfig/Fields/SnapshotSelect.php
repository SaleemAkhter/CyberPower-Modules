<?php

namespace ModulesGarden\Servers\VultrVps\App\UI\Admin\ProductConfig\Fields;

use ModulesGarden\Servers\VultrVps\App\Api\ApiClient;
use ModulesGarden\Servers\VultrVps\App\Api\ApiClientFactory;
use ModulesGarden\Servers\VultrVps\App\Models\Whmcs\Product;
use ModulesGarden\Servers\VultrVps\App\Repositories\ProductSettingRepository;
use ModulesGarden\Servers\VultrVps\Core\UI\Interfaces\AdminArea;
use ModulesGarden\Servers\VultrVps\Core\UI\Widget\Forms\AjaxFields\Select;
use function ModulesGarden\Servers\VultrVps\Core\Helper\sl;

class SnapshotSelect extends OsSelect
{
    protected $id = 'snapshot_id';
    protected $name = 'snapshot_id';



    protected function setOptions()
    {
        $osId = $this->getRequestValue('mgpci')['os_id'] ? $this->getRequestValue('mgpci')['os_id'] : $this->productSetting->os_id;
        $options = [];
        $images = [];
        foreach ($this->apiClient->images() as $entery)
        {
            if($entery->status != 'available'){
                continue;
            }
            $images[$entery->id] = $entery->operating_system->display_name;
        }
        asort($images);
        foreach ($images as $id => $image) {
            $options[] = [
                'key'   => $id,
                'value' => $image
            ];
        }
        $this->setAvailableValues($options);
    }

}
