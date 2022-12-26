<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\Http\Client;

use ModulesGarden\Servers\DirectAdminExtended\Core\Http\AbstractController;
use ModulesGarden\Servers\DirectAdminExtended\Core\Helper;
use ModulesGarden\Servers\DirectAdminExtended\App\Helpers\FeaturesSettingsConstants;
use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Customs\Buttons\Breadcrumb;
use \ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Admin\SystemBackup\Pages\BackupEdit;
use \ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Admin\SystemBackup\Pages\BackupDirectory;
use \ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Admin\SystemBackup\Pages\BackupFiles;

class SystemBackup extends AbstractController
{
    use \ModulesGarden\Servers\DirectAdminExtended\App\Traits\ProductsFeatureComponent;
    use \ModulesGarden\Servers\DirectAdminExtended\App\Traits\ClassNameComponent;
    use \ModulesGarden\Servers\DirectAdminExtended\App\Traits\BreadcrumbComponent;

    public function index()
    {
        return Helper\view()
            ->addElement(new Breadcrumb($this->getClassName(), $this->getClassMethods()))
            ->addElement(new \ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Customs\Pages\Description($this->getClassName()))
            ->addElement(BackupEdit::class);
    }

    public function backupDirectory()
    {
        return Helper\view()
            ->addElement(new Breadcrumb($this->getClassName(), ['index']))
            ->addElement(new \ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Customs\Pages\Description($this->getClassName()))
            ->addElement(BackupDirectory::class);
    }

    public function backupFiles()
    {
        return Helper\view()
            ->addElement(new Breadcrumb($this->getClassName(), ['index']))
            ->addElement(new \ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Customs\Pages\Description($this->getClassName()))
            ->addElement(BackupFiles::class);
    }

}
