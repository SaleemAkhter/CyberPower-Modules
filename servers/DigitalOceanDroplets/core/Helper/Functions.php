<?php

namespace ModulesGarden\Servers\DigitalOceanDroplets\Core\Helper;

use ModulesGarden\Servers\DigitalOceanDroplets\Core\Http\JsonResponse;
use ModulesGarden\Servers\DigitalOceanDroplets\Core\Http\RedirectResponse;
use ModulesGarden\Servers\DigitalOceanDroplets\Core\Http\Response;
use ModulesGarden\Servers\DigitalOceanDroplets\Core\Helper\Lang;
use ModulesGarden\Servers\DigitalOceanDroplets\Core\ModuleNamespace;
use ModulesGarden\Servers\DigitalOceanDroplets\Core\ServiceLocator;
use ModulesGarden\Servers\DigitalOceanDroplets\Core\DependencyInjection;
use ModulesGarden\Servers\DigitalOceanDroplets\Core\ModuleConstants;
use ModulesGarden\Servers\DigitalOceanDroplets\Core\Cache\CacheManager;
use ModulesGarden\Servers\DigitalOceanDroplets\Core\Logger\Entity;

if (!function_exists('json'))
{

    /**
     * @param array $data
     * @return JsonResponse
     */
    function json(array $data = [])
    {
        $data = ['data' => $data];
        return JsonResponse::create($data);
    }
}

if (!function_exists('response'))
{

    /**
     * @param array $data
     * @return \ModulesGarden\Servers\DigitalOceanDroplets\Core\Http\Response
     */
    function response(array $data = [])
    {
        return Response::create()->setData($data);
    }
}

if (!function_exists('cache'))
{

    /**
     * @param array $data
     * @return \ModulesGarden\Servers\DigitalOceanDroplets\Core\Interfaces\CacheManagerInterface
     */
    function cache($key = null)
    {
        return ($key === null) ? CacheManager::cache() : CacheManager::cache($key);
    }
}

if (!function_exists('redirect'))
{

    /**
     * @param array $data
     * @return JsonResponse
     */
    function redirect($controller, $action, $params)
    {
        return RedirectResponse::createMG($controller, $action, $params);
    }
}

if (!function_exists('redirectByUrl'))
{

    /**
     * @param array $data
     * @return JsonResponse
     */
    function redirectByUrl($url, $params)
    {
        return RedirectResponse::createByUrl($url, $params);
    }
}

if (!function_exists('t'))
{

    /**
     * @param array $data
     * @return JsonResponse
     */
    function t(array $data = [])
    {
        return Lang::getInstance(ModuleConstants::getLangsDir());
    }
}

if (!function_exists('errorLog'))
{

    function errorLog($message = "", $request = [], $response = [], $vars = [], $beforeVars = [], $level = Entity::LEVEL_HIGHT, $reference = [])
    {
        $entitylogger = sl('entityLogger');
        $entitylogger
                ->setMessage($message)
                ->setLevel($level)
                ->setType(Entity::TYPE_ERROR)
                ->setReference(Entity::convertToId($reference), Entity::convertToClassName($reference))
                ->setRequest($request)
                ->setResponse($response)
                ->setBeforeVars($beforeVars)
                ->setVars($vars);
        return $entitylogger->save();
    }
}

if (!function_exists('successLog'))
{

    function successLog($message = "", $request = [], $response = [], $vars = [], $beforeVars = [], $level = Entity::LEVEL_LOW, $reference = [])
    {
        $entitylogger = sl('entityLogger');
        $entitylogger
                ->setMessage($message)
                ->setLevel($level)
                ->setType(Entity::TYPE_SUCCESS)
                ->setReference(Entity::convertToId($reference), Entity::convertToClassName($reference))
                ->setRequest($request)
                ->setResponse($response)
                ->setBeforeVars($beforeVars)
                ->setVars($vars);
        return $entitylogger->save();
    }
}

if (!function_exists('infoLog'))
{

    function infoLog($message = "", $request = [], $response = [], $vars = [], $beforeVars = [], $level = Entity::LEVEL_LOW, $reference = [])
    {
        $entitylogger = sl('entityLogger');
        $entitylogger
                ->setMessage($message)
                ->setLevel($level)
                ->setType(Entity::TYPE_INFO)
                ->setReference(Entity::convertToId($reference), Entity::convertToClassName($reference))
                ->setRequest($request)
                ->setResponse($response)
                ->setBeforeVars($beforeVars)
                ->setVars($vars);
        return $entitylogger->save();
    }
}

if (!function_exists('criticalLog'))
{

    function criticalLog($message = "", $request = [], $response = [], $vars = [], $beforeVars = [], $level = Entity::LEVEL_LOW, $reference = [])
    {
        $entitylogger = sl('entityLogger');
        $entitylogger
                ->setMessage($message)
                ->setLevel($level)
                ->setType(Entity::TYPE_CRITICAL)
                ->setReference(Entity::convertToId($reference), Entity::convertToClassName($reference))
                ->setRequest($request)
                ->setResponse($response)
                ->setBeforeVars($beforeVars)
                ->setVars($vars);
        return $entitylogger->save();
    }
}

if (!function_exists('debugLog'))
{

    function debugLog($message = "", $request = [], $response = [], $vars = [], $beforeVars = [], $level = Entity::LEVEL_LOW, $reference = [])
    {
        $entitylogger = sl('entityLogger');
        $entitylogger
                ->setMessage($message)
                ->setLevel($level)
                ->setType(Entity::TYPE_DEBUG)
                ->setReference(Entity::convertToId($reference), Entity::convertToClassName($reference))
                ->setRequest($request)
                ->setResponse($response)
                ->setBeforeVars($beforeVars)
                ->setVars($vars);
        return $entitylogger->save();
    }
}

if (!function_exists('sl'))
{

    /**
     * @param string $class
     * @param string|null $method
     * @return object
     */
    function sl($class, $method = null)
    {

        $return = null;

        if ($class != null && $method == null)
        {
            $return = ServiceLocator::call($class);
        }
        elseif ($class != null && $method != null)
        {
            $return = ServiceLocator::call($class, $method);
        }
        return $return;
    }
}

if (!function_exists('di'))
{

    /**
     * @param string|null $class
     * @param string|null $method
     * @param bool $isCreate
     * @return type
     */
    function di($class, $method = null, $isCreate = true)
    {
        $return     = null;
        $methodName = ($isCreate) ? 'create' : 'get';

        if ($class != null && $method == null)
        {
            $return = DependencyInjection::{$methodName}($class);
        }
        elseif ($class != null && $method != null)
        {
            $return = DependencyInjection::{$methodName}($class, $method);
        }

        return $return;
    }
}

if (!function_exists('isAdmin'))
{

    /**
     * @return bool
     */
    function isAdmin()
    {
        $request = sl('request');
        $server  = $request->server->all();
        $session = $request->getSession();
        if (isset($session['adminid']) && isset($server['REQUEST_URI']) && strpos($server['REQUEST_URI'], '/' . getAdminDirName() . '/') !== false
        )
        {
            return true;
        }

        return false;
    }
}

if (!function_exists('getAdminDirName'))
{

    /**
     * @return string
     */
    function getAdminDirName()
    {
        $fileName = 'configuration.php';
        $filePath = ModuleConstants::getFullPathWhmcs();

        if (file_exists($filePath . DIRECTORY_SEPARATOR . $fileName))
        {
            include $filePath . DIRECTORY_SEPARATOR . $fileName;
        }

        if ($customadminpath && is_string($customadminpath))
        {
            return $customadminpath;
        }

        return 'admin';
    }
}

if (!function_exists('getModuleName'))
{

    /**
     * @return string
     */
    function getModuleName()
    {
        $request = sl('request');

        if (isAdmin())
        {
            $data = $request->get('module', $request->query->get('module', 'DigitalOceanDroplets'));
            return isset($data) ? $data : '';
        }

        $data = $request->get('m', $request->query->get('m', 'DigitalOceanDroplets'));
        return isset($data) ? $data : '';
    }
}

if (!function_exists('\ModulesGarden\Servers\DigitalOceanDroplets\Core\Helper\view'))
{

    /**
     * main View Controler
     * 
     * @return \ModulesGarden\Servers\DigitalOceanDroplets\Core\UI\View
     */
    function view($template = null)
    {
        $request = sl('request');

        if ($request->get('ajax') && ModuleNamespace::validate($request->get('namespace')))
        {
            $view      = sl('viewAjax');
            $namespace = $request->get('namespace');
            $view->initAjaxElementContext($namespace);

            return $view;
        }

        $view = sl('view');

        if ($template)
        {
            $view->setTemplate($template);
        }

        return $view;
    }
}

if (!function_exists('convertStringToNamespace'))
{

    function convertStringToNamespace($string)
    {
        return str_replace("_", "\\", $string);
    }
}
