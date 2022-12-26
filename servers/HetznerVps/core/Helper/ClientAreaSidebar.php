<?php

namespace ModulesGarden\Servers\HetznerVps\Core\Helper;

use \ModulesGarden\Servers\HetznerVps\Core\Helper\Lang;
use \ModulesGarden\Servers\HetznerVps\Core\Helper\BuildUrl;
use function \ModulesGarden\Servers\HetznerVps\Core\Helper\sl;

class ClientAreaSidebar
{

    use \ModulesGarden\Servers\HetznerVps\App\Traits\HostingComponent;

    protected $lang      = null;
    protected $request   = null;
    protected $hostingId = null;
    protected $productId = null;
    protected $server;
    protected $client;

    public function __construct()
    {
        $this->lang = Lang::getInstance();
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
        if (!$this->hosting || !$this->request->getSession('uid') || $this->hosting->domainstatus !== 'Active' || $this->hosting->servers->type !== 'HetznerVps' || !is_array($children))
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

        return $this->hosting->servers->type === "HetznerVps";
    }

}
