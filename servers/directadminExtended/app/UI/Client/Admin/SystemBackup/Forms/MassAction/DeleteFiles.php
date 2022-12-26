<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Admin\SystemBackup\Forms\MassAction;

use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Forms\BaseForm;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Admin\SystemBackup\Providers;

class DeleteFiles extends BaseForm implements ClientArea
{
    protected $id    = 'deleteFilesForm';
    protected $name  = 'deleteFilesForm';
    protected $title = 'deleteFilesForm';

    public function getDefaultActions()
    {
        return ['deleteMany'];
    }

    public function initContent()
    {
        $this->setFormType('deleteMany')
                ->setProvider(new Providers\File())
                ->setConfirmMessage('confirmMassFilesDelete');
    }
}
