<?php

namespace ModulesGarden\Servers\HetznerVps\Core\Helper;

use \ModulesGarden\Servers\HetznerVps\Core\ServiceLocator;
use \ModulesGarden\Servers\HetznerVps\Core\App\Controllers\Instances\Addon\Config;

/**
 * Description of BuildUrl
 *
 * @author Rafał Ossowski <rafal.os@modulesgarden.com>
 */
class BuildUrl
{
    public static function getUrl($controller = null, $action = null, array $params = [], $isFullUrl = true)
    {
        if (isAdmin())
        {
            $url = 'addonmodules.php?module=' . getModuleName();
        }
        else
        {
            $url = 'index.php?m=' . getModuleName();
        }

        if ($controller)
        {
            $url .= '&mg-page=' . $controller;
            if ($action)
            {
                $url .= '&mg-action=' . $action;
            }

            if ($params)
            {
                $url .= '&' . http_build_query($params);
            }
        }

        if ($isFullUrl)
        {
            $baseUrl = self::baseUrl();
            $url     = $baseUrl . $url;
        }

        return $url;
    }
    
    public static function getBaseUrl()
    {
        return self::baseUrl();
    }

    public static function getNewUrl($protocol = 'http', $host = 'modulesgarden.com', $params = [])
    {
        $url = "{$protocol}://{$host}";
        if ($params)
        {
            $url .= '?' . http_build_query($params);
        }
        return $url;
    }

    public static function getAppAssetsURL()
    {
        return self::getAssetsURL(true);
    }

    public static function getAssetsURL($isApp = false)
    {
        $addon    = ServiceLocator::call(Config::class);
        $name     = $addon->getConfigValue('systemName');
        $template = $addon->getConfigValue('template', 'default');

        if (isAdmin())
        {
            return $isApp ? '../modules/' . self::getType() . '/' . $name . '/app/UI/Admin/Templates/assets'
                    : '../modules/' . self::getType() . '/' . $name . '/templates/admin/assets';
        }

        return $isApp ? 'modules/' . self::getType() . '/' . $name . '/app/UI/Client/Templates/assets'
                : 'modules/' . self::getType() . '/' . $name . '/templates/client/' . $template . '/assets';
    }

    public static function isCustomIntegrationCss()
    {
        $modulePath = \ModulesGarden\Servers\HetznerVps\Core\ModuleConstants::getFullPath();
        $integrationPath = $modulePath . DIRECTORY_SEPARATOR . 'app' . DIRECTORY_SEPARATOR . 'UI' . DIRECTORY_SEPARATOR
                . (isAdmin() ? 'Admin' : 'Client') . DIRECTORY_SEPARATOR . 'Templates' . DIRECTORY_SEPARATOR . 'assets'
                . DIRECTORY_SEPARATOR . 'css' . DIRECTORY_SEPARATOR . 'integration.css';

        return file_exists($integrationPath);
    }

    public static function isCustomModuleCss()
    {
        $modulePath = \ModulesGarden\Servers\HetznerVps\Core\ModuleConstants::getFullPath();
        $integrationPath = $modulePath . DIRECTORY_SEPARATOR . 'app' . DIRECTORY_SEPARATOR . 'UI' . DIRECTORY_SEPARATOR
                . (isAdmin() ? 'Admin' : 'Client') . DIRECTORY_SEPARATOR . 'Templates' . DIRECTORY_SEPARATOR . 'assets'
                . DIRECTORY_SEPARATOR . 'css' . DIRECTORY_SEPARATOR . 'module_styles.css';

        return file_exists($integrationPath);
    }

    public static function getType()
    {
        if (strpos(trim(self::class, '\\'), 'ModulesGarden\Servers') === 0)
        {
            return 'servers';
        }

        return 'addons';
    }

    private static function baseUrl()
    {
        $host   = $GLOBALS['CONFIG']['SystemURL'] ? $GLOBALS['CONFIG']['SystemURL'] : $_SERVER['HTTP_HOST'];
        $url = \parse_url( $host );
        $surfix = $_SERVER['PHP_SELF'];
        $surfix = explode('/', $surfix);
        array_pop($surfix);
        $surfix = implode('/', $surfix);
        $scheme = $_SERVER['REQUEST_SCHEME'] ? :$url['scheme'];
        return "{$scheme}://{$url['host']}{$surfix}/";
    }
}



