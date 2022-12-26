<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\ApacheHandlers\Forms\MassAction;

use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Forms\BaseForm;
use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\ApacheHandlers\Providers;

class Delete extends BaseForm implements ClientArea
{
    protected $id    = 'massDeleteForm';
    protected $name  = 'massDeleteForm';
    protected $title = 'massDeleteForm';

    public function getDefaultActions()
    {
        return ['deleteMany'];
    }

    public function initContent()
    {
        $this->setFormType('deleteMany')
            ->setProvider(new Providers\ApacheHandlers())
            ->setConfirmMessage('deleteMany');
    }
}