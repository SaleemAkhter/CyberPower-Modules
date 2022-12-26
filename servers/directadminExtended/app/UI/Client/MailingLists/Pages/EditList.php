<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\MailingLists\Pages;

use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Customs\Pages\Tabs;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Interfaces\ClientArea;
use ModulesGarden\DirectAdminExtended\Core\Helper;

class EditList extends Tabs implements ClientArea
{
    protected $id    = 'mailingListEdit';
    protected $name  = 'mailingListEdit';
    protected $title = 'mailingListEdit';

    protected $tabs  = [
        NormalSubscribers::class,
        DigestSubscribers::class
    ];

    public function initContent()
    {
        foreach ($this->tabs as $tab)
        {
            $this->addElement(Helper\di($tab));
        }
    }
}
