<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\MailingLists\Buttons;

use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Buttons\ButtonRedirect;

class View extends ButtonRedirect implements ClientArea
{
    protected $id    = 'edit';
    protected $name  = 'edit';
    protected $title = 'edit';
    protected $icon           = 'lu-btn__icon lu-zmdi lu-zmdi-edit';

}
