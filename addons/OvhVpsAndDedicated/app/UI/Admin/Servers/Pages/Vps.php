<?php

namespace ModulesGarden\OvhVpsAndDedicated\App\UI\Admin\Servers\Pages;

use ModulesGarden\OvhVpsAndDedicated\App\Helpers\Models\Client\Constants;
use ModulesGarden\OvhVpsAndDedicated\App\Libs\Repository\Manage\CustomFields;
use ModulesGarden\OvhVpsAndDedicated\App\Libs\Traits\Widgets\Datatable\DefaultValueOnEmptyColumn;
use ModulesGarden\OvhVpsAndDedicated\App\UI\Admin\Servers\Buttons\AssignClientButton;
use ModulesGarden\OvhVpsAndDedicated\App\UI\Admin\Servers\Buttons\AssignProducts;
use ModulesGarden\OvhVpsAndDedicated\App\UI\Admin\Servers\Buttons\ReuseButton;
use ModulesGarden\OvhVpsAndDedicated\Core\UI\Interfaces\AdminArea;
use ModulesGarden\OvhVpsAndDedicated\Core\UI\Widget\DataTable\Column;
use ModulesGarden\OvhVpsAndDedicated\Core\UI\Widget\DataTable\DataProviders\DataProvider;
use ModulesGarden\OvhVpsAndDedicated\Core\UI\Widget\DataTable\DataProviders\Providers\ArrayDataProvider;
use ModulesGarden\OvhVpsAndDedicated\Core\UI\Widget\DataTable\DataTable;
use ModulesGarden\OvhVpsAndDedicated\App\UI\Admin\Servers\Providers\Vps as VpsProvider;
use ModulesGarden\OvhVpsAndDedicated\App\Helpers\Formatter;

/**
 * Class Vps
 *
 * @author Artur Pilch <artur.pi@modulesgarden.com>
 */
class Vps extends DataTable implements AdminArea
{
    use DefaultValueOnEmptyColumn;

    protected $id    = 'vpsPage';
    protected $name  = 'vpsPage';
    protected $title = null;
    protected $actionIdColumnName = 'name';

    private $clientsGroupByMachineName;

    public function __construct($baseId = null)
    {
        parent::__construct($baseId);
        $this->setColumnsNameToDefaultValue(['displayName']);
    }

    protected function loadHtml()
    {
        $this
            ->addColumn((new Column('name'))
                ->setOrderable(DataProvider::SORT_ASC)
                ->setSearchable(true))
            ->addColumn((new Column('state'))
                ->setOrderable()
                ->setSearchable(true))
            ->addColumn((new Column('displayName'))
                ->setOrderable()
                ->setSearchable(true))
            ->addColumn((new Column('client'))
                ->setOrderable()
                ->setSearchable(true))
            ->addColumn((new Column('service'))
                ->setOrderable()
                ->setSearchable(true))
            ->addColumn((new Column('reuse')));


    }

    public function customColumnHtmlReuse()
    {
        $switch = new ReuseButton('reuseSwitch');
        return $switch->getHtml();
    }

    public function replaceFieldState($key, $row)
    {
        return Formatter\State::vps($row[$key]);
    }

    public function replaceFieldClient($key, $row)
    {
        if(!$row[$key])
        {
            return '-';
        }
        $serviceName = $row['name'];
        $clientData = $this->clientsGroupByMachineName[$serviceName];
        $formatted = sprintf(Constants::CLIENT_URL_TO_SPRINTF, $clientData['id'], $clientData['id'], $clientData['firstname'] .' '. $clientData['lastname']);

        return $formatted;
    }



    public function replaceFieldService($key, $row)
    {
        if($row['client'] == '-')
        {
            return '-';
        }
        $serviceName = $row['name'];
        $clientData = $this->clientsGroupByMachineName[$serviceName];
        $formatted = sprintf(Constants::HOSTING_URL_TO_SPRINTF, $clientData['id'], $clientData['serviceId'], $clientData['serviceId'], $clientData['domain']);

        return $formatted;
    }

    public function initContent()
    {
        $this->clientsGroupByMachineName = CustomFields::getAllClientWithVpsService();

        $this->addActionButton((new AssignClientButton())->setDisableByColumnValue('client', '-'));
        $this->addActionButton((new AssignProducts()));
    }

    protected function loadData()
    {
        $vpsMachines = (new VpsProvider())->getVpsMachines($this->clientsGroupByMachineName);

        $dataProv = new ArrayDataProvider();
        $dataProv->setData($vpsMachines);
        $dataProv->setDefaultSorting('name', 'asc');
        $this->setDataProvider($dataProv);
    }


}