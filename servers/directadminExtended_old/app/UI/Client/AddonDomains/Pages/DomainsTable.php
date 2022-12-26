<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\AddonDomains\Pages;

use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Buttons\ButtonRedirect;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\DataTable\Column;
use ModulesGarden\Servers\DirectAdminExtended\App\Helpers\DataTableApi;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\DataTable\DataProviders\Providers\ArrayDataProvider;
use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\AddonDomains\Buttons;

class DomainsTable extends DataTableApi implements ClientArea
{
    protected $id    = 'domainsTable';
    protected $name  = 'domainsTable';
    protected $title = null;

    protected $info = false;

    protected function loadHtml()
    {
        $this->addColumn(
            (new Column('name'))
                //->addSearchableName('name')
                ->setSearchable(true, Column::TYPE_STRING)
                ->setOrderable('ASC')
        );
    }

    public function initContent()
    {
        $this->addMassActionButton(new Buttons\MassAction\Delete())
                ->addButton(new Buttons\Add())
                ->addActionButton(new Buttons\Info())
                ->addActionButton($this->getRedirectButton())
                ->addActionButton(new Buttons\Edit())
                ->addActionButton(new Buttons\SuspendUnsuspend())
                ->addActionButton(new Buttons\Delete())
                ->addMassActionButton(new Buttons\MassAction\SuspendUnsuspend());
    }

    public function getRedirectButton()
    {
        $button = new ButtonRedirect('logsButton');

        $button->setRawUrl($this->getURL())
            ->setIcon('lu-zmdi lu-zmdi-collection-text')
            ->setRedirectParams(['domain' => ':id']);

        return $button;
    }

    protected function getURL()
    {
        $params = [
            'action' => 'productdetails',
            'id'     =>   $this->getRequestValue('id', false),
            'modop'     => 'custom',
            'a'     => 'management',
            'mg-page'     => 'addonDomains',
        ];

        return 'clientarea.php?'. \http_build_query($params);

    }
    public function replaceFieldId($key, $row)
    {
        return $row['name'];
    }


    protected function loadData()
    {

        $this->loadUserApi();

        $result     = $this->userApi->domain->lists()->response;

        foreach($result as $items){
            $items->id = $items->name;
        }

        $result = $this->toArray($result);

        $provider   = new ArrayDataProvider();

        $provider->setData($result);
        $provider->setDefaultSorting('name', 'ASC');

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
