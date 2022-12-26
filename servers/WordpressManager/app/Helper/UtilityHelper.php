<?php

/* * ********************************************************************
 * Wordpress_Manager Product developed. (Dec 8, 2017)
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

namespace ModulesGarden\WordpressManager\App\Helper;

/**
 * Description of Utility
 *
 * @author Pawel Kopec <pawelk@modulesgarden.com>
 */
class UtilityHelper
{

    public static function formatUnit(&$value, $inUnit, $outUnit)
    {

        if (!in_array($inUnit, array('mb', 'gb')))
        {
            throw new \Exception(sprintf("Unit value ('%s') is invalid.", $inUnit));
        }

        if (!in_array($outUnit, array('mb', 'gb')))
        {
            throw new \Exception(sprintf("Unit value ('%s') is invalid.", $inUnit));
        }

        if ($value == "0")
        {
            return;
        }
        if (empty($value) || !is_numeric($value))
        {
            throw new \Exception(sprintf("Unit value ('%s') is invalid.", $value));
        }


        if ($inUnit == $outUnit)
        {
            return;
        }
        else if ($inUnit == 'mb' && $outUnit == 'gb')
        {
            $value = ceil($value / 1024);
        }
        else if ($inUnit == 'gb' && $outUnit == 'mb')
        {
            $value *= 1024;
        }
    }

    /**
     * FUNCTION MG_formatBytes
     * format Bytes
     * @param int $bytes
     * @param int $precision
     * @return string
     */
    public static function formatBytes($bytes, $precision = 2)
    {
        $units = array('B', 'KB', 'MB', 'GB', 'TB');
        $bytes = max($bytes, 0);
        $pow   = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow   = min($pow, count($units) - 1);
        $bytes /= (1 << (10 * $pow));
        return round($bytes, $precision) . ' ' . $units[$pow];
    }

    public static function generatePassword($length = 8, $chars = "")
    {
        if (!$chars)
        {
            $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        }
        $count = mb_strlen($chars);
        for ($i = 0, $result = ''; $i < $length; $i++)
        {
            $index  = rand(0, $count - 1);
            $result .= mb_substr($chars, $index, 1);
        }
        return $result;
    }
    
    public static function  htmlEntityDecode($subject){
        $replace=['&amp;' => '&', '&#8211;' => '-'];
        return str_replace(array_keys( $replace), array_values($replace), $subject);
    }
}
