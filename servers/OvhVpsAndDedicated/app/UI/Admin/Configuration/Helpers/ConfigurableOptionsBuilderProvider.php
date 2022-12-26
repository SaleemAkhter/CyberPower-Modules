<?php

namespace ModulesGarden\Servers\OvhVpsAndDedicated\App\UI\Admin\Configuration\Helpers;

use ModulesGarden\Servers\OvhVpsAndDedicated\App\Helpers\FieldsProvider;
use ModulesGarden\Servers\OvhVpsAndDedicated\App\Service\ConfigurableOptions\Helper\TypeConstans;
use ModulesGarden\Servers\OvhVpsAndDedicated\App\Service\ConfigurableOptions\Models\Option;
use ModulesGarden\Servers\OvhVpsAndDedicated\App\Service\ConfigurableOptions\Models\SubOption;
use ModulesGarden\Servers\OvhVpsAndDedicated\App\UI\Admin\Configuration\Helpers\Abstracts\ConfigurableOptionsBuilderProviderBase;
use ModulesGarden\Servers\OvhVpsAndDedicated\Core\Traits\Lang;

/**
 * Description of Config
 *
 * @author Artur Pilch <artur.pi@modulesgarden.com>
 */
class ConfigurableOptionsBuilderProvider extends ConfigurableOptionsBuilderProviderBase
{
    use Lang;
    /**
     * @var Config
     */
    protected $config;

    /**
     * @var bool
     */
    protected $generateOptions = true;
    /**
     * @var FieldsProvider
     */
    private  $fieldsProvider;

    public function __construct($productId = null, $generateOptions = true)
    {
        parent::__construct($productId);
        $this->fieldsProvider = new FieldsProvider($productId);
        $this->config = new Config($productId);
        $this->generateOptions = $generateOptions;
        $this->loadLang();
    }
    
    public function product()
    {
        $option   = new Option('product', 'Product', TypeConstans::DROPDOWN);
        if($this->generateOptions)
        {
            $products = $this->config->getAllVpsProducts();
            foreach ($products as $planCode => $name)
            {
                if( in_array($planCode, ['publiccloud-vps','s1-2'])){
                    continue;
                }
                $option->addSubOption(new SubOption($planCode, $name));
            }
        }

        return $option;
    }

    public function systemVersions()
    {

        $option   = new Option('systemVersions', 'System Version', TypeConstans::DROPDOWN);

        if($this->generateOptions)
        {
            $products = $this->config->getAllSystemVersions();
            asort($products);
            foreach ($products as $key => $name)
            {
                $option->addSubOption(new SubOption($key, $name));
            }
        }

        return $option;
    }


    public function planCodeVpsOsAddon(){
        $option   = new Option('planCodeVpsOsAddon', 'Solution Type & Operating System & Application', TypeConstans::DROPDOWN);
        if($this->generateOptions)
        {
            $option->addSubOption(new SubOption('0', 'None'));
            $i=0;
            foreach ($this->getConfig()->getOrderCatalogFormattedVps()['plans'] as $item)
            {
                if($i>60){
                    break;
                }
                $planCode = $item['details']['product']['name'];
                $planName = $item['details']['product']['description'];
                if(preg_match("/publiccloud\-vps/",$planCode)){
                    continue;
                }
                foreach ($item['details']['product']['configurations'] as $configuration) {

                    if($configuration['name']!='vps_os'){
                        continue;
                    }
                    //OS
                    foreach ($configuration['values'] as $os){
                        //Addons
                        foreach ($item['addonsFamily'] as $addonFamily){
                            if (in_array($addonFamily['family'],['cpanel','plesk','windows']))
                            {
                                foreach ( $addonFamily['addons'] as $addon){
                                    $key = sprintf("%s:%s:%s",$planCode, $os,$addon['plan']['planCode'] );
                                    $name = sprintf("%s - %s (%s)",$planName, $os, $addon['invoiceName']);
                                    $option->addSubOption(new SubOption($key, $name));
                                    $i++;
                                }
                            }
                        }
                    }
                }
            }
        }
        return $option;
    }

    public function localization()
    {
        $option        = new Option('localization', 'Localization', TypeConstans::DROPDOWN);

        $plan = $this->fieldsProvider->getField('vpsProduct');
        if(!$plan){
            return $option;
        }

        if($this->generateOptions)
        {
            $localizations = $this->config->getLocalizations($plan);
            foreach ($localizations as $key => $name)
            {
                $option->addSubOption(new SubOption($key, $name));
            }
        }

        return $option;
    }



    public function snapshot()
    {
        $option = new Option('snapshot', 'Snapshot', TypeConstans::CHECKBOX);
        $option->addSubOption(new SubOption('snapshot', 'Enabled'));
        return $option;
    }


    public function duration()
    {
        $option   = new Option('duration', 'Duration', TypeConstans::DROPDOWN);

        if($this->generateOptions)
        {
            $subOption = $this->config->getDuration();
            foreach ($subOption as $key => $name)
            {
                $option->addSubOption(new SubOption($key, $name));
            }
        }

        return $option;
    }

    public function license()
    {
        $option   = new Option('license', 'License', TypeConstans::DROPDOWN);

        if($this->generateOptions)
        {
            $option->addSubOption(new SubOption('0', 'None'));
            $licenses = $this->config->getLicense('cpanel');
            foreach ($licenses as $key => $name)
            {
                $option->addSubOption(new SubOption($key, $name));
            }

            $licenses = $this->config->getLicense('plesk');
            foreach ($licenses as $key => $name)
            {
                $option->addSubOption(new SubOption($key, $name));
            }
            $option->addSubOption(new SubOption('option-windows', 'Windows License'));
        }

        return $option;
    }

}
