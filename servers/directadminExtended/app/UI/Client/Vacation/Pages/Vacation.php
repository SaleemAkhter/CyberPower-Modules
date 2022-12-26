<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Vacation\Pages;

use ModulesGarden\Servers\DirectAdminExtended\Core\Helper\Lang;
use ModulesGarden\Servers\DirectAdminExtended\Core\ServiceLocator;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\DataTable\Column;
use ModulesGarden\Servers\DirectAdminExtended\App\Helpers\DataTableApi;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\DataTable\DataProviders\Providers\ArrayDataProvider;
use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Vacation\Buttons;
use ModulesGarden\Servers\DirectAdminExtended\App\Libs\DirectAdmin\Models;

class Vacation extends DataTableApi implements ClientArea
{
    protected $id    = 'vacation';
    protected $name  = 'vacation';
    protected $title = null;


    protected function loadHtml()
    {
        $this->addColumn((new Column('id'))->setSearchable(true)->setOrderable('ASC'))
            ->addColumn(new Column('start'))
            ->addColumn(new Column('end'));
    }

    public function initContent()
    {
        $this->addButton(new Buttons\Add())
            ->addActionButton(new Buttons\Edit())
            ->addActionButton(new Buttons\Delete())
        ->addMassActionButton(new Buttons\MassAction\Delete());
    }

    public function replaceFieldStart($colName, $row)
    {

        return $row['startday'] . '/' . $row['startmonth'] . '/' . $row['startyear'] . ' ' . ServiceLocator::call('lang')->absoluteTranslate($row['starttime']);
    }

    public function replaceFieldEnd($colName, $row)
    {
        return $row['endday'] . '/' . $row['endmonth'] . '/' . $row['endyear'] . ' ' . ServiceLocator::call('lang')->absoluteTranslate($row['endtime']);
    }

    protected function loadData()
    {
        $this->loadUserApi();

        $result = [];
        foreach ($this->getDomainList() as $domain)
        {
            $data     = [
                'domain' => $domain
            ];
            $response = $this->userApi->vacation->lists(new Models\Command\Vacation($data))->toArray();
            foreach ($response as $key => $each)
            {
                $response[$key]['id'] = $each['user'] . '@'. $domain;
            }
            $result = array_merge($result, $response);
        }
        $provider = new ArrayDataProvider();
        $provider->setData($result);
        $provider->setDefaultSorting('id', 'ASC');
        
        $this->setDataProvider($provider);
    }
}
