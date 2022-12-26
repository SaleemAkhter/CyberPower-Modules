<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\CatchEmails\Buttons;


use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Buttons\ButtonDataTableModalAction;
use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\CatchEmails\Modals\Edit as Modal;


class Edit extends ButtonDataTableModalAction implements ClientArea
{
    protected $id    = 'editButton';
    protected $name  = 'editButton';
    protected $title = 'editButton';

    public function initContent()
    {
        $this->initLoadModalAction(new Modal);
    }
}