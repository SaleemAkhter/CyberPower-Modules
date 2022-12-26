<?php

namespace ModulesGarden\Servers\DigitalOceanDroplets\App\Helpers;

use ModulesGarden\Servers\DigitalOceanDroplets\App\Libs\DigitalOceanDroplets\Api;
use ModulesGarden\Servers\DigitalOceanDroplets\App\UI\Rebuild\Helpers\ImageManager;

class RebuildDropletFieldsHelper
{
    /**
     * @param array $whmcsParams
     * @return array of available images
     */
    public static function getAvailableImages( array $whmcsParams)
    {
        $fieldDataProv =  new FieldsProvider($whmcsParams['packageid']);

        $api = new Api($whmcsParams);
        $onlyInitiallyBought = $fieldDataProv->getField('clientAreaOnlyInitialImageRebuild') === 'on';

        $dataManger   = new ImageManager($whmcsParams);

        if($onlyInitiallyBought){
            $image = $api->droplet->one()->image;
            $data = $dataManger->getOnlyInitialPurchaseImages($image->slug);
        }else{
            $data         = $dataManger->getAllTemplates();
        }
        return $data;
    }
}