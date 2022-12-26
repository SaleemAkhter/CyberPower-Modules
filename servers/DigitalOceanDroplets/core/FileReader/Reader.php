<?php

namespace ModulesGarden\Servers\DigitalOceanDroplets\Core\FileReader;

use ModulesGarden\Servers\DigitalOceanDroplets\Core\FileReader\Reader\Ini;
use ModulesGarden\Servers\DigitalOceanDroplets\Core\FileReader\Reader\Json;
use ModulesGarden\Servers\DigitalOceanDroplets\Core\FileReader\Reader\Xml;
use ModulesGarden\Servers\DigitalOceanDroplets\Core\FileReader\Reader\Yml;
use ModulesGarden\Servers\DigitalOceanDroplets\Core\FileReader\Reader\Php;
use ModulesGarden\Servers\DigitalOceanDroplets\Core\FileReader\Reader\Sql;
use ModulesGarden\Servers\DigitalOceanDroplets\Core\FileReader\Reader\AbstractType;
use ModulesGarden\Servers\DigitalOceanDroplets\Core\ServiceLocator;

/**
 * Description of Reader
 *
 * @author Rafał Ossowski <rafal.os@modulesgarden.com>
 */
class Reader
{

    /**
     * @param string $file
     * @return AbstractType
     */
    public static function read($file)
    {
        $path = explode(DIRECTORY_SEPARATOR, $file);
        $file = end($path);
        array_pop($path);
        $path = implode(DIRECTORY_SEPARATOR, $path);

        $instance = null;

        if (self::isXML($file))
        {
            $instance = new Xml($file, $path);
        }
        elseif (self::isINI($file))
        {
            $instance = new Ini($file, $path);
        }
        elseif (self::isPHP($file))
        {
            $instance = new Php($file, $path);
        }
        elseif (self::isSQL($file))
        {
            $instance = new Sql($file, $path);
        }
        elseif (self::isYML($file))
        {
            $instance = new Yml($file, $path);
        }
        elseif (self::isJSON($file))
        {
            $instance = new Json($file, $path);
        }

        if ($instance == null)
        {
            ServiceLocator::call('errorManager')->addError(self::class, 'Can\'t read file: ' . $file, ['file' => $file, 'path' => $path]);
        }

        return $instance;
    }

    private static function getType($file)
    {
        $type  = null;
        $array = explode('.', $file);
        if (is_array($array))
        {
            $type = end($array);
        }

        return strtolower($type);
    }

    private static function isXML($file)
    {
        $type = self::getType($file);

        if ($type != null && $type == 'xml')
        {
            return true;
        }
        return false;
    }

    private static function isINI($file)
    {
        $type = self::getType($file);

        if ($type != null && $type == 'ini')
        {
            return true;
        }
        return false;
    }

    private static function isYML($file)
    {
        $type = self::getType($file);

        if ($type != null && $type == 'yml')
        {
            return true;
        }
        return false;
    }

    private static function isJSON($file)
    {
        $type = self::getType($file);

        if ($type != null && $type == 'json')
        {
            return true;
        }
        return false;
    }
    
    private static function isPHP($file)
    {
        $type = self::getType($file);

        if ($type != null && $type == 'php')
        {
            return true;
        }
        return false;
    }
    
    private static function isSQL($file)
    {
        $type = self::getType($file);

        if ($type != null && $type == 'sql')
        {
            return true;
        }
        return false;
    }
}
