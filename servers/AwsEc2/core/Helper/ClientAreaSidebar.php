<?php

namespace ModulesGarden\Servers\AwsEc2\Core\Helper;


use ModulesGarden\Servers\AwsEc2\Core\Lang\Lang;
use ModulesGarden\Servers\AwsEc2\Core\Models\ProductSettings\Repository;
use ModulesGarden\Servers\AwsEc2\Core\Models\Whmcs\Hosting;
use ModulesGarden\Servers\AwsEc2\Core\Models\Whmcs\Server;

class ClientAreaSidebar
{
    protected $lang      = null;
    protected $request   = null;
    protected $productId;
    protected $hosting;
    protected $hostingId;
    protected $server;
    protected $client;

    public function __construct()
    {
        $this->lang = new Lang();
        $this->lang->addToContext('sidebarMenu');
        $this->request = sl('request');

        $this->hostingId = $this->request->get('id', '');
        $this->hosting = Hosting::where('id', $this->hostingId)->first();;
        if ($this->hosting)
        {
            $this->productId = $this->hosting->packageid;
            $this->server    = Server::where('id', $this->hosting->server)->first();
        }
    }

    public function addChildren(&$menuItem, $children)
    {
        if (!$this->hosting || !$this->request->getSession('uid') || $this->hosting->domainstatus !== 'Active' || $this->server->type != 'AwsEc2')
        {
            return;
        }

        if(!is_array($children) && !($children instanceof \Countable)) {
            return;
        }

        foreach ($children as $childName => $child)
        {
            //todo add verification if more settings are here
            if($childName == 'firewallRules')
                if(!$this->isSettingEnabled('enableFirewallConfig'))
                    continue;
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

    public function isProperProductType()
    {
        return $this->server->type === 'AwsEc2';
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

    protected function isSettingEnabled($setting, $productId = null)
    {
        if ($productId === null) {
            $request = sl('request');
            $hostingId = $request->get('id', '');
            $hosting = Hosting::where('id', $hostingId)->first();
            $productId = $hosting->packageid;
        }

        $settingValue = (new Repository())->getProductSettings($productId);

        if ($settingValue[$setting] == 'on')
        {
            return true;
        }
        else
        {
            return false;
        }
    }
}
