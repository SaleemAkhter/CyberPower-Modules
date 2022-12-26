<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\MailingLists\Pages;

use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\DataTable\Column;
use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\MailingLists\Buttons;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\DataTable\DataProviders\Providers\ArrayDataProvider;

class NormalSubscribers extends SubscriberList implements ClientArea
{
    protected $id    = 'normalSubscribers';
    protected $name  = 'normalSubscribersName';
    protected $title = 'normalSubscribersTitle';


    protected function loadHtml()
    {
        $this->addColumn((new Column('email'))->setSearchable(true)->setOrderable('ASC'));
    }

    public function initContent()
    {
        $this->addButton(new Buttons\AddSubNormal())
            ->addActionButton(new Buttons\DeleteSub())
            ->addMassActionButton(new Buttons\MassAction\DeleteSub());
    }

    protected function loadData()
    {
        $provider    = new ArrayDataProvider();

        $provider->setData($this->generateArray('normal'));
        $provider->setDefaultSorting('email', 'ASC');

        $this->setDataProvider($provider);
    }
}