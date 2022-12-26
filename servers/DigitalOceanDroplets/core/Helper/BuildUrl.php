<?php

namespace ModulesGarden\Servers\DigitalOceanDroplets\Core\Helper;

use ModulesGarden\Servers\DigitalOceanDroplets\Core\Http\AbstractController;
use ModulesGarden\Servers\DigitalOceanDroplets\Core\ServiceLocator;
use ModulesGarden\Servers\DigitalOceanDroplets\Core\Configuration\Addon;

/**
 * Description of BuildUrl
 *
 * @author Rafał Ossowski <rafal.os@modulesgarden.com>
 */
class BuildUrl
{

    public static function getUrl($controller = null, $action = null, array $params = [], $isFullUrl = true)
    {
        if (AbstractController::isAdmin())
        {
            $url = 'addonmodules.php?module=' . AbstractController::getModule();
        }
        else
        {
            $url = 'clientarea.php?m=' . AbstractController::getModule();
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

    public static function getAssetsURL()
    {

        $addon    = ServiceLocator::call(Addon::class);
        $name     = $addon->getConfig('systemName');
        $template = $addon->getConfig('template', 'default');

        if (AbstractController::isAdmin())
        {
            return '../modules/servers/' . $name . '/templates/admin/assets';
        }
        else
        {
            return 'modules/servers/' . $name . '/templates/client/' . $template . '/assets';
        }
    }

    private static function baseUrl()
    {
        $protocol = 'https';
        if (!isset($_SERVER['HTTPS']) || $_SERVER['HTTPS'] != 'on')
        {
            $protocol = 'http';
        }
        $host   = $_SERVER['HTTP_HOST'];
        $surfix = $_SERVER['PHP_SELF'];
        $surfix = explode('/', $surfix);
        array_pop($surfix);
        $surfix = implode('/', $surfix);
        return "{$protocol}://{$host}{$surfix}/";
    }
}
