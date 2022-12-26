<?php

namespace ModulesGarden\Servers\HetznerVps\App\Http\Client;

use Exception;
use ModulesGarden\Servers\HetznerVps\App\Helpers\FieldsProvider;
use ModulesGarden\Servers\HetznerVps\App\UI\Rebuild\Pages\AlertBox;
use ModulesGarden\Servers\HetznerVps\App\UI\Rebuild\Pages\RebuildPage;
use ModulesGarden\Servers\HetznerVps\Core\Helper;
use ModulesGarden\Servers\HetznerVps\Core\Http\AbstractController;
use function ModulesGarden\Servers\HetznerVps\Core\Helper\sl;

/**
 * Description of Clientsservices
 *
 * @author Mateusz Pawłowski <mateusz.pa@modulesgarden.com>
 */
class Rebuild extends AbstractController
{
    public function index()
    {
        $fieldsProvider = new FieldsProvider(sl("whmcsParams")->getWhmcsParams()['packageid']);
        if ($fieldsProvider->getField('clientArea'. end(explode('\\',__CLASS__))) != 'on') {
            return;
        }
        try {
            Helper\sl("sidebar")->getSidebar("managementHetznerVps")->getChild("rebuild")->setActive(true);
            return Helper\view()->addElement(AlertBox::class)->addElement(RebuildPage::class);
        } catch (Exception $ex) {
            return $ex->getMessage();
        }
    }
}