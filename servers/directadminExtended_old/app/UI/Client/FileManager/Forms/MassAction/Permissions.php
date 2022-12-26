<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\FileManager\Forms\MassAction;

use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Forms\BaseForm;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\FileManager\Providers;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Forms\Fields;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Forms\Sections\BoxSection;

class Permissions extends BaseForm implements ClientArea
{
    protected $id    = 'permissionsForm';
    protected $name  = 'permissionsForm';
    protected $title = 'permissionsForm';
    protected $types = [
        'Read',
        'Write',
        'Execute'
    ];

    public function getDefaultActions()
    {
        return ['permissionMany'];
    }

    public function initContent()
    {
        $this->setFormType('permissionMany')
            ->setProvider(new Providers\FileManager());

        $this->addSection($this->buildSection('owner'))
            ->addSection($this->buildSection('group'))
            ->addSection($this->buildSection('others'));
    }

    private function buildSwitchers($name)
    {
        $switchers = [];
        foreach ($this->types as $type)
        {
            $switchers[] = new Fields\Switcher($name . $type);
        }

        return $switchers;
    }

    private function buildSection($name)
    {
        $section = new BoxSection($name . 'Section');
        foreach ($this->buildSwitchers($name) as $switcher)
        {
            $section->addField($switcher);
        }

        return $section;
    }
}

