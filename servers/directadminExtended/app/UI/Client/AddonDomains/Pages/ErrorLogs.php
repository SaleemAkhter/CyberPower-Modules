<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\AddonDomains\Pages;

use ModulesGarden\Servers\DirectAdminExtended\App\Helpers\RawDataTableApi;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Traits\RequestObjectHandler;
use ModulesGarden\Servers\DirectAdminExtended\App\Libs\DirectAdmin\Models\Command\Domain;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\DataTable\Column;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\DataTable\DataProviders\Providers\ArrayDataProvider;


class ErrorLogs extends RawDataTableApi implements ClientArea
{
    protected $id    = 'errorLogs';
    protected $name  = 'errorLogs';
    protected $title = 'errorLogsTab';

    protected $info = false;

    protected function loadHtml()
    {
        $this->addColumn(
            (new Column('logs'))
                //->addSearchableName('name')
                ->setSearchable(true, Column::TYPE_STRING)
                ->setOrderable('DESC')
        );
    }

    public function replaceFieldId($key, $row)
    {
        return $row['name'];
    }


    protected function loadData()
    {

        $this->loadUserApi();

       $result = $this->userApi->domain->getLogs(new Domain([
            'name'  => $this->getRequestValue('domain', false)
        ]));


        $provider = new ArrayDataProvider();

        $provider->setData($this->toArray(array_reverse($result->getLogs())));
        $provider->setDefaultSorting('name', 'DESC');

        $this->setDataProvider($provider);
    }

    private function toArray($result)
    {
        $resultArray = [];
        foreach($result as $keyRow => $row)
        {
            $resultArray[$keyRow]['logs'] = $row;
        }
        return $resultArray;
    }
}
