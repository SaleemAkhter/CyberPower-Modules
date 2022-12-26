<?php

use \ModulesGarden\Servers\HetznerVps\Core\DependencyInjection;
use \ModulesGarden\Servers\HetznerVps\Core\Http\View\MainMenu;
use \ModulesGarden\Servers\HetznerVps\Core\Helper\ClientAreaSidebar;
use \ModulesGarden\Servers\HetznerVps\Core\Http\Request;
use WHMCS\View\Menu\Item as MenuItem;

$hookManager->register(
    function (\WHMCS\View\Menu\Item $primarySidebar)
    {
        /**
         * @var  main\Core\Http\Request $request
         */
        $request = \ModulesGarden\Servers\HetznerVps\Core\Helper\sl('request');
        if (!$request->get('id'))
        {
            return;
        }
        $clientAreaSideBar = new \ModulesGarden\Servers\HetznerVps\App\Service\Sidebar\ClientAreaSidebarService($request->get("id"), $primarySidebar);
        if (!$clientAreaSideBar->isActive() || !$clientAreaSideBar->isSupportedModule())
        {
            return;
        }
        if (!function_exists('ModuleBuildParams'))
        {
            require_once \ModulesGarden\Servers\HetznerVps\Core\ModuleConstants::getFullPathWhmcs('includes') . DIRECTORY_SEPARATOR . "modulefunctions.php";
        }
        $params = \ModuleBuildParams($request->get("id"));
        \ModulesGarden\Servers\HetznerVps\Core\Helper\sl("whmcsParams")->setParams($params);
        //Page Cancel
        if ($request->get('action') == "cancel")
        {
            $clientAreaSideBar->informationReplaceUri();
        } //Page Productdetails
        else
        {
            if ($request->get('action') == "productdetails")
            {
                $clientAreaSideBar->informationReplaceUri();
                $clientAreaSideBar->build();
            }
        }
    }, 943
);