<?php

namespace ModulesGarden\Servers\OvhVpsAndDedicated\Core\UI\Helpers;

use \ModulesGarden\Servers\OvhVpsAndDedicated\Core\ServiceLocator;
use \ModulesGarden\Servers\OvhVpsAndDedicated\Core\Helper\BuildUrl;
use \ModulesGarden\Servers\OvhVpsAndDedicated\Core\App\Controllers\Instances\Addon\Config;

/**
 * BreadcrumbsHandler
 *
 * @author Sławomir Miśkowicz <slawomir@modulesgarden.com>
 */
class BreadcrumbsHandler
{
    use \ModulesGarden\Servers\OvhVpsAndDedicated\Core\Traits\IsAdmin;
    
    protected $breadcrumbs = [];
    
    public function __construct()
    {
        $this->loadDefault();
    }
    
    public function addBreadcrumb($url = null, $title = null, $order = null, $rawTitle = null)
    {
        $this->breadcrumbs[] = new \ModulesGarden\Servers\OvhVpsAndDedicated\Core\UI\Helpers\Breadcrumb(
                $url, $title, $this->getOrder($order), $rawTitle);
    }
    
    public function loadDefault()
    {
        if (!$this->isAdmin())
        {
            $clientAreaName = ServiceLocator::call(Config::class)->getConfigValue('clientareaName', 'default');
            $url            = BuildUrl::getUrl();
            $this->addBreadcrumb($url, $clientAreaName, null);
        }
        //todo AA
        if ($this->isAdmin())
        {
            return;
        }
        
        $router = new \ModulesGarden\Servers\OvhVpsAndDedicated\Core\App\Controllers\Router();
        if ($router->getControllerMethod() !== 'index')
        {
            $url = BuildUrl::getUrl($router->getController(), $router->getControllerMethod());
            $this->addBreadcrumb($url, $router->getController() . '_' . $router->getControllerMethod(), null);            
        }
    }
    
    public function getOrder($order = null)
    {
        if (is_int($order) && $order > 0)
        {
            return $order;
        }
        
        if (count($this->breadcrumbs) === 0)
        {
            return 100;
        }
                
        $last = end($this->breadcrumbs);
        
        return $last->getOrder() + 100;
    }
    
    public function getBreadcrumbs()
    {
        //sort
        $bcList = [];
        foreach ($this->breadcrumbs as $brc)
        {
            $bcDetails = $brc->getBreadcrumb();
            $bcList[$bcDetails['url']] = $bcDetails['title'];
        }
   
        return $bcList;
    }
}
