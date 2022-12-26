<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Subdomains\Forms;

use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Forms\BaseForm;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Forms\FormConstants;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Subdomains\Providers;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Forms\Sections\InputGroup;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Forms\Fields\InputGroupElements;
use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Customs\Fields\InputGroupWithAttr;

class Add extends BaseForm implements ClientArea
{
    protected $id    = 'addForm';
    protected $name  = 'addForm';
    protected $title = 'addForm';

    public function initContent()
    {
        $this->setFormType(FormConstants::CREATE)
                ->setProvider(new Providers\SubdomainsCreate());

        $name = (new InputGroup('nameGroup'))
                ->addInputComponent((new InputGroupElements\Text('name'))->notEmpty())
                ->addInputAddon('dot', false, '.')
                ->addInputComponent(new InputGroupElements\Select('domains'))
                ->setDescription('');

        $this->addSection($name)
            ->loadDataToForm();
    }
}
