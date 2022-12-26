<?php

namespace ModulesGarden\Servers\HetznerVps\App\Http\Client;

use Exception;
use ModulesGarden\Servers\HetznerVps\App\Helpers\FieldsProvider;
use ModulesGarden\Servers\HetznerVps\App\UI\FloatingIP\Pages\FloatingIPPage;
use ModulesGarden\Servers\HetznerVps\Core\Helper;
use ModulesGarden\Servers\HetznerVps\Core\Http\AbstractClientController;
use function ModulesGarden\Servers\HetznerVps\Core\Helper\sl;

class FloatingIPs extends AbstractClientController
{
    public function index()
    {
        $fieldsProvider = new FieldsProvider(sl("whmcsParams")->getWhmcsParams()['packageid']);
        if ($fieldsProvider->getField('clientArea'. end(explode('\\',__CLASS__))) != 'on') {
            return;
        }
        try {
            Helper\sl("sidebar")->getSidebar("managementHetznerVps")->getChild("floatingIPs")->setActive(true);
            return Helper\view()->addElement(FloatingIPPage::class);
        } catch (Exception $ex) {
            return $ex->getMessage();
        }
    }

}
