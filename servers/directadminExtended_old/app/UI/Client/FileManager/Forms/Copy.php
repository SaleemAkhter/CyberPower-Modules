<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\FileManager\Forms;

use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Customs\Sections\FormGroupSection;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Forms\BaseForm;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\FileManager\Providers;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Forms\Fields;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Forms\Sections;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Forms\Fields\InputGroupElements;
use ModulesGarden\Servers\DirectAdminExtended\Core\Http\Request;

class Copy extends BaseForm implements ClientArea
{
    protected $id    = 'copyForm';
    protected $name  = 'copyForm';
    protected $title = 'copyForm';

    public function getDefaultActions()
    {
        return ['copy'];
    }

    public function initContent()
    {
        $this->setFormType('copy')
            ->setProvider(new Providers\FileManager());

        $file    = new Fields\Hidden('file');
        $oldPath = new Fields\Hidden('path');

        $hiddenFieldsSection = new FormGroupSection('hiddenFields');
        $hiddenFieldsSection->addField($oldPath)
            ->addField($file);


        $currentPath = Request::build()->getSession('fileManagerPath');

        $pathValue = $currentPath ? '/' . $currentPath : '';
        $pathValue .= '/' . $file->getValue();

        $dirPath     = (new Sections\InputGroup('dirPathGroup'))
            ->addInputAddon('home',false, 'home')
            ->addInputComponent((new InputGroupElements\Text('newPath'))->setDefaultValue($pathValue))
            ->setDescription('description');;

        $overwrite        = new Fields\Switcher('overwrite');
        $overwriteSection = (new FormGroupSection('overwriteSection'))
            ->addField($overwrite);

        $this->addSection($dirPath)
            ->addSection($overwriteSection)
            ->addSection($hiddenFieldsSection)
            ->loadDataToForm();
    }

}

