<?php

use \ModulesGarden\Servers\DirectAdminExtended\Core\DependencyInjection;
use \ModulesGarden\Servers\DirectAdminExtended\Core\Http\View\MainMenu;
use \ModulesGarden\Servers\DirectAdminExtended\Core\Http\Request;

use WHMCS\View\Menu\Item as MenuItem;

$hookManager->register(
    function (MenuItem $primarySidebar)
    {
        $sidebarHaleper = new \ModulesGarden\Servers\DirectAdminExtended\App\Helpers\ClientAreaSidebar();
        if(!$sidebarHaleper->isProperProductType())
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
        
        if ($request->get('action', false) !== 'productdetails')
        {
            return;
        }
        
        $menuItems = DependencyInjection::create(MainMenu::class)->getMenu();

        $sidebarHaleper->removeWpMenu($primarySidebar);

        foreach ($menuItems as $name => $item)
        {
            if (is_null($primarySidebar->getChild($name)))
            {   
                $sidebarHaleper->addSubmenuItem($primarySidebar, $name, $item);
            }   
            
            if (!is_null($primarySidebar->getChild($name)) && count($item['children']) > 0)
            {   
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

            $changePassword = $action->getChild('Change Password');
            if (is_object($changePassword))
            {
                $currentLink = $changePassword->getUri();
                if (strpos($currentLink, '#') === 0)
                {
                    $link = 'clientarea.php?action=productdetails&id=' . (int) $_GET['id'] . $currentLink;
                    $changePassword->setUri('#" onclick = " window.location = \'clientarea.php?action=productdetails&id=' . $link . '\' " ');
                }
                if (strpos($currentLink, '#tabChangepw') > 0)
                {
                    $changePassword->setUri('#" onclick = " window.location = \'' . $currentLink . '\' " ');
                }
            }

            if( !$sidebarHaleper->checkFeature('change_password', $sidebarHaleper->getProductId()))
            {
                $action->removeChild('Change Password');
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
                        $link = 'clientarea.php?action=productdetails&id=' . (int) $_GET['id'];
                        $info->setUri('#" onclick = " window.location = \'clientarea.php?action=productdetails&id=' . $link . '\' " ');
                    }
                    if (strpos($currentLink, '#tabOverview') > 0)
                    {
                        $info->setUri('#" onclick = " event.preventDefault(); window.location = \'' . str_replace('#tabOverview', '', $currentLink) . '\'"');
                    }
                }
            }
        }
    },
    1001
);
