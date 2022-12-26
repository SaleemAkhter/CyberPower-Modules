<?php

namespace ModulesGarden\DirectAdminExtended\App\UI\Admin\Features\Forms;

use ModulesGarden\DirectAdminExtended\Core\UI\Widget\Forms\BaseForm;
use ModulesGarden\DirectAdminExtended\Core\UI\Interfaces\AdminArea;
use ModulesGarden\DirectAdminExtended\Core\UI\Widget\Forms\FormConstants;
use ModulesGarden\DirectAdminExtended\Core\UI\Widget\Forms\Fields;
use ModulesGarden\DirectAdminExtended\App\UI\Admin\Features\Providers;

class CreateBackup extends BaseForm implements AdminArea
{
    protected $id    = 'createBackup';
    protected $name  = 'createBackup';
    protected $title = 'createBackup';

    public function initContent()
    {
        $this->setFormType(FormConstants::CREATE)
                ->setProvider(new Providers\Backup())
                ->loadFields()
                ->loadDataToForm();
    }

    protected function loadFields()
    {
        $server         = (new Fields\Select('server_id'))->notEmpty();
        $name           = (new Fields\Text('name'))->notEmpty();
        $path           = (new Fields\Text('path'))->notEmpty();
        $adminAccess    = new Fields\Switcher('admin_access');
        $enableRestore  = new Fields\Switcher('enable_restore');

        $this->addField($server)
            ->addField($name)
            ->addField($path)
            ->addField($adminAccess)
            ->addField($enableRestore);

        return $this;
    }
}
