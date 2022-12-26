<?php

namespace ModulesGarden\DirectAdminExtended\App\UI\Admin\Features\Forms;

use ModulesGarden\DirectAdminExtended\Core\UI\Interfaces\AdminArea;
use ModulesGarden\DirectAdminExtended\Core\UI\Widget\Forms\Fields\Hidden;
use ModulesGarden\DirectAdminExtended\Core\UI\Widget\Forms\FormConstants;
use ModulesGarden\DirectAdminExtended\App\UI\Admin\Features\Providers;

class EditBackup extends CreateBackup implements AdminArea
{
    protected $id    = 'editBackup';
    protected $name  = 'editBackup';
    protected $title = 'editBackup';

    public function initContent()
    {
        $this->setFormType(FormConstants::UPDATE)
                ->setProvider(new Providers\BackupUpdate())
                ->addField(new Hidden('id'))
                ->loadFields()
                ->loadDataToForm();

    }
}
