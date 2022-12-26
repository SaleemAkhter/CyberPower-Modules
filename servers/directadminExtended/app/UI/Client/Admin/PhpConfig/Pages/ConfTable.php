<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Admin\PhpConfig\Pages;

use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Buttons\ButtonRedirect;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\DataTable\Column;
use ModulesGarden\Servers\DirectAdminExtended\App\Helpers\DataTableApi;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\DataTable\DataProviders\Providers\ArrayDataProvider;
use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Admin\PhpConfig\Buttons;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Traits\WhmcsParams;
use function ModulesGarden\Servers\DirectAdminExtended\Core\Helper\di;
use ModulesGarden\Servers\DirectAdminExtended\Core\Lang\Lang;

class ConfTable extends DataTableApi implements ClientArea
{
    use WhmcsParams;

    protected $id    = 'confTable';
    protected $name  = 'confTable';
    protected $title = null;

    protected $info = false;

    protected function loadHtml()
    {
        $this->addColumn((new Column('domain'))->setSearchable(true, Column::TYPE_STRING)->setOrderable('ASC'))
        ->addColumn((new Column('user'))->setSearchable(false)->setOrderable(false))
        ->addColumn((new Column('openbasedir'))->setSearchable(false)->setOrderable(false));
    }

    public function initContent()
    {
        $this->addMassActionButton(new Buttons\MassAction\EnableOpenbasedir())
            ->addMassActionButton(new Buttons\MassAction\DisableOpenbasedir());
    }


    public function replaceFieldOpenbasedir($key, $row)
    {
        $class=($row['openbasedir']=="ON")?"success":"danger";
        return '<span class="label label-'.$class.'">Open BaseDir</span>';;
    }


    protected function loadData()
    {

        $this->loadAdminApi();

        $result     = $this->adminApi->phpConfig->admin_getDomains();

        $ipdevices=[];
        $rows=[];
        foreach($result->domains as $key=>$item){
            if($key=='info'){
                continue;
            }

            $rows[]=[
                'id' =>base64_encode(json_encode(['domain'=>$item->domain])),
                'domain' =>$item->domain,
                'user' =>$item->user,
                'openbasedir'=>$item->open_basedir

            ];
        }

        $result = $this->toArray($rows);
        $provider   = new ArrayDataProvider();

        $provider->setData($result);
        $provider->setDefaultSorting('domain', 'ASC');
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
