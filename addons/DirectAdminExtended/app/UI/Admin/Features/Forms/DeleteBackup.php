<?php

namespace ModulesGarden\DirectAdminExtended\App\UI\Admin\Features\Forms;

use ModulesGarden\DirectAdminExtended\Core\UI\Widget\Forms\BaseForm;
use ModulesGarden\DirectAdminExtended\Core\UI\Interfaces\AdminArea;
use ModulesGarden\DirectAdminExtended\Core\UI\Widget\Forms\FormConstants;
use ModulesGarden\DirectAdminExtended\Core\UI\Widget\Forms\Fields\Hidden;
use ModulesGarden\DirectAdminExtended\App\UI\Admin\Features\Providers;

class DeleteBackup extends BaseForm implements AdminArea
{
    protected $id    = 'deleteBackup';
    protected $name  = 'deleteBackup';
    protected $title = 'deleteBackup';

    public function initContent()
    {
        $this->setFormType(FormConstants::DELETE)
                ->setProvider(new Providers\Backup())
                ->setConfirmMessage('deleteBackupPath');

        $id = new Hidden('id');

        $this->addField($id)
            ->loadDataToForm();
    }
}
