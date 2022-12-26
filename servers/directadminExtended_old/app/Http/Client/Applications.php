<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\Http\Client;

use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Redirect\Pages\RedirectPage;
use ModulesGarden\Servers\DirectAdminExtended\Core\Http\AbstractController;
use ModulesGarden\Servers\DirectAdminExtended\Core\Helper;
use ModulesGarden\Servers\DirectAdminExtended\App\Helpers\FeaturesSettingsConstants;
use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Applications\Providers;

class Applications extends AbstractController
{

    use \ModulesGarden\Servers\DirectAdminExtended\App\Traits\ProductsFeatureComponent;
    use \ModulesGarden\Servers\DirectAdminExtended\App\Traits\ClassNameComponent;

    public function index()
    {
        if ($this->isFeatureEnabled(FeaturesSettingsConstants::INSTALL_APPS) === false) {
            return null;
        }

        return Helper\view()
            ->addElement(new \ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Customs\Pages\Description($this->getClassName()))
            ->addElement(\ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Applications\Pages\Applications::class);
    }

    public function wpLogin()
    {
        $wp = new Providers\Wordpress();
        $url = $wp->redirect();
        header('Location: ' . $url);
        exit();
    }
}
