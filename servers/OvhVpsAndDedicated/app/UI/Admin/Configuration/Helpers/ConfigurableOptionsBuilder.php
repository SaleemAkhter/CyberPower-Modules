<?php

namespace ModulesGarden\Servers\OvhVpsAndDedicated\App\UI\Admin\Configuration\Helpers;

use ModulesGarden\Servers\OvhVpsAndDedicated\App\Service\ConfigurableOptions\ConfigurableOptions;

use ModulesGarden\Servers\OvhVpsAndDedicated\App\UI\Admin\Configuration\Helpers\Dedicated;


/**
 * Description of Config
 *
 * @author Artur Pilch <artur.pi@modulesgarden.com>
 */
class ConfigurableOptionsBuilder
{

    public static function build(ConfigurableOptions $configurableOptions, $fieldsStatus = [])
    {
        $provider = new ConfigurableOptionsBuilderProvider($configurableOptions->getProductID(), true);

        foreach ($fieldsStatus as $key => $field)
        {
            if ($field == "on")
            {
                $configurableOptions->addOption($provider->{$key}());
            }
        }

        return $configurableOptions;
    }


    public static function buildAll(ConfigurableOptions $configurableOptions, $generateOptions = true)
    {
        $provider        = new ConfigurableOptionsBuilderProvider($configurableOptions->getProductID(), $generateOptions);
        $configurableOptions
            ->addOption($provider->product())
            ->addOption($provider->systemVersions())
            ->addOption($provider->localization())
            ->addOption($provider->license())
            ->addOption($provider->snapshot());


        return $configurableOptions;
    }

    public static function buildAllDedicated(ConfigurableOptions $configurableOptions)
    {
        $provider        = new Dedicated\ConfigurableOptionsBuilderProvider($configurableOptions->getProductID());

        $configurableOptions
            ->addOption($provider->dedicatedSystemTemplate())
            ->addOption($provider->dedicatedLanguage())
        ;

        return $configurableOptions;
    }

    public static function buildDedicated(ConfigurableOptions $configurableOptions, $fieldsStatus = [])
    {
        $provider = new Dedicated\ConfigurableOptionsBuilderProvider($configurableOptions->getProductID());


        foreach ($fieldsStatus as $key => $field)
        {
            if ($field == "on")
            {
                $configurableOptions->addOption($provider->{$key}());
            }
        }

        return $configurableOptions;
    }
}
