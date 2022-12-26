<?php

use ModulesGarden\Servers\GoogleCloudVirtualMachines\Core\DependencyInjection;
use \ModulesGarden\Servers\GoogleCloudVirtualMachines\Core\Http\View\MainMenu;
use ModulesGarden\Servers\GoogleCloudVirtualMachines\Core\Helper\ClientAreaSidebar;
use \ModulesGarden\Servers\GoogleCloudVirtualMachines\Core\Http\Request;
use WHMCS\View\Menu\Item as MenuItem;

$hookManager->register(
        function (MenuItem $primarySidebar)
{
    $sidebarHaleper = new ClientAreaSidebar();

    if(!$sidebarHaleper->isProperProductType())
    {
        return;
    }

    $request = Request::build();
    
    $snapshotsEnabled = '';
    $graphsEnabled = '';
    if($_GET['action'] === 'productdetails'){
        $hostingModel = new ModulesGarden\Servers\GoogleCloudVirtualMachines\Core\Models\Whmcs\Hosting();
        $hosting = $hostingModel->where('id', $_GET['id'])->first();

        /* Just return without adding anything if product server type is not from this module */
        if($hosting->product()->first()->servertype !== 'GoogleCloudVirtualMachines')
        {
            return;
        }

        $productSettingRepo = new \ModulesGarden\Servers\GoogleCloudVirtualMachines\App\Repositories\ProductSettingRepository($hosting->packageid);
        $snapshotsEnabled = $productSettingRepo->snapshots;
        $graphsEnabled = $productSettingRepo->graphs;
    }

    $menuItems      = DependencyInjection::create(MainMenu::class)->getMenu();


    foreach ($menuItems as $name => $item)
    {
        if (is_null($primarySidebar->getChild($name)))
        {
            $sidebarHaleper->addSubmenuItem($primarySidebar, $name, $item);
        }

        if (!is_null($primarySidebar->getChild($name)) && count($item['children']) > 0)
        {
            if((array_key_exists('snapshot', $item['children']) && $snapshotsEnabled !== 'on')){
                unset($item['children']['snapshot']);
            }

            if((array_key_exists('graph', $item['children']) && $graphsEnabled !== 'on')){
                unset($item['children']['graph']);
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
        $changePassword = $action->getChild('Change Password');
        if (is_object($changePassword))
        {
                $currentLink = $changePassword->getUri();
                if (strpos($currentLink, '#') === 0) {
                    $link = 'clientarea.php?action=productdetails&id=' . (int)$_GET['id'] . $currentLink;
                    $changePassword->setUri('#blank" onclick = " window.location = \'clientarea.php?action=productdetails&id=' . $link . '\' " ');
                }
                if (strpos($currentLink, '#tabChangepw') > 0) {
                    $changePassword->setUri('#blank" onclick = " window.location = \'' . $currentLink . '\' " ');
                }

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
                    $info->setUri('#blank" onclick = " window.location = \'clientarea.php?action=productdetails&id=' . $link . '\' " ');
    }
                if (strpos($currentLink, '#tabOverview') > 0)
                {
                    $info->setUri('#blank" onclick = " event.preventDefault(); window.location = \'' . str_replace('#tabOverview', '', $currentLink) . '\'"');
                }
            }
        }
    }
}, 943
);
