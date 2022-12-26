<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\Helpers;

use \ModulesGarden\Servers\DirectAdminExtended\Core\Models\Whmcs\Product;
use \ModulesGarden\Servers\DirectAdminExtended\App\Models\ServerSettings;
use function \ModulesGarden\Servers\DirectAdminExtended\Core\Helper\sl;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Traits\RequestObjectHandler;

class ClientAreaSidebar
{

    use \ModulesGarden\Servers\DirectAdminExtended\App\Traits\HostingComponent;
    use \ModulesGarden\Servers\DirectAdminExtended\App\Traits\ProductsFeatureComponent;
    use \ModulesGarden\Servers\DirectAdminExtended\Core\Traits\Lang;
    use RequestObjectHandler;

    protected $request   = null;
    protected $hostingId = null;
    protected $productId = null;
    protected $product;
    protected $server;
    protected $client;

    public function __construct()
    {
        $this->loadLang();
       // $this->lang->setContext([]);
        $this->lang->setContext('addonCA','sidebarMenu');

        $this->request = sl('request');

        $this->hostingId = $this->request->get('id', '');
        $this->initHosting($this->hostingId);

        if ($this->hosting)
        {
            $this->productId = $this->hosting->getProductIdByHostingId($this->hostingId);
            $this->server    = $this->hosting->getServer();
            $this->product   = Product::where('id', '=', $this->productId)->first();
        }
    }

    public function addChildren(&$menuItem, $children)
    {
        if (!$this->hosting || !$this->request->getSession('uid') || $this->hosting->domainstatus !== 'Active' || $this->server->type !== 'directadminExtended')
        {
            return;
        }
        
        foreach ($children as $childName => $child)
        {
            if (in_array($childName, FeaturesWithControllers::getOneClickLogin()))
            {
                $this->addOneClickLogin($menuItem, $childName, $child);
                continue;
            }
            if($this->product->type === 'reselleraccount')
            {
                break;
            }
            if ($this->isFeatureEnabled(array_search(ucfirst($childName), FeaturesWithControllers::get()), $this->productId) === false)
            {
                continue;
            }

            $setting = array_search(ucfirst($childName), FeaturesWithControllers::get());

            if (!$this->isFeatureEnabled($setting, $this->productId)) {
                continue;
            }

            if ($childName === 'wordPressManager') {
                if (
                    \ModulesGarden\Servers\DirectAdminExtended\App\Libs\WordPressManager\WordPressManager::isActive() !== true
                    || $this->isFeatureEnabled('wordpress_manager', $this->productId) !== true
                    || !\ModulesGarden\Servers\DirectAdminExtended\App\Libs\WordPressManager\WordPressManager::activeForHosting($this->getHostingId())) {
                    continue;
                }
            }

            $btn = $menuItem->addChild($childName, [
                'label'   => $this->lang->T($childName),
                'uri'     => $this->parseUrl($childName, $child),
                'icon'    => $child['icon'],
                'order'   => $child['order'],
                'display' => true
            ]);

            if ($btn && (strtolower($this->request->get('mg-page', null)) === strtolower($childName)) ||
                    ($this->request->get('mg-page', null) === 'user' && $childName === 'users'))
            {
                $btn->setClass('active');
            }
        }
    }

    public function addOneClickLogin(&$menuItem, $childName, $child)
    {
        $feature = array_search($childName, FeaturesWithControllers::getOneClickLogin());
        if ($this->isFeatureEnabled($feature, $this->productId) === false)
        {
            return;
        }
        $params = sl('request')->query->all();
        unset($params['mg-action']);
        unset($params['mg-page']);
        $menuItem->addChild($childName, [
            'label'   => $this->lang->T($childName),
            'uri'     => (new SSO($this->getRequestValue('id')))->getLocalLink(strtolower($childName)),
            'icon'    => $child['icon'],
            'order'   => $child['order'],
            'display' => true,
            'attributes' => [
                'target' => '_blank'
            ]

        ]);
    }

    public function addSubmenuItem(&$primaryMenuItem, $name, $menuItem)
    {
        if (!$this->hosting || !$this->request->getSession('uid') || $this->hosting->domainstatus !== 'Active')
        {
            return;
        }

        $primaryMenuItem->addChild($name, [
            'label'   => $this->lang->T($name),
            'uri'     => '#',
            'icon'    => $menuItem['icon'],
            'order'   => $menuItem['order'],
            'display' => true
        ]);
    }

    public function parseUrl($pageName, $menuItem)
    {
        if ($menuItem['externalUrl'])
        {
            return $menuItem['externalUrl'];
        }

        if (!$menuItem['url'])
        {
            $menuItem['url'] = 'clientarea.php?action=productdetails&amp;id=' . $this->hostingId . '&amp;modop=custom&amp;a=management&amp;mg-page=' . $pageName;
        }

        return $menuItem['url'];
    }
    
    public function isProperProductType()
    {
        return $this->server->type === 'directadminExtended';
    }

    public function getProductId()
    {
        return $this->productId;
    }

    public function checkFeature($feature, $productId = null)
    {
        return $this->isFeatureEnabled($feature, $productId);
    }

    public function removeWpMenu(&$primarySidebar)
    {
        $isResellerAccountType = Product::where('id', $this->productId)->first()->type === 'reselleraccount';
        $isWordPressIntegrationActive = $this->isFeatureEnabled(FeaturesSettingsConstants::WORDPRESS_MANAGER, $this->productId);

        if (!$this->hosting
            || !$isWordPressIntegrationActive
            || $this->hosting->domainstatus !== 'Active'
            || $isResellerAccountType
            || !$this->request->getSession('uid')
        ) {
            return;
        }

        $action   = $primarySidebar->getChild('Service Details Actions');
///        $wpButton = $action->getChild('mg-wordpress-manager');

        if (is_object($wpButton))
        {
            $action->removeChild('mg-wordpress-manager');
        }
    }
}
