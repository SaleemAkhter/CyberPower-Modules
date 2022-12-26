<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\Http\Client;

use ModulesGarden\Servers\DirectAdminExtended\App\Helpers\FeaturesSettingsConstants;
use ModulesGarden\Servers\DirectAdminExtended\App\Traits\DirectAdminAPIComponent;
use ModulesGarden\Servers\DirectAdminExtended\App\Traits\UserDomainComponent;
use ModulesGarden\Servers\DirectAdminExtended\Core\Http\AbstractController;
use ModulesGarden\Servers\DirectAdminExtended\Core\Helper;

class HotlinkProtection extends AbstractController
{
    use \ModulesGarden\Servers\DirectAdminExtended\App\Traits\ProductsFeatureComponent,
        \ModulesGarden\Servers\DirectAdminExtended\App\Traits\ClassNameComponent,
        DirectAdminAPIComponent;

    public function index()
    {
         if ($this->isFeatureEnabled(FeaturesSettingsConstants::HOTLINK_PROTECTION) === false) {
            return null;
        }

        return Helper\view()
            ->addElement(new \ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Customs\Pages\Description($this->getClassName()))
            ->addElement(\ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\HotlinkProtection\Pages\HotlinkTable::class);
    }

    public function hotlinkProtection()
    {
         if ($this->isFeatureEnabled(FeaturesSettingsConstants::HOTLINK_PROTECTION) === false) {
            return null;
        }

        return Helper\view()
            ->addElement(new \ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Customs\Pages\Description($this->getClassName()))
            ->addElement(\ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\HotlinkProtection\Pages\HotlinkProtection::class);
    }
}
