<?php

namespace ModulesGarden\WordpressManager\Core\UI\Traits;

/**
 * View Breadcrumb related functions
 *
 * @author Sławomir Miśkowicz <slawomir@modulesgarden.com>
 */
trait ViewBreadcrumb
{
    protected $breadcrumbs = null;
    
    public function initBreadcrumbs()
    {
        $this->breadcrumbs = new \ModulesGarden\WordpressManager\Core\UI\Helpers\BreadcrumbsHandler();
    }
    
    public function getBreadcrumbs()
    {
        return $this->breadcrumbs->getBreadcrumbs();
    }
    
    public function addBreadcrumb($url = null, $title = null, $order = null, $rawTitle = null)
    {
        $this->breadcrumbs->addBreadcrumb($url, $title, $order, $rawTitle);
        
        return $this;
    }
    
    public function replaceBreadcrumbTitle($key = null, $value = null)
    {
        $this->breadcrumbs->replaceBreadcrumbTitle($key, $value);
        
        return $this;
    }    
}
