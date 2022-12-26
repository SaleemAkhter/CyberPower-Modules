<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Backups\Pages;

use ModulesGarden\Servers\DirectAdminExtended\App\Helpers\DataTableApi;
use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Backups\Helpers\FTPBackups;
use function ModulesGarden\Servers\DirectAdminExtended\Core\Helper\sl;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\DataTable\Column;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\DataTable\DataProviders\Providers\ArrayDataProvider;
use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Backups\Buttons;

class FTPBackupsList extends DataTableApi implements ClientArea
{

    protected $id    = 'fTPBackupsTable';
    protected $name  = 'fTPBackupsTable';
    protected $title = 'fTPBackupsTableTab';

    protected $ftpBackups;
    protected $serverID;

    protected function loadHtml()
    {
        $this->addColumn((new Column('name', null, ['name']))->setSearchable(true)->setOrderable('ASC'));
    }

    public function replaceFieldId($key, $row)
    {
        return $row->file;
    }

    public function initContent()
    {
        $this->ftpBackups = new FTPBackups();
        $this->serverID = $this->getRequestValue('ftpbackup');

        if($this->ftpBackups->checkIsEnableRestore($this->serverID)){
            $this->addActionButton(new Buttons\RestoreFTP());
        }
    }
    protected function loadData()
    {
        $data = $this->ftpBackups->getFTPBackupsList($this->serverID);
        foreach ($data as $key => $item){

            $data[$key]['allData'] = base64_encode(json_encode($item));
        }

        $provider = new ArrayDataProvider();
        $provider->setData($data);
        $provider->setDefaultSorting('name', 'ASC');
        $this->setDataProvider($provider);
    }
}
