<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\EmailForwarders\Forms;

use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Customs\Sections\FormGroupSection;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Forms\BaseForm;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Forms\FormConstants;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Forms\Sections;
use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\EmailForwarders\Providers;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Forms\Fields;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Forms\Fields\InputGroupElements;

class Add extends BaseForm implements ClientArea
{
    protected $id    = 'addForm';
    protected $name  = 'addForm';
    protected $title = 'addForm';

    public function initContent()
    {
        $this->setFormType(FormConstants::CREATE)
                ->setProvider(new Providers\EmailForwardersCreate());

        $this->addSection($this->getEmailSection())
                ->addSection($this->getDestinationSection())
                ->loadDataToForm();
    }

    protected function getEmailSection()
    {
        $loginGroup = (new Sections\InputGroup('loginGroup'))
            ->addTextField('name')
            ->addInputAddon('mail', false, '@')
            ->addInputComponent(new InputGroupElements\Select('domains'))
            ->setDescription('emailDescription');

        return $loginGroup;
    }

    protected function getDestinationSection()
    {
        $destinationSections = new FormGroupSection('destinationSections');
        $destinationSections->addField((new Fields\Text('destination'))->setDescription('description')->notEmpty());
        return $destinationSections;
    }
}
