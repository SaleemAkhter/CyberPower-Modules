<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\FileManager\Forms;

use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Forms\BaseForm;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\FileManager\Providers;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Forms\Fields;

class Rename extends BaseForm implements ClientArea
{
    protected $id    = 'permissionsForm';
    protected $name  = 'permissionsForm';
    protected $title = 'permissionsForm';

    public function getDefaultActions()
    {
        return ['rename'];
    }

    public function initContent()
    {
        $this->setFormType('rename')
            ->setProvider(new Providers\FileManager());

        $path    = new Fields\Hidden('path');
        $oldName = new Fields\Hidden('oldFile');
        $name    = new Fields\Text('file');

        $this->addField($path)
            ->addField($oldName)
            ->addField($name)
            ->loadDataToForm();
    }

}

