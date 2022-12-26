<?php

/* * ********************************************************************
 * Wordpress_Manager Product developed. (Dec 4, 2017)
 * *
 *
 *  CREATED BY MODULESGARDEN       ->       http://modulesgarden.com
 *  CONTACT                        ->       contact@modulesgarden.com
 *
 *
 * This software is furnished under a license and may be used and copied
 * only  in  accordance  with  the  terms  of such  license and with the
 * inclusion of the above copyright notice.  This software  or any other
 * copies thereof may not be provided or otherwise made available to any
 * other person.  No title to and  ownership of the  software is  hereby
 * transferred.
 *
 *
 * ******************************************************************** */

use \ModulesGarden\WordpressManager\Core\Helper\BuildUrl;
use \ModulesGarden\WordpressManager\App\Models\Installation;
use ModulesGarden\WordpressManager\App\Repositories\ProductSettingRepository;
use ModulesGarden\WordpressManager\App\Models\Whmcs\Hosting;
use \ModulesGarden\WordpressManager\App\Http\Client\SidebarHook;
use function \ModulesGarden\WordpressManager\Core\Helper\sl;
use \ModulesGarden\Servers\DirectAdminExtended\Core\DependencyInjection;
use \ModulesGarden\Servers\DirectAdminExtended\Core\Http\View\MainMenu;
/**
 * Description of ClientAreaPrimarySidebar
 *
 * @author Pawel Kopec <pawelk@modulesgarden.com>
 */
$hookManager->register(
    function (WHMCS\View\Menu\Item $primarySidebar)
    {

        $request = sl('request');
        /* @var $request ModulesGarden\WordpressManager\Core\Http\Request */
        if ((!$request->get('m') ||  $request->get('m')!="WordpressManager") && (!$request->get('action') || $request->get('action') != "productdetails" || !$request->get('id')))
        {
            return;
        }
        $id=$request->get('id')??$request->get('hostingId');
        $repository = new ProductSettingRepository;
        if (!$repository->isEnabled($id))
        {
            return;
        }
        $panel = $primarySidebar->getChild('Service Details Actions');
        if (!is_a($panel, 'WHMCS\View\Menu\Item'))
        {
            $panel = $primarySidebar->addChild('Service Details Actions');
        }


        $hosting = Hosting::with('product')->where('id', $id)->first();

        $repository = new ProductSettingRepository;
        $model      = $repository->forProductId($hosting->packageid);
        $data       = $model->toArray();

        if($data['actions-control-panel'] == 0 || ($hosting->product->type=="reselleraccount" && (!isset($_SESSION['resellerloginas']) || empty($_SESSION['resellerloginas']))))
            return;

        if($request->get('id') && $request->get('m')=="WordpressManager"){
            $link = 'clientarea.php?action=productdetails&id=' . (int) $_GET['id'] ;
            $primarySidebar->addChild('Overview');
            $overview= $primarySidebar->getChild('Overview');
            $overview->addChild("Information")->setUri($link);

            $primarySidebar->addChild('Wordpress Manager');
            $WPManager= $primarySidebar->getChild('Wordpress Manager');
            $link = 'index.php?m=WordpressManager&mg-page=home&mg-action=new&id=' . (int) $_GET['id'] ;
            $WPManager->addChild("New Installation")->setUri($link);
            $link = 'index.php?m=WordpressManager&mg-page=home&mg-action=remoteimport&id=' . (int) $_GET['id'] ;
            $WPManager->addChild("Remote Import")->setUri($link);
            $sidebarHaleper = new \ModulesGarden\Servers\DirectAdminExtended\App\Helpers\ClientAreaSidebar();
            if(!$sidebarHaleper->isProperProductType())
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

            $action = $primarySidebar->getChild('Service Details Actions');


            if( !$sidebarHaleper->checkFeature('change_password', $sidebarHaleper->getProductId()))
            {
                $action->removeChild('Change Password');
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
        }
        if($request->get('id') && $request->get('m')!="WordpressManager"){
            $sidebar = $panel->addChild("mg-wordpress-manager", array(
                'label' => sl('lang')->absoluteT('Wordpress Management'),
                'uri'   => "index.php?m=WordpressManager&id=".$request->get('id'),
                "order" => 300,
                "icon" => sl('sidebar')->getSidebar("management")->getChild('details')->getIcon()
            ));
            $installations = Installation::where('user_id', $request->getSession('uid'))->where('hosting_id', $id)->get();

            if(count($installations) > 0)
            {
                $list = $primarySidebar->addChild('mg-wordpress-manager-list', [
                    'label' => sl('lang')->absoluteT('Wordpress SSO'),
                    'uri' => '#',
                    'order' => '100',
                    'icon' => 'fa-bars',
                ]);
                foreach($installations as $install)
                {
                    $list->addChild('mg-wordpress-manager-install-' . $install->id)
                    ->setLabel(sl('lang')->absoluteT('Wordpress Login') . ' ' . $install->domain)
                    ->setAttribute('target', '_blank')
                    ->setUri(BuildUrl::getUrl('home', 'controlPanel', ['wpid' => $install->id]));
                }
            }
        }


    }, 1000
);

