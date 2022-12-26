<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\Http\Client;

use ModulesGarden\Servers\DirectAdminExtended\Core\Http\AbstractController;
use ModulesGarden\Servers\DirectAdminExtended\Core\Helper;
use ModulesGarden\Servers\DirectAdminExtended\App\Helpers\FeaturesSettingsConstants;
use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Customs\Buttons\Breadcrumb;
use \ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Admin\BackupTransfer\Pages\BackupSchedule;
use \ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Admin\BackupTransfer\Pages\BackupScheduleEdit;
use \ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Admin\BackupTransfer\Pages\Backup;
use \ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Admin\BackupTransfer\Pages\Restore;

class BackupTransfer extends AbstractController
{
    use \ModulesGarden\Servers\DirectAdminExtended\App\Traits\ProductsFeatureComponent;
    use \ModulesGarden\Servers\DirectAdminExtended\App\Traits\ClassNameComponent;
    use \ModulesGarden\Servers\DirectAdminExtended\App\Traits\BreadcrumbComponent;

    public function index()
    {
        return Helper\view()
            ->addElement(new Breadcrumb($this->getClassName(), $this->getClassMethods()))
            ->addElement(new \ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Customs\Pages\Description($this->getClassName()))
            ->addElement(\ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Admin\BackupTransfer\Pages\BackupsTable::class);
    }

    public function schedule()
    {
        return Helper\view()
        ->addElement(new Breadcrumb($this->getClassName(), ['index','schedule']))
        ->addElement(new \ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Customs\Pages\Description($this->getClassName()."ScheduleTitle"))
        ->addElement(BackupSchedule::class);
    }
    public function edit()
    {
        return Helper\view()
        ->addElement(new Breadcrumb($this->getClassName(), ['index','Edit']))
        ->addElement(new \ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Customs\Pages\Description($this->getClassName()."EditTitle"))
        ->addElement(BackupScheduleEdit::class);
    }
    public function backup()
    {
        return Helper\view()
        ->addElement(new Breadcrumb($this->getClassName(), ['index','backup']))
        ->addElement(new \ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Customs\Pages\Description($this->getClassName()."BackupTitle"))
        ->addElement(Backup::class);
    }
    public function restore()
    {
        return Helper\view()
        ->addElement(new Breadcrumb($this->getClassName(), ['index','restore']))
        ->addElement(new \ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Customs\Pages\Description($this->getClassName()."RestoreTitle"))
        ->addElement(Restore::class);
    }

}
