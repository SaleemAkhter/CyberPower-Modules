<?php

namespace ModulesGarden\DirectAdminExtended\App\UI\Admin\Features\Forms;

use ModulesGarden\DirectAdminExtended\Core\UI\Widget\Forms\BaseForm;
use ModulesGarden\DirectAdminExtended\Core\UI\Interfaces\AdminArea;
use ModulesGarden\DirectAdminExtended\Core\UI\Widget\Forms\FormConstants;
use ModulesGarden\DirectAdminExtended\Core\UI\Widget\Forms\Fields\Hidden;
use ModulesGarden\DirectAdminExtended\App\UI\Admin\Features\Providers;

class DeleteEndPoint extends BaseForm implements AdminArea
{
    protected $id    = 'deleteEndPoint';
    protected $name  = 'deleteEndPoint';
    protected $title = 'deleteEndPoint';

    public function initContent()
    {
        $this->setFormType(FormConstants::DELETE)
                ->setProvider(new Providers\FtpEndPoint())
                ->setConfirmMessage('deleteEndPoint');

        $id = new Hidden('id');

        $this->addField($id)
            ->loadDataToForm();
    }
}
