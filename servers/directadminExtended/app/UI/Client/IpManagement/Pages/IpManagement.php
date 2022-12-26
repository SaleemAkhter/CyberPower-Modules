<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\IpManagement\Pages;

use ModulesGarden\Servers\DirectAdminExtended\App\Traits\DataTableDropdownActionButtons;
use ModulesGarden\Servers\DirectAdminExtended\Core\Helper\BuildUrl;
use function ModulesGarden\Servers\DirectAdminExtended\Core\Helper\sl;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\DataTable\Column;
use ModulesGarden\Servers\DirectAdminExtended\App\Helpers\DataTableApi;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\DataTable\DataProviders\Providers\ArrayDataProvider;
use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\IpManagement\Buttons;
use ModulesGarden\Servers\DirectAdminExtended\App\Libs\DirectAdmin\Models;
use ModulesGarden\Servers\DirectAdminExtended\App\Libs\DirectAdmin\DirectAdmin;
use function \ModulesGarden\Servers\DirectAdminExtended\Core\Helper\di;

class IpManagement extends DataTableApi implements ClientArea
{
    use DataTableDropdownActionButtons;

    protected $id    = 'ipManagement';
    protected $name  = 'ipManagement';
    protected $title = null;

    protected function loadHtml()
    {
        // ->setSearchable(true, Column::TYPE_STRING)
        $this->addColumn((new Column('address'))->setSearchable(true,Column::TYPE_STRING)->setOrderable('ASC'))
                ->addColumn((new Column('status'))->setSearchable(true)->setOrderable())
                ->addColumn((new Column('value'))->setSearchable(true)->setOrderable())
                ->addColumn((new Column('ns'))->setSearchable(true)->setOrderable());
    }

    protected function getURL($row)
    {
        $params = [
            'action' => 'productdetails',
            'id'     =>   $this->getRequestValue('id', false),
            'modop'     => 'custom',
            'a'     => 'management',
            'mg-page'     => 'IpManagement',
            'mg-action'     => 'ShowMessage',
            'actionElementId'=>$row['message']

        ];

        return 'clientarea.php?'. \http_build_query($params);

    }
    public function initContent()
    {
        $this->addMassActionButton((new Buttons\MassAction\Share()))
            ->addMassActionButton(new Buttons\MassAction\Free());
    }

    protected function loadData()
    {
        $result = [];
        if($this->getWhmcsParamByKey('producttype')  == "reselleraccount" )
        {
            $this->loadUserApi();
            $result=$this->userApi->Ip->getIpListWithDetail();
        }else{
            $this->loadUserApi();
            $result=[];
        }

        $provider   = new ArrayDataProvider();

        $provider->setData($result);

        $provider->setDefaultSorting('address', 'ASC');

        $this->setDataProvider($provider);
    }
    public function replaceFieldId($key, $row)
    {
        if($row['address'] != null)
        {
            return $row['address'];
        }
        return $row['address'];

    }

}
