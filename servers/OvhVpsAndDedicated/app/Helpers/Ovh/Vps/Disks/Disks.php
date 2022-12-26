<?php

namespace ModulesGarden\Servers\OvhVpsAndDedicated\App\Helpers\Ovh\Vps\Disks;

use ModulesGarden\Servers\OvhVpsAndDedicated\App\Helpers\Formatter\Formatter;


/**
 * Class Disk
 *
 * @author Artur Pilch <artur.pi@modulesgarden.com>
 */
class Disks
{
    public static function formatToDatatable($disks)
    {
        return Formatter::formatForDisplay($disks, Fields::getFieldToFormat());
    }
}