<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\EmailForwarders\Forms;

use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Forms\BaseForm;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Forms\FormConstants;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Forms\Sections;
use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\EmailForwarders\Providers;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Forms\Fields;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Forms\Fields\InputGroupElements;
use ModulesGarden\Servers\DirectAdminExtended\App\Helpers\Validator;

class Edit extends Add implements ClientArea
{
    protected $id    = 'addForm';
    protected $name  = 'addForm';
    protected $title = 'addForm';

    public function initContent()
    {
        $this->setFormType(FormConstants::UPDATE)
            ->setProvider(new Providers\EmailForwardersEdit());

        $this->addSection($this->getEmailSection())
            ->addSection($this->getDestinationSection())
            ->loadDataToForm();
    }

    protected function getEmailSection()
    {
        $loginGroup = (new Sections\InputGroup('loginGroup'))
            ->addInputComponent((new InputGroupElements\Text('nameCopy'))->disableField())
            ->addInputAddon('mail', false, '@')
            ->addInputComponent((new InputGroupElements\Select('domainsCopy'))->disableField())
            ->addField(new Fields\Hidden('name'))
            ->addField(new Fields\Hidden('domains'));

        return $loginGroup;
    }


}
