<?php

namespace ModulesGarden\Servers\OvhVpsAndDedicated\App\UI\Admin\Configuration\Fields\Vps;

use ModulesGarden\Servers\OvhVpsAndDedicated\App\UI\Admin\Configuration\Fields\Base\Vps\VpsConfigSelect;
use ModulesGarden\Servers\OvhVpsAndDedicated\App\UI\Admin\Configuration\Helpers\Fields;
use ModulesGarden\Servers\OvhVpsAndDedicated\Core\UI\Interfaces\AdminArea;
use ModulesGarden\Servers\OvhVpsAndDedicated\Core\UI\Interfaces\ClientArea;

/**
 * Class DedicatedLanguage
 *
 * @author Artur Pilch <artur.pi@modulesgarden.com>
 * @deprecated 1.0.7
 */
class Language extends VpsConfigSelect implements AdminArea, ClientArea
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


        $os        = $this->config->getOS($productValue && array_key_exists($productValue, $products) ? $productValue : key($products));
        $osValue   = $this->fieldsProvider->getField('vpsOs');
        $requestOs = $this->getRequestValue('packageconfigoption_vpsOs');
        $requestOs ? $osValue = $requestOs : null;


        $distribution        = $this->config->getDistributions($osValue && array_key_exists($osValue, $os) ? $osValue : key($os));
        $distributionValue   = $this->fieldsProvider->getField('vpsDistribution');
        $requestDistribution = $this->getRequestValue('packageconfigoption_vpsDistribution');
        $requestDistribution ? $distributionValue = $requestDistribution : null;

        $version      = $this->config->getVpsDistributionVersion($distributionValue && array_key_exists($distributionValue, $distribution) ? $distributionValue : key($distribution));
        $versionValue = $this->fieldsProvider->getField('vpsVersion');
        $requestVersion = $this->getRequestValue('packageconfigoption_vpsVersion');
        $requestVersion ? $versionValue = $requestVersion : null;

        $language     = $this->config->getVpsDistributionLanguages($versionValue && array_key_exists($versionValue, $version) ? $versionValue : key($version));

        return $language;
    }

    public function loadOptions()
    {
        $productValue   = $this->fieldsProvider->getField('vpsProduct');
        $requestProduct = $this->getRequestValue('packageconfigoption_vpsProduct');
        $requestProduct ? $productValue = $requestProduct : null;

        if(!$requestProduct)
        {
            $this->data['additionalData'] = Fields::getOptionsFields();
            return;
        }

        $result = $this->config->getOptions($productValue);

        $out = [];
        foreach (Fields::getOptionsFields() as $item => $value)
        {
            if(isset($result[lcfirst($item)]) && $this->checkSelects(lcfirst($item)))
            {
                $out[$item] = [
                    'action' => 'show',
                    'fieldType'=> $value['fieldType']
                ];
                continue;
            }
            $out[$item] = [
                'action' => 'hide',
                'fieldType'=> $value['fieldType']
            ];
        }

        $this->data['additionalData'] = $out;

    }

    private function checkSelects($key)
    {
        $osValue   = $this->fieldsProvider->getField('vpsOs');
        $requestOs = $this->getRequestValue('packageconfigoption_vpsOs');
        $requestOs ? $osValue = $requestOs : null;

        $distributionValue   = $this->fieldsProvider->getField('vpsDistribution');
        $requestDistribution = $this->getRequestValue('packageconfigoption_vpsDistribution');
        $requestDistribution ? $distributionValue = $requestDistribution : null;

        switch ($key)
        {
            case 'cpanel':
            case 'plesk':
                return $key == $distributionValue;
            case 'windows':
                return $key == $osValue;
        }
        return true;
    }
}
