<?php


namespace ModulesGarden\Servers\HetznerVps\App\Http\Client;

use Exception;
use ModulesGarden\Servers\HetznerVps\App\Helpers\FieldsProvider;
use ModulesGarden\Servers\HetznerVps\App\UI\Backups\Pages\BackupsInformation;
use ModulesGarden\Servers\HetznerVps\App\UI\Backups\Pages\BackupsPage;
use ModulesGarden\Servers\HetznerVps\Core\Helper;
use ModulesGarden\Servers\HetznerVps\Core\Http\AbstractController;
use function ModulesGarden\Servers\HetznerVps\Core\Helper\sl;

class Backups extends AbstractController
{
    public function index()
    {
        $fieldsProvider = new FieldsProvider(sl("whmcsParams")->getWhmcsParams()['packageid']);
        if ($fieldsProvider->getField('clientArea'. end(explode('\\',__CLASS__))) != 'on') {
            return;
        }
        try {
            Helper\sl("sidebar")->getSidebar("managementHetznerVps")->getChild("backups")->setActive(true);
            return Helper\view()->addElement(BackupsInformation::class)->addElement(BackupsPage::class);
        } catch (Exception $ex) {
            return $ex->getMessage();
        }
    }
}