<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Cron\Forms\MassAction;

use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Forms\BaseForm;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Forms\Fields;
use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Cron\Providers;

class Delete extends BaseForm implements ClientArea
{
    protected $id    = 'deleteForm';
    protected $name  = 'deleteForm';
    protected $title = 'deleteForm';

    public function initContent()
    {
        $this->addDefaultActions('massDelete')
            ->setFormType('massDelete')
            ->setProvider(new Providers\Cron())
            ->setConfirmMessage('deleteCronJob');
    }
}
