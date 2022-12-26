<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\Http\Client;

use ModulesGarden\Servers\DirectAdminExtended\App\Helpers\DownloadFile;
use ModulesGarden\Servers\DirectAdminExtended\Core\Http\AbstractController;
use ModulesGarden\Servers\DirectAdminExtended\Core\Helper;
use ModulesGarden\Servers\DirectAdminExtended\App\Helpers\FeaturesSettingsConstants;
use ModulesGarden\Servers\DirectAdminExtended\Core\Http\Request;

class ProtectedDirectories extends AbstractController
{

    use \ModulesGarden\Servers\DirectAdminExtended\App\Traits\ProductsFeatureComponent;
    use \ModulesGarden\Servers\DirectAdminExtended\App\Traits\ClassNameComponent;

    public function index()
    {

        if ($this->isFeatureEnabled(FeaturesSettingsConstants::PROTECTED_DIRECTORIES) === false) {
            return null;
        }

        return Helper\view()
            ->addElement(new \ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Customs\Pages\Description($this->getClassName()))
            ->addElement(\ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\ProtectedDirectories\Pages\DomainsTable::class);
    }

    public function protectedDirectories()
    {

        if ($this->isFeatureEnabled(FeaturesSettingsConstants::PROTECTED_DIRECTORIES) === false) {
            return null;
        }

        return Helper\view()
            ->addElement(new \ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Customs\Pages\Description($this->getClassName()))->addElement(\ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\ProtectedDirectories\Pages\ProtectedDirectories::class);
    }
}
