<?php

namespace ModulesGarden\Servers\OvhVpsAndDedicated\App\UI\Admin\Configuration\Fields\Vps;

use ModulesGarden\Servers\OvhVpsAndDedicated\App\UI\Admin\Configuration\Fields\Base\Vps\VpsConfigSelect;
use ModulesGarden\Servers\OvhVpsAndDedicated\Core\UI\Interfaces\AdminArea;
use ModulesGarden\Servers\OvhVpsAndDedicated\Core\UI\Interfaces\ClientArea;

/**
 * Class DedicatedLanguage
 *
 * @author Artur Pilch <artur.pi@modulesgarden.com>
 */
class Distribution extends VpsConfigSelect implements AdminArea, ClientArea
{


    public function getFieldValues()
    {
        $categories      = $this->config->getCategory();
        $categoryValue   = $this->fieldsProvider->getField('vpsCategory');
        $requestCategory = $this->getRequestValue('packageconfigoption_vpsCategory');
        $categoryValue   = $requestCategory ? $requestCategory : $categoryValue;

        $products       = $this->config->getProduct($categoryValue && array_key_exists($categoryValue, $categories) ? $categoryValue : key($categories));
        $productValue   = $this->fieldsProvider->getField('vpsProduct');
        $requestProduct = $this->getRequestValue('packageconfigoption_vpsProduct');
        $requestProduct ? $productValue = $requestProduct : null;

        $os  = $this->config->getOS($productValue && array_key_exists($productValue, $products) ? $productValue : key($products));
        $osValue = $this->fieldsProvider->getField('vpsOs');
        $requestOs = $this->getRequestValue('packageconfigoption_vpsOs');
        $requestOs ? $osValue = $requestOs : null;

        $distribution = $this->config->getDistributions($osValue && array_key_exists($osValue, $os) ? $osValue : key($os));

        return $distribution;
    }

    public function getSelectedValue()
    {
        if($selected = $this->getRequestValue('packageconfigoption_vpsDistribution'))
        {
            return $selected;
        }
        return parent::getSelectedValue();
    }
}
