<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\Http\Client;

use ModulesGarden\Servers\DirectAdminExtended\Core\Http\AbstractController;
use ModulesGarden\Servers\DirectAdminExtended\Core\Helper;
use ModulesGarden\Servers\DirectAdminExtended\App\Helpers\FeaturesSettingsConstants;
use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Customs\Buttons\Breadcrumb;
use \ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Admin\PluginManager\Pages\PluginsTable;
use \ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Admin\PluginManager\Pages\Edit;

class PluginManager extends AbstractController
{
    use \ModulesGarden\Servers\DirectAdminExtended\App\Traits\ProductsFeatureComponent;
    use \ModulesGarden\Servers\DirectAdminExtended\App\Traits\ClassNameComponent;
    use \ModulesGarden\Servers\DirectAdminExtended\App\Traits\BreadcrumbComponent;

    public function index()
    {
        return Helper\view()
            ->addElement(new Breadcrumb($this->getClassName(), $this->getClassMethods()))
            ->addElement(new \ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Customs\Pages\Description($this->getClassName()))
            ->addElement(PluginsTable::class);
    }
}
