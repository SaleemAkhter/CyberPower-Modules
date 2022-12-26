<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Databases\Forms;

use \ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Forms\BaseForm;
use \ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Forms\Fields\Hidden;
use \ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Forms\FormConstants;
use \ModulesGarden\Servers\DirectAdminExtended\Core\UI\Interfaces\ClientArea;

class DeleteDatabase extends BaseForm implements ClientArea
{
    protected $id    = 'deleteDatabaseForm';
    protected $name  = 'deleteDatabaseForm';
    protected $title = 'deleteDatabaseForm';

    public function initContent()
    {
        $this->setFormType(FormConstants::DELETE)
                ->setProvider(new \ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Databases\Providers\Databases())
                ->setConfirmMessage('confirmDatabaseDelete');

        $field = new Hidden('idHidden');

        $this->addField($field)
                ->loadDataToForm();
    }
}
