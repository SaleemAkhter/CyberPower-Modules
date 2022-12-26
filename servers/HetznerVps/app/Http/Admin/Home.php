<?php

namespace ModulesGarden\Servers\HetznerVps\App\Http\Admin;

use Exception;
use ModulesGarden\Servers\HetznerVps\App\Libs\HetznerVps\Api;
use ModulesGarden\Servers\HetznerVps\App\UI\Backups\Pages\BackupsPage;
use ModulesGarden\Servers\HetznerVps\App\UI\FloatingIP\Pages\FloatingIPPage;
use ModulesGarden\Servers\HetznerVps\App\UI\Home\Pages\ControlPanel;
use ModulesGarden\Servers\HetznerVps\App\UI\Isos\Pages\IsosPage;
use ModulesGarden\Servers\HetznerVps\App\UI\Product\Pages\ServerInformation;
use ModulesGarden\Servers\HetznerVps\App\UI\Rebuild\Pages\RebuildPage;
use ModulesGarden\Servers\HetznerVps\Core\Helper;
use ModulesGarden\Servers\HetznerVps\Core\Http\AbstractController;
use ModulesGarden\Servers\HetznerVps\Core\Traits\OutputBuffer;
use ModulesGarden\Servers\HetznerVps\Core\UI\ResponseTemplates\HtmlDataJsonResponse;
use ModulesGarden\Servers\HetznerVps\Core\UI\Traits\WhmcsParams;

/**
 * Example admin home page controler
 * @author Sławomir Miśkowicz <slawomir@modulesgarden.com>
 */
class Home extends AbstractController
{
    use WhmcsParams;
    use OutputBuffer;

    public function index()
    {
        try {
            $api = new Api($this->getWhmcsParams());
            $server = $api->servers()->get($this->getWhmcsParams()['customfields']['serverID']);
            unset($api);
            if (is_null($server)) throw new Exception('ServerNotExist', 404);
        } catch (Exception $ex) {
            return (new HtmlDataJsonResponse())->setStatusError()->setMessage($ex->getMessage());
        }

        $view = Helper\viewIntegrationAddon();
        $view->initCustomAssetFiles();
        $view->addElement(ControlPanel::class)
            ->addElement(ServerInformation::class)
            ->addElement(RebuildPage::class)
            ->addElement(IsosPage::class)
            ->addElement(FloatingIPPage::class)
            ->addElement(BackupsPage::class);

        return $view;
    }
}
