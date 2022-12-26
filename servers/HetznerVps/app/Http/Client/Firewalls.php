<?php

namespace ModulesGarden\Servers\HetznerVps\App\Http\Client;

use Exception;
use ModulesGarden\Servers\HetznerVps\App\Helpers\FieldsProvider;
use ModulesGarden\Servers\HetznerVps\App\UI\Firewall\Pages\FirewallRules;
use ModulesGarden\Servers\HetznerVps\App\UI\Firewall\Pages\FirewallPage;
use ModulesGarden\Servers\HetznerVps\Core\Helper;
use ModulesGarden\Servers\HetznerVps\Core\Http\AbstractClientController;
use function ModulesGarden\Servers\HetznerVps\Core\Helper\sl;

class Firewalls extends AbstractClientController
{
    public function index()
    {
        $fieldsProvider = new FieldsProvider(sl("whmcsParams")->getWhmcsParams()['packageid']);
        if ($fieldsProvider->getField('clientArea'. end(explode('\\',__CLASS__))) != 'on') {
            return;
        }
        try {
            Helper\sl("sidebar")->getSidebar("managementHetznerVps")->getChild("firewalls")->setActive(true);
            if( $this->getRequestValue('firewallid') ){
                return Helper\view()->addElement(FirewallRules::class);
            }
            return Helper\view()->addElement(FirewallPage::class);
        } catch (Exception $ex) {
            return $ex->getMessage();
        }
    }
}
