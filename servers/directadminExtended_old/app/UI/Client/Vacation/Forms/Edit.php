<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Vacation\Forms;

use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Forms\Fields\Hidden;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Forms\FormConstants;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Vacation\Providers;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Forms\Sections;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Forms\Fields\InputGroupElements;


class Edit extends Add implements ClientArea
{
    protected $id    = 'editForm';
    protected $name  = 'editForm';
    protected $title = 'editForm';

    public function initContent()
    {
        $this->setFormType(FormConstants::UPDATE)
                ->setProvider(new Providers\VacationEdit());

        $this->loadNameSection()
            ->loadMessageSection()
            ->loadTimeSection()
            ->loadDataToForm();
    }

    protected function loadNameSection()
    {

        $name = (new Sections\InputGroup('nameGroup'))
            ->addInputComponent((new InputGroupElements\Select('oldName'))->disableField())
            ->addInputAddon('dot', false, '@')
            ->addInputComponent((new InputGroupElements\Select('oldDomains'))->addHtmlAttribute('reload_change', "initReloadModal")->disableField())
            ->setDescription('')
            ->addField(new Hidden('name'))
            ->addField(new Hidden('domains'));


        $this->addSection($name);

        return $this;
    }
}
