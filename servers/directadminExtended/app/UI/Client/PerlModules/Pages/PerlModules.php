<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\PerlModules\Pages;

use ModulesGarden\Servers\DirectAdminExtended\App\Traits\DirectAdminAPIComponent;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Builder\BaseContainer;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Traits;
use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Customs\Sections\ListSection;

class PerlModules extends BaseContainer implements ClientArea
{
    use Traits\Sections;
    use Traits\Title;
    use DirectAdminAPIComponent;

    protected $id = 'perlModules';

    public function initContent()
    {
        $this->setTitle('perlModulesTitle');
        $this->loadUserApi();

        $perlModulesList = $this->userApi->perlModules->lists()->first()->getList();
        $perlModulesCols = array_chunk($perlModulesList, ceil(count($perlModulesList)/3));
        foreach ($perlModulesCols as $key => $colItems)
        {
            $this->addSection((new ListSection('list' . $key))->setItems($colItems));
        }
    }
}
