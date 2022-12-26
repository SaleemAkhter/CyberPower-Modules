<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Backups\Pages;

use ModulesGarden\DirectAdminExtended\App\Models\BackupPath;
use ModulesGarden\DirectAdminExtended\App\Models\FTPEndPoints;
use ModulesGarden\Servers\DirectAdminExtended\App\Helpers\RawDataTableApi;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Traits\WhmcsParams;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Buttons\ButtonRedirect;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\DataTable\Column;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\DataTable\DataProviders\Providers\QueryDataProvider;

class FTPBackupsTable extends RawDataTableApi implements ClientArea
{
    use WhmcsParams;

    protected $id    = 'fTPBackupsTable';
    protected $name  = 'fTPBackupsTable';
    protected $title = 'fTPBackupsTableTab';

    protected function loadHtml()
    {
        $this->addColumn((new Column('name', null, ['name']))->setSearchable(true)->setOrderable('ASC'));
    }

    public function initContent()
    {
        $this->addActionButton($this->getButtonRedirect());
    }

    protected  function getButtonRedirect(){

        $button = new ButtonRedirect();

        $button->setIcon('lu-icon-in-button lu-zmdi lu-zmdi-eye')
            ->setRawUrl($this->getURL())
            ->setRedirectParams(['ftpbackup' => ':id']);
        return $button;
    }

    protected function getURL(){
        $params = [
            'action'      => 'productdetails',
            'id'          => $_GET['id'], //TODO - $this->>getReuest();
            'modop'       => 'custom',
            'a'           => 'management',
            'mg-page'     => 'backups',
        ];

        return 'clientarea.php?'. \http_build_query($params);
    }

    protected function loadData()
    {

        $backups = (new FTPEndPoints())->where([
            'server_id' => $this->getWhmcsParamByKey('serverid'),
            'product_id' => $this->getWhmcsParamByKey('packageid'),
        ])->getQuery();

        $provider = new QueryDataProvider();
        $provider->setData($backups);
        $provider->setDefaultSorting('name', 'ASC');
        $this->setDataProvider($provider);


    }
}
