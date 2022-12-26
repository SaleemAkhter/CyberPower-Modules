<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Applications\Pages;

use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\DataTable\Column;
use ModulesGarden\Servers\DirectAdminExtended\App\Helpers\RawDataTableApi;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\DataTable\DataProviders\Providers\ArrayDataProvider;
use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Applications\Buttons;
use ModulesGarden\Servers\DirectAdminExtended\App\Helpers\ApplicationInstaller;

class BackupsTable extends RawDataTableApi implements ClientArea
{
    protected $id    = 'BackupsTable';
    protected $name  = 'BackupsTable';
    protected $title = 'BackupsTab';

    protected function loadHtml()
    {
        $this->addColumn((new Column('name'))->setSearchable(true)->setOrderable('ASC'))
                ->addColumn((new Column('domain'))->setSearchable(true)->setOrderable())
                ->addColumn(new Column('version'));
    }

    public function initContent()
    {
        $this->addActionButton(new Buttons\RestoreBackup())
                ->addActionButton(new Buttons\DeleteBackup());
    }

    protected function loadData()
    {
        logActivity("Here for backups");
        $result   = (new ApplicationInstaller($this->loadRequiredParams()))->getInstaller()->getBackups();

        $domainList = $this->getDomainList();

        foreach($result as $key => $backup)
        {
            if($backup->getDomain() && !array_key_exists($backup->getDomain(), $domainList))
            {
                unset($result[$key]);
            }
        }
        $results = $this->convertToArray($result);
        $provider = new ArrayDataProvider();

        $provider->setData($results);
        $provider->setDefaultSorting('name', 'ASC');
        
        $this->setDataProvider($provider);
    }


    private function convertToArray($data)
    {
        if (is_array($data) || is_object($data))
        {
            $result = [];
            foreach ($data as $key => $value)
            {
                $result[$key] = $this->convertToArray($value);
            }
            return $result;
        }

        return $data;
    }
}
