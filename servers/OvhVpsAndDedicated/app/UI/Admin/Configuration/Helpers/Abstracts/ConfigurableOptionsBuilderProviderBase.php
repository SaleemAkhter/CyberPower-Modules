<?php

namespace ModulesGarden\Servers\OvhVpsAndDedicated\App\UI\Admin\Configuration\Helpers\Abstracts;


/**
 * Class ConfigurableOptionsBuilderProvider
 *
 * @author Artur Pilch <artur.pi@modulesgarden.com>
 */
abstract class ConfigurableOptionsBuilderProviderBase
{
    protected $productId;

    protected $config;

    public function __construct($productId = null)
    {
        $this->productId = $productId;
    }

    /**
     * @return \ModulesGarden\Servers\OvhVpsAndDedicated\App\UI\Admin\Configuration\Helpers\Config
     */
    public function getConfig()
    {
        return $this->config;
    }
}