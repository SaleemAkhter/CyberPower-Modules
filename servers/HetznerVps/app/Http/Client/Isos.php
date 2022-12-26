<?php

namespace ModulesGarden\Servers\HetznerVps\App\Http\Client;

use Exception;
use ModulesGarden\Servers\HetznerVps\App\Helpers\FieldsProvider;
use ModulesGarden\Servers\HetznerVps\App\UI\Isos\Pages\IsosPage;
use ModulesGarden\Servers\HetznerVps\Core\Helper;
use ModulesGarden\Servers\HetznerVps\Core\Http\AbstractController;
use function ModulesGarden\Servers\HetznerVps\Core\Helper\sl;

/**
 * Description of Clientsservices
 *
 * @author Mateusz Pawłowski <mateusz.pa@modulesgarden.com>
 */
class Isos extends AbstractController
{
    public function index()
    {
        $fieldsProvider = new FieldsProvider(sl("whmcsParams")->getWhmcsParams()['packageid']);
        if ($fieldsProvider->getField('clientArea'. end(explode('\\',__CLASS__))) != 'on') {
            return;
        }
        try {
            Helper\sl("sidebar")->getSidebar("managementHetznerVps")->getChild("isos")->setActive(true);
            return Helper\view()->addElement(IsosPage::class);
        } catch (Exception $ex) {
            return $ex->getMessage();
        }
    }
}