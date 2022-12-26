<?php

namespace ModulesGarden\WordpressManager\Core\UI\Helpers;

use \ModulesGarden\WordpressManager\Core\ServiceLocator;
use \ModulesGarden\WordpressManager\Core\Helper\BuildUrl;
use \ModulesGarden\WordpressManager\Core\App\Controllers\Instances\Addon\Config;

/**
 * BreadcrumbsHandler
 *
 * @author Sławomir Miśkowicz <slawomir@modulesgarden.com>
 */
class BreadcrumbsHandler
{
    use \ModulesGarden\WordpressManager\Core\Traits\IsAdmin;
    
    protected $breadcrumbs = [];
    
    public function __construct()
    {
        $this->loadDefault();
    }
    
    public function addBreadcrumb($url = null, $title = null, $order = null, $rawTitle = null)
    {
        $this->breadcrumbs[] = new \ModulesGarden\WordpressManager\Core\UI\Helpers\Breadcrumb(
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
        
        $router = new \ModulesGarden\WordpressManager\Core\App\Controllers\Router();
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
        usort($this->breadcrumbs, function ($a, $b) { return ($a->getOrder() > $b->getOrder()); });
        
        $bcList = [];
        foreach ($this->breadcrumbs as $brc)
        {
            $bcDetails = $brc->getBreadcrumb();
            $bcList[$bcDetails['url']] = $bcDetails['title'];
        }
   
        return $bcList;
    }
    
    public function replaceBreadcrumbTitle($key = null, $value = null)
    {
        if ($key === null || !is_string($value) || $value === '' ||  !$this->breadcrumbs[$key])
        {
            return $this;
        }

        $this->breadcrumbs[$key]->setTitle($value);
        
        return $this;
    }    
}
