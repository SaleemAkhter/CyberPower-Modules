<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Autoresponders\Forms;

use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Forms\BaseTabsForm;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Forms\Fields;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Forms\FormConstants;
use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Autoresponders\Providers;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Forms\Sections\InputGroup;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Forms\Fields\InputGroupElements;
use ModulesGarden\Servers\DirectAdminExtended\App\Helpers\Validator;


class Add extends BaseTabsForm implements ClientArea
{
    protected $id    = 'addForm';
    protected $name  = 'addForm';
    protected $title = 'addForm';

    public function initContent()
    {
        $this->setFormType(FormConstants::CREATE)
            ->setProvider(new Providers\AutorespondersCreate());

        $address = (new InputGroup('addressSection'))
            ->addTextField('address')
            ->addInputAddon('@',false, '@')
            ->addInputComponent(new InputGroupElements\Select('domain'))
            ->setDescription('');

        $message = (new Fields\Textarea('message'))->notEmpty();
        $cc      = (new Fields\Text('cc'))->addValidator(new Validator\Email());

        $this->addSection($address)
            ->addField($message)
            ->addField($cc)
            ->loadDataToForm();
    }

}
