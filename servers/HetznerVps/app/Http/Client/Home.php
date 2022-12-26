<?php

namespace ModulesGarden\Servers\HetznerVps\App\Http\Client;

use Exception;
use ModulesGarden\Servers\HetznerVps\App\Helpers\AppParams;
use ModulesGarden\Servers\HetznerVps\App\Libs\HetznerVps\Api;
use ModulesGarden\Servers\HetznerVps\App\Service\Sidebar\ProductService;
use ModulesGarden\Servers\HetznerVps\App\UI\Home\Pages\ControlPanel;
use ModulesGarden\Servers\HetznerVps\App\UI\Home\Pages\ManageService;
use ModulesGarden\Servers\HetznerVps\App\UI\Product\Pages\ServerInformation;
use ModulesGarden\Servers\HetznerVps\Core\Helper;
use ModulesGarden\Servers\HetznerVps\Core\Http\AbstractClientController;
use ModulesGarden\Servers\HetznerVps\Core\Http\AbstractController;
use ModulesGarden\Servers\HetznerVps\Core\UI\ResponseTemplates\HtmlDataJsonResponse;
use ModulesGarden\Servers\HetznerVps\Core\UI\Traits\RequestObjectHandler;
use ModulesGarden\Servers\HetznerVps\Core\UI\Traits\WhmcsParams;
use function ModulesGarden\Servers\HetznerVps\Core\Helper\sl;

/**
 * Description of Clientsservices
 *
 * @author Mateusz Pawłowski <mateusz.pa@modulesgarden.com>
 */
class Home extends AbstractClientController
{
    use WhmcsParams;
    use ProductService;
    use RequestObjectHandler;

    public function index()
    {
        $this->whmcsParams = sl("whmcsParams")->getWhmcsParams();
        $api = new Api($this->whmcsParams);
        $server = $api->servers()->get($this->whmcsParams['customfields']['serverID']);
        unset($api);

        try {
            if (is_null($server)) throw new Exception('ServerNotExist', 404);

            (new AppParams())->initFromWhmcsParams();
            return Helper\view()
                ->addElement(new ControlPanel())
                ->addElement(new ManageService())
                ->addElement(new ServerInformation());
        } catch (Exception $ex) {
            return (new HtmlDataJsonResponse())->setStatusError()->setMessage($ex->getMessage());
//            return $ex->getMessage();
        }
    }
}
