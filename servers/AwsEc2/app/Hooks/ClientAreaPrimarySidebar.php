<?php

use ModulesGarden\Servers\AwsEc2\App\Libs\AwsIntegration\ClientWrapper;
use ModulesGarden\Servers\AwsEc2\Core\Models\ProductSettings\Repository;
use ModulesGarden\Servers\AwsEc2\Core\Models\Whmcs\Hosting;
use WHMCS\View\Menu\Item as MenuItem;
use ModulesGarden\Servers\AwsEc2\Core\DependencyInjection;
use ModulesGarden\Servers\AwsEc2\Core\Helper\ClientAreaSidebar;
use ModulesGarden\Servers\AwsEc2\Core\Http\Request;
use ModulesGarden\Servers\AwsEc2\Core\Http\View\MainMenu;

use function ModulesGarden\Servers\AwsEc2\Core\Helper\sl;

$hookManager->register(
    function (MenuItem $primarySidebar)
    {
        $request = sl('request');

        $id = $request->get('id','');
        $hosting = (new Hosting())->where('id', $id)->first();

        $sidebarHeleper = new ClientAreaSidebar();
        if(!$sidebarHeleper->isProperProductType())
        {
            return;
        }

        if($hosting->domainstatus !== 'Active')
            return;

        $productId = $hosting->packageid;

        $productConfigRepo = new Repository();
        $productConfig = $productConfigRepo->getProductSettings($productId);
        if($productConfig['firewall'] === 'off')
            return;

        $request = Request::build();

        if ($request->get('action', false) === 'productdetails') {
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
        if ($request->get('action', false) === 'productdetails') {
            $overview = $primarySidebar->getChild('Service Details Overview');
            if (is_object($overview))
            {
                $info = $overview->addChild('Available Addons')->setUri('cart.php?gid=addons')->setOrder(100);
            }
        }

        if ($request->get('action', false) !== 'productdetails')
        {
            return;
        }
        $menuItems = DependencyInjection::create(MainMenu::class)->getMenu();

        foreach ($menuItems as $name => $item)
        {
            if (is_null($primarySidebar->getChild($name)))
            {
                $sidebarHeleper->addSubmenuItem($primarySidebar, $name, $item);
            }

            if (!is_string($item) && (is_array($item['children']) || $item['children'] instanceof \Countable) && count($item['children']) > 0)
            {
                $sidebarHeleper->addChildren($primarySidebar->getChild($name), $item['children']);
            }
        }
    },
    1001
);
