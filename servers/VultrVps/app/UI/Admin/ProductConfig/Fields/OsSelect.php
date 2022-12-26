<?php

namespace ModulesGarden\Servers\VultrVps\App\UI\Admin\ProductConfig\Fields;

use ModulesGarden\Servers\VultrVps\App\Api\ApiClient;
use ModulesGarden\Servers\VultrVps\App\Api\ApiClientFactory;
use ModulesGarden\Servers\VultrVps\App\Models\Whmcs\Product;
use ModulesGarden\Servers\VultrVps\App\Repositories\ProductSettingRepository;
use ModulesGarden\Servers\VultrVps\Core\UI\Interfaces\AdminArea;
use ModulesGarden\Servers\VultrVps\Core\UI\Widget\Forms\AjaxFields\Select;
use function ModulesGarden\Servers\VultrVps\Core\Helper\sl;

class OsSelect extends Select implements AdminArea
{
    protected $id = 'os_id';
    protected $name = 'os_id';
    /**
     * @var ApiClient
     */
    protected $apiClient;
    /**
     * @var ProductSettingRepository
     */
    protected $productSetting;

    public function prepareAjaxData()
    {
        //init product
        $this->product        = new Product();
        $this->product->id    = $this->getRequestValue('id');
        $this->productSetting = new ProductSettingRepository($this->product->id);
        //init params
        sl("whmcsParams")->setParams($this->product->getParams());
        //api
        $this->apiClient = (new ApiClientFactory())->fromWhmcsParams();
        //load images
        $this->setOptions();
        $this->setSelectedValue($this->productSetting->get($this->id));
    }

    protected function setOptions()
    {
        $options = [];
        $images = [];
        $osRepository = $this->apiClient->os();
        $osRepository->findNotName(['Snapshot','Custom','Backup','Application']);
        foreach ($osRepository->get() as $entery)
        {
            $images[$entery->id] = $entery->name;
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
