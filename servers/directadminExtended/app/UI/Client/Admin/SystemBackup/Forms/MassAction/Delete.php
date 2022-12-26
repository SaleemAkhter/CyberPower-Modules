<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Admin\SystemBackup\Forms\MassAction;

use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Forms\BaseForm;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Admin\SystemBackup\Providers;

class Delete extends BaseForm implements ClientArea
{
    protected $id    = 'deleteForm';
    protected $name  = 'deleteForm';
    protected $title = 'deleteForm';

    public function getDefaultActions()
    {
        return ['deleteMany'];
    }

    public function initContent()
    {
        $this->setFormType('deleteMany')
                ->setProvider(new Providers\Directory())
                ->setConfirmMessage('confirmMassDirectoryDelete');
    }
}
