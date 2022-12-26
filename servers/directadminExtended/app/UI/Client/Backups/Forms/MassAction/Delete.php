<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Backups\Forms\MassAction;

use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Forms\BaseForm;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Backups\Providers;

class Delete extends BaseForm implements ClientArea
{
    protected $id    = 'deleteForm';
    protected $name  = 'deleteForm';
    protected $title = 'deleteForm';
    
    public function getDefaultActions()
    {
        return ['deleteMassive'];
    }

    public function initContent()
    {
        $this->setFormType('deleteMassive')
                ->setProvider(new Providers\Backups())
                ->setConfirmMessage('confirmMassBackupDelete');
    }
}
