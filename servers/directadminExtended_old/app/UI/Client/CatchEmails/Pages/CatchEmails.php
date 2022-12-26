<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\CatchEmails\Pages;

use ModulesGarden\Servers\DirectAdminExtended\App\Helpers\DataTableApi;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\DataTable\Column;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\DataTable\DataProviders\Providers\ArrayDataProvider;
use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\CatchEmails\Buttons\Edit;

class CatchEmails extends DataTableApi implements ClientArea
{
    protected $id    = 'catchEmailsTable';
    protected $name  = 'catchEmailsTable';
    protected $title = 'catchEmailsTableTitle';


    public function loadHtml()
    {
        $this->addColumn((new Column('domain'))->setSearchable(true)->setOrderable('ASC'));
    }

    public function initContent()
    {
        $this->addActionButton(new Edit());
    }

    public function loadData()
    {
        $result = [];
        $array = [];
        foreach( $this->getDomainList() as $elem => $value)
        {
            $result['domain'] = $value;
            $result['id'] = $value;
            $array[] = $result;
        }
        $provider = new ArrayDataProvider();
        $provider->setData($array);
        $provider->setDefaultSorting('id', 'ASC');
        $this->setDataProvider($provider);
    }
}