<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\MessageSystem\Pages;

use ModulesGarden\Servers\DirectAdminExtended\App\Traits\DataTableDropdownActionButtons;
use ModulesGarden\Servers\DirectAdminExtended\Core\Helper\BuildUrl;
use function ModulesGarden\Servers\DirectAdminExtended\Core\Helper\sl;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\DataTable\Column;
use ModulesGarden\Servers\DirectAdminExtended\App\Helpers\DataTableApi;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\DataTable\DataProviders\Providers\ArrayDataProvider;
use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\MessageSystem\Buttons;
use ModulesGarden\Servers\DirectAdminExtended\App\Libs\DirectAdmin\Models;

class MessageSystem extends DataTableApi implements ClientArea
{
    use DataTableDropdownActionButtons;

    protected $id    = 'messageSystem';
    protected $name  = 'messageSystem';
    protected $title = null;

    protected function loadHtml()
    {
        $this->addColumn((new Column('id'))->setSearchable(true)->setOrderable('ASC'))
                ->addColumn((new Column('subject'))->setOrderable())
                ->addColumn((new Column('received'))->setOrderable());
    }
    public function getRedirectButton()
    {
        $button = new ButtonRedirect('editButton');

        $button->setRawUrl($this->getURL())
            ->setIcon('lu-btn__icon lu-zmdi lu-zmdi-edit')
            ->setRedirectParams(['actionElementId'=>':id']);

        return $button;
    }
    protected function getURL($row)
    {
        $params = [
            'action' => 'productdetails',
            'id'     =>   $this->getRequestValue('id', false),
            'modop'     => 'custom',
            'a'     => 'management',
            'mg-page'     => 'MessageSystem',
            'mg-action'     => 'ShowMessage',
            'actionElementId'=>$row['message']

        ];

        return 'clientarea.php?'. \http_build_query($params);

    }
    public function initContent()
    {
        $this->addMassActionButton((new Buttons\MassAction\MarkRead()))
            ->addMassActionButton(new Buttons\MassAction\Delete());
    }

    protected function loadData()
    {
        $result = [];
        if($this->getWhmcsParamByKey('producttype')  == "reselleraccount" ||  $this->getWhmcsParamByKey('producttype')  == "server")
        {
            $this->loadResellerApi([],false);
            $messages=$this->resellerApi->messageSystem->list();
            $limit=$messages->info;
            unset($messages->info);
            $result=json_decode(json_encode($messages),true);
        }else{
            $this->loadUserApi();
            $result=[];
        }



        $provider   = new ArrayDataProvider();

        $provider->setData($result);

        $provider->setDefaultSorting('message', 'ASC');

        $this->setDataProvider($provider);
    }
    public function replaceFieldId($key, $row)
    {
        if($row['message'] != null)
        {
            return $row['message'];
        }
        return $row['message'];

    }
    public function replaceFieldSubject($key, $row)
    {
        if($row['subject'] != null && $row['new']=="yes")
        {
            $url=$this->getURL($row);

            return "<a href='{$url}'><strong class='lu-bold'><i class='unread lu-zmdi lu-zmdi-circle'></i>{$row['subject']}</strong></a>";
        }
        $url=$this->getURL($row);
        return "<a href='{$url}'>{$row['subject']}</a>";

    }
    public function replaceFieldReceived($key, $row)
    {
        if($row['last_message'] != null)
        {
            return fromMySQLDate(date("Y-m-d H:i:s",$row['last_message']),true);
        }
        return $row['last_message'];

    }
}
