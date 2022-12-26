<?php

namespace ModulesGarden\Servers\VultrVps\App\Traits;

use ModulesGarden\Servers\VultrVps\App\Repositories\ProductSettingRepository;

/**
 * Trait ProductSetting
 * @package ModulesGarden\Servers\VultrVps\App\Traits
 * @method getWhmcsParamByKey()
 */
trait ProductSetting
{

    /**
     * @var ProductSettingRepository
     */
    protected $productSetting;


    /**
     * @return ProductSettingRepository
     */
    public function productSetting()
    {
        if (empty($this->productSetting))
        {
            $this->productSetting = new ProductSettingRepository($this->getWhmcsParamByKey("packageid"));
        }
        return $this->productSetting;
    }
}