<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\Http\Client;

use ModulesGarden\Servers\DirectAdminExtended\App\Traits\DirectAdminAPIComponent;
use ModulesGarden\Servers\DirectAdminExtended\App\Traits\PackagesComponent;
use ModulesGarden\Servers\DirectAdminExtended\Core\Http\AbstractController;
use ModulesGarden\Servers\DirectAdminExtended\Core\Helper;
use ModulesGarden\Servers\DirectAdminExtended\App\Helpers\FeaturesSettingsConstants;
use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\AddonDomains\Pages\DomainLogsTabs;
use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Customs\Buttons\Breadcrumb;
define("RESELLER_PAGE",true);

class Packages extends AbstractController
{
    use \ModulesGarden\Servers\DirectAdminExtended\App\Traits\ProductsFeatureComponent,
        \ModulesGarden\Servers\DirectAdminExtended\App\Traits\ClassNameComponent,
        \ModulesGarden\Servers\DirectAdminExtended\App\Traits\BreadcrumbComponent,
        DirectAdminAPIComponent,
        PackagesComponent;

    public function index()
    {
        if ($this->isFeatureEnabled(FeaturesSettingsConstants::PACKAGE_MANAGER) === false)
        {
            return null;
        }

        $domainName = $this->getRequestValue('domain' ,false);

        if($domainName !== false)
        {
            $this->loadUserApi();
            if(in_array($domainName, $this->getDomainList())){
                return Helper\view()->addElement(DomainLogsTabs::class);
            }
        }
        return Helper\view()
                        ->addElement(new Breadcrumb($this->getClassName(), $this->getClassMethods()))
                        ->addElement(new \ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Customs\Pages\Description($this->getClassName()))
                        ->addElement(\ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Packages\Pages\PackagesTable::class);
    }
}
