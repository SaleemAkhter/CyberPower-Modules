<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\Traits;

use ModulesGarden\Servers\DirectAdminExtended\App\Libs\Enums\WhmcsEnums;
use ModulesGarden\Servers\DirectAdminExtended\App\Models\FunctionsSettings;
use ModulesGarden\Servers\DirectAdminExtended\Core\Helper;
use ModulesGarden\WordpressManager\Core\Models\Whmcs\Hosting;

/**
 * ProductsFeatureComponent trait
 *
 * @author Michal Zarow <michal.za@modulesgarden.com>
 */
trait ProductsFeatureComponent
{
    protected $featuresSettingsList = null;

    protected function loadFeaturesSettingsList($productId = null)
    {
        if ($this->featuresSettingsList !== null)
        {
            return;
        }

        if($productId === null)
        {
            global $smarty;
            $productId = $smarty->get_template_vars('pid');
        }

        $this->featuresSettingsList = FunctionsSettings::where('product_id', '=', $productId)->first();
    }

    protected function isFeatureEnabled($feature , $productId = null)
    {
        $this->loadFeaturesSettingsList($productId);
        
        if (!$this->featuresSettingsList)
        {
            return false;
        }

        if ($this->featuresSettingsList->{$feature} == 'on')
        {
            return true;
        }
        
        return false;
    }


    protected function getFeaturesSettings($feature , $productId = null)
    {
        $this->loadFeaturesSettingsList($productId);

        if (!$this->featuresSettingsList)
        {
            return false;
        }

        return $this->featuresSettingsList->{$feature};
    }

    protected function isHostingAvtive()
    {
        return static::isHostingActiveStatic();
    }

    public static function isHostingActiveStatic()
    {
        $request = Helper\sl('request');
        $id = $request->get('id');
        if(!$id) {
            return false;
        }

        $hosting = (new Hosting())->where('id', $id)->first();
        if($hosting->domainStatus === WhmcsEnums::DomainStatusActive){
            return true;
        }

        return false;
    }

    /**
     *
     * @description get product id for hosting
     * @global type $smarty
     * @return type
     */
    public static function getHostingProductId()
    {
        global $smarty;
        $productId = $smarty->get_template_vars('pid');
        return $productId;
    }

    public function getHostingId()
    {
        $request = Helper\sl('request');
        return $request->get('id');
    }
}
