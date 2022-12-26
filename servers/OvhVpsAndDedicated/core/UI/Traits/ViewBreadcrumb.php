<?php

namespace ModulesGarden\Servers\OvhVpsAndDedicated\Core\UI\Traits;

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
        $this->breadcrumbs = new \ModulesGarden\Servers\OvhVpsAndDedicated\Core\UI\Helpers\BreadcrumbsHandler();
    }
    
    public function getBreadcrumbs()
    {
        return $this->breadcrumbs->getBreadcrumbs();
    }
}
