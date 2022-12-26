<?php

namespace ModulesGarden\Servers\OvhVpsAndDedicated\App\UI\Admin\Disks\Helpers\Decorators;

/**
 * Class Unit
 *
 * @author Artur Pilch <artur.pi@modulesgarden.com>
 */
class Unit
{
    const MB = 'MB';
    const GB = 'GB';

    const SECONDS = 's';

    public static function decorate($amount, $unit, $perTime = null)
    {
        $out = "$amount $unit";
        if($perTime)
        {
            $out .= "/$perTime";
        }
        return $out ;
    }
}