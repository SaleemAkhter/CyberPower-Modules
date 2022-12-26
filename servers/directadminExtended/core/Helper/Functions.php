<?php

namespace ModulesGarden\Servers\DirectAdminExtended\Core\Helper;

use ModulesGarden\Servers\DirectAdminExtended\Core\Events\Dispatcher;
use ModulesGarden\Servers\DirectAdminExtended\Core\Http\JsonResponse;
use ModulesGarden\Servers\DirectAdminExtended\Core\Http\RedirectResponse;
use ModulesGarden\Servers\DirectAdminExtended\Core\Http\Response;
use ModulesGarden\Servers\DirectAdminExtended\Core\ServiceLocator;
use ModulesGarden\Servers\DirectAdminExtended\Core\DependencyInjection;
use ModulesGarden\Servers\DirectAdminExtended\Core\ModuleConstants;
use ModulesGarden\Servers\DirectAdminExtended\Core\Cache\CacheManager;
use ModulesGarden\Servers\DirectAdminExtended\Core\Logger\Entity;

if (!function_exists('\ModulesGarden\Servers\DirectAdminExtended\Core\Helper\json'))
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

if (!function_exists('\ModulesGarden\Servers\DirectAdminExtended\Core\Helper\response'))
{
    /**
    /**
     * @param array $data
     * @return \ModulesGarden\Servers\DirectAdminExtended\Core\Http\Response
     */
    function response(array $data = [])
    {
        return Response::create()->setData($data);
    }
}

if (!function_exists('\ModulesGarden\Servers\DirectAdminExtended\Core\Helper\cache'))
{
    /**
     * @param array $data
     * @return \ModulesGarden\Servers\DirectAdminExtended\Core\Interfaces\CacheManagerInterface
     */
    function cache($key = null)
    {
        return ($key === null) ? CacheManager::cache() : CacheManager::cache($key);
    }
}

if (!function_exists('\ModulesGarden\Servers\DirectAdminExtended\Core\Helper\redirect'))
{
    /**
     * @param array $data
     * @return JsonResponse
     */
    function redirect($controller = null, $action = null, array $params = [])
    {
        return RedirectResponse::createMG($controller, $action, $params);
    }
}

if (!function_exists('\ModulesGarden\Servers\DirectAdminExtended\Core\Helper\redirectByUrl'))
{
    /**
     * @param array $data
     * @return JsonResponse
     */
    function redirectByUrl($url, array $params = [])
    {
        return RedirectResponse::createByUrl($url, $params);
    }
}

if (!function_exists('\ModulesGarden\Servers\DirectAdminExtended\Core\Helper\t'))
{
    /**
     * @param array $data
     * @return JsonResponse
     */
    function t(array $data = [])
    {
        return ServiceLocator::call('lang');
    }
}

if (!function_exists('\ModulesGarden\Servers\DirectAdminExtended\Core\Helper\errorLog'))
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

if (!function_exists('\ModulesGarden\Servers\DirectAdminExtended\Core\Helper\successLog'))
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

if (!function_exists('\ModulesGarden\Servers\DirectAdminExtended\Core\Helper\infoLog'))
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

if (!function_exists('\ModulesGarden\Servers\DirectAdminExtended\Core\Helper\criticalLog'))
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

if (!function_exists('\ModulesGarden\Servers\DirectAdminExtended\Core\Helper\debugLog'))
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

if (!function_exists('\ModulesGarden\Servers\DirectAdminExtended\Core\Helper\sl'))
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

if (!function_exists('\ModulesGarden\Servers\DirectAdminExtended\Core\Helper\di'))
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

if (!function_exists('\ModulesGarden\Servers\DirectAdminExtended\Core\Helper\isAdmin'))
{

    /**
     * @return bool
     */
    function isAdmin()
    {
        $request = sl('request');
        $server  = $request->server->all();
        $session = $request->getSession();

        if (isset($session['adminid']) && isset($server['REQUEST_URI']) && strpos($server['REQUEST_URI'], '/' . getAdminDirName() . '/') !== false)
        {
            return true;
        }

        return false;
    }
}

if (!function_exists('\ModulesGarden\Servers\DirectAdminExtended\Core\Helper\getAdminDirName'))
{

    /**
     * @return string
     */
    function getAdminDirName()
    {
        $fileName = 'configuration.php';
        $filePath = ModuleConstants::getFullPathWhmcs();

        global $customadminpath;
        if (!$customadminpath && file_exists($filePath . DIRECTORY_SEPARATOR . $fileName))
        {
            include_once $filePath . DIRECTORY_SEPARATOR . $fileName;
        }

        if ($customadminpath && is_string($customadminpath))
        {
            return $customadminpath;
        }

        return 'admin';
    }
}

if (!function_exists('\ModulesGarden\Servers\DirectAdminExtended\Core\Helper\getModuleName'))
{

    /**
     * @return string
     */
    function getModuleName()
    {
        $request = sl('request');

        if (isAdmin())
        {
            $data = $request->get('module', $request->query->get('module', 'DirectAdminExtended'));
            return isset($data) ? $data : '';
        }

        $data = $request->get('m', $request->query->get('m', 'DirectAdminExtended'));
        return isset($data) ? $data : '';
    }
}

if (!function_exists('\ModulesGarden\Servers\DirectAdminExtended\Core\Helper\view'))
{

    /**
     * main View Controler
     * 
     * @return \ModulesGarden\Servers\DirectAdminExtended\Core\UI\View
     */
    function view($template = null)
    {
        $request = sl('request');

        if ($request->get('ajax') && $request->get('namespace') != null && $request->get('namespace') != '' && $request->get('namespace') != 'undefined')
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

if (!function_exists('\ModulesGarden\Servers\DirectAdminExtended\Core\Helper\viewIntegrationAddon'))
{
    /**
     * View Integration Addon Controler
     *
     * @return \ModulesGarden\Servers\DirectAdminExtended\Core\UI\ViewIntegrationAddon
     */
    function viewIntegrationAddon($wrapperTemplate = null, $viewTemplate = null)
    {
        $request = sl('request');

        if ($request->get('ajax') && $request->get('namespace') != null && $request->get('namespace') != '' && $request->get('namespace') != 'undefined')
        {
            $view      = sl('viewAjax');
            $namespace = $request->get('namespace');
            $view->initAjaxElementContext($namespace);

            return $view;
        }

        $view = sl('viewIntegrationAddon');

        if ($viewTemplate)
        {
            $view->setTemplate($viewTemplate);
        }

        if ($wrapperTemplate)
        {
           // $view->setTemplate($viewTemplate);
        }

        return $view;
    }
}

if (!function_exists('\ModulesGarden\Servers\DirectAdminExtended\Core\Helper\convertStringToNamespace'))
{
    function convertStringToNamespace($string)
    {
        return str_replace("_", "\\", $string);
    }
}

if (!function_exists('\ModulesGarden\Servers\DirectAdminExtended\Core\Helper\getRequest'))
{
    function getRequest()
    {
        return sl(\ModulesGarden\Servers\DirectAdminExtended\Core\Http\Request::class);
    }
}

if(!function_exists('\ModulesGarden\Servers\DirectAdminExtended\Core\Helper\fire'))
{
    /**
     * Fire event!
     * @param $event
     */
    function fire($event)
    {
        if(WhmcsVersionComparator::isWhmcsVersionHigherOrEqual('8.0.0'))
        {
            return DependencyInjection::call(Dispatcher::class)->dispatch($event);
        }

        return DependencyInjection::call(Dispatcher::class)->fire($event);
    }
}

if(!function_exists('\ModulesGarden\Servers\DirectAdminExtended\Core\Helper\dispatch'))
{
    function queue($job, $arguments)
    {
        return DependencyInjection::call(Dispatcher::class)->queue($job, $arguments);
    }
}

if (!function_exists('\ModulesGarden\Servers\DirectAdminExtended\Core\Helper\isAddon'))
{
    /**
     * @return bool
     */
    function isAddon()
    {
        if(strpos(__NAMESPACE__, 'Servers') === false)
        {
            return true;
        }

        return false;
    }
}

if (!function_exists('\ModulesGarden\Servers\DirectAdminExtended\Core\Helper\isResellerLevel'))
{
    /**
     * @return bool
     */
    function isResellerLevel()
    {
       return (isset($_SESSION['resellerloginas'],$_SESSION['resellerloginas'][$_GET['id']]));
    }
}
if (!function_exists('\ModulesGarden\Servers\DirectAdminExtended\Core\Helper\isAdminLevel'))
{
    /**
     * @return bool
     */
    function isAdminLevel()
    {
       return (isset($_SESSION['adminloginas'],$_SESSION['adminloginas'][$_GET['id']]));
    }
}
if (!function_exists('\ModulesGarden\Servers\DirectAdminExtended\Core\Helper\isAdminLoggedInAsReseller'))
{
    /**
     * @return bool
     */
    function isAdminLoggedInAsReseller()
    {
       if (isset($_SESSION['adminloginas'],$_SESSION['adminloginas'][$_GET['id']])){
            return $_SESSION['adminloginasrole'][$_GET['id']]=='reseller';
       }
       return false;
    }
}
if (!function_exists('\ModulesGarden\Servers\DirectAdminExtended\Core\Helper\isAdminLoggedInAsUser'))
{
    /**
     * @return bool
     */
    function isAdminLoggedInAsUser()
    {
       if (isset($_SESSION['adminloginas'],$_SESSION['adminloginas'][$_GET['id']])){
            return $_SESSION['adminloginasrole'][$_GET['id']]=='user';
       }
       return false;
    }
}
