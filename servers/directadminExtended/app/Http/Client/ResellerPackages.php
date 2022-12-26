<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\Http\Client;

use ModulesGarden\Servers\DirectAdminExtended\App\Traits\DirectAdminAPIComponent;
use ModulesGarden\Servers\DirectAdminExtended\App\Traits\UsersComponent;
use ModulesGarden\Servers\DirectAdminExtended\Core\Http\AbstractController;
use ModulesGarden\Servers\DirectAdminExtended\Core\Helper;
use ModulesGarden\Servers\DirectAdminExtended\App\Helpers\FeaturesSettingsConstants;
use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Admin\ResellerPackages\Pages\UserEditPackage;
use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Admin\ResellerPackages\Pages\UserEditIp;
use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Admin\ResellerPackages\Pages\UserEditUsage;

use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Customs\Buttons\Breadcrumb;
use ModulesGarden\Servers\DirectAdminExtended\Core\Http\Request;
use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Redirect\Pages\RedirectPage;

define("ADMIN_PAGE",true);

class ResellerPackages extends AbstractController
{
    use \ModulesGarden\Servers\DirectAdminExtended\App\Traits\ProductsFeatureComponent,
        \ModulesGarden\Servers\DirectAdminExtended\App\Traits\ClassNameComponent,
        \ModulesGarden\Servers\DirectAdminExtended\App\Traits\BreadcrumbComponent,
        DirectAdminAPIComponent,
        UsersComponent;

    public function index()
    {
        if ($this->isFeatureEnabled(FeaturesSettingsConstants::ADMIN_MANAGE_USER_PACKAGES) === false)
        {
            return null;
        }
        return Helper\view()
                        ->addElement(new Breadcrumb($this->getClassName(), $this->getClassMethods()))
                        ->addElement(new \ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Customs\Pages\Description($this->getClassName()))
                        ->addElement(\ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Admin\ResellerPackages\Pages\PackagesTable::class);
    }

    public function add()
    {
        if ($this->isFeatureEnabled(FeaturesSettingsConstants::ADMIN_ADD_USER) === false)
        {
            return null;
        }

        return Helper\view()
                        ->addElement(new Breadcrumb($this->getClassName(), ['index','Add']))
                        ->addElement(
                            (new \ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Customs\Pages\Description($this->getClassName()))
                            ->setTitle("AddTitle")
                            ->setDescription("AddDescription")
                        )
                        ->addElement(\ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Admin\ResellerPackages\Forms\Add::class);

    }
    public function edit()
    {
        if ($this->isFeatureEnabled(FeaturesSettingsConstants::ADMIN_ADD_USER) === false)
        {
            return null;
        }

        return Helper\view()
                        ->addElement(new Breadcrumb($this->getClassName(), ['index','Edit']))
                        ->addElement(
                            (new \ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Customs\Pages\Description($this->getClassName()))
                            ->setTitle("EditTitle")
                            ->setDescription("EditDescription")
                        )
                        ->addElement(\ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Admin\ResellerPackages\Forms\Edit::class);

    }
}
