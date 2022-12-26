<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\SiteRedirection\Forms;

use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Customs\Sections\FormGroupSection;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Forms\BaseForm;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Forms\Fields\Select;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Forms\Fields\Text;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Forms\FormConstants;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\SiteRedirection\Providers;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Forms\Sections\InputGroup;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Forms\Fields\InputGroupElements;
use ModulesGarden\Servers\DirectAdminExtended\App\Helpers\Validator;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Forms\Sections;

class Add extends BaseForm implements ClientArea
{
    protected $id    = 'addForm';
    protected $name  = 'addForm';
    protected $title = 'addForm';

    public function initContent()
    {
        $this->setFormType(FormConstants::CREATE)
                ->setProvider(new Providers\SiteRedirectionCreate());

        $this->loadFields()
            ->loadDataToForm();
    }

    public function loadFields()
    {
        $type = (new FormGroupSection('typeSection'))
            ->addField(new Select('type'));

        $from = (new InputGroup('nameGroup'))
            ->addInputComponent(new InputGroupElements\Select('domains'))
            ->addInputAddon('/', false, '/')
            ->addInputComponent((new InputGroupElements\Text('from')))
            ->setDescription('description');

        $destination = new InputGroupElements\Text('destination');
        $destination->addValidator(new Validator\Url());

        $destinationGroup = (new Sections\InputGroup('destinationGroup'))
            ->addInputComponent(new InputGroupElements\Select('protocol'))
            ->addInputComponent($destination)
            ->setDescription('urlDescription');

        $this->addSection($type)
            ->addSection($from)
            ->addSection($destinationGroup);

        return $this;
    }
}
