<?php

namespace ModulesGarden\Servers\HetznerVps\App\Http\Admin\Server;

use Exception;
use ModulesGarden\Servers\HetznerVps\App\UI\Configuration\Pages\CronInformation;
use ModulesGarden\Servers\HetznerVps\Core\Helper;
use ModulesGarden\Servers\HetznerVps\Core\UI\Helpers\AlertTypesConstants;
use ModulesGarden\Servers\HetznerVps\Core\UI\Traits\RequestObjectHandler;
use ModulesGarden\Servers\HetznerVps\Core\UI\Widget\Forms\Sections\BaseSection;
use ModulesGarden\Servers\HetznerVps\Core\UI\Widget\Forms\Sections\RawSection;
use ModulesGarden\Servers\HetznerVps\Core\UI\Widget\Others\InfoWidget;
use ModulesGarden\Servers\HetznerVps\Packages\WhmcsService\UI\ConfigurableOption\OptionsWidget;
use function ModulesGarden\Servers\HetznerVps\Core\Helper\sl;

/**
 * Description of Clientsservices
 *
 * @author Mateusz Pawłowski <mateusz.pa@modulesgarden.com>
 */
class Product
{
    use RequestObjectHandler;

    public function index()
    {
        $productId = $this->getRequestValue("id");
        $product = \ModulesGarden\Servers\HetznerVps\App\Models\Whmcs\Product::where("id", $productId)->firstOrFail();
        sl("whmcsParams")->setParams($product->getParams());
        try {
            return Helper\viewIntegrationAddon()
                ->addElement(CronInformation::class)
                ->addElement(\ModulesGarden\Servers\HetznerVps\App\UI\Configuration\Pages\Form::class)
                ->addElement(\ModulesGarden\Servers\HetznerVps\App\UI\Configuration\Pages\Features::class)
                ->addElement(new OptionsWidget())
                ;
        } catch (Exception $ex) {
            $info = new RawSection();
            $info->setInternalAlertMessage($ex->getMessage());
            $info->setInternalAlertMessageRaw(true);
            $info->setInternalAlertMessageType(AlertTypesConstants::DANGER);
            return Helper\viewIntegrationAddon()->addElement($info);
        }
    }
}
