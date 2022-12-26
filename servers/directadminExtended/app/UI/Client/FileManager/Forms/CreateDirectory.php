<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\FileManager\Forms;

use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Forms\BaseForm;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\FileManager\Providers;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Forms\Fields;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Forms\FormConstants;

class CreateDirectory extends BaseForm implements ClientArea
{
    protected $id    = 'createForm';
    protected $name  = 'createForm';
    protected $title = 'createForm';

    public function initContent()
    {
        $this->setFormType(FormConstants::CREATE)
            ->setProvider(new Providers\FileManager());

        $name = new Fields\Text('name');

        $this->addField($name)
            ->loadDataToForm();
    }

}

