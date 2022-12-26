<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\Http\Admin;

use ModulesGarden\Servers\DirectAdminExtended\App\UI\Admin\ProductConfiguration\Pages\ConfigForm;
use ModulesGarden\Servers\DirectAdminExtended\App\UI\Admin\ProductConfiguration\Pages\ConfigurableOptionsPage;
use ModulesGarden\Servers\DirectAdminExtended\App\UI\Admin\ProductConfiguration\Pages\CustomFieldsPage;
use ModulesGarden\Servers\DirectAdminExtended\Core\Http\AbstractController;
use ModulesGarden\Servers\DirectAdminExtended\Core\Helper;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Others\ModuleDescription;


class ProductConfiguration extends AbstractController
{
    public function index()
    {
        try
        {
            return Helper\viewIntegrationAddon()
                ->addElement(ConfigForm::class);
        }
        catch (\Exception $ex)
        {
            $details = new ModuleDescription();
            $details->setRaw(true)->setDescription($ex->getMessage())->replaceClasses(['danger']);

            return Helper\viewIntegrationAddon()
                ->addElement($details);
        }

    }
}
