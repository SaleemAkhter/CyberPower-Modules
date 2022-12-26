<?php

namespace ModulesGarden\Servers\OvhVpsAndDedicated\App\UI\Admin\Ips\Pages;


use ModulesGarden\Servers\OvhVpsAndDedicated\App\Helpers\WhmcsParams;
use ModulesGarden\Servers\OvhVpsAndDedicated\App\Traits\WhmcsParamsApp;
use ModulesGarden\Servers\OvhVpsAndDedicated\App\UI\Admin\Ips\Buttons\Reverse;
use ModulesGarden\Servers\OvhVpsAndDedicated\App\UI\Admin\Ips\Providers\IpDedicated;
use function ModulesGarden\Servers\OvhVpsAndDedicated\Core\Helper\isAdmin;
use ModulesGarden\Servers\OvhVpsAndDedicated\Core\UI\Interfaces\AdminArea;
use ModulesGarden\Servers\OvhVpsAndDedicated\Core\UI\Widget\DataTable\Column;
use ModulesGarden\Servers\OvhVpsAndDedicated\Core\UI\Widget\DataTable\DataProviders\DataProvider;
use ModulesGarden\Servers\OvhVpsAndDedicated\Core\UI\Widget\DataTable\DataProviders\Providers\ArrayDataProvider;
use ModulesGarden\Servers\OvhVpsAndDedicated\Core\UI\Widget\DataTable\DataTable;

use ModulesGarden\Servers\OvhVpsAndDedicated\App\Helpers\PageController;



/**
 * Description of ProductPage
 *
 * @author Artur Pilch <artur.pi@modulesgarden.com>
 */
class IpsListDedicated extends DataTable implements AdminArea
{
    use WhmcsParamsApp;

    protected $id    = 'ipsTable';
    protected $name  = 'ipsTable';
    protected $title = 'Ips';

    public function loadHtml()
    {
        $this->addColumn((new Column('ipAddress'))->setOrderable(DataProvider::SORT_ASC)->setSearchable(true))
            ->addColumn((new Column('version'))->setOrderable()->setSearchable(true))
            ->addColumn((new Column('mask'))->setOrderable()->setSearchable(true))
            ;

        $this->addColumn((new Column('reverse'))->setOrderable()->setSearchable(true));
    }

    public function replaceFieldVersion($key, &$row)
    {
        $row['id'] = base64_encode(\json_encode($row));

        return $row[$key];
    }

    public function initContent()
    {
        if($this->canAddReverse())
        {
            $this->addActionButton(new Reverse());
        }

    }

    protected function loadData()
    {
        $provider = new IpDedicated();
        $data = $provider->getDedicatedIps($this->isReverse());

        $dataProvider = new ArrayDataProvider();
        $dataProvider->setData($data);
        $dataProvider->setDefaultSorting('ipAddress', 'asc');
        $this->setDataProvider($dataProvider);
    }

    private function isReverse()
    {
        $pageController = new PageController($this->getAppWhmcsParams(WhmcsParams::getEssentialsKeys()));
        if (!$pageController->dedicatedClientAreaIpsReverse()) {

            return false;
        }
        return true;
    }

    private function canAddReverse()
    {
        if(!$this->isReverse() && !isAdmin())
        {
            return false;
        }

        return true;
    }

}
