<?php

namespace ModulesGarden\Servers\OvhVpsAndDedicated\App\Integrations\Admin;

use ModulesGarden\Servers\OvhVpsAndDedicated\App\Helpers\Models\Product;
use ModulesGarden\Servers\OvhVpsAndDedicated\App\Models\Hosting;
use ModulesGarden\Servers\OvhVpsAndDedicated\Core\Hook\AbstractHookIntegrationController;
use ModulesGarden\Servers\OvhVpsAndDedicated\Core\UI\Traits\RequestObjectHandler;
use ModulesGarden\Servers\OvhVpsAndDedicated\App\Helpers\Server;
/**
 * Class ServicePage
 *
 * @author Artur Pilch <artur.pi@modulesgarden.com>
 */
class ServicePage extends AbstractHookIntegrationController
{
    use RequestObjectHandler;
    /**
     * ProductEdit constructor.
     */
    public function __construct()
    {

//        if($this->isOvhIntegration())
//        {
//            $this->addIntegration(
//                'clientsservices',
//                [],
//                [\ModulesGarden\Servers\OvhVpsAndDedicated\App\Http\Admin\Server\ProductPage::class, 'servicePageIndex'],
//                '#mg-ovh-hosting-integration',
//                self::TYPE_PREPEND,
//                null,
//                self::INSERT_TYPE_FULL
//            );
//        }
    }

    private function isOvhIntegration()
    {
        $serviceId = $this->getServiceId();
        $productId = Hosting::where('id', $serviceId)->first()->packageid;
        $serverType = Product::getServerTypeById($productId);

        return in_array($serverType, $this->getAllowedOvhServers());
    }

    private function getServiceId()
    {
        $serviceId = $this->getRequestValue('productselect');
        if($serviceId)
        {
            return $serviceId;
        }

        return $this->getRequestValue('id');
    }

    private function getAllowedOvhServers()
    {
        return [
            Server\Constants::VPS,
            Server\Constants::DEDICATED,
        ];
    }
}
