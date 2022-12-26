<?php

use \ModulesGarden\Servers\OvhVpsAndDedicated\Core\DependencyInjection;
use function ModulesGarden\Servers\OvhVpsAndDedicated\Core\Helper\sl;
use \ModulesGarden\Servers\OvhVpsAndDedicated\Core\Http\View\MainMenu;
use \ModulesGarden\Servers\OvhVpsAndDedicated\App\Helpers\ClientAreaSidebar;
use \ModulesGarden\Servers\OvhVpsAndDedicated\Core\Http\Request;
use WHMCS\View\Menu\Item as MenuItem;

$hookManager->register(
    function (MenuItem $primarySidebar)
    {
        try{
            $request = sl('request');
            if (!$request->get('id'))
            {
                return;
            }
            //is Active
            $query =   \ModulesGarden\Servers\OvhVpsAndDedicated\App\Models\Whmcs\Hosting::ofId($request->get('id'))
                ->active();
            if(!$query->count()){
                return;
            }
            $query =   \ModulesGarden\Servers\OvhVpsAndDedicated\App\Models\Whmcs\Hosting::ofServerType($request->get('id'),'OvhVpsAndDedicated');
            if(!$query->count()){
                return;
            }

            $lang = DependencyInjection::get('lang');
            $lang->setContext('addonCA');

            $sidebarHaleper = new ClientAreaSidebar();
            if (!$sidebarHaleper->isProperProductType())
            {
                return;
            }

            $request = Request::build();

            if ($request->get('action', false) === 'cancel')
            {
                $overview = $primarySidebar->getChild('Service Details Overview');
                if (is_object($overview))
                {
                    $information = $overview->getChild('Information');
                    if (is_object($information))
                    {
                        $uri = $information->getUri();
                        $pos = stripos($uri, '#');
                        if ($pos > 0)
                        {
                            $uri = substr($uri, 0, $pos);
                            $information->setUri($uri);
                        }
                    }
                }
            }

            $menuItems = DependencyInjection::create(MainMenu::class)->getMenu();

            foreach ($menuItems as $name => $item)
            {
                if (is_null($primarySidebar->getChild($name)))
                {
                    $sidebarHaleper->addSubmenuItem($primarySidebar, $name, $item);
                }

                if (!is_null($primarySidebar->getChild($name)) && count($item['children']) > 0)
                {
                    if (count($item['children']) > 0)
                    {
                        try
                        {
                            $pageController = new \ModulesGarden\Servers\OvhVpsAndDedicated\App\Helpers\PageController();
                            $item['children'] = $pageController->checkPages($item['children']);
                        }
                        catch (\Exception $ex)
                        {
                            continue;
                        }
                    }

                    $sidebarHaleper->addChildren($primarySidebar->getChild($name), $item['children']);
                }
            }

            if ($request->get('a', false) == 'management')
            {

                $action = $primarySidebar->getChild('Service Details Actions');
                if (!is_object($action))
                {
                    return;
                }

                $overview = $primarySidebar->getChild('Service Details Overview');
                if (is_object($overview))
                {
                    $info = $overview->getChild('Information');
                    if (is_object($info))
                    {
                        $currentLink = $info->getUri();
                        if (strpos($currentLink, '#') === 0)
                        {
                            $link = 'clientarea.php?action=productdetails&id=' . (int)$_GET['id'];
                            $info->setUri('#" onclick = " window.location = \'clientarea.php?action=productdetails&id=' . $link . '\' " ');
                        }
                        if (strpos($currentLink, '#tabOverview') > 0)
                        {
                            $info->setUri('#" onclick = " event.preventDefault(); window.location = \'' . str_replace('#tabOverview', '', $currentLink) . '\'"');
                        }
                    }
                }
            }
        }catch (\Exception $ex){
            //nothing to do
        }
    }, 943
);
