<?php


namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Applications\Modals;

use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Applications\Forms;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Modals\BaseEditModal;

class StagingApp extends BaseEditModal implements ClientArea
{
    protected $id    = 'stagingAppModal';
    protected $name  = 'stagingAppModal';
    protected $title = 'stagingAppModal';


    public function initContent()
    {
        $this->addForm(new Forms\StagingApp());
    }
}