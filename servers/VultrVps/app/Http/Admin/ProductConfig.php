<?php

namespace ModulesGarden\Servers\VultrVps\App\Http\Admin;

use ModulesGarden\Servers\VultrVps\App\UI\Admin\ProductConfig\Pages\ConfigForm;
use ModulesGarden\Servers\VultrVps\Core\Helper;
use ModulesGarden\Servers\VultrVps\Core\Http\AbstractController;
use ModulesGarden\Servers\VultrVps\Core\UI\Helpers\AlertTypesConstants;
use ModulesGarden\Servers\VultrVps\Core\UI\Widget\Forms\Sections\RawSection;
use ModulesGarden\Servers\VultrVps\Packages\WhmcsService\UI\ConfigurableOption\OptionsWidget;

/**
 * ProductConfig page controller
 * @author Sławomir Miśkowicz <slawomir@modulesgarden.com>
 */
class ProductConfig extends AbstractController
{

    /**
     *
     * @return \ModulesGarden\Servers\VultrVps\Core\UI\ViewIntegrationAddon
     */
    public function index()
    {

        try{
            return Helper\viewIntegrationAddon()->addElement(ConfigForm::class)
                ->addElement(OptionsWidget::class);
        }catch (\Exception $ex){
            $info = new RawSection();
            $info->setInternalAlertMessage($ex->getMessage());
            $info->setInternalAlertMessageType(AlertTypesConstants::DANGER);
            return Helper\viewIntegrationAddon()->addElement($info);
        }

    }
}
