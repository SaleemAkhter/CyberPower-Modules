<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\FileManager\Forms;

use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Customs\Sections\FormGroupSection;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Forms\BaseForm;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\FileManager\Providers;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Forms\Fields;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Forms\Sections\BoxSection;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Forms\Sections\InputGroup;

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
        return ['permissions'];
    }

    public function initContent()
    {
        $this->setFormType('permissions')
            ->setProvider(new Providers\FileManagerPermission());

        $path = new Fields\Hidden('path');
        $file = new Fields\Hidden('file');

        $hiddenFieldsSection = new FormGroupSection('hiddenFields');
        $hiddenFieldsSection->addField($path)
                 ->addField($file);
        $this->addSection($this->buildSection('owner'))
            ->addSection($this->buildSection('group'))
            ->addSection($this->buildSection('others'))
            ->addSection($hiddenFieldsSection)
            ->loadDataToForm();
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

