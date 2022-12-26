<?php

/* * ********************************************************************
 * ProxmoxAddon product developed. (Sep 13, 2018)
 * *
 *
 *  CREATED BY MODULESGARDEN       ->       http://modulesgarden.com
 *  CONTACT                        ->       contact@modulesgarden.com
 *
 *
 * This software is furnished under a license and may be used and copied
 * only  in  accordance  with  the  terms  of such  license and with the
 * inclusion of the above copyright notice.  This software  or any other
 * copies thereof may not be provided or otherwise made available to any
 * other person.  No title to and  ownership of the  software is  hereby
 * transferred.
 *
 *
 * ******************************************************************** */

namespace ModulesGarden\Servers\VultrVps\App\Helpers;

/**
 * Description of Format
 *
 * @author Pawel Kopec <pawelk@modulesgardne.com>
 */
class Format
{

    /**
     * FUNCTION convert
     * Format bytes
     * @param int $bytes
     * @param int $precision
     * @return string
     */
    public static function convertBytes($bytes, $precision = 2)
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];

        $bytes = max($bytes, 0);
        $pow   = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow   = min($pow, count($units) - 1);
        $bytes /= (1 << (10 * $pow));

        return round($bytes, $precision) . ' ' . $units[$pow];
    }

    public static function uptime($uptime)
    {
        if (!$uptime)
        {
            return false;
        }
        $days  = floor($uptime / 60 / 60 / 24);
        $hours = $uptime / 60 / 60 % 24;
        $mins  = $uptime / 60 % 60;
        $secs  = $uptime % 60;

        $hours = ($hours < 10) ? "0" . $hours : $hours;
        $mins  = ($mins < 10) ? "0" . $mins : $mins;
        $secs  = ($secs < 10) ? "0" . $secs : $secs;

        if ($days)
        {
            return "{$days} days $hours:$mins:$secs";
        }
        else
        {
            return "$hours:$mins:$secs";
        }
    }
}
