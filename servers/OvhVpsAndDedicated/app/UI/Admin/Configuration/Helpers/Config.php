<?php

namespace ModulesGarden\Servers\OvhVpsAndDedicated\App\UI\Admin\Configuration\Helpers;

use ModulesGarden\Servers\OvhVpsAndDedicated\App\UI\Admin\Configuration\Helpers\Abstracts\ConfigBase;
use ModulesGarden\Servers\OvhVpsAndDedicated\App\UI\Admin\Configuration\Helpers\Prepare\Prepare;
use ModulesGarden\Servers\OvhVpsAndDedicated\Core\ServiceLocator;
use ModulesGarden\Servers\OvhVpsAndDedicated\Core\RegisterManager\Register;


/**
 * Description of Config
 *
 * @author Artur Pilch <artur.pi@modulesgarden.com>
 */
class Config extends ConfigBase
{


    protected $clearProductInfo;
    protected $datacenter;

    public function setBasics()
    {
        $this->productInfo = $this->api->order->catalog()->formatted()->vps();
        $this->clearProductInfo = $this->productInfo;
    }

    /**
     * @return array
     */
    public function getOrderCatalogFormattedVps(){
        return $this->productInfo;
    }

    public function getDistributions($value)
    {
        $this->productInfo = $this->productInfo[$value];

        return Prepare::vpsDistribution($this->productInfo);
    }

    public function getOptions($value)
    {
        $out = [];
        foreach ($this->clearProductInfo as $products)
        {
            foreach($products['plans'] as $item)
            {
                if($item['planCode'] != $value)
                {
                    continue;
                }
                foreach ($item['addonsFamily'] as $addonsFamily)
                {
                    $family = $addonsFamily['family'];

                    foreach ($addonsFamily['addons'] as $addon)
                    {
                        $out[$family][] = $addon['plan'];
                    }
                }
            }
        }

        return $out;
    }

    /**
     * @return array
     * @deprecated since 1.0.7
     */
    public function getCategory()
    {
        return Prepare::vpsCategory($this->productInfo);
    }

    public function getProduct($family=null)
    {
        return Prepare::vpsProduct($this->productInfo['plans']);
    }

    public function getLocalizations($value)
    {
        foreach ($this->productInfo['plans'] as $item)
        {
            if ($value == $item['details']['product']['name'])
            {
                foreach ($item['details']['product']['configurations'] as $configuration) {
                    if($configuration['name']=='vps_datacenter'){
                        return Prepare::vpsLocalization($configuration['values']);
                    }
                }
            }
        }
    }

    public function getOS($value)
    {
        foreach ($this->productInfo['plans'] as $item)
        {
            if ($value == $item['details']['product']['name'])
            {
                foreach ($item['details']['product']['configurations'] as $configuration) {
                   if($configuration['name']=='vps_os'){
                       return Prepare::vpsOS($configuration['values']);
                   }
                }
            }
        }
    }

    public function getVpsDistributionVersion($value)
    {
        $this->productInfo = $this->productInfo[$value];
        return Prepare::vpsDistributionVersion($this->productInfo);
    }

    public function getVpsDistributionLanguages($value)
    {
        $exploded = explode(':', $value);
        $version  = $exploded[0];
        $bits     = $exploded[1];

        $this->productInfo = $this->productInfo[$version][$bits];
        return Prepare::vpsDistributionLanguages($this->productInfo);
    }

    public function getAllVpsProducts()
    {
        return Prepare::vpsProduct($this->clearProductInfo['plans']);
    }

    public function getAllSystemVersions()
    {
        $out = [];
        foreach ($this->clearProductInfo['plans'] as $item)
        {

            foreach ($item['details']['product']['configurations'] as $configuration) {
                if($configuration['name']=='vps_os'){
                    $out +=  Prepare::vpsOS($configuration['values']);
                }
            }
        }
        return $out;
    }

    public function getAllSystemLanguages()
    {
        $out = [];
        foreach ($this->clearProductInfo as $products)
        {
            foreach($products['plans'] as $item)
            {
                foreach ($item['extraInfos']['os'] as $systems)
                {
                    foreach ($systems as $versions)
                    {
                        foreach ($versions as $bits)
                        {
                            foreach ($bits as $languages)
                            {
                                $out += Prepare::vpsDistributionLanguages($languages);
                            }
                        }

                    }
                }
            }
        }
        return $out;
    }

    public function getLicense($family)
    {
        $out = [];
        foreach ($this->productInfo['plans'] as $item)
        {
            foreach ($item['addonsFamily'] as $addonFamily){
                if ($family == $addonFamily['family'])
                {
                    foreach ( $addonFamily['addons'] as $addon){

                        $out[ $addon['plan']['planCode']] = $addon['invoiceName'];
                    }
                }
            }

        }
        return $out;
    }

    public function getDuration()
    {
        $values = [1,3,6,12];
        $out = [];
        $lang = ServiceLocator::call('lang');

        foreach ($values as $value)
        {
            $toLang      = "$value months";
            $out[$value] = $lang->translate('durationPeriod', $toLang);

        }

        return $out;
    }

    public static function getSslType()
    {
        $lang = ServiceLocator::call('lang');
        return [
            'NONE' => $lang->absoluteTranslate('productPage', 'configuration', 'smpt', 'type', 'none'),
            'SSL'  => $lang->absoluteTranslate('productPage', 'configuration', 'smpt', 'type', 'ssl'),
            'TLS'  => $lang->absoluteTranslate('productPage', 'configuration', 'smpt', 'type', 'tls'),
            'SSL/novalidate-cert'  => $lang->absoluteTranslate('productPage', 'configuration', 'smpt', 'type', 'sslNoCert'),
        ];
    }

}
