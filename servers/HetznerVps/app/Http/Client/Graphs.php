<?php

namespace ModulesGarden\Servers\HetznerVps\App\Http\Client;

use Exception;
use ModulesGarden\Servers\HetznerVps\App\Helpers\FieldsProvider;
use ModulesGarden\Servers\HetznerVps\App\UI\Graphs\Pages\CpuGraph;
use ModulesGarden\Servers\HetznerVps\App\UI\Graphs\Pages\DiskGraph;
use ModulesGarden\Servers\HetznerVps\App\UI\Graphs\Pages\NetworkGraph;
use ModulesGarden\Servers\HetznerVps\Core\Helper;
use ModulesGarden\Servers\HetznerVps\Core\Http\AbstractClientController;
use function ModulesGarden\Servers\HetznerVps\Core\Helper\sl;

/**
 * Description of Clientsservices
 */
class Graphs extends AbstractClientController
{
    public function index()
    {
        $fieldsProvider = new FieldsProvider(sl("whmcsParams")->getWhmcsParams()['packageid']);
        if ($fieldsProvider->getField('clientArea'. end(explode('\\',__CLASS__))) != 'on') {
            return;
        }
        try {
            Helper\sl("sidebar")->getSidebar("managementHetznerVps")->getChild("graphs")->setActive(true);
            $view = Helper\view();
            $view->initCustomAssetFiles();
            $view->addElement(CpuGraph::class)
                ->addElement(DiskGraph::class)
                ->addElement(NetworkGraph::class);
            return $view;
        } catch (Exception $ex) {
            return $ex->getMessage();
        }
    }
}
