<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\DomainPointers\Pages;

use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\DataTable\Column;
use ModulesGarden\Servers\DirectAdminExtended\App\Helpers\DataTableApi;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\DataTable\DataProviders\Providers\ArrayDataProvider;
use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\DomainPointers\Buttons;
use ModulesGarden\Servers\DirectAdminExtended\App\Libs\DirectAdmin\Models;

class DomainsTable extends DataTableApi implements ClientArea
{
    protected $id    = 'domainsTable';
    protected $name  = 'domainsTable';
    protected $title = null;

    protected function loadHtml()
    {
        $this->addColumn((new Column('source'))->setSearchable(true, Column::TYPE_STRING)->setOrderable('ASC'))
            ->addColumn(new Column('domain'))
            ->addColumn(new Column('type'));
    }

    public function initContent()
    {

        $this->addButton(new Buttons\Add())
                ->addActionButton(new Buttons\Delete())
            ->addMassActionButton(new Buttons\MassAction\Delete());
    }
    public function replaceFieldType($key, $row)
    {
        return ucfirst($row[$key]);
    }

    protected function loadData()
    {
        $this->loadUserApi();

        $result = [];
        foreach ($this->getDomainList() as $domain)
        {
            $data       = [
                'domain' => $domain
            ];
            $domainPointers = $this->userApi->domainPointer->lists(new Models\Command\DomainPointer($data))->response;
            foreach ($domainPointers as $each)
            {
                $each->domain = $domain;
                $each->id = base64_encode(json_encode($each));
            }
            $result     = array_merge($result, $domainPointers);

        }
        $result = $this->toArray($result);
        $provider = new ArrayDataProvider();
        $provider->setData($result);
        $provider->setDefaultSorting('source', 'ASC');
        
        $this->setDataProvider($provider);
    }

    private function toArray($result)
    {
        $resultArray = [];
        foreach($result as $keyRow => $row)
        {
            if(is_object($row))
            {
                foreach($row as $key => $value)
                {
                    $resultArray[$keyRow][$key] = $value;
                }

                continue;
            }
            $resultArray[$keyRow] = $row;
        }

        return $resultArray;
    }
}
