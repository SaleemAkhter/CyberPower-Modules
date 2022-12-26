<?php

namespace ModulesGarden\Servers\OvhVpsAndDedicated\App\Helpers\Ovh\Vps\Order;

/**
 * Class Provider
 *
 * @author Artur Pilch <artur.pi@modulesgarden.com>
 */
class Provider
{
    public static function getDataToCreateCartFromParams($ovhSubsidiary)
    {
        return [
            'description'   => 'Vps Order Cart',
            'ovhSubsidiary' => $ovhSubsidiary,
        ];
    }
}