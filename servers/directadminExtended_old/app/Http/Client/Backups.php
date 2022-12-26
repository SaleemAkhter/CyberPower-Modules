<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\Http\Client;

use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Backups\Helpers\AdminBackups;
use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Backups\Helpers\FTPBackups;
use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Backups\Pages\AdminBackupsList;
use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Backups\Pages\FTPBackupsList;
use ModulesGarden\Servers\DirectAdminExtended\Core\Http\AbstractController;
use ModulesGarden\Servers\DirectAdminExtended\Core\Helper;
use ModulesGarden\Servers\DirectAdminExtended\App\Helpers\FeaturesSettingsConstants;

class Backups extends AbstractController
{
    use \ModulesGarden\Servers\DirectAdminExtended\App\Traits\ProductsFeatureComponent;
    use \ModulesGarden\Servers\DirectAdminExtended\App\Traits\ClassNameComponent;
    
    public function index()
    {
        if ($this->isFeatureEnabled(FeaturesSettingsConstants::BACKUP) === false)
        {
            return null;
        }

        if($this->getRequestValue('ftpbackup') && (new FTPBackups())->checkToShow()){
            return Helper\view()->addElement(FTPBackupsList::class);
        }
        if($this->getRequestValue('adminbackup') && (new AdminBackups())->checkToShow()){
            return Helper\view()->addElement(AdminBackupsList::class);

        }
        return Helper\view()
                        ->addElement(new \ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Customs\Pages\Description($this->getClassName()))
                        ->addElement(\ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Backups\Pages\BackupsTabs::class);
    }
}
