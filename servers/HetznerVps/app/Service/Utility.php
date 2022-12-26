<?php

/* * ********************************************************************
 * HetznerVps product developed. (2016-12-15)
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

namespace ModulesGarden\Servers\HetznerVps\App\Service;

use Illuminate\Database\Capsule\Manager as DB;

/**
 * Description of Utility
 *
 * @author Pawel Kopec <pawelk@modulesgarden.com>
 * @version 1.0.0
 */
class Utility
{

    public static function unitFormat(&$value, $inUnit, $outUnit)
    {
        if (!in_array($inUnit, ['bytes', 'mb', 'gb']))
        {
            $debug = debug_backtrace();
            throw new \Exception(sprintf("Unit value ('%s') is invalid. File: %s:%s", $inUnit, $debug[0]['file'],$debug[0]['line'] ));
        }

        if (!in_array($outUnit, ['mb', 'gb', 'bytes']))
        {
            $debug = debug_backtrace();
            throw new \Exception(sprintf("Unit value ('%s') is invalid. File: %s:%s", $outUnit, $debug[0]['file'],$debug[0]['line']));
        }

        if ($value == 0)
        {
            return;
        }
        if (empty($value) || !is_numeric($value))
        {
            $debug = debug_backtrace();
            throw new \Exception(sprintf("Unit value ('%s') is invalid. File: %s:%s", $value, $debug[0]['file'],$debug[0]['line']));
        }

        if ($inUnit == 'mb' && $outUnit == 'gb' && $value < 1024)
        {
            $debug = debug_backtrace();
            throw new \Exception(sprintf("Unit value %sMB is smaller than 1 GB. File: %s:%s", $value, $debug[0]['file'],$debug[0]['line']));
        }

        if ($inUnit == $outUnit)
        {
            return;
        }
        else
        {
            if ($inUnit == 'bytes' && $outUnit == 'mb')
            {
                $value /= pow(1024, 2);
            }
            else
            {
                if ($inUnit == 'bytes' && $outUnit == 'gb')
                {
                    $value /= pow(1024, 3);
                }
                else
                {
                    if ($inUnit == 'mb' && $outUnit == 'gb')
                    {
                        $value = ceil($value / 1024);
                    }
                    else
                    {
                        if ($inUnit == 'gb' && $outUnit == 'mb')
                        {
                            $value *= 1024;
                        }
                        else
                        {
                            if ($inUnit == 'gb' && $outUnit == 'bytes')
                            {
                                $value *= pow(1024, 3);
                            }
                            else
                            {
                                if ($inUnit == 'mb' && $outUnit == 'bytes')
                                {
                                    $value *= pow(1024, 2);
                                }
                            }
                        }
                    }
                }
            }
        }
    }

    static function timeStamp($strTime = 'now')
    {
        return date('Y-m-d H:i:s', strtotime($strTime));
    }

    static function obClean()
    {
        $outputBuffering = ob_get_contents();
        if ($outputBuffering !== false)
        {
            if (!empty($outputBuffering))
            {
                ob_clean();
            }
            else
            {
                ob_start();
            }
        }
        http_response_code(200);
    }

    /**
     * FUNCTION MG_uptime
     * Calculate uptime
     * @param int $uptime
     * @return boolean
     */
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


    public static function isAddon($name)
    {
        if (DB::table('tbladdonmodules')->where("module", $name)->count())
        {
            $file = ROOTDIR . DIRECTORY_SEPARATOR . 'modules' . DIRECTORY_SEPARATOR . 'addons' . DIRECTORY_SEPARATOR . $name . DIRECTORY_SEPARATOR . $name . '.php';
            return file_exists($file);
        }
        return false;
    }

    static function isIpManagerHetznerVpsIntegration()
    {
        if (!self::isAddon('ipmanager2'))
        {
            return false;
        }
        return DB::table('ip_manager_modules')->where("modulename", "HetznerVpsIntegration")->where("enabled", "1")->count();
    }

    static function isIpManagerHetznerCloudIntegration()
    {
        if (!self::isAddon('ipmanager2'))
        {
            return false;
        }
        return DB::table('ip_manager_modules')->where("modulename", "HetznerCloudIntegration")->where("enabled", "1")->count();
    }

    static function replaceSpecialChars($string)
    {
        $replace = [
            '&lt;'   => '', '&gt;' => '', '&#039;' => '', '&amp;' => '',
            '&quot;' => '', 'À' => 'A', 'Á' => 'A', 'Â' => 'A', 'Ã' => 'A', 'Ä' => 'Ae',
            '&Auml;' => 'A', 'Å' => 'A', 'Ā' => 'A', 'Ą' => 'A', 'Ă' => 'A', 'Æ' => 'Ae',
            'Ç'      => 'C', 'Ć' => 'C', 'Č' => 'C', 'Ĉ' => 'C', 'Ċ' => 'C', 'Ď' => 'D', 'Đ' => 'D',
            'Ð'      => 'D', 'È' => 'E', 'É' => 'E', 'Ê' => 'E', 'Ë' => 'E', 'Ē' => 'E',
            'Ę'      => 'E', 'Ě' => 'E', 'Ĕ' => 'E', 'Ė' => 'E', 'Ĝ' => 'G', 'Ğ' => 'G',
            'Ġ'      => 'G', 'Ģ' => 'G', 'Ĥ' => 'H', 'Ħ' => 'H', 'Ì' => 'I', 'Í' => 'I',
            'Î'      => 'I', 'Ï' => 'I', 'Ī' => 'I', 'Ĩ' => 'I', 'Ĭ' => 'I', 'Į' => 'I',
            'İ'      => 'I', 'Ĳ' => 'IJ', 'Ĵ' => 'J', 'Ķ' => 'K', 'Ł' => 'K', 'Ľ' => 'K',
            'Ĺ'      => 'K', 'Ļ' => 'K', 'Ŀ' => 'K', 'Ñ' => 'N', 'Ń' => 'N', 'Ň' => 'N',
            'Ņ'      => 'N', 'Ŋ' => 'N', 'Ò' => 'O', 'Ó' => 'O', 'Ô' => 'O', 'Õ' => 'O',
            'Ö'      => 'Oe', '&Ouml;' => 'Oe', 'Ø' => 'O', 'Ō' => 'O', 'Ő' => 'O', 'Ŏ' => 'O',
            'Œ'      => 'OE', 'Ŕ' => 'R', 'Ř' => 'R', 'Ŗ' => 'R', 'Ś' => 'S', 'Š' => 'S',
            'Ş'      => 'S', 'Ŝ' => 'S', 'Ș' => 'S', 'Ť' => 'T', 'Ţ' => 'T', 'Ŧ' => 'T',
            'Ț'      => 'T', 'Ù' => 'U', 'Ú' => 'U', 'Û' => 'U', 'Ü' => 'Ue', 'Ū' => 'U',
            '&Uuml;' => 'Ue', 'Ů' => 'U', 'Ű' => 'U', 'Ŭ' => 'U', 'Ũ' => 'U', 'Ų' => 'U',
            'Ŵ'      => 'W', 'Ý' => 'Y', 'Ŷ' => 'Y', 'Ÿ' => 'Y', 'Ź' => 'Z', 'Ž' => 'Z',
            'Ż'      => 'Z', 'Þ' => 'T', 'à' => 'a', 'á' => 'a', 'â' => 'a', 'ã' => 'a',
            'ä'      => 'ae', '&auml;' => 'ae', 'å' => 'a', 'ā' => 'a', 'ą' => 'a', 'ă' => 'a',
            'æ'      => 'ae', 'ç' => 'c', 'ć' => 'c', 'č' => 'c', 'ĉ' => 'c', 'ċ' => 'c',
            'ď'      => 'd', 'đ' => 'd', 'ð' => 'd', 'è' => 'e', 'é' => 'e', 'ê' => 'e',
            'ë'      => 'e', 'ē' => 'e', 'ę' => 'e', 'ě' => 'e', 'ĕ' => 'e', 'ė' => 'e',
            'ƒ'      => 'f', 'ĝ' => 'g', 'ğ' => 'g', 'ġ' => 'g', 'ģ' => 'g', 'ĥ' => 'h',
            'ħ'      => 'h', 'ì' => 'i', 'í' => 'i', 'î' => 'i', 'ï' => 'i', 'ī' => 'i',
            'ĩ'      => 'i', 'ĭ' => 'i', 'į' => 'i', 'ı' => 'i', 'ĳ' => 'ij', 'ĵ' => 'j',
            'ķ'      => 'k', 'ĸ' => 'k', 'ł' => 'l', 'ľ' => 'l', 'ĺ' => 'l', 'ļ' => 'l',
            'ŀ'      => 'l', 'ñ' => 'n', 'ń' => 'n', 'ň' => 'n', 'ņ' => 'n', 'ŉ' => 'n',
            'ŋ'      => 'n', 'ò' => 'o', 'ó' => 'o', 'ô' => 'o', 'õ' => 'o', 'ö' => 'oe',
            '&ouml;' => 'oe', 'ø' => 'o', 'ō' => 'o', 'ő' => 'o', 'ŏ' => 'o', 'œ' => 'oe',
            'ŕ'      => 'r', 'ř' => 'r', 'ŗ' => 'r', 'š' => 's', 'ù' => 'u', 'ú' => 'u',
            'û'      => 'u', 'ü' => 'ue', 'ū' => 'u', '&uuml;' => 'ue', 'ů' => 'u', 'ű' => 'u',
            'ŭ'      => 'u', 'ũ' => 'u', 'ų' => 'u', 'ŵ' => 'w', 'ý' => 'y', 'ÿ' => 'y',
            'ŷ'      => 'y', 'ž' => 'z', 'ż' => 'z', 'ź' => 'z', 'þ' => 't', 'ß' => 'ss',
            'ſ'      => 'ss', 'ый' => 'iy', 'А' => 'A', 'Б' => 'B', 'В' => 'V', 'Г' => 'G',
            'Д'      => 'D', 'Е' => 'E', 'Ё' => 'YO', 'Ж' => 'ZH', 'З' => 'Z', 'И' => 'I',
            'Й'      => 'Y', 'К' => 'K', 'Л' => 'L', 'М' => 'M', 'Н' => 'N', 'О' => 'O',
            'П'      => 'P', 'Р' => 'R', 'С' => 'S', 'Т' => 'T', 'У' => 'U', 'Ф' => 'F',
            'Х'      => 'H', 'Ц' => 'C', 'Ч' => 'CH', 'Ш' => 'SH', 'Щ' => 'SCH', 'Ъ' => '',
            'Ы'      => 'Y', 'Ь' => '', 'Э' => 'E', 'Ю' => 'YU', 'Я' => 'YA', 'а' => 'a',
            'б'      => 'b', 'в' => 'v', 'г' => 'g', 'д' => 'd', 'е' => 'e', 'ё' => 'yo',
            'ж'      => 'zh', 'з' => 'z', 'и' => 'i', 'й' => 'y', 'к' => 'k', 'л' => 'l',
            'м'      => 'm', 'н' => 'n', 'о' => 'o', 'п' => 'p', 'р' => 'r', 'с' => 's',
            'т'      => 't', 'у' => 'u', 'ф' => 'f', 'х' => 'h', 'ц' => 'c', 'ч' => 'ch',
            'ш'      => 'sh', 'щ' => 'sch', 'ъ' => '', 'ы' => 'y', 'ь' => '', 'э' => 'e',
            'ю'      => 'yu', 'я' => 'ya'
        ];
        return str_replace(array_keys($replace), $replace, $string);
    }

    public static  function cpuUsage($usege){
        $usege *= 100;
        return number_format($usege, 2, '.', '');
    }
}
