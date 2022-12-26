<?php

namespace ModulesGarden\DirectAdminExtended\App\UI\Admin\Features\Forms;

use ModulesGarden\DirectAdminExtended\Core\UI\Interfaces\AdminArea;
use ModulesGarden\DirectAdminExtended\Core\UI\Widget\Forms\Fields\Hidden;
use ModulesGarden\DirectAdminExtended\Core\UI\Widget\Forms\FormConstants;
use ModulesGarden\DirectAdminExtended\App\UI\Admin\Features\Providers;

class EditEndPoint extends CreateEndPoint implements AdminArea
{
    protected $id    = 'editEndPoint';
    protected $name  = 'editEndPoint';
    protected $title = 'editEndPoint';

    public function initContent()
    {
        $this->setFormType(FormConstants::UPDATE)
                ->setProvider(new Providers\FtpEndPointUpdate())
                ->addField(new Hidden('id'))
                ->loadFields()
                ->loadDataToForm();

    }
}
