<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\ProtectedDirectories\Forms;

use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Forms\BaseForm;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Forms\Fields\Hidden;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Forms\FormConstants;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\ProtectedDirectories\Providers;

class Delete extends BaseForm implements ClientArea
{
    protected $id    = 'deleteForm';
    protected $name  = 'deleteForm';
    protected $title = 'deleteForm';

    public function initContent()
    { 
        $this->setFormType('delete')
            ->setProvider(new Providers\ProtectedDirectories())
            ->setConfirmMessage('confirmPdDelete');

        $this->addField(new Hidden('name'))
            ->addField(new Hidden('path'))
            ->loadDataToForm();
    }
}
