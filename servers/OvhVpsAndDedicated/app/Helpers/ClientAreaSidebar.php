<?php

namespace ModulesGarden\Servers\OvhVpsAndDedicated\App\Helpers;


use ModulesGarden\Servers\OvhVpsAndDedicated\App\Helpers\Account\Utilities\ServerStrategyProvider;
use ModulesGarden\Servers\OvhVpsAndDedicated\App\Traits\WhmcsParamsApp;
use \ModulesGarden\Servers\OvhVpsAndDedicated\Core\Helper\BuildUrl;
use function \ModulesGarden\Servers\OvhVpsAndDedicated\Core\Helper\sl;
use ModulesGarden\Servers\OvhVpsAndDedicated\Core\ServiceLocator;

class ClientAreaSidebar
{
    use \ModulesGarden\Servers\OvhVpsAndDedicated\App\Traits\HostingComponent;
    use WhmcsParamsApp;

    protected $lang      = null;
    protected $request   = null;
    protected $hostingId = null;
    protected $productId = null;
    protected $server;
    protected $client;

    public function __construct()
    {

        $this->lang = ServiceLocator::call('lang');
        $this->lang->addToContext('sidebarMenu');

        $this->request = sl('request');

        $this->hostingId = $this->request->get('id', '');
        $this->initHosting($this->hostingId);

        if ($this->hosting)
        {
            $this->productId = $this->hosting->product->id;
            $this->server    = $this->hosting->servers->id;
        }
    }

    public function addChildren(&$menuItem, $children)
    {
        if (!$this->hosting || !$this->request->getSession('uid') || $this->hosting->domainstatus !== 'Active' || $this->hosting->servers->type !== 'OvhVpsAndDedicated')
        {
            return;
        }

        foreach ($children as $childName => $child) {

            $btn = $menuItem->addChild($childName, [
                'label'   => $this->lang->T($childName),
                'uri'     => $this->parseUrl($childName, $child),
                'icon'    => $child['icon'],
                'order'   => $child['order'],
                'display' => true
            ]);

            if ($btn && (($this->request->get('mg-page', null) === $childName) ||
                    ($this->request->get('mg-page', null) === 'user' && $childName === 'users')))
            {
                $btn->setClass('active');
            }
        }
    }

    public function addOneClickLogin(&$menuItem, $childName, $child)
    {

        $menuItem->addChild($childName, [
            'label'   => $this->lang->T($childName),
//            'uri' => BuildUrl::getUrl('OneClickLogin', 'redirect',array_merge($params,['where' => $childName])),
            'icon'    => $child['icon'],
            'order'   => $child['order'],
            'display' => true
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
    public function isProperProductType(){

        $serverStrategyProvider = new ServerStrategyProvider();
        $server = $serverStrategyProvider->getServerStrategy($this->getWhmcsEssentialParams());

        try
        {
            if(!is_object($server))
            {
                return false;

            }
            $server->getInfo();
        }
        catch (\Exception $exception)
        {
            return false;
        }

        return $this->hosting->servers->type === "OvhVpsAndDedicated";
    }

}
