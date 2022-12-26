<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Ssl\Pages;

use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\DirectAdminExtended\Core\Helper;
use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Customs\Pages\Tabs;

class Ssl extends Tabs implements ClientArea
{
    protected $id    = 'sslPage';
    protected $name  = 'sslPage';
    protected $title = 'sslPage';

    protected $tabs  = [
        Certificates::class,
        PrivateKeys::class,
        CsrKeys::class,
    ];

    public function initContent()
    {
        foreach ($this->tabs as $tab)
        {
            $this->addElement(Helper\di($tab));
        }
    }
}
