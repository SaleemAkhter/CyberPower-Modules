<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Users\Pages;

use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Buttons\ButtonRedirect;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\DataTable\Column;
use ModulesGarden\Servers\DirectAdminExtended\App\Helpers\DataTableApi;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\DataTable\DataProviders\Providers\ArrayDataProvider;
use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Users\Buttons;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Traits\WhmcsParams;
use function ModulesGarden\Servers\DirectAdminExtended\Core\Helper\di;
use ModulesGarden\Servers\DirectAdminExtended\Core\Lang\Lang;


class UsersTable extends DataTableApi implements ClientArea
{
    use WhmcsParams;

    protected $id    = 'usersTable';
    protected $name  = 'usersTable';
    protected $title = null;

    protected $info = false;

    protected function loadHtml()
    {
        $this->addColumn(
            (new Column('username'))
                //->addSearchableName('name')
                ->setSearchable(true, Column::TYPE_STRING)
                ->setOrderable('ASC')
        )
        ->addColumn((new Column('bandwidth'))->setTitle("Bandwidth")->setSearchable(true)->setOrderable(true))
        ->addColumn((new Column('disk'))->setTitle('disk usage')->setSearchable(true)->setOrderable(true)->setType('int'))
        ->addColumn((new Column('usage.vdomains'))->setTitle('# of Domains')->setSearchable(true)->setOrderable(true))
        ->addColumn((new Column('package'))->setSearchable(false)->setOrderable(true))
        ->addColumn((new Column('suspended'))->setTitle('Suspended')->setSearchable(false)->setOrderable(true))
        ->addColumn((new Column('ip'))->setSearchable(true)->setOrderable(true))
        ->addColumn((new Column('domain'))->setTitle('domains')->setSearchable(true)->setOrderable(true))
        ->addColumn((new Column('date_created'))->setTitle('date_created')->setSearchable(true)->setOrderable(true)->setType('date'));
    }

    public function initContent()
    {
        $this->addButton(new Buttons\Add())
                ->addActionButtonToDropdown($this->getLoginAsButton())
                ->addActionButtonToDropdown($this->getRedirectButton())
                ->addActionButtonToDropdown((new Buttons\Suspend()))
                ->addActionButtonToDropdown((new Buttons\Unsuspend()))
                ->addActionButtonToDropdown(new Buttons\Delete())
                ->addMassActionButton(new Buttons\MassAction\SuspendUnsuspend());
    }
    public function getRedirectButton()
    {
        $button = new ButtonRedirect('editButton');

        $button->setRawUrl($this->getURL())
            ->setIcon('')
            ->setShowTitle()
            ->setRedirectParams(['actionElementId'=>':id']);

        return $button;
    }

    public function getLoginAsButton()
    {
        $button = new ButtonRedirect('loginAsButton');

        $button->setRawUrl($this->getLoginURL())
            ->setIcon('')
            ->setShowTitle()
            ->setRedirectParams(['actionElementId'=>':id','doLogin'=>'1']);

        return $button;
    }
    protected function getURL()
    {
        $params = [
            'action' => 'productdetails',
            'id'     =>   $this->getRequestValue('id', false),
            'modop'     => 'custom',
            'a'     => 'management',
            'mg-page'     => 'users',
            'mg-action'     => 'EditUser',
        ];

        return 'clientarea.php?'. \http_build_query($params);

    }
    protected function getLoginURL()
    {
        $params = [
            'action' => 'productdetails',
            'id'     =>   $this->getRequestValue('id', false),
            'modop'     => 'custom',
            'a'     => 'management',
            'mg-page'     => 'users',
            'mg-action'     => 'LoginAs',
        ];

        return 'clientarea.php?'. \http_build_query($params);

    }
    public function replaceFieldBandwidth($key, $row)
    {
        if($row['usage'] != null)
        {
            return $row['usage']['bandwidth'] . ' / ' . $row['bandwidth'];
        }
        return $row['bandwidth'];

    }
    public function replaceFieldDisk($key, $row)
    {
        if($row['usage'] != null)
        {
            return $row['usage']['quota'] . ' / ' . $row['quota'];
        }
        return $row['quota'];

    }
    public function replaceFieldSuspended($key, $row)
    {

        return ($row['account']=="ON")?"No":"Yes";

    }

    public function replaceFieldId($key, $row)
    {
        return $row['name'];
    }
    public function replaceFieldDomain($key, $row)
    {
        return '<a href="http://'.$row['domain'].'" target="_blank">'.$row['domain']."</a>";
    }


    protected function loadData()
    {

        $this->loadResellerApi();

        $result     = $this->resellerApi->reseller->listUsers();
        foreach($result as $key=>$items){
            $result[$key]['id'] = $items['username'];
            $result[$key]['user'] = $items['username'];
        }
        $result = json_decode(json_encode($result),true);
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
