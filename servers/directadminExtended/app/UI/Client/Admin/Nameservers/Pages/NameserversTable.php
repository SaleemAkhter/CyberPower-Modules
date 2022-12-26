<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Admin\Nameservers\Pages;

use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Buttons\ButtonRedirect;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\DataTable\Column;
use ModulesGarden\Servers\DirectAdminExtended\App\Helpers\DataTableApi;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\DataTable\DataProviders\Providers\ArrayDataProvider;
use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Admin\Nameservers\Buttons;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Traits\WhmcsParams;
use function ModulesGarden\Servers\DirectAdminExtended\Core\Helper\di;
use ModulesGarden\Servers\DirectAdminExtended\Core\Lang\Lang;

class NameserversTable extends DataTableApi implements ClientArea
{
    use WhmcsParams;

    protected $id    = 'nameserversTable';
    protected $name  = 'NameserversTable';
    protected $title = null;

    protected $info = false;

    protected function loadHtml()
    {
        $this->addColumn((new Column('ip'))->setSearchable(true, Column::TYPE_STRING)->setOrderable('ASC'))
        ->addColumn((new Column('status'))->setSearchable(false)->setOrderable(false))
        ->addColumn((new Column('users'))->setSearchable(false)->setOrderable(false))
        ->addColumn((new Column('nameserver'))->setSearchable(false)->setOrderable(false));
    }

    public function initContent()
    {
        $this->addButton(new Buttons\Add())
            ->addMassActionButton(new Buttons\MassAction\Delete());
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

        $this->loadAdminApi();

        $result     = $this->adminApi->nameserver->admin_getIpListWithDetail();
        // debug($result);
        // die();
        $ipdevices=[];
        // foreach ($result->data as $ip => $data) {
        //     $ipdevices[key($ip)]=$iface;
        // }
        $rows=[];
        foreach($result->data as $ip=>$item){
            if($key=='info'){
                continue;
            }

            $reseller=(!empty($item->extra))?implode(", ",$item->extra->creators):"";
            $device=$ipdevices[$item->ip];
            $rows[]=[
                'id' =>base64_encode(json_encode(['ip'=>$ip])),
                'ip' =>$ip,
                'status' =>$item->status,
                'users' =>$item->value,
                'nameserver'=>$item->ns

            ];
        }

        $result = $this->toArray($rows);
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
