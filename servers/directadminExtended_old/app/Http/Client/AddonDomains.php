<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\Http\Client;

use ModulesGarden\Servers\DirectAdminExtended\App\Traits\DirectAdminAPIComponent;
use ModulesGarden\Servers\DirectAdminExtended\App\Traits\UserDomainComponent;
use ModulesGarden\Servers\DirectAdminExtended\Core\Http\AbstractController;
use ModulesGarden\Servers\DirectAdminExtended\Core\Helper;
use ModulesGarden\Servers\DirectAdminExtended\App\Helpers\FeaturesSettingsConstants;
use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\AddonDomains\Pages\DomainLogsTabs;

class AddonDomains extends AbstractController
{
    use \ModulesGarden\Servers\DirectAdminExtended\App\Traits\ProductsFeatureComponent,
        \ModulesGarden\Servers\DirectAdminExtended\App\Traits\ClassNameComponent,
        DirectAdminAPIComponent,
        UserDomainComponent;
    
    public function index()
    {
        if ($this->isFeatureEnabled(FeaturesSettingsConstants::ADDON_DOMAIN) === false)
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
                        ->addElement(new \ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Customs\Pages\Description($this->getClassName()))
                        ->addElement(\ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\AddonDomains\Pages\DomainsTable::class);
    }
}
