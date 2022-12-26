<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\DomainPointers\Forms;

use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Customs\Sections\FormGroupSection;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Helpers\AlertTypesConstants;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Forms\BaseForm;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Forms\Fields\Select;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Forms\FormConstants;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\DomainPointers\Providers;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Forms\Sections\InputGroup;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Forms\Fields\InputGroupElements;
use ModulesGarden\Servers\DirectAdminExtended\App\Helpers\Validator;

class Add extends BaseForm implements ClientArea
{
    protected $id    = 'addForm';
    protected $name  = 'addForm';
    protected $title = 'addForm';

    public function initContent()
    {
        $this->setFormType(FormConstants::CREATE)
                ->setProvider(new Providers\DomainPointersCreate());

        $name = (new InputGroup('nameGroup'))
                ->addInputComponent((new InputGroupElements\Text('name'))->addValidator(new Validator\DomainRegrex()))
                ->addInputAddon('arrow', false, '>')
                ->addInputComponent(new InputGroupElements\Select('domains'))
                ->setDescription('description');


        $typeSection = new FormGroupSection('typeSection');

        $type = new Select('type');
        $typeSection->addField($type);
        $this->addInternalAlert('addPointerAlertInfo', AlertTypesConstants::INFO, AlertTypesConstants::DEFAULT_SIZE)
            ->addSection($name)
            ->addSection($typeSection)
            ->loadDataToForm();
    }
}
