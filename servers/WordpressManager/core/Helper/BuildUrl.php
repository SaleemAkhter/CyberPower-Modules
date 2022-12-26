<?php

namespace ModulesGarden\WordpressManager\Core\Helper;

use \ModulesGarden\WordpressManager\Core\ServiceLocator;
use \ModulesGarden\WordpressManager\Core\App\Controllers\Instances\Addon\Config;
use function \ModulesGarden\WordpressManager\Core\Helper\isAdmin;
use function \ModulesGarden\WordpressManager\Core\Helper\getModuleName;

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
    public static function getAssetsURL()
    {
        $addon    = ServiceLocator::call(Config::class);
        $name     = $addon->getConfigValue('systemName');
        $template = $addon->getConfigValue('template', 'default');

        if (isAdmin())
        {
            return '../modules/' . self::getType() . '/' . $name . '/templates/admin/assets';
        }

        return 'modules/' . self::getType() . '/' . $name . '/templates/client/' . $template . '/assets';
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
        $protocol = 'https';
        if (!isset($_SERVER['HTTPS']) || $_SERVER['HTTPS'] != 'on')
        {
            $protocol = 'http';
        }
        $host   = $_SERVER['MB_HTTP_HOST'] ?: $_SERVER['HTTP_HOST'];
        $surfix = $_SERVER['PHP_SELF'];
        $surfix = explode('/', $surfix);
        array_pop($surfix);
        $surfix = implode('/', $surfix);
        return "{$protocol}://{$host}{$surfix}/";
    }
}
