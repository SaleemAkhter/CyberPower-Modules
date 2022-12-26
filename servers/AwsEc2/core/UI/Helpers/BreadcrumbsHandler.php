<?php

namespace ModulesGarden\Servers\AwsEc2\Core\UI\Helpers;

use ModulesGarden\Servers\AwsEc2\Core\ServiceLocator;
use ModulesGarden\Servers\AwsEc2\Core\Helper\BuildUrl;
use ModulesGarden\Servers\AwsEc2\Core\App\Controllers\Instances\Addon\Config;
use ModulesGarden\Servers\AwsEc2\Core\DependencyInjection;
use ModulesGarden\Servers\AwsEc2\Core\Traits\IsAdmin;
use ModulesGarden\Servers\AwsEc2\Core\Http\View\MainMenu;

/**
 * BreadcrumbsHandler
 *
 * @author Sławomir Miśkowicz <slawomir@modulesgarden.com>
 */
class BreadcrumbsHandler
{
    use IsAdmin;
    
    protected $breadcrumbs = [];
    
    public function __construct()
    {
        $this->loadDefault();
    }
    
    public function addBreadcrumb($url = null, $title = null, $order = null, $rawTitle = null)
    {
        $this->breadcrumbs[] = new \ModulesGarden\Servers\AwsEc2\Core\UI\Helpers\Breadcrumb(
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

        $router = new \ModulesGarden\Servers\AwsEc2\Core\App\Controllers\Router();
        if ($this->isAdmin())
        {
            $mainMenu = DependencyInjection::create(MainMenu::class)->buildBreadcrumb($router->getController(), $router->getControllerMethod(), []);
            $order = 10;
            foreach ($mainMenu->getBreadcrumb() as $item)
            {
                $this->addBreadcrumb($item['url'], $item['name'], $order);
                $order += 10;
            }
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
