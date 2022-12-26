<?php

namespace ModulesGarden\OvhVpsAndDedicated\App\UI\Admin\Servers\Pages;

use ModulesGarden\OvhVpsAndDedicated\App\UI\Admin\Servers\Buttons\ListButton;
use ModulesGarden\OvhVpsAndDedicated\Core\Helper\BuildUrl;
use ModulesGarden\OvhVpsAndDedicated\Core\UI\Interfaces\AdminArea;
use ModulesGarden\OvhVpsAndDedicated\Core\UI\Widget\Buttons\ButtonRedirect;
use ModulesGarden\OvhVpsAndDedicated\Core\UI\Widget\DataTable\Column;
use ModulesGarden\OvhVpsAndDedicated\Core\UI\Widget\DataTable\DataProviders\DataProvider;
use ModulesGarden\OvhVpsAndDedicated\Core\UI\Widget\DataTable\DataProviders\Providers\ArrayDataProvider;
use ModulesGarden\OvhVpsAndDedicated\Core\UI\Widget\DataTable\DataTable;
use ModulesGarden\OvhVpsAndDedicated\App\Libs\Repository\Manage\Server;
use ModulesGarden\OvhVpsAndDedicated\App\UI\Admin\Servers\Helpers\Decorators;

/**
 * Class VpsPage
 *
 * @author Artur Pilch <artur.pi@modulesgarden.com>
 */
class Servers extends DataTable implements AdminArea
{
    protected $id    = 'vpsPage';
    protected $name  = 'vpsPage';
    protected $title = null;

    protected function loadHtml()
    {

        $this
            ->addColumn((new Column('id'))
                ->setOrderable(DataProvider::SORT_ASC)
                ->setSearchable(true, Column::TYPE_INT))
            ->addColumn((new Column('name'))
                ->setOrderable()
                ->setSearchable(true))
            ->addColumn((new Column('ovhServerType'))
                ->setOrderable()
                ->setSearchable(true))
        ;
    }

    /**
     * Main Content inti
     */
    public function initContent()
    {
        $whmcsServerButton = new ButtonRedirect('whmcsServer');
        $whmcsServerButton->setIcon('lu-btn__icon lu-zmdi lu-zmdi-edit')
            ->setRawUrl("configservers.php")
            ->setRedirectParams(['action' => 'manage', 'id' => ':id']);

        $redirectButton = new ListButton();
        $redirectButton->setRawUrl(BuildUrl::getUrl('home'))
            ->setRedirectParams(['serverid' => ':id', 'mg-action' => ':ovhServerType']);

        $this->addActionButton($whmcsServerButton);
        $this->addActionButton($redirectButton);
    }

    /**
     * Load Servers Data to Datatable
     */
    protected function loadData()
    {
        $servers = Server::getOvhServers();
        $servers =  empty($servers) ? [] : $servers->toArray();

        $dataProv = new ArrayDataProvider();
        $dataProv->setDefaultSorting('id', 'asc')->setData($servers);

        $this->setDataProvider($dataProv);
    }

    /**
     * Replace OVH Server Type html
     *
     * @return string
     */
    public function customColumnHtmlOvhServerType()
    {
        return Decorators\OvhServerType::columnHtmldecorate();
    }


    /**
     * @param $key
     * @param $row
     * @return string
     */
    public function replaceFieldOvhServerType($key, $row)
    {
        return Decorators\OvhServerType::decorate($row[$key]);
    }
}