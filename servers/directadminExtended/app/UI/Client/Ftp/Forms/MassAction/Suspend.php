<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Ftp\Forms\MassAction;

use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Forms\BaseForm;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Forms\Fields\Text;
use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Ftp\Providers;

class Suspend extends BaseForm implements ClientArea
{
    protected $id    = 'suspendForm';
    protected $name  = 'suspendForm';
    protected $title = 'suspendForm';

    public function getDefaultActions()
    {
        return ['suspendMany'];
    }

    public function initContent()
    {
        $this->setFormType('suspendMany')
            ->setProvider(new Providers\Ftp())
            ->setConfirmMessage('suspendMany');
    }
}