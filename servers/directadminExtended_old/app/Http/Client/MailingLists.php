<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\Http\Client;

use ModulesGarden\Servers\DirectAdminExtended\Core\Http\AbstractController;
use ModulesGarden\Servers\DirectAdminExtended\Core\Helper;
use ModulesGarden\Servers\DirectAdminExtended\App\Helpers\FeaturesSettingsConstants;
use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Customs\Buttons\Breadcrumb;

class MailingLists extends AbstractController
{
    use \ModulesGarden\Servers\DirectAdminExtended\App\Traits\ProductsFeatureComponent;
    use \ModulesGarden\Servers\DirectAdminExtended\App\Traits\ClassNameComponent;
    use \ModulesGarden\Servers\DirectAdminExtended\App\Traits\BreadcrumbComponent;

    public function index()
    {
        if ($this->isFeatureEnabled(FeaturesSettingsConstants::MAILING_LISTS) === false)
        {
            return null;
        }

        return Helper\view()
                        ->addElement(new \ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Customs\Pages\Description($this->getClassName()))
                        ->addElement(\ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\MailingLists\Pages\MailingLists::class);
    }

    public function edit()
    {
        if ($this->isFeatureEnabled(FeaturesSettingsConstants::MAILING_LISTS) === false)
        {
            return null;
        }

        return Helper\view()
            ->addElement(new \ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Customs\Pages\Description($this->getClassName()))
            ->addElement(new Breadcrumb($this->getClassName(), $this->getClassMethods()))
            ->addElement(\ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\MailingLists\Pages\EditList::class);
    }
}
