<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Packages\Pages;

use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Buttons\ButtonRedirect;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\DataTable\Column;
use ModulesGarden\Servers\DirectAdminExtended\App\Helpers\DataTableApi;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\DataTable\DataProviders\Providers\ArrayDataProvider;
use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Packages\Buttons;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Traits\WhmcsParams;
use function ModulesGarden\Servers\DirectAdminExtended\Core\Helper\di;
use ModulesGarden\Servers\DirectAdminExtended\Core\Lang\Lang;

class PackagesTable extends DataTableApi implements ClientArea
{
    use WhmcsParams;

    protected $id    = 'packagesTable';
    protected $name  = 'packagesTable';
    protected $title = null;

    protected $info = false;

    protected function loadHtml()
    {
        $this->addColumn((new Column('name'))->setTitle("Package")->setSearchable(true, Column::TYPE_STRING)->setOrderable('ASC'))
        ->addColumn((new Column('bandwidth'))->setTitle("Bandwidth (MB)")->setSearchable(false)->setOrderable(false))
        ->addColumn((new Column('quota'))->setTitle('Disk Usage (MB)')->setSearchable(false)->setOrderable(false));
    }

    public function initContent()
    {
        $this->addButton(new Buttons\Add())
                ->addActionButton(new Buttons\Edit())
                ->addActionButton(new Buttons\Delete());
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

        $this->loadResellerApi();

        $result     = $this->resellerApi->resellerPackage->list();
        foreach($result as $items){
            $items['id'] = $items['username'];
        }

        $result = $this->toArray($result);
        $provider   = new ArrayDataProvider();

        $provider->setData($result);
        $provider->setDefaultSorting('username', 'ASC');
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
    protected function formatLimitValue($value)
    {
        if((int) $value > 0 || empty($value))
        {
            return $value;
        }

        return di(Lang::class)->absoluteTranslate('unlimited');
    }

    protected function toMegabyte($number)
    {
        if(is_numeric($number))
        {
            return round($number / 1024 / 1024, 3);
        }
        return $number;
    }
}
