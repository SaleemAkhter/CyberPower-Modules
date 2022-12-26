<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\Helpers;

class StringFormat
{
    
    protected static $sign;

    public static function getWithUnderscore($string)
    {
        self::$sign = '_';
        
        return self::get($string);
    }
    
    public static function getWithHyphen($string)
    {
        self::$sign = '-';
        
        return self::get($string);
    }
    
    public static function withoutSign($string)
    {
        self::$sign = '';
        
        return self::get($string);
    }

    public static function get($string)
    {
        if (is_string($string))
        {
            $pieces = preg_split('/(?=[A-Z])/', trim($string));
            foreach ($pieces as $k => &$piece)
            {
                if(!$piece)
                {
                    unset($pieces[$k]);
                    continue;
                }
                if (ctype_upper($piece[0]))
                {
                    $piece = strtolower($piece);
                }
            }

            return (string) implode(self::$sign, $pieces);
        }

        return $string;
    }
}
