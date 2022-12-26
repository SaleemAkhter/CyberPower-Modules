<?php

namespace ModulesGarden\Servers\GoogleCloudVirtualMachines\App\UI\Admin\ProductConfig\Providers;

use ModulesGarden\Servers\GoogleCloudVirtualMachines\App\Api\ApiClient;
use ModulesGarden\Servers\GoogleCloudVirtualMachines\App\Api\GoogleClientFactory;
use ModulesGarden\Servers\GoogleCloudVirtualMachines\App\Models\Whmcs\Product;
use ModulesGarden\Servers\GoogleCloudVirtualMachines\App\Repositories\ProductSettingRepository;
use ModulesGarden\Servers\GoogleCloudVirtualMachines\Core\UI\Interfaces\AdminArea;
use ModulesGarden\Servers\GoogleCloudVirtualMachines\Core\UI\Widget\Forms\DataProviders\BaseDataProvider;
use function ModulesGarden\Servers\GoogleCloudVirtualMachines\Core\Helper\sl;
use ModulesGarden\Servers\GoogleCloudVirtualMachines\App\Traits\ProductSetting;

class ConfigProvider extends BaseDataProvider implements AdminArea
{
    use ProductSetting;
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
        $this->productSetting = new ProductSettingRepository($this->getRequestValue('id') );
    }


    public function read()
    {
        foreach ($this->productSetting->all() as $key => $value)
        {
            $this->data[sprintf("%s", $key)] = $value;
        }
    }

    public function update()
    {
        $values         = $this->getRequestValue('mgpci');
        if (empty($values))
        {
            return;
        }
        //delete
        $this->productSetting->flush();
        sleep(1);
        //save
        $this->productSetting->fill($values)
             ->save();
    }
    
    public function getSnapshotsEnabledValue(){
        return $this->productSetting()->snapshots;
    }

    public function getGraphsEnabledValue(){
        return $this->productSetting()->graphs;
    }

}
