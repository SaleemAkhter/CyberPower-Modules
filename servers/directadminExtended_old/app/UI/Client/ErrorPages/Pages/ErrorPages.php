<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\ErrorPages\Pages;

use ModulesGarden\Servers\DirectAdminExtended\Core\ServiceLocator;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\DataTable\Column;
use ModulesGarden\Servers\DirectAdminExtended\App\Helpers\DataTableApi;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\DataTable\DataProviders\Providers\ArrayDataProvider;
use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\ErrorPages\Buttons;

class ErrorPages extends DataTableApi implements ClientArea
{
    protected $id    = 'ErrorPagesTable';
    protected $name  = 'ErrorPagesTable';
    protected $title = null;

    protected function loadHtml()
    {
        $this->addColumn((new Column('name'))->setSearchable(true)->setOrderable('ASC'))
            ->addColumn((new Column('id'))->setSearchable(true));
    }

    public function initContent()
    {



        $this->addActionButton(new Buttons\View())
            ->addActionButton(new Buttons\Edit());
    }

    public function replaceFieldName($colName, $row)
    {
        return ServiceLocator::call('lang')->absoluteTranslate($row[$colName]);
    }

    protected function loadData()
    {
        $this->loadUserApi();

        $pages = [
            ['name' => 'errorPage401', 'id' => '401.shtml'],
            ['name' => 'errorPage403', 'id' => '403.shtml'],
            ['name' => 'errorPage404', 'id' => '404.shtml'],
            ['name' => 'errorPage500', 'id' => '500.shtml']
        ];

        $provider = new ArrayDataProvider();
        $provider->setData($pages);
        $provider->setDefaultSorting('name', 'ASC');
        
        $this->setDataProvider($provider);
    }
}
