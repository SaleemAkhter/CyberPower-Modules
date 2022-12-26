<?php

namespace ModulesGarden\Servers\DigitalOceanDroplets\App\UI\Configuration\Helpers;

use ModulesGarden\Servers\DigitalOceanDroplets\App\Helpers\UserData;
use ModulesGarden\Servers\DigitalOceanDroplets\App\Libs\DigitalOceanDroplets\Models\Images\Criteria;
use ModulesGarden\Servers\DigitalOceanDroplets\App\Service\ConfigurableOptions\ConfigurableOptions;
use ModulesGarden\Servers\DigitalOceanDroplets\App\Service\ConfigurableOptions\Helper\TypeConstans;
use ModulesGarden\Servers\DigitalOceanDroplets\App\Service\ConfigurableOptions\Models\Option;
use ModulesGarden\Servers\DigitalOceanDroplets\App\Service\ConfigurableOptions\Models\SubOption;

/**
 * Description of Config
 *
 * @author Mateusz Pawłowski <mateusz.pa@modulesgarden.com>
 */
class ConfigurableOptionsBuilder
{

    public static function build(ConfigurableOptions $configurableOptions, $fieldsStatus = [])
    {
        foreach ($fieldsStatus as $key => $field)
        {
            if ($field == "on")
            {
                $configurableOptions->addOption(self::$key());
            }
        }
        return $configurableOptions;
    }

    public static function buildAll(ConfigurableOptions $configurableOptions)
    {
        $configurableOptions->addOption(self::region())
                ->addOption(self::size())
                ->addOption(self::image())
                ->addOption(self::snapshots())
                ->addOption(self::volume())
                ->addOption(self::backups())
                ->addOption(self::monitoring())
                ->addOption(self::ipv6())
                ->addOption(self::privateNetwork())
                ->addOption(self::userData())
                ->addOption(self::floatingips())
                ->addOption(self::firewalls())
                ->addOption(self::inboundRules())
                ->addOption(self::outboundRules())
                ->addOption(self::totalRules());

        return $configurableOptions;
    }

    private static function region($status = null)
    {
        $regions = Config::getRegionsAndSiezes()['regions'];
        $option  = new Option('region', 'Region', TypeConstans::DROPDOWN);
        foreach ($regions as $key => $name)
        {
            $option->addSubOption(new SubOption($key, $name));
        }
        return $option;
    }

    private static function size()
    {
        $sizes  = Config::getRegionsAndSiezes()['size'];
        $option = new Option('size', 'Size Slug Plan', TypeConstans::DROPDOWN);
        foreach ($sizes as $key => $name)
        {
            $option->addSubOption(new SubOption($key, $name));
        }
        return $option;
    }

    private static function image()
    {
        $criteria = new Criteria();
        $criteria->setPrivate(false);

        $images = Config::getImagesList($criteria);
        $option = new Option('image', 'Image', TypeConstans::DROPDOWN);
        foreach ($images as $key => $name)
        {
            $option->addSubOption(new SubOption($key, $name));
        }
        return $option;
    }

    private static function snapshots()
    {
        $option = new Option('snapshots', 'Snapshots Limit', TypeConstans::QUANTITY);
        $option->addSubOption(new SubOption('1', 'Snapshots'));

        return $option;
    }

    private static function volume()
    {
        $option = new Option('volume', 'Additional Volume Size', TypeConstans::QUANTITY);
        $option->addSubOption(new SubOption('1', 'GB'));

        return $option;
    }

    private static function backups()
    {
        $option = new Option('backups', 'Backups', TypeConstans::CHECKBOX);
        $option->addSubOption(new SubOption('backups', 'Enable Backups'));
        return $option;
    }

    private static function monitoring()
    {
        $option = new Option('monitoring', 'Monitoring', TypeConstans::CHECKBOX);
        $option->addSubOption(new SubOption('monitoring', 'Enable Monitoring'));
        return $option;
    }

    private static function ipv6()
    {
        $option = new Option('ipv6', 'IPv6', TypeConstans::CHECKBOX);
        $option->addSubOption(new SubOption('ipv6', 'Enable IPv6'));
        return $option;
    }

    private static function privateNetwork()
    {
        $option = new Option('privateNetwork', 'Private Networking', TypeConstans::CHECKBOX);
        $option->addSubOption(new SubOption('privateNetwork', 'Enable Private Networking'));
        return $option;
    }

    private static function userData()
    {
        $files  = UserData::getFilesNames();
        $option = new Option('userData', 'User Data', TypeConstans::DROPDOWN);
        foreach ($files as $key => $name)
        {
            $option->addSubOption(new SubOption($key, $name));
        }
        return $option;
    }

    private static function firewalls()
    {
        $option = new Option('firewalls', 'Firewalls Limit', TypeConstans::QUANTITY);
        $option->addSubOption(new SubOption('1', 'Firewalls'));

        return $option;
    }

    private static function floatingips()
    {
        $option = new Option('floatingips', 'Floating IPs', TypeConstans::CHECKBOX);
        $option->addSubOption(new SubOption('floatingips', 'Enable Floating IPs'));

        return $option;
    }

    private static function inboundRules()
    {
        $option = new Option('inboundRules', 'Inbound Rules', TypeConstans::QUANTITY);
        $option->addSubOption(new SubOption('1', 'Inbound Rules'));

        return $option;
    }

    private static function outboundRules()
    {
        $option = new Option('outboundRules', 'Outbound Rules', TypeConstans::QUANTITY);
        $option->addSubOption(new SubOption('1', 'Outbound Rules'));

        return $option;
    }

    private static function totalRules()
    {
        $option = new Option('totalRules', 'Total Rules', TypeConstans::QUANTITY);
        $option->addSubOption(new SubOption('1', 'Total Firewall Rules'));

        return $option;
    }
}
