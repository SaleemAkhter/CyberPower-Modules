<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Autoresponders\Forms;

use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Forms\BaseForm;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Forms\FormConstants;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Forms\Fields;
use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Autoresponders\Providers;
use ModulesGarden\Servers\DirectAdminExtended\App\Helpers\Validator;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Forms\Fields\InputGroupElements;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Forms\Sections\InputGroup;

class Edit extends BaseForm implements ClientArea
{
    protected $id    = 'editForm';
    protected $name  = 'editForm';
    protected $title = 'editForm';

    public function initContent()
    {
        $this->setFormType(FormConstants::UPDATE)
            ->setProvider(new Providers\AutorespondersModify());

        $address = (new InputGroup('addressSection'))
            ->addInputComponent((new InputGroupElements\Text('addressCopy'))->disableField())
            ->addInputAddon('@',false, '@')
            ->addInputComponent((new InputGroupElements\Select('domainCopy'))->disableField())
            ->setDescription('');


        $hiddenAddress = new Fields\Hidden('address');
        $hiddenDomain = new Fields\Hidden('domain');

        $message = (new Fields\Textarea('message'))->notEmpty();
        $cc      = (new Fields\Text('cc'))->addValidator(new Validator\Email());

        $this->addSection($address)
            ->addField($hiddenAddress)
            ->addField($hiddenDomain)
            ->addField($message)
            ->addField($cc)
            ->loadDataToForm();
    }
}

