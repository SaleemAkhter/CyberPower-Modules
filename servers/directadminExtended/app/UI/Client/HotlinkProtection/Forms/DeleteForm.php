<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\HotlinkProtection\Forms;

use \ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Forms\BaseForm;
use \ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Forms\Fields\Hidden;
use \ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Forms\FormConstants;
use \ModulesGarden\Servers\DirectAdminExtended\Core\UI\Interfaces\ClientArea;
use \ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\HotlinkProtection\Providers;

class DeleteForm extends BaseForm implements ClientArea
{
    protected $id    = 'deleteForm';
    protected $name  = 'deleteForm';
    protected $title = 'deleteForm';

    public function initContent()
    {
        $this->setFormType(FormConstants::DELETE)
            ->setProvider(new Providers\DeleteUrlProvider())
            ->setConfirmMessage('confirmUrlDelete');

        $this->addField(new Hidden('id'))
            ->loadDataToForm();
    }
}
