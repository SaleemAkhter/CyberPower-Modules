<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Backups\Pages;

use ModulesGarden\Servers\DirectAdminExtended\App\Helpers\DataTableApi;
use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Backups\Helpers\AdminBackups;
use function ModulesGarden\Servers\DirectAdminExtended\Core\Helper\sl;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\DataTable\Column;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\DataTable\DataProviders\Providers\ArrayDataProvider;
use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Backups\Buttons;
use ModulesGarden\Servers\DirectAdminExtended\App\Libs\DirectAdmin\Models;

class AdminBackupsList extends DataTableApi implements ClientArea
{
    protected $id    = 'adminBackupsList';
    protected $name  = 'adminBackupsList';
    protected $title = 'adminBackupsList';

    protected $adminBackups;
    protected $serverID;

    protected function loadHtml()
    {
        $this->addColumn((new Column('name', null, ['name']))->setSearchable(true)->setOrderable('ASC'));
    }
    public function initContent()
    {
        $this->adminBackups = new AdminBackups($this->whmcsParams);
        $this->serverID = sl('request')->get('adminbackup');

        if($this->adminBackups->checkIsEnableRestore($this->serverID)){
            $this->addActionButton(new Buttons\RestoreLocal());
        }
    }

    protected function loadData()
    {

        $data = $this->adminBackups->getLocalBackupsList($this->serverID);

        foreach ($data as $key => $item){

            $data[$key]['allData'] = base64_encode(json_encode($item));
        }
        $provider = new ArrayDataProvider();
        $provider->setData($data);
        $provider->setDefaultSorting('name', 'ASC');
        $this->setDataProvider($provider);

    }
}
