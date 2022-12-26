<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\FileManager\Forms;

use ModulesGarden\DirectAdminExtended\Core\UI\Widget\Forms\FormConstants;
use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\FileManager\Providers;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Forms\Fields\Text;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Forms\Fields\Password;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Forms\BaseForm;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Forms\Fields\Hidden;

class Protect extends BaseForm implements ClientArea
{
    protected $id    = 'protectForm';
    protected $name  = 'protectForm';
    protected $title = 'protectForm';

    public function getDefaultActions()
    {
        return ['protect'];
    }

    public function initContent()
    {
        $this->setFormType('protect')
            ->setProvider(new Providers\FileManager()) 
            ->addField((new Text('name'))->setPlaceholder('e.g. Private Area')->notEmpty())
            ->addField((new Text('user'))->notEmpty())
            ->addField((new Password('password'))->notEmpty()) 
            ->addField(new Hidden('path'))
            ->addField(new Hidden('file'))

            ->loadDataToForm();
    }
}
