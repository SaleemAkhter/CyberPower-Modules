<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Applications\Forms;

use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Applications\Providers;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Forms\BaseForm;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Forms\Fields\Hidden;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Forms\Fields\Select;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Forms\Fields\Text;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Forms\FormConstants;

class StagingApp extends BaseForm implements ClientArea
{
    protected $id    = 'stagingAppForm';
    protected $name  = 'stagingAppForm';
    protected $title = 'stagingAppForm';

    public function initContent()
    {
        $this->setFormType(FormConstants::UPDATE)
            ->setProvider(new Providers\ApplicationsStaging());

        $this->addField((new Select())->initIds('domain'))
            ->addField((new Text())->initIds('directory')->setDescription('description'))
            ->addField((new Text())->initIds('softdb')->setDescription('description'))
            ->addField((new Hidden())->initIds('id'))
            ->loadDataToForm();
    }
}