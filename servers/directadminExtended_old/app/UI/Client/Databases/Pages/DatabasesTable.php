<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Databases\Pages;

use ModulesGarden\Servers\DirectAdminExtended\Core\Helper\BuildUrl;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Buttons\ButtonRedirect;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\DataTable\Column;
use ModulesGarden\Servers\DirectAdminExtended\App\Helpers\RawDataTableApi;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\DataTable\DataProviders\Providers\ArrayDataProvider;
use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Databases\Buttons;

class DatabasesTable extends RawDataTableApi implements ClientArea
{
    protected $id    = 'databasesTable';
    protected $name  = 'databasesTable';
    protected $title = 'databasesTableTitle';

    protected function loadHtml()
    {
        $this->addColumn((new Column('name'))->setSearchable(true)->setOrderable('ASC'));
    }

    public function initContent()
    {
        $download = new ButtonRedirect();
        $download
            ->initIds('downloadDatabase')
            ->setName('downloadDatabase')
            ->setIcon('icon-in-button lu-zmdi lu-zmdi-download')
            ->setRawUrl(BuildUrl::getUrl('databases', 'download'))
            ->setRedirectParams([
                'name' => ':id'
            ])
            ->addHtmlAttribute('data-toggle', 'lu-tooltip');

        $this->addButton(new Buttons\AddDatabase())
                ->addActionButton($download)
                ->addActionButton(new Buttons\DeleteDatabase())
                ->addMassActionButton(new Buttons\MassAction\DeleteDatabase());
    }

    protected function loadData()
    {
        $this->loadUserApi();
        $result   = $this->userApi->database->lists()->toArray();

        // init ID for mass action and proper sort/search work
        foreach($result as $key => $database)
        {
            $result[$key]['id'] = $database['name'];
        }
        $provider = new ArrayDataProvider();

        $provider->setData($result);
        $provider->setDefaultSorting('name', 'ASC');
        
        $this->setDataProvider($provider);
    }
}
