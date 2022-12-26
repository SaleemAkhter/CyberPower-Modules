<?php

namespace ModulesGarden\Servers\VultrVps\App\UI\Admin\ProductConfig\Providers;

use ModulesGarden\Servers\VultrVps\App\Api\ApiClient;
use ModulesGarden\Servers\VultrVps\App\Api\ApiClientFactory;
use ModulesGarden\Servers\VultrVps\App\Models\Whmcs\Product;
use ModulesGarden\Servers\VultrVps\App\Repositories\ProductSettingRepository;
use ModulesGarden\Servers\VultrVps\Core\UI\Interfaces\AdminArea;
use ModulesGarden\Servers\VultrVps\Core\UI\Widget\Forms\DataProviders\BaseDataProvider;
use function ModulesGarden\Servers\VultrVps\Core\Helper\sl;

class ConfigProvider extends BaseDataProvider implements AdminArea
{
    /**
     * @var ApiClient
     */
    protected $apiClient;
    /**
     * @var Product
     */
    protected $product;
    /**
     * @var ProductSettingRepository
     */
    protected $productSetting;

    public function __construct()
    {
        $this->product        = new Product();
        $this->product->id    = $this->getRequestValue('id');
        $this->productSetting = new ProductSettingRepository($this->product->id);
    }


    public function read()
    {

        sl("whmcsParams")->setParams($this->product->getParams());
        $lang = sl('lang');
        $this->apiClient = (new ApiClientFactory())->fromWhmcsParams();
        foreach ($this->productSetting->all() as $key => $value)
        {
            if(is_array($value)){
                $this->data[sprintf("%s[]", $key)] = $value;
            }else{
                $this->data[sprintf("%s", $key)] = $value;
            }

        }
        //region
        $this->availableValues['region'] = [];
        foreach ($this->apiClient->regions() as $region)
        {
            $this->availableValues['region'][$region->getId()]  = sprintf("%s - %s", $region->getCity() ,$region->getCountry());
        }
        asort($this->availableValues['region']);
        //plan
        foreach ($this->apiClient->plans() as $entery)
        {
            $this->availableValues['plan'][$entery->id] = $entery->id;
        }
        //os_id
        $this->availableValues['os_id'][0] = $lang->abtr('Disabled');
        //changeOsId
        $this->availableValues['changeOsId[]']=[];
        $osRepository = $this->apiClient->os();
        $osRepository->findNotName(['Snapshot','Custom','Backup','Application']);
        foreach ((array) $osRepository->get() as $entery)
        {
            $this->availableValues['os_id'][$entery->id] = $entery->name;
            $this->availableValues['changeOsId[]'][$entery->id] = $entery->name;
        }
        asort($this->availableValues['os_id']);
        asort($this->availableValues['changeOsId[]']);
        //iso_id
        $this->availableValues['iso_id'][0] = $lang->abtr('Disabled');
        foreach ((array) $this->apiClient->iso() as $entery)
        {
            $this->availableValues['iso_id'][$entery->id] = $entery->filename;
        }
        asort($this->availableValues['iso_id']);
        //snapshot_id
        $this->availableValues['snapshot_id'][0] = $lang->abtr('Disabled');
        foreach ((array) $this->apiClient->snapshots() as $entery)
        {
            $this->availableValues['snapshot_id'][$entery->id] = $entery->description;
        }
        asort($this->availableValues['snapshot_id']);
        /*application
        $this->availableValues['app_id'][0] = $lang->abtr('Disabled');
        foreach ((array) $this->apiClient->applications() as $entery)
        {
            $this->availableValues['app_id'][$entery->id] = $entery->deploy_name;
        }
        */
    }

    public function update()
    {
        $values = $this->getRequestValue('mgpci');
        if (empty($values))
        {
            return;
        }
        $this->productSetting->flush();
        sleep(1);
        //save
        $this->productSetting->fill($values)
            ->save();
    }

}
