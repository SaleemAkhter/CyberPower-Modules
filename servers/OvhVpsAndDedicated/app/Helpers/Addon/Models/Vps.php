<?php

namespace ModulesGarden\Servers\OvhVpsAndDedicated\App\Helpers\Addon\Models;

use ModulesGarden\OvhVpsAndDedicated\App\Libs\Repository\Manage\Machine;
/**
 * Class Vps
 *
 * @author Artur Pilch <artur.pi@modulesgarden.com>
 */
class Vps
{
    CONST REUSE = 'reuse';
    CONST ON = 'on';

    public static function assignVpsAsReusable($vpsName)
    {
        return Machine::createOrUpdateSetting($vpsName, SELF::REUSE, SELF::ON);
    }
}