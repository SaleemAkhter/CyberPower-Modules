<?php
/**
 * Created by PhpStorm.
 * User: Mateusz Pawłowski
 * Date: 2018-10-11
 * Time: 10:44
 */

namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Backups\Pages;
use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Backups\Helpers\AdminBackups;
use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Backups\Helpers\FTPBackups;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\DirectAdminExtended\Core\Helper;
use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Customs\Pages\Tabs;

class BackupsTabs extends Tabs implements ClientArea
{

    protected $id    = 'backupsTabs';
    protected $name  = 'backupsTabs';
    protected $title = 'backupsTabs';

    protected $tabs  = [
        BackupsTable::class,
    ];

    public function initContent()
    {
        if((new AdminBackups())->isEnable())
        {
            $this->addTab(AdminBackupsTable::class);
        }
        if((new FTPBackups())->isEnable())
        {
            $this->addTab(FTPBackupsTable::class);
        }

        foreach ($this->tabs as $tab)
        {

            $this->addElement(Helper\di($tab));
        }
    }



    public function addTab($class){
        $this->tabs[] = $class;
    }

}