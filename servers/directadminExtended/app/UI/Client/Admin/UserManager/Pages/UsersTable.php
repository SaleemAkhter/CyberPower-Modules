<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Admin\UserManager\Pages;

use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Buttons\ButtonRedirect;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\DataTable\Column;
use ModulesGarden\Servers\DirectAdminExtended\App\Helpers\DataTableApi;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\DataTable\DataProviders\Providers\ArrayDataProvider;
use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Admin\UserManager\Buttons;
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
        ->addColumn((new Column('vdomains'))->setTitle('# of Domains')->setSearchable(true)->setOrderable(true))
        ->addColumn((new Column('suspended'))->setTitle('Suspended')->setSearchable(false)->setOrderable(true))
        ->addColumn((new Column('ip'))->setSearchable(true)->setOrderable(true))
        ->addColumn((new Column('domain'))->setTitle('domains')->setSearchable(true)->setOrderable(true))
        ->addColumn((new Column('sent_emails'))->setTitle('sent_emails')->setSearchable(true)->setOrderable(true))
        ->addColumn((new Column('date_created'))->setTitle('date_created')->setSearchable(true)->setOrderable(true)->setType('date'));
    }

    public function initContent()
    {
        $this->addActionButtonToDropdown($this->getLoginAsButton())
                ->addActionButtonToDropdown($this->getEditButton())
                ->addActionButtonToDropdown((new Buttons\Suspend()))
                ->addActionButtonToDropdown((new Buttons\Unsuspend()))
                ->addActionButtonToDropdown(new Buttons\Delete())
                ->addMassActionButton(new Buttons\MassAction\SuspendUnsuspend());
    }
    public function getEditButton()
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
            ->setTitle("LoginAs")
            ->setRedirectParams(['actionElementId'=>':id','doLogin'=>'1','is_user'=>':is_user']);

        return $button;
    }
    protected function getURL()
    {
        $params = [
            'action' => 'productdetails',
            'id'     =>   $this->getRequestValue('id', false),
            'modop'     => 'custom',
            'a'     => 'management',
            'mg-page'     => 'UserManager',
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
            'mg-page'     => 'UserManager',
            'mg-action'     => 'LoginAs',
        ];

        return 'clientarea.php?'. \http_build_query($params);

    }
    protected function getInfoURL($username)
    {
        $params = [
            'action' => 'productdetails',
            'id'     =>   $this->getRequestValue('id', false),
            'modop'     => 'custom',
            'a'     => 'management',
            'mg-page'     => 'UserManager',
            'mg-action'     => 'info',
            'actionElementId'=>$username
        ];

        return 'clientarea.php?'. \http_build_query($params);

    }
    public function replaceFieldUsername($key, $row)
    {
        return '<a href="'.$this->getInfoURL($row['username']).'">'.$row['username'].'</a>';

    }
    public function replaceFieldBandwidth($key, $row)
    {
        if(is_array($row['bandwidth']))
        {
            return $this->bytesToHuman($row['bandwidth']['usage']*1048576) . ' / ' . $row['bandwidth']['limit'];
        }
        return $row['bandwidth'];

    }
    public function replaceFieldDisk($key, $row)
    {
        if(is_array($row['disk']))
        {
            return $this->bytesToHuman($row['disk']['usage']*1048576) . ' / ' . $row['disk']['limit'];
        }
        return $row['disk'];

    }
    public function replaceFieldVdomains($key, $row)
    {
        if(is_array($row['vdomains']))
        {
            return $row['vdomains']['usage'] . ' / ' . $row['vdomains']['limit'];
        }
        return $row['vdomains'];

    }
    public function replaceFieldSuspended($key, $row)
    {

        return ($row['suspended']=="no")?"No":"Yes";

    }
    public function replaceFieldDate_created($key, $row)
    {

        return fromMySQLDate(date("Y-m-d H:i:s",$row['date_created']));

    }
    public function replaceFieldIp($key, $row)
    {

        return implode(" ",$row['ip']);

    }
    public function replaceFieldId($key, $row)
    {
        return $row['name'];
    }
    public function replaceFieldDomain($key, $row)
    {
        $domains='';
        foreach ($row['domain'] as $domain => $v) {
            $domains.= '<a href="http://'.$domain.'" target="_blank">'.$domain."</a>";
        }
        return $domains;

    }
// bandwidth
// disk
// usage.vdomains
// package
// suspended
// ip
// domain
// sent_emails
// date_created

    protected function loadData()
    {

        $this->loadAdminApi();

        $result     = $this->adminApi->reseller->listAllUsers();
        $users=[];
        // debug($result);die();
        foreach($result as $key=>$user){
            $users[$key]['id']=$users[$key]['username'] =$users[$key]['user'] = $user->username->value;
            $users[$key]['bandwidth']=$user->bandwidth;
            $users[$key]['is_user']=$user->username->is_user;
            $users[$key]['disk']=$user->quota;
            $users[$key]['vdomains']=$user->vdomains;
            $users[$key]['suspended']=$user->suspended->value;
            $users[$key]['ip']=$user->ip;
            $users[$key]['domain']=$user->domains;
            $users[$key]['sent_emails']=$user->sent_emails;
            $users[$key]['date_created']=$user->date_created;
        }
        $result = json_decode(json_encode($users),true);
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
    protected function bytesToHuman($bytes)
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB', 'PB'];
        for ($i = 0; $bytes > 1024; $i++) $bytes /= 1024;
        return round($bytes, 2) . ' ' . $units[$i];
    }
}
