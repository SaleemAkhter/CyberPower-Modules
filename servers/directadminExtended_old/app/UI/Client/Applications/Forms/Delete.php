<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Applications\Forms;

use \ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Forms\BaseForm;
use \ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Forms\Fields\Hidden;
use \ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Forms\FormConstants;
use \ModulesGarden\Servers\DirectAdminExtended\Core\UI\Interfaces\ClientArea;

class Delete extends BaseForm implements ClientArea
{
    protected $id    = 'deleteForm';
    protected $name  = 'deleteForm';
    protected $title = 'deleteForm';

    public function initContent()
    {
        $this->setFormType(FormConstants::DELETE)
                ->setProvider(new \ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Applications\Providers\Applications())
                ->setConfirmMessage('confirmApplicationDelete');

        $field = new Hidden('id');

        $this->addField($field)
                ->loadDataToForm();
    }
}
