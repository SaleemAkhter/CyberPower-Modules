<?php

namespace ModulesGarden\WordpressManager\Core;

class ModuleConstants
{
    protected static $mgDevConfig       = null;
    protected static $mgHelperLangs     = null;
    protected static $mgCoreConfig      = null;
    protected static $mgModuleRootDir   = null;
    protected static $mgTemplateDir     = null;
    protected static $mgIsPhp7          = false;
    protected static $mgModuleNamespace = "ModulesGarden\WordpressManager";
    protected static $prefixDataBase    = "";

    public static function initialize()
    {
        self::$mgModuleRootDir = dirname(__DIR__);
        self::$mgDevConfig     = self::getFullPath("app", "Config");
        self::$mgHelperLangs   = self::getFullPath("langs");
        self::$mgCoreConfig    = self::getFullPath("core", "Config");
        self::$mgTemplateDir   = self::getFullPath("templates");
        self::$mgIsPhp7        = self::checkIfPhp7();
        self::$prefixDataBase  = self::loadDataBasePrefix();
    }

    public static function loadDataBasePrefix()
    {
        $namespaceParts = explode("\\", self::$mgModuleNamespace);

        return end($namespaceParts);
    }

    public static function checkIfPhp7()
    {
        return (version_compare(PHP_VERSION, '7.0.0') >= 0);
    }

    public static function getDevConfigDir()
    {
        return self::$mgDevConfig;
    }

    public static function getCoreConfigDir()
    {
        return self::$mgCoreConfig;
    }

    public static function getLangsDir()
    {
        return self::$mgHelperLangs;
    }

    public static function getModuleRootDir()
    {
        return self::$mgModuleRootDir;
    }

    public static function getFullPath()
    {
        $fullPath = self::getModuleRootDir();
        foreach (func_get_args() as $dir)
        {
            $fullPath .= (DS . $dir);
        }

        return $fullPath;
    }
    
    public static function getFullNamespace()
    {
        $fullNamespace = self::getRootNamespace();
        foreach (func_get_args() as $dir)
        {
            $fullNamespace .= ("\\" . $dir);
        }

        return $fullNamespace;
    }
    
    public static function getFullPathWhmcs()
    {
        $fullPath = ROOTDIR;
        foreach (func_get_args() as $dir)
        {
            $fullPath .= (DS . $dir);
        }

        return $fullPath;
    }
    
    public static function requireFile($file, $ones = true)
    {
        if ($ones)
        {
            require_once $file;
        }
        else
        {
            require $file;
        }
    }

    public static function getTemplateDir()
    {
        return self::$mgTemplateDir;
    }

    public static function isPhp7orHigher()
    {
        return self::$mgIsPhp7;
    }

    public static function getPrefixDataBase()
    {
        return self::$prefixDataBase . "_";
    }

    public static function getRootNamespace()
    {
        return self::$mgModuleNamespace;
    }
}
