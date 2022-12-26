<?php

namespace ModulesGarden\Servers\GoogleCloudVirtualMachines\App\Traits;

use ModulesGarden\Servers\GoogleCloudVirtualMachines\App\Repositories\ProductSettingRepository;

/**
 * Trait ProductSetting
 * @package ModulesGarden\Servers\GoogleCloudVirtualMachines\App\Traits
 * @method getWhmcsParamByKey()
 */
trait ProductSetting
{


    /**
     * @return ProductSettingRepository
     */
    public function productSetting(){
        if(empty($this->productSetting)){
            $this->productSetting = new ProductSettingRepository($this->getWhmcsParamByKey("packageid") );
        }
        return $this->productSetting;
    }
}