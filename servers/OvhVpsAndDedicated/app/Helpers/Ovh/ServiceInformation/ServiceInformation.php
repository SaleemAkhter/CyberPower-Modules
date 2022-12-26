<?php

namespace ModulesGarden\Servers\OvhVpsAndDedicated\App\Helpers\Ovh\ServiceInformation;

use ModulesGarden\Servers\OvhVpsAndDedicated\App\Helpers\Formatter\Formatter;
use ModulesGarden\OvhVpsAndDedicated\App\Helpers\Formatter as StyleFormatter;
use ModulesGarden\Servers\OvhVpsAndDedicated\Core\ServiceLocator;
use ModulesGarden\Servers\OvhVpsAndDedicated\App\UI\Admin\Disks\Helpers\Decorators\Unit;

/**
 * Class ServiceInformation
 *
 * @author Artur Pilch <artur.pi@modulesgarden.com>
 */
class ServiceInformation
{
    const UP = 'up';
    const DOWN = 'down';

    public static function convertInformation($info)
    {
        unset($info['model']);
        unset($info['monitoringIpBlocks']);

        $toFormat = [
            'cluster'     => Formatter::UCFIRST,
            'netbootMode' => Formatter::UCFIRST,
            'state'       => Formatter::UCFIRST,
            'offerType'   => Formatter::STRTOUPPER
        ];

        $info = Formatter::format($info, $toFormat);

        isset($info['state']) ? $info['state'] = StyleFormatter\State::vps($info['state']) : false;
        isset($info['memoryLimit']) ? $info['memoryLimit'] = Unit::decorate($info['memoryLimit'], Unit::MB) : false;

        $info = StyleFormatter\State::convertTrueAndFalseToEnabledAndDisabledWithLabel($info);
        return Formatter::formatToNameAndValueWithLangedName($info);
    }

    public static function convertStatuses($statuses)
    {
        $langManager = ServiceLocator::call('lang');

        foreach ($statuses as &$status)
        {
            $lang = $langManager->absoluteTranslate('server', 'machine', 'state', self::UP);
            if ($status != self::UP && $status['state'] != self::UP)
            {
                $lang = $langManager->absoluteTranslate('server', 'machine', 'state', self::DOWN);
            }
            $status = $lang;
            $status = StyleFormatter\State::vps($status);
        }

        return Formatter::formatToNameAndValueWithLangedName($statuses);
    }

    public static function convertDedicatedInformation($data)
    {
        $toFormat = [
            'supportLevel' => Formatter::UCFIRST,
            'commercialRange' => Formatter::STRTOUPPER,
            'state' => Formatter::UCFIRST,
        ];

        if(isset($data['backup']))
        {
            $data['backup'] = self::convertBackup($data['backup']);
        }

        $data = Formatter::format($data, $toFormat);

        $data['state'] = StyleFormatter\State::vps($data['state']);
        $data = StyleFormatter\State::convertTrueAndFalseToEnabledAndDisabledWithLabel($data);

        $data = Formatter::formatToNameAndValueWithLangedName($data);

        return $data;
    }

    private static function convertBackup($backup)
    {
        $usage = $backup['usage'] ? $backup['usage'] : 0;
        $unit = $backup['quota']['unit'];
        $value = $backup['quota']['value'];

        return "{$usage} / {$value} {$unit}";
    }




}