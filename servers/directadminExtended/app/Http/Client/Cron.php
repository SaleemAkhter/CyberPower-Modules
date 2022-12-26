<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\Http\Client;

use ModulesGarden\Servers\DirectAdminExtended\Core\Http\AbstractController;
use ModulesGarden\Servers\DirectAdminExtended\Core\Helper;
use ModulesGarden\Servers\DirectAdminExtended\App\Helpers\FeaturesSettingsConstants;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Helpers\AlertTypesConstants;
use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Customs\Buttons\Breadcrumb;

class Cron extends AbstractController
{
    use \ModulesGarden\Servers\DirectAdminExtended\App\Traits\ProductsFeatureComponent;
    use \ModulesGarden\Servers\DirectAdminExtended\App\Traits\ClassNameComponent,
    \ModulesGarden\Servers\DirectAdminExtended\App\Traits\BreadcrumbComponent;
    
    public function index()
    {
        if ($this->isFeatureEnabled(FeaturesSettingsConstants::CRON) === false)
        {
            return null;
        }

        return Helper\view()
                        ->addElement(new Breadcrumb($this->getClassName(), $this->getClassMethods()))
                        ->addElement(new \ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Customs\Pages\Description($this->getClassName(),true, AlertTypesConstants::WARNING))
                        ->addElement(\ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Cron\Pages\Cron::class);
    }
}
