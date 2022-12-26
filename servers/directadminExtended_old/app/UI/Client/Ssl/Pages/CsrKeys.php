<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Ssl\Pages;

use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Builder\BaseContainer;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\DataTable\Column;
use ModulesGarden\Servers\DirectAdminExtended\App\Helpers\RawDataTableApi;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\DataTable\DataProviders\Providers\ArrayDataProvider;
use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Ssl\Buttons;

class CsrKeys extends BaseContainer implements ClientArea
{
    protected $id    = 'csrKeysTable';
    protected $name  = 'csrKeysTable';
    protected $title = 'csrKeysTableTitle';

    public function initContent()
    {
        $this->addButton(new Buttons\GenerateCSR());
    }
}
