<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\Http\Client;

use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Customs\Buttons\Breadcrumb;
use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Subdomains\Pages\Logs;
use ModulesGarden\Servers\DirectAdminExtended\Core\Http\AbstractController;
use ModulesGarden\Servers\DirectAdminExtended\Core\Helper;
use ModulesGarden\Servers\DirectAdminExtended\App\Helpers\FeaturesSettingsConstants;

class Subdomains extends AbstractController
{
    use \ModulesGarden\Servers\DirectAdminExtended\App\Traits\ProductsFeatureComponent;
    use \ModulesGarden\Servers\DirectAdminExtended\App\Traits\ClassNameComponent;
    use \ModulesGarden\Servers\DirectAdminExtended\App\Traits\BreadcrumbComponent;
    
    public function index()
    {
        if ($this->isFeatureEnabled(FeaturesSettingsConstants::SUBDOMAINS) === false)
        {
            return null;
        }

        return Helper\view()
                        ->addElement(new \ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Customs\Pages\Description($this->getClassName()))
                        ->addElement(\ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Subdomains\Pages\DomainsTable::class);
    }

    public function logs()
    {
        if ($this->isFeatureEnabled(FeaturesSettingsConstants::SUBDOMAINS) === false)
        {
            return null;
        }

        return Helper\view()
            ->addElement(new Breadcrumb($this->getClassName(), $this->getClassMethods()))
            ->addElement(Logs::class);
    }
}
